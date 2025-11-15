<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Tabungan Siswa</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 0;
        }

        h4 {
            text-align: center;
            margin-top: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #666;
            padding: 6px;
        }

        th {
            background: #f0f0f0;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>
    <h2><?= esc($sekolah) ?></h2>
    <h4>Laporan Tabungan Siswa<br><small>Dicetak: <?= esc($tanggal) ?></small></h4>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Jurusan</th>
                <th>Total Setoran</th>
                <th>Total Tarikan</th>
                <th>Saldo Akhir</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($laporan as $r): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= esc($r['nama']) ?></td>
                    <td><?= esc($r['kelas']) ?></td>
                    <td><?= esc($r['jurusan']) ?></td>
                    <td class="text-right"><?= number_format($r['total_setor'], 0, ',', '.') ?></td>
                    <td class="text-right"><?= number_format($r['total_tarik'], 0, ',', '.') ?></td>
                    <td class="text-right"><b><?= number_format($r['saldo'], 0, ',', '.') ?></b></td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</body>

</html>