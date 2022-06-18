<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $user = session('user_level'); // 1 = admin, 2 = petugas, 3 = user

        $roles = is_array($arguments) ? $arguments : [$arguments];
        if (!in_array($user, $roles)) {
            return redirect()->to('/notfound');
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
