<?php

namespace App\Controllers\Ekskul;

use App\Controllers\BaseController;
use App\Models\Ekskul\EkskulModel;
use App\Models\Ekskul\AnggotaEkskulModel;
use App\Models\SiswaModel;

class AnggotaEkskulController extends BaseController
{
    protected $ekskulModel;
    protected $anggotaModel;
    protected $siswaModel;

    public function __construct()
    {
        $this->ekskulModel  = new EkskulModel();
        $this->anggotaModel = new AnggotaEkskulModel();
        $this->siswaModel   = new SiswaModel();
    }

    public function index($ekskul_id)
    {
        $ekskul = $this->ekskulModel->find($ekskul_id);
        if (!$ekskul) return redirect()->back()->with('error', 'Ekskul tidak ditemukan');

        $anggota = $this->anggotaModel
            ->select('anggota_ekskul.*, siswa.nama, siswa.nisn, siswa.kelas')
            ->join('siswa', 'siswa.id = anggota_ekskul.siswa_id')
            ->where('anggota_ekskul.ekskul_id', $ekskul_id)
            ->findAll();

        return view('admin/ekskul/anggota/index', [
            'ekskul'  => $ekskul,
            'anggota' => $anggota
        ]);
    }

    public function add($ekskul_id)
    {
        $ekskul = $this->ekskulModel->find($ekskul_id);
        $siswa  = $this->siswaModel->findAll();

        return view('admin/ekskul/anggota/add', [
            'ekskul' => $ekskul,
            'siswa'  => $siswa
        ]);
    }

    public function save()
    {
        $data = [
            'ekskul_id' => $this->request->getPost('ekskul_id'),
            'siswa_id'  => $this->request->getPost('siswa_id'),
            'status'    => 'aktif'
        ];

        // Cek jika sudah ada
        $exists = $this->anggotaModel
            ->where('ekskul_id', $data['ekskul_id'])
            ->where('siswa_id', $data['siswa_id'])
            ->first();

        if ($exists)
            return redirect()->back()->with('error', 'Siswa sudah terdaftar');

        $this->anggotaModel->insert($data);
        return redirect()->to('ekskul/anggota/' . $data['ekskul_id'])
            ->with('success', 'Anggota berhasil ditambahkan');
    }

    public function delete($id)
    {
        $cek = $this->anggotaModel->find($id);
        if (!$cek) return redirect()->back()->with('error', 'Data tidak ditemukan');

        $ekskul_id = $cek['ekskul_id'];
        $this->anggotaModel->delete($id);

        return redirect()->to('ekskul/anggota/' . $ekskul_id)
            ->with('success', 'Anggota berhasil dihapus');
    }
}
