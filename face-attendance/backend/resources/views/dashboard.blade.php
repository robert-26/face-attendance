@extends('layouts.app')

@section('content')
<div class="page-header">
    <div class="page-title-group">
        <div class="page-icon">🏠</div>
        <div>
            <h1 class="page-title">Dashboard Admin</h1>
            <p class="page-subtitle">Ringkasan sistem absensi wajah</p>
        </div>
    </div>
    <div style="font-size:.75rem; color:var(--text-muted);">
        {{ now()->translatedFormat('l, d M Y') }}
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="glass-card h-100">
            <div class="glass-card-body d-flex align-items-center gap-3">
                <div style="width:44px;height:44px;border-radius:8px;background:rgba(79,70,229,.12);display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">🧑‍🎓</div>
                <div>
                    <div style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.2rem;">Jumlah Mahasiswa</div>
                    <div style="font-size:1.75rem;font-weight:700;line-height:1;color:var(--text);">{{ $mahasiswaCount }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="glass-card h-100">
            <div class="glass-card-body d-flex align-items-center gap-3">
                <div style="width:44px;height:44px;border-radius:8px;background:rgba(16,185,129,.12);display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">📅</div>
                <div>
                    <div style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:.2rem;">Absensi Hari Ini</div>
                    <div style="font-size:1.75rem;font-weight:700;line-height:1;color:var(--success);">{{ $absensiToday }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="glass-card">
            <div class="glass-card-header">
                Aksi Cepat
            </div>
            <div class="glass-card-body">
                <div class="d-flex gap-2 flex-wrap">
                    <a class="btn-modern btn-success-modern" href="{{ route('mahasiswa.index') }}">
                        <span>👥</span> Kelola Mahasiswa
                    </a>
                    <a class="btn-modern btn-primary-modern" href="{{ route('absensi.index') }}">
                        <span>📋</span> Lihat Absensi
                    </a>
                    <a class="btn-modern btn-warning-modern" href="{{ route('train.model') }}">
                        <span>🧠</span> Latih Model Wajah
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="glass-card h-100">
            <div class="glass-card-header">
                Pengaturan Absensi
            </div>
            <div class="glass-card-body">
                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="deadline" class="form-label" style="font-size:.8125rem;font-weight:600;color:var(--text);">Batas Waktu Absensi</label>
                        <input
                            type="time"
                            id="deadline"
                            name="deadline"
                            class="form-control @error('deadline') is-invalid @enderror"
                            value="{{ old('deadline', $deadline) }}"
                            required
                        >
                        @error('deadline')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div style="font-size:.75rem;color:var(--text-muted);margin-top:.5rem;">
                            Mahasiswa hanya dapat melakukan absen hadir atau izin sampai pukul {{ $deadline }} WIB.
                        </div>
                    </div>
                    <button type="submit" class="btn-modern btn-primary-modern">
                        Simpan Batas Waktu
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
