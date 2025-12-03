<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<style>
    /* Modern Glassmorphism Design */
    :root {
        --primary: #4361ee;
        --primary-light: #4895ef;
        --secondary: #3f37c9;
        --success: #4cc9f0;
        --warning: #f72585;
        --dark: #1e1e2c;
        --light: #f8f9fa;
        --glass-bg: rgba(255, 255, 255, 0.9);
        --glass-border: rgba(255, 255, 255, 0.2);
        --shadow-light: 0 8px 32px rgba(0, 0, 0, 0.1);
        --shadow-medium: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .dashboard-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Glass Card Effect */
    .glass-card {
        background: var(--glass-bg);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 16px;
        border: 1px solid var(--glass-border);
        box-shadow: var(--shadow-light);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
    }

    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-medium);
    }

    /* Modern Header */
    .dashboard-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        border-radius: 16px;
        padding: 25px 30px;
        margin-bottom: 25px;
        box-shadow: var(--shadow-medium);
        position: relative;
        overflow: hidden;
    }

    .dashboard-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }

    .dashboard-header h1 {
        font-weight: 800;
        font-size: 2.2rem;
        margin-bottom: 8px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .dashboard-header p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }

    /* Stats Cards Modern - Simplified to 2 cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
        padding: 25px;
        border-radius: 16px;
        text-align: center;
        box-shadow: var(--shadow-medium);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:nth-child(2) {
        background: linear-gradient(135deg, #7209b7 0%, #b5179e 100%);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(rgba(255, 255, 255, 0.1), transparent);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
        opacity: 0.9;
    }

    .stat-number {
        font-size: 2.8rem;
        font-weight: 800;
        margin-bottom: 5px;
        line-height: 1;
    }

    .stat-label {
        font-size: 1rem;
        opacity: 0.9;
        font-weight: 500;
        letter-spacing: 0.5px;
    }

    /* Enhanced Table */
    .table-modern {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-light);
        border: none;
    }

    .table-modern thead {
        background: linear-gradient(135deg, var(--dark) 0%, #2d3047 100%);
    }

    .table-modern thead th {
        border: none;
        padding: 18px 15px;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: white;
        vertical-align: middle;
    }

    .table-modern tbody td {
        padding: 16px 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f4;
        font-weight: 500;
    }

    .table-modern tbody tr {
        transition: all 0.3s ease;
    }

    .table-modern tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.002);
    }

    /* Action Buttons */
    .action-group {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-action:hover {
        transform: translateY(-3px);
        box-shadow: 0 7px 10px rgba(0, 0, 0, 0.15);
    }

    /* Modern Modal */
    .modal-glass {
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
    }

    .modal-glass .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        overflow: hidden;
    }

    .modal-glass .modal-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
        border-bottom: none;
        padding: 20px 25px;
    }

    .modal-glass .modal-body {
        padding: 25px;
    }

    .modal-glass .modal-footer {
        border-top: 1px solid #eee;
        padding: 20px 25px;
    }

    /* Form Styling */
    .form-modern .form-control {
        border-radius: 10px;
        padding: 12px 15px;
        border: 1px solid #e0e0e0;
        transition: all 0.3s ease;
    }

    .form-modern .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }

    .form-modern .input-group-text {
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-right: none;
    }

    /* Button Styles */
    .btn-modern {
        border-radius: 10px;
        padding: 12px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0 7px 10px rgba(0, 0, 0, 0.15);
    }

    .btn-modern-primary {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
        color: white;
    }

    /* Card Header */
    .card-header-modern {
        background: white;
        border-bottom: 1px solid #f0f0f0;
        padding: 20px 25px;
        border-radius: 16px 16px 0 0 !important;
    }

    /* Search Box */
    .search-box {
        background: white;
        border-radius: 12px;
        padding: 15px 20px;
        box-shadow: var(--shadow-light);
        margin-bottom: 20px;
    }

    /* Loading Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in {
        animation: fadeIn 0.6s ease-in-out;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: var(--secondary);
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 10px;
        }

        .dashboard-header {
            padding: 20px;
        }

        .dashboard-header h1 {
            font-size: 1.8rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .stat-card {
            padding: 20px;
        }

        .table-modern thead {
            display: none;
        }

        .table-modern tbody tr {
            display: block;
            margin-bottom: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 15px;
        }

        .table-modern td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 8px;
            border: none;
            border-bottom: 1px solid #f1f3f4;
        }

        .table-modern td:before {
            content: attr(data-label);
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            font-size: 0.75rem;
            width: 40%;
        }

        .table-modern td:last-child {
            border-bottom: none;
            justify-content: center;
            gap: 10px;
            padding-top: 15px;
        }

        .action-group {
            flex-direction: row;
            width: 100%;
        }

        .btn-action {
            width: 40px;
            height: 40px;
            flex: 1;
        }
    }

    @media (max-width: 576px) {
        .dashboard-header h1 {
            font-size: 1.5rem;
        }

        .stat-number {
            font-size: 2.2rem;
        }

        .modal-glass .modal-dialog {
            margin: 10px;
        }

        .btn-modern {
            padding: 10px 20px;
            font-size: 0.9rem;
        }
    }
</style>

<div class="dashboard-container fade-in">
    <!-- Modern Header -->
    <div class="dashboard-header glass-card">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1><i class="fas fa-graduation-cap mr-2"></i>Data Jurusan</h1>
                <p>Kelola program studi dan jurusan akademik sekolah</p>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <button type="button" class="btn btn-modern btn-modern-primary" id="btnAdd">
                    <i class="fas fa-plus-circle mr-2"></i> Tambah Jurusan
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards - Simplified -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-school"></i>
            </div>
            <div class="stat-number" id="totalJurusan">0</div>
            <div class="stat-label">Total Jurusan</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number" id="activeJurusan">0</div>
            <div class="stat-label">Jurusan Aktif</div>
        </div>
    </div>

    <!-- Search Box -->
    <div class="search-box glass-card">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" id="searchInput" class="form-control" placeholder="Cari nama jurusan...">
                    <div class="input-group-append">
                        <span class="input-group-text bg-primary text-white border-0">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-md-right mt-2 mt-md-0">
                <button type="button" class="btn btn-outline-primary btn-sm" id="btnRefresh">
                    <i class="fas fa-sync-alt mr-1"></i> Refresh Data
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="glass-card">
        <div class="card-header-modern d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3 class="card-title mb-1 text-dark" style="font-weight: 700;">
                    <i class="fas fa-list-alt text-primary mr-2"></i>Daftar Jurusan
                </h3>
                <p class="text-muted mb-0">Kelola semua jurusan akademik sekolah</p>
            </div>
            <div class="mt-2 mt-md-0">
                <div class="text-muted">
                    <i class="fas fa-info-circle me-1"></i> Total: <span id="counterJurusan">0</span> jurusan
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="tbljurusan" class="table table-modern w-100">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th>Nama Jurusan</th>
                            <th width="15%" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated by DataTables -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade modal-glass" id="modalForm" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formjurusan" autocomplete="off" class="form-modern">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-graduation-cap me-2"></i> <span id="modalTitle">Tambah Jurusan</span>
                    </h5>
                    <button type="button" class="close text-white" data-bs-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">

                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">Nama Jurusan <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-light border-right-0">
                                    <i class="fas fa-school text-primary"></i>
                                </span>
                            </div>
                            <input type="text" name="nama_jurusan" id="nama_jurusan" class="form-control border-left-0"
                                placeholder="Masukkan nama jurusan" required>
                        </div>
                        <small class="form-text text-muted">Contoh: Teknik Komputer dan Informatika, Akuntansi, dll.</small>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary btn-modern" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary btn-modern shadow-sm">
                        <i class="fas fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ✅ Pastikan jQuery sudah termuat
        if (typeof $ === 'undefined') {
            console.error('jQuery belum dimuat! Pastikan layout/main.php memuat jQuery sebelum renderSection("scripts").');
            return;
        }

        $(function() {
            const base = '<?= base_url('jurusan') ?>';
            let lastAddedId = null;

            // 🔹 Inisialisasi DataTable dengan konfigurasi modern
            const table = $('#tbljurusan').DataTable({
                ajax: {
                    url: base + '/list',
                    dataSrc: 'data',
                    error: function(xhr, error, thrown) {
                        console.error('Error loading data:', error);
                        Swal.fire('Error', 'Gagal memuat data jurusan', 'error');
                    }
                },
                responsive: true,
                order: [
                    [1, 'asc']
                ],
                paging: true,
                pageLength: 10,
                lengthChange: true,
                searching: true,
                dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ jurusan",
                    emptyTable: "Belum ada data jurusan.",
                    paginate: {
                        previous: "<i class='fas fa-chevron-left'></i>",
                        next: "<i class='fas fa-chevron-right'></i>"
                    }
                },
                createdRow: function(row, data) {
                    // Highlight row baru setelah ditambahkan
                    if (lastAddedId && data.id == lastAddedId) {
                        $(row).addClass('highlight-new');
                        setTimeout(() => $(row).removeClass('highlight-new'), 3000);
                    }

                    // Add data labels for mobile
                    const headers = ['No', 'Nama Jurusan', 'Aksi'];
                    $(row).find('td').each(function(i) {
                        if (headers[i]) {
                            $(this).attr('data-label', headers[i]);
                        }
                    });
                },
                columns: [{
                        data: null,
                        className: 'text-center',
                        render: (d, t, r, m) => m.row + 1
                    },
                    {
                        data: 'nama_jurusan',
                        render: d => `<span class="fw-semibold">${d}</span>`
                    },
                    {
                        data: null,
                        className: 'text-center',
                        render: function(data, type, row) {
                            return `
                            <div class="action-group">
                                <button class="btn btn-info btn-action edit" data-id="${data.id}" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger btn-action del" data-id="${data.id}" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>`;
                        }
                    }
                ],
                initComplete: function() {
                    // Update statistics
                    try {
                        const data = this.api().data().toArray();
                        const totalJurusan = data.length;
                        $('#totalJurusan').text(totalJurusan);
                        $('#activeJurusan').text(totalJurusan); // Asumsi semua aktif
                        $('#counterJurusan').text(totalJurusan);
                    } catch (error) {
                        console.error('Error updating statistics:', error);
                    }
                },
                drawCallback: function() {
                    // Update counter setiap kali tabel di-redraw
                    const data = this.api().data().toArray();
                    $('#counterJurusan').text(data.length);
                }
            });

            // 🔹 Enhanced search functionality
            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw();
            });

            // 🔹 Refresh button
            $('#btnRefresh').click(function() {
                const btn = $(this);
                const originalHtml = btn.html();

                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Loading...');

                table.ajax.reload(() => {
                    btn.prop('disabled', false).html(originalHtml);
                    Swal.fire({
                        icon: 'success',
                        title: 'Data diperbarui!',
                        timer: 1500,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                });
            });

            // 🔹 Tombol Tambah
            $('#btnAdd').click(function() {
                $('#formjurusan')[0].reset();
                $('#id').val('');
                $('#modalTitle').text('Tambah Jurusan');
                $('#modalForm').modal('show');
            });

            // 🔹 Simpan Data dengan enhanced UX
            $('#formjurusan').submit(function(e) {
                e.preventDefault();
                const submitBtn = $(this).find('button[type="submit"]');
                const originalText = submitBtn.html();

                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...');

                $.ajax({
                    url: base + '/save',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: res => {
                        if (res && res.success) {
                            lastAddedId = res.id || null;
                            $('#modalForm').modal('hide');
                            table.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Data jurusan berhasil disimpan.',
                                timer: 2000,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end'
                            });
                        } else {
                            const errorMsg = (res && res.message) ? res.message : 'Terjadi kesalahan saat menyimpan data.';
                            Swal.fire('Gagal', errorMsg, 'error');
                        }
                    },
                    error: (xhr, status, error) => {
                        console.error('AJAX Error:', status, error);
                        Swal.fire('Error', 'Terjadi kesalahan pada server: ' + error, 'error');
                    },
                    complete: () => {
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });

            // 🔹 Edit Data dengan enhanced error handling
            $('#tbljurusan').on('click', '.edit', function() {
                const id = $(this).data('id');
                $.get(base + '/get/' + id)
                    .done(res => {
                        if (res) {
                            $('#id').val(res.id);
                            $('#nama_jurusan').val(res.nama_jurusan);
                            $('#modalTitle').text('Edit Jurusan');
                            $('#modalForm').modal('show');
                        } else {
                            Swal.fire('Error', 'Data jurusan tidak ditemukan', 'error');
                        }
                    })
                    .fail(() => {
                        Swal.fire('Error', 'Gagal memuat data jurusan', 'error');
                    });
            });

            // 🔹 Hapus Data dengan konfirmasi yang lebih baik
            $('#tbljurusan').on('click', '.del', function() {
                const id = $(this).data('id');
                const row = $(this).closest('tr');
                const jurusanName = row.find('td:eq(1)').text().trim();

                Swal.fire({
                    title: 'Hapus Jurusan?',
                    html: `Anda akan menghapus jurusan: <strong>"${jurusanName}"</strong><br><small class="text-muted">Data yang dihapus tidak dapat dikembalikan!</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    backdrop: true
                }).then(result => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: base + '/delete/' + id,
                            method: 'GET',
                            success: (res) => {
                                if (res && res.success) {
                                    table.ajax.reload(null, false);
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Terhapus!',
                                        text: 'Data jurusan berhasil dihapus.',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                } else {
                                    const errorMsg = (res && res.message) ? res.message : 'Terjadi kesalahan saat menghapus data';
                                    Swal.fire('Gagal!', errorMsg, 'error');
                                }
                            },
                            error: () => {
                                Swal.fire('Error!', 'Terjadi kesalahan pada server.', 'error');
                            }
                        });
                    }
                });
            });
        });
    });
</script>

<style>
    .highlight-new {
        background: linear-gradient(135deg, #d4edda, #c3e6cb) !important;
        animation: pulseHighlight 2s ease-in-out;
    }

    @keyframes pulseHighlight {
        0% {
            background-color: #d4edda;
        }

        50% {
            background-color: #a1e1af;
        }

        100% {
            background-color: #d4edda;
        }
    }

    /* DataTables custom styling */
    .dataTables_wrapper .dataTables_filter {
        float: right;
        text-align: right;
    }

    .dataTables_wrapper .dataTables_paginate {
        float: right;
        margin-top: 10px;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 8px !important;
        margin: 0 3px;
        border: 1px solid #dee2e6 !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%) !important;
        color: white !important;
        border: none !important;
    }
</style>
<?= $this->endSection(); ?>