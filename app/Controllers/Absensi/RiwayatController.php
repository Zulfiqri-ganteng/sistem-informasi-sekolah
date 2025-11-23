<?php

namespace App\Controllers\Absensi;

use App\Controllers\BaseController;
use App\Models\AbsensiModel;

class RiwayatController extends BaseController
{
    protected $absensiModel;

    public function __construct()
    {
        $this->absensiModel = new AbsensiModel();
    }

    /** =============================
     *  HALAMAN VIEW
     *  ============================= */
    public function index()
    {
        return view('absensi/riwayat/index');
    }


    /** =============================
     *  AJAX DATATABLES
     *  ============================= */
    public function riwayatAjax()
    {
        $role       = session('role');      // role user yang sedang login
        $userId     = session('id');
        $kelasUser  = session('kelas');

        /** =============================
         *  BASE QUERY
         *  ============================= */
        $builder = $this->absensiModel
            ->select("
                absensi.*,
                users.nama AS user_nama,
                users.role AS user_role,
                siswa.nama AS siswa_nama,
                siswa.kelas AS siswa_kelas
            ")
            // Join ke tabel users menggunakan absensi.user_id (HARUSNYA USER ID)
            ->join('users', 'users.id = absensi.user_id', 'left')
            // Join ke tabel siswa menggunakan users.siswa_id
            ->join('siswa', 'siswa.id = users.siswa_id', 'left');

        /** =============================
         *  FILTER ROLE 
         *  ============================= */

        // siswa → lihat absensi miliknya sendiri
        if ($role === 'siswa') {
            $builder->where('absensi.user_id', $userId);
        }

        // guru → lihat siswa yang satu kelas dengan dia
        if ($role === 'guru') {
            // Filter hanya absensi siswa di kelas guru
            $builder->where('siswa.kelas', $kelasUser)
                ->where('users.role', 'siswa');
        }

        // admin → bebas tanpa filter

        $result = $builder
            ->orderBy('absensi.created_at', 'DESC')
            ->get()
            ->getResult();


        /** =============================
         *  FORMAT OUTPUT DATATABLES
         *  ============================= */
        $output = [];

        foreach ($result as $row) {

            // Menentukan Nama: Utamakan siswa.nama, jika null, gunakan users.nama (untuk guru/admin)
            $nama = !empty($row->siswa_nama) ? $row->siswa_nama : ($row->user_nama ?? "-");

            // Menentukan Kelas: Ambil dari siswa.kelas. Jika null (untuk guru/admin), gunakan "-"
            $kelas = $row->siswa_kelas ?? "-";

            // Role: Ambil role dari data absensi/users, bukan dari sesi login saat ini
            $displayRole = $row->user_role ?? $row->user_type ?? "-";


            $output[] = [
                "created_at" => $row->created_at,
                "nama"       => $nama,
                "role"       => $displayRole, // Menggunakan role dari data hasil join
                "kelas"      => $kelas,
                "status"     => $row->status,
                "jam_masuk"  => $row->jam_masuk,
                "jam_pulang" => $row->jam_pulang,
            ];
        }


        return $this->response->setJSON([
            "data" => $output
        ]);
    }
}
