<?= $this->extend('layout/main') ?>

<?= $this->section('head') ?>
<!-- Tambahkan CSS SweetAlert2 & DataTables jika belum ada di layout main -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>

    /* ===========================
   EKSUL UI PREMIUM-PROMAX
   CLEAN – MODERN – INTERACTIVE
=========================== */

    :root {
        --primary: #2d5be3;
        --primary-soft: #e7edff;
        --danger-soft: #ffe7e7;
        --warning-soft: #fff4db;
        --success-soft: #e8f7e1;
        --dark: #2a2d3e;
        --text-muted: #6c757d;
    }

    /* ---------- HEADER CARDS ---------- */
    .info-box-modern {
        border-radius: 16px;
        padding: 16px;
        display: flex;
        align-items: center;
        background: #fff;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.06);
        margin-bottom: 20px;
    }

    .info-box-modern .icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.7rem;
        margin-right: 15px;
    }

    .info-box-modern h3 {
        margin: 0;
        font-size: 1.4rem;
        font-weight: 700;
    }

    /* ---------- SCHEDULE CHIP ---------- */
    .schedule-chip {
        padding: 12px;
        display: flex;
        justify-content: space-between;
        background: var(--primary-soft);
        border-left: 4px solid var(--primary);
        border-radius: 12px;
        margin-bottom: 10px;
        transition: all .2s ease-in-out;
    }

    .schedule-chip:hover {
        transform: translateX(6px);
        background: #dfe6ff;
    }

    .schedule-chip span {
        font-weight: 600;
        color: var(--dark);
    }

    /* Action buttons inside chip */
    .schedule-actions .btn-icon {
        border: none;
        background: none;
        font-size: 1.1rem;
    }

    /* ---------- TABLE DESIGN ---------- */
    .table-modern thead {
        background: var(--primary);
        color: #fff;
    }

    .table-modern thead th {
        padding: 14px;
        border: none;
        font-size: .9rem;
    }

    .table-modern tbody tr:hover {
        background: #f5f7ff;
    }

    .table-modern td {
        padding: 14px !important;
    }

    /* ---------- ACTION BUTTONS ---------- */
    .action-group .btn {
        border-radius: 50%;
        width: 36px;
        height: 36px;
        padding: 0;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.07);
    }

    /* ---------- CARD WRAPPER ---------- */
    .card-modern {
        border: none;
        border-radius: 18px;
        box-shadow: 0 7px 20px rgba(0, 0, 0, 0.06);
    }

    /* ---------- MODAL PREMIUM ---------- */
    .modal-content {
        border-radius: 18px;
        border: none;
    }

    .modal-header {
        border-radius: 18px 18px 0 0;
    }

    .modal-body input,
    .modal-body select,
    .modal-body textarea {
        border-radius: 12px !important;
    }

    /* ---------- MOBILE FIX ---------- */
    @media (max-width: 768px) {

        .info-box-modern {
            padding: 12px;
        }

        .schedule-chip {
            flex-direction: column;
            align-items: flex-start;
        }

        .schedule-actions {
            margin-top: 6px;
            width: 100%;
            text-align: right;
        }

        .action-group {
            flex-direction: row;
        }

        .action-group .btn {
            width: 32px !important;
            height: 32px !important;
        }

        /* Full width & reduced padding */
        .container,
        .container-fluid,
        .row,
        .col,
        main {
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
    }
</style>


<div class="content-wrapper" style="background-color: #f4f6f9;">
    <!-- Header with White Background -->
    <div class="content-header bg-white shadow-sm mb-4" style="border-bottom: 1px solid #dee2e6;">
        <div class="container-fluid">
            <div class="row py-2 align-items-center">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark" style="font-weight: 700; font-size: 1.8rem;">
                        <i class="fas fa-running text-primary mr-2"></i>Manajemen Ekskul
                    </h1>
                    <p class="text-muted mb-0 mt-1" style="font-size: 0.95rem;">Platform kontrol kegiatan ekstrakurikuler sekolah.</p>
                </div>
                <!-- <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('admin') ?>" class="text-primary font-weight-bold">Dashboard</a></li>
                        <li class="breadcrumb-item active">Ekstrakurikuler</li>
                    </ol>
                </div> -->
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- STATS WIDGETS (Penambahan Baru untuk Visual Dashboard) -->
            <?php
            // Hitung Statistik Sederhana
            $totalEkskul = count($ekskulList ?? []);
            $totalPembimbing = count($pembimbingList ?? []);
            $totalJadwal = 0;
            foreach (($ekskulList ?? []) as $e) {
                $totalJadwal += count($e['jadwal'] ?? []);
            }
            ?>
            <div class="row mb-4">
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="info-box-modern">
                        <div class="icon-wrapper bg-primary-soft text-primary">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <div class="content-wrapper">
                            <span class="text-muted font-weight-bold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">Total Ekskul</span>
                            <h3 class="font-weight-bold mb-0 text-dark"><?= $totalEkskul ?> <small class="text-muted" style="font-size: 1rem;">Cabang</small></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 col-12">
                    <div class="info-box-modern">
                        <div class="icon-wrapper bg-success-soft text-success">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="content-wrapper">
                            <span class="text-muted font-weight-bold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">Pembimbing</span>
                            <h3 class="font-weight-bold mb-0 text-dark"><?= $totalPembimbing ?> <small class="text-muted" style="font-size: 1rem;">Guru</small></h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12 col-12">
                    <div class="info-box-modern">
                        <div class="icon-wrapper bg-warning-soft text-warning">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="content-wrapper">
                            <span class="text-muted font-weight-bold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px;">Jadwal Aktif</span>
                            <h3 class="font-weight-bold mb-0 text-dark"><?= $totalJadwal ?> <small class="text-muted" style="font-size: 1rem;">Sesi/Minggu</small></h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Flashdata SweetAlert Handling (Hidden div for JS to pick up) -->
            <div id="flash-success" data-msg="<?= session()->getFlashdata('success') ?>"></div>
            <div id="flash-error" data-msg="<?= session()->getFlashdata('error') ?>"></div>

            <div class="card card-modern">
                <div class="card-header bg-white pt-4 pb-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="card-title font-weight-bold text-dark" style="font-size: 1.25rem;">
                            <i class="fas fa-list-alt text-primary mr-2"></i>Daftar Ekstrakurikuler
                        </h3>
                        <p class="text-muted small mb-0 ml-4 pl-1">Kelola data dan jadwal latihan di sini.</p>
                    </div>
                    <button type="button" class="btn btn-primary shadow-sm btn-tambah-ekskul px-4 rounded-pill">
                        <i class="fas fa-plus mr-1"></i> Tambah Baru
                    </button>
                </div>

                <div class="card-body">
                    <!-- Deteksi Error Validasi untuk Membuka Modal -->
                    <?php
                    $validation_errors = session()->getFlashdata('validation_errors');
                    $has_errors = $validation_errors || (isset($validation) && $validation->getErrors());
                    ?>
                    <div id="validation-check"
                        data-has-error="<?= $has_errors ? 'true' : 'false' ?>"
                        data-modal-target="<?= session()->getFlashdata('show_modal') === 'jadwal' ? '#modalTambahJadwal' : '#modalTambahEkskul' ?>">
                    </div>

                    <div class="table-responsive">
                        <table id="ekskulTable" class="table table-modern table-hover w-100">
                            <thead>
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th>Ekstrakurikuler</th>
                                    <th>Pembimbing</th>
                                    <th>Jadwal Aktif</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($ekskulList ?? [] as $ekskul) : ?>
                                    <tr>
                                        <td class="text-center font-weight-bold text-muted"><?= $no++ ?></td>
                                        <td>
                                            <span class="font-weight-bold text-dark" style="font-size: 1.05rem;"><?= esc($ekskul['nama_ekskul']) ?></span>
                                            <?php if (!empty($ekskul['keterangan'])): ?>
                                                <br>
                                                <small class="text-muted">
                                                    <?= esc($ekskul['keterangan']) ?>
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center p-2 rounded bg-light" style="width: fit-content;">
                                                <div class="mr-2 text-primary">
                                                    <i class="fas fa-user-circle fa-lg"></i>
                                                </div>
                                                <span class="font-weight-500"><?= esc($ekskul['pembimbing_nama'] ?? 'Belum ditentukan') ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if (!empty($ekskul['jadwal'])) : ?>
                                                <?php foreach ($ekskul['jadwal'] as $jadwal) :
                                                    $jadwal_id = $jadwal['jadwal_id'] ?? $jadwal['id'] ?? null;
                                                ?>
                                                    <div class="schedule-chip">
                                                        <span>
                                                            <i class="far fa-calendar-alt mr-1"></i>
                                                            <strong><?= esc($jadwal['hari']) ?></strong>
                                                            <small class="text-muted ml-1" style="font-size: 0.85em;">(<?= date('H:i', strtotime($jadwal['jam_mulai'])) ?> - <?= date('H:i', strtotime($jadwal['jam_selesai'])) ?>)</small>
                                                        </span>
                                                        <div class="schedule-actions">
                                                            <button type="button" class="btn-icon text-warning btn-edit-jadwal"
                                                                data-ekskul-id="<?= $ekskul['id'] ?>"
                                                                data-ekskul-nama="<?= esc($ekskul['nama_ekskul']) ?>"
                                                                data-jadwal-id="<?= $jadwal_id ?>"
                                                                data-hari-index="<?= $jadwal['hari_index'] ?? '' ?>"
                                                                data-jam-mulai="<?= date('H:i', strtotime($jadwal['jam_mulai'])) ?>"
                                                                data-jam-selesai="<?= date('H:i', strtotime($jadwal['jam_selesai'])) ?>"
                                                                title="Edit Jadwal">
                                                                <i class="fas fa-pencil-alt"></i>
                                                            </button>
                                                            <button type="button" class="btn-icon text-danger btn-delete-jadwal"
                                                                data-url="<?= base_url('ekskul/deleteJadwal/' . $jadwal_id) ?>"
                                                                title="Hapus Jadwal">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <small class="text-muted font-italic">Belum ada jadwal</small>
                                            <?php endif; ?>

                                            <button type="button" class="btn btn-xs btn-outline-primary mt-2 btn-tambah-jadwal-trigger rounded-pill px-3 py-1"
                                                style="font-size: 0.75rem;"
                                                data-ekskul-id="<?= $ekskul['id'] ?>"
                                                data-ekskul-nama="<?= esc($ekskul['nama_ekskul']) ?>">
                                                <i class="fas fa-plus mr-1"></i> Jadwal
                                            </button>
                                        </td>
                                        <td class="text-center">
                                            <div class="action-group">

                                                <!-- Tombol Anggota Ekskul -->
                                                <a href="<?= smart_url('ekskul/anggota/' . $ekskul['id']) ?>"
                                                    class="btn btn-sm btn-warning shadow-sm rounded-circle"
                                                    style="width: 32px; height: 32px; padding: 0; line-height: 32px;"
                                                    title="Kelola Anggota Ekskul">
                                                    <i class="fas fa-users"></i>
                                                </a>

                                                <!-- Tombol Edit -->
                                                <button type="button"
                                                    class="btn btn-sm btn-info btn-edit shadow-sm rounded-circle"
                                                    style="width: 32px; height: 32px; padding: 0; line-height: 32px;"
                                                    data-id="<?= $ekskul['id'] ?>"
                                                    data-nama="<?= esc($ekskul['nama_ekskul']) ?>"
                                                    data-pembimbing="<?= esc($ekskul['pembimbing_id']) ?>"
                                                    data-keterangan="<?= esc($ekskul['keterangan']) ?>"
                                                    title="Edit Data Ekskul">
                                                    <i class="fas fa-edit"></i>
                                                </button>

                                                <!-- Tombol Delete -->
                                                <button type="button"
                                                    class="btn btn-sm btn-danger btn-delete-ekskul shadow-sm rounded-circle"
                                                    style="width: 32px; height: 32px; padding: 0; line-height: 32px;"
                                                    data-url="<?= base_url('ekskul/delete/' . $ekskul['id']) ?>"
                                                    data-nama="<?= esc($ekskul['nama_ekskul']) ?>"
                                                    title="Hapus Data Ekskul">
                                                    <i class="fas fa-trash"></i>
                                                </button>

                                            </div>
                                        </td>

                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Tambah/Edit Ekskul (Desain Modern) -->
<div class="modal fade" id="modalTambahEkskul" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="modalTambahEkskulLabel">Form Ekstrakurikuler</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('ekskul/save') ?>" method="post" id="formEkskul">
                <div class="modal-body p-4">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" id="ekskulId">

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Nama Ekstrakurikuler <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0"><i class="fas fa-graduation-cap text-primary"></i></span>
                            </div>
                            <input type="text" class="form-control border-left-0" id="nama_ekskul" name="nama_ekskul"
                                placeholder="Misal: Basket, Tari, Coding" value="<?= old('nama_ekskul') ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Pembimbing <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="pembimbing_id" name="pembimbing_id" required style="width: 100%;">
                            <option value="">-- Pilih Guru Pembimbing --</option>
                            <?php foreach ($pembimbingList ?? [] as $pembimbing) : ?>
                                <option value="<?= $pembimbing['id'] ?>" <?= old('pembimbing_id') == $pembimbing['id'] ? 'selected' : '' ?>>
                                    <?= esc($pembimbing['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold text-dark">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Deskripsi singkat..."><?= old('keterangan') ?></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary shadow-sm" id="btnSaveEkskul"><i class="fas fa-save mr-1"></i> Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit Jadwal (Desain Modern) -->
<div class="modal fade" id="modalTambahJadwal" tabindex="-1" role="dialog" data-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content border-top-success">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title font-weight-bold" id="modalTambahJadwalLabel">Kelola Jadwal</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('ekskul/saveJadwal') ?>" method="post" id="formJadwal">
                <div class="modal-body p-4">
                    <div class="alert alert-light border-left-success border-0 shadow-sm mb-3">
                        <small class="text-muted d-block">Menambahkan jadwal untuk:</small>
                        <strong id="jadwalEkskulNama" class="text-success h6"></strong>
                    </div>

                    <?= csrf_field() ?>
                    <input type="hidden" name="ekskul_id" id="jadwalEkskulId">
                    <input type="hidden" name="id" id="jadwalId">

                    <div class="form-group">
                        <label class="font-weight-bold">Hari</label>
                        <select class="form-control" id="hari" name="hari_index" required>
                            <option value="">Pilih Hari...</option>
                            <?php $days = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
                            foreach ($days as $index => $day) : ?>
                                <option value="<?= $index ?>"><?= $day ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="font-weight-bold small">Mulai</label>
                                <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="font-weight-bold small">Selesai</label>
                                <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light justify-content-between">
                    <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm shadow-sm" id="btnSaveJadwal"><i class="fas fa-check mr-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Script SweetAlert2 & DataTables -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // --- 1. INISIALISASI & KONFIGURASI --- //

        // Select2
        $('.select2').select2({
            dropdownParent: $('#modalTambahEkskul'),
            theme: 'bootstrap4',
            placeholder: 'Pilih opsi'
        });

        // DataTables Responsive
        const table = $('#ekskulTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            },
            "columnDefs": [{
                    "orderable": false,
                    "targets": [3, 4]
                } // Nonaktifkan sort di kolom Jadwal & Aksi
            ],
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        });

        // --- 2. NOTIFIKASI OTOMATIS (SWEETALERT) --- //

        const flashSuccess = $('#flash-success').data('msg');
        const flashError = $('#flash-error').data('msg');

        if (flashSuccess) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: flashSuccess,
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }

        if (flashError) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: flashError,
            });
        }

        // Cek Error Validasi untuk Membuka Modal Kembali
        const validationEl = $('#validation-check');
        if (validationEl.data('has-error')) {
            const targetModal = validationEl.data('modal-target');

            // Isi ulang data khusus untuk Jadwal jika validasi gagal
            const oldEkskulId = '<?= old('ekskul_id') ?>';
            if (targetModal === '#modalTambahJadwal' && oldEkskulId) {
                $('#jadwalEkskulId').val(oldEkskulId);
                const oldJadwalId = '<?= old('id') ?>';
                if (oldJadwalId) { // Jika mode edit
                    $('#jadwalId').val(oldJadwalId);
                    $('#modalTambahJadwalLabel').text('Edit Jadwal (Koreksi)');
                }

                $('#hari').val('<?= old('hari_index') ?>');
                $('#jam_mulai').val('<?= old('jam_mulai') ?>');
                $('#jam_selesai').val('<?= old('jam_selesai') ?>');
            }

            $(targetModal).modal('show');

            // Tampilkan error field dengan class is-invalid
            <?php if (isset($validation)) : ?>
                const errors = <?= json_encode($validation->getErrors()) ?>;
                $.each(errors, function(field, message) {
                    $(`[name="${field}"]`).addClass('is-invalid');
                    // Opsional: tambahkan pesan di bawah input
                    // $(`[name="${field}"]`).after(`<div class="invalid-feedback">${message}</div>`);
                });
            <?php endif; ?>
        }

        // --- 3. LOGIKA EKSKUL (MASTER) --- //

        // Tambah Ekskul
        $('.btn-tambah-ekskul').on('click', function() {
            $('#modalTambahEkskulLabel').text('Tambah Ekstrakurikuler Baru');
            $('#formEkskul')[0].reset();
            $('#ekskulId').val('');
            $('#pembimbing_id').val('').trigger('change');
            $('.is-invalid').removeClass('is-invalid'); // Bersihkan error
            $('#modalTambahEkskul').modal('show');
        });

        // Edit Ekskul
        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const pembimbingId = $(this).data('pembimbing');
            const keterangan = $(this).data('keterangan');

            $('#modalTambahEkskulLabel').text('Edit Ekskul: ' + nama);
            $('#ekskulId').val(id);
            $('#nama_ekskul').val(nama);
            $('#keterangan').val(keterangan);
            $('#pembimbing_id').val(pembimbingId).trigger('change');
            $('.is-invalid').removeClass('is-invalid');

            $('#modalTambahEkskul').modal('show');
        });

        // Hapus Ekskul dengan SweetAlert
        $(document).on('click', '.btn-delete-ekskul', function() {
            const url = $(this).data('url');
            const nama = $(this).data('nama');

            Swal.fire({
                title: 'Hapus ' + nama + '?',
                text: "Semua jadwal terkait juga akan dihapus. Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });

        // --- 4. LOGIKA JADWAL --- //

        // Tambah Jadwal
        $(document).on('click', '.btn-tambah-jadwal-trigger', function() {
            const ekskulId = $(this).data('ekskul-id');
            const ekskulNama = $(this).data('ekskul-nama');

            $('#modalTambahJadwalLabel').text('Tambah Jadwal');
            $('#formJadwal')[0].reset();
            $('#jadwalEkskulId').val(ekskulId);
            $('#jadwalId').val(''); // Kosongkan ID jadwal
            $('#jadwalEkskulNama').text(ekskulNama);
            $('.is-invalid').removeClass('is-invalid');

            $('#modalTambahJadwal').modal('show');
        });

        // Edit Jadwal
        $(document).on('click', '.btn-edit-jadwal', function() {
            const ekskulId = $(this).data('ekskul-id');
            const jadwalId = $(this).data('jadwal-id');
            const ekskulNama = $(this).data('ekskul-nama');

            // Isi data
            $('#modalTambahJadwalLabel').text('Perbarui Jadwal');
            $('#jadwalEkskulId').val(ekskulId);
            $('#jadwalId').val(jadwalId);
            $('#jadwalEkskulNama').text(ekskulNama);

            $('#hari').val($(this).data('hari-index'));
            $('#jam_mulai').val($(this).data('jam-mulai'));
            $('#jam_selesai').val($(this).data('jam-selesai'));

            $('.is-invalid').removeClass('is-invalid');
            $('#modalTambahJadwal').modal('show');
        });

        // Hapus Jadwal dengan SweetAlert
        $(document).on('click', '.btn-delete-jadwal', function() {
            const url = $(this).data('url');

            Swal.fire({
                title: 'Hapus Jadwal?',
                text: "Jadwal akan dihapus dari sistem.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>