<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.auth_verify_otp') }} - TNI AU Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #001f3f 0%, #003366 100%);
        }
        .card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.35);
            max-width: 420px;
            width: 100%;
        }
        .card-header-custom {
            background: linear-gradient(135deg, #003d82 0%, #0066cc 100%);
            color: white;
            border-radius: 14px 14px 0 0;
            padding: 20px 24px 14px;
        }
        .otp-input {
            letter-spacing: 6px;
            font-size: 1.5rem;
            text-align: center;
            font-weight: 700;
        }
        .toggle-pwd { cursor: pointer; }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header-custom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="fas fa-shield-alt me-2"></i> {{ __('messages.auth_verify_otp') }}</h5>
                    <small class="opacity-75">{{ __('messages.auth_enter_otp_new_pass') }}</small>
                </div>
                <a href="{{ route('password.otp.request') }}" class="text-white opacity-75" title="{{ __('messages.kembali') }}">
                    <i class="fas fa-arrow-left fa-lg"></i>
                </a>
            </div>
        </div>
        <div class="card-body p-4">

            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show py-2">
                    <i class="fas fa-info-circle me-1"></i> {{ session('info') }}
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger py-2">
                    @foreach($errors->all() as $error)
                        <div><i class="fas fa-exclamation-circle me-1"></i>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.otp.reset') }}">
                @csrf
                <input type="hidden" name="username" value="{{ $username }}">

                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('messages.auth_otp_6digit') }}</label>
                    <input type="text" name="otp" maxlength="6" inputmode="numeric" pattern="\d{6}"
                           class="form-control otp-input @error('otp') is-invalid @enderror"
                           placeholder="______" value="{{ old('otp') }}" required autofocus>
                    @error('otp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">{{ __('messages.auth_otp_expiry_note') }}</small>
                </div>

                <hr>

                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('messages.password_baru') }}</label>
                    <div class="input-group">
                        <input type="password" id="pwd_new" name="password" minlength="8"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="{{ __('messages.auth_min_8_chars') }}" required>
                        <button type="button" class="btn btn-outline-secondary toggle-pwd" onclick="togglePwd('pwd_new')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold">{{ __('messages.auth_confirm_new_pass') }}</label>
                    <div class="input-group">
                        <input type="password" id="pwd_confirm" name="password_confirmation" minlength="8"
                               class="form-control" placeholder="{{ __('messages.auth_repeat_new_pass') }}" required>
                        <button type="button" class="btn btn-outline-secondary toggle-pwd" onclick="togglePwd('pwd_confirm')">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-success w-100 fw-semibold">
                    <i class="fas fa-check me-1"></i> {{ __('messages.auth_reset_password') }}
                </button>

                <div class="text-center mt-3">
                    <a href="{{ route('password.otp.request') }}" class="text-decoration-none text-secondary small">
                        <i class="fas fa-redo me-1"></i> {{ __('messages.auth_resend_otp') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePwd(id) {
            const el = document.getElementById(id);
            el.type = el.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>
