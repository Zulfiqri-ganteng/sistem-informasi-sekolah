<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Database;
use App\Models\AbsensiModel;
use App\Models\SiswaModel;
use App\Models\GuruModel;

class Dashboard extends BaseController
{
    protected $db;
    protected $absensi;
    protected $siswa;
    protected $guru;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->absensi = new AbsensiModel();
        $this->siswa = new SiswaModel();
        $this->guru = new GuruModel();
    }

    /**
     * Main merged dashboard (Tabungan + Absensi)
     * Supports optional GET filters: jurusan, kelas
     */
    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu!');
        }

        // --- TABUNGAN (existing data) ---
        // keep defaults only as fallback; we'll load real jurusan/kelas from DB
        $jurusanList = $this->getJurusan();
        // map jurusan rows to simple array
        $jurusanList = array_map(fn($r) => $r['jurusan'], $jurusanList);
        $kelasList = $this->getKelas(); // load kelas+jurusan for initial server-render

        $jumlahSiswa = $this->db->table('siswa')->countAllResults();
        $jumlahGuru  = $this->db->table('guru')->countAllResults();
        $jumlahKelas = $this->db->table('kelas')->countAllResults();
        $totalTabungan = $this->db->table('tabungan')->selectSum('saldo')->get()->getRow()->saldo ?? 0;

        $bulan = date('m');
        $tahun = date('Y');

        $transaksiBulan = $this->db->table('transaksi')
            ->where('MONTH(created_at)', $bulan)
            ->where('YEAR(created_at)', $tahun)
            ->countAllResults();

        // chartData (monthly)
        $chartQuery = $this->db->query("
            SELECT MONTH(created_at) AS bulan,
                   SUM(CASE WHEN tipe='setor' THEN jumlah ELSE -jumlah END) AS total
            FROM transaksi
            WHERE YEAR(created_at) = YEAR(CURDATE())
            GROUP BY MONTH(created_at)
            ORDER BY bulan ASC
        ");
        $chartRows = $chartQuery->getResultArray();
        $chartData = array_fill(1, 12, 0);
        foreach ($chartRows as $r) {
            $chartData[(int)$r['bulan']] = (int)$r['total'];
        }

        // top savers, perKelas, recentTransaksi, sparklines, etc.
        $topSavers = $this->db->table('siswa s')
            ->select('s.nama, s.kelas, s.jurusan, COALESCE(t.saldo,0) as saldo')
            ->join('tabungan t', 't.siswa_id = s.id', 'left')
            ->orderBy('t.saldo', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        $perKelas = $this->db->table('siswa s')
            ->select('s.kelas, SUM(COALESCE(t.saldo,0)) as total')
            ->join('tabungan t', 't.siswa_id = s.id', 'left')
            ->groupBy('s.kelas')
            ->orderBy('s.kelas', 'ASC')
            ->get()->getResultArray();

        $recentTransaksi = $this->db->table('transaksi t')
            ->select('t.*, s.nama')
            ->join('siswa s', 's.id = t.siswa_id', 'left')
            ->orderBy('t.created_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        $penerimaanHari = $this->db->table('transaksi')
            ->select("SUM(IF(tipe='setor', jumlah, 0)) - SUM(IF(tipe='tarik', jumlah, 0)) AS total")
            ->where('DATE(created_at)', date('Y-m-d'))
            ->get()->getRow()->total ?? 0;

        // sparklines (simple helpers)
        $sparkTransaksi = $this->getTransaksiPerHari();
        $sparkPenerimaan = $this->getPenerimaanPerHari();
        $sparkSaldo = $this->getSaldoPerHari();
        $sparkSiswa = $this->dummySpark(7, 5, 15);
        $recentActivities = $this->getActivityTimeline();

        // --- ABSENSI (new merged part) ---
        // default filters from GET
        $jurusanFilter = $this->request->getGet('jurusan') ?? 'all';
        $kelasFilter = $this->request->getGet('kelas') ?? 'all';

        // compute counts and initial rekap for server-render (first load) — will be refreshed with AJAX when user changes filter
        $today = date('Y-m-d');

        // build base absensi query with optional joins/filters (optimized)
        $builder = $this->db->table('absensi a')
            ->select('a.*, s.nama as siswa_nama, s.kelas as siswa_kelas, s.jurusan as siswa_jurusan, g.nama as guru_nama')
            ->join('siswa s', "s.id = a.user_id AND a.user_type='siswa'", 'left')
            ->join('guru g', "g.id = a.user_id AND a.user_type='guru'", 'left')
            ->where('a.tanggal', $today);

        if ($jurusanFilter !== 'all') {
            $builder->where('s.jurusan', $jurusanFilter);
        }
        if ($kelasFilter !== 'all') {
            $builder->where('s.kelas', $kelasFilter);
        }

        $rekapRaw = $builder->orderBy('a.jam_masuk', 'ASC')->get()->getResultArray();

        // counts by status (use single efficient query)
        $countBuilder = $this->db->table('absensi a')->select("a.status, COUNT(*) as cnt")->where('a.tanggal', $today);

        if ($jurusanFilter !== 'all') $countBuilder->join('siswa s', 's.id = a.user_id', 'left')->where('s.jurusan', $jurusanFilter);
        if ($kelasFilter !== 'all') $countBuilder->join('siswa s2', 's2.id = a.user_id', 'left')->where('s2.kelas', $kelasFilter);

        $statusRows = $countBuilder->groupBy('a.status')->get()->getResultArray();
        $counts = ['masuk' => 0, 'terlambat' => 0, 'izin' => 0, 'sakit' => 0, 'pulang_awal' => 0];
        foreach ($statusRows as $r) {
            $counts[$r['status']] = (int)$r['cnt'];
        }

        // format rekap for view
        $rekap = [];
        foreach ($rekapRaw as $r) {
            $ownerName = $r['user_type'] === 'guru' ? ($r['guru_nama'] ?? ('Guru-' . $r['user_id'])) : ($r['siswa_nama'] ?? ('Siswa-' . $r['user_id']));
            $kelas = $r['user_type'] === 'siswa' ? ($r['siswa_kelas'] ?? '-') : '-';
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

        // --- return view with all data
        return view('dashboard/index', [
            'title' => 'Dashboard',
            'jurusanList' => $jurusanList,
            'kelasList'   => $kelasList,

            'jumlahSiswa' => $jumlahSiswa,
            'jumlahGuru' => $jumlahGuru,
            'jumlahKelas' => $jumlahKelas,
            'totalTabungan' => $totalTabungan,
            'transaksiBulan' => $transaksiBulan,
            'chartData' => array_values($chartData),
            'topSavers' => $topSavers,
            'perKelas' => $perKelas,
            'recentTransaksi' => $recentTransaksi,
            'penerimaanHari' => $penerimaanHari,
            'sparkSiswa' => $sparkSiswa,
            'sparkTransaksi' => $sparkTransaksi,
            'sparkPenerimaan' => $sparkPenerimaan,
            'sparkSaldo' => $sparkSaldo,
            'recentActivities' => $recentActivities,

            // absensi
            'jurusanList' => $jurusanList,
            'selectedJurusan' => $jurusanFilter,
            'selectedKelas' => $kelasFilter,
            'hadir' => $counts['masuk'],
            'telat' => $counts['terlambat'],
            'izin' => $counts['izin'],
            'sakit' => $counts['sakit'],
            'pulang_awal' => $counts['pulang_awal'],
            'rekap' => $rekap,
            'today' => $today,
        ]);
    }

    /**
     * Public alias used by JS: /dashboard/kelas/{jurusan?}
     */
    public function kelas($jurusan = null)
    {
        // delegate to internal helper logic used previously
        $jurusan = urldecode($jurusan ?? $this->request->getGet('jurusan'));
        if (!$jurusan || $jurusan === 'all') {
            $rows = $this->db->table('siswa')->select('kelas, jurusan')->where('kelas IS NOT NULL')->groupBy('kelas, jurusan')->orderBy('kelas', 'ASC')->get()->getResultArray();
        } else {
            $rows = $this->db->table('siswa')->select('kelas, jurusan')->where('jurusan', $jurusan)->where('kelas IS NOT NULL')->groupBy('kelas, jurusan')->orderBy('kelas', 'ASC')->get()->getResultArray();
        }
        // return array of kelas strings for backwards compatibility with existing JS
        $kelas = array_values(array_filter(array_map(fn($r) => $r['kelas'], $rows)));
        return $this->response->setJSON(['kelas' => $kelas]);
    }

    /**
     * AJAX: return absensi summary + rekap (filtered)
     * Accepts GET params: jurusan, kelas
     */
    public function absensiAjax()
    {
        $jurusan = $this->request->getGet('jurusan') ?? 'all';
        $kelas = $this->request->getGet('kelas') ?? 'all';
        $today = date('Y-m-d');

        // counts
        $countBuilder = $this->db->table('absensi a')->select("a.status, COUNT(*) as cnt")->where('a.tanggal', $today);
        if ($jurusan !== 'all') $countBuilder->join('siswa s', 's.id = a.user_id', 'left')->where('s.jurusan', $jurusan);
        if ($kelas !== 'all') $countBuilder->join('siswa s2', 's2.id = a.user_id', 'left')->where('s2.kelas', $kelas);
        $statusRows = $countBuilder->groupBy('a.status')->get()->getResultArray();
        $counts = ['masuk' => 0, 'terlambat' => 0, 'izin' => 0, 'sakit' => 0, 'pulang_awal' => 0];
        foreach ($statusRows as $r) $counts[$r['status']] = (int)$r['cnt'];

        // rekap rows
        $builder = $this->db->table('absensi a')
            ->select('a.*, s.nama as siswa_nama, s.kelas as siswa_kelas, s.jurusan as siswa_jurusan, g.nama as guru_nama')
            ->join('siswa s', "s.id = a.user_id AND a.user_type='siswa'", 'left')
            ->join('guru g', "g.id = a.user_id AND a.user_type='guru'", 'left')
            ->where('a.tanggal', $today);

        if ($jurusan !== 'all') $builder->where('s.jurusan', $jurusan);
        if ($kelas !== 'all') $builder->where('s.kelas', $kelas);

        $rows = $builder->orderBy('a.jam_masuk', 'ASC')->get()->getResultArray();
        $rekap = [];
        foreach ($rows as $r) {
            $ownerName = $r['user_type'] === 'guru' ? ($r['guru_nama'] ?? ('Guru-' . $r['user_id'])) : ($r['siswa_nama'] ?? ('Siswa-' . $r['user_id']));
            $kelasName = $r['user_type'] === 'siswa' ? ($r['siswa_kelas'] ?? '-') : '-';
            $rekap[] = [
                'id' => $r['id'],
                'nama' => $ownerName,
                'user_type' => $r['user_type'],
                'kelas' => $kelasName,
                'jam_masuk' => $r['jam_masuk'],
                'jam_pulang' => $r['jam_pulang'],
                'status' => $r['status']
            ];
        }

        return $this->response->setJSON([
            'counts' => $counts,
            'rekap' => $rekap,
            'today' => $today
        ]);
    }

    /* ---------------- helper functions ---------------- */
    private function getTransaksiPerHari()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = date('Y-m-d', strtotime("-$i days"));
            $count = $this->db->table('transaksi')->where("DATE(created_at)", $tgl)->countAllResults();
            $data[] = $count;
        }
        return $data;
    }

    private function getPenerimaanPerHari()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = date('Y-m-d', strtotime("-$i days"));
            $row = $this->db->table('transaksi')
                ->select("SUM(IF(tipe='setor', jumlah, 0)) - SUM(IF(tipe='tarik', jumlah, 0)) AS total")
                ->where("DATE(created_at)", $tgl)
                ->get()->getRow();
            $data[] = intval($row->total ?? 0);
        }
        return $data;
    }

    private function getSaldoPerHari()
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $tgl = date('Y-m-d', strtotime("-$i days"));
            $row = $this->db->query("
                SELECT SUM(CASE WHEN tipe='setor' THEN jumlah ELSE -jumlah END) AS total
                FROM transaksi WHERE DATE(created_at) <= ?
            ", [$tgl])->getRow();
            $data[] = intval($row->total ?? 0);
        }
        return $data;
    }

    private function dummySpark($count, $min, $max)
    {
        $arr = [];
        for ($i = 0; $i < $count; $i++) $arr[] = rand($min, $max);
        return $arr;
    }

    private function getActivityTimeline()
    {
        $rows = $this->db->table('transaksi t')
            ->select("t.created_at, t.tipe AS title, CONCAT('Jumlah: ', t.jumlah) AS detail")
            ->orderBy('t.created_at', 'DESC')
            ->limit(10)->get()->getResultArray();
        foreach ($rows as &$r) {
            if ($r['title'] === 'setor') $r['icon'] = 'fa-solid fa-arrow-down text-success';
            elseif ($r['title'] === 'tarik') $r['icon'] = 'fa-solid fa-arrow-up text-danger';
            else $r['icon'] = 'fa-solid fa-circle-info text-primary';
        }
        return $rows;
    }

private function getJurusan()
    {
        $db = \Config\Database::connect();

        // Query ini sudah benar, tetapi jika error tetap terjadi,
        // kita paksa alias kolomnya untuk memastikan CodeIgniter tidak bingung.
        // TETAP KAN menggunakan tabel siswa, karena berdasarkan screenshot Anda
        // kolom 'jurusan' ada di tabel 'siswa'.
        return $db->table('siswa')
            ->select('jurusan')
            ->where('jurusan IS NOT NULL')
            ->groupBy('jurusan')
            ->orderBy('jurusan')
            ->get()->getResultArray();
    }

    private function getKelas()
    {
        $db = \Config\Database::connect();

        // Cek apakah tabel kelas ada isinya
        $kelasTable = $db->table('kelas')->countAllResults();

        if ($kelasTable > 0) {
            // Pakai tabel kelas kalau ada.
            // **PERBAIKAN:** Kita asumsikan tabel 'kelas' HANYA punya 'nama_kelas' dan 'id'.
            // Kolom 'jurusan' kemungkinan TIDAK ADA di tabel 'kelas' tetapi diminta di index.php,
            // sehingga kita berikan nilai NULL sebagai 'jurusan' untuk mencegah error.
            
            // Jika tabel 'kelas' Anda memang memiliki kolom 'jurusan', ganti dengan:
            // ->select('nama_kelas as kelas, jurusan')

            // Jika tidak, gunakan:
            return $db->table('kelas')
                ->select('nama_kelas as kelas, NULL as jurusan') // <-- ASUMSI perbaikan: berikan nilai NULL untuk kolom 'jurusan' di tabel 'kelas'
                ->orderBy('nama_kelas')
                ->get()->getResultArray();
        }

        // Fallback ke tabel siswa (sudah benar dan memiliki kolom 'kelas' dan 'jurusan')
        return $db->table('siswa')
            ->select('kelas, jurusan')
            ->where('kelas IS NOT NULL')
            ->groupBy('kelas, jurusan')
            ->orderBy('kelas')
            ->get()->getResultArray();
    }

    public function transaksiAjax()
    {
        $db = Database::connect();

        $data = $db->table('transaksi t')
            ->select('t.id, t.created_at, t.tipe, t.jumlah, t.keterangan, s.nama')
            ->join('siswa s', 's.id = t.siswa_id', 'left')
            ->orderBy('t.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['data' => $data]);
    }

    public function rekapAjax()
    {
        $db = \Config\Database::connect();
        $request = service('request');

        // Datatables required variables
        $draw   = $request->getGet('draw');
        $start  = $request->getGet('start');
        $length = $request->getGet('length');
        $search = $request->getGet('search')['value'] ?? '';

        // Filter jurusan & kelas
        $filterJurusan = $request->getGet('jurusan');
        $filterKelas   = $request->getGet('kelas');

        $builder = $db->table('absensi a')
            ->select('a.*, s.nama as siswa_nama, s.kelas, s.jurusan, g.nama as guru_nama')
            ->join('siswa s', 's.id = a.user_id AND a.user_type="siswa"', 'left')
            ->join('guru g', 'g.id = a.user_id AND a.user_type="guru"', 'left')
            ->where('a.tanggal', date('Y-m-d'));

        if ($filterJurusan) {
            $builder->where('s.jurusan', $filterJurusan);
        }

        if ($filterKelas) {
            $builder->where('s.kelas', $filterKelas);
        }

        if (!empty($search)) {
            $builder->groupStart()
                ->like('s.nama', $search)
                ->orLike('g.nama', $search)
                ->orLike('s.kelas', $search)
                ->groupEnd();
        }

        // Count total filtered
        $countFiltered = $builder->countAllResults(false);

        // Pagination
        $builder->limit($length, $start);
        $results = $builder->get()->getResultArray();

        // Format untuk datatables
        $data = [];
        foreach ($results as $r) {
            $nama = ($r['user_type'] === 'guru') ? $r['guru_nama'] : $r['siswa_nama'];

            $data[] = [
                'nama'   => $nama,
                'jenis'  => ucfirst($r['user_type']),
                'kelas'  => $r['kelas'] ?? '-',
                'masuk'  => $r['jam_masuk'] ?? '-',
                'pulang' => $r['jam_pulang'] ?? '-',
                'status' => $r['status'],
            ];
        }

        return $this->response->setJSON([
            'draw'            => intval($draw),
            'recordsTotal'    => $countFiltered,
            'recordsFiltered' => $countFiltered,
            'data'            => $data,
        ]);
    }
}
