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
use App\Models\Ekskul\EkskulModel;
use App\Models\Ekskul\JadwalEkskulModel;
use App\Models\Ekskul\AnggotaEkskulModel;

class ScanController extends BaseController
{
    protected $absensiModel;
    protected $barcodeModel;
    protected $siswaModel;
    protected $guruModel;
    protected $izinModel;
    protected $jadwalModel;
    protected $hariLiburModel;
    protected $ekskulModel;
    protected $jadwalEkskulModel;
    protected $anggotaEkskulModel;

    public function __construct()
    {
        $this->absensiModel       = new AbsensiModel();
        $this->barcodeModel       = new BarcodeModel();
        $this->siswaModel         = new SiswaModel();
        $this->guruModel          = new GuruModel();
        $this->izinModel          = new IzinModel();
        $this->jadwalModel        = new JadwalModel();
        $this->hariLiburModel     = new HariLiburModel();
        $this->ekskulModel        = new EkskulModel();
        $this->jadwalEkskulModel  = new JadwalEkskulModel();
        $this->anggotaEkskulModel = new AnggotaEkskulModel();

        // Auto-reset status terlambat hari sebelumnya
        $this->autoResetStatus();
    }

    /* =========================================================
     * 1) CAMERA PAGE
     * ========================================================== */
    public function camera()
    {
        $this->autoPulangEkskul();
        return view('absensi/scan_camera');
    }

    /* =========================================================
     * 2) SCAN RESULT PAGE
     *    - mendeteksi mode: harian atau ekskul
     *    - mematuhi aturan: EKS KUL TIDAK BOLEH OVERLAP DENGAN HARIAN
     * ========================================================== */
    public function scan()
    {
        $this->autoPulangEkskul();

        // Konversi terlambat ke hadir (jika sudah lewat waktu konversi)
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
         * CEK MODE: EKS KUL atau HARIAN (deteksi awal)
         * - aturan strict: jika bukan ekskul & harian OFF -> TOLAK
         * -------------------------------------------------------*/
        $today = date('Y-m-d');
        $jamNow = date('H:i:s');
        $hari_index = date('N');

        // Ambil jadwal harian (untuk cek overlap + status)
        $jadwalSekolah = $this->jadwalModel->where('hari_index', $hari_index)->first();

        // Cek jadwal ekskul yang berjalan sekarang
        $jadwalEkskul = $this->jadwalEkskulModel
            ->where('hari_index', $hari_index)
            ->where('jam_mulai <=', $jamNow)
            ->where('jam_selesai >=', $jamNow)
            ->first();

        $isAnggotaEkskul = false;
        $ekskulAktif = null;
        $tipe_absen = 'harian';

        if ($jadwalEkskul && $ownerType === 'siswa') {
            // cek apakah siswa adalah anggota ekskul
            $cekAnggota = $this->anggotaEkskulModel
                ->where('ekskul_id', $jadwalEkskul['ekskul_id'])
                ->where('siswa_id', $ownerId)
                ->where('status', 'aktif')
                ->first();

            if ($cekAnggota) {
                // sebelum set ekskul, pastikan tidak overlap dengan jam sekolah aktif
                if ($jadwalSekolah && isset($jadwalSekolah['status']) && $jadwalSekolah['status'] !== 'libur') {
                    $ek_mulai = $jadwalEkskul['jam_mulai'];
                    $ek_selesai = $jadwalEkskul['jam_selesai'];
                    $sch_mulai = $jadwalSekolah['jam_masuk_normal'];
                    $sch_selesai = $jadwalSekolah['jam_pulang_normal'];

                    $isOverlap = !($ek_selesai <= $sch_mulai || $ek_mulai >= $sch_selesai);
                    if ($isOverlap) {
                        return view('absensi/scan_error', [
                            'message' => 'Terdeteksi bentrok jadwal antara Ekskul dan Jam Sekolah. Hubungi admin untuk perbaikan jadwal.'
                        ]);
                    }
                }

                // jika tidak overlap, set ekskul aktif
                $isAnggotaEkskul = true;
                $ekskulAktif = $jadwalEkskul['ekskul_id'];
                $tipe_absen = 'ekskul';
            }
        }

        /* -------------------------------------------------------
         * CEK STATUS ABSENSI HARI INI sesuai tipe
         * -------------------------------------------------------*/
        if ($tipe_absen === 'ekskul') {
            // cek absensi ekskul hari ini
            $absenToday = $this->absensiModel
                ->where('user_id', $ownerId)
                ->where('user_type', $ownerType)
                ->where('tanggal', $today)
                ->where('tipe_absen', 'ekskul')
                ->first();

            // nextAction untuk ekskul
            if (!$absenToday) {
                $nextAction = 'masuk';
            } else {
                $nextAction = empty($absenToday['jam_pulang']) ? 'pulang' : 'done';
            }
        } else {
            // fallback ke cek harian
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
        }

        /* -------------------------------------------------------
         * RETURN VIEW DENGAN INFO TIPE ABSENSI
         * -------------------------------------------------------*/
        return view('absensi/scan_result', [
            'barcode' => $barcode,
            'owner' => $owner,
            'owner_type' => $ownerType,
            'nextAction' => $nextAction,
            'tipe_absen' => $tipe_absen,
            'jadwalEkskul' => $jadwalEkskul,
            'ekskul_id' => $ekskulAktif,
        ]);
    }

    /* =========================================================
     * 3) PROCESS ABSENSI DENGAN LOGIKA TERBARU (Strict Mode)
     *    - prioritas: EKS KUL (jika aktif & anggota) -> HARIAN
     *    - strict: jika bukan ekskul & harian OFF/Libur => TOLAK
     * ========================================================== */
    public function processScan()
    {
        // Konversi otomatis terlambat → hadir
        $this->konversiTerlambatKeHadir();

        $successUrl = smart_url('absensi/success');
        $errorUrl   = smart_url('absensi/scan-camera');

        if (!$this->request->is('post')) {
            return redirect()->to($errorUrl)->with('error', 'Metode request tidak valid.');
        }

        $barcodeId = $this->request->getPost('barcode_id');
        if (!$barcodeId) {
            return redirect()->to($errorUrl)->with('error', 'Barcode tidak ditemukan.');
        }

        $barcode = $this->barcodeModel->find($barcodeId);
        if (!$barcode) {
            return redirect()->to($errorUrl)->with('error', 'QR tidak valid.');
        }

        $ownerId   = (int)$barcode['owner_id'];
        $ownerType = $barcode['owner_type'];

        $tanggal    = date('Y-m-d');
        $jamNow     = date('H:i:s');
        $hari_index = date('N');

        // LOG realtime (simple)
        log_message('info', "[SCAN] owner={$ownerType}:{$ownerId} time={$jamNow} date={$tanggal}");

        // ======================================================
        // 1) CEK HARI LIBUR INSIDENTAL
        // ======================================================
        $libur = $this->hariLiburModel->where('tanggal', $tanggal)->first();
        if ($libur) {
            log_message('warning', "[SCAN] Hari libur: {$libur['keterangan']}");
            return redirect()->to($errorUrl)->with('error', 'Hari ini libur: ' . ($libur['keterangan'] ?? ''));
        }

        // ======================================================
        // 2) CEK EKS KUL (DIPRIORITASKAN)
        // ======================================================
        $jadwalEkskul = $this->jadwalEkskulModel
            ->where('hari_index', $hari_index)
            ->where('jam_mulai <=', $jamNow)
            ->where('jam_selesai >=', $jamNow)
            ->first();

        if ($jadwalEkskul && $ownerType === 'siswa') {

            // Cek apakah siswa anggota ekskul
            $anggota = $this->anggotaEkskulModel
                ->where('ekskul_id', $jadwalEkskul['ekskul_id'])
                ->where('siswa_id', $ownerId)
                ->where('status', 'aktif')
                ->first();

            if ($anggota) {

                // Pastikan ekskul TIDAK overlap dengan sekolah (aturan C)
                $jadwalHarian = $this->jadwalModel->where('hari_index', $hari_index)->first();
                if ($jadwalHarian && $jadwalHarian['status'] !== 'libur') {
                    $isOverlap = !(
                        $jadwalEkskul['jam_selesai'] <= $jadwalHarian['jam_masuk_normal'] ||
                        $jadwalEkskul['jam_mulai']   >= $jadwalHarian['jam_pulang_normal']
                    );

                    if ($isOverlap) {
                        log_message('error', "[SCAN] Ekskul overlap detected for ekskul_id={$jadwalEkskul['ekskul_id']}");
                        return redirect()->to($errorUrl)->with('error', 'Jadwal ekskul bentrok dengan jam sekolah. Hubungi admin.');
                    }
                }

                // ======================================================
                // INSERT / UPDATE ABSENSI EKS KUL
                // ======================================================
                try {
                    $existingEks = $this->absensiModel
                        ->where('user_id', $ownerId)
                        ->where('tanggal', $tanggal)
                        ->where('tipe_absen', 'ekskul')
                        ->first();

                    // MASUK ekskul
                    if (!$existingEks) {
                        $insertData = [
                            'user_id'     => $ownerId,
                            'user_type'   => $ownerType,
                            'tanggal'     => $tanggal,
                            'jam_masuk'   => $jamNow,
                            'status'      => 'masuk',
                            'tipe_absen'  => 'ekskul',
                            'ekskul_id'   => $jadwalEkskul['ekskul_id'],
                            'keterangan'  => 'hadir_ekskul'
                        ];

                        $this->absensiModel->insert($insertData);
                        log_message('info', "[SCAN] Insert ekskul absensi: " . json_encode($insertData));

                        return redirect()->to($successUrl)->with('success', 'Absensi ekskul (masuk) dicatat.');
                    }

                    // PULANG ekskul (jika belum ada jam_pulang)
                    if (empty($existingEks['jam_pulang'])) {
                        // jangan override ekskul_id — hanya set jam_pulang + status
                        $this->absensiModel->update($existingEks['id'], [
                            'jam_pulang' => $jamNow,
                            'status'     => 'pulang'
                        ]);
                        log_message('info', "[SCAN] Update ekskul pulang id={$existingEks['id']} jam_pulang={$jamNow}");

                        return redirect()->to($successUrl)->with('success', 'Absensi ekskul (pulang) dicatat.');
                    }

                    // sudah lengkap
                    return redirect()->to($errorUrl)->with('error', 'Absensi ekskul sudah lengkap hari ini.');
                } catch (\Throwable $e) {
                    log_message('error', "[SCAN][ERROR] ekskul process failed: " . $e->getMessage());
                    return redirect()->to($errorUrl)->with('error', 'Terjadi kesalahan saat memproses absensi ekskul.');
                }
            }
        }

        // ======================================================
        // strict mode: jika bukan ekskul dan jadwal harian OFF/Libur -> tolak
        // ======================================================
        $jadwal = $this->jadwalModel->where('hari_index', $hari_index)->first();
        if (!$jadwal || (isset($jadwal['status']) && $jadwal['status'] === 'libur')) {
            // dalam strict mode, jika bukan jam ekskul (karena kita tiba disini) -> tolak
            log_message('warning', "[SCAN] Strict mode reject: not ekskul & harian OFF for owner={$ownerType}:{$ownerId}");
            return redirect()->to($errorUrl)->with('error', 'Saat ini bukan waktu absensi (mode ketat). Hubungi admin jika perlu.');
        }

        // ======================================================
        // 3) PROSES ABSENSI HARIAN (fallback ketika jadwal harian aktif)
        // ======================================================
        try {
            // Ambil parameter waktu harian
            $WAKTU_MASUK   = $jadwal['jam_masuk_normal'] ?? '07:30:00';
            $WAKTU_PENGUNCIAN = $jadwal['jam_penguncian'] ?? '08:30:00';
            $WAKTU_PULANG_MINIMAL = $jadwal['jam_pulang_minimal'] ?? '12:00:00';
            $WAKTU_PULANG_NORMAL = $jadwal['jam_pulang_normal'] ?? '15:00:00';
            $WAKTU_KONVERSI = $jadwal['jam_konversi_hadir'] ?? '10:00:00';

            // Cek izin / sakit
            $isIzin = $this->izinModel
                ->where('user_id', $ownerId)
                ->where('tanggal', $tanggal)
                ->where('status', 'approved')
                ->first();

            if ($isIzin) {
                return redirect()->to($errorUrl)->with('error', 'Anda sudah izin/sakit hari ini.');
            }

            $absenToday = $this->absensiModel
                ->where('user_id', $ownerId)
                ->where('tanggal', $tanggal)
                ->first();

            // ===== MASUK HARIAN =====
            if (!$absenToday) {
                if ($jamNow > $WAKTU_PENGUNCIAN) {
                    return redirect()->to($errorUrl)->with('error', 'Waktu masuk sudah habis.');
                }

                $status = ($jamNow > $WAKTU_MASUK) ? 'terlambat' : 'masuk';

                $insert = [
                    'user_id'    => $ownerId,
                    'user_type'  => $ownerType,
                    'tanggal'    => $tanggal,
                    'jam_masuk'  => $jamNow,
                    'status'     => $status,
                    'tipe_absen' => 'harian'
                ];

                $this->absensiModel->insert($insert);
                log_message('info', "[SCAN] Insert harian absensi: " . json_encode($insert));

                return redirect()->to($successUrl)->with('success', 'Absensi masuk dicatat.');
            }

            // ===== PULANG HARIAN =====
            if (empty($absenToday['jam_pulang'])) {
                if ($jamNow < $WAKTU_PULANG_MINIMAL) {
                    return redirect()->to($errorUrl)->with('error', 'Belum waktunya pulang.');
                }

                $update = ['jam_pulang' => $jamNow];

                // konversi terlambat -> hadir
                if ($absenToday['status'] === 'terlambat' && $jamNow >= $WAKTU_KONVERSI) {
                    $update['status'] = 'hadir';
                }

                // pulang awal
                if ($jamNow < $WAKTU_PULANG_NORMAL) {
                    $update['status'] = 'pulang_awal';
                }

                $this->absensiModel->update($absenToday['id'], $update);
                log_message('info', "[SCAN] Update harian pulang id={$absenToday['id']} " . json_encode($update));

                return redirect()->to($successUrl)->with('success', 'Absensi pulang dicatat.');
            }

            // sudah lengkap
            return redirect()->to($errorUrl)->with('error', 'Absensi hari ini sudah lengkap.');
        } catch (\Throwable $e) {
            log_message('error', "[SCAN][ERROR] harian process failed: " . $e->getMessage());
            return redirect()->to($errorUrl)->with('error', 'Terjadi kesalahan saat memproses absensi harian.');
        }
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

    /* =========================================================
 * AUTO PULANG EKSKUL – Hybrid (Cron + Trigger)
 * ========================================================== */
    public function autoPulangEkskul()
    {
        $now        = date('H:i:s');
        $today      = date('Y-m-d');
        $hari_index = date('N');

        // Ambil jadwal ekskul hari ini
        $jadwal = $this->jadwalEkskulModel
            ->where('hari_index', $hari_index)
            ->findAll();

        if (!$jadwal) {
            return false;
        }

        $updated = 0;

        foreach ($jadwal as $j) {

            $jamSelesai  = $j['jam_selesai'];     // ex: 10:00:00
            $graceMinute = 10;                    // Grace period 10 menit
            $deadline    = date('H:i:s', strtotime($jamSelesai . " +{$graceMinute} minutes"));

            // Jika waktu sekarang belum lewat deadline → skip
            if ($now < $deadline) {
                continue;
            }

            // Ambil semua absen ekskul yang MASUK tapi belum PULANG
            $absenList = $this->absensiModel
                ->where('tanggal', $today)
                ->where('tipe_absen', 'ekskul')
                ->where('ekskul_id', $j['ekskul_id'])
                ->where('jam_pulang', null)
                ->findAll();

            foreach ($absenList as $a) {

                $this->absensiModel->update($a['id'], [
                    'jam_pulang' => $now,
                    'status'     => 'pulang',
                    'keterangan' => 'auto_pulang_ekskul'
                ]);

                $updated++;
            }
        }

        return $updated;
    }
    public function cronAutoPulang()
    {
        $updated = $this->autoPulangEkskul();
        return "AUTO PULANG AKTIF — updated: {$updated}";
    }
}
