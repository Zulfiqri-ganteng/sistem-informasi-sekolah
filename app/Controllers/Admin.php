<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Database;
use Config\Services;

class Admin extends BaseController
{
    protected $db;
    protected $validation;
    protected $imageService;
    protected $maxUploadSize = 2 * 1024 * 1024; // 2MB

    public function __construct()
    {
        $this->db = Database::connect();
        $this->validation = Services::validation();
        $this->imageService = Services::image();
        helper(['url', 'form', 'text']);
    }

    // =======================
    // PROFIL ADMIN
    // =======================
    public function profil()
    {
        $userId = session()->get('user_id');
        $user = $this->db->table('users')->where('id', $userId)->get()->getRowArray();

        if (!$user) {
            return redirect()->to('/dashboard')->with('error', 'Data admin tidak ditemukan.');
        }

        $data = [
            'title' => 'Profil Admin',
            'admin' => $user,
            'user'  => $user
        ];

        return view('admin/profil', $data);
    }

    // =======================
    // UPDATE PROFIL + PASSWORD
    // =======================
    public function updateProfil()
    {
        $userId = session()->get('user_id');
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Session berakhir, silakan login ulang.');
        }

        // Ambil input dari form
        $nama     = $this->request->getPost('nama');
        $email    = $this->request->getPost('email');
        $telepon  = $this->request->getPost('telepon');
        $username = $this->request->getPost('username');
        $foto     = $this->request->getFile('foto');

        $oldPass  = $this->request->getPost('old_password');
        $newPass  = $this->request->getPost('new_password');
        $confirm  = $this->request->getPost('confirm_password');

        // Ambil data user lama
        $user = $this->db->table('users')->where('id', $userId)->get()->getRowArray();
        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        // --- Upload Foto Baru (jika ada) ---
        $fotoName = $user['foto'] ?? null;
        $path = FCPATH . 'uploads/admin/';

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            if ($foto->getSize() > $this->maxUploadSize) {
                return redirect()->back()->with('error', 'Ukuran foto maksimal 2MB.');
            }

            $newName = $foto->getRandomName();
            $foto->move($path, $newName);

            // Resize otomatis (agar tidak terlalu besar)
            $this->imageService
                ->withFile($path . $newName)
                ->resize(600, 600, true, 'auto')
                ->save($path . $newName);

            // Hapus foto lama (kecuali default)
            if ($fotoName && file_exists($path . $fotoName)) {
                unlink($path . $fotoName);
            }

            $fotoName = $newName;
        }

        // --- Update profil umum ---
        $updateData = [
            'username' => $username,
            'nama'     => $nama,
            'email'    => $email,
            'telepon'  => $telepon,
            'updated_at' => date('Y-m-d H:i:s'),  // <-- WAJIB!
        ];
        if ($fotoName) {
            $updateData['foto'] = $fotoName;
        }

        $this->db->table('users')->where('id', $userId)->update($updateData);

        // --- Update Password (jika diisi semua) ---
        if (!empty($oldPass) && !empty($newPass) && !empty($confirm)) {

            if ($newPass !== $confirm) {
                return redirect()->back()->with('error', 'Konfirmasi password tidak cocok.');
            }

            $stored = $user['password'];
            $validOld = false;

            // Dukung hash modern & legacy plain text
            if (password_get_info($stored)['algo']) {
                $validOld = password_verify($oldPass, $stored);
            } else {
                $validOld = ($oldPass === $stored);
            }

            if (!$validOld) {
                return redirect()->back()->with('error', 'Password lama salah.');
            }

            $hash = password_hash($newPass, PASSWORD_DEFAULT);
            $this->db->table('users')->where('id', $userId)->update(['password' => $hash]);
        }

        // --- Update session agar navbar ikut berubah ---
        session()->set([
            'nama' => $nama,
            'foto' => $fotoName,
        ]);

        return redirect()->to('/admin/profil')->with('success', 'Profil berhasil diperbarui.');
    }




    // =======================
    // JSON RESPONSE HELPER
    // =======================
    protected function respondJSON($ok, $msg, $data = [])
    {
        $res = ['success' => (bool)$ok, 'message' => $msg];
        if (!empty($data)) $res['data'] = $data;
        return $this->response->setJSON($res);
    }
}
