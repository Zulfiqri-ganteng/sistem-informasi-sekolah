<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
    /* MODERN DESIGN SYSTEM */
    :root {
        --primary: #4361ee;
        --primary-dark: #3a56d4;
        --secondary: #6c757d;
        --success: #06d6a0;
        --danger: #ef476f;
        --warning: #ffd166;
        --light: #f8f9fa;
        --dark: #212529;
        --border-radius: 12px;
        --box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    /* CARD ENHANCEMENT */
    .card-modern {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        overflow: hidden;
        transition: var(--transition);
        background: #ffffff;
    }

    .card-modern:hover {
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
    }

    /* TABLE ENHANCEMENT */
    .table-modern {
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-modern thead th {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff;
        padding: 16px 20px;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        font-weight: 600;
        border: none;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table-modern thead th:first-child {
        border-radius: 10px 0 0 0;
    }

    .table-modern thead th:last-child {
        border-radius: 0 10px 0 0;
    }

    .table-modern tbody tr {
        transition: var(--transition);
    }

    .table-modern tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.04);
        transform: translateY(-1px);
    }

    .table-modern tbody td {
        padding: 18px 20px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f9;
        background: #fff;
    }

    .table-modern tbody tr:last-child td {
        border-bottom: none;
    }

    /* BADGE */
    .badge-status {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.3px;
    }

    .badge-aktif {
        background: rgba(6, 214, 160, 0.1);
        color: var(--success);
        border: 1px solid rgba(6, 214, 160, 0.2);
    }

    .badge-nonaktif {
        background: rgba(239, 71, 111, 0.1);
        color: var(--danger);
        border: 1px solid rgba(239, 71, 111, 0.2);
    }

    /* ACTION BUTTONS */
    .btn-action-group {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        transition: var(--transition);
        border: 1px solid transparent;
    }

    .btn-action-edit {
        background: rgba(6, 214, 160, 0.1);
        color: var(--success);
    }

    .btn-action-edit:hover {
        background: var(--success);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(6, 214, 160, 0.3);
    }

    .btn-action-delete {
        background: rgba(239, 71, 111, 0.1);
        color: var(--danger);
    }

    .btn-action-delete:hover {
        background: var(--danger);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 71, 111, 0.3);
    }

    /* HEADER ENHANCEMENT */
    .content-header {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-bottom: 1px solid #e9ecef;
        padding: 1.5rem 0 !important;
    }

    .header-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .header-title i {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .header-subtitle {
        color: #6c757d;
        font-size: 0.95rem;
        margin-top: 6px;
        padding-left: 40px;
    }

    /* BUTTON ENHANCEMENT */
    .btn-primary-custom {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        border: none;
        border-radius: 10px;
        padding: 10px 24px;
        font-weight: 600;
        letter-spacing: 0.3px;
        transition: var(--transition);
        box-shadow: 0 4px 15px rgba(67, 97, 238, 0.2);
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(67, 97, 238, 0.3);
        background: linear-gradient(135deg, var(--primary-dark), var(--primary));
    }

    .btn-secondary-custom {
        background: #ffffff;
        border: 1px solid #e0e0e0;
        color: var(--dark);
        border-radius: 10px;
        padding: 10px 24px;
        font-weight: 600;
        letter-spacing: 0.3px;
        transition: var(--transition);
    }

    .btn-secondary-custom:hover {
        background: #f8f9fa;
        border-color: #d0d0d0;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    /* MODAL ENHANCEMENT */
    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    .modal-header {
        background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        color: #fff;
        border-bottom: none;
        padding: 24px 32px;
    }

    .modal-title {
        font-weight: 700;
        font-size: 1.25rem;
    }

    .modal-body {
        padding: 32px;
    }

    .modal-footer {
        border-top: 1px solid #f1f3f9;
        padding: 20px 32px;
        background: #f8fafc;
    }

    /* FORM CONTROLS */
    .form-control-modern {
        border: 1.5px solid #e9ecef;
        border-radius: 10px;
        padding: 12px 16px;
        transition: var(--transition);
        font-size: 0.95rem;
    }

    .form-control-modern:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    /* STATS CARD */
    .stats-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #f1f3f9;
        transition: var(--transition);
    }

    .stats-card:hover {
        border-color: var(--primary);
        box-shadow: 0 8px 20px rgba(67, 97, 238, 0.08);
    }

    .stats-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 16px;
        background: rgba(67, 97, 238, 0.1);
        color: var(--primary);
    }

    /* EMPTY STATE */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 4rem;
        color: #e9ecef;
        margin-bottom: 20px;
    }
</style>

<div class="content-wrapper" style="background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); min-height: calc(100vh - 56px);">

    <!-- HEADER -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); margin-right: 16px;">
                            <i class="fas fa-users text-white" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h1 class="header-title m-0">
                                <?= esc($ekskul['nama_ekskul']) ?>
                                <span class="badge badge-status badge-aktif ml-2">Aktif</span>
                            </h1>
                            <p class="header-subtitle m-0">
                                <i class="fas fa-info-circle mr-1"></i>
                                Kelola daftar siswa anggota ekskul <?= esc($ekskul['nama_ekskul']) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="d-flex justify-content-lg-end gap-3">
                        <a href="<?= smart_url('ekskul') ?>" class="btn btn-secondary-custom">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <a href="<?= smart_url('ekskul/anggota/add/' . $ekskul['id']) ?>" class="btn btn-primary-custom">
                            <i class="fas fa-plus mr-2"></i> Tambah Anggota
                        </a>
                    </div>
                </div>
            </div>

            <!-- STATS -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h3 class="mb-1"><?= count($anggota) ?></h3>
                        <p class="text-muted mb-0">Total Anggota</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: rgba(6, 214, 160, 0.1); color: var(--success);">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <h3 class="mb-1"><?= isset($stats['aktif']) ? $stats['aktif'] : count($anggota) ?></h3>
                        <p class="text-muted mb-0">Aktif</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: rgba(255, 209, 102, 0.1); color: var(--warning);">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="mb-1"><?= date('Y') ?></h3>
                        <p class="text-muted mb-0">Tahun Ajaran</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <div class="stats-icon" style="background: rgba(239, 71, 111, 0.1); color: var(--danger);">
                            <i class="fas fa-user-times"></i>
                        </div>
                        <h3 class="mb-1"><?= isset($stats['nonaktif']) ? $stats['nonaktif'] : 0 ?></h3>
                        <p class="text-muted mb-0">Nonaktif</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTENT -->
    <section class="content">
        <div class="container-fluid">

            <!-- FLASH MESSAGES (MODAL VERSION) -->
            <?php if (session()->getFlashdata('success')): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: '<?= addslashes(session()->getFlashdata('success')) ?>',
                            timer: 3000,
                            showConfirmButton: false,
                            toast: true,
                            position: 'top-end',
                            background: '#f8f9fa',
                            customClass: {
                                title: 'text-success',
                                popup: 'shadow-lg'
                            }
                        });
                    });
                </script>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: '<?= addslashes(session()->getFlashdata('error')) ?>',
                            confirmButtonColor: '#4361ee',
                            background: '#f8f9fa',
                            customClass: {
                                title: 'text-danger',
                                popup: 'shadow-lg'
                            }
                        });
                    });
                </script>
            <?php endif; ?>

            <!-- MAIN CARD -->
            <div class="card card-modern">

                <!-- CARD HEADER -->
                <div class="card-header d-flex justify-content-between align-items-center py-4 border-bottom-0">
                    <div>
                        <h4 class="font-weight-bold text-dark mb-1">
                            <i class="fas fa-list-alt mr-2" style="color: var(--primary);"></i>
                            Daftar Anggota
                        </h4>
                        <p class="text-muted mb-0 small">
                            <i class="fas fa-filter mr-1"></i>
                            Tampilkan semua anggota ekskul
                        </p>
                    </div>

                    <div class="d-flex gap-2">
                        <div class="input-group" style="max-width: 300px;">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                            </div>
                            <input type="text" id="searchTable" class="form-control form-control-modern border-left-0" placeholder="Cari anggota...">
                        </div>
                        <button class="btn btn-light border" onclick="refreshTable()" title="Refresh">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>

                <!-- CARD BODY -->
                <div class="card-body p-0">
                    <?php if (empty($anggota)): ?>
                        <div class="empty-state">
                            <i class="fas fa-users-slash"></i>
                            <h4 class="mb-2">Belum ada anggota</h4>
                            <p class="text-muted mb-4">Tambahkan anggota pertama untuk ekskul ini</p>
                            <a href="<?= smart_url('ekskul/anggota/add/' . $ekskul['id']) ?>" class="btn btn-primary-custom">
                                <i class="fas fa-plus mr-2"></i> Tambah Anggota Pertama
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-modern mb-0">
                                <thead>
                                    <tr>
                                        <th width="5%" class="text-center">No</th>
                                        <th width="15%">NISN</th>
                                        <th width="30%">Nama Lengkap</th>
                                        <th width="20%">Kelas</th>
                                        <th width="15%" class="text-center">Status</th>
                                        <th width="15%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <?php $no = 1;
                                    foreach ($anggota as $row): ?>
                                        <tr>
                                            <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                            <td>
                                                <div class="font-weight-bold text-dark"><?= esc($row['nisn']) ?></div>
                                            </td>
                                            <td>
                                                <div class="font-weight-bold text-dark"><?= esc($row['nama']) ?></div>
                                                <small class="text-muted">Siswa</small>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-graduation-cap mr-2 text-muted"></i>
                                                    <?= esc($row['kelas']) ?>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <?php $status = isset($row['status']) ? $row['status'] : 'aktif'; ?>
                                                <span class="badge-status badge-<?= $status ?>">
                                                    <i class="fas fa-circle mr-1" style="font-size: 8px;"></i>
                                                    <?= ucfirst($status) ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-action-group">
                                                    <button class="btn-action btn-action-edit"
                                                        data-id="<?= $row['id'] ?>"
                                                        data-nisn="<?= esc($row['nisn']) ?>"
                                                        data-nama="<?= esc($row['nama']) ?>"
                                                        data-kelas="<?= esc($row['kelas']) ?>"
                                                        data-ekskul-id="<?= $ekskul['id'] ?>"
                                                        data-siswa-id="<?= $row['siswa_id'] ?>"
                                                        data-status="<?= $status ?>"
                                                        onclick="openEditModal(this)"
                                                        title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn-action btn-action-delete"
                                                        onclick="deleteAnggota('<?= smart_url('ekskul/anggota/delete/' . $row['id']) ?>')"
                                                        title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- TABLE FOOTER -->
                        <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center py-3">
                            <div class="text-muted small">
                                Menampilkan <span class="font-weight-bold"><?= count($anggota) ?></span> dari <?= count($anggota) ?> anggota
                            </div>
                            <div>
                                <button class="btn btn-outline-primary btn-sm" onclick="exportToExcel()">
                                    <i class="fas fa-file-excel mr-1"></i> Export
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-edit mr-2"></i>Edit Anggota
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= smart_url('ekskul/anggota/update') ?>" method="POST" id="editForm">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editId">
                    <input type="hidden" name="ekskul_id" id="editEkskulId">
                    <input type="hidden" name="siswa_id" id="editSiswaId">

                    <div class="form-group">
                        <label class="font-weight-bold text-dark mb-2">NISN</label>
                        <input type="text" class="form-control form-control-modern" id="editNisn" disabled>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark mb-2">Nama Lengkap</label>
                        <input type="text" class="form-control form-control-modern" id="editNama" disabled>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark mb-2">Kelas</label>
                        <input type="text" class="form-control form-control-modern" id="editKelas" disabled>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark mb-2">Status Keanggotaan</label>
                        <select class="form-control form-control-modern" name="status" id="editStatus" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                            <option value="alumni">Alumni</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary-custom">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Enhanced search functionality
    document.getElementById('searchTable')?.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('#tableBody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });

    // Enhanced edit modal function
    function openEditModal(element) {
        const data = element.dataset;

        document.getElementById('editId').value = data.id;
        document.getElementById('editEkskulId').value = data.ekskulId;
        document.getElementById('editSiswaId').value = data.siswaId;
        document.getElementById('editNisn').value = data.nisn;
        document.getElementById('editNama').value = data.nama;
        document.getElementById('editKelas').value = data.kelas;
        document.getElementById('editStatus').value = data.status || 'aktif';

        $('#modalEdit').modal('show');

        // Focus on status field when modal opens
        setTimeout(() => {
            document.getElementById('editStatus').focus();
        }, 500);
    }

    // Enhanced delete function with more options
    function deleteAnggota(url) {
        Swal.fire({
            title: 'Hapus Anggota?',
            html: `
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle text-warning" style="font-size: 4rem;"></i>
                    <p class="mt-3">Data yang sudah dihapus tidak dapat dikembalikan</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4361ee',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            background: '#f8f9fa',
            customClass: {
                popup: 'shadow-lg border-0'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                Swal.fire({
                    title: 'Menghapus...',
                    text: 'Sedang menghapus data',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Redirect to delete URL
                window.location.href = url;
            }
        });
    }

    // Export to Excel function
    function exportToExcel() {
        Swal.fire({
            title: 'Export Data',
            text: 'Pilih format untuk mengekspor data',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Excel (.xlsx)',
            cancelButtonText: 'PDF (.pdf)',
            showDenyButton: true,
            denyButtonText: 'CSV (.csv)',
            background: '#f8f9fa'
        }).then((result) => {
            if (result.isConfirmed) {
                // Excel export logic here
                window.location.href = '<?= smart_url('ekskul/anggota/export/excel/' . $ekskul['id']) ?>';
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                // PDF export logic here
                window.location.href = '<?= smart_url('ekskul/anggota/export/pdf/' . $ekskul['id']) ?>';
            } else if (result.isDenied) {
                // CSV export logic here
                window.location.href = '<?= smart_url('ekskul/anggota/export/csv/' . $ekskul['id']) ?>';
            }
        });
    }

    // Refresh table function
    function refreshTable() {
        const tableBody = document.getElementById('tableBody');
        if (tableBody) {
            const rows = tableBody.querySelectorAll('tr');
            const refreshBtn = event.currentTarget;

            // Add rotation animation
            refreshBtn.style.transition = 'transform 0.3s ease';
            refreshBtn.style.transform = 'rotate(360deg)';

            setTimeout(() => {
                refreshBtn.style.transform = 'rotate(0deg)';

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Diperbarui!',
                    text: 'Data tabel telah diperbarui',
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            }, 300);
        }
    }

    // Form submission handling
    document.getElementById('editForm')?.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        // Show loading state
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';
        submitBtn.disabled = true;

        // Re-enable after 3 seconds if still processing
        setTimeout(() => {
            if (submitBtn.disabled) {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }, 3000);
    });

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        $('[title]').tooltip({
            trigger: 'hover',
            placement: 'top'
        });

        // Add animation to stats cards
        const statsCards = document.querySelectorAll('.stats-card');
        statsCards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
            card.classList.add('animate__animated', 'animate__fadeInUp');
        });
    });
</script>

<?= $this->endSection() ?>