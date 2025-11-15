<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\GuruModel;
use App\Models\UserModel;
use App\Models\KelasModel;
use App\Models\SiswaModel;
use App\Models\TabunganModel;
use Config\Database;
use CodeIgniter\HTTP\ResponseInterface;

class Guru extends BaseController
{
    protected $db;
    protected $guruModel;
    protected $userModel;
    protected $kelasModel;
    protected $siswaModel;
    protected $tabunganModel;

    public function __construct()
    {
        $this->db            = Database::connect();
        $this->guruModel     = new GuruModel();
        $this->userModel     = new UserModel();
        $this->kelasModel    = new KelasModel();
        $this->siswaModel    = new SiswaModel();
        $this->tabunganModel = new TabunganModel();


        helper(['form', 'url']);
    }

    // ============================================================
    // DASHBOARD GURU
    // ============================================================
    public function index()
    {
        $userId = session()->get('user_id');
        $guru   = $this->guruModel->where('user_id', $userId)->first();

        if (!$guru) {
            return redirect()->to('/login')->with('error', 'Akun guru tidak ditemukan.');
        }

        // Ambil semua kelas yang diampu guru ini
        $kelasList = $this->kelasModel->where('guru_id', $guru['id'])->findAll();

        $kelasNamaList = array_column($kelasList, 'nama_kelas');

        // Hitung jumlah siswa
        $jumlahSiswa = (!empty($kelasNamaList))
            ? $this->siswaModel->whereIn('kelas', $kelasNamaList)->countAllResults()
            : 0;

        // Hitung total saldo gabungan
        $totalSaldo = 0;
        if (!empty($kelasNamaList)) {
            $query = $this->db->table('tabungan')
                ->selectSum('saldo')
                ->join('siswa', 'siswa.id = tabungan.siswa_id')
                ->whereIn('siswa.kelas', $kelasNamaList)
                ->get()
                ->getRowArray();

            $totalSaldo = $query['saldo'] ?? 0;
        }

        // Transaksi 10 terakhir
        $recentTransaksi = [];
        if (!empty($kelasNamaList)) {
            $recentTransaksi = $this->db->table('transaksi t')
                ->select('t.*, s.nama, s.kelas')
                ->join('siswa s', 's.id = t.siswa_id')
                ->whereIn('s.kelas', $kelasNamaList)
                ->orderBy('t.created_at', 'DESC')
                ->limit(10)
                ->get()
                ->getResultArray();
        }

        // TOP SISWA
        $topSiswa = [];
        if (!empty($kelasNamaList)) {
            $topSiswa = $this->db->table('tabungan t')
                ->select('s.nama, t.saldo')
                ->join('siswa s', 's.id = t.siswa_id')
                ->whereIn('s.kelas', $kelasNamaList)
                ->orderBy('t.saldo', 'DESC')
                ->limit(5)
                ->get()
                ->getResultArray();
        }

        return view('guru/dashboard', [
            'title'           => 'Dashboard Guru',
            'guru'            => $guru,
            'kelasList'       => $kelasList,
            'jumlahSiswa'     => $jumlahSiswa,
            'totalSaldo'      => $totalSaldo,
            'recentTransaksi' => $recentTransaksi,
            'topSiswa'        => $topSiswa
        ]);
    }

    public function chartData()
    {
        try {
            $userId = session()->get('user_id');

            // Ambil data guru
            $guru = $this->guruModel->where('user_id', $userId)->first();
            if (!$guru) {
                return $this->response->setJSON(['labels' => [], 'values' => []]);
            }

            // Ambil semua kelas yang diawalikan guru
            $kelasList = $this->kelasModel->where('guru_id', $guru['id'])->findAll();
            if (empty($kelasList)) {
                return $this->response->setJSON(['labels' => [], 'values' => []]);
            }

            // Kelas pertama sebagai default
            $kelasNama = $kelasList[0]['nama_kelas'] ?? null;
            if (!$kelasNama) {
                return $this->response->setJSON(['labels' => [], 'values' => []]);
            }

            // Ambil saldo siswa berdasarkan nama kelas
            $sql = "
            SELECT
                s.id,
                s.nama,
                (
                    COALESCE((SELECT SUM(jumlah) FROM tabungan WHERE siswa_id = s.id AND tipe='setor'), 0) -
                    COALESCE((SELECT SUM(jumlah) FROM tabungan WHERE siswa_id = s.id AND tipe='tarik'), 0)
                ) AS saldo
            FROM siswa s
            WHERE s.kelas = ?
            ORDER BY s.nama ASC
        ";

            $data = $this->db->query($sql, [$kelasNama])->getResultArray();

            $labels = array_column($data, 'nama');
            $values = array_map(fn($r) => (float) ($r['saldo'] ?? 0), $data);

            return $this->response->setJSON([
                'labels' => $labels,
                'values' => $values
            ]);
        } catch (\Exception $e) {
            log_message('error', 'chartData error: ' . $e->getMessage());
            return $this->response->setJSON(['labels' => [], 'values' => []]);
        }
    }

    // ============================================================
    // KELAS
    // ============================================================
    public function kelas()
    {
        $userId = session()->get('user_id');
        $guru   = $this->guruModel->where('user_id', $userId)->first();

        $kelas = $this->kelasModel->where('guru_id', $guru['id'])->findAll();

        return view('guru/kelas', [
            'title' => 'Kelas Saya',
            'kelas' => $kelas
        ]);
    }

    // ============================================================
    // SISWA DALAM KELAS
    // ============================================================
    public function siswa($kelas_id)
    {
        $kelas = $this->kelasModel->find($kelas_id);
        if (!$kelas) return redirect()->back()->with('error', 'Kelas tidak ditemukan.');

        $siswa = $this->siswaModel->where('kelas', $kelas['nama_kelas'])->findAll();

        return view('guru/siswa', [
            'title' => 'Siswa Kelas ' . $kelas['nama_kelas'],
            'kelas' => $kelas,
            'siswa' => $siswa
        ]);
    }

    // ============================================================
    // DETAIL SISWA
    // ============================================================
    public function siswaGet($id)
    {
        $siswa = $this->siswaModel->find($id);
        if (!$siswa) return redirect()->back();

        $saldoRow = $this->tabunganModel->where('siswa_id', $id)->first();
        $saldo    = $saldoRow['saldo'] ?? 0;

        $transaksi = $this->db->table('transaksi')
            ->where('siswa_id', $id)
            ->orderBy('created_at', 'DESC')
            ->limit(20)
            ->get()
            ->getResultArray();

        return view('guru/siswa_detail', [
            'title'     => 'Detail Siswa',
            'siswa'     => $siswa,
            'saldo'     => $saldo,
            'transaksi' => $transaksi
        ]);
    }

    // ============================================================
    // GET SISWA DALAM KELAS (AJAX)
    // ============================================================
    public function getSiswaKelas()
    {
        $userId = session()->get('user_id');
        $guru   = $this->guruModel->where('user_id', $userId)->first();

        if (!$guru) return $this->response->setJSON([]);

        // Semua kelas yang dia ampu
        $kelas = $this->kelasModel->where('guru_id', $guru['id'])->findAll();
        if (!$kelas) return $this->response->setJSON([]);

        $kelasNamaList = array_column($kelas, 'nama_kelas');

        $siswa = $this->siswaModel
            ->whereIn('kelas', $kelasNamaList)
            ->orderBy('nama', 'ASC')
            ->findAll();

        return $this->response->setJSON($siswa);
    }
    // ================================
    // 🔵 PROFIL GURU
    // ================================
    public function profil()
    {
        $userId = session()->get('user_id');

        // Ambil data guru berdasarkan user_id
        $guru = $this->guruModel
            ->where('user_id', $userId)
            ->first();

        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        // Ambil list mapel guru dari tabel pivot
        $mapel = $this->db->table('guru_mapel')
            ->select('mapel.nama_mapel')
            ->join('mapel', 'mapel.id = guru_mapel.mapel_id')
            ->where('guru_mapel.guru_id', $guru['id'])
            ->get()
            ->getResultArray();

        return view('guru/profil', [
            'guru'  => $guru,
            'mapel' => $mapel, // <-- KIRIM KE VIEW!
            'title' => 'Profil Guru'
        ]);
    }





    // ================================
    // 🟡 FORM GANTI PASSWORD
    // ================================
    public function gantiPassword()
    {
        return view('guru/ganti_password', [
            'title' => 'Ganti Password'
        ]);
    }

    // ================================
    // 🟢 PROSES UPDATE PASSWORD
    // ================================
    public function updatePassword()
    {
        $userId      = session()->get('user_id');
        $oldPassword = $this->request->getPost('old_password');
        $newPassword = $this->request->getPost('new_password');

        $user = $this->userModel->find($userId);

        if (!$user || !password_verify($oldPassword, $user['password'])) {
            return redirect()->back()->with('error', 'Password lama salah');
        }

        $this->userModel->update($userId, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);

        return redirect()->back()->with('success', 'Password berhasil diubah');
    }
    public function updateProfil()
    {
        $userId = session()->get('user_id');

        // Ambil data guru
        $guru = $this->guruModel->where('user_id', $userId)->first();
        if (!$guru) {
            return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
        }

        // Ambil input form
        $nama     = $this->request->getPost('nama');
        $email    = $this->request->getPost('email');
        $telepon  = $this->request->getPost('telepon');
        $alamat   = $this->request->getPost('alamat');
        $password = $this->request->getPost('password');

        // FOTO
        $foto = $this->request->getFile('foto');
        $fotoName = $guru['foto'] ?? null; // foto lama

        // Pastikan folder ada
        $uploadPath = FCPATH . 'uploads/guru/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {

            // Hapus foto lama jika ada dan bukan default
            if (!empty($fotoName) && $fotoName !== 'default.png' && file_exists($uploadPath . $fotoName)) {
                unlink($uploadPath . $fotoName);
            }

            // Nama file baru yang rapi
            $fotoName = 'guru_' . time() . '_' . $foto->getRandomName();

            // Simpan file
            $foto->move($uploadPath, $fotoName);
        }

        // Masukkan $fotoName ke database


        // Update tabel guru
        $guruData = [
            'nama'    => $nama,
            'email'   => $email,
            'telepon' => $telepon,
            'alamat'  => $alamat,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($fotoName) {
            $guruData['foto'] = $fotoName;
        }

        $this->guruModel->update($guru['id'], $guruData);

        // Update tabel users
        $userUpdate = [
            'nama'    => $nama,
            'email'   => $email,
            'telepon' => $telepon,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if (!empty($password)) {
            $userUpdate['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($fotoName) {
            $userUpdate['foto'] = $fotoName;
        }

        $this->userModel->update($userId, $userUpdate);
        session()->set([
            'nama' => $nama,
            'foto' => $fotoName,
        ]);

        return redirect()->to('/guru/profil')->with('success', 'Profil berhasil diperbarui');
    }
}
