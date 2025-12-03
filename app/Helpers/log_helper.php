<?php

use CodeIgniter\I18n\Time;

if (! function_exists('logCrud')) {
    /**
     * Log aktivitas dengan format CRUD / aksi khusus
     *
     * @param string $module  - contoh: siswa, guru, tabungan, absensi
     * @param string $action  - create, update, delete, scan, setor, tarik, export, dll
     * @param string $detail  - deskripsi singkat
     * @param array|null $meta
     */
    function logCrud(string $module, string $action, string $detail, $meta = null)
    {
        helper('activity');

        activity_log([
            'user_id'   => session()->get('user_id'),
            'role'      => session()->get('role'),
            'module'    => $module,
            'action'    => $action,
            'detail'    => $detail,
            'meta'      => $meta,
            'created_at' => Time::now(),
        ]);
    }
}
