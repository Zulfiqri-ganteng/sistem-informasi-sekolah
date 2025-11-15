<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table = 'transaksi';
    protected $allowedFields = ['siswa_id', 'tipe', 'jumlah', 'keterangan', 'created_at'];
    protected $useTimestamps = true;
}
