<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Database;

class Dashboard extends BaseController
{
    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu!');
        }

        if ($session->get('role') === 'siswa') {
            return redirect()->to('/siswa/dashboard');
        }

        $db = Database::connect();

        /* ============================================================
         ================  DATA LAMA (TETAP DIPERTAHANKAN) ============
         ============================================================ */

        // 📊 Statistik utama
        $jumlahSiswa = $db->table('siswa')->countAllResults();

        // Tambahan Premium: jumlah guru, kelas
        $jumlahGuru = $db->table('guru')->countAllResults();
        $jumlahKelas = $db->table('kelas')->countAllResults();

        // Total saldo semua siswa (tabungan)
        $totalTabungan = $db->table('tabungan')->selectSum('saldo')->get()->getRow()->saldo ?? 0;

        $bulan = date('m');
        $tahun = date('Y');

        $transaksiBulan = $db->table('transaksi')
            ->where('MONTH(created_at)', $bulan)
            ->where('YEAR(created_at)', $tahun)
            ->countAllResults();


        // ========================
        // Grafik tabungan tahunan
        // ========================
        $chartQuery = $db->query("
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

        // 🏆 Top 5 siswa
        $topSavers = $db->table('siswa s')
            ->select('s.nama, s.kelas, s.jurusan, COALESCE(t.saldo,0) as saldo')
            ->join('tabungan t', 't.siswa_id = s.id', 'left')
            ->orderBy('t.saldo', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        // 🏫 Tabungan per kelas
        $perKelas = $db->table('siswa s')
            ->select('s.kelas, SUM(COALESCE(t.saldo,0)) as total')
            ->join('tabungan t', 't.siswa_id = s.id', 'left')
            ->groupBy('s.kelas')
            ->orderBy('s.kelas', 'ASC')
            ->get()->getResultArray();

        // 🕒 5 transaksi terbaru
        $recentTransaksi = $db->table('transaksi t')
            ->select('t.*, s.nama')
            ->join('siswa s', 's.id = t.siswa_id', 'left')
            ->orderBy('t.created_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();


        /* ============================================================
         ================  FITUR PREMIUM YANG DITAMBAHKAN ============
         ============================================================ */

        // 💰 Penerimaan hari ini (setor - tarik)
        $penerimaanHari = $db->table('transaksi')
            ->select("SUM(IF(tipe='setor', jumlah, 0)) - SUM(IF(tipe='tarik', jumlah, 0)) AS total")
            ->where('DATE(created_at)', date('Y-m-d'))
            ->get()->getRow()->total ?? 0;


        // 🟦 SPARKLINE - Transaksi 7 hari
        $sparkTransaksi = $this->getTransaksiPerHari();

        // 🟩 SPARKLINE - Penerimaan 7 hari
        $sparkPenerimaan = $this->getPenerimaanPerHari();

        // 🟧 SPARKLINE - Saldo total 7 hari
        $sparkSaldo = $this->getSaldoPerHari();

        // 🟨 SPARKLINE - Siswa (dummy — supaya grafik hidup)
        $sparkSiswa = $this->dummySpark(7, 5, 15);

        // 🔔 Timeline aktivitas terbaru (10 transaksi)
        $recentActivities = $this->getActivityTimeline();


        /* ============================================================
                              RETURN KE VIEW
         ============================================================ */
        $jumlahUser = $db->table('users')->countAllResults();

        return view('dashboard/index', [
            'title' => 'Dashboard',

            // Data lama
            'jumlahUser' => $jumlahUser,

            'jumlahSiswa' => $jumlahSiswa,
            'totalTabungan' => $totalTabungan,
            'transaksiBulan' => $transaksiBulan,
            'chartData' => array_values($chartData),
            'topSavers' => $topSavers,
            'perKelas' => $perKelas,
            'recentTransaksi' => $recentTransaksi,

            // Data upgrade premium
            'jumlahGuru' => $jumlahGuru,
            'jumlahKelas' => $jumlahKelas,
            'penerimaanHari' => $penerimaanHari,

            'sparkSiswa' => $sparkSiswa,
            'sparkTransaksi' => $sparkTransaksi,
            'sparkPenerimaan' => $sparkPenerimaan,
            'sparkSaldo' => $sparkSaldo,

            'recentActivities' => $recentActivities,
        ]);
    }


    /* ============================================================
                           AJAX SUMMARY ENDPOINT
       ============================================================ */

    public function summaryAjax()
    {
        $db = Database::connect();

        $data = [
            'jumlahSiswa' => $db->table('siswa')->countAllResults(),
            'jumlahGuru' => $db->table('guru')->countAllResults(),
            'jumlahKelas' => $db->table('kelas')->countAllResults(),
            'totalTabungan' => $db->table('tabungan')->selectSum('saldo')->get()->getRow()->saldo ?? 0,

            'penerimaanHari' => $db->table('transaksi')
                ->select("SUM(IF(tipe='setor', jumlah, 0)) - SUM(IF(tipe='tarik', jumlah, 0)) AS total")
                ->where('DATE(created_at)', date('Y-m-d'))
                ->get()->getRow()->total ?? 0,
        ];

        return $this->response->setJSON($data);
    }


    /* ============================================================
                           SPARKLINE HELPER
       ============================================================ */

    private function getTransaksiPerHari()
    {
        $db = Database::connect();
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $tgl = date('Y-m-d', strtotime("-$i days"));

            $count = $db->table('transaksi')
                ->where("DATE(created_at)", $tgl)
                ->countAllResults();

            $data[] = $count;
        }

        return $data;
    }


    private function getPenerimaanPerHari()
    {
        $db = Database::connect();
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $tgl = date('Y-m-d', strtotime("-$i days"));

            $row = $db->table('transaksi')
                ->select("SUM(IF(tipe='setor', jumlah, 0)) - SUM(IF(tipe='tarik', jumlah, 0)) AS total")
                ->where("DATE(created_at)", $tgl)
                ->get()->getRow();

            $data[] = intval($row->total ?? 0);
        }

        return $data;
    }


    private function getSaldoPerHari()
    {
        $db = Database::connect();
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $tgl = date('Y-m-d', strtotime("-$i days"));

            // total saldo berdasarkan transaksi sampai tanggal itu
            $row = $db->query("
            SELECT 
                SUM(CASE WHEN tipe='setor' THEN jumlah ELSE -jumlah END) AS total
            FROM transaksi 
            WHERE DATE(created_at) <= ?
        ", [$tgl])->getRow();

            $data[] = intval($row->total ?? 0);
        }

        return $data;
    }



    private function dummySpark($count, $min, $max)
    {
        $arr = [];
        for ($i = 0; $i < $count; $i++) {
            $arr[] = rand($min, $max);
        }
        return $arr;
    }

    private function getActivityTimeline()
    {
        $db = Database::connect();

        $rows = $db->table('transaksi t')
            ->select("t.created_at, t.tipe AS title, CONCAT('Jumlah: ', t.jumlah) AS detail")
            ->orderBy('t.created_at', 'DESC')
            ->limit(10)
            ->get()->getResultArray();

        foreach ($rows as &$r) {
            if ($r['title'] === 'setor') $r['icon'] = 'fa-solid fa-arrow-down text-success';
            elseif ($r['title'] === 'tarik') $r['icon'] = 'fa-solid fa-arrow-up text-danger';
            else $r['icon'] = 'fa-solid fa-circle-info text-primary';
        }

        return $rows;
    }


    /* ============================================================
                        DATATABLES (LAMA, TIDAK DIUBAH)
       ============================================================ */

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
}
