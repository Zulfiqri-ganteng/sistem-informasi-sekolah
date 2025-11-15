<?php

namespace App\Controllers;

use App\Models\KelasModel;
use App\Models\GuruModel;
use CodeIgniter\Controller;
use Config\Database;

class Kelas extends Controller
{
    protected $kelasModel;
    protected $guruModel;
    protected $request;
    protected $db;

    public function __construct()
    {
        $this->kelasModel = new KelasModel();
        $this->guruModel  = new GuruModel();
        $this->request    = service('request');
        $this->db         = Database::connect();   // <-- WAJIB! FIX ERROR 500
    }

    // ==============================
    // 📄 Halaman View
    // ==============================
    public function index()
    {
        return view('kelas/index', ['title' => 'Data Kelas']);
    }

    // ==============================
    // 📊 Data untuk DataTables
    // ==============================
    public function list()
    {
        // Ambil semua kelas + guru wali
        $kelas = $this->kelasModel
            ->select('kelas.id, kelas.nama_kelas, kelas.guru_id, guru.nama AS guru_nama')
            ->join('guru', 'guru.id = kelas.guru_id', 'left')
            ->orderBy('kelas.nama_kelas', 'ASC')
            ->findAll();

        $db = \Config\Database::connect();

        foreach ($kelas as &$k) {

            // ===== Hitung jumlah siswa =====
            $jumlah = $db->table('siswa')
                ->where('kelas', $k['nama_kelas'])   // siswa.kelas = nama_kelas
                ->countAllResults();

            $k['jumlah_siswa'] = $jumlah;

            // ===== Hitung total saldo siswa pada kelas ini =====
            $totalSaldo = $db->table('siswa s')
                ->selectSum('ts.saldo')
                ->join('tabungan_saldo ts', 'ts.siswa_id = s.id', 'left')
                ->where('s.kelas', $k['nama_kelas'])
                ->get()->getRow()->saldo;

            $k['total_saldo'] = $totalSaldo ?? 0;
        }

        return $this->response->setJSON(['data' => $kelas]);
    }


    // ==============================
    // 📥 Ambil 1 Data
    // ==============================
    public function get($id = null)
    {
        $row = $this->kelasModel->find($id);

        if (!$row) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['error' => 'Data tidak ditemukan']);
        }

        return $this->response->setJSON($row);
    }

    // ==============================
    // 💾 Simpan (Insert / Update)
    // ==============================
    public function save()
    {
        $post = $this->request->getPost();

        $id        = $post['id'] ?? null;
        $namaKelas = $post['nama_kelas'] ?? null;
        $guruId    = $post['guru_id'] ?? null;

        if (empty($namaKelas)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nama kelas wajib diisi'
            ]);
        }

        if (!empty($guruId)) {

            $cekGuru = $this->kelasModel
                ->where('guru_id', $guruId)
                ->where('id !=', $id)
                ->first();

            if ($cekGuru) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Guru ini sudah menjadi wali kelas di: <b>' . $cekGuru['nama_kelas'] . '</b>'
                ]);
            }
        }

        $data = [
            'nama_kelas' => $namaKelas,
            'guru_id'    => $guruId
        ];

        if (!empty($id)) {
            $this->kelasModel->update($id, $data);
        } else {
            $this->kelasModel->insert($data);
        }

        return $this->response->setJSON(['success' => true]);
    }

    // ==============================
    // ⭐ Dropdown Guru
    // ==============================
    public function getGuruDropdown()
    {
        $kelas = $this->kelasModel->findAll();

        $waliKelas = [];
        foreach ($kelas as $k) {
            if (!empty($k['guru_id'])) {
                $waliKelas[$k['guru_id']] = $k['nama_kelas'];
            }
        }

        $guruList = $this->guruModel->findAll();
        $result = [];

        foreach ($guruList as $g) {
            $result[] = [
                'id'         => $g['id'],
                'nama'       => $g['nama'],
                'is_wali'    => isset($waliKelas[$g['id']]),
                'kelas_wali' => $waliKelas[$g['id']] ?? null
            ];
        }

        return $this->response->setJSON($result);
    }

    // ==============================
    // 🧑‍🎓 Load siswa dalam kelas
    // ==============================
    public function siswa($id = null)
    {
        if (empty($id)) {
            return $this->response->setStatusCode(400)
                ->setJSON(['error' => 'ID kelas dibutuhkan']);
        }

        $kelas = $this->kelasModel->find($id);
        if (!$kelas) {
            return $this->response->setStatusCode(404)
                ->setJSON(['error' => 'Kelas tidak ditemukan']);
        }

        $namaKelas = $kelas['nama_kelas'];

        $builder = $this->db->table('siswa s')
            ->select('s.id, s.nisn, s.nama, s.jenis_kelamin, s.foto,
                      COALESCE(ts.saldo, 0) AS saldo')
            ->join('tabungan_saldo ts', 'ts.siswa_id = s.id', 'left')
            ->where('s.kelas', $namaKelas)
            ->orderBy('s.nama', 'ASC');

        $siswa = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'kelas' => [
                'id'         => $kelas['id'],
                'nama_kelas' => $kelas['nama_kelas'],
                'guru_id'    => $kelas['guru_id']
            ],
            'jumlah' => count($siswa),
            'siswa'  => $siswa
        ]);
    }

    // ==============================
    public function delete($id = null)
    {
        if (!$id) return $this->response->setJSON(['success' => false]);

        $this->kelasModel->delete($id);
        return $this->response->setJSON(['success' => true]);
    }
}
