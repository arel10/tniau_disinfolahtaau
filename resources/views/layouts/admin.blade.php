<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('messages.admin_dashboard')) - TNI AU Disinfolahtaau</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 250px;
            --primary-color: #003d82;
            --secondary-color: #0066cc;
        }
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            overflow-y: auto;
            z-index: 1000;
        }
        .sidebar .logo {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-left: 3px solid white;
        }
        /* Profil collapse submenu */
        #profilSubmenu .nav-link,
        #ziSubmenu .nav-link,
        #pelayananPublikSubmenu .nav-link,
        #customMenuSubmenu .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 6px 15px;
            font-size: 0.9rem;
            border-left: 2px solid transparent;
        }
        #profilSubmenu .nav-link:hover,
        #profilSubmenu .nav-link.active,
        #ziSubmenu .nav-link:hover,
        #ziSubmenu .nav-link.active,
        #pelayananPublikSubmenu .nav-link:hover,
        #pelayananPublikSubmenu .nav-link.active,
        #customMenuSubmenu .nav-link:hover,
        #customMenuSubmenu .nav-link.active {
            color: #fff;
            background-color: rgba(255,255,255,0.08);
            border-left: 2px solid #5dade2;
        }
        .sidebar [data-bs-toggle="collapse"] .fa-chevron-down {
            transition: transform 0.3s;
        }
        .sidebar [data-bs-toggle="collapse"][aria-expanded="true"] .fa-chevron-down {
            transform: rotate(180deg);
        }
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: 100vh;
        }
        .top-navbar {
            background-color: white;
            padding: 15px 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: -20px -20px 20px -20px;
        }
        .card {
            border: none;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .stat-card {
            border-left: 4px solid var(--secondary-color);
        }

        /* Modern Pagination */
        .pagination {
            gap: 4px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .pagination .page-item .page-link {
            border: none;
            border-radius: 8px !important;
            padding: 8px 14px;
            font-size: 0.875rem;
            font-weight: 600;
            color: #003d82;
            background: #f0f4f8;
            transition: all 0.2s ease;
            min-width: 38px;
            text-align: center;
            box-shadow: none;
        }
        .pagination .page-item .page-link:hover {
            background: linear-gradient(135deg, #001f3f, #0066cc);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,61,130,0.25);
        }
        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, #001f3f, #0066cc);
            color: #fff;
            box-shadow: 0 4px 12px rgba(0,61,130,0.3);
        }
        .pagination .page-item.disabled .page-link {
            background: #f0f4f8;
            color: #b0bec5;
            opacity: 0.6;
            cursor: not-allowed;
        }
        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            font-size: 0.8rem;
        }

        /* Pagination info text */
        .pagination-info {
            font-size: 0.85rem;
            color: #6c757d;
            font-weight: 500;
        }

        /* ===== MOBILE SIDEBAR TOGGLE ===== */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 10px;
            left: 8px;
            z-index: 1050;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 1.1rem;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            line-height: 1;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            z-index: 999;
        }
        .sidebar-overlay.show { display: block; }

        @media (max-width: 991.98px) {
            .sidebar-toggle { display: block; }
            .sidebar {
                width: min(86vw, 320px);
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar.show { transform: translateX(0); }
            .main-content {
                margin-left: 0 !important;
            }
            .top-navbar h5 { margin-left: 40px; font-size: 1.05rem; }
            .top-navbar .d-flex {
                flex-wrap: nowrap;
                gap: 10px;
                align-items: center !important;
            }

            .top-navbar h5 {
                flex: 1 1 auto;
                min-width: 0;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .top-navbar .dropdown {
                margin-left: auto;
                flex: 0 0 auto;
            }

            .main-content .container,
            .main-content .container-fluid {
                padding-left: 0;
                padding-right: 0;
            }

            .card-body {
                padding: 0.85rem;
            }

            .table-responsive {
                border-radius: 10px;
                -webkit-overflow-scrolling: touch;
            }

            .table-responsive table {
                min-width: 680px;
            }

            .main-content .d-flex.gap-2,
            .main-content .d-flex.gap-3 {
                flex-wrap: wrap;
            }
        }
        @media (max-width: 575.98px) {
            .main-content { padding: 12px; }
            .top-navbar { padding: 10px 15px; margin: -12px -12px 12px -12px; }
            .top-navbar h5 { font-size: 1rem; }
            .top-navbar .dropdown .btn {
                font-size: 0.84rem;
                padding: 6px 10px;
                width: auto;
            }

            .sidebar .logo img {
                height: 48px !important;
                margin-bottom: 8px !important;
            }
            .sidebar .logo h5 {
                font-size: 0.9rem;
            }
            .sidebar .nav-link {
                padding: 10px 14px;
                font-size: 0.88rem;
            }

            .card-header,
            .card-footer {
                padding: 0.7rem 0.85rem;
            }

            .form-control,
            .form-select,
            .form-label,
            textarea,
            input,
            select {
                font-size: 0.9rem;
            }

            .input-group > .btn {
                white-space: nowrap;
            }

            .btn-sm { padding: 3px 8px; font-size: 0.72rem; }
            .btn { padding: 4px 10px; font-size: 0.8rem; }
            .table-responsive .btn, .card .btn { padding: 3px 8px; font-size: 0.72rem; }

            .main-content .btn-group,
            .main-content .btn-toolbar {
                flex-wrap: wrap;
            }

            /* Keep visual content inside mobile frame on admin pages */
            .main-content {
                overflow-x: hidden;
            }
            .main-content img,
            .main-content svg,
            .main-content canvas,
            .main-content iframe,
            .main-content video {
                max-width: 100%;
                height: auto;
            }
            .main-content .org-chart-scroll,
            .main-content .diagram-scroll,
            .main-content .chart-scroll {
                max-width: 100%;
            }

            .modal-dialog {
                margin: 0.5rem;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Mobile sidebar toggle -->
    <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
        <i class="fas fa-bars"></i>
    </button>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="logo">
            <img src="{{ asset('assets/image/disinfolahta.png') }}" alt="Logo TNI AU" style="height: 60px; margin-bottom: 10px;">
            <h5 class="mb-0">DISINSINFOLAHTAAU</h5>
    
        </div>
        <nav class="nav flex-column mt-3">
            <!-- Dashboard -->
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> {{ __('messages.admin_dashboard') }}
            </a>
            <!-- Menu Kustom -->
            <a class="nav-link {{ request()->routeIs('admin.custom-menu.*') ? 'active' : '' }}" href="{{ route('admin.custom-menu.index') }}">
                <i class="fas fa-plus-square"></i> {{ __('messages.admin_tambah_menu') }}
            </a>
            @if(Auth::user()->isAdmin())
            <!-- Manajemen User (Admin Only) -->
            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <i class="fas fa-users-cog"></i> {{ __('messages.admin_user') }}
            </a>
            @endif
            <!-- Profil -->
            <a class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('admin.profil.*') || request()->routeIs('admin.struktur.*') ? 'active' : '' }}"
               data-bs-toggle="collapse" href="#profilSubmenu" role="button"
               aria-expanded="{{ request()->routeIs('admin.profil.*') || request()->routeIs('admin.struktur.*') ? 'true' : 'false' }}"
               aria-controls="profilSubmenu">
                <span><i class="fas fa-user-circle"></i> {{ __('messages.admin_profil') }}</span>
                <i class="fas fa-chevron-down small"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.profil.*') || request()->routeIs('admin.struktur.*') ? 'show' : '' }}" id="profilSubmenu">
                <nav class="nav flex-column ms-3">
                    <a class="nav-link py-2 {{ request()->routeIs('admin.profil.kata-pengantar') ? 'active' : '' }}" href="{{ route('admin.profil.kata-pengantar') }}">
                        <i class="fas fa-comment-dots fa-sm"></i> {{ __('messages.admin_kata_pengantar') }}
                    </a>
                    <a class="nav-link py-2 {{ request()->routeIs('admin.profil.sejarah') ? 'active' : '' }}" href="{{ route('admin.profil.sejarah') }}">
                        <i class="fas fa-clock fa-sm"></i> {{ __('messages.admin_sejarah') }}
                    </a>
                    <a class="nav-link py-2 {{ request()->routeIs('admin.struktur.*') ? 'active' : '' }}" href="{{ route('admin.struktur.index') }}">
                        <i class="fas fa-sitemap fa-sm"></i> {{ __('messages.admin_struktur_organisasi') }}
                    </a>
                    <a class="nav-link py-2 {{ request()->routeIs('admin.profil.tentang') ? 'active' : '' }}" href="{{ route('admin.profil.tentang') }}">
                        <i class="fas fa-info-circle fa-sm"></i> {{ __('messages.admin_tentang_kami') }}
                    </a>
                </nav>
            </div>
            <!-- Berita -->
            <a class="nav-link {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}" href="{{ route('admin.berita.index') }}">
                <i class="fas fa-newspaper"></i> {{ __('messages.admin_berita') }}
            </a>
            <!-- Zona Integritas -->
            <a class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('admin.zi.*') ? 'active' : '' }}"
               data-bs-toggle="collapse" href="#ziSubmenu" role="button"
               aria-expanded="{{ request()->routeIs('admin.zi.*') ? 'true' : 'false' }}"
               aria-controls="ziSubmenu">
                <span><i class="fas fa-shield-alt"></i> {{ __('messages.admin_zona_integritas') }}</span>
                <i class="fas fa-chevron-down small"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.zi.*') ? 'show' : '' }}" id="ziSubmenu">
                <nav class="nav flex-column ms-3">
                    <a class="nav-link py-2 {{ request()->routeIs('admin.zi.pages.*') && request()->get('type') === 'zona_integritas' ? 'active' : '' }}" href="{{ route('admin.zi.pages.index', ['type' => 'zona_integritas']) }}">
                        <i class="fas fa-file-alt fa-sm"></i> {{ __('messages.admin_zona_integritas') }}
                    </a>
                    <a class="nav-link py-2 {{ request()->routeIs('admin.zi.perancangan.*') ? 'active' : '' }}" href="{{ route('admin.zi.perancangan.index') }}">
                        <i class="fas fa-bullhorn fa-sm"></i> {{ __('messages.admin_perancangan') }}
                    </a>
                    <a class="nav-link py-2 {{ request()->routeIs('admin.zi.penetapan.*') ? 'active' : '' }}" href="{{ route('admin.zi.penetapan.index') }}">
                        <i class="fas fa-clipboard-check fa-sm"></i> {{ __('messages.admin_penetapan') }}
                    </a>
                    <a class="nav-link py-2 {{ request()->routeIs('admin.zi.pembangunan.*') ? 'active' : '' }}" href="{{ route('admin.zi.pembangunan.index') }}">
                        <i class="fas fa-hard-hat fa-sm"></i> {{ __('messages.admin_pembangunan') }}
                    </a>
                    <a class="nav-link py-2 {{ request()->routeIs('admin.zi.pemantauan.*') ? 'active' : '' }}" href="{{ route('admin.zi.pemantauan.index') }}">
                        <i class="fas fa-search fa-sm"></i> {{ __('messages.admin_pemantauan') }}
                    </a>
                </nav>
            </div>
            <!-- PIA -->
            <a class="nav-link {{ request()->routeIs('admin.pia.*') ? 'active' : '' }}" href="{{ route('admin.pia.index') }}">
                <i class="fas fa-users"></i> {{ __('messages.admin_pia') }}
            </a>
            <!-- e-Library -->
            <a class="nav-link {{ request()->routeIs('admin.e-library.*') ? 'active' : '' }}" href="{{ route('admin.e-library.index') }}">
                <i class="fas fa-book"></i> {{ __('messages.admin_e_library') }}
            </a>
            <!-- Galeri -->
            <a class="nav-link {{ request()->routeIs('admin.galeri.*') ? 'active' : '' }}" href="{{ route('admin.galeri.index') }}">
                <i class="fas fa-images"></i> {{ __('messages.admin_galeri') }}
            </a>
            <!-- Pelayanan Publik -->
            <a class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('admin.pelayanan-publik.*') ? 'active' : '' }}"
               data-bs-toggle="collapse" href="#pelayananPublikSubmenu" role="button"
               aria-expanded="{{ request()->routeIs('admin.pelayanan-publik.*') ? 'true' : 'false' }}"
               aria-controls="pelayananPublikSubmenu">
                <span><i class="fas fa-hand-holding-heart"></i> {{ __('messages.admin_pelayanan_publik') }}</span>
                <i class="fas fa-chevron-down small"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.pelayanan-publik.*') || request()->routeIs('admin.tutorial.*') ? 'show' : '' }}" id="pelayananPublikSubmenu">
                <nav class="nav flex-column ms-3">
                    <a class="nav-link py-2 {{ request()->routeIs('admin.pelayanan-publik.berita.*') ? 'active' : '' }}" href="{{ route('admin.pelayanan-publik.berita.index') }}">
                        <i class="fas fa-newspaper fa-sm"></i> {{ __('messages.admin_berita_pelayanan') }}
                    </a>
                    <a class="nav-link py-2 {{ request()->routeIs('admin.pelayanan-publik.standar.*') ? 'active' : '' }}" href="{{ route('admin.pelayanan-publik.standar.index') }}">
                        <i class="fas fa-list-alt fa-sm"></i> {{ __('messages.admin_standar_pelayanan') }}
                    </a>
                    <a class="nav-link py-2 {{ request()->routeIs('admin.pelayanan-publik.pengaduan.*') ? 'active' : '' }}" href="{{ route('admin.pelayanan-publik.pengaduan.index') }}">
                        <i class="fas fa-comments fa-sm"></i> {{ __('messages.admin_layanan_pengaduan') }}
                    </a>
                    <a class="nav-link py-2 {{ request()->routeIs('admin.pelayanan-publik.kompensasi.*') ? 'active' : '' }}" href="{{ route('admin.pelayanan-publik.kompensasi.index') }}">
                        <i class="fas fa-gift fa-sm"></i> {{ __('messages.admin_kompensasi_pelayanan') }}
                    </a>
                    <a class="nav-link py-2 {{ request()->routeIs('admin.pelayanan-publik.survei.*') ? 'active' : '' }}" href="{{ route('admin.pelayanan-publik.survei.index') }}">
                        <i class="fas fa-poll fa-sm"></i> {{ __('messages.admin_survei_kepuasan') }}
                    </a>
                    <a class="nav-link py-2 {{ request()->routeIs('admin.pelayanan-publik.hasil-survei.*') ? 'active' : '' }}" href="{{ route('admin.pelayanan-publik.hasil-survei.index') }}">
                        <i class="fas fa-chart-bar fa-sm"></i> {{ __('messages.admin_hasil_survei') }}
                    </a>
                    <a class="nav-link py-2 {{ request()->routeIs('admin.tutorial.*') ? 'active' : '' }}" href="{{ route('admin.tutorial.index') }}">
                        <i class="fas fa-chalkboard-teacher fa-sm"></i> {{ __('messages.admin_tutorial') }}
                    </a>
                </nav>
            </div>
            <!-- SP4N-lapor! -->
            <a class="nav-link {{ request()->routeIs('admin.sp4n-lapor.*') ? 'active' : '' }}" href="{{ route('admin.sp4n-lapor.index') }}">
                <i class="fas fa-bullhorn"></i> {{ __('messages.admin_sp4n_lapor') }}
            </a>
            <!-- Whistle Blowing -->
            <a class="nav-link {{ request()->routeIs('admin.whistle-blowing.*') ? 'active' : '' }}" href="{{ route('admin.whistle-blowing.index') }}">
                <i class="fas fa-bullhorn"></i> {{ __('messages.admin_whistle_blowing') }}
            </a>
            <!-- Kontak -->
            <a class="nav-link {{ request()->routeIs('admin.kontak.*') ? 'active' : '' }}" href="{{ route('admin.kontak.index') }}">
                <i class="fas fa-envelope"></i> {{ __('messages.admin_pesan_kontak') }}
            </a>
               <a class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('admin.events.*') ? 'active' : '' }}"
                  data-bs-toggle="collapse" href="#eventsSubmenu" role="button"
                  aria-expanded="{{ request()->routeIs('admin.events.*') ? 'true' : 'false' }}"
                  aria-controls="eventsSubmenu">
                   <span><i class="fas fa-calendar-alt"></i> {{ __('messages.admin_events') }}</span>
                   <i class="fas fa-chevron-down small"></i>
               </a>
               <div class="collapse {{ request()->routeIs('admin.events.*') ? 'show' : '' }}" id="eventsSubmenu">
                   <nav class="nav flex-column ms-3">
                       @php
                           $eventsSidebar = \App\Models\Event::orderBy('position')->get();
                           $routeEvent = request()->route('event');
                           $currentEventId = $routeEvent instanceof \App\Models\Event ? $routeEvent->id : $routeEvent;
                       @endphp
                       @foreach($eventsSidebar as $ev)
                           <a class="nav-link py-2 {{ (request()->routeIs('admin.events.show') || request()->routeIs('admin.events.edit')) && $currentEventId == $ev->id ? 'active' : '' }}" href="{{ route('admin.events.show', $ev) }}">
                               <i class="fas fa-calendar-day fa-sm me-2"></i> {{ Str::limit($ev->nama_kegiatan, 24) }}
                           </a>
                       @endforeach
                       {{-- Include CustomMenu items attached to the built-in Events section so admin-created pages (e.g., PTM AU) appear here --}}
                       @php
                           $customEventMenus = \App\Models\CustomMenu::forBuiltin('events')->ordered()->get();
                       @endphp
                       @foreach($customEventMenus as $cm)
                           <a class="nav-link py-2 {{ request()->routeIs('admin.custom-menu.edit') && request()->route('custom_menu') == $cm->id ? 'active' : '' }}" href="{{ route('admin.custom-menu.edit', $cm->id) }}">
                               <i class="fas fa-plus-square fa-sm me-2"></i> {{ Str::limit($cm->name, 24) }}
                           </a>
                       @endforeach
                   </nav>
               </div>
            <!-- Live Chat -->
            @php $chatUnread = \App\Models\ChatSession::unread()->count(); @endphp
            <a class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('admin.live-chat.*') ? 'active' : '' }}" href="{{ route('admin.live-chat.index') }}">
                <span><i class="fas fa-comments"></i> {{ __('messages.admin_live_chat') }}</span>
                @if($chatUnread > 0)
                <span class="badge bg-danger rounded-pill">{{ $chatUnread }}</span>
                @endif
            </a>
            <hr class="text-white">
            <a class="nav-link" href="{{ route('admin.preview-site') }}">
                <i class="fas fa-external-link-alt"></i> {{ __('messages.admin_lihat_website') }}
            </a>
            <a class="nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" href="#settingSubmenu" role="button" aria-expanded="{{ request()->routeIs('admin.setting.*') ? 'true' : 'false' }}" aria-controls="settingSubmenu">
                <span><i class="fas fa-cog"></i> {{ __('messages.admin_setting') }}</span>
                <i class="fas fa-chevron-down small"></i>
            </a>
            <div class="collapse {{ request()->routeIs('admin.setting.*') ? 'show' : '' }}" id="settingSubmenu">
                <nav class="nav flex-column ms-3">
                    <a class="nav-link py-2 {{ request()->routeIs('admin.setting.background') ? 'active' : '' }}" href="{{ route('admin.setting.background') }}"><i class="fas fa-image fa-sm me-2"></i> {{ __('messages.admin_background') }}</a>
                    <a class="nav-link py-2 {{ request()->routeIs('admin.setting.alamat') ? 'active' : '' }}" href="{{ route('admin.setting.alamat') }}"><i class="fas fa-map-marker-alt fa-sm me-2"></i> {{ __('messages.admin_alamat') }}</a>
                    <a class="nav-link py-2 {{ request()->routeIs('admin.setting.instansi-terkait') ? 'active' : '' }}" href="{{ route('admin.setting.instansi-terkait') }}"><i class="fas fa-building fa-sm me-2"></i> {{ __('messages.admin_instansi_terkait') }}</a>
                    <a class="nav-link py-2 {{ request()->routeIs('admin.setting.media-sosial') ? 'active' : '' }}" href="{{ route('admin.setting.media-sosial') }}"><i class="fab fa-facebook fa-sm me-2"></i> {{ __('messages.admin_media_sosial') }}</a>
                    <a class="nav-link py-2 {{ request()->routeIs('admin.setting.hubungi-kami') ? 'active' : '' }}" href="{{ route('admin.setting.hubungi-kami') }}"><i class="fas fa-phone fa-sm me-2"></i> {{ __('messages.admin_hubungi_kami') }}</a>
                    <a class="nav-link py-2 {{ request()->routeIs('admin.setting.menu-utama') ? 'active' : '' }}" href="{{ route('admin.setting.menu-utama') }}"><i class="fas fa-bars fa-sm me-2"></i> {{ __('messages.admin_menu_utama') }}</a>
                    <a class="nav-link py-2 {{ request()->routeIs('admin.setting.logo-footer') ? 'active' : '' }}" href="{{ route('admin.setting.logo-footer') }}"><i class="fas fa-shield-alt fa-sm me-2"></i> {{ __('messages.admin_logo_footer') }}</a>
                </nav>
            </div>
            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> {{ __('messages.admin_logout') }}
            </a>
        </nav>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <div class="top-navbar">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">@yield('page-title', __('messages.admin_dashboard'))</h5>
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle me-1"></i>
                        {{ Auth::user()->username ?: Auth::user()->name }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userDropdown">
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                                <i class="fas fa-id-card me-2 text-primary"></i> {{ __('messages.admin_profil') }}
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt me-2"></i> {{ __('messages.admin_logout') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Content -->
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Sidebar mobile toggle -->
    <script>
        (function() {
            var toggle = document.getElementById('sidebarToggle');
            var sidebar = document.querySelector('.sidebar');
            var overlay = document.getElementById('sidebarOverlay');
            if (!toggle || !sidebar || !overlay) return;

            function openSidebar() {
                sidebar.classList.add('show');
                overlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
            function closeSidebar() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            }

            toggle.addEventListener('click', function() {
                if (sidebar.classList.contains('show')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            });
            overlay.addEventListener('click', closeSidebar);

            // Close on ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeSidebar();
            });

            // Ensure state is reset when returning to desktop size
            window.addEventListener('resize', function() {
                if (window.innerWidth > 991.98) {
                    closeSidebar();
                }
            });

            // Close sidebar when clicking a nav link (mobile)
            sidebar.querySelectorAll('.nav-link:not([data-bs-toggle])').forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 991.98) closeSidebar();
                });
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
