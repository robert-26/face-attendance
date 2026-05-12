<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Face Attendance System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            background: #0f172a;
            -webkit-font-smoothing: antialiased;
        }

        /* ========== LEFT PANEL ========== */
        .left-panel {
            flex: 1;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            overflow: hidden;
            background: #111827;
            border-right: 1px solid rgba(255,255,255,.06);
        }

        /* Subtle grid pattern */
        .grid-overlay {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.02) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        .left-content {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 400px;
        }

        /* Simple icon container */
        .face-ring-wrap {
            position: relative;
            width: 120px; height: 120px;
            margin: 0 auto 2rem;
        }

        .face-ring, .face-ring-1, .face-ring-2, .face-ring-3 { display: none; }

        .face-icon {
            width: 120px; height: 120px;
            background: rgba(79,70,229,.1);
            border: 1px solid rgba(79,70,229,.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
        }

        .brackets { display: none; }

        .left-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #f8fafc;
            line-height: 1.3;
            letter-spacing: -.02em;
            margin-bottom: .75rem;
        }

        .left-title span {
            color: #818cf8;
        }

        .left-desc {
            color: #94a3b8;
            font-size: .875rem;
            line-height: 1.6;
            max-width: 320px;
            margin: 0 auto;
        }

        /* Feature pills */
        .feature-pills {
            display: flex;
            gap: .5rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 1.5rem;
        }

        .pill {
            display: flex;
            align-items: center;
            gap: .35rem;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 6px;
            padding: .3rem .7rem;
            font-size: .7rem;
            color: #94a3b8;
        }

        .pill-dot {
            width: 5px; height: 5px;
            border-radius: 50%;
            background: #4f46e5;
        }

        /* Orbs & scanline hidden */
        .orb, .scanline { display: none; }

        /* ========== RIGHT PANEL ========== */
        .right-panel {
            width: 460px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem;
            background: #0f172a;
            position: relative;
            z-index: 5;
        }

        .login-box {
            width: 100%;
            max-width: 360px;
        }

        .login-header {
            margin-bottom: 2rem;
        }

        .login-tag { display: none; }

        .login-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #f8fafc;
            line-height: 1.2;
            letter-spacing: -.02em;
            margin-bottom: .4rem;
        }

        .login-sub {
            font-size: .8125rem;
            color: #94a3b8;
        }

        /* Form fields */
        .field-group {
            margin-bottom: 1rem;
        }

        .field-label {
            display: block;
            font-size: .75rem;
            font-weight: 600;
            color: #94a3b8;
            margin-bottom: .4rem;
            text-transform: none;
            letter-spacing: 0;
        }

        .field-wrap {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: .75rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: .85rem;
            pointer-events: none;
            opacity: 0.5;
        }

        .field-input {
            width: 100%;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 8px;
            color: #f8fafc;
            font-size: .875rem;
            font-family: 'Inter', sans-serif;
            padding: .65rem .875rem .65rem 2.5rem;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }

        .field-input::placeholder {
            color: #64748b;
        }

        .field-input:focus {
            background: rgba(79,70,229,.05);
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79,70,229,.1);
        }

        /* Toggle password */
        .toggle-pw {
            position: absolute;
            right: .75rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            opacity: 0.4;
            font-size: .85rem;
            transition: opacity .15s;
            user-select: none;
        }

        .toggle-pw:hover { opacity: 0.7; }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: .75rem;
            border: none;
            border-radius: 8px;
            font-size: .875rem;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            color: #fff;
            cursor: pointer;
            background: #4f46e5;
            transition: background .15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            margin-top: 1.5rem;
        }

        .btn-login:hover {
            background: #4338ca;
        }

        .btn-login:active { opacity: .9; }

        .btn-login::after { display: none; }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin: 1.5rem 0;
            color: #64748b;
            font-size: .7rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,.06);
        }

        .login-footer {
            text-align: center;
            font-size: .75rem;
            color: #64748b;
        }

        .login-footer a {
            color: #818cf8;
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body { flex-direction: column; overflow-y: auto; }
            .left-panel { min-height: 35vh; flex: none; padding: 2rem 1.5rem; }
            .right-panel { width: 100%; min-height: auto; border-left: none; padding: 2rem 1.5rem; }
            .left-title { font-size: 1.25rem; }
            .face-ring-wrap { width: 90px; height: 90px; }
            .face-icon { width: 90px; height: 90px; font-size: 2rem; }
        }
    </style>
</head>
<body>

    
    <div class="left-panel">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
        <div class="grid-overlay"></div>
        <div class="scanline"></div>

        <div class="left-content">
            <div class="face-ring-wrap">
                <div class="face-ring face-ring-1"></div>
                <div class="face-ring face-ring-2"></div>
                <div class="face-ring face-ring-3"></div>
                <div class="face-icon">🎓</div>
                <div class="brackets">
                    <div class="bracket bracket-tl"></div>
                    <div class="bracket bracket-tr"></div>
                    <div class="bracket bracket-bl"></div>
                    <div class="bracket bracket-br"></div>
                </div>
            </div>

            <h1 class="left-title">
                Sistem Absensi<br><span>Berbasis Wajah</span>
            </h1>

            <p class="left-desc">
                Platform presensi cerdas menggunakan teknologi pengenalan wajah
                real-time untuk kampus modern.
            </p>

            <div class="feature-pills">
                <div class="pill"><div class="pill-dot"></div> Pengenalan Real-time</div>
                <div class="pill"><div class="pill-dot" style="background:#10b981;"></div> Akurasi Tinggi</div>
                <div class="pill"><div class="pill-dot" style="background:#f59e0b;"></div> Anti-Spoofing</div>
            </div>
        </div>
    </div>

    
    <div class="right-panel">
        <div class="login-box">

            <div class="login-header">
                <h2 class="login-title">Selamat Datang</h2>
                <p class="login-sub">Masuk Ke Akun Anda</p>
            </div>

            <?php if(session('error')): ?>
                <div style="background: rgba(239,68,68,.08); border: 1px solid rgba(239,68,68,.2); border-radius: 8px; padding: .65rem .875rem; margin-bottom: 1rem; color: #fca5a5; font-size: .8125rem; display: flex; align-items: center; gap: .4rem;">
                    <span>⚠️</span> <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
                <div style="background: rgba(16,185,129,.08); border: 1px solid rgba(16,185,129,.2); border-radius: 8px; padding: .65rem .875rem; margin-bottom: 1rem; color: #34d399; font-size: .8125rem; display: flex; align-items: center; gap: .4rem;">
                    <span>✅</span> <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('login.post')); ?>">
                <?php echo csrf_field(); ?>
                <div class="field-group">
                    <label class="field-label" for="identifier">NIM</label>
                    <div class="field-wrap">
                        <span class="field-icon">👤</span>
                        <input class="field-input" type="text" id="identifier" name="identifier"
                               placeholder="Masukkan NIM anda" required autocomplete="username">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="password">Password</label>
                    <div class="field-wrap">
                        <span class="field-icon">🔑</span>
                        <input class="field-input" type="password" id="password" name="password"
                               placeholder="Masukkan Password Anda" required autocomplete="current-password">
                        <span class="toggle-pw" id="togglePw" onclick="togglePassword()">👁</span>
                    </div>
                </div>

                <button type="submit" class="btn-login" onclick="setLoading(this)">
                    <span>Masuk</span>
                    <span>→</span>
                </button>
            </form>

            <div class="divider">© <?php echo e(date('Y')); ?> Face Attendance System</div>

            <div class="login-footer">
                Butuh bantuan? Hubungi <a href="#">Administrator</a>
            </div>

        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('togglePw');
            if (input.type === 'password') {
                input.type = 'text';
                icon.textContent = '🙈';
            } else {
                input.type = 'password';
                icon.textContent = '👁';
            }
        }

        // Button loading state
        function setLoading(btn) {
            // We ensure it's triggered on valid forms automatically,
            // or just bind it via an event listener inside a timeout so form submits.
            setTimeout(() => {
                btn.innerHTML = '<span>Memproses...</span><span class="spinner"></span>';
                btn.style.opacity = '0.8';
                // Note: Disable button will prevent form from submitting if we aren't careful,
                // so we don't fully disable it or we do it carefully after the submit event fired.
            }, 50);
        }
    </script>
</body>
</html>
<?php /**PATH D:\Face_Recognition_PCD\face-attendance\backend\resources\views/auth/login.blade.php ENDPATH**/ ?>