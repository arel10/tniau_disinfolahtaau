<div id="liveChatWidget">
    <button id="chatWidgetToggle" title="Live Chat">
        <i class="fas fa-comments"></i>
        <span id="chatWidgetUnread" style="display:none;">0</span>
    </button>

    <div id="chatWidgetPanel" style="display:none;">
        <div class="chat-head">
            <strong>Live Chat</strong>
            <button id="chatWidgetClose" type="button">x</button>
        </div>

        <div id="chatWidgetState" class="chat-note">Silakan isi nama dan kirim pesan.</div>

        <div class="chat-start-row">
            <input type="text" id="chatVisitorName" placeholder="Nama (opsional)">
        </div>

        <div id="chatWidgetMessages"></div>

        <div class="chat-input-row">
            <textarea id="chatVisitorMessage" rows="1" placeholder="Ketik pesan..."></textarea>
            <button id="chatSendBtn" type="button">Kirim</button>
        </div>

        <div class="chat-actions-row">
            <button id="chatEndBtn" type="button">Akhiri Chat</button>
        </div>
    </div>
</div>

<style>
#liveChatWidget {
    position: fixed;
    right: 20px;
    bottom: 20px;
    z-index: 99999;
    font-family: Arial, sans-serif;
}
#chatWidgetToggle {
    width: 56px;
    height: 56px;
    border: 0;
    border-radius: 999px;
    color: #fff;
    background: #0b4a8f;
    cursor: pointer;
    position: relative;
}
#chatWidgetUnread {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #dc3545;
    color: #fff;
    border-radius: 999px;
    min-width: 20px;
    height: 20px;
    font-size: 11px;
    line-height: 20px;
    text-align: center;
}
#chatWidgetPanel {
    width: 340px;
    max-width: calc(100vw - 24px);
    margin-bottom: 10px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
    overflow: hidden;
}
#chatWidgetPanel .chat-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    color: #fff;
    background: #0b4a8f;
}
#chatWidgetClose {
    border: 0;
    color: #fff;
    background: transparent;
    font-size: 16px;
    cursor: pointer;
}
#chatWidgetState {
    padding: 8px 12px;
    border-bottom: 1px solid #ececec;
    font-size: 12px;
    color: #5d5d5d;
}
#chatWidgetPanel .chat-start-row,
#chatWidgetPanel .chat-input-row,
#chatWidgetPanel .chat-actions-row {
    padding: 8px 12px;
}
#chatVisitorName,
#chatVisitorMessage {
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 8px;
    font-size: 13px;
}
#chatWidgetMessages {
    background: #f7f8fb;
    min-height: 220px;
    max-height: 300px;
    overflow-y: auto;
    padding: 10px;
}
.chat-msg {
    margin-bottom: 8px;
    max-width: 85%;
}
.chat-msg .bubble {
    border-radius: 10px;
    padding: 8px 10px;
    font-size: 13px;
    line-height: 1.4;
    word-break: break-word;
}
.chat-msg .time {
    font-size: 10px;
    margin-top: 2px;
    color: #777;
}
.chat-msg.visitor {
    margin-left: auto;
    text-align: right;
}
.chat-msg.visitor .bubble {
    color: #fff;
    background: #0b4a8f;
}
.chat-msg.admin .bubble {
    background: #fff;
    border: 1px solid #ddd;
}
#chatSendBtn,
#chatEndBtn {
    border: 0;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    padding: 8px 10px;
}
#chatSendBtn {
    color: #fff;
    background: #0b4a8f;
}
#chatEndBtn {
    width: 100%;
    color: #b02a37;
    background: #fbeaec;
}
@media (max-width: 480px) {
    #liveChatWidget { right: 10px; bottom: 10px; }
}
</style>

<script>
(function () {
    const panel = document.getElementById('chatWidgetPanel');
    const toggle = document.getElementById('chatWidgetToggle');
    const closeBtn = document.getElementById('chatWidgetClose');
    const unreadEl = document.getElementById('chatWidgetUnread');
    const stateEl = document.getElementById('chatWidgetState');
    const messagesEl = document.getElementById('chatWidgetMessages');
    const visitorNameEl = document.getElementById('chatVisitorName');
    const visitorNameRowEl = visitorNameEl ? visitorNameEl.closest('.chat-start-row') : null;
    const messageEl = document.getElementById('chatVisitorMessage');
    const sendBtn = document.getElementById('chatSendBtn');
    const endBtn = document.getElementById('chatEndBtn');

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    let open = false;
    let sessionId = null;
    let lastId = 0;
    let pollTimer = null;
    let unread = 0;
    let lastSentPageState = null;
    
    // Idle and visibility tracking
    let lastActivityTime = Date.now();
    let isPageVisible = !document.hidden;
    let isWindowFocused = document.hasFocus();
    let visitorHiddenState = false;
    // Protect against auto-reset on user interaction
    let userJustClickedToggle = false;
    // How long to protect the UI after a user explicitly clicks the toggle (ms)
    const USER_CLICK_PROTECTION_MS = 2000; // 2 seconds (was 500ms)
    const IDLE_TIMEOUT_MS = 120000; // 2 minutes
    const BROWSER_CLOSE_RESET_MS = 2 * 60 * 1000; // 2 minutes
    const CHAT_LAST_CLOSED_AT_KEY = 'livechat_last_closed_at';

    function setUnread(count) {
        unread = Math.max(count, 0);
        if (unread > 0) {
            unreadEl.textContent = String(unread);
            unreadEl.style.display = 'inline-block';
        } else {
            unreadEl.style.display = 'none';
        }
    }

    function appendMessage(msg) {
        if (messagesEl.querySelector('[data-message-id="' + msg.id + '"]')) {
            return;
        }

        const row = document.createElement('div');
        row.className = 'chat-msg ' + msg.sender_type;
        row.setAttribute('data-message-id', msg.id);
        // Always compute local time from ISO timestamp so time is accurate on visitor device.
        let timeStr = '';
        try {
            if (msg.created_at) {
                const d = new Date(msg.created_at);
                timeStr = d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: false });
            }
        } catch (e) { /* ignore */ }
        if (!timeStr) {
            timeStr = msg.time || '';
        }
        row.innerHTML = '<div class="bubble">' + msg.message + '</div><div class="time">' + timeStr + '</div>';
        messagesEl.appendChild(row);
        messagesEl.scrollTop = messagesEl.scrollHeight;
        if (msg.id > lastId) {
            lastId = msg.id;
        }

        syncVisitorNameVisibility();
    }

    function syncVisitorNameVisibility() {
        if (!visitorNameRowEl) {
            return;
        }

        const hasMessages = !!messagesEl.querySelector('[data-message-id]');
        visitorNameRowEl.style.display = hasMessages ? 'none' : '';
    }

    function setReadOnlyMode(readonlyText) {
        hideInputControls();
        stateEl.textContent = readonlyText;
    }

    function enableInputMode(text) {
        showInputControls();
        stateEl.textContent = text;
    }

    function resetWidgetState() {
        if (!sessionId) {
            stateEl.textContent = 'Silakan isi nama dan kirim pesan.';
            showInputControls();
            endBtn.disabled = true;
            syncVisitorNameVisibility();
        }
    }

    function rememberBrowserClosedNow() {
        try {
            localStorage.setItem(CHAT_LAST_CLOSED_AT_KEY, String(Date.now()));
        } catch (e) {
            // ignore storage errors
        }
    }

    function clearRememberedBrowserClose() {
        try {
            localStorage.removeItem(CHAT_LAST_CLOSED_AT_KEY);
        } catch (e) {
            // ignore storage errors
        }
    }

    function getRememberedBrowserCloseAt() {
        try {
            const raw = localStorage.getItem(CHAT_LAST_CLOSED_AT_KEY);
            if (!raw) return null;
            const ts = Number(raw);
            return Number.isFinite(ts) ? ts : null;
        } catch (e) {
            return null;
        }
    }

    function maybeResetAfterLongBrowserClose() {
        const closedAt = getRememberedBrowserCloseAt();
        if (!closedAt) return;

        const elapsed = Date.now() - closedAt;
        if (elapsed >= BROWSER_CLOSE_RESET_MS) {
            // Reset only local widget UI; keep token so admin replies can reopen history.
            resetToInitialState();
        }

        clearRememberedBrowserClose();
    }

    /**
     * Reset widget to completely initial state: no session, no messages, closed panel, form visible.
     * Call this when: hidden_for_visitor detected, idle timeout triggered, tab closed, etc.
     * This ensures frontend UI immediately reflects hidden state without waiting for server.
     */
    function resetToInitialState() {
        try {
            // PROTECT: Don't reset panel when user just clicked toggle - give them time to interact
            if (userJustClickedToggle) {
                console.debug('[live-chat] resetToInitialState: SKIPPED due to userJustClickedToggle protection');
                return; // Exit early, don't reset anything
            }
            
            console.debug('[live-chat] resetToInitialState called');
            sessionId = null;
            lastId = 0;
            visitorHiddenState = false;
            open = false;
            if (panel) panel.style.display = 'none';
            if (messagesEl) messagesEl.innerHTML = '';
            if (visitorNameEl) visitorNameEl.value = '';
            if (messageEl) messageEl.value = '';
            resetWidgetState();
            setUnread(0);
            console.debug('[live-chat] resetToInitialState done: sessionId=' + sessionId + ', open=' + open + ', display=' + (panel?.style?.display));
        } catch (e) {
            console.error('[live-chat] resetToInitialState error:', e);
        }
    }

    function hideInputControls() {
        if (messageEl) messageEl.style.display = 'none';
        if (sendBtn) sendBtn.style.display = 'none';
        if (visitorNameRowEl) visitorNameRowEl.style.display = 'none';
        if (endBtn) endBtn.style.display = 'none';
    }

    function showInputControls() {
        if (messageEl) messageEl.style.display = '';
        if (sendBtn) sendBtn.style.display = '';
        if (endBtn) endBtn.style.display = '';
        syncVisitorNameVisibility();
    }

    function poll() {
        // Protect: don't auto-reset while user just clicked toggle and widget is open
        if (userJustClickedToggle && open) {
            console.debug('[live-chat] poll: protected from resetting during user click');
            // Still fetch, but don't apply resets
            if (!sessionId) {
                refreshStatus();
            }
            return;
        }

        // If we don't have a local sessionId, fetch the global status which will
        // reveal whether a session exists and whether it's hidden. This allows
        // the client to detect admin replies even when the widget hasn't been opened.
        if (!sessionId) {
            refreshStatus();
            return;
        }

        fetch('/livechat/poll?session_id=' + encodeURIComponent(sessionId) + '&after_id=' + encodeURIComponent(lastId), {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            // Safety: if sessionId was cleared (e.g., by resetToInitialState), bail out
            if (!sessionId) {
                console.debug('[live-chat] poll: sessionId cleared during fetch, ignoring response');
                return;
            }

            // Handle finished sessions right away
            if (data.status === 'closed' || data.status === 'expired' || data.ended) {
                console.debug('[live-chat] poll: session finished, hiding');
                setReadOnlyMode('Sesi chat sudah selesai.');
                resetToInitialState();
                return;
            }

            // Update visibility flag from server
            visitorHiddenState = data.hidden_for_visitor === true;

            // If server says hidden_for_visitor or not visible, ensure UI is reset/hidden
            if (data.hidden_for_visitor === true || data.is_visible === false) {
                console.debug('[live-chat] poll: hidden for visitor, resetting widget');
                if (sessionId) resetToInitialState();
                return;
            }

            // Otherwise, session is visible to visitor — render new messages only
            if (Array.isArray(data.messages) && data.messages.length > 0) {
                data.messages.forEach(msg => {
                    appendMessage(msg);
                });
            }

            // If server provided an explicit unread_count use it (covers admin-mark-as-read updates).
            if (typeof data.unread_count !== 'undefined') {
                setUnread(data.unread_count);
            } else {
                // Fallback: increment unread for incoming admin messages when widget closed
                if (Array.isArray(data.messages) && data.messages.length > 0) {
                    data.messages.forEach(msg => {
                        if (!open && msg.sender_type === 'admin') {
                            setUnread(unread + 1);
                        }
                    });
                }
            }

            // Enable input when visible and active
            if (data.status === 'active') {
                enableInputMode('Online');
                endBtn.disabled = false;
            } else if (data.status === 'inactive') {
                // If inactive but visible, still hide panel UI (visitor considered away)
                if (open) {
                    open = false;
                    panel.style.display = 'none';
                }
            }
        })
        .catch(() => {});
    }

    function startPolling() {
        if (pollTimer) return;
        poll();
        pollTimer = setInterval(poll, 4000);
    }

    function sendMessage() {
        const text = (messageEl.value || '').trim();
        if (!text) return;

        recordUserActivity();

        fetch('/livechat/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                message: text,
                visitor_name: visitorNameEl.value.trim() || null
            })
        })
        .then(async (r) => {
            const textBody = await r.text().catch(() => '');
            let data = {};
            try { data = JSON.parse(textBody || '{}'); } catch (e) { data = { raw: textBody }; }

            if (!r.ok) {
                const msg = data?.message || data?.raw || 'Gagal mengirim pesan.';
                alert(msg);
                throw new Error(msg);
            }

            if (data.message) {
                appendMessage(data.message);
            }
            sessionId = data.session_id;
            messageEl.value = '';
            visitorHiddenState = false;
            enableInputMode('Online');
            endBtn.disabled = false;
        })
        .catch((err) => {
            console.error('sendMessage error:', err);
        });
    }

    function endChat() {
        if (!sessionId) return;
        fetch('/livechat/end', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify({ session_id: sessionId })
        })
        .then(async (r) => {
            const data = await r.json().catch(() => ({}));
            if (!r.ok) {
                const msg = data?.message || 'Gagal mengakhiri chat.';
                alert(msg);
                throw new Error(msg);
            }

            // success
            sessionId = null;
            messagesEl.innerHTML = '';
            lastId = 0;
            visitorHiddenState = true;
            resetWidgetState();
            open = false;
            panel.style.display = 'none';
            setUnread(0);
        })
        .catch((err) => {
            console.error('endChat error:', err);
        });
    }

    toggle.addEventListener('click', function () {
        userJustClickedToggle = true;
        console.debug('[live-chat] toggle clicked, userJustClickedToggle=true');
        
        open = !open;
        panel.style.display = open ? 'block' : 'none';
        if (open) {
            recordUserActivity();
            setUnread(0);
            resetWidgetState();
            refreshStatus();
            startPolling();
        }
        
        // Protect this toggle state for a short window to prevent auto-reset from kicking in
        setTimeout(() => {
            userJustClickedToggle = false;
            console.debug('[live-chat] user click protection expired');
        }, USER_CLICK_PROTECTION_MS);
    });

    closeBtn.addEventListener('click', function () {
        open = false;
        panel.style.display = 'none';
    });

    sendBtn.addEventListener('click', sendMessage);
    messageEl.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
    endBtn.addEventListener('click', endChat);

    function refreshStatus() {
        // Protect: don't auto-reset while user just clicked toggle and widget is now open
        if (userJustClickedToggle && open) {
            console.debug('[live-chat] refreshStatus: skipped due to user click protection (open widget)');
            return;
        }

        // Include visitor name so server can resolve sessions by name when provided
        const nameParam = (visitorNameEl && visitorNameEl.value.trim()) ? ('?visitor_name=' + encodeURIComponent(visitorNameEl.value.trim())) : '';
        fetch('/livechat/status' + nameParam, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                if (data.has_session && data.session) {
                    const session = data.session;
                    
                    // If server marks session as hidden, visitor must start fresh.
                    let isSessionHidden = session.hidden_for_visitor === true || session.is_visible === false;
                    if (isSessionHidden) {
                        console.debug('[live-chat] refreshStatus: session hidden, resetting to initial');
                        resetToInitialState();
                        return;
                    }

                    if (!sessionId || sessionId !== session.id) {
                        messagesEl.innerHTML = '';
                        lastId = 0;
                        syncVisitorNameVisibility();
                    }

                    sessionId = session.id;

                    if (Array.isArray(session.messages) && session.messages.length) {
                        if (session.status === 'inactive') {
                            if (open) {
                                open = false;
                                panel.style.display = 'none';
                            }
                            return;
                        } else if (session.status === 'expired' || session.status === 'closed') {
                            sessionId = null;
                            messagesEl.innerHTML = '';
                            lastId = 0;
                            resetWidgetState();
                            endBtn.disabled = true;
                            if (open) {
                                open = false;
                                panel.style.display = 'none';
                            }
                        } else {
                            session.messages.forEach(appendMessage);
                            // Prefer server-provided unread_count when available, otherwise recalc
                            const unreadCount = (typeof session.unread_count !== 'undefined')
                                ? session.unread_count
                                : session.messages.filter(m => m.sender_type === 'admin' && !m.is_read).length;
                            setUnread(unreadCount);
                            endBtn.disabled = false;
                            recordUserActivity();
                        }
                    } else {
                        if (session.status === 'inactive') {
                            if (open) {
                                open = false;
                                panel.style.display = 'none';
                            }
                            return;
                        } else if (session.status === 'expired' || session.status === 'closed') {
                            sessionId = null;
                            messagesEl.innerHTML = '';
                            lastId = 0;
                            resetWidgetState();
                            endBtn.disabled = true;
                            if (open) {
                                open = false;
                                panel.style.display = 'none';
                            }
                        }
                    }
                } else {
                    messagesEl.innerHTML = '';
                    lastId = 0;
                    syncVisitorNameVisibility();
                    resetWidgetState();
                }
            })
            .catch(() => {});
    }

    // === Activity Recording Functions ===
    function recordPageState(state) {
        // Debounce identical consecutive states to avoid spamming endpoint
        if (lastSentPageState === state) return;
        lastSentPageState = state;

        const payload = { state: state };
        if (sessionId) payload.session_id = sessionId;
        // include visitor token from cookie so server can resolve session even when sessionId is missing
        function getCookie(name) {
            const v = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
            return v ? v.pop() : null;
        }
        const visitorToken = getCookie('visitor_chat_token');
        if (visitorToken) payload.visitor_token = visitorToken;

        console.debug('[live-chat] recordPageState ->', payload);

        fetch('/livechat/page-state', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify(payload)
        })
        .then(r => r.json())
        .then(data => {
            console.debug('[live-chat] page-state response', data);
            if (data.success) {
                visitorHiddenState = data.hidden_for_visitor;

                if (data.hidden_for_visitor) {
                    // Ensure chat UI fully hidden and messages cleared immediately
                    if (open) {
                        open = false;
                        panel.style.display = 'none';
                    }
                    if (messagesEl && messagesEl.innerHTML.trim() !== '') {
                        messagesEl.innerHTML = '';
                    }
                    // keep polling active to detect admin replies while visitor is away
                } else {
                    // visitor became active again — refresh status to load messages
                    refreshStatus();
                }
            }
        })
        .catch((err) => { console.error('[live-chat] page-state error', err); });
    }

    // Use sendBeacon for unload/quick-hidden states so server reliably records 'left' even when
    // the page is closed or navigated away. Falls back to fetch when sendBeacon unavailable.
    function sendPageStateBeacon(state) {
        const payload = { state: state };
        if (sessionId) payload.session_id = sessionId;
        function getCookie(name) {
            const v = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
            return v ? v.pop() : null;
        }
        const visitorToken = getCookie('visitor_chat_token');
        if (visitorToken) payload.visitor_token = visitorToken;

        try {
            if (navigator && navigator.sendBeacon) {
                const blob = new Blob([JSON.stringify(payload)], { type: 'application/json' });
                navigator.sendBeacon('/livechat/page-state', blob);
                console.debug('[live-chat] page-state beacon sent', payload);
                return;
            }
        } catch (e) {
            // ignore and fallback to fetch
        }

        // fallback
        fetch('/livechat/page-state', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify(payload)
        }).catch(() => {});
    }

    function recordUserActivity() {
        lastActivityTime = Date.now();
        // DO NOT clear protection flag here - let the setTimeout in toggle handler clear it
        // This prevents resetToInitialState() from immediately closing widget after user click
        // Ensure server knows visitor is active when widget is open or previously hidden
        if (visitorHiddenState === true || open === true) {
            recordPageState('active');
        }
    }

    function checkActivityState() {
        // Protect: don't auto-reset while user just clicked toggle
        if (userJustClickedToggle) {
            console.debug('[live-chat] checkActivityState: skipped due to user click protection');
            return;
        }

        const now = Date.now();
        const idleTime = now - lastActivityTime;
        let currentState = 'active';
        
        if (!isPageVisible) {
            currentState = 'hidden';
        } else if (!isWindowFocused) {
            currentState = 'hidden';
        } else if (idleTime > IDLE_TIMEOUT_MS) {
            currentState = 'idle';
        }
        
        // Notify server (async, fire-and-forget) without resetting UI immediately.
        recordPageState(currentState);
    }

    // Activity event listener
    function activityDetected() {
        recordUserActivity();
    }

    // Add listeners for user activity
    document.addEventListener('mousemove', activityDetected, true);
    document.addEventListener('mousedown', activityDetected, true);
    document.addEventListener('keydown', activityDetected, true);
    document.addEventListener('scroll', activityDetected, true);
    document.addEventListener('touchstart', activityDetected, true);
    document.addEventListener('touchmove', activityDetected, true);

    // Track page visibility changes
    document.addEventListener('visibilitychange', function () {
        isPageVisible = !document.hidden;
        if (isPageVisible) {
            console.debug('[live-chat] visibilitychange -> visible, refreshing');
            recordUserActivity();
            refreshStatus();
        } else {
            console.debug('[live-chat] visibilitychange -> hidden');
            sendPageStateBeacon('hidden');
        }
    });

    // Track window focus/blur
    window.addEventListener('focus', function () {
        isWindowFocused = true;
        recordUserActivity();
        refreshStatus();
    });

    window.addEventListener('blur', function () {
        isWindowFocused = false;
        console.debug('[live-chat] window blur');
        sendPageStateBeacon('hidden');
    });

    // Check activity state periodically every 5 seconds
    setInterval(checkActivityState, 5000);

    // Initial status refresh and start background polling so visibility and
    // admin replies are detected even when the widget is closed.
    console.debug('[live-chat] page load: initial refresh and polling');
    maybeResetAfterLongBrowserClose();
    recordUserActivity();
    refreshStatus();
    startPolling();

    // Ensure we notify server when the page is unloaded so visitor_left_page_at is recorded
    window.addEventListener('beforeunload', function () {
        rememberBrowserClosedNow();
        try { sendPageStateBeacon('left'); } catch (e) {}
    });

    // Some browsers fire pagehide more reliably than beforeunload.
    window.addEventListener('pagehide', function () {
        rememberBrowserClosedNow();
        try { sendPageStateBeacon('left'); } catch (e) {}
    });

    resetWidgetState();
    syncVisitorNameVisibility();
})();
</script>
