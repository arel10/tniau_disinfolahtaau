<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.auth_2fa_setup') }} - TNI AU Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #001f3f 0%, #003366 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .setup-card { max-width: 520px; width: 100%; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.4); border-radius: 12px; background: rgba(255,255,255,0.97); }
        .qr-box { background: #f8f9fa; border-radius: 8px; padding: 24px; text-align: center; }
        .qr-box svg { max-width: 200px; height: auto; }
        .secret-key { font-family: 'Courier New', monospace; font-size: 0.85rem; word-break: break-all; background: #e9ecef; padding: 8px 12px; border-radius: 6px; text-align: center; }
        .step-badge { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; background: #003366; color: #fff; border-radius: 50%; font-weight: 600; font-size: 0.85rem; }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="setup-card card mx-auto">
        <div class="card-body p-4">
            <div class="text-center mb-3">
                <i class="fas fa-shield-alt fa-2x text-primary mb-2"></i>
                <h5 class="fw-bold">{{ __('messages.auth_2fa_setup_title') }}</h5>
                <p class="text-muted small mb-0">{{ __('messages.auth_2fa_strengthen') }}</p>
            </div>

            @if(session('warning'))
                <div class="alert alert-warning small">{{ session('warning') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger small">{{ session('error') }}</div>
            @endif

            {{-- Step 1: Install app --}}
            <div class="mb-3">
                <p class="mb-1"><span class="step-badge me-2">1</span> <strong>{{ __('messages.auth_install_auth_app') }}</strong></p>
                <p class="text-muted small ms-4">{!! __('messages.auth_download_ga') !!}</p>
            </div>

            {{-- Step 2: Scan QR --}}
            <div class="mb-3">
                <p class="mb-1"><span class="step-badge me-2">2</span> <strong>{{ __('messages.auth_scan_qr') }}</strong></p>
                <div class="qr-box mt-2">
                    {!! $qrCodeSvg !!}
                </div>
                <p class="text-muted small mt-2 ms-4">{{ __('messages.auth_or_manual_code') }}</p>
                <div class="secret-key mt-1">{{ $secret }}</div>
            </div>

            {{-- Step 3: Verify --}}
            <div class="mb-3">
                <p class="mb-1"><span class="step-badge me-2">3</span> <strong>{{ __('messages.auth_enter_6digit_app') }}</strong></p>
                <form action="{{ route('two-factor.confirm-setup') }}" method="POST" class="mt-2">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="code" class="form-control form-control-lg text-center" 
                               placeholder="000000" maxlength="6" pattern="[0-9]{6}" inputmode="numeric"
                               autocomplete="one-time-code" autofocus required>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check me-1"></i> {{ __('messages.auth_verify') }}
                        </button>
                    </div>
                    @error('code')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
