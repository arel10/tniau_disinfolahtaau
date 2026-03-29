<?php

namespace App\Http\Controllers;

use App\Models\ChatSession;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class VisitorChatController extends Controller
{
    public function status(Request $request)
    {
        $token = $this->resolveVisitorToken($request, false);
        $name = $request->query('visitor_name');

        // Prefer resolving by provided visitor_name when present
        if (!empty($name)) {
            $session = ChatSession::where('visitor_name', $name)->latest('id')->first();
        } else {
            if (!$token) {
                return response()->json(['has_session' => false]);
            }
            $session = ChatSession::where('visitor_token', $token)->latest('id')->first();
        }
        if (!$session || !$session->messages()->exists()) {
            Log::debug('VisitorChatController@status: session not found or no messages', ['token' => $token, 'name' => $name, 'session_id' => $session?->id ?? null]);
            return response()->json(['has_session' => false]);
        }

        $session->applyLifecycleTransitions();
        $this->restoreVisibilityIfAdminReplied($session);

        // Hidden sessions should never be shown again in visitor widget.
        $isHidden = $session->hidden_for_visitor ?? false;
        if ($isHidden) {
            Log::debug('VisitorChatController@status: session hidden, rejecting', ['session_id' => $session->id]);
            return response()->json(['has_session' => false]);
        }

        return response()->json([
            'has_session' => !$session->isFinished(),
            'session' => !$session->isFinished() ? $this->sessionPayload($session, 0, true) : null,
        ]);
    }

    public function startOrResume(Request $request)
    {
        $validated = $request->validate([
            'visitor_name' => 'nullable|string|max:120',
        ]);

        $token = $this->resolveVisitorToken($request);

        // Prefer name-based session lookup when visitor provides a name
        $session = null;
        if (!empty($validated['visitor_name'])) {
            $session = ChatSession::where('visitor_name', $validated['visitor_name'])->latest('id')->first();
        }
        if (!$session) {
            $session = ChatSession::where('visitor_token', $token)->latest('id')->first();
        }
        if ($session) {
            $session->applyLifecycleTransitions();
            $this->restoreVisibilityIfAdminReplied($session);
        }

        // Hidden sessions should start fresh only when there is no new admin reply.
        if ($session && !$session->isFinished()) {
            $this->restoreVisibilityIfAdminReplied($session);
            $isHidden = $session->hidden_for_visitor ?? false;
            if ($isHidden) {
                Log::debug('VisitorChatController@startOrResume: rejecting hidden session', ['session_id' => $session->id]);
                $session = null;
            }
        }

        if (!$session || $session->isFinished() || !$session->messages()->exists()) {
            return response()
                ->json(['session' => null, 'has_session' => false])
                ->cookie('visitor_chat_token', $token, 60 * 24 * 30);
        }

        if (($validated['visitor_name'] ?? null) && empty($session->visitor_name)) {
            $session->update(['visitor_name' => $validated['visitor_name']]);
        }

        if ($session->status === ChatSession::STATUS_INACTIVE) {
            $session->touchActivity(ChatSession::STATUS_ACTIVE);
        }

        return response()
            ->json(['session' => $this->sessionPayload($session, 0, true)])
            ->cookie('visitor_chat_token', $token, 60 * 24 * 30);
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
            'visitor_name' => 'nullable|string|max:120',
        ]);

        $message = trim($validated['message']);
        if ($message === '') {
            return response()->json(['message' => 'Message is required.'], 422);
        }

        $token = $this->resolveVisitorToken($request);
        Log::debug('VisitorChatController@send: incoming', ['token' => $token, 'visitor_name' => $validated['visitor_name'] ?? null, 'message' => mb_substr($validated['message'] ?? '',0,120)]);

        // Prefer name-based session mapping: one name = one room
        $session = null;
        if (!empty($validated['visitor_name'])) {
            $session = ChatSession::where('visitor_name', $validated['visitor_name'])->latest('id')->first();
        }
        if (!$session) {
            $session = ChatSession::where('visitor_token', $token)->latest('id')->first();
        }
        if ($session) {
            $session->applyLifecycleTransitions();
        }

        // If there is an existing session but it's finished (closed/expired),
        // create a new session
        if ($session && $session->isFinished()) {
            $session = null; // force creation of a new session
        }

        if ($session && !$session->isFinished()) {
            // If existing session was hidden for visitor,
            // treat a new send as starting a new session: close the old one.
            try {
                if (($session->hidden_for_visitor ?? false)) {
                    $closeData = ['status' => ChatSession::STATUS_CLOSED];
                    if (Schema::hasColumn('chat_sessions', 'ended_at')) {
                        $closeData['ended_at'] = now();
                    }
                    if (Schema::hasColumn('chat_sessions', 'closed_reason')) {
                        $closeData['closed_reason'] = 'auto_closed_on_new_chat';
                    }
                    $session->forceFill($closeData)->save();
                    // mark as finished so we create a new session below
                    $session = null;
                }
            } catch (\Throwable $e) {
                // ignore schema errors and continue
            }
        }

        if (!$session) {
            // Create new session, only include columns that exist to avoid SQL errors
            $createData = [
                'visitor_token' => $token,
                'visitor_name' => $validated['visitor_name'] ?? null,
                'status' => ChatSession::STATUS_ACTIVE,
            ];

            if (Schema::hasColumn('chat_sessions', 'started_at')) {
                $createData['started_at'] = now();
            }
            if (Schema::hasColumn('chat_sessions', 'last_activity_at')) {
                $createData['last_activity_at'] = now();
            }
            if (Schema::hasColumn('chat_sessions', 'hidden_for_visitor')) {
                $createData['hidden_for_visitor'] = false;
            }

            $session = ChatSession::create($createData);
        } else {
            // Reuse existing active or inactive session
            if ($session->status === ChatSession::STATUS_INACTIVE) {
                // Visitor is resuming conversation - change back to ACTIVE
                $session->touchActivity(ChatSession::STATUS_ACTIVE);
            } else if (!$session->isActive()) {
                // Make sure status is ACTIVE
                $session->touchActivity(ChatSession::STATUS_ACTIVE);
            } else {
                // Record visitor activity and unhide if previously hidden
                $session->recordVisitorActivity();
            }
        }

        $created = $session->messages()->create([
            'sender_type' => 'visitor',
            'message' => $message,
            'is_read' => false,
        ]);

        if (empty($session->visitor_name) && !empty($validated['visitor_name'])) {
            $session->update(['visitor_name' => $validated['visitor_name']]);
        }

        return response()->json([
            'session_id' => $session->id,
            'message' => $this->messagePayload($created),
            'status' => $session->status,
            'hidden_for_visitor' => $session->hidden_for_visitor,
        ]);
    }

    public function poll(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required|integer',
            'after_id' => 'nullable|integer|min:0',
        ]);

        $token = $this->resolveVisitorToken($request);
        Log::debug('VisitorChatController@poll', ['token' => $token, 'session_id' => $validated['session_id'] ?? null, 'after_id' => $validated['after_id'] ?? 0]);

        // Try strict lookup by id + token, but fall back to id-only when needed
        $session = ChatSession::where('id', $validated['session_id'])
            ->where('visitor_token', $token)
            ->first();
        if (!$session) {
            $session = ChatSession::find($validated['session_id']);
            if (!$session) abort(404, 'Session not found');
        }

        $session->applyLifecycleTransitions();
        $this->restoreVisibilityIfAdminReplied($session);

        // Hidden sessions should not be rendered on visitor side.
        $isHidden = $session->hidden_for_visitor ?? false;
        if ($isHidden) {
            Log::debug('VisitorChatController@poll: session hidden, returning empty', ['session_id' => $session->id]);
            return response()->json([
                'id' => $session->id,
                'status' => $session->status,
                'status_label' => $session->status_label,
                'hidden_for_visitor' => true,
                'is_visible' => false,
                'admin_replied' => !empty($session->last_reply_by_admin_at),
                'messages' => [], // Empty messages force frontend to reset
                'unread_count' => 0,
                'last_activity_at' => optional($session->last_activity_at)?->toDateTimeString(),
                'last_seen_by_visitor_at' => optional($session->last_seen_by_visitor_at)?->toDateTimeString(),
            ]);
        }

        // NOTE: Do NOT call recordVisitorActivity() here. Polling alone is not user activity.
        // Only explicit user actions and recordPageState calls should update activity timestamp.
        // If we reset activity_at on every poll, idle timeout will never trigger.

        $afterId = $validated['after_id'] ?? 0;

        $messages = $session->messages()
            ->where('id', '>', $afterId)
            ->get()
            ->map(fn($msg) => $this->messagePayload($msg));

        $session->messages()
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $payload = $this->sessionPayload($session, $afterId, false, $messages->all());
        Log::debug('VisitorChatController@poll: response', ['session_id' => $session->id, 'payload_unread' => $payload['unread_count'] ?? null]);
        return response()->json($payload);
    }

    public function end(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'required|integer',
        ]);

        $token = $this->resolveVisitorToken($request);

        $session = ChatSession::where('id', $validated['session_id'])
            ->where('visitor_token', $token)
            ->firstOrFail();

        if (!$session->isFinished()) {
            // Build update data only for columns that exist to avoid SQL errors
            $data = ['status' => ChatSession::STATUS_CLOSED];
            if (Schema::hasColumn('chat_sessions', 'ended_at')) {
                $data['ended_at'] = now();
            }
            if (Schema::hasColumn('chat_sessions', 'last_activity_at')) {
                $data['last_activity_at'] = now();
            }
            if (Schema::hasColumn('chat_sessions', 'hidden_for_visitor')) {
                $data['hidden_for_visitor'] = true;
            }

            $session->forceFill($data)->save();
        }

        return response()->json(['success' => true]);
    }

    /**
     * Record that visitor left the page or became idle
     * This hides the chat from visitor's view but keeps it in database
     */
    public function recordVisitorPageState(Request $request)
    {
        $validated = $request->validate([
            'session_id' => 'nullable|integer',
            'state' => 'required|in:active,idle,hidden,left', // active: visitor on page, idle: > 2 min, hidden: tab hidden, left: page left
        ]);

        $token = $this->resolveVisitorToken($request, false);

        // Debug logging to help diagnose missing page-state calls
        try {
            Log::debug('VisitorChatController@recordVisitorPageState called', [
                'visitor_token' => $token,
                'payload' => $validated,
                'remote_addr' => $request->ip(),
            ]);
        } catch (\Throwable $e) {
            // ignore logging failures
        }

        // Prefer explicit session_id when provided. If token lookup fails, fall back to id-only lookup.
        $session = null;
        if (!empty($validated['session_id'])) {
            $session = ChatSession::where('id', $validated['session_id'])
                ->where('visitor_token', $token)
                ->first();
            if (!$session) {
                $session = ChatSession::find($validated['session_id']);
            }
        }

        if (!$session && $token) {
            $session = ChatSession::where('visitor_token', $token)->latest('id')->first();
        }

        if (!$session || $session->isFinished()) {
            try { Log::debug('recordVisitorPageState: session missing or finished', ['session_id' => $session?->id ?? null]); } catch (\Throwable $e) {}
            return response()->json(['success' => false, 'message' => 'Session not found or finished']);
        }

        $state = $validated['state'];

        if ($state === 'active') {
            // Visitor is actively on page - show chat
            try { Log::debug('recordVisitorPageState: setting active', ['session_id' => $session->id]); } catch (\Throwable $e) {}
            $session->recordVisitorActivity();
        } elseif (in_array($state, ['idle', 'hidden'])) {
            // Do not hide immediately for idle/hidden; just keep last active state untouched.
            try { Log::debug('recordVisitorPageState: idle/hidden received (no immediate hide)', ['state' => $state, 'session_id' => $session->id]); } catch (\Throwable $e) {}
        } elseif ($state === 'left') {
            // Mark visitor left page; lifecycle transition will hide after timeout.
            try { Log::debug('recordVisitorPageState: marking left', ['session_id' => $session->id]); } catch (\Throwable $e) {}
            $session->markVisitorLeft();
        }

        try { Log::debug('recordVisitorPageState: after update', ['session_id' => $session->id, 'hidden_for_visitor' => $session->hidden_for_visitor ?? null]); } catch (\Throwable $e) {}

        return response()->json([
            'success' => true,
            'hidden_for_visitor' => $session->hidden_for_visitor,
        ]);
    }

    private function resolveVisitorToken(Request $request, bool $createIfMissing = true): ?string
    {
        $token = $request->session()->get('visitor_chat_token')
            ?: $request->cookie('visitor_chat_token');

        if (!$token && $createIfMissing) {
            $token = (string) Str::uuid();
            $request->session()->put('visitor_chat_token', $token);
        }

        return $token;
    }

    /**
     * If visitor chat is hidden, make it visible again when admin has replied
     * after visitor last seen timestamp.
     */
    private function restoreVisibilityIfAdminReplied(ChatSession $session): void
    {
        if (($session->hidden_for_visitor ?? false) !== true) {
            return;
        }

        if ($session->isFinished() || empty($session->last_reply_by_admin_at)) {
            return;
        }

        $visitorSawLatestReply = !empty($session->last_seen_by_visitor_at)
            && $session->last_seen_by_visitor_at->greaterThanOrEqualTo($session->last_reply_by_admin_at);

        if ($visitorSawLatestReply) {
            return;
        }

        // Only restore when there is a NEW admin reply after chat became hidden.
        // This keeps history hidden when visitor returns after >2 minutes,
        // and re-opens it only after admin sends a fresh message.
        if (!empty($session->visitor_left_page_at)) {
            if (!$session->last_reply_by_admin_at->greaterThan($session->visitor_left_page_at)) {
                return;
            }
        }

        $session->showForVisitor();

        if ($session->status === ChatSession::STATUS_INACTIVE) {
            $session->touchActivity(ChatSession::STATUS_ACTIVE);
        } else {
            $session->refresh();
        }
    }

    /**
     * Clear visitor-side chat linkage without deleting DB records.
     * Used when browser was closed for a long time and widget should start fresh.
     */
    public function resetVisitorState(Request $request)
    {
        $request->session()->forget('visitor_chat_token');

        return response()->json(['success' => true])
            ->cookie('visitor_chat_token', '', -1, '/');
    }

    private function sessionPayload(ChatSession $session, int $afterId = 0, bool $includeAll = false, ?array $prefetchedMessages = null): array
    {
        $query = $session->messages();
        if (!$includeAll) {
            $query->where('id', '>', $afterId);
        }

        $messages = $prefetchedMessages ?? $query->get()->map(fn($msg) => $this->messagePayload($msg))->all();

        return [
            'id' => $session->id,
            'status' => $session->status,
            'status_label' => $session->status_label,
            'hidden_for_visitor' => $session->hidden_for_visitor ?? false,
            'is_visible' => $session->isVisibleToVisitor(),
            'admin_replied' => !empty($session->last_reply_by_admin_at),
            'closed_reason' => $session->closed_reason ?? null,
            'ended' => $session->isFinished(),
            'messages' => $messages,
            // Number of admin messages not yet read by the visitor
            'unread_count' => (int) $session->messages()->where('sender_type', 'admin')->where('is_read', false)->count(),
            'last_activity_at' => optional($session->last_activity_at)?->toDateTimeString(),
            'last_seen_by_visitor_at' => optional($session->last_seen_by_visitor_at)?->toDateTimeString(),
        ];
    }

    private function messagePayload($msg): array
    {
        return [
            'id' => $msg->id,
            'sender_type' => $msg->sender_type,
            'sender_label' => $msg->sender_label,
            'message' => e($msg->message),
            'is_read' => (bool) $msg->is_read,
            'time' => $msg->created_at->format('H:i'),
            'created_at' => $msg->created_at->toIso8601String(),
        ];
    }
}
