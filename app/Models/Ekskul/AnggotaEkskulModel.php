<?php

namespace App\Models\Ekskul;

use CodeIgniter\Model;

class AnggotaEkskulModel extends Model
{
    protected $table      = 'anggota_ekskul';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'ekskul_id',
        'siswa_id',
        'status',
        'created_at',
        'updated_at'
    ];
}
