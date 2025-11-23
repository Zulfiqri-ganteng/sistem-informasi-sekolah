<?php

namespace App\Controllers\Absensi;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\SiswaModel;
use App\Models\GuruModel;

class DashboardController extends BaseController
{
    protected $absensi;
    protected $siswa;
    protected $guru;

    public function __construct()
    {
        $this->absensi = new AbsensiModel();
        $this->siswa   = new SiswaModel();
        $this->guru    = new GuruModel();
    }

    public function index()
    {
        $today = date('Y-m-d');

        // counts by status
        $hadir = $this->absensi->where('tanggal', $today)->where('status', 'masuk')->countAllResults();
        $telat  = $this->absensi->where('tanggal', $today)->where('status', 'terlambat')->countAllResults();
        $izin   = $this->absensi->where('tanggal', $today)->where('status', 'izin')->countAllResults();
        $sakit  = $this->absensi->where('tanggal', $today)->where('status', 'sakit')->countAllResults();
        $pulang_awal = $this->absensi->where('tanggal', $today)->where('status', 'pulang_awal')->countAllResults();

        // rekap: ambil last status per record with owner info
        $rekapRaw = $this->absensi->where('tanggal', $today)->orderBy('jam_masuk', 'ASC')->findAll();

        // enrich with owner name & kelas (if siswa)
        $rekap = [];
        foreach ($rekapRaw as $r) {
            $ownerName = ($r['user_type'] === 'guru') ? ($this->guru->find($r['user_id'])['nama'] ?? 'Guru-' . $r['user_id']) : ($this->siswa->find($r['user_id'])['nama'] ?? 'Siswa-' . $r['user_id']);
            $kelas = null;
            if ($r['user_type'] === 'siswa') {
                $sRow = $this->siswa->find($r['user_id']);
                $kelas = $sRow['kelas'] ?? null;
            }
            $rekap[] = [
                'id' => $r['id'],
                'nama' => $ownerName,
                'user_type' => $r['user_type'],
                'kelas' => $kelas,
                'jam_masuk' => $r['jam_masuk'],
                'jam_pulang' => $r['jam_pulang'],
                'status' => $r['status']
            ];
        }

        return view('absensi/dashboard', [
            'today' => $today,
            'hadir' => $hadir,
            'telat' => $telat,
            'izin'  => $izin,
            'sakit' => $sakit,
            'pulang_awal' => $pulang_awal,
            'rekap' => $rekap,
            'title' => 'Dashboard Absensi'
        ]);
    }
}
