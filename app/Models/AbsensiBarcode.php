<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BarcodeModel;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

class AbsensiBarcode extends BaseController
{
    protected $barcodeModel;

    public function __construct()
    {
        $this->barcodeModel = new BarcodeModel();
    }

    // Form generate
    public function generateForm()
    {
        // ambil data siswa & guru untuk pilihan (sesuaikan nama model kamu)
        $siswaModel = model('App\Models\SiswaModel'); // sesuaikan
        $guruModel = model('App\Models\GuruModel'); // sesuaikan

        $data = [
            'siswa' => $siswaModel->findAll(),
            'guru'  => $guruModel->findAll(),
            'title' => 'Generate QR Code Absensi'
        ];

        return view('absensi/generate_form', $data);
    }

    // Proses generate
    public function generate()
    {
        $post = $this->request->getPost();
        $ownerType = $post['owner_type'] ?? null; // 'siswa' atau 'guru'
        $ownerId = $post['owner_id'] ?? null;
        $validForMinutes = intval($post['valid_minutes'] ?? 60);

        if (!in_array($ownerType, ['siswa','guru']) || !$ownerId) {
            return redirect()->back()->with('error','Pilih pemilik yang valid.');
        }

        // buat token unik, misal base64 random + timestamp
        $token = bin2hex(random_bytes(16)) . '-' . time();

        // Simpan dulu record
        $expiresAt = date('Y-m-d H:i:s', time() + ($validForMinutes * 60));

        $insertId = $this->barcodeModel->insert([
            'owner_id'   => $ownerId,
            'owner_type' => $ownerType,
            'token'      => $token,
            'expires_at' => $expiresAt
        ]);

        if (!$insertId) {
            return redirect()->back()->with('error','Gagal menyimpan data barcode.');
        }

        // Buat payload QR (bisa sesuaikan format, mis. JSON)
        $payload = json_encode([
            'type' => 'absensi',
            'token' => $token,
            'owner_type' => $ownerType,
            'owner_id' => $ownerId,
            'issued_at' => date('c')
        ]);

        // buat direktori penyimpanan
        $saveDir = WRITEPATH . 'uploads/qrcodes/';
        if (!is_dir($saveDir)) {
            mkdir($saveDir, 0755, true);
        }

        $fileName = 'qr_' . $insertId . '_' . date('YmdHis') . '.png';
        $filePath = $saveDir . $fileName;

        // Bangun QR Code pakai endroid
        $result = Builder::create()
            ->data($payload)
            ->size(400)
            ->margin(10)
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->build();

        $result->saveToFile($filePath);

        // update record dengan path relatif
        $this->barcodeModel->update($insertId, [
            'file_path' => 'uploads/qrcodes/' . $fileName
        ]);

        return redirect()->to(base_url('absensi/qrcode/'.$insertId))->with('success','QR Code berhasil dibuat.');
    }

    // tampilkan QR
    public function qrcode($id)
    {
        $barcode = $this->barcodeModel->find($id);
        if (!$barcode) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('QR tidak ditemukan');
        }

        return view('absensi/show_qr', ['barcode' => $barcode]);
    }
}
