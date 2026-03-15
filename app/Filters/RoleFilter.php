<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        // Pastikan user sudah login dulu sebelum cek role
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        $allowedRoles = $arguments ?? [];

        // Jika role user tidak ada dalam daftar yang diizinkan (arguments)
        if (!empty($allowedRoles) && !in_array($role, $allowedRoles)) {
            // Jika dia client coba masuk ke admin, balikin ke client
            if ($role === 'client') {
                return redirect()->to('/client')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }
            // Jika dia admin coba masuk ke area lain, balikin ke dashboard
            return redirect()->to('/dashboard');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}