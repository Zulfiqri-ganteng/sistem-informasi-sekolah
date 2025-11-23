<?php

namespace App\Models;

use CodeIgniter\Model;

class IzinModel extends Model
{
    protected $table = 'izin';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'user_type',
        'tanggal',
        'jenis',
        'keterangan',
        'lampiran',
        'status' // Kolom status di tabel Anda adalah ENUM('pending','approved','rejected')
    ];

    // =========================================================
    // BARIS INI DITAMBAHKAN UNTUK MENGAKTIFKAN TIMESTAMPS
    // =========================================================
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    // =========================================================
}
