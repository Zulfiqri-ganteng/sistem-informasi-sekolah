<?php

namespace App\Controllers\Ekskul;

use App\Controllers\BaseController;
use App\Models\Ekskul\EkskulModel;
use App\Models\Ekskul\JadwalEkskulModel;
use App\Models\GuruModel;
use CodeIgniter\API\ResponseTrait;

class EkskulController extends BaseController
{
    use ResponseTrait;

    protected $ekskulModel;
    protected $jadwalModel;
    protected $guruModel;
    protected $db;
    protected $validation;

    // Mapping index hari
    private $hariMap = [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
        7 => 'Minggu'
    ];

    public function __construct()
    {
        // Inisialisasi Model
        $this->ekskulModel = new EkskulModel();
        $this->jadwalModel = new JadwalEkskulModel();
        $this->guruModel = new GuruModel();

        // Inisialisasi Tools
        $this->db = \Config\Database::connect();
        $this->validation = \Config\Services::validation();

        // Load helper
        helper(['form', 'url']);
    }

    /**
     * Menampilkan daftar semua Ekstrakurikuler.
     */
    public function index()
    {
        // 1. Ambil data guru/pembimbing untuk dropdown di modal
        $pembimbingList = $this->guruModel->select('id, nama')->findAll();

        // 2. Ambil semua data Ekskul
        $ekskulList = $this->ekskulModel->findAll();

        // 3. Gabungkan data Pembimbing dan Jadwal
        foreach ($ekskulList as $key => $ekskul) {
            // Gabungkan data Guru/Pembimbing
            $guru = $this->guruModel->find($ekskul['pembimbing_id']);
            $ekskulList[$key]['pembimbing_nama'] = $guru ? $guru['nama'] : 'Tidak Diketahui';

            // Ambil data jadwal berdasarkan ekskul_id
            $jadwal = $this->jadwalModel
                ->where('ekskul_id', $ekskul['id'])
                ->orderBy('hari_index', 'ASC')
                ->findAll();

            // Format jadwal dengan nama hari
            $formattedJadwal = [];
            foreach ($jadwal as $j) {
                $j['hari'] = $this->hariMap[$j['hari_index']] ?? 'Tidak Diketahui';
                $formattedJadwal[] = $j;
            }
            $ekskulList[$key]['jadwal'] = $formattedJadwal;
        }

        $data = [
            'title' => 'Manajemen Ekstrakurikuler',
            'ekskulList' => $ekskulList,
            'pembimbingList' => $pembimbingList,
            'validation' => $this->validation
        ];

        return view('admin/ekskul/index', $data);
    }

    /**
     * Menyimpan data Ekstrakurikuler baru atau mengupdate yang sudah ada.
     */
    public function save()
    {
        $input = $this->request->getPost();

        // Aturan validasi
        $rules = [
            'nama_ekskul' => 'required|min_length[3]|max_length[100]',
            'pembimbing_id' => 'required|integer',
            'keterangan' => 'permit_empty|max_length[500]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->to(base_url('ekskul'))->withInput()->with('validation', $this->validation);
        }

        $id = $input['id'] ?? null;
        $data = [
            'nama_ekskul' => $input['nama_ekskul'],
            'pembimbing_id' => $input['pembimbing_id'],
            'keterangan' => $input['keterangan'] ?? null
        ];

        try {
            if (empty($id)) {
                $this->ekskulModel->insert($data);
                $message = 'Ekstrakurikuler berhasil ditambahkan.';
            } else {
                $this->ekskulModel->update($id, $data);
                $message = 'Ekstrakurikuler berhasil diperbarui.';
            }
            session()->setFlashdata('success', $message);
        } catch (\Exception $e) {
            log_message('error', 'Error saving ekskul: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menyimpan ekstrakurikuler. Error: ' . $e->getMessage());
        }

        return redirect()->to(base_url('ekskul'));
    }

    /**
     * Menghapus data Ekstrakurikuler beserta jadwal terkait.
     */
    public function delete($id = null)
    {
        if (is_null($id)) {
            session()->setFlashdata('error', 'ID Ekstrakurikuler tidak valid.');
            return redirect()->to(base_url('ekskul'));
        }

        $this->db->transBegin();

        try {
            // 1. Hapus semua jadwal terkait
            $this->jadwalModel->where('ekskul_id', $id)->delete();

            // 2. Hapus Ekstrakurikuler
            $deleted = $this->ekskulModel->delete($id);

            if ($deleted) {
                $this->db->transCommit();
                session()->setFlashdata('success', 'Ekstrakurikuler dan semua data terkait berhasil dihapus.');
            } else {
                $this->db->transRollback();
                session()->setFlashdata('error', 'Gagal menghapus ekstrakurikuler. Data tidak ditemukan.');
            }
        } catch (\Exception $e) {
            $this->db->transRollback();
            log_message('error', 'Error deleting ekskul: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menghapus ekstrakurikuler karena masalah database.');
        }

        return redirect()->to(base_url('ekskul'));
    }

    // --- LOGIKA JADWAL EKSTRAKURIKULER ---

    /**
     * Menyimpan data Jadwal baru atau mengupdate yang sudah ada.
     * Menggantikan fungsi addJadwal() yang sebelumnya hanya insert.
     */
    public function saveJadwal()
    {
        $input = $this->request->getPost();

        // **PERUBAHAN DI SINI: valid_time diganti dengan valid_time_format**
        $rules = [
            'ekskul_id' => 'required|integer',
            'hari_index' => 'required|integer|greater_than_equal_to[1]|less_than_equal_to[7]',
            // Menggunakan aturan kustom yang baru didaftarkan
            'jam_mulai' => 'required|valid_time_format',
            // Menggunakan dua aturan kustom yang baru didaftarkan
            'jam_selesai' => 'required|valid_time_format|later_than[jam_mulai]',
        ];

        // Pastikan Anda menggunakan $this->validation->setRules($rules)->run($input)
        if (!$this->validation->setRules($rules)->run($input)) {
            // Jika validasi gagal, kembalikan ke halaman sebelumnya dengan error
            session()->setFlashdata('error', 'Gagal menyimpan jadwal. Pastikan Jam Selesai setelah Jam Mulai dan format waktu benar (HH:MM).');
            return redirect()->back()->withInput();
        }

        $id = $input['id'] ?? null; // ID Jadwal (untuk edit, bisa null untuk simpan baru)

        $data = [
            'ekskul_id' => $input['ekskul_id'],
            'hari_index' => $input['hari_index'],
            'jam_mulai' => $input['jam_mulai'],
            'jam_selesai' => $input['jam_selesai'],
        ];

        try {
            if (empty($id)) {
                // Insert data baru
                $this->jadwalModel->insert($data);
                $message = 'Jadwal baru berhasil ditambahkan.';
            } else {
                // Update data
                $this->jadwalModel->update($id, $data);
                $message = 'Jadwal berhasil diubah.';
            }
            session()->setFlashdata('success', $message);
        } catch (\Exception $e) {
            log_message('error', 'Error saving jadwal: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menyimpan jadwal. Silakan cek log: ' . $e->getMessage());
        }

        // Redirect kembali ke halaman detail ekskul
        return redirect()->to(base_url('ekskul'));
    }

    /**
     * Menghapus satu item jadwal berdasarkan ID Jadwal.
     */
    public function deleteJadwal($id = null)
    {
        if (is_null($id)) {
            session()->setFlashdata('error', 'ID Jadwal tidak valid.');
            return redirect()->to(base_url('ekskul'));
        }

        try {
            $deleted = $this->jadwalModel->delete($id);

            if ($deleted) {
                session()->setFlashdata('success', 'Jadwal berhasil dihapus.');
            } else {
                session()->setFlashdata('error', 'Gagal menghapus jadwal. Data tidak ditemukan.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error deleting jadwal: ' . $e->getMessage());
            session()->setFlashdata('error', 'Gagal menghapus jadwal karena masalah database.');
        }

        return redirect()->to(base_url('ekskul'));
    }
}
