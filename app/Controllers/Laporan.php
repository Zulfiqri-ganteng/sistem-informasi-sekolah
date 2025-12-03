<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LaporanModel;
use Dompdf\Dompdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory as WordIO;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Laporan extends BaseController
{
    protected $laporanModel;
    protected $session;

    public function __construct()
    {
        $this->laporanModel = new LaporanModel();
        $this->session      = session();
    }

    public function index()
    {
        return view('laporan/index', ['title' => 'Laporan Tabungan Siswa']);
    }

    // -----------------------------------------------------
    // AJAX DataTables
    // -----------------------------------------------------
    public function data()
    {
        $filters = [
            'kelas'   => $this->request->getGet('kelas'),
            'jurusan' => $this->request->getGet('jurusan'),
            'from'    => $this->request->getGet('from'),
            'to'      => $this->request->getGet('to'),
        ];

        $role   = $this->session->get('role');
        $userId = $this->session->get('user_id');

        if ($role === 'guru') {
            $filters['wali_kelas'] = $userId;
        } elseif ($role === 'siswa') {
            $filters['siswa_id'] = $userId;
        }

        $data = $this->laporanModel->getLaporan($filters);

        return $this->response->setJSON([
            'data' => $data,
            'meta' => [
                'totalSetor' => array_sum(array_column($data, 'total_setor')),
                'totalTarik' => array_sum(array_column($data, 'total_tarik')),
                'totalSaldo' => array_sum(array_column($data, 'saldo')),
            ]
        ]);
    }


    // -----------------------------------------------------
    // DETAIL TRANSAKSI
    // -----------------------------------------------------
    public function detail($id)
    {
        return $this->response->setJSON([
            'data' => $this->laporanModel->getDetailTransaksi($id)
        ]);
    }


    // -----------------------------------------------------
    // EXPORT EXCEL
    // -----------------------------------------------------
    public function exportExcel()
    {
        $filters = [
            'kelas'   => $this->request->getGet('kelas') ?? '',
            'jurusan' => $this->request->getGet('jurusan') ?? '',
            'from'    => $this->request->getGet('from') ?? '',
            'to'      => $this->request->getGet('to') ?? '',
        ];

        $data = $this->laporanModel->getLaporan($filters);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Tabungan');

        // Header Title
        // =============================
        //  HEADER TITLE
        // =============================
        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'LAPORAN TABUNGAN SISWA');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 18,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical'   => 'center'
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '003366'] // Navy Blue Premium
            ]
        ]);

        // =============================
        //  SUBHEADER
        // =============================
        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue('A2', 'Tanggal: ' . date('d-m-Y H:i'));
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['size' => 11, 'color' => ['rgb' => '333333']],
            'alignment' => ['horizontal' => 'center'],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => 'E6E6E6'] // Abu luxury
            ]
        ]);

        // =============================
        //  TABLE HEADER
        // =============================
        $headerRow = 4;
        $headers = ['#', 'Nama', 'Kelas', 'Jurusan', 'Total Setor', 'Total Tarik', 'Saldo'];

        $startCol = 'A';
        foreach ($headers as $h) {
            $sheet->setCellValue($startCol . $headerRow, $h);
            $startCol++;
        }

        $sheet->getStyle("A{$headerRow}:G{$headerRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => ['horizontal' => 'center'],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['rgb' => '145A8A'] // Blue steel
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => 'FFFFFF']]
            ]
        ]);

        // =============================
        //  DATA ROWS
        // =============================
        $row = 5;

        foreach ($data as $i => $d) {
            $sheet->setCellValue("A{$row}", $i + 1);
            $sheet->setCellValue("B{$row}", $d['nama']);
            $sheet->setCellValue("C{$row}", $d['kelas']);
            $sheet->setCellValue("D{$row}", $d['jurusan']);
            $sheet->setCellValue("E{$row}", (float) $d['total_setor']);
            $sheet->setCellValue("F{$row}", (float) $d['total_tarik']);
            $sheet->setCellValue("G{$row}", (float) $d['saldo']);

            // Border tiap baris
            $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                'borders' => ['allBorders' => ['borderStyle' => 'thin', 'color' => ['rgb' => 'CCCCCC']]]
            ]);

            // Format ribuan
            $sheet->getStyle("E{$row}:G{$row}")
                ->getNumberFormat()->setFormatCode('#,##0');

            // Highlight saldo merah jika minus
            if ((float)$d['saldo'] < 0) {
                $sheet->getStyle("G{$row}")->applyFromArray([
                    'font' => ['color' => ['rgb' => 'FF0000'], 'bold' => true]
                ]);
            }

            $row++;
        }

        // Autofit
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }


        // Output
        $filename = 'Laporan_Tabungan_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"{$filename}\"");

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }


    // -----------------------------------------------------
    // EXPORT PDF
    // -----------------------------------------------------
    public function exportPdf()
    {
        $filters = [
            'kelas'   => $this->request->getGet('kelas'),
            'jurusan' => $this->request->getGet('jurusan'),
            'from'    => $this->request->getGet('from'),
            'to'      => $this->request->getGet('to'),
        ];

        $data = $this->laporanModel->getLaporan($filters);

        $html = view('laporan/pdf', [
            'laporan' => $data,
            'tanggal' => date('d-m-Y H:i'),
            'sekolah' => 'Sistem Akademik Sekolah Kota Bekasi'
        ]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('Laporan_Tabungan_' . date('Ymd_His') . '.pdf', ['Attachment' => false]);
        exit;
    }


    // -----------------------------------------------------
    // EXPORT WORD
    // -----------------------------------------------------
    public function exportWord()
    {
        $filters = [
            'kelas'   => $this->request->getGet('kelas'),
            'jurusan' => $this->request->getGet('jurusan'),
            'from'    => $this->request->getGet('from'),
            'to'      => $this->request->getGet('to')
        ];

        $data = $this->laporanModel->getLaporan($filters);

        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Calibri');
        $phpWord->setDefaultFontSize(11);

        // ==============================================
        // SECTION LANDSCAPE
        // ==============================================
        $section = $phpWord->addSection([
            'orientation' => 'landscape',
            'marginTop'   => 800,
            'marginLeft'  => 800,
            'marginRight' => 800
        ]);

        // ==============================================
        // FIX LOGO (PNG/JPG)
        // ==============================================
        $logoJpg = FCPATH . 'assets/img/logo.jpg';
        $logoPng = FCPATH . 'assets/img/logo.png';

        $logo = file_exists($logoJpg) ? $logoJpg : $logoPng;

        // ==============================================
        // KOP SURAT
        // ==============================================
        $kop = $section->addTable();
        $kop->addRow();

        // LOGO
        $kop->addCell(1500)->addImage($logo, [
            'width' => 70,
            'alignment' => 'left'
        ]);

        // Identitas Sekolah
        $cell = $kop->addCell(9000);
        $cell->addText('PEMERINTAH KOTA BEKASI', ['bold' => true, 'size' => 14], ['alignment' => 'center']);
        $cell->addText('Sistem Informasi Sekolah', ['bold' => true, 'size' => 18], ['alignment' => 'center']);
        $cell->addText('Jl. Pendidikan No. 123, Kota Bekasi — Telp: (021) 1234567', ['size' => 11], ['alignment' => 'center']);

        // Garis tebal bawah kop
        $section->addLine([
            'weight' => 3,
            'color'  => '000000'
        ]);

        // Garis tipis kedua
        $section->addLine([
            'weight' => 1,
            'color'  => '000000'
        ]);

        $section->addTextBreak(1);

        // ==============================================
        // JUDUL LAPORAN
        // ==============================================
        $section->addText(
            'LAPORAN TABUNGAN SISWA',
            ['bold' => true, 'size' => 16],
            ['alignment' => 'center']
        );

        $section->addText(
            'Tanggal: ' . date('d-m-Y H:i'),
            ['size' => 11],
            ['alignment' => 'center']
        );

        $section->addTextBreak(1);

        // ==============================================
        // STYLE TABEL PREMIUM
        // ==============================================
        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '444444',
            'cellMargin' => 80
        ];
        $phpWord->addTableStyle('TabelPremium', $tableStyle);
        $table = $section->addTable('TabelPremium');

        // HEADER TABEL (dengan warna premium)
        $table->addRow();

        $headerStyle = ['bold' => true, 'color' => 'FFFFFF', 'size' => 11];
        $headerCellStyle = ['bgColor' => '1F4E78']; // Navy premium

        $table->addCell(800, $headerCellStyle)->addText('#', $headerStyle);
        $table->addCell(2000, $headerCellStyle)->addText('Nama', $headerStyle);
        $table->addCell(1500, $headerCellStyle)->addText('Kelas', $headerStyle);
        $table->addCell(3500, $headerCellStyle)->addText('Jurusan', $headerStyle);
        $table->addCell(1500, $headerCellStyle)->addText('Total Setor', $headerStyle);
        $table->addCell(1500, $headerCellStyle)->addText('Total Tarik', $headerStyle);
        $table->addCell(1500, $headerCellStyle)->addText('Saldo', $headerStyle);

        // ==============================================
        // DATA TABEL
        // ==============================================
        $no = 1;
        foreach ($data as $r) {
            $table->addRow();
            $table->addCell(800)->addText($no++);
            $table->addCell(2000)->addText($r['nama']);
            $table->addCell(1500)->addText($r['kelas']);
            $table->addCell(3500)->addText($r['jurusan']);
            $table->addCell(1500)->addText(number_format($r['total_setor'], 0, ',', '.'));
            $table->addCell(1500)->addText(number_format($r['total_tarik'], 0, ',', '.'));
            $table->addCell(1500)->addText(number_format($r['saldo'], 0, ',', '.'));
        }

        $section->addTextBreak(3);

        // ==============================================
        // TANDA TANGAN
        // ==============================================
        $section->addText('Kepala Sekolah,', [], ['alignment' => 'right']);
        $section->addTextBreak(4);
        $section->addText('_________________________', [], ['alignment' => 'right']);

        // ==============================================
        // OUTPUT FILE
        // ==============================================
        $fileName = 'Laporan_Tabungan_' . date('Ymd_His') . '.docx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header("Content-Disposition: attachment; filename=\"$fileName\"");

        $writer = WordIO::createWriter($phpWord, 'Word2007');
        $writer->save('php://output');
        exit;
    }
}
