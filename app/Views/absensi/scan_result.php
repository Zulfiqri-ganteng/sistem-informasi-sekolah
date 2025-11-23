<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php
// expects $barcode, $owner, $owner_type, $nextAction
$owner_type = $owner_type ?? ($owner['role'] ?? ($owner['jenis'] ?? 'siswa'));
$ownerName = $owner['nama'] ?? $owner['nama_lengkap'] ?? 'Pengguna';
$photo = $owner['foto'] ?? null;
$img = $photo ? smart_url($photo) : smart_url('uploads/admin/default.png');
?>

<style>
    .scan-result-wrap {
        max-width: 960px;
        margin: 28px auto;
    }

    .result-card {
        background: #fff;
        border-radius: 10px;
        padding: 18px;
        box-shadow: 0 12px 30px rgba(2, 8, 23, 0.06);
    }

    .header-bar {
        background: linear-gradient(90deg, #06b6d4, #059669);
        color: #fff;
        padding: 14px;
        border-radius: 8px;
        margin-bottom: 14px;
    }

    .avatar {
        width: 140px;
        height: 140px;
        border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 8px 20px rgba(2, 8, 23, 0.08);
    }

    .badge-action {
        display: inline-block;
        padding: 8px 12px;
        border-radius: 999px;
        background: #10b981;
        color: #fff;
        font-weight: 700;
    }

    .actions {
        display: flex;
        gap: 12px;
        margin-top: 10px;
    }

    .btn-lg {
        padding: 12px 18px;
        font-size: 16px;
    }
</style>

<div class="scan-result-wrap">
    <div class="header-bar">
        <h3 class="mb-0">Konfirmasi Absensi</h3>
        <small>Token: <strong><?= esc($barcode['token']) ?></strong></small>
    </div>

    <div class="result-card">
        <div class="row align-items-center">
            <div class="col-md-3 text-center">
                <img src="<?= $img ?>" alt="Avatar" class="avatar">
                <div style="margin-top:10px; font-weight:700;"><?= esc($ownerName) ?></div>
                <div style="margin-top:8px;">
                    <span class="badge-action"><?= $nextAction === 'masuk' ? 'Menandai: Masuk' : ($nextAction === 'pulang' ? 'Menandai: Pulang' : 'Sudah Selesai') ?></span>
                </div>
            </div>

            <div class="col-md-6">
                <table class="table table-borderless mb-0">
                    <tr>
                        <td style="width:120px">Nama</td>
                        <td><strong><?= esc($ownerName) ?></strong></td>
                    </tr>
                    <tr>
                        <td>Role</td>
                        <td><strong><?= esc(ucfirst($owner_type)) ?></strong></td>
                    </tr>
                    <tr>
                        <td>NIS / NIP</td>
                        <td><strong><?= esc($owner['nis'] ?? $owner['nip'] ?? '-') ?></strong></td>
                    </tr>
                </table>
            </div>

            <div class="col-md-3 text-center">
                <div style="font-size:14px;color:#6b7280">Tipe QR</div>
                <div style="font-weight:800;font-size:20px"><?= esc(ucfirst($owner_type)) ?></div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-8">
                <div><strong>Preview Info QR & Pilihan Tindakan</strong></div>
                <div style="margin-top:10px;" class="actions">
                    <?php if ($nextAction === 'masuk'): ?>
                        <form method="post" action="<?= smart_url('absensi/process-scan') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="barcode_id" value="<?= esc($barcode['id']) ?>">
                            <button class="btn btn-success btn-lg">✅ Konfirmasi & Absen</button>
                        </form>
                    <?php elseif ($nextAction === 'pulang'): ?>
                        <form method="post" action="<?= smart_url('absensi/process-scan') ?>">
                            <?= csrf_field() ?>
                            <input type="hidden" name="barcode_id" value="<?= esc($barcode['id']) ?>">
                            <button class="btn btn-warning btn-lg">⤴ Konfirmasi Pulang</button>
                        </form>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-lg" disabled>✓ Sudah absen hari ini</button>
                    <?php endif; ?>

                    <a href="<?= smart_url('absensi/scan-camera') ?>" class="btn btn-outline-secondary btn-lg">↩ Kembali ke Scanner</a>

                    <a href="<?= smart_url('absensi/qrcode/' . $barcode['id']) ?>" class="btn btn-info btn-lg">👁️ Lihat QR</a>
                </div>
            </div>

            <div class="col-md-4 text-end">
                <small class="text-muted">Dikonfirmasi oleh: <strong><?= esc(session()->get('role') ?? '-') ?></strong></small>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>