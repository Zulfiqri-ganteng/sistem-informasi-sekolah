<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<style>
    body {
        background: #f5f7fb !important;
    }

    .hero-box {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        color: white;
        padding: 28px;
        border-radius: 18px;
        box-shadow: 0 10px 25px rgba(79, 70, 229, .25);
        margin-bottom: 25px;
    }

    .stat-box {
        background: white;
        border-radius: 16px;
        padding: 18px;
        text-align: center;
        box-shadow: 0 4px 14px rgba(0, 0, 0, .08);
    }

    .stat-box h3 {
        font-weight: 700;
    }

    .stat-label {
        font-size: .8rem;
        color: #64748b;
    }

    .filter-select {
        border-radius: 10px;
        padding: 8px 14px;
        border: 1px solid #d0d7e2;
    }

    .badge-status {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: .75rem;
        font-weight: 600;
    }
</style>

<div class="container-fluid">

    <!-- HEADER -->
    <div class="hero-box">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3 class="fw-bold mb-1">
                    <i class="fa-solid fa-clock-rotate-left me-2"></i>Riwayat Absensi
                </h3>
                <div class="opacity-75">Semua aktivitas absensi sesuai hak akses Anda.</div>
            </div>

            <div>
                <select id="filterRange" class="filter-select">
                    <option value="today">Hari Ini</option>
                    <option value="yesterday">Kemarin</option>
                    <option value="week">Minggu Ini</option>
                    <option value="month">Bulan Ini</option>
                    <option value="all">Semua Waktu</option>
                </select>
            </div>
        </div>
    </div>

    <!-- STATISTIK -->
    <div class="row g-3 mb-4">

        <div class="col-md-2 col-6">
            <div class="stat-box">
                <h3 id="statHadir">0</h3>
                <div class="stat-label">Hadir</div>
            </div>
        </div>

        <div class="col-md-2 col-6">
            <div class="stat-box">
                <h3 id="statTerlambat">0</h3>
                <div class="stat-label">Terlambat</div>
            </div>
        </div>

        <div class="col-md-2 col-6">
            <div class="stat-box">
                <h3 id="statIzin">0</h3>
                <div class="stat-label">Izin</div>
            </div>
        </div>

        <div class="col-md-2 col-6">
            <div class="stat-box">
                <h3 id="statSakit">0</h3>
                <div class="stat-label">Sakit</div>
            </div>
        </div>

        <div class="col-md-2 col-6">
            <div class="stat-box">
                <h3 id="statPulang">0</h3>
                <div class="stat-label">Pulang Awal</div>
            </div>
        </div>

    </div>

    <!-- TABEL RIWAYAT -->
    <div class="card shadow-sm border-0">
        <div class="card-body">

            <table id="tableRiwayat" class="table table-striped table-bordered w-100">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Kelas</th>
                        <th>Status</th>
                        <th>Masuk</th>
                        <th>Pulang</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
    let table;

    function loadRiwayat(filter = "today") {
        if (table) table.destroy();

        table = $('#tableRiwayat').DataTable({
            ajax: {
                url: "<?= base_url('absensi/riwayatAjax') ?>",
                data: {
                    filter: filter
                },
                dataSrc: function(json) {

                    // HITUNG STATISTIK
                    let hadir = 0,
                        terlambat = 0,
                        izin = 0,
                        sakit = 0,
                        pulang = 0;

                    json.data.forEach(r => {
                        let st = r.status.toLowerCase();

                        if (["masuk", "hadir"].includes(st)) hadir++;
                        else if (st === "terlambat") terlambat++;
                        else if (st === "izin") izin++;
                        else if (st === "sakit") sakit++;
                        else if (["pulang", "pulang_awal"].includes(st)) pulang++;
                    });

                    $("#statHadir").text(hadir);
                    $("#statTerlambat").text(terlambat);
                    $("#statIzin").text(izin);
                    $("#statSakit").text(sakit);
                    $("#statPulang").text(pulang);

                    return json.data;
                }
            },

            columns: [{
                    data: 'created_at',
                    render: d => new Date(d).toLocaleString('id-ID')
                },
                {
                    data: 'nama'
                },
                {
                    data: 'tipe',
                    render: t => `<span class="badge bg-dark text-white">${t}</span>`
                },
                {
                    data: 'kelas'
                },

                {
                    data: null,
                    render: function(row) {
                        return `<span class="badge-status bg-${row.status_color}-subtle text-${row.status_color}">
                                ${row.status.toUpperCase()}
                            </span>`;
                    }
                },

                {
                    data: 'jam_masuk'
                },
                {
                    data: 'jam_pulang'
                }
            ],

            responsive: true,
            pageLength: 10,
            order: [
                [0, "desc"]
            ],
            language: {
                emptyTable: "Tidak ada data.",
                lengthMenu: "Tampilkan _MENU_ data",
                search: "Cari:",
                info: "Menampilkan _START_–_END_ dari _TOTAL_ data",
                paginate: {
                    previous: "<",
                    next: ">"
                }
            }
        });
    }

    $(document).ready(function() {

        loadRiwayat();

        $("#filterRange").change(function() {
            loadRiwayat($(this).val());
        });

    });
</script>

<?= $this->endSection() ?>