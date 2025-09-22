<?php 
namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_param_manasuka;

class login extends Controller
{
    protected $m_user;
    protected $m_param_manasuka;

    function __construct()
    {
        $this->m_user = model(M_user::class);
        $this->m_param_manasuka = model(M_param_manasuka::class);
    }

    public function index()
    {
        $data = [
            'title_meta' => view('partials/title-meta', ['title' => 'Login'])
        ];

        return view('auth-login', $data);
    }

    public function login_proc()
    {
        $username = request()->getPost('username');
        $pass = md5(request()->getPost('password'));
        $status = $this->m_user->countUsername($username)[0]->hitung;

        if ($status != 0) {
            $user = $this->m_user->getUser($username)[0];
            
            if (password_verify($pass, $user->pass)) {
                $flag = $user->flag;
                
                if ($flag != 0) {
                    $userdata = [
                        'iduser' => $user->iduser,
                        'username' => $user->username,
                        'flag' => $user->flag,
                        'idgroup' => $user->idgroup,
                        'logged_in' => TRUE
                    ];
                    session()->set($userdata);

                    if(password_needs_rehash($user->pass, PASSWORD_DEFAULT)){
                        $newHash = password_hash($pass, PASSWORD_DEFAULT);
                        $this->m_user->updateUser($user->iduser, $newHash);
                    }
                    
                    if($user->idgroup == 1){
                        return redirect()->to('admin/dashboard');
                        echo session()->get('username');
                    } elseif($user->idgroup == 2) {
                        return redirect()->to('bendahara/dashboard');
                    } elseif($user->idgroup == 3) {
                        return redirect()->to('ketua/dashboard');
                    } elseif($user->idgroup == 4) {
                        $cek_new_user = $this->m_param_manasuka->where('idanggota', $userdata['iduser'])->get()->getResult();

                        if ($cek_new_user != null) {
                            return redirect()->to('anggota/dashboard');
                        } else {
                            echo "<script>alert('Selamat datang di Ekoperasi! silahkan isi pengajuan manasuka terlebih dahulu'); window.location.href = '".base_url()."/anggota/profile/set-manasuka';</script>";
                            exit;
                        }
                    }
                } else {
                    $alert = '
                        <div class="alert alert-danger text-center mb-4 mt-4 pt-2" role="alert">
                            Akun ini sudah tidak aktif
                        </div>
                    ';

                    session()->setFlashdata('notif_login', $alert);
                    session()->setFlashdata('s_username', $username);
                    return redirect()->to('/');
                }
            } else {
                $alert = '
                    <div class="alert alert-danger text-center mb-4 mt-4 pt-2" role="alert">
                        Password tidak sesuai
                    </div>
                ';

                session()->setFlashdata('notif_login', $alert);
                session()->setFlashdata('s_username', $username);
                return redirect()->to('/');
            }
        } else {
            $alert = '
                <div class="alert alert-danger text-center mb-4 mt-4 pt-2" role="alert">
                    Akun tidak terdaftar
                </div>
            ';

            session()->setFlashdata('notif_login', $alert);
            session()->setFlashdata('s_username', $username);
            return redirect()->to('/');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }

    public function forgot_password()
    {
        $data = ['title_meta' => view('partials/title-meta', ['title' => 'Lupa Password'])];
        return view('forgot-password', $data);
    }

    public function reset_password()
    {
        $username = request()->getPost('username');
        $cek_username = $this->m_user->countUsername($username)[0]->hitung;

        if ($cek_username != 0) {
            // generate random hash password
            $temp_plain = bin2hex(random_bytes(6)); // example: "4f3a9b7c2d1e"

            // add password temp
            $dataset = [
                'temp_password_hash' =>  $temp_plain,
                'temp_password_expires_at' => date('Y-m-d H:i:s', strtotime('+7 days')),
                'must_reset_password'	=> 1
            ];

            $this->m_user->updateUserByUsername($username, $dataset);
        } else {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Username tidak ditemukan',
                    'status' => 'danger'
                ]
            );
            
            session()->setFlashdata('notif', $alert);
            return redirect()->back();
        }

        $alert = view(
            'partials/notification-alert', 
            [
                'notif_text' => 'Permintaan reset password berhasil dikirim',
                'status' => 'success'
            ]
        );
        
        $data_session = [
            'username' => request()->getPost('username'),
            'notif' => $alert
        ];

        session()->setFlashdata($data_session);
        return redirect()->back();
    }
}