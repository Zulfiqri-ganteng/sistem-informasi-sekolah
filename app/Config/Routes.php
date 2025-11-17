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


// =====================
// ⚙️ DEFAULTS
// =====================
// Hapus default Auth agar tidak loop redirect
$routes->setDefaultController('Dashboard');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);
