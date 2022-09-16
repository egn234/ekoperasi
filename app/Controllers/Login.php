<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_user;

class login extends Controller
{
	function __construct()
	{
		$this->m_user = new M_user();
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
		$username = $this->request->getPost('username');
		$pass = md5($this->request->getPost('password'));
		$status = $this->m_user->countUsername($username)[0]->hitung;

		if ($status != 0) {
			$user = $this->m_user->getUser($username)[0];
			
			if ($user->pass == $pass) {	
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
						return redirect()->to('anggota/dashboard');
					}
				}
				else {
					$alert = view(
						'partials/notification-alert', 
						[
							'notif_text' => 'Akun ini sudah tidak aktif',
							'status' => 'danger'
						]
					);

					session()->setFlashdata('notif_login', $alert);
					session()->setFlashdata('s_username', $username);
					return redirect()->to('/');
				}
			}
			else {
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'Password tidak sesuai',
						'status' => 'danger'
					]
				);

				session()->setFlashdata('notif_login', $alert);
				session()->setFlashdata('s_username', $username);
				return redirect()->to('/');
			}
		}	
		else {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Akun Tidak Terdaftar',
					'status' => 'danger'
				]
			);

			session()->setFlashdata('notif_login', $alert);
			session()->setFlashdata('s_username', $username);
			return redirect()->to('/');
		}
	}

	public function logout()
	{
		session_destroy();
		redirect()->to('/');
	}

}