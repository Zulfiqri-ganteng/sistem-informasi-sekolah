<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    /* Global Card Style */
    .card-form {
        max-width: 600px;
        margin: 40px auto;
        padding: 30px;
        border-radius: 18px;
        background: #ffffff;
        box-shadow: 0 10px 30px rgba(0, 0, 0, .08), 0 0 5px rgba(0, 0, 0, .03);
        border: 1px solid #e0e0e0;
    }

    /* Input Focus Styling */
    .form-control:focus,
    .form-select:focus,
    .form-control-plaintext:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    /* Input Readonly/Disabled Styling */
    .form-control:disabled,
    .form-control[readonly] {
        background-color: #f8f9fa;
        opacity: 1;
        cursor: not-allowed;
    }

    /* Tombol Kirim */
    .btn-submit {
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
    }
</style>

<div class="card-form">
    <h2 class="fw-bold mb-4 text-center text-primary">
        <i class="fa-solid fa-file-alt me-2"></i> Form Pengajuan Izin
    </h2>

    <!-- ALERT MESSAGE (Success or Error) -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-check-circle me-1"></i>
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error') || session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-times-circle me-1"></i>
            <!-- Tampilkan pesan error sederhana jika ada errors array -->
            <?php if (session()->getFlashdata('errors')): ?>
                Pengajuan gagal. Harap periksa kembali isian formulir Anda.
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <?= session()->getFlashdata('error') ?>
            <?php endif; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- FORM START -->
    <form id="izinForm" action="<?= base_url('absensi/izin/submit') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <!-- Tanggal -->
        <div class="mb-3">
            <label for="tanggal" class="form-label fw-bold">Tanggal Izin <span class="text-danger">*</span></label>
            <!-- Hidden input untuk memastikan tanggal hari ini terkirim jika disabled -->
            <input type="hidden" name="tanggal_pulang_awal" id="tanggal_pulang_awal" value="<?= date('Y-m-d') ?>">

            <input type="date" name="tanggal" id="tanggal" class="form-control"
                value="<?= old('tanggal', date('Y-m-d')) ?>" required
                min="<?= date('Y-m-d') ?>"
                aria-describedby="tanggalHelp">
            <div id="tanggalHelp" class="form-text">Tanggal Izin harus hari ini atau di masa depan.</div>
        </div>

        <!-- Jenis Izin -->
        <div class="mb-3">
            <label for="jenis" class="form-label fw-bold">Jenis Izin <span class="text-danger">*</span></label>
            <select name="jenis" id="jenis" class="form-select" required>
                <option value="" disabled selected>Pilih Jenis Izin</option>
                <option value="izin" <?= (old('jenis') == 'izin' ? 'selected' : '') ?>>Izin (Keperluan Pribadi)</option>
                <option value="sakit" <?= (old('jenis') == 'sakit' ? 'selected' : '') ?>>Sakit</option>
                <option value="pulang-awal" <?= (old('jenis') == 'pulang-awal' ? 'selected' : '') ?>>Pulang Awal (Hanya berlaku hari ini)</option>
            </select>
        </div>

        <!-- Keterangan -->
        <div class="mb-3">
            <label for="keterangan" class="form-label fw-bold">Keterangan / Alasan <span class="text-danger">*</span></label>
            <textarea name="keterangan" id="keterangan" class="form-control" rows="3" required placeholder="Jelaskan alasan pengajuan izin Anda secara singkat dan jelas."><?= old('keterangan') ?></textarea>
        </div>

        <!-- Lampiran -->
        <div class="mb-4">
            <label for="lampiran" class="form-label fw-bold">Lampiran Pendukung (Opsional)</label>
            <input type="file" name="lampiran" id="lampiran" class="form-control" accept=".jpg,.jpeg,.png,.pdf" aria-describedby="lampiranHelp">
            <div id="lampiranHelp" class="form-text">Contoh: Surat Dokter (untuk Sakit), atau surat pendukung lainnya. Maks: 2MB.</div>
            <div id="fileError" class="text-danger mt-1 d-none"><i class="fa-solid fa-exclamation-triangle me-1"></i> Ukuran atau jenis file tidak valid.</div>
        </div>

        <button type="submit" class="btn btn-primary btn-submit btn-lg w-100" id="btnSubmit">
            <i class="fa-solid fa-paper-plane me-1"></i> Kirim Pengajuan Izin
        </button>
    </form>
    <!-- FORM END -->
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('izinForm');
        const btn = document.getElementById('btnSubmit');
        const jenisSelect = document.getElementById('jenis');
        const tanggalInput = document.getElementById('tanggal');
        const tanggalPulangAwalHidden = document.getElementById('tanggal_pulang_awal');
        const lampiranInput = document.getElementById('lampiran');
        const fileError = document.getElementById('fileError');
        const today = new Date().toISOString().split('T')[0];

        // --- INISIALISASI ---

        // Mengatur tanggal minimal agar tidak bisa memilih masa lalu
        tanggalInput.setAttribute('min', today);

        // Fungsi untuk menangani perubahan jenis izin (Pulang Awal vs lainnya)
        function handleJenisChange() {
            if (jenisSelect.value === 'pulang-awal') {
                // Pulang Awal: Kunci tanggal hari ini dan non-aktifkan input
                tanggalInput.value = today;
                tanggalInput.setAttribute('readonly', 'readonly');
                tanggalInput.disabled = true;
                // Pastikan hidden input aktif
                tanggalPulangAwalHidden.disabled = false;
            } else {
                // Izin/Sakit: Aktifkan input tanggal
                tanggalInput.removeAttribute('readonly');
                tanggalInput.disabled = false;
                // Non-aktifkan hidden input agar tidak bentrok dengan input 'tanggal' utama
                tanggalPulangAwalHidden.disabled = true;
            }
        }

        // Jalankan saat load (untuk old value jika ada error)
        handleJenisChange();

        // Listener saat jenis izin diubah
        jenisSelect.addEventListener('change', handleJenisChange);

        // --- VALIDASI DAN SUBMIT ---

        // Mencegah double submit dan validasi lampiran:
        form.addEventListener('submit', function(e) {

            // 1. Validasi Lampiran (Max 2MB, Tipe: jpg, png, pdf)
            if (lampiranInput.files.length > 0) {
                const file = lampiranInput.files[0];
                const maxSize = 2 * 1024 * 1024; // 2MB
                const allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];

                if (file.size > maxSize || !allowedTypes.includes(file.type)) {
                    e.preventDefault();
                    fileError.classList.remove('d-none');
                    lampiranInput.value = ''; // Reset input file
                    return;
                }
            }

            // Sembunyikan pesan error jika sebelumnya muncul
            fileError.classList.add('d-none');


            // 2. Mencegah double submit: non-aktifkan tombol saat form disubmit
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Mengirim...';
            btn.disabled = true;

            // Jika input tanggal dinonaktifkan (karena pulang-awal), hapus atribut 'disabled' 
            // sebentar agar nilainya terkirim (hanya untuk browser tertentu)
            if (tanggalInput.disabled) {
                tanggalInput.disabled = false;
            } else {
                // Jika tidak pulang-awal, non-aktifkan hidden input 
                // agar yang terkirim hanya input 'tanggal' utama
                tanggalPulangAwalHidden.disabled = true;
            }
        });

        // Pastikan tombol aktif kembali jika ada kesalahan server
        <?php if (session()->getFlashdata('error') || session()->getFlashdata('errors')): ?>
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-paper-plane me-1"></i> Kirim Pengajuan Izin';
        <?php endif; ?>

    });
</script>

<?= $this->endSection() ?>