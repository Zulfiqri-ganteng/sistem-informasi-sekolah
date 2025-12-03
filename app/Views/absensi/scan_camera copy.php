<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- qr library (UMD) -->
<script src="<?= smart_url('assets/qr/qr-scanner.umd.min.js') ?>"></script>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --danger-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        --dark-gradient: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
        --glass-bg: rgba(255, 255, 255, 0.1);
        --glass-border: rgba(255, 255, 255, 0.2);
        --glass-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }

    .scan-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .scan-card {
        background: var(--dark-gradient);
        padding: 2rem;
        border-radius: 20px;
        color: white;
        box-shadow: var(--glass-shadow);
        backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        position: relative;
        overflow: hidden;
    }

    .scan-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0) 70%);
        z-index: 0;
    }

    .scan-header {
        position: relative;
        z-index: 1;
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .scan-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        background: linear-gradient(45deg, #fff, #a8edea);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .scan-subtitle {
        font-size: 1rem;
        opacity: 0.9;
        color: #e0f7fa;
    }

    .camera-section {
        position: relative;
        z-index: 1;
        margin-bottom: 1.5rem;
    }

    .camera-box {
        width: 100%;
        aspect-ratio: 1;
        background: rgba(0, 0, 0, 0.3);
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        border: 2px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
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
        border-radius: 16px;
        border: 3px solid rgba(0, 255, 180, 0.8);
        box-shadow: 0 0 40px rgba(0, 255, 160, 0.3);
        pointer-events: none;
        animation: pulse 2s infinite;
    }

    .scan-overlay::before,
    .scan-overlay::after {
        content: '';
        position: absolute;
        width: 30px;
        height: 30px;
        border: 3px solid #00ffb4;
    }

    .scan-overlay::before {
        top: -3px;
        left: -3px;
        border-right: none;
        border-bottom: none;
        border-radius: 12px 0 0 0;
    }

    .scan-overlay::after {
        bottom: -3px;
        right: -3px;
        border-left: none;
        border-top: none;
        border-radius: 0 0 12px 0;
    }

    .status-indicator {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        margin: 1rem 0;
        padding: 0.75rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #ff4757;
        animation: blink 1.5s infinite;
    }

    .status-dot.active {
        background: #2ed573;
        animation: none;
    }

    .status-message {
        font-size: 0.9rem;
        font-weight: 500;
    }

    .controls {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .btn-scan {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        border: none;
        font-weight: 600;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-scan:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }

    .btn-primary-scan {
        background: var(--primary-gradient);
        color: white;
    }

    .btn-success-scan {
        background: var(--success-gradient);
        color: white;
    }

    .btn-danger-scan {
        background: var(--danger-gradient);
        color: white;
    }

    .btn-outline-scan {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
    }

    .notification-area {
        min-height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .scan-toast {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        font-weight: 500;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        max-width: 80%;
        text-align: center;
    }

    .scan-toast.success {
        background: rgba(46, 213, 115, 0.2);
        border-color: rgba(46, 213, 115, 0.4);
    }

    .scan-toast.error {
        background: rgba(255, 71, 87, 0.2);
        border-color: rgba(255, 71, 87, 0.4);
    }

    .scan-toast.info {
        background: rgba(52, 152, 219, 0.2);
        border-color: rgba(52, 152, 219, 0.4);
    }

    .footer-note {
        text-align: center;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.7);
        margin-top: 1rem;
        padding: 1rem;
        background: rgba(0, 0, 0, 0.2);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .camera-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 0.5rem;
        font-size: 0.8rem;
        color: rgba(255, 255, 255, 0.7);
    }

    /* Animations */
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(0, 255, 160, 0.4);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(0, 255, 160, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(0, 255, 160, 0);
        }
    }

    @keyframes blink {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }
    }

    @keyframes scan-line {
        0% {
            top: 10%;
        }

        100% {
            top: 90%;
        }
    }

    .scan-line {
        position: absolute;
        height: 2px;
        width: 100%;
        background: linear-gradient(90deg, transparent, #00ffb4, transparent);
        top: 10%;
        animation: scan-line 2s linear infinite;
        z-index: 1;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .scan-container {
            margin: 1rem auto;
            padding: 0 0.5rem;
        }

        .scan-card {
            padding: 1.5rem;
        }

        .scan-title {
            font-size: 1.5rem;
        }

        .controls {
            flex-direction: column;
            align-items: center;
        }

        .btn-scan {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .scan-card {
            padding: 1rem;
        }

        .scan-title {
            font-size: 1.3rem;
        }

        .camera-box {
            aspect-ratio: 0.9;
        }
    }
</style>

<div class="scan-container">
    <div class="scan-card">
        <div class="scan-header">
            <h1 class="scan-title">
                <i class="fas fa-qrcode me-2"></i>Scanner QR Absensi
            </h1>
            <p class="scan-subtitle">Arahkan kamera ke QR Code untuk melakukan absensi secara otomatis</p>
        </div>

        <div class="camera-section">
            <div class="camera-box" id="cameraBox">
                <video id="video" playsinline muted></video>
                <div class="scan-overlay" aria-hidden="true"></div>
                <div class="scan-line"></div>
            </div>

            <div class="camera-info">
                <span id="cameraLabel">Kamera: Menunggu inisialisasi</span>
                <span id="fpsCounter">0 FPS</span>
            </div>
        </div>

        <div class="status-indicator">
            <div class="status-dot" id="statusDot"></div>
            <div class="status-message" id="statusMessage">Mempersiapkan scanner...</div>
        </div>

        <div class="controls">
            <button id="startBtn" class="btn-scan btn-success-scan">
                <i class="fas fa-play"></i> Mulai Scan
            </button>
            <button id="swapBtn" class="btn-scan btn-outline-scan">
                <i class="fas fa-sync-alt"></i> Ganti Kamera
            </button>
            <button id="stopBtn" class="btn-scan btn-danger-scan">
                <i class="fas fa-stop"></i> Hentikan
            </button>
        </div>

        <div class="notification-area" id="notifArea"></div>

        <div class="footer-note">
            <i class="fas fa-info-circle me-1"></i>
            Pastikan browser memiliki akses ke kamera. Untuk hasil terbaik, gunakan HTTPS.
        </div>
    </div>
</div>

<!-- Suara beep scan berhasil -->
<audio id="beepSound" preload="auto">
    <source src="<?= base_url('assets/sounds/beep.mp3') ?>" type="audio/mpeg">
</audio>

<script>
    const videoElem = document.getElementById('video');
    const statusMessage = document.getElementById('statusMessage');
    const statusDot = document.getElementById('statusDot');
    const notifArea = document.getElementById('notifArea');
    const swapBtn = document.getElementById('swapBtn');
    const startBtn = document.getElementById('startBtn');
    const stopBtn = document.getElementById('stopBtn');
    const cameraLabel = document.getElementById('cameraLabel');
    const fpsCounter = document.getElementById('fpsCounter');
    const beepSound = document.getElementById('beepSound');

    let scanner = null;
    let availableCameras = [];
    let cameraIndex = 0;
    let usingFront = true;
    let fps = 0;
    let lastScanTime = 0;

    // Update FPS counter
    function updateFPS() {
        const now = performance.now();
        if (lastScanTime > 0) {
            fps = Math.round(1000 / (now - lastScanTime));
        }
        lastScanTime = now;
        fpsCounter.textContent = `${fps} FPS`;
    }

    function showStatus(message, type = 'info') {
        statusMessage.textContent = message;

        // Update status dot
        statusDot.className = 'status-dot';
        if (type === 'success') {
            statusDot.classList.add('active');
        } else if (type === 'error') {
            statusDot.style.animation = 'blink 0.5s infinite';
        } else {
            statusDot.style.animation = 'blink 1.5s infinite';
        }
    }

    function showToast(message, type = 'info') {
        const toastClass = `scan-toast ${type}`;
        notifArea.innerHTML = `
            <div class="${toastClass}">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'}"></i>
                ${message}
            </div>
        `;

        // Auto hide after 3 seconds for success/info, 5 seconds for errors
        const duration = type === 'error' ? 5000 : 3000;
        setTimeout(() => {
            if (notifArea.innerHTML.includes(message)) {
                notifArea.innerHTML = '';
            }
        }, duration);
    }

    async function listCameras() {
        try {
            const cams = await QrScanner.listCameras(true);
            availableCameras = cams;
            return cams;
        } catch (e) {
            console.error('Error listing cameras:', e);
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
            showToast('Error: Library QR Scanner tidak terdeteksi', 'error');
            return;
        }

        if (scanner) {
            try {
                await scanner.stop();
            } catch (e) {
                console.error('Error stopping scanner:', e);
            }
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
                if (!prefDevice) {
                    prefDevice = availableCameras[0].id;
                    cameraIndex = 0;
                }

                // Update camera label
                cameraLabel.textContent = `Kamera: ${availableCameras[cameraIndex].label || 'Unknown'}`;
            } else {
                cameraLabel.textContent = 'Kamera: Default';
            }

            scanner = new QrScanner(
                videoElem,
                result => {
                    updateFPS();

                    let text = (typeof result === 'string') ? result :
                        (result && (result.data || result.rawValue || result.text || result.data?.data));

                    if (!text) return;

                    const token = parseTokenFromText(String(text));
                    if (!token) {
                        showToast('QR Code tidak valid atau tidak mengandung token absensi', 'error');
                        return;
                    }

                    // Play beep sound
                    if (beepSound) {
                        beepSound.currentTime = 0;
                        beepSound.volume = 0.7;
                        beepSound.play().catch(() => {
                            // Fallback if audio fails
                            console.log('Beep sound failed to play');
                        });
                    }

                    // Show success notification
                    showStatus('QR Code terdeteksi!', 'success');
                    showToast('QR Code berhasil dipindai. Mengarahkan ke halaman absensi...', 'success');

                    // Redirect to absensi page
                    setTimeout(() => {
                        window.location.href = "<?= smart_url('absensi/scan') ?>?token=" + encodeURIComponent(token);
                    }, 500);
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
                    },
                    onDecodeError: (error) => {
                        // Silently handle decode errors - they're normal during scanning
                        if (error && error !== 'No QR code found') {
                            console.debug('QR decode error:', error);
                        }
                    }
                }
            );

            if (prefDevice) {
                await scanner.setCamera(prefDevice);
            }

            await scanner.start();
            showStatus('Kamera aktif - siap memindai QR Code', 'success');
            showToast('Scanner berhasil diaktifkan', 'success');

        } catch (e) {
            console.error('Scanner error:', e);
            if (e && e.name === 'NotAllowedError') {
                showStatus('Izin kamera ditolak oleh pengguna', 'error');
                showToast('Error: Akses kamera ditolak. Silakan berikan izin akses kamera.', 'error');
            } else if (e && e.name === 'NotFoundError') {
                showStatus('Tidak ada kamera yang ditemukan', 'error');
                showToast('Error: Tidak ada kamera yang terdeteksi pada perangkat ini.', 'error');
            } else {
                showStatus('Gagal mengakses kamera: ' + (e.message || e), 'error');
                showToast('Error: Gagal mengakses kamera. ' + (e.message || ''), 'error');
            }
        }
    }

    // Event listeners
    swapBtn.addEventListener('click', async () => {
        await listCameras();
        if (!availableCameras.length) {
            usingFront = !usingFront;
            showToast('Mengganti tipe kamera');
        } else {
            cameraIndex = (cameraIndex + 1) % availableCameras.length;
            const selectedCamera = availableCameras[cameraIndex];
            if (scanner) {
                await scanner.setCamera(selectedCamera.id);
            } else {
                usingFront = /front|user|face/i.test(selectedCamera.label);
            }
            cameraLabel.textContent = `Kamera: ${selectedCamera.label || 'Unknown'}`;
            showToast(`Mengganti ke: ${selectedCamera.label || 'Kamera ' + (cameraIndex + 1)}`);
        }
        try {
            await startScanner();
        } catch (e) {
            console.error('Error after camera swap:', e);
        }
    });

    startBtn.addEventListener('click', async () => {
        await startScanner();
    });

    stopBtn.addEventListener('click', async () => {
        if (scanner) {
            await scanner.stop();
            showStatus('Scanner dihentikan');
            showToast('Scanner dihentikan', 'info');
            statusDot.className = 'status-dot';
            cameraLabel.textContent = 'Kamera: Nonaktif';
            fpsCounter.textContent = '0 FPS';
        } else {
            showStatus('Scanner tidak aktif');
        }
    });

    // Initialize on page load
    window.addEventListener('load', () => {
        if (location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
            showStatus('Peringatan: Jalankan melalui HTTPS untuk pengalaman terbaik', 'error');
            showToast('Peringatan: Beberapa browser mungkin membatasi akses kamera pada HTTP', 'error');
        }

        // Auto-start scanner
        startScanner().catch((e) => {
            console.error('Failed to auto-start scanner:', e);
        });
    });

    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
        if (scanner) {
            try {
                scanner.stop();
            } catch (e) {
                console.error('Error stopping scanner on unload:', e);
            }
        }
    });
</script>

<?= $this->endSection() ?>