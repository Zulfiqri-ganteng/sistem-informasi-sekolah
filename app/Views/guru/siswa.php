<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0"><i class="fa fa-users me-2"></i> Siswa — <?= esc($kelas['nama_kelas'] ?? '-') ?></h6>
            <div>
                <a href="<?= smart_url('guru/dashboard') ?>" class="btn btn-sm btn-outline-primary">Kembali</a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="dtSiswa" class="table table-striped table-hover w-100">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>NISN</th>
                            <th>Kelas</th>
                            <th class="text-end">Saldo (Rp)</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($siswa as $i => $s):
                            $saldoRow = $this->db->table('tabungan')->where('siswa_id', $s['id'])->get()->getRowArray();
                            $saldo = $saldoRow['saldo'] ?? 0;
                        ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= esc($s['nama']) ?></td>
                                <td><?= esc($s['nisn'] ?? '-') ?></td>
                                <td><?= esc($s['kelas']) ?></td>
                                <td class="text-end fw-semibold">Rp <?= number_format($saldo, 0, ',', '.') ?></td>
                                <td>
                                    <a href="<?= smart_url('guru/siswa/' . $s['id']) ?>" class="btn btn-sm btn-light">Detail</a>
                                    <button class="btn btn-sm btn-primary btn-transaksi" data-id="<?= $s['id'] ?>" data-nama="<?= esc($s['nama']) ?>">Transaksi</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->include('guru/partials/_transaksi_modal') ?>

<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        $('#dtSiswa').DataTable({
            pageLength: 25,
            responsive: true,
            ordering: true,
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data"
            }
        });

        // show modal transaksi
        $('.btn-transaksi').on('click', function() {
            const id = $(this).data('id'),
                nama = $(this).data('nama');
            openTransaksi(id, nama);
        });

        // submit transaksi via AJAX (handled in modal partial)
    });
</script>

<?= $this->endSection() ?>