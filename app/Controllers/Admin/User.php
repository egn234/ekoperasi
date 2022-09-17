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
			$alert = view('partials/notification-alert', 
				['notif_text' => 'Pilih Institusi terlebih dahulu',
				 'status' => 'warning']
				);
			
			$data_session = [
				'notif' => $alert,
				'temp_dat' => $dataset
			];
			session()->setFlashdata($data_session);
			return redirect()->to('admin/user/add');
		}

		if ($dataset['pass'] != $pass2) {
			$alert = view('partials/notification-alert', 
				['notif_text' => 'Password tidak cocok',
				 'status' => 'warning']
				);
			
			$data_session = [
				'notif' => $alert,
				'temp_dat' => $dataset
			];
			session()->setFlashdata($data_session);
			return redirect()->to('admin/user/add');			
		}

		if ($dataset['idgroup'] == "") {
			$alert = view('partials/notification-alert', 
				['notif_text' => 'Pilih Grup terlebih dahulu',
				 'status' => 'warning']
				);
			
			$data_session = [
				'notif' => $alert,
				'temp_dat' => $dataset
			];
			session()->setFlashdata($data_session);
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
		
		$alert = view('partials/notification-alert', 
			['notif_text' => 'User berhasil dibuat',
			 'status' => 'success']
			);
		
		$data_session = [
			'notif' => $alert
		];

		session()->setFlashdata($data_session);
		return redirect()->to('admin/user');
	}
}