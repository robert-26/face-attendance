@extends('layouts.app')

@section('content')

<style>
    /* ===== STAT CARDS ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .stats-grid { grid-template-columns: 1fr; } }

    .stat-card-new {
        position: relative;
        background: rgba(255,255,255,0.04);
        backdrop-filter: blur(14px);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 20px;
        padding: 1.5rem 1.75rem;
        overflow: hidden;
        cursor: default;
        transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s;
    }

    .stat-card-new:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.35);
    }

    .stat-card-new.total  { border-color: rgba(167,139,250,0.2); }
    .stat-card-new.hadir  { border-color: rgba(52,211,153,0.2); }
    .stat-card-new.izin   { border-color: rgba(251,191,36,0.2); }
    .stat-card-new.alpha  { border-color: rgba(248,113,113,0.2); }

    .stat-card-new:hover.total  { border-color: rgba(167,139,250,0.5); box-shadow: 0 20px 40px rgba(167,139,250,0.15); }
    .stat-card-new:hover.hadir  { border-color: rgba(52,211,153,0.5);  box-shadow: 0 20px 40px rgba(52,211,153,0.15);  }
    .stat-card-new:hover.izin   { border-color: rgba(251,191,36,0.5);  box-shadow: 0 20px 40px rgba(251,191,36,0.15);  }
    .stat-card-new:hover.alpha  { border-color: rgba(248,113,113,0.5); box-shadow: 0 20px 40px rgba(248,113,113,0.15); }

    /* Glow orb behind each card */
    .stat-card-new::before {
        content: '';
        position: absolute;
        width: 120px; height: 120px;
        border-radius: 50%;
        right: -30px; top: -30px;
        filter: blur(40px);
        opacity: 0.25;
        transition: opacity 0.3s;
    }

    .stat-card-new:hover::before { opacity: 0.45; }
    .stat-card-new.total::before  { background: #a78bfa; }
    .stat-card-new.hadir::before  { background: #34d399; }
    .stat-card-new.izin::before   { background: #fbbf24; }
    .stat-card-new.alpha::before  { background: #f87171; }

    .stat-icon {
        width: 42px; height: 42px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }

    .stat-card-new.total .stat-icon  { background: rgba(167,139,250,0.15); }
    .stat-card-new.hadir .stat-icon  { background: rgba(52,211,153,0.15);  }
    .stat-card-new.izin  .stat-icon  { background: rgba(251,191,36,0.15);  }
    .stat-card-new.alpha .stat-icon  { background: rgba(248,113,113,0.15); }

    .stat-num {
        font-size: 2.4rem;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -2px;
        margin-bottom: 0.4rem;
    }

    .stat-card-new.total .stat-num  { color: #c4b5fd; }
    .stat-card-new.hadir .stat-num  { color: #34d399; }
    .stat-card-new.izin  .stat-num  { color: #fbbf24; }
    .stat-card-new.alpha .stat-num  { color: #f87171; }

    .stat-lbl {
        font-size: 0.8rem;
        font-weight: 500;
        color: rgba(255,255,255,0.4);
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    /* Thin progress bar at bottom of each stat card */
    .stat-bar {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 3px;
        background: rgba(255,255,255,0.06);
        border-radius: 0 0 20px 20px;
        overflow: hidden;
    }

    .stat-bar-fill {
        height: 100%;
        border-radius: 3px;
        transition: width 1s cubic-bezier(0.16,1,0.3,1);
    }

    .stat-card-new.total .stat-bar-fill  { background: #a78bfa; width: 100%; }
    .stat-card-new.hadir .stat-bar-fill  { background: #34d399; }
    .stat-card-new.izin  .stat-bar-fill  { background: #fbbf24; }
    .stat-card-new.alpha .stat-bar-fill  { background: #f87171; }

    /* ===== MAIN TABLE CARD ===== */
    .table-card {
        background: rgba(255,255,255,0.03);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 24px;
        overflow: hidden;
    }

    /* ===== TOOLBAR ===== */
    .toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid rgba(255,255,255,0.07);
    }

    .toolbar-title {
        font-size: 1rem;
        font-weight: 700;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .toolbar-title-icon {
        width: 32px; height: 32px;
        background: linear-gradient(135deg, #a78bfa, #7c3aed);
        border-radius: 9px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem;
        box-shadow: 0 4px 12px rgba(124,58,237,0.4);
    }

    .controls {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .search-wrap {
        position: relative;
    }

    .search-wrap .s-icon {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        font-size: 0.85rem;
        opacity: 0.4;
        pointer-events: none;
    }

    .search-inp {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 12px;
        color: #fff;
        font-size: 0.875rem;
        font-family: 'Inter', sans-serif;
        padding: 0.6rem 1rem 0.6rem 2.4rem;
        outline: none;
        width: 230px;
        transition: all 0.3s;
    }

    .search-inp::placeholder { color: rgba(255,255,255,0.25); }

    .search-inp:focus {
        border-color: #a78bfa;
        background: rgba(167,139,250,0.09);
        box-shadow: 0 0 0 3px rgba(167,139,250,0.12);
        width: 270px;
    }

    /* Filter pills */
    .filter-pills {
        display: flex;
        gap: 0.4rem;
    }

    .fpill {
        padding: 0.5rem 1rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid rgba(255,255,255,0.12);
        background: rgba(255,255,255,0.05);
        color: rgba(255,255,255,0.5);
        transition: all 0.25s;
        user-select: none;
    }

    .fpill:hover { background: rgba(255,255,255,0.1); color: #fff; }

    .fpill.active-all    { background: rgba(167,139,250,0.2); border-color: rgba(167,139,250,0.4); color: #c4b5fd; }
    .fpill.active-hadir  { background: rgba(52,211,153,0.15); border-color: rgba(52,211,153,0.4);  color: #34d399; }
    .fpill.active-izin   { background: rgba(251,191,36,0.15); border-color: rgba(251,191,36,0.4);  color: #fbbf24; }
    .fpill.active-alpha  { background: rgba(248,113,113,0.15); border-color: rgba(248,113,113,0.4); color: #f87171; }

    /* ===== TABLE ===== */
    .table-responsive { overflow-x: auto; }

    .atbl {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .atbl thead tr {
        background: rgba(255,255,255,0.03);
    }

    .atbl thead th {
        padding: 0.9rem 1.5rem;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: rgba(255,255,255,0.35);
        border-bottom: 1px solid rgba(255,255,255,0.07);
        white-space: nowrap;
    }

    .atbl tbody tr {
        transition: background 0.2s;
        animation: rowIn 0.5s cubic-bezier(0.16,1,0.3,1) both;
    }

    @keyframes rowIn {
        from { opacity: 0; transform: translateY(8px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .atbl tbody tr:nth-child(1)  { animation-delay: 0.04s; }
    .atbl tbody tr:nth-child(2)  { animation-delay: 0.08s; }
    .atbl tbody tr:nth-child(3)  { animation-delay: 0.12s; }
    .atbl tbody tr:nth-child(4)  { animation-delay: 0.16s; }
    .atbl tbody tr:nth-child(5)  { animation-delay: 0.20s; }
    .atbl tbody tr:nth-child(n+6){ animation-delay: 0.24s; }

    .atbl tbody tr:hover { background: rgba(167,139,250,0.06); }

    .atbl tbody td {
        padding: 1rem 1.5rem;
        font-size: 0.875rem;
        color: rgba(255,255,255,0.78);
        border-bottom: 1px solid rgba(255,255,255,0.04);
        vertical-align: middle;
    }

    .atbl tbody tr:last-child td { border-bottom: none; }

    /* Row number */
    .row-num {
        font-size: 0.72rem;
        color: rgba(255,255,255,0.2);
        font-weight: 600;
        font-variant-numeric: tabular-nums;
    }

    /* NIM chip */
    .nim-chip {
        display: inline-flex;
        align-items: center;
        background: rgba(167,139,250,0.12);
        color: #c4b5fd;
        border: 1px solid rgba(167,139,250,0.22);
        border-radius: 8px;
        padding: 0.22rem 0.65rem;
        font-size: 0.78rem;
        font-weight: 600;
        font-family: 'Courier New', monospace;
        letter-spacing: 0.5px;
    }

    /* Avatar + name */
    .av-wrap { display: flex; align-items: center; gap: 0.7rem; }

    .av {
        flex-shrink: 0;
        width: 36px; height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #7c3aed, #a78bfa);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem;
        font-weight: 700;
        color: #fff;
        box-shadow: 0 4px 10px rgba(124,58,237,0.4);
    }

    .av-name  { font-size: 0.875rem; font-weight: 600; color: #e2e8f0; }
    .av-kelas { font-size: 0.75rem; color: rgba(255,255,255,0.38); margin-top: 1px; }

    /* Date & time */
    .date-txt { font-size: 0.875rem; color: rgba(255,255,255,0.65); }

    .time-chip {
        display: inline-block;
        background: rgba(99,102,241,0.15);
        color: #a5b4fc;
        border: 1px solid rgba(99,102,241,0.2);
        border-radius: 999px;
        padding: 0.18rem 0.7rem;
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    /* Status badge */
    .sbadge {
        display: inline-flex;
        align-items: center;
        gap: 0.38rem;
        padding: 0.28rem 0.8rem;
        border-radius: 999px;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: capitalize;
    }

    .sbadge.hadir { background: rgba(52,211,153,0.12); color: #34d399; border: 1px solid rgba(52,211,153,0.25); }
    .sbadge.izin  { background: rgba(251,191,36,0.12);  color: #fbbf24; border: 1px solid rgba(251,191,36,0.25);  }
    .sbadge.alpha { background: rgba(248,113,113,0.12); color: #f87171; border: 1px solid rgba(248,113,113,0.25); }

    .sdot {
        width: 6px; height: 6px;
        border-radius: 50%;
        flex-shrink: 0;
        animation: blinkDot 2.5s ease-in-out infinite;
    }

    .sbadge.hadir .sdot { background: #34d399; box-shadow: 0 0 5px #34d399; }
    .sbadge.izin  .sdot { background: #fbbf24; box-shadow: 0 0 5px #fbbf24; animation-delay: 0.5s; }
    .sbadge.alpha .sdot { background: #f87171; box-shadow: 0 0 5px #f87171; animation-delay: 1s; }

    @keyframes blinkDot {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.3; }
    }

    /* ===== EMPTY STATE ===== */
    .empty-box {
        padding: 5rem 2rem;
        text-align: center;
    }

    .empty-box .e-icon {
        font-size: 3.5rem;
        margin-bottom: 1rem;
        filter: grayscale(0.3);
        opacity: 0.5;
    }

    .empty-box p { color: rgba(255,255,255,0.3); font-size: 0.95rem; }

    /* ===== FOOTER ===== */
    .tbl-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 2rem;
        border-top: 1px solid rgba(255,255,255,0.06);
        font-size: 0.78rem;
        color: rgba(255,255,255,0.28);
    }

    .tbl-footer-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(167,139,250,0.1);
        border: 1px solid rgba(167,139,250,0.2);
        border-radius: 999px;
        padding: 0.3rem 0.75rem;
        color: #c4b5fd;
        font-size: 0.75rem;
        font-weight: 600;
    }
</style>

{{-- PHP calculations --}}
@php
    $totalHadir = $absensi->where('status', 'hadir')->count();
    $totalIzin  = $absensi->where('status', 'izin')->count();
    $totalAlpha = $absensi->where('status', 'alpha')->count();
    $total      = $absensi->count();
    $pctHadir   = $total > 0 ? round($totalHadir / $total * 100) : 0;
    $pctIzin    = $total > 0 ? round($totalIzin  / $total * 100) : 0;
    $pctAlpha   = $total > 0 ? round($totalAlpha / $total * 100) : 0;
@endphp

{{-- PAGE HEADER --}}
<div class="page-header">
    <div class="page-title-group">
        <div class="page-icon">📋</div>
        <div>
            <h1 class="page-title">Data Absensi</h1>
            <p class="page-subtitle">Rekap & riwayat kehadiran seluruh mahasiswa</p>
        </div>
    </div>
    <div style="font-size: 0.8rem; color: rgba(255,255,255,0.3);">
        Diperbarui: {{ now()->translatedFormat('d M Y, H:i') }}
    </div>
</div>

{{-- STAT CARDS --}}
<div class="stats-grid">
    <div class="stat-card-new total">
        <div class="stat-icon">📊</div>
        <div class="stat-num">{{ $total }}</div>
        <div class="stat-lbl">Total Record</div>
        <div class="stat-bar"><div class="stat-bar-fill" style="width:100%"></div></div>
    </div>
    <div class="stat-card-new hadir">
        <div class="stat-icon">✅</div>
        <div class="stat-num">{{ $totalHadir }}</div>
        <div class="stat-lbl">Hadir · {{ $pctHadir }}%</div>
        <div class="stat-bar"><div class="stat-bar-fill" id="barHadir" style="width:0%"></div></div>
    </div>
    <div class="stat-card-new izin">
        <div class="stat-icon">📝</div>
        <div class="stat-num">{{ $totalIzin }}</div>
        <div class="stat-lbl">Izin · {{ $pctIzin }}%</div>
        <div class="stat-bar"><div class="stat-bar-fill" id="barIzin" style="width:0%"></div></div>
    </div>
    <div class="stat-card-new alpha">
        <div class="stat-icon">❌</div>
        <div class="stat-num">{{ $totalAlpha }}</div>
        <div class="stat-lbl">Alpha · {{ $pctAlpha }}%</div>
        <div class="stat-bar"><div class="stat-bar-fill" id="barAlpha" style="width:0%"></div></div>
    </div>
</div>

{{-- MAIN TABLE CARD --}}
<div class="table-card">

    {{-- TOOLBAR --}}
    <div class="toolbar">
        <div class="toolbar-title">
            <div class="toolbar-title-icon">📋</div>
            Rekap Kehadiran
        </div>
        <div class="controls">
            {{-- Live search --}}
            <div class="search-wrap">
                <span class="s-icon">🔍</span>
                <input type="text" class="search-inp" id="searchInput" placeholder="Cari NIM, nama, kelas…">
            </div>
            {{-- Filter pills --}}
            <div class="filter-pills" id="filterPills">
                <div class="fpill active-all" data-val="">Semua</div>
                <div class="fpill" data-val="hadir">Hadir</div>
                <div class="fpill" data-val="izin">Izin</div>
                <div class="fpill" data-val="alpha">Alpha</div>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="atbl" id="absensiTable">
            <thead>
                <tr>
                    <th style="width:48px">#</th>
                    <th>NIM</th>
                    <th>Mahasiswa</th>
                    <th>Tanggal</th>
                    <th>Waktu</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @forelse($absensi as $i => $data)
                    @php
                        $sc       = strtolower($data->status ?? 'alpha');
                        if (!in_array($sc, ['hadir','izin','alpha'])) $sc = 'alpha';
                        $initials = strtoupper(substr($data->nama ?? '?', 0, 1));
                    @endphp
                    <tr data-nim="{{ strtolower($data->nim) }}"
                        data-nama="{{ strtolower($data->nama) }}"
                        data-kelas="{{ strtolower($data->kelas) }}"
                        data-status="{{ $sc }}">

                        <td><span class="row-num">{{ $i + 1 }}</span></td>

                        <td><span class="nim-chip">{{ $data->nim }}</span></td>

                        <td>
                            <div class="av-wrap">
                                <div class="av">{{ $initials }}</div>
                                <div>
                                    <div class="av-name">{{ $data->nama }}</div>
                                    <div class="av-kelas">{{ $data->kelas }}</div>
                                </div>
                            </div>
                        </td>

                        <td><span class="date-txt">{{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d M Y') }}</span></td>

                        <td><span class="time-chip">{{ $data->waktu }}</span></td>

                        <td>
                            <span class="sbadge {{ $sc }}">
                                <span class="sdot"></span>
                                {{ ucfirst($data->status) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-box">
                                <div class="e-icon">📭</div>
                                <p>Belum ada data absensi tercatat.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- FOOTER --}}
    <div class="tbl-footer" id="tableFooter">
        <div>Menampilkan <strong style="color:rgba(255,255,255,0.55)" id="visibleCount">{{ $total }}</strong> dari {{ $total }} record</div>
        <div class="tbl-footer-badge">
            <span>📅</span> {{ now()->translatedFormat('d M Y') }}
        </div>
    </div>

</div>

<script>
    // === Animate progress bars on load ===
    window.addEventListener('load', () => {
        setTimeout(() => {
            const barHadir = document.getElementById('barHadir');
            const barIzin  = document.getElementById('barIzin');
            const barAlpha = document.getElementById('barAlpha');
            if (barHadir) barHadir.style.width = '{{ $pctHadir }}%';
            if (barIzin)  barIzin.style.width  = '{{ $pctIzin }}%';
            if (barAlpha) barAlpha.style.width  = '{{ $pctAlpha }}%';
        }, 200);
    });

    // === Filter pills ===
    const pills      = document.querySelectorAll('.fpill');
    const searchInp  = document.getElementById('searchInput');
    const rows       = document.querySelectorAll('#tableBody tr[data-nim]');
    const countEl    = document.getElementById('visibleCount');

    let activeStatus = '';

    pills.forEach(pill => {
        pill.addEventListener('click', () => {
            pills.forEach(p => p.className = 'fpill');          // reset
            activeStatus = pill.dataset.val;

            if (activeStatus === '')       pill.classList.add('active-all');
            else if (activeStatus === 'hadir') pill.classList.add('active-hadir');
            else if (activeStatus === 'izin')  pill.classList.add('active-izin');
            else if (activeStatus === 'alpha') pill.classList.add('active-alpha');

            filterTable();
        });
    });

    // Default: "Semua" active
    document.querySelector('.fpill[data-val=""]').classList.add('active-all');

    function filterTable() {
        const q = searchInp.value.toLowerCase().trim();
        let shown = 0;

        rows.forEach(row => {
            const nim   = row.dataset.nim   || '';
            const nama  = row.dataset.nama  || '';
            const kelas = row.dataset.kelas || '';
            const st    = row.dataset.status || '';

            const matchSearch = !q || nim.includes(q) || nama.includes(q) || kelas.includes(q);
            const matchStatus = !activeStatus || st === activeStatus;

            const visible = matchSearch && matchStatus;
            row.style.display = visible ? '' : 'none';
            if (visible) shown++;
        });

        if (countEl) countEl.textContent = shown;
    }

    searchInp.addEventListener('input', filterTable);
</script>

@endsection
