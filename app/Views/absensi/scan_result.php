<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<?php
// expects: $barcode, $owner, $owner_type, $nextAction, $tipe_absen, $jadwalEkskul, $ekskul_id

$owner_type = $owner_type ?? ($owner['role'] ?? ($owner['jenis'] ?? 'siswa'));
$ownerName  = $owner['nama'] ?? $owner['nama_lengkap'] ?? 'Pengguna';
$photo      = $owner['foto'] ?? null;

$img = $photo ? smart_url($photo) : smart_url('uploads/admin/default.png');

// BUTTON STATE
$statusConfig = [
    'masuk' => ['color' => 'success', 'icon' => 'sign-in-alt', 'text' => 'Menandai Masuk'],
    'pulang' => ['color' => 'warning', 'icon' => 'sign-out-alt', 'text' => 'Menandai Pulang'],
    'default' => ['color' => 'secondary', 'icon' => 'check-circle', 'text' => 'Sudah Selesai']
];

$status = $nextAction === 'masuk' ? $statusConfig['masuk'] : ($nextAction === 'pulang' ? $statusConfig['pulang'] : $statusConfig['default']);

// MODE DEBUG (tampilkan blok logic di scan + processScan)
$DEBUG = true;
?>

<style>
    /* STYLE TETAP SAMA DENGAN ORI MAS */
    .result-wrap {
        max-width: 1000px;
        margin: 2rem auto;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        padding: 1.25rem;
    }

    .token {
        background: rgba(255, 255, 255, 0.12);
        padding: 0.4rem 0.8rem;
        border-radius: 40px;
    }

    .avatar {
        width: 140px;
        height: 140px;
        border-radius: 12px;
        object-fit: cover;
        border: 4px solid #fff;
    }

    .info {
        padding: 1.25rem;
        background: #fbfdff;
    }

    .actions {
        padding: 1.25rem;
        background: #fff;
        border-top: 1px solid #eee;
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }

    .btn {
        padding: 0.85rem 1.25rem;
        border-radius: 10px;
        font-weight: 700;
        border: none;
        cursor: pointer;
    }

    .btn-success {
        background: linear-gradient(90deg, #4facfe, #00f2fe);
        color: #fff;
    }

    .btn-warning {
        background: linear-gradient(90deg, #fa709a, #fee140);
        color: #fff;
    }

    .btn-outline {
        background: #fff;
        border: 1px solid #e6edf7;
        color: #333;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.45rem 0.75rem;
        border-radius: 40px;
        font-weight: 700;
    }

    .badge-harian {
        background: rgba(79, 172, 254, 0.12);
        color: #0f172a;
    }

    .badge-ekskul {
        background: rgba(232, 121, 249, 0.12);
        color: #7e22ce;
    }

    .badge-strict {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    .notice {
        padding: 0.9rem 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        display: flex;
        gap: .75rem;
        align-items: center;
    }

    .notice-ekskul {
        background: #fff0f6;
        border-left: 4px solid #c026d3;
        color: #5b1855;
    }

    .notice-harian {
        background: #eef6ff;
        border-left: 4px solid #4f46e5;
        color: #0f172a;
    }

    .footer {
        padding: 0.9rem 1.25rem;
        background: #fbfdff;
        display: flex;
        justify-content: space-between;
        color: #6b7280;
        font-size: 0.95rem;
    }

    .debug-box {
        background: #1e293b;
        color: #fff;
        padding: 1rem;
        border-radius: 10px;
        margin: 1rem;
        font-family: monospace;
        white-space: pre-line;
    }
</style>


<div class="result-wrap">
    <div class="card">

        <!-- HEADER -->
        <div class="header d-flex justify-content-between align-items-center">
            <div>
                <h4 style="margin:0"><i class="fas fa-check-circle"></i> Konfirmasi Absensi</h4>
                <div class="token" style="margin-top:8px;">
                    <i class="fas fa-qrcode"></i> <strong><?= esc($barcode['token']) ?></strong>
                </div>
            </div>

            <div style="text-align:right;">
                <span class="badge <?= $tipe_absen === 'ekskul' ? 'badge-ekskul' : 'badge-harian' ?>">
                    <i class="fas fa-<?= $tipe_absen === 'ekskul' ? 'dumbbell' : 'school' ?>"></i>
                    <?= ucfirst($tipe_absen) ?>
                </span>
                <div style="margin-top:6px;">
                    <span class="badge badge-strict">
                        <i class="fas fa-shield-alt"></i> Strict Mode
                    </span>
                </div>
            </div>
        </div>

        <!-- NOTICE -->
        <?php if ($tipe_absen === 'ekskul'): ?>
            <div class="notice notice-ekskul" style="margin:1rem;">
                <i class="fas fa-info-circle fa-lg"></i>
                <div>
                    <strong>Mode Ekskul Aktif</strong>
                    <div class="small">
                        <?= date('H:i', strtotime($jadwalEkskul['jam_mulai'])) ?>
                        s/d
                        <?= date('H:i', strtotime($jadwalEkskul['jam_selesai'])) ?><br>

                        <?php if (!empty($ekskul_id)): ?>
                            Ekskul ID: <strong><?= esc($ekskul_id) ?></strong>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="notice notice-harian" style="margin:1rem;">
                <i class="fas fa-info-circle fa-lg"></i>
                <div>
                    <strong>Mode Harian</strong>
                    <div class="small">Mengikuti jam masuk/pulang & penguncian sistem.</div>
                </div>
            </div>
        <?php endif; ?>

        <!-- PROFIL -->
        <div class="d-flex gap-3 align-items-center" style="padding:1.25rem;">
            <div style="flex:0 0 160px; text-align:center;">
                <img src="<?= $img ?>" class="avatar"
                    onerror="this.src='<?= smart_url('uploads/admin/default.png') ?>'">
                <div style="margin-top:10px; font-weight:800;"><?= esc($ownerName) ?></div>
            </div>

            <div class="info" style="flex:1;">
                <table style="width:100%;">
                    <tr>
                        <td>Role</td>
                        <td><b><?= ucfirst($owner_type) ?></b></td>
                    </tr>
                    <tr>
                        <td>NIS/NIP</td>
                        <td><b><?= esc($owner['nisn'] ?? $owner['nip'] ?? '-') ?></b></td>
                    </tr>
                    <tr>
                        <td>Tipe Absensi</td>
                        <td><b><?= ucfirst($tipe_absen) ?></b></td>
                    </tr>

                    <?php if ($tipe_absen === 'ekskul'): ?>
                        <tr>
                            <td>Ekskul</td>
                            <td><b>ID: <?= esc($ekskul_id) ?></b></td>
                        </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>


        <!-- ACTION BUTTONS -->
        <div class="actions">

            <?php if ($nextAction === 'masuk'): ?>
                <form method="post" action="<?= smart_url('absensi/process-scan') ?>" style="flex:1;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="barcode_id" value="<?= esc($barcode['id']) ?>">
                    <button class="btn btn-success" style="width:100%;">
                        <i class="fas fa-sign-in-alt"></i> Konfirmasi & Masuk
                    </button>
                </form>

            <?php elseif ($nextAction === 'pulang'): ?>
                <form method="post" action="<?= smart_url('absensi/process-scan') ?>" style="flex:1;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="barcode_id" value="<?= esc($barcode['id']) ?>">
                    <button class="btn btn-warning" style="width:100%;">
                        <i class="fas fa-sign-out-alt"></i> Konfirmasi & Pulang
                    </button>
                </form>

            <?php else: ?>
                <div style="flex:1;">
                    <button class="btn btn-outline" disabled style="width:100%;">
                        <i class="fas fa-check-circle"></i> Absensi Hari Ini Sudah Selesai
                    </button>
                </div>
            <?php endif; ?>

            <a href="<?= smart_url('absensi/scan-camera') ?>" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <a href="<?= smart_url('absensi/qrcode/' . $barcode['id']) ?>" class="btn btn-outline">
                <i class="fas fa-eye"></i> Lihat QR
            </a>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <div>Dikonfirmasi oleh: <b><?= esc(session()->get('role')) ?></b></div>
            <div>Waktu Scan: <b><?= date('Y-m-d H:i:s') ?></b></div>
        </div>

        <!-- DEBUG PANEL -->
        <?php if ($DEBUG): ?>
            <div class="debug-box">
                <b>DEBUG MODE ON</b>
                --------------------------
                User Type : <?= $owner_type ?>

                NextAction : <?= $nextAction ?>

                Tipe Absen : <?= $tipe_absen ?>

                Ekskul Active : <?= $tipe_absen === 'ekskul' ? 'YES' : 'NO' ?>

                Ekskul ID : <?= $ekskul_id ?: '-' ?>

                Jam Now : <?= date('H:i:s') ?>

                Controller Log :
                - Mode Strict ENABLED
                - Harian OFF = ditolak
                - Ekskul diprioritaskan
                - Update ekskul tidak menimpa ekskul_id
                - Debug halaman aktif
            </div>
        <?php endif; ?>

    </div>
</div>

<?= $this->endSection() ?>