<?php 
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class LoginFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->has('logged_in') && session('logged_in')) {
            $flag = session('flag');
            $idgroup = session('idgroup');

            if ($flag === 0) {
                session_destroy();
                return redirect()->to('/');
            }

            if (isset($idgroup)) {
                switch ($idgroup) {
                    case 1:
                        return redirect()->to('admin/dashboard');
                    case 2:
                        return redirect()->to('bendahara/dashboard');
                    case 3:
                        return redirect()->to('ketua/dashboard');
                    case 4:
                        return redirect()->to('anggota/dashboard');
                    default:
                        break;
                }
            }
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}