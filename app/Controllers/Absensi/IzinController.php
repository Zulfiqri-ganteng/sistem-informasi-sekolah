<?php

namespace App\Controllers\Absensi;

use App\Controllers\BaseController;
use App\Models\IzinModel;
use App\Models\AbsensiModel;
use App\Models\SiswaModel;
use App\Models\GuruModel;

class IzinController extends BaseController
{
    protected $izin, $absensi, $siswa, $guru;

    public function __construct()
    {
        // Inisialisasi Model
        $this->izin      = new IzinModel();
        $this->absensi   = new AbsensiModel();
        $this->siswa     = new SiswaModel();
        $this->guru      = new GuruModel();
    }

    /**
     * Menampilkan form pengajuan izin (untuk siswa/guru).
     * Route: GET /absensi/izin/form
     */
    public function form()
    {
        // Memastikan view berada di app/Views/absensi/izin_form.php
        return view('absensi/izin_form', [
            'validation' => \Config\Services::validation(),
            'title' => 'Form Pengajuan Izin'
        ]);
    }

    /**
     * Menyimpan pengajuan izin ke database.
     * Route: POST /absensi/izin/submit
     */
    public function submit()
    {
        $userId = session()->get('user_id');
        $userRole = session()->get('role');

        // Pengecekan Sesi Pengguna
        if (!$userId) {
            return redirect()->to(smart_url('login'))->with('error', 'Silakan login terlebih dahulu. Data sesi tidak ditemukan.');
        }

        // 1. Validasi Input
        $rules = [
            'tanggal' => 'required|valid_date',
            'jenis' => 'required|in_list[izin,sakit,pulang-awal]',
            'keterangan' => 'required|max_length[500]',
        ];

        $jenisIzin = $this->request->getPost('jenis');

        // Lampiran wajib diisi untuk jenis 'izin' atau 'sakit'
        if ($jenisIzin !== 'pulang-awal') {
            $rules['lampiran'] = 'uploaded[lampiran]|max_size[lampiran,2048]|ext_in[lampiran,pdf,jpg,jpeg,png]';
        }

        if (!$this->validate($rules)) {
            // Jika validasi gagal, kembalikan ke form dengan error
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // --- Pengecekan Tanggal Manual ---
        $tanggalPengajuan = $this->request->getPost('tanggal');
        $today = date('Y-m-d');

        // Pastikan tanggal pengajuan tidak di masa lalu (untuk mencegah pengajuan izin hari kemarin)
        if ($tanggalPengajuan < $today) {
            return redirect()->back()->withInput()->with('error', 'Pengajuan izin hanya dapat dilakukan untuk tanggal hari ini atau tanggal di masa depan.');
        }
        // -----------------------------------------------------------

        $lampiran = null;
        $file = $this->request->getFile('lampiran');

        try {
            // 2. Upload File Lampiran (Hanya jika ada file dan jenis bukan 'pulang-awal')
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                // PENTING: Pastikan folder 'writable/uploads/izin/' sudah ada dan bisa ditulis
                $file->move(WRITEPATH . 'uploads/izin', $newName);
                $lampiran = $newName;
            }
        } catch (\Throwable $e) {
            // Tangkap error saat upload file
            log_message('error', 'File Upload Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal mengunggah lampiran: ' . $e->getMessage());
        }

        try {
            // 3. Simpan Data ke IzinModel
            $dataIzin = [
                'user_id'    => $userId,
                'user_type'  => $userRole,
                'tanggal'    => $tanggalPengajuan,
                'jenis'      => $jenisIzin,
                'keterangan' => $this->request->getPost('keterangan'),
                'lampiran'   => $lampiran,
                'status'     => 'pending',
                // created_at diisi otomatis oleh Model jika $useTimestamps = true
            ];

            $this->izin->insert($dataIzin);

            // Jika berhasil, redirect ke form dengan pesan sukses
            return redirect()->to(smart_url('absensi/izin/form'))->with('success', 'Pengajuan izin berhasil dikirim. Menunggu persetujuan.');
        } catch (\Throwable $e) {
            // Tangkap error jika terjadi masalah saat menyimpan ke database
            log_message('error', 'Database Insert Error: ' . $e->getMessage());

            // Opsional: Hapus file yang terlanjur terupload jika insert database gagal
            if ($lampiran && file_exists(WRITEPATH . 'uploads/izin/' . $lampiran)) {
                @unlink(WRITEPATH . 'uploads/izin/' . $lampiran);
            }

            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan pengajuan izin. Detail Error: ' . $e->getMessage());
        }
    }


    /**
     * Menampilkan daftar semua pengajuan izin untuk Admin/Guru.
     * Route: GET /absensi/izin/list
     */
    public function adminList()
    {
        // Hanya Admin/Guru yang boleh mengakses (Pastikan di Route Filter sudah terpasang)
        if (session()->get('role') !== 'guru' && session()->get('role') !== 'admin') {
            return redirect()->to(smart_url('/'))->with('error', 'Akses ditolak.');
        }

        // Ambil data izin
        $listIzin = $this->izin->orderBy('tanggal', 'DESC')->findAll();
        $output = [];

        foreach ($listIzin as $row) {
            $userInfo = [
                'user_name' => 'Pengguna Tidak Ditemukan', // Fallback awal
                'user_foto' => null,
                'nisn' => 'N/A',
                'nip' => 'N/A',
                'identifier' => 'N/A', // Identifier umum untuk di view (NISN/NIP)
                'nama_model' => '', // Untuk menampilkan data dari model mana (Siswa/Guru)
            ];

            // Ambil data detail user
            if ($row['user_type'] === 'siswa') {
                // *** PERBAIKAN KRUSIAL DI SINI ***
                // Mencari berdasarkan kolom 'user_id' (Foreign Key ke tabel users) BUKAN Primary Key 'id'
                $user = $this->siswa
                    ->select('nama, foto, nisn')
                    ->where('user_id', $row['user_id'])
                    ->first();
                if ($user) {
                    $userInfo['user_name'] = $user['nama'];
                    $userInfo['user_foto'] = $user['foto'];
                    $userInfo['nisn'] = $user['nisn'];
                    $userInfo['identifier'] = $user['nisn'];
                    $userInfo['nama_model'] = 'Siswa';
                }
            } elseif ($row['user_type'] === 'guru') {
                // *** PERBAIKAN KRUSIAL DI SINI ***
                // Mencari berdasarkan kolom 'user_id' (Foreign Key ke tabel users) BUKAN Primary Key 'id'
                $user = $this->guru
                    ->select('nama, foto, nip')
                    ->where('user_id', $row['user_id'])
                    ->first();
                if ($user) {
                    $userInfo['user_name'] = $user['nama'];
                    $userInfo['user_foto'] = $user['foto'];
                    $userInfo['nip'] = $user['nip'];
                    $userInfo['identifier'] = $user['nip'];
                    $userInfo['nama_model'] = 'Guru';
                }
            }

            // gabungkan row izin + data user
            $output[] = array_merge($row, $userInfo);
        }

        // View absensi/izin_admin.php yang menampilkan daftar dan tombol approve/reject
        return view('absensi/izin_admin', [
            'list'  => $output,
            'title' => 'Kelola Pengajuan Izin',
            'baseURL' => base_url() // Kirim base_url ke view
        ]);
    }


    /**
     * Menyetujui pengajuan izin.
     * Route: GET /absensi/izin/approve/{id}
     */
    public function approve($id)
    {
        // Hanya Admin/Guru yang boleh mengakses
        if (session()->get('role') !== 'guru' && session()->get('role') !== 'admin') {
            return redirect()->to(smart_url('/'))->with('error', 'Akses ditolak.');
        }

        $izin = $this->izin->find($id);
        if (!$izin) return redirect()->back()->with('error', 'Data Izin tidak ditemukan.');
        if ($izin['status'] === 'approved') return redirect()->back()->with('warning', 'Izin sudah disetujui sebelumnya.');

        // 1. Update status izin di IzinModel
        $this->izin->update($id, [
            'status' => 'approved',
            'approved_by' => session()->get('user_id'),
            'approved_at' => date('Y-m-d H:i:s'),
            // updated_at akan diisi otomatis oleh Model
        ]);

        // 2. Sinkronisasi dengan AbsensiModel
        // Cari data absensi hari itu
        $absenToday = $this->absensi
            ->where('user_id', $izin['user_id'])
            ->where('user_type', $izin['user_type'])
            ->where('tanggal', $izin['tanggal'])
            ->first();

        try {
            if ($izin['jenis'] !== 'pulang-awal') {
                // Untuk Izin/Sakit: Set status absensi menjadi 'izin' atau 'sakit'
                $dataAbsen = [
                    'user_id'    => $izin['user_id'],
                    'user_type'  => $izin['user_type'],
                    'tanggal'    => $izin['tanggal'],
                    'status'     => $izin['jenis'], // 'izin' atau 'sakit'
                    'keterangan' => 'Pengajuan Izin/Sakit disetujui.',
                    'jam_masuk'  => null,
                    'jam_pulang' => null,
                ];

                if (!$absenToday) {
                    // Insert baru jika belum ada data absensi
                    $this->absensi->insert($dataAbsen);
                } else {
                    // Update status absensi yang sudah ada
                    $this->absensi->update($absenToday['id'], ['status' => $izin['jenis']]);
                }
            } else {
                // Khusus PULANG AWAL: Hanya update jika sudah ada data absensi masuk
                if ($absenToday && ($absenToday['status'] === 'masuk' || $absenToday['status'] === 'terlambat')) {
                    // Update status absensi menjadi 'pulang_awal'. 
                    $this->absensi->update($absenToday['id'], ['status' => 'pulang_awal']);
                } else if (!$absenToday) {
                    // Beri peringatan jika pulang awal disetujui tapi belum ada data absen masuk
                    return redirect()->back()->with('warning', 'Izin Pulang Awal disetujui. Namun, data absensi masuk untuk tanggal tersebut belum ditemukan. Status absensi tidak diubah.');
                }
            }
        } catch (\Throwable $e) {
            log_message('error', 'Absensi Update Error on Approve: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal update status absensi. Error: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Izin berhasil disetujui.');
    }

    /**
     * Menolak pengajuan izin.
     * Route: GET /absensi/izin/reject/{id}
     */
    public function reject($id)
    {
        // Hanya Admin/Guru yang boleh mengakses
        if (session()->get('role') !== 'guru' && session()->get('role') !== 'admin') {
            return redirect()->to(smart_url('/'))->with('error', 'Akses ditolak.');
        }

        $izin = $this->izin->find($id);
        if (!$izin) return redirect()->back()->with('error', 'Data Izin tidak ditemukan.');
        if ($izin['status'] === 'rejected') return redirect()->back()->with('warning', 'Izin sudah ditolak sebelumnya.');

        // 1. Update status izin di IzinModel
        $this->izin->update($id, [
            'status' => 'rejected',
            'rejected_by' => session()->get('user_id'),
            'rejected_at' => date('Y-m-d H:i:s'),
            // updated_at akan diisi otomatis oleh Model
        ]);

        try {
            // 2. Sinkronisasi dengan AbsensiModel
            if ($izin['jenis'] !== 'pulang-awal') {
                // Untuk Izin/Sakit: Hapus data absensi yang sebelumnya di-insert sebagai 'izin'/'sakit'
                $this->absensi
                    ->where('user_id', $izin['user_id'])
                    ->where('user_type', $izin['user_type'])
                    ->where('tanggal', $izin['tanggal'])
                    // Hapus hanya jika statusnya adalah 'izin' atau 'sakit' (mencegah menghapus data 'masuk'/'terlambat')
                    ->whereIn('status', ['izin', 'sakit'])
                    ->delete();
            } else {
                // Untuk Pulang Awal yang ditolak, tidak perlu ada perubahan pada tabel absensi.
            }
        } catch (\Throwable $e) {
            log_message('error', 'Absensi Delete Error on Reject: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal sinkronisasi status absensi. Error: ' . $e->getMessage());
        }


        return redirect()->back()->with('success', 'Izin berhasil ditolak.');
    }
}
