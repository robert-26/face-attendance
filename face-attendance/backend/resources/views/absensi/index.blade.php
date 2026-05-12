@extends('layouts.app')

@section('content')

<style>
    /* ===== STAT CARDS ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: .75rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 900px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 480px) { .stats-grid { grid-template-columns: 1fr; } }

    .stat-card-new {
        position: relative;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 1.1rem 1.25rem;
        overflow: hidden;
        cursor: default;
        transition: border-color .15s;
    }

    .stat-card-new:hover { border-color: rgba(255,255,255,.14); }

    .stat-card-new::before { display: none; }

    .stat-icon {
        width: 34px; height: 34px;
        border-radius: 7px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        margin-bottom: .75rem;
    }

    .stat-card-new.total .stat-icon  { background: rgba(79,70,229,.12); }
    .stat-card-new.hadir .stat-icon  { background: rgba(16,185,129,.12); }
    .stat-card-new.izin  .stat-icon  { background: rgba(245,158,11,.12); }
    .stat-card-new.alpha .stat-icon  { background: rgba(239,68,68,.12); }

    .stat-num {
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: .3rem;
        color: var(--text);
    }

    .stat-card-new.hadir .stat-num  { color: #34d399; }
    .stat-card-new.izin  .stat-num  { color: #fbbf24; }
    .stat-card-new.alpha .stat-num  { color: #f87171; }

    .stat-lbl {
        font-size: .7rem;
        font-weight: 500;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .stat-bar {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        height: 2px;
        background: rgba(255,255,255,.04);
        overflow: hidden;
    }

    .stat-bar-fill {
        height: 100%;
        transition: width .8s ease;
    }

    .stat-card-new.total .stat-bar-fill  { background: #4f46e5; width: 100%; }
    .stat-card-new.hadir .stat-bar-fill  { background: #10b981; }
    .stat-card-new.izin  .stat-bar-fill  { background: #f59e0b; }
    .stat-card-new.alpha .stat-bar-fill  { background: #ef4444; }

    /* ===== MAIN TABLE CARD ===== */
    .table-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
    }

    /* ===== TOOLBAR ===== */
    .toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: .75rem;
        padding: .875rem 1.25rem;
        border-bottom: 1px solid var(--border);
    }

    .toolbar-title {
        font-size: .8125rem;
        font-weight: 600;
        color: var(--text);
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .toolbar-title-icon {
        width: 28px; height: 28px;
        background: var(--accent);
        border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        font-size: .8rem;
    }

    .controls {
        display: flex;
        align-items: center;
        gap: .5rem;
        flex-wrap: wrap;
    }

    .search-wrap { position: relative; }

    .search-wrap .s-icon {
        position: absolute;
        left: .7rem; top: 50%;
        transform: translateY(-50%);
        font-size: .75rem;
        opacity: 0.4;
        pointer-events: none;
    }

    .search-inp {
        background: rgba(255,255,255,.04);
        border: 1px solid var(--border);
        border-radius: 7px;
        color: var(--text);
        font-size: .8125rem;
        font-family: 'Inter', sans-serif;
        padding: .45rem .75rem .45rem 2rem;
        outline: none;
        width: 200px;
        transition: border-color .15s, width .2s;
    }

    .search-inp::placeholder { color: var(--text-muted); }

    .search-inp:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(79,70,229,.1);
        width: 240px;
    }

    /* Filter pills */
    .filter-pills { display: flex; gap: .3rem; }

    .fpill {
        padding: .35rem .75rem;
        border-radius: 6px;
        font-size: .7rem;
        font-weight: 600;
        cursor: pointer;
        border: 1px solid var(--border);
        background: transparent;
        color: var(--text-secondary);
        transition: all .15s;
        user-select: none;
    }

    .fpill:hover { background: rgba(255,255,255,.06); color: var(--text); }

    .fpill.active-all    { background: rgba(79,70,229,.15); border-color: rgba(79,70,229,.3); color: #a5b4fc; }
    .fpill.active-hadir  { background: rgba(16,185,129,.1); border-color: rgba(16,185,129,.25); color: #34d399; }
    .fpill.active-izin   { background: rgba(245,158,11,.1); border-color: rgba(245,158,11,.25); color: #fbbf24; }
    .fpill.active-alpha  { background: rgba(239,68,68,.1); border-color: rgba(239,68,68,.25); color: #f87171; }

    /* ===== TABLE ===== */
    .table-responsive { overflow-x: auto; }

    .atbl {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .atbl thead tr { background: rgba(255,255,255,.02); }

    .atbl thead th {
        padding: .65rem 1.25rem;
        font-size: .65rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border);
        white-space: nowrap;
    }

    .atbl tbody tr { transition: background .12s; }
    .atbl tbody tr:hover { background: rgba(255,255,255,.03); }

    .atbl tbody td {
        padding: .7rem 1.25rem;
        font-size: .8125rem;
        color: var(--text-secondary);
        border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
    }

    .atbl tbody tr:last-child td { border-bottom: none; }

    /* Row number */
    .row-num {
        font-size: .7rem;
        color: var(--text-muted);
        font-weight: 500;
        font-variant-numeric: tabular-nums;
    }

    /* NIM chip */
    .nim-chip {
        display: inline-flex;
        align-items: center;
        background: rgba(79,70,229,.1);
        color: #a5b4fc;
        border: 1px solid rgba(79,70,229,.18);
        border-radius: 5px;
        padding: .15rem .5rem;
        font-size: .7rem;
        font-weight: 600;
        font-family: 'Inter', monospace;
        letter-spacing: .02em;
    }

    /* Avatar + name */
    .av-wrap { display: flex; align-items: center; gap: .6rem; }

    .av {
        flex-shrink: 0;
        width: 30px; height: 30px;
        border-radius: 50%;
        background: var(--accent);
        display: flex; align-items: center; justify-content: center;
        font-size: .7rem;
        font-weight: 700;
        color: #fff;
    }

    .av-name  { font-size: .8125rem; font-weight: 600; color: var(--text); }
    .av-kelas { font-size: .7rem; color: var(--text-muted); margin-top: 1px; }

    /* Date & time */
    .date-txt { font-size: .8125rem; color: var(--text-secondary); }

    .time-chip {
        display: inline-block;
        background: rgba(79,70,229,.1);
        color: #a5b4fc;
        border: 1px solid rgba(79,70,229,.15);
        border-radius: 5px;
        padding: .12rem .5rem;
        font-size: .7rem;
        font-weight: 600;
    }

    /* Status badge */
    .sbadge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .2rem .6rem;
        border-radius: 5px;
        font-size: .7rem;
        font-weight: 600;
        text-transform: capitalize;
    }

    .sbadge.hadir { background: rgba(16,185,129,.1); color: #34d399; border: 1px solid rgba(16,185,129,.2); }
    .sbadge.izin  { background: rgba(245,158,11,.1); color: #fbbf24; border: 1px solid rgba(245,158,11,.2); }
    .sbadge.alpha { background: rgba(239,68,68,.1); color: #f87171; border: 1px solid rgba(239,68,68,.2); }

    .sdot {
        width: 5px; height: 5px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .sbadge.hadir .sdot { background: #10b981; }
    .sbadge.izin  .sdot { background: #f59e0b; }
    .sbadge.alpha .sdot { background: #ef4444; }

    /* ===== EMPTY STATE ===== */
    .empty-box {
        padding: 3.5rem 2rem;
        text-align: center;
    }

    .empty-box .e-icon {
        font-size: 2.5rem;
        margin-bottom: .75rem;
        opacity: 0.3;
    }

    .empty-box p { color: var(--text-muted); font-size: .8125rem; }

    /* ===== FOOTER ===== */
    .tbl-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: .75rem 1.25rem;
        border-top: 1px solid var(--border);
        font-size: .7rem;
        color: var(--text-muted);
    }

    .tbl-footer-badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        background: rgba(79,70,229,.08);
        border: 1px solid rgba(79,70,229,.15);
        border-radius: 5px;
        padding: .2rem .5rem;
        color: #a5b4fc;
        font-size: .65rem;
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
    <div style="font-size: 0.7rem; color: var(--text-muted);">
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
        <div>Menampilkan <strong style="color:var(--text-secondary)" id="visibleCount">{{ $total }}</strong> dari {{ $total }} record</div>
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
