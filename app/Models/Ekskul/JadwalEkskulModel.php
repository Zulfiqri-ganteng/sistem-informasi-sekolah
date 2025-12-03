<?php

namespace App\Models\Ekskul;

use CodeIgniter\Model;

class JadwalEkskulModel extends Model
{
    protected $table = 'jadwal_ekskul';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'ekskul_id',
        'hari_index', // 1=Senin, 7=Minggu
        'jam_mulai',
        'jam_selesai',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Aturan validasi dasar
    protected $validationRules = [
        'ekskul_id' => 'required|integer',
        'hari_index' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[7]',
        'jam_mulai' => 'required',
        'jam_selesai' => 'required',
    ];
}