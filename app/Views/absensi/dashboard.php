<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    .stat-grid {
        display: flex;
        gap: 18px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .stat-card {
        flex: 1;
        min-width: 160px;
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 12px 30px rgba(2, 8, 23, 0.06);
    }

    .stat-card h4 {
        font-size: 20px;
        margin: 0;
    }

    .stat-value {
        font-size: 34px;
        font-weight: 800;
        margin-top: 12px;
    }

    .rekap-card {
        background: #fff;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 12px 30px rgba(2, 8, 23, 0.06);
    }

    .table-responsive {
        overflow: auto;
    }

    .badge-status {
        padding: 6px 10px;
        border-radius: 6px;
        font-weight: 700;
        color: #fff;
    }

    .badge-terlambat {
        background: #f59e0b;
    }

    .badge-masuk {
        background: #10b981;
    }
</style>

<div class="container-fluid">
    <h2 class="mb-4">Absensi Hari Ini (<?= date('d M Y', strtotime($today)) ?>)</h2>

    <div class="stat-grid mb-4">
        <div class="stat-card">
            <h4>Hadir</h4>
            <div class="stat-value text-success"><?= esc($hadir) ?></div>
        </div>
        <div class="stat-card">
            <h4>Terlambat</h4>
            <div class="stat-value text-warning"><?= esc($telat) ?></div>
        </div>
        <div class="stat-card">
            <h4>Izin</h4>
            <div class="stat-value text-info"><?= esc($izin) ?></div>
        </div>
        <div class="stat-card">
            <h4>Sakit</h4>
            <div class="stat-value text-primary"><?= esc($sakit) ?></div>
        </div>
        <div class="stat-card">
            <h4>Pulang Awal</h4>
            <div class="stat-value text-danger"><?= esc($pulang_awal) ?></div>
        </div>
    </div>

    <div class="rekap-card">
        <h4>Rekap Absensi Hari Ini</h4>
        <div class="table-responsive mt-3">
            <table class="table table-striped table-bordered">
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
                            <td colspan="6" class="text-center">Belum ada data.</td>
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
                                    <?php if ($r['status'] === 'terlambat'): ?>
                                        <span class="badge-status badge-terlambat">TERLAMBAT</span>
                                    <?php elseif ($r['status'] === 'masuk'): ?>
                                        <span class="badge-status badge-masuk">MASUK</span>
                                    <?php else: ?>
                                        <span class="badge-status" style="background:#6b7280"><?= esc(strtoupper($r['status'])) ?></span>
                                    <?php endif; ?>
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