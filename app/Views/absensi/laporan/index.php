<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #0ba360 0%, #3cba92 100%);
        --card-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        --card-shadow-hover: 0 15px 35px rgba(0, 0, 0, 0.1);
        --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .page-header {
        background: var(--primary-gradient);
        padding: 2rem 0;
        margin: -2rem -2rem 2rem -2rem;
        border-radius: 0 0 20px 20px;
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" d="M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,202.7C672,203,768,181,864,160C960,139,1056,117,1152,117.3C1248,117,1344,139,1392,149.3L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
        background-size: cover;
        opacity: 0.3;
    }

    .page-header h3 {
        font-weight: 700;
        color: white;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        position: relative;
    }

    .page-header .lead {
        color: rgba(255, 255, 255, 0.9);
        position: relative;
    }

    .report-card {
        border: none;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        transition: var(--transition-smooth);
        overflow: hidden;
        height: 100%;
        background: white;
        position: relative;
        z-index: 1;
    }

    .report-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
        z-index: 2;
    }

    .report-card.harian::before {
        background: var(--primary-gradient);
    }

    .report-card.ekskul::before {
        background: var(--success-gradient);
    }

    .report-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--card-shadow-hover);
    }

    .card-icon-wrapper {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }

    .card-icon-wrapper.harian {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        color: #667eea;
    }

    .card-icon-wrapper.ekskul {
        background: linear-gradient(135deg, rgba(11, 163, 96, 0.1) 0%, rgba(60, 186, 146, 0.1) 100%);
        color: #0ba360;
    }

    .card-icon-wrapper .bi {
        font-size: 1.75rem;
        z-index: 1;
    }

    .card-title {
        font-weight: 700;
        font-size: 1.25rem;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }

    .card-subtitle {
        color: #718096;
        font-size: 0.875rem;
        line-height: 1.4;
    }

    .form-label {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control,
    .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        transition: var(--transition-smooth);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        transform: translateY(-1px);
    }

    .btn-submit {
        border: none;
        border-radius: 10px;
        padding: 0.875rem 1.5rem;
        font-weight: 600;
        font-size: 0.95rem;
        transition: var(--transition-smooth);
        position: relative;
        overflow: hidden;
        z-index: 1;
    }

    .btn-submit.harian {
        background: var(--primary-gradient);
        color: white;
    }

    .btn-submit.ekskul {
        background: var(--success-gradient);
        color: white;
    }

    .btn-submit::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.1);
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        z-index: -1;
    }

    .btn-submit:hover::before {
        transform: translateX(0);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .stats-badge {
        background: linear-gradient(135deg, #f6f9ff 0%, #f1f5f9 100%);
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.5rem 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: #4a5568;
        font-size: 0.875rem;
    }

    .stats-badge .bi {
        font-size: 1rem;
    }

    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem 0;
            margin: -1.5rem -1.5rem 2rem -1.5rem;
        }

        .card-icon-wrapper {
            width: 56px;
            height: 56px;
        }

        .card-icon-wrapper .bi {
            font-size: 1.5rem;
        }
    }
</style>

<div class="container-fluid px-0">
    <!-- Header Section -->
    <div class="page-header px-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h3><i class="bi bi-bar-chart-line me-3"></i>Laporan & Analisis Absensi</h3>
                    <p class="lead mb-0">Monitor dan analisis data absensi harian & ekskul dengan visualisasi yang intuitif</p>
                </div>
                <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                    <div class="stats-badge">
                        <i class="bi bi-calendar3"></i>
                        <span><?= date('d F Y') ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4">
        <div class="row g-4">
            <!-- Laporan Harian -->
            <div class="col-xl-6">
                <div class="report-card harian">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start mb-4">
                            <div class="card-icon-wrapper harian me-3">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="card-title">Laporan Absensi Harian</h4>
                                <p class="card-subtitle mb-0">
                                    Pantau kehadiran siswa per hari dengan detail status (hadir, terlambat, izin, alpha)
                                </p>
                            </div>
                        </div>

                        <?php if (session()->getFlashdata('error_harian')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <?= session()->getFlashdata('error_harian') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="<?= smart_url('absensi/laporan/hasil') ?>" method="post" class="mt-4">
                            <input type="hidden" name="jenis" value="harian">

                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="bi bi-calendar-date me-2"></i>Pilih Tanggal
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-calendar3 text-primary"></i>
                                    </span>
                                    <input type="date" name="tanggal" class="form-control border-start-0 ps-3" required
                                        value="<?= date('Y-m-d') ?>">
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    Pilih tanggal untuk melihat laporan kehadiran harian
                                </small>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn-submit harian">
                                    <i class="bi bi-eye me-2"></i>
                                    Tampilkan Laporan Harian
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Laporan Ekskul Bulanan -->
            <div class="col-xl-6">
                <div class="report-card ekskul">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-start mb-4">
                            <div class="card-icon-wrapper ekskul me-3">
                                <i class="bi bi-trophy"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h4 class="card-title">Laporan Ekskul Bulanan</h4>
                                <p class="card-subtitle mb-0">
                                    Analisis kehadiran ekskul per bulan berdasarkan jadwal pertemuan
                                </p>
                            </div>
                        </div>

                        <?php if (session()->getFlashdata('error_ekskul')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle me-2"></i>
                                <?= session()->getFlashdata('error_ekskul') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form action="<?= smart_url('absensi/laporan/ekskulBulanan') ?>" method="get" class="mt-4">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="bi bi-award me-2"></i>Pilih Ekskul
                                    </label>
                                    <select name="ekskul_id" class="form-select" required>
                                        <option value="" disabled selected>-- Pilih Ekskul --</option>
                                        <?php foreach ($ekskul as $e): ?>
                                            <option value="<?= $e['id'] ?>">
                                                <?= esc($e['nama_ekskul']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-calendar-month me-2"></i>Periode Bulan
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-calendar text-success"></i>
                                        </span>
                                        <input type="month" name="bulan" class="form-control border-start-0 ps-3"
                                            value="<?= date('Y-m') ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label d-block">&nbsp;</label>
                                    <button type="submit" class="btn-submit ekskul w-100">
                                        <i class="bi bi-graph-up me-2"></i>
                                        Tampilkan Laporan
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="mt-4 pt-3 border-top">
                            <h6 class="mb-3"><i class="bi bi-info-circle me-2"></i>Fitur Laporan Ekskul</h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <small>4-5 Pertemuan/Bulan</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="bi bi-check-circle-fill text-success me-2"></i>
                                        <small>Export PDF Support</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats & Info -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="bi bi-clock-history text-primary fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Data Real-time</h6>
                                        <p class="text-muted mb-0 small">Update otomatis setiap absensi baru</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="bi bi-file-earmark-pdf text-success fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Export PDF</h6>
                                        <p class="text-muted mb-0 small">Ekspor laporan dalam format PDF</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                        <i class="bi bi-funnel text-info fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Filter Lengkap</h6>
                                        <p class="text-muted mb-0 small">Filter berdasarkan tanggal & ekskul</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>