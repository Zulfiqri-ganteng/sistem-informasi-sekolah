<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SiswaModel;
use App\Models\GuruModel;
use App\Models\BarcodeModel;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

class AbsensiBarcode extends BaseController
{
    protected $siswa;
    protected $guru;
    protected $barcode;

    public function __construct()
    {
        $this->siswa   = new SiswaModel();
        $this->guru    = new GuruModel();
        $this->barcode = new BarcodeModel();
    }

    /* ============================================================
       FORM GENERATE
    ============================================================ */
    public function generateForm()
    {
        return view('absensi/generate_form', [
            'title' => 'Generate QR Premium',
            'siswa' => $this->siswa->findAll(),   // sesuai tabel mas
            'guru'  => $this->guru->findAll(),    // sesuai tabel mas
            'kelas' => $this->getAllKelas()       // generate dari kolom “kelas”
        ]);
    }

    /* ============================================================
       AMBIL SEMUA KELAS (dari kolom siswa.kelas)
    ============================================================ */
    private function getAllKelas()
    {
        return $this->siswa
            ->select('kelas')
            ->groupBy('kelas')
            ->orderBy('kelas', 'ASC')
            ->findAll();
    }

    /* ============================================================
       GENERATE QR SATUAN
    ============================================================ */
    private function generateSingle($type, $id)
    {
        $user = ($type === 'siswa')
            ? $this->siswa->find($id)
            : $this->guru->find($id);

        if (!$user) {
            throw new \Exception("Data {$type} ID {$id} tidak ditemukan.");
        }

        // token
        $token = bin2hex(random_bytes(16));

        // simpan ke tabel barcodes
        $barcodeId = $this->barcode->insert([
            'owner_id'   => $id,
            'owner_type' => $type,
            'token'      => $token,
            'expires_at' => null
        ]);

        // payload QR
        $payload = json_encode([
            "type" => $type,
            "user_id" => $id,
            "token" => $token
        ]);

        // direktori simpan
        $saveDir = FCPATH . "uploads/qrcodes/";
        if (!is_dir($saveDir)) mkdir($saveDir, 0755, true);

        // Nama aman
        $safe = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $user['nama']));
        $fileName = "qr-{$type}-{$safe}.png";
        $filePath = $saveDir . $fileName;

        // generate QR
        $result = Builder::create()
            ->data($payload)
            ->size(400)
            ->margin(10)
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->build();

        $result->saveToFile($filePath);

        // update
        $this->barcode->update($barcodeId, [
            'file_path' => "uploads/qrcodes/" . $fileName
        ]);

        return $barcodeId;
    }

    /* ============================================================
       GENERATE QR BANYAK SISWA
    ============================================================ */
    private function generateMultiSiswa($ids)
    {
        $result = [];
        foreach ($ids as $id) {
            $result[] = $this->generateSingle('siswa', $id);
        }
        return $result;
    }

    /* ============================================================
       GENERATE QR BANYAK GURU
    ============================================================ */
    private function generateMultiGuru($ids)
    {
        $result = [];
        foreach ($ids as $id) {
            $result[] = $this->generateSingle('guru', $id);
        }
        return $result;
    }

    /* ============================================================
       GENERATE QR SATU KELAS (berdasarkan kolom siswa.kelas)
    ============================================================ */
    private function generateKelas($kelasString)
    {
        $list = $this->siswa
            ->where('kelas', $kelasString)
            ->findAll();

        $result = [];

        foreach ($list as $row) {
            $result[] = $this->generateSingle('siswa', $row['id']);
        }

        return $result;
    }

    /* ============================================================
       PROSES GENERATE
    ============================================================ */
    public function generate()
    {
        $mode = $this->request->getPost('mode');

        if ($mode === 'siswa') {

            $ids = $this->request->getPost('owner_id');
            if (!$ids) return back()->with('error', 'Pilih siswa.');

            $result = $this->generateMultiSiswa($ids);
            return redirect()->to('absensi/qrcode-bundle?list=' . implode(',', $result));
        }

        if ($mode === 'guru') {

            $ids = $this->request->getPost('owner_id');
            if (!$ids) return back()->with('error', 'Pilih guru.');

            $result = $this->generateMultiGuru($ids);
            return redirect()->to('absensi/qrcode-bundle?list=' . implode(',', $result));
        }

        if ($mode === 'kelas') {

            $kelas = $this->request->getPost('kelas_id');
            if (!$kelas) return back()->with('error', 'Pilih kelas.');

            $result = $this->generateKelas($kelas);
            return redirect()->to('absensi/qrcode-bundle?list=' . implode(',', $result));
        }

        return back()->with('error', 'Mode tidak valid.');
    }

    /* ============================================================
       TAMPIL QR SATU
    ============================================================ */
    public function qrcode($id)
    {
        $barcode = $this->barcode->find($id);
        if (!$barcode) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        $user = ($barcode['owner_type'] === 'siswa')
            ? $this->siswa->find($barcode['owner_id'])
            : $this->guru->find($barcode['owner_id']);

        return view('absensi/show_qr', [
            'barcode' => $barcode,
            'user' => $user
        ]);
    }

    /* ============================================================
       TAMPIL BUNDLE (BANYAK QR)
    ============================================================ */
    public function qrcodeBundle()
    {
        $list = $this->request->getGet('list');
        $ids  = explode(',', $list);

        $dataQR = [];
        foreach ($ids as $id) {
            $dataQR[] = $this->barcode->find($id);
        }

        return view('absensi/show_bundle', [
            'data' => $dataQR
        ]);
    }
}
