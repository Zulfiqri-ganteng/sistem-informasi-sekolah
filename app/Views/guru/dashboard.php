<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<?= $this->include('guru/partials/_transaksi_modal'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<div class="container-fluid py-4 animate__animated animate__fadeIn">

    <!-- PREMIUM HEADER (NAVY + GOLD) -->
    <div class="premium-header mb-4 p-4 rounded-4 shadow-sm text-white"
        style="background: linear-gradient(90deg,#09203f 0%,#2e5a88 100%); border-left:4px solid #d4af37;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            <div class="d-flex align-items-center gap-3">
                <div class="avatar rounded-circle bg-white overflow-hidden"
                    style="width:72px;height:72px;display:flex;align-items:center;justify-content:center;">

                    <img src="<?= smart_url('uploads/guru/' . ($guru['foto'] ?? 'default.png')) ?>"
                        alt="guru"
                        style="width:100%;height:100%;object-fit:cover;object-position:center -8px;">


                </div>

                <div>
                    <h4 class="mb-0 fw-bold" style="letter-spacing:0.2px;">Selamat Datang, <?= esc($guru['nama'] ?? 'Guru') ?></h4>
                    <div class="small text-white-50">Dashboard Guru — Sistem Tabungan Sekolah</div>
                    <div class="small text-white-50 mt-1">
                        <span class="badge bg-success rounded-pill" style="padding:4px 10px;font-size:11px;">● Aktif</span>
                        <span class="ms-2">Wali Kelas: <b><?= implode(', ', array_map(fn($k) => $k['nama_kelas'], $kelasList)) ?: '-' ?></b></span>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-4 flex-wrap">
                <div class="text-center">
                    <div class="small text-white-50">Kelas Anda</div>
                    <div class="h5 fw-bold mb-0"><?= count($kelasList) ?></div>
                </div>

                <div class="text-center">
                    <div class="small text-white-50">Jumlah Siswa</div>
                    <div class="h5 fw-bold mb-0"><?= $jumlahSiswa ?? 0 ?></div>
                </div>

                <div class="text-center">
                    <div class="small text-white-50">Total Saldo</div>
                    <div class="h5 fw-bold mb-0">Rp <?= number_format($totalSaldo ?? 0, 0, ',', '.') ?></div>
                </div>

                <button id="btnRefreshDashboard" class="btn btn-outline-light btn-sm">
                    <i class="fa-solid fa-rotate me-1"></i> Refresh
                </button>
            </div>
        </div>
    </div>
    <!-- END HEADER -->

    <!-- SUMMARY GRID (mini stat cards + sparkline placeholders) -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 p-3 h-100">
                <small class="text-muted">Siswa Aktif</small>
                <h3 class="fw-bold mb-0"><?= $jumlahSiswa ?? 0 ?></h3>
                <small class="text-secondary">Total siswa di kelas Anda</small>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 p-3 h-100">
                <small class="text-muted">Transaksi Terakhir</small>
                <h3 class="fw-bold mb-0"><?= count($recentTransaksi ?? []) ?></h3>
                <small class="text-secondary">10 transaksi terbaru</small>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 p-3 h-100">
                <small class="text-muted">Total Saldo</small>
                <h3 class="fw-bold mb-0">Rp <?= number_format($totalSaldo ?? 0, 0, ',', '.') ?></h3>
                <small class="text-secondary">Saldo gabungan kelas</small>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 p-3 h-100">
                <small class="text-muted">Aksi Cepat</small>
                <div class="mt-2">
                    <a href="<?= smart_url('guru/kelas') ?>" class="btn btn-light btn-sm me-2"><i class="fa fa-list me-1"></i> Kelas Saya</a>
                    <a href="<?= smart_url('guru/profil') ?>" class="btn btn-outline-light btn-sm"><i class="fa fa-user me-1"></i> Profil</a>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN: grafik + recent transaksi + top students -->
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 p-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="mb-0 fw-semibold"><i class="fa fa-chart-column me-2"></i> Grafik Saldo Siswa</h6>
                    <small class="text-muted">Per siswa — kelas pertama</small>
                </div>
                <div style="height:260px;">
                    <canvas id="chartGuru" height="260"></canvas>
                </div>
            </div>

            <div class="card shadow-sm border-0 mt-3 p-3">
                <div class="d-flex justify-content-between">
                    <h6 class="fw-semibold mb-0"><i class="fa fa-clock-rotate-left me-2"></i> Aktivitas Terbaru</h6>
                    <small class="text-muted">Transaksi terakhir</small>
                </div>

                <div class="mt-3">
                    <?php if (!empty($recentTransaksi)): ?>
                        <ul class="list-unstyled mb-0">
                            <?php foreach ($recentTransaksi as $t): ?>
                                <li class="d-flex justify-content-between py-2 border-bottom">
                                    <div>
                                        <div class="fw-semibold"><?= esc($t['nama']) ?> <small class="text-muted">[<?= esc($t['kelas']) ?>]</small></div>
                                        <div class="small text-secondary"><?= esc($t['keterangan'] ?? '-') ?></div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold"><?= $t['tipe'] === 'setor' ? '+' : '-' ?> Rp <?= number_format($t['jumlah'], 0, ',', '.') ?></div>
                                        <div class="small text-muted"><?= date('d M Y H:i', strtotime($t['created_at'])) ?></div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="text-muted small">Belum ada aktivitas terbaru.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm border-0 p-3">
                <h6 class="fw-semibold"><i class="fa fa-trophy me-2 text-warning"></i> Top Siswa (Saldo)</h6>
                <div class="mt-2">
                    <?php if (!empty($topSiswa)): ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach ($topSiswa as $i => $r): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div><span class="fw-semibold"><?= $i + 1 ?>.</span> <?= esc($r['nama']) ?></div>
                                    <div class="fw-semibold text-success">
                                        Rp <?= number_format($r['saldo'], 0, ',', '.') ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="text-muted small">Belum ada data saldo.</div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

</div>

<style>
    .premium-header {
        animation: fadeInDown .5s;
    }

    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-10px)
        }

        to {
            opacity: 1;
            transform: none
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart: fetch labels/values from endpoint
        fetch('<?= smart_url('guru/chart-data') ?>')
            .then(r => r.json())
            .then(j => {
                const ctx = document.getElementById('chartGuru').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: j.labels,
                        datasets: [{
                            label: 'Saldo (Rp)',
                            data: j.values,
                            backgroundColor: '#0d6efd'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                ticks: {
                                    callback: v => 'Rp ' + v.toLocaleString('id-ID')
                                }
                            }
                        }
                    }
                });
            }).catch(() => {
                /* ignore */
            });

        // refresh button
        document.getElementById('btnRefreshDashboard').addEventListener('click', () => location.reload());
    });
</script>

<?= $this->endSection() ?>