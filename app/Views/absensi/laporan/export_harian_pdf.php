<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width:100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border:1px solid #444; padding:6px; text-align:center; }
        th { background:#efefef; font-weight:bold; }
        h3 { text-align:center; margin-bottom:0; }
        .sub { text-align:center; margin-top:0; font-size:12px; }
    </style>
</head>
<body>

<h3>LAPORAN ABSENSI HARIAN</h3>
<div class="sub"><?= date('d F Y', strtotime($tanggal)) ?></div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>L/P</th>
            <th>Status</th>
            <th>Jam Masuk</th>
            <th>Jam Pulang</th>
        </tr>
    </thead>

    <tbody>
        <?php $no = 1; foreach ($siswa as $s): 
            $a = $absensi[$s['id']] ?? null;
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= esc($s['nama']) ?></td>
            <td><?= esc($s['kelas']) ?></td>
            <td><?= esc($s['jenis_kelamin']) ?></td>
            <td><?= strtoupper($a['status'] ?? '-') ?></td>
            <td><?= $a['jam_masuk'] ?? '-' ?></td>
            <td><?= $a['jam_pulang'] ?? '-' ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
