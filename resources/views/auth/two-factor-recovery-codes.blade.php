<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.auth_recovery_codes') }} - TNI AU Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #001f3f 0%, #003366 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .rc-card { max-width: 480px; width: 100%; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.4); border-radius: 12px; background: rgba(255,255,255,0.97); }
        .code-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; font-family: 'Courier New', monospace; font-size: 1rem; }
        .code-grid .code-item { background: #f8f9fa; padding: 8px 12px; border-radius: 6px; text-align: center; border: 1px solid #dee2e6; }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="rc-card card mx-auto">
        <div class="card-body p-4">
            <div class="text-center mb-3">
                <i class="fas fa-key fa-2x text-warning mb-2"></i>
                <h5 class="fw-bold">{{ __('messages.auth_recovery_codes') }}</h5>
                @if($firstSetup)
                    <div class="alert alert-success small">
                        <i class="fas fa-check-circle me-1"></i> {{ __('messages.auth_2fa_enabled') }}
                    </div>
                @endif
                <p class="text-muted small">{{ __('messages.auth_save_recovery_codes') }}</p>
            </div>

            <div class="alert alert-danger small mb-3">
                <i class="fas fa-exclamation-triangle me-1"></i>
                <strong>{{ __('messages.auth_important') }}</strong> {!! __('messages.auth_code_one_time_note') !!}
            </div>

            <div class="code-grid mb-3" id="codeGrid">
                @foreach($recoveryCodes as $code)
                    <div class="code-item">{{ $code }}</div>
                @endforeach
            </div>

            <div class="d-flex gap-2 mb-3">
                <button class="btn btn-outline-primary btn-sm flex-fill" onclick="copyRecoveryCodes()">
                    <i class="fas fa-copy me-1"></i> {{ __('messages.auth_copy_all') }}
                </button>
                <button class="btn btn-outline-secondary btn-sm flex-fill" onclick="downloadRecoveryCodes()">
                    <i class="fas fa-download me-1"></i> {{ __('messages.auth_download_txt') }}
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-right me-1"></i> {{ __('messages.auth_continue_dashboard') }}
                </a>
            </div>
        </div>
    </div>
</div>
<script>
    const codes = @json($recoveryCodes);
    function copyRecoveryCodes() {
        navigator.clipboard.writeText(codes.join('\n')).then(() => {
            alert('{{ __('messages.auth_codes_copied') }}');
        });
    }
    function downloadRecoveryCodes() {
        const text = "{{ __('messages.auth_recovery_2fa_header') }} - {{ config('app.name') }}\n" +
                     "{{ __('messages.auth_date_label') }} {{ now()->format('d M Y H:i') }}\n" +
                     "═══════════════════════════\n\n" +
                     codes.join('\n') +
                     "\n\n═══════════════════════════\n" +
                     "{{ __('messages.auth_save_safe') }}";
        const blob = new Blob([text], {type: 'text/plain'});
        const a = document.createElement('a');
        a.href = URL.createObjectURL(blob);
        a.download = '2fa-recovery-codes.txt';
        a.click();
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
