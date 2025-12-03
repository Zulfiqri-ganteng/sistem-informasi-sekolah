<style>
    body {
        font-family: Arial, sans-serif;
        font-size: 11px;
    }

    h2 {
        text-align: center;
        margin-bottom: 0;
    }

    .kop {
        text-align: center;
        font-size: 14px;
        font-weight: bold;
    }

    .line {
        border-top: 3px solid #000;
        margin: 10px 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    th {
        background: #1F4E78;
        color: white;
        padding: 6px;
        font-size: 11px;
        border: 1px solid #333;
    }

    td {
        padding: 5px;
        border: 1px solid #666;
    }
</style>

<div class="kop">
    <div>PEMERINTAH KOTA BEKASI</div>
    <div>Sistem Informasi Sekolah</div>
    <div style="font-weight: normal">Jl. Pendidikan No. 123 — Telp: (021) 1234567</div>
</div>
<div class="line"></div>

<h2>LAPORAN TABUNGAN SISWA</h2>
<p style="text-align:center">Tanggal: <?= $tanggal ?></p>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Jurusan</th>
            <th>Total Setor</th>
            <th>Total Tarik</th>
            <th>Saldo</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($laporan as $i => $r): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= $r['nama'] ?></td>
                <td><?= $r['kelas'] ?></td>
                <td><?= $r['jurusan'] ?></td>
                <td><?= number_format($r['total_setor'], 0, ',', '.') ?></td>
                <td><?= number_format($r['total_tarik'], 0, ',', '.') ?></td>
                <td><?= number_format($r['saldo'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<br><br>
<div style="text-align:right;">
    Kepala Sekolah,<br><br><br>
    _____________________
</div>