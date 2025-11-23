<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<style>
    body {
        background: #f1f5f9 !important;
    }

    .hero-card {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        border-radius: 16px;
        padding: 25px;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 30px rgba(79, 70, 229, 0.25);
    }

    .hero-card .circle-deco {
        position: absolute;
        width: 180px;
        height: 180px;
        background: rgba(255, 255, 255, 0.09);
        border-radius: 50%;
        top: -40px;
        right: -30px;
    }

    .pro-card {
        background: white;
        border-radius: 14px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06);
    }

    .badge-status {
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 600;
    }
</style>

<div class="container-fluid py-4 px-3 px-md-4">

    <div class="hero-card mb-4 animate__animated animate__fadeInDown">
        <div class="circle-deco"></div>
        <h3 class="fw-bold mb-1">
            <i class="fa-solid fa-clock-rotate-left me-2"></i>
            Riwayat Absensi
        </h3>
        <p class="mb-0 opacity-75">
            Semua aktivitas absensi sesuai hak akses Anda.
        </p>
    </div>

    <div class="pro-card animate__animated animate__fadeInUp">
        <div class="d-flex justify-content-between mb-3">
            <h5 class="fw-bold mb-0">
                <i class="fa-solid fa-list-check text-primary me-2"></i>
                Data Riwayat Absensi
            </h5>
        </div>

        <div class="table-responsive">
            <table id="tableRiwayat" class="table table-striped table-bordered w-100">
                <thead class="bg-light">
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

<script>
    $(document).ready(function() {

        $('#tableRiwayat').DataTable({
            ajax: {
                url: "<?= base_url('absensi/riwayatAjax') ?>",
                dataSrc: 'data'
            },

            columns: [{
                    data: 'created_at',
                    render: d => new Date(d).toLocaleString('id-ID')
                },
                {
                    data: 'nama' // Mengambil dari kunci 'nama' di JSON
                },

                {
                    data: 'role',
                    render: role =>
                        role === 'siswa' ?
                        `<span class="badge bg-primary bg-opacity-10 text-primary">Siswa</span>` : `<span class="badge bg-dark text-white">Guru</span>`
                },

                {
                    data: 'kelas', // Mengambil dari kunci 'kelas' di JSON
                    defaultContent: '-'
                },

                {
                    data: 'status',
                    render: function(status) {

                        let color = "secondary";
                        if (status === "masuk") color = "success";
                        if (status === "terlambat") color = "warning";
                        if (status === "izin") color = "info";
                        if (status === "sakit") color = "primary";
                        if (status === "pulang_awal") color = "danger";

                        return `<span class="badge-status bg-${color}-subtle text-${color}">${status.toUpperCase()}</span>`;
                    }
                },

                {
                    data: 'jam_masuk',
                    defaultContent: '-'
                },
                {
                    data: 'jam_pulang',
                    defaultContent: '-'
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

    });
</script>

<?= $this->endSection(); ?>