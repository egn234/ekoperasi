<?php 

namespace App\Controllers\Anggota;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_group;
use App\Models\M_param;
use App\Models\M_param_manasuka;

use App\Controllers\Anggota\Notifications;

class Profile extends Controller
{

	function __construct()
	{
		$this->m_user = new M_user();
		$this->m_group = new M_group();
		$this->m_param = new M_param();
		$this->m_param_manasuka = new M_param_manasuka();
		$this->notification = new Notifications();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
	}

	public function index()
	{
		$data = [
			'title_meta' => view('anggota/partials/title-meta', ['title' => 'Profile']),
			'page_title' => view('anggota/partials/page-title', ['title' => 'Profile', 'li_1' => 'EKoperasi', 'li_2' => 'Profile']),
			'notification_list' => $this->notification->index()['notification_list'],
			'notification_badges' => $this->notification->index()['notification_badges'],
			'duser' => $this->account
		];
		
		return view('anggota/prof/prof-detail', $data);
	}

	public function update_proc()
	{
		$dataset = [
			'nama_lengkap' => strtoupper($this->request->getPost('nama_lengkap')),
			'tempat_lahir' => $this->request->getPost('tempat_lahir'),
			'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
			'status_pegawai' => $this->request->getPost('status_pegawai'),
			'instansi' => $this->request->getPost('instansi'),
			'alamat' => $this->request->getPost('alamat'),
			'nama_bank' => strtoupper($this->request->getPost('nama_bank')),
			'no_rek' => $this->request->getPost('no_rek'),
			'nomor_telepon' => $this->request->getPost('nomor_telepon'),
			'email' => $this->request->getPost('email'),
			'unit_kerja' => $this->request->getPost('unit_kerja')
		];	

		$nik_baru = $this->request->getPost('nik');
		$nik_awal = $this->account->nik;

		if ($nik_baru != $nik_awal) {
			$cek_nik = $this->m_user->select('count(iduser) as hitung')
				->where("nik = '".$nik_baru."' AND iduser != ".$this->account->iduser)
				->get()->getResult()[0]->hitung;

			if ($cek_nik == 0) {
				$dataset += ['nik' => $nik_baru];
			}else{
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'NIK telah terdaftar',
					 	'status' => 'danger'
					]
				);
				
				$data_session = [
					'notif' => $alert
				];

				session()->setFlashdata($data_session);
				return redirect()->back();
			}
		}

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

	public function set_manasuka()
	{
		$default_param = $this->m_param->getParamById(3)[0]->nilai;

		$data = [
			'title_meta' => view('anggota/partials/title-meta', ['title' => 'Profile']),
			'page_title' => view('anggota/partials/page-title', ['title' => 'Profile', 'li_1' => 'EKoperasi', 'li_2' => 'Profile']),
			'duser' => $this->account,
			'default_param' => $default_param
		];
		
		return view('anggota/prof/set-manasuka', $data);
	}

	public function set_manasuka_proc()
	{
		$iduser = $this->request->getPost('iduser');
		$nominal_param = filter_var($this->request->getPost('nilai'), FILTER_SANITIZE_NUMBER_INT);

		$param_r = [
			'idanggota' => $iduser,
			'nilai' => $nominal_param,
			'created' => date('Y-m-d H:i:s')
		];

		$this->m_param_manasuka->insertParamManasuka($param_r);

		return redirect()->to('anggota/dashboard');
	}
}