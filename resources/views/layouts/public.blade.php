<!DOCTYPE html>
    <html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', __('messages.site_name'))</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        @if(session('locale') === 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
        @endif
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            :root {
                --primary-color: #003d82;
                --secondary-color: #0066cc;
                --bg-color: #ffffff;
                --text-color: #212529;
                --card-bg: #ffffff;
            }
            [data-theme="dark"] {
                --bg-color: #1a1a2e;
                --text-color: #e0e0e0;
                --card-bg: #16213e;
            }
            [data-theme="dark"] body,
            [data-theme="dark"] main {
                background-color: var(--bg-color);
                color: var(--text-color);
            }
            [data-theme="dark"] .card {
                background-color: var(--card-bg);
                color: var(--text-color);
                border-color: #2a2a4a;
            }
            [data-theme="dark"] .bg-light, [data-theme="dark"] .bg-white {
                background-color: var(--card-bg) !important;
                color: var(--text-color) !important;
            }
            [data-theme="dark"] .text-dark { color: var(--text-color) !important; }
            [data-theme="dark"] .text-muted { color: #aaa !important; }
            [data-theme="dark"] .text-body { color: var(--text-color) !important; }
            [data-theme="dark"] .border { border-color: #2a2a4a !important; }
            [data-theme="dark"] .table { color: var(--text-color); }
            [data-theme="dark"] .table-hover tbody tr:hover { background-color: rgba(255,255,255,0.03); }
            [data-theme="dark"] .form-control, [data-theme="dark"] .form-select { background-color: #1e293b; color: #e0e0e0; border-color: #2a2a4a; }
            [data-theme="dark"] .form-control:focus, [data-theme="dark"] .form-select:focus { border-color: #4da3ff; background-color: #1e293b; color: #e0e0e0; }
            [data-theme="dark"] .form-control::placeholder { color: #888; }
            [data-theme="dark"] .alert-light { background-color: #1e293b !important; color: #e0e0e0 !important; border-color: #2a2a4a !important; }
            [data-theme="dark"] .alert-info { background-color: #1e293b !important; color: #7db8ff !important; border-color: #2a2a4a !important; }
            [data-theme="dark"] h1, [data-theme="dark"] h2, [data-theme="dark"] h3, [data-theme="dark"] h4, [data-theme="dark"] h5, [data-theme="dark"] h6 { color: #e0e0e0; }
            [data-theme="dark"] p { color: #ccc; }
            [data-theme="dark"] a:not(.btn):not(.nav-link):not(.dropdown-item):not(.text-white):not(.badge) { color: #7db8ff; }
            [data-theme="dark"] a:not(.btn):not(.nav-link):not(.dropdown-item):not(.text-white):not(.badge):hover { color: #4da3ff; }
            [data-theme="dark"] .content-wrapper { background-color: var(--bg-color) !important; }
            [data-theme="dark"] .shared-sections-wrapper { background-color: var(--bg-color) !important; }
            [data-theme="dark"] html { background-color: #0f0f23 !important; }

            /* --- Global dark-mode: card-body, accordion, tabs, list-group, etc. --- */
            [data-theme="dark"] .card-body { background-color: var(--card-bg) !important; color: var(--text-color) !important; }
            [data-theme="dark"] .card-title { color: #e0e0e0 !important; }
            [data-theme="dark"] .card-text { color: #aaa !important; }
            [data-theme="dark"] .list-group-item { background-color: #1e293b; color: #e0e0e0; border-color: #2a2a4a; }
            [data-theme="dark"] .list-group-item:hover { background-color: #263548; }
            [data-theme="dark"] .list-group-item-action { color: #e0e0e0; }
            [data-theme="dark"] .accordion-item { background-color: #1e293b; border-color: #2a2a4a; }
            [data-theme="dark"] .accordion-button { background-color: #1e293b; color: #e0e0e0; }
            [data-theme="dark"] .accordion-button:not(.collapsed) { background-color: #263548; color: #7db8ff; }
            [data-theme="dark"] .accordion-button::after { filter: invert(1) brightness(2); }
            [data-theme="dark"] .accordion-body { background-color: #1e293b; color: #ccc; }
            [data-theme="dark"] .nav-tabs { border-bottom-color: #2a2a4a; }
            [data-theme="dark"] .nav-tabs .nav-link { color: #aaa; }
            [data-theme="dark"] .nav-tabs .nav-link.active { background-color: var(--card-bg); color: #7db8ff; border-color: #2a2a4a #2a2a4a var(--card-bg); }
            [data-theme="dark"] .tab-content { color: #ccc; }
            [data-theme="dark"] hr { border-color: #3a3a5a; }
            [data-theme="dark"] blockquote { color: #bbb; }
            [data-theme="dark"] .btn-outline-primary { color: #7db8ff; border-color: #4a6fa5; }
            [data-theme="dark"] .btn-outline-primary:hover { background-color: #1a3a6a; color: #fff; }
            [data-theme="dark"] .btn-outline-danger { color: #ff7b7b; border-color: #994444; }
            [data-theme="dark"] .border-top { border-color: #2a2a4a !important; }

            /* ===== FIXED HEADER WRAPPER ===== */
            .fixed-header {
                position: fixed;
                top: 0; left: 0; right: 0;
                z-index: 1040;
            }

            /* ===== TOP BAR (date, time, location) ===== */
            .top-bar {
                background: linear-gradient(135deg, #001f3f 0%, #003366 100%);
                color: white;
                font-size: 0.8rem;
                padding: 6px 0;
            }
            .top-bar a { color: rgba(255,255,255,0.85); text-decoration: none; }
            .top-bar a:hover { color: #ffc107; }
            .top-bar .clock-time {
                font-weight: 700;
                font-size: 0.8rem;
            }
            /* Ensure date + time stay on a single line on desktop */
            .top-bar .top-bar-datetime {
                display: flex;
                align-items: center;
                gap: 8px;
                white-space: nowrap;
            }
            @media (min-width: 992px) {
                .top-bar .top-bar-datetime { flex-direction: row; }
                .top-bar .top-bar-datetime #headerDate { font-weight: 600; }
                .top-bar .top-bar-datetime .clock-time { margin-left: 6px; }
            }
            /* Visitor badge */
            .visitor-badge {
                background: #ffc107;
                color: #1a1a2e;
                padding: 3px 12px;
                border-radius: 4px;
                font-weight: 700;
                font-size: 0.75rem;
                white-space: nowrap;
                display: inline-flex;
                align-items: center;
                gap: 5px;
            }
            .visitor-stats {
                font-size: 0.78rem;
                color: rgba(255,255,255,0.9);
                white-space: nowrap;
            }
            .visitor-stats .vs-num {
                color: #ffc107;
                font-weight: 700;
            }

            /* ===== BERITA TREN BAR ===== */
            .berita-tren-bar {
                background: linear-gradient(135deg, #001f3f 0%, #003366 100%);
                color: white;
                padding: 6px 0;
                font-size: 0.85rem;
                overflow: hidden;
            }
            .berita-tren-label {
                background: #e74c3c;
                padding: 3px 12px;
                border-radius: 4px;
                font-weight: 700;
                font-size: 0.78rem;
                white-space: nowrap;
                display: inline-flex;
                align-items: center;
                gap: 5px;
            }
            .berita-tren-ticker {
                overflow: hidden;
                white-space: nowrap;
                flex: 1;
            }
            .berita-tren-ticker .ticker-inner {
                display: inline-flex;
                white-space: nowrap;
                animation: tickerScroll 60s linear infinite;
            }
            .berita-tren-ticker .ticker-inner a {
                color: white;
                text-decoration: none;
                margin-right: 80px;
            }
            .berita-tren-ticker .ticker-inner a:hover { color: #ffc107; }
            @keyframes tickerScroll {
                0%   { transform: translateX(0); }
                100% { transform: translateX(-50%); }
            }

            /* ===== MAIN NAVBAR ===== */
            .main-navbar {
                background: linear-gradient(135deg, #001f3f 0%, #003366 100%);
                padding: 0;
                align-items: flex-start !important;
            }
            .main-navbar .navbar-nav {
                flex-wrap: nowrap;
            }
            .main-navbar .navbar-brand {
                padding-top: 2px;
                padding-bottom: 4px;
            }
            .main-navbar .navbar-nav .nav-link {
                color: rgba(255,255,255,0.9);
                font-weight: 500;
                padding: 10px 6px;
                font-size: 0.78rem;
                white-space: nowrap;
            }
            .main-navbar .navbar-nav .nav-link:hover,
            .main-navbar .navbar-nav .nav-link.active {
                color: #fff;
                background: rgba(255,255,255,0.1);
            }
            /* Home icon: no box highlight */
            .main-navbar .navbar-nav .nav-item:first-child .nav-link:hover,
            .main-navbar .navbar-nav .nav-item:first-child .nav-link.active {
                background: transparent;
            }
            .main-navbar .dropdown-menu {
                border: 1px solid rgba(0,0,0,0.08);
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                border-radius: 0;
                background: #fff;
                padding: 6px 0;
                margin-top: 0;
                min-width: 180px;
            }
            [data-theme="dark"] .main-navbar .dropdown-menu {
                background: #1e293b;
                border-color: #2a2a4a;
                box-shadow: 0 4px 20px rgba(0,0,0,0.4);
            }
            .main-navbar .dropdown-menu .dropdown-item {
                color: #333;
                font-size: 0.85rem;
                font-weight: 500;
                padding: 8px 18px;
                transition: background 0.2s;
            }
            [data-theme="dark"] .main-navbar .dropdown-menu .dropdown-item {
                color: #d0d0d0;
            }
            .main-navbar .dropdown-menu .dropdown-item:hover,
            .main-navbar .dropdown-menu .dropdown-item:focus {
                background: #f0f0f0;
                color: #000;
            }
            [data-theme="dark"] .main-navbar .dropdown-menu .dropdown-item:hover,
            [data-theme="dark"] .main-navbar .dropdown-menu .dropdown-item:focus {
                background: rgba(255,255,255,0.08);
                color: #fff;
            }
            .main-navbar .dropdown-menu .dropdown-item.active {
                background: #e8e8e8;
                color: #000;
            }
            [data-theme="dark"] .main-navbar .dropdown-menu .dropdown-item.active {
                background: rgba(255,255,255,0.12);
                color: #fff;
            }
            .main-navbar .dropdown-menu .dropdown-item img {
                border-radius: 2px;
            }
            .main-navbar .dropdown-menu .dropdown-divider {
                border-color: rgba(0,0,0,0.08);
                margin: 4px 0;
            }
            .main-navbar .navbar-brand {
                font-weight: bold;
                font-size: 1.1rem;
            }
            .main-navbar .nav-icon-btn {
                color: rgba(255,255,255,0.85);
                font-size: 1rem;
                padding: 8px;
                cursor: pointer;
                background: none;
                border: none;
            }
            .main-navbar .nav-icon-btn:hover {
                color: #fff;
            }
            /* Navbar collapse: desktop shows single horizontal row with tools on the right; mobile stacks vertically */
            .main-navbar .navbar-collapse {
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                width: 100%;
            }
            @media (max-width: 991.98px) {
                .main-navbar .navbar-collapse { flex-direction: column; align-items: stretch; }
            }
            /* Nav divider line between row 1 and row 2 */
            .nav-divider {
                border-top: 1px solid rgba(255,255,255,0.15);
                width: 100%;
            }
            /* Tools row (bottom-right: search, user, bell, theme toggle) */
            .nav-tools-row {
                display: flex;
                justify-content: flex-end;
                align-items: center;
                width: auto;
                padding: 0;
                gap: 6px;
                margin-left: 12px;
            }
            .nav-tools-row .nav-link,
            .nav-tools-row .nav-icon-btn {
                color: rgba(255,255,255,0.85);
                font-size: 0.88rem;
                padding: 4px 6px;
            }
            .nav-tools-row .nav-link:hover,
            .nav-tools-row .nav-icon-btn:hover { color: #fff; }
            /* Old row-2 wrap — no longer used, hidden */
            .nav-row-2-wrap { display: none !important; }
            /* Flag emoji in dropdown */
            .dropdown-item .flag-emoji {
                margin-right: 8px;
                font-size: 1.1rem;
            }
            /* Dark mode toggle */
            .theme-toggle {
                position: relative;
                width: 44px;
                height: 24px;
                background: rgba(255,255,255,0.2);
                border-radius: 12px;
                cursor: pointer;
                border: none;
                padding: 0;
                transition: background 0.3s;
            }
            .theme-toggle .toggle-circle {
                position: absolute;
                top: 2px;
                left: 2px;
                width: 20px;
                height: 20px;
                background: white;
                border-radius: 50%;
                transition: transform 0.3s;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.65rem;
                color: #333;
            }
            [data-theme="dark"] .theme-toggle {
                background: rgba(255,255,255,0.35);
            }
            [data-theme="dark"] .theme-toggle .toggle-circle {
                transform: translateX(20px);
            }

            /* Gradient overlay between navbar and hero */
            .navbar-hero-gradient { display: none; }

            /* Fixed video parallax wrapper needs no overflow:hidden */
            .hero-section {
                color: white;
                padding-bottom: 120px;
                min-height: 100vh;
                height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                overflow: hidden;
            }
            /* Full-bleed video contained within hero section */
            .hero-section .hero-video {
                position: absolute;
                top: 0; left: 0;
                width: 100%; height: 100%;
                object-fit: cover;
                z-index: 0;
            }
            /* Overlay aligned with hero section */
            .hero-section .hero-overlay {
                position: absolute;
                top: 0; left: 0; right: 0; bottom: 0;
                background: linear-gradient(
                    to bottom,
                    rgba(0,31,63,0.55) 0%,
                    transparent 12%,
                    transparent 88%,
                    rgba(0,0,0,0.35) 100%
                );
                z-index: 1;
                pointer-events: none;
            }
            .hero-section .container {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 2;
                width: 100vw;
                max-width: 100vw;
                text-align: center;
                pointer-events: auto;
            }
            .hero-section::after {
                content: '';
                position: absolute;
                bottom: 0; left: 0; right: 0;
                height: 60px;
                background: linear-gradient(to bottom, transparent, rgba(0,0,0,0.12));
                pointer-events: none;
                z-index: 2;
            }
            .page-hero {
                background: linear-gradient(rgba(0, 61, 130, 0.55), rgba(0, 61, 130, 0.75));
                color: white;
                padding-bottom: 24px;
                padding-top: 16px;
                min-height: 100px;
                display: flex;
                align-items: flex-end;
                justify-content: flex-start;
                text-align: left;
                position: relative;
                overflow: hidden;
                clip-path: inset(0);
            }
            .page-hero-video-bg {
                position: absolute;
                top: 0; left: 0;
                width: 100%; height: 100%;
                object-fit: cover;
            }
            .page-hero > *:not(.page-hero-video-bg):not(.page-hero-overlay) { position: relative; z-index: 2; }
            .page-hero-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(rgba(0,61,130,0.45), rgba(0,61,130,0.65));
                z-index: 1;
                pointer-events: none;
            }
            .page-hero h2 {
                font-weight: 700;
                font-size: 2rem;
                text-shadow: 0 2px 8px rgba(0,0,0,0.3);
                margin: 0;
            }
            .page-hero .breadcrumb {
                background: transparent;
                justify-content: flex-start;
                margin-bottom: 0;
                margin-top: 8px;
            }
            .page-hero .breadcrumb-item,
            .page-hero .breadcrumb-item a {
                color: rgba(255,255,255,0.85);
                font-size: 0.9rem;
            }
            .page-hero .breadcrumb-item.active {
                color: rgba(255,255,255,0.65);
            }
            .page-hero .breadcrumb-item + .breadcrumb-item::before {
                color: rgba(255,255,255,0.5);
            }
            .card {
                transition: transform 0.3s;
                height: 100%;
            }
            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
            }
            [data-theme="dark"] .card:hover {
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            }
            .footer {
                background-color: #113254;
                color: #ffffff;
                padding: 40px 0 20px;
                margin-top: 0;
                position: relative;
                z-index: 10;
                clear: both;
            }
            .badge-kategori {
                background-color: var(--secondary-color);
            }

            /* ===== GLOBAL: Prevent horizontal overflow on mobile ===== */
            html, body { overflow-x: hidden; max-width: 100vw; }
            html { background-color: #001f3f; }

            /* ===== TABLET (≤991px): lg breakpoint ===== */
            @media (max-width: 991.98px) {
                .top-bar { font-size: 0.7rem; padding: 4px 0; }
                .top-bar .visitor-stats { display: none; }

                /* Mobile dropdown: disable CSS hover, show only via .show class */
                .main-navbar .dropdown-menu {
                    position: static !important;
                    float: none !important;
                    width: 100% !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    border: none !important;
                    border-radius: 0 !important;
                    box-shadow: none !important;
                    background: rgba(0,20,50,0.6) !important;
                    display: none !important;
                    transform: none !important;
                    opacity: 1 !important;
                    pointer-events: auto !important;
                }
                .main-navbar .dropdown-menu.show {
                    display: block !important;
                }
                .main-navbar .dropdown-menu .dropdown-item {
                    padding: 12px 20px 12px 36px;
                    font-size: 0.88rem;
                    border-bottom: 1px solid rgba(255,255,255,0.06);
                    color: rgba(255,255,255,0.85);
                    white-space: normal;
                    word-break: break-word;
                }
                .main-navbar .dropdown-menu .dropdown-item:hover,
                .main-navbar .dropdown-menu .dropdown-item:active,
                .main-navbar .dropdown-menu .dropdown-item:focus {
                    background: rgba(255,255,255,0.12) !important;
                    color: #fff;
                }
                .main-navbar .dropdown-toggle::after {
                    float: right;
                    margin-top: 8px;
                    transition: transform 0.25s ease;
                }
                .main-navbar .dropdown.open > .dropdown-toggle::after {
                    transform: rotate(180deg);
                }
                .berita-tren-bar { font-size: 0.75rem; padding: 4px 0; }

                /* ===== MOBILE SIDE-DRAWER MENU ===== */
                .main-navbar .navbar-nav {
                    flex-wrap: wrap !important;
                    gap: 0 !important;
                    flex-direction: column !important;
                    width: 100% !important;
                }
                .main-navbar .navbar-nav .nav-item {
                    width: 100%;
                }
                .main-navbar .navbar-nav .nav-link {
                    padding: 13px 18px;
                    font-size: 0.92rem;
                    white-space: normal;
                    border-bottom: 1px solid rgba(255,255,255,0.07);
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                }
                /* Side-drawer overlay backdrop */
                .mobile-menu-backdrop {
                    display: none;
                    position: fixed;
                    inset: 0;
                    background: rgba(0,0,0,0.5);
                    z-index: 1039;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }
                .mobile-menu-backdrop.show {
                    display: block;
                    opacity: 1;
                }
                /* Disable Bootstrap collapse animation — we use our own slide */
                .main-navbar .navbar-collapse {
                    display: flex !important;
                    flex-direction: column !important;
                    position: fixed;
                    top: 0;
                    left: 0;
                    bottom: 0;
                    width: 280px;
                    max-width: 82vw;
                    z-index: 1050;
                    background: linear-gradient(180deg, #001f3f 0%, #002e5c 100%);
                    overflow-y: auto;
                    overflow-x: hidden;
                    -webkit-overflow-scrolling: touch;
                    transform: translateX(-100%);
                    transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
                    visibility: hidden;
                    padding-bottom: 60px;
                    box-shadow: 4px 0 24px rgba(0,0,0,0.3);
                }
                .main-navbar .navbar-collapse.show {
                    transform: translateX(0);
                    visibility: visible;
                }
                /* Override Bootstrap collapsing state */
                .main-navbar .navbar-collapse.collapsing {
                    display: flex !important;
                    flex-direction: column !important;
                    position: fixed;
                    top: 0;
                    left: 0;
                    bottom: 0;
                    width: 280px;
                    max-width: 82vw;
                    z-index: 1050;
                    background: linear-gradient(180deg, #001f3f 0%, #002e5c 100%);
                    overflow-y: auto;
                    overflow-x: hidden;
                    transform: translateX(-100%);
                    transition: transform 0.3s cubic-bezier(0.4,0,0.2,1);
                    visibility: hidden;
                    padding-bottom: 60px;
                    box-shadow: 4px 0 24px rgba(0,0,0,0.3);
                    height: auto !important;
                }
                /* Dropdown items inside drawer */
                .main-navbar .dropdown-menu .dropdown-item {
                    padding: 12px 20px 12px 36px;
                }
                /* Smooth open animation for mobile dropdown */
                .main-navbar .dropdown-menu.show {
                    animation: mobileDropSlide 0.2s ease forwards;
                }
                @keyframes mobileDropSlide {
                    from { opacity: 0; max-height: 0; }
                    to   { opacity: 1; max-height: 600px; }
                }
                .nav-tools-row {
                    justify-content: center;
                    margin: 8px 0;
                    margin-left: 0;
                    width: 100%;
                }

                /* Footer: allow wrapping (override .flex-nowrap) */
                .footer .row.flex-nowrap,
                .footer .row {
                    flex-wrap: wrap !important;
                }
                .footer .col-lg-3 {
                    margin-bottom: 24px !important;
                }

                /* Hero: already absolute+overflow:hidden globally */

                /* Page hero */
                .page-hero h2 { font-size: 1.5rem; }
            }

            /* Mobile-only info rows (hidden on desktop) */
            .top-bar-mobile-row {
                display: none;
            }
            /* Mobile clock in navbar (hidden on desktop) */
            .navbar-mobile-clock {
                display: none;
            }

            /* ===== MOBILE (≤767px): md breakpoint ===== */
            @media (max-width: 767.98px) {
                /* Top bar: hide location, datetime, dividers & desktop search/toggle in desktop row */
                .top-bar-location,
                .top-bar-datetime,
                .top-bar-divider,
                .top-bar-desktop-actions { display: none !important; }

                /* Hide visitor badge & stats on mobile */
                .visitor-badge,
                .visitor-stats { display: none !important; }

                /* Show mobile info row: single line */
                .top-bar-mobile-row {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    font-size: 0.72rem;
                    color: rgba(255,255,255,0.9);
                    padding: 4px 10px 5px;
                    border-top: 1px solid rgba(255,255,255,0.1);
                    width: 100%;
                    gap: 6px;
                    flex-wrap: nowrap;
                    overflow: hidden;
                }
                .mobile-info-left {
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    min-width: 0;
                    flex: 1;
                    overflow: hidden;
                }
                /* shift mobile date slightly right and add separator before time */
                .top-bar-mobile-row .mobile-info-left #headerDateMobile {
                    margin-left: 6px;
                    font-weight: 600;
                }
                .top-bar-mobile-row .mobile-info-left #headerDateMobile::after {
                    content: " |";
                    margin-left: 8px;
                    color: rgba(255,255,255,0.6);
                    font-weight: 400;
                }
                .mobile-info-left a {
                    color: rgba(255,255,255,0.85);
                    text-decoration: none;
                    max-width: 130px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                    display: inline-block;
                    vertical-align: middle;
                }
                .mobile-info-left a:hover { color: #ffc107; }
                .mobile-info-right {
                    display: flex;
                    align-items: center;
                    gap: 4px;
                    flex-shrink: 0;
                }
                .mobile-clock-inline { display: inline-flex !important; align-items: center; gap:6px; font-weight:700; }
                /* Align hamburger with location icon above (match top-bar-mobile-row padding) */
                .main-navbar .container-fluid {
                    padding-left: 10px !important;
                    padding-right: 10px !important;
                }
                /* Reduce hamburger size + remove border/frame on mobile */
                .navbar-toggler {
                    padding: 4px 6px;
                    width: 38px;
                    height: 38px;
                    border: none !important;
                    outline: none !important;
                    box-shadow: none !important;
                    background: transparent !important;
                    margin-left: 0 !important;
                }
                .navbar-toggler:focus {
                    box-shadow: none !important;
                }
                .navbar-toggler .navbar-toggler-icon {
                    background-size: 18px 18px;
                }
                .top-bar-mobile-row .mobile-info-right .nav-link.nav-icon-btn {
                    padding: 6px;
                    font-size: 0.95rem;
                }
                .theme-toggle {
                    width: 36px !important;
                    height: 20px !important;
                }
                .theme-toggle .toggle-circle {
                    width: 16px !important;
                    height: 16px !important;
                    top: 2px !important;
                    left: 2px !important;
                    font-size: 0.58rem !important;
                }
                [data-theme="dark"] .theme-toggle .toggle-circle { transform: translateX(16px) !important; }
                .mobile-bar-divider {
                    color: rgba(255,255,255,0.3);
                }

                /* Clock in navbar toggler row */
                .navbar-mobile-clock {
                    display: flex;
                    align-items: center;
                    color: rgba(255,255,255,0.9);
                    font-size: 0.78rem;
                    font-weight: 700;
                    white-space: nowrap;
                    margin-left: auto;
                    padding-right: 6px;
                }

                /* Footer columns full width */
                .footer .col-lg-3.col-md-6 {
                    flex: 0 0 100% !important;
                    max-width: 100% !important;
                }

                /* Hero smaller on mobile */
                .hero-section { min-height: 60vh; height: 60vh; padding-bottom: 60px; }
                .hero-section h1.display-4 { font-size: 1.5rem; }
                .hero-section .lead { font-size: 0.9rem; }

                /* Page hero */
                .page-hero h2 { font-size: 1.2rem; }
                .page-hero .breadcrumb-item,
                .page-hero .breadcrumb-item a { font-size: 0.8rem; }
                .page-hero { min-height: 80px; }

                /* Cards: no lift on mobile (prevents accidental touch effects) */
                .card:hover { transform: none; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
                /* Mobile: place trending bar above top-bar (only visual order) */
                .fixed-header { display: flex; flex-direction: column; }
                .berita-tren-bar { order: -1; }
                .top-bar { order: 0; }
            }

            /* ===== SMALL PHONE (≤575px): sm breakpoint ===== */
            @media (max-width: 575.98px) {
                .berita-tren-label { font-size: 0.7rem; padding: 2px 8px; }
                .hero-section h1.display-4 { font-size: 1.25rem; }
                .hero-section .lead { font-size: 0.82rem; }
                .page-hero h2 { font-size: 1rem; }
                .footer { padding: 24px 0 16px; }
                .footer h6 { font-size: 0.85rem; }
                .footer .col-lg-3 { padding: 0 12px; }
            }
            /* Desktop: add separator between date and time in top-bar */
            @media (min-width: 768px) {
                .top-bar .top-bar-datetime #headerDate::after {
                    content: " |";
                    margin-left: 8px;
                    color: rgba(255,255,255,0.6);
                    font-weight: 500;
                }
            }

            /* Desktop: make theme toggle smaller and keep location tidy */
            @media (min-width: 992px) {
                .top-bar .top-bar-location {
                    max-width: 420px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                    display: inline-block;
                    vertical-align: middle;
                }
                .top-bar .top-bar-location a {
                    display: inline-block;
                    max-width: 100%;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    vertical-align: middle;
                    color: rgba(255,255,255,0.85);
                }
                .top-bar .top-bar-datetime { gap: 6px; }
                .theme-toggle { width: 36px; height: 20px; }
                .theme-toggle .toggle-circle { width: 16px; height: 16px; top: 2px; left: 2px; font-size: 0.6rem; }
                [data-theme="dark"] .theme-toggle .toggle-circle { transform: translateX(16px); }
                /* Reduce location text and add spacing from visitor stats on desktop */
                .top-bar .top-bar-location { max-width: 260px; margin-left: 12px; }
                .top-bar .visitor-stats { max-width: 420px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
                .top-bar .visitor-badge { margin-right: 8px; }
            }

        </style>

        {{-- Pre-hide Google Translate toolbar to prevent layout shift --}}
        <style>
            .goog-te-banner-frame.skiptranslate { display: none !important; }
            body { top: 0 !important; }
            .goog-tooltip, #goog-gt-tt, .goog-te-balloon-frame { display: none !important; }
            .goog-text-highlight { background: none !important; box-shadow: none !important; }
            .goog-te-gadget { font-size: 0 !important; line-height: 0 !important; }
            #google_translate_element { opacity: 0; height: 0; overflow: hidden; position: absolute; }
            font[style] > font { font-family: inherit !important; }
        </style>

        @stack('styles')
    </head>
    <body>

        {{-- Google Translate: placed at top of body --}}
        @php
            $gtLocale = session('locale', 'id');
            $gtMap = ['en'=>'en','ar'=>'ar','fr'=>'fr','es'=>'es','ru'=>'ru','ja'=>'ja'];
            $gtTarget = $gtMap[$gtLocale] ?? null;
        @endphp
        @if($gtTarget)
        <div id="google_translate_element"></div>
        <script>
            // Clear then set googtrans cookie before GT script loads
            (function(){
                var t = '/id/{{ $gtTarget }}';
                // Clear old cookies first
                ['', location.hostname, '.' + location.hostname].forEach(function(d){
                    var base = 'googtrans=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/';
                    document.cookie = d ? base + ';domain=' + d : base;
                });
                // Set new cookies
                document.cookie = 'googtrans=' + t + ';path=/';
                document.cookie = 'googtrans=' + t + ';path=/;domain=' + location.hostname;
                document.cookie = 'googtrans=' + t + ';path=/;domain=.' + location.hostname;
            })();

            function googleTranslateElementInit() {
                new google.translate.TranslateElement({
                    pageLanguage: 'id',
                    includedLanguages: 'en,ar,fr,es,ru,ja',
                    autoDisplay: true
                }, 'google_translate_element');
            }
        </script>
        <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
        <script>
            // GT re-trigger: fires multiple times after load to catch all content
            window.addEventListener('load', function() {
                function retriggerGT() {
                    try {
                        var combo = document.querySelector('.goog-te-combo');
                        if (combo && combo.value && combo.value !== 'id') {
                            var evt = document.createEvent('HTMLEvents');
                            evt.initEvent('change', true, true);
                            combo.dispatchEvent(evt);
                        }
                    } catch(e) {}
                }
                // Multiple retries to ensure all dynamic content is translated
                setTimeout(retriggerGT, 1000);
                setTimeout(retriggerGT, 3000);
                setTimeout(retriggerGT, 6000);
            });
        </script>
        @else
        <script>
            // Clear googtrans cookies when using Indonesian
            ['', location.hostname, '.' + location.hostname].forEach(function(d){
                var base = 'googtrans=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/';
                document.cookie = d ? base + ';domain=' + d : base;
            });
        </script>
        @endif
        <!-- ===== FIXED HEADER (all bars stacked with no gaps) ===== -->
        <div class="fixed-header">
        <!-- ===== TOP BAR: Date, Time, Location ===== -->
        <div class="top-bar">
            <div class="container-fluid px-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="visitor-badge"><i class="fas fa-users"></i> {{ __('messages.website_visitors') }}</span>
                    <span class="visitor-stats">
                        {{ __('messages.today') }}: <span class="vs-num">{{ $visitHariIni ?? 0 }}</span>
                        {{ __('messages.this_week') }}: <span class="vs-num">{{ $visitMingguIni ?? 0 }}</span>
                        {{ __('messages.this_month') }}: <span class="vs-num">{{ $visitBulanIni ?? 0 }}</span>
                        {{ __('messages.this_year') }}: <span class="vs-num">{{ number_format($visitTahunIni ?? 0) }}</span>
                    </span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="d-flex align-items-center gap-1 top-bar-location">
                        <i class="fas fa-map-marker-alt"></i>
                        <a href="{{ setting('alamat_link', 'https://www.google.com/maps/place/Dinas+Informasi+dan+Pengolahan+Data+TNI+Angkatan+Udara/@-6.261453,106.918343,17z') }}" target="_blank" rel="noopener" style="color:rgba(255,255,255,0.85);text-decoration:none;" onmouseover="this.style.color='#ffc107'" onmouseout="this.style.color='rgba(255,255,255,0.85)'">{{ localized_text(setting('alamat_text', 'Cilangkap, Cipayung, East Jakarta City, Jakarta 13870')) }}</a>
                    </div>
                    <span class="top-bar-divider" style="color:rgba(255,255,255,0.3);font-size:0.9em;">|</span>
                    <div class="d-flex align-items-center gap-1 top-bar-datetime">
                        <i class="fas fa-calendar-alt"></i>
                        <span id="headerDate">--</span>
                        <span class="clock-time" id="headerTime">--:--:--</span>
                    </div>
                    <span class="top-bar-divider" style="color:rgba(255,255,255,0.3);font-size:0.9em;">|</span>
                    <div class="top-bar-desktop-actions d-flex align-items-center gap-2">
                        <a href="#" id="searchBtnDesktop" class="nav-link nav-icon-btn p-1" title="{{ __('messages.btn_cari') }}" onclick="openSearchOverlay();return false;"><i class="fas fa-search"></i></a>
                        <button class="theme-toggle p-1" id="themeToggle" title="{{ __('messages.day_night_mode') }}" style="margin-left:2px;">
                            <span class="toggle-circle"><i class="fas fa-sun"></i></span>
                        </button>
                        @if(session('from_admin_site_preview'))
                        <a href="{{ route('admin.exit-preview') }}" class="nav-link nav-icon-btn p-1 ms-1" title="{{ __('messages.back_to_admin') }}" style="color:#ffc107;font-size:1rem;">
                            <i class="fas fa-user-cog"></i>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Mobile-only info row: location | date | search | toggle — all on one line -->
            <div class="top-bar-mobile-row">
                <div class="mobile-info-left">
                    <i class="fas fa-map-marker-alt" style="flex-shrink:0;"></i>
                    <a href="{{ setting('alamat_link', 'https://www.google.com/maps/place/Dinas+Informasi+dan+Pengolahan+Data+TNI+Angkatan+Udara/@-6.261453,106.918343,17z') }}" target="_blank" rel="noopener">{{ localized_text(setting('alamat_text', 'Cilangkap, Jakarta Timur')) }}</a>
                    <span class="mobile-bar-divider">|</span>
                    <i class="fas fa-calendar-alt" style="flex-shrink:0;"></i>
                    <span id="headerDateMobile" style="white-space:nowrap;">--</span>
                </div>
                <div class="mobile-info-right">
                    <a href="#" id="searchBtnMobile" class="nav-link nav-icon-btn p-1" style="color:rgba(255,255,255,0.85);font-size:0.9rem;" title="{{ __('messages.btn_cari') }}" onclick="openSearchOverlay();return false;"><i class="fas fa-search"></i></a>
                    <button class="theme-toggle p-1" id="themeToggleMobile" title="{{ __('messages.day_night_mode') }}" style="margin-left:2px;">
                        <span class="toggle-circle"><i class="fas fa-sun"></i></span>
                    </button>
                    <!-- mobile clock placeholder (will be populated/moved by JS on small screens) -->
                    <span id="headerTimeMobile" class="mobile-clock-inline" style="display:none;margin-left:8px;">--:--:--</span>
                </div>
            </div>
        </div>

        <!-- ===== BERITA TREN TICKER ===== -->
        <div class="berita-tren-bar">
            <div class="container-fluid px-3 d-flex align-items-center gap-3">
                <span class="berita-tren-label"><i class="fas fa-fire"></i> {{ __('messages.trending_news') }}:</span>
                <div class="berita-tren-ticker">
                    <div class="ticker-inner" translate="yes">
                        @if(isset($beritaTren) && $beritaTren->count())
                            @foreach($beritaTren as $bt)
                                <a href="{{ route('berita.show', $bt->slug) }}">{{ $bt->localized_judul }}</a>
                            @endforeach
                            {{-- Duplicate set for seamless infinite loop --}}
                            @foreach($beritaTren as $bt)
                                <a href="{{ route('berita.show', $bt->slug) }}">{{ $bt->localized_judul }}</a>
                            @endforeach
                        @else
                            <span>{{ __('messages.no_trending_news') }}</span><span style="margin-right:80px;"></span><span>{{ __('messages.no_trending_news') }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== MAIN NAVBAR (Single block, two rows) ===== -->
        <nav class="main-navbar navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid px-3">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- Mobile-only clock: shown right of hamburger, hidden when menu opens -->
                <span class="navbar-mobile-clock" id="navbarMobileClock">
                    <span id="headerTimeNavbar">--:--:--</span>
                </span>
                <div class="collapse navbar-collapse" id="mainNavbar">
                    {{-- Mobile close button (visible only <992px) --}}
                    <div class="d-lg-none d-flex justify-content-between align-items-center px-3 py-2" style="border-bottom:1px solid rgba(255,255,255,0.12);">
                        <span class="text-white fw-bold" style="font-size:0.9rem;"><i class="fas fa-bars me-2"></i>{{ __('messages.menu') }}</span>
                        <button type="button" class="btn-close btn-close-white" id="mobileMenuClose" aria-label="Close"></button>
                    </div>
                    <!-- === SINGLE ROW: All menu items === -->
                    @php
                    $builtinCustomMenus = \App\Models\CustomMenu::whereNotNull('builtin_parent')
                        ->where('is_published', true)->orderBy('position')
                        ->get()->groupBy('builtin_parent');
                    @endphp
                    <ul class="navbar-nav align-items-lg-center w-100 justify-content-between" style="flex-wrap:nowrap;gap:0.5rem;">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}"><i class="fas fa-home"></i></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ __('messages.profile') }}</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('profil.kata-pengantar') }}">{{ __('messages.foreword') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('profil.sejarah') }}">{{ __('messages.history') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('profil.struktur') }}">{{ __('messages.org_structure') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('profil.index') }}">{{ __('messages.about_us') }}</a></li>
                                @foreach($builtinCustomMenus->get('profil', collect()) as $cm)
                                <li><a class="dropdown-item" href="{{ url('/halaman/'.$cm->slug) }}">{{ localized_text($cm->name) }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ __('messages.news') }}</a>
                            <ul class="dropdown-menu">
                                @if(isset($navKategoris) && $navKategoris->count())
                                    @foreach($navKategoris as $kat)
                                        <li><a class="dropdown-item" href="{{ route('berita.kategori', $kat->slug) }}">{{ $kat->localized_nama_kategori }}</a></li>
                                    @endforeach
                                @endif
                                @foreach($builtinCustomMenus->get('berita', collect()) as $cm)
                                <li><a class="dropdown-item" href="{{ url('/halaman/'.$cm->slug) }}">{{ localized_text($cm->name) }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ __('messages.zona_integritas') }}</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('zona.index') }}">{{ __('messages.zona_integritas') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('zona.perancangan') }}">{{ __('messages.PERANCANGAN') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('zona.penetapan') }}">{{ __('messages.penetapan') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('zona.pembangunan') }}">{{ __('messages.pembangunan') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('zona.pemantauan') }}">{{ __('messages.pemantauan') }}</a></li>
                                @foreach($builtinCustomMenus->get('zona-integritas', collect()) as $cm)
                                <li><a class="dropdown-item" href="{{ url('/halaman/'.$cm->slug) }}">{{ localized_text($cm->name) }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        @if($builtinCustomMenus->has('pia'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ __('messages.pia') }}</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('pia') }}">{{ __('messages.pia') }}</a></li>
                                @foreach($builtinCustomMenus->get('pia') as $cm)
                                <li><a class="dropdown-item" href="{{ url('/halaman/'.$cm->slug) }}">{{ localized_text($cm->name) }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('pia') }}">{{ __('messages.pia') }}</a>
                        </li>
                        @endif
                        @if($builtinCustomMenus->has('e-library'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ __('messages.e_library') }}</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('e-library.index') }}">{{ __('messages.e_library') }}</a></li>
                                @foreach($builtinCustomMenus->get('e-library') as $cm)
                                <li><a class="dropdown-item" href="{{ url('/halaman/'.$cm->slug) }}">{{ localized_text($cm->name) }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('e-library.index') }}">{{ __('messages.e_library') }}</a>
                        </li>
                        @endif
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ __('messages.gallery') }}</a>
                            <ul class="dropdown-menu">
                                @php
                                $galeriMenuLabels = [
                                    'video' => __('messages.filter_video'),
                                    'lain-lain' => __('messages.galeri_lain_lain'),
                                ];
                                @endphp
                                @foreach(\App\Models\Galeri::$kategoriGaleriOptions as $key => $label)
                                    <li><a class="dropdown-item" href="{{ route('galeri.kategori', $key) }}">{{ $galeriMenuLabels[$key] ?? $label }}</a></li>
                                @endforeach
                                @foreach($builtinCustomMenus->get('galeri', collect()) as $cm)
                                <li><a class="dropdown-item" href="{{ url('/halaman/'.$cm->slug) }}">{{ localized_text($cm->name) }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ __('messages.public_service') }}</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('pelayanan.berita') }}">{{ __('messages.public_service_news') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('pelayanan.standar') }}">{{ __('messages.public_service_standard') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('pelayanan.pengaduan') }}">{{ __('messages.complaint_service') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('pelayanan.kompensasi') }}">{{ __('messages.public_service_compensation') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('pelayanan.survei') }}">{{ __('messages.public_satisfaction_survey') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('pelayanan.hasil-survei') }}">{{ __('messages.public_satisfaction_result') }}</a></li>
                                <li><a class="dropdown-item" href="{{ route('tutorial') }}">{{ __('messages.tutorial') }}</a></li>
                                @foreach($builtinCustomMenus->get('pelayanan-publik', collect()) as $cm)
                                <li><a class="dropdown-item" href="{{ url('/halaman/'.$cm->slug) }}">{{ localized_text($cm->name) }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        {{-- Secondary items in same row --}}
                        @if($builtinCustomMenus->has('sp4n-lapor'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ __('messages.sp4n_lapor') }}</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('sp4n-lapor') }}">{{ __('messages.sp4n_lapor') }}</a></li>
                                @foreach($builtinCustomMenus->get('sp4n-lapor') as $cm)
                                <li><a class="dropdown-item" href="{{ url('/halaman/'.$cm->slug) }}">{{ localized_text($cm->name) }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('sp4n-lapor') }}">{{ __('messages.sp4n_lapor') }}</a>
                        </li>
                        @endif
                        @if($builtinCustomMenus->has('whistle-blowing'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ __('messages.whistle_blowing') }}</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('whistle-blowing') }}">{{ __('messages.whistle_blowing') }}</a></li>
                                @foreach($builtinCustomMenus->get('whistle-blowing') as $cm)
                                <li><a class="dropdown-item" href="{{ url('/halaman/'.$cm->slug) }}">{{ localized_text($cm->name) }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('whistle-blowing') }}">{{ __('messages.whistle_blowing') }}</a>
                        </li>
                        @endif
                        @if($builtinCustomMenus->has('kontak'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ __('messages.contact') }}</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('kontak.index') }}">{{ __('messages.contact') }}</a></li>
                                @foreach($builtinCustomMenus->get('kontak') as $cm)
                                <li><a class="dropdown-item" href="{{ url('/halaman/'.$cm->slug) }}">{{ localized_text($cm->name) }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('kontak.index') }}">{{ __('messages.contact') }}</a>
                        </li>
                        @endif
                        @php $navEvents = \App\Models\Event::where('is_published', true)->orderBy('position')->get(); @endphp
                        @if($navEvents->count() || $builtinCustomMenus->has('events'))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ __('messages.events') }}</a>
                            <ul class="dropdown-menu">
                                @foreach($navEvents as $navEv)
                                <li><a class="dropdown-item" href="{{ route('events.show', $navEv) }}">{{ $navEv->localized_nama_kegiatan }}</a></li>
                                @endforeach
                                @foreach($builtinCustomMenus->get('events', collect()) as $cm)
                                <li><a class="dropdown-item" href="{{ url('/halaman/'.$cm->slug) }}">{{ localized_text($cm->name) }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('events.index') }}">{{ __('messages.events') }}</a>
                        </li>
                        @endif
                        {{-- Dynamic Custom Menus --}}
                        @php $customNavMenus = \App\Models\CustomMenu::topLevel()->published()->ordered()->with(['children' => fn($q) => $q->published()->ordered()])->get(); @endphp
                        @foreach($customNavMenus as $cMenu)
                            @if($cMenu->children->count())
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">{{ localized_text($cMenu->name) }}</a>
                                <ul class="dropdown-menu">
                                    @foreach($cMenu->children as $cChild)
                                    <li><a class="dropdown-item" href="{{ url('/halaman/' . $cMenu->slug . '/' . $cChild->slug) }}">{{ localized_text($cChild->name) }}</a></li>
                                    @endforeach
                                </ul>
                            </li>
                            @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/halaman/' . $cMenu->slug) }}">{{ localized_text($cMenu->name) }}</a>
                            </li>
                            @endif
                        @endforeach
                        @php
                            $currentLocale = session('locale', 'id');
                            $langFlags = [
                                'id' => 'id', 'en' => 'us', 'ar' => 'sa',
                                'fr' => 'fr', 'ru' => 'ru', 'es' => 'es', 'ja' => 'jp',
                            ];
                            $currentFlag = $langFlags[$currentLocale] ?? 'id';
                        @endphp
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://flagcdn.com/w40/{{ $currentFlag }}.png" alt="{{ strtoupper($currentLocale) }}" style="height:18px;width:auto;border-radius:2px;">
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @foreach($langFlags as $code => $flag)
                                @php
                                    $langNames = ['id'=>'Indonesia','en'=>'English','ar'=>'العربية','fr'=>'Français','ru'=>'Русский','es'=>'Español','ja'=>'日本語'];
                                @endphp
                                <li>
                                    <a class="dropdown-item d-flex align-items-center gap-2{{ $code === $currentLocale ? ' active' : '' }}" href="{{ route('lang.switch', $code) }}">
                                        <img src="https://flagcdn.com/w40/{{ $flag }}.png" style="width:24px;height:16px;border-radius:2px;">
                                        {{ $langNames[$code] ?? strtoupper($code) }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        </div><!-- end .fixed-header -->

        <!-- Content -->
        <main style="position: relative; z-index: 1;">
            @hasSection('hero')
                @yield('hero')
            @endif
            {{-- Area putih z-index:10 > hero text z-index:2 → menutupi teks saat scroll --}}
            <div class="content-wrapper" style="position: relative; z-index: 10; background-color: #f8f9fa;" translate="yes">
                @yield('content')
            </div>

            {{-- ===== SHARED SECTIONS: Yang Terlewat, Galeri, Instansi ===== --}}
            <div class="shared-sections-wrapper" style="position: relative; z-index: 10; background-color: #f8f9fa;">
                @include('partials.shared-sections')
            </div>
        </main>

        <!-- Footer -->
        <footer class="footer" style="background: linear-gradient(135deg, #001f3f 0%, #003366 100%);" translate="yes">
            <div class="container-fluid px-1 px-lg-3">
                <div class="row gx-0 gy-0 d-flex flex-row flex-nowrap justify-content-between align-items-stretch" style="min-height: 100px;">
                    <div class="col-lg-3 col-md-6 mb-0 d-flex flex-column justify-content-between align-items-start h-100">
                        <div class="d-flex align-items-center mb-3">
                            @php $footerLogo = setting('footer_logo'); @endphp
                            <img src="{{ $footerLogo ? asset('storage/'.$footerLogo) : asset('assets/image/disinfolahta.png') }}" alt="{{ __('messages.site_name') }}" style="height: 50px; margin-right: 15px;">
                            <div>
                                <h5 class="text-white mb-0 fw-bold">{{ localized_setting('footer_site_name', __('messages.site_name')) }}</h5>
                                <small class="text-white-50">{{ localized_setting('footer_site_subtitle', __('messages.site_subtitle')) }}</small>
                            </div>
                        </div>
                        <p class="text-white" style="opacity: 0.85;">{{ localized_setting('footer_description', __('messages.site_description')) }}</p>
                        <div class="d-flex gap-2 mt-3">
                            @if(isset($mediaSosialFooter) && $mediaSosialFooter->count())
                                @foreach($mediaSosialFooter as $sosmed)
                                <a href="{{ $sosmed->link ?: '#' }}" target="_blank" title="{{ $sosmed->nama }}" class="btn btn-outline-light btn-sm rounded-circle" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; overflow:hidden; padding:0;">
                                    @if($sosmed->logo)
                                        <img src="{{ asset($sosmed->logo) }}" alt="{{ $sosmed->nama }}" style="width:22px;height:22px;object-fit:contain;filter:brightness(0) invert(1);">
                                    @elseif($sosmed->icon)
                                        <i class="{{ $sosmed->icon }}"></i>
                                    @endif
                                </a>
                                @endforeach
                            @else
                            <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="btn btn-outline-light btn-sm rounded-circle" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                                <i class="fab fa-youtube"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-0 d-flex flex-column justify-content-between align-items-start h-100">
                        <h6 class="text-warning fw-bold mb-3 text-uppercase">{{ __('messages.main_menu') }}</h6>
                        <ul class="list-unstyled">
                            @if(isset($menuUtamaFooter) && $menuUtamaFooter->count())
                                @php
                                $footerIconMap = [
                                    'home'              => 'fa-home',
                                    'berita.index'      => 'fa-newspaper',
                                    'galeri.index'      => 'fa-images',
                                    'profil.index'      => 'fa-user-circle',
                                    'kontak.index'      => 'fa-envelope',
                                    'events.index'      => 'fa-calendar-alt',
                                    'elibrary.index'    => 'fa-book',
                                    'pelayanan.index'   => 'fa-concierge-bell',
                                    'pia'               => 'fa-shield-alt',
                                    'survei.index'      => 'fa-poll',
                                    'sp4n.index'        => 'fa-comment-dots',
                                    'whistleblowing.index' => 'fa-exclamation-triangle',
                                ];
                                $footerMenuTrans = [
                                    'home'              => __('messages.home'),
                                    'berita.index'      => __('messages.news'),
                                    'galeri.index'      => __('messages.gallery'),
                                    'profil.index'      => __('messages.profile'),
                                    'kontak.index'      => __('messages.contact'),
                                    'events.index'      => __('messages.events'),
                                    'elibrary.index'    => __('messages.e_library'),
                                    'pelayanan.index'   => __('messages.public_service'),
                                    'pia'               => __('messages.pia'),
                                    'survei.index'      => __('messages.public_satisfaction_survey'),
                                    'sp4n.index'        => __('messages.sp4n_lapor'),
                                    'whistleblowing.index' => __('messages.whistle_blowing'),
                                    'tutorial.index'    => __('messages.tutorial'),
                                ];
                                @endphp
                                @foreach($menuUtamaFooter as $menu)
                                @php $fIcon = $footerIconMap[$menu->route_name] ?? 'fa-angle-right'; @endphp
                                <li class="mb-2">
                                    <a href="{{ route($menu->route_name) }}" class="text-white text-decoration-none hover-link">
                                        <i class="fas {{ $fIcon }} me-2"></i>{{ $footerMenuTrans[$menu->route_name] ?? $menu->nama }}
                                    </a>
                                </li>
                                @endforeach
                            @else
                            <li class="mb-2"><a href="{{ route('home') }}" class="text-white text-decoration-none hover-link"><i class="fas fa-home me-2"></i>{{ __('messages.home') }}</a></li>
                            <li class="mb-2"><a href="{{ route('berita.index') }}" class="text-white text-decoration-none hover-link"><i class="fas fa-newspaper me-2"></i>{{ __('messages.news') }}</a></li>
                            <li class="mb-2"><a href="{{ route('galeri.index') }}" class="text-white text-decoration-none hover-link"><i class="fas fa-images me-2"></i>{{ __('messages.gallery') }}</a></li>
                            <li class="mb-2"><a href="{{ route('profil.index') }}" class="text-white text-decoration-none hover-link"><i class="fas fa-user-circle me-2"></i>{{ __('messages.profile') }}</a></li>
                            <li class="mb-2"><a href="{{ route('kontak.index') }}" class="text-white text-decoration-none hover-link"><i class="fas fa-envelope me-2"></i>{{ __('messages.contact') }}</a></li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-0 d-flex flex-column justify-content-between align-items-start h-100">
                        <h6 class="text-warning fw-bold mb-3 text-uppercase">{{ __('messages.visitor_statistics') }}</h6>
                        <div class="visitor-counter-widget">
                            <div class="vc-digital-counter mb-2">
                                @php $totalStr = str_pad($visitTotal ?? 0, 6, '0', STR_PAD_LEFT); @endphp
                                @foreach(str_split($totalStr) as $digit)
                                    <span class="vc-digit">{{ $digit }}</span>
                                @endforeach
                            </div>
                            <ul class="list-unstyled vc-stats-list">
                                <li><i class="fas fa-user text-warning me-2"></i><span class="text-white">{{ __('messages.users_today') }} : {{ $visitHariIni ?? 0 }}</span></li>
                                <li><i class="fas fa-user text-warning me-2"></i><span class="text-white">{{ __('messages.users_yesterday') }} : {{ $visitKemarin ?? 0 }}</span></li>
                                <li><i class="fas fa-users text-warning me-2"></i><span class="text-white">{{ __('messages.users_last_7_days') }} : {{ $visitMingguIni ?? 0 }}</span></li>
                                <li><i class="fas fa-users text-warning me-2"></i><span class="text-white">{{ __('messages.users_last_30_days') }} : {{ $visitBulanIni ?? 0 }}</span></li>
                                <li><i class="fas fa-calendar text-warning me-2"></i><span class="text-white">{{ __('messages.users_this_month') }} : {{ $visitBulanIni ?? 0 }}</span></li>
                                <li><i class="fas fa-calendar-alt text-warning me-2"></i><span class="text-white">{{ __('messages.users_this_year') }} : {{ $visitTahunIni ?? 0 }}</span></li>
                                <li><i class="fas fa-chart-bar text-warning me-2"></i><span class="text-white">{{ __('messages.total_users') }} : {{ number_format($visitTotal ?? 0) }}</span></li>
                                <li><i class="fas fa-eye text-warning me-2"></i><span class="text-white">{{ __('messages.views_today') }} : {{ $viewsHariIni ?? 0 }}</span></li>
                                <li><i class="fas fa-chart-line text-warning me-2"></i><span class="text-white">{{ __('messages.total_views') }} : {{ number_format($viewsTotal ?? 0) }}</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-0 d-flex flex-column justify-content-between align-items-start h-100">
                        <h6 class="text-warning fw-bold mb-3 text-uppercase">{{ __('messages.contact_us') }}</h6>
                        <ul class="list-unstyled">
                            @if(isset($hubungiKamiFooter) && $hubungiKamiFooter->count())
                                @foreach($hubungiKamiFooter as $kontak)
                                <li class="mb-3 d-flex align-items-center">
                                    @if($kontak->icon_image)
                                        <img src="{{ asset('storage/'.$kontak->icon_image) }}" alt="" style="width:18px;height:18px;object-fit:contain;filter:brightness(0) invert(1) sepia(1) saturate(5) hue-rotate(5deg);margin-right:12px;flex-shrink:0;">
                                    @else
                                        <i class="{{ $kontak->icon }} text-warning me-3" style="width:16px;text-align:center;flex-shrink:0;"></i>
                                    @endif
                                    @if($kontak->link)
                                        <a href="{{ $kontak->link }}" target="_blank" class="text-white text-decoration-none">{{ $kontak->teks }}</a>
                                    @else
                                        <span class="text-white">{{ $kontak->teks }}</span>
                                    @endif
                                </li>
                                @endforeach
                            @else
                            <li class="mb-3 d-flex align-items-start">
                                <i class="fas fa-map-marker-alt text-warning me-3 mt-1"></i>
                                @if(setting('alamat_link'))
                                    <a href="{{ setting('alamat_link') }}" target="_blank" class="text-white text-decoration-none">{{ localized_text(setting('alamat_text', 'Disinfolahtaau Gedung B 3 Lantai 1 Jl. Raya hamkam Cilangkap, Jakarta Timur.')) }}</a>
                                @else
                                    <span class="text-white">{{ localized_text(setting('alamat_text', 'Disinfolahtaau Gedung B 3 Lantai 1 Jl. Raya hamkam Cilangkap, Jakarta Timur.')) }}</span>
                                @endif
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <i class="fas fa-envelope text-warning me-3"></i>
                                <a href="mailto:pustasisinfo@tni-au.mil.id" class="text-white text-decoration-none">pustasisinfo@tni-au.mil.id</a>
                            </li>
                            <li class="mb-3 d-flex align-items-center">
                                <i class="fas fa-phone text-warning me-3"></i>
                                <span class="text-white">+62-21-8709169</span>
                            </li>
                            <li class="d-flex align-items-center">
                                <i class="fas fa-clock text-warning me-3"></i>
                                <span class="text-white">{{ __('messages.working_hours') }}</span>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
                <hr style="border-color: rgba(255,255,255,0.2);">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="text-white mb-0">&copy; {{ date('Y') }} <strong>{{ __('messages.site_name') }}</strong>. {{ __('messages.all_rights_reserved') }}</p>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <small class="text-white-50">{{ __('messages.misc_motto') }}</small>
                    </div>
                </div>
            </div>
        </footer>
        
        <style>
            .footer .hover-link:hover {
                color: #ffc107 !important;
                padding-left: 5px;
                transition: all 0.3s ease;
            }
            .footer a {
                transition: all 0.3s ease;
            }
            .footer .btn-outline-light:hover {
                background-color: #ffc107;
                border-color: #ffc107;
                color: #001f3f !important;
            }
            /* Visitor Counter Widget */
            .visitor-counter-widget {
                background: rgba(255,255,255,0.05);
                border-radius: 8px;
                padding: 12px;
            }
            .vc-digital-counter {
                display: flex;
                gap: 3px;
                justify-content: center;
            }
            .vc-digit {
                background: #111;
                color: #ffc107;
                font-family: 'Courier New', monospace;
                font-size: 1.3rem;
                font-weight: 900;
                padding: 4px 8px;
                border-radius: 4px;
                border: 1px solid #333;
                min-width: 28px;
                text-align: center;
                line-height: 1;
            }
            .vc-stats-list li {
                padding: 3px 0;
                font-size: 0.82rem;
                display: flex;
                align-items: center;
            }
            .vc-stats-list li i {
                width: 20px;
                text-align: center;
                font-size: 0.78rem;
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
            /* Pagination Dark Mode */
            [data-theme="dark"] .pagination .page-item .page-link {
                background: #1e293b;
                color: #7db8ff;
                border-color: #2a2a4a;
            }
            [data-theme="dark"] .pagination .page-item .page-link:hover {
                background: linear-gradient(135deg, #003d82, #0066cc);
                color: #fff;
            }
            [data-theme="dark"] .pagination .page-item.active .page-link {
                background: linear-gradient(135deg, #003d82, #0066cc);
                color: #fff;
            }
            [data-theme="dark"] .pagination .page-item.disabled .page-link {
                background: #16213e;
                color: #555;
            }
        </style>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Search Overlay (AJAX) -->
        <div id="searchOverlay" style="display:none;position:fixed;inset:0;z-index:2000;align-items:center;justify-content:center;">
            <div style="position: absolute; inset:0; background: rgba(0,0,0,0.45);"></div>
            <div id="searchOverlayCard" style="position:relative; width:900px; max-width:94%; background:#fff; border-radius:12px; box-shadow:0 10px 40px rgba(0,0,0,0.4); padding:18px;">
                <button id="searchOverlayClose" style="position:absolute;right:12px;top:12px;border:none;background:none;font-size:1.2rem;">&times;</button>
                <h5 style="margin:0 0 12px 0;"><i class="fas fa-search me-2"></i>{{ __('messages.btn_cari') }}</h5>
                <form id="overlaySearchForm" action="{{ route('search') }}" method="GET" style="display:flex;gap:8px;margin-bottom:10px;">
                    <input id="overlaySearchInput" name="q" type="search" placeholder="{{ __('messages.filter_cari_berita') }}" class="form-control" style="border-radius:8px;" autocomplete="off">
                    <button type="submit" class="btn btn-primary">{{ __('messages.btn_cari') }}</button>
                </form>
                <div id="overlaySearchResults" style="max-height:60vh; overflow:auto;">
                    <div class="text-muted">Ketik lalu tekan Enter untuk mencari.</div>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Backdrop -->
        <div class="mobile-menu-backdrop" id="mobileMenuBackdrop"></div>

        <!-- Mobile Side-Drawer Menu & Dropdown Toggle -->
        <script>
        (function() {
            var BP = 992;
            function isMobile() { return window.innerWidth < BP; }

            var navCollapse = document.getElementById('mainNavbar');
            var backdrop = document.getElementById('mobileMenuBackdrop');
            var closeBtn = document.getElementById('mobileMenuClose');
            var toggler = document.querySelector('.main-navbar .navbar-toggler');
            var navbarClock = document.getElementById('navbarMobileClock');

            // === Open drawer ===
            function openDrawer() {
                if (!navCollapse) return;
                navCollapse.classList.add('show');
                backdrop.classList.add('show');
                document.body.style.overflow = 'hidden';
                if (navbarClock) navbarClock.style.display = 'none';
            }

            // === Close drawer ===
            function closeDrawer() {
                if (!navCollapse) return;
                navCollapse.classList.remove('show');
                backdrop.classList.remove('show');
                document.body.style.overflow = '';
                if (navbarClock) navbarClock.style.display = '';
            }

            // Override Bootstrap collapse toggler on mobile — use our own drawer logic
            if (toggler) {
                toggler.addEventListener('click', function(e) {
                    if (!isMobile()) return; // let Bootstrap handle on desktop
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    var isOpen = navCollapse.classList.contains('show');
                    if (isOpen) { closeDrawer(); } else { openDrawer(); }
                });
            }

            // Prevent Bootstrap collapse from interfering on mobile
            if (navCollapse) {
                navCollapse.addEventListener('show.bs.collapse', function(e) {
                    if (isMobile()) {
                        e.preventDefault();
                        openDrawer();
                    }
                });
                navCollapse.addEventListener('hide.bs.collapse', function(e) {
                    if (isMobile()) {
                        e.preventDefault();
                        closeDrawer();
                    }
                });
            }

            // Close button inside drawer
            if (closeBtn) {
                closeBtn.addEventListener('click', function() { closeDrawer(); });
            }

            // ===== Search overlay logic =====
            function openSearchOverlay(prefill) {
                var overlay = document.getElementById('searchOverlay');
                var input = document.getElementById('overlaySearchInput');
                var results = document.getElementById('overlaySearchResults');
                if (!overlay) return;
                overlay.style.display = 'flex';
                if (prefill) input.value = prefill;
                input.focus();
                results.innerHTML = '<div class="text-muted">Ketik lalu tekan Enter untuk mencari.</div>';
                document.body.style.overflow = 'hidden';
            }

            function closeSearchOverlay() {
                var overlay = document.getElementById('searchOverlay');
                if (!overlay) return;
                overlay.style.display = 'none';
                document.body.style.overflow = '';
            }

            window.openSearchOverlay = openSearchOverlay;

            var overlayClose = document.getElementById('searchOverlayClose');
            if (overlayClose) overlayClose.addEventListener('click', closeSearchOverlay);

            // Submit handler: perform AJAX GET and render results
            var overlayForm = document.getElementById('overlaySearchForm');
            if (overlayForm) {
                overlayForm.addEventListener('submit', function(e){
                    e.preventDefault();
                    var q = document.getElementById('overlaySearchInput').value.trim();
                    if (q.length < 2) return;
                    var resultsEl = document.getElementById('overlaySearchResults');
                    resultsEl.innerHTML = '<div class="text-muted">Mencari...</div>';
                    fetch(this.action + '?q=' + encodeURIComponent(q), {
                        headers: { 'Accept': 'application/json' },
                        credentials: 'same-origin'
                    }).then(function(resp){
                        return resp.json();
                    }).then(function(json){
                        renderOverlayResults(json.results || [], q, document.getElementById('overlaySearchResults'));
                    }).catch(function(){
                        resultsEl.innerHTML = '<div class="text-danger">Terjadi kesalahan saat mencari.</div>';
                    });
                });
            }

            function renderOverlayResults(items, q, container) {
                if (!container) return;
                if (!items || !items.length) {
                    container.innerHTML = '<div class="no-results-box" style="padding:20px;">Tidak ada hasil untuk kata kunci &quot;' + escapeHtml(q) + '&quot;</div>';
                    return;
                }
                var html = '';
                items.forEach(function(it){
                    html += '<a href="' + (it.url || '#') + '" class="search-card d-block" style="margin-bottom:10px; display:flex; gap:12px; text-decoration:none; color:inherit;">';
                    if (it.image) {
                        html += '<img src="'+it.image+'" class="search-card-thumb" style="width:80px;height:60px;object-fit:cover;border-radius:8px;">';
                    } else {
                        html += '<div class="search-card-thumb-icon" style="width:80px;height:60px;border-radius:8px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#003d82,#0066cc);color:#fff;"><i class="'+(it.icon||'fas fa-file')+'"></i></div>';
                    }
                    html += '<div class="search-card-body">';
                    html += '<span class="search-card-type">'+escapeHtml(it.type||'')+'</span>';
                    html += '<div class="search-card-title" style="font-weight:700;margin-bottom:6px;">'+(it.highlighted_title||escapeHtml(it.title||''))+'</div>';
                    if (it.highlighted_excerpt) html += '<div class="search-card-excerpt">'+it.highlighted_excerpt+'</div>';
                    if (it.date) html += '<div class="search-card-date"><i class="fas fa-calendar-alt me-1"></i>'+escapeHtml(it.date)+'</div>';
                    html += '</div></a>';
                });
                container.innerHTML = html;
            }

            function escapeHtml(s){ return String(s).replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":"&#39;"}[c]; }); }
            // Tap backdrop to close
            if (backdrop) {
                backdrop.addEventListener('click', function() { closeDrawer(); });
            }

            // Remove Bootstrap data-bs-toggle from all dropdown toggles on mobile
            // so Bootstrap doesn't interfere with our custom mobile dropdown logic
            function disableBootstrapDropdowns() {
                if (!isMobile()) return;
                document.querySelectorAll('.main-navbar .dropdown-toggle').forEach(function(t) {
                    t.removeAttribute('data-bs-toggle');
                    try { var i = bootstrap.Dropdown.getInstance(t); if (i) i.dispose(); } catch(e) {}
                    t.classList.remove('show');
                });
            }

            function enableBootstrapDropdowns() {
                if (isMobile()) return;
                document.querySelectorAll('.main-navbar .dropdown-toggle').forEach(function(t) {
                    if (!t.getAttribute('data-bs-toggle')) t.setAttribute('data-bs-toggle', 'dropdown');
                    t.closest('.dropdown').classList.remove('open');
                });
            }

            function closeAllDropdowns(except) {
                document.querySelectorAll('.main-navbar .dropdown').forEach(function(d) {
                    if (except && d === except) return;
                    d.classList.remove('open');
                    var m = d.querySelector('.dropdown-menu');
                    if (m) m.classList.remove('show');
                    var t = d.querySelector('.dropdown-toggle');
                    if (t) t.setAttribute('aria-expanded', 'false');
                });
            }

            function sync() {
                if (isMobile()) {
                    disableBootstrapDropdowns();
                    try { var bsC = bootstrap.Collapse.getInstance(navCollapse); if (bsC) bsC.dispose(); } catch(e) {}
                    if (toggler) toggler.removeAttribute('data-bs-toggle');
                } else {
                    enableBootstrapDropdowns();
                    if (toggler && !toggler.getAttribute('data-bs-toggle')) toggler.setAttribute('data-bs-toggle', 'collapse');
                    backdrop.classList.remove('show');
                    document.body.style.overflow = '';
                    navCollapse.classList.remove('show');
                }
            }

            sync();
            document.addEventListener('DOMContentLoaded', function() { setTimeout(sync, 50); });

            var resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() { sync(); if (!isMobile()) closeDrawer(); }, 100);
            });

            // CAPTURE PHASE — handle mobile dropdown toggles only.
            // IMPORTANT: check e.target FIRST before modifying any DOM state,
            // so that dropdown-item clicks are never interrupted.
            document.addEventListener('click', function(e) {
                if (!isMobile()) return;

                // 1. Direct navigation links (non-toggle) — let them navigate, just close drawer
                var navLink = e.target.closest('.main-navbar .nav-link:not(.dropdown-toggle)');
                if (navLink) {
                    setTimeout(closeDrawer, 150);
                    return; // allow default navigation
                }

                // 2. Dropdown sub-menu items — let them navigate, close drawer
                var dropdownItem = e.target.closest('.main-navbar .dropdown-item');
                if (dropdownItem) {
                    setTimeout(closeDrawer, 150);
                    return; // allow default navigation
                }

                // 3. Dropdown toggle — open/close sub-menu, prevent href="#" from firing
                var toggle = e.target.closest('.main-navbar .dropdown-toggle');
                if (toggle) {
                    e.preventDefault();
                    e.stopImmediatePropagation();

                    var dropdown = toggle.closest('.dropdown');
                    var menu = dropdown.querySelector('.dropdown-menu');
                    var isOpen = dropdown.classList.contains('open');

                    closeAllDropdowns(dropdown);

                    if (isOpen) {
                        dropdown.classList.remove('open');
                        menu.classList.remove('show');
                        toggle.setAttribute('aria-expanded', 'false');
                    } else {
                        dropdown.classList.add('open');
                        menu.classList.add('show');
                        toggle.setAttribute('aria-expanded', 'true');
                    }
                    return;
                }

                // 4. Click outside navbar — close all dropdowns
                if (!e.target.closest('.main-navbar')) {
                    closeAllDropdowns();
                }
            }, true);
        })();
        </script>

        <!-- Live Clock & Date Script -->
        <script>
            // Localized day/month names from lang files (stored as JSON strings in translations)
            // Decode the JSON string in PHP, then emit as a JS array literal.
            const days = @json(json_decode(__('messages.days_json'), true));
            const months = @json(json_decode(__('messages.months_json'), true));

            function updateHeaderClock() {
                const now = new Date();

                const h = String(now.getHours()).padStart(2, '0');
                const m = String(now.getMinutes()).padStart(2, '0');
                const s = String(now.getSeconds()).padStart(2, '0');
                const timeStr = h + ':' + m + ':' + s + ' {{ __("messages.timezone") }}';

                const dayName = days[now.getDay()];
                const date = now.getDate();
                const monthName = months[now.getMonth()];
                const year = now.getFullYear();
                const dateStr = dayName + ', ' + date + ' ' + monthName + ' ' + year;

                const elTime = document.getElementById('headerTime');
                const elDate = document.getElementById('headerDate');
                if (elTime) elTime.textContent = timeStr;
                if (elDate) elDate.textContent = dateStr;

                const elTimeMobile = document.getElementById('headerTimeMobile');
                const elDateMobile = document.getElementById('headerDateMobile');
                const elTimeNavbar = document.getElementById('headerTimeNavbar');
                if (elTimeMobile) elTimeMobile.textContent = timeStr;
                if (elDateMobile) elDateMobile.textContent = dateStr;
                if (elTimeNavbar) elTimeNavbar.textContent = timeStr;
            }
            updateHeaderClock();
            setInterval(updateHeaderClock, 1000);
            
            // Reposition clock and mobile buttons on small screens
            function repositionMobileControls() {
                var isMobile = window.innerWidth <= 767.98;
                var navbarClock = document.getElementById('navbarMobileClock');
                var mobileInfoRight = document.querySelector('.top-bar .top-bar-mobile-row .mobile-info-right');
                if (!navbarClock || !mobileInfoRight) return;

                var searchBtn = mobileInfoRight.querySelector('.nav-link.nav-icon-btn');
                var themeBtn = document.getElementById('themeToggleMobile');
                var headerTimeNavbar = document.getElementById('headerTimeNavbar');
                var headerTimeMobile = document.getElementById('headerTimeMobile');

                if (isMobile) {
                    // move search and theme into navbar area (next to hamburger)
                    if (searchBtn && navbarClock && !navbarClock.contains(searchBtn)) navbarClock.appendChild(searchBtn);
                    if (themeBtn && navbarClock && !navbarClock.contains(themeBtn)) navbarClock.appendChild(themeBtn);
                    // hide the small navbar clock element (we'll show mobile clock in top bar)
                    if (headerTimeNavbar) headerTimeNavbar.style.display = 'none';
                    if (headerTimeMobile) headerTimeMobile.style.display = 'inline-flex';
                } else {
                    // move buttons back to mobile-info-right container
                    if (searchBtn && mobileInfoRight && !mobileInfoRight.contains(searchBtn)) mobileInfoRight.insertBefore(searchBtn, mobileInfoRight.firstChild);
                    if (themeBtn && mobileInfoRight && !mobileInfoRight.contains(themeBtn)) mobileInfoRight.insertBefore(themeBtn, mobileInfoRight.querySelector('.mobile-clock-inline'));
                    // restore navbar clock visibility
                    if (headerTimeNavbar) headerTimeNavbar.style.display = '';
                    if (headerTimeMobile) headerTimeMobile.style.display = 'none';
                }
            }
            // run once and on resize
            repositionMobileControls();
            window.addEventListener('resize', function () { repositionMobileControls(); });
        </script>

        <!-- Dark/Light Mode Toggle Script -->
        <script>
            (function() {
                const toggle = document.getElementById('themeToggle');
                const toggleMobile = document.getElementById('themeToggleMobile');
                const icon = toggle ? toggle.querySelector('.toggle-circle i') : null;
                const iconMobile = toggleMobile ? toggleMobile.querySelector('.toggle-circle i') : null;
                const saved = localStorage.getItem('theme') || 'light';
                document.documentElement.setAttribute('data-theme', saved);
                updateIcon(saved);

                function applyTheme(btn) {
                    const current = document.documentElement.getAttribute('data-theme');
                    const next = current === 'dark' ? 'light' : 'dark';
                    document.documentElement.setAttribute('data-theme', next);
                    localStorage.setItem('theme', next);
                    updateIcon(next);
                }

                if (toggle) toggle.addEventListener('click', applyTheme);
                if (toggleMobile) toggleMobile.addEventListener('click', applyTheme);

                function updateIcon(theme) {
                    const cls = theme === 'dark' ? 'fas fa-moon' : 'fas fa-sun';
                    if (icon) icon.className = cls;
                    if (iconMobile) iconMobile.className = cls;
                }
            })();
        </script>

        <!-- Dynamic body padding based on actual header height -->
        <script>
            function adjustBodyPadding() {
                const header = document.querySelector('.fixed-header');
                if (!header) return;
                const h = header.offsetHeight;
                // Non-hero pages: apply to body
                document.body.style.paddingTop = h + 'px';
                // Hero/page-hero: extend behind header (no gap)
                document.querySelectorAll('.hero-section, .page-hero').forEach(function(el) {
                    el.style.marginTop = '-' + h + 'px';
                    el.style.paddingTop = (h + 60) + 'px';
                });
            }
            adjustBodyPadding();
            window.addEventListener('resize', adjustBodyPadding);
            window.addEventListener('load', adjustBodyPadding);
        </script>

        @php
            $pgBgSrc  = setting('page_hero_bg', 'assets/image/pesawat.jpg');
            $pgBgType = setting('page_hero_bg_type', 'image');
            $pgRot    = (int) setting('page_hero_bg_rotation', 0);
        @endphp
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var src  = '{{ asset($pgBgSrc) }}';
                var type = '{{ $pgBgType }}';
                var rot  = '{{ $pgRot }}';

                function buildTransform(el, deg) {
                    if (!deg) return '';
                    if (deg === 180) return 'rotate(180deg)';
                    // 90 / 270: scale to fill container
                    var w = el.offsetWidth  || window.innerWidth;
                    var h = el.offsetHeight || 400;
                    var scale = (Math.max(w / h, h / w) * 1.05).toFixed(3);
                    return 'rotate(' + deg + 'deg) scale(' + scale + ')';
                }

                document.querySelectorAll('.page-hero').forEach(function (el) {
                    // overlay
                    var overlay = document.createElement('div');
                    overlay.className = 'page-hero-overlay';
                    el.insertBefore(overlay, el.firstChild);
                    // media element: video or img
                    var media;
                    if (type === 'video') {
                        media = document.createElement('video');
                        media.autoplay    = true;
                        media.muted       = true;
                        media.loop        = true;
                        media.playsInline = true;
                        media.src = src;
                    } else {
                        media = document.createElement('img');
                        media.src = src;
                        media.alt = '';
                    }
                    media.className = 'page-hero-video-bg';
                    var tf = buildTransform(el, rot);
                    if (tf) media.style.transform = tf;
                    el.insertBefore(media, el.firstChild);
                });

                // recalculate on resize (for 90/270 scale)
                if (rot === 90 || rot === 270) {
                    window.addEventListener('resize', function () {
                        document.querySelectorAll('.page-hero-video-bg').forEach(function (m) {
                            var tf = buildTransform(m.parentElement, rot);
                            if (tf) m.style.transform = tf;
                        });
                    });
                }
            });
        </script>

        {{-- ==================== SEARCH OVERLAY ==================== --}}
        <div id="searchOverlay" style="display:none;">
            <div id="searchOverlayBackdrop" onclick="closeSearchOverlay()"></div>
            <div id="searchOverlayBox">
                <div id="searchOverlayHeader">
                    <span id="searchOverlayTitle"><i class="fas fa-search me-2"></i>{{ __('messages.btn_cari') }}</span>
                    <button id="searchOverlayClose" onclick="closeSearchOverlay()" title="{{ __('messages.btn_tutup') }}">&times;</button>
                </div>
                <form id="searchOverlayForm" action="{{ route('search') }}" method="GET">
                    <div id="searchOverlayInputWrap">
                        <i class="fas fa-search" id="searchOverlayIcon"></i>
                           <input type="text" id="searchOverlayInput" name="q" autocomplete="off" placeholder="{{ __('messages.filter_cari_berita') }}">
                           <button type="submit" id="searchOverlayBtn">{{ __('messages.btn_cari') }}</button>
                    </div>
                </form>
                <div id="searchOverlayHints">
                    <span>{{ __('messages.menu') }}:</span>
                    <a href="{{ route('search', ['q' => 'berita']) }}" onclick="closeSearchOverlay()">{{ __('messages.news') }}</a>
                    <a href="{{ route('search', ['q' => 'galeri']) }}" onclick="closeSearchOverlay()">{{ __('messages.gallery') }}</a>
                    <a href="{{ route('search', ['q' => 'pelayanan']) }}" onclick="closeSearchOverlay()">{{ __('messages.public_service') }}</a>
                    <a href="{{ route('search', ['q' => 'event']) }}" onclick="closeSearchOverlay()">{{ __('messages.events') }}</a>
                </div>
            </div>
        </div>

        <style>
            #searchOverlay {
                position: fixed; inset: 0; z-index: 999999;
                display: flex; align-items: flex-start; justify-content: center;
                padding-top: 90px;
            }
            #searchOverlayBackdrop {
                position: absolute; inset: 0;
                background: rgba(0,0,0,0.55);
                backdrop-filter: blur(3px);
                -webkit-backdrop-filter: blur(3px);
            }
            #searchOverlayBox {
                position: relative; z-index: 1;
                background: #fff;
                border-radius: 16px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.25);
                width: 100%; max-width: 640px;
                margin: 0 16px;
                overflow: hidden;
                animation: searchBoxIn 0.22s cubic-bezier(0.4,0,0.2,1);
            }
            [data-theme="dark"] #searchOverlayBox { background: #1a2333; box-shadow: 0 20px 60px rgba(0,0,0,0.6); }
            @keyframes searchBoxIn {
                from { opacity:0; transform:translateY(-18px) scale(0.97); }
                to   { opacity:1; transform:translateY(0) scale(1); }
            }
            #searchOverlayHeader {
                display: flex; align-items: center; justify-content: space-between;
                padding: 16px 20px 12px;
                border-bottom: 1px solid #e9ecef;
            }
            [data-theme="dark"] #searchOverlayHeader { border-color: #2d3a52; }
            #searchOverlayTitle { font-size: 1rem; font-weight: 700; color: #003d82; }
            [data-theme="dark"] #searchOverlayTitle { color: #7eb4ff; }
            #searchOverlayClose {
                background: none; border: none; font-size: 1.6rem; line-height:1;
                color: #999; cursor: pointer; padding: 0 4px;
                transition: color 0.15s;
            }
            #searchOverlayClose:hover { color: #003d82; }
            #searchOverlayInputWrap {
                display: flex; align-items: center; gap: 10px;
                padding: 16px 20px;
            }
            #searchOverlayIcon { color: #003d82; font-size: 1.1rem; flex-shrink:0; }
            [data-theme="dark"] #searchOverlayIcon { color: #7eb4ff; }
            #searchOverlayInput {
                flex: 1; border: none; outline: none; font-size: 1.05rem;
                background: transparent; color: #1a2340;
            }
            [data-theme="dark"] #searchOverlayInput { color: #e4e8f0; }
            #searchOverlayInput::placeholder { color: #aaa; }
            #searchOverlayBtn {
                background: #003d82; color: #fff; border: none;
                border-radius: 8px; padding: 8px 20px;
                font-weight: 600; font-size: 0.95rem; cursor: pointer;
                transition: background 0.2s;
                flex-shrink: 0;
            }
            #searchOverlayBtn:hover { background: #0055b3; }
            #searchOverlayHints {
                padding: 10px 20px 16px;
                border-top: 1px solid #f0f0f0;
                font-size: 0.82rem; color: #888;
                display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
            }
            [data-theme="dark"] #searchOverlayHints { border-color: #2d3a52; color: #7a8aaa; }
            #searchOverlayHints a {
                color: #003d82; background: #e8f0fe; border-radius: 20px;
                padding: 3px 12px; text-decoration: none; font-weight: 600;
                transition: background 0.15s;
            }
            [data-theme="dark"] #searchOverlayHints a { color: #7eb4ff; background: #1a2a44; }
            #searchOverlayHints a:hover { background: #cce0ff; }
        </style>

        <script>
            function openSearchOverlay() {
                var overlay = document.getElementById('searchOverlay');
                var input   = document.getElementById('searchOverlayInput');
                if (!overlay) return;
                overlay.style.display = 'flex';
                document.body.style.overflow = 'hidden';
                setTimeout(function(){ if(input) input.focus(); }, 80);
            }
            function closeSearchOverlay() {
                var overlay = document.getElementById('searchOverlay');
                if (!overlay) return;
                overlay.style.display = 'none';
                document.body.style.overflow = '';
            }
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeSearchOverlay();
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    openSearchOverlay();
                }
            });
        </script>

        @unless(session('from_admin_site_preview'))
            @include('partials.live-chat-widget')
        @endunless

        @stack('scripts')
    </body>
    </html>
