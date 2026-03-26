@extends('layouts.admin')

@section('title', 'Administrator Profile')
@section('page-title', 'Administrator Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm">
            <div class="card-header text-center py-3" style="background: linear-gradient(135deg, #003d82 0%, #0066cc 100%);">
                <h5 class="text-white fw-bold mb-0">
                    <i class="fas fa-user-circle me-2"></i> Administrator Profile
                </h5>
            </div>
            <div class="card-body p-4">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show py-2">
                        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show py-2">
                        <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.profile.update') }}">
                    @csrf

                    {{-- Username --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('messages.username') }}</label>
                        <input type="text" name="username"
                               class="form-control @error('username') is-invalid @enderror"
                               value="{{ old('username', $user->username) }}" required>
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">{{ __('messages.email') }}</label>
                        <input type="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr class="my-3">

                    {{-- Ganti Password --}}
                    <div class="mb-2">
                        <label class="form-label fw-semibold">{{ __('messages.password_lama') }}</label>
                        <div class="input-group">
                            <input type="password" id="pwd_lama" name="password_lama"
                                   class="form-control @error('password_lama') is-invalid @enderror"
                                   placeholder="Kosongkan jika tidak ingin mengganti password">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePwd('pwd_lama')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password_lama')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-2">
                        <input type="password" id="pwd_baru" name="password_baru"
                               class="form-control @error('password_baru') is-invalid @enderror"
                               placeholder="Password Baru">
                        @error('password_baru')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input type="password" name="password_baru_confirmation"
                               class="form-control"
                               placeholder="Konfirmasi Password Baru">
                    </div>

                    <small class="text-muted d-block mb-4">
                        * Kosongkan Password Lama/Baru jika tidak ingin mengganti password.
                    </small>

                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function togglePwd(id) {
        const el = document.getElementById(id);
        el.type = el.type === 'password' ? 'text' : 'password';
    }
</script>
@endpush
