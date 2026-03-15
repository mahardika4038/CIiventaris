<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Cek apakah user sudah login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login dulu.');
        }
        
        // Redirect berdasarkan role jika mencoba akses halaman yang tidak sesuai
        $role = session()->get('role');
        $uri = $request->getUri()->getPath();
        
        // Jika client mencoba akses dashboard
        if ($role === 'client' && strpos($uri, 'dashboard') === 0) {
            return redirect()->to('/client');
        }
        
        // Jika admin/superadmin mencoba akses client area
        if (in_array($role, ['superadmin', 'admin']) && strpos($uri, 'client') === 0) {
            return redirect()->to('/dashboard');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Kosongkan
    }
}

