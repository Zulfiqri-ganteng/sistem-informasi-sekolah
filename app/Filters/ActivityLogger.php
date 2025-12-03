<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ActivityLogger implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // nothing on before by default
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Only log if this response is not 4xx/5xx (optional)
        $status = $response->getStatusCode();
        if ($status >= 400) {
            // Optionally log errors elsewhere. We skip to avoid noise.
            return;
        }

        // Decide what to log: only log non-GET requests or specific actions
        $method = $request->getMethod();
        $uri = $request->getUri()->getPath();

        // You can customize rules: log POST/PUT/DELETE + important GET endpoints
        $shouldLog = in_array($method, ['post', 'put', 'delete']) || preg_match('#/(login|logout|upload|export|scan)#i', $uri);

        if (!$shouldLog) {
            return;
        }

        // Get user info safely (depends on your auth session keys)
        $session = session();
        $userId = $session->get('user_id') ?? null;
        $role = $session->get('role') ?? ($session->get('level') ?? 'guest');

        // Build detail: route + inputs summary
        $input = $request->getPost() ?? [];
        // Avoid storing passwords and large binary data
        if (isset($input['password'])) {
            $input['password'] = '***';
        }

        // If files present, note file names
        $files = [];
        try {
            foreach ($request->getFiles() as $f) {
                if (is_array($f)) {
                    foreach ($f as $single) {
                        if ($single && $single->isValid()) $files[] = $single->getClientName();
                    }
                } elseif ($f && $f->isValid()) {
                    $files[] = $f->getClientName();
                }
            }
        } catch (\Exception $e) {
            // ignore file read errors
        }

        $detail = [
            'method' => strtoupper($method),
            'uri' => $uri,
            'inputs' => $input,
            'files' => $files,
            'status' => $status
        ];

        // call helper activity_log
        helper('activity');
        activity_log([
            'user_id' => $userId,
            'role' => $role,
            'module' => trim(explode('/', $uri)[0] ?? 'app'),
            'action' => $method . ' ' . $uri,
            'detail' => json_encode($detail, JSON_UNESCAPED_UNICODE),
            'meta' => [
                'response_status' => $status
            ]
        ]);
    }
}
