<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Face Attendance System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg:#0a0818; --sidebar-bg:rgba(15,12,41,0.95);
            --sidebar-w:260px; --accent:#a78bfa; --accent-dark:#7c3aed;
            --glass-bg:rgba(255,255,255,0.04); --glass-border:rgba(255,255,255,0.08);
            --text:#e2e8f0; --muted:rgba(255,255,255,0.45);
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Inter',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:flex}
        body::before,body::after{content:'';position:fixed;border-radius:50%;filter:blur(100px);pointer-events:none;z-index:0}
        body::before{width:500px;height:500px;background:radial-gradient(circle,rgba(124,58,237,.18),transparent 70%);top:-150px;left:-100px;animation:driftA 12s ease-in-out infinite alternate}
        body::after{width:400px;height:400px;background:radial-gradient(circle,rgba(6,182,212,.1),transparent 70%);bottom:-100px;right:-80px;animation:driftB 15s ease-in-out infinite alternate}
        @keyframes driftA{to{transform:translate(40px,60px)}}
        @keyframes driftB{to{transform:translate(-40px,-50px)}}

        /* SIDEBAR */
        .sidebar{position:fixed;top:0;left:0;bottom:0;width:var(--sidebar-w);background:var(--sidebar-bg);backdrop-filter:blur(20px);border-right:1px solid var(--glass-border);display:flex;flex-direction:column;z-index:100;transition:transform .3s ease}
        .sidebar-brand{display:flex;align-items:center;gap:.75rem;padding:1.75rem 1.5rem 1.5rem;border-bottom:1px solid var(--glass-border);text-decoration:none}
        .brand-icon{width:40px;height:40px;background:linear-gradient(135deg,var(--accent-dark),var(--accent));border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;box-shadow:0 6px 18px rgba(124,58,237,.45);flex-shrink:0}
        .brand-text{font-size:1rem;font-weight:800;line-height:1.2;background:linear-gradient(135deg,#fff,var(--accent));-webkit-background-clip:text;-webkit-text-fill-color:transparent}
        .brand-sub{font-size:.68rem;color:var(--muted);font-weight:400;-webkit-text-fill-color:var(--muted)}
        .sidebar-nav{flex:1;padding:1.25rem .75rem;overflow-y:auto}
        .nav-section-label{font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:1.2px;color:rgba(255,255,255,.2);padding:0 .75rem;margin-bottom:.5rem;margin-top:1rem}
        .nav-section-label:first-child{margin-top:0}
        .nav-item-link{display:flex;align-items:center;gap:.75rem;padding:.7rem .9rem;border-radius:12px;text-decoration:none;font-size:.875rem;font-weight:500;color:var(--muted);transition:all .25s ease;position:relative;margin-bottom:2px}
        .nav-item-link:hover{background:rgba(255,255,255,.06);color:#fff}
        .nav-item-link.active{background:rgba(167,139,250,.12);color:var(--accent);font-weight:600}
        .nav-item-link.active::before{content:'';position:absolute;left:0;top:25%;bottom:25%;width:3px;background:var(--accent);border-radius:0 4px 4px 0;box-shadow:0 0 8px var(--accent)}
        .nav-icon{width:32px;height:32px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:.95rem;flex-shrink:0;background:rgba(255,255,255,.05);transition:background .25s}
        .nav-item-link:hover .nav-icon,.nav-item-link.active .nav-icon{background:rgba(167,139,250,.15)}
        .sidebar-footer{padding:1rem .75rem;border-top:1px solid var(--glass-border)}
        .logout-btn{display:flex;align-items:center;gap:.75rem;padding:.7rem .9rem;border-radius:12px;text-decoration:none;font-size:.875rem;font-weight:500;color:rgba(248,113,113,.7);transition:all .25s;width:100%}
        .logout-btn:hover{background:rgba(248,113,113,.08);color:#f87171}
        .admin-badge{display:flex;align-items:center;gap:.6rem;padding:.75rem;background:var(--glass-bg);border:1px solid var(--glass-border);border-radius:12px;margin-bottom:.75rem}
        .admin-av{width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--accent-dark),var(--accent));display:flex;align-items:center;justify-content:center;font-size:.85rem;font-weight:700;color:#fff;flex-shrink:0}
        .admin-name{font-size:.8rem;font-weight:600;color:#fff}
        .admin-role{font-size:.68rem;color:var(--muted)}

        /* MAIN */
        .main-wrapper{margin-left:var(--sidebar-w);flex:1;min-height:100vh;position:relative;z-index:1}
        .topbar{display:none;align-items:center;justify-content:space-between;padding:1rem 1.5rem;background:rgba(15,12,41,.8);backdrop-filter:blur(12px);border-bottom:1px solid var(--glass-border);position:sticky;top:0;z-index:50}
        .topbar-brand{font-size:1rem;font-weight:800;background:linear-gradient(135deg,#fff,var(--accent));-webkit-background-clip:text;-webkit-text-fill-color:transparent}
        .topbar-toggle{background:none;border:1px solid var(--glass-border);border-radius:9px;padding:.4rem .55rem;cursor:pointer;color:var(--muted);font-size:1rem;transition:all .2s}
        .topbar-toggle:hover{background:var(--glass-bg);color:#fff}
        .main-content{padding:2.5rem;max-width:1400px}

        /* ALERTS */
        .alert-glass{border-radius:14px;border:1px solid;padding:.9rem 1.2rem;margin-bottom:1.5rem;font-size:.875rem;display:flex;align-items:flex-start;gap:.6rem;animation:alertIn .4s cubic-bezier(.16,1,.3,1) both}
        @keyframes alertIn{from{opacity:0;transform:translateY(-10px)}to{opacity:1;transform:translateY(0)}}
        .alert-success-g{background:rgba(52,211,153,.08);border-color:rgba(52,211,153,.25);color:#6ee7b7}
        .alert-danger-g{background:rgba(248,113,113,.08);border-color:rgba(248,113,113,.25);color:#fca5a5}
        .alert-output-g{background:rgba(99,102,241,.08);border-color:rgba(99,102,241,.25);color:#a5b4fc}

        /* DESIGN TOKENS */
        .glass-card{background:rgba(255,255,255,.04);backdrop-filter:blur(16px);border:1px solid var(--glass-border);border-radius:20px;overflow:hidden}
        .glass-card-header{padding:1.25rem 1.75rem;border-bottom:1px solid rgba(255,255,255,.07);font-size:1rem;font-weight:600;color:#fff}
        .glass-card-body{padding:1.5rem 1.75rem}
        .page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;flex-wrap:wrap;gap:1rem}
        .page-title-group{display:flex;align-items:center;gap:1rem}
        .page-icon{width:50px;height:50px;background:linear-gradient(135deg,var(--accent),var(--accent-dark));border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;box-shadow:0 8px 20px rgba(124,58,237,.4);flex-shrink:0}
        .page-title{font-size:1.55rem;font-weight:800;color:#fff;margin:0;letter-spacing:-.5px}
        .page-subtitle{font-size:.85rem;color:var(--muted);margin:0}
        .form-control,.form-select{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:12px;color:#fff;padding:.7rem 1rem;font-family:'Inter',sans-serif;transition:all .3s}
        .form-control:focus,.form-select:focus{background:rgba(167,139,250,.1);border-color:var(--accent);box-shadow:0 0 0 4px rgba(167,139,250,.12);color:#fff}
        .form-control::placeholder{color:rgba(255,255,255,.3)}
        .form-label{font-size:.8rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:.4rem}
        .btn-modern{padding:.6rem 1.4rem;border-radius:12px;font-weight:600;font-family:'Inter',sans-serif;font-size:.875rem;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:.4rem;transition:all .3s ease;text-decoration:none}
        .btn-primary-modern{background:linear-gradient(135deg,var(--accent),var(--accent-dark));color:#fff;box-shadow:0 4px 14px rgba(124,58,237,.4)}
        .btn-primary-modern:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(124,58,237,.6);color:#fff}
        .btn-success-modern{background:linear-gradient(135deg,#10b981,#059669);color:#fff;box-shadow:0 4px 14px rgba(16,185,129,.4)}
        .btn-success-modern:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(16,185,129,.6);color:#fff}
        .btn-danger-modern{background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;box-shadow:0 4px 14px rgba(239,68,68,.35)}
        .btn-danger-modern:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(239,68,68,.55);color:#fff}
        .btn-warning-modern{background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;box-shadow:0 4px 14px rgba(245,158,11,.35)}
        .btn-warning-modern:hover{transform:translateY(-2px);box-shadow:0 6px 20px rgba(245,158,11,.55);color:#fff}
        .btn-secondary-modern{background:rgba(255,255,255,.08);color:var(--text);border:1px solid rgba(255,255,255,.14)}
        .btn-secondary-modern:hover{background:rgba(255,255,255,.13);color:#fff;transform:translateY(-2px)}

        @media(max-width:900px){
            .sidebar{transform:translateX(-100%)}
            .sidebar.open{transform:translateX(0);box-shadow:10px 0 40px rgba(0,0,0,.5)}
            .main-wrapper{margin-left:0}
            .topbar{display:flex}
            .main-content{padding:1.5rem}
        }
        .sidebar-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:99;backdrop-filter:blur(2px)}
        .sidebar-overlay.show{display:block}
    </style>
</head>
<body>

<aside class="sidebar" id="sidebar">
    <a class="sidebar-brand" href="<?php echo e(session('admin_logged_in') ? route('dashboard') : (session('mhs_logged_in') ? route('mhs.absen') : route('mhs.absen'))); ?>">
        <div class="brand-icon">🧑‍💻</div>
        <div>
            <div class="brand-text">FaceAttend</div>
            <div class="brand-sub">Sistem Absensi Wajah</div>
        </div>
    </a>
    <nav class="sidebar-nav">
    <?php if(session('admin_logged_in')): ?>

        <div class="nav-section-label">Admin</div>

        <a href="<?php echo e(route('dashboard')); ?>"
           class="nav-item-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
            <div class="nav-icon">🏠</div>
            Dashboard
        </a>

        <a href="<?php echo e(route('mahasiswa.index')); ?>"
           class="nav-item-link <?php echo e(request()->routeIs('mahasiswa.*') ? 'active' : ''); ?>">
            <div class="nav-icon">👥</div>
            Mahasiswa
        </a>

        <a href="<?php echo e(route('absensi.index')); ?>"
           class="nav-item-link <?php echo e(request()->routeIs('absensi.*') ? 'active' : ''); ?>">
            <div class="nav-icon">📋</div>
            Data Absensi
        </a>



    <?php elseif(session('mhs_logged_in')): ?>

        <div class="nav-section-label">Mahasiswa</div>

        <a href="<?php echo e(route('mhs.absen')); ?>"
           class="nav-item-link <?php echo e(request()->routeIs('mhs.absen') ? 'active' : ''); ?>">
            <div class="nav-icon">📸</div>
            Portal Absensi
        </a>

        <a href="<?php echo e(route('mhs.riwayat')); ?>"
           class="nav-item-link <?php echo e(request()->routeIs('mhs.riwayat') ? 'active' : ''); ?>">
            <div class="nav-icon">📜</div>
            Riwayat Kehadiran
        </a>

    <?php else: ?>

        <div class="nav-section-label">Akses Sistem</div>

        <a href="<?php echo e(route('login')); ?>"
           class="nav-item-link <?php echo e(request()->routeIs('login') ? 'active' : ''); ?>">
            <div class="nav-icon">🔐</div>
            Login / Masuk
        </a>

    <?php endif; ?>
    </nav>

    <?php if(session('admin_logged_in')): ?>
        <div class="sidebar-footer">
            <div class="admin-badge">
                <div class="admin-av">A</div>
                <div>
                    <div class="admin-name">Administrator</div>
                    <div class="admin-role">admin@kampus.com</div>
                </div>
            </div>
            <a href="<?php echo e(route('logout')); ?>" class="logout-btn">
                <span style="font-size:1rem;">🚪</span> Keluar
            </a>
        </div>
    <?php elseif(session('mhs_logged_in')): ?>
        <div class="sidebar-footer">
            <div class="admin-badge">
                <div class="admin-av"><?php echo e(strtoupper(substr(session('mhs_nama'), 0, 1))); ?></div>
                <div>
                    <div class="admin-name"><?php echo e(session('mhs_nama')); ?></div>
                    <div class="admin-role"><?php echo e(session('mhs_nim')); ?></div>
                </div>
            </div>
            <a href="<?php echo e(route('logout')); ?>" class="logout-btn">
                <span style="font-size:1rem;">🚪</span> Keluar
            </a>
        </div>
    <?php endif; ?>
    </aside>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="main-wrapper">
    <div class="topbar">
        <span class="topbar-brand">🧑‍💻 FaceAttend</span>
        <button class="topbar-toggle" onclick="toggleSidebar()">☰</button>
    </div>
    <div class="main-content">
        <?php if(session('success')): ?>
            <div class="alert-glass alert-success-g"><span>✅</span><span><?php echo e(session('success')); ?></span></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert-glass alert-danger-g"><span>⚠️</span><span><?php echo e(session('error')); ?></span></div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="alert-glass alert-danger-g">
                <span>⚠️</span>
                <div>
                    <strong>Terjadi Kesalahan Input:</strong>
                    <ul class="mb-0 mt-1" style="padding-left: 1.2rem; font-size: 0.8rem;">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
        <?php if(session('output')): ?>
            <div class="alert-glass alert-output-g"><span>💻</span><pre class="mb-0" style="white-space:pre-wrap;font-size:.82rem;"><?php echo e(session('output')); ?></pre></div>
        <?php endif; ?>
        <?php echo $__env->yieldContent('content'); ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function toggleSidebar(){document.getElementById('sidebar').classList.toggle('open');document.getElementById('sidebarOverlay').classList.toggle('show')}
    function closeSidebar(){document.getElementById('sidebar').classList.remove('open');document.getElementById('sidebarOverlay').classList.remove('show')}
</script>

<?php echo $__env->yieldPushContent('modals'); ?>

</body>
</html><?php /**PATH D:\Face_Recognition_PCD\face-attendance\backend\resources\views/layouts/app.blade.php ENDPATH**/ ?>