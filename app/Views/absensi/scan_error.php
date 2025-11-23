<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="container py-5">
    <div class="alert alert-danger shadow-sm p-4 text-center">
        <h4 class="fw-bold mb-2">QR Tidak Valid</h4>
        <p><?= esc($message ?? 'Terjadi kesalahan saat membaca QR.') ?></p>
        <a href="<?= base_url('absensi/scan-camera') ?>" class="btn btn-primary mt-3">
            ← Kembali ke Scan Kamera
        </a>
    </div>
</div>

<?= $this->endSection() ?>