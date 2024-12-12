<?php 
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class LoginFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->has('logged_in') && session()->get('logged_in')) {
            $flag = session()->get('flag');
            $idgroup = session()->get('idgroup');

            if ($flag === 0) {
                session()->destroy();
                return redirect()->to('/');
            }

            switch ($idgroup) {
                case 1:
                    return redirect()->to('admin/dashboard');
                    break;
                case 2:
                    return redirect()->to('bendahara/dashboard');
                    break;
                case 3:
                    return redirect()->to('ketua/dashboard');
                    break;
                case 4:
                    return redirect()->to('anggota/dashboard');
                    break;
                default:
                    session()->destroy();
                    return redirect()->to('/');
            }
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        
    }
}