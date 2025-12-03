<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    :root {
        --primary-color: #4361ee;
        --success-color: #06d6a0;
        --warning-color: #ffd166;
        --danger-color: #ef476f;
        --light-bg: #f8f9fa;
        --card-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        --table-header-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .report-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
    }

    .report-header::before {
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

    .header-content {
        position: relative;
        z-index: 2;
    }

    .ekskul-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .stat-card {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .stat-label {
        font-size: 0.875rem;
        opacity: 0.9;
    }

    .report-table-container {
        background: white;
        border-radius: 16px;
        box-shadow: var(--card-shadow);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .table-header {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem;
        border-bottom: 1px solid #e9ecef;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .table-custom {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.875rem;
    }

    .table-custom thead th {
        background: linear-gradient(135deg, #4361ee 0%, #3a56d4 100%);
        color: white;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 1rem;
        border: none;
        position: sticky;
        top: 0;
        z-index: 10;
        white-space: nowrap;
    }

    .table-custom tbody tr {
        transition: all 0.2s ease;
    }

    .table-custom tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.05);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .table-custom td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }

    .table-custom tr:last-child td {
        border-bottom: none;
    }

    .attendance-cell {
        width: 60px;
        text-align: center;
        padding: 0.5rem;
    }

    .attendance-present {
        background-color: #d1f7e5;
        color: #0d8b5a;
        font-weight: 700;
        border-radius: 8px;
        padding: 0.5rem;
        display: inline-block;
        min-width: 32px;
        box-shadow: 0 2px 4px rgba(13, 139, 90, 0.1);
    }

    .attendance-absent {
        color: #999;
        font-size: 0.75rem;
    }

    .student-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .student-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
        flex-shrink: 0;
    }

    .student-details h6 {
        margin: 0;
        font-weight: 600;
        color: #2d3748;
    }

    .student-details small {
        color: #718096;
        font-size: 0.75rem;
    }

    .gender-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .gender-male {
        background-color: #e3f2fd;
        color: #1976d2;
    }

    .gender-female {
        background-color: #fce4ec;
        color: #c2185b;
    }

    .summary-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        border: 1px solid #e9ecef;
    }

    .summary-title {
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #718096;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .summary-value {
        font-size: 2rem;
        font-weight: 700;
        color: #4361ee;
        line-height: 1;
    }

    .summary-label {
        font-size: 0.875rem;
        color: #718096;
        margin-top: 0.5rem;
    }

    .export-btn {
        background: linear-gradient(135deg, #ef476f 0%, #ff6b6b 100%);
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .export-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(239, 71, 111, 0.3);
        color: white;
    }

    .meeting-date {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .date-day {
        font-weight: 700;
        font-size: 1rem;
        color: #2d3748;
    }

    .date-month {
        font-size: 0.75rem;
        color: #718096;
        text-transform: uppercase;
    }

    @media print {
        .report-header {
            break-inside: avoid;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;

        }

        .table-custom thead th {
            background: linear-gradient(135deg, #4361ee 0%, #3a56d4 100%) !important;
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;

        }

        .no-print {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .report-header {
            padding: 1.5rem;
        }

        .ekskul-stats {
            grid-template-columns: 1fr;
        }

        .table-custom {
            font-size: 0.75rem;
        }

        .attendance-cell {
            width: 45px;
            padding: 0.25rem;
        }
    }
</style>

<div class="container-fluid">
    <!-- Report Header -->
    <div class="report-header">
        <div class="header-content">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="mb-2"><?= esc($ekskulInfo['nama_ekskul']) ?></h1>
                    <p class="mb-0 opacity-90">
                        <i class="bi bi-calendar-month me-1"></i>
                        <?= date('F Y', strtotime($bulan . '-01')) ?>
                        <span class="mx-2">•</span>
                        <i class="bi bi-person me-1"></i>
                        <?= $ekskulInfo['pembina'] ?? 'Pembina Tidak Ditetapkan' ?>
                    </p>
                </div>
                <a target="_blank"
                    href="<?= smart_url('absensi/laporan/ekskulBulananPdf?ekskul_id=' . $ekskulInfo['id'] . '&bulan=' . $bulan) ?>"
                    class="export-btn no-print">
                    <i class="bi bi-file-earmark-pdf"></i>
                    Export PDF
                </a>
            </div>

            <!-- Statistics Cards -->
            <div class="ekskul-stats">
                <div class="stat-card">
                    <div class="stat-value"><?= count($siswa) ?></div>
                    <div class="stat-label">Total Anggota</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value"><?= count($meetingDates) ?></div>
                    <div class="stat-label">Jumlah Pertemuan</div>
                </div>
                <div class="stat-card">
                    <?php
                    $totalAttendance = array_sum($rekap);
                    $maxAttendance = count($meetingDates) * count($siswa);
                    $percentage = $maxAttendance > 0 ? round(($totalAttendance / $maxAttendance) * 100) : 0;
                    ?>
                    <div class="stat-value"><?= $percentage ?>%</div>
                    <div class="stat-label">Rata-rata Kehadiran</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">
                        <?php
                        $completedCount = 0;
                        foreach ($siswa as $s) {
                            if (($rekap[$s['id']] ?? 0) == count($meetingDates)) {
                                $completedCount++;
                            }
                        }
                        echo $completedCount;
                        ?>
                    </div>
                    <div class="stat-label">Hadir Semua Pertemuan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Row -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="summary-card">
                <div class="summary-title">Kehadiran Tertinggi</div>
                <?php
                $maxAttendance = max($rekap);
                $topStudents = [];
                foreach ($rekap as $sid => $att) {
                    if ($att == $maxAttendance) {
                        $student = array_filter($siswa, fn($s) => $s['id'] == $sid);
                        if (!empty($student)) {
                            $topStudents[] = reset($student)['nama'];
                        }
                    }
                }
                ?>
                <div class="summary-value"><?= $maxAttendance ?>/<?= count($meetingDates) ?></div>
                <div class="summary-label">
                    <?php
                    if (!empty($topStudents)) {
                        echo count($topStudents) > 1 ?
                            count($topStudents) . ' siswa' :
                            esc($topStudents[0]);
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="summary-card">
                <div class="summary-title">Kehadiran Terendah</div>
                <?php
                $minAttendance = min($rekap);
                $lowStudents = [];
                foreach ($rekap as $sid => $att) {
                    if ($att == $minAttendance) {
                        $student = array_filter($siswa, fn($s) => $s['id'] == $sid);
                        if (!empty($student)) {
                            $lowStudents[] = reset($student)['nama'];
                        }
                    }
                }
                ?>
                <div class="summary-value"><?= $minAttendance ?>/<?= count($meetingDates) ?></div>
                <div class="summary-label">
                    <?php
                    if (!empty($lowStudents)) {
                        echo count($lowStudents) > 1 ?
                            count($lowStudents) . ' siswa' :
                            esc($lowStudents[0]);
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="summary-card">
                <div class="summary-title">Rata-rata Kehadiran</div>
                <?php
                $average = count($siswa) > 0 ? round(array_sum($rekap) / count($siswa), 1) : 0;
                ?>
                <div class="summary-value"><?= $average ?></div>
                <div class="summary-label">per siswa</div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="summary-card">
                <div class="summary-title">Status Laporan</div>
                <div class="summary-value text-success">Selesai</div>
                <div class="summary-label">
                    Diperbarui: <?= date('d/m/Y H:i') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="report-table-container">
        <div class="table-header">
            <h5 class="mb-0">
                <i class="bi bi-list-check me-2"></i>
                Daftar Hadir Anggota Ekskul
            </h5>
            <p class="text-muted mb-0 mt-2 small">
                H = Hadir (Termasuk status Masuk, Pulang, dan Pulang Awal)
            </p>
        </div>

        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">No</th>
                        <th style="min-width: 250px;">Nama Siswa</th>
                        <th style="width: 100px; text-align: center;">Kelas</th>
                        <th style="width: 80px; text-align: center;">L/P</th>

                        <?php foreach ($meetingDates as $index => $date): ?>
                            <th style="width: 70px; text-align: center;">
                                <div class="meeting-date">
                                    <span class="date-day"><?= date('d', strtotime($date)) ?></span>
                                    <span class="date-month"><?= date('M', strtotime($date)) ?></span>
                                </div>
                            </th>
                        <?php endforeach; ?>

                        <th style="width: 100px; text-align: center;">Total</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($siswa as $s): ?>
                        <?php
                        $sid = $s['id'];
                        $initials = '';
                        $nameParts = explode(' ', $s['nama']);
                        foreach ($nameParts as $part) {
                            $initials .= strtoupper(substr($part, 0, 1));
                        }
                        $initials = substr($initials, 0, 2);
                        ?>
                        <tr>
                            <td style="text-align: center; font-weight: 600; color: #718096;">
                                <?= $no++ ?>
                            </td>

                            <td>
                                <div class="student-info">
                                    <div class="student-avatar">
                                        <?= $initials ?>
                                    </div>
                                    <div class="student-details">
                                        <h6><?= esc($s['nama']) ?></h6>
                                        <small>NIS: <?= $s['nisn'] ?? '-' ?></small>
                                    </div>
                                </div>
                            </td>

                            <td style="text-align: center; font-weight: 600; color: #4361ee;">
                                <?= esc($s['kelas']) ?>
                            </td>

                            <td style="text-align: center;">
                                <?php
                                $gender = strtoupper($s['jenis_kelamin'] ?? '');
                                $genderClass = $gender === 'L' ? 'gender-male' : ($gender === 'P' ? 'gender-female' : '');
                                ?>
                                <span class="gender-badge <?= $genderClass ?>">
                                    <?= $gender ?: '-' ?>
                                </span>
                            </td>

                            <?php foreach ($meetingDates as $md): ?>
                                <?php
                                $row = $absensiMap[$sid][$md] ?? null;
                                $status = strtolower($row['status'] ?? '');
                                $hadir = in_array($status, ['h', 'hadir', 'masuk', 'pulang', 'pulang_awal']);
                                ?>
                                <td style="text-align: center;" class="attendance-cell">
                                    <?php if ($hadir): ?>
                                        <span class="attendance-present">H</span>
                                    <?php else: ?>
                                        <span class="attendance-absent">-</span>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>

                            <td style="text-align: center;">
                                <div style="
                                    background: linear-gradient(135deg, #4361ee 0%, #3a56d4 100%);
                                    color: white;
                                    padding: 0.5rem;
                                    border-radius: 8px;
                                    font-weight: 700;
                                    min-width: 40px;
                                    display: inline-block;
                                    box-shadow: 0 2px 4px rgba(67, 97, 238, 0.2);
                                ">
                                    <?= $rekap[$sid] ?? 0 ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>


</div>

<?= $this->endSection() ?>