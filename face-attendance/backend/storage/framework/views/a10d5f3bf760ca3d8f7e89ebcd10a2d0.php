<?php $__env->startSection('content'); ?>
<style>
    .mhs-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .profile-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .profile-av {
        width: 44px; height: 44px;
        border-radius: 10px;
        background: var(--accent);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; font-weight: 700; color: #fff;
    }
    .profile-info h2 { font-size: 1rem; font-weight: 700; margin: 0 0 0.15rem 0; color: var(--text); }
    .profile-info p { margin: 0; color: var(--text-secondary); font-size: .78rem; }
    
    .status-alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: .75rem;
        font-weight: 600;
        font-size: .875rem;
    }
    .status-alert.hadir { background: rgba(16,185,129,.08); border: 1px solid rgba(16,185,129,.18); color: #34d399; }
    .status-alert.izin { background: rgba(245,158,11,.08); border: 1px solid rgba(245,158,11,.18); color: #fbbf24; }
    .status-alert.alpha { background: rgba(239,68,68,.08); border: 1px solid rgba(239,68,68,.18); color: #f87171; }
    .status-alert.closed { background: rgba(239,68,68,.08); border: 1px solid rgba(239,68,68,.18); color: #f87171; }
    
    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1rem;
    }
    
    .action-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 1.5rem;
        text-align: center;
        transition: border-color .15s;
    }
    .action-card:hover { border-color: rgba(255,255,255,.14); }
    
    .action-icon {
        width: 56px; height: 56px;
        margin: 0 auto 1.25rem;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
    }
    .icon-absen { background: rgba(16,185,129,.1); }
    .icon-izin { background: rgba(245,158,11,.1); }
    
    .action-title { font-size: 1rem; font-weight: 700; color: var(--text); margin-bottom: .35rem; }
    .action-desc { font-size: .8125rem; color: var(--text-secondary); margin-bottom: 1.5rem; line-height: 1.5; }
    
    .absen-btn {
        width: 100%; padding: .7rem;
        border-radius: 8px; font-weight: 600; font-size: .875rem;
        border: none; cursor: pointer; transition: background .15s;
        font-family: 'Inter', sans-serif;
    }
    .btn-hadir { background: var(--success); color: #fff; }
    .btn-hadir:hover { background: #059669; }
    
    .btn-izin { background: var(--warning); color: #fff; }
    .btn-izin:hover { background: #d97706; }
</style>

<div class="mhs-header">
    <div class="page-title-group">
        <div class="page-icon">📸</div>
        <div>
            <h1 class="page-title">Portal Absensi</h1>
            <p class="page-subtitle">Silakan lakukan absensi harian Anda</p>
        </div>
    </div>
    
    <div class="profile-card">
        <div class="profile-av"><?php echo e(strtoupper(substr($nama, 0, 1))); ?></div>
        <div class="profile-info">
            <h2><?php echo e($nama); ?></h2>
            <p><?php echo e($nim); ?> · Kelas <?php echo e($kelas); ?></p>
        </div>
    </div>
</div>

<div class="glass-card mb-4">
    <div class="glass-card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.2rem;">Tanggal Hari Ini</div>
            <div style="font-size:1rem;font-weight:700;color:var(--text);"><?php echo e(now()->translatedFormat('l, d F Y')); ?></div>
        </div>
        <div class="text-end">
            <div style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.2rem;">Batas Waktu Absen</div>
            <div style="font-size:1rem;font-weight:700;color:#f87171;"><?php echo e($deadline); ?> WIB</div>
        </div>
    </div>
</div>

<?php if($sudahAbsen): ?>
    <div class="status-alert <?php echo e(strtolower($sudahAbsen->status)); ?>">
        <?php if(strtolower($sudahAbsen->status) == 'hadir'): ?>
            <span style="font-size: 1.25rem;">✅</span> 
            <div>Anda telah tercatat <strong>HADIR</strong> pada pukul <?php echo e($sudahAbsen->waktu); ?>. Terima kasih!</div>
        <?php elseif(strtolower($sudahAbsen->status) == 'izin'): ?>
            <span style="font-size: 1.25rem;">📝</span> 
            <div>Anda telah mengajukan <strong>IZIN</strong> pada pukul <?php echo e($sudahAbsen->waktu); ?>. Keterangan: <?php echo e($sudahAbsen->keterangan); ?></div>
        <?php else: ?>
            <span style="font-size: 1.25rem;">❌</span> 
            <div>Anda tercatat <strong>ALPHA</strong> hari ini.</div>
        <?php endif; ?>
    </div>
<?php elseif($isPastDeadline): ?>
    <div class="status-alert closed">
        <span style="font-size: 1.25rem;">!</span>
        <div>Absensi hari ini sudah ditutup. Batas waktu absensi adalah pukul <strong><?php echo e($deadline); ?> WIB</strong>.</div>
    </div>
<?php else: ?>
    <div class="action-grid">
        
        <div class="action-card">
            <div class="action-icon icon-absen">👤</div>
            <h3 class="action-title">Absensi Hadir</h3>
            <p class="action-desc">Sistem akan mengaktifkan kamera untuk memindai dan mengenali wajah Anda secara real-time.</p>
            
            <button type="button" class="absen-btn btn-hadir" onclick="openCameraModal()">
                📸 Pindai Wajah Sekarang
            </button>
        </div>

        
        <div class="action-card">
            <div class="action-icon icon-izin">📝</div>
            <h3 class="action-title">Pengajuan Izin</h3>
            <p class="action-desc">Berhalangan hadir? Ajukan izin dengan menyertakan keterangan yang jelas.</p>
            
            <button type="button" class="absen-btn btn-izin" data-bs-toggle="modal" data-bs-target="#izinModal">
                📄 Form Izin
            </button>
        </div>
    </div>
<?php endif; ?>


<?php $__env->startPush('modals'); ?>

<div class="modal fade" id="izinModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; color: var(--text);">
            <div class="modal-header" style="border-bottom: 1px solid var(--border);">
                <h5 class="modal-title" style="font-weight: 600; font-size:.875rem;">Pengajuan Izin</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('mhs.absen.izin')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Keterangan Izin</label>
                        <textarea class="form-control" name="keterangan" rows="4" placeholder="Tuliskan alasan izin Anda (Sakit, Acara Keluarga, dll)..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--border);">
                    <button type="button" class="btn btn-secondary-modern" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning-modern" style="border-radius: 8px; padding:.45rem .875rem; font-size:.8125rem; font-weight:600; border:none; cursor:pointer;">Kirim Pengajuan Izin</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="cameraModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--surface); border: 1px solid var(--border); border-radius: 12px; color: var(--text);">
            <div class="modal-header" style="border-bottom: 1px solid var(--border);">
                <h5 class="modal-title" style="font-weight: 600; font-size:.875rem;">Ambil Foto Wajah</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="closeCamera()"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <video id="webcam" autoplay playsinline style="width: 100%; max-height: 400px; border-radius: 8px; background: #000; transform: scaleX(-1);"></video>
                <canvas id="canvas" style="display: none;"></canvas>
                <form action="<?php echo e(route('mhs.absen.wajah')); ?>" method="POST" id="formHadir" class="mt-3">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="image" id="imageData">
                    <button type="button" id="btnCapture" class="absen-btn btn-hadir" onclick="captureAndSubmit()">
                        📸 Ambil Foto & Absen
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let video = document.getElementById('webcam');
    let canvas = document.getElementById('canvas');
    let stream = null;

    function openCameraModal() {
        var myModal = new bootstrap.Modal(document.getElementById('cameraModal'));
        myModal.show();
        
        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true }).then(function(s) {
                stream = s;
                video.srcObject = stream;
                video.play();
            }).catch(function(err) {
                alert("Gagal mengakses kamera: " + err);
            });
        } else {
            alert("Kamera tidak didukung di browser ini.");
        }
    }

    function closeCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    }

    document.getElementById('cameraModal').addEventListener('hidden.bs.modal', function () {
        closeCamera();
    });

    function captureAndSubmit() {
        let btn = document.getElementById('btnCapture');
        btn.innerHTML = 'Memproses... ⏳';
        btn.disabled = true;

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        let ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        let dataURL = canvas.toDataURL('image/jpeg', 0.8);
        document.getElementById('imageData').value = dataURL;
        
        document.getElementById('formHadir').submit();
    }
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\Face_Recognition_PCD\face-attendance\backend\resources\views/mahasiswa/absen.blade.php ENDPATH**/ ?>