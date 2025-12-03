<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\JadwalModel;
use App\Models\HariLiburModel;

class JadwalController extends BaseController
{
    protected $jadwalModel;
    protected $hariLiburModel;

    public function __construct()
    {
        $this->jadwalModel = new JadwalModel();
        $this->hariLiburModel = new HariLiburModel();
    }

    /* TAMPILAN UTAMA (JADWAL HARIAN) */
    public function index()
    {
        $data['jadwal'] = $this->jadwalModel->orderBy('hari_index', 'ASC')->findAll();
        $data['libur'] = $this->hariLiburModel->orderBy('tanggal', 'DESC')->findAll(10); // Ambil 10 libur terbaru

        return view('admin/jadwal/index', $data);
    }

    /* PROSES UPDATE JADWAL HARIAN */
    public function updateJadwal()
    {
        $id = $this->request->getPost('id');
        $data = [
            'jam_masuk_normal'   => $this->request->getPost('jam_masuk_normal'),
            'jam_penguncian'     => $this->request->getPost('jam_penguncian'),
            'jam_pulang_minimal' => $this->request->getPost('jam_pulang_minimal'),
            'jam_pulang_normal'  => $this->request->getPost('jam_pulang_normal'),
            'status'             => $this->request->getPost('status'),
        ];

        $this->jadwalModel->update($id, $data);

        return redirect()->back()->with('success', 'Jadwal hari berhasil diperbarui.');
    }

    /* PROSES TAMBAH HARI LIBUR */
    public function addHariLibur()
    {
        $rules = [
            'tanggal' => 'required|valid_date[Y-m-d]',
            'keterangan' => 'required|min_length[3]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Tanggal atau Keterangan tidak valid.');
        }

        $this->hariLiburModel->insert([
            'tanggal' => $this->request->getPost('tanggal'),
            'keterangan' => $this->request->getPost('keterangan'),
        ]);

        return redirect()->back()->with('success', 'Hari libur berhasil ditambahkan.');
    }

    /* PROSES HAPUS HARI LIBUR */
    public function deleteHariLibur($id)
    {
        $this->hariLiburModel->delete($id);
        return redirect()->back()->with('success', 'Hari libur berhasil dihapus.');
    }
}
