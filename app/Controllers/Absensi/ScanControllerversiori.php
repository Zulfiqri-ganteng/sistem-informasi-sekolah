<?php

namespace App\Controllers\Absensi;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\BarcodeModel;
use App\Models\SiswaModel;
use App\Models\GuruModel;
use App\Models\IzinModel;
use App\Models\JadwalModel;
use App\Models\HariLiburModel;
use App\Models\Ekskul\EkskulModel; // TAMBAHKAN
use App\Models\Ekskul\JadwalEkskulModel; // TAMBAHKAN
class ScanController extends BaseController
{
    protected $absensiModel;
    protected $barcodeModel;
    protected $siswaModel;
    protected $guruModel;
    protected $izinModel;
    protected $jadwalModel;
    protected $hariLiburModel;
    protected $ekskulModel; // TAMBAHKAN
    protected $jadwalEkskulModel; // TAMBAHKAN
    public function __construct()
    {
        $this->absensiModel = new AbsensiModel();
        $this->barcodeModel = new BarcodeModel();
        $this->siswaModel = new SiswaModel();
        $this->guruModel = new GuruModel();
        $this->izinModel = new IzinModel();
        $this->jadwalModel = new JadwalModel();
        $this->hariLiburModel = new HariLiburModel();
        $this->ekskulModel = new EkskulModel(); // TAMBAHKAN
        $this->jadwalEkskulModel = new JadwalEkskulModel(); // TAMBAHKAN
        // Auto-reset status terlambat hari sebelumnya
        $this->autoResetStatus();
    }

    /* =========================================================
    * 1) CAMERA PAGE
    * ========================================================== */
    public function camera()
    {
        return view('absensi/scan_camera');
    }

    /* =========================================================
    * 2) SCAN RESULT PAGE
    * ========================================================== */
    public function scan()
    {
        // Konversi terlambat ke hadir untuk hari ini (jika sudah lewat waktu)
        $this->konversiTerlambatKeHadir();

        $token = $this->request->getGet('token');

        if (!$token) {
            return view('absensi/scan_error', ['message' => 'Token tidak valid.']);
        }

        // ekstraksi token
        $token = $this->extractToken($token);
        if (!$token) {
            return view('absensi/scan_error', ['message' => 'Token tidak valid.']);
        }

        // cek barcode
        $barcode = $this->barcodeModel->where('token', $token)->first();
        if (!$barcode) {
            return view('absensi/scan_error', ['message' => 'QR tidak dikenali.']);
        }

        /* -------------------------------------------------------
         * RULE AKSES
         * -------------------------------------------------------*/
        $sessionUser = session()->get('user_id');
        $sessionRole = session()->get('role');

        if (!$sessionUser || !$sessionRole) {
            return redirect()->to('/login');
        }

        // guru hanya boleh scan siswa
        if ($sessionRole === 'guru' && $barcode['owner_type'] !== 'siswa') {
            return view('absensi/scan_error', [
                'message' => 'Guru hanya dapat scan QR siswa.'
            ]);
        }

        // siswa hanya boleh scan QR miliknya
        if ($sessionRole === 'siswa') {
            if (
                $barcode['owner_type'] !== 'siswa' ||
                (int)$barcode['owner_id'] !== (int)$sessionUser
            ) {
                return view('absensi/scan_error', ['message' => 'QR ini bukan milik Anda.']);
            }
        }

        /* -------------------------------------------------------
         * AMBIL DATA PEMILIK QR
         * -------------------------------------------------------*/
        $ownerId = (int)$barcode['owner_id'];
        $ownerType = $barcode['owner_type'];

        $owner = ($ownerType === 'guru')
            ? $this->guruModel->find($ownerId)
            : $this->siswaModel->find($ownerId);

        if (!$owner) {
            return view('absensi/scan_error', ['message' => 'Pemilik QR tidak ditemukan.']);
        }

        /* -------------------------------------------------------
         * CEK STATUS ABSENSI HARI INI
         * -------------------------------------------------------*/
        $today = date('Y-m-d');

        $absenToday = $this->absensiModel
            ->where('user_id', $ownerId)
            ->where('user_type', $ownerType)
            ->where('tanggal', $today)
            ->first();

        // Cek Izin di tabel 'izin'
        $isIzin = $this->izinModel
            ->where('user_id', $ownerId)
            ->where('tanggal', $today)
            ->whereIn('status', ['approved'])
            ->first();

        if ($isIzin || ($absenToday && ($absenToday['status'] === 'izin' || $absenToday['status'] === 'sakit'))) {
            $nextAction = 'done';
        } else {
            $nextAction = 'masuk';
            if ($absenToday) {
                $nextAction = empty($absenToday['jam_pulang']) ? 'pulang' : 'done';
            }
        }

        return view('absensi/scan_result', [
            'barcode' => $barcode,
            'owner' => $owner,
            'owner_type' => $ownerType,
            'nextAction' => $nextAction,
        ]);
    }

    /* =========================================================
    * 3) PROCESS ABSENSI DENGAN LOGIKA STATUS TERBARU
    * ========================================================== */
    public function processScan()
    {
        // Konversi terlambat ke hadir untuk hari ini (jika sudah lewat waktu)
        $this->konversiTerlambatKeHadir();

        $successUrl = base_url('absensi/success');
        $errorUrl = base_url('absensi/scan-camera');

        if (!$this->request->is('post')) {
            return redirect()->to($errorUrl)->with('error', 'Metode request tidak valid.');
        }

        $barcodeId = $this->request->getPost('barcode_id');

        if (!$barcodeId) {
            return redirect()->to($errorUrl)->with('error', 'Barcode tidak ditemukan (ID kosong).');
        }

        $barcode = $this->barcodeModel->find($barcodeId);
        if (!$barcode) {
            return redirect()->to($errorUrl)->with('error', 'QR tidak valid.');
        }

        $ownerId = (int)$barcode['owner_id'];
        $ownerType = $barcode['owner_type'];

        $tanggal = date('Y-m-d');
        $jamNow = date('H:i:s');

        // =====================================================
        // LOGIKA JADWAL DINAMIS DARI DATABASE
        // =====================================================
        $hari_index = date('N');

        // 1. Cek Hari Libur Insidental
        $isHariLiburInsidental = $this->hariLiburModel->where('tanggal', $tanggal)->first();
        if ($isHariLiburInsidental) {
            return redirect()->to($errorUrl)->with('error', 'Hari ini libur: ' . $isHariLiburInsidental['keterangan']);
        }

        // 2. Ambil Aturan Jadwal Harian dari DB
        $jadwal = $this->jadwalModel->where('hari_index', $hari_index)->first();

        // 3. Cek Jadwal Harian
        if (!$jadwal || $jadwal['status'] === 'libur') {
            $hariNama = $jadwal['hari_nama'] ?? 'Tanggal';
            return redirect()->to($errorUrl)->with('error', $hariNama . ' ini adalah hari libur sekolah.');
        }

        // 4. Definisikan Variabel Waktu dari Database
        $WAKTU_MASUK_NORMAL = $jadwal['jam_masuk_normal'];
        $WAKTU_PENGUNCIAN = $jadwal['jam_penguncian'];
        $WAKTU_PULANG_MINIMAL = $jadwal['jam_pulang_minimal'];
        $WAKTU_PULANG_NORMAL = $jadwal['jam_pulang_normal'];
        $WAKTU_KONVERSI_HADIR = $jadwal['jam_konversi_hadir'] ?? '10:00:00';

        // =====================================================

        // CEK IZIN/SAKIT YANG SUDAH DISETUJUI
        $isIzin = $this->izinModel
            ->where('user_id', $ownerId)
            ->where('user_type', $ownerType)
            ->where('tanggal', $tanggal)
            ->where('status', 'approved')
            ->first();

        if ($isIzin) {
            return redirect()
                ->to($errorUrl)
                ->with('error', 'Anda telah memiliki izin/sakit yang disetujui hari ini. Tidak perlu scan.');
        }

        // cek absen hari ini
        $absenToday = $this->absensiModel
            ->where('user_id', $ownerId)
            ->where('user_type', $ownerType)
            ->where('tanggal', $tanggal)
            ->first();

        /* ===== MASUK ===== */
        if (!$absenToday) {

            // Implementasi Penguncian Absen
            if ($jamNow > $WAKTU_PENGUNCIAN) {
                return redirect()->to($errorUrl)->with('error', 'Waktu absen masuk telah habis (Batas ' . $WAKTU_PENGUNCIAN . '). Anda harus melalui proses administrasi di pos piket.');
            }

            // Tentukan status: Masuk Normal atau Terlambat
            $status = ($jamNow > $WAKTU_MASUK_NORMAL) ? 'terlambat' : 'masuk';

            // Catat Absen Masuk
            $this->absensiModel->insert([
                'user_id' => $ownerId,
                'user_type' => $ownerType,
                'tanggal' => $tanggal,
                'jam_masuk' => $jamNow,
                'status' => $status,
            ]);

            return redirect()
                ->to($successUrl)
                ->with('success', 'Absensi masuk berhasil dicatat. Status: ' . strtoupper($status));
        }

        /* ===== PULANG ===== */
        if ($absenToday && empty($absenToday['jam_pulang'])) {

            $updateData = ['jam_pulang' => $jamNow];
            $currentStatus = $absenToday['status'];

            // ✅ LOGIKA BARU: Konversi Terlambat → Hadir jika sudah lewat waktu konversi
            if ($currentStatus === 'terlambat' && $jamNow >= $WAKTU_KONVERSI_HADIR) {
                $updateData['status'] = 'hadir';
            }

            // Cek Batas Minimal Pulang
            if ($jamNow < $WAKTU_PULANG_MINIMAL) {
                return redirect()->to($errorUrl)->with('error', 'Belum waktunya pulang (minimal jam ' . $WAKTU_PULANG_MINIMAL . ').');
            }

            // Implementasi Pulang Awal
            if ($jamNow < $WAKTU_PULANG_NORMAL) {
                if (!isset($updateData['status']) || $updateData['status'] !== 'hadir') {
                    $updateData['status'] = 'pulang_awal';
                }
            }

            $this->absensiModel->update($absenToday['id'], $updateData);

            $messageStatus = $updateData['status'] ?? $currentStatus;

            return redirect()
                ->to($successUrl)
                ->with('success', 'Absensi pulang berhasil dicatat. Status Akhir: ' . strtoupper($messageStatus));
        }

        /* ===== SUDAH SELESAI ===== */
        return redirect()
            ->to($errorUrl)
            ->with('error', 'Anda sudah absen penuh hari ini.');
    }

    /* =========================================================
    * 4) AUTO-RESET STATUS TERLAMBAT HARI SEBELUMNYA
    * ========================================================== */
    protected function autoResetStatus()
    {
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));

        // Reset status terlambat kemarin menjadi hadir
        $this->absensiModel
            ->where('tanggal', $yesterday)
            ->where('status', 'terlambat')
            ->set(['status' => 'hadir'])
            ->update();

        // Juga reset untuk hari-hari sebelumnya (safety measure)
        $this->absensiModel
            ->where('tanggal <', $today)
            ->where('status', 'terlambat')
            ->set(['status' => 'hadir'])
            ->update();
    }

    /* =========================================================
    * 5) MANUAL RESET STATUS TERLAMBAT (untuk testing)
    * ========================================================== */
    public function resetStatusHarian()
    {
        $today = date('Y-m-d');

        $result = $this->absensiModel
            ->where('tanggal', $today)
            ->where('status', 'terlambat')
            ->set(['status' => 'hadir'])
            ->update();

        return "Status terlambat berhasil direset menjadi hadir untuk tanggal $today. Data terupdate: $result";
    }

    /* =========================================================
    * 6) KONVERSI TERLAMBAT KE HADIR (manual trigger)
    * ========================================================== */
    public function konversiTerlambatKeHadir()
    {
        $today = date('Y-m-d');
        $jamNow = date('H:i:s');

        $hari_index = date('N');
        $jadwal = $this->jadwalModel->where('hari_index', $hari_index)->first();

        if ($jadwal && isset($jadwal['jam_konversi_hadir'])) {
            $WAKTU_KONVERSI_HADIR = $jadwal['jam_konversi_hadir'];

            if ($jamNow >= $WAKTU_KONVERSI_HADIR) {
                $result = $this->absensiModel
                    ->where('tanggal', $today)
                    ->where('status', 'terlambat')
                    ->set(['status' => 'hadir'])
                    ->update();

                // Log untuk debugging (opsional)
                // log_message('info', "Konversi terlambat ke hadir: {$result} record diupdate pada {$today} {$jamNow}");

                return "Konversi status terlambat → hadir selesai untuk $today. Data terupdate: $result";
            }
        }

        return "Belum waktunya konversi status";
    }

    /* =========================================================
    * 7) TOKEN PARSER
    * ========================================================== */
    protected function extractToken(string $raw)
    {
        $raw = trim($raw);

        if (strpos($raw, 'token=') !== false) {
            if (preg_match('/token=([a-f0-9]+)/i', $raw, $m)) {
                return $m[1];
            }
        }

        if (preg_match('/^[a-z0-9]{10,}$/i', $raw)) {
            return $raw;
        }

        return null;
    }

    /* =========================================================
    * 8) HALAMAN SUKSES
    * ========================================================== */
    public function success()
    {
        return view('absensi/success');
    }
}
