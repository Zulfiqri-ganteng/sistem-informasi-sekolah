<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ==================== AUTH ====================
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::process');
$routes->get('logout', 'Auth::logout');

$routes->get('register-siswa', 'Auth::registerSiswa');
$routes->post('register-siswa', 'Auth::registerSubmit');
// jika menggunakan route global (di luar group)
// $routes->get('register-siswa', 'Auth::registerSiswa');
$routes->post('register-siswa/submit', 'Auth::registerSubmit');

// 🔹 Lupa Password
$routes->get('forgot-password', 'Auth::forgotPassword');
$routes->post('forgot-password', 'Auth::sendResetLink');
$routes->get('reset-password/(:any)', 'Auth::resetPassword/$1');
$routes->post('reset-password/(:any)', 'Auth::saveNewPassword/$1');

// =====================
// 🏠 DASHBOARD
// =====================
$routes->get('/', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('dashboard/transaksiAjax', 'Dashboard::transaksiAjax');

// untuk AJAX kelas per jurusan & absensi filter
$routes->get('dashboard/kelas/(:segment)', 'Dashboard::getKelasByJurusan/$1');
$routes->get('dashboard/kelas', 'Dashboard::getKelasByJurusan'); // fallback
$routes->get('dashboard/absensiAjax', 'Dashboard::absensiAjax');

// =====================
// 🎓 SISWA
// =====================
// ====================
// 👨‍🎓 DATA SISWA (ADMIN)
// ====================
$routes->group('siswa', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Siswa::index');
    $routes->get('list', 'Siswa::list');
    $routes->post('save', 'Siswa::save');
    $routes->get('get/(:num)', 'Siswa::get/$1');
    $routes->get('delete/(:num)', 'Siswa::delete/$1');
    $routes->get('options', 'Siswa::options');
    $routes->get('dropdown', 'Siswa::dropdown');
    $routes->get('search', 'Siswa::search');
});

// AREA SISWA
// ===================
$routes->group('siswa', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'SiswaDashboard::dashboard');
    $routes->get('transaksi', 'SiswaDashboard::transaksi');
    $routes->get('profil', 'SiswaDashboard::profil');
    $routes->post('update-profil', 'SiswaDashboard::updateProfil');
    // $routes->get('ganti-password', 'SiswaDashboard::gantiPassword');
    $routes->post('ganti-password', 'SiswaDashboard::gantiPasswordPost');
    $routes->get('tabungan', 'Siswa\Tabungan::index'); // 👈 ini yang hilang
});


// =====================
// ⚙️ ADMIN PROFIL & PASSWORD
// =====================
$routes->group('admin', ['filter' => 'auth'], static function ($routes) {
    $routes->get('profil', 'Admin::profil');
    $routes->post('update-profil', 'Admin::updateProfil');

    // Ganti password admin
    $routes->get('ganti-password', 'Admin::gantiPassword');
    $routes->post('ganti-password', 'Admin::gantiPassword');
});
// =============== ADMIN: CRUD DATA GURU ==================
$routes->group('admin/guru', ['filter' => 'auth'], static function ($routes) {

    $routes->get('/', 'GuruController::index');           // halaman utama CRUD guru
    $routes->get('list', 'GuruController::list');         // ajax datatables
    $routes->get('get/(:num)', 'GuruController::get/$1'); // ambil data edit
    $routes->post('save', 'GuruController::save');        // tambah & edit guru
    $routes->get('delete/(:num)', 'GuruController::delete/$1');
    $routes->get('getMapel', 'GuruController::getMapel');
});
// ================= GURU =====================
$routes->group('guru', ['filter' => 'auth'], static function ($routes) {

    $routes->get('/', 'Guru::index');
    $routes->get('dashboard', 'Guru::index');

    $routes->get('kelas', 'Guru::kelas');
    $routes->get('kelas/(:num)', 'Guru::siswa/$1');

    $routes->get('siswa/(:num)', 'Guru::siswaGet/$1');

    $routes->get('getSiswaKelas', 'Guru::getSiswaKelas');
    $routes->get('transaksi/list', 'Guru::transaksiList');

    // ================= TRANSAKSI =================
    $routes->post('transaksi/create', 'GuruTransaksi::create');
    // PROFIL
    $routes->get('profil', 'Guru::profil');
    $routes->post('profil/update', 'Guru::updateProfil');

    // GANTI PASSWORD
    $routes->get('ganti-password', 'Guru::gantiPassword');
    $routes->post('ganti-password', 'Guru::updatePassword');
    $routes->get('profil', 'Guru\ProfilController::index');
    $routes->get('profil', 'Guru::profil');
    $routes->post('update-profil', 'Guru::updateProfil');

    // $routes->post('update-profil', 'Guru\ProfilController::updateProfil');
    $routes->post('guru/update-profil', 'Guru::updateProfil');
    $routes->get('chart-data', 'Guru::chartData');
});

// =====================
// 📚 MATA PELAJARAN
// =====================
$routes->group('mapel', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Mapel::index');
    $routes->get('list', 'Mapel::list');
    $routes->get('get/(:num)', 'Mapel::get/$1');
    $routes->post('save', 'Mapel::save');
    $routes->get('delete/(:num)', 'Mapel::delete/$1');
});


// =====================
// 🏫 KELAS
// =====================
$routes->group('kelas', ['filter' => 'auth'], static function ($routes) {

    $routes->get('/', 'Kelas::index');
    $routes->get('list', 'Kelas::list');
    $routes->post('save', 'Kelas::save');
    $routes->get('delete/(:num)', 'Kelas::delete/$1');
    $routes->get('get/(:num)', 'Kelas::get/$1');
    $routes->get('getGuruDropdown', 'Kelas::getGuruDropdown');

    // endpoint siswa per kelas
    $routes->get('siswa/(:num)', 'Kelas::siswa/$1');
});

// ====================
// 🎓 JURUSAN
// ====================
$routes->group('jurusan', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Jurusan::index');
    $routes->get('list', 'Jurusan::list');
    $routes->get('get/(:num)', 'Jurusan::get/$1');       // ✅ route untuk tombol edit (ambil data)
    $routes->post('save', 'Jurusan::save');              // ✅ route untuk tambah/update
    $routes->get('delete/(:num)', 'Jurusan::delete/$1'); // ✅ route untuk hapus
});

// =====================
// 💰 TABUNGAN
// =====================
$routes->group('tabungan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Tabungan::index');
    $routes->get('list', 'Tabungan::list');
    $routes->post('transaction', 'Tabungan::transaction');
    $routes->get('mutasi/(:num)', 'Tabungan::mutasi/$1');
    $routes->get('dashboard', 'Tabungan::dashboard');
    $routes->get('report', 'Tabungan::report');
    $routes->get('reportData', 'Tabungan::reportData');
    $routes->get('exportCsv', 'Tabungan::exportCsv');
});

// =====================
// 📊 LAPORAN
// =====================
// ========================
// LAPORAN TABUNGAN (group + filter)
$routes->group('laporan', ['filter' => 'auth'], static function ($routes) {

    $routes->get('/', 'Laporan::index');
    $routes->get('data', 'Laporan::data');
    $routes->get('detail/(:num)', 'Laporan::detail/$1');

    // EXPORT FIX
    $routes->get('export-excel', 'Laporan::exportExcel');
    $routes->get('export-pdf', 'Laporan::exportPdf');
    $routes->get('export-word', 'Laporan::exportWord');
});

// Manajemen User (admin only)
$routes->group('users', ['filter' => 'role:admin'], function ($routes) {
    $routes->get('/', 'Users::index');
    $routes->get('toggleStatus/(:num)', 'Users::toggleStatus/$1');
    $routes->get('reset/(:num)', 'Users::resetPassword/$1');
});
/** ============================================
 *  ABSENSI — HALAMAN RIWAYAT (semua role)
 *  ============================================ */
$routes->group('absensi', ['filter' => 'auth'], function ($routes) {
    $routes->get('riwayat', 'Absensi\RiwayatController::index');
    $routes->get('riwayatAjax', 'Absensi\RiwayatController::riwayatAjax'); // AJAX WAJIB
});


/** ============================================
 *  ABSENSI --- ADMIN (full access)
 *  ============================================ */
$routes->group('absensi', ['filter' => 'absensiRole:admin'], function ($routes) {

    $routes->get('success', 'Absensi\ScanController::success');

    // SCAN QR
    $routes->get('scan-camera', 'Absensi\ScanController::camera');   // buka kamera
    $routes->get('scan', 'Absensi\ScanController::scan');            // hasil token
    $routes->post('process-scan', 'Absensi\ScanController::processScan');

    // DASHBOARD ABSENSI
    $routes->get('dashboard', 'Absensi\DashboardController::index');

    // GENERATE QR
    $routes->get('generate', 'AbsensiBarcode::generateForm');
    $routes->post('generate', 'AbsensiBarcode::generate');

    $routes->get('qrcode/(:num)', 'AbsensiBarcode::qrcode/$1');
    $routes->get('qrcode-bundle', 'AbsensiBarcode::qrcodeBundle');
    $routes->post('download-bundle', 'AbsensiBarcode::downloadBundle');
    $routes->get('get-list/(:segment)', 'AbsensiBarcode::getList/$1');
});


/** ============================================
 *  ABSENSI --- GURU
 *  ============================================ */
$routes->group('absensi', ['filter' => 'absensiRole:guru'], function ($routes) {
    $routes->get('success', 'Absensi\ScanController::success');

    // SCAN QR
    $routes->get('scan-camera', 'Absensi\ScanController::camera');
    $routes->get('scan', 'Absensi\ScanController::scan');
    $routes->post('process-scan', 'Absensi\ScanController::processScan');
});


/** ============================================
 *  ABSENSI --- SISWA
 *  ============================================ */
$routes->group('absensi', ['filter' => 'absensiRole:siswa'], function ($routes) {

    $routes->get('success', 'Absensi\ScanController::success');

    // SCAN QR
    $routes->get('scan-camera', 'Absensi\ScanController::camera');
    $routes->get('scan', 'Absensi\ScanController::scan');
    $routes->post('process-scan', 'Absensi\ScanController::processScan');
});

// Pastikan filter absensiRole:admin,guru mendukung multiple role (dipisahkan koma)
// Rute umum Absensi (jika ada)
$routes->get('absensi/rekapAjax', 'Absensi\DashboardController::rekapAjax');


/** ============================================
 * IZIN ABSENSI — ADMIN/GURU (Kelola Izin)
 * URL: /absensi/izin/admin
 * ============================================ */
$routes->group('absensi/izin', ['filter' => 'absensiRole:admin,guru'], function ($routes) {
    // Rute untuk menampilkan daftar izin yang perlu di-manage (URL: absensi/izin/admin)
    $routes->get('admin', 'Absensi\IzinController::adminList');

    // Rute untuk aksi approve dan reject yang dipanggil dari halaman adminList
    $routes->post('approve/(:num)', 'Absensi\IzinController::approve/$1');
    $routes->post('reject/(:num)', 'Absensi\IzinController::reject/$1');
});


/** ============================================
 * IZIN ABSENSI — SISWA (Form Pengajuan)
 * URL: /absensi/izin/form, /absensi/izin/submit
 * ============================================ 
 * Dikelompokkan terpisah untuk menghindari konflik filter dengan Admin/Guru.
 */
$routes->group('absensi/izin', ['filter' => 'absensiRole:siswa'], function ($routes) {
    // Rute untuk form pengajuan izin (URL: absensi/izin/form)
    $routes->get('form', 'Absensi\IzinController::form');
    // Rute untuk submit form pengajuan
    $routes->post('submit', 'Absensi\IzinController::submit');
});

// =====================
// ⚙️ DEFAULTS
// =====================
$routes->setDefaultController('Dashboard');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);
