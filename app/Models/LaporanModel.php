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

    /**
     * Ambil laporan sesuai filter.
     * filters: kelas, jurusan, from, to, siswa_id, wali_kelas
     */
    public function getLaporan(array $filters = [])
    {
        $from = !empty($filters['from']) ? $filters['from'] : null;
        $to = !empty($filters['to']) ? $filters['to'] : null;

        // Build date condition for SUM
        $dateCond = "1=1";
        if ($from && $to) {
            $fromEsc = $this->db->escapeString($from);
            $toEsc = $this->db->escapeString($to);
            $dateCond = "DATE(t.created_at) BETWEEN '{$fromEsc}' AND '{$toEsc}'";
        } elseif ($from) {
            $fromEsc = $this->db->escapeString($from);
            $dateCond = "DATE(t.created_at) >= '{$fromEsc}'";
        } elseif ($to) {
            $toEsc = $this->db->escapeString($to);
            $dateCond = "DATE(t.created_at) <= '{$toEsc}'";
        }

        $select = "
            s.id, s.nama, s.kelas, s.jurusan,
            COALESCE(SUM(CASE WHEN t.tipe = 'setor' AND ({$dateCond}) THEN t.jumlah ELSE 0 END),0) AS total_setor,
            COALESCE(SUM(CASE WHEN t.tipe = 'tarik' AND ({$dateCond}) THEN t.jumlah ELSE 0 END),0) AS total_tarik,
            (
                COALESCE(SUM(CASE WHEN t.tipe = 'setor' AND ({$dateCond}) THEN t.jumlah ELSE 0 END),0)
                -
                COALESCE(SUM(CASE WHEN t.tipe = 'tarik' AND ({$dateCond}) THEN t.jumlah ELSE 0 END),0)
            ) AS saldo
        ";

        $builder = $this->db->table('siswa s')
            ->select($select)
            ->join('transaksi t', 't.siswa_id = s.id', 'left')
            ->groupBy('s.id');

        // Apply filters only if provided (non-empty)
        if (!empty($filters['kelas'])) {
            $builder->where('s.kelas', $filters['kelas']);
        }
        if (!empty($filters['jurusan'])) {
            $builder->where('s.jurusan', $filters['jurusan']);
        }
        if (!empty($filters['siswa_id'])) {
            $builder->where('s.id', $filters['siswa_id']);
        }
        if (!empty($filters['wali_kelas'])) {
            // adjust if your siswa table has wali_kelas column
            $builder->where('s.wali_kelas', $filters['wali_kelas']);
        }

        return $builder->orderBy('s.nama', 'ASC')->get()->getResultArray();
    }

    /**
     * Detail transaksi per siswa
     */
    public function getDetailTransaksi($siswaId)
    {
        return $this->db->table('transaksi t')
            ->select('t.id, t.siswa_id, t.tipe, t.jumlah, t.keterangan, t.created_at, s.nama as siswa_nama')
            ->join('siswa s', 's.id = t.siswa_id', 'left')
            ->where('t.siswa_id', $siswaId)
            ->orderBy('t.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Totals (respect filters)
     */
    public function getTotals(array $filters = [])
    {
        $rows = $this->getLaporan($filters);
        return [
            'totalSetor' => array_sum(array_column($rows, 'total_setor')),
            'totalTarik' => array_sum(array_column($rows, 'total_tarik')),
            'totalSaldo' => array_sum(array_column($rows, 'saldo')),
        ];
    }

    /**
     * Distinct lists for filters (kelas & jurusan) — use Query Builder distinct()
     */
    public function getFiltersList()
    {
        $kelasRows = $this->db->table('siswa')
            ->select('kelas')
            ->distinct()
            ->where('kelas IS NOT NULL', null, false)
            ->orderBy('kelas', 'ASC')
            ->get()
            ->getResultArray();

        $jurusanRows = $this->db->table('siswa')
            ->select('jurusan')
            ->distinct()
            ->where('jurusan IS NOT NULL', null, false)
            ->orderBy('jurusan', 'ASC')
            ->get()
            ->getResultArray();

        $kelas = array_values(array_filter(array_map(fn($r) => $r['kelas'] ?? null, $kelasRows)));
        $jurusan = array_values(array_filter(array_map(fn($r) => $r['jurusan'] ?? null, $jurusanRows)));

        return [
            'kelas' => $kelas,
            'jurusan' => $jurusan
        ];
    }

    /**
     * Data grouped per month (for chart per bulan)
     * returns ['YYYY-MM' => totalSaldo]
     */
    public function getMonthlySaldo($year = null, $filters = [])
    {
        if (!$year) $year = date('Y');

        // compute saldo per siswa then sum per month using transaksi time
        $qb = $this->db->table('transaksi t')
            ->select("DATE_FORMAT(t.created_at, '%Y-%m') as ym,
                SUM(CASE WHEN t.tipe='setor' THEN t.jumlah ELSE 0 END) AS total_setor,
                SUM(CASE WHEN t.tipe='tarik' THEN t.jumlah ELSE 0 END) AS total_tarik")
            ->join('siswa s', 's.id = t.siswa_id', 'left')
            ->where("YEAR(t.created_at) = ", $year, false);

        // apply simple filters on siswa if provided
        if (!empty($filters['kelas'])) $qb->where('s.kelas', $filters['kelas']);
        if (!empty($filters['jurusan'])) $qb->where('s.jurusan', $filters['jurusan']);

        $qb->groupBy('ym')->orderBy('ym', 'ASC');

        $rows = $qb->get()->getResultArray();

        $result = [];
        foreach ($rows as $r) {
            $result[$r['ym']] = floatval($r['total_setor']) - floatval($r['total_tarik']);
        }

        // ensure all months present
        for ($m = 1; $m <= 12; $m++) {
            $key = $year . '-' . str_pad($m, 2, '0', STR_PAD_LEFT);
            if (!isset($result[$key])) $result[$key] = 0;
        }

        ksort($result);
        return $result;
    }
}
