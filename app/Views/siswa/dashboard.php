<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid py-4 animate__animated animate__fadeIn">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <h3 class="fw-bold text-navy mb-2">
            <i class="fa-solid fa-user-graduate me-2 text-primary"></i> Dashboard Siswa
        </h3>
        <button id="btnRefreshDashboard" class="btn btn-outline-primary btn-sm">
            <i class="fa-solid fa-rotate me-1"></i> Refresh Data
        </button>
    </div>

    <!-- KPI CARDS -->
    <div class="row g-4 mb-4">
        <div class="col-md-4 col-sm-6">
            <div class="card kpi-card shadow-sm border-0 text-center p-4">
                <div class="icon bg-success-subtle text-success rounded-circle mx-auto mb-3">
                    <i class="fa-solid fa-wallet fa-lg"></i>
                </div>
                <h6 class="text-muted fw-semibold mb-1">Saldo Tabungan</h6>
                <h2 class="fw-bold text-success mb-0">Rp <?= number_format($saldo, 0, ',', '.'); ?></h2>
                <small class="text-secondary">Saldo saat ini</small>
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="card kpi-card shadow-sm border-0 text-center p-4">
                <div class="icon bg-primary-subtle text-primary rounded-circle mx-auto mb-3">
                    <i class="fa-solid fa-arrow-down-long fa-lg"></i>
                </div>
                <h6 class="text-muted fw-semibold mb-1">Total Setoran</h6>
                <h3 class="fw-bold text-primary mb-0">Rp <?= number_format($total_setor, 0, ',', '.'); ?></h3>
            </div>
        </div>

        <div class="col-md-4 col-sm-6">
            <div class="card kpi-card shadow-sm border-0 text-center p-4">
                <div class="icon bg-danger-subtle text-danger rounded-circle mx-auto mb-3">
                    <i class="fa-solid fa-arrow-up-long fa-lg"></i>
                </div>
                <h6 class="text-muted fw-semibold mb-1">Total Penarikan</h6>
                <h3 class="fw-bold text-danger mb-0">Rp <?= number_format($total_tarik, 0, ',', '.'); ?></h3>
            </div>
        </div>
    </div>

    <!-- GRAFIK -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white fw-bold d-flex align-items-center">
            <i class="fa-solid fa-chart-line me-2"></i> Grafik Setoran Bulanan
        </div>
        <div class="card-body">
            <canvas id="chartTabungan" height="100"></canvas>
        </div>
    </div>

    <!-- TRANSAKSI TERBARU -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white fw-bold d-flex align-items-center">
            <i class="fa-solid fa-clock-rotate-left me-2 text-warning"></i> Riwayat Transaksi Terakhir
        </div>
        <div class="card-body">
            <?php if (!empty($transaksi)): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Tipe</th>
                                <th class="text-end">Jumlah (Rp)</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transaksi as $t): ?>
                                <tr>
                                    <td><?= date('d M Y H:i', strtotime($t['created_at'])); ?></td>
                                    <td>
                                        <span class="badge <?= $t['tipe'] == 'setor' ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                            <?= ucfirst($t['tipe']); ?>
                                        </span>
                                    </td>
                                    <td class="text-end fw-semibold"><?= number_format($t['jumlah'], 0, ',', '.'); ?></td>
                                    <td><?= esc($t['keterangan'] ?? '-'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info mb-0">
                    <i class="fa-solid fa-circle-info me-1"></i> Belum ada transaksi.
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chartTabungan').getContext('2d');
    const dataBulan = <?= json_encode($chartData) ?>;
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Setoran per Bulan',
                data: dataBulan,
                backgroundColor: 'rgba(13,110,253,0.6)',
                borderColor: '#0d6efd',
                borderWidth: 2,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: v => 'Rp ' + v.toLocaleString('id-ID')
                    }
                }
            }
        }
    });

    // Tombol refresh
    document.getElementById('btnRefreshDashboard').addEventListener('click', () => location.reload());
</script>

<style>
    .kpi-card {
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
    }
</style>

<?= $this->endSection(); ?>