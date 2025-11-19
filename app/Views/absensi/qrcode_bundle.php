<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="card shadow-sm p-4">
    <h2 class="fw-bold mb-3">QR Bundle</h2>
    <p class="text-muted">Daftar QR yang baru dibuat — download per file atau cetak halaman ini.</p>

    <div class="row">
        <?php if (empty($data)): ?>
            <div class="col-12 alert alert-warning">Tidak ada QR.</div>
        <?php else: ?>
            <?php foreach ($data as $b):
                if (!$b) continue;
                $file = $b['file_path'] ?? null;
                // identify owner
                $ownerType = $b['owner_type'];
                $ownerId = $b['owner_id'];
                // try to find name by scanning DB quickly (but controller didn't pass owners here).
                // We'll show owner_type + id if no name provided.
                $label = strtoupper($ownerType) . " #{$ownerId}";
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card p-3 h-100 text-center">
                        <div style="min-height:200px; display:flex; align-items:center; justify-content:center;">
                            <?php if ($file && file_exists(FCPATH . $file)): ?>
                                <img src="<?= base_url($file) ?>" style="max-width:100%;height:auto;">
                            <?php else: ?>
                                <div class="text-muted">File tidak tersedia</div>
                            <?php endif; ?>
                        </div>

                        <div class="mt-3">
                            <div class="fw-bold"><?= esc($label) ?></div>
                            <small class="text-muted">Token: <?= esc($b['token']) ?></small>
                        </div>

                        <div class="mt-3">
                            <?php if ($file && file_exists(FCPATH . $file)): ?>
                                <a class="btn btn-sm btn-outline-primary" href="<?= base_url($file) ?>" download>Download</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="mt-3">
        <button class="btn btn-secondary" onclick="window.print()">Cetak Semua</button>
        <a href="<?= base_url('absensi/generate') ?>" class="btn btn-outline-dark ms-2">Kembali</a>
    </div>
</div>

<?= $this->endSection() ?>