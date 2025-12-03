<div class="sidebar shadow-lg">
    <div class="brand text-center py-4 border-bottom border-secondary-subtle">
        <img src="https://upload.wikimedia.org/wikipedia/commons/4/47/Logo_SMK.png" alt="logo" class="mb-2" style="height:55px;">
        <h6 class="fw-bold text-white mb-0">Sistem Informasi Sekolah</h6>
        <small class="text-light opacity-75">Sistem Tabungan</small>
    </div>

    <ul class="nav flex-column mt-3">
        <li class="nav-item">
            <a href="<?= site_url('/') ?>" class="nav-link px-3">
                <i class="fa-solid fa-gauge-high me-2"></i><span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= site_url('siswa') ?>" class="nav-link px-3">
                <i class="fa-solid fa-user-graduate me-2"></i><span>Data Siswa</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= site_url('tabungan') ?>" class="nav-link px-3">
                <i class="fa-solid fa-wallet me-2"></i><span>Tabungan</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= site_url('laporan') ?>" class="nav-link px-3">
                <i class="fa-solid fa-chart-line me-2"></i><span>Laporan</span>
            </a>
        </li>
        <li class="nav-item mt-auto border-top border-secondary-subtle">
            <a href="#" class="nav-link px-3 text-danger">
                <i class="fa-solid fa-right-from-bracket me-2"></i><span>Keluar</span>
            </a>
        </li>
    </ul>
</div>

<style>
    .sidebar {
        background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        width: 260px;
        color: #fff;
        transition: all .3s ease;
        z-index: 1031;
    }

    .sidebar .nav-link {
        color: #cfd8dc;
        font-weight: 500;
        display: flex;
        align-items: center;
        padding: 10px 20px;
        border-left: 4px solid transparent;
        transition: .25s ease;
    }

    .sidebar .nav-link i {
        font-size: 1.1rem;
        width: 25px;
        text-align: center;
    }

    .sidebar .nav-link:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.1);
        border-left-color: #3b82f6;
        transform: translateX(2px);
    }

    .sidebar .nav-link.active {
        color: #fff;
        background: rgba(255, 255, 255, 0.15);
        border-left-color: #0d6efd;
        font-weight: 600;
    }

    .sidebar.collapsed {
        width: 80px;
    }

    .sidebar.collapsed .nav-link span {
        display: none;
    }

    .sidebar.collapsed .nav-link i {
        margin-right: 0;
    }

    .sidebar.collapsed .brand h6,
    .sidebar.collapsed .brand small {
        display: none;
    }

    @media(max-width:768px) {
        .sidebar {
            left: -260px;
        }

        .sidebar.active {
            left: 0;
        }
    }
</style>

<script>
    // Highlight active menu
    $('.sidebar .nav-link').each(function() {
        if (this.href === window.location.href) $(this).addClass('active');
    });
</script>