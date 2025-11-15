<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <h4 class="fw-semibold mb-2">
        <i class="fa fa-book text-primary me-2"></i> Data Mata Pelajaran
    </h4>
    <button class="btn btn-primary btn-sm shadow-sm" id="btnAdd">
        <i class="fa fa-plus-circle me-1"></i> Tambah Mapel
    </button>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table id="tableMapel" class="table table-hover align-middle w-100">
                <thead class="table-primary text-center">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Mata Pelajaran</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="modalForm" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="formMapel" autocomplete="off">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fa fa-book me-2"></i> Form Data Mata Pelajaran
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary">Kode Mapel</label>
                        <input type="text" name="kode_mapel" id="kode_mapel" class="form-control shadow-sm" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary">Nama Mata Pelajaran</label>
                        <input type="text" name="nama_mapel" id="nama_mapel" class="form-control shadow-sm" required>
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
    document.addEventListener('DOMContentLoaded', function() {
        // ✅ Pastikan jQuery sudah termuat
        if (typeof $ === 'undefined') {
            console.error('jQuery belum dimuat! Pastikan layout/main.php memuat jQuery sebelum renderSection("scripts").');
            return;
        }

        $(function() {
            const base = '<?= smart_url('mapel') ?>';

            // 🔹 Inisialisasi DataTable
            const table = $('#tableMapel').DataTable({
                ajax: {
                    url: base + '/list',
                    dataSrc: 'data'
                },
                columns: [{
                        data: null,
                        className: 'text-center',
                        render: (d, t, r, m) => m.row + 1
                    },
                    {
                        data: 'kode_mapel',
                        className: 'text-center'
                    },
                    {
                        data: 'nama_mapel'
                    },
                    {
                        data: null,
                        className: 'text-center',
                        render: d => `
                        <button class="btn btn-warning btn-sm edit" data-id="${d.id}">
                            <i class="fa fa-pen"></i>
                        </button>
                        <button class="btn btn-danger btn-sm del" data-id="${d.id}">
                            <i class="fa fa-trash"></i>
                        </button>`
                    }
                ],
                order: [
                    [1, 'asc']
                ],
                language: {
                    emptyTable: "Belum ada data mata pelajaran.",
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data"
                }
            });

            // 🔹 Tombol Tambah
            $('#btnAdd').click(function() {
                $('#formMapel')[0].reset();
                $('#id').val('');
                $('#modalForm').modal('show');
            });

            // 🔹 Simpan Data
            $('#formMapel').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: base + '/save',
                    type: 'POST',
                    data: $(this).serialize(),
                    success: res => {
                        if (res.success) {
                            $('#modalForm').modal('hide');
                            table.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: 'Data mata pelajaran berhasil disimpan!',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan data.', 'error');
                        }
                    },
                    error: () => Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error')
                });
            });

            // 🔹 Edit Data
            $('#tableMapel').on('click', '.edit', function() {
                $.get(base + '/get/' + $(this).data('id'), res => {
                    $('#id').val(res.id);
                    $('#kode_mapel').val(res.kode_mapel);
                    $('#nama_mapel').val(res.nama_mapel);
                    $('#modalForm').modal('show');
                });
            });

            // 🔹 Hapus Data
            $('#tableMapel').on('click', '.del', function() {
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: 'Data ini tidak dapat dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (result.isConfirmed) {
                        $.get(base + '/delete/' + $(this).data('id'), () => {
                            table.ajax.reload();
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: 'Data berhasil dihapus.',
                                timer: 1200,
                                showConfirmButton: false
                            });
                        });
                    }
                });
            });
        });
    });
</script>

<style>
    .dataTables_wrapper .dataTables_filter {
        float: right;
        text-align: right;
    }

    .dataTables_wrapper .dataTables_paginate {
        float: right;
        margin-top: 10px;
    }
</style>
<?= $this->endSection(); ?>