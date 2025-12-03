<?php

namespace App\Controllers;

use App\Models\ActivityLogModel;
use Config\Database;
use CodeIgniter\HTTP\ResponseInterface;

class ActivityLogs extends BaseController
{
    protected $db;
    protected $activityModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->activityModel = new ActivityLogModel();
        helper(['url', 'form', 'activity']); // pastikan helper activity ada
    }

    /**
     * Halaman index
     */
    public function index()
    {
        return view('activity/index', [
            'title' => 'Log Aktivitas'
        ]);
    }

    /**
     * AJAX endpoint untuk DataTables / list
     * Mengembalikan array { data: [...] } supaya mudah dipakai client-side.
     * Jika Anda memakai server-side processing DataTables, bisa tambahkan param 'draw', 'recordsTotal', 'recordsFiltered'.
     */
    public function ajaxList()
    {
        // terima filter (opsional)
        $dateFrom = $this->request->getPost('date_from');
        $dateTo   = $this->request->getPost('date_to');
        $role     = $this->request->getPost('role');
        $q        = $this->request->getPost('q'); // generic search module/action/detail

        $builder = $this->db->table('activity_logs a');

        // Select utama: ambil nama actor (guru / siswa / users.username) via left join
        // Pastikan kolom/kondisi join sesuai DB Anda: guru.user_id, users.siswa_id
        $builder->select("
            a.*,
            u.username AS user_username,
            u.nama     AS user_nama,
            u.siswa_id AS users_siswa_id,
            g.nama     AS guru_nama,
            s.nama     AS siswa_nama
        ");

        $builder->join('users u', 'u.id = a.user_id', 'left');
        $builder->join('guru g', 'g.user_id = u.id', 'left');      // guru may have user_id
        $builder->join('siswa s', 's.id = u.siswa_id', 'left');    // siswa linked from users.siswa_id

        // Filters
        if ($dateFrom) {
            $builder->where('DATE(a.created_at) >=', $dateFrom);
        }
        if ($dateTo) {
            $builder->where('DATE(a.created_at) <=', $dateTo);
        }
        if ($role && $role !== 'all') {
            $builder->where('a.role', $role);
        }
        if ($q) {
            $builder->groupStart()
                ->like('a.module', $q)
                ->orLike('a.action', $q)
                ->orLike('a.detail', $q)
                ->groupEnd();
        }

        $builder->orderBy('a.created_at', 'DESC');

        $rows = $builder->get()->getResultArray();

        // Format rows: tentukan actor_name dengan prioritas: guru.nama -> siswa.nama -> users.nama -> username
        $data = [];
        foreach ($rows as $r) {
            $actorName = null;
            if (!empty($r['guru_nama'])) {
                $actorName = $r['guru_nama'];
            } elseif (!empty($r['siswa_nama'])) {
                $actorName = $r['siswa_nama'];
            } elseif (!empty($r['user_nama'])) {
                $actorName = $r['user_nama'];
            } elseif (!empty($r['user_username'])) {
                $actorName = $r['user_username'];
            } else {
                $actorName = 'Guest';
            }

            // Jika detail JSON, coba decode untuk tampilan ringkas
            $detail = $r['detail'];
            $detailShort = $detail;
            // jika panjang, trim
            if (is_string($detail) && strlen($detail) > 150) {
                $detailShort = substr($detail, 0, 150) . '...';
            }

            // Jika meta JSON -> decode untuk disimpan di kolom meta_json
            $metaDecoded = null;
            if (!empty($r['meta'])) {
                $metaJson = json_decode($r['meta'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $metaDecoded = $metaJson;
                }
            }

            $data[] = [
                'id'          => $r['id'],
                'actor_id'    => $r['user_id'],
                'actor_name'  => $actorName,
                'actor_role'  => $r['role'],
                'module'      => $r['module'],
                'action'      => $r['action'],
                'detail'      => $r['detail'],
                'detail_short' => $detailShort,
                'meta'        => $metaDecoded,
                'ip_address'  => $r['ip_address'],
                'user_agent'  => $r['user_agent'],
                'created_at'  => $r['created_at'],
            ];
        }

        return $this->response->setJSON(['data' => $data]);
    }

    /**
     * View detail single activity (bisa dipanggil via modal)
     */
    public function view($id = null)
    {
        if (!$id) {
            return $this->failNotFound('ID tidak ditemukan');
        }

        $builder = $this->db->table('activity_logs a');
        $builder->select("
            a.*,
            u.username AS user_username,
            u.nama     AS user_nama,
            g.nama     AS guru_nama,
            s.nama     AS siswa_nama
        ");
        $builder->join('users u', 'u.id = a.user_id', 'left');
        $builder->join('guru g', 'g.user_id = u.id', 'left');
        $builder->join('siswa s', 's.id = u.siswa_id', 'left');
        $builder->where('a.id', $id);
        $row = $builder->get()->getRowArray();

        if (!$row) {
            return $this->failNotFound('Log tidak ditemukan');
        }

        $actorName = $row['guru_nama'] ?? $row['siswa_nama'] ?? $row['user_nama'] ?? $row['user_username'] ?? 'Guest';

        $row['actor_name'] = $actorName;

        // decode detail/meta jika JSON
        $row['detail_parsed'] = json_decode($row['detail'], true);
        $row['meta_parsed']   = json_decode($row['meta'], true);

        return $this->response->setJSON($row);
    }

    /**
     * Export CSV sederhana (semua atau berdasarkan filter POST)
     */
    public function exportCsv()
    {
        // Anda bisa menambahkan filter sama seperti ajaxList jika perlu
        $builder = $this->db->table('activity_logs a')
            ->select('a.*, u.username AS user_username, u.nama AS user_nama, g.nama AS guru_nama, s.nama AS siswa_nama')
            ->join('users u', 'u.id = a.user_id', 'left')
            ->join('guru g', 'g.user_id = u.id', 'left')
            ->join('siswa s', 's.id = u.siswa_id', 'left')
            ->orderBy('a.created_at', 'DESC');

        $rows = $builder->get()->getResultArray();

        $filename = 'activity_logs_' . date('Ymd_His') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $out = fopen('php://output', 'w');
        fputcsv($out, ['ID', 'Waktu', 'Actor ID', 'Actor Name', 'Role', 'Module', 'Action', 'Detail', 'IP', 'User Agent', 'Meta']);

        foreach ($rows as $r) {
            $actorName = $r['guru_nama'] ?? $r['siswa_nama'] ?? $r['user_nama'] ?? $r['user_username'] ?? 'Guest';
            fputcsv($out, [
                $r['id'],
                $r['created_at'],
                $r['user_id'],
                $actorName,
                $r['role'],
                $r['module'],
                $r['action'],
                $r['detail'],
                $r['ip_address'],
                $r['user_agent'],
                $r['meta']
            ]);
        }

        fclose($out);
        exit;
    }
}
