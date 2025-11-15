<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<h4 class="fw-semibold mb-3">🧾 Kelas yang Anda Ampu</h4>

<div class="row">
    <?php if (!empty($kelas)): ?>
        <?php foreach ($kelas as $k): ?>
            <div class="col-md-4 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-bold text-primary"><?= esc($k['nama_kelas']) ?></h5>
                        <p class="text-muted mb-2">Klik untuk melihat siswa</p>
                        <a href="<?= smart_url('guru/siswa/' . $k['id']) ?>"
                            class="btn btn-sm btn-outline-primary w-100">
                            <i class="fa fa-users"></i> Lihat Siswa
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-md-12">
            <div class="alert alert-info">Anda belum memiliki kelas yang diampu.</div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection(); ?>