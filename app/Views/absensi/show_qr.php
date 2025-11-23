<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    .qr-wrapper {
        max-width: 600px;
        margin: auto;
        padding: 30px;
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, .08);
        text-align: center;
        transition: .25s ease;
    }

    .qr-wrapper:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 45px rgba(0, 0, 0, .12);
    }

    .photo-box {
        display: flex;
        justify-content: center;
        margin-bottom: 18px;
    }

    .photo-box img {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        object-fit: cover;
        border: 5px solid #f1f1f1;
        box-shadow: 0 6px 20px rgba(0, 0, 0, .15);
    }

    .qr-image {
        max-width: 280px;
        border-radius: 14px;
        padding: 12px;
        background: #ffffff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, .12);
    }

    .token-box {
        background: #eef7ff;
        padding: 14px 18px;
        border-radius: 12px;
        margin-top: 18px;
        border-left: 4px solid #0d6efd;
        word-break: break-all;
        font-size: .95rem;
        display: inline-block;
    }

    .info-box {
        background: #f8f9fa;
        padding: 14px 18px;
        border-radius: 14px;
        margin-top: 18px;
        font-size: .88rem;
        line-height: 1.5;
    }

    .btn-toggle {
        border-radius: 30px;
    }
</style>

<div class="qr-wrapper">

    <h2 class="fw-bold mb-3">QR Code Absensi</h2>

    <?php
    $img = $user['foto']
        ? base_url('uploads/' . $barcode['owner_type'] . '/' . $user['foto'])
        : base_url('assets/default/user.png');
    ?>

    <div class="photo-box">
        <img src="<?= $img ?>" alt="Foto">
    </div>

    <h3 class="fw-bold"><?= esc($user['nama']) ?></h3>

    <p class="text-muted mb-2">
        <?= $barcode['owner_type'] === 'siswa'
            ? "NISN: {$user['nisn']} • Kelas: {$user['kelas']}"
            : "NIP: {$user['nip']}" ?>
    </p>

    <!-- QR IMAGE -->
    <img src="<?= base_url($barcode['file_path']) ?>" class="qr-image my-3">

    <!-- TOKEN (BISA DISHOW/HIDE) -->
    <div class="token-box">
        <span id="tokenValue">••••••••••••••••••••••••</span>
    </div>

    <button class="btn btn-outline-primary mt-2 btn-toggle" onclick="toggleToken()">
        <i class="fa-solid fa-eye"></i> Tampilkan Token
    </button>

    <!-- INFO -->
    <div class="info-box mt-3">
        Dibuat: <strong><?= date('d M Y H:i', strtotime($barcode['created_at'])) ?></strong><br>
        <?php if ($barcode['expires_at']): ?>
            Kadaluarsa: <strong><?= date('d M Y H:i', strtotime($barcode['expires_at'])) ?></strong>
        <?php else: ?>
            <span>Tidak ada masa kadaluarsa</span>
        <?php endif; ?>
    </div>

    <a href="<?= base_url('absensi/generate') ?>" class="btn btn-primary btn-lg mt-4 px-4">
        <i class="fa-solid fa-arrow-left me-2"></i> Kembali Generate
    </a>

</div>

<script>
    let shown = false;

    function toggleToken() {
        const el = document.getElementById('tokenValue');
        if (!shown) {
            el.innerText = "<?= $barcode['token'] ?>";
        } else {
            el.innerText = "••••••••••••••••••••••••";
        }
        shown = !shown;
    }
</script>

<?= $this->endSection() ?>