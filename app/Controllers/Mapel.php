<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Mapel extends Controller
{
    public function index()
    {
        return view('mapel/index', ['title' => 'Data Mata Pelajaran']);
    }

    public function list()
    {
        $db = \Config\Database::connect();
        $data = $db->table('mapel')->get()->getResultArray();
        return $this->response->setJSON(['data' => $data]);
    }

    public function save()
    {
        $db = \Config\Database::connect();
        $data = [
            'kode_mapel' => $this->request->getPost('kode_mapel'),
            'nama_mapel' => $this->request->getPost('nama_mapel')
        ];

        if ($this->request->getPost('id'))
            $db->table('mapel')->where('id', $this->request->getPost('id'))->update($data);
        else
            $db->table('mapel')->insert($data);

        return $this->response->setJSON(['success' => true]);
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        $db->table('mapel')->delete(['id' => $id]);
        return $this->response->setJSON(['success' => true]);
    }

    public function get($id)
    {
        $db = \Config\Database::connect();
        $data = $db->table('mapel')->where('id', $id)->get()->getRowArray();
        return $this->response->setJSON($data);
    }
}
