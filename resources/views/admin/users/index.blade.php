@extends('layouts.admin')

@section('title', __('messages.admin_manajemen_user'))
@section('page-title', __('messages.admin_manajemen_user'))

@push('styles')
<style>
    .setting-card { border:none; box-shadow:0 2px 12px rgba(0,61,130,0.10); border-radius:12px; }
    .setting-card .card-header { background:linear-gradient(135deg,#001f3f 0%,#003d82 100%); color:white; border-radius:12px 12px 0 0 !important; padding:16px 24px; }
    .setting-card .card-header h6 { color:white !important; }
    .badge-admin { background: linear-gradient(135deg, #dc3545, #c82333); }
    .badge-user { background: linear-gradient(135deg, #0d6efd, #0b5ed7); }

    @media (max-width: 575.98px) {
        .users-list-frame {
            padding: 0 !important;
        }

        .users-list-frame .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .users-list-frame .table-responsive table {
            min-width: 760px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4 gap-3">
        <div style="background:linear-gradient(135deg,#001f3f,#0066cc);border-radius:10px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <i class="fas fa-users-cog text-white fa-lg"></i>
        </div>
        <div class="flex-grow-1">
            <h4 class="mb-0 fw-bold text-dark">Manajemen User</h4>
            <small class="text-muted">Kelola user dan hak akses.</small>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tambah User
        </a>
    </div>

    <!-- Filter -->
    <div class="card setting-card mb-4">
        <div class="card-header">
            <h6 class="mb-0 fw-bold"><i class="fas fa-filter me-2"></i>Filter User</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-bold">Cari</label>
                        <input type="text" name="search" class="form-control" placeholder="Cari nama, username, email..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold">{{ __('messages.role') }}</label>
                        <select name="role" class="form-select">
                            <option value="">Semua Role</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i> Filter</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card setting-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold"><i class="fas fa-list me-2"></i>Daftar User</h6>
            <span class="badge bg-light text-dark">{{ $users->total() }} user</span>
        </div>
        <div class="card-body p-0 users-list-frame">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>{{ __('messages.nama') }}</th>
                            <th>{{ __('messages.username') }}</th>
                            <th>{{ __('messages.email') }}</th>
                            <th>{{ __('messages.role') }}</th>
                            <th>Dibuat</th>
                            <th style="width:150px;">{{ __('messages.aksi') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td data-label="#">{{ $users->firstItem() + $loop->index }}</td>
                            <td data-label="Nama">
                                <div class="fw-bold">{{ $user->name }}</div>
                                @if($user->phone)
                                <small class="text-muted"><i class="fas fa-phone fa-sm"></i> {{ $user->phone }}</small>
                                @endif
                            </td>
                            <td data-label="Username"><code>{{ $user->username }}</code></td>
                            <td data-label="Email">{{ $user->email }}</td>
                            <td data-label="Role">
                                @if($user->role === 'admin')
                                    <span class="badge badge-admin"><i class="fas fa-shield-alt me-1"></i> Admin</span>
                                @else
                                    <span class="badge badge-user"><i class="fas fa-user me-1"></i> User</span>
                                @endif
                            </td>
                            <td data-label="Dibuat"><small>{{ $user->created_at->format('d M Y') }}</small></td>
                            <td data-label="Aksi">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus user {{ $user->name }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="fas fa-users fa-2x text-muted mb-2 d-block"></i>
                                Belum ada user.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
        <div class="card-footer bg-white">
            <div class="d-flex justify-content-center">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
