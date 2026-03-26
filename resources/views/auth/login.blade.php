<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.auth_login') }} - TNI AU Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 0;
            overflow: hidden;
            position: relative;
        }
        .login-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -2;
        }
        .login-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0,31,63,0.4) 0%, rgba(0,51,102,0.35) 100%);
            z-index: -1;
        }
        .container {
            position: relative;
            z-index: 1;
            padding: 20px;
            max-height: 100vh;
            overflow-y: auto;
        }
        .login-card {
            max-width: 400px;
            width: 100%;
            margin: 0 auto;
        }
        .login-header {
            text-align: center;
            color: white;
            margin-bottom: 20px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.7);
        }
        .login-header h2 {
            font-size: 1.5rem;
            margin-bottom: 8px;
        }
        .login-header p {
            font-size: 0.95rem;
            margin-bottom: 0;
        }
        .login-header img {
            height: 70px;
            margin-bottom: 15px;
            filter: drop-shadow(0 2px 8px rgba(0,0,0,0.5));
        }
        .card {
            border: none;
            box-shadow: 0 10px 40px rgba(0,0,0,0.4);
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.95);
            border-radius: 12px;
        }
        /* Hide internal scrollbar visually on desktop/laptop but keep touch and wheel scrolling functional */
        @media (min-width: 992px) {
            .container {
                -ms-overflow-style: none; /* IE and Edge */
                scrollbar-width: none; /* Firefox */
            }
            .container::-webkit-scrollbar {
                display: none; /* Chrome, Safari, Opera */
                width: 0;
                height: 0;
            }

            /* Desktop / Laptop: slightly reduce login card and logo to avoid cropping */
            .login-card { max-width: 340px; }
            .login-header img { height: 60px !important; margin-bottom: 12px !important; }
            .login-header h2 { font-size: 1.25rem; }
            .login-header p { font-size: 0.9rem; }
        }
    </style>
</head>
<body>
    @php
        $loginBg     = setting('login_bg', 'assets/video/backround.mp4');
        $loginBgType = setting('login_bg_type', 'video');
        $loginBgRot  = (int) setting('login_bg_rotation', 0);
        $rotStyle    = $loginBgRot ? 'transform:rotate('.$loginBgRot.'deg)'.($loginBgRot==90||$loginBgRot==270 ? ' scale(1.45)' : '').';' : '';
    @endphp
    @if($loginBgType === 'video')
        <video class="login-video" autoplay muted loop playsinline style="{{ $rotStyle }}">
            <source src="{{ asset($loginBg) }}" type="video/mp4">
        </video>
    @else
        <img class="login-video" src="{{ asset($loginBg) }}" alt="Login Background" style="{{ $rotStyle }}">
    @endif
    <div class="login-overlay"></div>
    <div class="container">
        <div class="login-card mx-auto">
            <div class="login-header">
                <img src="{{ asset('assets/image/disinfolahta.png') }}" alt="Logo TNI AU" style="height: 80px; margin-bottom: 20px;">
                <h2>TNI AU - DISINFOLAHTAAU</h2>
                <p>{{ __('messages.auth_admin_panel') }}</p>
            </div>

            <div class="card">
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.submit') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.username') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required autofocus autocomplete="username">
                            </div>
                            @error('username')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('messages.password') }}</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">
                                {{ __('messages.auth_remember_me') }}
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-sign-in-alt"></i> {{ __('messages.auth_login') }}
                        </button>

                        <div class="text-center mt-3">
                            <a href="{{ route('password.otp.request') }}" class="text-decoration-none text-secondary small">
                                <i class="fas fa-question-circle"></i> {{ __('messages.auth_forgot_password') }}
                            </a>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <a href="{{ route('home') }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.auth_back_to_website') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
