<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<style>
    .bundle-wrapper {
        max-width: 1000px;
        margin: auto;
    }

    .qr-card {
        background: #ffffff;
        padding: 25px;
        border-radius: 18px;
        margin-bottom: 30px;
        box-shadow: 0 12px 35px rgba(0, 0, 0, .09);
        transition: .25s ease;
    }

    .qr-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 18px 45px rgba(0, 0, 0, .14);
    }

    .qr-img {
        width: 170px;
        padding: 8px;
        border-radius: 12px;
        background: #ffffff;
        box-shadow: 0 4px 18px rgba(0, 0, 0, .12);
    }

    .avatar-box img {
        width: 75px;
        height: 75px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 6px 20px rgba(0, 0, 0, .15);
    }

    .token-box {
        background: #eef7ff;
        padding: 10px 15px;
        border-radius: 10px;
        margin-top: 10px;
        border-left: 4px solid #0d6efd;
        display: inline-block;
        word-break: break-all;
        font-size: .9rem;
    }

    .token-toggle {
        cursor: pointer;
        color: #0d6efd;
        font-size: .85rem;
    }

    .header-box {
        background: #f0f7ff;
        padding: 16px 20px;
        border-radius: 14px;
        border-left: 5px solid #0d6efd;
        margin-bottom: 25px;
    }
</style>

<?php
$list = $list ?? [];
$ids = array_map(fn($r) => $r['barcode']['id'], $list);
?>

<div class="bundle-wrapper">

    <h2 class="fw-bold mb-4">Bundle QR Code Premium</h2>

    <div class="header-box">
        Total QR berhasil digenerate:
        <strong><?= count($list) ?></strong><br>
        Anda dapat mencetak atau mendownload bundle ZIP.
    </div>

    <!-- DOWNLOAD ZIP -->
    <form method="post" action="<?= base_url('absensi/download-bundle') ?>" class="mb-4">
        <?= csrf_field() ?>
        <input type="hidden" name="ids" value="<?= implode(',', $ids) ?>">

        <button class="btn btn-success btn-lg px-4">
            <i class="fa-solid fa-file-zipper me-2"></i> Download ZIP
        </button>
    </form>

    <!-- LIST QR -->
    <?php foreach ($list as $row): ?>
        <?php
        $b = $row['barcode'];
        $u = $row['owner'];

        $img = !empty($u['foto'])
            ? base_url('uploads/' . $b['owner_type'] . '/' . $u['foto'])
            : base_url('assets/default/user.png');

        $qrImg = base_url($b['file_path']);
        $uniqueID = "token_" . $b['id'];
        ?>

        <div class="qr-card row g-4 align-items-center">

            <!-- QR IMAGE -->
            <div class="col-md-4 text-center">
                <img src="<?= $qrImg ?>" class="qr-img">
            </div>

            <!-- INFO -->
            <div class="col-md-8">

                <div class="d-flex align-items-center mb-2">
                    <div class="avatar-box me-3">
                        <img src="<?= $img ?>">
                    </div>

                    <div>
                        <h4 class="fw-bold mb-1"><?= esc($u['nama']) ?></h4>

                        <?php if (isset($u['nisn'])): ?>
                            <div class="text-muted">NISN: <?= esc($u['nisn']) ?></div>
                            <div class="text-muted">Kelas: <?= esc($u['kelas']) ?></div>
                        <?php endif; ?>

                        <?php if (isset($u['nip'])): ?>
                            <div class="text-muted">NIP: <?= esc($u['nip']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- TOKEN -->
                <div class="token-box">
                    <span id="<?= $uniqueID ?>">••••••••••••••••••</span>
                </div>

                <div class="token-toggle" onclick="toggleToken('<?= $uniqueID ?>', '<?= $b['token'] ?>')">
                    <i class="fa-solid fa-eye"></i> tampilkan token
                </div>

                <!-- BUTTON -->
                <div class="mt-3">
                    <a href="<?= base_url('absensi/qrcode/' . $b['id']) ?>" class="btn btn-primary px-3">
                        <i class="fa-solid fa-eye me-1"></i> Lihat QR
                    </a>
                </div>

            </div>
        </div>

    <?php endforeach; ?>

</div>

<script>
    function toggleToken(id, realToken) {
        let el = document.getElementById(id);

        if (el.dataset.show === "1") {
            el.innerText = "••••••••••••••••••";
            el.dataset.show = "0";
        } else {
            el.innerText = realToken;
            el.dataset.show = "1";
        }
    }
</script>

<?= $this->endSection() ?>