<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LaporanModel;
use Dompdf\Dompdf;
use Config\Services;

class Laporan extends BaseController
{
    protected $laporanModel;
    protected $session;

    public function __construct()
    {
        $this->laporanModel = new LaporanModel();
        $this->session = session();
    }

    // ===============================
    // Halaman utama
    // ===============================
    public function index()
    {
        return view('laporan/index', [
            'title' => 'Laporan Tabungan Siswa'
        ]);
    }

    // ===============================
    // Ambil Data (AJAX)
    // ===============================
    public function data()
    {
        $filters = [
            'kelas'   => $this->request->getGet('kelas'),
            'jurusan' => $this->request->getGet('jurusan'),
            'from'    => $this->request->getGet('from'),
            'to'      => $this->request->getGet('to'),
        ];

        // Role-based filtering
        $role = $this->session->get('role');
        $userId = $this->session->get('user_id');

        if ($role === 'guru') {
            $filters['wali_kelas'] = $userId;
        } elseif ($role === 'siswa') {
            $filters['siswa_id'] = $userId;
        }

        $data = $this->laporanModel->getLaporan($filters);

        $meta = [
            'totalSetor' => array_sum(array_column($data, 'total_setor')),
            'totalTarik' => array_sum(array_column($data, 'total_tarik')),
            'totalSaldo' => array_sum(array_column($data, 'saldo')),
        ];

        return $this->response->setJSON([
            'data' => $data,
            'meta' => $meta
        ]);
    }

    // ===============================
    // Detail per siswa
    // ===============================
    public function detail($id)
    {
        return $this->response->setJSON([
            'data' => $this->laporanModel->getDetailTransaksi($id)
        ]);
    }

    // ===============================
    // Export Excel
    // ===============================
    public function exportExcel()
    {
        $data = $this->laporanModel->getLaporan();

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=Laporan_Tabungan_Sekolah.xls");

        echo "<table border='1'>
        <tr style='background:#e8e8e8;font-weight:bold'>
            <th>Nama</th>
            <th>Kelas</th>
            <th>Jurusan</th>
            <th>Total Setoran</th>
            <th>Total Tarikan</th>
            <th>Saldo Akhir</th>
        </tr>";

        foreach ($data as $r) {
            echo "<tr>
                <td>{$r['nama']}</td>
                <td>{$r['kelas']}</td>
                <td>{$r['jurusan']}</td>
                <td align='right'>" . number_format($r['total_setor'], 0, ',', '.') . "</td>
                <td align='right'>" . number_format($r['total_tarik'], 0, ',', '.') . "</td>
                <td align='right'><b>" . number_format($r['saldo'], 0, ',', '.') . "</b></td>
            </tr>";
        }
        echo "</table>";
        exit;
    }

    // ===============================
    // Export PDF (dengan kop sekolah)
    // ===============================
    public function exportPdf()
    {
        $data = [
            'laporan' => $this->laporanModel->getLaporan(),
            'tanggal' => date('d-m-Y H:i'),
            'sekolah' => 'Sistem Informasi Sekolah'
        ];

        $html = view('laporan/pdf', $data);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('Laporan_Tabungan_' . date('Ymd_His') . '.pdf', ["Attachment" => false]);
    }
}
