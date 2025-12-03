<?php

namespace App\Controllers;

use App\Models\GuruModel;
use App\Models\UserModel;
use App\Models\MapelModel;
use App\Models\KelasModel;
use App\Models\JurusanModel;
use App\Models\GuruMapelModel;
use CodeIgniter\Controller;
use Config\Database;

class GuruController extends Controller
{
    protected $guruModel;
    protected $userModel;
    protected $mapelModel;
    protected $kelasModel;
    protected $jurusanModel;
    protected $guruMapelModel;
    protected $db;

    public function __construct()
    {
        $this->guruModel       = new GuruModel();
        $this->userModel       = new UserModel();
        $this->mapelModel      = new MapelModel();
        $this->kelasModel      = new KelasModel();
        $this->jurusanModel    = new JurusanModel();
        $this->guruMapelModel  = new GuruMapelModel();
        $this->db              = Database::connect();
        helper('activity');
    }

    // ==============================
    // 📄 HALAMAN INDEX
    // ==============================
    public function index()
    {
        return view('guru/index', [
            'title' => 'Data Guru'
        ]);
    }

    // ==============================
    // 📋 DATATABLES + FILTER
    // ==============================
    public function list()
    {
        try {

            $jurusanId = $this->request->getGet('jurusan_id');
            $kelasName = $this->request->getGet('kelas');

            $builder = $this->guruModel
                ->select('guru.*, GROUP_CONCAT(mapel.nama_mapel SEPARATOR ", ") AS mapel')
                ->join('guru_mapel', 'guru_mapel.guru_id = guru.id', 'left')
                ->join('mapel', 'mapel.id = guru_mapel.mapel_id', 'left')
                ->groupBy('guru.id')
                ->orderBy('guru.nama', 'ASC');

            // FILTER JURUSAN
            if (!empty($jurusanId)) {
                $builder
                    ->join('kelas k1', 'k1.guru_id = guru.id', 'left')
                    ->where('k1.jurusan_id', $jurusanId);
            }

            // FILTER KELAS
            if (!empty($kelasName)) {
                $builder
                    ->join('kelas k2', 'k2.guru_id = guru.id', 'left')
                    ->where('k2.nama_kelas', $kelasName);
            }

            $data = $builder->findAll();

            return $this->response->setJSON(['data' => $data]);
        } catch (\Throwable $e) {

            return $this->response->setJSON([
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine()
            ]);
        }
    }

    // ==============================
    // 💾 INSERT & UPDATE GURU
    // ==============================
    public function save()
    {
        try {

            $post = $this->request->getPost();

            $id       = $post['id'] ?? null;
            $nip      = $post['nip'] ?? '';
            $nama     = $post['nama'] ?? '';
            $email    = $post['email'] ?? '';
            $telepon  = $post['telepon'] ?? '';
            $alamat   = $post['alamat'] ?? '';
            $mapelIds = $post['mapel_id'] ?? [];   // Array mapel multiple

            // FOTO
            $fotoName = null;
            $file = $this->request->getFile('foto');

            if ($file && $file->isValid() && !$file->hasMoved()) {
                $fotoName = time() . '_' . $file->getRandomName();
                $file->move(FCPATH . 'uploads/guru', $fotoName);
            }

            // ==============================
            // INSERT BARU
            // ==============================
            if (empty($id)) {

                // Buat akun user otomatis
                $username       = $nip ?: strtolower(preg_replace('/\s+/', '', $nama)) . rand(100, 999);
                $plainPassword  = $nip ?: substr(md5(time()), 0, 8);
                $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

                $userData = [
                    'username'   => $username,
                    'nama'       => $nama,
                    'email'      => $email,
                    'telepon'    => $telepon,
                    'password'   => $hashedPassword,
                    'role'       => 'guru',
                    'foto'       => $fotoName,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $this->db->table('users')->insert($userData);
                $userId = $this->db->insertID();

                // Simpan guru
                $guruData = [
                    'user_id'    => $userId,
                    'nip'        => $nip,
                    'nama'       => $nama,
                    'telepon'    => $telepon,
                    'email'      => $email,
                    'alamat'     => $alamat,
                    'foto'       => $fotoName,
                    'created_at' => date('Y-m-d H:i:s')
                ];

                $this->guruModel->insert($guruData);
                $guruId = $this->guruModel->insertID();
                // 🔥 LOG: Guru baru ditambahkan
                logCrud(
                    'guru',
                    'create',
                    "Tambah guru baru: $nama (NIP: $nip)",
                    $guruData
                );

                // Insert multi mapel
                foreach ($mapelIds as $m) {
                    $this->guruMapelModel->insert([
                        'guru_id'  => $guruId,
                        'mapel_id' => $m
                    ]);
                }
            }

            // ==============================
            // UPDATE
            // ==============================
            else {

                $guruData = [
                    'nip'        => $nip,
                    'nama'       => $nama,
                    'telepon'    => $telepon,
                    'email'      => $email,
                    'alamat'     => $alamat,
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                if ($fotoName) {
                    $guruData['foto'] = $fotoName;
                }

                $this->guruModel->update($id, $guruData);
                // 🔥 LOG: Update guru
                logCrud(
                    'guru',
                    'update',
                    "Update data guru ID: $id ($nama)",
                    $guruData
                );

                // Sync user
                $guru = $this->guruModel->find($id);

                if ($guru && $guru['user_id']) {

                    $userUp = [
                        'nama'       => $nama,
                        'email'      => $email,
                        'telepon'    => $telepon,
                        'updated_at' => date('Y-m-d H:i:s')
                    ];

                    if ($fotoName) {
                        $userUp['foto'] = $fotoName;
                    }

                    $this->userModel->update($guru['user_id'], $userUp);
                }

                // Update mapel multiple
                $this->guruMapelModel->deleteByGuru($id); // hapus semua mapel lama

                foreach ($mapelIds as $m) {
                    $this->guruMapelModel->insert([
                        'guru_id'  => $id,
                        'mapel_id' => $m
                    ]);
                }
            }

            return $this->response->setJSON(['success' => true]);
        } catch (\Throwable $e) {

            return $this->response->setJSON([
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine()
            ]);
        }
    }

    // ==============================
    // 🗑 DELETE
    // ==============================
    public function delete($id)
    {
        $guru = $this->guruModel->find($id);

        if ($guru && $guru['user_id']) {
            $this->userModel->delete($guru['user_id']);
        }

        $this->guruModel->delete($id);
        // 🔥 LOG DELETE
        logCrud(
            'guru',
            'delete',
            "Hapus guru ID: $id beserta akun user terkait",
            $guru ?? null
        );

        return $this->response->setJSON(['success' => true]);
    }

    // ==============================
    // 🔍 GET DETAIL
    // ==============================
    public function get($id)
    {
        $guru = $this->guruModel->find($id);

        if (!$guru) {
            return $this->response->setJSON(['error' => 'Guru tidak ditemukan']);
        }

        $mapelList = $this->guruMapelModel
            ->select('mapel_id')
            ->where('guru_id', $id)
            ->findAll();

        $guru['mapel_ids'] = array_column($mapelList, 'mapel_id');

        return $this->response->setJSON($guru);
    }

    // ==============================
    // 📚 GET MAPEL DROPDOWN
    // ==============================
    public function getMapel()
    {
        $mapel = $this->mapelModel->select('id, nama_mapel')->findAll();
        return $this->response->setJSON($mapel);
    }
}
