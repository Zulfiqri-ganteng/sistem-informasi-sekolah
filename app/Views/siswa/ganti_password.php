<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<div class="container py-4 animate__animated animate__fadeIn">
    <h3 class="fw-bold mb-4">
        <i class="fa-solid fa-lock text-warning me-2"></i> Ganti Password
    </h3>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 p-4" style="max-width:600px;">
        <form method="post" action="<?= smart_url('siswa/gantiPasswordPost') ?>">
            <div class="mb-3">
                <label class="form-label">Password Lama</label>
                <div class="input-group">
                    <input type="password" name="old_password" id="oldPass" class="form-control" required>
                    <button type="button" class="btn btn-outline-secondary"
                        onclick="togglePass('oldPass', this)">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password Baru</label>
                <div class="input-group">
                    <input type="password" name="new_password" id="newPass" class="form-control" required>
                    <button type="button" class="btn btn-outline-secondary"
                        onclick="togglePass('newPass', this)">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Konfirmasi Password Baru</label>
                <div class="input-group">
                    <input type="password" name="confirm_password" id="confPass" class="form-control" required>
                    <button type="button" class="btn btn-outline-secondary"
                        onclick="togglePass('confPass', this)">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button class="btn btn-primary" type="submit">
                    <i class="fa-solid fa-save me-1"></i> Simpan Password
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePass(id, btn) {
        const input = document.getElementById(id);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>

<style>
    .card {
        border-radius: 16px;
    }

    .input-group .btn {
        border-color: #dee2e6;
    }

    .input-group .btn:hover {
        background-color: #f8f9fa;
    }
</style>

<?= $this->endSection(); ?>