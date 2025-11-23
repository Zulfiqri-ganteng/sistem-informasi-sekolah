<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>
<?php
$foto = session('foto');
$path = FCPATH . 'uploads/admin/' . $foto;

if (!empty($foto) && file_exists($path)) {
    $fotoUrl = base_url('uploads/admin/' . $foto);
} else {
    $fotoUrl = 'https://ui-avatars.com/api/?name=' . urlencode(session('nama') ?? 'Admin') . '&background=random&color=fff&size=128';
}
?>

<!-- =======================================================
     ASSETS & DEPENDENCIES
     ======================================================= -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- =======================================================
     CUSTOM STYLES (Modern Executive Look)
     ======================================================= -->
<style>
    :root {
        --primary: #4361ee;
        --secondary: #3f37c9;
        --success: #4cc9f0;
        --info: #4895ef;
        --warning: #f72585;
        --dark: #0f172a;
        --light: #f8fafc;
        --card-bg: #ffffff;
        --radius: 16px;
        --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: #f1f5f9;
        color: #334155;
    }

    /* --- Utilities --- */
    .fw-medium {
        font-weight: 500;
    }

    .fw-bold {
        font-weight: 700;
    }

    .text-xs {
        font-size: 0.75rem;
    }

    .text-sm {
        font-size: 0.875rem;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    /* --- Modern Cards --- */
    .pro-card {
        background: var(--card-bg);
        border-radius: var(--radius);
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: var(--shadow-md);
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
        overflow: hidden;
    }

    .pro-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }

    /* --- Hero Section --- */
    .hero-bg {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        border-radius: var(--radius);
        color: white;
        position: relative;
        z-index: 1;
        box-shadow: 0 20px 40px -10px rgba(67, 97, 238, 0.4);
    }

    .hero-bg::after {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        z-index: -1;
        opacity: 0.5;
    }

    /* --- Status Pills --- */
    .stat-pill {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-radius: 12px;
        background: white;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }

    .stat-pill:hover {
        border-color: var(--primary);
        background: #f8fafc;
    }

    .stat-icon-box {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 1rem;
    }

    /* --- Finance Wallet --- */
    .wallet-card {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
        color: white;
    }

    /* --- Table Styles --- */
    .table-pro th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        color: #64748b;
        background-color: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
        padding: 1rem;
    }

    .table-pro td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
    }

    .avatar-sm {
        width: 32px;
        height: 32px;
        background: #e2e8f0;
        color: #64748b;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 10px;
    }

    /* --- Animations --- */
    .spin-fast {
        animation: fa-spin 1s infinite linear;
    }
</style>

<div class="container-fluid py-4 px-3 px-md-4">

    <!-- 1. HEADER & WELCOME SECTION (UPGRADED PREMIUM DESIGN) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="pro-hero-card position-relative overflow-hidden p-4 p-md-5 animate__animated animate__fadeInDown">

                <!-- Background Decorative Elements -->
                <div class="hero-deco-circle hero-deco-1"></div>
                <div class="hero-deco-circle hero-deco-2"></div>
                <div class="hero-deco-blur"></div>

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">

                    <!-- LEFT: PROFILE -->
                    <div class="d-flex align-items-center gap-4">
                        <div class="position-relative">
                            <img src="<?= $fotoUrl ?>"
                                class="hero-avatar shadow-lg"
                                alt="Profile">

                            <!-- Online indicator -->
                            <span class="hero-online-indicator"></span>
                        </div>

                        <div>
                            <h2 class="mb-1 hero-title">
                                Selamat Datang, <?= esc(session('nama') ?? 'Administrator') ?>!
                            </h2>
                            <p class="mb-0 hero-subtitle">
                                Ringkasan aktivitas & statistik sekolah hari ini.
                            </p>
                        </div>
                    </div>

                    <!-- RIGHT: STATISTICS -->
                    <div class="d-none d-md-flex gap-4 text-center">
                        <div class="hero-stat-box">
                            <h4 class="hero-stat-number"><?= $jumlahSiswa ?? 0 ?></h4>
                            <small class="hero-stat-label">Siswa</small>
                        </div>

                        <div class="vr hero-divider"></div>

                        <div class="hero-stat-box">
                            <h4 class="hero-stat-number"><?= $jumlahGuru ?? 0 ?></h4>
                            <small class="hero-stat-label">Guru</small>
                        </div>

                        <div class="vr hero-divider"></div>

                        <div class="hero-stat-box">
                            <h4 class="hero-stat-number"><?= $jumlahKelas ?? 0 ?></h4>
                            <small class="hero-stat-label">Kelas</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- EXTRA CSS FOR PREMIUM HEADER -->
    <style>
        .pro-hero-card {
            background: linear-gradient(135deg, #4361ee, #3a0ca3);
            border-radius: 22px;
            position: relative;
            color: #fff;
            box-shadow: 0 15px 40px -10px rgba(67, 97, 238, .35);
        }

        /* Decorative circles */
        .hero-deco-circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.25;
            filter: blur(1px);
        }

        .hero-deco-1 {
            width: 180px;
            height: 180px;
            background: #4cc9f0;
            top: -40px;
            right: -40px;
        }

        .hero-deco-2 {
            width: 140px;
            height: 140px;
            background: #f72585;
            bottom: -30px;
            left: -30px;
        }

        /* Blur background */
        .hero-deco-blur {
            position: absolute;
            inset: 0;
            backdrop-filter: blur(40px);
            opacity: .15;
            z-index: 0;
        }

        /* Avatar */
        .hero-avatar {
            width: 72px;
            height: 72px;
            object-fit: cover;
            border-radius: 16px;
            border: 3px solid rgba(255, 255, 255, 0.35);
            position: relative;
            z-index: 2;
        }

        /* Online status */
        .hero-online-indicator {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 16px;
            height: 16px;
            background: #32d657;
            border: 3px solid #fff;
            border-radius: 50%;
            z-index: 3;
        }

        /* Text style */
        .hero-title {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .hero-subtitle {
            font-size: 0.95rem;
            color: #e2e8f0;
        }

        /* Stats */
        .hero-stat-box h4 {
            margin-bottom: 0;
        }

        .hero-stat-number {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .hero-stat-label {
            text-transform: uppercase;
            opacity: 0.7;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .hero-divider {
            opacity: .5;
            height: 48px;
            align-self: center;
        }

        /* MOBILE OPTIMIZATION */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 1.2rem;
            }

            .hero-avatar {
                width: 62px;
                height: 62px;
                border-radius: 12px;
            }

            .hero-stat-number {
                font-size: 1.2rem;
            }
        }
    </style>


    <!-- 2. CONTROL BAR (Filter & Actions) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="pro-card p-3 d-flex flex-wrap gap-3 align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3 flex-grow-1">
                    <div class="d-flex align-items-center text-muted fw-bold small">
                        <i class="fa-solid fa-filter me-2 text-primary"></i> FILTER DATA:
                    </div>
                    <select id="filterJurusan" class="form-select form-select-sm border-0 bg-light" style="max-width: 200px; font-weight: 500;">
                        <option value="all">🔍 Semua Jurusan</option>
                        <?php if (!empty($jurusanList)): foreach ($jurusanList as $j): ?>
                                <option value="<?= esc($j) ?>" <?= (isset($selectedJurusan) && $selectedJurusan === $j) ? 'selected' : '' ?>><?= esc($j) ?></option>
                        <?php endforeach;
                        endif; ?>
                    </select>
                    <select id="filterKelas" class="form-select form-select-sm border-0 bg-light" style="max-width: 200px; font-weight: 500;">
                        <option value="">🏫 Semua Kelas</option>
                        <?php
                        $seen = [];
                        if (!empty($kelasList)): foreach ($kelasList as $k):
                                $kelasName = is_array($k) ? ($k['kelas'] ?? ($k['nama_kelas'] ?? '')) : (is_object($k) ? ($k->kelas ?? '') : $k);
                                $jurusanFor = is_array($k) ? ($k['jurusan'] ?? '') : (is_object($k) ? ($k->jurusan ?? '') : '');
                                if (empty($kelasName) || in_array($kelasName, $seen)) continue;
                                $seen[] = $kelasName;
                        ?>
                                <option data-jurusan="<?= esc($jurusanFor) ?>" value="<?= esc($kelasName) ?>" <?= (isset($selectedKelas) && $selectedKelas === $kelasName) ? 'selected' : '' ?>>
                                    <?= esc($kelasName) ?>
                                </option>
                        <?php endforeach;
                        endif; ?>
                    </select>
                </div>
                <div>
                    <button id="btnRefreshDashboard" class="btn btn-primary rounded-pill px-4 shadow-sm btn-sm fw-bold">
                        <i class="fa-solid fa-rotate me-2"></i> Refresh Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. MAIN DASHBOARD CONTENT -->
    <div class="row g-4">

        <!-- LEFT COLUMN: CHART & TABLE (The heavy data) -->
        <div class="col-xl-8 col-lg-7">

            <!-- Chart Section -->
            <div class="pro-card mb-4 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold mb-0">Tren Tabungan Siswa</h5>
                        <small class="text-muted">Analisis pemasukan tabungan tahun <?= date('Y') ?></small>
                    </div>
                    <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                        <i class="fa-solid fa-chart-line me-1"></i> Grafik Tahunan
                    </div>
                </div>
                <div style="height: 300px; width: 100%;">
                    <canvas id="chartTabungan"></canvas>
                </div>
            </div>

            <!-- Table Section -->
            <div class="pro-card">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white sticky-top rounded-top">
                    <div>
                        <h5 class="fw-bold mb-0"><i class="fa-solid fa-list-check me-2 text-success"></i> Log Absensi Hari Ini</h5>
                        <small class="text-muted"><?= date('l, d F Y') ?> &bull; <span class="text-success">Realtime Update</span></small>
                    </div>
                </div>
                <div class="table-responsive" style="max-height: 450px; overflow-y: auto;">
                    <table class="table table-pro w-100 mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Nama Lengkap</th>
                                <th>Status</th>
                                <th>Kelas</th>
                                <th>Jam Masuk</th>
                                <th class="text-end pe-4">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="bodyRekapAbsensi">
                            <?php if (!empty($rekap)): foreach ($rekap as $r): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="avatar-sm">
                                                    <?= strtoupper(substr($r['nama'], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark"><?= esc($r['nama']) ?></div>
                                                    <div class="text-xs text-muted"><?= esc(ucfirst($r['user_type'])) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($r['user_type'] == 'siswa'): ?>
                                                <span class="badge bg-light text-dark border">Siswa</span>
                                            <?php else: ?>
                                                <span class="badge bg-dark text-white border">Guru</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc($r['kelas'] ?? '-') ?></td>
                                        <td class="font-monospace text-dark fw-bold"><?= esc($r['jam_masuk'] ?? '--:--') ?></td>
                                        <td class="text-end pe-4">
                                            <?php
                                            $badges = [
                                                'masuk' => ['bg' => 'success', 'icon' => 'check-circle'],
                                                'terlambat' => ['bg' => 'warning', 'icon' => 'clock'],
                                                'izin' => ['bg' => 'info', 'icon' => 'envelope-open-text'],
                                                'sakit' => ['bg' => 'primary', 'icon' => 'notes-medical'],
                                                'pulang_awal' => ['bg' => 'danger', 'icon' => 'person-walking-arrow-right']
                                            ];
                                            $st = $badges[$r['status']] ?? ['bg' => 'secondary', 'icon' => 'minus'];
                                            ?>
                                            <span class="badge bg-<?= $st['bg'] ?> bg-opacity-10 text-<?= $st['bg'] ?> px-3 py-2 rounded-pill">
                                                <i class="fa-solid fa-<?= $st['icon'] ?> me-1"></i> <?= strtoupper($r['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach;
                            else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <img src="https://cdn-icons-png.flaticon.com/512/7486/7486747.png" width="60" class="mb-3 opacity-50" alt="Empty">
                                        <p class="mb-0">Belum ada data absensi yang terekam.</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN: SNAPSHOTS (The quick overview) -->
        <div class="col-xl-4 col-lg-5">

            <!-- 1. Finance Card (Wallet Style) -->
            <div class="pro-card wallet-card mb-4 overflow-hidden">
                <div class="p-4 position-relative z-1">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <small class="text-white-50 text-uppercase fw-bold ls-1">Total Saldo Tabungan</small>
                            <h2 class="fw-bold mt-1 mb-0">Rp <?= number_format($totalTabungan ?? 0, 0, ',', '.') ?></h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-2 d-flex justify-content-center align-items-center" style="width:48px; height:48px;">
                            <i class="fa-solid fa-wallet text-white fs-5"></i>
                        </div>
                    </div>

                    <div class="row g-0 pt-3 border-top border-white border-opacity-25">
                        <div class="col-6 border-end border-white border-opacity-25 pe-3">
                            <small class="text-white-50 d-block mb-1">Masuk Hari Ini</small>
                            <div class="fw-bold">Rp <?= number_format($penerimaanHari ?? 0, 0, ',', '.') ?></div>
                        </div>
                        <div class="col-6 ps-3">
                            <small class="text-white-50 d-block mb-1">Total Transaksi</small>
                            <div class="fw-bold"><?= $transaksiBulan ?? 0 ?> <span class="fw-normal text-xs">Bulan ini</span></div>
                        </div>
                    </div>

                    <!-- Decor -->
                    <i class="fa-solid fa-sack-dollar position-absolute text-white opacity-10" style="font-size: 8rem; right: -20px; bottom: -30px; z-index: -1;"></i>
                </div>
            </div>

            <!-- 2. Attendance Quick Stats -->
            <h6 class="fw-bold text-muted text-uppercase ls-1 mb-3 ms-1">Statistik Kehadiran</h6>
            <div class="row g-3 mb-4">
                <div class="col-6">
                    <div class="stat-pill border-start border-4 border-success">
                        <div class="stat-icon-box bg-success bg-opacity-10 text-success">
                            <i class="fa-solid fa-user-check"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold" id="valHadir"><?= $hadir ?? 0 ?></h4>
                            <small class="text-muted text-xs">Hadir</small>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="stat-pill border-start border-4 border-warning">
                        <div class="stat-icon-box bg-warning bg-opacity-10 text-warning">
                            <i class="fa-solid fa-user-clock"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold" id="valTelat"><?= $telat ?? 0 ?></h4>
                            <small class="text-muted text-xs">Telat</small>
                        </div>
                    </div>
                </div>
                <!-- Mini Grid for others -->
                <div class="col-4">
                    <div class="pro-card p-2 text-center py-3">
                        <h5 class="fw-bold text-info mb-0" id="valIzin"><?= $izin ?? 0 ?></h5>
                        <small class="text-muted text-xs fw-bold">IZIN</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="pro-card p-2 text-center py-3">
                        <h5 class="fw-bold text-primary mb-0" id="valSakit"><?= $sakit ?? 0 ?></h5>
                        <small class="text-muted text-xs fw-bold">SAKIT</small>
                    </div>
                </div>
                <div class="col-4">
                    <div class="pro-card p-2 text-center py-3">
                        <h5 class="fw-bold text-danger mb-0" id="valPulang"><?= $pulang_awal ?? 0 ?></h5>
                        <small class="text-muted text-xs fw-bold">PLG.AWAL</small>
                    </div>
                </div>
            </div>

            <!-- 3. Top Savers List -->
            <div class="pro-card">
                <div class="p-3 border-bottom bg-light d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0">🏆 Top 5 Penabung</h6>
                    <small class="text-muted">Rank</small>
                </div>
                <div class="p-2">
                    <?php if (!empty($topSavers)): foreach ($topSavers as $i => $s): ?>
                            <div class="d-flex align-items-center justify-content-between p-2 rounded hover-bg-light mb-1">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm <?= $i == 0 ? 'bg-warning text-dark' : ($i == 1 ? 'bg-secondary text-white' : 'bg-white text-muted border') ?>" style="width: 32px; height: 32px;">
                                        <?= $i + 1 ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark text-sm"><?= esc($s['nama']) ?></div>
                                        <small class="text-muted text-xs"><?= esc($s['kelas']) ?></small>
                                    </div>
                                </div>
                                <div class="fw-bold text-success text-sm">
                                    Rp <?= number_format($s['saldo'], 0, ',', '.') ?>
                                </div>
                            </div>
                        <?php endforeach;
                    else: ?>
                        <div class="text-center text-muted small py-3">Belum ada data tabungan.</div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- =======================================================
     INTERACTIVE JAVASCRIPT
     ======================================================= -->
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // -----------------------------------------------------
        // 1. CHART.JS CONFIGURATION (Gradient & Smooth)
        // -----------------------------------------------------
        const ctx = document.getElementById('chartTabungan').getContext('2d');
        const chartRawData = <?= json_encode($chartData ?? []) ?>;

        // Create Gradient
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(67, 97, 238, 0.5)'); // Top opacity
        gradient.addColorStop(1, 'rgba(67, 97, 238, 0.0)'); // Bottom transparency

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pemasukan Tabungan',
                    data: chartRawData,
                    borderColor: '#4361ee',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#4361ee',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: true,
                    tension: 0.4 // Smooth curves
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: {
                            family: 'Plus Jakarta Sans',
                            size: 13
                        },
                        bodyFont: {
                            family: 'Plus Jakarta Sans',
                            size: 14,
                            weight: 'bold'
                        },
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                let value = context.parsed.y;
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            borderDash: [4, 4],
                            color: '#e2e8f0'
                        },
                        ticks: {
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 11
                            },
                            callback: function(value) {
                                return (value / 1000) + 'k';
                            }
                        },
                        border: {
                            display: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 11
                            }
                        },
                        border: {
                            display: false
                        }
                    }
                }
            }
        });

        // -----------------------------------------------------
        // 2. AJAX FILTER LOGIC (Real-time update)
        // -----------------------------------------------------
        const filterJurusan = document.getElementById('filterJurusan');
        const filterKelas = document.getElementById('filterKelas');
        const btnRefresh = document.getElementById('btnRefreshDashboard');

        // Dynamic Kelas options based on Jurusan
        filterJurusan.addEventListener('change', function() {
            const selectedJurusan = this.value;
            const options = filterKelas.options;

            // Filter options in client-side for speed
            for (let i = 0; i < options.length; i++) {
                const opt = options[i];
                const dataJurusan = opt.getAttribute('data-jurusan');
                if (selectedJurusan === 'all' || !dataJurusan || dataJurusan === selectedJurusan) {
                    opt.style.display = 'block';
                } else {
                    opt.style.display = 'none';
                }
            }
            filterKelas.value = ""; // Reset kelas selection
            fetchDashboardData();
        });

        filterKelas.addEventListener('change', fetchDashboardData);
        btnRefresh.addEventListener('click', fetchDashboardData);

        function fetchDashboardData() {
            const jurusan = filterJurusan.value;
            const kelas = filterKelas.value;

            // UI Loading State
            const originalBtnHtml = btnRefresh.innerHTML;
            btnRefresh.innerHTML = '<i class="fa-solid fa-circle-notch spin-fast me-2"></i> Loading...';
            btnRefresh.classList.add('disabled');

            // GUNAKAN URL AJAX yang benar
            fetch(`<?= base_url('dashboard/absensiAjax') ?>?jurusan=${jurusan}&kelas=${kelas}`)
                .then(response => response.json())
                .then(data => {
                    // Update Counters with Animation
                    animateNumber("valHadir", parseInt(document.getElementById("valHadir").innerText), data.counts.masuk);
                    animateNumber("valTelat", parseInt(document.getElementById("valTelat").innerText), data.counts.terlambat);
                    animateNumber("valIzin", parseInt(document.getElementById("valIzin").innerText), data.counts.izin);
                    animateNumber("valSakit", parseInt(document.getElementById("valSakit").innerText), data.counts.sakit);
                    animateNumber("valPulang", parseInt(document.getElementById("valPulang").innerText), data.counts.pulang_awal);

                    // Update Table Content
                    renderTable(data.rekap);
                })
                .catch(err => {
                    console.error("Error fetching data:", err);
                    alert("Gagal memuat data terbaru. Periksa koneksi internet.");
                })
                .finally(() => {
                    btnRefresh.innerHTML = originalBtnHtml;
                    btnRefresh.classList.remove('disabled');
                });
        }

        // -----------------------------------------------------
        // 3. HELPER FUNCTIONS
        // -----------------------------------------------------
        function animateNumber(id, start, end) {
            if (start === end) return;
            const obj = document.getElementById(id);
            const duration = 1000; // 1 second
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                obj.innerHTML = Math.floor(progress * (end - start) + start);
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        function renderTable(data) {
            const tbody = document.getElementById('bodyRekapAbsensi');
            tbody.innerHTML = ''; // Clear current

            if (data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted animate__animated animate__fadeIn">
                            <i class="fa-regular fa-folder-open fa-2x mb-2 opacity-50"></i>
                            <p class="mb-0">Tidak ada data ditemukan untuk filter ini.</p>
                        </td>
                    </tr>`;
                return;
            }

            data.forEach((r, index) => {
                // Determine Badge Style
                let badgeClass = 'bg-secondary text-white';
                let iconClass = 'fa-minus';

                if (r.status === 'masuk') {
                    badgeClass = 'bg-success bg-opacity-10 text-success';
                    iconClass = 'fa-check-circle';
                } else if (r.status === 'terlambat') {
                    badgeClass = 'bg-warning bg-opacity-10 text-warning';
                    iconClass = 'fa-clock';
                } else if (r.status === 'izin') {
                    badgeClass = 'bg-info bg-opacity-10 text-info';
                    iconClass = 'fa-envelope-open-text';
                } else if (r.status === 'sakit') {
                    badgeClass = 'bg-primary bg-opacity-10 text-primary';
                    iconClass = 'fa-notes-medical';
                } else if (r.status === 'pulang_awal') {
                    badgeClass = 'bg-danger bg-opacity-10 text-danger';
                    iconClass = 'fa-person-walking-arrow-right';
                }

                const delay = index * 50; // Stagger effect
                const initial = r.nama.charAt(0).toUpperCase();

                const row = `
                    <tr class="animate__animated animate__fadeIn" style="animation-delay: ${delay}ms">
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-sm">${initial}</div>
                                <div>
                                    <div class="fw-bold text-dark">${r.nama}</div>
                                    <div class="text-xs text-muted">${r.user_type.charAt(0).toUpperCase() + r.user_type.slice(1)}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                             <span class="badge ${r.user_type === 'siswa' ? 'bg-light text-dark border' : 'bg-dark text-white border'}">${r.user_type === 'siswa' ? 'Siswa' : 'Guru'}</span>
                        </td>
                        <td>${r.kelas || '-'}</td>
                        <td class="font-monospace text-dark fw-bold">${r.jam_masuk || '--:--'}</td>
                        <td class="text-end pe-4">
                            <span class="badge ${badgeClass} px-3 py-2 rounded-pill">
                                <i class="fa-solid ${iconClass} me-1"></i> ${r.status.toUpperCase()}
                            </span>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        }
    });
</script>

<?= $this->endSection(); ?>