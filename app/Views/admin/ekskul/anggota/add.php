<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
    .card-modern {
        border: none;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, .05);
    }
</style>

<div class="content-wrapper" style="background:#f4f6f9;">
    <div class="content-header bg-white shadow-sm mb-4">
        <div class="container-fluid">
            <div class="row py-3 align-items-center">
                <div class="col-sm-8">
                    <h3 class="font-weight-bold text-dark m-0">
                        <i class="fas fa-user-plus text-primary mr-2"></i>
                        Tambah Anggota - <?= esc($ekskul['nama_ekskul']) ?>
                    </h3>
                    <p class="text-muted mb-0 ml-4">Tambahkan siswa sebagai anggota ekskul.</p>
                </div>
                <div class="col-sm-4 text-right">
                    <a href="<?= smart_url('ekskul/anggota/' . $ekskul['id']) ?>" class="btn btn-secondary shadow-sm rounded-pill px-4">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>


    <section class="content">
        <div class="container-fluid">

            <div class="card card-modern">
                <div class="card-body p-4">

                    <form method="post" action="<?= smart_url('ekskul/anggota/save') ?>">

                        <?= csrf_field() ?>
                        <input type="hidden" name="ekskul_id" value="<?= $ekskul['id'] ?>">

                        <div class="form-group">
                            <label class="font-weight-bold">Pilih Siswa</label>
                            <select name="siswa_id" class="form-control select2" required>
                                <option value="">-- Pilih Siswa --</option>
                                <?php foreach ($siswa as $s): ?>
                                    <option value="<?= $s['id'] ?>">
                                        <?= $s['nisn'] ?> - <?= $s['nama'] ?> (<?= $s['kelas'] ?>)
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <button class="btn btn-success rounded-pill px-4 shadow-sm">
                            <i class="fas fa-check mr-1"></i> Simpan Anggota
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </section>
</div>

<script>
    $('.select2').select2({
        theme: 'bootstrap4'
    });
</script>

<?= $this->endSection() ?>