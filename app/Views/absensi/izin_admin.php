<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<!-- DataTables, Select2, dan Font Awesome (CDN) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.3.0/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
<!-- Memastikan Bootstrap JS loaded untuk Modal dan Tooltip -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
    /* Styling Dasar dan Konsisten */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    /* Badge yang Lebih Jelas */
    .status-badge {
        padding: 0.4rem 1rem;
        border-radius: 9999px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    /* Tampilan Pengaju */
    .user-info {
        display: flex;
        align-items: center;
        /* Tambahkan text-nowrap untuk mencegah baris nama pecah */
        white-space: nowrap;
    }

    .user-info .user-name-text {
        font-weight: 600;
        /* Untuk nama */
    }

    .user-info img {
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        border: 2px solid #f8f9fa;
        /* Border tipis agar foto menonjol dari background */
        object-fit: cover;
    }

    .user-identifier {
        font-size: 0.8rem;
        color: #6c757d;
        display: block;
        font-weight: 400;
        /* Lebih ringan dari nama */
    }

    /* Card untuk Filter yang Lebih Menonjol */
    .filter-card {
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    /* Button Aksi */
    .btn-action-group {
        display: flex;
        gap: 5px;
        flex-wrap: nowrap;
    }

    /* Font style for better readability */
    body {
        font-family: 'Inter', sans-serif;
    }

    /* Style untuk baris Pending yang perlu diperhatikan */
    tr[data-status="pending"] {
        background-color: #fffbe6;
        /* Warna kuning muda */
        border-left: 5px solid #ffc107;
        /* Garis batas kuning */
    }

    /* Override Datatables header/footer */
    #izinTable_wrapper .row:first-child,
    #izinTable_wrapper .row:last-child {
        padding: 0 1rem;
    }
</style>

<div class="page-header">
    <h1 class="page-title fw-bold text-primary">
        <i class="fas fa-clipboard-list me-2"></i> Kelola Pengajuan Izin Absensi
    </h1>
</div>

<!-- Filter dan Reset -->
<div class="filter-card shadow-sm">
    <div class="row g-3 align-items-end">
        <div class="col-md-4 col-sm-6">
            <label for="filter_jenis" class="form-label fw-bold">Jenis Izin:</label>
            <select id="filter_jenis" class="form-select select2-bootstrap-5" data-placeholder="Semua Jenis" data-theme="bootstrap-5">
                <option></option>
                <option value="izin">Izin</option>
                <option value="sakit">Sakit</option>
                <option value="pulang-awal">Pulang Awal</option>
            </select>
        </div>
        <div class="col-md-4 col-sm-6">
            <label for="filter_status" class="form-label fw-bold">Status Verifikasi:</label>
            <select id="filter_status" class="form-select select2-bootstrap-5" data-placeholder="Semua Status" data-theme="bootstrap-5">
                <option></option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>
        <div class="col-md-4 col-sm-12">
            <button id="btn_reset" class="btn btn-outline-secondary w-100">
                <i class="fas fa-undo me-2"></i> Reset Filter
            </button>
        </div>
    </div>
</div>

<div class="card shadow-lg border-0">
    <div class="card-header bg-white border-bottom d-flex flex-wrap justify-content-between align-items-center py-3">
        <h5 class="card-title mb-2 mb-md-0">Daftar Pengajuan Izin</h5>
        <!-- Custom Search Input -->
        <div class="w-100 w-md-auto">
            <input type="search" class="form-control" id="global_search" placeholder="Cari cepat (Nama, NIP/NIS, Keterangan...)">
        </div>
    </div>
    <div class="card-body">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-times-circle me-2"></i> <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <!-- Menambahkan data-datatable-initialized agar bisa ditarget di JS -->
            <table id="izinTable" class="table table-striped table-hover align-middle" style="width:100%">
                <thead>
                    <tr>
                        <th>Tgl Izin</th>
                        <th>Pengaju (ID)</th>
                        <th>Jenis Izin</th>
                        <th>Keterangan</th>
                        <th>Lampiran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($list as $row): ?>
                        <?php
                        $badge_class = 'bg-secondary';
                        $status_icon = '<i class="fas fa-hourglass-half"></i>';
                        if ($row['status'] == 'approved') {
                            $badge_class = 'bg-success';
                            $status_icon = '<i class="fas fa-check-circle"></i>';
                        } else if ($row['status'] == 'rejected') {
                            $badge_class = 'bg-danger';
                            $status_icon = '<i class="fas fa-times-circle"></i>';
                        } else if ($row['status'] == 'pending') {
                            // Perhatikan: Menghapus text-white agar terlihat di bg-warning
                            $badge_class = 'bg-warning text-dark';
                            $status_icon = '<i class="fas fa-clock"></i>';
                        }

                        // PENTING: Menentukan identifier NISN/NIP dan labelnya
                        $identifier = '';
                        $identifier_label = '';
                        if ($row['user_type'] === 'siswa') {
                            // Cek apakah kolom NISN ada (Jika Siswa)
                            $identifier = $row['nisn'] ?? $row['nis'] ?? '-';
                            $identifier_label = 'NISN';
                        } else {
                            // Cek apakah kolom NIP ada (Jika Guru)
                            $identifier = $row['nip'] ?? '-';
                            $identifier_label = 'NIP';
                        }

                        $jenis_izin_label = ucwords(str_replace('-', ' ', esc($row['jenis'])));

                        // Perbaiki URL lampiran, asumsikan base_url() tersedia
                        $lampiran_url = $row['lampiran'] ? base_url('uploads/izin/' . esc($row['lampiran'])) : null;

                        // Periksa apakah user_name ada, jika tidak, gunakan fallback
                        $user_name = $row['user_name'] ?? ('Pengguna [ID:' . $row['user_id'] . ']');

                        // Tentukan folder foto berdasarkan user_type (siswa / guru). Jika user_foto kosong, pakai default.
                        $fotoFolder = ($row['user_type'] === 'siswa') ? 'siswa' : 'guru';
                        $fotoFile = $row['user_foto'] ?? 'default.png';
                        $foto_url = base_url('uploads/' . $fotoFolder . '/' . $fotoFile);
                        // default image berada di uploads/users/default.png (sesuaikan dengan struktur public/uploads)
                        $default_img = base_url('uploads/users/default.png');

                        // Untuk data-search-term (semua lowercase) - gunakan esc untuk keamanan
                        $search_term = strtolower($user_name . ' ' . $identifier . ' ' . ($row['keterangan'] ?? ''));
                        ?>
                        <!-- Menambahkan data-search-term untuk pencarian global -->
                        <tr data-jenis="<?= esc($row['jenis']) ?>" data-status="<?= esc($row['status']) ?>" data-search-term="<?= esc($search_term) ?>">
                            <td class="text-nowrap fw-bold"><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
                            <td>
                                <div class="user-info">
                                    <img src="<?= esc($foto_url) ?>" onerror="this.onerror=null;this.src='<?= esc($default_img) ?>';" class="rounded-circle me-2" alt="Foto" width="35" height="35">
                                    <div>
                                        <span class="user-name-text"><?= esc($user_name) ?></span>
                                        <!-- Tampilkan Tipe Pengguna dan ID (NISN/NIP) -->
                                        <span class="user-identifier">(<?= esc(ucwords($row['user_type'])) ?> - <?= esc($identifier) ?>)</span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-primary"><?= $jenis_izin_label ?></span></td>
                            <td style="max-width: 250px; white-space: normal;"><?= esc($row['keterangan']) ?></td>
                            <td class="text-center">
                                <?php if (!empty($row['lampiran'])): ?>
                                    <a href="<?= esc($lampiran_url) ?>" target="_blank" class="btn btn-sm btn-info text-white" data-bs-toggle="tooltip" title="Lihat Lampiran">
                                        <i class="fas fa-file-alt"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-badge <?= esc($badge_class) ?>">
                                    <?= $status_icon ?>
                                    <?= esc(ucwords($row['status'])) ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-action-group">
                                    <?php if ($row['status'] == 'pending'): ?>
                                        <button class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Setujui Izin" onclick='openApprove(<?= $row["id"] ?>, <?= json_encode($user_name) ?>)'>
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="tooltip" title="Tolak Izin" onclick='openReject(<?= $row["id"] ?>, <?= json_encode($user_name) ?>)'>
                                            <i class="fas fa-times"></i>
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm" disabled data-bs-toggle="tooltip" title="Sudah diverifikasi">
                                            <i class="fas fa-info-circle"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Approve -->
<div class="modal fade" id="modalApprove" tabindex="-1" aria-labelledby="modalApproveLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalApproveLabel"><i class="fas fa-check-circle me-2"></i> Konfirmasi Persetujuan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Action diarahkan ke controller, pastikan URI sudah benar -->
            <form id="formApprove" method="post">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <p class="mb-3">Apakah Anda yakin ingin **menyetujui** izin untuk **<span id="approveUser" class="fw-bold text-success"></span>**?</p>
                    <div class="alert alert-warning border-0" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i> Tindakan ini akan **mengubah status absensi** pengguna menjadi 'Izin'/'Sakit' atau 'Pulang Awal' pada tanggal pengajuan.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-thumbs-up me-1"></i> Setujui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="modalReject" tabindex="-1" aria-labelledby="modalRejectLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalRejectLabel"><i class="fas fa-times-circle me-2"></i> Konfirmasi Penolakan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- Action diarahkan ke controller, pastikan URI sudah benar -->
            <form id="formReject" method="post">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <p class="mb-3">Apakah Anda yakin ingin **menolak** izin untuk **<span id="rejectUser" class="fw-bold text-danger"></span>**?</p>
                    <div class="alert alert-info border-0" role="alert">
                        <i class="fas fa-info-circle me-2"></i> Pengajuan akan ditolak. Pengguna harus melakukan **scan absensi normal** atau akan tercatat sebagai Alpha jika tidak ada absensi.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-ban me-1"></i> Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Inisialisasi Select2
        $('.select2-bootstrap-5').select2({
            theme: "bootstrap-5",
            width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
            placeholder: $(this).data('placeholder') || 'Pilih...',
            allowClear: true
        });

        // Inisialisasi DataTables
        // Penting: Matikan fitur searching default Datatables
        var table = $('#izinTable').DataTable({
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.6/i18n/id.json"
            },
            "columnDefs": [{
                    "orderable": false,
                    "targets": [4, 6]
                }, // Kolom Lampiran dan Aksi tidak bisa disortir
                {
                    "visible": false,
                    "targets": []
                } // Tidak menyembunyikan kolom (Jenis sudah ditangani oleh custom filter)
            ],
            // Matikan fitur search default Datatables
            "searching": false,
            // Matikan fitur info default Datatables
            "info": true,
            // Perbaiki urutan default, misal berdasarkan kolom Tgl Izin (kolom 0) descending
            "order": [
                [0, 'desc']
            ],
        });

        // ==========================================================
        // CUSTOM FILTERING & SEARCHING LOGIC
        // ==========================================================

        /**
         * Custom filter function that filters rows based on:
         * 1. Jenis Izin (select filter)
         * 2. Status Verifikasi (select filter)
         * 3. Global Search (text input)
         */
        function applyCustomFilter() {
            const jenis = $('#filter_jenis').val();
            const status = $('#filter_status').val();
            const searchTerm = $('#global_search').val().toLowerCase().trim();

            // Gunakan table.rows().every() untuk iterasi yang dioptimalkan oleh Datatables
            table.rows().every(function() {
                const rowNode = this.node();
                const row = $(rowNode);

                // Ambil data attribute
                const rowJenis = row.data('jenis');
                const rowStatus = row.data('status');
                // Ambil data-search-term yang berisi nama, id, dan keterangan (sudah lowercase dari PHP)
                const rowSearchTerm = row.data('searchTerm');

                // Logika Filter Jenis
                const matchJenis = !jenis || rowJenis === jenis;

                // Logika Filter Status
                const matchStatus = !status || rowStatus === status;

                // Logika Global Search (Mencari di dalam data-search-term)
                const matchSearch = !searchTerm || (rowSearchTerm && rowSearchTerm.includes(searchTerm));

                // Gabungkan semua filter
                if (matchJenis && matchStatus && matchSearch) {
                    row.show();
                } else {
                    row.hide();
                }
            });

            // Trik untuk memperbarui Datatables info/pagination setelah custom filtering
            // Kita harus memanggil draw setelah show/hide untuk mengupdate info/pagination Datatables
            // Kita buat fungsi draw custom yang hanya memperbarui info/pagination 
            // tanpa mengaktifkan filter/search Datatables yang sudah kita matikan.
            // Gunakan .draw('page') untuk mengupdate tanpa sorting ulang
            table.draw('page');
        }

        // Event listener untuk Filter Jenis & Status
        $('#filter_jenis, #filter_status').on('change', function() {
            applyCustomFilter();
        });

        // Event listener untuk Global Search
        $('#global_search').on('keyup', function() {
            applyCustomFilter();
        });

        // Reset Filter
        $('#btn_reset').on('click', function() {
            // Reset Select2 dan trigger change
            $('#filter_jenis').val('').trigger('change');
            $('#filter_status').val('').trigger('change');
            // Reset search input
            $('#global_search').val('');
            // Panggil applyCustomFilter untuk mereset tampilan
            applyCustomFilter();
        });

        // Inisialisasi Tooltip setelah DOM siap
        function initTooltips() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
        initTooltips();

        // Aplikasikan filter awal
        applyCustomFilter();
    });

    /**
     * Fungsi untuk membuka modal Persetujuan (Approve)
     * @param {number} id ID Pengajuan Izin
     * @param {string} user Nama Pengaju
     */
    function openApprove(id, user) {
        $('#approveUser').text(user);
        // PENTING: Gunakan base_url() untuk route CodeIgniter 4
        $('#formApprove').attr('action', "<?= base_url('absensi/izin/approve/') ?>" + id);
        var modal = new bootstrap.Modal(document.getElementById('modalApprove'));
        modal.show();
    }

    /**
     * Fungsi untuk membuka modal Penolakan (Reject)
     * @param {number} id ID Pengajuan Izin
     * @param {string} user Nama Pengaju
     */
    function openReject(id, user) {
        $('#rejectUser').text(user);
        // PENTING: Gunakan base_url() untuk route CodeIgniter 4
        $('#formReject').attr('action', "<?= base_url('absensi/izin/reject/') ?>" + id);
        var modal = new bootstrap.Modal(document.getElementById('modalReject'));
        modal.show();
    }
</script>

<?= $this->endSection() ?>