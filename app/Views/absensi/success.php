<?= $this->extend('layout/main'); ?>
<?= $this->section('content'); ?>

<?php
$role = session()->get('role');

$dashboardURL = ($role === 'admin')
    ? base_url('absensi/dashboard')
    : (($role === 'guru')
        ? base_url('guru/dashboard')
        : base_url('siswa/dashboard'));
?>

<style>
    /* =======================
       WRAPPER ANIMATION
    ======================== */
    .success-wrapper {
        max-width: 650px;
        margin: 45px auto;
        padding: 40px;
        background: #ffffff;
        border-radius: 22px;
        text-align: center;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.10);
        animation: fadeInUp .8s ease forwards;
        opacity: 0;
        transform: translateY(30px);
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* =======================
       ICON ANIMATION
    ======================== */
    .success-icon {
        width: 110px;
        height: 110px;
        background: #28a745;
        color: #fff;
        border-radius: 50%;
        font-size: 55px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: auto;
        animation: pop .5s ease-out, pulse 1.8s infinite ease-in-out;
    }

    @keyframes pop {
        0% {
            transform: scale(.6);
            opacity: 0;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.06);
        }

        100% {
            transform: scale(1);
        }
    }

    /* =======================
       INFO BOX
    ======================== */
    .info-box {
        background: #eef6ff;
        padding: 18px;
        border-left: 5px solid #0d6efd;
        border-radius: 12px;
        margin: 25px 0;
        text-align: left;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    /* =======================
       BUTTON
    ======================== */
    .dashboard-btn {
        width: 100%;
        padding: 14px;
        font-size: 17px;
        font-weight: 600;
        border-radius: 12px;
    }

    /* =======================
       LOADER LINE
    ======================== */
    .loader-line {
        width: 100%;
        height: 6px;
        background: #e1e1e1;
        border-radius: 12px;
        margin-top: 22px;
        overflow: hidden;
    }

    .loader-fill {
        height: 100%;
        width: 0%;
        background: #0d6efd;
        transition: width linear;
    }
</style>


<!-- SOUND SUCCESS -->
<audio id="successSound" preload="auto">
    <source src="<?= base_url('assets/sounds/success.mp3') ?>" type="audio/mpeg">
</audio>


<div class="success-wrapper">

    <div class="success-icon">
        <i class="fa fa-check"></i>
    </div>

    <h2 class="fw-bold mt-3">Absensi Berhasil!</h2>
    <p class="text-muted">Data absensi berhasil dicatat ke sistem.</p>

    <div class="info-box">
        <strong>Status Hari Ini:</strong><br>
        Waktu: <?= date('d M Y • H:i') ?>
    </div>

    <a href="<?= $dashboardURL ?>" class="btn btn-primary dashboard-btn">
        <i class="fa fa-home me-2"></i> Kembali ke Dashboard
    </a>

    <div class="loader-line mt-3">
        <div class="loader-fill" id="loaderFill"></div>
    </div>

    <small class="text-muted">Anda akan diarahkan dalam <span id="countdown">3</span> detik...</small>
</div>


<script>
    // PLAY SOUND ONCE
    const audio = document.getElementById("successSound");
    setTimeout(() => {
        audio.play().catch(() => {});
    }, 200);

    // REDIRECT COUNTDOWN + PROGRESS
    let timeLeft = 3;
    const countdown = document.getElementById("countdown");
    const loaderFill = document.getElementById("loaderFill");

    function updateLoader() {
        loaderFill.style.width = ((3 - timeLeft) / 3 * 100) + "%";
    }

    const timer = setInterval(() => {
        timeLeft--;
        countdown.innerText = timeLeft;
        updateLoader();

        if (timeLeft <= 0) {
            clearInterval(timer);
            window.location.href = "<?= $dashboardURL ?>";
        }
    }, 1000);

    updateLoader();
</script>


<?= $this->endSection(); ?>