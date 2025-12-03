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
        helper(['url', 'form', 'activity']); // tambahkan activity helper
    }

    // ==========================
    // HALAMAN INDEX
    // ==========================
    public function index()
    {
        return view('tabungan/index');
    }

    // ==========================
    // LIST DATA TABUNGAN
    // ==========================
    public function list()
    {
        $builder = $this->db->table('siswa s');
        $builder->select('s.id, s.nama, s.nisn, s.kelas, s.jurusan, COALESCE(t.saldo,0) as saldo');
        $builder->join('tabungan t', 't.siswa_id = s.id', 'left');
        $data = $builder->orderBy('s.nama', 'ASC')->get()->getResultArray();

        // KPI
        $totalSiswa = count($data);
        $totalSaldo = array_sum(array_column($data, 'saldo'));

        $totalSiswaMenabung = 0;
        foreach ($data as $row) {
            if ((int)$row['saldo'] > 0) {
                $totalSiswaMenabung++;
            }
        }

        return $this->respond([
            'data' => $data,
            'meta' => [
                'totalSiswa' => $totalSiswa,
                'totalSiswaMenabung' => $totalSiswaMenabung,
                'totalSaldo' => $totalSaldo
            ]
        ]);
    }

    // ==========================
    // TRANSAKSI SETOR / TARIK
    // ==========================
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

        // Pastikan row tabungan ada
        $row = $db->table('tabungan')->where('siswa_id', $siswa_id)->get()->getRowArray();
        if (!$row) {
            $db->table('tabungan')->insert(['siswa_id' => $siswa_id, 'saldo' => 0]);
            $currentSaldo = 0;
        } else {
            $currentSaldo = (int) $row['saldo'];
        }

        // Validasi penarikan
        if ($tipe == 'tarik' && $jumlah > $currentSaldo) {
            $db->transComplete();
            return $this->fail('Saldo tidak cukup.');
        }

        // Insert transaksi
        $db->table('transaksi')->insert([
            'siswa_id' => $siswa_id,
            'tipe' => $tipe,
            'jumlah' => $jumlah,
            'keterangan' => $keterangan,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // Hitung saldo baru
        $newSaldo = ($tipe == 'setor')
            ? $currentSaldo + $jumlah
            : $currentSaldo - $jumlah;

        // Update tabungan utama
        $db->table('tabungan')->where('siswa_id', $siswa_id)
            ->set('saldo', $newSaldo)
            ->update();

        // Sinkron ke tabungan_saldo
        $saldoRow = $db->table('tabungan_saldo')
            ->where('siswa_id', $siswa_id)
            ->get()
            ->getRow();

        if ($saldoRow) {
            $db->table('tabungan_saldo')
                ->where('siswa_id', $siswa_id)
                ->update(['saldo' => $newSaldo]);
        } else {
            $db->table('tabungan_saldo')->insert([
                'siswa_id' => $siswa_id,
                'saldo' => $newSaldo
            ]);
        }

        // Ambil detail siswa untuk log
        $siswa = $db->table('siswa')
            ->select('nama, nisn')
            ->where('id', $siswa_id)
            ->get()
            ->getRowArray();

        $namaSiswa = $siswa['nama'] ?? 'Tidak diketahui';
        $nisnSiswa = $siswa['nisn'] ?? '-';

        // ====================
        // 🔥 LOG TRANSAKSI
        // ====================
        logCrud(
            'tabungan',
            $tipe,
            ucfirst($tipe) . " Rp " . number_format($jumlah, 0, ',', '.') .
                " untuk siswa $namaSiswa (NISN: $nisnSiswa)",
            [
                'siswa_id'  => $siswa_id,
                'nama'      => $namaSiswa,
                'nisn'      => $nisnSiswa,
                'tipe'      => $tipe,
                'jumlah'    => $jumlah,
                'saldo_baru' => $newSaldo
            ]
        );

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->failServerError('Gagal menyimpan transaksi.');
        }

        return $this->respond(['success' => true, 'saldo' => $newSaldo]);
    }

    // ==========================
    // RIWAYAT MUTASI
    // ==========================
    public function mutasi($siswa_id = null)
    {
        if (!$siswa_id) return $this->failNotFound('ID siswa tidak ditemukan.');

        $data = $this->db->table('transaksi')
            ->where('siswa_id', $siswa_id)
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();

        // Log akses
        logCrud(
            'tabungan',
            'mutasi',
            "Akses riwayat tabungan siswa ID: {$siswa_id}"
        );

        return $this->respond(['data' => $data]);
    }

    // ==========================
    // DASHBOARD TABUNGAN
    // ==========================
    public function dashboard()
    {
        $totalSavers = $this->db->table('tabungan')->where('saldo >', 0)->countAllResults();
        $totalSaldo = (int)$this->db->table('tabungan')->selectSum('saldo')->get()->getRowArray()['saldo'];

        $builder = $this->db->table('siswa s')
            ->select('s.kelas, SUM(COALESCE(t.saldo,0)) as total')
            ->join('tabungan t', 't.siswa_id = s.id', 'left')
            ->groupBy('s.kelas')
            ->orderBy('total', 'DESC')
            ->get()->getResultArray();

        $top = count($builder) ? $builder[0] : ['kelas' => '-', 'total' => 0];

        return $this->respond([
            'totalSavers' => (int)$totalSavers,
            'totalSaldo' => (int)$totalSaldo,
            'kelasTop' => $top,
            'byKelas' => $builder
        ]);
    }

    // ==========================
    // REPORT DATA
    // ==========================
    public function reportData()
    {
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

    // ==========================
    // VIEW REPORT
    // ==========================
    public function report()
    {
        return view('tabungan/report');
    }

    // ==========================
    // EXPORT CSV
    // ==========================
    public function exportCsv()
    {
        $data = $this->db->table('siswa s')
            ->select('s.nisn, s.nama, s.kelas, s.jurusan, COALESCE(t.saldo,0) as saldo')
            ->join('tabungan t', 't.siswa_id = s.id', 'left')
            ->orderBy('s.nama', 'ASC')
            ->get()->getResultArray();

        // Log
        logCrud(
            'tabungan',
            'export',
            "Export CSV rekap tabungan"
        );

        $filename = 'rekap_tabungan_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $out = fopen('php://output', 'w');
        fputcsv($out, ['NISN', 'Nama', 'Kelas', 'Jurusan', 'Saldo']);
        foreach ($data as $r) {
            fputcsv($out, [$r['nisn'], $r['nama'], $r['kelas'], $r['jurusan'], $r['saldo']]);
        }
        fclose($out);
        exit;
    }
}
