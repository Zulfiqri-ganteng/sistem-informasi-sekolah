<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <h4 class="fw-semibold mb-2"><i class="fa fa-user-graduate text-primary me-2"></i>Data Siswa</h4>
    <button class="btn btn-primary btn-sm shadow-sm" id="btnAdd">
        <i class="fa fa-plus-circle me-1"></i> Tambah Siswa
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <!-- Toolbar: Search + Export -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <input type="text" id="searchInput" class="form-control" placeholder="🔍 Cari nama atau NISN..." style="min-width:250px;">
                <select id="filterKelas" class="form-select" style="min-width:140px;">
                    <option value="">Semua Kelas</option>
                </select>
                <select id="filterJurusan" class="form-select" style="min-width:180px;">
                    <option value="">Semua Jurusan</option>
                </select>
            </div>
            <div id="exportButtons" class="d-flex gap-2 flex-wrap"></div>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table id="tableSiswa" class="table table-hover align-middle w-100">
                <thead class="table-primary text-center align-middle">
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Telepon</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="modalForm" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="formSiswa" enctype="multipart/form-data" autocomplete="off">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fa fa-user-edit me-2"></i>Form Data Siswa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <input type="hidden" name="id" id="id">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">NISN</label>
                            <input type="text" name="nisn" id="nisn" class="form-control shadow-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" class="form-control shadow-sm" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Email (Aktif)</label>
                            <input type="email" name="email" id="email" class="form-control shadow-sm" placeholder="contoh: zulfiqri@gmail.com" required>
                        </div>
                        <!-- <div class="col-md-6">
                            <label for="email" class="form-label">Email Siswa</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="contoh: siswa@gmail.com">
                        </div> -->

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Kelas</label>
                            <select name="kelas" id="kelas" class="form-select shadow-sm" required></select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Jurusan</label>
                            <select name="jurusan" id="jurusan" class="form-select shadow-sm" required></select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold text-secondary">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control shadow-sm" rows="2"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Telepon</label>
                            <input type="text" name="telepon" id="telepon" class="form-control shadow-sm">
                        </div>

                        <div class="col-md-6 text-center">
                            <label class="form-label fw-semibold text-secondary">Foto</label>
                            <div class="border rounded p-2 bg-light">
                                <img id="previewFoto" src="<?= smart_url('assets/img/default-avatar.png') ?>"
                                    alt="Preview" class="rounded-circle mb-2 shadow-sm" width="100" height="100">
                                <input type="file" name="foto" class="form-control shadow-sm" accept="image/*"
                                    onchange="previewImage(event)">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-primary shadow-sm">
                        <i class="fa fa-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>


<?= $this->section('scripts'); ?>

<!-- DataTables Export Buttons -->
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
    $(function() {
        const base = '<?= smart_url('siswa') ?>';
        let lastAddedId = null;

        // Preview foto
        window.previewImage = e => {
            const file = e.target.files[0];
            if (file) $('#previewFoto').attr('src', URL.createObjectURL(file));
        };

        // Warna badge jurusan otomatis
        const jurusanColor = jur => {
            const colors = {
                'Desain Komunikasi Visual (DKV)': 'bg-info text-dark',
                'Teknik Komputer dan Jaringan (TKJ)': 'bg-primary text-white',
                'Akuntansi dan Keuangan (AK)': 'bg-success text-white',
                'Multimedia (MM)': 'bg-warning text-dark',
                'Otomatisasi Perkantoran (OTKP)': 'bg-secondary text-white',
                'Manajement Perkantoran (MP)': 'bg-secondary text-white',
                'Farmasi': 'bg-danger text-white'
            };
            return colors[jur] || 'bg-light text-dark';
        };

        // DataTable dengan export
        const table = $('#tableSiswa').DataTable({
            ajax: {
                url: base + '/list',
                dataSrc: 'data'
            },
            responsive: false,
            scrollX: true,
            order: [
                [3, 'asc']
            ],
            paging: true,
            info: false,
            lengthChange: false,
            searching: false,
            dom: 'Bfrtip',
            // buttons: [{
            //         extend: 'excelHtml5',
            //         text: '<i class="fa fa-file-excel"></i> Excel',
            //         className: 'btn btn-success btn-sm shadow-sm',
            //         title: 'Data Siswa SMK Galajuara',
            //         exportOptions: {
            //             columns: [0, 2, 3, 4, 5, 6]
            //         }
            //     },
            //     // {
            //     //     extend: 'pdfHtml5',
            //     //     text: '<i class="fa fa-file-pdf"></i> PDF',
            //     //     className: 'btn btn-danger btn-sm shadow-sm',
            //     //     orientation: 'portrait',
            //     //     pageSize: 'A4',
            //     //     title: 'Data Siswa SMK Galajuara',
            //     //     exportOptions: {
            //     //         columns: [0, 2, 3, 4, 5, 6]
            //     //     },
            //     //     customize: function(doc) {
            //     //         doc.styles.tableHeader.fillColor = '#1d4ed8';
            //     //         doc.styles.tableHeader.color = 'white';
            //     //         doc.content.splice(0, 0, {
            //     //             text: 'SMK GALAJUARA\nData Siswa\n\n',
            //     //             fontSize: 14,
            //     //             bold: true,
            //     //             alignment: 'center'
            //     //         });
            //     //     }
            //     // },
            //     // {
            //     //     extend: 'print',
            //     //     text: '<i class="fa fa-print"></i> Print',
            //     //     className: 'btn btn-secondary btn-sm shadow-sm',
            //     //     title: 'Data Siswa',
            //     //     exportOptions: {
            //     //         columns: [0, 2, 3, 4, 5, 6]
            //     //     }
            //     // }
            // ],
            createdRow: function(row, data) {
                if (lastAddedId && data.id == lastAddedId) {
                    $(row).addClass('highlight-new');
                    setTimeout(() => $(row).removeClass('highlight-new'), 2500);
                }
            },
            columns: [{
                    data: null,
                    render: (d, t, r, m) => m.row + 1
                },
                {
                    data: 'foto',
                    render: d =>
                        `<img src="<?= smart_url('uploads/siswa') ?>/${d || 'default-avatar.png'}"
              class="rounded-circle shadow-sm border border-2 border-light" width="45" height="45">`
                },
                {
                    data: 'nisn'
                },
                {
                    data: 'nama'
                },
                {
                    data: 'email'
                },
                {
                    data: 'kelas',
                    render: d => `<span class="badge bg-primary-subtle text-primary px-2 py-1 rounded-pill">${d}</span>`
                },
                {
                    data: 'jurusan',
                    render: d => `<span class="badge ${jurusanColor(d)} px-2 py-1 rounded-pill">${d}</span>`
                },
                {
                    data: 'telepon'
                },
                {
                    data: null,
                    render: d =>
                        `<div class="text-center">
          <button class="btn btn-warning btn-sm edit" data-id="${d.id}" title="Edit"><i class="fa fa-pen"></i></button>
          <button class="btn btn-danger btn-sm del" data-id="${d.id}" title="Hapus"><i class="fa fa-trash"></i></button>
        </div>`
                }
            ]
        });

        table.buttons().container().appendTo('#exportButtons');

        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });

        function loadOptions(selectedKelas = '', selectedJurusan = '') {
            $.get(base + '/options', res => {
                let kelasOpt = '<option value="">-- Pilih Kelas --</option>';
                let jurOpt = '<option value="">-- Pilih Jurusan --</option>';
                res.kelas.forEach(k => {
                    const sel = k.nama_kelas === selectedKelas ? 'selected' : '';
                    kelasOpt += `<option value="${k.nama_kelas}" ${sel}>${k.nama_kelas}</option>`;
                });
                res.jurusan.forEach(j => {
                    const sel = j.nama_jurusan === selectedJurusan ? 'selected' : '';
                    jurOpt += `<option value="${j.nama_jurusan}" ${sel}>${j.nama_jurusan}</option>`;
                });
                $('#kelas').html(kelasOpt);
                $('#jurusan').html(jurOpt);
                $('#filterKelas').html('<option value="">Semua Kelas</option>' + kelasOpt);
                $('#filterJurusan').html('<option value="">Semua Jurusan</option>' + jurOpt);
            });
        }
        loadOptions();

        $('#filterKelas,#filterJurusan').on('change', function() {
            const k = $('#filterKelas').val(),
                j = $('#filterJurusan').val();
            table.column(4).search(k).column(5).search(j).draw();
        });

        $('#btnAdd').click(() => {
            $('#formSiswa')[0].reset();
            $('#id').val('');
            $('#previewFoto').attr('src', '<?= smart_url('assets/img/default-avatar.png') ?>');
            loadOptions();
            $('#modalForm').modal('show');
        });

        $('#formSiswa').submit(function(e) {
            e.preventDefault();
            const fd = new FormData(this);
            $.ajax({
                url: base + '/save',
                type: 'POST',
                data: fd,
                processData: false,
                contentType: false,
                success: res => {
                    if (res.success) {
                        lastAddedId = res.id || null;
                        $('#modalForm').modal('hide');
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data siswa berhasil disimpan.',
                            timer: 1800,
                            showConfirmButton: false
                        });
                    } else Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan data', 'error');
                },
                error: () => Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error')
            });
        });

        $('#tableSiswa').on('click', '.edit', function() {
            const id = $(this).data('id');
            $.get(base + '/get/' + id, res => {
                $('#id').val(res.id);
                $('#nisn').val(res.nisn);
                $('#nama').val(res.nama);
                $('#email').val(res.email);
                $('#alamat').val(res.alamat);
                $('#telepon').val(res.telepon);
                $('#previewFoto').attr('src', res.foto ? `<?= smart_url('uploads/siswa') ?>/${res.foto}` : '<?= smart_url('assets/img/default-avatar.png') ?>');
                loadOptions(res.kelas, res.jurusan);
                $('#modalForm').modal('show');
            });
        });

        $('#tableSiswa').on('click', '.del', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Yakin hapus?',
                text: 'Data siswa akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(r => {
                if (r.isConfirmed) {
                    $.get(base + '/delete/' + id, () => {
                        table.ajax.reload();
                        Swal.fire('Terhapus', 'Data siswa berhasil dihapus', 'success');
                    });
                }
            });
        });
    });
</script>

<style>
    .highlight-new {
        background-color: #d4edda !important;
        animation: pulseGreen 1.2s ease-in-out 2;
    }

    @keyframes pulseGreen {
        0% {
            background-color: #c3e6cb;
        }

        50% {
            background-color: #a1e1af;
        }

        100% {
            background-color: #d4edda;
        }
    }

    /* Responsif di HP */
    @media (max-width:768px) {
        #tableSiswa thead {
            display: none !important;
        }

        #tableSiswa tbody tr {
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, #f7faff, #ffffff);
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            margin-bottom: 15px;
            padding: 10px 14px;
            animation: fadeIn .4s ease;
        }

        #tableSiswa tbody td {
            display: block;
            border: none !important;
            font-size: 14px;
        }

        #tableSiswa tbody td img {
            width: 65px;
            height: 65px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.15);
        }

        #tableSiswa tbody td span.badge {
            display: inline-block;
            margin-top: 4px;
            font-size: 12px;
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<?= $this->endSection(); ?>