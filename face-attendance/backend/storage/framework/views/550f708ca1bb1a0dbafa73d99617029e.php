<?php $__env->startSection('content'); ?>

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
                <span style="font-weight:700;">Data Absensi Anda</span>
            </div>
            
            <div style="overflow-x:auto;">
                <table class="table table-dark table-hover mb-0" style="background:transparent;">
                    <thead style="background:rgba(255,255,255,0.03);">
                        <tr>
                            <th class="px-4 py-3 border-0" style="font-size:.7rem; color:var(--muted); text-transform:uppercase; letter-spacing:1px;">Tanggal</th>
                            <th class="px-4 py-3 border-0" style="font-size:.7rem; color:var(--muted); text-transform:uppercase; letter-spacing:1px;">Jam</th>
                            <th class="px-4 py-3 border-0" style="font-size:.7rem; color:var(--muted); text-transform:uppercase; letter-spacing:1px;">Status</th>
                            <th class="px-4 py-3 border-0" style="font-size:.7rem; color:var(--muted); text-transform:uppercase; letter-spacing:1px;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $absensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.04);">
                            <td class="px-4 py-3 border-0">
                                <div style="font-weight:600; color:#fff;"><?php echo e(\Carbon\Carbon::parse($row->tanggal)->translatedFormat('d F Y')); ?></div>
                            </td>
                            <td class="px-4 py-3 border-0">
                                <div style="color:rgba(255,255,255,0.7); font-family:monospace;"><?php echo e(substr($row->waktu, 0, 5)); ?> WIB</div>
                            </td>
                            <td class="px-4 py-3 border-0">
                                <?php if($row->status == 'hadir'): ?>
                                    <span style="background:rgba(16,185,129,0.1); color:#10b981; border:1px solid rgba(16,185,129,0.2); padding:0.25rem 0.75rem; border-radius:8px; font-size:0.75rem; font-weight:700; text-transform:uppercase;">Hadir</span>
                                <?php elseif($row->status == 'izin'): ?>
                                    <span style="background:rgba(245,158,11,0.1); color:#f59e0b; border:1px solid rgba(245,158,11,0.2); padding:0.25rem 0.75rem; border-radius:8px; font-size:0.75rem; font-weight:700; text-transform:uppercase;">Izin</span>
                                <?php else: ?>
                                    <span style="background:rgba(239,68,68,0.1); color:#ef4444; border:1px solid rgba(239,68,68,0.2); padding:0.25rem 0.75rem; border-radius:8px; font-size:0.75rem; font-weight:700; text-transform:uppercase;">Alpha</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 border-0">
                                <div style="color:rgba(255,255,255,0.4); font-size:0.85rem;"><?php echo e($row->keterangan ?? '-'); ?></div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 border-0">
                                <div style="font-size:3rem; opacity:0.2; margin-bottom:1rem;">📭</div>
                                <div style="color:rgba(255,255,255,0.3);">Belum ada riwayat absensi.</div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="p-4" style="border-top:1px solid rgba(255,255,255,0.05); font-size:0.8rem; color:var(--muted);">
                Menampilkan <strong><?php echo e(count($absensi)); ?></strong> data riwayat.
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Face_Recognition_PCD\face-attendance\backend\resources\views/mahasiswa/riwayat.blade.php ENDPATH**/ ?>