<?php

namespace App\Models;

use CodeIgniter\Model;

class GuruMapelModel extends Model
{
    protected $table = 'guru_mapel';
    protected $primaryKey = 'id';

    protected $allowedFields = ['guru_id', 'mapel_id', 'created_at'];

    // ambil mapel2 yang dimiliki guru
    public function getMapelByGuru($guruId)
    {
        return $this->select('mapel.id, mapel.nama_mapel')
            ->join('mapel', 'mapel.id = guru_mapel.mapel_id')
            ->where('guru_mapel.guru_id', $guruId)
            ->findAll();
    }

    // hapus semua mapel milik guru
    public function deleteByGuru($guruId)
    {
        return $this->where('guru_id', $guruId)->delete();
    }
}
