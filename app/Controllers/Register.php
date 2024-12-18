<?php
namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\M_user;
use App\Models\M_param;
use App\Models\M_param_manasuka;
use App\Models\M_deposit;

class register extends BaseController
{
	protected $m_user;
	protected $m_param;
	protected $m_deposit;
	protected $m_param_manasuka;

	function __construct()
	{
		$this->m_user = model(M_user::class);	
		$this->m_param = model(M_param::class);	
		$this->m_deposit = model(M_deposit::class);	
		$this->m_param_manasuka = model(M_param_manasuka::class);	
	}

	public function index()
	{
		$simpanan_pokok = $this->m_param->getParamById(1)[0];
		$simpanan_wajib = $this->m_param->getParamById(2)[0];
		
		$cek_username = $this->m_user->getUsernameGiat()[0]->username;
		$filter_int = filter_var($cek_username, FILTER_SANITIZE_NUMBER_INT);
		$clean_int = intval($filter_int);

		if ($clean_int >= 1000) {
			$username = 'GIAT'.($clean_int+1);
		}elseif ($clean_int >= 100) {
			$username = 'GIAT0'.($clean_int+1);
		}elseif ($clean_int >= 10) {
			$username = 'GIAT00'.($clean_int+1);
		}elseif ($clean_int >= 1) {
			$username = 'GIAT000'.($clean_int+1);
		}

		$data = [
			'title_meta' => view('partials/title-meta', ['title' => 'Register']),
			'simp_pokok' => $simpanan_pokok,
			'simp_wajib' => $simpanan_wajib,
			'username' => $username
		];
		return view('auth-register', $data);		
	}

	public function register_proc()
	{
		$dataset = [
			'nama_lengkap' => strtoupper($this->request->getPost('nama_lengkap')),
			'nik' => $this->request->getPost('nik'),
			'tempat_lahir' => $this->request->getPost('tempat_lahir'),
			'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
			'instansi' => $this->request->getPost('instansi'),
			'unit_kerja' => $this->request->getPost('unit_kerja'),
			'status_pegawai' => $this->request->getPost('status_pegawai'),
			'alamat' => $this->request->getPost('alamat'),
			'nama_bank' => strtoupper($this->request->getPost('nama_bank')),
			'no_rek' => $this->request->getPost('no_rek'),
			'nomor_telepon' => $this->request->getPost('nomor_telepon'),
			'email' => $this->request->getPost('email'),
			'username' => $this->request->getPost('username'),
			'pass' => md5($this->request->getPost('pass')),
			'idgroup' => 4
		];

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
			
			$dataset += ['notif' => $alert];
			session()->setFlashdata($dataset);
			return redirect()->to('registrasi');
        }

		$pass2 = md5(request()->getPost('pass2'));
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

		if ($dataset['status_pegawai'] == "") {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Pilih status status_pegawai terlebih dahulu',
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

		//check duplicate nip
		$nip = $this->request->getPost('nip');

		if($nip != null || $nip != ''){
			$cek_nip = $this->m_user->select('count(iduser) as hitung')
				->where('nip', $nip)
				->get()
				->getResult()[0]
				->hitung;

			if($cek_nip != 0){
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'NIP Telah Terdaftar',
					 	'status' => 'warning'
					]
				);
				
				$dataset += [
					'nip' => $nip,
					'notif' => $alert
				];
				
				session()->setFlashdata($dataset);
				return redirect()->back();
			}else{
				$dataset += ['nip' => $nip];
			}
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
			'username' => $this->request->getPost('username'),
			'notif_login' => $alert
		];

		session()->setFlashdata($data_session);
		return redirect()->to('/');
	}
}