

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div class="page-title-group">
        <div class="page-icon">🏠</div>
        <div>
            <h1 class="page-title">Dashboard Admin</h1>
            <p class="page-subtitle">Ringkasan sistem absensi wajah</p>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="glass-card h-100">
            <div class="glass-card-body d-flex align-items-center">
                <div class="me-4" style="font-size: 3rem; opacity: 0.8;">🧑‍🎓</div>
                <div>
                    <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Jumlah Mahasiswa</div>
                    <div style="font-size: 2.5rem; font-weight: 700; line-height: 1; color: #a78bfa;"><?php echo e($mahasiswaCount); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="glass-card h-100">
            <div class="glass-card-body d-flex align-items-center">
                <div class="me-4" style="font-size: 3rem; opacity: 0.8;">📅</div>
                <div>
                    <div style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Absensi Hari Ini</div>
                    <div style="font-size: 2.5rem; font-weight: 700; line-height: 1; color: #34d399;"><?php echo e($absensiToday); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="glass-card">
            <div class="glass-card-header">
                🚀 Aksi Cepat
            </div>
            <div class="glass-card-body">
                <div class="d-flex gap-3 flex-wrap">
                    <a class="btn-modern btn-success-modern" href="<?php echo e(route('mahasiswa.index')); ?>">
                        <span>👥</span> Kelola Mahasiswa
                    </a>
                    <a class="btn-modern btn-primary-modern" href="<?php echo e(route('absensi.index')); ?>">
                        <span>📋</span> Lihat Absensi
                    </a>
                    <a class="btn-modern btn-warning-modern" href="<?php echo e(route('train.model')); ?>">
                        <span>🧠</span> Latih Model Wajah
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Face_Recognition_PCD\face-attendance\backend\resources\views/dashboard.blade.php ENDPATH**/ ?>