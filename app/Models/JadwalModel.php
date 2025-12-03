<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalModel extends Model
{
    protected $table            = 'app_jadwal_sekolah';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'hari_index',
        'hari_nama',
        'jam_masuk_normal',
        'jam_penguncian',
        'jam_pulang_minimal',
        'jam_pulang_normal',
        'status',
    ];
    protected $returnType       = 'array';
    protected $useTimestamps    = false;
}
