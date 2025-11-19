<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<!-- Select2 + jQuery -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.3.0/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>

<style>
    .card-mode {
        cursor: pointer;
        transition: .15s;
        border-radius: .6rem;
    }

    .card-mode:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 40px rgba(2, 6, 23, .12);
    }

    .select2-container--bootstrap-5 .select2-selection {
        min-height: 55px;
        padding: 8px 12px;
        border-radius: .6rem;
    }

    .preview-box {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 18px;
        display: none;
    }

    .select2-result-user {
        display: flex;
        align-items: center;
        gap: .75rem;
    }

    .select2-result-user img {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        object-fit: cover;
    }

    .preview-item {
        display: flex;
        align-items: center;
        gap: .85rem;
        margin-bottom: 12px;
    }

    .preview-item img {
        width: 64px;
        height: 64px;
        border-radius: 8px;
        object-fit: cover;
    }
</style>

<div class="card shadow-sm p-4">
    <h2 class="fw-bold mb-3">Generate QR Code Absensi (Premium)</h2>
    <div class="alert alert-info">Pilih mode generate lalu pilih satu atau beberapa pemilik QR. Multi-select didukung.</div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card p-4 card-mode text-center" onclick="setMode('siswa')">
                <i class="fa-solid fa-users fa-2x text-primary mb-2"></i>
                <div class="fw-bold">Generate Banyak Siswa</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 card-mode text-center" onclick="setMode('guru')">
                <i class="fa-solid fa-chalkboard-user fa-2x text-warning mb-2"></i>
                <div class="fw-bold">Generate Banyak Guru</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-4 card-mode text-center" onclick="setMode('kelas')">
                <i class="fa-solid fa-school fa-2x text-success mb-2"></i>
                <div class="fw-bold">Generate Satu Kelas</div>
            </div>
        </div>
    </div>

    <form method="post" action="<?= base_url('absensi/generate') ?>">
        <?= csrf_field() ?>
        <input type="hidden" id="mode" name="mode" value="">

        <!-- SISWA MULTI -->
        <div id="form-siswa" class="d-none mb-3">
            <label class="fw-bold mb-2">Pilih Banyak Siswa</label>
            <select id="siswa_select" name="owner_id[]" multiple class="form-select select2-premium">
                <?php foreach ($siswa as $s):
                    // build info text (kelas field in DB is string)
                    $info = ($s['nisn'] ?? '') . ($s['kelas'] ? " | {$s['kelas']}" : '');
                    $foto = $s['foto'] ?? '';
                ?>
                    <option value="<?= esc($s['id']) ?>"
                        data-info="<?= esc($info) ?>"
                        data-foto="<?= esc($foto) ?>"><?= esc($s['nama']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- GURU MULTI -->
        <div id="form-guru" class="d-none mb-3">
            <label class="fw-bold mb-2">Pilih Banyak Guru</label>
            <select id="guru_select" name="owner_id[]" multiple class="form-select select2-premium">
                <?php foreach ($guru as $g):
                    $info = $g['nip'] ?? '';
                    $foto = $g['foto'] ?? '';
                ?>
                    <option value="<?= esc($g['id']) ?>"
                        data-info="<?= esc($info) ?>"
                        data-foto="<?= esc($foto) ?>"><?= esc($g['nama']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- KELAS -->
        <div id="form-kelas" class="d-none mb-3">
            <label class="fw-bold mb-2">Pilih Kelas</label>
            <select name="kelas_id" id="kelas_select" class="form-select">
                <option value="">-- Pilih Kelas --</option>
                <?php foreach ($kelas as $k):
                    // $k may be array with key 'kelas' (from groupBy). handle both
                    $val = $k['kelas'] ?? ($k['nama_kelas'] ?? '');
                    if (!$val) continue;
                ?>
                    <option value="<?= esc($val) ?>"><?= esc($val) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- PREVIEW -->
        <div id="preview" class="preview-box mt-3">
            <h5 class="fw-bold mb-3">Preview Identitas</h5>
            <div id="preview_content"></div>
        </div>

        <button id="btn_generate" class="btn btn-primary btn-lg mt-3 d-none">
            <i class="fa-solid fa-qrcode me-2"></i> Generate QR Premium
        </button>
    </form>
</div>

<script>
    function setMode(m) {
        $('#mode').val(m);
        $('#form-siswa,#form-guru,#form-kelas').addClass('d-none');
        $('#btn_generate').addClass('d-none');
        $('#preview').hide().find('#preview_content').html('');

        if (m === 'siswa') $('#form-siswa').removeClass('d-none');
        if (m === 'guru') $('#form-guru').removeClass('d-none');
        if (m === 'kelas') {
            $('#form-kelas').removeClass('d-none');
            $('#btn_generate').removeClass('d-none');
        }
    }

    // Select2 init with template
    $('.select2-premium').select2({
        theme: 'bootstrap-5',
        placeholder: 'Pilih ...',
        allowClear: true,
        templateResult: formatUser,
        templateSelection: formatSelected,
        escapeMarkup: function(m) {
            return m;
        }
    });

    function formatUser(state) {
        if (!state.id) return state.text;
        let foto = $(state.element).data('foto');
        let info = $(state.element).data('info') || '';
        let img = foto ? "<?= base_url('uploads/foto/') ?>" + foto : "<?= base_url('assets/default/user.png') ?>";
        return `
        <div class="select2-result-user">
            <img src="${img}" onerror="this.src='<?= base_url('assets/default/user.png') ?>'">
            <div>
                <div class="fw-bold">${state.text}</div>
                <small class="text-muted">${info}</small>
            </div>
        </div>
    `;
    }

    function formatSelected(state) {
        return state.text;
    }

    // preview when select changes
    $('#siswa_select, #guru_select').on('change', function() {
        let items = $(this).find('option:selected');
        if (items.length === 0) {
            $('#preview').hide();
            $('#btn_generate').addClass('d-none');
            return;
        }
        $('#preview_content').html('');
        items.each(function() {
            let foto = $(this).data('foto');
            let info = $(this).data('info') || '';
            let name = $(this).text();
            let img = foto ? "<?= base_url('uploads/foto/') ?>" + foto : "<?= base_url('assets/default/user.png') ?>";
            let node = `
            <div class="preview-item">
                <img src="${img}" onerror="this.src='<?= base_url('assets/default/user.png') ?>'">
                <div>
                    <div class="fw-bold">${name}</div>
                    <div class="text-muted">${info}</div>
                </div>
            </div>
        `;
            $('#preview_content').append(node);
        });
        $('#preview').show();
        $('#btn_generate').removeClass('d-none');
    });
</script>

<?= $this->endSection() ?>