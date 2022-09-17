<?php 
namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\M_user;
use App\Models\M_group;

class User extends Controller
{

	function __construct()
	{
		$this->m_user = new M_user();
		$this->m_group = new M_group();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
	}

	public function list()
	{	
		$user_list = $this->m_user->getAllUser();

		$data = [
			'title_meta' => view('admin/partials/title-meta', ['title' => 'User']),
			'page_title' => view('admin/partials/page-title', ['title' => 'User List', 'li_1' => 'EKoperasi', 'li_2' => 'User List']),
			'duser' => $this->account,
			'usr_list' => $user_list
		];
		
		echo view('admin/user/user_list', $data);
	}

	public function add_user()
	{
		$group_list = $this->m_group->getAllGroup();

		$data = [
			'title_meta' => view('admin/partials/title-meta', ['title' => 'User']),
			'page_title' => view('admin/partials/page-title', ['title' => 'Tambah User', 'li_1' => 'EKoperasi', 'li_2' => 'New User']),
			'duser' => $this->account,
			'grp_list' => $group_list
		];
		
		echo view('admin/user/add_user', $data);
	}

	public function add_user_proc()
	{
		$dataset = [
			'nama_lengkap' => $this->request->getPost('nama_lengkap'),
			'nik' => $this->request->getPost('nik'),
			'tempat_lahir' => $this->request->getPost('tempat_lahir'),
			'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
			'instansi' => $this->request->getPost('instansi'),
			'alamat' => $this->request->getPost('alamat'),
			'nomor_telepon' => $this->request->getPost('nomor_telepon'),
			'email' => $this->request->getPost('email'),
			'unit_kerja' => $this->request->getPost('unit_kerja'),
			'username' => $this->request->getPost('username'),
			'pass' => md5($this->request->getPost('pass')),
			'idgroup' => $this->request->getPost('idgroup')
		];

		$pass2 = md5($this->request->getPost('pass2'));

		if ($dataset['instansi'] == "") {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Pilih Institusi terlebih dahulu',
				 	'status' => 'warning'
				]
			);
			
			$dataset += ['notif' => $alert];
			session()->setFlashdata($dataset);
			return redirect()->to('admin/user/add');
		}

		$cek_username = $this->m_user->countUser($dataset['username'])[0]->hitung;

		if ($cek_username != 0) {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Username Telah Terpakai',
				 	'status' => 'warning'
				]
			);
			
			$dataset += ['notif' => $alert];
			session()->setFlashdata($dataset);
			return redirect()->to('admin/user/add');
		}

		$cek_nik = $this->m_user->countNIK($dataset['nik'])[0]->hitung;

		if ($cek_nik != 0) {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'NIK Telah Terdaftar',
				 	'status' => 'warning'
				]
			);
			
			$dataset += ['notif' => $alert];
			session()->setFlashdata($dataset);
			return redirect()->to('admin/user/add');
		}

		if ($dataset['pass'] != $pass2) {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Password tidak cocok',
				 	'status' => 'warning'
				]
			);
			
			$dataset += ['notif' => $alert];
			session()->setFlashdata($dataset);
			return redirect()->to('admin/user/add');			
		}

		if ($dataset['idgroup'] == "") {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Pilih Grup terlebih dahulu',
				 	'status' => 'warning'
				]
			);
			
			$dataset += ['notif' => $alert];
			session()->setFlashdata($dataset);
			return redirect()->to('admin/user/add');
		}

		$img = $this->request->getFile('profil_pic');
		
		if ($img->isValid()) {
			$newName = $img->getRandomName();
			$img->move(ROOTPATH . 'public/uploads/user/ ' . $dataset['username'] . '/profil_pic/', $newName);
			$profile_pic = $img->getName();
			$dataset += ['profil_pic' => $profile_pic];
		}

		$dataset += [
			'created' => date('Y-m-d H:i:s'),
			'closebook_param_count' => 0,
			'flag' => 1
		];
		
		$this->m_user->insertUser($dataset);
		
		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'User berhasil dibuat',
			 	'status' => 'success'
			]
		);
		
		$data_session = [
			'notif' => $alert
		];

		session()->setFlashdata($data_session);
		return redirect()->to('admin/user_list');
	}

	public function flag_switch($iduser = false)
	{
		$user = $this->m_user->getUserById($iduser)[0];

		if ($user->user_flag == 0) {
			
			if ($user->closebook_param_count == 1) {
				
				$this->m_user->aktifkanUser($iduser);

				$alert = view('partials/notification-alert', 
					[
						'notif_text' => 'User Diaktifkan',
					 	'status' => 'success'
					]
				);
				
				session()->setFlashdata('notif', $alert);

			}elseif ($user->closebook_param_count == 2) {

				$alert = view('partials/notification-alert', 
					[
						'notif_text' => 'User Sudah melebihi batas aktivasi',
						'status' => 'danger'
					]
				);
				
				session()->setFlashdata('notif', $alert);
			}

		}elseif ($user->user_flag == 1) {

			$this->m_user->nonaktifkanUser($iduser);

			if ($user->closebook_param_count == 0) {
				$this->m_user->closebookCount($iduser, 1);
				
			}elseif ($user->closebook_param_count == 1) {
				$this->m_user->closebookCount($iduser, 2);
			}


			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'User Dinonaktifkan',
					'status' => 'success'
				]
			);

			session()->setFlashdata('notif', $alert);
		}

		return redirect()->to(base_url('admin/user/list'));
	}

	public function konfirSwitch()
	{
		if ($_POST['rowid']) {
			$id = $_POST['rowid'];
			$user = $this->m_user->getUserById($id)[0];
			$data = ['a' => $user];
			echo view('admin/user/part-user-mod-switch', $data);
		}
	}
}