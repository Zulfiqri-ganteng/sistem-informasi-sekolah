<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Jurusan extends Controller
{
    public function index()
    {
        return view('jurusan/index', ['title' => 'Data Jurusan']);
    }

    public function list()
    {
        $db = \Config\Database::connect();
        $data = $db->table('jurusan')->get()->getResultArray();
        return $this->response->setJSON(['data' => $data]);
    }

    public function save()
    {
        $db = \Config\Database::connect();
        $data = ['nama_jurusan' => $this->request->getPost('nama_jurusan')];
        if ($this->request->getPost('id'))
            $db->table('jurusan')->where('id', $this->request->getPost('id'))->update($data);
        else
            $db->table('jurusan')->insert($data);
        return $this->response->setJSON(['success' => true]);
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        $db->table('jurusan')->delete(['id' => $id]);
        return $this->response->setJSON(['success' => true]);
    }

    public function get($id)
    {
        $db = \Config\Database::connect();
        $data = $db->table('jurusan')->where('id', $id)->get()->getRowArray();
        return $this->response->setJSON($data);
    }
}
