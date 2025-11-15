<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use CodeIgniter\Controller;

class Siswa extends BaseController
{
    protected $siswaModel;

    public function __construct()
    {
        $this->siswaModel = new SiswaModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        $data['title'] = 'Data Siswa';
        return view('siswa/index', $data);
    }

    public function list()
    {
        if ($this->request->isAJAX()) {
            $db = \Config\Database::connect();

            // Join siswa dan users biar bisa ambil email
            $builder = $db->table('siswa s');
            $builder->select('
            s.id,
            s.nisn,
            s.nama,
            s.kelas,
            s.jurusan,
            s.telepon,
            s.foto,
            u.email
        ');
            $builder->join('users u', 'u.siswa_id = s.id', 'left');
            $builder->orderBy('s.id', 'DESC');

            $query = $builder->get();
            $data = $query->getResultArray();

            return $this->response->setJSON(['data' => $data]);
        }

        // Kalau bukan AJAX request, tampilkan error 404
        throw new \CodeIgniter\Exceptions\PageNotFoundException();
    }


    public function get($id)
    {
        $data = $this->siswaModel->find($id);
        return $this->response->setJSON($data);
    }

    public function save()
    {
        $db = \Config\Database::connect();
        $id = $this->request->getPost('id');
        $file = $this->request->getFile('foto');
        $fotoName = null;

        // Upload foto jika ada
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $fotoName = $file->getRandomName();
            $file->move('uploads/siswa', $fotoName);
        }

        // Data siswa
        $data = [
            'nisn'     => $this->request->getPost('nisn'),
            'nama'     => $this->request->getPost('nama'),
            'kelas'    => $this->request->getPost('kelas'),
            'jurusan'  => $this->request->getPost('jurusan'),
            'alamat'   => $this->request->getPost('alamat'),
            'telepon'  => $this->request->getPost('telepon'),
        ];

        if ($fotoName) {
            $data['foto'] = $fotoName;
        }

        // INSERT baru
        if (empty($id)) {
            $this->siswaModel->insert($data);
            $siswa_id = $db->insertID();

            // Ambil email dari input form
            $email = $this->request->getPost('email');

            // Username & password default = NISN
            $username = $data['nisn'];
            $password = $data['nisn'];

            // Simpan akun login siswa otomatis
            $db->table('users')->insert([
                'username'   => $username,
                'password'   => password_hash($password, PASSWORD_DEFAULT),
                'role'       => 'siswa',
                'siswa_id'   => $siswa_id,
                'email'      => $email ?? null, // <- biar tidak error kalau kosong
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return $this->response->setJSON([
                'success'  => true,
                'message'  => 'Siswa berhasil ditambahkan.',
                'username' => $username,
                'password' => $password
            ]);
        }

        // UPDATE siswa
        if (!$fotoName) {
            unset($data['foto']);
        }

        $this->siswaModel->update($id, $data);
        return $this->response->setJSON(['success' => true]);
    }




    public function delete($id)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID siswa tidak ditemukan.']);
        }

        $db = \Config\Database::connect();

        // Ambil data siswa untuk mendapatkan nama file foto
        $data = $this->siswaModel->find($id);

        if ($data && !empty($data['foto'])) {
            $path = FCPATH . 'uploads/siswa/' . $data['foto'];
            if (file_exists($path)) {
                unlink($path); // hapus foto
            }
        }

        // Mulai transaksi
        $db->transStart();

        // 🔥 Hapus saldo siswa di tabel tabungan_saldo
        $db->table('tabungan_saldo')->where('siswa_id', $id)->delete();

        // 🔥 Hapus data tabungan utama
        $db->table('tabungan')->where('siswa_id', $id)->delete();

        // 🔥 Hapus semua transaksi siswa tersebut
        $db->table('transaksi')->where('siswa_id', $id)->delete();

        // 🔥 Hapus juga user login siswa (jika ada)
        $db->table('users')->where('siswa_id', $id)->delete();

        // 🔥 Hapus data siswa dari tabel utama
        $this->siswaModel->delete($id);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus data siswa.']);
        }

        return $this->response->setJSON(['success' => true, 'message' => 'Data siswa dan seluruh data terkait berhasil dihapus.']);
    }


    // 🔍 Method: Ambil opsi kelas dan jurusan untuk form
    public function options()
    {
        $db = \Config\Database::connect();

        // Ambil data kelas
        $kelas = $db->table('kelas')
            ->select('id, nama_kelas')
            ->orderBy('nama_kelas', 'ASC')
            ->get()
            ->getResultArray();

        // Ambil data jurusan
        $jurusan = $db->table('jurusan')
            ->select('id, nama_jurusan')
            ->orderBy('nama_jurusan', 'ASC')
            ->get()
            ->getResultArray();

        // Balikkan dalam format JSON
        return $this->response->setJSON([
            'kelas' => $kelas,
            'jurusan' => $jurusan
        ]);
    }

    // 🔍 Method: Pencarian Siswa untuk Select2 (untuk fitur tabungan)
    public function search()
    {
        $q = $this->request->getGet('q'); // Ambil parameter pencarian

        // Jika tidak ada query, kembalikan array kosong
        if (!$q) {
            return $this->response->setJSON(['data' => []]);
        }

        // Query ke database dengan like pada nama dan nisn
        $builder = $this->db->table('siswa');
        $builder->select('id, nama, nisn, kelas')
            ->like('nama', $q) // Cari berdasarkan nama
            ->orLike('nisn', $q) // Atau NISN
            ->limit(10); // Batasi hasil maksimal 10

        $data = $builder->get()->getResultArray();

        // Format ulang agar cocok dengan Select2
        $results = [];
        foreach ($data as $row) {
            $results[] = [
                'id' => $row['id'],
                'text' => $row['nama'] . ' (NISN: ' . $row['nisn'] . ') - ' . $row['kelas']
            ];
        }

        return $this->response->setJSON(['data' => $results]);
    }
    public function dropdown()
    {
        $data = $this->siswaModel
            ->select('id, nama, kelas')
            ->orderBy('nama', 'ASC')
            ->findAll();

        return $this->response->setJSON($data);
    }
}
