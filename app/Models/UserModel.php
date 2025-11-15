<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'username',
        'password',
        'role',
        'siswa_id',
        'guru_id',
        'status',
        'foto',
        'created_at'
    ];

    // Ambil user + otomatis join nama siswa/guru
    public function getAllUsers()
    {
        return $this->select('users.*, 
                              siswa.nama as siswa_nama,
                              guru.nama as guru_nama')
            ->join('siswa', 'siswa.id = users.siswa_id', 'left')
            ->join('guru', 'guru.id = users.guru_id', 'left')
            ->orderBy('users.id', 'ASC')
            ->findAll();
    }
}
