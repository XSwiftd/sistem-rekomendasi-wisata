<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NusaTripRec — Sistem Rekomendasi Wisata Indonesia')</title>

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Typography: Instrument Serif (editorial headings) + Plus Jakarta Sans (body) -->
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        /* ============================================
           DESIGN SYSTEM — NusaTripRec Premium
           Indonesian Heritage × Modern Minimalist
           ============================================ */

        :root {
            /* Color Palette */
            --navy:          #0B1D3A;
            --navy-mid:      #152D50;
            --navy-light:    #1E3A5F;
            --terracotta:    #C4653A;
            --terracotta-dk: #A8522C;
            --terracotta-lt: #E8946B;
            --ivory:         #FAF8F5;
            --ivory-warm:    #F5F0EA;
            --sand:          #C9A96E;
            --sand-light:    #E3D5B8;
            --sand-muted:    #D4C5A9;
            --white:         #FFFFFF;
            --slate:         #2D3748;
            --slate-light:   #4A5568;
            --muted:         #94A3B8;
            --muted-light:   #CBD5E1;
            --border:        rgba(11, 29, 58, 0.07);

            /* Shadows — multi-layered, diffused */
            --shadow-xs:  0 1px 2px rgba(11,29,58,0.04);
            --shadow-sm:  0 2px 8px -2px rgba(11,29,58,0.06), 0 1px 3px rgba(11,29,58,0.04);
            --shadow-md:  0 8px 24px -4px rgba(11,29,58,0.08), 0 2px 8px -2px rgba(11,29,58,0.03);
            --shadow-lg:  0 20px 48px -8px rgba(11,29,58,0.10), 0 4px 16px -4px rgba(11,29,58,0.04);
            --shadow-xl:  0 32px 64px -12px rgba(11,29,58,0.14), 0 8px 24px -6px rgba(11,29,58,0.05);
            --shadow-warm: 0 20px 48px -8px rgba(196,101,58,0.12), 0 4px 16px rgba(196,101,58,0.04);

            /* Radii */
            --radius-sm:   12px;
            --radius-md:   20px;
            --radius-lg:   24px;
            --radius-xl:   32px;
            --radius-full: 100px;

            /* Motion */
            --ease-out:    cubic-bezier(0.16, 1, 0.3, 1);
            --ease-smooth: cubic-bezier(0.4, 0, 0.2, 1);
            --duration:    0.45s;
            --duration-fast: 0.25s;
        }

        /* ---- Base & Typography ---- */

        *, *::before, *::after { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--ivory);
            color: var(--slate);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-size: 0.95rem;
            line-height: 1.65;
        }

        h1, h2, h3, h4, .font-editorial {
            font-family: 'Instrument Serif', Georgia, 'Times New Roman', serif;
            font-weight: 400;
            color: var(--navy);
            letter-spacing: -0.02em;
            line-height: 1.15;
        }

        h5, h6 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            color: var(--navy);
            letter-spacing: -0.01em;
        }

        .font-sans { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif { font-family: 'Instrument Serif', Georgia, serif; }

        a { color: var(--terracotta); transition: color var(--duration-fast) var(--ease-smooth); }
        a:hover { color: var(--terracotta-dk); }

        ::selection { background: var(--sand-light); color: var(--navy); }

        /* ---- Floating Glass Navbar ---- */

        .navbar-nusa {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1050;
            padding: 0;
            transition: all 0.4s var(--ease-smooth);
        }

        .navbar-inner {
            max-width: 1320px;
            margin: 16px auto;
            padding: 10px 28px;
            border-radius: var(--radius-full);
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border: 1px solid rgba(11, 29, 58, 0.06);
            box-shadow: var(--shadow-md);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            transition: all 0.4s var(--ease-smooth);
        }

        .navbar-nusa.scrolled .navbar-inner {
            margin-top: 8px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: var(--shadow-lg);
        }

        /* Brand */
        .brand-nusa {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            flex-shrink: 0;
        }
        .brand-nusa:hover { text-decoration: none; }
        .brand-mark {
            width: 38px;
            height: 38px;
            border-radius: var(--radius-sm);
            background: linear-gradient(135deg, var(--navy), var(--navy-mid));
            color: var(--sand);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            box-shadow: 0 4px 12px rgba(11,29,58,0.2);
        }
        .brand-wordmark {
            font-family: 'Instrument Serif', serif;
            font-size: 1.45rem;
            color: var(--navy);
            line-height: 1;
            letter-spacing: -0.03em;
        }
        .brand-wordmark em {
            font-style: italic;
            color: var(--terracotta);
        }

        /* Nav Links */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .nav-link-nusa {
            color: var(--slate-light);
            font-weight: 500;
            font-size: 0.88rem;
            padding: 8px 18px;
            border-radius: var(--radius-full);
            text-decoration: none;
            transition: all var(--duration-fast) var(--ease-smooth);
            white-space: nowrap;
        }
        .nav-link-nusa:hover {
            color: var(--navy);
            background: rgba(11, 29, 58, 0.04);
        }
        .nav-link-nusa.active {
            color: var(--terracotta);
            background: rgba(196, 101, 58, 0.08);
            font-weight: 600;
        }

        /* User Switcher */
        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 5px 16px 5px 5px;
            border-radius: var(--radius-full);
            border: 1px solid var(--border);
            background: var(--white);
            cursor: pointer;
            transition: all var(--duration-fast) var(--ease-smooth);
            flex-shrink: 0;
        }
        .user-pill:hover {
            border-color: var(--sand);
            box-shadow: var(--shadow-sm);
        }
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--terracotta), var(--sand));
            color: var(--white);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.78rem;
            letter-spacing: 0.02em;
        }
        .user-pill-info {
            text-align: left;
            line-height: 1.15;
        }
        .user-pill-label {
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--muted);
            font-weight: 600;
        }
        .user-pill-name {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--navy);
        }

        /* User Dropdown */
        .user-dropdown {
            border: none;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-xl);
            padding: 8px;
            min-width: 280px;
            max-height: 380px;
            overflow-y: auto;
            background: var(--white);
        }
        .user-dropdown .dropdown-header {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--muted);
            font-weight: 700;
            padding: 10px 14px 6px;
        }
        .user-dropdown .dropdown-item {
            border-radius: var(--radius-sm);
            padding: 10px 14px;
            font-size: 0.88rem;
            transition: all var(--duration-fast) var(--ease-smooth);
            margin-bottom: 2px;
        }
        .user-dropdown .dropdown-item:hover {
            background: var(--ivory);
        }
        .user-dropdown .dropdown-item.is-active {
            background: rgba(196, 101, 58, 0.08);
            color: var(--terracotta);
        }

        /* Navbar spacer */
        .nav-spacer { height: 88px; }

        /* Mobile navbar */
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            padding: 6px;
            color: var(--navy);
            font-size: 1.4rem;
            cursor: pointer;
        }
        .mobile-nav-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 1040;
            background: rgba(11, 29, 58, 0.3);
            backdrop-filter: blur(4px);
        }
        .mobile-nav-drawer {
            display: none;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            width: min(340px, 85vw);
            z-index: 1060;
            background: var(--white);
            box-shadow: -20px 0 60px rgba(11,29,58,0.15);
            padding: 28px;
            overflow-y: auto;
            animation: slideInRight 0.4s var(--ease-out);
        }
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @media (max-width: 767.98px) {
            .navbar-inner { margin: 10px 12px; padding: 8px 16px; }
            .desktop-nav, .desktop-user { display: none !important; }
            .mobile-toggle { display: block; }
            .nav-spacer { height: 72px; }
        }

        /* ---- Flash Messages ---- */

        .flash-toast {
            border: none;
            border-radius: var(--radius-md);
            padding: 16px 24px;
            box-shadow: var(--shadow-md);
            font-weight: 500;
            font-size: 0.9rem;
            animation: slideDown 0.5s var(--ease-out);
        }
        .flash-toast.success {
            background: linear-gradient(135deg, #ECFDF5, #D1FAE5);
            color: #065F46;
            border-left: 4px solid #059669;
        }
        .flash-toast.error {
            background: linear-gradient(135deg, #FEF2F2, #FECACA);
            color: #991B1B;
            border-left: 4px solid #DC2626;
        }
        @keyframes slideDown {
            from { transform: translateY(-16px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* ---- Buttons ---- */

        .btn-terra {
            background: var(--terracotta);
            color: var(--white);
            border: none;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: var(--radius-full);
            box-shadow: 0 4px 14px rgba(196,101,58,0.25);
            transition: all var(--duration-fast) var(--ease-out);
        }
        .btn-terra:hover {
            background: var(--terracotta-dk);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(196,101,58,0.3);
        }

        .btn-terra-outline {
            color: var(--terracotta);
            border: 1.5px solid rgba(196,101,58,0.3);
            background: transparent;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: var(--radius-full);
            transition: all var(--duration-fast) var(--ease-out);
        }
        .btn-terra-outline:hover {
            background: var(--terracotta);
            border-color: var(--terracotta);
            color: var(--white);
            box-shadow: var(--shadow-warm);
        }

        .btn-navy {
            background: var(--navy);
            color: var(--white);
            border: none;
            font-weight: 600;
            padding: 10px 24px;
            border-radius: var(--radius-full);
            box-shadow: 0 4px 14px rgba(11,29,58,0.2);
            transition: all var(--duration-fast) var(--ease-out);
        }
        .btn-navy:hover {
            background: var(--navy-mid);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(11,29,58,0.25);
        }

        /* ---- Cards — shared premium style ---- */

        .card-nusa {
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            background: var(--white);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: transform var(--duration) var(--ease-out),
                        box-shadow var(--duration) var(--ease-out);
        }
        .card-nusa:hover {
            transform: translateY(-6px);
            box-shadow: var(--shadow-lg);
        }
        .card-nusa .card-visual {
            overflow: hidden;
            position: relative;
        }
        .card-nusa .card-visual img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.7s var(--ease-out);
        }
        .card-nusa:hover .card-visual img {
            transform: scale(1.06);
        }
        .card-nusa .card-visual::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(0deg, rgba(11,29,58,0.5) 0%, rgba(11,29,58,0.1) 40%, transparent 65%);
            pointer-events: none;
        }

        /* ---- Pagination ---- */

        .pagination .page-link {
            border: none;
            border-radius: var(--radius-sm);
            margin: 0 3px;
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--slate-light);
            transition: all var(--duration-fast) var(--ease-smooth);
            padding: 8px 14px;
        }
        .pagination .page-link:hover {
            background: var(--ivory-warm);
            color: var(--navy);
        }
        .pagination .page-item.active .page-link {
            background: var(--navy);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(11,29,58,0.2);
        }

        /* ---- Footer ---- */

        .footer-nusa {
            margin-top: auto;
            background: var(--navy);
            padding: 64px 0 40px;
        }
        .footer-brand {
            font-family: 'Instrument Serif', serif;
            font-size: 1.8rem;
            color: var(--white);
            letter-spacing: -0.03em;
        }
        .footer-brand em { color: var(--sand); font-style: italic; }
        .footer-divider {
            width: 48px;
            height: 2px;
            background: var(--sand);
            opacity: 0.4;
            border: none;
            margin: 20px 0;
        }
        .footer-text {
            color: var(--muted);
            font-size: 0.85rem;
            line-height: 1.7;
            max-width: 420px;
        }
        .footer-tag {
            display: inline-block;
            padding: 5px 14px;
            border-radius: var(--radius-full);
            border: 1px solid rgba(255,255,255,0.1);
            color: var(--muted);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.3px;
        }
        .footer-tag.accent { border-color: rgba(201,169,110,0.3); color: var(--sand); }

        /* ---- Form Overrides ---- */

        .form-control, .form-select {
            border: 1.5px solid rgba(11,29,58,0.1);
            border-radius: var(--radius-sm);
            padding: 11px 16px;
            font-size: 0.9rem;
            transition: all var(--duration-fast) var(--ease-smooth);
            background-color: var(--white);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--terracotta);
            box-shadow: 0 0 0 4px rgba(196,101,58,0.08);
            background-color: var(--white);
        }

        /* ---- Utility ---- */

        .text-terra { color: var(--terracotta) !important; }
        .text-navy { color: var(--navy) !important; }
        .text-sand { color: var(--sand) !important; }
        .bg-ivory { background-color: var(--ivory) !important; }
        .bg-ivory-warm { background-color: var(--ivory-warm) !important; }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--muted-light); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--muted); }
    </style>
    @stack('styles')
</head>
<body>

    <!-- ====== FLOATING GLASS NAVBAR ====== -->
    <nav class="navbar-nusa" id="mainNav">
        <div class="navbar-inner">
            <!-- Brand -->
            <a class="brand-nusa" href="{{ url('/') }}">
                <div class="brand-mark"><i class="bi bi-compass"></i></div>
                <span class="brand-wordmark">NusaTrip<em>Rec</em></span>
            </a>

            <!-- Desktop Nav Links -->
            <ul class="nav-links desktop-nav">
                <li>
                    <a class="nav-link-nusa {{ request()->is('/') || request()->is('wisata*') ? 'active' : '' }}" href="{{ url('/') }}">
                        <i class="bi bi-compass me-1"></i> Jelajahi
                    </a>
                </li>
                <li>
                    <a class="nav-link-nusa {{ request()->is('bookmarks*') ? 'active' : '' }}" href="{{ url('/bookmarks') }}">
                        <i class="bi bi-bookmark me-1"></i> Tersimpan
                    </a>
                </li>
            </ul>

            <!-- User Switcher (Desktop) -->
            @php
                $activeUserId = session('active_user_id', 1);
                $allUsers = \App\Models\User::select('user_id', 'nama_pengguna', 'minat_kategori')->limit(15)->get();
                $currentUser = $allUsers->firstWhere('user_id', $activeUserId) ?? $allUsers->first();
                $initials = $currentUser ? strtoupper(mb_substr($currentUser->nama_pengguna, 0, 1)) : 'U';
            @endphp

            <div class="dropdown desktop-user">
                <button class="user-pill dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <div class="user-avatar">{{ $initials }}</div>
                    <div class="user-pill-info">
                        <div class="user-pill-label">Persona Aktif</div>
                        <div class="user-pill-name">{{ $currentUser ? $currentUser->nama_pengguna : 'User #1' }}</div>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end user-dropdown mt-2">
                    <li><span class="dropdown-header">Pilih Persona Uji</span></li>
                    @foreach($allUsers as $u)
                        <li>
                            <a class="dropdown-item d-flex justify-content-between align-items-center {{ $u->user_id == $activeUserId ? 'is-active' : '' }}"
                               href="{{ url('/switch-user/' . $u->user_id) }}">
                                <div class="text-truncate pe-2">
                                    <div class="fw-semibold" style="font-size: 0.88rem;">{{ $u->nama_pengguna }}</div>
                                    <small class="text-muted" style="font-size: 0.72rem;">{{ $u->minat_kategori }}</small>
                                </div>
                                <span class="badge {{ $u->user_id == $activeUserId ? 'text-white' : 'bg-light text-muted border' }} rounded-pill"
                                      style="{{ $u->user_id == $activeUserId ? 'background: var(--terracotta);' : '' }}">
                                    #{{ $u->user_id }}
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Mobile Toggle -->
            <button class="mobile-toggle" onclick="toggleMobileNav()" aria-label="Menu">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </nav>

    <!-- Mobile Nav Drawer -->
    <div class="mobile-nav-overlay" id="mobileOverlay" onclick="toggleMobileNav()"></div>
    <div class="mobile-nav-drawer" id="mobileDrawer">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <span class="brand-wordmark">NusaTrip<em>Rec</em></span>
            <button class="mobile-toggle" onclick="toggleMobileNav()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="d-flex flex-column gap-2 mb-4">
            <a class="nav-link-nusa {{ request()->is('/') || request()->is('wisata*') ? 'active' : '' }}" href="{{ url('/') }}" style="font-size: 1rem; padding: 12px 18px;">
                <i class="bi bi-compass me-2"></i> Jelajahi Destinasi
            </a>
            <a class="nav-link-nusa {{ request()->is('bookmarks*') ? 'active' : '' }}" href="{{ url('/bookmarks') }}" style="font-size: 1rem; padding: 12px 18px;">
                <i class="bi bi-bookmark me-2"></i> Koleksi Tersimpan
            </a>
        </div>
        <hr style="border-color: var(--border);">
        <p class="user-pill-label mb-2">Persona Aktif</p>
        <div class="d-flex flex-column gap-1" style="max-height: 240px; overflow-y: auto;">
            @foreach($allUsers as $u)
                <a class="dropdown-item d-flex justify-content-between align-items-center rounded-3 py-2 {{ $u->user_id == $activeUserId ? 'is-active' : '' }}"
                   href="{{ url('/switch-user/' . $u->user_id) }}" style="font-size: 0.88rem;">
                    <span class="text-truncate">{{ $u->nama_pengguna }}</span>
                    <span class="badge rounded-pill {{ $u->user_id == $activeUserId ? 'text-white' : 'bg-light text-muted border' }}"
                          style="{{ $u->user_id == $activeUserId ? 'background: var(--terracotta);' : '' }}">
                        #{{ $u->user_id }}
                    </span>
                </a>
            @endforeach
        </div>
    </div>

    <!-- Navbar Spacer -->
    <div class="nav-spacer"></div>

    <!-- Flash Messages -->
    <div class="container mt-3">
        @if(session('success'))
            <div class="flash-toast success d-flex align-items-center" role="alert">
                <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                <div class="flex-grow-1">{{ session('success') }}</div>
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Tutup" onclick="this.parentElement.remove()"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="flash-toast error d-flex align-items-center" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div class="flex-grow-1">{{ session('error') }}</div>
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Tutup" onclick="this.parentElement.remove()"></button>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="flex-grow-1" style="padding-top: 12px; padding-bottom: 48px;">
        @yield('content')
    </main>

    <!-- ====== FOOTER ====== -->
    <footer class="footer-nusa">
        <div class="container">
            <div class="row gy-4 align-items-start">
                <div class="col-lg-5">
                    <div class="footer-brand">NusaTrip<em>Rec</em></div>
                    <hr class="footer-divider">
                    <p class="footer-text">
                        Eksplorasi cerdas keindahan Nusantara — rekomendasi wisata yang dipersonalisasi
                        menggunakan Content-Based Filtering dengan TF-IDF & Cosine Similarity.
                    </p>
                </div>
                <div class="col-lg-3 offset-lg-1">
                    <p class="user-pill-label mb-3" style="color: var(--muted);">Navigasi</p>
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ url('/') }}" class="text-decoration-none" style="color: var(--muted-light); font-size: 0.9rem;">Jelajahi Destinasi</a>
                        <a href="{{ url('/bookmarks') }}" class="text-decoration-none" style="color: var(--muted-light); font-size: 0.9rem;">Koleksi Tersimpan</a>
                    </div>
                </div>
                <div class="col-lg-3">
                    <p class="user-pill-label mb-3" style="color: var(--muted);">Teknologi</p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="footer-tag">Laravel</span>
                        <span class="footer-tag">Bootstrap 5</span>
                        <span class="footer-tag accent">Python API</span>
                        <span class="footer-tag accent">TF-IDF</span>
                    </div>
                </div>
            </div>
            <hr style="border-color: rgba(255,255,255,0.06); margin: 40px 0 20px;">
            <p class="text-center mb-0" style="color: rgba(148,163,184,0.5); font-size: 0.8rem;">
                © {{ date('Y') }} NusaTripRec — Tugas Kelompok Sistem Rekomendasi
            </p>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Navbar scroll effect
        const nav = document.getElementById('mainNav');
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 32);
        }, { passive: true });

        // Mobile nav
        function toggleMobileNav() {
            const overlay = document.getElementById('mobileOverlay');
            const drawer = document.getElementById('mobileDrawer');
            const isOpen = drawer.style.display === 'block';
            overlay.style.display = isOpen ? 'none' : 'block';
            drawer.style.display = isOpen ? 'none' : 'block';
            document.body.style.overflow = isOpen ? '' : 'hidden';
        }

        // Auto-dismiss flash
        document.querySelectorAll('.flash-toast').forEach(el => {
            setTimeout(() => { el.style.opacity = '0'; el.style.transform = 'translateY(-10px)'; setTimeout(() => el.remove(), 400); }, 4000);
        });
    </script>
    @stack('scripts')
</body>
</html>
