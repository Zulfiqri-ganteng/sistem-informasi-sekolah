<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">Detail Siswa — <?= esc($siswa['nama']) ?></h6>
            <a href="<?= smart_url('guru/kelas') ?>" class="btn btn-sm btn-outline-primary">Kembali</a>
        </div>

        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card p-3">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:72px;height:72px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;">
                                <i class="fa fa-user fa-2x text-muted"></i>
                            </div>
                            <div>
                                <div class="fw-bold"><?= esc($siswa['nama']) ?></div>
                                <div class="small text-muted"><?= esc($siswa['nisn'] ?? '-') ?></div>
                                <div class="mt-2 fw-semibold">Saldo: Rp <span id="saldoVal"><?= number_format($saldo, 0, ',', '.') ?></span></div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button class="btn btn-primary btn-sm w-100" onclick="openTransaksi(<?= $siswa['id'] ?>, '<?= esc($siswa['nama'], 'js') ?>')">Buat Transaksi</button>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card p-3">
                        <h6 class="fw-semibold">Riwayat Transaksi (terbaru)</h6>
                        <div class="table-responsive mt-2">
                            <table class="table table-striped" id="dtTrans">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Tipe</th>
                                        <th class="text-end">Jumlah</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($transaksi as $t): ?>
                                        <tr>
                                            <td><?= date('d M Y H:i', strtotime($t['created_at'])) ?></td>
                                            <td><?= esc($t['tipe']) ?></td>
                                            <td class="text-end">Rp <?= number_format($t['jumlah'], 0, ',', '.') ?></td>
                                            <td><?= esc($t['keterangan'] ?? '-') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->include('guru/partials/_transaksi_modal') ?>

<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        $('#dtTrans').DataTable({
            pageLength: 10,
            ordering: false,
            searching: false
        });
    });
</script>

<?= $this->endSection() ?>