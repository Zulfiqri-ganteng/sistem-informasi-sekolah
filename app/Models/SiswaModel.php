<?php

namespace App\Models;

use CodeIgniter\Model;

class SiswaModel extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nisn',
        'nama',
        'kelas',
        'jurusan',
        'alamat',
        'telepon',
        'foto',
        'created_at',
        'jenis_kelamin',
        'updated_at'
    ];
    protected $useTimestamps = true;
}
