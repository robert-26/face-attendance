@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="glass-card mt-4">
            <div class="glass-card-header d-flex align-items-center">
                <span style="font-size: 1.5rem; margin-right: 10px;">📷</span> Ambil Dataset Wajah
            </div>
            <div class="glass-card-body text-center py-5">
                <div class="mb-4">
                    <div style="font-size: 0.9rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">NIM Mahasiswa</div>
                    <div style="font-size: 2rem; font-weight: 700; color: var(--primary-accent); font-family: monospace;">{{ $nim }}</div>
                </div>
                
                <p class="mb-4" style="color: var(--text-main); font-size: 1.1rem;">
                    Tekan tombol di bawah untuk membuka kamera dan merekam dataset wajah (sekitar 20-30 gambar).
                    <br><small style="color: var(--text-muted);">Pastikan pencahayaan cukup dan wajah terlihat jelas.</small>
                </p>
                
                <div id="cameraContainer" style="display: none; margin-bottom: 20px;">
                    <video id="webcam" autoplay playsinline style="width: 100%; max-width: 500px; border-radius: 12px; background: #000; margin: 0 auto; display: block; transform: scaleX(-1);"></video>
                    <canvas id="canvas" style="display: none;"></canvas>
                    <div class="progress mt-3" style="max-width: 500px; margin: 0 auto; height: 25px; border-radius: 12px; background: rgba(255,255,255,0.1);">
                        <div id="captureProgress" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%; font-weight: bold;">0 / 25</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('mahasiswa.capture.run') }}" id="captureForm" class="d-inline-block">
                    @csrf
                    <input type="hidden" name="nim" value="{{ $nim }}">
                    <input type="hidden" name="images" id="imagesData">
                    
                    <button type="button" id="btnStart" class="btn-modern btn-primary-modern btn-lg px-4 py-3 mx-2 shadow" onclick="startCaptureProcess()">
                        <span style="font-size: 1.2rem; margin-right: 8px;">⏺️</span> Buka Kamera & Rekam
                    </button>
                    <a href="{{ route('mahasiswa.index') }}" class="btn-modern btn-secondary-modern btn-lg px-4 py-3 mx-2">
                        Kembali
                    </a>
                </form>

<script>
    let video = document.getElementById('webcam');
    let canvas = document.getElementById('canvas');
    let stream = null;
    let capturedImages = [];
    let isCapturing = false;

    function startCaptureProcess() {
        document.getElementById('btnStart').style.display = 'none';
        document.getElementById('cameraContainer').style.display = 'block';

        if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({ video: true }).then(function(s) {
                stream = s;
                video.srcObject = stream;
                video.play();
                
                // Wait 1 second for camera to adjust light, then start capturing
                setTimeout(captureFrames, 1000);
            }).catch(function(err) {
                alert("Gagal mengakses kamera: " + err);
                document.getElementById('btnStart').style.display = 'inline-block';
                document.getElementById('cameraContainer').style.display = 'none';
            });
        } else {
            alert("Kamera tidak didukung di browser ini.");
        }
    }

    function captureFrames() {
        if (capturedImages.length >= 25) {
            // Done capturing
            stream.getTracks().forEach(track => track.stop());
            document.getElementById('imagesData').value = JSON.stringify(capturedImages);
            document.getElementById('captureProgress').innerText = "Selesai! Menyimpan...";
            document.getElementById('captureForm').submit();
            return;
        }

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        let ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        let dataURL = canvas.toDataURL('image/jpeg', 0.8);
        capturedImages.push(dataURL);
        
        let percentage = (capturedImages.length / 25) * 100;
        document.getElementById('captureProgress').style.width = percentage + '%';
        document.getElementById('captureProgress').innerText = capturedImages.length + " / 25";

        // Capture next frame after 200ms
        setTimeout(captureFrames, 200);
    }
</script>
            </div>
        </div>
    </div>
</div>
@endsection
