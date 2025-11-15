<?php

namespace App\Models;
use CodeIgniter\Model;

class SaldoModel extends Model
{
    protected $table = 'tabungan_saldo';
    protected $primaryKey = 'siswa_id';
    protected $allowedFields = ['siswa_id', 'saldo'];
    protected $useTimestamps = false;

    // Hitung saldo otomatis dari transaksi
    public function updateSaldo($siswa_id)
    {
        $db = \Config\Database::connect();
        $query = $db->query("
            SELECT 
                SUM(CASE WHEN tipe='setor' THEN jumlah ELSE -jumlah END) as total 
            FROM tabungan_transaksi WHERE siswa_id = ?", [$siswa_id]);
        $total = $query->getRow()->total ?? 0;

        $this->save(['siswa_id' => $siswa_id, 'saldo' => $total]);
        return $total;
    }
}
