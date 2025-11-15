<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\KelasModel;
use App\Models\GuruModel;
use App\Models\TabunganModel;
use Config\Database;

class GuruTransaksi extends BaseController
{
    protected $db;
    protected $siswaModel;
    protected $kelasModel;
    protected $guruModel;
    protected $tabunganModel;

    public function __construct()
    {
        $this->db            = Database::connect();
        $this->siswaModel    = new SiswaModel();
        $this->kelasModel    = new KelasModel();
        $this->guruModel     = new GuruModel();
        $this->tabunganModel = new TabunganModel();
    }

    // ==========================================================
    //  FORM MODAL (AJAX LOAD)
    // ==========================================================
    public function createForm()
    {
        $userId = session()->get('user_id');

        $guru = $this->guruModel->where('user_id', $userId)->first();
        if (!$guru) {
            return $this->response->setJSON(['html' => 'Guru tidak ditemukan']);
        }

        $kelas = $this->kelasModel->where('guru_id', $guru['id'])->findAll();

        $siswaList = [];
        if (!empty($kelas)) {
            $namaKelas = array_column($kelas, 'nama_kelas');
            $siswaList = $this->siswaModel
                ->whereIn('kelas', $namaKelas)
                ->orderBy('nama', 'ASC')
                ->findAll();
        }

        $html = view('guru/partials/_transaksi_modal', [
            'siswaList' => $siswaList
        ]);

        return $this->response->setJSON(['html' => $html]);
    }

    // ==========================================================
    //  PROSES CREATE TRANSAKSI (FINAL)
    // ==========================================================
    public function create()
    {
        if (!$this->request->is('post')) {

            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }

        $session = session();
        $userId = $session->get('user_id');

        $guru = $this->guruModel->where('user_id', $userId)->first();
        if (!$guru) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Guru tidak ditemukan'
            ]);
        }

        // ambil data POST
        $siswa_id   = (int) $this->request->getPost('siswa_id');
        $tipe       = $this->request->getPost('tipe');
        $jumlah     = (int) $this->request->getPost('jumlah');
        $keterangan = $this->request->getPost('keterangan');

        if (!$siswa_id || $jumlah <= 0 || !in_array($tipe, ['setor', 'tarik'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data tidak valid'
            ]);
        }

        // pastikan siswa adalah bimbingan guru
        $kelas = $this->kelasModel->where('guru_id', $guru['id'])->first();
        if (!$kelas) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Guru tidak memiliki kelas'
            ]);
        }

        $siswa = $this->siswaModel->find($siswa_id);
        if (!$siswa || $siswa['kelas'] !== $kelas['nama_kelas']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Siswa tidak termasuk kelas Anda'
            ]);
        }

        // ambil saldo lama
        $tab = $this->tabunganModel->where('siswa_id', $siswa_id)->first();
        $saldoOld = $tab['saldo'] ?? 0;

        // hitung saldo baru
        if ($tipe === 'setor') {
            $saldoNew = $saldoOld + $jumlah;
        } else {
            if ($jumlah > $saldoOld) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Saldo tidak cukup'
                ]);
            }
            $saldoNew = $saldoOld - $jumlah;
        }

        // transaksi DB
        $this->db->transStart();

        // insert ke transaksi
        $this->db->table('transaksi')->insert([
            'siswa_id'   => $siswa_id,
            'tipe'       => $tipe,
            'jumlah'     => $jumlah,
            'keterangan' => $keterangan,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        // update atau insert saldo
        if ($tab) {
            $this->tabunganModel->update($tab['id'], ['saldo' => $saldoNew]);
        } else {
            $this->tabunganModel->insert(['siswa_id' => $siswa_id, 'saldo' => $saldoNew]);
        }

        $this->db->transComplete();

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Transaksi berhasil disimpan'
        ]);
    }
}
