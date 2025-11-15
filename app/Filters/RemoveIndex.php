<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RemoveIndex implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $uri = $request->getUri()->getPath();
        if (strpos($uri, 'index.php') !== false) {
            $clean = str_replace('index.php/', '', $uri);
            return redirect()->to(base_url($clean));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu aksi setelah request
    }
}
