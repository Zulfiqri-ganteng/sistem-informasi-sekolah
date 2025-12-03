<?php

namespace App\Models;

use CodeIgniter\Model;

class HariLiburModel extends Model
{
    protected $table            = 'app_hari_libur';
    protected $primaryKey       = 'id';
    protected $useTimestamps    = true;
    protected $allowedFields    = ['tanggal', 'keterangan'];
    protected $returnType       = 'array';
    protected $createdField     = 'created_at';
    protected $updatedField     = ''; // Tidak ada updated_at
}