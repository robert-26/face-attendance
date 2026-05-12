@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="page-title-group">
                <div class="page-icon">📷</div>
                <div>
                    <h1 class="page-title">Ambil Dataset Wajah</h1>
                    <p class="page-subtitle">Rekam wajah mahasiswa untuk pelatihan model</p>
                </div>
            </div>
        </div>

        <div class="glass-card">
            <div class="glass-card-header">
                Mahasiswa: <span style="font-family:'Inter',monospace;color:#a5b4fc;font-weight:700;margin-left:.25rem;">{{ $nim }}</span>
            </div>
            <div class="glass-card-body text-center" style="padding:2rem 1.5rem;">

                <p style="color:var(--text-secondary);font-size:.875rem;line-height:1.6;margin-bottom:1.5rem;">
                    Tekan tombol di bawah untuk membuka kamera dan merekam dataset wajah (sekitar 25 gambar).<br>
                    <span style="color:var(--text-muted);font-size:.8rem;">Pastikan pencahayaan cukup dan wajah terlihat jelas.</span>
                </p>

                <div id="cameraContainer" style="display: none; margin-bottom: 1.5rem;">
                    <video id="webcam" autoplay playsinline
                           style="width: 100%; max-width: 480px; border-radius: 8px; background: #000; margin: 0 auto; display: block; transform: scaleX(-1); border: 1px solid var(--border);">
                    </video>
                    <canvas id="canvas" style="display: none;"></canvas>

                    {{-- Progress bar --}}
                    <div style="max-width:480px;margin:1rem auto 0;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:.4rem;">
                            <span style="font-size:.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:.04em;">Progress</span>
                            <span style="font-size:.7rem;color:var(--text-secondary);" id="progressLabel">0 / 25</span>
                        </div>
                        <div class="progress" style="height:6px;border-radius:4px;background:rgba(255,255,255,.06);">
                            <div id="captureProgress"
                                 class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                 role="progressbar"
                                 style="width: 0%; border-radius:4px; font-weight:bold;">
                                0 / 25
                            </div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('mahasiswa.capture.run') }}" id="captureForm" class="d-inline-flex gap-2">
                    @csrf
                    <input type="hidden" name="nim" value="{{ $nim }}">
                    <input type="hidden" name="images" id="imagesData">

                    <button type="button" id="btnStart"
                            class="btn-modern btn-primary-modern"
                            style="padding:.6rem 1.25rem;"
                            onclick="startCaptureProcess()">
                        ⏺ Buka Kamera &amp; Rekam
                    </button>
                    <a href="{{ route('mahasiswa.index') }}" class="btn-modern btn-secondary-modern" style="padding:.6rem 1.25rem;">
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
            document.getElementById('progressLabel').textContent = "Selesai!";
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
        document.getElementById('progressLabel').textContent = capturedImages.length + " / 25";

        // Capture next frame after 200ms
        setTimeout(captureFrames, 200);
    }
</script>
            </div>
        </div>
    </div>
</div>
@endsection
