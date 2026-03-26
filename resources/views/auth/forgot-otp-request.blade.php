<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.auth_forgot_password') }} - TNI AU Admin</title>
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
            max-width: 400px;
            width: 100%;
        }
        .card-header-custom {
            background: linear-gradient(135deg, #003d82 0%, #0066cc 100%);
            color: white;
            border-radius: 14px 14px 0 0;
            padding: 20px 24px 14px;
        }
        .channel-btn {
            cursor: pointer;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 8px;
            transition: all 0.2s;
            background: white;
        }
        .channel-btn:hover, .channel-btn.selected {
            border-color: #0066cc;
            background: #e8f0fc;
        }
        input[name="channel"]:checked + .channel-btn {
            border-color: #0066cc;
            background: #e8f0fc;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header-custom">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold"><i class="fas fa-key me-2"></i> {{ __('messages.auth_forgot_password') }}</h5>
                    <small class="opacity-75">{{ __('messages.auth_reset_your_password') }}</small>
                </div>
                <a href="{{ route('login') }}" class="text-white opacity-75" title="{{ __('messages.admin_tutup') }}">
                    <i class="fas fa-times fa-lg"></i>
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

            <form method="POST" action="{{ route('password.otp.send') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold"><i class="fas fa-user me-1"></i> {{ __('messages.username') }}</label>
                    <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                           value="{{ old('username') }}" placeholder="{{ __('messages.username') }}" required autofocus>
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <input type="hidden" name="channel" value="email">
                    <div class="channel-btn selected">
                        <i class="fas fa-envelope text-primary me-2"></i>
                        <strong>{{ __('messages.auth_send_code_email') }}</strong>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 fw-semibold">
                    <i class="fas fa-paper-plane me-1"></i> {{ __('messages.auth_send_code') }}
                </button>

                <div class="text-center mt-3">
                    <a href="{{ route('login') }}" class="text-decoration-none text-secondary small">
                        <i class="fas fa-arrow-left me-1"></i> {{ __('messages.auth_back_to_login') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectChannel(el, val) {
            document.querySelectorAll('.channel-btn').forEach(b => b.classList.remove('selected'));
            el.classList.add('selected');
            document.querySelectorAll('input[name="channel"]').forEach(r => r.checked = (r.value === val));
        }
    </script>
</body>
</html>
