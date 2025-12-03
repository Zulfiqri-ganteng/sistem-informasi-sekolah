<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

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

    /* Enhanced Card Styles */
    .kelas-card {
        background: white;
        border-radius: 16px;
        border: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        position: relative;
    }

    .kelas-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }

    .kelas-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
    }

    .kelas-header {
        padding: 20px 20px 15px;
        border-bottom: 1px solid #f1f3f4;
    }

    .kelas-body {
        padding: 20px;
    }

    .kelas-footer {
        padding: 15px 20px;
        background: #f8f9fa;
        border-top: 1px solid #f1f3f4;
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

    /* Slide Panel Enhancement */
    .slide-panel-enhanced {
        position: fixed;
        right: 0;
        top: 0;
        width: 480px;
        height: 100vh;
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(20px);
        box-shadow: -12px 0 40px rgba(0, 0, 0, 0.15);
        transform: translateX(100%);
        transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 1050;
        display: flex;
        flex-direction: column;
    }

    .slide-panel-enhanced.open {
        transform: translateX(0);
    }

    .slide-panel-enhanced .panel-header {
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        color: white;
        padding: 20px;
    }

    .slide-panel-enhanced .panel-body {
        overflow-y: auto;
        flex: 1;
        padding: 20px;
    }

    /* Student Item */
    .student-item {
        display: flex;
        align-items: center;
        padding: 15px;
        margin-bottom: 10px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
        border: 1px solid #f1f3f4;
    }

    .student-item:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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

    /* Responsive Design */
    @media (max-width: 768px) {
        .dashboard-container {
            padding: 10px;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .slide-panel-enhanced {
            width: 100%;
        }

        .kelas-card {
            margin-bottom: 15px;
        }
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

    /* Progress Bar */
    .progress-thin {
        height: 6px;
        border-radius: 3px;
        background: #e9ecef;
        overflow: hidden;
    }

    .progress-thin .progress-bar {
        border-radius: 3px;
    }
</style>

<div class="dashboard-container fade-in">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap glass-card p-4">
        <div>
            <h4 class="fw-bold mb-2 text-primary">
                <i class="fas fa-school me-2"></i>Manajemen Data Kelas
            </h4>
            <p class="text-muted mb-0">Kelola informasi kelas dan distribusi siswa dengan mudah</p>
        </div>
        <button class="btn btn-primary btn-lg shadow-sm px-4" id="btnAdd">
            <i class="fas fa-plus-circle me-2"></i>Tambah Kelas
        </button>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number" id="totalKelas">0</div>
            <div class="stat-label">Total Kelas</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
            <div class="stat-number" id="totalSiswa">0</div>
            <div class="stat-label">Total Siswa</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #fc466b 0%, #3f5efb 100%);">
            <div class="stat-number" id="totalWali">0</div>
            <div class="stat-label">Guru Wali</div>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #fdbb2d 0%, #22c1c3 100%);">
            <div class="stat-number" id="avgSiswa">0</div>
            <div class="stat-label">Rata-rata Siswa/Kelas</div>
        </div>
    </div>

    <!-- Grid Kelas -->
    <div id="kelasGrid" class="row g-4 mb-4"></div>

    <!-- Detail Area -->
    <div id="kelasDetailArea" class="glass-card d-none">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h4 id="detailTitle" class="mb-1 fw-bold text-primary"></h4>
                    <p id="detailSubtitle" class="text-muted mb-0"></p>
                </div>
                <button class="btn btn-outline-secondary btn-sm" onclick="$('#kelasDetailArea').addClass('d-none')">
                    <i class="fas fa-times me-1"></i>Tutup
                </button>
            </div>

            <div class="table-responsive">
                <table class="table table-modern w-100" id="detailTable">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th width="60">Foto</th>
                            <th>NISN</th>
                            <th>Nama Siswa</th>
                            <th width="120">Kelas</th>
                            <th width="150" class="text-end">Saldo Tabungan</th>
                            <th width="80" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="detailBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Modal Form -->
<div class="modal fade modal-glass" id="modalForm" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <form id="formKelas" autocomplete="off">
                <div class="modal-header bg-primary text-white border-0 rounded-top">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-school me-2"></i>
                        <span id="modalTitle">Tambah Data Kelas</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <input type="hidden" id="id" name="id">
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark">Nama Kelas *</label>
                        <input type="text" name="nama_kelas" id="nama_kelas" class="form-control form-control-lg border-0 shadow-sm"
                            placeholder="Contoh: X TKJ 1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark">Guru Wali *</label>
                        <select name="guru_id" id="guru_id" class="form-select form-select-lg border-0 shadow-sm" required>
                            <option value="">-- Pilih Guru --</option>
                        </select>
                        <div class="form-text">Guru yang sudah menjadi wali kelas akan ditandai</div>
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

<!-- Enhanced Slide Panel -->
<div id="slidePanel" class="slide-panel-enhanced">
    <div class="panel-header">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h5 id="panelTitle" class="mb-1 fw-bold"></h5>
                <small id="panelCount" class="opacity-75"></small>
            </div>
            <button id="slidePanelClose" class="btn btn-light btn-sm rounded-circle">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <div class="panel-body">
        <div id="panelStats" class="row g-3 mb-4">
            <div class="col-6">
                <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                    <div class="fw-bold text-primary fs-4" id="panelTotalSaldo">Rp 0</div>
                    <small class="text-muted">Total Saldo</small>
                </div>
            </div>
            <div class="col-6">
                <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                    <div class="fw-bold text-success fs-4" id="panelAvgSaldo">Rp 0</div>
                    <small class="text-muted">Rata-rata</small>
                </div>
            </div>
        </div>

        <div id="panelList"></div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(function() {
        const base = '<?= base_url("kelas") ?>';
        const cache = {
            kelasList: null,
            siswaByKelas: {}
        };

        // Utility functions
        function rupiah(val) {
            const n = Number(val) || 0;
            return 'Rp ' + n.toLocaleString('id-ID');
        }

        function getColorByIndex(index) {
            const colors = [
                'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
                'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)',
                'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)',
                'linear-gradient(135deg, #fa709a 0%, #fee140 100%)',
                'linear-gradient(135deg, #a8edea 0%, #fed6e3 100%)'
            ];
            return colors[index % colors.length];
        }

        // Load kelas data
        async function loadKelas() {
            if (cache.kelasList) {
                renderKelas(cache.kelasList);
                updateStatistics(cache.kelasList);
                return;
            }

            try {
                showLoading();
                const res = await $.getJSON(base + '/list');
                const data = res.data || [];
                cache.kelasList = data;
                renderKelas(data);
                updateStatistics(data);
            } catch (err) {
                console.error('Error loading kelas:', err);
                Swal.fire('Error', 'Gagal memuat data kelas', 'error');
            } finally {
                hideLoading();
            }
        }

        function showLoading() {
            $('#kelasGrid').html(`
                <div class="col-12">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary mb-3" role="status"></div>
                        <p class="text-muted">Memuat data kelas...</p>
                    </div>
                </div>
            `);
        }

        function hideLoading() {
            // Loading will be replaced by actual content
        }

        function updateStatistics(data) {
            const totalKelas = data.length;
            const totalSiswa = data.reduce((sum, k) => sum + (Number(k.jumlah_siswa) || 0), 0);
            const totalWali = [...new Set(data.map(k => k.guru_id).filter(Boolean))].length;
            const avgSiswa = totalKelas ? Math.round(totalSiswa / totalKelas) : 0;

            $('#totalKelas').text(totalKelas);
            $('#totalSiswa').text(totalSiswa);
            $('#totalWali').text(totalWali);
            $('#avgSiswa').text(avgSiswa);
        }

        function renderKelas(list) {
            const $grid = $('#kelasGrid').empty();

            if (!list.length) {
                $grid.html(`
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-school fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada data kelas</h5>
                            <p class="text-muted">Klik tombol "Tambah Kelas" untuk memulai</p>
                        </div>
                    </div>
                `);
                return;
            }

            list.forEach((k, index) => {
                const jumlah = Number(k.jumlah_siswa) || 0;
                const totalSaldo = Number(k.total_saldo) || 0;
                const avgSaldo = jumlah ? Math.round(totalSaldo / jumlah) : 0;
                const progressWidth = Math.min((jumlah / 40) * 100, 100);

                const card = `
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3">
                        <div class="kelas-card h-100">
                            <div class="kelas-header">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="fw-bold text-dark mb-0">${k.nama_kelas}</h5>
                                    <span class="badge bg-primary rounded-pill">${jumlah} Siswa</span>
                                </div>
                                <p class="text-muted small mb-0">
                                    <i class="fas fa-user-tie me-1"></i>${k.guru_nama || 'Belum ada wali'}
                                </p>
                            </div>

                            <div class="kelas-body">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted small">Total Saldo Kelas</span>
                                        <span class="fw-bold text-success">${rupiah(totalSaldo)}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-muted small">Rata-rata per Siswa</span>
                                        <span class="fw-bold text-primary">${rupiah(avgSaldo)}</span>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small text-muted mb-1">
                                        <span>Kapasitas Kelas</span>
                                        <span>${jumlah}/40</span>
                                    </div>
                                    <div class="progress progress-thin">
                                        <div class="progress-bar ${progressWidth >= 90 ? 'bg-danger' : progressWidth >= 70 ? 'bg-warning' : 'bg-success'}" 
                                             style="width: ${progressWidth}%"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="kelas-footer">
                                <div class="d-flex gap-2">
                                    <button class="btn btn-primary btn-sm flex-grow-1 view-btn" data-id="${k.id}">
                                        <i class="fas fa-eye me-1"></i>Lihat
                                    </button>
                                    <button class="btn btn-warning btn-action edit-btn" data-id="${k.id}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-danger btn-action del-btn" data-id="${k.id}" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                $grid.append(card);
            });
        }

        // Load guru dropdown
        function loadGuruDropdown(selectedId = null) {
            $.getJSON(base + '/getGuruDropdown', function(list) {
                let html = '<option value="">-- Pilih Guru --</option>';
                list.forEach(g => {
                    const disabled = g.is_wali && g.id != selectedId ? 'disabled' : '';
                    const selected = g.id == selectedId ? 'selected' : '';
                    const waliInfo = g.is_wali ? ` (Wali ${g.kelas_wali})` : '';
                    html += `<option value="${g.id}" ${disabled} ${selected}>${g.nama}${waliInfo}</option>`;
                });
                $('#guru_id').html(html);
            }).fail(() => {
                $('#guru_id').html('<option value="">-- Gagal memuat data guru --</option>');
            });
        }

        // Event Handlers
        $('#btnAdd').on('click', function() {
            $('#formKelas')[0].reset();
            $('#id').val('');
            $('#modalTitle').text('Tambah Data Kelas');
            loadGuruDropdown();
            new bootstrap.Modal(document.getElementById('modalForm')).show();
        });

        $('#formKelas').on('submit', function(e) {
            e.preventDefault();
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();

            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...');

            const data = $(this).serialize();
            $.post(base + '/save', data, res => {
                if (res.success) {
                    $('#modalForm').modal('hide');
                    cache.kelasList = null;
                    loadKelas();
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Data kelas berhasil disimpan.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Gagal', res.message || 'Gagal menyimpan data', 'error');
                }
            }).fail(() => {
                Swal.fire('Error', 'Tidak dapat terhubung ke server', 'error');
            }).always(() => {
                submitBtn.prop('disabled', false).html(originalText);
            });
        });

        // Delegated events
        $(document).on('click', '.edit-btn', function(e) {
            e.stopPropagation();
            const id = $(this).data('id');

            $.getJSON(base + '/get/' + id, res => {
                if (!res) {
                    Swal.fire('Error', 'Data tidak ditemukan', 'error');
                    return;
                }

                $('#id').val(res.id);
                $('#nama_kelas').val(res.nama_kelas);
                $('#modalTitle').text('Edit Data Kelas');
                loadGuruDropdown(res.guru_id);
                new bootstrap.Modal(document.getElementById('modalForm')).show();
            }).fail(() => {
                Swal.fire('Error', 'Gagal memuat data kelas', 'error');
            });
        });

        $(document).on('click', '.del-btn', function(e) {
            e.stopPropagation();
            const id = $(this).data('id');

            Swal.fire({
                title: 'Yakin menghapus kelas?',
                text: 'Data kelas dan hubungan dengan siswa akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(result => {
                if (result.isConfirmed) {
                    $.getJSON(base + '/delete/' + id, res => {
                        if (res.success) {
                            cache.kelasList = null;
                            loadKelas();
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: 'Data kelas berhasil dihapus.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire('Gagal', res.message || 'Gagal menghapus data', 'error');
                        }
                    }).fail(() => {
                        Swal.fire('Error', 'Gagal menghapus data', 'error');
                    });
                }
            });
        });

        $(document).on('click', '.view-btn', function(e) {
            e.stopPropagation();
            const id = $(this).data('id');
            openSlidePanel(id);
        });

        // Enhanced Slide Panel
        function openSlidePanel(kelasId) {
            const $panel = $('#slidePanel');

            // Show loading state
            $('#panelTitle').text('Memuat...');
            $('#panelCount').text('');
            $('#panelTotalSaldo').text('Rp 0');
            $('#panelAvgSaldo').text('Rp 0');
            $('#panelList').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i></div>');

            $panel.addClass('open');

            // Check cache first
            if (cache.siswaByKelas[kelasId]) {
                renderPanel(kelasId, cache.siswaByKelas[kelasId]);
                return;
            }

            $.getJSON(base + '/siswa/' + kelasId)
                .done(res => {
                    if (res.error) {
                        Swal.fire('Error', res.error, 'error');
                        $panel.removeClass('open');
                        return;
                    }
                    cache.siswaByKelas[kelasId] = res;
                    renderPanel(kelasId, res);
                })
                .fail(() => {
                    Swal.fire('Error', 'Gagal memuat data siswa', 'error');
                    $panel.removeClass('open');
                });
        }

        function renderPanel(kelasId, res) {
            $('#panelTitle').text(res.kelas.nama_kelas);
            $('#panelCount').text((res.jumlah || 0) + ' Siswa');

            const totalSaldo = res.siswa ? res.siswa.reduce((sum, s) => sum + (Number(s.saldo) || 0), 0) : 0;
            const avgSaldo = res.jumlah ? Math.round(totalSaldo / res.jumlah) : 0;

            $('#panelTotalSaldo').text(rupiah(totalSaldo));
            $('#panelAvgSaldo').text(rupiah(avgSaldo));

            if (!res.siswa || !res.siswa.length) {
                $('#panelList').html(`
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-users fa-3x mb-3"></i>
                        <p>Belum ada siswa di kelas ini</p>
                    </div>
                `);
                return;
            }

            let html = '';
            res.siswa.forEach((s, i) => {
                const foto = s.foto ? `<?= base_url('uploads/siswa') ?>/${s.foto}` : `<?= base_url('assets/img/default-avatar.png') ?>`;
                html += `
                    <div class="student-item">
                        <img src="${foto}" class="rounded-circle me-3" width="50" height="50" 
                             style="object-fit: cover;" onerror="this.src='<?= base_url('assets/img/default-avatar.png') ?>'">
                        <div class="flex-grow-1">
                            <div class="fw-semibold text-dark">${s.nama}</div>
                            <div class="small text-muted">${s.nisn || 'NISN tidak tersedia'}</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-success">${rupiah(s.saldo || 0)}</div>
                            <a href="<?= base_url('siswa') ?>/${s.id}" target="_blank" class="btn btn-sm btn-outline-primary mt-1">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                `;
            });

            $('#panelList').html(html);
        }

        $('#slidePanelClose').on('click', function() {
            $('#slidePanel').removeClass('open');
        });

        // Close panel when clicking outside
        $(document).on('click', function(e) {
            const $panel = $('#slidePanel');
            if ($panel.hasClass('open') && !$(e.target).closest('#slidePanel').length &&
                !$(e.target).closest('.view-btn').length) {
                $panel.removeClass('open');
            }
        });

        // Initialize
        loadKelas();

        // Auto-refresh every 5 minutes
        setInterval(() => {
            cache.kelasList = null;
            cache.siswaByKelas = {};
            loadKelas();
        }, 5 * 60 * 1000);
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

    /* Enhanced mobile responsiveness */
    @media (max-width: 576px) {
        .modal-dialog {
            margin: 10px;
        }

        .btn-action {
            width: 40px !important;
            height: 40px !important;
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

        .student-item {
            padding: 12px;
        }

        .student-item img {
            width: 40px !important;
            height: 40px !important;
        }
    }

    /* Smooth transitions */
    .kelas-card,
    .student-item,
    .btn-action {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>

<?= $this->endSection() ?>