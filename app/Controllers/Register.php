<?php 

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_user;
use App\Models\M_param;
use App\Models\M_param_manasuka;
use App\Models\M_deposit;

class register extends Controller
{

	function __construct()
	{
		$this->m_user = new M_User();	
		$this->m_param = new M_param();	
		$this->m_deposit = new M_deposit();	
		$this->m_param_manasuka = new M_param_manasuka();	
	}

	public function index()
	{
		$simpanan_pokok = $this->m_param->getParamById(1)[0];
		$simpanan_wajib = $this->m_param->getParamById(2)[0];
		
		$data = [
			'title_meta' => view('partials/title-meta', ['title' => 'Register']),
			'simp_pokok' => $simpanan_pokok,
			'simp_wajib' => $simpanan_wajib
		];
		return view('auth-register', $data);		
	}

	public function register_proc()
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
			'idgroup' => 4
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
			return redirect()->to('register');
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
			return redirect()->to('register');
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
			return redirect()->to('register');
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
			return redirect()->to('register');			
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
			return redirect()->to('register');
		}

		$img = $this->request->getFile('profil_pic');
		
		if ($img->isValid()) {
			$newName = $img->getRandomName();
			$img->move(ROOTPATH . 'public/uploads/user/' . $dataset['username'] . '/profil_pic/', $newName);
			$profile_pic = $img->getName();
			$dataset += ['profil_pic' => $profile_pic];
		}

		$dataset += [
			'created' => date('Y-m-d H:i:s'),
			'closebook_param_count' => 0,
			'flag' => 1
		];
		
		$this->m_user->insertUser($dataset);

		$iduser_new = $this->m_user->getUser($dataset['username'])[0]->iduser;
		
		$init_aktivasi = [
			$this->m_param->getParamById(1)[0]->nilai,
			$this->m_param->getParamById(2)[0]->nilai
		];

		$j_deposit_r = ['pokok', 'wajib'];

		for ($i = 0; $i < count($init_aktivasi); $i++) {
			$dataset = [
				'jenis_pengajuan' => 'penyimpanan',
				'jenis_deposit' => $j_deposit_r[$i],
				'cash_in' => $init_aktivasi[$i],
				'cash_out' => 0,
				'deskripsi' => 'biaya awal registrasi',
				'status' => 'diproses',
				'date_created' => date('Y-m-d H:i:s'),
				'idanggota' => $iduser_new
			];

			$this->m_deposit->insertDeposit($dataset);
		}
		
		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'User berhasil dibuat',
			 	'status' => 'success'
			]
		);
		
		$data_session = [
			'notif_login' => $alert
		];

		session()->setFlashdata($data_session);
		return redirect()->to('/');
	}
}