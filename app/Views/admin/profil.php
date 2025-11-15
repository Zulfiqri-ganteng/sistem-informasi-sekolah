<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<div class="container py-4 animate__animated animate__fadeIn">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h4 class="fw-bold mb-2">
            <i class="fa-solid fa-user-gear text-primary me-2"></i> Profil Admin
        </h4>
        <?php
        $updated = $admin['updated_at'] ?? null;
        $created = $admin['created_at'] ?? null;

        // Tentukan waktu yang dipakai
        if (!empty($updated)) {
            $waktu = $updated;
        } elseif (!empty($created)) {
            $waktu = $created;
        } else {
            $waktu = date('Y-m-d H:i:s'); // fallback aman
        }

        $tanggal = date('d M Y H:i', strtotime($waktu));
        ?>

        <small class="text-muted">
            Terakhir diperbarui: <strong><?= $tanggal ?></strong>
        </small>


    </div>

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

    <!-- Form update profil admin -->
    <form action="<?= smart_url('admin/update-profil') ?>" method="post" enctype="multipart/form-data">
        <!-- Foto admin -->
        <div class="card border-0 shadow-sm mb-4 p-4 rounded-4 bg-light">
            <div class="row align-items-center">
                <div class="col-md-3 text-center mb-3">
                    <?php
                    $foto = !empty($admin['foto']) && file_exists(FCPATH . 'uploads/admin/' . $admin['foto'])
                        ? smart_url('uploads/admin/' . $admin['foto'])
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
                    <h5 class="fw-bold mb-1 text-primary"><?= esc($admin['nama'] ?? session()->get('nama')) ?></h5>
                    <p class="mb-2 text-muted"><i class="fa-solid fa-user-shield me-2"></i><?= esc($admin['username'] ?? session()->get('username')) ?></p>
                    <p class="small text-muted mb-0">
                        <i class="fa-solid fa-calendar-day me-1"></i> Terdaftar sejak <?= date('d M Y', strtotime($admin['created_at'] ?? date('Y-m-d'))) ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Data utama -->
        <div class="card border-0 shadow-sm p-4 rounded-4 mb-4">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="<?= esc($admin['nama'] ?? session()->get('nama')) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= esc($admin['username'] ?? session()->get('username')) ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Email (opsional)</label>
                <input type="email" name="email" class="form-control" value="<?= esc($admin['email'] ?? '') ?>">
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold">Nomor Telepon</label>
                <input type="text" name="telepon" class="form-control" value="<?= esc($admin['telepon'] ?? '') ?>">
            </div>

            <hr>
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

<?= $this->endSection(); ?>