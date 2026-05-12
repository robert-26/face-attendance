<?php $__env->startSection('content'); ?>
<style>
    /* === SEARCH BAR === */
    .mhs-search-wrap { position: relative; }
    .mhs-search-inp {
        background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.12);
        border-radius: 12px; color: #fff; font-family: 'Inter',sans-serif;
        font-size: .875rem; padding: .65rem 1rem .65rem 2.5rem; outline: none;
        width: 240px; transition: all .3s;
    }
    .mhs-search-inp::placeholder { color: rgba(255,255,255,.28); }
    .mhs-search-inp:focus {
        border-color: var(--accent); background: rgba(167,139,250,.08);
        box-shadow: 0 0 0 3px rgba(167,139,250,.12); width: 290px;
    }
    .mhs-s-icon { position: absolute; left: .85rem; top: 50%; transform: translateY(-50%); font-size: .85rem; opacity: .4; pointer-events: none; }

    /* === STUDENT TABLE === */
    .mhs-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .mhs-table thead tr { background: rgba(255,255,255,.03); }
    .mhs-table thead th {
        padding: .9rem 1.5rem; font-size: .7rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,.35);
        border-bottom: 1px solid rgba(255,255,255,.07); white-space: nowrap;
    }
    .mhs-table tbody tr { transition: background .2s; animation: mhsRowIn .45s cubic-bezier(.16,1,.3,1) both; }
    .mhs-table tbody tr:hover { background: rgba(167,139,250,.05); }
    .mhs-table tbody td {
        padding: .95rem 1.5rem; font-size: .875rem;
        color: rgba(255,255,255,.78); border-bottom: 1px solid rgba(255,255,255,.04);
        vertical-align: middle;
    }
    .mhs-table tbody tr:last-child td { border-bottom: none; }
    .mhs-table tbody tr:nth-child(1){animation-delay:.04s}
    .mhs-table tbody tr:nth-child(2){animation-delay:.08s}
    .mhs-table tbody tr:nth-child(3){animation-delay:.12s}
    .mhs-table tbody tr:nth-child(4){animation-delay:.16s}
    .mhs-table tbody tr:nth-child(n+5){animation-delay:.20s}
    @keyframes mhsRowIn { from{opacity:0;transform:translateX(-8px)} to{opacity:1;transform:translateX(0)} }

    /* Cells */
    .nim-chip {
        display: inline-flex; align-items: center;
        background: rgba(167,139,250,.12); color: #c4b5fd;
        border: 1px solid rgba(167,139,250,.22); border-radius: 8px;
        padding: .22rem .7rem; font-size: .78rem; font-weight: 700;
        font-family: 'Courier New',monospace; letter-spacing: .5px;
    }
    .mhs-av-wrap { display: flex; align-items: center; gap: .7rem; }
    .mhs-av {
        flex-shrink: 0; width: 38px; height: 38px; border-radius: 50%;
        background: linear-gradient(135deg,#7c3aed,#a78bfa);
        display: flex; align-items: center; justify-content: center;
        font-size: .85rem; font-weight: 700; color: #fff;
        box-shadow: 0 4px 10px rgba(124,58,237,.4);
    }
    .mhs-name { font-size: .875rem; font-weight: 600; color: #e2e8f0; }
    .kelas-chip {
        display: inline-block; background: rgba(99,102,241,.12);
        color: #a5b4fc; border: 1px solid rgba(99,102,241,.2);
        border-radius: 8px; padding: .2rem .65rem; font-size: .78rem; font-weight: 600;
    }

    /* === ADD FORM CARD === */
    .add-card {
        background: rgba(255,255,255,.04); backdrop-filter: blur(16px);
        border: 1px solid rgba(255,255,255,.08); border-radius: 20px;
        overflow: hidden; position: sticky; top: 24px;
    }
    .add-card-header {
        padding: 1.25rem 1.75rem; border-bottom: 1px solid rgba(255,255,255,.07);
        font-size: 1rem; font-weight: 700; color: #fff;
        display: flex; align-items: center; gap: .6rem;
    }
    .add-card-icon {
        width: 32px; height: 32px; border-radius: 9px;
        background: linear-gradient(135deg,#10b981,#059669);
        display: flex; align-items: center; justify-content: center; font-size: .9rem;
        box-shadow: 0 4px 10px rgba(16,185,129,.4);
    }
    .add-card-body { padding: 1.5rem 1.75rem; }

    /* Table footer */
    .tbl-foot {
        display: flex; align-items: center; justify-content: space-between;
        padding: .9rem 1.5rem; border-top: 1px solid rgba(255,255,255,.06);
        font-size: .78rem; color: rgba(255,255,255,.28);
    }
</style>


<div class="page-header">
    <div class="page-title-group">
        <div class="page-icon">👥</div>
        <div>
            <h1 class="page-title">Kelola Mahasiswa</h1>
            <p class="page-subtitle">Daftar & registrasi data mahasiswa</p>
        </div>
    </div>
    <div style="font-size:.8rem;color:var(--muted);">
        Total: <strong style="color:var(--accent)"><?php echo e(count($mahasiswas)); ?></strong> mahasiswa
    </div>
</div>

<div class="row g-4">

    <div class="col-12">
        <div class="glass-card">

            
            <div class="glass-card-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;">
                <span style="font-weight:700;">Daftar Mahasiswa</span>
                <div style="display:flex; gap:1rem; align-items:center; flex-wrap:wrap;">
                    <div class="mhs-search-wrap">
                        <span class="mhs-s-icon">🔍</span>
                        <input type="text" class="mhs-search-inp" id="mhsSearch" placeholder="Cari NIM, nama, kelas…">
                    </div>
                    <a href="<?php echo e(route('train.model')); ?>" class="btn-modern btn-primary-modern" style="padding: .65rem 1rem;" onclick="this.innerHTML='🧠 Memproses...'; this.style.opacity=0.7;">
                        🧠 Latih Model
                    </a>
                    <button type="button" class="btn-modern btn-success-modern" style="padding: .65rem 1rem;" data-bs-toggle="modal" data-bs-target="#addMahasiswaModal">
                        ✨ Tambah Mahasiswa
                    </button>
                </div>
            </div>

            
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
                        <?php $__empty_1 = true; $__currentLoopData = $mahasiswas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php $initials = strtoupper(substr($m->nama ?? '?', 0, 1)); ?>
                            <tr data-search="<?php echo e(strtolower($m->nim . ' ' . $m->nama . ' ' . $m->kelas)); ?>">
                                <td style="color:rgba(255,255,255,.2);font-size:.72rem;font-weight:600;"><?php echo e($i + 1); ?></td>
                                <td><span class="nim-chip"><?php echo e($m->nim); ?></span></td>
                                <td>
                                    <div class="mhs-av-wrap">
                                        <div class="mhs-av"><?php echo e($initials); ?></div>
                                        <div class="mhs-name"><?php echo e($m->nama); ?></div>
                                    </div>
                                </td>
                                <td><span class="kelas-chip"><?php echo e($m->kelas); ?></span></td>
                                <td style="text-align:right;">
                                    <div style="display:flex;gap:.5rem;justify-content:flex-end;">
                                        <a href="<?php echo e(route('mahasiswa.capture.page', $m->nim)); ?>"
                                           class="btn-modern btn-primary-modern"
                                           style="padding:.38rem .85rem;font-size:.78rem;">
                                            📷 Dataset
                                        </a>
                                        <form method="POST" action="<?php echo e(route('mahasiswa.destroy', $m->id)); ?>" style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <button class="btn-modern btn-danger-modern"
                                                    style="padding:.38rem .75rem;font-size:.78rem;"
                                                    onclick="return confirm('Hapus <?php echo e($m->nama); ?>?');">
                                                🗑
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr id="emptyRow">
                                <td colspan="5" style="text-align:center;padding:4rem 1rem;color:rgba(255,255,255,.3);">
                                    <div style="font-size:3rem;margin-bottom:1rem;opacity:.4;">📭</div>
                                    Belum ada data mahasiswa.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            
            <div class="tbl-foot">
                <span>Menampilkan <strong id="mhsCount" style="color:rgba(255,255,255,.55)"><?php echo e(count($mahasiswas)); ?></strong> dari <?php echo e(count($mahasiswas)); ?> data</span>
                <span><?php echo e(now()->translatedFormat('d M Y')); ?></span>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('modals'); ?>
    
    <div class="modal fade" id="addMahasiswaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: rgba(15,12,41,0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; color: #fff;">
                <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <h5 class="modal-title" style="font-weight: 700; display:flex; align-items:center; gap:0.5rem;">
                        <div class="add-card-icon" style="width:28px; height:28px; font-size:0.8rem;">✨</div>
                        Tambah Mahasiswa
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="<?php echo e(route('mahasiswa.store')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label" for="nim" style="color: rgba(255,255,255,0.6); font-size: 0.85rem; text-transform: uppercase;">NIM</label>
                            <input type="text" class="form-control" id="nim" name="nim" value="<?php echo e(old('nim')); ?>"
                                   placeholder="Contoh: 19051234" required style="background: rgba(255,255,255,0.05); color: #fff; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="nama" style="color: rgba(255,255,255,0.6); font-size: 0.85rem; text-transform: uppercase;">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo e(old('nama')); ?>"
                                   placeholder="Masukkan nama…" required style="background: rgba(255,255,255,0.05); color: #fff; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px;">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="kelas" style="color: rgba(255,255,255,0.6); font-size: 0.85rem; text-transform: uppercase;">Kelas</label>
                            <input type="text" class="form-control" id="kelas" name="kelas" value="<?php echo e(old('kelas')); ?>"
                                   placeholder="Contoh: TI-3A" required style="background: rgba(255,255,255,0.05); color: #fff; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px;">
                        </div>
                        
                        
                        <div style="padding:1rem;background:rgba(167,139,250,.07);border:1px solid rgba(167,139,250,.15);border-radius:12px;">
                            <div style="font-size:.75rem;font-weight:700;color:var(--accent);text-transform:uppercase;letter-spacing:.5px;margin-bottom:.5rem;">ℹ️ Info</div>
                            <ul style="font-size:.78rem;color:rgba(255,255,255,.45);padding-left:1.2rem;margin:0;line-height:1.7;">
                                <li>Setelah menambah mahasiswa, ambil <strong style="color:rgba(255,255,255,.65)">dataset wajah</strong>.</li>
                                <li>Latih ulang model setelah dataset baru ditambahkan.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.05);">
                        <button type="button" class="btn btn-secondary-modern" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-modern btn-success-modern" style="border-radius: 12px; padding: 0.5rem 1rem;">
                            💾 Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopPush(); ?>

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

<?php if($errors->any()): ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('addMahasiswaModal'));
        myModal.show();
    });
</script>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Face_Recognition_PCD\face-attendance\backend\resources\views/mahasiswa/index.blade.php ENDPATH**/ ?>