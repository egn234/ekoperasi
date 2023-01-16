<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_user;
use App\Models\M_param_manasuka;

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
						$this->m_param_manasuka = new M_param_manasuka();
						$cek_new_user = $this->m_param_manasuka->where('idanggota', $userdata['iduser'])->get()->getResult();

						if ($cek_new_user != null) {
							return redirect()->to('anggota/dashboard');
						}else{
				    		echo "<script>alert('Isi data diri terlebih dahulu'); window.location.href = '".base_url()."/anggota/profile/set-manasuka';</script>";
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