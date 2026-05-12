@extends('layouts.app')

@section('content')
<div class="row justify-content-center" style="min-height: 70vh; align-items: center;">
    <div class="col-md-8 col-lg-5">

        <div class="glass-card" style="border-radius: 12px;">
            <div class="glass-card-header" style="text-align:center;padding:1.25rem 1.5rem;">
                <div style="width:40px;height:40px;border-radius:10px;background:var(--accent);display:inline-flex;align-items:center;justify-content:center;font-size:1.1rem;margin-bottom:.75rem;">
                    📸
                </div>
                <div style="font-weight:700;font-size:1rem;color:var(--text);">Portal Absensi Mahasiswa</div>
                <div style="font-size:.78rem;color:var(--text-muted);margin-top:.2rem;">Posisikan wajah Anda di depan kamera untuk melakukan presensi.</div>
            </div>

            <div class="glass-card-body" style="padding:1.5rem;">
                <form method="POST" action="{{ route('absen.run') }}">
                    @csrf
                    <button type="submit"
                            class="btn-modern btn-primary-modern"
                            style="width:100%;padding:.75rem;font-size:.9rem;justify-content:center;border-radius:8px;">
                        <span>👁️</span> Absen Sekarang
                    </button>
                </form>

                @if(session('result'))
                    <div style="margin-top:1.25rem;background:rgba(16,185,129,.06);border:1px solid rgba(16,185,129,.15);border-radius:8px;padding:1rem;">
                        <div style="font-weight:600;margin-bottom:.4rem;display:flex;align-items:center;gap:.4rem;font-size:.8125rem;color:#34d399;">
                            <span>✅</span> Hasil Deteksi:
                        </div>
                        <pre style="margin:0;color:var(--text-secondary);font-family:'Inter',monospace;font-size:.78rem;white-space:pre-wrap;word-wrap:break-word;">{{ session('result') }}</pre>
                    </div>
                @endif
            </div>
        </div>

        <div style="text-align:center;margin-top:1.25rem;">
            <p style="color:var(--text-muted);font-size:.72rem;">
                &copy; {{ date('Y') }} Sistem Presensi Wajah.
            </p>
        </div>
    </div>
</div>
@endsection