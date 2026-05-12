@extends('layouts.app')

@section('content')
{{-- PAGE HEADER --}}
<div class="page-header">
    <div class="page-title-group">
        <div class="page-icon">📜</div>
        <div>
            <h1 class="page-title">Riwayat Kehadiran</h1>
            <p class="page-subtitle">Daftar rekaman absensi Anda selama ini</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="glass-card">
            <div class="glass-card-header">
                <span style="font-weight:600;">Data Absensi Anda</span>
            </div>
            
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:separate;border-spacing:0;">
                    <thead>
                        <tr style="background:rgba(255,255,255,.02);">
                            <th style="padding:.65rem 1.25rem;font-size:.65rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);border-bottom:1px solid var(--border);text-align:left;">Tanggal</th>
                            <th style="padding:.65rem 1.25rem;font-size:.65rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);border-bottom:1px solid var(--border);text-align:left;">Jam</th>
                            <th style="padding:.65rem 1.25rem;font-size:.65rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);border-bottom:1px solid var(--border);text-align:left;">Status</th>
                            <th style="padding:.65rem 1.25rem;font-size:.65rem;font-weight:600;text-transform:uppercase;letter-spacing:.06em;color:var(--text-muted);border-bottom:1px solid var(--border);text-align:left;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absensi as $row)
                        <tr style="transition:background .12s;">
                            <td style="padding:.7rem 1.25rem;font-size:.8125rem;border-bottom:1px solid rgba(255,255,255,.04);">
                                <div style="font-weight:600; color:var(--text);">{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d F Y') }}</div>
                            </td>
                            <td style="padding:.7rem 1.25rem;font-size:.8125rem;border-bottom:1px solid rgba(255,255,255,.04);">
                                <div style="color:var(--text-secondary);font-family:'Inter',monospace;font-size:.78rem;">{{ substr($row->waktu, 0, 5) }} WIB</div>
                            </td>
                            <td style="padding:.7rem 1.25rem;font-size:.8125rem;border-bottom:1px solid rgba(255,255,255,.04);">
                                @if($row->status == 'hadir')
                                    <span style="background:rgba(16,185,129,.1);color:#34d399;border:1px solid rgba(16,185,129,.2);padding:.15rem .5rem;border-radius:5px;font-size:.7rem;font-weight:600;text-transform:uppercase;">Hadir</span>
                                @elseif($row->status == 'izin')
                                    <span style="background:rgba(245,158,11,.1);color:#fbbf24;border:1px solid rgba(245,158,11,.2);padding:.15rem .5rem;border-radius:5px;font-size:.7rem;font-weight:600;text-transform:uppercase;">Izin</span>
                                @else
                                    <span style="background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.2);padding:.15rem .5rem;border-radius:5px;font-size:.7rem;font-weight:600;text-transform:uppercase;">Alpha</span>
                                @endif
                            </td>
                            <td style="padding:.7rem 1.25rem;font-size:.8125rem;border-bottom:1px solid rgba(255,255,255,.04);">
                                <div style="color:var(--text-muted); font-size:.78rem;">{{ $row->keterangan ?? '-' }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align:center;padding:3rem 1rem;">
                                <div style="font-size:2rem;opacity:.25;margin-bottom:.75rem;">📭</div>
                                <div style="color:var(--text-muted);font-size:.8125rem;">Belum ada riwayat absensi.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div style="padding:.75rem 1.25rem;border-top:1px solid var(--border);font-size:.7rem;color:var(--text-muted);">
                Menampilkan <strong>{{ count($absensi) }}</strong> data riwayat.
            </div>
        </div>
    </div>
</div>
@endsection
