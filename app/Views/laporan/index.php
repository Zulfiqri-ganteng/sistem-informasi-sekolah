<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary-color: #4361ee;
        --success-color: #06d6a0;
        --warning-color: #ffd166;
        --danger-color: #ef476f;
        --dark-color: #2b2d42;
        --light-color: #f8f9fa;
    }

    .stat-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }

    .stat-icon.setor {
        background-color: rgba(6, 214, 160, 0.15);
        color: var(--success-color);
    }

    .stat-icon.tarik {
        background-color: rgba(255, 209, 102, 0.15);
        color: var(--warning-color);
    }

    .stat-icon.saldo {
        background-color: rgba(67, 97, 238, 0.15);
        color: var(--primary-color);
    }

    .amount {
        font-weight: 700;
        letter-spacing: -0.5px;
        margin: 8px 0;
    }

    .trend-badge {
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 20px;
    }

    .btn-export {
        border-radius: 8px;
        padding: 8px 16px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-export.excel {
        background: linear-gradient(135deg, #21d374, #1da857);
        color: white;
    }

    .btn-export.pdf {
        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
        color: white;
    }

    .btn-export.word {
        background: linear-gradient(135deg, #2b579a, #1e3a8a);
        color: white;
    }

    .filter-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        background: white;
    }

    .table-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table-header {
        background: linear-gradient(135deg, #4361ee, #3a56d4);
        color: white;
        border-radius: 12px 12px 0 0 !important;
    }

    .table>thead th {
        border-bottom: none;
        font-weight: 600;
        padding: 16px 12px;
    }

    .table-striped>tbody>tr:nth-of-type(odd) {
        background-color: rgba(67, 97, 238, 0.02);
    }

    .table-hover tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.05);
        transition: background-color 0.2s ease;
    }

    .student-link {
        color: var(--primary-color);
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s;
    }

    .student-link:hover {
        color: #3a56d4;
        text-decoration: underline;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .badge-transaksi {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-setor {
        background-color: rgba(6, 214, 160, 0.15);
        color: var(--success-color);
    }

    .badge-tarik {
        background-color: rgba(255, 209, 102, 0.15);
        color: #d4a600;
    }

    .page-title {
        color: var(--dark-color);
        font-weight: 700;
        position: relative;
        padding-bottom: 10px;
    }

    .page-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 4px;
        background: linear-gradient(to right, var(--primary-color), #7209b7);
        border-radius: 2px;
    }

    .modal-xl .modal-content {
        border-radius: 16px;
        overflow: hidden;
    }

    .detail-header {
        background: linear-gradient(135deg, #4361ee, #3a56d4);
        color: white;
        padding: 20px;
        margin: -1rem -1rem 1rem -1rem;
    }

    .search-box {
        position: relative;
        width: 300px;
    }

    .search-box input {
        padding-left: 40px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .quick-actions {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .quick-action-btn {
        flex: 1;
        padding: 12px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
        background: white;
        color: var(--dark-color);
        font-weight: 500;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .quick-action-btn:hover {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .loading-skeleton {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        border-radius: 8px;
    }

    @keyframes loading {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title mb-1">
                <i class="fas fa-piggy-bank me-2"></i>Laporan Tabungan Sekolah
            </h2>
            <p class="text-muted mb-0">Monitor dan kelola semua transaksi tabungan siswa</p>
        </div>
        <div class="d-flex gap-2">
            <button id="btnExportExcel" class="btn btn-export excel">
                <i class="fas fa-file-excel me-1"></i> Excel
            </button>
            <button id="btnExportPdf" class="btn btn-export pdf">
                <i class="fas fa-file-pdf me-1"></i> PDF
            </button>
            <button id="btnExportWord" class="btn btn-export word">
                <i class="fas fa-file-word me-1"></i> Word
            </button>
        </div>
    </div>

    <!-- Statistic Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-icon setor">
                                <i class="fas fa-arrow-down fs-4"></i>
                            </div>
                            <h6 class="text-uppercase text-muted mb-2 fw-semibold">Total Setoran</h6>
                            <h2 id="totalSetor" class="amount text-success">Rp 0</h2>
                            <span class="text-muted small">Total dana masuk</span>
                        </div>
                        <span class="trend-badge bg-success bg-opacity-10 text-success">
                            <i class="fas fa-arrow-up me-1"></i> Aktif
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-icon tarik">
                                <i class="fas fa-arrow-up fs-4"></i>
                            </div>
                            <h6 class="text-uppercase text-muted mb-2 fw-semibold">Total Tarikan</h6>
                            <h2 id="totalTarik" class="amount text-warning">Rp 0</h2>
                            <span class="text-muted small">Total dana keluar</span>
                        </div>
                        <span class="trend-badge bg-warning bg-opacity-10 text-warning">
                            <i class="fas fa-chart-line me-1"></i> Normal
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="stat-icon saldo">
                                <i class="fas fa-wallet fs-4"></i>
                            </div>
                            <h6 class="text-uppercase text-muted mb-2 fw-semibold">Total Saldo</h6>
                            <h2 id="totalSaldo" class="amount text-primary">Rp 0</h2>
                            <span class="text-muted small">Saldo keseluruhan</span>
                        </div>
                        <span class="trend-badge bg-primary bg-opacity-10 text-primary">
                            <i class="fas fa-check-circle me-1"></i> Stabil
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <button id="btnQuickReport" class="quick-action-btn">
            <i class="fas fa-file-invoice me-2"></i> Laporan Cepat
        </button>
        <button id="btnResetAll" class="quick-action-btn">
            <i class="fas fa-rotate me-2"></i> Reset Semua Filter
        </button>
    </div>

    <!-- Filter Section -->
    <div class="card filter-card mb-4">
        <div class="card-body p-4">
            <h6 class="fw-bold mb-3">
                <i class="fas fa-filter me-2 text-primary"></i> Filter Data
            </h6>
            <div class="row g-3 align-items-end">
                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-semibold">Kelas</label>
                    <select id="filterKelas" class="form-select">
                        <option value="">Semua Kelas</option>
                        <?php foreach ($lists['kelas'] ?? [] as $k): ?>
                            <option><?= esc($k) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-semibold">Jurusan</label>
                    <select id="filterJurusan" class="form-select">
                        <option value="">Semua Jurusan</option>
                        <?php foreach ($lists['jurusan'] ?? [] as $j): ?>
                            <option><?= esc($j) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-semibold">Dari Tanggal</label>
                    <input type="date" id="filterFrom" class="form-control">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-semibold">Sampai Tanggal</label>
                    <input type="date" id="filterTo" class="form-control">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-semibold">Cari Nama Siswa</label>
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="filterNama" class="form-control" placeholder="Cari nama siswa...">
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <div class="d-grid gap-2">
                        <button id="btnFilter" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i> Terapkan Filter
                        </button>
                        <button id="btnReset" class="btn btn-outline-secondary">
                            <i class="fas fa-rotate me-2"></i> Reset Filter
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card table-card">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Data Tabungan Siswa</h5>
                    <p class="text-muted small mb-0" id="tableInfo">Memuat data...</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="tableSearch" class="form-control" placeholder="Cari di tabel...">
                    </div>
                    <button id="btnRefresh" class="btn btn-outline-primary">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table id="tableLaporan" class="table table-hover w-100">
                    <thead class="table-header">
                        <tr>
                            <th class="text-white">#</th>
                            <th class="text-white">Nama Siswa</th>
                            <th class="text-white">Kelas</th>
                            <th class="text-white">Jurusan</th>
                            <th class="text-white text-end">Setoran</th>
                            <th class="text-white text-end">Tarikan</th>
                            <th class="text-white text-end">Saldo</th>
                            <th class="text-white text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data akan dimuat via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="detail-header">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="modal-title text-white mb-1">Detail Transaksi</h5>
                        <p id="detailStudentInfo" class="text-white-50 mb-0"></p>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="modal-body p-4">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-2">Total Setoran</h6>
                                <h4 id="detailTotalSetor" class="text-success fw-bold">Rp 0</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-2">Total Tarikan</h6>
                                <h4 id="detailTotalTarik" class="text-warning fw-bold">Rp 0</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-2">Saldo Akhir</h6>
                                <h4 id="detailSaldo" class="text-primary fw-bold">Rp 0</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 bg-light">
                            <div class="card-body text-center">
                                <h6 class="text-muted mb-2">Jumlah Transaksi</h6>
                                <h4 id="detailTotalTransaksi" class="text-dark fw-bold">0</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <h6 class="fw-bold mb-3">Riwayat Transaksi</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-hover" id="detailTable">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Tanggal</th>
                                <th width="120">Tipe</th>
                                <th>Keterangan</th>
                                <th class="text-end" width="150">Jumlah</th>
                                <th class="text-center" width="100">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data detail akan dimuat di sini -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button id="btnPrintDetail" class="btn btn-outline-secondary">
                    <i class="fas fa-print me-2"></i> Cetak
                </button>
                <button class="btn btn-primary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Quick Report Modal -->
<div class="modal fade" id="modalQuickReport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Laporan Cepat Tabungan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Pilih Siswa</label>
                        <select id="quickReportStudent" class="form-select">
                            <option value="">Pilih siswa...</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Periode</label>
                        <div class="input-group">
                            <input type="date" id="quickReportFrom" class="form-control">
                            <span class="input-group-text">s/d</span>
                            <input type="date" id="quickReportTo" class="form-control" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                </div>

                <div id="quickReportResult" class="d-none">
                    <div class="alert alert-info">
                        <h6 class="fw-bold">Ringkasan Laporan</h6>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Nama Siswa:</strong> <span id="reportStudentName">-</span></p>
                                <p class="mb-1"><strong>Kelas/Jurusan:</strong> <span id="reportStudentClass">-</span></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Periode:</strong> <span id="reportPeriod">-</span></p>
                                <p class="mb-1"><strong>Saldo Akhir:</strong> <span id="reportFinalBalance" class="fw-bold text-primary">Rp 0</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card border-0 bg-success bg-opacity-10">
                                <div class="card-body text-center">
                                    <h6 class="text-success mb-2">Total Setoran</h6>
                                    <h4 id="reportTotalSetor" class="text-success fw-bold">Rp 0</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 bg-warning bg-opacity-10">
                                <div class="card-body text-center">
                                    <h6 class="text-warning mb-2">Total Tarikan</h6>
                                    <h4 id="reportTotalTarik" class="text-warning fw-bold">Rp 0</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 bg-primary bg-opacity-10">
                                <div class="card-body text-center">
                                    <h6 class="text-primary mb-2">Jumlah Transaksi</h6>
                                    <h4 id="reportTransactionCount" class="text-primary fw-bold">0</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button id="btnExportQuickReport" class="btn btn-success">
                            <i class="fas fa-download me-2"></i> Export Laporan
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button id="btnGenerateQuickReport" class="btn btn-primary">
                    <i class="fas fa-chart-pie me-2"></i> Buat Laporan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(function() {
        const base = '<?= smart_url() ?>';
        let currentStudentId = null;
        let allStudents = [];

        // Initialize Select2
        $('#filterKelas, #filterJurusan, #quickReportStudent').select2({
            theme: 'bootstrap-5',
            width: '100%',
            placeholder: 'Pilih opsi...',
            allowClear: true
        });

        // Format currency
        function formatCurrency(amount) {
            return 'Rp ' + Number(amount || 0).toLocaleString('id-ID');
        }

        // Format date
        function formatDate(dateString) {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        }

        // Load all students for quick report
        function loadAllStudents() {
            $.ajax({
                    url: base + '/api/siswa/list',
                    method: 'GET',
                    dataType: 'json'
                })
                .done(res => {
                    const siswaList = res.data || res || [];
                    allStudents = siswaList;

                    // Populate quick report select
                    $('#quickReportStudent').empty().append('<option value="">Pilih siswa...</option>');
                    siswaList.forEach(s => {
                        $('#quickReportStudent').append(`<option value="${s.id}">${s.nama} - ${s.kelas} (${s.jurusan || '-'})</option>`);
                    });
                })
                .fail(() => {
                    console.warn('Gagal memuat daftar siswa');
                });
        }

        // Initialize DataTable
        const table = $('#tableLaporan').DataTable({
            ajax: {
                url: base + '/laporan/data',
                data: function(d) {
                    d.kelas = $('#filterKelas').val() || '';
                    d.jurusan = $('#filterJurusan').val() || '';
                    d.from = $('#filterFrom').val() || '';
                    d.to = $('#filterTo').val() || '';
                    d.nama = $('#filterNama').val() || '';
                },
                dataSrc: function(json) {
                    const meta = json.meta || {
                        totalSetor: 0,
                        totalTarik: 0,
                        totalSaldo: 0,
                        totalRecords: 0
                    };

                    // Update statistics
                    $('#totalSetor').text(formatCurrency(meta.totalSetor));
                    $('#totalTarik').text(formatCurrency(meta.totalTarik));
                    $('#totalSaldo').text(formatCurrency(meta.totalSaldo));

                    // Update table info
                    const records = meta.totalRecords || 0;
                    $('#tableInfo').text(`Menampilkan ${records} data siswa`);

                    return json.data || [];
                },
                error: function(xhr, error, thrown) {
                    console.error('Gagal memuat data:', error);
                    $('#tableInfo').text('Gagal memuat data. Silakan coba lagi.');
                }
            },
            columns: [{
                    data: null,
                    render: function(data, type, row, meta) {
                        return `<span class="text-muted">${meta.row + 1}</span>`;
                    },
                    className: 'text-center',
                    width: '50px'
                },
                {
                    data: 'nama',
                    render: function(data, type, row) {
                        return `
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                        <i class="fas fa-user text-primary"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <a href="#" class="student-link link-detail" data-id="${row.id}">${data}</a>
                                    <div class="text-muted small">${row.nis || '-'}</div>
                                </div>
                            </div>
                        `;
                    }
                },
                {
                    data: 'kelas',
                    className: 'fw-semibold'
                },
                {
                    data: 'jurusan',
                    render: function(data) {
                        return `<span class="badge bg-secondary bg-opacity-10 text-secondary">${data || '-'}</span>`;
                    }
                },
                {
                    data: 'total_setor',
                    className: 'text-end fw-semibold',
                    render: function(data) {
                        return `<span class="text-success">${formatCurrency(data)}</span>`;
                    }
                },
                {
                    data: 'total_tarik',
                    className: 'text-end fw-semibold',
                    render: function(data) {
                        return `<span class="text-warning">${formatCurrency(data)}</span>`;
                    }
                },
                {
                    data: 'saldo',
                    className: 'text-end fw-bold',
                    render: function(data) {
                        const color = data >= 0 ? 'text-primary' : 'text-danger';
                        return `<span class="${color}">${formatCurrency(data)}</span>`;
                    }
                },
                {
                    data: null,
                    className: 'text-center',
                    render: function(data) {
                        return `
                            <button class="btn btn-sm btn-outline-primary action-btn btnDetail" data-id="${data.id}" title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-success action-btn btnHistory" data-id="${data.id}" title="Riwayat">
                                <i class="fas fa-history"></i>
                            </button>
                        `;
                    },
                    width: '100px'
                }
            ],
            pageLength: 25,
            responsive: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                zeroRecords: "Tidak ada data yang cocok",
                paginate: {
                    first: "«",
                    last: "»",
                    next: "›",
                    previous: "‹"
                }
            },
            initComplete: function() {
                // Add search functionality
                $('#tableSearch').on('keyup', function() {
                    table.search(this.value).draw();
                });
            }
        });

        // Filter actions
        $('#btnFilter').click(function() {
            table.ajax.reload();
        });

        $('#btnReset').click(function() {
            $('#filterKelas, #filterJurusan, #filterFrom, #filterTo, #filterNama').val('').trigger('change');
            setTimeout(() => table.ajax.reload(), 100);
        });

        $('#btnResetAll').click(function() {
            $('#filterKelas, #filterJurusan, #filterFrom, #filterTo, #filterNama').val('').trigger('change');
            $('#tableSearch').val('');
            setTimeout(() => {
                table.search('').draw();
                table.ajax.reload();
            }, 100);
        });

        $('#btnRefresh').click(function() {
            table.ajax.reload();
            $(this).find('i').addClass('fa-spin');
            setTimeout(() => {
                $(this).find('i').removeClass('fa-spin');
            }, 500);
        });

        // Export functions
        $('#btnExportExcel').click(() => {
            window.location = base + '/laporan/export-excel?' + $.param({
                kelas: $('#filterKelas').val() || '',
                jurusan: $('#filterJurusan').val() || '',
                from: $('#filterFrom').val() || '',
                to: $('#filterTo').val() || '',
                nama: $('#filterNama').val() || ''
            });
        });

        $('#btnExportPdf').click(() => {
            window.location = base + '/laporan/export-pdf?' + $.param({
                kelas: $('#filterKelas').val() || '',
                jurusan: $('#filterJurusan').val() || '',
                from: $('#filterFrom').val() || '',
                to: $('#filterTo').val() || '',
                nama: $('#filterNama').val() || ''
            });
        });

        $('#btnExportWord').click(() => {
            window.location = base + '/laporan/export-word?' + $.param({
                kelas: $('#filterKelas').val() || '',
                jurusan: $('#filterJurusan').val() || '',
                from: $('#filterFrom').val() || '',
                to: $('#filterTo').val() || '',
                nama: $('#filterNama').val() || ''
            });
        });

        // Quick report functionality
        $('#btnQuickReport').click(function() {
            $('#modalQuickReport').modal('show');
        });

        $('#btnGenerateQuickReport').click(function() {
            const studentId = $('#quickReportStudent').val();
            const fromDate = $('#quickReportFrom').val();
            const toDate = $('#quickReportTo').val();

            if (!studentId) {
                alert('Pilih siswa terlebih dahulu!');
                return;
            }

            // Show loading
            $('#quickReportResult').addClass('d-none');
            $(this).html('<i class="fas fa-spinner fa-spin me-2"></i> Memproses...').prop('disabled', true);

            // Find student info
            const student = allStudents.find(s => s.id == studentId);
            if (student) {
                $('#reportStudentName').text(student.nama);
                $('#reportStudentClass').text(`${student.kelas} / ${student.jurusan || '-'}`);
            }

            $('#reportPeriod').text(`${fromDate || 'Semua waktu'} - ${toDate || 'Sekarang'}`);

            // Fetch student transaction data
            $.ajax({
                    url: base + '/laporan/detail/' + studentId,
                    method: 'GET',
                    data: {
                        from: fromDate,
                        to: toDate
                    },
                    dataType: 'json'
                })
                .done(res => {
                    const rows = res.data || [];

                    // Calculate totals
                    let totalSetor = 0;
                    let totalTarik = 0;

                    rows.forEach(r => {
                        if (r.tipe === 'setor') totalSetor += parseFloat(r.jumlah || 0);
                        if (r.tipe === 'tarik') totalTarik += parseFloat(r.jumlah || 0);
                    });

                    const finalBalance = totalSetor - totalTarik;
                    const transactionCount = rows.length;

                    // Update report display
                    $('#reportTotalSetor').text(formatCurrency(totalSetor));
                    $('#reportTotalTarik').text(formatCurrency(totalTarik));
                    $('#reportTransactionCount').text(transactionCount);
                    $('#reportFinalBalance').text(formatCurrency(finalBalance));

                    // Show result
                    $('#quickReportResult').removeClass('d-none');
                })
                .fail(() => {
                    alert('Gagal memuat data laporan. Silakan coba lagi.');
                })
                .always(() => {
                    $(this).html('<i class="fas fa-chart-pie me-2"></i> Buat Laporan').prop('disabled', false);
                });
        });

        $('#btnExportQuickReport').click(function() {
            const studentId = $('#quickReportStudent').val();
            const fromDate = $('#quickReportFrom').val();
            const toDate = $('#quickReportTo').val();

            if (!studentId) {
                alert('Pilih siswa terlebih dahulu!');
                return;
            }

            // Redirect to export endpoint
            window.location = base + '/laporan/export-siswa/' + studentId + '?' + $.param({
                from: fromDate,
                to: toDate,
                type: 'pdf'
            });
        });

        // Detail modal
        function loadStudentDetail(studentId) {
            currentStudentId = studentId;
            $('#detailTable tbody').html('<tr><td colspan="6" class="text-center">Memuat data...</td></tr>');

            $.ajax({
                    url: base + '/laporan/detail/' + studentId,
                    method: 'GET',
                    dataType: 'json'
                })
                .done(res => {
                    const rows = res.data || [];
                    const studentInfo = rows.length ? rows[0] : {};

                    // Update student info
                    $('#detailStudentInfo').html(`
                    ${studentInfo.siswa_nama || ''} • ${studentInfo.kelas || ''} • ${studentInfo.jurusan || ''}
                `);

                    // Calculate totals
                    let totalSetor = 0;
                    let totalTarik = 0;
                    let totalTransaksi = 0;

                    rows.forEach(r => {
                        if (r.tipe === 'setor') totalSetor += parseFloat(r.jumlah || 0);
                        if (r.tipe === 'tarik') totalTarik += parseFloat(r.jumlah || 0);
                        totalTransaksi++;
                    });

                    const saldoAkhir = totalSetor - totalTarik;

                    // Update summary cards
                    $('#detailTotalSetor').text(formatCurrency(totalSetor));
                    $('#detailTotalTarik').text(formatCurrency(totalTarik));
                    $('#detailSaldo').text(formatCurrency(saldoAkhir));
                    $('#detailTotalTransaksi').text(totalTransaksi);

                    // Update table
                    let html = '';
                    if (rows.length > 0) {
                        rows.forEach((r, i) => {
                            const badgeClass = r.tipe === 'setor' ? 'badge-setor' : 'badge-tarik';
                            const badgeText = r.tipe === 'setor' ? 'SETOR' : 'TARIK';
                            const textColor = r.tipe === 'setor' ? 'text-success' : 'text-warning';

                            html += `
                            <tr>
                                <td class="text-muted">${i + 1}</td>
                                <td>
                                    <div class="fw-semibold">${formatDate(r.created_at)}</div>
                                    <div class="text-muted small">${r.created_at ? new Date(r.created_at).toLocaleTimeString('id-ID') : ''}</div>
                                </td>
                                <td>
                                    <span class="badge-transaksi ${badgeClass}">${badgeText}</span>
                                </td>
                                <td>${r.keterangan || '-'}</td>
                                <td class="text-end fw-semibold ${textColor}">
                                    ${r.tipe === 'setor' ? '+' : '-'} ${formatCurrency(r.jumlah)}
                                </td>
                                <td class="text-center">
                                    <i class="fas fa-check-circle text-success"></i>
                                </td>
                            </tr>
                        `;
                        });
                    } else {
                        html = '<tr><td colspan="6" class="text-center py-4 text-muted">Belum ada transaksi</td></tr>';
                    }

                    $('#detailTable tbody').html(html);
                    $('#modalDetail').modal('show');
                })
                .fail(() => {
                    $('#detailTable tbody').html('<tr><td colspan="6" class="text-center text-danger">Gagal memuat data detail</td></tr>');
                });
        }

        // Event handlers for detail buttons
        $(document).on('click', '.link-detail, .btnDetail', function(e) {
            e.preventDefault();
            const studentId = $(this).data('id');
            if (studentId) {
                loadStudentDetail(studentId);
            }
        });

        // Print detail
        $('#btnPrintDetail').click(function() {
            const printWindow = window.open('', '_blank');
            const content = `
                <html>
                <head>
                    <title>Detail Transaksi - Tabungan Sekolah</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        h3 { color: #333; border-bottom: 2px solid #4361ee; padding-bottom: 10px; }
                        .info { margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 5px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th { background: #4361ee; color: white; padding: 10px; text-align: left; }
                        td { padding: 8px; border-bottom: 1px solid #ddd; }
                        .total { font-weight: bold; margin-top: 20px; }
                        @media print {
                            body { margin: 0; }
                            .no-print { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <h3>Detail Transaksi Tabungan</h3>
                    <div class="info">${$('#detailStudentInfo').html()}</div>
                    ${$('#detailTable').parent().html()}
                    <div class="total">
                        Dicetak pada: ${new Date().toLocaleString('id-ID')}
                    </div>
                </body>
                </html>
            `;

            printWindow.document.open();
            printWindow.document.write(content);
            printWindow.document.close();
            printWindow.print();
        });

        // Initialize
        loadAllStudents();
        table.ajax.reload();

        // Set default dates for filters
        const today = new Date();
        const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        $('#filterFrom').val(firstDayOfMonth.toISOString().split('T')[0]);
        $('#filterTo').val(today.toISOString().split('T')[0]);

        // Auto-refresh every 60 seconds
        setInterval(() => {
            table.ajax.reload(null, false);
        }, 60000);
    });
</script>

<?= $this->endSection(); ?>