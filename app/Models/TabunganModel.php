<?php
namespace App\Models;
use CodeIgniter\Model;

class TabunganModel extends Model {
    protected $table = 'tabungan';
    protected $primaryKey = 'id';
    protected $allowedFields = ['siswa_id', 'saldo'];

    public function getJoinSiswa() {
        return $this->select('tabungan.id, siswa.nama, siswa.kelas, siswa.jurusan, tabungan.saldo')
                    ->join('siswa', 'siswa.id = tabungan.siswa_id')
                    ->findAll();
    }

    public function updateSaldo($siswaId, $saldoBaru) {
        $data = $this->where('siswa_id', $siswaId)->first();
        if ($data) $this->where('siswa_id', $siswaId)->set('saldo', $saldoBaru)->update();
        else $this->insert(['siswa_id' => $siswaId, 'saldo' => $saldoBaru]);
    }
}
