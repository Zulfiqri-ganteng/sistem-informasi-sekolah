<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    :root {
        --primary: #4361ee;
        --primary-light: #e6ebff;
        --secondary: #3f37c9;
        --success: #4cc9f0;
        --warning: #f72585;
        --info: #4895ef;
        --danger: #f94144;
        --dark: #212529;
        --light: #f8f9fa;
        --gray: #6c757d;
        --border-radius: 12px;
        --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .dashboard-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }

    .date-badge {
        background: var(--primary-light);
        color: var(--primary);
        padding: 8px 16px;
        border-radius: 30px;
        font-weight: 600;
        font-size: 14px;
    }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: #fff;
        border-radius: var(--border-radius);
        padding: 24px;
        box-shadow: var(--shadow);
        transition: var(--transition);
        border-left: 4px solid;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    }

    .stat-card.hadir {
        border-left-color: var(--success);
    }

    .stat-card.terlambat {
        border-left-color: var(--warning);
    }

    .stat-card.izin {
        border-left-color: var(--info);
    }

    .stat-card.sakit {
        border-left-color: var(--primary);
    }

    .stat-card.pulang-awal {
        border-left-color: var(--danger);
    }

    .stat-icon {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        opacity: 0.8;
    }

    .stat-card.hadir .stat-icon {
        background: rgba(76, 201, 240, 0.2);
        color: var(--success);
    }

    .stat-card.terlambat .stat-icon {
        background: rgba(247, 37, 133, 0.2);
        color: var(--warning);
    }

    .stat-card.izin .stat-icon {
        background: rgba(72, 149, 239, 0.2);
        color: var(--info);
    }

    .stat-card.sakit .stat-icon {
        background: rgba(67, 97, 238, 0.2);
        color: var(--primary);
    }

    .stat-card.pulang-awal .stat-icon {
        background: rgba(249, 65, 68, 0.2);
        color: var(--danger);
    }

    .stat-card h4 {
        font-size: 16px;
        margin: 0 0 10px 0;
        color: var(--gray);
        font-weight: 600;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 800;
        margin: 0;
    }

    .stat-card.hadir .stat-value {
        color: var(--success);
    }

    .stat-card.terlambat .stat-value {
        color: var(--warning);
    }

    .stat-card.izin .stat-value {
        color: var(--info);
    }

    .stat-card.sakit .stat-value {
        color: var(--primary);
    }

    .stat-card.pulang-awal .stat-value {
        color: var(--danger);
    }

    .rekap-card {
        background: #fff;
        border-radius: var(--border-radius);
        padding: 24px;
        box-shadow: var(--shadow);
        margin-bottom: 30px;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .card-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }

    .table-responsive {
        overflow: auto;
        border-radius: 10px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
    }

    .table thead th {
        background-color: var(--primary-light);
        color: var(--primary);
        font-weight: 600;
        padding: 15px;
        text-align: left;
        border: none;
        font-size: 14px;
    }

    .table tbody tr {
        border-bottom: 1px solid #f0f0f0;
        transition: var(--transition);
    }

    .table tbody tr:hover {
        background-color: #f8faff;
    }

    .table tbody td {
        padding: 15px;
        border: none;
        font-size: 14px;
    }

    .badge-status {
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
        display: inline-block;
    }

    .badge-terlambat {
        background: rgba(247, 37, 133, 0.1);
        color: var(--warning);
    }

    .badge-masuk {
        background: rgba(76, 201, 240, 0.1);
        color: var(--success);
    }

    .badge-izin {
        background: rgba(72, 149, 239, 0.1);
        color: var(--info);
    }

    .badge-sakit {
        background: rgba(67, 97, 238, 0.1);
        color: var(--primary);
    }

    .badge-pulang-awal {
        background: rgba(249, 65, 68, 0.1);
        color: var(--danger);
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--gray);
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .dashboard-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .stat-grid {
            grid-template-columns: 1fr;
        }

        .stat-card {
            padding: 20px;
        }

        .table-responsive {
            font-size: 12px;
        }

        .table thead th,
        .table tbody td {
            padding: 10px;
        }
    }
</style>

<div class="container-fluid">
    <div class="dashboard-header">
        <h1 class="dashboard-title">Dashboard Absensi</h1>
        <div class="date-badge">
            <i class="fas fa-calendar-alt me-2"></i>
            <?= date('d M Y', strtotime($today)) ?>
        </div>
    </div>

    <div class="stat-grid">
        <div class="stat-card hadir">
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <h4>Hadir</h4>
            <div class="stat-value"><?= esc($hadir) ?></div>
        </div>
        <div class="stat-card terlambat">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <h4>Terlambat</h4>
            <div class="stat-value"><?= esc($telat) ?></div>
        </div>
        <div class="stat-card izin">
            <div class="stat-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <h4>Izin</h4>
            <div class="stat-value"><?= esc($izin) ?></div>
        </div>
        <div class="stat-card sakit">
            <div class="stat-icon">
                <i class="fas fa-heartbeat"></i>
            </div>
            <h4>Sakit</h4>
            <div class="stat-value"><?= esc($sakit) ?></div>
        </div>
        <div class="stat-card pulang-awal">
            <div class="stat-icon">
                <i class="fas fa-home"></i>
            </div>
            <h4>Pulang Awal</h4>
            <div class="stat-value"><?= esc($pulang_awal) ?></div>
        </div>
    </div>

    <div class="rekap-card">
        <div class="card-header">
            <h3 class="card-title">Rekap Absensi Hari Ini</h3>
            <div class="card-actions">
                <button class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-download me-1"></i> Export
                </button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Jenis</th>
                        <th>Kelas</th>
                        <th>Masuk</th>
                        <th>Pulang</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($rekap)): ?>
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-clipboard-list"></i>
                                    <p>Belum ada data absensi hari ini.</p>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($rekap as $r): ?>
                            <tr>
                                <td><?= esc($r['nama']) ?></td>
                                <td><?= esc(ucfirst($r['user_type'])) ?></td>
                                <td><?= esc($r['kelas'] ?? '-') ?></td>
                                <td><?= esc($r['jam_masuk'] ?? '-') ?></td>
                                <td><?= esc($r['jam_pulang'] ?? '-') ?></td>
                                <td>
                                    <?php
                                    $statusClass = 'badge-masuk';
                                    if ($r['status'] === 'terlambat') {
                                        $statusClass = 'badge-terlambat';
                                    } elseif ($r['status'] === 'izin') {
                                        $statusClass = 'badge-izin';
                                    } elseif ($r['status'] === 'sakit') {
                                        $statusClass = 'badge-sakit';
                                    } elseif ($r['status'] === 'pulang_awal') {
                                        $statusClass = 'badge-pulang-awal';
                                    }
                                    ?>
                                    <span class="badge-status <?= $statusClass ?>">
                                        <?= esc(strtoupper($r['status'])) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>