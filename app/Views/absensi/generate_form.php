<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<!-- SELECT2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.3.0/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
<!-- Animate CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<style>
    :root {
        --primary: #4361ee;
        --secondary: #6c757d;
        --success: #06d6a0;
        --warning: #ffd166;
        --danger: #ef476f;
        --light: #f8f9fa;
        --dark: #212529;
        --border-radius: 16px;
        --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .page-header {
        background: linear-gradient(135deg, #4361ee 0%, #3a56d4 100%);
        border-radius: var(--border-radius);
        padding: 2.5rem;
        margin-bottom: 2.5rem;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 200%;
        background: rgba(255, 255, 255, 0.1);
        transform: rotate(30deg);
    }

    .mode-card {
        cursor: pointer;
        padding: 2.5rem 1.5rem;
        border-radius: var(--border-radius);
        transition: var(--transition);
        border: 2px solid transparent;
        background: white;
        text-align: center;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        box-shadow: var(--box-shadow);
    }

    .mode-card:hover {
        transform: translateY(-10px);
        border-color: var(--primary);
        box-shadow: 0 20px 40px rgba(67, 97, 238, 0.15);
    }

    .mode-card.active {
        border-color: var(--primary);
        background: linear-gradient(135deg, rgba(67, 97, 238, 0.05) 0%, rgba(67, 97, 238, 0.02) 100%);
    }

    .mode-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        font-size: 2rem;
        transition: var(--transition);
    }

    .mode-card:hover .mode-icon {
        transform: scale(1.1);
    }

    .mode-card[data-mode="siswa"] .mode-icon {
        background: linear-gradient(135deg, rgba(67, 97, 238, 0.15) 0%, rgba(67, 97, 238, 0.1) 100%);
        color: var(--primary);
    }

    .mode-card[data-mode="guru"] .mode-icon {
        background: linear-gradient(135deg, rgba(255, 209, 102, 0.15) 0%, rgba(255, 209, 102, 0.1) 100%);
        color: #e6b400;
    }

    .mode-card[data-mode="kelas"] .mode-icon {
        background: linear-gradient(135deg, rgba(6, 214, 160, 0.15) 0%, rgba(6, 214, 160, 0.1) 100%);
        color: var(--success);
    }

    .form-section {
        background: white;
        border-radius: var(--border-radius);
        padding: 2.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--box-shadow);
        border: 1px solid #e9ecef;
        animation: fadeInUp 0.5s ease;
    }

    .form-section h5 {
        color: var(--primary);
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-section h5 i {
        font-size: 1.25rem;
    }

    .preview-box {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 2rem;
        border-radius: var(--border-radius);
        border: 2px dashed #c7d2fe;
        margin-top: 2rem;
        display: none;
        animation: fadeIn 0.5s ease;
    }

    .preview-box.show {
        display: block;
    }

    .preview-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid #e9ecef;
        transition: var(--transition);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .preview-card:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .preview-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e9ecef;
        flex-shrink: 0;
    }

    .preview-info h6 {
        margin-bottom: 0.25rem;
        color: var(--dark);
    }

    .preview-info p {
        color: #6c757d;
        font-size: 0.875rem;
        margin-bottom: 0;
    }

    .btn-generate {
        background: linear-gradient(135deg, var(--primary) 0%, #3a56d4 100%);
        border: none;
        padding: 1rem 2.5rem;
        font-size: 1.1rem;
        font-weight: 600;
        border-radius: 50px;
        transition: var(--transition);
        display: none;
        align-items: center;
        gap: 0.75rem;
        margin-top: 2rem;
    }

    .btn-generate:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(67, 97, 238, 0.3);
    }

    .btn-generate.show {
        display: inline-flex;
        animation: fadeInUp 0.5s ease;
    }

    .select2-container--bootstrap-5 .select2-selection {
        min-height: 60px;
        padding: 12px 16px;
        border-radius: 12px;
        border: 2px solid #e9ecef;
        font-size: 1rem;
        transition: var(--transition);
    }

    .select2-container--bootstrap-5 .select2-selection:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
    }

    .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .stats-bar {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }

    .stat-item {
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 12px;
        border: 1px solid #e9ecef;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        min-width: 180px;
    }

    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .stat-icon.siswa {
        background: rgba(67, 97, 238, 0.1);
        color: var(--primary);
    }

    .stat-icon.guru {
        background: rgba(255, 209, 102, 0.1);
        color: #e6b400;
    }

    .stat-icon.kelas {
        background: rgba(6, 214, 160, 0.1);
        color: var(--success);
    }

    .stat-content h4 {
        margin-bottom: 0;
        font-weight: 700;
    }

    .stat-content p {
        margin-bottom: 0;
        font-size: 0.875rem;
        color: #6c757d;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }

        .mode-card {
            padding: 1.5rem 1rem;
        }

        .form-section {
            padding: 1.5rem;
        }

        .stats-bar {
            flex-direction: column;
        }

        .stat-item {
            min-width: 100%;
        }
    }
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="page-header animate__animated animate__fadeIn">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h2 mb-2 fw-bold">
                    <i class="fa-solid fa-qrcode me-3"></i>Generate QR Code Absensi
                </h1>
                <p class="mb-0 opacity-75">Buat kode QR untuk absensi siswa, guru, atau kelas dengan cepat dan mudah</p>
            </div>
            <div class="col-auto">
                <div class="bg-white bg-opacity-20 p-3 rounded-circle">
                    <i class="fa-solid fa-sparkles fa-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-bar">
        <div class="stat-item">
            <div class="stat-icon siswa">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stat-content">
                <h4><?= count($siswa) ?></h4>
                <p>Total Siswa</p>
            </div>
        </div>

        <div class="stat-item">
            <div class="stat-icon guru">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
            <div class="stat-content">
                <h4><?= count($guru) ?></h4>
                <p>Total Guru</p>
            </div>
        </div>

        <div class="stat-item">
            <div class="stat-icon kelas">
                <i class="fa-solid fa-school"></i>
            </div>
            <div class="stat-content">
                <h4><?= count($kelas) ?></h4>
                <p>Total Kelas</p>
            </div>
        </div>
    </div>

    <!-- Mode Selection -->
    <div class="form-section">
        <h5><i class="fa-solid fa-sliders"></i> Pilih Mode Generate</h5>
        <div class="alert alert-info bg-light border-info border-opacity-25">
            <i class="fa-solid fa-circle-info me-2"></i>
            Pilih mode lalu pilih pemilik QR. Sistem mendukung <b>multi-select</b> dan <b>auto-preview</b> identitas.
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="mode-card" data-mode="siswa" onclick="setMode('siswa')">
                    <div class="mode-icon">
                        <i class="fa-solid fa-users fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Generate QR Banyak Siswa</h5>
                    <p class="text-muted mb-0">Buat QR code untuk beberapa siswa sekaligus</p>
                    <div class="mt-3 text-primary">
                        <i class="fa-solid fa-check-circle"></i>
                        <small>Multi-select tersedia</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mode-card" data-mode="guru" onclick="setMode('guru')">
                    <div class="mode-icon">
                        <i class="fa-solid fa-chalkboard-user fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Generate QR Banyak Guru</h5>
                    <p class="text-muted mb-0">Buat QR code untuk beberapa guru sekaligus</p>
                    <div class="mt-3 text-warning">
                        <i class="fa-solid fa-check-circle"></i>
                        <small>Multi-select tersedia</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mode-card" data-mode="kelas" onclick="setMode('kelas')">
                    <div class="mode-icon">
                        <i class="fa-solid fa-school fa-2x"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Generate QR Satu Kelas</h5>
                    <p class="text-muted mb-0">Buat QR code untuk seluruh siswa dalam satu kelas</p>
                    <div class="mt-3 text-success">
                        <i class="fa-solid fa-check-circle"></i>
                        <small>Seluruh kelas</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <form action="<?= base_url('absensi/generate') ?>" method="post" class="form-section">
        <?= csrf_field() ?>
        <input type="hidden" name="mode" id="mode" value="">

        <!-- Siswa Form -->
        <div id="form-siswa" class="d-none">
            <h5><i class="fa-solid fa-user-graduate"></i> Pilih Siswa</h5>
            <label class="form-label fw-semibold mb-3">Pilih satu atau beberapa siswa dari daftar</label>
            <select name="owner_id[]" multiple class="form-select select2-premium">
                <?php foreach ($siswa as $s): ?>
                    <option value="<?= $s['id'] ?>"
                        data-role="siswa"
                        data-foto="<?= $s['foto'] ?>"
                        data-info="NISN: <?= $s['nisn'] ?> | Kelas: <?= $s['kelas'] ?>">
                        <?= $s['nama'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Guru Form -->
        <div id="form-guru" class="d-none">
            <h5><i class="fa-solid fa-chalkboard-user"></i> Pilih Guru</h5>
            <label class="form-label fw-semibold mb-3">Pilih satu atau beberapa guru dari daftar</label>
            <select name="owner_id[]" multiple class="form-select select2-premium">
                <?php foreach ($guru as $g): ?>
                    <option value="<?= $g['id'] ?>"
                        data-role="guru"
                        data-foto="<?= $g['foto'] ?>"
                        data-info="NIP: <?= $g['nip'] ?>">
                        <?= $g['nama'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Kelas Form -->
        <div id="form-kelas" class="d-none">
            <h5><i class="fa-solid fa-school"></i> Pilih Kelas</h5>
            <label class="form-label fw-semibold mb-3">Pilih satu kelas untuk generate QR semua siswanya</label>
            <select name="kelas_id" class="form-select select2-premium">
                <option value="">-- Pilih Kelas --</option>
                <?php foreach ($kelas as $k): ?>
                    <option value="<?= $k['id'] ?>"
                        data-role="kelas"
                        data-info="Kelas <?= $k['nama_kelas'] ?>">
                        <?= $k['nama_kelas'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Preview Section -->
        <div id="preview" class="preview-box">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0"><i class="fa-solid fa-eye me-2"></i>Preview Identitas</h5>
                <span class="badge bg-primary px-3 py-2" id="previewCount">0 item</span>
            </div>
            <div id="preview_content" class="row"></div>
        </div>

        <!-- Generate Button -->
        <div class="text-center">
            <button type="submit" id="btn_generate" class="btn-generate">
                <i class="fa-solid fa-qrcode"></i> Generate QR Code Premium
            </button>
        </div>
    </form>
</div>

<!-- JavaScript Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>

<script>
    class QRGenerator {
        constructor() {
            this.currentMode = '';
            this.initSelect2();
            this.bindEvents();
        }

        initSelect2() {
            $('.select2-premium').select2({
                theme: "bootstrap-5",
                placeholder: "Pilih data...",
                allowClear: true,
                width: '100%',
                templateResult: this.formatOption.bind(this),
                templateSelection: this.formatSelection.bind(this)
            });
        }

        bindEvents() {
            // Mode card click
            $('.mode-card').on('click', (e) => {
                const mode = $(e.currentTarget).data('mode');
                this.setMode(mode);
            });

            // Select change event
            $('.select2-premium').on('change', this.handleSelectChange.bind(this));
        }

        setMode(mode) {
            this.currentMode = mode;
            $('#mode').val(mode);

            // Reset UI
            $('.mode-card').removeClass('active');
            $(`.mode-card[data-mode="${mode}"]`).addClass('active');

            // Hide all forms
            $('#form-siswa, #form-guru, #form-kelas').addClass('d-none');
            $('#btn_generate').removeClass('show');
            $('#preview').removeClass('show');
            $('#preview_content').html('');

            // Show selected form
            if (mode === 'siswa') {
                $('#form-siswa').removeClass('d-none');
                // Reset selection
                $('#form-siswa select').val(null).trigger('change');
            } else if (mode === 'guru') {
                $('#form-guru').removeClass('d-none');
                $('#form-guru select').val(null).trigger('change');
            } else if (mode === 'kelas') {
                $('#form-kelas').removeClass('d-none');
                // Show generate button immediately for kelas mode
                $('#btn_generate').addClass('show');
                $('#form-kelas select').val('').trigger('change');
            }
        }

        formatOption(state) {
            if (!state.id) return state.text;

            const $option = $(state.element);
            const role = $option.data('role');
            const foto = $option.data('foto');
            const info = $option.data('info') || '';

            // Mode kelas
            if (!role) {
                return $(`
                <div class="d-flex align-items-center p-2">
                    <div class="bg-success bg-opacity-10 p-2 rounded-circle me-3">
                        <i class="fa-solid fa-school text-success"></i>
                    </div>
                    <div>
                        <strong>${state.text}</strong>
                        <br><small class="text-muted">${info}</small>
                    </div>
                </div>
            `);
            }

            // Mode siswa/guru
            const folder = role === 'guru' ? 'uploads/guru/' : 'uploads/siswa/';
            const imgUrl = foto ?
                "<?= base_url() ?>/" + folder + foto :
                "<?= base_url('assets/default/user.png') ?>";

            return $(`
            <div class="d-flex align-items-center p-2">
                <img src="${imgUrl}" 
                     width="45" 
                     height="45" 
                     class="rounded-circle me-3 object-fit-cover"
                     onerror="this.src='<?= base_url('assets/default/user.png') ?>'">
                <div>
                    <strong>${state.text}</strong>
                    <br><small class="text-muted">${info}</small>
                </div>
            </div>
        `);
        }

        formatSelection(state) {
            return state.text;
        }

        handleSelectChange(e) {
            const $select = $(e.target);
            const selected = $select.find(':selected');

            if (selected.length === 0) {
                $('#preview').removeClass('show');
                if (this.currentMode !== 'kelas') {
                    $('#btn_generate').removeClass('show');
                }
                return;
            }

            // Update preview count
            $('#previewCount').text(`${selected.length} item`);

            // Generate preview
            this.generatePreview(selected);

            // Show preview and button
            $('#preview').addClass('show');
            $('#btn_generate').addClass('show');

            // Animate
            $('#preview').addClass('animate__animated animate__fadeIn');
            setTimeout(() => {
                $('#preview').removeClass('animate__animated animate__fadeIn');
            }, 500);
        }

        generatePreview(selected) {
            const $container = $('#preview_content');
            $container.html('');

            selected.each((index, element) => {
                const $option = $(element);
                const text = $option.text();
                const foto = $option.data('foto');
                const role = $option.data('role');
                const info = $option.data('info') || '';

                const folder = role === 'guru' ? 'uploads/guru/' : 'uploads/siswa/';
                const imgUrl = foto ?
                    "<?= base_url() ?>/" + folder + foto :
                    "<?= base_url('assets/default/user.png') ?>";

                const icon = role === 'guru' ? 'fa-chalkboard-user' :
                    role === 'siswa' ? 'fa-user-graduate' : 'fa-school';
                const color = role === 'guru' ? 'warning' :
                    role === 'siswa' ? 'primary' : 'success';

                const html = `
                <div class="col-lg-6 col-xl-4 mb-3">
                    <div class="preview-card">
                        <img src="${imgUrl}" 
                             alt="${text}" 
                             class="preview-avatar"
                             onerror="this.src='<?= base_url('assets/default/user.png') ?>'">
                        <div class="preview-info">
                            <h6 class="fw-bold mb-1">
                                <i class="fa-solid ${icon} text-${color} me-1"></i>
                                ${text}
                            </h6>
                            <p class="mb-0">${info}</p>
                        </div>
                    </div>
                </div>
            `;

                $container.append(html);
            });
        }
    }

    // Initialize when DOM is ready
    document.addEventListener('DOMContentLoaded', () => {
        window.qrGenerator = new QRGenerator();
    });
</script>

<?= $this->endSection() ?>