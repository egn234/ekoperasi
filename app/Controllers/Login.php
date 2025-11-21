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
        $this->m_user = new M_user();
        $this->m_param_manasuka = new M_param_manasuka();
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
        // Load reCAPTCHA helper
        helper('recaptcha');
        
        // Validate reCAPTCHA
        $recaptchaToken = request()->getPost('recaptcha_token');
        $validation = validate_recaptcha($recaptchaToken);
        
        if (!$validation['success']) {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Captcha tidak sesuai',
                    'status' => 'warning'
                ]
            );
            
            $error = ['notif_login' => $alert];
            session()->setFlashdata($error);
            return redirect()->to('/');
        }

        $username = request()->getPost('username');
        $pass = md5(request()->getPost('password'));
        $status = $this->m_user->countUsername($username)[0]->hitung;

        if ($status != 0) {
            $user = $this->m_user->getUser($username)[0];
            
            if (password_verify($pass, $user->pass)) {
                if ($user->verified == 0) {
                    $alert = '
                    <div class="alert alert-danger text-center mb-4 mt-4 pt-2" role="alert">
                        Akun ini belum diverifikasi oleh admin
                    </div>
                    ';

                    session()->setFlashdata('notif_login', $alert);
                    session()->setFlashdata('s_username', $username);
                    return redirect()->to('/');
                }
                
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
                            // Set flash message untuk user baru
                            $alert = '
                            <div class="alert alert-info text-center mb-4 mt-4 pt-2" role="alert">
                                Selamat datang di Ekoperasi! Silahkan isi pengajuan manasuka terlebih dahulu
                            </div>
                            ';
                            session()->setFlashdata('notif', $alert);
                            return redirect()->to('anggota/profile/set-manasuka');
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

    public function forgot_password_proc()
    {
        $username = request()->getPost('username');
        $nik = request()->getPost('nik');
        $email = request()->getPost('email');
        $nomor_telepon = request()->getPost('nomor_telepon');
        
        // Load reCAPTCHA helper
        helper('recaptcha');
        
        // Validate reCAPTCHA
        $recaptchaToken = request()->getPost('recaptcha_token');
        $validation = validate_recaptcha($recaptchaToken);
        
        if (!$validation['success']) {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Captcha tidak sesuai',
                    'status' => 'warning'
                ]
            );

            $dataset['notif'] = $alert;
            $dataset['username'] = $username;
            $dataset['nik'] = $nik;
            $dataset['nomor_telepon'] = $nomor_telepon;
            $dataset['email'] = $email;

            $dataset += ['notif' => $alert];
            session()->setFlashdata($dataset);
            return redirect()->back();
        }

        $cek_data = $this->m_user
            ->where('username', $username)
            ->where('nik', $nik)
            ->where('email', $email)
            ->where('nomor_telepon', $nomor_telepon)
            ->countAllResults();

        if ($cek_data == 0) {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'User tidak ditemukan',
                    'status' => 'danger'
                ]
            );

            session()->setFlashdata('notif', $alert);
            return redirect()->back();
        }

        $cek_token = $this->m_user
            ->where('username', $username)
            ->where('pass_reset_status', 0)
            ->countAllResults();
        
        if($cek_token != 0) {
            $token = bin2hex(random_bytes(16));
            $db = \Config\Database::connect();
            $builder = $db->table('tb_user');
            $builder->where('username', $username)->update([
                'pass_reset_token' => $token,
                'pass_reset_status' => 1
            ]);
        } else {
            $token = $this->m_user->where('username', $username)->get()->getResult()[0]->pass_reset_token;
        }
        
        $alert = view(
            'partials/notification-alert', 
            [
                'notif_text' => 'Permintaan reset password berhasil, tolong buat password baru',
                'status' => 'success'
            ]
        );
        
        $data_session = [
            'username' => request()->getPost('username'),
            'notif' => $alert
        ];

        session()->setFlashdata($data_session);
        return redirect()->to('/reset_password?token=' . $token);
    }

    public function reset_password()
    {
        $token = request()->getGet('token');
        
        if ($token == null) {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Forbidden',
                    'status' => 'danger'
                ]
            );
            
            session()->setFlashdata('notif_login', $alert);
            return redirect()->to('/');
        }

        $data = [
            'title_meta' => view('partials/title-meta', ['title' => 'Reset Password']),
            'token' => $token
        ];

        return view('reset-password', $data);
    }

    public function update_password($token)
    {
        // Load reCAPTCHA helper
        helper('recaptcha');
        
        // Validate reCAPTCHA
        $recaptchaToken = request()->getPost('recaptcha_token');
        $validation = validate_recaptcha($recaptchaToken);
        
        if (!$validation['success']) {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Captcha tidak sesuai',
                    'status' => 'warning'
                ]
            );

            $error = ['notif' => $alert];
            session()->setFlashdata($error);
            return redirect()->back();
        }

        $pass = request()->getPost('pass');
        $pass2 = request()->getPost('pass2');

        // TODO: CAPTCHA here

        $cek_token = $this->m_user
            ->where('pass_reset_token', $token)
            ->where('pass_reset_status', 1)
            ->countAllResults();

        if ($cek_token == 0) {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Forbidden',
                    'status' => 'danger'
                ]
            );
            
            session()->setFlashdata('notif_login', $alert);
            return redirect()->to('/');
        }

        if ($pass != $pass2) {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Konfirmasi password tidak sesuai',
                    'status' => 'danger'
                ]
            );
            
            session()->setFlashdata('notif', $alert);
            return redirect()->back();
        }

        $hash = password_hash(md5($pass), PASSWORD_DEFAULT);

        $db = \Config\Database::connect();
        $builder = $db->table('tb_user');
        $builder->where('pass_reset_token', $token)->update([
            'pass' => $hash,
            'pass_reset_status' => 0
        ]);

        $alert = view(
            'partials/notification-alert', 
            [
                'notif_text' => 'Password berhasil diubah',
                'status' => 'success'
            ]
        );
        
        $data_session = ['notif_login' => $alert];
        session()->setFlashdata($data_session);
        return redirect()->to('/');
    }
}