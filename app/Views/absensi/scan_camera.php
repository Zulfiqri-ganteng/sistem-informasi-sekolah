<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>


<!-- qr library (UMD) -->
<script src="<?= smart_url('assets/qr/qr-scanner.umd.min.js') ?>"></script>

<style>
    /* compact premium styling (sesuaikan theme) */
    .scan-container {
        max-width: 640px;
        margin: 30px auto;
    }

    .scan-card {
        background: linear-gradient(180deg, #0b1620, #072024);
        padding: 18px;
        border-radius: 12px;
        color: #e7fbf0;
        box-shadow: 0 14px 30px rgba(0, 0, 0, .45);
    }

    .scan-title {
        font-size: 20px;
        font-weight: 700;
    }

    .camera-box {
        width: 100%;
        aspect-ratio: 1;
        background: #000;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
    }

    #video {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform: scaleX(-1);
    }

    .scan-overlay {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: 70%;
        height: 70%;
        border-radius: 12px;
        border: 3px solid rgba(0, 255, 180, .9);
        box-shadow: 0 0 36px rgba(0, 255, 160, .06);
        pointer-events: none;
    }

    .controls {
        margin-top: 12px;
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .toast {
        margin-top: 10px;
        text-align: center;
    }

    .footer-note {
        font-size: 13px;
        color: #a7d3c8;
        margin-top: 10px;
        text-align: center;
    }
</style>

<div class="scan-container">
    <div class="scan-card">
        <div class="scan-title">Scan QR Absensi — Mode Profesional</div>
        <div class="small-muted">Auto-fit, deteksi token otomatis.</div>

        <div style="margin-top:14px;">
            <div class="camera-box" id="cameraBox">
                <video id="video" playsinline muted></video>
                <div class="scan-overlay" aria-hidden="true"></div>
            </div>

            <div id="statusArea" style="margin-top:12px;">
                <div id="statusMessage" class="small-muted">Menunggu kamera...</div>
            </div>
        </div>

        <div class="controls">
            <button id="swapBtn" class="btn btn-sm btn-outline-light">🔁 Ganti Kamera</button>
            <button id="startBtn" class="btn btn-sm btn-success">▶ Mulai Kamera</button>
            <button id="stopBtn" class="btn btn-sm btn-danger">■ Hentikan</button>
        </div>

        <div id="notifArea" class="toast"></div>
        <div class="footer-note">Jika kamera tidak berfungsi: periksa izin browser atau jalankan lewat HTTPS (localhost dianggap aman).</div>
    </div>
</div>

<!-- Suara beep scan berhasil -->
<audio id="beepSound" preload="auto">
    <source src="<?= base_url('assets/sounds/beep.mp3') ?>" type="audio/mpeg">
</audio>

<script>
    const videoElem = document.getElementById('video');
    const statusMessage = document.getElementById('statusMessage');
    const notifArea = document.getElementById('notifArea');
    const swapBtn = document.getElementById('swapBtn');
    const startBtn = document.getElementById('startBtn');
    const stopBtn = document.getElementById('stopBtn');
    const beep = document.getElementById('beep');

    let scanner = null;
    let availableCameras = [];
    let cameraIndex = 0;
    let usingFront = true;

    function showStatus(t, type = 'info') {
        statusMessage.textContent = t;
        notifArea.innerHTML = `<div class="alert alert-${type==='error'?'danger': type==='success'?'success':'secondary'} py-1 px-2" style="display:inline-block">${t}</div>`;
    }

    function showToast(t, lvl = 'info') {
        notifArea.innerHTML = `<div class="alert alert-${lvl==='error'?'danger': lvl==='success'?'success':'info'} py-1 px-2" style="display:inline-block">${t}</div>`;
        setTimeout(() => notifArea.innerHTML = '', 2500);
    }

    async function listCameras() {
        try {
            const cams = await QrScanner.listCameras(true);
            availableCameras = cams;
            return cams;
        } catch (e) {
            return [];
        }
    }

    function parseTokenFromText(text) {
        try {
            if (typeof text !== 'string') return null;
            if (text.indexOf('token=') !== -1) {
                const m = text.match(/[?&]token=([^&]+)/);
                if (m) return decodeURIComponent(m[1]);
            }
            if (/^[a-z0-9\-]{8,}$/i.test(text)) return text;
            return null;
        } catch (e) {
            return null;
        }
    }

    async function startScanner() {
        if (!window.QrScanner) {
            showStatus('Library QR tidak ditemukan.', 'error');
            return;
        }
        if (scanner) {
            try {
                await scanner.stop();
            } catch (e) {}
            scanner = null;
        }
        showStatus('Menyiapkan kamera...');
        try {
            await listCameras();
            let pref = usingFront ? 'user' : 'environment';
            let prefDevice = null;
            if (availableCameras.length) {
                for (let i = 0; i < availableCameras.length; i++) {
                    const c = availableCameras[i];
                    if (usingFront && /front|user|face/i.test(c.label)) {
                        prefDevice = c.id;
                        cameraIndex = i;
                        break;
                    }
                    if (!usingFront && /back|rear|environment/i.test(c.label)) {
                        prefDevice = c.id;
                        cameraIndex = i;
                        break;
                    }
                }
                if (!prefDevice) prefDevice = availableCameras[0].id;
            }

            scanner = new QrScanner(
                videoElem,
                result => {
                    let text = (typeof result === 'string') ? result :
                        (result && (result.data || result.rawValue || result.text || result.data?.data));

                    if (!text) return;

                    const token = parseTokenFromText(String(text));
                    if (!token) {
                        showToast('Token tidak dikenali dari QR', 'error');
                        return;
                    }

                    // 🔊 BEEP sound
                    const beep = document.getElementById('beepSound');
                    if (beep) {
                        beep.currentTime = 0;
                        beep.volume = 1.0;
                        beep.play().catch(() => {});
                    }

                    // Notif keren
                    showToast('QR Terdeteksi • Memproses...', 'success');

                    // Redirect cepat
                    setTimeout(() => {
                        window.location.href = "<?= smart_url('absensi/scan') ?>?token=" + encodeURIComponent(token);
                    }, 150);
                }, {
                    preferredCamera: prefDevice || pref,
                    highlightScanRegion: true,
                    highlightCodeOutline: true,
                    maxScansPerSecond: 6,
                    calculateScanRegion: (video) => {
                        const w = video.videoWidth,
                            h = video.videoHeight;
                        const size = Math.round(Math.min(w, h) * 0.72);
                        return {
                            x: Math.round((w - size) / 2),
                            y: Math.round((h - size) / 2),
                            width: size,
                            height: size,
                            downScaledWidth: 400,
                            downScaledHeight: 400
                        };
                    }
                }
            );

            if (prefDevice) await scanner.setCamera(prefDevice);
            await scanner.start();
            showStatus('Kamera aktif — siap memindai', 'success');
        } catch (e) {
            console.error(e);
            if (e && e.name === 'NotAllowedError') showStatus('Izin kamera ditolak.', 'error');
            else showStatus('Gagal membuka kamera: ' + (e.message || e), 'error');
        }
    }

    swapBtn.addEventListener('click', async () => {
        await listCameras();
        if (!availableCameras.length) {
            usingFront = !usingFront;
            showToast('Toggle kamera');
        } else {
            cameraIndex = (cameraIndex + 1) % availableCameras.length;
            const sel = availableCameras[cameraIndex];
            if (scanner) await scanner.setCamera(sel.id);
            else usingFront = /front|user|face/i.test(sel.label);
            showToast('Mengganti ke: ' + sel.label);
        }
        try {
            await startScanner();
        } catch (e) {}
    });
    startBtn.addEventListener('click', async () => await startScanner());
    stopBtn.addEventListener('click', async () => {
        if (scanner) {
            await scanner.stop();
            showStatus('Kamera dihentikan.');
            showToast('Kamera dihentikan', 'info');
        } else showStatus('Kamera tidak aktif.');
    });

    window.addEventListener('load', () => {
        if (location.protocol !== 'https:' && location.hostname !== 'localhost') {
            showStatus('Disarankan jalankan lewat HTTPS. Kamera mungkin dibatasi.', 'error');
        } else {
            startScanner().catch(() => {});
        }
    });
    window.addEventListener('beforeunload', () => {
        if (scanner) try {
            scanner.stop();
        } catch (e) {}
    });
</script>

<?= $this->endSection() ?>