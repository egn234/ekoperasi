<?php 
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\M_param_manasuka;

class LoginFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->has('logged_in') && session('logged_in')) {
            $flag = session('flag');
            $idgroup = session('idgroup');
            $iduser = session('iduser');

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
                        // Cek apakah anggota sudah mengisi manasuka
                        $m_param_manasuka = new M_param_manasuka();
                        $cek_new_user = $m_param_manasuka->where('idanggota', $iduser)->get()->getResult();
                        
                        if ($cek_new_user != null) {
                            return redirect()->to('anggota/dashboard');
                        } else {
                            // Jika belum mengisi manasuka, redirect ke halaman set-manasuka
                            return redirect()->to('anggota/profile/set-manasuka');
                        }
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