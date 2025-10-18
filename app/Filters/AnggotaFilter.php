<?php 
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AnggotaFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if(!session()->get('logged_in')) {
            $alert = '
                <div class="alert alert-danger text-center mb-4 mt-4 pt-2" role="alert">
                    Session habis
                </div>
            ';

            session()->setFlashdata('notif_login', $alert);
            return redirect()->to('/');
        } else {
            $flag = session()->get('flag');
            $idgroup = session()->get('idgroup');

            if ($flag == 0) {
                $alert = '
                    <div class="alert alert-danger text-center mb-4 mt-4 pt-2" role="alert">
                        Akun ini sudah tidak aktif
                    </div>
                ';

                session()->setFlashdata('notif_login', $alert);
                return redirect()->to('/');
            }

            if ($idgroup == 1) {
                return redirect()->to('admin/dashboard');
            }

            if ($idgroup == 2) {
                return redirect()->to('bendahara/dashboard');
            }

            if ($idgroup == 3) {
                return redirect()->to('ketua/dashboard');
            }

            // Untuk anggota (idgroup == 4), cek apakah sudah mengisi manasuka
            if ($idgroup == 4) {
                $m_user = new \App\Models\M_user();
                $m_param_manasuka = new \App\Models\M_param_manasuka();
                $iduser = session()->get('iduser');
                
                // Cek data user untuk validasi KTP
                $user_data = $m_user->getUserById($iduser)[0];
                $currentPath = $request->getUri()->getPath();
                
                // Jika ktp_file null dan tidak sedang di halaman profile#about
                if (!empty($user_data) 
                    && (is_null($user_data->ktp_file) || empty($user_data->ktp_file)) 
                    && strpos($currentPath, 'anggota/profile') === false) {
                    
                    $alert = '
                        <div class="alert alert-warning text-center mb-4 mt-4 pt-2" role="alert">
                            Silahkan lengkapi data KTP Anda terlebih dahulu
                        </div>
                    ';

                    session()->setFlashdata('notif', $alert);
                    return redirect()->to('anggota/profile#about');
                }
                
                $cek_manasuka = $m_param_manasuka->where('idanggota', $iduser)->get()->getResult();
                
                // Jika belum mengisi manasuka dan tidak sedang di halaman set-manasuka atau proses set-manasuka
                if (empty($cek_manasuka)
                    && strpos($currentPath, 'set-manasuka') === false 
                    && strpos($currentPath, 'set-manasuka-proc') === false) {
                    
                    $alert = '
                        <div class="alert alert-info text-center mb-4 mt-4 pt-2" role="alert">
                            Silahkan isi pengajuan manasuka terlebih dahulu
                        </div>
                    ';
                    session()->setFlashdata('notif', $alert);
                    return redirect()->to('anggota/profile/set-manasuka');
                }
            }
        }
    }
        
    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
            
    }
}