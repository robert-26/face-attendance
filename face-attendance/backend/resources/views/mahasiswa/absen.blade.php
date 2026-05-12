@extends('layouts.app')

@section('content')
<style>
    .mhs-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .profile-card {
        background: linear-gradient(135deg, rgba(167,139,250,0.1), rgba(124,58,237,0.05));
        border: 1px solid rgba(167,139,250,0.2);
        border-radius: 20px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
    .profile-av {
        width: 64px; height: 64px;
        border-radius: 16px;
        background: linear-gradient(135deg, #7c3aed, #a78bfa);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; font-weight: 800; color: #fff;
        box-shadow: 0 8px 20px rgba(124,58,237,0.4);
    }
    .profile-info h2 { font-size: 1.25rem; font-weight: 800; margin: 0 0 0.2rem 0; color: #fff; }
    .profile-info p { margin: 0; color: rgba(255,255,255,0.6); font-size: 0.9rem; }
    
    .status-alert {
        padding: 1.25rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        font-weight: 600;
        font-size: 1.05rem;
    }
    .status-alert.hadir { background: rgba(52,211,153,0.15); border: 1px solid rgba(52,211,153,0.3); color: #34d399; }
    .status-alert.izin { background: rgba(251,191,36,0.15); border: 1px solid rgba(251,191,36,0.3); color: #fbbf24; }
    .status-alert.alpha { background: rgba(248,113,113,0.15); border: 1px solid rgba(248,113,113,0.3); color: #f87171; }
    
    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
    }
    
    .action-card {
        background: rgba(255,255,255,0.04);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        transition: transform 0.3s;
    }
    .action-card:hover { transform: translateY(-5px); }
    
    .action-icon {
        width: 80px; height: 80px;
        margin: 0 auto 1.5rem;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 2.5rem;
    }
    .icon-absen { background: rgba(52,211,153,0.1); color: #34d399; box-shadow: 0 0 30px rgba(52,211,153,0.2); }
    .icon-izin { background: rgba(251,191,36,0.1); color: #fbbf24; box-shadow: 0 0 30px rgba(251,191,36,0.2); }
    
    .action-title { font-size: 1.2rem; font-weight: 700; color: #fff; margin-bottom: 0.5rem; }
    .action-desc { font-size: 0.9rem; color: rgba(255,255,255,0.5); margin-bottom: 2rem; line-height: 1.5; }
    
    .absen-btn {
        width: 100%; padding: 1rem;
        border-radius: 12px; font-weight: 700; font-size: 1rem;
        border: none; cursor: pointer; transition: all 0.3s;
    }
    .btn-hadir { background: linear-gradient(135deg, #10b981, #059669); color: #fff; box-shadow: 0 4px 15px rgba(16,185,129,0.4); }
    .btn-hadir:hover { box-shadow: 0 8px 25px rgba(16,185,129,0.6); transform: translateY(-2px); }
    
    .btn-izin { background: linear-gradient(135deg, #f59e0b, #d97706); color: #fff; box-shadow: 0 4px 15px rgba(245,158,11,0.4); }
    .btn-izin:hover { box-shadow: 0 8px 25px rgba(245,158,11,0.6); transform: translateY(-2px); }
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
        <div class="profile-av">{{ strtoupper(substr($nama, 0, 1)) }}</div>
        <div class="profile-info">
            <h2>{{ $nama }}</h2>
            <p>{{ $nim }} • Kelas {{ $kelas }}</p>
        </div>
    </div>
</div>

<div class="glass-card mb-4">
    <div class="glass-card-body d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <div style="font-size: 0.85rem; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">Tanggal Hari Ini</div>
            <div style="font-size: 1.25rem; font-weight: 700; color: #fff;">{{ now()->translatedFormat('l, d F Y') }}</div>
        </div>
        <div class="text-end">
            <div style="font-size: 0.85rem; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem;">Batas Waktu Absen</div>
            <div style="font-size: 1.25rem; font-weight: 700; color: #f87171;">{{ $deadline }} WIB</div>
        </div>
    </div>
</div>

@if($sudahAbsen)
    <div class="status-alert {{ strtolower($sudahAbsen->status) }}">
        @if(strtolower($sudahAbsen->status) == 'hadir')
            <span style="font-size: 1.5rem;">✅</span> 
            <div>Anda telah tercatat <strong>HADIR</strong> pada pukul {{ $sudahAbsen->waktu }}. Terima kasih!</div>
        @elseif(strtolower($sudahAbsen->status) == 'izin')
            <span style="font-size: 1.5rem;">📝</span> 
            <div>Anda telah mengajukan <strong>IZIN</strong> pada pukul {{ $sudahAbsen->waktu }}. Keterangan: {{ $sudahAbsen->keterangan }}</div>
        @else
            <span style="font-size: 1.5rem;">❌</span> 
            <div>Anda tercatat <strong>ALPHA</strong> hari ini.</div>
        @endif
    </div>
@else
    <div class="action-grid">
        {{-- Card Absen Wajah --}}
        <div class="action-card">
            <div class="action-icon icon-absen">👤</div>
            <h3 class="action-title">Absensi Hadir</h3>
            <p class="action-desc">Sistem akan mengaktifkan kamera untuk memindai dan mengenali wajah Anda secara real-time.</p>
            
            <button type="button" class="absen-btn btn-hadir" onclick="openCameraModal()">
                📸 Pindai Wajah Sekarang
            </button>
        </div>

        {{-- Card Izin --}}
        <div class="action-card">
            <div class="action-icon icon-izin">📝</div>
            <h3 class="action-title">Pengajuan Izin</h3>
            <p class="action-desc">Berhalangan hadir? Ajukan izin dengan menyertakan keterangan yang jelas.</p>
            
            <button type="button" class="absen-btn btn-izin" data-bs-toggle="modal" data-bs-target="#izinModal">
                📄 Form Izin
            </button>
        </div>
    </div>
@endif

{{-- Modal Izin --}}
@push('modals')
{{-- Modal Izin --}}
<div class="modal fade" id="izinModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: rgba(15,12,41,0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; color: #fff;">
            <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                <h5 class="modal-title" style="font-weight: 700;">Pengajuan Izin</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('mhs.absen.izin') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label" style="color: rgba(255,255,255,0.6); font-size: 0.85rem; text-transform: uppercase;">Keterangan Izin</label>
                        <textarea class="form-control" name="keterangan" rows="4" placeholder="Tuliskan alasan izin Anda (Sakit, Acara Keluarga, dll)..." required style="background: rgba(255,255,255,0.05); color: #fff; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 1rem;"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.05);">
                    <button type="button" class="btn btn-secondary-modern" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning-modern" style="border-radius: 12px;">Kirim Pengajuan Izin</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Kamera --}}
<div class="modal fade" id="cameraModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: rgba(15,12,41,0.95); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; color: #fff;">
            <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                <h5 class="modal-title" style="font-weight: 700;">Ambil Foto Wajah</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="closeCamera()"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <video id="webcam" autoplay playsinline style="width: 100%; max-height: 400px; border-radius: 12px; background: #000; transform: scaleX(-1);"></video>
                <canvas id="canvas" style="display: none;"></canvas>
                <form action="{{ route('mhs.absen.wajah') }}" method="POST" id="formHadir" class="mt-3">
                    @csrf
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
@endpush

@endsection
