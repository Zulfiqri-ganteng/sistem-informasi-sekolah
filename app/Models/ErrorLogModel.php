<?php

namespace App\Models;

use CodeIgniter\Model;

class ErrorLogModel extends Model
{
    protected $table = 'error_log';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'level', 'message', 'context', 
        'file', 'line', 'created_at'
    ];
}
