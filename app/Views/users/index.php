<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    :root {
        --primary: #4361ee;
        --secondary: #3f37c9;
        --success: #4cc9f0;
        --danger: #f72585;
        --warning: #f8961e;
        --info: #4895ef;
    }

    .card-custom {
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s, box-shadow 0.3s;
        overflow: hidden;
    }

    .card-header-custom {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
        border-radius: 15px 15px 0 0 !important;
        padding: 1.5rem;
        border: none;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
        border-left: 4px solid;
        transition: transform 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-3px);
    }

    .stat-card.total {
        border-left-color: #4361ee;
    }

    .stat-card.admin {
        border-left-color: #f72585;
    }

    .stat-card.guru {
        border-left-color: #f8961e;
    }

    .stat-card.siswa {
        border-left-color: #43aa8b;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .stat-icon.total {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
    }

    .stat-icon.admin {
        background: linear-gradient(135deg, #f72585, #b5179e);
        color: white;
    }

    .stat-icon.guru {
        background: linear-gradient(135deg, #f8961e, #f3722c);
        color: white;
    }

    .stat-icon.siswa {
        background: linear-gradient(135deg, #43aa8b, #4d908e);
        color: white;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.2rem;
    }

    .stat-label {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 500;
    }

    .filter-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
    }

    .table-container {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
    }

    .table thead th {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
        border: none;
        padding: 1rem;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .table tbody tr {
        transition: all 0.3s ease;
    }

    .table tbody tr:hover {
        background: linear-gradient(90deg, rgba(67, 97, 238, 0.05), rgba(67, 97, 238, 0.02)) !important;
        transform: scale(1.002);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .badge-custom {
        padding: 0.5em 1em;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.75rem;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .badge-role-admin {
        background: linear-gradient(135deg, #f72585, #b5179e);
        color: white;
    }

    .badge-role-guru {
        background: linear-gradient(135deg, #f8961e, #f3722c);
        color: white;
    }

    .badge-role-siswa {
        background: linear-gradient(135deg, #43aa8b, #4d908e);
        color: white;
    }

    .badge-status-active {
        background: linear-gradient(135deg, #4cc9f0, #4895ef);
        color: white;
    }

    .badge-status-inactive {
        background: linear-gradient(135deg, #6c757d, #495057);
        color: white;
    }

    .action-btn {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 0 2px;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .action-btn:hover {
        transform: translateY(-2px) scale(1.1);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .btn-toggle {
        background: linear-gradient(135deg, #f8961e, #f3722c);
        color: white;
    }

    .btn-toggle:hover {
        background: linear-gradient(135deg, #e07c0e, #d45a1a);
        color: white;
    }

    .btn-reset {
        background: linear-gradient(135deg, #43aa8b, #4d908e);
        color: white;
    }

    .btn-reset:hover {
        background: linear-gradient(135deg, #3a9578, #44817f);
        color: white;
    }

    .btn-info-custom {
        background: linear-gradient(135deg, #4895ef, #4361ee);
        color: white;
    }

    .btn-info-custom:hover {
        background: linear-gradient(135deg, #3a7bd5, #3a0ca3);
        color: white;
    }

    .btn-add {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
    }

    .btn-add:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
    }

    .refresh-btn {
        background: linear-gradient(135deg, #4895ef, #4361ee);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 10px 15px;
        font-weight: 600;
        transition: .3s;
    }

    .refresh-btn:hover {
        transform: rotate(15deg);
    }

    .user-avatar {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.8rem;
        margin-right: 10px;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .table-responsive {
            font-size: 0.8rem;
        }

        .action-btn {
            width: 30px;
            height: 30px;
            margin: 1px;
        }
    }

    @media (max-width: 576px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .card-header-custom {
            padding: 1rem;
        }

        .btn-add {
            width: 100%;
            margin-top: 0.5rem;
        }
    }
</style>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fa-solid fa-users-gear text-primary me-2"></i>
                Manajemen Pengguna
            </h1>
            <p class="text-muted mb-0">Kelola semua pengguna sistem dalam satu tempat</p>
        </div>

        
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card total">
            <div class="stat-icon total">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stat-number"><?= count($users) ?></div>
            <div class="stat-label">Total Pengguna</div>
        </div>

        <div class="stat-card admin">
            <div class="stat-icon admin">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div class="stat-number"><?= array_reduce($users, function ($carry, $user) {
                                            return $carry + ($user['role'] === 'admin' ? 1 : 0);
                                        }, 0) ?></div>
            <div class="stat-label">Admin</div>
        </div>

        <div class="stat-card guru">
            <div class="stat-icon guru">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
            <div class="stat-number"><?= array_reduce($users, function ($carry, $user) {
                                            return $carry + ($user['role'] === 'guru' ? 1 : 0);
                                        }, 0) ?></div>
            <div class="stat-label">Guru</div>
        </div>

        <div class="stat-card siswa">
            <div class="stat-icon siswa">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
            <div class="stat-number"><?= array_reduce($users, function ($carry, $user) {
                                            return $carry + ($user['role'] === 'siswa' ? 1 : 0);
                                        }, 0) ?></div>
            <div class="stat-label">Siswa</div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-card">
        <h5 class="mb-3">
            <i class="fa-solid fa-filter me-2"></i>Filter Data
        </h5>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Role</label>
                <select id="filterRole" class="form-select">
                    <option value="all">Semua Role</option>
                    <option value="admin">Admin</option>
                    <option value="guru">Guru</option>
                    <option value="siswa">Siswa</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select id="filterStatus" class="form-select">
                    <option value="all">Semua Status</option>
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Pencarian</label>
                <input id="searchInput" class="form-control" placeholder="Cari username, nama...">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <div class="w-100 d-flex gap-2">
                    <button id="applyFilter" class="btn btn-primary flex-fill">
                        <i class="fa-solid fa-filter"></i> Terapkan
                    </button>
                    <button id="resetFilter" class="btn btn-outline-secondary flex-fill">Reset</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card card-custom">
        <div class="card-header card-header-custom">
            <h5 class="mb-0">
                <i class="fa-solid fa-list me-2"></i>
                Daftar Pengguna Sistem
            </h5>
        </div>

        <div class="card-body">
            <div class="table-container">
                <div class="table-responsive">
                    <table id="usersTable" class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">#</th>
                                <th width="20%">Pengguna</th>
                                <th width="15%">Role</th>
                                <th width="15%">Status</th>
                                <th width="25%">Informasi</th>
                                <th width="20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($users as $u): ?>
                                <tr>
                                    <td class="fw-bold"><?= $no++ ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar">
                                                <?= strtoupper(substr($u['username'], 0, 2)) ?>
                                            </div>
                                            <div>
                                                <div class="fw-semibold"><?= esc($u['username']) ?></div>
                                                <small class="text-muted">ID: <?= $u['id'] ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if ($u['role'] == 'admin'): ?>
                                            <span class="badge-custom badge-role-admin">Admin</span>
                                        <?php elseif ($u['role'] == 'guru'): ?>
                                            <span class="badge-custom badge-role-guru">Guru</span>
                                        <?php else: ?>
                                            <span class="badge-custom badge-role-siswa">Siswa</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($u['status'] == 1): ?>
                                            <span class="badge-custom badge-status-active">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge-custom badge-status-inactive">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div class="text-truncate" title="<?= esc($u['nama_siswa'] ?: $u['nama_guru'] ?: '-') ?>">
                                                <i class="fa-solid fa-user me-1 text-muted"></i>
                                                <?= esc($u['nama_siswa'] ?: $u['nama_guru'] ?: '-') ?>
                                            </div>
                                            <div class="text-muted">
                                                <i class="fa-solid fa-calendar me-1"></i>
                                                Bergabung: <?= date('d M Y', strtotime($u['created_at'] ?? 'now')) ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <a href="<?= base_url('users/toggleStatus/' . $u['id']) ?>"
                                                class="btn action-btn btn-toggle toggleStatus"
                                                data-id="<?= $u['id'] ?>"
                                                data-username="<?= esc($u['username']) ?>"
                                                data-status="<?= $u['status'] ?>"
                                                title="<?= $u['status'] == 1 ? 'Nonaktifkan' : 'Aktifkan' ?>">
                                                <i class="fa-solid fa-power-off"></i>
                                            </a>

                                            <a href="<?= base_url('users/reset/' . $u['id']) ?>"
                                                class="btn action-btn btn-reset resetPass"
                                                data-id="<?= $u['id'] ?>"
                                                data-username="<?= esc($u['username']) ?>"
                                                title="Reset Password">
                                                <i class="fa-solid fa-key"></i>
                                            </a>

                                            <button class="btn action-btn btn-info-custom"
                                                onclick="showUserDetail(<?= htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8') ?>)"
                                                title="Detail User">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Detail Modal -->
<div class="modal fade" id="userDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa-solid fa-user-circle me-2"></i>
                    Detail Pengguna
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userDetailContent">
                <!-- Content will be loaded by JavaScript -->
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
    $(document).ready(function() {
        // Initialize DataTables
        let table = $('#usersTable').DataTable({
            pageLength: 25,
            responsive: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                zeroRecords: "Tidak ada data yang cocok",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                infoFiltered: "(disaring dari _MAX_ total data)",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>'
        });

        // Filter functionality
        $('#applyFilter').click(function() {
            const role = $('#filterRole').val();
            const status = $('#filterStatus').val();
            const search = $('#searchInput').val();

            table.column(2).search(role === 'all' ? '' : role);
            table.column(3).search(status === 'all' ? '' : status);
            table.search(search).draw();
        });

        $('#resetFilter').click(function() {
            $('#filterRole').val('all');
            $('#filterStatus').val('all');
            $('#searchInput').val('');
            table.search('').columns().search('').draw();
        });

        // Refresh table - FIXED
        $('#refreshTable').click(function() {
            // Reload the page to refresh data from server
            location.reload();
        });

        // Toggle Status with enhanced confirmation
        $(document).on('click', '.toggleStatus', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const username = $(this).data('username');
            const currentStatus = $(this).data('status');
            const newStatus = currentStatus == 1 ? 0 : 1;
            const actionText = newStatus == 1 ? 'mengaktifkan' : 'menonaktifkan';

            Swal.fire({
                title: `${newStatus == 1 ? 'Aktifkan' : 'Nonaktifkan'} Pengguna?`,
                html: `Anda akan ${actionText} akun <b>${username}</b>.<br>Apakah Anda yakin?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: `Ya, ${newStatus == 1 ? 'Aktifkan' : 'Nonaktifkan'}`,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/users/toggleStatus/${id}`;
                }
            });
        });

        // Reset Password with enhanced confirmation
        $(document).on('click', '.resetPass', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const username = $(this).data('username');

            Swal.fire({
                title: 'Reset Password?',
                html: `Password untuk <b>${username}</b> akan direset ke default (sama dengan username).<br>Apakah Anda yakin?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#43aa8b',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Reset Password',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/users/reset/${id}`;
                }
            });
        });
    });

    // Show user detail modal
    function showUserDetail(user) {
        const userDetailContent = `
            <div class="row">
                <div class="col-md-3 text-center mb-3">
                    <div class="user-avatar mx-auto" style="width: 80px; height: 80px; font-size: 1.5rem;">
                        ${user.username.substring(0, 2).toUpperCase()}
                    </div>
                    <h5 class="mt-2">${user.username}</h5>
                    <span class="badge-custom ${user.role == 'admin' ? 'badge-role-admin' : user.role == 'guru' ? 'badge-role-guru' : 'badge-role-siswa'}">
                        ${user.role}
                    </span>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-6 mb-2">
                            <strong>ID:</strong> ${user.id}
                        </div>
                        <div class="col-6 mb-2">
                            <strong>Status:</strong> 
                            <span class="badge-custom ${user.status == 1 ? 'badge-status-active' : 'badge-status-inactive'}">
                                ${user.status == 1 ? 'Aktif' : 'Nonaktif'}
                            </span>
                        </div>
                        <div class="col-12 mb-2">
                            <strong>Nama Lengkap:</strong> ${user.nama_siswa || user.nama_guru || '-'}
                        </div>
                        <div class="col-6 mb-2">
                            <strong>Dibuat:</strong> ${new Date(user.created_at).toLocaleDateString('id-ID')}
                        </div>
                        <div class="col-6 mb-2">
                            <strong>Diupdate:</strong> ${new Date(user.updated_at || user.created_at).toLocaleDateString('id-ID')}
                        </div>
                    </div>
                </div>
            </div>
        `;

        $('#userDetailContent').html(userDetailContent);
        $('#userDetailModal').modal('show');
    }

    // Show add user modal (placeholder function)
    function showAddUserModal() {
        Swal.fire({
            title: 'Tambah Pengguna',
            text: 'Fitur tambah pengguna akan segera tersedia!',
            icon: 'info',
            confirmButtonText: 'Oke'
        });
    }
</script>

<!-- FLASHDATA ALERT -->
<?php if (session()->getFlashdata('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "<?= session()->getFlashdata('success') ?>",
            timer: 2000,
            showConfirmButton: false,
            position: 'top-end',
            toast: true
        });
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "<?= session()->getFlashdata('error') ?>",
            confirmButtonText: 'Oke'
        });
    </script>
<?php endif; ?>

<?= $this->endSection() ?>