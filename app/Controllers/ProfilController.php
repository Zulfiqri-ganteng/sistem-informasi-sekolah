<?php

namespace App\Controllers\Guru;

use App\Controllers\BaseController;
use App\Models\GuruModel;
use App\Models\UserModel;
use App\Models\GuruMapelModel;

class ProfilController extends BaseController
{
    protected $guruModel;
    protected $userModel;
    protected $mapelPivot;

    public function __construct()
    {
        $this->guruModel   = new GuruModel();
        $this->userModel   = new UserModel();
        $this->mapelPivot  = new GuruMapelModel();
    }

    public function index()
    {
        // Ambil user login
        $userId = session()->get('user_id');

        // Ambil user
        $user = $this->userModel->find($userId);

        // Ambil data guru
        $guru = $this->guruModel->where('user_id', $userId)->first();

        // Ambil mapel multiple
        $mapel = $this->mapelPivot->getMapelByGuru($guru['id']);

        return view('guru/profil', [
            'guru'  => $guru,
            'user'  => $user,
            'mapel' => $mapel
        ]);
    }

    public function updateProfil()
    {
        $userId = session()->get('user_id');
        $user   = $this->userModel->find($userId);
        $guru   = $this->guruModel->where('user_id', $userId)->first();

        // --- Update foto
        $fotoName = $guru['foto'];
        $fotoFile = $this->request->getFile('foto');

        if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
            $fotoName = time() . '_' . $fotoFile->getRandomName();
            $fotoFile->move(FCPATH . 'uploads/guru', $fotoName);
        }

        // --- Update user (akun login)
        $this->userModel->update($userId, [
            'nama'    => $this->request->getPost('nama'),
            'email'   => $this->request->getPost('email'),
            'telepon' => $this->request->getPost('telepon'),
            'foto'    => $fotoName
        ]);

        // --- Update guru (biodata)
        $this->guruModel->update($guru['id'], [
            'nama'    => $this->request->getPost('nama'),
            'alamat'  => $this->request->getPost('alamat'),
            'foto'    => $fotoName,
        ]);

        // --- Ganti Password (opsional)
        $old  = $this->request->getPost('old_password');
        $new  = $this->request->getPost('new_password');
        $conf = $this->request->getPost('confirm_password');

        if ($old && $new && $conf) {
            if (!password_verify($old, $user['password'])) {
                return redirect()->back()->with('error', 'Password lama salah!');
            }
            if ($new !== $conf) {
                return redirect()->back()->with('error', 'Konfirmasi password tidak sama!');
            }

            $this->userModel->update($userId, [
                'password' => password_hash($new, PASSWORD_DEFAULT)
            ]);
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
