<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\JurusanModel;
use Config\Services;
use CodeIgniter\I18n\Time;

class Auth extends BaseController
{
    protected $userModel;
    protected $siswaModel;
    protected $kelasModel;
    protected $jurusanModel;
    protected $db;

    public function __construct()
    {
        $this->userModel   = new UserModel();
        $this->siswaModel  = new SiswaModel();
        $this->kelasModel  = new KelasModel();
        $this->jurusanModel = new JurusanModel();
        $this->db = \Config\Database::connect();

        helper(['url', 'form']);
    }

    // --------------------------------------------------------
    // 🔐 Halaman Login
    // --------------------------------------------------------
    public function login()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole(session()->get('role'));
        }

        return view('auth/login', [
            'title' => 'Login Sistem Tabungan'
        ]);
    }

    // --------------------------------------------------------
    // 🔐 Proses Login
    // --------------------------------------------------------
    public function process()
    {
        $username = trim($this->request->getPost('username'));
        $password = trim($this->request->getPost('password'));

        // Ambil user
        $user = $this->userModel
            ->select('users.*, siswa.nama, siswa.kelas, siswa.jurusan, siswa.foto')
            ->join('siswa', 'siswa.id = users.siswa_id', 'left')
            ->where('users.username', $username)
            ->first();

        // Username tidak ditemukan
        if (!$user) {
            return redirect()->back()->with('error', 'Username tidak ditemukan.');
        }

        // ❗ BLOKIR USER NON-AKTIF
        if (isset($user['status']) && $user['status'] == 0) {
            return redirect()->back()->with('error', 'Akun Anda sedang dinonaktifkan!');
        }

        // Verifikasi password
        $storedPassword = $user['password'];

        $isValid = password_get_info($storedPassword)['algo']
            ? password_verify($password, $storedPassword)
            : $password === $storedPassword;

        if (!$isValid) {
            return redirect()->back()->with('error', 'Password salah.');
        }

        // Auto hash jika masih plaintext
        if (!password_get_info($storedPassword)['algo']) {
            $this->userModel->update($user['id'], [
                'password' => password_hash($storedPassword, PASSWORD_DEFAULT)
            ]);
        }

        // Buat session
        $sessionData = [
            'id'         => $user['id'],            // WAJIB UNTUK SMART LOGGER
            'user_id'    => $user['id'],
            'username'   => $user['username'],
            'role'       => $user['role'],
            'siswa_id'   => $user['siswa_id'],
            'nama'       => $user['nama'] ?? ucfirst($user['username']),
            'kelas'      => $user['kelas'] ?? null,
            'jurusan'    => $user['jurusan'] ?? null,
            'foto'       => $user['foto'] ?? 'default.png',
            'isLoggedIn' => true
        ];

        session()->set($sessionData);

        // --------------------------------------------------------
        // ✉️ Email “Login Berhasil” (jika ada email)
        // --------------------------------------------------------
        if (!empty($user['email'])) {
            $email = Services::email();
            $email->setTo($user['email']);
            $email->setSubject('Login Berhasil - Sistem Informasi Sekolah');
            $email->setMessage("
                <h3 style='color:#004aad'>Login Berhasil</h3>
                <p>Halo <b>{$user['nama']}</b>, Anda berhasil login pada:</p>
                <p><b>" . date('d M Y H:i:s') . "</b></p>
                <p>IP Address: " . $_SERVER['REMOTE_ADDR'] . "</p>
            ");
            @$email->send();
        }
        // // LOG AKTIVITAS
        // helper('activity');
        // activity_log([
        //     'user_id' => $user['id'],
        //     'role' => $user['role'],
        //     'module' => 'auth',
        //     'action' => 'login',
        //     'detail' => 'Login sukses ke sistem'
        // ]);
        // Redirect sesuai role
        return $this->redirectByRole($user['role'])
            ->with('success', 'Selamat datang, ' . ($user['nama'] ?? $user['username']) . '!');
    }

    // --------------------------------------------------------
    // 🚪 Logout
    // --------------------------------------------------------
    public function logout()
    {
        // helper('activity');
        // activity_log([
        //     'user_id' => session()->get('user_id'),
        //     'role'    => session()->get('role'),
        //     'module'  => 'auth',
        //     'action'  => 'logout',
        //     'detail'  => 'Logout dari sistem'
        // ]);

        // hancurkan session SETELAH log terekam
        session()->destroy();

        return redirect()->to('/login')->with('success', 'Anda telah logout.');
    }

    // --------------------------------------------------------
    // 🔁 Redirect Otomatis Berdasarkan Role
    // --------------------------------------------------------
    private function redirectByRole($role)
    {
        if ($role === 'admin') return redirect()->to('/dashboard');
        if ($role === 'guru')  return redirect()->to('/guru/dashboard');
        if ($role === 'siswa') return redirect()->to('/siswa/dashboard');

        return redirect()->to('/login');
    }

    // --------------------------------------------------------
    // 📝 Registrasi Siswa
    // --------------------------------------------------------
    public function registerSiswa()
    {
        return view('auth/register_siswa', [
            'kelas'   => $this->kelasModel->findAll(),
            'jurusan' => $this->jurusanModel->findAll(),
            'title'   => 'Registrasi Siswa | Sistem Informasi Sekolah'
        ]);
    }

    public function registerSubmit()
    {
        $nisn     = trim($this->request->getPost('nisn'));
        $nama     = trim($this->request->getPost('nama'));
        $kelas    = trim($this->request->getPost('kelas'));
        $jurusan  = trim($this->request->getPost('jurusan'));
        $email    = trim($this->request->getPost('email'));

        if (empty($nisn) || empty($nama) || empty($kelas) || empty($jurusan) || empty($email)) {
            return redirect()->back()->with('error', 'Semua field wajib diisi!');
        }

        if ($this->siswaModel->where('nisn', $nisn)->first()) {
            return redirect()->back()->with('error', 'NISN sudah terdaftar!');
        }

        if ($this->userModel->where('username', $nisn)->orWhere('email', $email)->first()) {
            return redirect()->back()->with('error', 'Akun dengan NISN atau Email ini sudah ada!');
        }

        // Simpan siswa
        $this->siswaModel->insert([
            'nisn'     => $nisn,
            'nama'     => $nama,
            'kelas'    => $kelas,
            'jurusan'  => $jurusan,
            'foto'     => 'default.png'
        ]);

        $siswaId = $this->siswaModel->getInsertID();

        // Simpan user
        $this->userModel->insert([
            'username' => $nisn,
            'password' => password_hash($nisn, PASSWORD_DEFAULT),
            'role'     => 'siswa',
            'siswa_id' => $siswaId,
            'email'    => $email,
            'status'   => 1
        ]);

        return redirect()->to('/login')
            ->with('success', 'Registrasi berhasil! Silakan login menggunakan NISN Anda.');
    }

    // --------------------------------------------------------
    // 🔑 Lupa Password (mengirim email reset)
    // --------------------------------------------------------
    public function forgotPassword()
    {
        return view('auth/forgot_password');
    }

    public function sendResetLink()
    {
        $email = $this->request->getPost('email');
        $user = $this->userModel->where('email', $email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Email tidak ditemukan.');
        }

        // Generate token
        $token = bin2hex(random_bytes(32));
        $expires = Time::now()->addMinutes(30);

        // Simpan ke database
        $this->db->table('password_resets')->insert([
            'email'      => $email,
            'token'      => $token,
            'expires_at' => $expires->toDateTimeString()
        ]);

        // Link reset
        $link = base_url("reset-password/$token");

        // ===== 🔥 PANGGIL TEMPLATE EMAIL BARU =====
        $content = view('auth/email_reset_password', ['link' => $link]);

        $html = view('auth/email_template', [
            'subject' => 'Reset Password - Sistem Informasi Sekolah',
            'content' => $content
        ]);

        // Kirim email
        $mail = Services::email();
        $mail->setTo($email);
        $mail->setSubject('Reset Password - Sistem Informasi Sekolah');
        $mail->setMessage($html);
        $mail->setMailType('html');
        @$mail->send();

        return redirect()->to('login')
            ->with('success', 'Link reset password sudah dikirim ke email Anda.');
    }


    // --------------------------------------------------------
    // 🛠 Reset Password dengan Token
    // --------------------------------------------------------
    public function resetPassword($token)
    {
        $reset = $this->db->table('password_resets')->where('token', $token)->get()->getRow();

        if (!$reset || strtotime($reset->expires_at) < time()) {
            return redirect()->to('login')->with('error', 'Token tidak valid atau kedaluwarsa.');
        }

        return view('auth/reset_password', ['token' => $token]);
    }

    public function saveNewPassword($token)
    {
        $reset = $this->db->table('password_resets')->where('token', $token)->get()->getRow();

        if (!$reset) {
            return redirect()->to('login')->with('error', 'Token tidak valid.');
        }

        $newPassword = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);

        $this->userModel
            ->where('email', $reset->email)
            ->set(['password' => $newPassword])
            ->update();

        $this->db->table('password_resets')->where('email', $reset->email)->delete();

        return redirect()->to('login')->with('success', 'Password berhasil diubah.');
    }
}
