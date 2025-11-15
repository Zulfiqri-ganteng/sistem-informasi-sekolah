<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanModel extends Model
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function getLaporan($filters = [])
    {
        $builder = $this->db->table('siswa s')
            ->select("
                s.id, s.nama, s.kelas, s.jurusan,
                COALESCE(SUM(CASE WHEN t.tipe='setor' THEN t.jumlah ELSE 0 END),0) AS total_setor,
                COALESCE(SUM(CASE WHEN t.tipe='tarik' THEN t.jumlah ELSE 0 END),0) AS total_tarik,
                (COALESCE(SUM(CASE WHEN t.tipe='setor' THEN t.jumlah ELSE 0 END),0) -
                 COALESCE(SUM(CASE WHEN t.tipe='tarik' THEN t.jumlah ELSE 0 END),0)) AS saldo
            ")
            ->join('transaksi t', 't.siswa_id = s.id', 'left')
            ->groupBy('s.id');

        if (!empty($filters['kelas'])) $builder->where('s.kelas', $filters['kelas']);
        if (!empty($filters['jurusan'])) $builder->where('s.jurusan', $filters['jurusan']);
        if (!empty($filters['from']) && !empty($filters['to'])) {
            $builder->where('DATE(t.created_at) >=', $filters['from']);
            $builder->where('DATE(t.created_at) <=', $filters['to']);
        }
        if (!empty($filters['siswa_id'])) $builder->where('s.id', $filters['siswa_id']);

        return $builder->orderBy('s.nama', 'ASC')->get()->getResultArray();
    }

    public function getDetailTransaksi($siswaId)
    {
        return $this->db->table('transaksi')
            ->where('siswa_id', $siswaId)
            ->orderBy('created_at', 'DESC')
            ->get()->getResultArray();
    }
}
