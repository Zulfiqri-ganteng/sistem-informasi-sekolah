<?php

namespace App\Models;

use CodeIgniter\Model;

class ErrorLogModel extends Model
{
    protected $table = 'error_logs';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'level',
        'message',
        'file',
        'line',
        'url',
        'user_id',
        'user_role',
        'ip_address',
        'user_agent',
    ];

    protected $useTimestamps = true;
}
