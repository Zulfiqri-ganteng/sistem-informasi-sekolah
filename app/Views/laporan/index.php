<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-navy mb-0">
            <i class="fa-solid fa-chart-line me-2"></i>Laporan Tabungan Sekolah
        </h3>
        <div>
            <button id="btnExportExcel" class="btn btn-success btn-sm me-2"><i class="fa-solid fa-file-excel me-1"></i> Excel</button>
            <button id="btnExportPdf" class="btn btn-danger btn-sm me-2"><i class="fa-solid fa-file-pdf me-1"></i> PDF</button>
            <button id="btnExportWord" class="btn btn-secondary btn-sm"><i class="fa-solid fa-file-word me-1"></i> Word</button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <h6 class="fw-semibold text-success">Total Setoran</h6>
                <h3 id="totalSetor">Rp 0</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <h6 class="fw-semibold text-warning">Total Tarikan</h6>
                <h3 id="totalTarik">Rp 0</h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 text-center">
                <h6 class="fw-semibold text-primary">Total Saldo</h6>
                <h3 id="totalSaldo">Rp 0</h3>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-3"><i class="fa-solid fa-chart-pie me-1"></i> Grafik Saldo per Kelas / Bulanan</h6>
            <div class="row">
                <div class="col-md-8">
                    <canvas id="chartSaldo" height="150"></canvas>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pilih Siswa (lihat saldo per siswa)</label>
                    <select id="selectSiswa" class="form-select"></select>

                    <hr />

                    <div>
                        <strong>Detail Bulanan (thn):</strong>
                        <select id="selectYear" class="form-select mt-2">
                            <?php for ($y = date('Y'); $y >= date('Y') - 4; $y--): ?>
                                <option value="<?= $y ?>"><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Kelas</label>
                    <select id="filterKelas" class="form-select">
                        <option value="">Semua</option>
                        <?php foreach ($lists['kelas'] ?? [] as $k): ?>
                            <option><?= esc($k) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Jurusan</label>
                    <select id="filterJurusan" class="form-select">
                        <option value="">Semua</option>
                        <?php foreach ($lists['jurusan'] ?? [] as $j): ?>
                            <option><?= esc($j) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Dari</label>
                    <input type="date" id="filterFrom" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Sampai</label>
                    <input type="date" id="filterTo" class="form-control">
                </div>
                <div class="col-md-2 text-end">
                    <button id="btnFilter" class="btn btn-primary mt-1"><i class="fa-solid fa-filter me-1"></i> Terapkan</button>
                    <button id="btnReset" class="btn btn-outline-secondary mt-1"><i class="fa-solid fa-rotate me-1"></i> Reset</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card mb-5">
        <div class="card-body">
            <table id="tableLaporan" class="table table-striped table-hover w-100">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th class="text-end">Setor (Rp)</th>
                        <th class="text-end">Tarik (Rp)</th>
                        <th class="text-end">Saldo (Rp)</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen-sm-down modal-xl modal-dialog-slideout">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detailInfo" class="mb-3"></div>
                <table class="table table-sm table-bordered" id="detailTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Tipe</th>
                            <th>Keterangan</th>
                            <th class="text-end">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button id="btnPrintDetail" class="btn btn-outline-secondary btn-sm">Print</button>
                <button class="btn btn-primary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(function() {
        const base = '<?= smart_url() ?>';
        let chartInstance;

        // init select2
        $('#filterKelas, #filterJurusan, #selectSiswa').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        // populate siswa list (for quick per-siswa chart) - use AJAX to avoid heavy load
        function loadSiswaList() {
            $.getJSON(base + '/api/siswa/list') // you can create this endpoint OR reuse existing; fallback to table scan later
                .done(res => {
                    $('#selectSiswa').empty().append('<option value="">-- Pilih Siswa (Semua) --</option>');
                    if (Array.isArray(res)) {
                        res.forEach(s => {
                            $('#selectSiswa').append(`<option value="${s.id}">${s.nama} — ${s.kelas}</option>`);
                        });
                    } else if (res.data) {
                        res.data.forEach(s => $('#selectSiswa').append(`<option value="${s.id}">${s.nama} — ${s.kelas}</option>`));
                    }
                })
                .fail(() => {
                    // fallback: try to build from datatable later
                });
        }

        // DataTable
        const table = $('#tableLaporan').DataTable({
            ajax: {
                url: base + '/laporan/data',
                data: d => {
                    // normalize: empty string => null in controller
                    d.kelas = $('#filterKelas').val() || '';
                    d.jurusan = $('#filterJurusan').val() || '';
                    d.from = $('#filterFrom').val() || '';
                    d.to = $('#filterTo').val() || '';
                },
                dataSrc: json => {
                    const meta = json.meta || {
                        totalSetor: 0,
                        totalTarik: 0,
                        totalSaldo: 0
                    };
                    $('#totalSetor').text('Rp ' + Number(meta.totalSetor || 0).toLocaleString('id-ID'));
                    $('#totalTarik').text('Rp ' + Number(meta.totalTarik || 0).toLocaleString('id-ID'));
                    $('#totalSaldo').text('Rp ' + Number(meta.totalSaldo || 0).toLocaleString('id-ID'));
                    updateChart(json.monthly || {});
                    return json.data || [];
                },
                error: () => console.warn('Gagal memuat data laporan.')
            },
            columns: [{
                    data: null,
                    render: (d, i, full, meta) => meta.row + 1,
                    className: 'text-center'
                },
                {
                    data: 'nama',
                    render: (d, t, r) => `<a href="#" class="link-detail" data-id="${r.id}">${d}</a>`
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
                    render: d => 'Rp ' + Number(d || 0).toLocaleString('id-ID')
                },
                {
                    data: 'total_tarik',
                    className: 'text-end',
                    render: d => 'Rp ' + Number(d || 0).toLocaleString('id-ID')
                },
                {
                    data: 'saldo',
                    className: 'text-end fw-bold',
                    render: d => 'Rp ' + Number(d || 0).toLocaleString('id-ID')
                },
                {
                    data: null,
                    className: 'text-center',
                    render: d => `<button class="btn btn-sm btn-outline-primary btnDetail" data-id="${d.id}"><i class="fa fa-eye"></i></button>`
                }
            ],
            pageLength: 25,
            responsive: true
        });

        $('#btnFilter').click(() => table.ajax.reload());
        $('#btnReset').click(() => {
            $('#filterKelas,#filterJurusan,#filterFrom,#filterTo').val('').trigger('change');
            table.ajax.reload();
        });

        // Exports
        function buildExportUrl(endpoint) {
            const params = $.param({
                kelas: $('#filterKelas').val() || '',
                jurusan: $('#filterJurusan').val() || '',
                from: $('#filterFrom').val() || '',
                to: $('#filterTo').val() || ''
            });
            return base + '/laporan/' + endpoint + (params ? ('?' + params) : '');
        }
        $('#btnExportExcel').click(() => window.location = '<?= base_url("laporan/export-excel") ?>');
        $('#btnExportPdf').click(() => window.location = '<?= base_url("laporan/export-pdf") ?>');
        $('#btnExportWord').click(() => window.location = '<?= base_url("laporan/export-word") ?>');


        // Detail handler
        $(document).on('click', '.link-detail, .btnDetail', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            if (!id) return;
            $('#detailTable tbody').html('');
            $('#detailInfo').html('Memuat...');
            $('#modalDetail').modal('show');

            $.getJSON(base + '/laporan/detail/' + id)
                .done(res => {
                    const rows = res.data || [];
                    const nama = rows.length ? (rows[0].siswa_nama || '') : '';
                    $('#detailInfo').html(`<strong>${nama}</strong>`);
                    let html = '';
                    rows.forEach((r, i) => {
                        html += `<tr>
                        <td>${i+1}</td>
                        <td>${r.created_at ? (new Date(r.created_at).toLocaleString('id-ID')) : ''}</td>
                        <td>${r.tipe}</td>
                        <td>${r.keterangan || '-'}</td>
                        <td class="text-end">${Number(r.jumlah || 0).toLocaleString('id-ID')}</td>
                    </tr>`;
                    });
                    if (!html) html = '<tr><td colspan="5" class="text-center">Belum ada transaksi</td></tr>';
                    $('#detailTable tbody').html(html);
                })
                .fail(() => {
                    $('#detailInfo').html('Gagal memuat detail.');
                });
        });

        $('#btnPrintDetail').click(() => {
            const w = window.open('', '_blank');
            const content = document.querySelector('#modalDetail .modal-body').innerHTML;
            w.document.open();
            w.document.write(`<html><head><meta charset="utf-8"><title>Detail Transaksi</title></head><body>${content}</body></html>`);
            w.document.close();
            w.print();
        });

        // Chart update
        function updateChart(monthlyObj) {
            const labels = Object.keys(monthlyObj).sort();
            const values = labels.map(k => monthlyObj[k] || 0);
            const ctx = document.getElementById('chartSaldo').getContext('2d');
            if (chartInstance) chartInstance.destroy();
            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Saldo (per bulan)',
                        data: values,
                        backgroundColor: '#0d6efd88',
                        borderColor: '#0d6efd',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Populate siswa list (fallback: extract from table after load)
        function ensureSiswaList() {
            // if selectSiswa empty, populate with current datatable rows
            if ($('#selectSiswa option').length <= 1) {
                const rows = table.rows().data().toArray();
                $('#selectSiswa').empty().append('<option value="">-- Semua --</option>');
                rows.forEach(r => $('#selectSiswa').append(`<option value="${r.id}">${r.nama} — ${r.kelas}</option>`));
                $('#selectSiswa').trigger('change');
            }
        }

        // reload siswa list after table draw
        table.on('draw', function() {
            ensureSiswaList();
        });

        // change year - reload chart by hitting /laporan/data with year param if needed
        $('#selectYear').change(() => table.ajax.reload());

        // initial actions
        loadSiswaList();
        table.ajax.reload();
    });
</script>

<style>
    /* small modal slideout style */
    .modal-dialog-slideout {
        transform: translateX(100%);
        transition: transform .35s ease;
    }

    .modal.show .modal-dialog-slideout {
        transform: translateX(0);
    }
</style>

<?= $this->endSection(); ?>