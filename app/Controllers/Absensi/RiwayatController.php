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

    /** ===========================================
     *  HALAMAN VIEW
     *  =========================================== */
    public function index()
    {
        return view('absensi/riwayat/index');
    }

    /** ===========================================
     *  AJAX DATATABLE PRO MAX
     *  =========================================== */
    public function riwayatAjax()
    {
        $role       = session('role');
        $userId     = session('id');
        $kelasUser  = session('kelas');
        $filter     = $this->request->getGet('filter') ?? 'today';

        $builder = $this->absensiModel
            ->select("
                absensi.*,
                COALESCE(siswa.nama, users.nama) AS final_nama,
                COALESCE(siswa.kelas, '-') AS final_kelas,
                users.role AS final_role,
                absensi.tipe_absen AS tipe
            ")
            ->join('siswa', 'siswa.id = absensi.user_id', 'left')
            ->join('users', 'users.id = siswa.user_id', 'left');


        /** ===========================================
         *  FILTER ROLE (ADMIN / GURU / SISWA)
         *  =========================================== */
        if ($role === 'siswa') {
            $builder->where('absensi.user_id', $userId);
        }

        if ($role === 'guru') {
            $builder
                ->where('users.role', 'siswa')
                ->where('siswa.kelas', $kelasUser);
        }

        /** ===========================================
         *  FILTER WAKTU
         *  =========================================== */
        switch ($filter) {
            case 'yesterday':
                $builder->where('DATE(absensi.created_at)', date('Y-m-d', strtotime('-1 day')));
                break;

            case 'week':
                $builder->where('YEARWEEK(absensi.created_at)', date('oW'));
                break;

            case 'month':
                $builder->where('MONTH(absensi.created_at)', date('m'))
                    ->where('YEAR(absensi.created_at)', date('Y'));
                break;

            case 'all':
                break;

            default: // today
                $builder->where('DATE(absensi.created_at)', date('Y-m-d'));
        }

        /** ===========================================
         *  EXECUTE QUERY
         *  =========================================== */
        $result = $builder
            ->orderBy('absensi.created_at', 'DESC')
            ->get()
            ->getResult();

        /** ===========================================
         *  FORMAT OUTPUT
         *  =========================================== */
        $output = [];

        foreach ($result as $row) {

            // Tentukan role untuk badge tampilan
            $tipeBadge = match ($row->final_role) {
                'siswa' => 'Siswa',
                'guru'  => 'Guru',
                default => 'Admin'
            };

            // Badge status
            $statusColor = 'secondary';

            if (in_array($row->status, ['masuk', 'hadir'])) $statusColor = 'success';
            elseif ($row->status === 'terlambat') $statusColor = 'warning';
            elseif ($row->status === 'izin') $statusColor = 'info';
            elseif ($row->status === 'sakit') $statusColor = 'primary';
            elseif (in_array($row->status, ['pulang', 'pulang_awal'])) $statusColor = 'danger';

            $output[] = [
                "created_at" => $row->created_at,
                "nama"       => $row->final_nama,
                "role"       => $tipeBadge,
                "kelas"      => $row->final_kelas,
                "tipe"       => strtoupper($row->tipe),
                "status"     => $row->status,
                "status_color" => $statusColor,
                "jam_masuk"  => $row->jam_masuk ?: "-",
                "jam_pulang" => $row->jam_pulang ?: "-",
            ];
        }

        return $this->response->setJSON(["data" => $output]);
    }
}
