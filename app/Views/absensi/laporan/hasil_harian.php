<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php
// ----------------------------------------------
// STATUS MAPPER → Ubah status DB menjadi H/I/S/A
// ----------------------------------------------
function mapStatusSymbol($status)
{
    $s = strtolower($status);

    if (in_array($s, ['masuk', 'hadir', 'pulang', 'pulang_awal'])) return 'H';
    if ($s === 'izin') return 'I';
    if ($s === 'sakit') return 'S';
    if ($s === 'terlambat') return 'H'; // terlambat tetap hadir

    return 'A'; // Alfa
}
?>

<style>
    /* ======================================================
   STYLE SIMPLE PREMIUM — Clean, Profesional, Modern UI
   ====================================================== */

    .page-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2d3e50;
    }

    .card-summary {
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        padding: 1.25rem;
        text-align: center;
    }

    .card-summary .value {
        font-size: 1.7rem;
        font-weight: 700;
    }

    .card-summary .label {
        font-size: 0.85rem;
        letter-spacing: .4px;
        text-transform: uppercase;
        color: #6c757d;
    }

    .badge-status {
        padding: .45rem .75rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: .8rem;
    }

    .bg-hadir {
        background: #d1fae5;
        color: #065f46;
    }

    .bg-izin {
        background: #dbeafe;
        color: #1e3a8a;
    }

    .bg-sakit {
        background: #fef3c7;
        color: #92400e;
    }

    .bg-alpha {
        background: #fee2e2;
        color: #991b1b;
    }

    .bg-none {
        background: #e5e7eb;
        color: #374151;
    }

    .time-box {
        padding: .4rem .75rem;
        background: #f8fafc;
        border-radius: 6px;
        font-family: monospace;
        font-weight: 600;
        color: #374151;
        border: 1px solid #e2e8f0;
    }

    .table thead th {
        background: #f1f5f9;
        font-size: .8rem;
        text-transform: uppercase;
        letter-spacing: .4px;
    }

    .export-btn {
        padding: .6rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
    }
</style>

<div class="container-fluid">

    <!-- ======================== HEADER ======================== -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title mb-1">Laporan Absensi Harian</h2>
            <div class="text-muted">
                <i class="bi bi-calendar3"></i>
                <?= date('l, d F Y', strtotime($tanggal)) ?>
            </div>
        </div>

        <div>
            <a href="<?= smart_url('absensi/laporan/export-pdf?tanggal=' . $tanggal . '&jenis=harian') ?>" class="export-btn btn btn-danger">
                <i class="bi bi-file-earmark-pdf"></i> PDF
            </a>
            <a href="<?= smart_url('absensi/laporan/export-word?tanggal=' . $tanggal . '&jenis=harian') ?>" class="export-btn btn btn-primary">
                <i class="bi bi-file-earmark-word"></i> Word
            </a>
            <a href="<?= smart_url('absensi/laporan/export-excel?tanggal=' . $tanggal . '&jenis=harian') ?>" class="export-btn btn btn-success">
                <i class="bi bi-file-earmark-excel"></i> Excel
            </a>
        </div>
    </div>

    <!-- ======================== STATISTICS ======================== -->
    <div class="row mb-4">

        <div class="col-md-2 col-6 mb-3">
            <div class="card-summary">
                <div class="value"><?= $totals['hadir'] ?></div>
                <div class="label">Hadir</div>
            </div>
        </div>

        <div class="col-md-2 col-6 mb-3">
            <div class="card-summary">
                <div class="value"><?= $totals['izin'] ?></div>
                <div class="label">Izin</div>
            </div>
        </div>

        <div class="col-md-2 col-6 mb-3">
            <div class="card-summary">
                <div class="value"><?= $totals['alpha'] ?></div>
                <div class="label">Alpha</div>
            </div>
        </div>

        <div class="col-md-2 col-6 mb-3">
            <div class="card-summary">
                <div class="value"><?= $totals['pulang'] ?></div>
                <div class="label">Pulang Awal</div>
            </div>
        </div>

        <div class="col-md-2 col-6 mb-3">
            <div class="card-summary">
                <div class="value"><?= count($siswa) ?></div>
                <div class="label">Total Siswa</div>
            </div>
        </div>

    </div>

    <!-- ======================== TABLE ======================== -->
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered align-middle">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Status</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $no = 1;
                        foreach ($siswa as $s):
                            $a = $absensi[$s['id']] ?? null;
                            $stSymbol = $a ? mapStatusSymbol($a['status']) : '-';
                        ?>

                            <tr>
                                <td><?= $no++ ?></td>

                                <td><?= esc($s['nama']) ?></td>

                                <td><?= esc($s['kelas']) ?></td>

                                <!-- Status -->
                                <td>
                                    <?php if (!$a): ?>
                                        <span class="badge-status bg-none">-</span>

                                    <?php elseif ($stSymbol === 'H'): ?>
                                        <span class="badge-status bg-hadir">H</span>

                                    <?php elseif ($stSymbol === 'I'): ?>
                                        <span class="badge-status bg-izin">I</span>

                                    <?php elseif ($stSymbol === 'S'): ?>
                                        <span class="badge-status bg-sakit">S</span>

                                    <?php else: ?>
                                        <span class="badge-status bg-alpha">A</span>
                                    <?php endif; ?>
                                </td>

                                <!-- Jam Masuk -->
                                <td>
                                    <?= (!empty($a['jam_masuk']))
                                        ? '<span class="time-box">' . $a['jam_masuk'] . '</span>'
                                        : '<span class="text-muted">-</span>' ?>
                                </td>

                                <!-- Jam Pulang -->
                                <td>
                                    <?= (!empty($a['jam_pulang']))
                                        ? '<span class="time-box">' . $a['jam_pulang'] . '</span>'
                                        : '<span class="text-muted">-</span>' ?>
                                </td>

                                <!-- Keterangan -->
                                <td>
                                    <?= !empty($a['keterangan'])
                                        ? esc($a['keterangan'])
                                        : '<span class="text-muted">-</span>' ?>
                                </td>

                            </tr>

                        <?php endforeach; ?>
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

<?= $this->endSection() ?>