<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Attendance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --sidebar-w: 240px;
            --bg: #0f172a;
            --sidebar-bg: #111827;
            --surface: #1e293b;
            --surface-hover: #253348;
            --accent: #4f46e5;
            --accent-hover: #4338ca;
            --border: rgba(255,255,255,0.08);
            --border-light: rgba(255,255,255,0.05);
            --text: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            font-size: 14px;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: transform .25s ease;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: .7rem;
            padding: 1.25rem 1.15rem;
            border-bottom: 1px solid var(--border);
            text-decoration: none;
            flex-shrink: 0;
        }

        .brand-icon {
            width: 32px; height: 32px;
            background: var(--accent);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: .9rem;
            flex-shrink: 0;
        }

        .brand-text {
            font-size: .875rem;
            font-weight: 700;
            color: var(--text);
            line-height: 1.2;
        }

        .brand-sub {
            font-size: .65rem;
            color: var(--text-muted);
            font-weight: 400;
        }

        .sidebar-nav {
            flex: 1;
            padding: .75rem;
            overflow-y: auto;
        }

        .nav-section-label {
            font-size: .625rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--text-muted);
            padding: 0 .5rem;
            margin: 1rem 0 .35rem;
        }

        .nav-section-label:first-child { margin-top: .25rem; }

        .nav-item-link {
            display: flex;
            align-items: center;
            gap: .6rem;
            padding: .5rem .625rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: .8125rem;
            font-weight: 500;
            color: var(--text-secondary);
            transition: background .15s, color .15s;
            margin-bottom: 1px;
            position: relative;
        }

        .nav-item-link:hover {
            background: rgba(255,255,255,.05);
            color: var(--text);
        }

        .nav-item-link.active {
            background: rgba(79,70,229,.15);
            color: var(--text);
            font-weight: 600;
        }

        .nav-item-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 6px; bottom: 6px;
            width: 3px;
            background: var(--accent);
            border-radius: 0 2px 2px 0;
        }

        .nav-icon {
            width: 26px; height: 26px;
            border-radius: 5px;
            display: flex; align-items: center; justify-content: center;
            font-size: .8rem;
            flex-shrink: 0;
            background: rgba(255,255,255,.05);
        }

        .nav-item-link.active .nav-icon {
            background: rgba(79,70,229,.2);
        }

        .sidebar-footer {
            padding: .75rem;
            border-top: 1px solid var(--border);
            flex-shrink: 0;
        }

        .admin-badge, .user-card {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .5rem .6rem;
            background: rgba(255,255,255,.04);
            border: 1px solid var(--border);
            border-radius: 7px;
            margin-bottom: .5rem;
        }

        .admin-av, .user-av {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: var(--accent);
            display: flex; align-items: center; justify-content: center;
            font-size: .7rem;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .admin-name, .user-name {
            font-size: .75rem;
            font-weight: 600;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .admin-role, .user-role {
            font-size: .625rem;
            color: var(--text-muted);
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .45rem .6rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: .78rem;
            font-weight: 500;
            color: #f87171;
            transition: background .15s;
            width: 100%;
        }

        .logout-btn:hover {
            background: rgba(248,113,113,.08);
            color: #f87171;
        }

        /* ===== MAIN WRAPPER ===== */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            flex: 1;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            display: none;
            align-items: center;
            justify-content: space-between;
            padding: .75rem 1rem;
            background: var(--sidebar-bg);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-brand {
            font-size: .875rem;
            font-weight: 700;
            color: var(--text);
        }

        .topbar-toggle {
            background: rgba(255,255,255,.06);
            border: 1px solid var(--border);
            border-radius: 6px;
            padding: .3rem .5rem;
            cursor: pointer;
            color: var(--text-secondary);
            font-size: .9rem;
            line-height: 1;
            transition: background .15s;
        }

        .topbar-toggle:hover { background: rgba(255,255,255,.1); }

        .main-content {
            padding: 1.75rem 2rem;
            flex: 1;
            max-width: 1280px;
            width: 100%;
        }

        /* ===== ALERTS ===== */
        .alert-glass, .alert-strip {
            display: flex;
            align-items: flex-start;
            gap: .5rem;
            padding: .7rem 1rem;
            border-radius: 8px;
            border: 1px solid;
            font-size: .8125rem;
            margin-bottom: 1rem;
        }

        .alert-success-g, .alert-success-s {
            background: rgba(16,185,129,.08);
            border-color: rgba(16,185,129,.2);
            color: #34d399;
        }

        .alert-danger-g, .alert-danger-s {
            background: rgba(239,68,68,.08);
            border-color: rgba(239,68,68,.2);
            color: #fca5a5;
        }

        .alert-output-g, .alert-output-s {
            background: rgba(79,70,229,.08);
            border-color: rgba(79,70,229,.2);
            color: #a5b4fc;
        }

        /* ===== DESIGN TOKENS ===== */

        /* Cards */
        .glass-card, .card-box {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
        }

        .glass-card-header, .card-box-header {
            padding: .875rem 1.25rem;
            border-bottom: 1px solid var(--border);
            font-size: .8125rem;
            font-weight: 600;
            color: var(--text);
        }

        .glass-card-body, .card-box-body {
            padding: 1.25rem;
        }

        /* Page header */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: .75rem;
        }

        .page-title-group {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .page-icon {
            width: 36px; height: 36px;
            background: var(--accent);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .page-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text);
            margin: 0;
            letter-spacing: -.02em;
        }

        .page-subtitle {
            font-size: .75rem;
            color: var(--text-secondary);
            margin: 0;
        }

        /* Buttons */
        .btn-modern {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .45rem 1rem;
            border-radius: 7px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            font-size: .8125rem;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: background .15s, box-shadow .15s;
            line-height: 1.4;
        }

        .btn-primary-modern {
            background: var(--accent);
            color: #fff;
        }
        .btn-primary-modern:hover { background: var(--accent-hover); color: #fff; }

        .btn-success-modern {
            background: var(--success);
            color: #fff;
        }
        .btn-success-modern:hover { background: #059669; color: #fff; }

        .btn-danger-modern {
            background: var(--danger);
            color: #fff;
        }
        .btn-danger-modern:hover { background: #dc2626; color: #fff; }

        .btn-warning-modern {
            background: var(--warning);
            color: #fff;
        }
        .btn-warning-modern:hover { background: #d97706; color: #fff; }

        .btn-secondary-modern {
            background: rgba(255,255,255,.06);
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }
        .btn-secondary-modern:hover { background: rgba(255,255,255,.1); color: var(--text); }

        /* Forms */
        .form-control, .form-select {
            background: rgba(255,255,255,.04);
            border: 1px solid var(--border);
            border-radius: 7px;
            color: var(--text);
            padding: .5rem .75rem;
            font-family: 'Inter', sans-serif;
            font-size: .8125rem;
            transition: border-color .15s, box-shadow .15s;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(79,70,229,.06);
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(79,70,229,.1);
            outline: none;
            color: var(--text);
        }

        .form-control::placeholder { color: var(--text-muted); }
        .form-label { font-size: .75rem; font-weight: 600; color: var(--text-secondary); margin-bottom: .3rem; }

        /* Modal overrides for dark theme */
        .modal-content {
            background: var(--surface) !important;
            border: 1px solid var(--border) !important;
            border-radius: 12px !important;
            color: var(--text) !important;
        }
        .modal-header { border-bottom: 1px solid var(--border) !important; }
        .modal-footer { border-top: 1px solid var(--border) !important; }
        .btn-close-white { filter: invert(1) grayscale(100%) brightness(200%); }

        /* Responsive */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.5);
            z-index: 99;
        }
        .sidebar-overlay.show { display: block; }

        @media (max-width: 900px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 4px 0 24px rgba(0,0,0,.4); }
            .main-wrapper { margin-left: 0; }
            .topbar { display: flex; }
            .main-content { padding: 1.25rem 1rem; }
        }
    </style>
</head>
<body>

<aside class="sidebar" id="sidebar">
    <a class="sidebar-brand" href="{{ session('admin_logged_in') ? route('dashboard') : (session('mhs_logged_in') ? route('mhs.absen') : route('mhs.absen')) }}">
        <div class="brand-icon">🎓</div>
        <div>
            <div class="brand-text">FaceAttend</div>
            <div class="brand-sub">Sistem Absensi Wajah</div>
        </div>
    </a>
    <nav class="sidebar-nav">
    @if(session('admin_logged_in'))

        <div class="nav-section-label">Administrasi</div>

        <a href="{{ route('dashboard') }}"
           class="nav-item-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <div class="nav-icon">🏠</div>
            Dashboard
        </a>

        <a href="{{ route('mahasiswa.index') }}"
           class="nav-item-link {{ request()->routeIs('mahasiswa.*') ? 'active' : '' }}">
            <div class="nav-icon">👥</div>
            Mahasiswa
        </a>

        <a href="{{ route('absensi.index') }}"
           class="nav-item-link {{ request()->routeIs('absensi.*') ? 'active' : '' }}">
            <div class="nav-icon">📋</div>
            Data Absensi
        </a>



    @elseif(session('mhs_logged_in'))

        <div class="nav-section-label">Mahasiswa</div>

        <a href="{{ route('mhs.absen') }}"
           class="nav-item-link {{ request()->routeIs('mhs.absen') ? 'active' : '' }}">
            <div class="nav-icon">📸</div>
            Portal Absensi
        </a>

        <a href="{{ route('mhs.riwayat') }}"
           class="nav-item-link {{ request()->routeIs('mhs.riwayat') ? 'active' : '' }}">
            <div class="nav-icon">📜</div>
            Riwayat Kehadiran
        </a>

    @else

        <div class="nav-section-label">Akses Sistem</div>

        <a href="{{ route('login') }}"
           class="nav-item-link {{ request()->routeIs('login') ? 'active' : '' }}">
            <div class="nav-icon">🔐</div>
            Login / Masuk
        </a>

    @endif
    </nav>

    @if(session('admin_logged_in'))
        <div class="sidebar-footer">
            <div class="admin-badge">
                <div class="admin-av">A</div>
                <div style="min-width:0;">
                    <div class="admin-name">Administrator</div>
                    <div class="admin-role">admin@kampus.com</div>
                </div>
            </div>
            <a href="{{ route('logout') }}" class="logout-btn">
                <span style="font-size:.85rem;">🚪</span> Keluar
            </a>
        </div>
    @elseif(session('mhs_logged_in'))
        <div class="sidebar-footer">
            <div class="admin-badge">
                <div class="admin-av">{{ strtoupper(substr(session('mhs_nama'), 0, 1)) }}</div>
                <div style="min-width:0;">
                    <div class="admin-name">{{ session('mhs_nama') }}</div>
                    <div class="admin-role">{{ session('mhs_nim') }}</div>
                </div>
            </div>
            <a href="{{ route('logout') }}" class="logout-btn">
                <span style="font-size:.85rem;">🚪</span> Keluar
            </a>
        </div>
    @endif
    </aside>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="main-wrapper">
    <div class="topbar">
        <span class="topbar-brand">🎓 FaceAttend</span>
        <button class="topbar-toggle" onclick="toggleSidebar()">☰</button>
    </div>
    <div class="main-content">
        @if(session('success'))
            <div class="alert-glass alert-success-g"><span>✅</span><span>{{ session('success') }}</span></div>
        @endif
        @if(session('error'))
            <div class="alert-glass alert-danger-g"><span>⚠️</span><span>{{ session('error') }}</span></div>
        @endif
        @if($errors->any())
            <div class="alert-glass alert-danger-g">
                <span>⚠️</span>
                <div>
                    <strong>Terjadi Kesalahan Input:</strong>
                    <ul class="mb-0 mt-1" style="padding-left: 1.2rem; font-size: 0.8rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        @if(session('output'))
            <div class="alert-glass alert-output-g"><span>💻</span><pre class="mb-0" style="white-space:pre-wrap;font-size:.82rem;">{{ session('output') }}</pre></div>
        @endif
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar(){document.getElementById('sidebar').classList.toggle('open');document.getElementById('sidebarOverlay').classList.toggle('show')}
    function closeSidebar(){document.getElementById('sidebar').classList.remove('open');document.getElementById('sidebarOverlay').classList.remove('show')}
</script>

@stack('modals')

</body>
</html>