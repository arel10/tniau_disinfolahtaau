<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.auth_2fa_verification') }} - TNI AU Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(135deg, #001f3f 0%, #003366 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .challenge-card { max-width: 420px; width: 100%; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.4); border-radius: 12px; background: rgba(255,255,255,0.97); }
        .code-input { font-size: 1.8rem; letter-spacing: 0.4em; text-align: center; font-family: 'Courier New', monospace; }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="challenge-card card mx-auto">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <i class="fas fa-lock fa-2x text-primary mb-2"></i>
                <h5 class="fw-bold">{{ __('messages.auth_2fa_verification') }}</h5>
                <p class="text-muted small mb-0">{{ __('messages.auth_2fa_enter_6digit') }}</p>
            </div>

            @if(session('error'))
                <div class="alert alert-danger small">{{ session('error') }}</div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning small">{{ session('warning') }}</div>
            @endif

            <form action="{{ route('two-factor.verify') }}" method="POST" id="otpForm">
                @csrf
                <div class="mb-3">
                    <input type="text" name="code" id="codeInput"
                           class="form-control code-input" 
                           placeholder="______" maxlength="9" 
                           autocomplete="one-time-code" autofocus required>
                    @error('code')
                        <div class="text-danger small mt-1 text-center">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-3">
                    <i class="fas fa-sign-in-alt me-1"></i> {{ __('messages.auth_verify_and_login') }}
                </button>
            </form>

            <hr>
            <div class="text-center">
                <button class="btn btn-link btn-sm text-muted" type="button" data-bs-toggle="collapse" data-bs-target="#recoverySection">
                    <i class="fas fa-key me-1"></i> {{ __('messages.auth_use_recovery_code') }}
                </button>
                <div class="collapse mt-2" id="recoverySection">
                    <form action="{{ route('two-factor.verify') }}" method="POST">
                        @csrf
                        <div class="input-group input-group-sm">
                            <input type="text" name="code" class="form-control" placeholder="XXXX-XXXX" style="font-family: monospace;">
                            <button type="submit" class="btn btn-outline-secondary">{{ __('messages.auth_use') }}</button>
                        </div>
                        <p class="text-muted mt-1" style="font-size: 0.75rem;">{{ __('messages.auth_recovery_one_time') }}</p>
                    </form>
                </div>
            </div>

            <div class="text-center mt-3">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link btn-sm text-danger">
                        <i class="fas fa-arrow-left me-1"></i> {{ __('messages.auth_back_to_login') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
