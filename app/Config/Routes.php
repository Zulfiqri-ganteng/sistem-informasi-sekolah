<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =======================
//  CRONJOB ROUTES
// =======================
$routes->get('cron/auto-pulang-ekskul', 'Absensi\ScanController::cronAutoPulang');

// =======================
// ACTIVITY LOGS (AMAN)
// =======================
$routes->get('activity', 'ActivityLogs::index', ['filter' => 'auth']);
$routes->post('activity/ajaxList', 'ActivityLogs::ajaxList', ['filter' => 'auth']);
$routes->get('activity/view/(:num)', 'ActivityLogs::view/$1', ['filter' => 'auth']);
$routes->get('activity/export', 'ActivityLogs::exportCsv', ['filter' => 'auth']);

// =======================
// ADMIN GROUP
// =======================
$routes->group('admin', ['filter' => ['auth', 'activityLogger']], static function ($routes) {
    $routes->get('error-log', 'Admin\ErrorLogController::index');
    $routes->get('error-log/(:num)', 'Admin\ErrorLogController::detail/$1');
    $routes->get('profil', 'Admin::profil');
    $routes->post('update-profil', 'Admin::updateProfil');

    // Ganti Password Admin
    $routes->get('ganti-password', 'Admin::gantiPassword');
    $routes->post('ganti-password', 'Admin::gantiPassword');

    // Jadwal
    $routes->get('jadwal', 'Admin\JadwalController::index');
    $routes->post('jadwal/update', 'Admin\JadwalController::updateJadwal');
    $routes->post('jadwal/add-libur', 'Admin\JadwalController::addHariLibur');
    $routes->get('jadwal/delete-libur/(:num)', 'Admin\JadwalController::deleteHariLibur/$1');

    // Absensi Admin
    $routes->get('dashboard', 'Absensi::dashboard');
    $routes->get('generate', 'Absensi::generate');
    $routes->get('scan-camera', 'Absensi::scanCamera');
    $routes->get('riwayat', 'Absensi::riwayat');
    $routes->get('izin/admin', 'Absensi::kelolaIzinAdmin');

    // Pengaturan Jadwal
    $routes->get('pengaturan', 'Admin\JadwalController::index');
    $routes->post('pengaturan/update-jadwal', 'Admin\JadwalController::updateJadwal');
    $routes->post('pengaturan/tambah-libur', 'Admin\JadwalController::addHariLibur');
    $routes->delete('pengaturan/hapus-libur/(:num)', 'Admin\JadwalController::deleteHariLibur/$1');
});

// ==================== AUTH ====================
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::process');
$routes->get('logout', 'Auth::logout');

$routes->get('register-siswa', 'Auth::registerSiswa');
$routes->post('register-siswa', 'Auth::registerSubmit');
$routes->post('register-siswa/submit', 'Auth::registerSubmit');

// Lupa Password
$routes->get('forgot-password', 'Auth::forgotPassword');
$routes->post('forgot-password', 'Auth::sendResetLink');
$routes->get('reset-password/(:any)', 'Auth::resetPassword/$1');
$routes->post('reset-password/(:any)', 'Auth::saveNewPassword/$1');

// =====================
// DASHBOARD
// =====================
$routes->get('/', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('dashboard/transaksiAjax', 'Dashboard::transaksiAjax');
$routes->get('dashboard/kelas/(:segment)', 'Dashboard::getKelasByJurusan/$1');
$routes->get('dashboard/kelas', 'Dashboard::getKelasByJurusan');
$routes->get('dashboard/absensiAjax', 'Dashboard::absensiAjax');

// ===================================================
// 🎓 SISWA — (CRUD + AREA SISWA + IMPORT WIZARD)
// ===================================================
$routes->group('siswa', ['filter' => 'auth'], static function ($routes) {

    // === CRUD DATA SISWA (ADMIN) ===
    $routes->get('/', 'Siswa::index');
    $routes->get('list', 'Siswa::list');
    $routes->post('save', 'Siswa::save');
    $routes->get('get/(:num)', 'Siswa::get/$1');
    $routes->get('delete/(:num)', 'Siswa::delete/$1');
    $routes->get('options', 'Siswa::options');
    $routes->get('dropdown', 'Siswa::dropdown');
    $routes->get('search', 'Siswa::search');

    // === AREA SISWA ===
    $routes->get('dashboard', 'SiswaDashboard::dashboard');
    $routes->get('transaksi', 'SiswaDashboard::transaksi');
    $routes->get('profil', 'SiswaDashboard::profil');
    $routes->post('update-profil', 'SiswaDashboard::updateProfil');
    $routes->post('ganti-password', 'SiswaDashboard::gantiPasswordPost');
    $routes->get('tabungan', 'Siswa\Tabungan::index');

    // === IMPORT WIZARD ===
    $routes->get('import', 'Import\SiswaImport::index');

    // Template (DUA ALIAS)
    $routes->get('template', 'Import\SiswaImport::downloadTemplate');
    $routes->get('import/template', 'Import\SiswaImport::downloadTemplate');

    // Preview (DUA ALIAS)
    $routes->post('import/preview', 'Import\SiswaImport::preview');
    $routes->post('importPreview', 'Import\SiswaImport::preview');

    // Finalize (DUA ALIAS)
    $routes->post('import/finalize', 'Import\SiswaImport::finalize');
    $routes->post('importSave', 'Import\SiswaImport::finalize');
});

// ===================================================
// GURU CRUD
// ===================================================
$routes->group('admin/guru', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'GuruController::index');
    $routes->get('list', 'GuruController::list');
    $routes->get('get/(:num)', 'GuruController::get/$1');
    $routes->post('save', 'GuruController::save');
    $routes->get('delete/(:num)', 'GuruController::delete/$1');
    $routes->get('getMapel', 'GuruController::getMapel');
});

// =============== GURU AREA ===============
$routes->group('guru', ['filter' => 'auth'], static function ($routes) {

    $routes->get('/', 'Guru::index');
    $routes->get('dashboard', 'Guru::index');

    $routes->get('kelas', 'Guru::kelas');
    $routes->get('kelas/(:num)', 'Guru::siswa/$1');

    $routes->get('siswa/(:num)', 'Guru::siswaGet/$1');
    $routes->get('getSiswaKelas', 'Guru::getSiswaKelas');
    $routes->get('transaksi/list', 'Guru::transaksiList');

    $routes->post('transaksi/create', 'GuruTransaksi::create');

    $routes->get('profil', 'Guru::profil');
    $routes->post('profil/update', 'Guru::updateProfil');
    $routes->get('ganti-password', 'Guru::gantiPassword');
    $routes->post('ganti-password', 'Guru::updatePassword');
    $routes->post('guru/update-profil', 'Guru::updateProfil');

    $routes->get('chart-data', 'Guru::chartData');
});

// =====================
// MAPEL
// =====================
$routes->group('mapel', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Mapel::index');
    $routes->get('list', 'Mapel::list');
    $routes->get('get/(:num)', 'Mapel::get/$1');
    $routes->post('save', 'Mapel::save');
    $routes->get('delete/(:num)', 'Mapel::delete/$1');
});

// =====================
// KELAS
// =====================
$routes->group('kelas', ['filter' => 'auth'], static function ($routes) {

    $routes->get('/', 'Kelas::index');
    $routes->get('list', 'Kelas::list');
    $routes->post('save', 'Kelas::save');
    $routes->get('delete/(:num)', 'Kelas::delete/$1');
    $routes->get('get/(:num)', 'Kelas::get/$1');
    $routes->get('getGuruDropdown', 'Kelas::getGuruDropdown');

    $routes->get('siswa/(:num)', 'Kelas::siswa/$1');
});

// =====================
// JURUSAN
// =====================
$routes->group('jurusan', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Jurusan::index');
    $routes->get('list', 'Jurusan::list');
    $routes->get('get/(:num)', 'Jurusan::get/$1');
    $routes->post('save', 'Jurusan::save');
    $routes->get('delete/(:num)', 'Jurusan::delete/$1');
});

// =====================
// TABUNGAN
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
// LAPORAN TABUNGAN
// =====================
$routes->group('laporan', ['filter' => 'auth'], static function ($routes) {

    $routes->get('/', 'Laporan::index');
    $routes->get('data', 'Laporan::data');
    $routes->get('detail/(:num)', 'Laporan::detail/$1');

    $routes->get('export-excel', 'Laporan::exportExcel');
    $routes->get('export-pdf', 'Laporan::exportPdf');
    $routes->get('export-word', 'Laporan::exportWord');
});

// =====================
// LAPORAN ABSENSI
// =====================
$routes->group('absensi/laporan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Absensi\LaporanController::index');
    $routes->post('hasil', 'Absensi\LaporanController::hasil');

    $routes->get('ekskulBulanan', 'Absensi\LaporanController::ekskulBulanan');
    $routes->get('ekskulBulananPdf', 'Absensi\LaporanController::ekskulBulananPdf');

    $routes->get('export-pdf', 'Absensi\LaporanController::exportPdf');
    $routes->get('export-word', 'Absensi\LaporanController::exportWord');
    $routes->get('export-excel', 'Absensi\LaporanController::exportExcel');
});

// =====================
// USER MANAGEMENT
// =====================
$routes->group('users', ['filter' => 'role:admin'], function ($routes) {
    $routes->get('/', 'Users::index');
    $routes->get('toggleStatus/(:num)', 'Users::toggleStatus/$1');
    $routes->get('reset/(:num)', 'Users::resetPassword/$1');
});

// =====================
// ABSENSI RIWAYAT (ALL ROLES)
// =====================
$routes->group('absensi', ['filter' => 'auth'], function ($routes) {
    $routes->get('riwayat', 'Absensi\RiwayatController::index');
    $routes->get('riwayatAjax', 'Absensi\RiwayatController::riwayatAjax');
});

// =====================
// ABSENSI ADMIN
// =====================
$routes->group('absensi', ['filter' => 'absensiRole:admin'], function ($routes) {

    $routes->get('success', 'Absensi\ScanController::success');
    $routes->get('scan-camera', 'Absensi\ScanController::camera');
    $routes->get('scan', 'Absensi\ScanController::scan');
    $routes->post('process-scan', 'Absensi\ScanController::processScan');

    $routes->get('dashboard', 'Absensi\DashboardController::index');

    $routes->get('generate', 'AbsensiBarcode::generateForm');
    $routes->post('generate', 'AbsensiBarcode::generate');
    $routes->get('qrcode/(:num)', 'AbsensiBarcode::qrcode/$1');
    $routes->get('qrcode-bundle', 'AbsensiBarcode::qrcodeBundle');
    $routes->post('download-bundle', 'AbsensiBarcode::downloadBundle');
    $routes->get('get-list/(:segment)', 'AbsensiBarcode::getList/$1');
});

// =====================
// ABSENSI GURU
// =====================
$routes->group('absensi', ['filter' => 'absensiRole:guru'], function ($routes) {
    $routes->get('success', 'Absensi\ScanController::success');
    $routes->get('scan-camera', 'Absensi\ScanController::camera');
    $routes->get('scan', 'Absensi\ScanController::scan');
    $routes->post('process-scan', 'Absensi\ScanController::processScan');
});

// =====================
// ABSENSI SISWA
// =====================
$routes->group('absensi', ['filter' => 'absensiRole:siswa'], function ($routes) {
    $routes->get('success', 'Absensi\ScanController::success');
    $routes->get('scan-camera', 'Absensi\ScanController::camera');
    $routes->get('scan', 'Absensi\ScanController::scan');
    $routes->post('process-scan', 'Absensi\ScanController::processScan');
});

// =====================
// IZIN (ADMIN + GURU)
// =====================
$routes->group('absensi/izin', ['filter' => 'absensiRole:admin,guru'], function ($routes) {
    $routes->get('admin', 'Absensi\IzinController::adminList');
    $routes->post('approve/(:num)', 'Absensi\IzinController::approve/$1');
    $routes->post('reject/(:num)', 'Absensi\IzinController::reject/$1');
});

// =====================
// IZIN SISWA
// =====================
$routes->group('absensi/izin', ['filter' => 'absensiRole:siswa'], function ($routes) {
    $routes->get('form', 'Absensi\IzinController::form');
    $routes->post('submit', 'Absensi\IzinController::submit');
});

// =====================
// EKSKUL
// =====================
$routes->group('ekskul', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Ekskul\EkskulController::index');
    $routes->post('save', 'Ekskul\EkskulController::save');
    $routes->get('delete/(:num)', 'Ekskul\EkskulController::delete/$1');
    $routes->post('saveJadwal', 'Ekskul\EkskulController::saveJadwal');
    $routes->get('deleteJadwal/(:num)', 'Ekskul\EkskulController::deleteJadwal/$1');
});

$routes->group('ekskul/anggota', function ($routes) {
    $routes->get('(:num)', 'Ekskul\AnggotaEkskulController::index/$1');
    $routes->get('add/(:num)', 'Ekskul\AnggotaEkskulController::add/$1');
    $routes->post('save', 'Ekskul\AnggotaEkskulController::save');
    $routes->get('delete/(:num)', 'Ekskul\AnggotaEkskulController::delete/$1');
    $routes->get('update/(:num)', 'Ekskul\AnggotaEkskulController::update/$1');
});

// =====================
// MAINTENANCE
// =====================
$routes->group('maintenance', ['filter' => 'role:admin'], function ($routes) {
    $routes->get('cleanLog', 'Maintenance::cleanLog');
    $routes->get('cleanAll', 'Maintenance::cleanAll');
    $routes->get('getLogCount', 'Maintenance::getLogCount');
});

// =====================
// DEFAULTS
// =====================
$routes->setDefaultController('Dashboard');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);
