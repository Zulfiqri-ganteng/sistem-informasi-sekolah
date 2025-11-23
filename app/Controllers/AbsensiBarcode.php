<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BarcodeModel;
use App\Models\SiswaModel;
use App\Models\GuruModel;
use App\Models\KelasModel;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class AbsensiBarcode extends BaseController
{
    protected $barcodeModel;
    protected $siswaModel;
    protected $guruModel;
    protected $kelasModel;

    public function __construct()
    {
        $this->barcodeModel = new BarcodeModel();
        $this->siswaModel   = new SiswaModel();
        $this->guruModel    = new GuruModel();
        $this->kelasModel   = new KelasModel();
    }

    // FORM: tampilkan halaman premium (Select2, multi-select, dsb)
    public function generateForm()
    {
        return view('absensi/generate_form', [
            'siswa' => $this->siswaModel->findAll(),
            'guru'  => $this->guruModel->findAll(),
            'kelas' => $this->kelasModel->findAll(),
            'title' => 'Generate QR Absensi'
        ]);
    }

    // UTILITY: create single QR, return inserted barcode id
    private function generateSingleQR(string $ownerType, int $ownerId)
    {
        // ambil data pemilik
        if ($ownerType === 'guru') {
            $user = $this->guruModel->find($ownerId);
        } else {
            $user = $this->siswaModel->find($ownerId);
        }

        if (!$user) {
            throw new \Exception("Data $ownerType dengan ID $ownerId tidak ditemukan.");
        }

        // token unik
        $token = bin2hex(random_bytes(16));

        // insert record
        $barcodeId = $this->barcodeModel->insert([
            'owner_id'   => $ownerId,
            'owner_type' => $ownerType,
            'token'      => $token,
            'expires_at' => null,
            'file_path'  => null,
        ]);

        // payload target (scan handler)
        $payload = $token;

        // safe filename
        $safe = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $user['nama'] ?? 'user-' . $ownerId));
        $fileName = "qr_{$ownerType}_{$safe}_{$barcodeId}.png";

        $saveDir = FCPATH . 'uploads/qrcodes/';
        if (!is_dir($saveDir)) {
            mkdir($saveDir, 0755, true);
        }
        $filePath = $saveDir . $fileName;

        // generate QR with chillerlan
        $options = new QROptions([
            'eccLevel'   => QRCode::ECC_H,
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'scale'      => 8,
            'margin'     => 2,
        ]);

        (new QRCode($options))->render($payload, $filePath);

        // simpan path relatif ke DB
        $this->barcodeModel->update($barcodeId, [
            'file_path' => 'uploads/qrcodes/' . $fileName
        ]);

        return $barcodeId;
    }

    // Generate list untuk 1 kelas (semua siswa dalam kelas)
    // gantikan fungsi generateClassQR lama dengan yang ini
    private function generateClassQR(int|string $kelasIdentifier): array
    {
        // jika input numeric => diasumsikan id kelas, ambil nama kelas dulu
        if (is_numeric($kelasIdentifier)) {
            $kelasRow = $this->kelasModel->find((int)$kelasIdentifier);
            $kelasName = $kelasRow['nama_kelas'] ?? null;
        } else {
            // jika bukan numeric diasumsikan nama kelas
            $kelasName = (string)$kelasIdentifier;
        }

        if (empty($kelasName)) {
            // tidak ada siswa jika nama kelas tidak ditemukan
            return [];
        }

        // ambil siswa berdasarkan kolom 'kelas' yang berisi nama kelas
        $siswaList = $this->siswaModel->where('kelas', $kelasName)->findAll();

        // kalau tetap kosong, kembalikan array kosong (lebih aman daripada memanggil kolom yang tidak ada)
        if (empty($siswaList)) {
            return [];
        }

        $generated = [];
        foreach ($siswaList as $s) {
            $generated[] = $this->generateSingleQR('siswa', (int)$s['id']);
        }

        return $generated;
    }


    // MAIN: proses generate (mendukung multi-select)
    public function generate()
    {
        $mode = $this->request->getPost('mode');
        // owner_id bisa berupa array (multi) atau single
        $ownerIds = $this->request->getPost('owner_id');
        $kelasId  = $this->request->getPost('kelas_id');

        if ($mode === 'siswa' && empty($ownerIds)) {
            return redirect()->back()->with('error', 'Pilih siswa dulu!');
        }
        if ($mode === 'guru' && empty($ownerIds)) {
            return redirect()->back()->with('error', 'Pilih guru dulu!');
        }
        if ($mode === 'kelas' && empty($kelasId)) {
            return redirect()->back()->with('error', 'Pilih kelas dulu!');
        }

        $generatedIds = [];

        if ($mode === 'siswa') {
            // jika owner_id tunggal -> jadikan array
            $ids = is_array($ownerIds) ? $ownerIds : [$ownerIds];
            foreach ($ids as $id) {
                $generatedIds[] = $this->generateSingleQR('siswa', (int)$id);
            }
        } elseif ($mode === 'guru') {
            $ids = is_array($ownerIds) ? $ownerIds : [$ownerIds];
            foreach ($ids as $id) {
                $generatedIds[] = $this->generateSingleQR('guru', (int)$id);
            }
        } elseif ($mode === 'kelas') {
            $generatedIds = $this->generateClassQR((int)$kelasId);
        } else {
            return redirect()->back()->with('error', 'Mode tidak valid.');
        }

        // jika banyak -> tunjukkan halaman bundle (list qrcodes)
        if (count($generatedIds) > 1) {
            return redirect()->to('absensi/qrcode-bundle?list=' . implode(',', $generatedIds));
        }

        // jika 1 saja -> langsung ke halaman QR tunggal
        return redirect()->to('absensi/qrcode/' . $generatedIds[0]);
    }

    // tampilkan QR satuan
    public function qrcode($id)
    {
        $barcode = $this->barcodeModel->find($id);
        if (!$barcode) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('QR tidak ditemukan');
        }

        $user = ($barcode['owner_type'] === 'guru')
            ? $this->guruModel->find($barcode['owner_id'])
            : $this->siswaModel->find($barcode['owner_id']);

        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Pemilik QR tidak ditemukan.');
        }

        return view('absensi/show_qr', [
            'barcode' => $barcode,
            'user'    => $user,
            'title'   => 'QR Absensi'
        ]);
    }

    // TAMPILKAN BUNDLE (GET?list=1,2,3)
    public function qrcodeBundle()
    {
        $list = $this->request->getGet('list'); // "1,2,3"
        if (!$list) {
            return redirect()->back()->with('error', 'Tidak ada daftar QR untuk ditampilkan.');
        }

        $ids = array_filter(array_map('intval', explode(',', $list)));

        // ambil barcodes
        $barcodes = $this->barcodeModel->whereIn('id', $ids)->findAll();

        // siapkan data lengkap owner per barcode
        $rows = [];
        foreach ($barcodes as $b) {
            $owner = ($b['owner_type'] === 'guru')
                ? $this->guruModel->find($b['owner_id'])
                : $this->siswaModel->find($b['owner_id']);

            $rows[] = [
                'barcode' => $b,
                'owner'   => $owner
            ];
        }

        return view('absensi/show_bundle', [
            'list'  => $rows,
            'title' => 'Bundle QR'
        ]);
    }

    // DOWNLOAD BUNDLE ZIP (POST ids=1,2,3)
    public function downloadBundle()
    {
        $idsString = $this->request->getPost('ids');
        if (!$idsString) {
            return redirect()->back()->with('error', 'Tidak ada file untuk di-download.');
        }

        $ids = array_filter(array_map('intval', explode(',', $idsString)));
        if (empty($ids)) {
            return redirect()->back()->with('error', 'Daftar QR kosong.');
        }

        $barcodes = $this->barcodeModel->whereIn('id', $ids)->findAll();
        if (empty($barcodes)) {
            return redirect()->back()->with('error', 'QR tidak ditemukan.');
        }

        $zipName = 'qr-bundle-' . date('YmdHis') . '.zip';
        $zipPath = WRITEPATH . 'uploads/zip/' . $zipName;

        if (!is_dir(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return redirect()->back()->with('error', 'Gagal membuat ZIP.');
        }

        $added = 0;
        foreach ($barcodes as $b) {
            $fileRel = $b['file_path'] ?? null; // relatif: uploads/qrcodes/...
            if (!$fileRel) continue;
            $fileFull = FCPATH . $fileRel;
            if (file_exists($fileFull)) {
                // buat nama di dalam zip: ownername_token.png
                $owner = ($b['owner_type'] === 'guru')
                    ? $this->guruModel->find($b['owner_id'])
                    : $this->siswaModel->find($b['owner_id']);
                $label = $owner['nama'] ?? $b['owner_type'] . '-' . $b['owner_id'];
                $labelSafe = preg_replace('/[^a-z0-9\-_\.]/i', '_', $label);
                $zipFilename = "{$labelSafe}_{$b['token']}.png";
                $zip->addFile($fileFull, $zipFilename);
                $added++;
            }
        }

        $zip->close();

        if ($added === 0) {
            // hapus file kosong
            @unlink($zipPath);
            return redirect()->back()->with('error', 'Tidak ada file QR untuk di-zip.');
        }

        // kirim file zip sebagai download
        return $this->response->download($zipPath, null)->setFileName($zipName);
    }

    // AJAX: get list siswa/guru minimal (id,nama)
    public function getList($type)
    {
        if ($type === 'guru') {
            $data = $this->guruModel->select('id, nama, foto')->findAll();
        } else {
            $data = $this->siswaModel->select('id, nama, nisn as info, foto, kelas')->findAll();
        }

        return $this->response->setJSON($data);
    }
}
