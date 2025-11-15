<?php

$uri = service('uri');

$segment = ''; // default
$total = (int) $uri->getTotalSegments();

if ($total >= 2) {
    $segment = $uri->getSegment(2);
} elseif ($total === 1) {
    $segment = $uri->getSegment(1);
}
// now $segment is safe (possibly empty string)
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= esc($title ?? 'Dashboard') ?> | SMK Galajuara</title>
    <!-- ======== CSS GLOBAL ======== -->

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <!-- SELECT2 CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />



    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom Style (Premium Minimal) -->
    <style>
        /* Custom Style (Premium Minimal) */
        :root {
            --sidebar-bg: #0f172a;
            --sidebar-active: #2563eb;
            --sidebar-hover: #1e40af;
            --sidebar-text: #cbd5e1;
            --sidebar-border: #334155;
            --transition-speed: .3s;

            --navy: #0b3a66;
            --gold: #d4af37;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f8fafc;
            overflow-x: hidden;
        }

        /* =========================================================
           SIDEBAR PREMIUM
        ========================================================= */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: var(--sidebar-bg);
            color: white;
            transition: all var(--transition-speed) ease;
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 3px 0 12px rgba(0, 0, 0, 0.25);
        }

        .sidebar.collapsed {
            width: 80px;
        }

        /* Logo */
        .sidebar .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 1.1rem;
            font-weight: bold;
            padding: 1rem;
            border-bottom: 1px solid var(--sidebar-border);
            background: #1e293b;
            letter-spacing: 0.5px;
        }

        /* Menu UL */
        .menu-list {
            list-style: none;
            margin: 0;
            padding: 0;
            margin-top: 10px;
        }

        /* Menu Item */
        .menu-link,
        .sidebar .dropdown-toggle {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 12px 20px;
            gap: 14px;
            color: var(--sidebar-text);
            text-decoration: none;
            border-left: 4px solid transparent;
            background: none;
            cursor: pointer;
            transition: background .22s, color .22s, padding .18s;
            font-size: 0.95rem;
            font-weight: 500;
        }

        /* Icon Sidebar */
        .menu-link i,
        .sidebar .dropdown-toggle i {
            width: 26px;
            text-align: center;
            font-size: 1.1rem;
        }

        .menu-link:hover,
        .sidebar .dropdown-toggle:hover {
            background: var(--sidebar-hover);
            color: #fff;
        }

        /* Active menu */
        .menu-link.active,
        .sidebar .dropdown-toggle.dropdown-open {
            background: var(--sidebar-active);
            color: #fff;
            border-left: 4px solid #60a5fa;
            box-shadow: inset 2px 0 5px rgba(0, 0, 0, 0.2);
        }

        /* Chevron Sidebar (single chevron only) */
        .sidebar .dropdown-toggle .chevron {
            margin-left: auto;
            font-size: .95rem;
            opacity: .95;
            transition: transform .28s cubic-bezier(.2, .8, .2, 1);
            display: inline-block;
            line-height: 1;
        }

        .sidebar .dropdown-toggle.dropdown-open .chevron {
            transform: rotate(180deg);
            opacity: 1;
        }

        /* Submenu */
        .submenu {
            list-style: none;
            padding-left: 60px;
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transform: translateY(-6px);
            transition:
                max-height 0.42s cubic-bezier(.25, .8, .25, 1),
                opacity 0.28s ease,
                transform 0.32s ease;
            margin: 0;
        }

        .submenu.show {
            opacity: 1;
            transform: translateY(0);
        }

        .submenu li a {
            display: block;
            padding: 8px 0;
            font-size: 0.9rem;
            color: var(--sidebar-text);
            text-decoration: none;
            transition: color .18s, transform .18s;
        }

        .submenu li a:hover {
            color: #fff;
            transform: translateX(5px);
        }

        .submenu li a.active {
            color: #fff;
            font-weight: 600;
            transform: translateX(4px);
        }

        /* Collapse */
        .sidebar.collapsed .menu-link span,
        .sidebar.collapsed .dropdown-toggle span {
            display: none;
        }

        .sidebar.collapsed .submenu {
            display: none !important;
        }

        .sidebar.collapsed .logo span {
            display: none;
        }

        /* =========================================================
           MAIN CONTENT
        ========================================================= */
        .main-content {
            margin-left: 250px;
            transition: all var(--transition-speed) ease;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-content.expanded {
            margin-left: 80px;
        }

        /* =========================================================
           TOPBAR FIX & PREMIUM UPGRADE
        ========================================================= */
        .topbar {
            background: white;
            padding: 12px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 900;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
        }

        .topbar .toggle-btn {
            border: none;
            background: transparent;
            font-size: 1.6rem;
            color: #0f172a;
            transition: transform .18s ease;
        }

        .topbar .toggle-btn:hover {
            transform: scale(1.05);
            color: var(--sidebar-active);
        }

        /* =========================================
           FIX — Dropdown user, supaya hover tidak hilang icon
        ======================================== */
        .topbar .dropdown-toggle {
            background: transparent !important;
            border: none;
            padding: 4px 8px;
            display: flex;
            align-items: center;
            color: #333 !important;
        }

        .topbar .dropdown-toggle:hover {
            background: #f5f5f5 !important;
            border-radius: 8px;
        }

        /* FIX caret dropdown */
        .topbar .dropdown-toggle::after {
            margin-left: .45rem;
            border-top: .40em solid;
            border-right: .35em solid transparent;
            border-left: .35em solid transparent;
        }

        /* User text */
        .topbar .dropdown-toggle span {
            font-weight: 600;
        }

        /* Dropdown menu styling */
        .dropdown-menu {
            border-radius: 10px;
            padding: 6px 0;
            font-size: .9rem;
        }

        /* Dropdown icons */
        .dropdown-item i {
            width: 18px;
            text-align: center;
        }

        /* Hover */
        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        /* =========================================================
           COMMON ELEMENTS
        ========================================================= */
        .avatar-sm {
            width: 38px;
            height: 38px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #ffffff33;
        }

        .content-card {
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 6px 18px rgba(11, 26, 46, 0.04);
            padding: 18px;
        }

        /* ===============================
           MOBILE SIDEBAR (UPGRADE PREMIUM)
           =============================== */
        @media (max-width: 768px) {

            /* sidebar default tertutup */
            .sidebar {
                left: -270px;
                width: 250px;
                transition: all .35s cubic-bezier(.25, .8, .25, 1);
                box-shadow: 4px 0 14px rgba(0, 0, 0, 0.25);
            }

            /* saat tombol diklik */
            .sidebar.show {
                left: 0;
            }

            /* konten tidak terdesak sidebar */
            .main-content {
                margin-left: 0 !important;
            }

            /* backdrop gelap modern ketika sidebar terbuka */
            body.sidebar-open::after {
                content: "";
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.35);
                backdrop-filter: blur(3px);
                z-index: 900;
            }
        }

        /* ===============================
           HAPUS CARET BOOTSTRAP SIDEBAR
           =============================== */
        .sidebar .dropdown-toggle::after,
        .sidebar-dropdown-toggle::after {
            display: none !important;
        }

        /* ===============================
           MAIN CONTENT PADDING
           =============================== */
        main {
            padding: 22px !important;
        }

        /* ===============================
           MOBILE CONTENT OPTIMIZED
           =============================== */
        @media (max-width: 768px) {

            main {
                padding: 10px !important;
            }

            .content-card,
            .dataTables_wrapper,
            .table,
            .card,
            .container-fluid,
            .row>div {
                width: 100% !important;
                max-width: 100% !important;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar" aria-label="Main sidebar">
        <div class="logo">
            <i class="fa fa-graduation-cap"></i>
            <span>My Deposite</span>
        </div>

        <?php $role = session()->get('role'); ?>

        <ul class="menu-list mt-3">

            <!-- DASHBOARD COMMON (all roles will have a dashboard link, adjusted per role) -->
            <?php if ($role === 'admin'): ?>
                <li>
                    <a href="<?= smart_url('dashboard') ?>" class="menu-link <?= $segment === 'dashboard' || $segment === '' ? 'active' : '' ?>">
                        <i class="fa fa-home"></i> <span>Dashboard</span>
                    </a>
                </li>
            <?php elseif ($role === 'guru'): ?>
                <li>
                    <a href="<?= smart_url('guru/dashboard') ?>" class="menu-link <?= $segment === 'dashboard' ? 'active' : '' ?>">
                        <i class="fa fa-home"></i> <span>Dashboard</span>
                    </a>
                </li>
            <?php elseif ($role === 'siswa'): ?>
                <li>
                    <a href="<?= smart_url('siswa/dashboard') ?>" class="menu-link <?= $segment === 'dashboard' ? 'active' : '' ?>">
                        <i class="fa fa-home"></i> <span>Dashboard</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- ADMIN MENU: Dropdown Professional -->
            <?php if ($role === 'admin'): ?>

                <!-- Manajemen Sekolah -->
                <li>
                    <a href="#" class="dropdown-toggle <?= in_array($segment, ['siswa', 'guru', 'kelas', 'mapel', 'jurusan']) ? 'dropdown-open' : '' ?>">
                        <i class="fa fa-school"></i> <span>Manajemen Sekolah</span>
                        <span class="chevron">▾</span>
                    </a>

                    <ul class="submenu <?= in_array($segment, ['siswa', 'guru', 'kelas', 'mapel', 'jurusan']) ? 'show' : '' ?>">
                        <li>
                            <a href="<?= smart_url('siswa') ?>" class="<?= $segment === 'siswa' ? 'active' : '' ?>">
                                Data Siswa
                            </a>
                        </li>

                        <li>
                            <a href="<?= smart_url('admin/guru') ?>" class="<?= $segment === 'guru' ? 'active' : '' ?>">
                                Data Guru
                            </a>
                        </li>

                        <li>
                            <a href="<?= smart_url('kelas') ?>" class="<?= $segment === 'kelas' ? 'active' : '' ?>">
                                Data Kelas
                            </a>
                        </li>

                        <li>
                            <a href="<?= smart_url('mapel') ?>" class="<?= $segment === 'mapel' ? 'active' : '' ?>">
                                Data Mapel
                            </a>
                        </li>

                        <li>
                            <a href="<?= smart_url('jurusan') ?>" class="<?= $segment === 'jurusan' ? 'active' : '' ?>">
                                Data Jurusan
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Keuangan -->
                <li>
                    <a href="#" class="dropdown-toggle <?= in_array($segment, ['tabungan', 'laporan']) ? 'dropdown-open' : '' ?>">
                        <i class="fa fa-wallet"></i> <span>Keuangan</span>
                        <span class="chevron">▾</span>
                    </a>

                    <ul class="submenu <?= in_array($segment, ['tabungan', 'laporan']) ? 'show' : '' ?>">
                        <li>
                            <a href="<?= smart_url('tabungan') ?>" class="<?= $segment === 'tabungan' ? 'active' : '' ?>">
                                Tabungan
                            </a>
                        </li>
                        <li>
                            <a href="<?= smart_url('laporan') ?>" class="<?= $segment === 'laporan' ? 'active' : '' ?>">
                                Laporan
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Lainnya (langsung link) -->
                <li>
                    <a href="<?= smart_url('users') ?>" class="menu-link <?= $segment === 'users' ? 'active' : '' ?>">
                        <i class="fa fa-user-shield"></i> <span>Manajemen User</span>
                    </a>
                </li>

            <?php elseif ($role === 'guru'): ?>
                <!-- GURU: keep simple links (you can extend similarly) -->
                <li>
                    <a href="#"
                        class="menu-link"
                        data-bs-toggle="modal"
                        data-bs-target="#modalTransaksi">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                        <span>Transaksi</span>
                    </a>
                </li>
                <li>
                    <a href="<?= smart_url('guru/kelas') ?>" class="menu-link <?= $segment === 'kelas' ? 'active' : '' ?>">
                        <i class="fa fa-users"></i> <span>Kelas Saya</span>
                    </a>
                </li>
                <li>
                    <a href="<?= smart_url('guru/siswa') ?>" class="menu-link <?= $segment === 'siswa' ? 'active' : '' ?>">
                        <i class="fa fa-user-graduate"></i> <span>Siswa Bimbingan</span>
                    </a>
                </li>
                <li>
                    <a href="<?= smart_url('guru/tugas') ?>"
                        class="menu-link <?= $segment === 'tugas' ? 'active' : '' ?>">
                        <i class="fa-solid fa-clipboard-list"></i>
                        <span>Manajemen Tugas</span>
                        <span class="badge bg-warning ms-2 small">New</span>
                    </a>
                </li>
            <?php elseif ($role === 'siswa'): ?>
                <!-- SISWA -->
                <li>
                    <a href="<?= smart_url('siswa/profil') ?>" class="menu-link <?= $segment === 'profil' ? 'active' : '' ?>">
                        <i class="fa-solid fa-user-graduate"></i> <span>Profil</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Logout always visible -->
            <li>
                <a href="<?= smart_url('logout') ?>" class="menu-link text-danger">
                    <i class="fa fa-sign-out-alt"></i> <span>Keluar</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <nav class="topbar d-flex justify-content-between align-items-center px-3 py-2 bg-white shadow-sm">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-primary btn-sm" id="toggleSidebar">
                    <i class="fa fa-bars"></i>
                </button>
            </div>


            <?php
            // 🧠 Ambil data user dari session (robust avatar handling)
            $role = session()->get('role');
            $fotoFile = session()->get('foto');

            // Fallback default
            $fotoUrl = smart_url('assets/img/default-user.png');

            if (!empty($fotoFile)) {

                // Cek jalur sesuai role
                if ($role === 'admin' && file_exists(FCPATH . 'uploads/admin/' . $fotoFile)) {
                    $fotoUrl = smart_url('uploads/admin/' . $fotoFile);
                } elseif ($role === 'guru' && file_exists(FCPATH . 'uploads/guru/' . $fotoFile)) {
                    $fotoUrl = smart_url('uploads/guru/' . $fotoFile);
                } elseif ($role === 'siswa' && file_exists(FCPATH . 'uploads/siswa/' . $fotoFile)) {
                    $fotoUrl = smart_url('uploads/siswa/' . $fotoFile);
                } elseif (file_exists(FCPATH . 'uploads/' . $fotoFile)) {
                    // fallback jika file user ada di root uploads
                    $fotoUrl = smart_url('uploads/' . $fotoFile);
                }
            }


            $namaUser = session()->get('nama') ?? session()->get('username') ?? 'Pengguna';
            $roleUser = ucfirst($role ?? 'User');
            ?>

            <!-- Dropdown User -->
            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                    data-bs-toggle="dropdown" aria-expanded="false">

                    <img src="<?= $fotoUrl ?>"
                        alt="User"
                        width="38" height="38"
                        class="rounded-circle border me-2 shadow-sm avatar-sm">

                    <div class="d-none d-sm-block text-start">
                        <span class="fw-semibold d-block"><?= esc($namaUser) ?></span>
                        <small class="text-muted"><?= esc($roleUser) ?></small>
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">

                    <?php if ($role === 'admin'): ?>

                        <li>
                            <a class="dropdown-item" href="<?= smart_url('admin/profil') ?>">
                                <i class="fa-solid fa-user-gear text-primary me-2"></i> Profil
                            </a>
                        </li>

                    <?php elseif ($role === 'guru'): ?>

                        <li>
                            <a class="dropdown-item" href="<?= smart_url('guru/profil') ?>">
                                <i class="fa-solid fa-user text-primary me-2"></i> Profil
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="<?= smart_url('guru/ganti-password') ?>">
                                <i class="fa-solid fa-lock text-warning me-2"></i> Ganti Password
                            </a>
                        </li>

                    <?php elseif ($role === 'siswa'): ?>

                        <li>
                            <a class="dropdown-item" href="<?= smart_url('siswa/profil') ?>">
                                <i class="fa-solid fa-user text-primary me-2"></i> Profil
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item" href="<?= smart_url('siswa/ganti-password') ?>">
                                <i class="fa-solid fa-lock text-warning me-2"></i> Ganti Password
                            </a>
                        </li>

                    <?php endif; ?>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item text-danger" href="<?= smart_url('logout') ?>">
                            <i class="fa-solid fa-right-from-bracket me-2"></i> Keluar
                        </a>
                    </li>

                </ul>
            </div>

        </nav>

        <style>
            .topbar {
                position: sticky;
                top: 0;
                z-index: 1020;
                transition: all .3s ease;
            }

            .dropdown-item i {
                width: 18px;
                text-align: center;
            }

            .dropdown-item:hover {
                background-color: #f8f9fa;
            }

            footer {
                text-align: center;
                padding: 10px;
                color: #6c757d;
                font-size: 0.9rem;
            }
        </style>

        <!-- small inline script for initial submenu open based on active -->
        <script>
            (function() {
                document.addEventListener('DOMContentLoaded', function() {
                    // open any submenu that contains an active link and set proper maxHeight
                    document.querySelectorAll('.submenu').forEach(function(sub) {
                        if (sub.querySelector('a.active')) {
                            sub.classList.add('show');
                            sub.style.maxHeight = sub.scrollHeight + 'px';
                            var prev = sub.previousElementSibling;
                            if (prev && prev.classList) prev.classList.add('dropdown-open');
                        }
                    });
                });
            })();
        </script>

        <!-- Konten Utama -->
        <main class="flex-grow-1 p-4">
            <?= $this->renderSection('content') ?>
        </main>

        <footer>
            © <?= date('Y') ?> Zulfiqri,S.Kom — Sistem Tabungan Siswa
        </footer>
    </div>

    <!-- ======== JS GLOBAL ======== -->

    <!-- jQuery HARUS pertama -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Select2 (setelah jQuery, sekali saja) -->

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <!-- Sidebar Toggle Animation & Dropdown (Premium Stable Version) -->
    <script>
        (function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.getElementById('toggleSidebar');

            // helper untuk set sidebar-open body class (mobile backdrop)
            function setBodySidebarOpen(open) {
                if (open) document.body.classList.add('sidebar-open');
                else document.body.classList.remove('sidebar-open');
            }

            // restore collapse preference (optional)
            const savedCollapsed = localStorage.getItem('sidebarCollapsed') === '1';
            if (savedCollapsed && window.innerWidth > 768) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('expanded');
            }

            // 1) TOGGLE SIDEBAR — DESKTOP & MOBILE
            if (toggleBtn) {
                toggleBtn.addEventListener('click', (e) => {
                    e.stopPropagation();

                    // DESKTOP MODE: collapse / expand
                    if (window.innerWidth > 768) {
                        const isCollapsed = sidebar.classList.toggle('collapsed');
                        mainContent.classList.toggle('expanded');
                        // store preference
                        localStorage.setItem('sidebarCollapsed', isCollapsed ? '1' : '0');
                        return;
                    }

                    // MOBILE MODE: slide-in
                    const isShown = sidebar.classList.toggle('show');
                    setBodySidebarOpen(isShown);
                });
            }

            // 2) SIDEBAR DROPDOWN — SMOOTH VERSION (isolated to sidebar)
            document.querySelectorAll(".sidebar .dropdown-toggle").forEach(menu => {
                // ensure single chevron: already in HTML, remove bootstrap caret bloat
                menu.classList.add("sidebar-dropdown-toggle");

                menu.addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const submenu = this.nextElementSibling;
                    if (!submenu) return;

                    const isOpen = submenu.classList.contains("show");

                    // Close all other submenus
                    document.querySelectorAll(".sidebar .submenu").forEach(s => {
                        if (s === submenu) return;
                        s.style.maxHeight = '0px';
                        s.classList.remove('show');
                        const prevT = s.previousElementSibling;
                        if (prevT) prevT.classList.remove('dropdown-open');
                    });

                    if (!isOpen) {
                        // open: set show and animate maxHeight
                        submenu.classList.add('show');
                        // allow CSS transition to run: set maxHeight to scrollHeight
                        submenu.style.maxHeight = submenu.scrollHeight + 'px';
                        this.classList.add('dropdown-open');
                        // after transition, clear inline maxHeight so future height changes (responsive) work
                        const clearMax = () => {
                            if (submenu.classList.contains('show')) {
                                submenu.style.maxHeight = submenu.scrollHeight + 'px';
                            } else {
                                submenu.style.maxHeight = '0px';
                            }
                        };
                        // run once after transition
                        submenu.addEventListener('transitionend', function handler() {
                            clearMax();
                            submenu.removeEventListener('transitionend', handler);
                        });
                    } else {
                        // close
                        submenu.style.maxHeight = submenu.scrollHeight + 'px';
                        // force reflow to enable transition from current height to 0
                        submenu.offsetHeight;
                        submenu.style.maxHeight = '0px';
                        this.classList.remove('dropdown-open');
                        // remove show class after transition to keep consistent state
                        submenu.addEventListener('transitionend', function handler() {
                            submenu.classList.remove('show');
                            submenu.removeEventListener('transitionend', handler);
                        });
                    }
                });
            });

            // 3) CLOSE SIDEBAR ON OUTSIDE CLICK (MOBILE ONLY)
            document.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                        if (sidebar.classList.contains('show')) {
                            sidebar.classList.remove('show');
                            setBodySidebarOpen(false);
                        }
                    }
                }
            });

            // also close on ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                        setBodySidebarOpen(false);
                    }
                }
            });

            // 4) AUTO-CLOSE SUBMENU SAAT PERPINDAHAN LAYAR
            window.addEventListener('resize', function() {
                if (window.innerWidth <= 768) {
                    document.querySelectorAll('.submenu').forEach(s => {
                        s.style.maxHeight = '0px';
                        s.classList.remove('show');
                    });
                    document.querySelectorAll('.sidebar .dropdown-toggle').forEach(t => t.classList.remove('dropdown-open'));
                } else {
                    // on larger screens ensure any shown submenu has correct maxHeight
                    document.querySelectorAll('.submenu.show').forEach(s => {
                        s.style.maxHeight = s.scrollHeight + 'px';
                    });
                }
            });

            // 5) AUTO-OPEN SUBMENU AKTIF (PENTING!!)
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.submenu').forEach(sub => {
                    if (sub.querySelector('a.active')) {
                        sub.classList.add('show');
                        sub.style.maxHeight = sub.scrollHeight + 'px';
                        const parentToggle = sub.previousElementSibling;
                        if (parentToggle) parentToggle.classList.add('dropdown-open');
                    } else {
                        // ensure closed
                        sub.style.maxHeight = '0px';
                        sub.classList.remove('show');
                    }
                });
            });

        })();
    </script>

    <?= $this->renderSection('scripts') ?>
</body>

</html>