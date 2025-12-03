<table border="1" cellspacing="0" cellpadding="6" width="100%">
    <tr>
        <th colspan="7" style="font-size:16px; text-align:center;">
            LAPORAN ABSENSI HARIAN<br>
            <?= date('d F Y', strtotime($tanggal)) ?>
        </th>
    </tr>

    <tr style="background:#e6e6e6;">
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