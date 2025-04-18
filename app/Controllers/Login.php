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
		// Ambil data reCAPTCHA response
		$recaptchaResponse = request()->getPost('recaptcha_token');
		$recaptchaSecret = getenv('RECAPTCHA_SECRET_KEY'); // Ganti dengan Secret Key Anda

		// Validasi reCAPTCHA ke Google
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data = [
			'secret'   => $recaptchaSecret,
			'response' => $recaptchaResponse,
			'remoteip' => request()->getIPAddress()
		];

		$options = [
			'http' => [
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($data)
			]
		];

		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		$response = json_decode($result);

        // Periksa hasil validasi
        if (!$response->success) {
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
					}
					elseif($user->idgroup == 2){
						return redirect()->to('bendahara/dashboard');
					}
					elseif($user->idgroup == 3){
						return redirect()->to('ketua/dashboard');
					}
					elseif($user->idgroup == 4){
						$cek_new_user = $this->m_param_manasuka->where('idanggota', $userdata['iduser'])->get()->getResult();

						if ($cek_new_user != null) {
							return redirect()->to('anggota/dashboard');
						}else{
				    		echo "<script>alert('Selamat datang di Ekoperasi! silahkan isi pengajuan manasuka terlebih dahulu'); window.location.href = '".base_url()."/anggota/profile/set-manasuka';</script>";
				    		exit;
						}
					}
				}
				else {
					$alert = '
						<div class="alert alert-danger text-center mb-4 mt-4 pt-2" role="alert">
							Akun ini sudah tidak aktif
						</div>
					';

					session()->setFlashdata('notif_login', $alert);
					session()->setFlashdata('s_username', $username);
					return redirect()->to('/');
				}
			}
			else {
				$alert = '
					<div class="alert alert-danger text-center mb-4 mt-4 pt-2" role="alert">
						Password tidak sesuai
					</div>
				';

				session()->setFlashdata('notif_login', $alert);
				session()->setFlashdata('s_username', $username);
				return redirect()->to('/');
			}
		}	
		else {
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

}