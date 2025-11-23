<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<!-- 
    TABUNGAN SISWA - Versi Profesional (perbaikan dropdown + quick action)
-->

<div class="container-fluid py-4 animate__animated animate__fadeIn">
    <div class="row g-3 align-items-center mb-3">
        <div class="col">
            <h1 class="h3 fw-bold text-navy d-flex align-items-center">
                <i class="fa-solid fa-piggy-bank me-2"></i> Tabungan Siswa
            </h1>
        </div>
        <div class="col-auto d-flex gap-2">
            <select id="filterKelas" class="form-select form-select-sm" style="min-width:180px">
                <option value="">Semua Kelas</option>
            </select>
            <select id="filterJurusan" class="form-select form-select-sm" style="min-width:200px">
                <option value="">Semua Jurusan</option>
            </select>
            <button id="btnRefresh" class="btn btn-outline-primary btn-sm">
                <i class="fa-solid fa-arrows-rotate me-1"></i> Refresh
            </button>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 kpi-card">
                <div class="card-body">
                    <small class="text-uppercase text-muted">Total Siswa Menabung</small>
                    <div class="d-flex align-items-end justify-content-between">
                        <h3 id="kpiCount" class="mb-0 fw-bold">0</h3>
                        <i class="fa-solid fa-people-group fa-2x text-navy"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100 kpi-card">
                <div class="card-body">
                    <small class="text-uppercase text-muted">Total Saldo Seluruh Siswa</small>
                    <div class="d-flex align-items-end justify-content-between">
                        <h3 id="kpiSaldo" class="mb-0 text-primary fw-bold">Rp 0</h3>
                        <i class="fa-solid fa-wallet fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 d-none d-md-block">
            <div class="card shadow-sm border-0 h-100 kpi-card">
                <div class="card-body">
                    <small class="text-uppercase text-muted">Catatan</small>
                    <div class="mt-2 text-muted small">
                        Gunakan tombol <span class="badge bg-success">Setor</span> untuk menambah saldo,
                        dan <span class="badge bg-warning text-dark">Tarik</span> untuk mengurangi.
                        Klik <span class="badge bg-light border">Riwayat</span> untuk melihat transaksi.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table -->
    <div class="row">
        <div class="col-lg-9">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tableTabungan" class="table table-striped table-hover align-middle w-100">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:50px">#</th>
                                    <th>Nama</th>
                                    <th style="width:120px">Kelas</th>
                                    <th style="width:220px">Jurusan</th>
                                    <th style="width:150px" class="text-end">Saldo (Rp)</th>
                                    <th style="width:140px" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Widgets -->
        <div class="col-lg-3">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body">
                    <h6 class="fw-semibold mb-3"><i class="fa-solid fa-bolt me-2 text-primary"></i>Quick Actions</h6>
                    <div class="d-grid gap-2">
                        <button id="btnAddTransaction" class="btn btn-primary">
                            <i class="fa-solid fa-plus me-2"></i>Tambah Transaksi
                        </button>
                        <button id="btnExport" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-file-export me-2"></i>Export CSV
                        </button>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h6 class="fw-semibold mb-2">Panduan Singkat</h6>
                    <ul class="small text-muted list-unstyled mb-0">
                        <li>➤ Filter berdasarkan kelas atau jurusan.</li>
                        <li>➤ Klik <span class="badge bg-success">Setor</span> untuk menambah saldo.</li>
                        <li>➤ Klik <span class="badge bg-warning text-dark">Tarik</span> untuk mengurangi.</li>
                        <li>➤ Klik <span class="badge bg-light border">Riwayat</span> untuk melihat mutasi.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Transaksi -->
<div class="modal fade" id="modalTransaksi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formTransaksi" class="modal-content shadow">
            <div class="modal-header bg-navy text-white">
                <h5 class="modal-title"><i class="fa-solid fa-wallet me-2"></i>Transaksi Tabungan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- gunakan select sebagai input siswa (name = siswa_id) -->
                <div class="mb-3">
                    <label class="form-label">Siswa</label>
                    <select id="tx_siswa_id" name="siswa_id" class="form-select" required>
                        <option value="">-- Pilih Siswa --</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipe Transaksi</label>
                    <select name="tipe" id="tx_tipe" class="form-select" required>
                        <option value="setor">Setor (Menambah)</option>
                        <option value="tarik">Tarik (Mengurangi)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jumlah (Rp)</label>
                    <input type="number" name="jumlah" id="tx_jumlah" class="form-control form-control-lg" min="1" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Keterangan (opsional)</label>
                    <textarea name="keterangan" id="tx_keterangan" class="form-control" rows="2"></textarea>
                </div>
                <div id="tx_warning" class="alert alert-warning small d-none"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-floppy-disk me-1"></i>Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Mutasi -->
<div class="modal fade" id="modalMutasi" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title"><i class="fa-solid fa-list me-2"></i>Riwayat Transaksi</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="mutasiHeader" class="mb-3 small text-muted"></div>
                <div class="table-responsive">
                    <table id="tableMutasi" class="table table-sm table-striped table-bordered w-100">
                        <thead class="table-light">
                            <tr>
                                <th style="width:180px">Tanggal</th>
                                <th style="width:120px">Tipe</th>
                                <th class="text-end">Jumlah (Rp)</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <small class="text-muted me-auto">Mutasi realtime — diperbarui otomatis setelah transaksi.</small>
                <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>

<!-- JS Libraries -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    :root {
        --navy: #0f2340;
        --accent: #1e6fef;
        --muted: #6b7280;
    }

    .text-navy {
        color: var(--navy);
    }

    .bg-navy {
        background-color: var(--navy);
    }

    .btn-primary {
        background: var(--accent);
        border-color: var(--accent);
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background: #1457d6;
    }

    .kpi-card {
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, .1);
    }

    table.dataTable thead th {
        background: #e9f2ff;
        color: var(--navy);
        font-weight: 600;
    }

    .select2-container--bootstrap5 .select2-selection {
        height: calc(1.5em + 0.75rem + 2px);
        padding: .375rem .75rem;
    }
</style>

<script>
    $(function() {
        const base = '<?= smart_url() ?>';
        const modalTransaksi = new bootstrap.Modal($('#modalTransaksi')[0]);
        const modalMutasi = new bootstrap.Modal($('#modalMutasi')[0]);

        let siswaLoaded = false; // cache flag agar tidak selalu reload options

        // 🎨 Animasi KPI
        function animateKpi(selector, newValue, prefix = '') {
            const el = $(selector);
            const oldValue = parseInt(el.text().replace(/\D/g, '')) || 0;
            $({
                val: oldValue
            }).animate({
                val: newValue
            }, {
                duration: 800,
                easing: 'swing',
                step: function() {
                    el.text(prefix + formatNumber(Math.floor(this.val)));
                },
                complete: function() {
                    el.text(prefix + formatNumber(newValue));
                }
            });
        }

        // 📚 Select2 only for filters (not required for transaksi select; keep simple)
        $('#filterKelas, #filterJurusan').select2({
            theme: "bootstrap-5",
            width: 'resolve'
        });

        // ⚙️ Load filter options (kelas/jurusan)
        function loadOptions() {
            $.getJSON(base + '/siswa/options')
                .done(res => {
                    const ksel = $('#filterKelas').empty().append('<option value="">Semua Kelas</option>');
                    (res.kelas || []).forEach(k => ksel.append(`<option value="${k.id}">${escapeHtml(k.nama_kelas)}</option>`));
                    const jsel = $('#filterJurusan').empty().append('<option value="">Semua Jurusan</option>');
                    (res.jurusan || []).forEach(j => jsel.append(`<option value="${j.id}">${escapeHtml(j.nama_jurusan)}</option>`));
                })
                .fail(() => {
                    console.warn('Gagal ambil options (kelas/jurusan).');
                });
        }
        loadOptions();

        // 📊 DataTable utama
        const table = $('#tableTabungan').DataTable({
            ajax: {
                url: base + '/tabungan/list',
                dataSrc: 'data',
                data: d => {
                    d.kelas_id = $('#filterKelas').val();
                    d.jurusan_id = $('#filterJurusan').val();
                },
                beforeSend: () => $('.dataTables_processing').show(),
                complete: (xhr) => {
                    $('.dataTables_processing').hide();
                    const json = xhr.responseJSON;

                    // ✅ Cek apakah backend mengirim data meta (sesuai controller Tabungan)
                    if (json && json.meta) {
                        const totalMenabung = json.meta.totalSiswaMenabung ?? json.meta.totalSavers ?? 0;
                        const totalSaldo = json.meta.totalSaldo ?? 0;

                        // 🔹 Update KPI dengan animasi
                        animateKpi('#kpiCount', totalMenabung);
                        animateKpi('#kpiSaldo', totalSaldo, 'Rp ');
                    }
                    // 🔁 Jika backend tidak kirim meta, hitung manual dari data
                    else if (json && Array.isArray(json.data)) {
                        let totalSaldo = 0;
                        let totalMenabung = 0;

                        json.data.forEach(r => {
                            const saldo = Number(r.saldo) || 0;
                            totalSaldo += saldo;
                            if (saldo > 0) totalMenabung++;
                        });

                        animateKpi('#kpiCount', totalMenabung);
                        animateKpi('#kpiSaldo', totalSaldo, 'Rp ');
                    }
                },
                error: () => Swal.fire('Kesalahan', 'Gagal mengambil data tabungan.', 'error')

            },
            columns: [{
                    data: null,
                    className: 'text-center',
                    render: (d, t, r, m) => m.row + 1 + m.settings._iDisplayStart
                },
                {
                    data: 'nama',
                    render: d => `<strong>${escapeHtml(d)}</strong>`
                },
                {
                    data: 'kelas'
                },
                {
                    data: 'jurusan'
                },
                {
                    data: 'saldo',
                    className: 'text-end',
                    render: d => {
                        const val = Number(d) || 0;
                        const badge = val > 0 ?
                            '<span class="badge bg-success-subtle text-success ms-2">Aktif</span>' :
                            '<span class="badge bg-secondary-subtle text-muted ms-2">Kosong</span>';
                        return `Rp ${formatNumber(val)} ${badge}`;
                    }
                },
                {
                    data: null,
                    className: 'text-center',
                    orderable: false,
                    render: d => `
                        <button class="btn btn-success btn-sm btn-setor" data-id="${d.id}" title="Transaksi">
                            <i class="fa-solid fa-wallet"></i>
                        </button>
                        <button class="btn btn-light btn-sm border ms-1 btn-mutasi" data-id="${d.id}" title="Riwayat">
                            <i class="fa-solid fa-list"></i>
                        </button>
                    `
                }
            ],
            pageLength: 10,
            ordering: false,
            responsive: true,
            language: {
                processing: "🔄 Memuat data...",
                emptyTable: "Belum ada data siswa"
            }
        });

        // 🔁 Filter reload
        $('#filterKelas, #filterJurusan, #btnRefresh').on('change click', () => {
            table.ajax.reload();
            Swal.fire({
                toast: true,
                icon: 'info',
                title: 'Data diperbarui',
                showConfirmButton: false,
                timer: 900,
                position: 'top-end'
            });
        });

        // 💰 Klik tombol dompet pada baris -> buka modal + pilih siswa
        $(document).on('click', '.btn-setor', function() {
            const id = $(this).data('id');
            // pastikan opsi siswa tersedia
            loadSiswaOptions(() => {
                // pilih siswa pada select
                $('#tx_siswa_id').val(id);
                $('#tx_tipe').val('setor');
                $('#tx_jumlah').val('');
                $('#tx_keterangan').val('');
                $('#tx_warning').addClass('d-none');
                modalTransaksi.show();
            });
        });

        // 🧾 Submit transaksi dengan konfirmasi
        $('#formTransaksi').on('submit', function(e) {
            e.preventDefault();
            const jumlah = Number($('#tx_jumlah').val());
            const tipe = $('#tx_tipe').val();
            const siswaId = $('#tx_siswa_id').val();
            const siswaText = $('#tx_siswa_id option:selected').text();

            if (!siswaId) {
                return Swal.fire('Pilih siswa', 'Silakan pilih siswa terlebih dahulu.', 'warning');
            }

            if (!jumlah || jumlah <= 0) {
                return $('#tx_warning').removeClass('d-none').text('Masukkan jumlah transaksi yang valid.');
            }

            Swal.fire({
                title: `Konfirmasi ${tipe === 'setor' ? 'Setoran' : 'Penarikan'}`,
                html: `<b>${escapeHtml(siswaText)}</b><br>Jumlah: <b>Rp ${formatNumber(jumlah)}</b><br><small>Lanjutkan transaksi ini?</small>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(result => {
                if (result.isConfirmed) {
                    $.post(base + '/tabungan/transaction', $('#formTransaksi').serialize(), res => {
                        if (res && res.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Transaksi tersimpan.',
                                timer: 1200,
                                showConfirmButton: false
                            });
                            modalTransaksi.hide();
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire('Gagal', (res && res.msg) || 'Server menolak transaksi.', 'error');
                        }
                    }, 'json').fail(() => {
                        Swal.fire('Kesalahan', 'Tidak dapat menghubungi server.', 'error');
                    });
                }
            });
        });

        // 📜 Mutasi modal
        $(document).on('click', '.btn-mutasi', function() {
            const id = $(this).data('id');
            $('#mutasiHeader').text('Memuat data...');
            $('#tableMutasi tbody').html('');
            $.getJSON(base + '/tabungan/mutasi/' + id)
                .done(res => {
                    if (!res.data || !res.data.length) {
                        $('#mutasiHeader').text('Belum ada transaksi.');
                        return modalMutasi.show();
                    }
                    $('#mutasiHeader').text('Riwayat transaksi');
                    const rows = res.data.map(r => `
                        <tr>
                            <td>${escapeHtml(r.created_at || r.tanggal || '')}</td>
                            <td><span class="badge ${r.tipe === 'setor' ? 'bg-success' : 'bg-warning text-dark'}">${r.tipe}</span></td>
                            <td class="text-end">Rp ${formatNumber(r.jumlah)}</td>
                            <td>${escapeHtml(r.keterangan || '')}</td>
                        </tr>`).join('');
                    $('#tableMutasi tbody').html(rows);
                    modalMutasi.show();
                })
                .fail(() => Swal.fire('Gagal', 'Tidak dapat memuat mutasi.', 'error'));
        });

        // 📦 Export CSV
        $('#btnExport').on('click', () => {
            const rows = table.rows({
                search: 'applied'
            }).data().toArray();
            if (!rows.length) return Swal.fire('Kosong', 'Tidak ada data untuk diexport.', 'info');
            const csv = ['Nama,Kelas,Jurusan,Saldo'];
            rows.forEach(r => csv.push(`"${r.nama}","${r.kelas}","${r.jurusan}","${r.saldo}"`));
            const blob = new Blob([csv.join('\n')], {
                type: 'text/csv'
            });
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = 'tabungan_export.csv';
            a.click();
            Swal.fire({
                toast: true,
                icon: 'success',
                title: 'File CSV berhasil dibuat',
                timer: 1200,
                showConfirmButton: false
            });
        });

        // ⚡ Quick Action: Tambah Transaksi (langsung buka modal)
        $('#btnAddTransaction').on('click', function() {
            // load siswa options satu kali (cache)
            loadSiswaOptions(() => {
                // reset form
                $('#tx_siswa_id').val('');
                $('#tx_tipe').val('setor');
                $('#tx_jumlah').val('');
                $('#tx_keterangan').val('');
                $('#tx_warning').addClass('d-none');

                modalTransaksi.show();


            });
        });

        /**
         * loadSiswaOptions
         * Memanggil endpoint /siswa/list dan mengisi <select id="tx_siswa_id">
         * callback optional dipanggil setelah opsi terisi (berguna saat membuka modal dari tabel)
         */
        function loadSiswaOptions(callback) {
            // jika sudah dimuat sekali, langsung panggil callback
            if (siswaLoaded) {
                if (typeof callback === 'function') callback();
                return;
            }

            $.getJSON(base + '/siswa/list')
                .done(res => {
                    const data = (res && res.data) ? res.data : (Array.isArray(res) ? res : []);
                    const select = $('#tx_siswa_id').empty();
                    select.append('<option value="">-- Pilih Siswa --</option>');
                    data.forEach(r => {
                        // tampilkan nama + kelas di option agar jelas
                        const label = `${r.nama}${r.kelas ? ' — ' + r.kelas : ''}`;
                        select.append(`<option value="${r.id}">${escapeHtml(label)}</option>`);
                    });
                    siswaLoaded = true;
                    if (typeof callback === 'function') callback();
                })
                .fail(() => {
                    // fallback: beri tahu user
                    Swal.fire('Gagal', 'Tidak dapat memuat daftar siswa.', 'error');
                    if (typeof callback === 'function') callback();
                });
        }

        // Utils
        function formatNumber(n) {
            return (Number(n) || 0).toLocaleString('id-ID');
        }

        function escapeHtml(s) {
            return s ? $('<div>').text(s).html() : '';
        }
    });
</script>

<?= $this->endSection(); ?>