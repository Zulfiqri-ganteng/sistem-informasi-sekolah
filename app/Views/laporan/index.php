<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-navy mb-0">
            <i class="fa-solid fa-chart-line me-2"></i>Laporan Tabungan Sekolah
        </h3>
        <div>
            <button id="btnExportExcel" class="btn btn-success btn-sm me-2">
                <i class="fa-solid fa-file-excel me-1"></i> Excel
            </button>
            <button id="btnExportPdf" class="btn btn-danger btn-sm">
                <i class="fa-solid fa-file-pdf me-1"></i> PDF
            </button>
        </div>
    </div>

    <!-- Statistik -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 text-center animate__animated animate__fadeIn">
                <h6 class="fw-semibold text-success">Total Setoran</h6>
                <h3 id="totalSetor" class="text-success">Rp 0</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 text-center animate__animated animate__fadeIn animate__delay-1s">
                <h6 class="fw-semibold text-warning">Total Tarikan</h6>
                <h3 id="totalTarik" class="text-warning">Rp 0</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-3 text-center animate__animated animate__fadeIn animate__delay-2s">
                <h6 class="fw-semibold text-primary">Total Saldo</h6>
                <h3 id="totalSaldo" class="text-primary">Rp 0</h3>
            </div>
        </div>
    </div>

    <!-- Grafik -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-3"><i class="fa-solid fa-chart-pie me-1"></i> Grafik Saldo per Kelas</h6>
            <canvas id="chartSaldo" height="120"></canvas>
        </div>
    </div>

    <!-- Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Kelas</label>
                    <input type="text" id="filterKelas" class="form-control" placeholder="Semua">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Jurusan</label>
                    <input type="text" id="filterJurusan" class="form-control" placeholder="Semua">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Dari</label>
                    <input type="date" id="filterFrom" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Sampai</label>
                    <input type="date" id="filterTo" class="form-control">
                </div>
            </div>
            <div class="text-end mt-3">
                <button id="btnFilter" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-filter me-1"></i> Terapkan
                </button>
                <button id="btnReset" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-rotate me-1"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- DataTable -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table id="tableLaporan" class="table table-striped table-hover w-100 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th class="text-end">Setor (Rp)</th>
                        <th class="text-end">Tarik (Rp)</th>
                        <th class="text-end">Saldo (Rp)</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(function() {
        const base = '<?= smart_url("index.php") ?>';
        let chartInstance;

        const table = $('#tableLaporan').DataTable({
            ajax: {
                url: base + '/laporan/data',
                data: d => {
                    d.kelas = $('#filterKelas').val();
                    d.jurusan = $('#filterJurusan').val();
                    d.from = $('#filterFrom').val();
                    d.to = $('#filterTo').val();
                },
                dataSrc: json => {
                    const meta = json.meta || {
                        totalSetor: 0,
                        totalTarik: 0,
                        totalSaldo: 0
                    };
                    $('#totalSetor').text('Rp ' + meta.totalSetor.toLocaleString('id-ID'));
                    $('#totalTarik').text('Rp ' + meta.totalTarik.toLocaleString('id-ID'));
                    $('#totalSaldo').text('Rp ' + meta.totalSaldo.toLocaleString('id-ID'));
                    updateChart(json.data);
                    return json.data || [];
                },
                error: () => console.warn('Gagal memuat data laporan.')
            },
            columns: [{
                    data: null,
                    render: (d, t, r, m) => m.row + 1,
                    className: 'text-center'
                },
                {
                    data: 'nama'
                },
                {
                    data: 'kelas'
                },
                {
                    data: 'jurusan'
                },
                {
                    data: 'total_setor',
                    className: 'text-end',
                    render: d => 'Rp ' + Number(d).toLocaleString('id-ID')
                },
                {
                    data: 'total_tarik',
                    className: 'text-end',
                    render: d => 'Rp ' + Number(d).toLocaleString('id-ID')
                },
                {
                    data: 'saldo',
                    className: 'text-end fw-bold',
                    render: d => 'Rp ' + Number(d).toLocaleString('id-ID')
                }
            ]
        });

        $('#btnFilter').click(() => table.ajax.reload());
        $('#btnReset').click(() => {
            $('#filterKelas,#filterJurusan,#filterFrom,#filterTo').val('');
            table.ajax.reload();
        });

        $('#btnExportExcel').click(() => window.location = base + '/laporan/export-excel');
        $('#btnExportPdf').click(() => window.location = base + '/laporan/export-pdf');

        // ========================
        // CHART SALDO PER KELAS
        // ========================
        function updateChart(data) {
            const grouped = {};
            data.forEach(r => {
                if (!grouped[r.kelas]) grouped[r.kelas] = 0;
                grouped[r.kelas] += parseFloat(r.saldo || 0);
            });
            const labels = Object.keys(grouped);
            const values = Object.values(grouped);
            const ctx = document.getElementById('chartSaldo').getContext('2d');

            if (chartInstance) chartInstance.destroy();
            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels.length ? labels : ['Belum Ada Data'],
                    datasets: [{
                        label: 'Saldo Total per Kelas',
                        data: values.length ? values : [0],
                        backgroundColor: '#0d6efd80',
                        borderColor: '#0d6efd',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    });
</script>

<style>
    .text-navy {
        color: #0f2340;
    }

    .table-striped>tbody>tr:nth-of-type(odd)>* {
        background-color: rgba(13, 110, 253, .03);
    }
</style>

<?= $this->endSection(); ?>