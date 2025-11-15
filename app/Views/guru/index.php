<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <h4 class="fw-semibold mb-2">
        <i class="fa fa-chalkboard-teacher text-primary me-2"></i> Data Guru
    </h4>
    <button class="btn btn-primary btn-sm shadow-sm" id="btnAdd">
        <i class="fa fa-plus-circle me-1"></i> Tambah Guru
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table id="tableGuru" class="table table-hover align-middle w-100">
                <thead class="table-primary text-center align-middle">
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Mapel</th>
                        <th>Email</th>
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
            <form id="formGuru" enctype="multipart/form-data" autocomplete="off">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title"><i class="fa fa-user-edit me-2"></i>Form Data Guru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" name="id" id="id">

                    <div class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">NIP</label>
                            <input type="text" name="nip" id="nip" class="form-control shadow-sm" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Nama Lengkap</label>
                            <input type="text" name="nama" id="nama" class="form-control shadow-sm" required>
                        </div>

                        <!-- SELECT2 MAPEL -->
                        <div class="col-12">
                            <label class="form-label fw-semibold text-secondary">Mata Pelajaran</label>
                            <select name="mapel_id[]" id="mapel" class="form-select shadow-sm" multiple="multiple" style="width: 100%;">
                            </select>
                            <small class="text-muted">Ketik nama mapel untuk mencari dan memilih lebih dari satu.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Email</label>
                            <input type="email" name="email" id="email" class="form-control shadow-sm">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary">Telepon</label>
                            <input type="text" name="telepon" id="telepon" class="form-control shadow-sm">
                        </div>

                        <div class="col-md-6 text-center">
                            <label class="form-label fw-semibold text-secondary">Foto</label>
                            <div class="border rounded p-2 bg-light">
                                <img id="previewFoto"
                                    src="<?= smart_url('uploads/guru/default.png') ?>"
                                    width="100" height="100"
                                    class="rounded-circle shadow-sm mb-2">
                                <input type="file" name="foto" class="form-control shadow-sm" accept="image/*"
                                    onchange="previewImage(event)">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold text-secondary">Alamat</label>
                            <textarea name="alamat" id="alamat" class="form-control shadow-sm" rows="2"></textarea>
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


<script>
    $(function() {

        const base = '<?= smart_url('admin/guru') ?>';

        window.previewImage = e => {
            const file = e.target.files[0];
            if (file) $('#previewFoto').attr('src', URL.createObjectURL(file));
        };

        // DATATABLE
        const table = $('#tableGuru').DataTable({
            ajax: {
                url: base + '/list',
                dataSrc: 'data'
            },
            responsive: true,
            order: [
                [3, 'asc']
            ],
            columns: [{
                    data: null,
                    render: (d, t, r, m) => m.row + 1
                },
                {
                    data: 'foto',
                    render: d => `<img src="<?= smart_url('uploads/guru') ?>/${d || 'default.png'}" width="45" height="45" class="rounded-circle shadow-sm">`
                },
                {
                    data: 'nip'
                },
                {
                    data: 'nama'
                },
                {
                    data: 'mapel'
                },
                {
                    data: 'email'
                },
                {
                    data: 'telepon'
                },
                {
                    data: null,
                    render: d => `
                    <div class="text-center">
                        <button class="btn btn-warning btn-sm edit" data-id="${d.id}">
                            <i class="fa fa-pen"></i>
                        </button>
                        <button class="btn btn-danger btn-sm del" data-id="${d.id}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                `
                }
            ]
        });

        // INIT SELECT2
        $('#mapel').select2({
            dropdownParent: $('#modalForm'),
            placeholder: "Pilih mata pelajaran...",
            allowClear: true,
            width: '100%'
        });

        // LOAD MAPEL
        function loadMapel(selected = []) {
            $.get(base + '/getMapel', function(data) {

                $('#mapel').empty();

                data.forEach(m => {
                    let option = new Option(m.nama_mapel, m.id, false, selected.includes(m.id.toString()));
                    $('#mapel').append(option);
                });

                $('#mapel').trigger('change');
            });
        }

        // TAMBAH
        $('#btnAdd').click(() => {
            $('#formGuru')[0].reset();
            $('#id').val('');
            $('#mapel').val(null).trigger('change');
            loadMapel([]);
            $('#modalForm').modal('show');
        });

        // SIMPAN
        $('#formGuru').submit(function(e) {
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
                        $('#modalForm').modal('hide');
                        table.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data guru berhasil disimpan.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire('Gagal', 'Terjadi kesalahan.', 'error');
                    }
                },
                error: (xhr) => {
                    console.log(xhr.responseText);
                    Swal.fire('Error Server', 'Silakan cek console!', 'error');
                }
            });
        });

        // EDIT
        $('#tableGuru').on('click', '.edit', function() {
            const id = $(this).data('id');

            $.get(base + '/get/' + id, res => {
                $('#id').val(res.id);
                $('#nip').val(res.nip);
                $('#nama').val(res.nama);
                $('#email').val(res.email);
                $('#telepon').val(res.telepon);
                $('#alamat').val(res.alamat);

                $('#previewFoto').attr('src',
                    res.foto ? `<?= smart_url('uploads/guru') ?>/${res.foto}` :
                    `<?= smart_url('uploads/guru/default.png') ?>`
                );

                loadMapel(res.mapel_ids);

                $('#modalForm').modal('show');
            });
        });

        // HAPUS
        $('#tableGuru').on('click', '.del', function() {
            const id = $(this).data('id');

            Swal.fire({
                title: "Hapus?",
                icon: "warning",
                showCancelButton: true
            }).then(r => {
                if (r.isConfirmed) {
                    $.get(base + '/delete/' + id, () => {
                        table.ajax.reload();
                        Swal.fire("Terhapus!", "", "success");
                    });
                }
            });
        });

    });
</script>

<?= $this->endSection(); ?>