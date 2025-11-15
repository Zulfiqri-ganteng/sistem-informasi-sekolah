<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<div class="container py-4 animate__animated animate__fadeIn">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h4 class="fw-bold mb-2">
            <i class="fa-solid fa-user-tie text-primary me-2"></i> Profil Guru
        </h4>
        <?php
        // Ambil timestamp guru
        $updated = $guru['updated_at'] ?? null;
        $created = $guru['created_at'] ?? null;

        // Tentukan waktu yang dipakai (updated_at prioritas)
        $waktu = !empty($updated) ? $updated : $created;

        // Format kan waktu
        $tanggal = date('d M Y H:i', strtotime($waktu));
        ?>

        <small class="text-muted">
            Terakhir diperbarui: <strong><?= $tanggal ?></strong>
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
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '<?= session()->getFlashdata('error') ?>'
            });
        </script>
    <?php endif; ?>

    <!-- FORM PROFIL -->
    <form action="<?= smart_url('guru/update-profil') ?>" method="post" enctype="multipart/form-data">

        <!-- Card Profil -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-light">
            <div class="row align-items-center">
                <div class="col-md-3 text-center mb-3">

                    <?php
                    $foto = !empty($guru['foto']) && file_exists(FCPATH . 'uploads/guru/' . $guru['foto'])
                        ? smart_url('uploads/guru/' . $guru['foto'])
                        : smart_url('uploads/guru/default.png');
                    ?>

                    <img src="<?= $foto . '?v=' . time() ?>" width="160" height="160"
                        class="rounded-circle border shadow-sm mb-3"
                        style="object-fit: cover; object-position: center -10px;"
                        id="previewFoto">


                    <label class="btn btn-outline-primary btn-sm w-100">
                        <i class="fa-solid fa-camera me-1"></i> Ganti Foto
                        <input type="file" name="foto" id="foto" hidden accept="image/*">
                    </label>
                </div>

                <div class="col-md-9">

                    <h5 class="fw-bold mb-1 text-primary"><?= esc($guru['nama']) ?></h5>

                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <span class="badge bg-primary-subtle text-primary border px-3 py-2">
                            <i class="fa-solid fa-id-card me-1"></i>
                            NIP: <?= esc($guru['nip']) ?>
                        </span>

                        <?php if (!empty($mapel)): ?>
                            <?php foreach ($mapel as $m): ?>
                                <span class="badge bg-success-subtle text-success border px-3 py-2">
                                    <i class="fa-solid fa-book me-1"></i> <?= esc($m['nama_mapel']) ?>
                                </span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger border px-3 py-2">
                                <i class="fa-solid fa-triangle-exclamation me-1"></i> Mapel: Belum diatur
                            </span>
                        <?php endif; ?>
                    </div>

                    <p class="small text-muted mb-0">
                        <i class="fa-solid fa-calendar-day me-1"></i>
                        Bergabung sejak <?= date('d M Y', strtotime($guru['created_at'])) ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="card border-0 shadow-sm rounded-4 p-4">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="<?= esc($guru['nama']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= esc($guru['email']) ?>" required>
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label fw-semibold">Nomor Telepon</label>
                    <input type="text" name="telepon" class="form-control" value="<?= esc($guru['telepon']) ?>">
                </div>

                <div class="col-md-6 mt-3">
                    <label class="form-label fw-semibold">Alamat</label>
                    <input type="text" name="alamat" class="form-control" value="<?= esc($guru['alamat']) ?>">
                </div>
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
                    <i class="fa-solid fa-save me-1"></i> Simpan Perubahan
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
        if (file) document.getElementById('previewFoto').src = URL.createObjectURL(file);
    });
</script>

<?= $this->endSection(); ?>