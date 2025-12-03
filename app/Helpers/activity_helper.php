<?php

use App\Models\ActivityLogModel;

if (!function_exists('activity_log')) {
    /**
     * Write an activity log
     *
     * @param array|string $params
     * @return bool
     */
    function activity_log($params)
    {
        $model = new ActivityLogModel();

        // Jika input berupa string → buat sebagai catatan umum
        if (is_string($params)) {
            $params = [
                'detail' => $params,
                'module' => 'general',
                'action' => 'note'
            ];
        }

        $session = session();

        $userId  = $params['user_id'] ?? ($session->get('user_id') ?? $session->get('id') ?? null);
        $role    = $params['role'] ?? ($session->get('role') ?? 'guest');
        $module  = $params['module'] ?? 'general';
        $action  = $params['action'] ?? 'action';
        $detail  = $params['detail'] ?? null;
        $meta    = $params['meta'] ?? null;

        $ip = $_SERVER['REMOTE_ADDR']     ?? getenv('REMOTE_ADDR');
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

        // Detail & meta dirapikan supaya tidak error json
        $data = [
            'user_id'    => $userId,
            'role'       => $role,
            'module'     => $module,
            'action'     => $action,
            'detail'     => is_array($detail)
                ? json_encode($detail, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR)
                : $detail,

            'meta'       => is_array($meta)
                ? json_encode($meta, JSON_UNESCAPED_UNICODE | JSON_PARTIAL_OUTPUT_ON_ERROR)
                : ($meta ?: null),

            'ip_address' => $ip,
            'user_agent' => $ua,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        try {
            return (bool)$model->insert($data);
        } catch (\Throwable $e) {
            // Jangan ganggu proses utama → log saja di file
            log_message('error', 'Activity log failed: ' . $e->getMessage());
            return false;
        }
    }
}

# =========================================================
#           LOG CRUD SEDERHANA (AMAN 100%)
# =========================================================

if (!function_exists('logCrud')) {

    /**
     * Log CRUD tindakan penting
     *
     * @param string $module
     * @param string $action  create/update/delete
     * @param string|null $detail
     * @param array|null  $meta
     */
    function logCrud($module, $action, $detail = null, $meta = null)
    {
        try {
            activity_log([
                'module' => $module,
                'action' => $action,
                'detail' => $detail,
                'meta'   => $meta,
            ]);
        } catch (\Throwable $e) {
            // Supaya tidak memblokir controller (penting!)
            log_message('error', 'logCrud failed: ' . $e->getMessage());
        }
    }
}
