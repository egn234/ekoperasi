<?php
namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_param;
use App\Models\M_param_manasuka;
use App\Models\M_deposit;

class register extends Controller
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
		$config = new \Config\Encryption();
		$encrypter = \Config\Services::encrypter($config);

		$nik = request()->getPost('nik');
		$alamat = request()->getPost('alamat');
		$nomor_telepon = request()->getPost('nomor_telepon');
		$no_rek = request()->getPost('no_rek');
		
		$pass = md5(request()->getPost('pass'));
		$pass2 = md5(request()->getPost('pass2'));
		
		$dataset = [
			'nama_lengkap' => strtoupper(request()->getPost('nama_lengkap')),
			'nik' => ($nik != null || $nik != '') ? base64_encode($encrypter->encrypt($nik)) : '',
			'tempat_lahir' => request()->getPost('tempat_lahir'),
			'tanggal_lahir' => request()->getPost('tanggal_lahir'),
			'instansi' => request()->getPost('instansi'),
			'unit_kerja' => request()->getPost('unit_kerja'),
			'status_pegawai' => request()->getPost('status_pegawai'),
			'alamat' => ($alamat != null || $alamat != '') ? base64_encode($encrypter->encrypt($alamat)) : '',
			'nama_bank' => strtoupper(request()->getPost('nama_bank')),
			'no_rek' => ($no_rek != null || $no_rek != '') ? base64_encode($encrypter->encrypt($no_rek)) : '',
			'nomor_telepon' => ($nomor_telepon != null || $nomor_telepon != '') ? base64_encode($encrypter->encrypt($nomor_telepon)) : '',
			'email' => request()->getPost('email'),
			'username' => request()->getPost('username'),
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
			return redirect()->to('registrasi');
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
			return redirect()->to('registrasi');
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
			return redirect()->to('registrasi');
		}

		//check duplicate nip
		$nip = request()->getPost('nip');

		if($nip != null || $nip != ''){
			$cek_nip = $this->m_user->select('count(iduser) as hitung')
				->where('nip', base64_encode($encrypter->encrypt($nip)))
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
				$dataset += ['nip' => base64_encode($encrypter->encrypt($nip))];
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
			return redirect()->to('registrasi');
		}

		if ($pass != $pass2) {
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
		} else {
			$dataset += ['pass' => password_hash($pass, PASSWORD_DEFAULT)];
		}

		$img = request()->getFile('profil_pic');

		if ($img->isValid()) {
			// Validation rules
			$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
			$allowedExtensions = ['jpg', 'jpeg', 'gif'];
			$maxSize = 2048; // Max size in KB (e.g., 2MB)

			// Validate MIME Type and Extension
			if (!in_array($img->getMimeType(), $allowedTypes) || 
        		!in_array(strtolower($img->getExtension()), $allowedExtensions)) {
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'Gambar Tidak Valid',
						 'status' => 'warning'
					]
				);
				
				$dataset += ['notif' => $alert];
				session()->setFlashdata($dataset);
				return redirect()->to('register');
			}

			$imageInfo = @getimagesize($img->getTempName());
			if ($imageInfo === false) {
				// Handle invalid image
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'Gambar Tidak Valid',
						 'status' => 'warning'
					]
				);
				
				$dataset += ['notif' => $alert];
				session()->setFlashdata($dataset);
				return redirect()->to('register');
			}

			// Validate File Size
			if ($img->getSizeByUnit('kb') > $maxSize) {
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'Gambar Terlalu Besar, Maks 2MB',
						 'status' => 'warning'
					]
				);
				
				$dataset += ['notif' => $alert];
				session()->setFlashdata($dataset);
				return redirect()->to('register');
			}

			// Optional: Validate Image Dimensions
			$imageInfo = getimagesize($img->getTempName());
			if ($imageInfo) {
				$width = $imageInfo[0];
				$height = $imageInfo[1];

				if ($width > 2000 || $height > 2000) { // Example dimensions limit
					return redirect()->back()->with('error', 'Image dimensions are too large. Maximum is 2000x2000 pixels.');
				}
			} else {
				return redirect()->back()->with('error', 'Uploaded file is not a valid image.');
			}

			// Move file to its destination if all validations pass
			$newName = $img->getRandomName();
			$img->move(ROOTPATH . 'public/uploads/user/' . $dataset['username'] . '/profil_pic/', $newName);
			$profile_pic = $img->getName();
			$dataset += ['profil_pic' => $profile_pic];
		} else {
			return redirect()->back()->with('error', $img->getErrorString());
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
			'username' => request()->getPost('username'),
			'notif_login' => $alert
		];

		session()->setFlashdata($data_session);
		return redirect()->to('/');
	}
}