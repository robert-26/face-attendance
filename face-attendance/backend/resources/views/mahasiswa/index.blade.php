@extends('layouts.app')

@section('content')
<style>
    /* === SEARCH BAR === */
    .mhs-search-wrap { position: relative; }
    .mhs-search-inp {
        background: rgba(255,255,255,.04); border: 1px solid var(--border);
        border-radius: 7px; color: var(--text); font-family: 'Inter',sans-serif;
        font-size: .8125rem; padding: .45rem .75rem .45rem 2rem; outline: none;
        width: 200px; transition: all .15s;
    }
    .mhs-search-inp::placeholder { color: var(--text-muted); }
    .mhs-search-inp:focus {
        border-color: var(--accent); background: rgba(79,70,229,.05);
        box-shadow: 0 0 0 3px rgba(79,70,229,.1); width: 240px;
    }
    .mhs-s-icon { position: absolute; left: .7rem; top: 50%; transform: translateY(-50%); font-size: .75rem; opacity: .4; pointer-events: none; }

    /* === STUDENT TABLE === */
    .mhs-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .mhs-table thead tr { background: rgba(255,255,255,.02); }
    .mhs-table thead th {
        padding: .65rem 1.25rem; font-size: .65rem; font-weight: 600;
        text-transform: uppercase; letter-spacing: .06em; color: var(--text-muted);
        border-bottom: 1px solid var(--border); white-space: nowrap;
    }
    .mhs-table tbody tr { transition: background .12s; }
    .mhs-table tbody tr:hover { background: rgba(255,255,255,.03); }
    .mhs-table tbody td {
        padding: .7rem 1.25rem; font-size: .8125rem;
        color: var(--text-secondary); border-bottom: 1px solid var(--border-light);
        vertical-align: middle;
    }
    .mhs-table tbody tr:last-child td { border-bottom: none; }

    /* Cells */
    .nim-chip {
        display: inline-flex; align-items: center;
        background: rgba(79,70,229,.1); color: #a5b4fc;
        border: 1px solid rgba(79,70,229,.18); border-radius: 5px;
        padding: .15rem .5rem; font-size: .7rem; font-weight: 600;
        font-family: 'Inter', monospace; letter-spacing: .02em;
    }
    .mhs-av-wrap { display: flex; align-items: center; gap: .6rem; }
    .mhs-av {
        flex-shrink: 0; width: 30px; height: 30px; border-radius: 50%;
        background: var(--accent);
        display: flex; align-items: center; justify-content: center;
        font-size: .7rem; font-weight: 700; color: #fff;
    }
    .mhs-name { font-size: .8125rem; font-weight: 600; color: var(--text); }
    .kelas-chip {
        display: inline-block; background: rgba(79,70,229,.08);
        color: #a5b4fc; border: 1px solid rgba(79,70,229,.12);
        border-radius: 5px; padding: .12rem .5rem; font-size: .7rem; font-weight: 600;
    }

    /* === ADD FORM CARD === */
    .add-card {
        background: var(--surface);
        border: 1px solid var(--border); border-radius: 10px;
        overflow: hidden; position: sticky; top: 24px;
    }
    .add-card-header {
        padding: .875rem 1.25rem; border-bottom: 1px solid var(--border);
        font-size: .8125rem; font-weight: 600; color: var(--text);
        display: flex; align-items: center; gap: .5rem;
    }
    .add-card-icon {
        width: 26px; height: 26px; border-radius: 6px;
        background: var(--success);
        display: flex; align-items: center; justify-content: center; font-size: .75rem;
    }
    .add-card-body { padding: 1.25rem; }

    /* Table footer */
    .tbl-foot {
        display: flex; align-items: center; justify-content: space-between;
        padding: .65rem 1.25rem; border-top: 1px solid var(--border);
        font-size: .7rem; color: var(--text-muted);
    }
</style>

{{-- PAGE HEADER --}}
<div class="page-header">
    <div class="page-title-group">
        <div class="page-icon">👥</div>
        <div>
            <h1 class="page-title">Kelola Mahasiswa</h1>
            <p class="page-subtitle">Daftar & registrasi data mahasiswa</p>
        </div>
    </div>
    <div style="font-size:.75rem;color:var(--text-muted);">
        Total: <strong style="color:#a5b4fc">{{ count($mahasiswas) }}</strong> mahasiswa
    </div>
</div>

<div class="row g-4">

    <div class="col-12">
        <div class="glass-card">

            {{-- Toolbar --}}
            <div class="glass-card-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;">
                <span style="font-weight:600;">Daftar Mahasiswa</span>
                <div style="display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;">
                    <div class="mhs-search-wrap">
                        <span class="mhs-s-icon">🔍</span>
                        <input type="text" class="mhs-search-inp" id="mhsSearch" placeholder="Cari NIM, nama, kelas…">
                    </div>
                    <a href="{{ route('train.model') }}" class="btn-modern btn-primary-modern" style="padding: .45rem .75rem;" onclick="this.innerHTML='🧠 Memproses...'; this.style.opacity=0.7;">
                        🧠 Latih Model
                    </a>
                    <button type="button" class="btn-modern btn-success-modern" style="padding: .45rem .75rem;" data-bs-toggle="modal" data-bs-target="#addMahasiswaModal">
                        + Tambah Mahasiswa
                    </button>
                </div>
            </div>

            {{-- Table --}}
            <div style="overflow-x:auto;">
                <table class="mhs-table" id="mhsTable">
                    <thead>
                        <tr>
                            <th style="width:48px">#</th>
                            <th>NIM</th>
                            <th>Mahasiswa</th>
                            <th>Kelas</th>
                            <th style="text-align:right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="mhsBody">
                        @forelse($mahasiswas as $i => $m)
                            @php $initials = strtoupper(substr($m->nama ?? '?', 0, 1)); @endphp
                            <tr data-search="{{ strtolower($m->nim . ' ' . $m->nama . ' ' . $m->kelas) }}">
                                <td style="color:var(--text-muted);font-size:.7rem;font-weight:500;">{{ $i + 1 }}</td>
                                <td><span class="nim-chip">{{ $m->nim }}</span></td>
                                <td>
                                    <div class="mhs-av-wrap">
                                        <div class="mhs-av">{{ $initials }}</div>
                                        <div class="mhs-name">{{ $m->nama }}</div>
                                    </div>
                                </td>
                                <td><span class="kelas-chip">{{ $m->kelas }}</span></td>
                                <td style="text-align:right;">
                                    <div style="display:flex;gap:.35rem;justify-content:flex-end;">
                                        <a href="{{ route('mahasiswa.capture.page', $m->nim) }}"
                                           class="btn-modern btn-primary-modern"
                                           style="padding:.3rem .6rem;font-size:.7rem;">
                                            📷 Dataset
                                        </a>
                                        <form method="POST" action="{{ route('mahasiswa.destroy', $m->id) }}" style="display:inline;">
                                            @csrf
                                            <button class="btn-modern btn-danger-modern"
                                                    style="padding:.3rem .55rem;font-size:.7rem;"
                                                    onclick="return confirm('Hapus {{ $m->nama }}?');">
                                                🗑
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRow">
                                <td colspan="5" style="text-align:center;padding:3rem 1rem;color:var(--text-muted);">
                                    <div style="font-size:2rem;margin-bottom:.75rem;opacity:.3;">📭</div>
                                    Belum ada data mahasiswa.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer --}}
            <div class="tbl-foot">
                <span>Menampilkan <strong id="mhsCount" style="color:var(--text-secondary)">{{ count($mahasiswas) }}</strong> dari {{ count($mahasiswas) }} data</span>
                <span>{{ now()->translatedFormat('d M Y') }}</span>
            </div>
        </div>
    </div>
</div>

@push('modals')
    {{-- Modal Tambah Mahasiswa --}}
    <div class="modal fade" id="addMahasiswaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; color: var(--text);">
                <div class="modal-header" style="border-bottom: 1px solid var(--border);">
                    <h5 class="modal-title" style="font-weight: 600; display:flex; align-items:center; gap:0.4rem; font-size:.875rem;">
                        <div class="add-card-icon" style="width:24px; height:24px; font-size:0.7rem;">+</div>
                        Tambah Mahasiswa
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('mahasiswa.store') }}">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label" for="nim">NIM</label>
                            <input type="text" class="form-control" id="nim" name="nim" value="{{ old('nim') }}"
                                   placeholder="Contoh: 19051234" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="nama">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama') }}"
                                   placeholder="Masukkan nama…" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="kelas">Kelas</label>
                            <input type="text" class="form-control" id="kelas" name="kelas" value="{{ old('kelas') }}"
                                   placeholder="Contoh: TI-3A" required>
                        </div>
                        
                        {{-- Quick tips --}}
                        <div style="padding:.75rem;background:rgba(79,70,229,.06);border:1px solid rgba(79,70,229,.12);border-radius:8px;">
                            <div style="font-size:.65rem;font-weight:600;color:#a5b4fc;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.35rem;">ℹ️ Info</div>
                            <ul style="font-size:.7rem;color:var(--text-muted);padding-left:1rem;margin:0;line-height:1.6;">
                                <li>Setelah menambah mahasiswa, ambil <strong style="color:var(--text-secondary)">dataset wajah</strong>.</li>
                                <li>Latih ulang model setelah dataset baru ditambahkan.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid var(--border);">
                        <button type="button" class="btn btn-secondary-modern" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-modern btn-success-modern" style="padding: 0.45rem .875rem;">
                            💾 Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

<script>
    const mhsSearch = document.getElementById('mhsSearch');
    const mhsRows   = document.querySelectorAll('#mhsBody tr[data-search]');
    const mhsCount  = document.getElementById('mhsCount');

    mhsSearch.addEventListener('input', () => {
        const q = mhsSearch.value.toLowerCase().trim();
        let shown = 0;
        mhsRows.forEach(row => {
            const match = !q || row.dataset.search.includes(q);
            row.style.display = match ? '' : 'none';
            if (match) shown++;
        });
        if (mhsCount) mhsCount.textContent = shown;
    });
</script>

@if($errors->any())
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('addMahasiswaModal'));
        myModal.show();
    });
</script>
@endif

@endsection
