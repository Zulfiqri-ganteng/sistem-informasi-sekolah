<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<!-- CSS Libraries -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<style>
    /* Modern Design System */
    :root {
        --primary: #4361ee;
        --secondary: #6c757d;
        --success: #06d6a0;
        --warning: #ffd166;
        --danger: #ef476f;
        --light: #f8f9fa;
        --dark: #212529;
        --border-radius: 12px;
        --box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    .upload-section {
        background: linear-gradient(135deg, #f5f7ff 0%, #f0f4ff 100%);
        border-radius: var(--border-radius);
        padding: 2.5rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(67, 97, 238, 0.1);
        box-shadow: var(--box-shadow);
    }

    .upload-zone {
        border: 3px dashed #c7d2fe;
        border-radius: var(--border-radius);
        padding: 3rem 2rem;
        text-align: center;
        background: rgba(255, 255, 255, 0.9);
        transition: var(--transition);
        cursor: pointer;
    }

    .upload-zone:hover {
        border-color: var(--primary);
        background: rgba(67, 97, 238, 0.02);
        transform: translateY(-2px);
    }

    .upload-zone.dragover {
        border-color: var(--primary);
        background: rgba(67, 97, 238, 0.05);
    }

    .upload-zone i {
        font-size: 4rem;
        color: var(--primary);
        margin-bottom: 1rem;
        display: block;
    }

    .file-info {
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        margin-top: 1rem;
        display: none;
    }

    .file-info.show {
        display: block;
        animation: fadeIn 0.5s ease;
    }

    .preview-card {
        background: white;
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--box-shadow);
        border: 1px solid #e9ecef;
        display: none;
    }

    .preview-card.show {
        display: block;
        animation: slideInUp 0.5s ease;
    }

    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-valid {
        background: rgba(6, 214, 160, 0.15);
        color: #06b48a;
    }

    .status-warning {
        background: rgba(255, 209, 102, 0.15);
        color: #e6b400;
    }

    .status-error {
        background: rgba(239, 71, 111, 0.15);
        color: #d43f63;
    }

    .editable-cell {
        position: relative;
        min-width: 120px;
    }

    .editable-cell:hover {
        background: rgba(67, 97, 238, 0.05);
    }

    .editable-content {
        padding: 0.5rem;
        border-radius: 6px;
        outline: none;
        min-height: 38px;
        display: flex;
        align-items: center;
        transition: var(--transition);
    }

    .editable-content:focus {
        background: rgba(67, 97, 238, 0.08);
        box-shadow: 0 0 0 2px rgba(67, 97, 238, 0.2);
    }

    .highlight-auto {
        background: rgba(255, 209, 102, 0.1) !important;
    }

    .highlight-error {
        background: rgba(239, 71, 111, 0.08) !important;
    }

    .stats-card {
        background: white;
        padding: 1.5rem;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        margin-bottom: 1.5rem;
    }

    .progress-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 9999;
        display: none;
    }

    .progress-container.show {
        display: block;
    }

    .import-summary {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1rem;
    }

    .tooltip-hover {
        position: relative;
        cursor: help;
    }

    .tooltip-hover::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: var(--dark);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.8rem;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: var(--transition);
        z-index: 1000;
    }

    .tooltip-hover:hover::after {
        opacity: 1;
        visibility: visible;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 768px) {
        .upload-section {
            padding: 1.5rem;
        }
        
        .upload-zone {
            padding: 2rem 1rem;
        }
        
        .btn-group-responsive {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .btn-group-responsive .btn {
            width: 100%;
        }
    }
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h3 mb-1"><i class="fas fa-file-import text-primary me-2"></i>Import Siswa</h2>
                    <p class="text-muted mb-0">Impor data siswa dari file Excel/CSV secara massal</p>
                </div>
                <div>
                    <a href="<?= site_url('siswa') ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Progress Bar -->
    <div class="progress-container" id="globalProgress">
        <div class="progress" style="height: 4px; border-radius: 0;">
            <div id="globalProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                 role="progressbar" style="width: 0%"></div>
        </div>
    </div>

    <!-- Upload Section -->
    <div class="row">
        <div class="col-12">
            <div class="upload-section">
                <div class="upload-zone" id="dropZone">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <h4 class="mb-2">Seret & Lepas File di sini</h4>
                    <p class="text-muted mb-3">Atau klik untuk memilih file</p>
                    <p class="small text-muted mb-3">
                        Format yang didukung: .xlsx, .xls, .csv<br>
                        Maksimal ukuran: 10MB
                    </p>
                    
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <input type="file" id="fileInput" accept=".xlsx,.xls,.csv" class="d-none">
                        <button class="btn btn-primary px-4" id="selectFileBtn">
                            <i class="fas fa-folder-open me-2"></i>Pilih File
                        </button>
                        <a href="<?= site_url('siswa/template') ?>" class="btn btn-outline-primary px-4">
                            <i class="fas fa-download me-2"></i>Template
                        </a>
                    </div>
                </div>

                <!-- File Info -->
                <div class="file-info" id="fileInfo">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file-excel text-success me-2"></i>
                            <span id="fileName" class="fw-semibold"></span>
                            <small class="text-muted ms-2" id="fileSize"></small>
                        </div>
                        <div>
                            <button class="btn btn-sm btn-outline-danger" id="removeFileBtn">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-center mt-4" id="uploadActions" style="display: none;">
                    <button class="btn btn-success px-5" id="uploadBtn">
                        <i class="fas fa-eye me-2"></i>Preview Data
                    </button>
                    <button class="btn btn-outline-secondary" id="resetBtn">
                        <i class="fas fa-redo me-2"></i>Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats & Preview -->
    <div class="preview-card" id="previewCard">
        <!-- Stats Row -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-list text-primary fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-0" id="totalRows">0</h5>
                            <small class="text-muted">Total Baris</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-check-circle text-success fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-0" id="validRows">0</h5>
                            <small class="text-muted">Data Valid</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-exclamation-triangle text-warning fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-0" id="warningRows">0</h5>
                            <small class="text-muted">Peringatan</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="bg-danger bg-opacity-10 p-3 rounded-circle me-3">
                            <i class="fas fa-times-circle text-danger fa-lg"></i>
                        </div>
                        <div>
                            <h5 class="mb-0" id="errorRows">0</h5>
                            <small class="text-muted">Error</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control border-start-0" 
                           placeholder="Cari di semua kolom...">
                    <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end gap-2">
                    <select id="filterStatus" class="form-select" style="max-width: 200px;">
                        <option value="">Semua Status</option>
                        <option value="valid">Valid</option>
                        <option value="warning">Peringatan</option>
                        <option value="error">Error</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Preview Table -->
        <div class="table-responsive border rounded">
            <table id="previewTable" class="table table-hover mb-0" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th width="120">NISN</th>
                        <th>Nama</th>
                        <th width="80">JK</th>
                        <th>Kelas</th>
                        <th>Match</th>
                        <th>Jurusan</th>
                        <th>Match</th>
                        <th>Telepon</th>
                        <th>Email</th>
                        <th>Alamat</th>
                        <th width="100">Status</th>
                        <th width="50">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="btn-group-responsive d-flex gap-2">
                        <button class="btn btn-outline-warning" id="validateBtn">
                            <i class="fas fa-check-double me-2"></i>Validasi Ulang
                        </button>
                        <button class="btn btn-outline-info" id="autoFixBtn">
                            <i class="fas fa-magic me-2"></i>Perbaiki Otomatis
                        </button>
                        <button class="btn btn-outline-secondary" id="exportBtn">
                            <i class="fas fa-download me-2"></i>Ekspor CSV
                        </button>
                    </div>
                    
                    <div class="text-end">
                        <div class="d-flex align-items-center gap-3">
                            <span class="text-muted">
                                <span id="selectedCount">0</span> dipilih
                            </span>
                            <button class="btn btn-success btn-lg px-4 py-2" id="importBtn">
                                <i class="fas fa-upload me-2"></i>Import Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Import Summary Modal -->
    <div class="modal fade" id="summaryModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Import Berhasil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center p-4">
                                <div class="bg-success bg-opacity-10 p-4 rounded-circle d-inline-block mb-3">
                                    <i class="fas fa-check fa-3x text-success"></i>
                                </div>
                                <h3 id="importSuccessCount">0</h3>
                                <p class="text-muted mb-0">Data Berhasil</p>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="import-summary" id="importSummary">
                                <!-- Summary will be populated here -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <a href="<?= site_url('siswa') ?>" class="btn btn-primary">
                        <i class="fas fa-list me-2"></i>Lihat Data Siswa
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Modern Import System
class SiswaImport {
    constructor() {
        this.initElements();
        this.initEvents();
        this.initDataTable();
        this.state = {
            file: null,
            previewData: [],
            selectedRows: new Set(),
            stats: { total: 0, valid: 0, warning: 0, error: 0 }
        };
    }

    initElements() {
        this.elements = {
            dropZone: document.getElementById('dropZone'),
            fileInput: document.getElementById('fileInput'),
            selectFileBtn: document.getElementById('selectFileBtn'),
            fileInfo: document.getElementById('fileInfo'),
            fileName: document.getElementById('fileName'),
            fileSize: document.getElementById('fileSize'),
            removeFileBtn: document.getElementById('removeFileBtn'),
            uploadActions: document.getElementById('uploadActions'),
            uploadBtn: document.getElementById('uploadBtn'),
            resetBtn: document.getElementById('resetBtn'),
            previewCard: document.getElementById('previewCard'),
            previewTable: $('#previewTable'),
            searchInput: document.getElementById('searchInput'),
            clearSearch: document.getElementById('clearSearch'),
            filterStatus: document.getElementById('filterStatus'),
            validateBtn: document.getElementById('validateBtn'),
            autoFixBtn: document.getElementById('autoFixBtn'),
            exportBtn: document.getElementById('exportBtn'),
            importBtn: document.getElementById('importBtn'),
            totalRows: document.getElementById('totalRows'),
            validRows: document.getElementById('validRows'),
            warningRows: document.getElementById('warningRows'),
            errorRows: document.getElementById('errorRows'),
            selectedCount: document.getElementById('selectedCount'),
            globalProgress: document.getElementById('globalProgress'),
            globalProgressBar: document.getElementById('globalProgressBar'),
            summaryModal: new bootstrap.Modal(document.getElementById('summaryModal'))
        };
    }

    initEvents() {
        // File Selection
        this.elements.selectFileBtn.addEventListener('click', () => this.elements.fileInput.click());
        this.elements.fileInput.addEventListener('change', (e) => this.handleFileSelect(e));
        
        // Drag & Drop
        this.elements.dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            this.elements.dropZone.classList.add('dragover');
        });
        
        this.elements.dropZone.addEventListener('dragleave', () => {
            this.elements.dropZone.classList.remove('dragover');
        });
        
        this.elements.dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            this.elements.dropZone.classList.remove('dragover');
            if (e.dataTransfer.files.length) {
                this.handleFileSelect({ target: { files: e.dataTransfer.files } });
            }
        });
        
        // File Actions
        this.elements.removeFileBtn.addEventListener('click', () => this.resetFile());
        this.elements.uploadBtn.addEventListener('click', () => this.uploadAndPreview());
        this.elements.resetBtn.addEventListener('click', () => this.resetAll());
        
        // Search & Filter
        this.elements.searchInput.addEventListener('input', () => this.filterTable());
        this.elements.clearSearch.addEventListener('click', () => {
            this.elements.searchInput.value = '';
            this.filterTable();
        });
        this.elements.filterStatus.addEventListener('change', () => this.filterTable());
        
        // Action Buttons
        this.elements.validateBtn.addEventListener('click', () => this.validateData());
        this.elements.autoFixBtn.addEventListener('click', () => this.autoFix());
        this.elements.exportBtn.addEventListener('click', () => this.exportCSV());
        this.elements.importBtn.addEventListener('click', () => this.confirmImport());
    }

    initDataTable() {
        this.dataTable = this.elements.previewTable.DataTable({
            paging: true,
            pageLength: 10,
            lengthChange: false,
            searching: false,
            ordering: true,
            info: true,
            autoWidth: false,
            responsive: true,
            language: {
                emptyTable: "Tidak ada data untuk ditampilkan",
                info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ baris",
                infoEmpty: "Menampilkan 0 hingga 0 dari 0 baris",
                infoFiltered: "(disaring dari _MAX_ total baris)",
                zeroRecords: "Tidak ditemukan data yang cocok",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Berikutnya",
                    previous: "Sebelumnya"
                }
            },
            columnDefs: [
                { targets: 0, orderable: false },
                { targets: -1, orderable: false }
            ]
        });
    }

    handleFileSelect(event) {
        const file = event.target.files[0];
        if (!file) return;

        const validTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                           'application/vnd.ms-excel', 'text/csv'];
        const validExts = ['.xlsx', '.xls', '.csv'];
        
        const fileExt = '.' + file.name.split('.').pop().toLowerCase();
        
        if (!validTypes.includes(file.type) && !validExts.includes(fileExt)) {
            this.showAlert('error', 'Format tidak didukung', 'Gunakan file .xlsx, .xls, atau .csv');
            return;
        }

        if (file.size > 10 * 1024 * 1024) {
            this.showAlert('error', 'File terlalu besar', 'Maksimal ukuran file adalah 10MB');
            return;
        }

        this.state.file = file;
        this.showFileInfo(file);
        this.elements.uploadActions.style.display = 'block';
    }

    showFileInfo(file) {
        this.elements.fileName.textContent = file.name;
        this.elements.fileSize.textContent = this.formatFileSize(file.size);
        this.elements.fileInfo.classList.add('show');
    }

    formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    resetFile() {
        this.state.file = null;
        this.elements.fileInput.value = '';
        this.elements.fileInfo.classList.remove('show');
        this.elements.uploadActions.style.display = 'none';
    }

    resetAll() {
        this.resetFile();
        this.hidePreview();
        this.state.previewData = [];
        this.state.selectedRows.clear();
        this.updateStats();
        this.dataTable.clear().draw();
    }

    hidePreview() {
        this.elements.previewCard.classList.remove('show');
    }

    async uploadAndPreview() {
        if (!this.state.file) {
            this.showAlert('warning', 'Pilih file terlebih dahulu');
            return;
        }

        this.showProgress('Mengunggah file...', 0);
        
        const formData = new FormData();
        formData.append('file', this.state.file);

        try {
            const response = await this.ajaxRequest(
                '<?= site_url("siswa/import/preview") ?>',
                'POST',
                formData,
                null,
                (progress) => {
                    this.updateProgress(progress);
                }
            );

            const result = JSON.parse(response);
            
            if (result.error) {
                throw new Error(result.error);
            }

            this.showProgress('Memproses data...', 100);
            
            setTimeout(() => {
                this.hideProgress();
                this.renderPreview(result.preview, result.meta);
                this.showAlert('success', 'Preview berhasil', 
                    `Total ${result.total} baris data ditemukan`);
            }, 500);

        } catch (error) {
            this.hideProgress();
            this.showAlert('error', 'Gagal memproses file', error.message);
        }
    }

    renderPreview(data, meta) {
        this.state.previewData = data;
        this.elements.previewCard.classList.add('show');
        
        // Clear table
        this.dataTable.clear();
        
        // Add rows
        data.forEach((row, index) => {
            const statusClass = `status-${row.status}`;
            const statusText = row.status === 'valid' ? 'Valid' : 
                             row.status === 'warning' ? 'Peringatan' : 'Error';
            
            const tr = [
                `<input type="checkbox" class="row-checkbox" data-index="${index}">`,
                `<div class="editable-cell" data-index="${index}" data-field="nisn">
                    <div class="editable-content" contenteditable="true">${this.escapeHtml(row.nisn)}</div>
                </div>`,
                `<div class="editable-cell" data-index="${index}" data-field="nama">
                    <div class="editable-content" contenteditable="true">${this.escapeHtml(row.nama)}</div>
                </div>`,
                `<div class="editable-cell" data-index="${index}" data-field="jk">
                    <select class="form-select form-select-sm" style="min-width: 70px;">
                        <option value="">Pilih</option>
                        <option value="L" ${row.jk === 'L' ? 'selected' : ''}>L</option>
                        <option value="P" ${row.jk === 'P' ? 'selected' : ''}>P</option>
                    </select>
                </div>`,
                `<div class="editable-cell" data-index="${index}" data-field="kelas">
                    <div class="editable-content" contenteditable="true">${this.escapeHtml(row.kelas)}</div>
                </div>`,
                `<span class="text-muted">${this.escapeHtml(row.kelas_match || '-')}</span>`,
                `<div class="editable-cell" data-index="${index}" data-field="jurusan">
                    <div class="editable-content" contenteditable="true">${this.escapeHtml(row.jurusan)}</div>
                </div>`,
                `<span class="text-muted">${this.escapeHtml(row.jurusan_match || '-')}</span>`,
                `<div class="editable-cell" data-index="${index}" data-field="telepon">
                    <div class="editable-content" contenteditable="true">${this.escapeHtml(row.telepon)}</div>
                </div>`,
                `<div class="editable-cell" data-index="${index}" data-field="email">
                    <div class="editable-content" contenteditable="true">${this.escapeHtml(row.email)}</div>
                </div>`,
                `<div class="editable-cell" data-index="${index}" data-field="alamat">
                    <div class="editable-content" contenteditable="true">${this.escapeHtml(row.alamat)}</div>
                </div>`,
                `<span class="status-badge ${statusClass}">${statusText}</span>`,
                `<button class="btn btn-sm btn-outline-danger remove-row" data-index="${index}">
                    <i class="fas fa-trash"></i>
                </button>`
            ];
            
            this.dataTable.row.add(tr);
        });
        
        this.dataTable.draw();
        this.updateStats(meta);
        this.bindRowEvents();
    }

    bindRowEvents() {
        // Editable content
        $(document).off('blur', '.editable-content').on('blur', '.editable-content', (e) => {
            const cell = $(e.target).closest('.editable-cell');
            const index = cell.data('index');
            const field = cell.data('field');
            const value = e.target.textContent.trim();
            
            if (index !== undefined && field) {
                this.state.previewData[index][field] = value;
                this.validateRow(index);
            }
        });
        
        // JK select
        $(document).off('change', 'select').on('change', 'select', (e) => {
            const row = $(e.target).closest('tr');
            const index = row.find('.row-checkbox').data('index');
            this.state.previewData[index].jk = e.target.value;
            this.validateRow(index);
        });
        
        // Row checkbox
        $(document).off('change', '.row-checkbox').on('change', '.row-checkbox', (e) => {
            const index = $(e.target).data('index');
            if (e.target.checked) {
                this.state.selectedRows.add(index);
            } else {
                this.state.selectedRows.delete(index);
            }
            this.updateSelectedCount();
        });
        
        // Remove row
        $(document).off('click', '.remove-row').on('click', '.remove-row', (e) => {
            const index = $(e.target).closest('.remove-row').data('index');
            this.removeRow(index);
        });
    }

    validateRow(index) {
        const row = this.state.previewData[index];
        const messages = [];
        
        if (!row.nisn) messages.push('NISN kosong');
        if (!row.nama) messages.push('Nama kosong');
        if (!row.jk) messages.push('JK tidak valid');
        if (!row.kelas) messages.push('Kelas kosong');
        if (!row.jurusan) messages.push('Jurusan kosong');
        
        row.messages = messages;
        row.status = messages.length === 0 ? 'valid' : 'warning';
        
        // Update table display
        const statusCell = this.dataTable.cell(index, 11).node();
        $(statusCell).html(`
            <span class="status-badge status-${row.status}">
                ${row.status === 'valid' ? 'Valid' : 'Peringatan'}
            </span>
        `);
        
        // Update row class
        const rowNode = this.dataTable.row(index).node();
        $(rowNode).removeClass('highlight-error highlight-auto')
                 .addClass(messages.length ? 'highlight-error' : '');
        
        this.updateStats();
    }

    updateStats(meta) {
        if (!this.state.previewData.length) {
            this.state.stats = { total: 0, valid: 0, warning: 0, error: 0 };
        } else {
            const stats = { total: 0, valid: 0, warning: 0, error: 0 };
            this.state.previewData.forEach(row => {
                stats.total++;
                if (row.status === 'valid') stats.valid++;
                else if (row.status === 'warning') stats.warning++;
                else stats.error++;
            });
            this.state.stats = stats;
        }
        
        // Update display
        this.elements.totalRows.textContent = this.state.stats.total;
        this.elements.validRows.textContent = this.state.stats.valid;
        this.elements.warningRows.textContent = this.state.stats.warning;
        this.elements.errorRows.textContent = this.state.stats.error;
    }

    updateSelectedCount() {
        this.elements.selectedCount.textContent = this.state.selectedRows.size;
    }

    filterTable() {
        const searchTerm = this.elements.searchInput.value.toLowerCase();
        const statusFilter = this.elements.filterStatus.value;
        
        this.dataTable.rows().every(function() {
            const row = this.data();
            const rowData = row.join(' ').toLowerCase();
            const statusMatch = !statusFilter || 
                row[11].toLowerCase().includes(statusFilter);
            
            const searchMatch = !searchTerm || rowData.includes(searchTerm);
            
            this.node().style.display = (statusMatch && searchMatch) ? '' : 'none';
        });
    }

    validateData() {
        this.state.previewData.forEach((row, index) => {
            this.validateRow(index);
        });
        this.showAlert('success', 'Validasi selesai', 
            `Ditemukan ${this.state.stats.warning} peringatan dan ${this.state.stats.error} error`);
    }

    autoFix() {
        let fixed = 0;
        this.state.previewData.forEach((row, index) => {
            if (!row.jk && row.jkRaw) {
                const jkNorm = row.jkRaw.toUpperCase();
                if (jkNorm.includes('L')) row.jk = 'L';
                else if (jkNorm.includes('P')) row.jk = 'P';
                fixed++;
            }
            
            // Trigger revalidation
            this.validateRow(index);
        });
        
        this.showAlert('info', 'Perbaikan otomatis', 
            `${fixed} baris diperbaiki`);
    }

    exportCSV() {
        if (!this.state.previewData.length) {
            this.showAlert('warning', 'Tidak ada data untuk diekspor');
            return;
        }
        
        const headers = ['NISN', 'Nama', 'JK', 'Kelas', 'Jurusan', 'Telepon', 'Email', 'Alamat', 'Status'];
        const csvData = [
            headers.join(','),
            ...this.state.previewData.map(row => [
                row.nisn,
                `"${row.nama.replace(/"/g, '""')}"`,
                row.jk,
                `"${row.kelas.replace(/"/g, '""')}"`,
                `"${row.jurusan.replace(/"/g, '""')}"`,
                row.telepon,
                row.email,
                `"${row.alamat.replace(/"/g, '""')}"`,
                row.status
            ].join(','))
        ].join('\n');
        
        const blob = new Blob([csvData], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', `preview_siswa_${new Date().getTime()}.csv`);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    async confirmImport() {
        if (!this.state.previewData.length) {
            this.showAlert('warning', 'Tidak ada data untuk diimport');
            return;
        }
        
        // Validate before import
        const invalidRows = this.state.previewData.filter(row => row.status !== 'valid');
        if (invalidRows.length) {
            const result = await Swal.fire({
                title: 'Ada data yang bermasalah',
                html: `Terdapat ${invalidRows.length} baris dengan status peringatan/error.<br>
                      Tetap lanjutkan import?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, import semua',
                cancelButtonText: 'Perbaiki dulu',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            });
            
            if (!result.isConfirmed) return;
        }
        
        const payload = {
            rows: this.state.previewData.map(row => ({
                row: row.row,
                nisn: row.nisn || '',
                nama: row.nama || '',
                jk: row.jk || '',
                kelas: row.kelas || '',
                jurusan: row.jurusan || '',
                telepon: row.telepon || '',
                email: row.email || '',
                alamat: row.alamat || ''
            }))
        };
        
        this.performImport(payload);
    }

    async performImport(payload) {
        this.showProgress('Mengimport data...', 0);
        
        try {
            const response = await this.ajaxRequest(
                '<?= site_url("siswa/import/finalize") ?>',
                'POST',
                JSON.stringify(payload),
                { 'Content-Type': 'application/json' }
            );
            
            const result = JSON.parse(response);
            
            if (result.error) {
                throw new Error(result.error);
            }
            
            this.showProgress('Import berhasil!', 100);
            
            setTimeout(() => {
                this.hideProgress();
                this.showImportSummary(result);
            }, 1000);
            
        } catch (error) {
            this.hideProgress();
            this.showAlert('error', 'Import gagal', error.message);
        }
    }

    showImportSummary(result) {
        const successCount = result.inserted || 0;
        const errorCount = result.skipped || 0;
        
        document.getElementById('importSuccessCount').textContent = successCount;
        
        let summaryHTML = '<div class="list-group">';
        
        if (result.errors && result.errors.length) {
            result.errors.forEach(error => {
                summaryHTML += `
                    <div class="list-group-item list-group-item-danger">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Baris ${error.row}:</strong> ${error.nisn}
                            </div>
                            <div>
                                <small class="text-muted">${error.messages.join(', ')}</small>
                            </div>
                        </div>
                    </div>
                `;
            });
        } else {
            summaryHTML += `
                <div class="list-group-item list-group-item-success">
                    <i class="fas fa-check-circle me-2"></i>
                    Semua data berhasil diimport
                </div>
            `;
        }
        
        summaryHTML += '</div>';
        document.getElementById('importSummary').innerHTML = summaryHTML;
        
        this.elements.summaryModal.show();
    }

    removeRow(index) {
        Swal.fire({
            title: 'Hapus baris ini?',
            text: "Data akan dihapus dari preview",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.state.previewData.splice(index, 1);
                this.dataTable.row(index).remove().draw();
                this.updateStats();
                this.showAlert('success', 'Berhasil', 'Baris telah dihapus');
            }
        });
    }

    // Utility Methods
    async ajaxRequest(url, method, data, headers, onProgress) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open(method, url, true);
            
            // Set headers
            if (headers) {
                Object.keys(headers).forEach(key => {
                    xhr.setRequestHeader(key, headers[key]);
                });
            }
            
            // Progress tracking
            if (onProgress && method === 'POST') {
                xhr.upload.onprogress = (e) => {
                    if (e.lengthComputable) {
                        const percentComplete = (e.loaded / e.total) * 100;
                        onProgress(percentComplete);
                    }
                };
            }
            
            xhr.onload = () => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    resolve(xhr.responseText);
                } else {
                    reject(new Error(`HTTP ${xhr.status}: ${xhr.statusText}`));
                }
            };
            
            xhr.onerror = () => reject(new Error('Network error'));
            
            xhr.send(data);
        });
    }

    showProgress(message, percent) {
        this.elements.glProgressText = message;
        this.elements.globalProgressBar.style.width = percent + '%';
        this.elements.globalProgress.classList.add('show');
    }

    updateProgress(percent) {
        this.elements.globalProgressBar.style.width = percent + '%';
    }

    hideProgress() {
        setTimeout(() => {
            this.elements.globalProgress.classList.remove('show');
            this.elements.globalProgressBar.style.width = '0%';
        }, 500);
    }

    showAlert(icon, title, text = '') {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.siswaImport = new SiswaImport();
});
</script>

<?= $this->endSection() ?>