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

    /* --- NEW: Professional Dashboard Styles --- */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    @media (min-width: 1024px) {
        .dashboard-grid {
            grid-template-columns: 2fr 1fr;
        }
    }

    .attendance-highlight {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: var(--radius);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .quick-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
        box-shadow: var(--shadow-sm);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-lg);
    }

    .stat-number {
        font-size: 1.75rem;
        font-weight: 800;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .live-indicator {
        display: inline-block;
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        margin-right: 0.5rem;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }

        100% {
            opacity: 1;
        }
    }

    .progress-thin {
        height: 6px;
        border-radius: 3px;
    }

    .feature-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 1rem;
    }

    /* --- Bug Fixes --- */
    .table-responsive {
        border-radius: 0 0 var(--radius) var(--radius);
    }

    .sticky-top {
        position: sticky;
        top: 0;
        background: white;
        z-index: 10;
    }
</style>

<div class="container-fluid py-4 px-3 px-md-4">

    <!-- 1. HEADER & WELCOME SECTION -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="pro-hero-card position-relative overflow-hidden p-4 p-md-5 animate__animated animate__fadeInDown">
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
                            <span class="hero-online-indicator"></span>
                        </div>
                        <div>
                            <h2 class="mb-1 hero-title">
                                Selamat Datang, <?= esc(session('nama') ?? 'Administrator') ?>!
                            </h2>

                            <p class="mb-0 hero-subtitle">
                                <span class="live-indicator"></span> Sistem monitoring real-time aktivitas sekolah
                            </p>

                            <!-- IT Helpdesk WA -->
                            <a href="https://wa.me/6285712345678?text=Halo%20IT%20Helpdesk,%20saya%20butuh%20bantuan%20terkait%20sistem."
                                class="it-helpdesk-link"
                                target="_blank">
                                <i class="fas fa-headset mr-1"></i> IT Helpdesk: Zulfiqri, S.Kom
                            </a>

                            <style>
                                /* Warna selaras gradasi header */
                                .it-helpdesk-link {
                                    display: inline-block;
                                    margin-top: 3px;
                                    font-size: 0.9rem;
                                    color: rgba(255, 255, 255, 0.85);
                                    font-weight: 500;
                                    text-decoration: none;
                                    transition: 0.2s;
                                }

                                .it-helpdesk-link i {
                                    color: rgba(255, 255, 255, 0.9);
                                }

                                /* Hover lembut */
                                .it-helpdesk-link:hover {
                                    color: #ffffff;
                                    transform: translateX(3px);
                                    text-decoration: underline;
                                }
                            </style>

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

        .hero-deco-blur {
            position: absolute;
            inset: 0;
            backdrop-filter: blur(40px);
            opacity: .15;
            z-index: 0;
        }

        .hero-avatar {
            width: 72px;
            height: 72px;
            object-fit: cover;
            border-radius: 16px;
            border: 3px solid rgba(255, 255, 255, 0.35);
            position: relative;
            z-index: 2;
        }

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

        .hero-title {
            font-size: 1.4rem;
            font-weight: 700;
        }

        .hero-subtitle {
            font-size: 0.95rem;
            color: #e2e8f0;
        }

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

    <!-- 2. ABSENSI HIGHLIGHT SECTION - NOW AT THE TOP -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="attendance-highlight">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="fw-bold mb-2"><i class="fa-solid fa-qrcode me-2"></i>Monitoring Absensi Real-time</h3>
                        <p class="mb-0 opacity-75">Pantau kehadiran siswa dan guru secara langsung dengan sistem QR Code</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                            <a href="<?= base_url('absensi/scan-camera') ?>" class="btn btn-light btn-sm rounded-pill px-3 fw-bold">
                                <i class="fa-solid fa-camera me-1"></i> Scan QR
                            </a>
                            <a href="<?= base_url('absensi/generate') ?>" class="btn btn-outline-light btn-sm rounded-pill px-3 fw-bold">
                                <i class="fa-solid fa-qrcode me-1"></i> Generate QR
                            </a>
                            <a href="<?= base_url('absensi/riwayat') ?>" class="btn btn-outline-light btn-sm rounded-pill px-3 fw-bold">
                                <i class="fa-solid fa-history me-1"></i> Riwayat
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. QUICK STATS GRID - ABSENSI FOCUSED -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="quick-stats-grid">
                <div class="stat-card">
                    <div class="stat-number text-success" id="valHadir"><?= $hadir ?? 0 ?></div>
                    <div class="stat-label">Hadir</div>
                    <div class="progress progress-thin mt-2">
                        <div class="progress-bar bg-success" style="width: <?= ($hadir ?? 0) > 0 ? min(($hadir / ($hadir + $telat + $izin + $sakit + $pulang_awal)) * 100, 100) : 0 ?>%"></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-number text-warning" id="valTelat"><?= $telat ?? 0 ?></div>
                    <div class="stat-label">Terlambat</div>
                    <div class="progress progress-thin mt-2">
                        <div class="progress-bar bg-warning" style="width: <?= ($telat ?? 0) > 0 ? min(($telat / ($hadir + $telat + $izin + $sakit + $pulang_awal)) * 100, 100) : 0 ?>%"></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-number text-info" id="valIzin"><?= $izin ?? 0 ?></div>
                    <div class="stat-label">Izin</div>
                    <div class="progress progress-thin mt-2">
                        <div class="progress-bar bg-info" style="width: <?= ($izin ?? 0) > 0 ? min(($izin / ($hadir + $telat + $izin + $sakit + $pulang_awal)) * 100, 100) : 0 ?>%"></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-number text-primary" id="valSakit"><?= $sakit ?? 0 ?></div>
                    <div class="stat-label">Sakit</div>
                    <div class="progress progress-thin mt-2">
                        <div class="progress-bar bg-primary" style="width: <?= ($sakit ?? 0) > 0 ? min(($sakit / ($hadir + $telat + $izin + $sakit + $pulang_awal)) * 100, 100) : 0 ?>%"></div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-number text-danger" id="valPulang"><?= $pulang_awal ?? 0 ?></div>
                    <div class="stat-label">Pulang Awal</div>
                    <div class="progress progress-thin mt-2">
                        <div class="progress-bar bg-danger" style="width: <?= ($pulang_awal ?? 0) > 0 ? min(($pulang_awal / ($hadir + $telat + $izin + $sakit + $pulang_awal)) * 100, 100) : 0 ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. CONTROL BAR -->
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

    <!-- 5. MAIN DASHBOARD CONTENT -->
    <div class="dashboard-grid">

        <!-- LEFT COLUMN: CHART & TABLE -->
        <div class="main-content-column">

            <!-- Table Section - ABSENSI FIRST -->
            <div class="pro-card mb-4">
                <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white sticky-top rounded-top">
                    <div>
                        <h5 class="fw-bold mb-0"><i class="fa-solid fa-list-check me-2 text-success"></i> Log Absensi Hari Ini</h5>
                        <small class="text-muted"><?= date('l, d F Y') ?> &bull; <span class="text-success"><span class="live-indicator"></span> Real-time Update</span></small>
                    </div>
                    <div class="text-muted small">
                        Total: <span id="totalRecords"><?= count($rekap ?? []) ?></span> records
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

            <!-- Chart Section -->
            <div class="pro-card p-4">
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

        </div>

        <!-- RIGHT COLUMN: QUICK OVERVIEW -->
        <div class="sidebar-column">

            <!-- Finance Card -->
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

                    <i class="fa-solid fa-sack-dollar position-absolute text-white opacity-10" style="font-size: 8rem; right: -20px; bottom: -30px; z-index: -1;"></i>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="pro-card mb-4">
                <div class="p-3 border-bottom bg-light">
                    <h6 class="fw-bold mb-0">🚀 Quick Actions</h6>
                </div>
                <div class="p-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="<?= base_url('absensi/scan-camera') ?>" class="btn btn-outline-primary w-100 py-2 d-flex flex-column align-items-center">
                                <i class="fa-solid fa-camera fs-5 mb-1"></i>
                                <small>Scan QR</small>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?= base_url('absensi/generate') ?>" class="btn btn-outline-success w-100 py-2 d-flex flex-column align-items-center">
                                <i class="fa-solid fa-qrcode fs-5 mb-1"></i>
                                <small>Generate QR</small>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?= base_url('siswa') ?>" class="btn btn-outline-info w-100 py-2 d-flex flex-column align-items-center">
                                <i class="fa-solid fa-users fs-5 mb-1"></i>
                                <small>Data Siswa</small>
                            </a>
                        </div>
                        <div class="col-6">
                            <a href="<?= base_url('absensi/riwayat') ?>" class="btn btn-outline-warning w-100 py-2 d-flex flex-column align-items-center">
                                <i class="fa-solid fa-history fs-5 mb-1"></i>
                                <small>Riwayat</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Savers List -->
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
     INTERACTIVE JAVASCRIPT - UPGRADED
     ======================================================= -->
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // -----------------------------------------------------
        // 1. CHART.JS CONFIGURATION
        // -----------------------------------------------------
        const ctx = document.getElementById('chartTabungan').getContext('2d');
        const chartRawData = <?= json_encode($chartData ?? array_fill(0, 12, 0)) ?>;

        // Create Gradient
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(67, 97, 238, 0.5)');
        gradient.addColorStop(1, 'rgba(67, 97, 238, 0.0)');

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
                    tension: 0.4
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
        // 2. AJAX FILTER LOGIC - BUG FIXED
        // -----------------------------------------------------
        const filterJurusan = document.getElementById('filterJurusan');
        const filterKelas = document.getElementById('filterKelas');
        const btnRefresh = document.getElementById('btnRefreshDashboard');

        // Dynamic Kelas options based on Jurusan - FIXED
        filterJurusan.addEventListener('change', function() {
            const selectedJurusan = this.value;
            const options = filterKelas.options;

            for (let i = 0; i < options.length; i++) {
                const opt = options[i];
                const dataJurusan = opt.getAttribute('data-jurusan');
                if (selectedJurusan === 'all' || !dataJurusan || dataJurusan === selectedJurusan) {
                    opt.style.display = 'block';
                } else {
                    opt.style.display = 'none';
                }
            }
            filterKelas.value = "";
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
            btnRefresh.disabled = true;

            // FIXED: Use proper URL construction
            const baseUrl = '<?= base_url() ?>';
            fetch(`${baseUrl}dashboard/absensiAjax?jurusan=${encodeURIComponent(jurusan)}&kelas=${encodeURIComponent(kelas)}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network response was not ok');
                    return response.json();
                })
                .then(data => {
                    // Update Counters with Animation
                    animateNumber("valHadir", parseInt(document.getElementById("valHadir").innerText) || 0, data.counts.masuk || 0);
                    animateNumber("valTelat", parseInt(document.getElementById("valTelat").innerText) || 0, data.counts.terlambat || 0);
                    animateNumber("valIzin", parseInt(document.getElementById("valIzin").innerText) || 0, data.counts.izin || 0);
                    animateNumber("valSakit", parseInt(document.getElementById("valSakit").innerText) || 0, data.counts.sakit || 0);
                    animateNumber("valPulang", parseInt(document.getElementById("valPulang").innerText) || 0, data.counts.pulang_awal || 0);

                    // Update Table Content
                    renderTable(data.rekap || []);

                    // Update progress bars
                    updateProgressBars(data.counts);
                })
                .catch(err => {
                    console.error("Error fetching data:", err);
                    showNotification('Gagal memuat data terbaru. Periksa koneksi internet.', 'error');
                })
                .finally(() => {
                    btnRefresh.innerHTML = originalBtnHtml;
                    btnRefresh.disabled = false;
                });
        }

        // -----------------------------------------------------
        // 3. HELPER FUNCTIONS - IMPROVED
        // -----------------------------------------------------
        function animateNumber(id, start, end) {
            if (start === end) return;
            const obj = document.getElementById(id);
            const duration = 800;
            let startTimestamp = null;

            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                const value = Math.floor(progress * (end - start) + start);
                obj.innerHTML = value;
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        function updateProgressBars(counts) {
            const total = counts.masuk + counts.terlambat + counts.izin + counts.sakit + counts.pulang_awal;

            if (total > 0) {
                document.querySelector('.progress-bar.bg-success').style.width = `${(counts.masuk / total) * 100}%`;
                document.querySelector('.progress-bar.bg-warning').style.width = `${(counts.terlambat / total) * 100}%`;
                document.querySelector('.progress-bar.bg-info').style.width = `${(counts.izin / total) * 100}%`;
                document.querySelector('.progress-bar.bg-primary').style.width = `${(counts.sakit / total) * 100}%`;
                document.querySelector('.progress-bar.bg-danger').style.width = `${(counts.pulang_awal / total) * 100}%`;
            }
        }

        function renderTable(data) {
            const tbody = document.getElementById('bodyRekapAbsensi');
            const totalRecords = document.getElementById('totalRecords');

            tbody.innerHTML = '';
            totalRecords.textContent = data.length;

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
                let badgeClass = 'bg-secondary bg-opacity-10 text-secondary';
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

                const delay = index * 50;
                const initial = r.nama ? r.nama.charAt(0).toUpperCase() : '?';

                const row = `
                    <tr class="animate__animated animate__fadeIn" style="animation-delay: ${delay}ms">
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-sm">${initial}</div>
                                <div>
                                    <div class="fw-bold text-dark">${r.nama || 'N/A'}</div>
                                    <div class="text-xs text-muted">${r.user_type ? r.user_type.charAt(0).toUpperCase() + r.user_type.slice(1) : 'Unknown'}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge ${r.user_type === 'siswa' ? 'bg-light text-dark border' : 'bg-dark text-white border'}">
                                ${r.user_type === 'siswa' ? 'Siswa' : (r.user_type === 'guru' ? 'Guru' : 'Unknown')}
                            </span>
                        </td>
                        <td>${r.kelas || '-'}</td>
                        <td class="font-monospace text-dark fw-bold">${r.jam_masuk || '--:--'}</td>
                        <td class="text-end pe-4">
                            <span class="badge ${badgeClass} px-3 py-2 rounded-pill">
                                <i class="fa-solid ${iconClass} me-1"></i> ${r.status ? r.status.toUpperCase() : 'UNKNOWN'}
                            </span>
                        </td>
                    </tr>
                `;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        }

        function showNotification(message, type = 'info') {
            // Simple notification system
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 5000);
        }

        // -----------------------------------------------------
        // 4. AUTO REFRESH EVERY 30 SECONDS
        // -----------------------------------------------------
        setInterval(() => {
            if (!btnRefresh.disabled) {
                fetchDashboardData();
            }
        }, 30000);

        // -----------------------------------------------------
        // 5. REAL-TIME FEATURES
        // -----------------------------------------------------
        // Add click handlers for quick actions
        document.querySelectorAll('.quick-actions .btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                // Add loading state to clicked button
                const originalHtml = this.innerHTML;
                this.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> Loading...';
                this.disabled = true;

                setTimeout(() => {
                    this.innerHTML = originalHtml;
                    this.disabled = false;
                }, 1000);
            });
        });

    });
</script>

<?= $this->endSection(); ?>