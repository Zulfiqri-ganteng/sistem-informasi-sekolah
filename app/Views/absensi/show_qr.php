<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    :root {
        --primary: #4361ee;
        --primary-light: #4895ef;
        --secondary: #3f37c9;
        --success: #4cc9f0;
        --dark: #212529;
        --light: #f8f9fa;
        --gradient: linear-gradient(135deg, var(--primary), var(--secondary));
        --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        --shadow-hover: 0 20px 40px rgba(0, 0, 0, 0.12);
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .qr-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 90vh;
        padding: 20px;
    }

    .qr-card {
        max-width: 480px;
        width: 100%;
        background: white;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: all 0.4s ease;
        position: relative;
    }

    .qr-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-hover);
    }

    .qr-header {
        background: var(--gradient);
        color: white;
        padding: 30px 20px 20px;
        text-align: center;
        position: relative;
    }

    .qr-header::after {
        content: '';
        position: absolute;
        bottom: -20px;
        left: 0;
        width: 100%;
        height: 40px;
        background: white;
        border-radius: 50% 50% 0 0;
    }

    .qr-header h1 {
        font-weight: 700;
        font-size: 1.8rem;
        margin: 0 0 10px;
        letter-spacing: -0.5px;
    }

    .qr-header p {
        opacity: 0.9;
        font-size: 0.95rem;
        margin: 0;
    }

    .qr-body {
        padding: 40px 30px 30px;
    }

    .user-profile {
        text-align: center;
        margin-bottom: 25px;
    }

    .photo-container {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        margin: 0 auto 15px;
        border: 5px solid white;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        position: relative;
        z-index: 2;
        overflow: hidden;
        background: white;
    }

    .photo-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-name {
        font-weight: 700;
        font-size: 1.5rem;
        color: var(--dark);
        margin-bottom: 5px;
    }

    .user-details {
        color: #6c757d;
        font-size: 0.95rem;
        margin-bottom: 25px;
    }

    .qr-display {
        background: #f8f9fa;
        border-radius: 16px;
        padding: 25px;
        text-align: center;
        margin-bottom: 25px;
        position: relative;
        border: 1px solid #e9ecef;
    }

    .qr-code {
        max-width: 220px;
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease;
    }

    .qr-code:hover {
        transform: scale(1.03);
    }

    .token-section {
        background: #f0f7ff;
        border-radius: 14px;
        padding: 18px;
        margin-bottom: 25px;
        border-left: 4px solid var(--primary);
        transition: all 0.3s ease;
    }

    .token-section:hover {
        background: #e8f2ff;
    }

    .token-label {
        font-size: 0.85rem;
        color: var(--primary);
        font-weight: 600;
        margin-bottom: 8px;
        display: block;
    }

    .token-value {
        font-family: 'Courier New', monospace;
        font-size: 1.1rem;
        word-break: break-all;
        color: var(--dark);
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .token-hidden {
        letter-spacing: 2px;
        color: #adb5bd;
    }

    .btn-toggle-token {
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 50px;
        padding: 10px 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin: 0 auto 25px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    }

    .btn-toggle-token:hover {
        background: var(--secondary);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(67, 97, 238, 0.4);
    }

    .info-section {
        background: #f8f9fa;
        border-radius: 14px;
        padding: 18px;
        margin-bottom: 25px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e9ecef;
    }

    .info-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .info-label {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .info-value {
        font-weight: 600;
        color: var(--dark);
    }

    .btn-back {
        background: white;
        color: var(--primary);
        border: 2px solid var(--primary);
        border-radius: 50px;
        padding: 12px 30px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin: 0 auto;
        transition: all 0.3s ease;
        width: 100%;
        max-width: 250px;
    }

    .btn-back:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(67, 97, 238, 0.3);
    }

    .qr-watermark {
        position: absolute;
        bottom: 15px;
        right: 15px;
        width: 50px;
        height: 50px;
        opacity: 0.1;
    }

    /* Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in {
        animation: fadeIn 0.6s ease forwards;
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        .qr-card {
            border-radius: 16px;
        }

        .qr-header {
            padding: 25px 15px 15px;
        }

        .qr-body {
            padding: 30px 20px 20px;
        }

        .photo-container {
            width: 100px;
            height: 100px;
        }
    }
</style>

<div class="qr-container">
    <div class="qr-card fade-in">
        <div class="qr-header">
            <h1>QR Code Absensi</h1>
            <p>Scan kode berikut untuk melakukan absensi</p>
        </div>

        <div class="qr-body">
            <?php
            $img = $user['foto']
                ? base_url('uploads/' . $barcode['owner_type'] . '/' . $user['foto'])
                : base_url('assets/uploads/users/default.png');
            ?>

            <div class="user-profile">
                <div class="photo-container">
                    <img src="<?= $img ?>" alt="Foto Profil">
                </div>
                <h2 class="user-name"><?= esc($user['nama']) ?></h2>
                <div class="user-details">
                    <?= $barcode['owner_type'] === 'siswa'
                        ? "NISN: {$user['nisn']} • Kelas: {$user['kelas']}"
                        : "NIP: {$user['nip']}" ?>
                </div>
            </div>

            <div class="qr-display">
                <img src="<?= base_url($barcode['file_path']) ?>" class="qr-code" alt="QR Code">
                <svg class="qr-watermark" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 11H21M3 15H21M7 19H17M7 7H17M7 3V5M17 3V5M6.2 21H17.8C18.9201 21 19.4802 21 19.908 20.782C20.2843 20.5903 20.5903 20.2843 20.782 19.908C21 19.4802 21 18.9201 21 17.8V8.2C21 7.0799 21 6.51984 20.782 6.09202C20.5903 5.71569 20.2843 5.40973 19.908 5.21799C19.4802 5 18.9201 5 17.8 5H6.2C5.0799 5 4.51984 5 4.09202 5.21799C3.71569 5.40973 3.40973 5.71569 3.21799 6.09202C3 6.51984 3 7.07989 3 8.2V17.8C3 18.9201 3 19.4802 3.21799 19.908C3.40973 20.2843 3.71569 20.5903 4.09202 20.782C4.51984 21 5.07989 21 6.2 21Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>

            <div class="token-section">
                <span class="token-label">TOKEN ABSENSI</span>
                <div class="token-value token-hidden" id="tokenValue">••••••••••••••••••••••••</div>
            </div>

            <button class="btn-toggle-token" onclick="toggleToken()">
                <i class="fa-solid fa-eye" id="tokenIcon"></i>
                <span id="tokenText">Tampilkan Token</span>
            </button>

            <div class="info-section">
                <div class="info-item">
                    <span class="info-label">Dibuat</span>
                    <span class="info-value"><?= date('d M Y H:i', strtotime($barcode['created_at'])) ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        <?php if ($barcode['expires_at'] && strtotime($barcode['expires_at']) > time()): ?>
                            <span style="color: #28a745;">Aktif</span>
                        <?php elseif ($barcode['expires_at'] && strtotime($barcode['expires_at']) <= time()): ?>
                            <span style="color: #dc3545;">Kadaluarsa</span>
                        <?php else: ?>
                            <span style="color: #6c757d;">Tidak Terbatas</span>
                        <?php endif; ?>
                    </span>
                </div>
                <?php if ($barcode['expires_at']): ?>
                    <div class="info-item">
                        <span class="info-label">Kadaluarsa</span>
                        <span class="info-value"><?= date('d M Y H:i', strtotime($barcode['expires_at'])) ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <a href="<?= base_url('absensi/generate') ?>" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>

<script>
    let tokenShown = false;
    const tokenValue = "<?= $barcode['token'] ?>";

    function toggleToken() {
        const tokenElement = document.getElementById('tokenValue');
        const tokenIcon = document.getElementById('tokenIcon');
        const tokenText = document.getElementById('tokenText');

        if (!tokenShown) {
            tokenElement.textContent = tokenValue;
            tokenElement.classList.remove('token-hidden');
            tokenIcon.className = 'fa-solid fa-eye-slash';
            tokenText.textContent = 'Sembunyikan Token';
        } else {
            tokenElement.textContent = '••••••••••••••••••••••••';
            tokenElement.classList.add('token-hidden');
            tokenIcon.className = 'fa-solid fa-eye';
            tokenText.textContent = 'Tampilkan Token';
        }

        tokenShown = !tokenShown;
    }
</script>

<?= $this->endSection() ?>