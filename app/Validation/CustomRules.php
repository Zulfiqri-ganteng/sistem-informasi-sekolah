<?php namespace App\Validation;

/**
 * CustomRules
 * Class ini berisi aturan validasi khusus yang tidak ada di CodeIgniter secara default.
 */
class CustomRules
{
    /**
     * Memastikan string adalah format waktu 24 jam yang valid (HH:MM).
     * Contoh: 08:00, 23:59.
     * * @param string|null $str String waktu yang divalidasi.
     * @return bool
     */
    public function valid_time_format(?string $str): bool
    {
        // Regex yang memeriksa format HH:MM, H antara 00-23, dan M antara 00-59
        // Anda bisa menyesuaikan regex ini tergantung format waktu yang Anda harapkan.
        return (bool) preg_match('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/', $str);
    }

    /**
     * Memastikan nilai bidang saat ini (jam_selesai) lebih lambat dari nilai bidang lainnya (jam_mulai).
     * * @param string|null $end_time Waktu selesai (nilai bidang saat ini).
     * @param string $start_time_field Nama bidang untuk waktu mulai.
     * @param array $data Semua data input.
     * @return bool
     */
    public function later_than(?string $end_time, string $start_time_field, array $data): bool
    {
        // Pastikan waktu selesai dan waktu mulai ada
        if (is_null($end_time) || !isset($data[$start_time_field])) {
            return false;
        }

        $start_time = $data[$start_time_field];

        // Konversi ke timestamp untuk perbandingan yang akurat
        $start_timestamp = strtotime($start_time);
        $end_timestamp = strtotime($end_time);

        // Membandingkan waktu
        return $end_timestamp > $start_timestamp;
    }
}