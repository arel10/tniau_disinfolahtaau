@extends('layouts.admin')
@section('page-title', 'Live Chat')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Live Chat</h4>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary btn-sm" id="toggleSelectionBtn">Pilih Chat</button>
            <form method="POST" action="{{ route('admin.live-chat.destroy-selected') }}" id="bulkDeleteForm" class="d-inline">
                @csrf
                @method('DELETE')
                <div id="bulkDeleteInputs"></div>
                <button type="submit" class="btn btn-danger btn-sm" id="bulkDeleteBtn" disabled>Hapus Terpilih</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <ul class="nav nav-tabs" id="chatTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="unread-tab" data-bs-toggle="tab" data-bs-target="#unread-pane" type="button" role="tab">
                Belum Dibaca ({{ $unreadSessions->total() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="read-tab" data-bs-toggle="tab" data-bs-target="#read-pane" type="button" role="tab">
                Sudah Dibaca ({{ $readSessions->total() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="ended-tab" data-bs-toggle="tab" data-bs-target="#ended-pane" type="button" role="tab">
                Diakhiri ({{ $endedSessions->total() }})
            </button>
        </li>
    </ul>

    <div class="tab-content border border-top-0 rounded-bottom p-3 bg-white">
        <div class="tab-pane fade show active" id="unread-pane" role="tabpanel">
            @forelse($unreadSessions as $session)
                <div class="border rounded p-3 mb-2 chat-select-card" data-session-id="{{ $session->id }}">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div class="form-check chat-select-box" style="display:none; padding-top: 4px;">
                            <input class="form-check-input chat-session-checkbox" type="checkbox" value="{{ $session->id }}" id="chat-session-{{ $session->id }}">
                        </div>
                        <a class="d-block text-decoration-none text-dark flex-grow-1" href="{{ route('admin.live-chat.show', $session) }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $session->display_name }}</strong>
                                    <span class="badge bg-warning text-dark ms-2">{{ $session->status_label }}</span>
                                    <div class="small mt-1">{{ \Illuminate\Support\Str::limit($session->latestMessage?->message, 100) }}</div>
                                </div>
                                <div class="text-end small">
                                    <div>{{ optional($session->last_activity_at)->diffForHumans() }}</div>
                                    <span class="badge bg-danger">Belum Dibaca</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-muted">Tidak ada room belum dibaca.</div>
            @endforelse

            <div class="mt-3">{{ $unreadSessions->links() }}</div>
        </div>

        <div class="tab-pane fade" id="read-pane" role="tabpanel">
            @forelse($readSessions as $session)
                <div class="border rounded p-3 mb-2 chat-select-card" data-session-id="{{ $session->id }}">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div class="form-check chat-select-box" style="display:none; padding-top: 4px;">
                            <input class="form-check-input chat-session-checkbox" type="checkbox" value="{{ $session->id }}" id="chat-session-read-{{ $session->id }}">
                        </div>
                        <a class="d-block text-decoration-none text-dark flex-grow-1" href="{{ route('admin.live-chat.show', $session) }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $session->display_name }}</strong>
                                    <span class="badge bg-secondary ms-2">{{ $session->status_label }}</span>
                                    <div class="small mt-1">{{ \Illuminate\Support\Str::limit($session->latestMessage?->message, 100) }}</div>
                                </div>
                                <div class="text-end small">
                                    <div>{{ optional($session->last_activity_at)->diffForHumans() }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-muted">Tidak ada room sudah dibaca.</div>
            @endforelse

            <div class="mt-3">{{ $readSessions->links() }}</div>
        </div>

        <div class="tab-pane fade" id="ended-pane" role="tabpanel">
            @forelse($endedSessions as $session)
                <div class="border rounded p-3 mb-2 chat-select-card" data-session-id="{{ $session->id }}">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div class="form-check chat-select-box" style="display:none; padding-top: 4px;">
                            <input class="form-check-input chat-session-checkbox" type="checkbox" value="{{ $session->id }}" id="chat-session-ended-{{ $session->id }}">
                        </div>
                        <a class="d-block text-decoration-none text-dark flex-grow-1 opacity-75" href="{{ route('admin.live-chat.show', $session) }}" style="cursor: pointer;">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>{{ $session->display_name }}</strong>
                                    <span class="badge bg-danger ms-2">{{ $session->status_label }}</span>
                                    <small class="d-block mt-1">Pengunjung menutup chat</small>
                                    <div class="small mt-1">{{ \Illuminate\Support\Str::limit($session->latestMessage?->message, 100) }}</div>
                                </div>
                                <div class="text-end small">
                                    <div>{{ optional($session->ended_at)->diffForHumans() }}</div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-muted">Tidak ada room yang diakhiri.</div>
            @endforelse

            <div class="mt-3">{{ $endedSessions->links() }}</div>
        </div>

        <!-- closed/expired merged into read pane -->
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const toggleSelectionBtn = document.getElementById('toggleSelectionBtn');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const bulkDeleteForm = document.getElementById('bulkDeleteForm');
    const bulkDeleteInputs = document.getElementById('bulkDeleteInputs');
    const selectionBoxes = Array.from(document.querySelectorAll('.chat-select-box'));
    const checkboxes = Array.from(document.querySelectorAll('.chat-session-checkbox'));
    let selectionMode = false;

    function syncSelectedInputs() {
        const selected = checkboxes.filter((checkbox) => checkbox.checked).map((checkbox) => checkbox.value);
        bulkDeleteInputs.innerHTML = '';

        selected.forEach((id) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'chat_session_ids[]';
            input.value = id;
            bulkDeleteInputs.appendChild(input);
        });

        bulkDeleteBtn.disabled = selected.length === 0;
    }

    toggleSelectionBtn.addEventListener('click', function () {
        selectionMode = !selectionMode;
        selectionBoxes.forEach((box) => {
            box.style.display = selectionMode ? 'block' : 'none';
        });

        if (!selectionMode) {
            checkboxes.forEach((checkbox) => {
                checkbox.checked = false;
            });
            bulkDeleteBtn.disabled = true;
            bulkDeleteInputs.innerHTML = '';
            toggleSelectionBtn.textContent = 'Pilih Chat';
            return;
        }

        toggleSelectionBtn.textContent = 'Batal Pilih';
    });

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', syncSelectedInputs);
    });

    bulkDeleteForm.addEventListener('submit', function (event) {
        if (bulkDeleteBtn.disabled) {
            event.preventDefault();
            return;
        }

        if (!confirm('Hapus chat yang dipilih?')) {
            event.preventDefault();
        }
    });
})();
</script>
@endpush
