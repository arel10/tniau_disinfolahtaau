@extends('layouts.admin')
@section('page-title', 'Live Chat - ' . $session->display_name)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Live Chat - {{ $session->display_name }}</h4>
            <small class="text-muted">
                Pengunjung: {{ $session->display_name }} |
                Status: <strong id="sessionStatus">{{ $session->status_label }}</strong>
            </small>
        </div>
        <a href="{{ route('admin.live-chat.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
    </div>

    @if(!$canReply)
        <div class="alert alert-warning">Room ini read-only karena status {{ $session->status_label }}.</div>
    @endif

    <div class="card">
        <div class="card-body" id="chatMessages" style="height:450px; overflow-y:auto; background:#f8fafc;">
            @foreach($session->messages as $msg)
                <div class="mb-2 {{ $msg->sender_type === 'admin' ? 'text-end' : 'text-start' }}" data-message-id="{{ $msg->id }}">
                    <div class="d-inline-block p-2 rounded {{ $msg->sender_type === 'admin' ? 'bg-primary text-white' : 'bg-white border' }}" style="max-width:75%;">
                        <div>{{ $msg->message }}</div>
                        <div class="mt-1">
                            <small class="{{ $msg->sender_type === 'admin' ? 'text-white-50' : 'text-muted' }}">{{ $msg->sender_label }} - {{ $msg->created_at->format('H:i') }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
            @if($session->messages->isEmpty())
                <div class="text-muted" id="emptyMessagesNotice">Belum ada pesan pada room ini.</div>
            @endif
        </div>

        @if($canReply)
            <div class="card-footer bg-white">
                <div class="input-group">
                    <textarea class="form-control" id="replyMessage" rows="2" placeholder="Ketik balasan..."></textarea>
                    <button class="btn btn-primary" id="replyBtn" type="button">Kirim</button>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const sessionId = {{ $session->id }};
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const messagesEl = document.getElementById('chatMessages');
    const inputEl = document.getElementById('replyMessage');
    const buttonEl = document.getElementById('replyBtn');
    const statusEl = document.getElementById('sessionStatus');
    let canReply = {{ $canReply ? 'true' : 'false' }};
    let lastId = {{ $session->messages->last()?->id ?? 0 }};

    function statusLabel(status) {
        const labels = {
            active: 'Aktif',
            inactive: 'Tidak Aktif',
            expired: 'Kedaluwarsa',
            closed: 'Ditutup'
        };

        return labels[status] || status;
    }

    function scrollBottom() {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function appendMessage(msg) {
        const emptyNotice = document.getElementById('emptyMessagesNotice');
        if (emptyNotice) {
            emptyNotice.remove();
        }

        const wrap = document.createElement('div');
        wrap.className = 'mb-2 ' + (msg.sender_type === 'admin' ? 'text-end' : 'text-start');
        wrap.setAttribute('data-message-id', msg.id);
        wrap.innerHTML = '<div class="d-inline-block p-2 rounded ' + (msg.sender_type === 'admin' ? 'bg-primary text-white' : 'bg-white border') + '" style="max-width:75%;">'
            + '<div>' + msg.message + '</div>'
            + '<div class="mt-1">'
            + '<small class="' + (msg.sender_type === 'admin' ? 'text-white-50' : 'text-muted') + '">' + msg.sender_label + ' - ' + msg.time + '</small>'
            + '</div>'
            + '</div>';
        messagesEl.appendChild(wrap);
        scrollBottom();
    }

    function refreshReplyState(status, allowReply) {
        statusEl.textContent = statusLabel(String(status));
        canReply = !!allowReply;
        if (inputEl) {
            inputEl.disabled = !canReply;
        }
        if (buttonEl) {
            buttonEl.disabled = !canReply;
        }
    }

    // Per-message deletion removed from room view; bulk deletion remains available on index.

    function sendReply() {
        if (!canReply) {
            return;
        }

        if (!inputEl || !buttonEl) {
            return;
        }

        const text = inputEl.value.trim();
        if (!text) {
            return;
        }

        buttonEl.disabled = true;

        fetch('/admin/live-chat/' + sessionId + '/reply', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: text })
        })
        .then(async (r) => {
            const data = await r.json();
            if (!r.ok) {
                throw new Error(data.message || 'Tidak dapat kirim balasan');
            }
            return data;
        })
        .then((data) => {
            appendMessage(data.message);
            lastId = data.message.id;
            inputEl.value = '';
            refreshReplyState(data.status, data.status === 'active');
        })
        .catch((err) => {
            alert(err.message);
        })
        .finally(() => {
            buttonEl.disabled = !canReply;
        });
    }

    function poll() {
        fetch('/admin/live-chat/' + sessionId + '/poll?after_id=' + encodeURIComponent(lastId), {
            credentials: 'same-origin',
            headers: { 'Accept': 'application/json' }
        })
        .then((r) => r.json())
        .then((data) => {
            if (Array.isArray(data.messages) && data.messages.length) {
                data.messages.forEach((msg) => {
                    appendMessage(msg);
                    if (msg.id > lastId) {
                        lastId = msg.id;
                    }
                });
            }

            refreshReplyState(data.status, data.can_reply);
        })
        .catch(() => {});
    }

    if (buttonEl) {
        buttonEl.addEventListener('click', sendReply);
    }
    if (inputEl) {
        inputEl.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendReply();
            }
        });
    }
    // Click handler for per-message delete removed.

    scrollBottom();
    setInterval(poll, 3000);
})();
</script>
@endpush
