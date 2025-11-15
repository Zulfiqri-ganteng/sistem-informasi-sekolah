<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-3">
            <h5 class="fw-bold mb-0">Daftar jurusan</h5>
            <button id="btnAdd" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Tambah jurusan</button>
        </div>
        <table id="tbljurusan" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama jurusan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalForm">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formjurusan">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Form jurusan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <label>Nama jurusan</label>
                    <input type="text" name="nama_jurusan" id="nama_jurusan" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
<?= $this->section('scripts'); ?>
<script>
    $(function() {
        const base = '<?= smart_url('jurusan') ?>';
        const tbl = $('#tbljurusan').DataTable({
            ajax: {
                url: base + '/list',
                dataSrc: 'data'
            },
            columns: [{
                    data: null,
                    render: (d, t, r, m) => m.row + 1
                },
                {
                    data: 'nama_jurusan'
                },
                {
                    data: null,
                    render: (d) => `
        <button class="btn btn-warning btn-sm edit" data-id="${d.id}"><i class="fa fa-edit"></i></button>
        <button class="btn btn-danger btn-sm del" data-id="${d.id}"><i class="fa fa-trash"></i></button>`
                }
            ]
        });

        $('#btnAdd').click(() => $('#modalForm').modal('show'));
        $('#formjurusan').submit(function(e) {
            e.preventDefault();
            $.post(base + '/save', $(this).serialize(), () => {
                $('#modalForm').modal('hide');
                tbl.ajax.reload();
                Swal.fire('Berhasil', 'Data disimpan', 'success');
            });
        });
        $('#tbljurusan').on('click', '.edit', function() {
            $.get(base + '/get/' + $(this).data('id'), r => {
                $('#id').val(r.id);
                $('#nama_jurusan').val(r.nama_jurusan);
                $('#modalForm').modal('show');
            });
        });
        $('#tbljurusan').on('click', '.del', function() {
            const id = $(this).data('id');
            Swal.fire({
                title: 'Yakin hapus?',
                showCancelButton: true
            }).then(res => {
                if (res.isConfirmed) $.get(base + '/delete/' + id, () => tbl.ajax.reload());
            });
        });
    });
</script>
<?= $this->endSection(); ?>