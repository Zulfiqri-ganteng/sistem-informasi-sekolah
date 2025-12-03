<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\SiswaModel;
use App\Models\GuruModel;

class Users extends BaseController
{
    protected $userModel;
    protected $siswaModel;
    protected $guruModel;

    public function __construct()
    {
        $this->userModel  = new UserModel();
        $this->siswaModel = new SiswaModel();
        $this->guruModel  = new GuruModel();
    }

    // ======================================================
    // 📌 LIST DATA USER
    // ======================================================
    public function index()
    {
        $users = $this->userModel
            ->select("
            users.*,
            siswa.nama AS nama_siswa,
            guru.nama AS nama_guru
        ")

            // JOIN SISWA PAKAI user_id
            ->join('siswa', 'siswa.user_id = users.id', 'left')

            // JOIN GURU PAKAI user_id
            ->join('guru', 'guru.user_id = users.id', 'left')

            ->orderBy('users.id', 'ASC')
            ->findAll();

        return view('users/index', [
            'title' => 'Manajemen User',
            'users' => $users
        ]);
    }



    // ======================================================
    // 🔐 RESET PASSWORD (kembali ke username)
    // ======================================================
    public function resetPassword($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $default = $user['username'];
        $hash = password_hash($default, PASSWORD_DEFAULT);

        $this->userModel->update($id, ['password' => $hash]);

        return redirect()->back()->with('success', 'Password berhasil direset ke default (username).');
    }

    // ======================================================
    // 🔄 TOGGLE AKTIF / NONAKTIF
    // ======================================================
    public function toggleStatus($id)
    {
        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $new = ($user['status'] == 1) ? 0 : 1;

        $this->userModel->update($id, ['status' => $new]);

        return redirect()->back()->with(
            'success',
            'Status user berhasil diubah menjadi ' . ($new ? 'Aktif' : 'Nonaktif') . '.'
        );
    }
}
