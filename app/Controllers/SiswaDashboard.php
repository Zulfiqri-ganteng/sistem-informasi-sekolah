<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Database;
use Config\Services;

class SiswaDashboard extends BaseController
{
    protected $db;
    protected $validation;
    protected $image;
    protected $maxUpload = 2 * 1024 * 1024; // 2MB

    public function __construct()
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->image = Services::image();
        helper(['url', 'form']);
    }

    // ===============================
    // DASHBOARD SISWA
    // ===============================
    public function dashboard()
    {
        $siswaId = session()->get('siswa_id');

        if (session()->get('role') !== 'siswa') {
            return redirect()->to('/dashboard');
        }

        $saldo = $this->db->table('tabungan_saldo')->where('siswa_id', $siswaId)->select('saldo')->get()->getRow('saldo') ?? 0;

        $total_setor = $this->db->table('transaksi')->where('siswa_id', $siswaId)->where('tipe', 'setor')->selectSum('jumlah')->get()->getRow('jumlah') ?? 0;
        $total_tarik = $this->db->table('transaksi')->where('siswa_id', $siswaId)->where('tipe', 'tarik')->selectSum('jumlah')->get()->getRow('jumlah') ?? 0;

        $chartData = array_fill(0, 12, 0);
        $grafik = $this->db->query("
            SELECT MONTH(created_at) AS bulan, SUM(jumlah) AS total
            FROM transaksi
            WHERE siswa_id = ? AND tipe = 'setor'
            GROUP BY MONTH(created_at)
        ", [$siswaId])->getResultArray();

        foreach ($grafik as $row) {
            $chartData[$row['bulan'] - 1] = (float)$row['total'];
        }

        $transaksi = $this->db->table('transaksi')
            ->where('siswa_id', $siswaId)
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get()->getResultArray();

        $data = [
            'title' => 'Dashboard Siswa',
            'saldo' => $saldo,
            'total_setor' => $total_setor,
            'total_tarik' => $total_tarik,
            'chartData' => $chartData,
            'transaksi' => $transaksi,
        ];

        return view('siswa/dashboard', $data);
    }

    // ===============================
    // PROFIL SISWA
    // ===============================
    public function profil()
    {
        $siswaId = session()->get('siswa_id');
        $userId = session()->get('user_id');

        $siswa = $this->db->table('siswa')->where('id', $siswaId)->get()->getRowArray();
        $user = $this->db->table('users')->where('id', $userId)->get()->getRowArray();

        if (!$siswa) {
            return redirect()->to('/siswa/dashboard')->with('error', 'Data siswa tidak ditemukan');
        }

        $data = [
            'title' => 'Profil Siswa',
            'siswa' => $siswa,
            'user'  => $user,
        ];

        return view('siswa/profil', $data);
    }

    // ==================================
    // UPDATE PROFIL SISWA (FINAL VERSION)
    // ==================================
    public function updateProfil()
    {
        $siswaId = session()->get('siswa_id');
        $userId  = session()->get('user_id');
        $db = $this->db;

        $nama     = $this->request->getPost('nama');
        $alamat   = $this->request->getPost('alamat');
        $telepon  = $this->request->getPost('telepon');
        $username = $this->request->getPost('username');
        $old      = trim($this->request->getPost('old_password'));
        $new      = trim($this->request->getPost('new_password'));
        $confirm  = trim($this->request->getPost('confirm_password'));

        // Folder upload
        $path = FCPATH . 'uploads/siswa/';
        if (!is_dir($path)) mkdir($path, 0777, true);

        $siswa = $db->table('siswa')->where('id', $siswaId)->get()->getRowArray();
        $updateFoto = $siswa['foto'] ?? 'default.png';

        // Proses foto
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->getError() !== 4) {
            if (!$foto->isValid()) {
                return redirect()->back()->with('error', 'File foto tidak valid: ' . $foto->getErrorString());
            }
            if ($foto->getSize() > $this->maxUpload) {
                return redirect()->back()->with('error', 'Ukuran foto maksimal 2MB.');
            }

            $newName = $foto->getRandomName();
            if (!$foto->move($path, $newName)) {
                return redirect()->back()->with('error', 'Gagal menyimpan foto ke folder.');
            }

            try {
                $this->image->withFile($path . $newName)->resize(600, 600, true, 'auto')->save($path . $newName);
            } catch (\Throwable $e) {
            }

            if (!empty($updateFoto) && $updateFoto !== 'default.png' && file_exists($path . $updateFoto)) {
                @unlink($path . $updateFoto);
            }

            $updateFoto = $newName;
            session()->set('foto', $newName);
        }

        // Update tabel siswa
        $db->table('siswa')->where('id', $siswaId)->update([
            'nama'       => $nama,
            'alamat'     => $alamat,
            'telepon'    => $telepon,
            'foto'       => $updateFoto,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        // Update username
        $db->table('users')->where('id', $userId)->update(['username' => $username]);
        session()->set('nama', $nama);

        // Update password jika diisi
        if (!empty($old) && !empty($new) && !empty($confirm)) {
            $user = $db->table('users')->where('id', $userId)->get()->getRowArray();
            if (!$user) {
                return redirect()->back()->with('error', 'User tidak ditemukan.');
            }

            $stored = $user['password'];
            $isValid = password_get_info($stored)['algo'] ? password_verify($old, $stored) : ($old === $stored);

            if (!$isValid) {
                return redirect()->back()->with('error', 'Password lama salah.');
            }

            if ($new !== $confirm) {
                return redirect()->back()->with('error', 'Konfirmasi password tidak sama.');
            }

            $hash = password_hash($new, PASSWORD_DEFAULT);
            $db->table('users')->where('id', $userId)->update([
                'password'   => $hash,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }

        // Ambil ulang data baru
        $updatedSiswa = $db->table('siswa')->where('id', $siswaId)->get()->getRowArray();
        session()->set('foto', $updatedSiswa['foto']);

        return redirect()->to('/siswa/profil')->with('success', 'Profil berhasil diperbarui.');
    }

    // ==================================
    // LOGOUT
    // ==================================
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Anda telah logout.');
    }
}
