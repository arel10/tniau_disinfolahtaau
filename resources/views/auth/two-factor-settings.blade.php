@extends('admin.layouts.app')

@section('title', __('messages.auth_2fa_settings'))

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-shield-alt me-2 text-primary"></i>{{ __('messages.auth_2fa_name') }}</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if(session('warning'))
                        <div class="alert alert-warning">{{ session('warning') }}</div>
                    @endif

                    @if($enabled)
                        {{-- 2FA Enabled --}}
                        <div class="text-center mb-4">
                            <span class="badge bg-success fs-6 px-3 py-2">
                                <i class="fas fa-check-circle me-1"></i> {{ __('messages.auth_2fa_active') }}
                            </span>
                        </div>

                        <p class="text-muted">{{ __('messages.auth_2fa_active_desc') }} <strong>{{ $recoveryCount }}</strong></p>

                        <div class="d-flex flex-column gap-2 mt-3">
                            {{-- Regenerate recovery codes --}}
                            <form action="{{ route('two-factor.regenerate-recovery') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-warning w-100" onclick="return confirm('{{ __('messages.auth_2fa_replace_confirm') }}')">
                                    <i class="fas fa-sync-alt me-1"></i> {{ __('messages.auth_gen_new_recovery') }}
                                </button>
                            </form>

                            {{-- Disable 2FA --}}
                            @unless(Auth::user()->isAdmin())
                            <button class="btn btn-outline-danger w-100" data-bs-toggle="collapse" data-bs-target="#disableSection">
                                <i class="fas fa-times-circle me-1"></i> {{ __('messages.auth_disable_2fa') }}
                            </button>
                            <div class="collapse" id="disableSection">
                                <form action="{{ route('two-factor.disable') }}" method="POST" class="card card-body border-danger mt-2">
                                    @csrf
                                    @method('DELETE')
                                    <div class="mb-2">
                                        <label class="form-label small">{{ __('messages.password') }}</label>
                                        <input type="password" name="password" class="form-control form-control-sm" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label small">{{ __('messages.auth_current_otp') }}</label>
                                        <input type="text" name="code" class="form-control form-control-sm" maxlength="6" pattern="[0-9]{6}" required>
                                    </div>
                                    <button type="submit" class="btn btn-danger btn-sm w-100" onclick="return confirm('{{ __('messages.auth_confirm_disable_2fa') }}')">
                                        {{ __('messages.auth_confirm_disable') }}
                                    </button>
                                </form>
                            </div>
                            @else
                                <div class="alert alert-info small mb-0">
                                    <i class="fas fa-info-circle me-1"></i> {{ __('messages.auth_admin_cant_disable') }}
                                </div>
                            @endunless
                        </div>

                    @else
                        {{-- 2FA Not Enabled --}}
                        <div class="text-center mb-4">
                            <span class="badge bg-secondary fs-6 px-3 py-2">
                                <i class="fas fa-times-circle me-1"></i> {{ __('messages.auth_2fa_not_active') }}
                            </span>
                        </div>
                        <p class="text-muted">{{ __('messages.auth_enable_2fa_desc') }}</p>
                        <a href="{{ route('two-factor.setup') }}" class="btn btn-primary w-100">
                            <i class="fas fa-qrcode me-1"></i> {{ __('messages.auth_enable_2fa_now') }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
