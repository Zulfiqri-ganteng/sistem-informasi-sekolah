<?php

namespace App\Controllers;

use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\GuruModel;
use App\Models\TabunganModel;
use App\Models\UserModel;

class GuruDashboard extends BaseController
{
    protected $siswaModel;
    protected $kelasModel;
    protected $guruModel;
    protected $tabunganModel;
    protected $userModel;
    protected $db;

    public function __construct()
    {
        $this->db            = \Config\Database::connect();
        $this->siswaModel    = new SiswaModel();
        $this->kelasModel    = new KelasModel();
        $this->guruModel     = new GuruModel();
        $this->tabunganModel = new TabunganModel();
        $this->userModel     = new UserModel();
    }

    // ================================
    // DASHBOARD GURU
    // ================================
    public function index()
    {
        $session = session();
        $userId = $session->get('user_id');

        // Ambil data guru
        $guru = $this->guruModel->where('user_id', $userId)->first();
        if (!$guru) {
            return redirect()->to('/login')->with('error', 'Akun guru tidak ditemukan.');
        }

        // Ambil kelas wali
        $kelas = $this->kelasModel->where('guru_id', $guru['id'])->first();
        $kelasId = $kelas['id'] ?? null;

        // Ambil siswa dalam kelas wali
        $siswa = [];
        $totalSaldo = 0;

        if ($kelasId) {
            $siswa = $this->siswaModel->where('kelas', $kelas['nama_kelas'])->findAll();

            $saldoQuery = $this->db->query("
                SELECT SUM(ts.saldo) AS total_saldo
                FROM tabungan_saldo ts
                JOIN siswa s ON s.id = ts.siswa_id
                WHERE s.kelas = ?
            ", [$kelas['nama_kelas']])->getRowArray();

            $totalSaldo = $saldoQuery['total_saldo'] ?? 0;
        }

        return view('guru/dashboard', [
            'title'       => 'Dashboard Guru',
            'guru'        => $guru,
            'kelas'       => $kelas,
            'siswa'       => $siswa,
            'total_saldo' => $totalSaldo
        ]);
    }

    // ================================
    // DATA UNTUK GRAFIK
    // ================================
    public function chartData()
    {
        $session = session();
        $userId = $session->get('user_id');

        $guru = $this->guruModel->where('user_id', $userId)->first();
        if (!$guru) return $this->response->setJSON(['labels' => [], 'values' => []]);

        $kelas = $this->kelasModel->where('guru_id', $guru['id'])->first();
        if (!$kelas) return $this->response->setJSON(['labels' => [], 'values' => []]);

        $query = $this->db->query("
            SELECT s.nama, ts.saldo
            FROM tabungan_saldo ts
            JOIN siswa s ON s.id = ts.siswa_id
            WHERE s.kelas = ?
            ORDER BY s.nama ASC
        ", [$kelas['nama_kelas']])->getResultArray();

        return $this->response->setJSON([
            'labels' => array_column($query, 'nama'),
            'values' => array_column($query, 'saldo')
        ]);
    }

    // ================================
    // LIST KELAS SAYA (WALI KELAS)
    // ================================
    public function kelas()
    {
        $userId = session()->get('user_id');

        // Cari guru berdasarkan user ID
        $guru = $this->guruModel->where('user_id', $userId)->first();
        if (!$guru) {
            return view('guru/kelas', ['kelas' => []]);
        }

        // Cari semua kelas yang diampu
        $kelas = $this->kelasModel->where('guru_id', $guru['id'])->findAll();

        return view('guru/kelas', [
            'title' => 'Kelas Saya',
            'kelas' => $kelas
        ]);
    }

    // ================================
    // LIST SISWA DALAM KELAS
    // ================================
    public function siswa($kelas_id)
    {
        $kelas = $this->kelasModel->find($kelas_id);
        if (!$kelas) {
            return redirect()->back()->with('error', 'Kelas tidak ditemukan.');
        }

        $siswa = $this->siswaModel
            ->where('kelas', $kelas['nama_kelas'])
            ->findAll();

        return view('guru/siswa', [
            'title' => 'Siswa dalam Kelas',
            'kelas' => $kelas,
            'siswa' => $siswa
        ]);
    }

    // ================================
    // DETAIL SISWA
    // ================================
    public function siswaGet($id)
    {
        $siswa = $this->siswaModel->find($id);

        return view('guru/siswa_detail', [
            'title' => 'Detail Siswa',
            'siswa' => $siswa
        ]);
    }

    // ================================
    // PROFIL GURU
    // ================================
    public function profil()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        return view('guru/profil', [
            'title' => 'Profil Saya',
            'user'  => $user
        ]);
    }

    public function updateProfil()
    {
        $userId = session()->get('user_id');

        $data = [
            'nama'  => $this->request->getPost('nama'),
            'email' => $this->request->getPost('email'),
        ];

        $this->userModel->update($userId, $data);

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    // ================================
    // GANTI PASSWORD GURU
    // ================================
    public function gantiPassword()
    {
        return view('guru/ganti_password', ['title' => 'Ganti Password']);
    }

    public function updatePassword()
    {
        $userId = session()->get('user_id');
        $password = $this->request->getPost('password');

        $this->userModel->update($userId, [
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        return redirect()->to('guru/profil')->with('success', 'Password berhasil diganti!');
    }
}
