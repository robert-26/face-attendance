<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Face Attendance System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            overflow: hidden;
            background: #0a0818;
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
            background: linear-gradient(145deg, #0d0b26 0%, #1a1040 50%, #0d0b26 100%);
        }

        /* Animated mesh gradient orbs */
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.45;
            animation: floatOrb 8s ease-in-out infinite;
        }

        .orb-1 {
            width: 420px; height: 420px;
            background: radial-gradient(circle, #7c3aed, #4f46e5);
            top: -100px; left: -100px;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, #a78bfa, #8b5cf6);
            bottom: -80px; right: -60px;
            animation-delay: -3s;
        }

        .orb-3 {
            width: 200px; height: 200px;
            background: radial-gradient(circle, #06b6d4, #0284c7);
            top: 50%; left: 60%;
            animation-delay: -6s;
        }

        @keyframes floatOrb {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%       { transform: translate(30px, -30px) scale(1.05); }
            66%       { transform: translate(-20px, 20px) scale(0.97); }
        }

        /* Grid overlay */
        .grid-overlay {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(167,139,250,0.06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(167,139,250,0.06) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        /* Scan-line animation */
        .scanline {
            position: absolute;
            left: 0; right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(167,139,250,0.6), transparent);
            animation: scanMove 4s linear infinite;
            opacity: 0.6;
        }

        @keyframes scanMove {
            from { top: -2px; }
            to   { top: 100%; }
        }

        .left-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        /* Face recognition animation ring */
        .face-ring-wrap {
            position: relative;
            width: 200px; height: 200px;
            margin: 0 auto 2.5rem;
        }

        .face-ring {
            position: absolute;
            border-radius: 50%;
            border: 2px solid transparent;
            animation: rotateSpin linear infinite;
        }

        .face-ring-1 {
            inset: 0;
            border-top-color: #a78bfa;
            border-right-color: rgba(167,139,250,0.3);
            animation-duration: 3s;
        }

        .face-ring-2 {
            inset: 14px;
            border-bottom-color: #06b6d4;
            border-left-color: rgba(6,182,212,0.3);
            animation-duration: 5s;
            animation-direction: reverse;
        }

        .face-ring-3 {
            inset: 28px;
            border-top-color: #8b5cf6;
            border-right-color: rgba(139,92,246,0.25);
            animation-duration: 7s;
        }

        @keyframes rotateSpin {
            to { transform: rotate(360deg); }
        }

        .face-icon {
            position: absolute;
            inset: 40px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3.5rem;
            border: 1px solid rgba(255,255,255,0.08);
            backdrop-filter: blur(4px);
            animation: pulseFace 3s ease-in-out infinite;
        }

        @keyframes pulseFace {
            0%, 100% { box-shadow: 0 0 0 0 rgba(167,139,250,0.35); }
            50%       { box-shadow: 0 0 0 20px rgba(167,139,250,0); }
        }

        /* Corner brackets */
        .brackets {
            position: absolute;
            inset: -4px;
        }

        .bracket {
            position: absolute;
            width: 20px; height: 20px;
            border-color: #a78bfa;
            border-style: solid;
            opacity: 0.8;
        }

        .bracket-tl { top: 0; left: 0;  border-width: 2px 0 0 2px; }
        .bracket-tr { top: 0; right: 0; border-width: 2px 2px 0 0; }
        .bracket-bl { bottom: 0; left: 0;  border-width: 0 0 2px 2px; }
        .bracket-br { bottom: 0; right: 0; border-width: 0 2px 2px 0; }

        .left-title {
            font-size: 2.2rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            letter-spacing: -1px;
            margin-bottom: 1rem;
        }

        .left-title span {
            background: linear-gradient(135deg, #a78bfa, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .left-desc {
            color: rgba(255,255,255,0.5);
            font-size: 1rem;
            line-height: 1.7;
            max-width: 340px;
        }

        /* Feature pills */
        .feature-pills {
            display: flex;
            gap: 0.6rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .pill {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 999px;
            padding: 0.35rem 0.85rem;
            font-size: 0.8rem;
            color: rgba(255,255,255,0.6);
            backdrop-filter: blur(6px);
        }

        .pill-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #a78bfa;
            box-shadow: 0 0 6px #a78bfa;
        }

        /* ========== RIGHT PANEL ========== */
        .right-panel {
            width: 480px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2.5rem;
            background: rgba(255,255,255,0.025);
            border-left: 1px solid rgba(255,255,255,0.07);
            backdrop-filter: blur(30px);
            position: relative;
            z-index: 5;
        }

        .login-box {
            width: 100%;
            max-width: 380px;
        }

        .login-header {
            margin-bottom: 2.5rem;
        }

        .login-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(167,139,250,0.12);
            border: 1px solid rgba(167,139,250,0.25);
            border-radius: 999px;
            padding: 0.3rem 0.9rem;
            font-size: 0.78rem;
            font-weight: 600;
            color: #a78bfa;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 1.25rem;
        }

        .login-title {
            font-size: 2rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.2;
            letter-spacing: -0.5px;
            margin-bottom: 0.5rem;
        }

        .login-sub {
            font-size: 0.9rem;
            color: rgba(255,255,255,0.4);
        }

        /* Form fields */
        .field-group {
            margin-bottom: 1.25rem;
        }

        .field-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 0.5rem;
        }

        .field-wrap {
            position: relative;
        }

        .field-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
            pointer-events: none;
            opacity: 0.5;
        }

        .field-input {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 14px;
            color: #fff;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            padding: 0.85rem 1rem 0.85rem 2.8rem;
            outline: none;
            transition: all 0.3s ease;
        }

        .field-input::placeholder {
            color: rgba(255,255,255,0.2);
        }

        .field-input:focus {
            background: rgba(167,139,250,0.08);
            border-color: #a78bfa;
            box-shadow: 0 0 0 4px rgba(167,139,250,0.12), 0 0 20px rgba(167,139,250,0.1);
        }

        /* Toggle password */
        .toggle-pw {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            opacity: 0.4;
            font-size: 1rem;
            transition: opacity 0.2s;
            user-select: none;
        }

        .toggle-pw:hover { opacity: 0.7; }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 14px;
            font-size: 1rem;
            font-weight: 700;
            font-family: 'Inter', sans-serif;
            color: #fff;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #8b5cf6 0%, #a78bfa 50%, #6d28d9 100%);
            background-size: 200% 200%;
            box-shadow: 0 6px 30px rgba(139, 92, 246, 0.5);
            transition: all 0.4s ease;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
            animation: gradShift 4s ease infinite;
        }

        @keyframes gradShift {
            0%, 100% { background-position: 0% 50%; }
            50%       { background-position: 100% 50%; }
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px rgba(139, 92, 246, 0.65);
        }

        .btn-login:active { transform: translateY(0); }

        .btn-login::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .btn-login:hover::after { opacity: 1; }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 2rem 0;
            color: rgba(255,255,255,0.2);
            font-size: 0.78rem;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.1);
        }

        /* Fade-in animation for the whole box */
        .login-box {
            animation: fadeSlide 0.7s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes fadeSlide {
            from { opacity: 0; transform: translateX(30px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            body { flex-direction: column; overflow-y: auto; }
            .left-panel { min-height: 40vh; flex: none; padding: 2.5rem 1.5rem; }
            .right-panel { width: 100%; min-height: auto; border-left: none; border-top: 1px solid rgba(255,255,255,0.07); }
            .left-title { font-size: 1.6rem; }
            .face-ring-wrap { width: 140px; height: 140px; }
        }
    </style>
</head>
<body>

    {{-- LEFT BRANDING PANEL --}}
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
                <div class="face-icon">🧑‍💻</div>
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
                <div class="pill"><div class="pill-dot" style="background:#06b6d4; box-shadow: 0 0 6px #06b6d4;"></div> Akurasi Tinggi</div>
                <div class="pill"><div class="pill-dot" style="background:#34d399; box-shadow: 0 0 6px #34d399;"></div> Anti-Spoofing</div>
            </div>
        </div>
    </div>

    {{-- RIGHT LOGIN PANEL --}}
    <div class="right-panel">
        <div class="login-box">

            <div class="login-header">
                <h2 class="login-title">Selamat Datang</h2>
                <p class="login-sub">Masuk dengan akun Mahasiswa atau Admin</p>
            </div>

            @if(session('error'))
                <div style="background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.3); border-radius: 12px; padding: 0.8rem 1rem; margin-bottom: 1.5rem; color: #f87171; font-size: 0.875rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span>⚠️</span> {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div style="background: rgba(52,211,153,0.12); border: 1px solid rgba(52,211,153,0.3); border-radius: 12px; padding: 0.8rem 1rem; margin-bottom: 1.5rem; color: #34d399; font-size: 0.875rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="field-group">
                    <label class="field-label" for="identifier">Email / Username / NIM</label>
                    <div class="field-wrap">
                        <span class="field-icon">👤</span>
                        <input class="field-input" type="text" id="identifier" name="identifier"
                               placeholder="Email Admin atau NIM Mahasiswa" required autocomplete="username">
                    </div>
                </div>

                <div class="field-group">
                    <label class="field-label" for="password">Password / Nama Lengkap</label>
                    <div class="field-wrap">
                        <span class="field-icon">🔑</span>
                        <input class="field-input" type="password" id="password" name="password"
                               placeholder="Password Admin atau Nama Mahasiswa" required autocomplete="current-password">
                        <span class="toggle-pw" id="togglePw" onclick="togglePassword()">👁</span>
                    </div>
                </div>

                <button type="submit" class="btn-login" onclick="setLoading(this)">
                    <span>Masuk</span>
                    <span>→</span>
                </button>
            </form>

            <div class="divider">© {{ date('Y') }} Face Attendance System</div>

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
