<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AbsensiRoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $role = $session->get('role');

        // Jika belum login
        if (!$role) {
            return redirect()->to('/login')->with('error', 'Silakan login dulu.');
        }

        // Cek role allowed per route (dari arguments)
        if ($arguments && !in_array($role, $arguments)) {
            return redirect()
                ->to('/dashboard')
                ->with('error', 'Anda tidak memiliki akses ke menu Absensi.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Nothing
    }
}
