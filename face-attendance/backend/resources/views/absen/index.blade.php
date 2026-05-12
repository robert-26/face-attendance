@extends('layouts.app')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="col-md-8 col-lg-6">
        <div class="glass-card shadow-lg text-center" style="border-radius: 30px; overflow: visible;">
            <div style="margin-top: -40px;">
                <div class="page-icon mx-auto" style="width: 80px; height: 80px; font-size: 2.5rem; box-shadow: 0 10px 25px rgba(124, 58, 237, 0.5);">
                    📸
                </div>
            </div>
            
            <div class="glass-card-body pt-4 pb-5 px-4 px-md-5">
                <h2 style="font-weight: 800; color: #fff; margin-bottom: 0.5rem; letter-spacing: -1px;">Portal Absensi Mahasiswa</h2>
                <p style="color: var(--text-muted); font-size: 1.1rem; margin-bottom: 2.5rem;">
                    Silakan posisikan wajah Anda di depan kamera untuk melakukan presensi.
                </p>

                <form method="POST" action="{{ route('absen.run') }}">
                    @csrf
                    <button type="submit" class="btn-modern btn-primary-modern shadow-lg" style="width: 100%; padding: 1.25rem; font-size: 1.25rem; border-radius: 20px; transition: all 0.4s ease;">
                        <span style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                            <span style="font-size: 1.5rem;">👁️</span> Absen Sekarang
                        </span>
                    </button>
                </form>

                @if(session('result'))
                    <div class="alert alert-modern alert-success-modern mt-4 text-start">
                        <div style="font-weight: 600; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 5px;">
                            <span>✅</span> Hasil Deteksi:
                        </div>
                        <pre class="mb-0" style="color: var(--text-main); font-family: 'Courier New', Courier, monospace; font-size: 0.9rem; white-space: pre-wrap; word-wrap: break-word;">{{ session('result') }}</pre>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="text-center mt-4">
            <p style="color: var(--text-muted); font-size: 0.85rem;">
                <span style="opacity: 0.7;">&copy; {{ date('Y') }} Sistem Presensi Wajah.</span>
            </p>
        </div>
    </div>
</div>
@endsection