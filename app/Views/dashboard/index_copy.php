<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid py-3">

    <div class="m3-header-compact mb-3 p-3 rounded-2 shadow-elev-2">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="m3-avatar-compact overflow-hidden d-flex align-items-center justify-content-center">
                    <?php
                    $foto = session('foto');
                    $path = FCPATH . 'uploads/admin/' . $foto;
                    if (!empty($foto) && file_exists($path)) {
                        $fotoUrl = smart_url('uploads/admin/' . $foto);
                    } else {
                        $fotoUrl = smart_url('uploads/admin/default.png');
                    }
                    ?>
                    <img src="<?= $fotoUrl ?>" alt="user" class="m3-avatar-img-compact">
                </div>

                <div class="d-flex flex-column">
                    <div class="fw-semibold m3-title-compact">Selamat Datang, <?= esc(session('nama') ?? 'Admin') ?></div>
                    <div class="small text-muted">Sistem Informasi Sekolah</div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 m3-counters-compact">
                <div class="text-center">
                    <div class="small text-muted">Siswa</div>
                    <div class="h6 fw-bold mb-0"><?= $jumlahSiswa ?? 0 ?></div>
                </div>
                <div class="text-center">
                    <div class="small text-muted">Guru</div>
                    <div class="h6 fw-bold mb-0"><?= $jumlahGuru ?? 0 ?></div>
                </div>
                <div class="text-center">
                    <div class="small text-muted">Kelas</div>
                    <div class="h6 fw-bold mb-0"><?= $jumlahKelas ?? 0 ?></div>
                </div>
                <div class="text-center d-none d-md-block">
                    <div class="small text-muted">Saldo</div>
                    <div class="h6 fw-bold mb-0">Rp <?= number_format($totalTabungan ?? 0, 0, ',', '.') ?></div>
                </div>

                <button id="btnRefreshDashboard" class="btn btn-outline-primary btn-sm ms-1">
                    <i class="fa-solid fa-rotate me-1"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="row mb-3 g-2">
        <div class="col-6 col-md-3">
            <select id="filterJurusan" class="form-select">
                <option value="all">Semua Jurusan</option>
                <?php foreach ($jurusanList as $j): ?>
                    <option value="<?= $j ?>" <?= ($selectedJurusan ?? 'all') === $j ? 'selected' : '' ?>>
                        <?= $j ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-6 col-md-3">
            <select id="filterKelas" class="form-select">
                <option value="all">Semua Kelas</option>
                <?php
                $selectedKelasFiltered = [];
                // Filter kelasList berdasarkan jurusan yang dipilih saat ini
                foreach ($kelasList as $k) {
                    // Jika tidak ada jurusan yang dipilih (all) atau jurusannya cocok
                    if (($selectedJurusan === 'all') || ($k['jurusan'] === $selectedJurusan)) {
                        $selectedKelasFiltered[] = $k;
                    }
                }
                ?>
                <?php foreach ($selectedKelasFiltered as $k): ?>
                    <option value="<?= $k['kelas'] ?>" <?= ($selectedKelas ?? 'all') === $k['kelas'] ? 'selected' : '' ?>>
                        <?= $k['kelas'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>


    </div>

    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="m3-panel-compact">
                <div class="m3-panel-header-compact d-flex align-items-center justify-content-between">
                    <div class="fw-semibold"><i class="fa-solid fa-user-check me-1"></i> Absensi Hari Ini</div>
                    <small class="text-muted"><?= date('d M Y', strtotime($today ?? date('Y-m-d'))) ?></small>
                </div>

                <div class="m3-panel-body-compact p-3">
                    <div class="row g-2">
                        <div class="col-6 col-md-2">
                            <div class="m3-card-compact text-center">
                                <small class="text-muted d-block">Hadir</small>
                                <div class="fw-bold display-6-sm text-success" id="countHadir"><?= $hadir ?? 0 ?></div>
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div class="m3-card-compact text-center">
                                <small class="text-muted d-block">Terlambat</small>
                                <div class="fw-bold display-6-sm text-warning" id="countTelat"><?= $telat ?? 0 ?></div>
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div class="m3-card-compact text-center">
                                <small class="text-muted d-block">Izin</small>
                                <div class="fw-bold display-6-sm text-info" id="countIzin"><?= $izin ?? 0 ?></div>
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div class="m3-card-compact text-center">
                                <small class="text-muted d-block">Sakit</small>
                                <div class="fw-bold display-6-sm text-primary" id="countSakit"><?= $sakit ?? 0 ?></div>
                            </div>
                        </div>
                        <div class="col-6 col-md-2">
                            <div class="m3-card-compact text-center">
                                <small class="text-muted d-block">Pulang Awal</small>
                                <div class="fw-bold display-6-sm text-danger" id="countPulangAwal"><?= $pulang_awal ?? 0 ?></div>
                            </div>
                        </div>
                        <div class="col-12 col-md-12 mt-2">
                            <div class="table-responsive" style="max-height:340px; overflow:auto;">
                                <table id="tableRekapAbsensi" class="table table-sm table-striped table-hover mb-0 w-100">
                                    <thead class="table-light small">
                                        <tr>
                                            <th>Nama</th>
                                            <th>Jenis</th>
                                            <th class="d-none d-md-table-cell">Kelas</th>
                                            <th>Masuk</th>
                                            <th>Pulang</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-2 mb-3">
        <div class="col-6 col-md-3">
            <div class="m3-card-compact">
                <small class="text-muted d-block">Siswa Aktif</small>
                <div class="fw-bold display-6-sm"><?= $jumlahSiswa ?? 0 ?></div>
                <div class="spark-wrap" style="height:36px;"><canvas id="sparkSiswa"></canvas></div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="m3-card-compact">
                <small class="text-muted d-block">Transaksi (7d)</small>
                <div class="fw-bold display-6-sm"><?= array_sum($sparkTransaksi ?? []) ?? ($transaksiBulan ?? 0) ?></div>
                <div class="spark-wrap" style="height:36px;"><canvas id="sparkTransaksi"></canvas></div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="m3-card-compact">
                <small class="text-muted d-block">Penerimaan</small>
                <div class="fw-bold display-6-sm">Rp <?= number_format($penerimaanHari ?? 0, 0, ',', '.') ?></div>
                <div class="spark-wrap" style="height:36px;"><canvas id="sparkPenerimaan"></canvas></div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="m3-card-compact">
                <small class="text-muted d-block">Saldo</small>
                <div class="fw-bold display-6-sm">Rp <?= number_format($totalTabungan ?? 0, 0, ',', '.') ?></div>
                <div class="spark-wrap" style="height:36px;"><canvas id="sparkSaldo"></canvas></div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="m3-panel-compact mb-2">
                <div class="m3-panel-header-compact d-flex align-items-center justify-content-between">
                    <div class="fw-semibold"><i class="fa-solid fa-chart-column me-1"></i> Grafik Tabungan Tahunan</div>
                    <small class="text-muted">Per bulan</small>
                </div>
                <div class="m3-panel-body-compact">
                    <canvas id="chartTabungan" height="90"></canvas>
                </div>
            </div>

            <div class="m3-panel-compact mb-2">
                <div class="m3-panel-header-compact fw-semibold">
                    <i class="fa-solid fa-trophy me-1 text-warning"></i> Top 5 Penabung
                </div>
                <div class="m3-panel-body-compact p-1">
                    <?php if (!empty($topSavers)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="table-light small">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th class="d-none d-md-table-cell">Kelas</th>
                                        <th class="text-end">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($topSavers as $i => $s): ?>
                                        <tr>
                                            <td><?= $i + 1 ?></td>
                                            <td><?= esc($s['nama']) ?></td>
                                            <td class="d-none d-md-table-cell"><?= esc($s['kelas']) ?></td>
                                            <td class="text-end text-success">Rp <?= number_format($s['saldo'] ?? 0, 0, ',', '.') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="small text-muted p-2">Belum ada data penabung.</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="m3-panel-compact mb-2">
                <div class="m3-panel-header-compact bg-info text-white fw-semibold">
                    <i class="fa-solid fa-school me-1"></i> Statistik per Kelas
                </div>
                <div class="m3-panel-body-compact">
                    <?php if (!empty($perKelas)): ?>
                        <div class="row">
                            <div class="col-md-6">
                                <canvas id="chartPerKelas" height="160"></canvas>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group list-group-flush small">
                                    <?php foreach ($perKelas as $r): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div><?= esc($r['kelas']) ?></div>
                                            <div class="fw-semibold text-success">Rp <?= number_format($r['total'] ?? 0, 0, ',', '.') ?></div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="small text-muted p-2">Belum ada data per kelas.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="m3-panel-compact mb-2">
                <div class="m3-panel-header-compact d-flex align-items-center justify-content-between">
                    <div><i class="fa-solid fa-clock-rotate-left me-1"></i> Transaksi</div>
                    <a href="<?= smart_url('tabungan') ?>" class="btn btn-light btn-sm text-primary"><i class="fa-solid fa-table-list"></i></a>
                </div>
                <div class="m3-panel-body-compact p-1">
                    <div class="table-responsive" style="max-height:300px;overflow:auto;">
                        <table id="tableTransaksi" class="table table-sm table-striped table-hover mb-0 w-100 small">
                            <thead class="table-light">
                                <tr>
                                    <th>Tgl</th>
                                    <th>Nama</th>
                                    <th>Tipe</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
<style>
    :root {
        --m3-surface: #ffffff;
        --m3-primary: #0b5ed7;
        --m3-muted: #6b7280;
        --radius-sm: 10px;
    }

    .m3-header-compact {
        background: linear-gradient(90deg, rgba(11, 58, 102, 0.04), rgba(11, 58, 102, 0.01));
        border-left: 3px solid var(--m3-primary);
        border-radius: 10px;
        padding: 10px;
        box-shadow: 0 6px 18px rgba(11, 26, 46, 0.06);
    }

    .m3-avatar-compact {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid rgba(11, 37, 77, 0.05);
    }

    .m3-avatar-img-compact {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .m3-title-compact {
        font-size: 1rem;
    }

    .m3-counters-compact {
        align-items: center;
        gap: 8px;
    }

    .m3-card-compact {
        background: var(--m3-surface);
        padding: 10px;
        border-radius: 10px;
        box-shadow: 0 6px 14px rgba(11, 26, 46, 0.06);
        border: 1px solid rgba(11, 37, 77, 0.04);
        height: 100%;
    }

    .display-6-sm {
        font-size: 1.15rem;
        margin: .25rem 0;
        font-weight: 700;
    }

    .spark-wrap {
        width: 120px;
    }

    .m3-panel-compact {
        background: var(--m3-surface);
        border-radius: 12px;
        box-shadow: 0 8px 26px rgba(11, 26, 46, 0.06);
        border: 1px solid rgba(11, 37, 77, 0.04);
        overflow: hidden;
    }

    .m3-panel-header-compact {
        padding: 8px 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(11, 37, 77, 0.03);
    }

    .m3-panel-body-compact {
        padding: 8px 10px;
    }

    table.table-sm td,
    table.table-sm th {
        padding: .35rem .5rem;
        vertical-align: middle;
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 8px;
            padding-right: 8px;
        }

        .m3-header-compact {
            padding: 8px;
        }

        .m3-panel-body-compact {
            padding: 6px;
        }

        .m3-card-compact {
            padding: 8px;
        }

        .spark-wrap {
            width: 100px;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // Sparklines data
        const sparkSiswaData = <?= json_encode($sparkSiswa ?? [5, 8, 6, 9, 12, 10, 14]) ?>;
        const sparkTransaksiData = <?= json_encode($sparkTransaksi ?? [2, 3, 4, 6, 5, 7, 8]) ?>;
        const sparkPenerimaanData = <?= json_encode($sparkPenerimaan ?? [100000, 120000, 90000, 150000, 130000, 110000, 170000]) ?>;
        const sparkSaldoData = <?= json_encode($sparkSaldo ?? [500000, 520000, 540000, 530000, 560000, 590000, 610000]) ?>;

        function createSpark(id, data, color) {
            const c = document.getElementById(id);
            if (!c) return;
            new Chart(c.getContext('2d'), {
                type: 'line',
                data: {
                    labels: data.map((_, i) => i + 1),
                    datasets: [{
                        data: data,
                        fill: false,
                        borderColor: color,
                        tension: .3,
                        pointRadius: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            display: false
                        },
                        y: {
                            display: false
                        }
                    }
                }
            });
        }

        createSpark('sparkSiswa', sparkSiswaData, '#0b3a66');
        createSpark('sparkTransaksi', sparkTransaksiData, '#198754');
        createSpark('sparkPenerimaan', sparkPenerimaanData, '#d4af37');
        createSpark('sparkSaldo', sparkSaldoData, '#20c997');

        // Main annual chart
        const dataBulan = <?= json_encode($chartData ?? array_fill(0, 12, 0)) ?>;
        const ctx = document.getElementById('chartTabungan').getContext('2d');
        const grad = ctx.createLinearGradient(0, 0, 0, 240);
        grad.addColorStop(0, 'rgba(11,94,215,0.9)');
        grad.addColorStop(1, 'rgba(11,94,215,0.06)');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Total Tabungan',
                    data: dataBulan,
                    backgroundColor: grad,
                    borderColor: '#0b5ed7',
                    borderWidth: 1,
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
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: v => 'Rp ' + v.toLocaleString('id-ID')
                        }
                    }
                }
            }
        });

        // Per-kelas doughnut
        const perKelasData = <?= json_encode($perKelas ?? []) ?>;
        if (perKelasData.length > 0) {
            const ctx2 = document.getElementById('chartPerKelas').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: perKelasData.map(r => r.kelas),
                    datasets: [{
                        data: perKelasData.map(r => r.total),
                        backgroundColor: ['#0b5ed7', '#198754', '#ffc107', '#dc3545', '#6f42c1', '#20c997', '#fd7e14', '#6610f2', '#0dcaf0', '#adb5bd']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: ctx => `${ctx.label}: Rp ${ctx.parsed.toLocaleString('id-ID')}`
                            }
                        }
                    }
                }
            });
        }

        // DataTables for transaksi
        $('#tableTransaksi').DataTable({
            ajax: {
                url: '<?= smart_url('dashboard/transaksiAjax') ?>',
                dataSrc: 'data'
            },
            columns: [{
                    data: 'created_at',
                    render: d => new Date(d).toLocaleString('id-ID')
                },
                {
                    data: 'nama',
                    defaultContent: '-'
                },
                {
                    data: 'tipe',
                    render: d => `<span class="badge ${d==='setor' ? 'bg-success' : 'bg-warning text-dark'}">${d}</span>`
                },
                {
                    data: 'jumlah',
                    render: d => 'Rp ' + parseInt(d).toLocaleString('id-ID'),
                    className: 'text-end'
                }
            ],
            responsive: true,
            pageLength: 5,
            order: [
                [0, 'desc']
            ],
            language: {
                emptyTable: "Belum ada transaksi",
                lengthMenu: "Tampilkan _MENU_ data",
                search: "Cari:",
                info: "Menampilkan _START_–_END_ dari _TOTAL_ transaksi",
                paginate: {
                    previous: "<",
                    next: ">"
                }
            }
        });

        // Refresh button
        document.getElementById('btnRefreshDashboard').addEventListener('click', () => {
            Swal.fire({
                toast: true,
                icon: 'info',
                title: 'Memuat...',
                position: 'top-end',
                showConfirmButton: false,
                timer: 800
            });
            setTimeout(() => location.reload(), 700);
        });

        // DataTables untuk Rekap Absensi (dipindahkan ke sini)
        let tableAbsensi = $('#tableRekapAbsensi').DataTable({
            processing: true,
            serverSide: false, // Diganti ke false karena data sudah di-render atau akan di-load melalui JS biasa
            // Menggunakan data yang sudah di-render oleh PHP
            data: <?= json_encode($rekap ?? []) ?>,
            columns: [{
                    data: 'nama'
                },
                {
                    data: 'user_type',
                    render: d => d.charAt(0).toUpperCase() + d.slice(1)
                },
                {
                    data: 'kelas',
                    defaultContent: '-',
                    className: 'd-none d-md-table-cell'
                },
                {
                    data: 'jam_masuk',
                    defaultContent: '-'
                },
                {
                    data: 'jam_pulang',
                    defaultContent: '-'
                },
                {
                    data: 'status',
                    render: function(status) {
                        if (status === 'terlambat')
                            return `<span class="badge bg-warning text-dark fw-bold">TERLAMBAT</span>`;
                        if (status === 'masuk')
                            return `<span class="badge bg-success fw-bold">MASUK</span>`;
                        return `<span class="badge bg-secondary fw-bold">${status.toUpperCase()}</span>`;
                    }
                }
            ],
            // Matikan fitur Datatables yang mengganggu
            paging: false,
            searching: false,
            info: false,
            responsive: true,
            order: [
                [3, 'asc']
            ],
            language: {
                emptyTable: "Belum ada data absensi hari ini."
            }
        });


        // Fungsi untuk me-load dropdown Kelas berdasarkan Jurusan
        function loadKelas(jurusan) {
            // URL yang benar harus memanggil controller/kelas
            $.get("<?= smart_url('dashboard/kelas') ?>/" + encodeURIComponent(jurusan), function(response) {
                let html = '<option value="all">Semua Kelas</option>';
                response.kelas.forEach(k => {
                    html += `<option value="${k}">${k}</option>`;
                });
                $('#filterKelas').html(html);
                // Setelah kelas di-load, reset filter kelas
                $('#filterKelas').val('all');
                // Panggil AJAX Absensi setelah filter kelas diperbarui
                reloadAbsensi();
            }).fail(function() {
                // Tambahkan penanganan error jika Ajax gagal
                console.error("Gagal memuat data kelas.");
                let html = '<option value="all">Semua Kelas</option>';
                $('#filterKelas').html(html);
            });
        }

        // Fungsi untuk me-load data Absensi (rekap & counts) menggunakan AJAX
        function reloadAbsensi() {
            const jurusan = $('#filterJurusan').val() || 'all';
            const kelas = $('#filterKelas').val() || 'all';

            $.get('<?= smart_url('dashboard/absensiAjax') ?>', {
                jurusan: jurusan,
                kelas: kelas
            }, function(response) {
                // 1. Update Counts
                $('#countHadir').text(response.counts.masuk);
                $('#countTelat').text(response.counts.terlambat);
                $('#countIzin').text(response.counts.izin);
                $('#countSakit').text(response.counts.sakit);
                $('#countPulangAwal').text(response.counts.pulang_awal);

                // 2. Update Rekap Table
                // Hancurkan DataTables yang lama
                if ($.fn.DataTable.isDataTable('#tableRekapAbsensi')) {
                    tableAbsensi.destroy();
                }

                // Inisialisasi DataTables baru dengan data dari AJAX
                tableAbsensi = $('#tableRekapAbsensi').DataTable({
                    data: response.rekap,
                    columns: [{
                            data: 'nama'
                        },
                        {
                            data: 'user_type',
                            render: d => d.charAt(0).toUpperCase() + d.slice(1)
                        },
                        {
                            data: 'kelas',
                            defaultContent: '-',
                            className: 'd-none d-md-table-cell'
                        },
                        {
                            data: 'jam_masuk',
                            defaultContent: '-'
                        },
                        {
                            data: 'jam_pulang',
                            defaultContent: '-'
                        },
                        {
                            data: 'status',
                            render: function(status) {
                                if (status === 'terlambat')
                                    return `<span class="badge bg-warning text-dark fw-bold">TERLAMBAT</span>`;
                                if (status === 'masuk')
                                    return `<span class="badge bg-success fw-bold">MASUK</span>`;
                                return `<span class="badge bg-secondary fw-bold">${status.toUpperCase()}</span>`;
                            }
                        }
                    ],
                    // Matikan fitur Datatables yang mengganggu
                    paging: false,
                    searching: false,
                    info: false,
                    responsive: true,
                    order: [
                        [3, 'asc']
                    ],
                    language: {
                        emptyTable: "Belum ada data absensi hari ini."
                    }
                });

            }).fail(function() {
                console.error("Gagal memuat data absensi.");
            });
        }

        // FILTER: PENGATURAN LOGIKA FILTER
        // 1. Ganti Jurusan: Muat Kelas baru & Reload Absensi
        $('#filterJurusan').on('change', function() {
            const selectedJurusan = $(this).val();
            if (selectedJurusan) {
                loadKelas(selectedJurusan); // loadKelas akan memanggil reloadAbsensi()
            } else {
                // Jika "Semua Jurusan" dipilih, panggil reloadAbsensi langsung
                reloadAbsensi();
            }
        });

        // 2. Ganti Kelas: Reload Absensi
        $('#filterKelas').on('change', function() {
            reloadAbsensi();
        });

        // Inisialisasi data absensi (jika belum diisi oleh PHP di bagian atas)
        // Jika Anda menggunakan Datatables di bagian rekapAbsensi, pastikan Anda menggunakan AJAX
        // atau memanggil fungsi reloadAbsensi() di akhir DOMContentLoaded
        // Karena kita sudah memuat rekap awal di PHP, kita hanya perlu mengatur filter.

    });
</script>

<?= $this->endSection(); ?>