<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    /* Row highlight */
    .table-hover tbody tr:hover {
        background-color: #e8f0ff !important;
        transition: 0.25s;
    }

    /* Neon hover icons */
    .action-btn i {
        transition: 0.2s;
    }

    .action-btn:hover i {
        transform: scale(1.2);
        text-shadow: 0 0 8px #4dabff;
    }
</style>

<div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-white fw-bold">
        <i class="fa fa-users me-2"></i> Manajemen User
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="usersTable" class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Nama Pengguna</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th width="140">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($users as $u): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($u['username']) ?></td>
                            <td>
                                <?= esc($u['nama_siswa'] ?: $u['nama_guru'] ?: '-') ?>
                            </td>

                            <td>
                                <?php if ($u['role'] == 'admin'): ?>
                                    <span class="badge bg-primary">Admin</span>
                                <?php elseif ($u['role'] == 'guru'): ?>
                                    <span class="badge bg-info">Guru</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Siswa</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if ($u['status'] == 1): ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Nonaktif</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <a href="<?= base_url('users/toggleStatus/' . $u['id']) ?>" ...>

                                    <a href="#" class="btn btn-warning btn-sm action-btn toggleStatus"
                                        data-id="<?= $u['id'] ?>">
                                        <i class="fa fa-power-off"></i>
                                    </a>

                                    <a href="#" class="btn btn-primary btn-sm action-btn resetPass"
                                        data-id="<?= $u['id'] ?>">
                                        <i class="fa fa-key"></i>
                                    </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('#usersTable').DataTable({
            pageLength: 25,
            responsive: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                zeroRecords: "Tidak ada data",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                paginate: {
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });

        // ========= KONFIRMASI TOGGLE STATUS =========
        $('.toggleStatus').click(function(e) {
            e.preventDefault();
            let id = $(this).data('id');

            Swal.fire({
                title: 'Ubah Status?',
                text: "Aktifkan atau Nonaktifkan user ini.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, ubah!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/users/toggleStatus/' + id;
                }
            });
        });

        // ========= KONFIRMASI RESET PASSWORD =========
        $('.resetPass').click(function(e) {
            e.preventDefault();
            let id = $(this).data('id');

            Swal.fire({
                title: 'Reset Password?',
                text: "Password akan dikembalikan ke default (username).",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Reset',
                cancelButtonText: 'Batal',
            }).then((res) => {
                if (res.isConfirmed) {
                    window.location.href = '/users/reset/' + id;
                }
            });
        });
    });
</script>

<!-- FLASHDATA ALERT -->
<?php if (session()->getFlashdata('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: "<?= session()->getFlashdata('success') ?>",
            timer: 1800,
            showConfirmButton: false
        });
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: "<?= session()->getFlashdata('error') ?>",
        });
    </script>
<?php endif; ?>

<?= $this->endSection() ?>