<?php

namespace App\Models;

use CodeIgniter\Model;

class BarcodeModel extends Model
{
    protected $table = 'barcodes';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'owner_id',
        'owner_type',
        'token',
        'file_path',
        'created_at',
        'updated_at',
        'expires_at'
    ];

    protected $useTimestamps = true;
}
