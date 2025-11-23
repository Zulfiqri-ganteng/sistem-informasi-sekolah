<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<!-- SELECT2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.3.0/select2-bootstrap-5-theme.min.css" rel="stylesheet" />

<!-- JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>

<style>
    .mode-card {
        cursor: pointer;
        padding: 30px;
        border-radius: 15px;
        transition: 0.25s;
        border: 1px solid #e0e0e0;
        background: #ffffff;
    }

    .mode-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    }

    .select2-container--bootstrap-5 .select2-selection {
        min-height: 58px;
        padding: 10px 14px;
        border-radius: 12px;
        font-size: 1rem;
    }

    .preview-box {
        background: #f8fafc;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        display: none;
    }
</style>

<div class="card shadow-sm p-4">

    <h2 class="fw-bold mb-4">
        <i class="fa-solid fa-qrcode me-2"></i> Generate QR Code Absensi (Premium)
    </h2>

    <div class="alert alert-info">
        Pilih mode lalu pilih pemilik QR. Sistem mendukung
        <b>multi-select</b> + <b>auto-preview</b> identitas.
    </div>

    <!-- ============================= -->
    <!-- MODE -->
    <!-- ============================= -->
    <h5 class="fw-bold mb-3">Pilih Mode Generate</h5>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="mode-card text-center" onclick="setMode('siswa')">
                <i class="fa-solid fa-users fa-3x text-primary mb-2"></i>
                <h6 class="fw-bold">Generate QR Banyak Siswa</h6>
            </div>
        </div>

        <div class="col-md-4">
            <div class="mode-card text-center" onclick="setMode('guru')">
                <i class="fa-solid fa-chalkboard-user fa-3x text-warning mb-2"></i>
                <h6 class="fw-bold">Generate QR Banyak Guru</h6>
            </div>
        </div>

        <div class="col-md-4">
            <div class="mode-card text-center" onclick="setMode('kelas')">
                <i class="fa-solid fa-school fa-3x text-success mb-2"></i>
                <h6 class="fw-bold">Generate QR Satu Kelas</h6>
            </div>
        </div>
    </div>

    <!-- ============================= -->
    <!-- FORM -->
    <!-- ============================= -->
    <form action="<?= base_url('absensi/generate') ?>" method="post">
        <?= csrf_field() ?>
        <input type="hidden" name="mode" id="mode">

        <!-- SISWA -->
        <div id="form-siswa" class="d-none mb-4">
            <label class="fw-bold mb-2">Pilih Banyak Siswa</label>
            <select name="owner_id[]" multiple class="form-select select2-premium">


                <?php foreach ($siswa as $s): ?>
                    <option
                        value="<?= $s['id'] ?>"
                        data-role="siswa"
                        data-foto="<?= $s['foto'] ?>"
                        data-info="NISN: <?= $s['nisn'] ?> | Kelas: <?= $s['kelas'] ?>">
                        <?= $s['nama'] ?>
                    </option>
                <?php endforeach; ?>



            </select>
        </div>

        <!-- GURU -->
        <div id="form-guru" class="d-none mb-4">
            <label class="fw-bold mb-2">Pilih Banyak Guru</label>
            <select name="owner_id[]" multiple class="form-select select2-premium">

                <?php foreach ($guru as $g): ?>
                    <option
                        value="<?= $g['id'] ?>"
                        data-role="guru"
                        data-foto="<?= $g['foto'] ?>"
                        data-info="NIP: <?= $g['nip'] ?>">
                        <?= $g['nama'] ?>
                    </option>
                <?php endforeach; ?>


            </select>
        </div>

        <!-- KELAS -->
        <div id="form-kelas" class="d-none mb-4">
            <label class="fw-bold mb-2">Pilih Kelas</label>
            <select name="kelas_id" class="form-select select2-premium">
                <option value="">-- Pilih Kelas --</option>

                <?php foreach ($kelas as $k): ?>
                    <option value="<?= $k['id'] ?>"
                        data-role="kelas"
                        data-info="Kelas <?= $k['nama_kelas'] ?>">
                        <?= $k['nama_kelas'] ?>
                    </option>
                <?php endforeach; ?>


            </select>
        </div>

        <!-- PREVIEW -->
        <div id="preview" class="preview-box">
            <h5 class="fw-bold mb-3">Preview Identitas</h5>
            <div id="preview_content"></div>
        </div>

        <button type="submit" id="btn_generate"
            class="btn btn-primary btn-lg mt-4 px-4 d-none">
            <i class="fa-solid fa-qrcode me-2"></i> Generate QR Premium
        </button>
    </form>

</div>


<!-- ============================= -->
<!-- JAVASCRIPT LOGIC -->
<!-- ============================= -->
<script>
    function setMode(mode) {
        $('#mode').val(mode);

        $("#form-siswa, #form-guru, #form-kelas").addClass("d-none");
        $("#btn_generate").addClass("d-none");
        $("#preview").hide();

        if (mode === 'siswa') $("#form-siswa").removeClass("d-none");
        if (mode === 'guru') $("#form-guru").removeClass("d-none");
        if (mode === 'kelas') {
            $("#form-kelas").removeClass("d-none");
            $("#btn_generate").removeClass("d-none");
        }
    }

    // =========================
    // SELECT2 PREMIUM
    // =========================
    $('.select2-premium').select2({
        theme: "bootstrap-5",
        placeholder: "Pilih data...",
        allowClear: true,
        templateResult: formatOption,
        templateSelection: formatSelection
    });

    function formatOption(state) {
        if (!state.id) return state.text;

        let role = $(state.element).data("role");
        let foto = $(state.element).data("foto");
        let info = $(state.element).data("info");

        // ================================
        // 1. MODE KELAS (TIDAK ADA ROLE)
        // ================================
        if (role === undefined) {
            return $(`
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-school me-2 text-success"></i>
                <strong>${state.text}</strong>
            </div>
        `);
        }

        // ================================
        // 2. MODE SISWA / GURU
        // ================================
        let folder = role === 'guru' ?
            'uploads/guru/' :
            'uploads/siswa/';

        let img = foto ?
            "<?= base_url() ?>/" + folder + foto :
            "<?= base_url('assets/default/user.png') ?>";

        return $(`
        <div class="d-flex align-items-center">
            <img src="${img}" width="40" height="40" class="rounded-circle me-2"
                onerror="this.src='<?= base_url('assets/default/user.png') ?>'">
            <div>
                <strong>${state.text}</strong><br>
                <small class="text-muted">${info}</small>
            </div>
        </div>
    `);
    }



    // After selected display name only
    function formatSelection(state) {
        return state.text;
    }

    // =========================
    // Preview Multi-select
    // =========================
    $('.select2-premium').on('change', function() {

        let items = $(this).find(":selected");

        if (items.length === 0) {
            $("#preview").hide();
            return;
        }

        $("#preview_content").html("");

        items.each(function() {

            let foto = $(this).data('foto');
            let role = $(this).data('role');
            let info = $(this).data('info') ?? '-';


            let folder = role === 'guru' ?
                'uploads/guru/' :
                'uploads/siswa/';

            let img = foto ?
                "<?= base_url() ?>/" + folder + foto :
                "<?= base_url('assets/default/user.png') ?>";

            $("#preview_content").append(`
            <div class="d-flex align-items-center mb-3">
                <img src="${img}" width="60" height="60" class="rounded-circle">
                <div class="ms-3">
                    <h6 class="fw-bold mb-0">${$(this).text()}</h6>
                    <small class="text-muted">${info}</small>
                </div>
            </div>
        `);
        });

        $("#preview").show();
        $("#btn_generate").removeClass("d-none");
    });
</script>

<?= $this->endSection() ?>