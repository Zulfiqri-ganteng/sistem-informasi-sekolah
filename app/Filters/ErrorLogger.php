<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\ErrorLogModel;

class ErrorLogger implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Nothing
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        if ($response->getStatusCode() >= 400) {
            
            $errorModel = new ErrorLogModel();

            $errorData = [
                'level'      => 'ERROR',
                'message'    => $response->getBody(),
                'file'       => 'N/A',
                'line'       => 0,
                'url'        => current_url(),
                'user_id'    => session()->get('id') ?? null,
                'user_role'  => session()->get('role') ?? null,
                'ip_address' => service('request')->getIPAddress(),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            ];

            $errorModel->insert($errorData);
        }
    }
}
