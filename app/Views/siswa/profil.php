<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<div class="container py-4 animate__animated animate__fadeIn">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <!-- <h4 class="fw-bold mb-2">
            <i class="fa-solid fa-user-graduate text-primary me-2"></i> Profil Siswa
        </h4> -->
        <small class="text-muted">
            Terakhir diperbarui:
            <?= date('d M Y H:i', strtotime($siswa['updated_at'] ?? $siswa['created_at'])) ?>
        </small>
    </div>

    <!-- Notifikasi -->
    <?php if (session()->getFlashdata('success')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= session()->getFlashdata('success') ?>',
                timer: 1500,
                showConfirmButton: false
            });
        </script>
    <?php elseif (session()->getFlashdata('error')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '<?= session()->getFlashdata('error') ?>'
            });
        </script>
    <?php endif; ?>

    <!-- FORM PROFIL (FOTO + DATA + PASSWORD) -->
    <form action="<?= smart_url('siswa/update-profil') ?>" method="post" enctype="multipart/form-data">

        <!-- Kartu Profil -->
        <div class="card border-0 shadow-sm mb-4 p-4 rounded-4 bg-light">
            <div class="row align-items-center">
                <div class="col-md-3 text-center mb-3">
                    <?php
                    $foto = !empty($siswa['foto']) && file_exists(FCPATH . 'uploads/siswa/' . $siswa['foto'])
                        ? smart_url('uploads/siswa/' . $siswa['foto'])
                        : smart_url('uploads/siswa/default.png');
                    ?>
                    <img src="<?= $foto . '?v=' . time() ?>" id="previewFoto" width="160" height="160"
                        class="rounded-circle border shadow-sm mb-3" style="object-fit: cover;">
                    <label class="btn btn-outline-primary btn-sm w-100">
                        <i class="fa-solid fa-camera me-1"></i> Ganti Foto
                        <input type="file" name="foto" id="foto" hidden accept="image/*">
                    </label>
                </div>

                <div class="col-md-9">
                    <h5 class="fw-bold mb-1 text-primary"><?= esc($siswa['nama']) ?></h5>
                    <p class="mb-2 text-muted">
                        <i class="fa-solid fa-id-card me-2"></i><?= esc($siswa['nisn']) ?>
                    </p>

                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <span class="badge bg-primary-subtle text-primary border px-3 py-2">
                            <i class="fa-solid fa-chalkboard-user me-1"></i> <?= esc($siswa['kelas']) ?>
                        </span>
                        <span class="badge bg-success-subtle text-success border px-3 py-2">
                            <i class="fa-solid fa-gears me-1"></i> <?= esc($siswa['jurusan']) ?>
                        </span>
                    </div>

                    <p class="small text-muted mb-0">
                        <i class="fa-solid fa-calendar-day me-1"></i>
                        Terdaftar sejak <?= date('d M Y', strtotime($siswa['created_at'])) ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Data Utama -->
        <div class="card border-0 shadow-sm p-4 rounded-4">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="<?= esc($siswa['nama']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= esc($user['username']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= esc($user['email']) ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Alamat</label>
                <input type="text" name="alamat" class="form-control" value="<?= esc($siswa['alamat']) ?>">
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Nomor Telepon</label>
                <input type="text" name="telepon" class="form-control" value="<?= esc($siswa['telepon']) ?>">
            </div>

            <hr>

            <!-- Ganti Password -->
            <h6 class="fw-bold text-muted mb-3"><i class="fa-solid fa-lock me-2"></i> Ganti Password</h6>
            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="password" name="old_password" class="form-control" placeholder="Password Lama">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePass(this)">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="password" name="new_password" class="form-control" placeholder="Password Baru">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePass(this)">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="password" name="confirm_password" class="form-control" placeholder="Konfirmasi Password">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePass(this)">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fa-solid fa-save me-2"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Script -->
<script>
    function togglePass(btn) {
        const input = btn.parentNode.querySelector('input');
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // Preview foto baru sebelum upload
    document.getElementById('foto').addEventListener('change', e => {
        const file = e.target.files[0];
        if (file && file.size <= 2 * 1024 * 1024) {
            document.getElementById('previewFoto').src = URL.createObjectURL(file);
        } else {
            Swal.fire('Ukuran Terlalu Besar', 'Maksimal 2MB', 'error');
            e.target.value = '';
        }
    });
</script>

<style>
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 .1rem rgba(13, 110, 253, .25);
    }

    .card {
        transition: 0.3s;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .badge {
        font-size: .85rem;
    }

    .btn-outline-primary:hover {
        color: #fff;
    }
</style>

<?= $this->endSection(); ?>