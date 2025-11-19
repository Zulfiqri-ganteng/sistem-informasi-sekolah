<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    .qr-wrap {
        max-width: 900px;
        margin: 0 auto;
    }

    .card-inner {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 28px;
        padding: 24px;
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(10, 20, 40, 0.06);
        background: #fff;
    }

    .avatar {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        overflow: hidden;
    }

    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .qr-img {
        width: 100%;
        max-width: 300px;
        display: block;
        margin: 0 auto;
    }

    .meta {
        color: #6b7280;
        margin-top: 8px;
    }
</style>

<div class="qr-wrap">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold">QR Code Absensi</h3>
        <a href="<?= base_url('absensi/generate') ?>" class="btn btn-light">← Generate Lain</a>
    </div>

    <div class="card card-inner printable">
        <div>
            <div class="d-flex gap-3 align-items-center">
                <div class="avatar">
                    <?php if (!empty($user['foto'])): ?>
                        <img src="<?= base_url('uploads/foto/' . $user['foto']) ?>" alt="foto">
                    <?php else: ?>
                        <img src="<?= base_url('assets/default/user.png') ?>" alt="no photo">
                    <?php endif; ?>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold"><?= esc($user['nama'] ?? '-') ?></h4>
                    <?php if ($barcode['owner_type'] === 'guru'): ?>
                        <div class="small text-muted">Guru / Staf</div>
                    <?php else: ?>
                        <div class="small text-muted">Siswa — Kelas <?= esc($user['kelas'] ?? '-') ?></div>
                    <?php endif; ?>
                    <div class="meta mt-2"><strong>Generated:</strong> <?= date('d M Y H:i', strtotime($barcode['created_at'])) ?></div>
                </div>
            </div>

            <hr>

            <div class="small text-muted">
                <strong>Catatan:</strong> QR berisi token singkat (URL). Gunakan halaman scan resmi untuk absensi.
            </div>

            <div class="mt-3">
                <a class="btn btn-primary" href="<?= base_url($barcode['file_path']) ?>" download>
                    <i class="fa fa-download me-2"></i>Download PNG
                </a>
                <button class="btn btn-outline-secondary ms-2" onclick="window.print()">Print Card</button>
            </div>
        </div>

        <div class="text-center">
            <img src="<?= base_url($barcode['file_path']) ?>" alt="qr" class="qr-img">
            <div class="meta">Scan menggunakan kamera ponsel atau aplikasi QR</div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>