<?php

namespace App\Controllers\Absensi;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;
use App\Models\SiswaModel;
use App\Models\HariLiburModel;
use App\Models\JadwalModel;

// Ekskul models
use App\Models\Ekskul\EkskulModel;
use App\Models\Ekskul\AnggotaEkskulModel;
use App\Models\Ekskul\JadwalEkskulModel;

class LaporanController extends BaseController
{
    protected $absensiModel;
    protected $siswaModel;
    protected $ekskulModel;
    protected $anggotaEkskulModel;
    protected $jadwalEkskulModel;
    protected $jadwalSekolahModel;
    protected $liburModel;

    public function __construct()
    {
        $this->absensiModel        = new AbsensiModel();
        $this->siswaModel          = new SiswaModel();
        $this->ekskulModel         = new EkskulModel();
        $this->anggotaEkskulModel  = new AnggotaEkskulModel();
        $this->jadwalEkskulModel   = new JadwalEkskulModel();
        $this->jadwalSekolahModel  = new JadwalModel();
        $this->liburModel          = new HariLiburModel();
    }

    // -------------------------------------------------------
    // INDEX
    // -------------------------------------------------------
    public function index()
    {
        $ekskul = $this->ekskulModel->orderBy('nama_ekskul', 'ASC')->findAll();

        return view('absensi/laporan/index', [
            'title'  => 'Laporan Absensi',
            'ekskul' => $ekskul
        ]);
    }

    // -------------------------------------------------------
    // HASIL HARIAN
    // -------------------------------------------------------
    public function hasil()
    {
        $tanggal = $this->request->getPost('tanggal');
        $jenis   = $this->request->getPost('jenis');
        $ekskulId = $this->request->getPost('ekskul_id') ?: null;

        if (!$tanggal) {
            return redirect()->back()->with('error', 'Pilih tanggal terlebih dahulu.');
        }

        // Cek hari libur
        $libur = $this->liburModel->where('tanggal', $tanggal)->first();
        if ($libur) {
            return redirect()->back()->with('error', 'Tanggal ini hari libur: ' . $libur['keterangan']);
        }

        // -------------------------------
        // Jika ekskul → redirect ke bulanan
        // -------------------------------
        if ($jenis === 'ekskul') {

            if (!$ekskulId) {
                return redirect()->back()->with('error', 'Pilih ekskul terlebih dahulu.');
            }

            $bulan = date('Y-m', strtotime($tanggal));

            return redirect()->to(
                smart_url("absensi/laporan/ekskulBulanan?ekskul_id={$ekskulId}&bulan={$bulan}")
            );
        }

        // -------------------------------
        // HARIAN
        // -------------------------------
        $absensiRaw = $this->absensiModel
            ->where('tanggal', $tanggal)
            ->where('tipe_absen', 'harian')
            ->where('user_type', 'siswa')
            ->findAll();

        if (!$absensiRaw) {
            return view('absensi/laporan/hasil_harian', [
                'title'   => 'Laporan Harian',
                'tanggal' => $tanggal,
                'siswa'   => [],
                'absensi' => [],
                'totals'  => [
                    'hadir' => 0,
                    'terlambat' => 0,
                    'pulang' => 0,
                    'izin' => 0,
                    'alpha' => 0
                ]
            ]);
        }

        $siswaIds = array_column($absensiRaw, 'user_id');

        $siswa = $this->siswaModel
            ->whereIn('id', $siswaIds)
            ->orderBy('kelas', 'ASC')
            ->orderBy('nama', 'ASC')
            ->findAll();

        $absMap = [];
        foreach ($absensiRaw as $a) {
            $absMap[$a['user_id']] = $a;
        }

        // Stat harian
        $totals = ['hadir' => 0, 'terlambat' => 0, 'pulang' => 0, 'izin' => 0, 'alpha' => 0];

        foreach ($absensiRaw as $a) {
            $st = strtolower($a['status']);

            switch ($st) {
                case 'masuk':
                case 'hadir':
                    $totals['hadir']++;
                    break;

                case 'terlambat':
                    $totals['terlambat']++;
                    break;

                case 'pulang':
                case 'pulang_awal':
                    $totals['pulang']++;
                    break;

                case 'izin':
                    $totals['izin']++;
                    break;

                default:
                    $totals['alpha']++;
                    break;
            }
        }

        return view('absensi/laporan/hasil_harian', [
            'title'   => 'Laporan Harian',
            'tanggal' => $tanggal,
            'siswa'   => $siswa,
            'absensi' => $absMap,
            'totals'  => $totals
        ]);
    }

    // -------------------------------------------------------
    // VIEW EKSUL BULANAN
    // -------------------------------------------------------
    public function ekskulBulanan()
    {
        $ekskulId = $this->request->getGet('ekskul_id') ?? $this->request->getPost('ekskul_id');
        $bulanStr = $this->request->getGet('bulan') ?? $this->request->getPost('bulan') ?? date('Y-m');

        if (!$ekskulId) {
            return redirect()->back()->with('error', 'Pilih ekskul terlebih dahulu.');
        }

        $data = $this->generateEkskulBulananData($ekskulId, $bulanStr);

        return view('absensi/laporan/hasil_ekskul_bulanan', $data);
    }

    // -------------------------------------------------------
    // GENERATE DATA EKSUL BULANAN
    // -------------------------------------------------------
    private function generateEkskulBulananData($ekskulId, $bulanStr)
    {
        if (!preg_match('/^\d{4}-\d{2}$/', $bulanStr)) {
            $bulanStr = date('Y-m');
        }

        [$year, $month] = explode('-', $bulanStr);
        $year = (int)$year;
        $month = (int)$month;

        // Ambil ekskul + pembina
        $db = \Config\Database::connect();

        $ekskulInfo = $db->table('ekskul e')
            ->select('e.*, g.nama AS pembina')
            ->join('guru g', 'g.id = e.pembimbing_id', 'left')
            ->where('e.id', $ekskulId)
            ->get()
            ->getRowArray();

        if (!$ekskulInfo) {
            throw new \RuntimeException('Ekskul tidak ditemukan.');
        }

        // Ambil jadwal ekskul
        $jadwals = $this->jadwalEkskulModel->where('ekskul_id', $ekskulId)->findAll();
        $hariIndices = [];

        foreach ($jadwals as $j) {
            if (isset($j['hari_index'])) {
                $hariIndices[] = (int)$j['hari_index'];
            }
        }

        if (empty($hariIndices)) {
            $hariIndices = [6]; // default Sabtu
        }

        // Generate tanggal pertemuan
        $start = new \DateTimeImmutable(sprintf('%04d-%02d-01', $year, $month));
        $end   = $start->modify('last day of this month');

        $period = new \DatePeriod($start, new \DateInterval('P1D'), $end->modify('+1 day'));

        $meetingDates = [];
        foreach ($period as $dt) {
            if (in_array((int)$dt->format('N'), $hariIndices)) {
                $meetingDates[] = $dt->format('Y-m-d');
            }
        }

        // Ambil anggota ekskul
        $anggota = $this->anggotaEkskulModel
            ->where('ekskul_id', $ekskulId)
            ->where('status', 'aktif')
            ->findAll();

        $anggotaIds = array_column($anggota, 'siswa_id');

        $siswa = $this->siswaModel
            ->whereIn('id', $anggotaIds)
            ->orderBy('kelas', 'ASC')
            ->orderBy('nama', 'ASC')
            ->findAll();

        // Ambil absensi ekskul bulan ini
        $firstDay = $start->format('Y-m-d');
        $lastDay  = $end->format('Y-m-d');

        $absensi = $this->absensiModel
            ->where('tanggal >=', $firstDay)
            ->where('tanggal <=', $lastDay)
            ->where('tipe_absen', 'ekskul')
            ->where('ekskul_id', $ekskulId)
            ->findAll();

        // Mapping absensi
        $absMap = [];
        foreach ($absensi as $a) {
            if ($a['user_type'] !== 'siswa') continue;
            $absMap[$a['user_id']][$a['tanggal']] = $a;
        }

        // Hitung hadir per siswa
        $rekap = [];

        foreach ($siswa as $s) {
            $sid = $s['id'];
            $cnt = 0;

            foreach ($meetingDates as $d) {
                $row = $absMap[$sid][$d] ?? null;

                if (!$row) continue;

                $status = strtolower($row['status']);

                // FIX FINAL: pulang & pulang_awal dianggap hadir
                if (in_array($status, ['h', 'hadir', 'masuk', 'pulang', 'pulang_awal'])) {
                    $cnt++;
                }
            }

            $rekap[$sid] = $cnt;
        }

        return [
            'title'         => 'Laporan Ekskul Bulanan',
            'ekskulInfo'    => $ekskulInfo,
            'bulan'         => $bulanStr,
            'meetingDates'  => $meetingDates,
            'meetingHeader' => array_map(fn($d) => date('d/m', strtotime($d)), $meetingDates),
            'siswa'         => $siswa,
            'absensiMap'    => $absMap,
            'rekap'         => $rekap
        ];
    }

    // -------------------------------------------------------
    // EXPORT PDF
    // -------------------------------------------------------
    public function ekskulBulananPdf()
    {
        $ekskulId = $this->request->getGet('ekskul_id');
        $bulan    = $this->request->getGet('bulan') ?? date('Y-m');

        if (!$ekskulId) {
            return redirect()->back()->with('error', 'Ekskul ID tidak ditemukan.');
        }

        $data = $this->generateEkskulBulananData($ekskulId, $bulan);

        // render view
        $html = view('absensi/laporan/export_ekskul_bulanan_pdf', $data);

        // Dompdf options
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $filename = 'Laporan_Ekskul_' . $data['ekskulInfo']['id'] . '_' . $bulan . '.pdf';

        return $dompdf->stream($filename, ['Attachment' => false]);
    }

    // Placeholder export lainnya
    public function exportPdf()
    {
        $tanggal = $this->request->getGet('tanggal');
        $jenis   = $this->request->getGet('jenis');

        if ($jenis !== 'harian') {
            return 'Jenis laporan tidak valid.';
        }

        if (!$tanggal) {
            return 'Tanggal tidak ditemukan.';
        }

        // Ambil data absensi harian
        $absensiRaw = $this->absensiModel
            ->where('tanggal', $tanggal)
            ->where('tipe_absen', 'harian')
            ->where('user_type', 'siswa')
            ->findAll();

        $siswaIds = array_column($absensiRaw, 'user_id');

        $siswa = $this->siswaModel
            ->whereIn('id', $siswaIds)
            ->orderBy('kelas', 'ASC')
            ->orderBy('nama', 'ASC')
            ->findAll();

        $absMap = [];
        foreach ($absensiRaw as $a) {
            $absMap[$a['user_id']] = $a;
        }

        // Data dikirim ke view PDF
        $data = [
            'tanggal' => $tanggal,
            'siswa'   => $siswa,
            'absensi' => $absMap
        ];

        // Render HTML View
        $html = view('absensi/laporan/export_harian_pdf', $data);

        // Dompdf settings
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->stream('Laporan_Harian_' . $tanggal . '.pdf', ['Attachment' => false]);
    }

    public function exportWord()
    {
        $tanggal = $this->request->getGet('tanggal');
        $jenis   = $this->request->getGet('jenis');

        if (!$tanggal || $jenis !== 'harian') {
            return "Data tidak valid.";
        }

        // Ambil data
        $absensi = $this->absensiModel
            ->where('tanggal', $tanggal)
            ->where('tipe_absen', 'harian')
            ->where('user_type', 'siswa')
            ->findAll();

        $siswaIds = array_column($absensi, 'user_id');
        $siswa = $this->siswaModel->whereIn('id', $siswaIds)->findAll();

        $data = [
            'tanggal' => $tanggal,
            'absensi' => $absensi,
            'siswa'   => $siswa
        ];

        // Bangun HTML dari view
        $html = view('absensi/laporan/export_harian_word', $data);

        // Header Word
        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=Laporan_Harian_$tanggal.doc");

        echo $html;
        exit;
    }



    public function exportExcel()
    {
        $tanggal = $this->request->getGet('tanggal');
        $jenis   = $this->request->getGet('jenis');

        if (!$tanggal || $jenis !== 'harian') {
            return "Data tidak valid.";
        }

        $absensi = $this->absensiModel
            ->where('tanggal', $tanggal)
            ->where('tipe_absen', 'harian')
            ->where('user_type', 'siswa')
            ->findAll();

        $siswaIds = array_column($absensi, 'user_id');
        $siswa = $this->siswaModel->whereIn('id', $siswaIds)->findAll();

        $data = [
            'tanggal' => $tanggal,
            'absensi' => $absensi,
            'siswa'   => $siswa
        ];

        // Load view khusus Excel
        $html = view('absensi/laporan/export_harian_excel', $data);

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Laporan_Harian_$tanggal.xls");

        echo $html;
        exit;
    }
}
