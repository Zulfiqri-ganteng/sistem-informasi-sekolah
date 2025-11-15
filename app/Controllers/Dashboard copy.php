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

        // 📊 Statistik utama
        $jumlahSiswa = $db->table('siswa')->countAllResults();
        $totalTabungan = $db->table('tabungan')->selectSum('saldo')->get()->getRow()->saldo ?? 0;

        $bulan = date('m');
        $tahun = date('Y');
        $transaksiBulan = $db->table('transaksi')
            ->where('MONTH(created_at)', $bulan)
            ->where('YEAR(created_at)', $tahun)
            ->countAllResults();

        // 📈 Grafik tabungan tahunan
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

        // 🕒 Hanya 5 transaksi untuk awal (tabel full pakai AJAX)
        $recentTransaksi = $db->table('transaksi t')
            ->select('t.*, s.nama')
            ->join('siswa s', 's.id = t.siswa_id', 'left')
            ->orderBy('t.created_at', 'DESC')
            ->limit(5)
            ->get()->getResultArray();

        return view('dashboard/index', [
            'title' => 'Dashboard',
            'jumlahSiswa' => $jumlahSiswa,
            'totalTabungan' => $totalTabungan,
            'transaksiBulan' => $transaksiBulan,
            'chartData' => array_values($chartData),
            'topSavers' => $topSavers,
            'perKelas' => $perKelas,
            'recentTransaksi' => $recentTransaksi,
        ]);
    }

    // 🔄 Endpoint untuk DataTables (AJAX)
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
