<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<!-- Custom Styles untuk Tampilan Lebih Modern dan Responsif -->
<style>
    :root {
        --primary-color: #3498db;
        --secondary-color: #2c3e50;
        --success-color: #27ae60;
        --warning-color: #f39c12;
        --danger-color: #e74c3c;
        --light-color: #f8f9fa;
        --dark-color: #343a40;
        --border-radius: 12px;
        --box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    /* Styling Umum untuk Card */
    .card-modern {
        border: none;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        transition: var(--transition);
        overflow: hidden;
    }

    .card-modern:hover {
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .card-header-modern {
        background: linear-gradient(135deg, var(--primary-color), #2980b9);
        border-bottom: none;
        padding: 1.25rem 1.5rem;
    }

    .card-header-modern h3 {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .card-header-modern small {
        opacity: 0.9;
    }

    /* Styling untuk Tabel Desktop */
    .table-modern {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    .table-modern thead th {
        background-color: var(--secondary-color);
        color: white;
        font-weight: 600;
        border: none;
        padding: 1rem;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table-modern tbody tr {
        transition: var(--transition);
    }

    .table-modern tbody tr:hover {
        background-color: rgba(52, 152, 219, 0.05);
    }

    .table-modern tbody td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #e9ecef;
    }

    /* Warna untuk highlight libur pada desktop */
    .table-modern .libur-row {
        background-color: rgba(243, 156, 18, 0.1) !important;
        border-left: 4px solid var(--warning-color);
    }

    /* Styling untuk tampilan mobile/card */
    @media (max-width: 991px) {
        .jadwal-item {
            border: none;
            border-radius: var(--border-radius);
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            background: white;
            position: relative;
        }

        .jadwal-item:hover {
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
        }

        /* Warna untuk highlight libur pada mobile */
        .jadwal-item.is-libur {
            background-color: rgba(243, 156, 18, 0.08);
            border-left: 4px solid var(--warning-color);
        }

        .jadwal-item strong {
            display: block;
            font-size: 1.25rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
            font-weight: 600;
        }

        .input-group-vertical {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .input-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .input-label i {
            margin-right: 0.5rem;
            color: var(--primary-color);
        }

        .action-button {
            margin-top: 1.5rem;
            width: 100%;
            border-radius: 8px;
            padding: 0.75rem;
            font-weight: 600;
            transition: var(--transition);
        }
    }

    /* Styling untuk custom switch toggle yang lebih modern */
    .switch-modern {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 32px;
    }

    .switch-modern input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider-modern {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: var(--danger-color);
        transition: var(--transition);
        border-radius: 34px;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2);
    }

    .slider-modern:before {
        position: absolute;
        content: "";
        height: 24px;
        width: 24px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: var(--transition);
        border-radius: 50%;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    input:checked+.slider-modern {
        background-color: var(--success-color);
    }

    input:checked+.slider-modern:before {
        transform: translateX(28px);
    }

    /* Label di samping switch */
    .switch-label-modern {
        font-weight: 600;
        margin-left: 70px;
        line-height: 32px;
        color: var(--dark-color);
    }

    /* Styling untuk form inputs */
    .form-control-modern {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 0.75rem;
        transition: var(--transition);
        box-shadow: none;
    }

    .form-control-modern:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
    }

    /* Styling untuk tombol */
    .btn-modern {
        border-radius: 8px;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: var(--transition);
        border: none;
    }

    .btn-primary-modern {
        background: linear-gradient(135deg, var(--primary-color), #2980b9);
        color: white;
    }

    .btn-primary-modern:hover {
        background: linear-gradient(135deg, #2980b9, #1f6390);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    .btn-success-modern {
        background: linear-gradient(135deg, var(--success-color), #219653);
        color: white;
    }

    .btn-success-modern:hover {
        background: linear-gradient(135deg, #219653, #1e8449);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    /* Styling untuk alert */
    .alert-modern {
        border-radius: 10px;
        border: none;
        padding: 1rem 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    /* Styling untuk list hari libur */
    .libur-list-item {
        border: none;
        border-bottom: 1px solid #f0f0f0;
        padding: 1rem 1.25rem;
        transition: var(--transition);
    }

    .libur-list-item:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .libur-list-item:last-child {
        border-bottom: none;
    }

    /* Styling untuk modal */
    .modal-modern .modal-content {
        border-radius: var(--border-radius);
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }

    .modal-modern .modal-header {
        border-bottom: 1px solid #e9ecef;
        padding: 1.25rem 1.5rem;
    }

    .modal-modern .modal-body {
        padding: 1.5rem;
    }

    .modal-modern .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 1.25rem 1.5rem;
    }

    /* Animasi loading untuk tombol */
    .btn-loading {
        position: relative;
        color: transparent !important;
    }

    .btn-loading:after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        margin: auto;
        border: 2px solid transparent;
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: button-loading-spinner 1s ease infinite;
    }

    @keyframes button-loading-spinner {
        from {
            transform: rotate(0turn);
        }

        to {
            transform: rotate(1turn);
        }
    }

    /* Responsif tambahan untuk device sangat kecil */
    @media (max-width: 575px) {
        .card-body {
            padding: 1rem;
        }

        .jadwal-item {
            padding: 1.25rem;
        }

        .table-modern thead th,
        .table-modern tbody td {
            padding: 0.75rem 0.5rem;
        }
    }
</style>

<div class="row">
    <!-- Kolom Utama: Pengaturan Jadwal Harian -->
    <div class="col-lg-8 col-md-12 mb-4">
        <div class="card card-modern">
            <div class="card-header card-header-modern text-white">
                <h3 class="card-title mb-1"><i class="fas fa-clock me-2"></i>Pengaturan Jadwal Absensi Harian</h3>
                <small class="d-block opacity-90">Atur jam masuk dan pulang per hari. Gunakan toggle untuk mengubah status Kerja/Libur.</small>
            </div>
            <div class="card-body p-0">
                <!-- Pesan Flashdata -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-modern alert-dismissible fade show m-3" role="alert">
                        <i class="fas fa-check-circle me-2"></i> <strong>Berhasil!</strong> <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-modern alert-dismissible fade show m-3" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i> <strong>Gagal!</strong> <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Tampilan Desktop (Tabel) -->
                <div class="d-none d-lg-block">
                    <div class="table-responsive">
                        <table class="table table-modern">
                            <thead>
                                <tr>
                                    <th style="width: 12%;">Hari</th>
                                    <th style="width: 15%;">Status</th>
                                    <th style="width: 18%;">Masuk Normal</th>
                                    <th style="width: 18%;">Kunci Absen</th>
                                    <th style="width: 18%;">Pulang Min.</th>
                                    <th style="width: 18%;">Pulang Normal</th>
                                    <th style="width: 10%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($jadwal as $j): ?>
                                    <!-- Tambahkan class jika statusnya libur untuk highlight -->
                                    <tr class="<?= strtolower($j['status']) == 'libur' ? 'libur-row' : '' ?>">
                                        <!-- Arah Simpan: base_url('admin/jadwal/update') -->
                                        <form method="post" action="<?= base_url('admin/jadwal/update') ?>" class="jadwal-form">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= $j['id'] ?>">

                                            <td class="fw-bold text-primary"><?= $j['hari_nama'] ?></td>
                                            <td>
                                                <!-- Switch Toggle untuk Status Hari -->
                                                <label class="switch-modern">
                                                    <!-- Jika 'kerja' checked=true. Gunakan data-id untuk targeting JS -->
                                                    <input type="checkbox" name="status_toggle" data-id="<?= $j['id'] ?>" onchange="updateHiddenStatus(this)" <?= strtolower($j['status']) == 'kerja' ? 'checked' : '' ?>>
                                                    <span class="slider-modern"></span>
                                                </label>
                                                <!-- Hidden input untuk nilai yang dikirim ke controller -->
                                                <input type="hidden" name="status" id="status-<?= $j['id'] ?>" value="<?= $j['status'] ?>">
                                                <span class="switch-label-modern" id="label-<?= $j['id'] ?>">
                                                    <?= ucfirst($j['status']) ?>
                                                </span>
                                            </td>
                                            <td><input type="time" name="jam_masuk_normal" class="form-control form-control-modern" value="<?= esc($j['jam_masuk_normal']) ?>" required></td>
                                            <td><input type="time" name="jam_penguncian" class="form-control form-control-modern" value="<?= esc($j['jam_penguncian']) ?>" required></td>
                                            <td><input type="time" name="jam_pulang_minimal" class="form-control form-control-modern" value="<?= esc($j['jam_pulang_minimal']) ?>" required></td>
                                            <td><input type="time" name="jam_pulang_normal" class="form-control form-control-modern" value="<?= esc($j['jam_pulang_normal']) ?>" required></td>
                                            <td>
                                                <button type="submit" class="btn btn-primary-modern btn-modern btn-sm w-100"><i class="fas fa-save me-1"></i> Simpan</button>
                                            </td>
                                        </form>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tampilan Tablet dan Mobile (Card View) -->
                <div class="d-lg-none p-3">
                    <?php foreach ($jadwal as $j): ?>
                        <div class="jadwal-item <?= strtolower($j['status']) == 'libur' ? 'is-libur' : '' ?>" id="card-<?= $j['id'] ?>">
                            <!-- Arah Simpan: base_url('admin/jadwal/update') -->
                            <form method="post" action="<?= base_url('admin/jadwal/update') ?>" class="jadwal-form">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $j['id'] ?>">

                                <strong><i class="fas fa-calendar-day me-2"></i> <?= $j['hari_nama'] ?></strong>

                                <div class="input-group-vertical">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="input-label"><i class="fas fa-toggle-on"></i> Status Hari</div>
                                        <div class="d-flex align-items-center">
                                            <!-- Switch Toggle Mobile -->
                                            <label class="switch-modern">
                                                <input type="checkbox" name="status_toggle_mobile" data-id="<?= $j['id'] ?>" onchange="updateHiddenStatus(this)" <?= strtolower($j['status']) == 'kerja' ? 'checked' : '' ?>>
                                                <span class="slider-modern"></span>
                                            </label>
                                            <!-- Hidden input status mobile -->
                                            <input type="hidden" name="status" id="status-mobile-<?= $j['id'] ?>" value="<?= $j['status'] ?>">
                                            <span class="text-muted ms-2 fw-medium" id="label-mobile-<?= $j['id'] ?>">
                                                <?= ucfirst($j['status']) ?>
                                            </span>
                                        </div>
                                    </div>
                                    <hr class="my-2">
                                    <div>
                                        <div class="input-label"><i class="fas fa-sign-in-alt"></i> Masuk Normal</div>
                                        <input type="time" name="jam_masuk_normal" class="form-control form-control-modern" value="<?= esc($j['jam_masuk_normal']) ?>" required>
                                    </div>
                                    <div>
                                        <div class="input-label"><i class="fas fa-lock"></i> Kunci Absen (Batas Akhir)</div>
                                        <input type="time" name="jam_penguncian" class="form-control form-control-modern" value="<?= esc($j['jam_penguncian']) ?>" required>
                                    </div>
                                    <div>
                                        <div class="input-label"><i class="fas fa-sign-out-alt"></i> Pulang Minimal</div>
                                        <input type="time" name="jam_pulang_minimal" class="form-control form-control-modern" value="<?= esc($j['jam_pulang_minimal']) ?>" required>
                                    </div>
                                    <div>
                                        <div class="input-label"><i class="fas fa-home"></i> Pulang Normal</div>
                                        <input type="time" name="jam_pulang_normal" class="form-control form-control-modern" value="<?= esc($j['jam_pulang_normal']) ?>" required>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary-modern action-button"><i class="fas fa-save me-2"></i> Simpan Jadwal</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kedua: Hari Libur Insidental -->
    <div class="col-lg-4 col-md-12">
        <div class="card card-modern mb-4">
            <div class="card-header card-header-modern text-white" style="background: linear-gradient(135deg, var(--success-color), #219653);">
                <h3 class="card-title mb-0"><i class="fas fa-calendar-plus me-2"></i>Tambah Hari Libur Insidental</h3>
            </div>
            <div class="card-body">
                <!-- Form Tambah Libur. Arah Simpan: base_url('admin/jadwal/add-libur') -->
                <form method="post" action="<?= base_url('admin/jadwal/add-libur') ?>" id="form-libur">
                    <?= csrf_field() ?>
                    <div class="form-group mb-3">
                        <label for="tanggal_libur" class="form-label fw-medium"><i class="fas fa-calendar me-2"></i>Tanggal Libur</label>
                        <input type="date" id="tanggal_libur" name="tanggal" class="form-control form-control-modern" required>
                    </div>
                    <div class="form-group mb-4">
                        <label for="keterangan_libur" class="form-label fw-medium"><i class="fas fa-tag me-2"></i>Keterangan</label>
                        <input type="text" id="keterangan_libur" name="keterangan" class="form-control form-control-modern" placeholder="Contoh: Libur Hari Kemerdekaan" required>
                    </div>
                    <button type="submit" class="btn btn-success-modern btn-modern w-100"><i class="fas fa-plus-circle me-2"></i>Tambah Libur</button>
                </form>
            </div>
        </div>

        <div class="card card-modern">
            <div class="card-header bg-light">
                <h5 class="card-title text-dark mb-0"><i class="fas fa-list-ul me-2"></i>Daftar Hari Libur (10 Terbaru)</h5>
            </div>
            <div class="card-body p-0">
                <!-- Daftar Hari Libur -->
                <div class="list-group list-group-flush">
                    <?php if (empty($libur)): ?>
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-info-circle fa-2x mb-2"></i>
                            <p class="mb-0">Tidak ada hari libur insidental saat ini.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($libur as $l): ?>
                            <div class="list-group-item libur-list-item d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <strong class="text-dark d-block"><?= date('d M Y', strtotime($l['tanggal'])) ?></strong>
                                    <small class="text-muted"><?= esc($l['keterangan']) ?></small>
                                </div>
                                <!-- Memanggil fungsi JS dengan modal untuk konfirmasi hapus -->
                                <button type="button" class="btn btn-danger btn-sm ms-2" onclick="showDeleteModal(<?= $l['id'] ?>, '<?= esc($l['keterangan']) ?>', '<?= date('d M Y', strtotime($l['tanggal'])) ?>')" title="Hapus Libur">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus (Mengganti window.confirm) -->
<div class="modal fade modal-modern" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Penghapusan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Anda yakin ingin menghapus hari libur ini?</p>
                <p class="fw-bold mb-2" id="libur-details"></p>
                <p class="text-danger small mb-0">Aksi ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-modern" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger btn-modern" id="confirmDeleteButton">Ya, Hapus Permanen</button>
            </div>
        </div>
    </div>
</div>

<!-- Form tersembunyi untuk menjalankan metode DELETE -->
<form id="delete-form" method="post" style="display: none;">
    <?= csrf_field() ?>
</form>

<script>
    // Rute asli yang digunakan (untuk Hapus Libur)
    const DELETE_URL = '<?= base_url('admin/jadwal/delete-libur') ?>';

    // Fungsi untuk menampilkan Modal Konfirmasi Hapus
    function showDeleteModal(id, keterangan, tanggal) {
        // Isi detail libur pada modal
        document.getElementById('libur-details').innerHTML = `<strong>Tanggal:</strong> ${tanggal}<br><strong>Keterangan:</strong> ${keterangan}`;

        // Set event listener pada tombol konfirmasi di modal
        const confirmButton = document.getElementById('confirmDeleteButton');

        // Hapus listener sebelumnya
        confirmButton.onclick = null;

        // Set aksi hapus saat tombol diklik
        confirmButton.onclick = function() {
            // Panggil fungsi delete sebenarnya
            performDelete(id);
            // Sembunyikan modal setelah dikonfirmasi
            const modal = bootstrap.Modal.getInstance(document.getElementById('deleteModal'));
            modal.hide();
        };

        // Tampilkan modal
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    // Fungsi untuk menjalankan penghapusan
    function performDelete(id) {
        // Menggunakan rute asli: admin/jadwal/delete-libur/123
        const deleteForm = document.getElementById('delete-form');
        deleteForm.action = DELETE_URL + '/' + id;

        // Rute asli Anda (berdasarkan kode original yang tidak pakai DELETE spoofing) 
        // kemungkinan besar menerima method POST atau GET, jadi kita set ke POST 
        // agar CSRF token tetap terkirim.
        deleteForm.method = 'post';

        // Kirim form untuk menjalankan hapus
        deleteForm.submit();
    }

    // Fungsi utama untuk mengupdate nilai hidden status dan visual
    function updateHiddenStatus(toggle) {
        const isChecked = toggle.checked; // true = Kerja, false = Libur
        const statusValue = isChecked ? 'Kerja' : 'Libur'; // Menggunakan Kapital (Kerja/Libur) sesuai data DB
        const displayValue = isChecked ? 'Kerja' : 'Libur';

        const id = toggle.getAttribute('data-id');

        // 1. Update Hidden Input (Untuk dikirim ke Controller)
        // Mencari hidden input dengan ID: status-ID (untuk desktop) atau status-mobile-ID (untuk mobile)
        const hiddenInputDesktop = document.getElementById('status-' + id);
        if (hiddenInputDesktop) {
            hiddenInputDesktop.value = statusValue;
        }
        const hiddenInputMobile = document.getElementById('status-mobile-' + id);
        if (hiddenInputMobile) {
            hiddenInputMobile.value = statusValue;
        }

        // 2. Update Label dan Highlight Visual
        if (toggle.name === 'status_toggle') {
            // Tampilan Desktop (Tabel)
            const row = toggle.closest('tr');
            if (row) {
                row.classList.toggle('libur-row', !isChecked);
                const label = document.getElementById('label-' + id);
                if (label) {
                    label.textContent = displayValue;
                    label.classList.toggle('text-success', isChecked);
                    label.classList.toggle('text-warning', !isChecked);
                }
            }
        } else if (toggle.name === 'status_toggle_mobile') {
            // Tampilan Mobile (Card)
            const card = document.getElementById('card-' + id);
            if (card) {
                card.classList.toggle('is-libur', !isChecked);
                const label = document.getElementById('label-mobile-' + id);
                if (label) {
                    label.textContent = displayValue;
                    label.classList.toggle('text-success', isChecked);
                    label.classList.toggle('text-warning', !isChecked);
                }
            }
        }
    }

    // Menambahkan loading state pada form submit
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('.jadwal-form, #form-libur');

        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitButton = this.querySelector('button[type="submit"]');

                // Tambahkan kelas loading
                submitButton.classList.add('btn-loading');
                submitButton.disabled = true;

                // Set timeout untuk mencegah form submit terlalu cepat (untuk demo)
                setTimeout(() => {
                    submitButton.classList.remove('btn-loading');
                    submitButton.disabled = false;
                }, 2000);
            });
        });
    });
</script>

<?= $this->endSection() ?>