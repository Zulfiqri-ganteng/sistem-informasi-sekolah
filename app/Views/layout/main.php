<?php
$uri = service('uri');

// AMBIL SEMUA SEGMEN SEBAGAI ARRAY AMAN (0-based)
$segments = $uri->getSegments();

// Segment utama untuk sidebar
$segment  = $segments[0] ?? '';   // sama dengan getSegment(1)
$segment2 = $segments[1] ?? '';   // sama dengan getSegment(2)
$segment3 = $segments[2] ?? '';   // sama dengan getSegment(3)

// Enhanced active detection
$currentUri = $uri->getPath();
$isAbsensiActive = strpos($currentUri, 'absensi') !== false;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <meta name="csrf-header" content="<?= csrf_header() ?>">

    <title><?= esc($title ?? 'Dashboard') ?> | Sistem Informasi Sekolah</title>

    <!-- ======== CSS GLOBAL ======== -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- SweetAlert -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- LIGHT MODE PREMIUM STYLE -->
    <style>
        :root {
            --sidebar-bg: #ffffff;
            --sidebar-active: #3b82f6;
            --sidebar-hover: #f1f5f9;
            --sidebar-text: #475569;
            --sidebar-border: #e2e8f0;
            --transition-speed: 0.4s;
            --sidebar-width: 280px;
            --sidebar-collapsed: 80px;
            --accent-glow: rgba(59, 130, 246, 0.1);
            --glass-bg: rgba(255, 255, 255, 0.95);
            --primary: #3b82f6;
            --primary-dark: #2563eb;

            /* Animation Curves */
            --ease-out-quint: cubic-bezier(0.23, 1, 0.32, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f8fafc;
            overflow-x: hidden;
            min-height: 100vh;
            color: #334155;
        }

        /* =========================================================
           LIGHT MODE SIDEBAR DESIGN - SCROLL FIXED
        ========================================================= */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
            color: #334155;
            transition: all var(--transition-speed) var(--ease-out-quint);
            z-index: 1050;
            /* PERBAIKAN SCROLL: overflow-y auto */
            overflow-x: hidden;
            overflow-y: auto;
            box-shadow:
                4px 0 20px rgba(0, 0, 0, 0.08),
                inset -1px 0 0 #e2e8f0;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }

        /* Custom Scrollbar untuk Light Mode */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 3px;
            margin: 5px 0;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #cbd5e1, #94a3b8);
            border-radius: 3px;
            transition: all 0.3s ease;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #94a3b8, #64748b);
        }

        /* Logo Section - Light Mode */
        .sidebar .logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem 1.5rem;
            text-align: center;
            background: var(--glass-bg);
            border-bottom: 1px solid var(--sidebar-border);
            transition: all var(--transition-speed) ease;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
            flex-shrink: 0;
            /* Prevent logo from shrinking */
        }

        .sidebar .logo::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80%;
            height: 1px;
            background: linear-gradient(90deg,
                    transparent,
                    var(--sidebar-active),
                    transparent);
        }

        .sidebar .logo img {
            width: 52px;
            height: 52px;
            border-radius: 12px;
            object-fit: cover;
            margin-bottom: 12px;
            transition: all var(--transition-speed) var(--ease-out-quint);
            border: 2px solid rgba(59, 130, 246, 0.2);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }

        .sidebar.collapsed .logo img {
            width: 44px;
            height: 44px;
            border-radius: 10px;
        }

        .logo-text {
            transition: all var(--transition-speed) var(--ease-out-quint);
            opacity: 1;
            transform: translateY(0);
        }

        .sidebar.collapsed .logo-text {
            opacity: 0;
            transform: translateY(-10px);
            height: 0;
            overflow: hidden;
        }

        .logo-main-text {
            font-size: 1.2rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .logo-sub-text {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        /* Menu List Container */
        .menu-list {
            list-style: none;
            margin: 0;
            padding: 1.5rem 0;
            position: relative;
            flex: 1;
            /* Take remaining space */
            overflow-y: auto;
            /* Enable scrolling for menu items */
        }

        .menu-list::before {
            content: '';
            position: absolute;
            top: 0;
            left: 20px;
            right: 20px;
            height: 1px;
            background: linear-gradient(90deg,
                    transparent,
                    var(--sidebar-active),
                    transparent);
        }

        /* Premium Menu Items - Light Mode */
        .menu-link,
        .sidebar .dropdown-toggle {
            display: flex;
            align-items: center;
            width: calc(100% - 2rem);
            margin: 0.25rem 1rem;
            padding: 0.9rem 1.2rem;
            gap: 1rem;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 12px;
            background: transparent;
            cursor: pointer;
            transition: all 0.35s var(--ease-out-quint);
            font-size: 0.92rem;
            font-weight: 600;
            position: relative;
            overflow: hidden;
            border: 1px solid transparent;
        }

        .menu-link::before,
        .sidebar .dropdown-toggle::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(59, 130, 246, 0.08),
                    transparent);
            transition: left 0.6s var(--ease-out-quint);
        }

        .menu-link:hover::before,
        .sidebar .dropdown-toggle:hover::before {
            left: 100%;
        }

        /* Icons */
        .menu-link i,
        .sidebar .dropdown-toggle i {
            width: 22px;
            text-align: center;
            font-size: 1.2rem;
            transition: all 0.3s var(--ease-out-quint);
            position: relative;
            z-index: 2;
            color: #64748b;
        }

        .menu-link:hover i,
        .sidebar .dropdown-toggle:hover i {
            transform: scale(1.15) translateY(-1px);
            color: var(--sidebar-active);
        }

        /* Active States - Light Mode */
        .menu-link.active,
        .sidebar .dropdown-toggle.dropdown-open {
            background: linear-gradient(135deg,
                    rgba(59, 130, 246, 0.1) 0%,
                    rgba(99, 102, 241, 0.05) 100%);
            color: var(--sidebar-active);
            border: 1px solid rgba(59, 130, 246, 0.2);
            box-shadow:
                inset 0 2px 8px rgba(59, 130, 246, 0.1),
                0 2px 12px rgba(59, 130, 246, 0.1);
            transform: translateX(4px);
        }

        .menu-link.active::after,
        .sidebar .dropdown-toggle.dropdown-open::after {
            content: '';
            position: absolute;
            right: -2px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: linear-gradient(180deg, var(--sidebar-active), var(--primary-dark));
            border-radius: 2px;
            box-shadow: 0 0 8px rgba(59, 130, 246, 0.4);
        }

        .menu-link.active i,
        .sidebar .dropdown-toggle.dropdown-open i {
            color: var(--sidebar-active);
        }

        /* Chevron Animation */
        .sidebar .dropdown-toggle .chevron {
            margin-left: auto;
            font-size: 0.8rem;
            opacity: 0.6;
            transition: all 0.4s var(--ease-out-quint);
            z-index: 2;
            color: #64748b;
        }

        .sidebar .dropdown-toggle.dropdown-open .chevron {
            transform: rotate(180deg) scale(1.2);
            opacity: 1;
            color: var(--sidebar-active);
        }

        /* Premium Submenu Design - Light Mode */
        .submenu {
            list-style: none;
            padding-left: 3.8rem;
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            transform: translateY(-15px) scale(0.95);
            transition:
                max-height 0.6s var(--ease-out-quint),
                opacity 0.4s ease 0.1s,
                transform 0.4s var(--ease-out-quint) 0.1s;
            margin: 0.5rem 0;
            background: rgba(241, 245, 249, 0.5);
            border-radius: 0 0 12px 12px;
            position: relative;
        }

        .submenu::before {
            content: '';
            position: absolute;
            top: 0;
            left: 2rem;
            width: 2px;
            height: 100%;
            background: linear-gradient(180deg,
                    transparent,
                    rgba(59, 130, 246, 0.3),
                    transparent);
        }

        .submenu.show {
            opacity: 1;
            transform: translateY(0) scale(1);
        }

        .submenu li {
            position: relative;
            opacity: 0;
            transform: translateX(-10px);
            transition: all 0.3s var(--ease-out-quint);
        }

        .submenu.show li {
            opacity: 1;
            transform: translateX(0);
        }

        .submenu.show li:nth-child(1) {
            transition-delay: 0.1s;
        }

        .submenu.show li:nth-child(2) {
            transition-delay: 0.15s;
        }

        .submenu.show li:nth-child(3) {
            transition-delay: 0.2s;
        }

        .submenu.show li:nth-child(4) {
            transition-delay: 0.25s;
        }

        .submenu.show li:nth-child(5) {
            transition-delay: 0.3s;
        }

        .submenu li::before {
            content: '';
            position: absolute;
            left: -1.2rem;
            top: 50%;
            width: 6px;
            height: 6px;
            background: #94a3b8;
            border-radius: 50%;
            transform: translateY(-50%);
            opacity: 0.5;
            transition: all 0.3s var(--ease-out-quint);
        }

        .submenu li a {
            display: block;
            padding: 0.7rem 0;
            font-size: 0.86rem;
            color: #64748b;
            text-decoration: none;
            transition: all 0.3s var(--ease-out-quint);
            position: relative;
            font-weight: 500;
            letter-spacing: 0.2px;
        }

        .submenu li a:hover {
            color: var(--sidebar-active);
            padding-left: 8px;
        }

        .submenu li a:hover::before {
            opacity: 1;
            transform: translateY(-50%) scale(1.3);
            background: var(--sidebar-active);
        }

        .submenu li a.active {
            color: var(--sidebar-active);
            font-weight: 700;
            padding-left: 8px;
        }

        .submenu li a.active::before {
            opacity: 1;
            background: var(--sidebar-active);
            transform: translateY(-50%) scale(1.3);
            box-shadow: 0 0 8px rgba(59, 130, 246, 0.4);
        }

        /* Collapsed State */
        .sidebar.collapsed .menu-link span,
        .sidebar.collapsed .dropdown-toggle span:not(.chevron) {
            opacity: 0;
            width: 0;
            overflow: hidden;
            transition: all var(--transition-speed) var(--ease-out-quint);
        }

        .sidebar.collapsed .submenu {
            display: none !important;
        }

        .sidebar.collapsed .dropdown-toggle .chevron {
            display: none;
        }

        /* Tooltip System untuk Light Mode */
        .sidebar.collapsed .menu-link:hover::after,
        .sidebar.collapsed .dropdown-toggle:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            left: calc(100% + 15px);
            top: 50%;
            transform: translateY(-50%) translateX(-10px);
            background: rgba(255, 255, 255, 0.95);
            color: #334155;
            padding: 0.7rem 1.2rem;
            border-radius: 10px;
            font-size: 0.82rem;
            font-weight: 600;
            white-space: nowrap;
            box-shadow:
                0 8px 30px rgba(0, 0, 0, 0.15),
                0 0 0 1px rgba(0, 0, 0, 0.05);
            z-index: 1060;
            backdrop-filter: blur(10px);
            border: 1px solid #e2e8f0;
            opacity: 0;
            animation: tooltipSlide 0.3s var(--ease-out-quint) forwards;
            pointer-events: none;
        }

        @keyframes tooltipSlide {
            to {
                opacity: 1;
                transform: translateY(-50%) translateX(0);
            }
        }

        /* =========================================================
           MAIN CONTENT - LIGHT MODE
        ========================================================= */
        .main-content {
            margin-left: var(--sidebar-width);
            transition: all var(--transition-speed) var(--ease-out-quint);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: #f8fafc;
            position: relative;
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed);
        }

        /* =========================================================
           TOPBAR - LIGHT MODE
        ========================================================= */
        .topbar {
            background: rgba(255, 255, 255, 0.9);
            padding: 0.8rem 2rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1040;
            backdrop-filter: blur(10px);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .topbar .toggle-btn {
            border: none;
            background: rgba(59, 130, 246, 0.1);
            font-size: 1.3rem;
            color: #475569;
            transition: all 0.3s var(--ease-out-quint);
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
        }

        .topbar .toggle-btn:hover {
            background: rgba(59, 130, 246, 0.2);
            color: var(--sidebar-active);
            transform: rotate(90deg) scale(1.1);
            border-color: rgba(59, 130, 246, 0.3);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }

        /* User Dropdown */
        .topbar .dropdown-toggle {
            background: rgba(255, 255, 255, 0.8) !important;
            border: 1px solid #e2e8f0;
            padding: 0.4rem 1rem;
            display: flex;
            align-items: center;
            color: #334155 !important;
            transition: all 0.3s var(--ease-out-quint);
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .topbar .dropdown-toggle:hover {
            background: rgba(59, 130, 246, 0.1) !important;
            transform: translateY(-2px);
            border-color: rgba(59, 130, 246, 0.3);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
        }

        .avatar-sm {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #e2e8f0;
            transition: all 0.3s var(--ease-out-quint);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .topbar .dropdown-toggle:hover .avatar-sm {
            border-color: var(--sidebar-active);
            transform: scale(1.08);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.2);
        }

        /* Dropdown Menu */
        .dropdown-menu {
            border-radius: 12px;
            padding: 0.6rem 0;
            font-size: 0.9rem;
            border: 1px solid #e2e8f0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .dropdown-item {
            padding: 0.7rem 1.2rem;
            transition: all 0.3s var(--ease-out-quint);
            display: flex;
            align-items: center;
            color: #475569;
            position: relative;
            overflow: hidden;
        }

        .dropdown-item:hover {
            background: rgba(59, 130, 246, 0.05);
            padding-left: 1.5rem;
            color: var(--sidebar-active);
        }

        .dropdown-item i {
            width: 18px;
            text-align: center;
            margin-right: 0.7rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover i {
            transform: scale(1.2);
        }

        /* =========================================================
           MOBILE OPTIMIZATIONS
        ========================================================= */
        @media (max-width: 768px) {
            .sidebar {
                left: calc(-1 * var(--sidebar-width));
                width: var(--sidebar-width);
                transition: all 0.5s var(--ease-out-quint);
                box-shadow: 20px 0 40px rgba(0, 0, 0, 0.15);
            }

            .sidebar.show {
                left: 0;
            }

            .main-content {
                margin-left: 0 !important;
            }

            /* Mobile Backdrop */
            .sidebar-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                backdrop-filter: blur(4px);
                z-index: 1049;
                opacity: 0;
                visibility: hidden;
                transition: all 0.4s var(--ease-out-quint);
            }

            .sidebar-backdrop.show {
                opacity: 1;
                visibility: visible;
            }

            .sidebar.collapsed {
                width: var(--sidebar-width);
                left: calc(-1 * var(--sidebar-width));
            }

            .sidebar.collapsed.show {
                left: 0;
            }

            .topbar {
                padding: 0.8rem 1rem;
            }
        }

        /* =========================================================
           CONTENT AREA - LIGHT MODE
        ========================================================= */
        .content-card {
            border-radius: 12px;
            background: #ffffff;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            border: 1px solid #f1f5f9;
            transition: all 0.4s var(--ease-out-quint);
        }

        .content-card:hover {
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
            border-color: #e2e8f0;
        }

        main {
            padding: 1.5rem !important;
            flex: 1;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 1.5rem;
            color: #64748b;
            font-size: 0.9rem;
            background: #ffffff;
            border-top: 1px solid #e2e8f0;
            margin-top: auto;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            height: 1px;
            background: linear-gradient(90deg,
                    transparent,
                    var(--sidebar-active),
                    transparent);
        }

        /* Remove Bootstrap Caret */
        .sidebar .dropdown-toggle::after,
        .sidebar-dropdown-toggle::after {
            display: none !important;
        }

        /* Loading Animation */
        @keyframes shimmer {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        .shimmer {
            position: relative;
            overflow: hidden;
        }

        .shimmer::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg,
                    transparent,
                    rgba(59, 130, 246, 0.1),
                    transparent);
            animation: shimmer 2s infinite;
        }


        /* =====================================
   GLOBAL MOBILE WIDTH FIX
===================================== */
        /* ==========================
   RESPONSIVE WIDTH FINAL FIX
   — FULL WIDTH FOR MOBILE —
============================ */
        @media (max-width: 768px) {

            /* Kurangi padding global halaman */
            main {
                padding: 0.8rem !important;
            }

            /* Pastikan semua grid/card melebar penuh */
            .content-card,
            .dashboard-header-card,
            .dashboard-absensi-section,
            .pro-card,
            .quick-stats-grid,
            .wallet-card,
            .stat-card,
            .dashboard-grid>div,
            .main-content-column,
            .sidebar-column {
                margin-left: 0 !important;
                margin-right: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }

            /* FIX PALING PENTING — hilangkan batas lebar container */
            .container,
            .container-fluid {
                padding-left: 0 !important;
                padding-right: 0 !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }

            /* Hilangkan overflow tersembunyi yang bikin sempit */
            body,
            html,
            main,
            #mainContent {
                width: 100% !important;
                max-width: 100% !important;
                overflow-x: hidden !important;
            }

            /* Card yang biasanya punya max-width bawaan */
            .dashboard-header-card,
            .dashboard-absensi-section,
            .quick-stats-grid {
                max-width: 100% !important;
            }

            /* Fix spacing antar elemen */
            .row>[class*='col'] {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            /* ===== FIX UTAMA TAMBAHAN =====
       Atasi pembungkus yang masih membatasi lebar (430px / max-width) */
            .main-content {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            /* Wrapper dashboard / content */
            .dashboard-container,
            .dashboard-wrapper,
            .dashboard-content,
            .dashboard-main,
            .dashboard-section {
                width: 100% !important;
                max-width: 100% !important;
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            /* Semua elemen direct child dari mainContent */
            #mainContent>div {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
            }

            /* Bar putih tempat card-card melekat */
            .content-wrapper,
            .page-wrapper,
            .content-area {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
        }
    </style>
</head>

<body>
    <!-- Mobile Backdrop -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Light Mode Sidebar -->
    <nav class="sidebar" id="sidebar" aria-label="Main sidebar">
        <div class="logo">
            <img src="<?= smart_url('assets/img/logo.png') ?>" alt="Logo Sekolah" class="shimmer">
            <div class="logo-text">
                <div class="logo-main-text">Sistem Informasi Sekolah</div>
                <div class="logo-sub-text">Sistem Informasi Sekolah</div>
            </div>
        </div>

        <?php $role = session()->get('role'); ?>

        <ul class="menu-list">
            <!-- Dashboard Links -->
            <?php if ($role === 'admin'): ?>
                <li>
                    <a href="<?= smart_url('dashboard') ?>"
                        class="menu-link <?= $segment === 'dashboard' ? 'active' : '' ?>"
                        data-tooltip="Dashboard">
                        <i class="fa fa-home"></i> <span>Dashboard</span>
                    </a>
                </li>
            <?php elseif ($role === 'guru'): ?>
                <li>
                    <a href="<?= smart_url('guru/dashboard') ?>"
                        class="menu-link <?= $segment === 'dashboard' ? 'active' : '' ?>"
                        data-tooltip="Dashboard">
                        <i class="fa fa-home"></i> <span>Dashboard</span>
                    </a>
                </li>
            <?php elseif ($role === 'siswa'): ?>
                <li>
                    <a href="<?= smart_url('siswa/dashboard') ?>"
                        class="menu-link <?= $segment === 'dashboard' ? 'active' : '' ?>"
                        data-tooltip="Dashboard">
                        <i class="fa fa-home"></i> <span>Dashboard</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- ADMIN MENU -->
            <?php if ($role === 'admin'): ?>

                <?php
                // ========== SAFE URI SEGMENTS (anti error) ==========
                $uri       = service('uri');
                $segments  = $uri->getSegments();     // array aman
                $segment   = $segments[0] ?? '';      // segmen-1
                $segment2  = $segments[1] ?? '';      // segmen-2
                $segment3  = $segments[2] ?? '';      // segmen-3
                $currentUri = $uri->getPath();

                $isAbsensiActive = strpos($currentUri, 'absensi') !== false;
                $isFinanceOpen   = in_array($segment, ['tabungan', 'laporan']);
                $masterDataSegments = ['siswa', 'guru', 'kelas', 'mapel', 'jurusan'];
                $isMasterDataOpen = in_array($segment, $masterDataSegments);

                // Laporan Absensi aktif jika URL mengandung "absensi/laporan"
                $isLaporanAbsensi = ($segment === 'absensi' && $segment2 === 'laporan');

                // Log
                $isLogActive = in_array($segment, ['activity', 'admin']) && ($segment2 === 'error-log');
                ?>

                <!-- ============================================
                MASTER DATA UTAMA
================================================= -->
                <li>
                    <a href="#"
                        class="dropdown-toggle <?= $isMasterDataOpen ? 'dropdown-open' : '' ?>"
                        data-tooltip="Master Data Utama">
                        <i class="fa fa-school"></i> <span>Master Data Utama</span>
                        <span class="chevron">▾</span>
                    </a>

                    <ul class="submenu <?= $isMasterDataOpen ? 'show' : '' ?>">
                        <li><a href="<?= smart_url('siswa') ?>" class="<?= $segment === 'siswa' ? 'active' : '' ?>">Data Siswa</a></li>
                        <li><a href="<?= smart_url('admin/guru') ?>" class="<?= $segment === 'guru' ? 'active' : '' ?>">Data Guru</a></li>
                        <li><a href="<?= smart_url('kelas') ?>" class="<?= $segment === 'kelas' ? 'active' : '' ?>">Data Kelas</a></li>
                        <li><a href="<?= smart_url('mapel') ?>" class="<?= $segment === 'mapel' ? 'active' : '' ?>">Data Mapel</a></li>
                        <li><a href="<?= smart_url('jurusan') ?>" class="<?= $segment === 'jurusan' ? 'active' : '' ?>">Data Jurusan</a></li>
                    </ul>
                </li>

                <!-- ============================================
                 EKSTRAKURIKULER
================================================= -->
                <li>
                    <a href="<?= smart_url('ekskul') ?>"
                        class="menu-link <?= $segment === 'ekskul' ? 'active' : '' ?>"
                        data-tooltip="Ekstrakurikuler">
                        <i class="fa fa-futbol"></i> <span>Ekstrakurikuler</span>
                    </a>
                </li>

                <!-- ============================================
                     KEUANGAN
================================================= -->
                <li>
                    <a href="#"
                        class="dropdown-toggle <?= $isFinanceOpen ? 'dropdown-open' : '' ?>"
                        data-tooltip="Keuangan">
                        <i class="fa fa-wallet"></i> <span>Keuangan</span>
                        <span class="chevron">▾</span>
                    </a>

                    <ul class="submenu <?= $isFinanceOpen ? 'show' : '' ?>">
                        <li><a href="<?= smart_url('tabungan') ?>" class="<?= $segment === 'tabungan' ? 'active' : '' ?>">Tabungan</a></li>
                        <li><a href="<?= smart_url('laporan') ?>" class="<?= $segment === 'laporan' ? 'active' : '' ?>">Laporan Tabungan</a></li>
                    </ul>
                </li>

                <!-- ============================================
                    ABSENSI
================================================= -->
                <li>
                    <a href="#"
                        class="dropdown-toggle <?= $isAbsensiActive ? 'dropdown-open' : '' ?>"
                        data-tooltip="Absensi">
                        <i class="fa fa-qrcode"></i> <span>Absensi</span>
                        <span class="chevron">▾</span>
                    </a>

                    <ul class="submenu <?= $isAbsensiActive ? 'show' : '' ?>">
                        <li><a href="<?= smart_url('absensi/dashboard') ?>"
                                class="<?= $segment2 === 'dashboard' ? 'active' : '' ?>">Data Utama</a></li>

                        <li><a href="<?= smart_url('absensi/generate') ?>"
                                class="<?= $segment2 === 'generate' ? 'active' : '' ?>">Generate QR</a></li>

                        <li><a href="<?= smart_url('absensi/scan-camera') ?>"
                                class="<?= $segment2 === 'scan-camera' ? 'active' : '' ?>">Scan QR</a></li>

                        <li><a href="<?= smart_url('absensi/riwayat') ?>"
                                class="<?= $segment2 === 'riwayat' ? 'active' : '' ?>">Riwayat Absensi</a></li>

                        <li><a href="<?= smart_url('absensi/izin/admin') ?>"
                                class="<?= $segment2 === 'izin' ? 'active' : '' ?>">Kelola Izin</a></li>

                        <!-- **FITUR BARU — LAPORAN ABSENSI** -->
                        <li><a href="<?= smart_url('absensi/laporan') ?>"
                                class="<?= $isLaporanAbsensi ? 'active' : '' ?>">Laporan Absensi</a></li>
                    </ul>
                </li>

                <!-- ============================================
               MANAJEMEN USER
================================================= -->
                <li>
                    <a href="<?= smart_url('users') ?>"
                        class="menu-link <?= $segment === 'users' ? 'active' : '' ?>"
                        data-tooltip="Manajemen User">
                        <i class="fa fa-user-shield"></i> <span>Manajemen User</span>
                    </a>
                </li>

                <!-- ============================================
             PENGATURAN JADWAL
================================================= -->
                <li>
                    <a href="<?= smart_url('admin/pengaturan') ?>"
                        class="menu-link <?= $segment === 'pengaturan' ? 'active' : '' ?>"
                        data-tooltip="Pengaturan Jadwal">
                        <i class="fa fa-cog"></i> <span>Pengaturan Jadwal</span>
                    </a>
                </li>

                <!-- ============================================
                 LOG & DEBUG
================================================= -->
                <li>
                    <a href="#"
                        class="dropdown-toggle <?= $isLogActive ? 'dropdown-open' : '' ?>"
                        data-tooltip="Log & Debug">
                        <i class="fa fa-bug"></i> <span>Log & Debug</span>
                        <span class="chevron">▾</span>
                    </a>

                    <ul class="submenu <?= $isLogActive ? 'show' : '' ?>">
                        <li>
                            <a href="<?= smart_url('activity') ?>"
                                class="<?= $segment === 'activity' ? 'active' : '' ?>">
                                Log Aktivitas
                            </a>
                        </li>

                        <li>
                            <a href="<?= smart_url('admin/error-log') ?>"
                                class="<?= $segment2 === 'error-log' ? 'active' : '' ?>">
                                Log Error
                            </a>
                        </li>
                    </ul>
                </li>

            <?php endif; ?>


            <!-- GURU MENU -->
            <?php if ($role === 'guru'): ?>
                <li>
                    <a href="<?= smart_url('tabungan') ?>"
                        class="menu-link <?= $segment === 'tabungan' ? 'active' : '' ?>"
                        data-tooltip="Tabungan">
                        <i class="fa-solid fa-piggy-bank"></i> <span>Tabungan</span>
                    </a>
                </li>

                <li>
                    <a href="#"
                        class="dropdown-toggle <?= in_array($segment, ['scan-camera', 'riwayat', 'izin']) ? 'dropdown-open' : '' ?>"
                        data-tooltip="Absensi">
                        <i class="fa fa-qrcode"></i> <span>Absensi</span>
                        <span class="chevron">▾</span>
                    </a>
                    <ul class="submenu <?= in_array($segment, ['scan-camera', 'riwayat', 'izin']) ? 'show' : '' ?>">
                        <li><a href="<?= smart_url('absensi/scan-camera') ?>" class="<?= $segment === 'scan-camera' ? 'active' : '' ?>">Scan QR</a></li>
                        <li><a href="<?= smart_url('absensi/riwayat') ?>" class="<?= $segment === 'riwayat' ? 'active' : '' ?>">Riwayat Absensi</a></li>
                        <li><a href="<?= smart_url('absensi/izin') ?>" class="<?= $segment === 'izin' ? 'active' : '' ?>">Daftar Izin Siswa</a></li>
                    </ul>
                </li>

                <li>
                    <a href="<?= smart_url('guru/kelas') ?>"
                        class="menu-link <?= $segment === 'kelas' ? 'active' : '' ?>"
                        data-tooltip="Kelas Saya">
                        <i class="fa fa-users"></i> <span>Kelas Saya</span>
                    </a>
                </li>

                <li>
                    <a href="<?= smart_url('guru/siswa') ?>"
                        class="menu-link <?= $segment === 'siswa' ? 'active' : '' ?>"
                        data-tooltip="Siswa Bimbingan">
                        <i class="fa fa-user-graduate"></i> <span>Siswa Bimbingan</span>
                    </a>
                </li>

                <li>
                    <a href="<?= smart_url('guru/tugas') ?>"
                        class="menu-link <?= $segment === 'tugas' ? 'active' : '' ?>"
                        data-tooltip="Manajemen Tugas">
                        <i class="fa-solid fa-clipboard-list"></i> <span>Manajemen Tugas</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- SISWA MENU -->
            <?php if ($role === 'siswa'): ?>
                <li>
                    <a href="<?= smart_url('siswa/profil') ?>"
                        class="menu-link <?= $segment === 'profil' ? 'active' : '' ?>"
                        data-tooltip="Profil">
                        <i class="fa-solid fa-user-graduate"></i> <span>Profil</span>
                    </a>
                </li>

                <li>
                    <a href="#"
                        class="dropdown-toggle <?= in_array($segment, ['scan-camera', 'riwayat', 'izin']) ? 'dropdown-open' : '' ?>"
                        data-tooltip="Absensi">
                        <i class="fa fa-qrcode"></i> <span>Absensi</span>
                        <span class="chevron">▾</span>
                    </a>
                    <ul class="submenu <?= in_array($segment, ['scan-camera', 'riwayat', 'izin']) ? 'show' : '' ?>">
                        <li><a href="<?= smart_url('absensi/scan-camera') ?>" class="<?= $segment === 'scan-camera' ? 'active' : '' ?>">Scan QR</a></li>
                        <li><a href="<?= smart_url('absensi/riwayat') ?>" class="<?= $segment === 'riwayat' ? 'active' : '' ?>">Riwayat Absensi</a></li>
                        <li><a href="<?= smart_url('absensi/izin/form') ?>" class="<?= $segment === 'izin' ? 'active' : '' ?>">Ajukan Izin</a></li>
                    </ul>
                </li>
            <?php endif; ?>

            <!-- LOGOUT -->
            <li>
                <a href="<?= smart_url('logout') ?>"
                    class="menu-link text-danger"
                    data-tooltip="Keluar">
                    <i class="fa fa-sign-out-alt"></i> <span>Sign Out</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <nav class="topbar">
            <button class="toggle-btn" id="toggleSidebar">
                <i class="fa fa-bars"></i>
            </button>

            <?php
            $role = session()->get('role');
            $fotoFile = session()->get('foto');
            $fotoUrl = smart_url('assets/img/default-user.png');

            if (!empty($fotoFile)) {
                if ($role === 'admin' && file_exists(FCPATH . 'uploads/admin/' . $fotoFile)) {
                    $fotoUrl = smart_url('uploads/admin/' . $fotoFile);
                } elseif ($role === 'guru' && file_exists(FCPATH . 'uploads/guru/' . $fotoFile)) {
                    $fotoUrl = smart_url('uploads/guru/' . $fotoFile);
                } elseif ($role === 'siswa' && file_exists(FCPATH . 'uploads/siswa/' . $fotoFile)) {
                    $fotoUrl = smart_url('uploads/siswa/' . $fotoFile);
                } elseif (file_exists(FCPATH . 'uploads/' . $fotoFile)) {
                    $fotoUrl = smart_url('uploads/' . $fotoFile);
                }
            }

            $namaUser = session()->get('nama') ?? session()->get('username') ?? 'Pengguna';
            $roleUser = ucfirst($role ?? 'User');
            ?>

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="<?= $fotoUrl ?>" alt="User" class="avatar-sm me-2">
                    <div class="d-none d-sm-block text-start">
                        <span class="fw-semibold d-block"><?= esc($namaUser) ?></span>
                        <small class="text-muted"><?= esc($roleUser) ?></small>
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                    <?php if ($role === 'admin'): ?>
                        <li><a class="dropdown-item" href="<?= smart_url('admin/profil') ?>"><i class="fa-solid fa-user-gear text-primary me-2"></i> Profil</a></li>
                    <?php elseif ($role === 'guru'): ?>
                        <li><a class="dropdown-item" href="<?= smart_url('guru/profil') ?>"><i class="fa-solid fa-user text-primary me-2"></i> Profil</a></li>
                        <li><a class="dropdown-item" href="<?= smart_url('guru/ganti-password') ?>"><i class="fa-solid fa-lock text-warning me-2"></i> Ganti Password</a></li>
                    <?php elseif ($role === 'siswa'): ?>
                        <li><a class="dropdown-item" href="<?= smart_url('siswa/profil') ?>"><i class="fa-solid fa-user text-primary me-2"></i> Profil</a></li>
                        <li><a class="dropdown-item" href="<?= smart_url('siswa/ganti-password') ?>"><i class="fa-solid fa-lock text-warning me-2"></i> Ganti Password</a></li>
                    <?php endif; ?>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item text-danger" href="<?= smart_url('logout') ?>"><i class="fa-solid fa-right-from-bracket me-2"></i> Sign Out</a></li>
                </ul>
            </div>
        </nav>

        <!-- Main Content Area -->
        <main>
            <?= $this->renderSection('content') ?>
        </main>

        <footer>
            © <?= date('Y') ?> Zulfiqri,S.Kom — Sistem Informasi Sekolah
        </footer>
    </div>

    <!-- ======== JS GLOBAL ======== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <!-- Enhanced Sidebar Script -->
    <script>
        (function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleBtn = document.getElementById('toggleSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');

            // Enhanced initialization
            function initSidebar() {
                const savedCollapsed = localStorage.getItem('sidebarCollapsed') === '1';
                const isMobile = window.innerWidth <= 768;

                if (!isMobile && savedCollapsed) {
                    sidebar.classList.add('collapsed');
                    mainContent.classList.add('expanded');
                }

                // Enhanced active submenu detection
                document.querySelectorAll('.submenu').forEach(sub => {
                    if (sub.querySelector('a.active')) {
                        const parentToggle = sub.previousElementSibling;
                        if (parentToggle) {
                            parentToggle.classList.add('dropdown-open');
                            sub.classList.add('show');
                            // Use setTimeout to ensure CSS transition works
                            setTimeout(() => {
                                sub.style.maxHeight = sub.scrollHeight + 'px';
                            }, 50);

                            // Animate submenu items
                            const items = sub.querySelectorAll('li');
                            items.forEach((item, index) => {
                                setTimeout(() => {
                                    item.style.opacity = '1';
                                    item.style.transform = 'translateX(0)';
                                }, 100 + (index * 50));
                            });
                        }
                    }
                });
            }

            // Toggle function
            function toggleSidebar() {
                const isMobile = window.innerWidth <= 768;

                if (isMobile) {
                    const isShown = sidebar.classList.toggle('show');
                    backdrop.classList.toggle('show');
                    document.body.style.overflow = isShown ? 'hidden' : '';
                } else {
                    const isCollapsed = sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                    localStorage.setItem('sidebarCollapsed', isCollapsed ? '1' : '0');

                    // Enhanced collapse behavior
                    if (isCollapsed) {
                        document.querySelectorAll('.submenu.show').forEach(sub => {
                            const parentToggle = sub.previousElementSibling;
                            if (parentToggle) parentToggle.classList.remove('dropdown-open');
                            sub.style.maxHeight = '0px';
                            setTimeout(() => sub.classList.remove('show'), 400);
                        });
                    }
                }
            }

            // Dropdown functionality
            function initDropdowns() {
                document.querySelectorAll(".sidebar .dropdown-toggle").forEach(menu => {
                    menu.addEventListener("click", function(e) {
                        e.preventDefault();
                        e.stopPropagation();

                        // Don't process dropdowns in collapsed mode on desktop
                        if (window.innerWidth > 768 && sidebar.classList.contains('collapsed')) {
                            return;
                        }

                        const submenu = this.nextElementSibling;
                        if (!submenu || !submenu.classList.contains('submenu')) return;

                        const isOpen = submenu.classList.contains("show");

                        // Close other submenus at the same level with animation
                        const parentLi = this.parentElement;
                        const siblingMenus = parentLi.parentElement.querySelectorAll('.dropdown-toggle');
                        siblingMenus.forEach(sibling => {
                            if (sibling !== this) {
                                const siblingSubmenu = sibling.nextElementSibling;
                                if (siblingSubmenu && siblingSubmenu.classList.contains('submenu') && siblingSubmenu.classList.contains('show')) {
                                    closeSubmenu(sibling, siblingSubmenu);
                                }
                            }
                        });

                        if (isOpen) {
                            closeSubmenu(this, submenu);
                        } else {
                            openSubmenu(this, submenu);
                        }
                    });
                });

                function openSubmenu(toggle, submenu) {
                    toggle.classList.add('dropdown-open');
                    submenu.classList.add('show');
                    submenu.style.maxHeight = submenu.scrollHeight + 'px';

                    // Animate submenu items
                    const items = submenu.querySelectorAll('li');
                    items.forEach((item, index) => {
                        item.style.opacity = '0';
                        item.style.transform = 'translateX(-10px)';
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'translateX(0)';
                        }, 100 + (index * 50));
                    });
                }

                function closeSubmenu(toggle, submenu) {
                    toggle.classList.remove('dropdown-open');
                    submenu.style.maxHeight = submenu.scrollHeight + 'px';
                    // Force reflow
                    submenu.offsetHeight;
                    submenu.style.maxHeight = '0px';

                    // Reset submenu items animation
                    const items = submenu.querySelectorAll('li');
                    items.forEach(item => {
                        item.style.opacity = '0';
                        item.style.transform = 'translateX(-10px)';
                    });

                    submenu.addEventListener('transitionend', function handler() {
                        submenu.classList.remove('show');
                        submenu.removeEventListener('transitionend', handler);
                    }, {
                        once: true
                    });
                }
            }

            // Backdrop functionality
            if (backdrop) {
                backdrop.addEventListener('click', () => {
                    sidebar.classList.remove('show');
                    backdrop.classList.remove('show');
                    document.body.style.overflow = '';
                });
            }

            // Enhanced resize handler
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    backdrop.classList.remove('show');
                    document.body.style.overflow = '';

                    // Ensure proper state on desktop
                    const savedCollapsed = localStorage.getItem('sidebarCollapsed') === '1';
                    if (savedCollapsed && !sidebar.classList.contains('collapsed')) {
                        sidebar.classList.add('collapsed');
                        mainContent.classList.add('expanded');
                    } else if (!savedCollapsed && sidebar.classList.contains('collapsed')) {
                        sidebar.classList.remove('collapsed');
                        mainContent.classList.remove('expanded');
                    }
                } else {
                    // On mobile, ensure sidebar is hidden initially
                    if (!sidebar.classList.contains('show')) {
                        sidebar.classList.remove('collapsed');
                        mainContent.classList.remove('expanded');
                    }
                }
            });

            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
                        sidebar.classList.remove('show');
                        backdrop.classList.remove('show');
                        document.body.style.overflow = '';
                    }
                }
            });

            // Initialize everything
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(() => {
                    initSidebar();
                    initDropdowns();

                    if (toggleBtn) {
                        toggleBtn.addEventListener('click', toggleSidebar);
                    }
                }, 100);
            });

        })();
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                [$('meta[name="csrf-header"]').attr('content')]: $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>


    <?= $this->renderSection('scripts') ?>

</body>

</html>