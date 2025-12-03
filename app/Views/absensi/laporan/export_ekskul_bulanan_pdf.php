<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      margin: 20px 35px;
      font-size: 11px;
    }

    .header {
      text-align: center;
      line-height: 1.4;
    }

    .school-name {
      font-weight: bold;
      font-size: 18px;
    }

    .header-line {
      border-bottom: 3px solid #000;
      margin: 10px 0 15px;
      width: 100%;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th,
    td {
      border: 1px solid #444;
      padding: 6px 4px;
      text-align: center;
      font-size: 10.5px;
    }

    th {
      background: #eef3ff;
      font-weight: bold;
    }

    .td-hadir {
      background: #c8f7c4 !important;
      font-weight: bold;
    }

    .info-table td {
      border: none;
      padding: 4px;
      text-align: left;
      vertical-align: top;
    }

    .footer {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      margin-top: 20px;
      font-size: 9px;
      text-align: center;
      color: #555;
      border-top: 1px solid #aaa;
      padding: 6px 35px;
      background: white;
    }

    .ttd {
      margin-top: 30px;
      float: right;
      width: 240px;
      text-align: left;
    }

    .space {
      height: 55px;
    }

    .title-section {
      text-align: center;
      margin-bottom: 15px;
    }

    .ekskul-info {
      text-align: center;
      font-style: italic;
      margin-bottom: 15px;
    }

    .table-container {
      margin-bottom: 70px;
    }

    .info-section {
      margin-bottom: 15px;
    }

    .info-label {
      width: 120px;
      display: inline-block;
    }

    .info-value {
      display: inline-block;
    }
  </style>
</head>

<body>

  <div class="header">
    <div class="school-name">SMK GALAJUARA</div>
    <div>Jl. Raya Setu – Bantargebang No. 123, Kota Bekasi</div>
    <div>Telp. (021) 999888 — Email: info@smkgalajuara.sch.id</div>
  </div>

  <div class="header-line"></div>

  <div class="title-section">
    <h3 style="margin: 0; padding: 0;">DAFTAR HADIR SISWA EKSTRAKURIKULER</h3>
  </div>

  <div class="ekskul-info">
    <?= esc($ekskulInfo['nama_ekskul']) ?> — <?= date('F Y', strtotime($bulan . "-01")) ?>
  </div>

  <div class="info-section">
    <div>
      <span class="info-label">Nama Ekskul</span>
      <span>:</span>
      <span class="info-value"><?= esc($ekskulInfo['nama_ekskul']) ?></span>
    </div>
    <div>
      <span class="info-label">Pembina</span>
      <span>:</span>
      <span class="info-value"><b><?= esc($ekskulInfo['pembina'] ?? '-') ?></b></span>
    </div>
    <div>
      <span class="info-label">Bulan</span>
      <span>:</span>
      <span class="info-value"><?= date('F Y', strtotime($bulan . "-01")) ?></span>
    </div>
  </div>

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th style="text-align:left;">Nama Siswa</th>
          <th>Kelas</th>
          <th>L/P</th>
          <?php foreach ($meetingHeader as $mh): ?>
            <th><?= $mh ?></th>
          <?php endforeach; ?>
          <th>Hadir</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1; ?>
        <?php foreach ($siswa as $s): ?>
          <?php $sid = $s['id']; ?>
          <tr>
            <td><?= $no++ ?></td>
            <td style="text-align:left;"><?= esc($s['nama']) ?></td>
            <td><?= esc($s['kelas']) ?></td>
            <td><?= esc($s['jenis_kelamin'] ?? '-') ?></td>
            <?php foreach ($meetingDates as $md): ?>
              <?php
              $row = $absensiMap[$sid][$md] ?? null;
              $status = strtolower($row['status'] ?? '');
              $hadir = in_array($status, ['h', 'hadir', 'masuk', 'pulang', 'pulang_awal']);
              ?>
              <td class="<?= $hadir ? 'td-hadir' : '' ?>"><?= $hadir ? 'H' : '' ?></td>
            <?php endforeach; ?>
            <td><b><?= $rekap[$sid] ?></b></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="ttd">
    Bekasi, <?= date('d F Y') ?><br>
    Pembina Ekskul,<br><br>
    <div class="space"></div>
    <b><u><?= esc($ekskulInfo['pembina'] ?? '-') ?></u></b>
  </div>

  <div class="footer">
    Dokumen ini dicetak otomatis oleh Sistem Informasi Sekolah SMK Galajuara.<br>
    Sah tanpa tanda tangan basah.
  </div>

</body>

</html>