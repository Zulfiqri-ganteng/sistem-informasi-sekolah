<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use Config\Database;

class Tabungan extends BaseController
{
    use ResponseTrait;

    protected $db;
    public function __construct()
    {
        $this->db = Database::connect();
        helper(['url', 'form']);
    }

    // Halaman utama
    public function index()
    {
        return view('tabungan/index');
    }

    // Data untuk DataTable (list)
    // Data untuk DataTable (list)
    public function list()
    {
        $builder = $this->db->table('siswa s');
        $builder->select('s.id, s.nama, s.nisn, s.kelas, s.jurusan, COALESCE(t.saldo,0) as saldo');
        $builder->join('tabungan t', 't.siswa_id = s.id', 'left');
        $data = $builder->orderBy('s.nama', 'ASC')->get()->getResultArray();

        // ✅ Hitung KPI:
        // 1️⃣ Total siswa (semua)
        $totalSiswa = count($data);

        // 2️⃣ Total saldo seluruh siswa
        $totalSaldo = array_sum(array_column($data, 'saldo'));

        // 3️⃣ Total siswa yang menabung (saldo > 0)
        $totalSiswaMenabung = 0;
        foreach ($data as $row) {
            if ((int)$row['saldo'] > 0) {
                $totalSiswaMenabung++;
            }
        }

        // ✅ Kembalikan semua data ke frontend
        return $this->respond([
            'data' => $data,
            'meta' => [
                'totalSiswa' => $totalSiswa,
                'totalSiswaMenabung' => $totalSiswaMenabung,
                'totalSaldo' => $totalSaldo
            ]
        ]);
    }



    // Simpan transaksi (setor/tarik)
    public function transaction()
    {
        $post = $this->request->getPost();
        $siswa_id = (int) ($post['siswa_id'] ?? 0);
        $tipe = $post['tipe'] ?? null;
        $jumlah = (int) ($post['jumlah'] ?? 0);
        $keterangan = $post['keterangan'] ?? null;

        if (!$siswa_id || !$tipe || $jumlah <= 0) {
            return $this->failValidationErrors('Data tidak lengkap.');
        }

        $db = $this->db;
        $db->transStart();

        // pastikan ada row tabungan untuk siswa
        $row = $db->table('tabungan')->where('siswa_id', $siswa_id)->get()->getRowArray();
        if (!$row) {
            // buat default row
            $db->table('tabungan')->insert(['siswa_id' => $siswa_id, 'saldo' => 0]);
            $currentSaldo = 0;
        } else {
            $currentSaldo = (int) $row['saldo'];
        }

        // validasi penarikan
        if ($tipe == 'tarik' && $jumlah > $currentSaldo) {
            $db->transComplete();
            return $this->fail('Saldo tidak cukup.');
        }

        // insert transaksi
        $db->table('transaksi')->insert([
            'siswa_id' => $siswa_id,
            'tipe' => $tipe,
            'jumlah' => $jumlah,
            'keterangan' => $keterangan,
            'created_at' => date('Y-m-d H:i:s') // tambahkan waktu biar rapih di dashboard siswa
        ]);

        // update saldo (tabel tabungan utama)
        $newSaldo = ($tipe == 'setor')
            ? $currentSaldo + $jumlah
            : $currentSaldo - $jumlah;

        $db->table('tabungan')->where('siswa_id', $siswa_id)
            ->set('saldo', $newSaldo)
            ->update();

        // 🔄 Sinkron juga ke tabel tabungan_saldo (buat dashboard siswa)
        $saldoRow = $db->table('tabungan_saldo')
            ->where('siswa_id', $siswa_id)
            ->get()
            ->getRow();

        if ($saldoRow) {
            // jika sudah ada, update saldo
            $db->table('tabungan_saldo')
                ->where('siswa_id', $siswa_id)
                ->update(['saldo' => $newSaldo]);
        } else {
            // jika belum ada, buat baru
            $db->table('tabungan_saldo')->insert([
                'siswa_id' => $siswa_id,
                'saldo' => $newSaldo
            ]);
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->failServerError('Gagal menyimpan transaksi.');
        }

        return $this->respond(['success' => true, 'saldo' => $newSaldo]);
    }


    // Riwayat mutasi siswa
    public function mutasi($siswa_id = null)
    {
        if (!$siswa_id) return $this->failNotFound('ID siswa tidak ditemukan.');

        $data = $this->db->table('transaksi')
            ->where('siswa_id', $siswa_id)
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();

        return $this->respond(['data' => $data]);
    }

    // Data dashboard untuk chart / ringkasan
    public function dashboard()
    {
        // total siswa menabung (saldo > 0)
        $totalSavers = $this->db->table('tabungan')->where('saldo >', 0)->countAllResults();

        // total saldo
        $totalSaldo = (int) $this->db->table('tabungan')->selectSum('saldo')->get()->getRowArray()['saldo'];

        // total per kelas
        $builder = $this->db->table('siswa s')
            ->select('s.kelas, SUM(COALESCE(t.saldo,0)) as total')
            ->join('tabungan t', 't.siswa_id = s.id', 'left')
            ->groupBy('s.kelas')
            ->orderBy('total', 'DESC')
            ->get()->getResultArray();

        // kelas tertinggi
        $top = count($builder) ? $builder[0] : ['kelas' => '-', 'total' => 0];

        return $this->respond([
            'totalSavers' => (int)$totalSavers,
            'totalSaldo' => (int)$totalSaldo,
            'kelasTop' => $top,
            'byKelas' => $builder
        ]);
    }

    // Data untuk laporan (rekap), paginable or full
    public function reportData()
    {
        // optional filter params: kelas, jurusan, tanggal_from, tanggal_to
        $kelas = $this->request->getGet('kelas');
        $jurusan = $this->request->getGet('jurusan');

        $builder = $this->db->table('siswa s')
            ->select('s.id, s.nama, s.kelas, s.jurusan, COALESCE(t.saldo,0) as saldo')
            ->join('tabungan t', 't.siswa_id = s.id', 'left');

        if ($kelas) $builder->where('s.kelas', $kelas);
        if ($jurusan) $builder->where('s.jurusan', $jurusan);

        $data = $builder->orderBy('s.nama', 'ASC')->get()->getResultArray();
        return $this->respond(['data' => $data]);
    }

    // Halaman laporan (view)
    public function report()
    {
        return view('tabungan/report');
    }

    // CSV export sederhana
    public function exportCsv()
    {
        $data = $this->db->table('siswa s')
            ->select('s.nisn, s.nama, s.kelas, s.jurusan, COALESCE(t.saldo,0) as saldo')
            ->join('tabungan t', 't.siswa_id = s.id', 'left')
            ->orderBy('s.nama', 'ASC')
            ->get()->getResultArray();

        $filename = 'rekap_tabungan_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $out = fopen('php://output', 'w');
        fputcsv($out, ['NISN', 'Nama', 'Kelas', 'Jurusan', 'Saldo']);
        foreach ($data as $r) {
            fputcsv($out, [$r->nisn, $r->nama, $r->kelas, $r->jurusan, $r->saldo]);
        }
        fclose($out);
        exit;
    }
}
