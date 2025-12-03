<?php

namespace App\Controllers;

helper('activity');

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

    // ==============================
    // LIST DATA + EMAIL DARI USERS
    // ==============================
    public function list()
    {
        if ($this->request->isAJAX()) {

            $db = \Config\Database::connect();

            $builder = $db->table('siswa s');
            $builder->select('
                s.id,
                s.nisn,
                s.nama,
                s.jenis_kelamin,
                s.kelas,
                s.jurusan,
                s.telepon,
                s.foto,
                u.email
            ');
            $builder->join('users u', 'u.siswa_id = s.id', 'left');
            $builder->orderBy('s.id', 'DESC');

            $data = $builder->get()->getResultArray();

            return $this->response->setJSON(['data' => $data]);
        }

        throw new \CodeIgniter\Exceptions\PageNotFoundException();
    }

    // ==============================
    // GET DETAIL INCLUDING EMAIL
    // ==============================
    public function get($id)
    {
        $db = \Config\Database::connect();

        $builder = $db->table('siswa s');
        $builder->select('s.*, u.email');
        $builder->join('users u', 'u.siswa_id = s.id', 'left');
        $builder->where('s.id', $id);

        $data = $builder->get()->getRowArray();

        return $this->response->setJSON($data);
    }

    // ==============================
    // INSERT + UPDATE SISWA
    // ==============================
    public function save()
    {
        $db = \Config\Database::connect();
        $id = $this->request->getPost('id');
        $email = $this->request->getPost('email');

        $file = $this->request->getFile('foto');
        $fotoName = null;

        // Upload foto
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
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'), // ⭐ WAJIB
        ];

        if ($fotoName) {
            $data['foto'] = $fotoName;
        }

        // ==========================================
        // INSERT BARU
        // ==========================================
        if (empty($id)) {

            $this->siswaModel->insert($data);
            $siswa_id = $db->insertID();

            // Username & password default = NISN
            $username = $data['nisn'];
            $password = $data['nisn'];

            // Insert users
            $db->table('users')->insert([
                'username'   => $username,
                'password'   => password_hash($password, PASSWORD_DEFAULT),
                'role'       => 'siswa',
                'siswa_id'   => $siswa_id,
                'email'      => $email,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            return $this->response->setJSON([
                'success'  => true,
                'message'  => 'Siswa berhasil ditambahkan.',
                'username' => $username,
                'password' => $password
            ]);
        }

        // ==========================================
        // UPDATE SISWA + UPDATE EMAIL USER
        // ==========================================
        if (!$fotoName) {
            unset($data['foto']);
        }

        // Update tabel siswa
        $this->siswaModel->update($id, $data);

        // Update email user
        $db->table('users')->where('siswa_id', $id)->update([
            'email' => $email
        ]);

        return $this->response->setJSON(['success' => true]);
    }

    // ==============================
    // DELETE DATA SISWA LENGKAP
    // ==============================
    public function delete($id)
    {
        $db = \Config\Database::connect();
        $data = $this->siswaModel->find($id);

        if ($data && !empty($data['foto'])) {
            $path = FCPATH . 'uploads/siswa/' . $data['foto'];
            if (file_exists($path)) unlink($path);
        }

        $db->transStart();

        $db->table('tabungan_saldo')->where('siswa_id', $id)->delete();
        $db->table('tabungan')->where('siswa_id', $id)->delete();
        $db->table('transaksi')->where('siswa_id', $id)->delete();
        $db->table('users')->where('siswa_id', $id)->delete();

        $this->siswaModel->delete($id);
        $db->transComplete();

        return $this->response->setJSON(['success' => true]);
    }

    // ==============================
    // OPTIONS KELAS & JURUSAN
    // ==============================
    public function options()
    {
        $db = \Config\Database::connect();

        return $this->response->setJSON([
            'kelas' => $db->table('kelas')->select('id, nama_kelas')->orderBy('nama_kelas')->get()->getResultArray(),
            'jurusan' => $db->table('jurusan')->select('id, nama_jurusan')->orderBy('nama_jurusan')->get()->getResultArray()
        ]);
    }

    // ==============================
    // SELECT2 PENCARIAN SISWA
    // ==============================
    public function search()
    {
        $q = $this->request->getGet('q');
        if (!$q) return $this->response->setJSON(['data' => []]);

        $builder = $this->db->table('siswa');
        $builder->select('id, nama, nisn, kelas')
            ->like('nama', $q)
            ->orLike('nisn', $q)
            ->limit(10);

        $data = $builder->get()->getResultArray();

        $results = array_map(fn($r) => [
            'id' => $r['id'],
            'text' => "{$r['nama']} (NISN: {$r['nisn']}) - {$r['kelas']}"
        ], $data);

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
