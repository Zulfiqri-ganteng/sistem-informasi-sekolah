<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<style>
    /* Modern Dashboard Styles */
    .dashboard-container {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 20px;
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .glass-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }

    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
    }

    /* Enhanced Table Styles */
    .table-modern {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .table-modern thead {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
    }

    .table-modern th {
        border: none;
        padding: 15px 12px;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table-modern td {
        padding: 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f3f4;
    }

    .table-modern tbody tr {
        transition: all 0.3s ease;
    }

    .table-modern tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Enhanced Badges */
    .badge-modern {
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.75rem;
        border: 1px solid transparent;
    }

    /* Action Buttons */
    .btn-action {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin: 2px;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-action:hover {
        transform: scale(1.1);
    }

    /* Search & Filter Section */
    .search-section {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* Enhanced Modal */
    .modal-glass {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
    }

    .modal-glass .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    }

    /* Select2 Customization */
    .select2-container--bootstrap5 .select2-selection {
        border: none !important;
        background: #f8f9fa !important;
        border-radius: 8px !important;
        min-height: 48px !important;
    }

    .select2-container--bootstrap5 .select2-selection--multiple .select2-selection__choice {
        background: linear-gradient(135deg, #4361ee, #3a0ca3) !important;
        color: white !important;
        border: none !important;
        border-radius: 20px !important;
        padding: 4px 12px !important;
    }

    /* Loading Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .fade-in {
        animation: fadeIn 0.6s ease-in-out;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: #4361ee;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #3a0ca3;
    }

    /* Mobile Responsive Design */
    @media (max-width: 768px) {
        .dashboard-container {
            padding: 10px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .table-modern thead {
            display: none;
        }

        .table-modern tbody tr {
            display: block;
            margin-bottom: 15px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
        }

        .table-modern td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 5px;
            border: none;
            border-bottom: 1px solid #f1f3f4;
        }

        .table-modern td:before {
            content: attr(data-label);
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            font-size: 0.75rem;
        }

        .table-modern td:last-child {
            border-bottom: none;
            justify-content: center;
            gap: 10px;
        }

        .btn-action {
            width: 45px !important;
            height: 45px !important;
        }
    }
</style>

<div class="dashboard-container fade-in">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap glass-card p-4">
        <div>
            <h4 class="fw-bold mb-2 text-primary">
                <i class="fas fa-chalkboard-teacher me-2"></i>Manajemen Data Guru
            </h4>
            <p class="text-muted mb-0">Kelola informasi guru dan mata pelajaran dengan mudah</p>
        </div>
        <button class="btn btn-primary btn-lg shadow-sm px-4" id="btnAdd">
            <i class="fas fa-plus-circle me-2"></i>Tambah Guru
        </button>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number" id="totalGuru">0</div>
            <div class="stat-label">Total Guru</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <div class="stat-number" id="totalMapel">0</div>
            <div class="stat-label">Total Mata Pelajaran</div>
        </div>
        <!-- <div class="stat-card" style="background: linear-gradient(135deg, #fc466b 0%, #3f5efb 100%);">
            <div class="stat-number" id="waliKelas">0</div>
            <div class="stat-label">Guru Wali Kelas</div>
        </div> -->
        <div class="stat-card" style="background: linear-gradient(135deg, #fdbb2d 0%, #22c1c3 100%);">
            <div class="stat-number" id="avgMapel">0</div>
            <div class="stat-label">Rata-rata Mapel/Guru</div>
        </div>
    </div>

    <!-- Search & Filter Section -->
    <div class="search-section glass-card">
        <div class="row g-3 align-items-center">
            <div class="col-md-6">
                <div class="input-group input-group-lg">
                    <span class="input-group-text bg-primary text-white border-0">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control border-0 shadow-none" 
                           placeholder="Cari nama, NIP, atau mata pelajaran...">
                </div>
            </div>
            <div class="col-md-4">
                <select id="filterMapel" class="form-select form-select-lg border-0 shadow-none">
                    <option value="">Semua Mata Pelajaran</option>
                </select>
            </div>
            <div class="col-md-2">
                <button id="btnReset" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-refresh me-2"></i>Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Main Table -->
    <div class="glass-card">
        <div class="table-responsive">
            <table id="tableGuru" class="table table-modern w-100">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th width="80">Foto</th>
                        <th width="120">NIP</th>
                        <th>Nama Lengkap</th>
                        <th width="200">Mata Pelajaran</th>
                        <th>Email</th>
                        <th width="120">Telepon</th>
                        <th width="100" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be populated by DataTables -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Enhanced Modal Form -->
<div class="modal fade modal-glass" id="modalForm" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="formGuru" enctype="multipart/form-data" autocomplete="off">
                <div class="modal-header bg-primary text-white border-0 rounded-top">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-user-edit me-2"></i>
                        <span id="modalTitle">Tambah Data Guru</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    <input type="hidden" name="id" id="id">

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">NIP *</label>
                            <input type="text" name="nip" id="nip" class="form-control form-control-lg border-0 shadow-sm" 
                                   placeholder="Masukkan NIP" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Nama Lengkap *</label>
                            <input type="text" name="nama" id="nama" class="form-control form-control-lg border-0 shadow-sm" 
                                   placeholder="Masukkan nama lengkap" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark">Mata Pelajaran</label>
                            <select name="mapel_id[]" id="mapel" class="form-select border-0 shadow-sm" multiple="multiple" style="width: 100%;">
                            </select>
                            <div class="form-text">Ketik untuk mencari dan pilih mata pelajaran (bisa lebih dari satu)</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Email</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg border-0 shadow-sm" 
                                   placeholder="contoh: guru@sekolah.sch.id">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Telepon</label>
                            <input type="text" name="telepon" id="telepon" class="form-control form-control-lg border-0 shadow-sm" 
                                   placeholder="Masukkan nomor telepon">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control border-0 shadow-sm" 
                                      rows="3" placeholder="Masukkan alamat lengkap"></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark">Foto Profil</label>
                            <div class="text-center">
                                <div class="position-relative d-inline-block">
                                    <img id="previewFoto" src="<?= base_url('uploads/guru/default.png') ?>" 
                                         alt="Preview Foto" class="rounded-circle shadow-lg" 
                                         width="120" height="120" style="object-fit: cover;"
                                         onerror="this.src='<?= base_url('uploads/guru/default.png') ?>'">
                                    <label for="fotoInput" class="btn btn-primary btn-sm rounded-circle position-absolute" 
                                           style="bottom: 5px; right: 5px; width: 35px; height: 35px; cursor: pointer;">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                    <input type="file" name="foto" id="fotoInput" class="d-none" 
                                           accept="image/*" onchange="previewImage(event)">
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">Format: JPG, PNG, GIF (Maks. 2MB)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 bg-light rounded-bottom">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>

<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(function() {
        const base = '<?= base_url('admin/guru') ?>';
        let lastAddedId = null;

        // Enhanced image preview
        window.previewImage = e => {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire('Error', 'Ukuran file maksimal 2MB', 'error');
                    e.target.value = '';
                    return;
                }
                $('#previewFoto').attr('src', URL.createObjectURL(file));
            }
        };

        // Initialize DataTable with enhanced features
        const table = $('#tableGuru').DataTable({
            ajax: {
                url: base + '/list',
                dataSrc: 'data',
                error: function(xhr, error, thrown) {
                    console.error('Error loading data:', error);
                    Swal.fire('Error', 'Gagal memuat data guru', 'error');
                }
            },
            responsive: true,
            order: [[3, 'asc']],
            paging: true,
            pageLength: 10,
            lengthChange: true,
            searching: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ guru",
                paginate: {
                    previous: "<i class='fas fa-chevron-left'></i>",
                    next: "<i class='fas fa-chevron-right'></i>"
                }
            },
            createdRow: function(row, data) {
                if (lastAddedId && data.id == lastAddedId) {
                    $(row).addClass('highlight-new');
                    setTimeout(() => $(row).removeClass('highlight-new'), 3000);
                }
                
                // Add data labels for mobile
                const headers = ['No', 'Foto', 'NIP', 'Nama Lengkap', 'Mata Pelajaran', 'Email', 'Telepon', 'Aksi'];
                $(row).find('td').each(function(i) {
                    if (headers[i]) {
                        $(this).attr('data-label', headers[i]);
                    }
                });
            },
            columns: [{
                    data: null,
                    render: (d, t, r, m) => m.row + 1,
                    className: 'text-center'
                },
                {
                    data: 'foto',
                    render: d => {
                        const defaultFoto = '<?= base_url('uploads/guru/default.png') ?>';
                        const fotoUrl = d ? `<?= base_url('uploads/guru') ?>/${d}` : defaultFoto;
                        return `<img src="${fotoUrl}" 
                              class="rounded-circle shadow-sm border border-3 border-light" 
                              width="50" height="50" style="object-fit: cover;"
                              onerror="this.src='${defaultFoto}'">`;
                    }
                },
                {
                    data: 'nip',
                    className: 'fw-bold text-primary'
                },
                {
                    data: 'nama',
                    render: d => `<span class="fw-semibold">${d}</span>`
                },
                {
                    data: 'mapel',
                    render: d => {
                        if (!d) return '-';
                        const mapelList = Array.isArray(d) ? d : [d];
                        return mapelList.map(m => 
                            `<span class="badge badge-modern bg-primary-subtle text-primary me-1 mb-1">${m}</span>`
                        ).join('');
                    }
                },
                {
                    data: 'email',
                    render: d => d ? `<a href="mailto:${d}" class="text-decoration-none">${d}</a>` : '-'
                },
                {
                    data: 'telepon',
                    render: d => d ? `<a href="tel:${d}" class="text-decoration-none">${d}</a>` : '-'
                },
                {
                    data: null,
                    render: d => 
                        `<div class="text-center">
                            <button class="btn btn-warning btn-action edit" data-id="${d.id}" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger btn-action del" data-id="${d.id}" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>`,
                    className: 'text-center'
                }
            ],
            initComplete: function() {
                // Update statistics
                try {
                    const data = this.api().data().toArray();
                    $('#totalGuru').text(data.length);
                    
                    // Count unique mapel and wali kelas
                    const allMapel = data.flatMap(g => {
                        const mapel = g.mapel;
                        return Array.isArray(mapel) ? mapel : (mapel ? [mapel] : []);
                    });
                    const uniqueMapel = [...new Set(allMapel.filter(Boolean))];
                    
                    $('#totalMapel').text(uniqueMapel.length);
                    $('#waliKelas').text(data.filter(g => g.is_wali).length);
                    $('#avgMapel').text(data.length ? (allMapel.length / data.length).toFixed(1) : '0');
                    
                    // Populate mapel filter
                    let mapelOptions = '<option value="">Semua Mata Pelajaran</option>';
                    uniqueMapel.forEach(m => {
                        mapelOptions += `<option value="${m}">${m}</option>`;
                    });
                    $('#filterMapel').html(mapelOptions);
                } catch (error) {
                    console.error('Error updating statistics:', error);
                    // Set default values if error
                    $('#totalGuru').text('0');
                    $('#totalMapel').text('0');
                    $('#waliKelas').text('0');
                    $('#avgMapel').text('0');
                }
            }
        });

        // Enhanced search functionality
        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });

        // Mapel filter
        $('#filterMapel').on('change', function() {
            const mapel = $(this).val();
            table.column(4).search(mapel).draw();
        });

        // Reset filters
        $('#btnReset').click(function() {
            $('#searchInput').val('');
            $('#filterMapel').val('');
            table.search('').columns().search('').draw();
        });

        // Initialize Select2
        function initSelect2() {
            $('#mapel').select2({
                dropdownParent: $('#modalForm'),
                theme: 'bootstrap4',
                placeholder: "Pilih mata pelajaran...",
                allowClear: true,
                width: '100%',
                language: {
                    noResults: function() {
                        return "Tidak ditemukan mata pelajaran";
                    }
                }
            });
        }

        // Load mapel options
        function loadMapel(selected = []) {
            $.get(base + '/getMapel', function(data) {
                $('#mapel').empty();
                
                if (data && Array.isArray(data)) {
                    data.forEach(m => {
                        const isSelected = selected.includes(m.id.toString());
                        const option = new Option(m.nama_mapel, m.id, false, isSelected);
                        $('#mapel').append(option);
                    });
                }
                
                $('#mapel').trigger('change');
            }).fail(() => {
                console.error('Failed to load mapel data');
                $('#mapel').html('<option value="">Gagal memuat data mata pelajaran</option>');
            });
        }

        // Add new teacher
        $('#btnAdd').click(() => {
            $('#formGuru')[0].reset();
            $('#id').val('');
            $('#modalTitle').text('Tambah Data Guru');
            $('#previewFoto').attr('src', '<?= base_url('uploads/guru/default.png') ?>');
            initSelect2();
            loadMapel([]);
            $('#modalForm').modal('show');
        });

        // Form submission with enhanced error handling
        $('#formGuru').submit(function(e) {
            e.preventDefault();
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...');
            
            const fd = new FormData(this);
            $.ajax({
                url: base + '/save',
                type: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                success: res => {
                    if (res && res.success) {
                        lastAddedId = res.id || null;
                        $('#modalForm').modal('hide');
                        table.ajax.reload(null, false);
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data guru berhasil disimpan.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        const errorMsg = res && res.message ? res.message : 'Terjadi kesalahan saat menyimpan data';
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

        // Edit teacher with enhanced error handling
        $('#tableGuru').on('click', '.edit', function() {
            const id = $(this).data('id');
            $.get(base + '/get/' + id, res => {
                if (res) {
                    $('#id').val(res.id || '');
                    $('#nip').val(res.nip || '');
                    $('#nama').val(res.nama || '');
                    $('#email').val(res.email || '');
                    $('#telepon').val(res.telepon || '');
                    $('#alamat').val(res.alamat || '');
                    $('#modalTitle').text('Edit Data Guru');
                    
                    const defaultFoto = '<?= base_url('uploads/guru/default.png') ?>';
                    const fotoUrl = res.foto ? `<?= base_url('uploads/guru') ?>/${res.foto}` : defaultFoto;
                    $('#previewFoto').attr('src', fotoUrl);
                    
                    initSelect2();
                    loadMapel(res.mapel_ids || []);
                    $('#modalForm').modal('show');
                } else {
                    Swal.fire('Error', 'Data guru tidak ditemukan', 'error');
                }
            }).fail(() => {
                Swal.fire('Error', 'Gagal memuat data guru', 'error');
            });
        });

        // Delete teacher with confirmation
        $('#tableGuru').on('click', '.del', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Yakin menghapus?',
                text: 'Data guru akan dihapus permanen dan tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(r => {
                if (r.isConfirmed) {
                    $.get(base + '/delete/' + id, (res) => {
                        if (res && res.success) {
                            table.ajax.reload(null, false);
                            Swal.fire('Terhapus!', 'Data guru berhasil dihapus.', 'success');
                        } else {
                            const errorMsg = res && res.message ? res.message : 'Terjadi kesalahan saat menghapus data';
                            Swal.fire('Gagal!', errorMsg, 'error');
                        }
                    }).fail(() => {
                        Swal.fire('Error!', 'Terjadi kesalahan pada server.', 'error');
                    });
                }
            });
        });

        // Initialize on page load
        initSelect2();
    });
</script>

<style>
    .highlight-new {
        background: linear-gradient(135deg, #d4edda, #c3e6cb) !important;
        animation: pulseHighlight 2s ease-in-out;
    }

    @keyframes pulseHighlight {
        0% { background-color: #d4edda; }
        50% { background-color: #a1e1af; }
        100% { background-color: #d4edda; }
    }

    /* Enhanced mobile responsiveness */
    @media (max-width: 576px) {
        .modal-dialog {
            margin: 10px;
        }
        
        .btn-action {
            width: 45px !important;
            height: 45px !important;
        }
        
        .stats-grid {
            gap: 10px;
        }
        
        .stat-card {
            padding: 15px;
        }
        
        .stat-number {
            font-size: 1.5rem;
        }

        /* Better badge display on mobile */
        .badge-modern {
            display: inline-block;
            margin-bottom: 2px;
            font-size: 0.7rem;
        }
    }

    /* Smooth transitions */
    .glass-card,
    .btn-action {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>

<?= $this->endSection(); ?>