<?php

namespace App\Controllers\Import;
helper('activity');

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\SiswaModel;
use App\Models\UserModel;

class SiswaImport extends BaseController
{
    protected $db;
    protected $siswaModel;
    protected $userModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->siswaModel = new SiswaModel();
        $this->userModel = new UserModel();
        helper(['text', 'filesystem']);
    }

    public function index()
    {
        return view('siswa/import');
    }

    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['nisn', 'nama', 'jk', 'kelas', 'jurusan', 'telepon', 'email', 'alamat'];
        $sheet->fromArray($headers, null, 'A1');

        $example = [
            ['20150001', 'Siswa A', 'L', 'X TKJ 1', 'Teknik Jaringan Komputer', '08123456789', 'siswaA@example.com', 'Bekasi'],
            ['20150002', 'Siswa B', 'P', 'XI RPL 2', 'Rekayasa Perangkat Lunak', '', '', '']
        ];
        $sheet->fromArray($example, null, 'A2');

        $writer = new Xlsx($spreadsheet);
        $filename = 'template_import_siswa.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $writer->save('php://output');
        exit;
    }

    /**
     * preview (AJAX)
     */
    public function preview()
    {
        $file = $this->request->getFile('file');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['error' => 'File tidak valid atau belum dipilih.'])->setStatusCode(400);
        }

        $ext = $file->getClientExtension();
        if (!in_array($ext, ['xlsx', 'xls', 'csv'])) {
            return $this->response->setJSON(['error' => 'Tipe file tidak didukung. Gunakan .xlsx/.xls/.csv'])->setStatusCode(400);
        }

        $tmp = $file->getTempName();

        try {
            $spreadsheet = IOFactory::load($tmp);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['error' => 'Gagal membaca file: ' . $e->getMessage()])->setStatusCode(500);
        }

        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        // minimal 2 baris (header + data)
        if (count($rows) <= 1) {
            return $this->response->setJSON(['error' => 'File kosong atau tidak ada data.'])->setStatusCode(400);
        }

        // build lookup maps kelas & jurusan (lowercase => canonical)
        $kelasRows = $this->db->table('kelas')->select('id, nama_kelas')->get()->getResultArray();
        $kelasMap = [];
        foreach ($kelasRows as $r) {
            $k = trim(strtolower(preg_replace('/\s+/', ' ', $r['nama_kelas'])));
            $kelasMap[$k] = $r['nama_kelas'];
        }
        $jurRows = $this->db->table('jurusan')->select('id, nama_jurusan')->get()->getResultArray();
        $jurMap = [];
        foreach ($jurRows as $r) {
            $j = trim(strtolower(preg_replace('/\s+/', ' ', $r['nama_jurusan'])));
            $jurMap[$j] = $r['nama_jurusan'];
        }

        $preview = [];
        $meta = [
            'errorRows' => [],
            'autoMappedRows' => []
        ];

        foreach ($rows as $rIdx => $r) {
            if ($rIdx == 1) continue; // header
            $nisn    = trim((string)($r['A'] ?? ''));
            $nama    = trim((string)($r['B'] ?? ''));
            $jkRaw   = trim((string)($r['C'] ?? ''));
            $kelas   = trim((string)($r['D'] ?? ''));
            $jurusan = trim((string)($r['E'] ?? ''));
            $telepon = trim((string)($r['F'] ?? ''));
            $email   = trim((string)($r['G'] ?? ''));
            $alamat  = trim((string)($r['H'] ?? ''));

            // skip empty rows
            if ($nisn === '' && $nama === '' && $jkRaw === '' && $kelas === '' && $jurusan === '' && $telepon === '' && $email === '' && $alamat === '') {
                continue;
            }

            $messages = [];
            // normalize JK
            $jk = '';
            $jkNorm = strtoupper(trim($jkRaw));
            if (in_array($jkNorm, ['L', 'P'])) $jk = $jkNorm;
            else {
                $first = strtoupper(substr($jkNorm, 0, 1));
                if ($first === 'L') $jk = 'L';
                if ($first === 'P') $jk = 'P';
            }
            if ($jk === '') $messages[] = 'JK invalid (pakai L/P)';

            if ($nisn === '') $messages[] = 'NISN kosong';
            if ($nama === '') $messages[] = 'Nama kosong';

            // duplicate check
            $dup = $this->db->table('siswa')->where('nisn', $nisn)->get()->getRowArray();
            if ($dup) $messages[] = 'Duplikat: NISN sudah ada';

            // mapping kelas / jurusan
            $kKey = trim(strtolower(preg_replace('/\s+/', ' ', $kelas)));
            $jKey = trim(strtolower(preg_replace('/\s+/', ' ', $jurusan)));
            $kelasMatch = $kelasMap[$kKey] ?? null;
            $jurusanMatch = $jurMap[$jKey] ?? null;
            if ($kelasMatch) $meta['autoMappedRows'][] = count($preview); // mark as auto-mapped
            if ($jurusanMatch) $meta['autoMappedRows'][] = count($preview);

            if (!$kelasMatch && $kelas !== '') {
                $messages[] = 'Kelas tidak ditemukan (akan dibuat saat finalize jika dikonfirmasi)';
            }
            if (!$jurusanMatch && $jurusan !== '') {
                $messages[] = 'Jurusan tidak ditemukan (akan dibuat saat finalize jika dikonfirmasi)';
            }

            $status = empty($messages) ? 'valid' : 'warning';
            if (!empty($messages)) $meta['errorRows'][] = count($preview);

            $preview[] = [
                'row' => $rIdx,
                'nisn' => $nisn,
                'nama' => $nama,
                'jk' => $jk,
                'kelas' => $kelas,
                'kelas_match' => $kelasMatch,
                'jurusan' => $jurusan,
                'jurusan_match' => $jurusanMatch,
                'telepon' => $telepon,
                'email' => $email,
                'alamat' => $alamat,
                'status' => $status,
                'messages' => $messages
            ];
        }

        return $this->response->setJSON([
            'total' => count($preview),
            'preview' => $preview,
            'meta' => $meta
        ]);
    }

    /**
     * finalize (AJAX) - menerima rows dari client (editable)
     */
    public function finalize()
    {
        $payload = $this->request->getJSON(true);
        if (!$payload || !isset($payload['rows']) || !is_array($payload['rows'])) {
            return $this->response->setJSON(['error' => 'Payload invalid'])->setStatusCode(400);
        }

        $rows = $payload['rows'];

        // prepare kelas/jurusan maps (to avoid repeated selects)
        $kelasRows = $this->db->table('kelas')->select('id, nama_kelas')->get()->getResultArray();
        $kelasMap = [];
        foreach ($kelasRows as $r) {
            $k = trim(strtolower(preg_replace('/\s+/', ' ', $r['nama_kelas'])));
            $kelasMap[$k] = $r['id'];
        }
        $jurRows = $this->db->table('jurusan')->select('id, nama_jurusan')->get()->getResultArray();
        $jurMap = [];
        foreach ($jurRows as $r) {
            $j = trim(strtolower(str_replace("\t", ' ', $r['nama_jurusan'])));
            $jurMap[$j] = $r['id'];
        }

        $results = [
            'total' => count($rows),
            'inserted' => 0,
            'skipped' => 0,
            'errors' => []
        ];

        $this->db->transStart();

        foreach ($rows as $idx => $r) {
            $rown = $r['row'] ?? ($idx + 1);
            $nisn = trim($r['nisn'] ?? '');
            $nama = trim($r['nama'] ?? '');
            $jkRaw = trim($r['jk'] ?? '');
            $kelasRaw = trim($r['kelas'] ?? '');
            $jurRaw = trim($r['jurusan'] ?? '');
            $telepon = trim($r['telepon'] ?? '');
            $email = trim($r['email'] ?? '');
            $alamat = trim($r['alamat'] ?? '');

            $rowErrors = [];
            if ($nisn === '') $rowErrors[] = 'NISN kosong';
            if ($nama === '') $rowErrors[] = 'Nama kosong';

            $jk = '';
            $jkNorm = strtoupper($jkRaw);
            if (in_array($jkNorm, ['L', 'P'])) $jk = $jkNorm;
            else {
                $first = strtoupper(substr($jkNorm, 0, 1));
                if ($first === 'L') $jk = 'L';
                if ($first === 'P') $jk = 'P';
            }
            if ($jk === '') $rowErrors[] = 'JK invalid';

            if (!empty($rowErrors)) {
                $results['skipped']++;
                $results['errors'][] = ['row' => $rown, 'nisn' => $nisn, 'messages' => $rowErrors];
                continue;
            }

            // duplicate check
            $existing = $this->db->table('siswa')->where('nisn', $nisn)->get()->getRowArray();
            if ($existing) {
                $results['skipped']++;
                $results['errors'][] = ['row' => $rown, 'nisn' => $nisn, 'messages' => ['Duplikat: NISN sudah ada']];
                continue;
            }

            // ensure kelas exists (create if missing)
            $kKey = trim(strtolower(preg_replace('/\s+/', ' ', $kelasRaw)));
            $kelasId = $kelasMap[$kKey] ?? null;
            if (!$kelasId && $kelasRaw !== '') {
                // insert new kelas (safe insert)
                $this->db->table('kelas')->insert(['nama_kelas' => $kelasRaw]);
                $kelasId = $this->db->insertID();
                $kelasMap[$kKey] = $kelasId;
            }

            // ensure jurusan exists (create if missing)
            $jKey = trim(strtolower(preg_replace('/\s+/', ' ', $jurRaw)));
            $jurId = $jurMap[$jKey] ?? null;
            if (!$jurId && $jurRaw !== '') {
                $this->db->table('jurusan')->insert(['nama_jurusan' => $jurRaw]);
                $jurId = $this->db->insertID();
                $jurMap[$jKey] = $jurId;
            }

            // Compose final kelas/jurusan strings to store into siswa table
            // NOTE: your schema stores nama_kelas/nama_jurusan in siswa.kelas and siswa.jurusan in current app,
            // so we keep compatibility: store the textual name (not id) to avoid breaking other features.
            $kelasFinal = $kelasRaw !== '' ? $kelasRaw : null;
            $jurusanFinal = $jurRaw !== '' ? $jurRaw : null;

            // insert siswa
            $this->db->table('siswa')->insert([
                'user_id' => null,
                'nisn' => $nisn,
                'nama' => $nama,
                'jenis_kelamin' => $jk,
                'kelas' => $kelasFinal,
                'jurusan' => $jurusanFinal,
                'telepon' => $telepon ?: null,
                'alamat' => $alamat ?: null,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $siswa_id = $this->db->insertID();

            // create user record
            $passwordPlain = $nisn; // initial password = NISN (sesuai kebijakan Anda)
            $this->db->table('users')->insert([
                'username' => $nisn,
                'password' => password_hash($passwordPlain, PASSWORD_DEFAULT),
                'role' => 'siswa',
                'status' => 1,
                'siswa_id' => $siswa_id,
                'email' => $email ?: null,
                'nama' => $nama,
                'created_at' => date('Y-m-d H:i:s')
            ]);

            $user_id = $this->db->insertID();

            // update siswa.user_id
            $this->db->table('siswa')->where('id', $siswa_id)->update(['user_id' => $user_id]);

            $results['inserted']++;
        }

        $this->db->transComplete();

        return $this->response->setJSON($results);
    }
}
