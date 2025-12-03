<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #e6e6e6;
            text-align: center;
        }
    </style>
</head>

<body>

    <h2 style="text-align:center;">LAPORAN ABSENSI HARIAN</h2>
    <h4 style="text-align:center;"><?= date('d F Y', strtotime($tanggal)) ?></h4>

    <table>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>L/P</th>
            <th>Status</th>
            <th>Jam Masuk</th>
            <th>Jam Pulang</th>
        </tr>

        <?php $no = 1; ?>
        <?php foreach ($absensi as $a):
            $s = array_values(array_filter($siswa, fn($x) => $x['id'] == $a['user_id']))[0];
        ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= esc($s['nama']) ?></td>
                <td><?= esc($s['kelas']) ?></td>
                <td><?= esc($s['jenis_kelamin']) ?></td>
                <td><?= strtoupper($a['status']) ?></td>
                <td><?= $a['jam_masuk'] ?></td>
                <td><?= $a['jam_pulang'] ?: '-' ?></td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>

</html>