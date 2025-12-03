<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table = 'activity_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'role',
        'module',
        'action',
        'detail',
        'ip_address',
        'user_agent',
        'meta',
        'created_at'
    ];
    protected $useTimestamps = false;
    protected $returnType = 'array';
    protected $order = 'created_at DESC';

    public function recent($limit = 50)
    {
        return $this->orderBy('created_at', 'DESC')->findAll($limit);
    }

    public function byUser($userId, $limit = 100)
    {
        return $this->where('user_id', $userId)->orderBy('created_at', 'DESC')->findAll($limit);
    }

    public function byRole($role, $limit = 100)
    {
        return $this->where('role', $role)->orderBy('created_at', 'DESC')->findAll($limit);
    }

    // Flexible query for DataTables / reporting
    public function datatableQuery($filters = [])
    {
        $builder = $this->builder();
        if (!empty($filters['start_date'])) {
            $builder->where('created_at >=', $filters['start_date'] . ' 00:00:00');
        }
        if (!empty($filters['end_date'])) {
            $builder->where('created_at <=', $filters['end_date'] . ' 23:59:59');
        }
        if (!empty($filters['role'])) {
            $builder->where('role', $filters['role']);
        }
        if (!empty($filters['module'])) {
            $builder->like('module', $filters['module']);
        }
        if (!empty($filters['action'])) {
            $builder->like('action', $filters['action']);
        }
        return $builder;
    }
}
