<?php

namespace App\Controllers\Absensi;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\BarcodeModel;
use App\Models\SiswaModel;
use App\Models\GuruModel;
use App\Models\IzinModel; // WAJIB: Model Izin ditambahkan untuk cek status

class ScanController extends BaseController
{
    protected $absensiModel;
    protected $barcodeModel;
    protected $siswaModel;
    protected $guruModel;
    protected $izinModel; // Properti baru untuk Model Izin

    // =========================================================
    // KONSTANTA WAKTU SEKOLAH (RULES FINAL)
    // =========================================================
    private const WAKTU_MASUK_NORMAL = '07:00:00';    // Batas waktu masuk hadir (setelah ini = terlambat)
    private const WAKTU_PENGUNCIAN = '07:30:00';      // Batas waktu scan mutlak (setelah ini scan ditolak)
    private const WAKTU_PULANG_MINIMAL = '12:00:00';  // Batas minimal jam bisa pulang
    private const WAKTU_PULANG_NORMAL = '15:00:00';   // Batas waktu pulang normal (sebelum ini = pulang_awal)

    public function __construct()
    {
        $this->absensiModel = new AbsensiModel();
        $this->barcodeModel = new BarcodeModel();
        $this->siswaModel   = new SiswaModel();
        $this->guruModel    = new GuruModel();
        $this->izinModel    = new IzinModel(); // Inisialisasi Model Izin
    }

    /* =========================================================
     *  1) CAMERA PAGE
     * ========================================================== */
    public function camera()
    {
        return view('absensi/scan_camera');
    }

    /* =========================================================
     *  2) SCAN RESULT PAGE
     *   (Menentukan Next Action, termasuk Cek Izin)
     * ========================================================== */
    public function scan()
    {
        $token = $this->request->getGet('token');

        if (!$token) {
            return view('absensi/scan_error', ['message' => 'Token tidak valid.']);
        }

        // ekstraksi token (raw / URL)
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
         *  RULE AKSES (FITUR ASLI TIDAK DIUBAH)
         *  -------------------------------------------------------*/
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
         *  AMBIL DATA PEMILIK QR
         *  -------------------------------------------------------*/
        $ownerId   = (int)$barcode['owner_id'];
        $ownerType = $barcode['owner_type'];

        $owner = ($ownerType === 'guru')
            ? $this->guruModel->find($ownerId)
            : $this->siswaModel->find($ownerId);

        if (!$owner) {
            return view('absensi/scan_error', ['message' => 'Pemilik QR tidak ditemukan.']);
        }

        /* -------------------------------------------------------
         *  CEK STATUS ABSENSI HARI INI
         *  -------------------------------------------------------*/
        $today = date('Y-m-d');

        $absenToday = $this->absensiModel
            ->where('user_id', $ownerId)
            ->where('user_type', $ownerType)
            ->where('tanggal', $today)
            ->first();

        // **LOGIKA BARU:** Cek Izin di tabel 'izin'
        $isIzin = $this->izinModel
            ->where('user_id', $ownerId)
            ->where('tanggal', $today)
            ->whereIn('status', ['approved'])
            ->first();

        if ($isIzin || ($absenToday && ($absenToday['status'] === 'izin' || $absenToday['status'] === 'sakit'))) {
            // Jika sudah tercatat izin di tabel absensi atau izin sudah diapprove
            $nextAction = 'done';
        } else {
            // Tentukan aksi scan (masuk, pulang, atau done)
            $nextAction = 'masuk';
            if ($absenToday) {
                $nextAction = empty($absenToday['jam_pulang']) ? 'pulang' : 'done';
            }
        }

        return view('absensi/scan_result', [
            'barcode'    => $barcode,
            'owner'      => $owner,
            'owner_type' => $ownerType,
            'nextAction' => $nextAction,
        ]);
    }

    /* =========================================================
     *  3) PROCESS ABSENSI (LOGIKA RULES SEKOLAH)
     * ========================================================== */
    public function processScan()
    {
        $barcodeId = $this->request->getPost('barcode_id');

        // Validasi
        if (!$barcodeId) {
            return redirect()->back()->with('error', 'Barcode tidak ditemukan.');
        }

        $barcode = $this->barcodeModel->find($barcodeId);
        if (!$barcode) {
            return redirect()->back()->with('error', 'QR tidak valid.');
        }

        $ownerId   = (int)$barcode['owner_id'];
        $ownerType = $barcode['owner_type'];

        $tanggal = date('Y-m-d');
        $jamNow  = date('H:i:s');

        // 1. CEK APAKAH ADA IZIN/SAKIT YANG SUDAH DISETUJUI HARI INI (Blokir Scan)
        $isIzin = $this->izinModel
            ->where('user_id', $ownerId)
            ->where('user_type', $ownerType)
            ->where('tanggal', $tanggal)
            ->where('status', 'approved')
            ->first();

        if ($isIzin) {
            return redirect()
                ->back()
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

            // PENTING: Implementasi Penguncian Absen (Batas 07:30)
            if ($jamNow > self::WAKTU_PENGUNCIAN) {
                return redirect()->back()->with('error', 'Waktu absen masuk telah habis (Batas 07:30). Anda harus melalui proses administrasi di pos piket.');
            }

            // Tentukan status: Masuk Normal (<= 07:00) atau Terlambat (> 07:00)
            $status = ($jamNow > self::WAKTU_MASUK_NORMAL) ? 'terlambat' : 'masuk';

            // Catat Absen Masuk
            $this->absensiModel->insert([
                'user_id'    => $ownerId,
                'user_type'  => $ownerType,
                'tanggal'    => $tanggal,
                'jam_masuk'  => $jamNow,
                'status'     => $status,
            ]);

            return redirect()
                ->to(smart_url('absensi/success'))
                ->with('success', 'Absensi masuk berhasil dicatat. Status: ' . strtoupper($status));
        }

        /* ===== PULANG ===== */
        if ($absenToday && empty($absenToday['jam_pulang'])) {

            $updateData = ['jam_pulang' => $jamNow];
            $currentStatus = $absenToday['status'];

            // Cek Batas Minimal Pulang (12:00)
            if ($jamNow < self::WAKTU_PULANG_MINIMAL) {
                return redirect()->back()->with('error', 'Belum waktunya pulang (minimal jam 12:00).');
            }

            // PENTING: Implementasi Pulang Awal (Sebelum 15:00)
            if ($jamNow < self::WAKTU_PULANG_NORMAL) {
                // Jika pulang sebelum jam 15:00, status di-override menjadi 'pulang_awal'
                $updateData['status'] = 'pulang_awal';
            }
            // Jika jamNow >= 15:00:00, status di database tetap pada status masuk awal ('masuk' atau 'terlambat')

            $this->absensiModel->update($absenToday['id'], $updateData);

            $messageStatus = $updateData['status'] ?? $currentStatus;

            return redirect()
                ->to(smart_url('absensi/success'))
                ->with('success', 'Absensi pulang berhasil dicatat. Status Akhir: ' . strtoupper($messageStatus));
        }

        /* ===== SUDAH SELESAI ===== */
        return redirect()
            ->back()
            ->with('error', 'Anda sudah absen penuh hari ini.');
    }

    /* =========================================================
     *  4) TOKEN PARSER (FITUR ASLI TIDAK DIUBAH)
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
     *  5) HALAMAN SUKSES (FITUR ASLI TIDAK DIUBAH)
     * ========================================================== */
    public function success()
    {
        return view('absensi/success');
    }
}
