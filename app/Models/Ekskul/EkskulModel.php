<?php

namespace App\Models\Ekskul;

use CodeIgniter\Model;

class EkskulModel extends Model
{
    protected $table = 'ekskul';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_ekskul',
        'pembimbing_id', // ID Guru yang membimbing
        'keterangan',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
