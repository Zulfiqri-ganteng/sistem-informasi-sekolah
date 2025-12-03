<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Maintenance extends BaseController
{
    /**
     * Mengambil jumlah log yang lebih dari 6 bulan
     */
    public function getLogCount()
    {
        $db = \Config\Database::connect();

        $count = $db->query("
            SELECT COUNT(*) AS total
            FROM activity_logs
            WHERE created_at < DATE_SUB(NOW(), INTERVAL 6 MONTH)
        ")->getRow()->total;

        return $this->response->setJSON(['total' => $count]);
    }

    /**
     * Bersihkan log berusia > 6 bulan
     */
    public function cleanLog()
    {
        $db = \Config\Database::connect();

        // hitung sebelum menghapus
        $countBefore = $db->query("
            SELECT COUNT(*) AS total
            FROM activity_logs
            WHERE created_at < DATE_SUB(NOW(), INTERVAL 6 MONTH)
        ")->getRow()->total;

        // proses hapus
        $db->query("
            DELETE FROM activity_logs
            WHERE created_at < DATE_SUB(NOW(), INTERVAL 6 MONTH)
        ");

        // catat aktivitas
        activity_log([
            'module' => 'activity',
            'action' => 'clean',
            'detail' => "Membersihkan {$countBefore} log yang berusia lebih dari 6 bulan",
        ]);

        session()->setFlashdata('success', "Berhasil menghapus {$countBefore} log lama!");

        return redirect()->to('/activity');
    }


    /**
     * 🔥 NEW FEATURE — Hapus Semua Log Sekarang
     */
    public function cleanAll()
    {
        $db = \Config\Database::connect();

        // hitung total sebelum hapus
        $countBefore = $db->query("SELECT COUNT(*) AS total FROM activity_logs")->getRow()->total;

        // hapus semua log
        $db->query("TRUNCATE TABLE activity_logs");

        // logging
        activity_log([
            'module' => 'activity',
            'action' => 'cleanAll',
            'detail' => "Menghapus seluruh log: {$countBefore} baris",
        ]);

        session()->setFlashdata('success', "Berhasil menghapus semua log ({$countBefore} entri)!");

        return redirect()->to('/activity');
    }
}
