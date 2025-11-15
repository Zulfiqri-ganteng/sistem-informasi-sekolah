<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="fa-solid fa-lock text-warning me-2"></i> Ganti Password</h3>
        <small class="text-muted">Amankan akun Anda secara berkala</small>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php elseif (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="card p-4 shadow-sm" style="max-width:600px">
        <form method="post" action="<?= smart_url('admin/ganti-password') ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label class="form-label">Password Lama</label>
                <input type="password" name="password_lama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password Baru</label>
                <input id="pw" type="password" name="password_baru" class="form-control" required>
                <div id="pwStrength" class="form-text"></div>
            </div>
            <div class="mb-4">
                <label class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="konfirmasi_password" class="form-control" required>
            </div>

            <div class="d-flex justify-content-end">
                <button class="btn btn-primary" type="submit"><i class="fa-solid fa-save me-1"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('pw')?.addEventListener('input', function() {
        const v = this.value;
        const el = document.getElementById('pwStrength');
        let score = 0;
        if (v.length >= 8) score++;
        if (/[A-Z]/.test(v)) score++;
        if (/[0-9]/.test(v)) score++;
        if (/[^A-Za-z0-9]/.test(v)) score++;
        const labels = ['Lemah', 'Cukup', 'Bagus', 'Kuat'];
        el.textContent = v ? `Kekuatan: ${labels[Math.max(0,score-1)]}` : '';
    });
</script>

<?= $this->endSection(); ?>