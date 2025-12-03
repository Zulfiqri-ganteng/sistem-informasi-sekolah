<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    protected $table      = 'absensi';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'user_type',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status',
        'keterangan',
        'lokasi_masuk',
        'lokasi_pulang',
        'tipe_absen',
        'ekskul_id',
        'created_at'
    ];

    protected $useTimestamps = false; // kita pakai created_at manual
}
