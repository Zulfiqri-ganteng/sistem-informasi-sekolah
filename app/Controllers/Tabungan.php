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
        helper(['url', 'form', 'activity']); // pastikan helper activity ada dan logCrud tersedia
    }

    // Halaman utama
    public function index()
    {
        return view('tabungan/index');
    }

    // Data untuk DataTable (list)
    public function list()
    {
        $session = session();
        $role = $session->get('role') ?? 'guest';

        $builder = $this->db->table('siswa s');
        $builder->select('s.id, s.nama, s.nisn, s.kelas, s.jurusan, COALESCE(t.saldo,0) as saldo');
        $builder->join('tabungan t', 't.siswa_id = s.id', 'left');

        // Jika yang login guru -> batasi ke kelas yang menjadi tanggung jawab guru tersebut
        if ($role === 'guru') {
            $userId = $session->get('user_id') ?? null;
            if ($userId) {
                // Cari guru.id berdasarkan users.id (user_id)
                $guruRow = $this->db->table('guru')->where('user_id', $userId)->get()->getRowArray();
                if ($guruRow && !empty($guruRow['id'])) {
                    // ambil semua nama_kelas yang di-wali oleh guru ini
                    $kelasRows = $this->db->table('kelas')->select('nama_kelas')->where('guru_id', $guruRow['id'])->get()->getResultArray();
                    $kelasNames = array_column($kelasRows, 'nama_kelas');

                    if (!empty($kelasNames)) {
                        // whereIn
                        $builder->whereIn('s.kelas', $kelasNames);
                    } else {
                        // jika guru tidak menjadi wali kelas manapun, jangan tampilkan data
                        return $this->respond([
                            'data' => [],
                            'meta' => [
                                'totalSiswa' => 0,
                                'totalSiswaMenabung' => 0,
                                'totalSaldo' => 0
                            ]
                        ]);
                    }
                }
            }
        }

        $data = $builder->orderBy('s.nama', 'ASC')->get()->getResultArray();

        // KPI:
        $totalSiswa = count($data);
        $totalSaldo = array_sum(array_column($data, 'saldo'));
        $totalSiswaMenabung = 0;
        foreach ($data as $row) {
            if ((int)$row['saldo'] > 0) $totalSiswaMenabung++;
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

        $session = session();
        $role = $session->get('role') ?? 'guest';

        // Jika guru, pastikan siswa termasuk di kelas yang dia wali
        if ($role === 'guru') {
            $userId = $session->get('user_id') ?? null;
            if (!$userId) return $this->failForbidden('Akses tidak diizinkan.');

            $guruRow = $this->db->table('guru')->where('user_id', $userId)->get()->getRowArray();
            if (!$guruRow) return $this->failForbidden('Akses tidak diizinkan.');

            // ambil nama_kelas yang dia wali
            $kelasRows = $this->db->table('kelas')->select('nama_kelas')->where('guru_id', $guruRow['id'])->get()->getResultArray();
            $kelasNames = array_column($kelasRows, 'nama_kelas');

            if (empty($kelasNames)) {
                return $this->failForbidden('Anda bukan wali kelas manapun.');
            }

            // cek apakah siswa termasuk kelas tersebut
            $siswaRowCheck = $this->db->table('siswa')->select('id, nama, nisn, kelas')->where('id', $siswa_id)->get()->getRowArray();
            if (!$siswaRowCheck) return $this->failNotFound('Siswa tidak ditemukan.');
            if (!in_array($siswaRowCheck['kelas'], $kelasNames)) {
                return $this->failForbidden('Siswa bukan berada di kelas Anda.');
            }
        } else {
            // untuk admin atau role lain, ambil siswa juga untuk logging
            $siswaRowCheck = $this->db->table('siswa')->select('id, nama, nisn, kelas')->where('id', $siswa_id)->get()->getRowArray();
            if (!$siswaRowCheck) return $this->failNotFound('Siswa tidak ditemukan.');
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
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // update saldo
        $newSaldo = ($tipe == 'setor') ? $currentSaldo + $jumlah : $currentSaldo - $jumlah;

        $db->table('tabungan')->where('siswa_id', $siswa_id)
            ->set('saldo', $newSaldo)
            ->update();

        // sinkron ke tabungan_saldo
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

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->failServerError('Gagal menyimpan transaksi.');
        }

        // ambil data siswa untuk kebutuhan log
        $siswa = $db->table('siswa')->where('id', $siswa_id)->get()->getRowArray();

        $namaSiswa = $siswa['nama'] ?? 'Tidak diketahui';
        $nisnSiswa = $siswa['nisn'] ?? '-';

        // LOG
        logCrud(
            'tabungan',
            $tipe,
            ucfirst($tipe) . " Rp " . number_format($jumlah, 0, ',', '.') .
                " untuk siswa $namaSiswa (NISN: $nisnSiswa)",
            [
                'siswa_id'   => $siswa_id,
                'nama'       => $namaSiswa,
                'nisn'       => $nisnSiswa,
                'tipe'       => $tipe,
                'jumlah'     => $jumlah,
                'saldo_baru' => $newSaldo
            ]
        );

        return $this->respond(['success' => true, 'saldo' => $newSaldo]);
    }

    // Riwayat mutasi siswa
    public function mutasi($siswa_id = null)
    {
        if (!$siswa_id) return $this->failNotFound('ID siswa tidak ditemukan.');

        $session = session();
        $role = $session->get('role') ?? 'guest';

        // Jika guru -> validasi siswa di kelasnya
        if ($role === 'guru') {
            $userId = $session->get('user_id') ?? null;
            $guruRow = $this->db->table('guru')->where('user_id', $userId)->get()->getRowArray();
            if (!$guruRow) return $this->failForbidden('Akses tidak diizinkan.');

            $kelasRows = $this->db->table('kelas')->select('nama_kelas')->where('guru_id', $guruRow['id'])->get()->getResultArray();
            $kelasNames = array_column($kelasRows, 'nama_kelas');
            $siswaRow = $this->db->table('siswa')->select('kelas')->where('id', $siswa_id)->get()->getRowArray();
            if (!$siswaRow || !in_array($siswaRow['kelas'], $kelasNames)) {
                return $this->failForbidden('Siswa bukan berada di kelas Anda.');
            }
        }

        $data = $this->db->table('transaksi')
            ->where('siswa_id', $siswa_id)
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();

        // 🔥 LOG RIWAYAT MUTASI
        logCrud(
            'tabungan',
            'mutasi',
            "Akses riwayat tabungan siswa ID: {$siswa_id}"
        );

        return $this->respond(['data' => $data]);
    }

    // (rest of methods dashboard, reportData, report, exportCsv tetap sama)
    public function dashboard()
    {
        $totalSavers = $this->db->table('tabungan')->where('saldo >', 0)->countAllResults();
        $totalSaldo = (int) $this->db->table('tabungan')->selectSum('saldo')->get()->getRowArray()['saldo'];

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

    public function report()
    {
        return view('tabungan/report');
    }

    public function exportCsv()
    {
        $data = $this->db->table('siswa s')
            ->select('s.nisn, s.nama, s.kelas, s.jurusan, COALESCE(t.saldo,0) as saldo')
            ->join('tabungan t', 't.siswa_id = s.id', 'left')
            ->orderBy('s.nama', 'ASC')
            ->get()->getResultArray();

        logCrud('tabungan', 'export', "Export CSV rekap tabungan");

        $filename = 'rekap_tabungan_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $out = fopen('php://output', 'w');
        fputcsv($out, ['NISN', 'Nama', 'Kelas', 'Jurusan', 'Saldo']);
        foreach ($data as $r) {
            // data dari query sebagai object/array bergantung DB driver; handle keduanya
            $nisn = is_object($r) ? $r->nisn : $r['nisn'];
            $nama = is_object($r) ? $r->nama : $r['nama'];
            $kelas = is_object($r) ? $r->kelas : $r['kelas'];
            $jurusan = is_object($r) ? $r->jurusan : $r['jurusan'];
            $saldo = is_object($r) ? $r->saldo : $r['saldo'];
            fputcsv($out, [$nisn, $nama, $kelas, $jurusan, $saldo]);
        }
        fclose($out);
        exit;
    }
}
