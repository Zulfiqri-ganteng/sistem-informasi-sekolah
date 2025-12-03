<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class TestMail extends Controller
{
    public function index()
    {
        $email = \Config\Services::email();

        $email->setTo('email_tujuan@gmail.com'); // ubah ke email kamu sendiri dulu
        $email->setFrom('noreply@zulfiqri.com', 'Sistem Informasi Sekolah');
        $email->setSubject('Tes SMTP Rumahweb');
        $email->setMessage('<h3>Halo!</h3><p>Email ini dikirim otomatis dari sistem tabungan Sistem Informasi Sekolah.</p>');

        if ($email->send()) {
            echo '✅ Email berhasil dikirim! Cek inbox/spam ya.';
        } else {
            echo '❌ Gagal kirim email:<br><pre>';
            print_r($email->printDebugger(['headers']));
            echo '</pre>';
        }
    }
}
