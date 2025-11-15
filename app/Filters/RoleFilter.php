<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $role = $session->get('role');

        if (!$role) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu!');
        }

        // Jika route ini membatasi role tertentu
        if ($arguments && !in_array($role, $arguments)) {
            // Redirect berdasarkan role
            switch ($role) {
                case 'admin':
                    return redirect()->to('/dashboard')->with('error', 'Akses ditolak!');
                case 'guru':
                    return redirect()->to('/guru/dashboard')->with('error', 'Akses ditolak!');
                case 'siswa':
                    return redirect()->to('/siswa/dashboard')->with('error', 'Akses ditolak!');
                default:
                    return redirect()->to('/login');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
