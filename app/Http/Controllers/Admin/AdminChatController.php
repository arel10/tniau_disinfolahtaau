<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminChatController extends Controller
{
    public function index()
    {
        // Apply lifecycle transitions in batches to avoid loading all sessions into memory (N+1 fix)
        ChatSession::whereIn('status', [ChatSession::STATUS_ACTIVE, ChatSession::STATUS_INACTIVE])
            ->chunkById(50, function ($sessions) {
                $sessions->each->applyLifecycleTransitions();
            });

        // Get sessions by status for different tabs
        $unreadIds = ChatSession::withMessages()->unread()->pluck('id');

        // Active/Inactive sessions (unread)
        $unreadSessions = ChatSession::with('latestMessage')
            ->withMessages()
            ->whereIn('status', [ChatSession::STATUS_ACTIVE, ChatSession::STATUS_INACTIVE])
            ->whereIn('id', $unreadIds)
            ->latest('last_activity_at')
            ->paginate(20, ['*'], 'unread_page');

        // Sessions considered "read" include ACTIVE, INACTIVE, EXPIRED (closed moved to 'ended')
        $readSessions = ChatSession::with('latestMessage')
            ->withMessages()
            ->whereIn('status', [
                ChatSession::STATUS_ACTIVE,
                ChatSession::STATUS_INACTIVE,
                ChatSession::STATUS_EXPIRED,
            ])
            ->whereNotIn('id', $unreadIds)
            ->latest('last_activity_at')
            ->paginate(20, ['*'], 'read_page');

        // Sessions explicitly ended by visitor (Diakhiri)
        $endedSessions = ChatSession::with('latestMessage')
            ->withMessages()
            ->where('status', ChatSession::STATUS_CLOSED)
            ->latest('ended_at')
            ->paginate(20, ['*'], 'ended_page');

        return view('admin.live-chat.index', compact(
            'unreadSessions',
            'readSessions',
            'endedSessions'
        ));
    }

    public function show(ChatSession $chatSession)
    {
        $chatSession->applyLifecycleTransitions();
        $chatSession->load(['messages.admin']);
        $chatSession->markAsReadByAdmin();

        return view('admin.live-chat.show', [
            'session' => $chatSession,
            'canReply' => $chatSession->canAdminReply(),
        ]);
    }

    public function destroySelected(Request $request)
    {
        $validated = $request->validate([
            'chat_session_ids' => 'required|array|min:1',
            'chat_session_ids.*' => 'integer|exists:chat_sessions,id',
        ]);

        $deleted = ChatSession::whereIn('id', $validated['chat_session_ids'])->delete();

        return redirect()
            ->route('admin.live-chat.index')
            ->with('success', $deleted . ' chat berhasil dihapus.');
    }

    public function sendReply(Request $request, ChatSession $chatSession)
    {
        $chatSession->applyLifecycleTransitions();

        if (!$chatSession->canAdminReply()) {
            return response()->json([
                'message' => 'Sesi chat sudah selesai atau kedaluwarsa.',
            ], 422);
        }

        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $message = trim($validated['message']);
        if ($message === '') {
            return response()->json(['message' => 'Message is required.'], 422);
        }

        $created = $chatSession->messages()->create([
            'sender_type' => 'admin',
            'admin_id' => Auth::id(),
            'message' => $message,
            'is_read' => false,
        ]);

        // Record admin reply (this unhides chat for visitor)
        $chatSession->recordAdminReply();

        // Refresh to get updated attributes
        $chatSession->refresh();

        return response()->json([
            'success' => true,
            'message' => $this->messagePayload($created),
            'status' => $chatSession->status,
            'hidden_for_visitor' => $chatSession->hidden_for_visitor,
            'is_visible' => $chatSession->isVisibleToVisitor(),
        ]);
    }

    public function poll(Request $request, ChatSession $chatSession)
    {
        $validated = $request->validate([
            'after_id' => 'nullable|integer|min:0',
        ]);

        $chatSession->applyLifecycleTransitions();

        $afterId = $validated['after_id'] ?? 0;

        $messages = $chatSession->messages()
            ->with('admin')
            ->where('id', '>', $afterId)
            ->get()
            ->map(fn($msg) => $this->messagePayload($msg))
            ->all();

        $chatSession->markAsReadByAdmin();

        return response()->json([
            'messages' => $messages,
            'status' => $chatSession->status,
            'status_label' => $chatSession->status_label,
            'can_reply' => $chatSession->canAdminReply(),
        ]);
    }

    public function destroyMessage(ChatSession $chatSession, ChatMessage $chatMessage)
    {
        abort_unless($chatMessage->chat_session_id === $chatSession->id, 404);

        $deletedId = $chatMessage->id;
        $chatMessage->delete();

        $chatSession->refresh();

        return response()->json([
            'success' => true,
            'deleted_id' => $deletedId,
            'status' => $chatSession->status,
            'status_label' => $chatSession->status_label,
            'can_reply' => $chatSession->canAdminReply(),
            'remaining_messages' => $chatSession->messages()->count(),
        ]);
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
