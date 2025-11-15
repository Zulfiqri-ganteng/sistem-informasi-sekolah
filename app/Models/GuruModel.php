<?php

namespace App\Models;

use CodeIgniter\Model;

class GuruModel extends Model
{
    protected $table = 'guru';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'nip',
        'nama',
        'telepon',
        'email',
        'foto',
        'alamat',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
}
