<?php 

namespace App\Controllers\Anggota;

use CodeIgniter\Controller;
use App\Models\M_user;
use App\Models\M_group;

class Profile extends Controller
{

	function __construct()
	{
		$this->m_user = new M_user();
		$this->m_group = new M_group();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
	}

	public function index()
	{
		$data = [
			'title_meta' => view('anggota/partials/title-meta', ['title' => 'Profile']),
			'page_title' => view('anggota/partials/page-title', ['title' => 'Profile', 'li_1' => 'EKoperasi', 'li_2' => 'Profile']),
			'duser' => $this->account
		];
		
		return view('anggota/prof/prof-detail', $data);
	}

	public function update_proc()
	{
		$dataset = [
			'nama_lengkap' => $this->request->getPost('nama_lengkap'),
			'tempat_lahir' => $this->request->getPost('tempat_lahir'),
			'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
			'instansi' => $this->request->getPost('instansi'),
			'alamat' => $this->request->getPost('alamat'),
			'nomor_telepon' => $this->request->getPost('nomor_telepon'),
			'email' => $this->request->getPost('email'),
			'unit_kerja' => $this->request->getPost('unit_kerja')
		];	

		$img = $this->request->getFile('profil_pic');

		if ($img->isValid()) {
			unlink(ROOTPATH . "public/uploads/user/" . $this->account->username . "/profil_pic/" . $this->account->profil_pic );
			$newName = $img->getRandomName();
			$img->move(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/profil_pic/', $newName);
			$profile_pic = $img->getName();
			$dataset += ['profil_pic' => $profile_pic];
		}

		$dataset += [
			'updated' => date('Y-m-d H:i:s')
		];
		
		$this->m_user->updateUser($this->account->iduser, $dataset);
		
		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'data pengguna berhasil diubah',
			 	'status' => 'success'
			]
		);
		
		$data_session = [
			'notif' => $alert
		];

		session()->setFlashdata($data_session);
		return redirect()->to('anggota/profile');
	}

	public function update_pass()
	{
		$old_pass = md5($this->request->getPost('old_pass'));

		$pass = md5($this->request->getPost('pass'));
		$pass2 = md5($this->request->getPost('pass2'));

		$cek_pass = $this->m_user->getPassword($this->account->iduser)[0]->pass;

		if ($old_pass != $cek_pass)
		{
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Password lama salah',
				 	'status' => 'danger'
				]
			);
			
			$dataset += ['notif' => $alert];
			session()->setFlashdata($dataset);
			return redirect()->to('anggota/profile');
		}

		if ($pass != $pass2)
		{
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Konfirmasi password tidak sesuai',
				 	'status' => 'danger'
				]
			);
			
			$dataset += ['notif' => $alert];
			session()->setFlashdata($dataset);
			return redirect()->to('anggota/profile');
		}

		$dataset = ['pass' => $pass];

		$this->m_user->updateUser($this->account->iduser, $dataset);

		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'Password berhasil diubah',
			 	'status' => 'success'
			]
		);
		
		$dataset += ['notif' => $alert];
		session()->setFlashdata($dataset);
		return redirect()->to('anggota/profile');
	}
}