<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $uri = service('uri');
        $path = trim($uri->getPath(), '/');

        // ✅ Abaikan halaman login, logout, dan auth/*
        if (in_array($path, ['login', 'logout']) || strpos($path, 'auth') === 0) {
            return;
        }

        // ✅ Kalau belum login, redirect ke login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = session()->get('role');

        // 🧠 Tambahan penting:
        // Izinkan admin akses semua route kecuali yang benar-benar area siswa login
        // Area siswa login hanya URL yang diawali "siswa/" (ada slash setelahnya)
        // Contoh: siswa/dashboard, siswa/profil → area siswa login
        // Tapi siswa, siswa/list, siswa/save → area admin, tetap boleh
        if ($role === 'admin') {
            // Kalau URL benar-benar area siswa login (siswa/dashboard dst) → blok
            if (preg_match('#^siswa/(dashboard|profil|transaksi)#', $path)) {
                return redirect()->to('/dashboard');
            }
        }

        // 🧩 Cegah siswa masuk ke area admin (semua kecuali siswa/...)
        if ($role === 'siswa') {
            if (!preg_match('#^siswa/#', $path)) {
                return redirect()->to('/siswa/dashboard');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $uri = service('uri');
        $path = trim($uri->getPath(), '/');

        // ✅ Kalau sudah login tapi buka login → redirect sesuai role
        if (session()->get('isLoggedIn') && ($path === 'login' || strpos($path, 'auth') === 0)) {
            $role = session()->get('role');
            if ($role === 'admin') {
                return redirect()->to('/dashboard');
            } elseif ($role === 'siswa') {
                return redirect()->to('/siswa/dashboard');
            }
        }
    }
}
