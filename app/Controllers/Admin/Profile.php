<?php 

namespace App\Controllers\Admin;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_group;

use App\Controllers\Admin\Notifications;

class Profile extends Controller
{
	protected $m_user;
	protected $m_group;
	protected $account;
	protected $notification;

	function __construct()
	{
		$this->m_user = model(M_user::class);
		$this->m_group = model(M_group::class);

		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
		
		$this->notification = new Notifications();

		$config = new \Config\Encryption();
		$encrypter = \Config\Services::encrypter($config);

		$data = $this->m_user->getUserById(session()->get('iduser'))[0];

		$nik = ($data->nik != null || $data->nik != '') ? $encrypter->decrypt(base64_decode($data->nik)) : '';
		$nip = ($data->nip != null || $data->nip != '') ? $encrypter->decrypt(base64_decode($data->nip)) : '';
		$no_rek = ($data->no_rek != null || $data->no_rek != '') ? $encrypter->decrypt(base64_decode($data->no_rek)) : '';
		$nomor_telepon = ($data->nomor_telepon != null || $data->nomor_telepon != '') ? $encrypter->decrypt(base64_decode($data->nomor_telepon)) : '';
		$alamat = ($data->alamat != null || $data->alamat != '') ? $encrypter->decrypt(base64_decode($data->alamat)) : '';

		$this->account = (object) [
			'iduser' => $data->iduser,
			'username' => $data->username,
			'nik' => $nik,
			'nip' => $nip,
			'nama_lengkap' => $data->nama_lengkap,
			'tempat_lahir' => $data->tempat_lahir,
			'tanggal_lahir' => $data->tanggal_lahir,
			'status_pegawai' => $data->status_pegawai,
			'nama_bank' => $data->nama_bank,
			'no_rek' => $no_rek,
			'alamat' => $alamat,
			'instansi' => $data->instansi,
			'unit_kerja' => $data->unit_kerja,
			'nomor_telepon' => $nomor_telepon,
			'email' => $data->email,
			'profil_pic' => $data->profil_pic,
			'user_created' => $data->user_created,
			'user_updated' => $data->user_updated,
			'closebook_request' => $data->closebook_request,
			'closebook_request_date' => $data->closebook_request_date,
			'closebook_last_updated' => $data->closebook_last_updated,
			'closebook_param_count' => $data->closebook_param_count,
			'user_flag' => $data->user_flag,
			'idgroup' => $data->idgroup,
			'group_type' => $data->group_type,
			'group_assigned' => $data->group_assigned,
			'group_flag' => $data->group_flag
		];
	}

	public function index()
	{
		$data = [
			'title_meta' => view('admin/partials/title-meta', ['title' => 'Profile']),
			'page_title' => view('admin/partials/page-title', ['title' => 'Profile', 'li_1' => 'EKoperasi', 'li_2' => 'Profile']),
			'notification_list' => $this->notification->index()['notification_list'],
			'notification_badges' => $this->notification->index()['notification_badges'],
			'duser' => $this->account
		];
		
		return view('admin/prof/prof-detail', $data);
	}

	public function update_proc()
	{
		$config = new \Config\Encryption();
		$encrypter = \Config\Services::encrypter($config);

		$alamat = request()->getPost('alamat');
		$nomor_telepon = request()->getPost('nomor_telepon');
		$no_rek = request()->getPost('no_rek');

		$dataset = [
			'nama_lengkap' => strtoupper(request()->getPost('nama_lengkap')),
			'tempat_lahir' => request()->getPost('tempat_lahir'),
			'tanggal_lahir' => request()->getPost('tanggal_lahir'),
			'status_pegawai' => request()->getPost('status_pegawai'),
			'instansi' => request()->getPost('instansi'),
			'alamat' => ($alamat != null || $alamat != '') ? base64_encode($encrypter->encrypt($alamat)) : '',
			'nama_bank' => strtoupper(request()->getPost('nama_bank')),
			'no_rek' => ($no_rek != null || $no_rek != '') ? base64_encode($encrypter->encrypt($no_rek)) : '',
			'nomor_telepon' => ($nomor_telepon != null || $nomor_telepon != '') ? base64_encode($encrypter->encrypt($nomor_telepon)) : '',
			'email' => request()->getPost('email'),
			'unit_kerja' => request()->getPost('unit_kerja')
		];

		//check duplicate nip
		$nip_baru = request()->getPost('nip');

		if($nip_baru != null || $nip_baru != ''){
			$nip_baru_enc = base64_encode($encrypter->encrypt($nip_baru));
			$nip_awal = $this->account->nip;

			if($nip_awal !== $nip_baru){
				$cek_nip = $this->m_user->select('count(iduser) as hitung')
					->where("nip = '".$nip_baru_enc."' AND iduser != ".$this->account->iduser)
					->get()->getResult()[0]->hitung;

				if ($cek_nip == 0) {
					$dataset += ['nip' => $nip_baru_enc];
				}else{
					$alert = view(
						'partials/notification-alert', 
						[
							'notif_text' => 'NIP telah terdaftar',
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
		}

		//check duplicate nik
		$nik_baru =	request()->getPost('nik');
		$nik_baru_enc = base64_encode($encrypter->encrypt($nik_baru));
		$nik_awal = $this->account->nik;

		if ($nik_baru !== $nik_awal) {
			$cek_nik = $this->m_user->select('count(iduser) as hitung')
				->where("nik = '".$nik_baru_enc."' AND iduser != ".$this->account->iduser)
				->get()->getResult()[0]->hitung;

			if ($cek_nik == 0) {
				$dataset += ['nik' => $nik_baru_enc];
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

		$img = request()->getFile('profil_pic');

		if ($img->isValid() && $img->hasMoved()) {
			$allowedTypes = ['image/png', 'image/jpg', 'image/jpeg'];
			if (in_array($img->getClientMimeType(), $allowedTypes)) {

				$oldFilePath = ROOTPATH . "public/uploads/user/" . $this->account->username . "/profil_pic/" . $this->account->profil_pic;
				if(is_file($oldFilePath)) {
					unlink($oldFilePath);
				}

				$newName = $img->getRandomName();
				$img->move(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/profil_pic/', $newName);
				$profile_pic = $img->getName();
				$dataset += ['profil_pic' => $profile_pic];
			} else {
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'Tipe file tidak diizinkan', 
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
		return redirect()->to('admin/profile');
	}

	public function update_pass()
	{
		$old_pass = md5(request()->getPost('old_pass'));

		$pass = md5(request()->getPost('pass'));
		$pass2 = md5(request()->getPost('pass2'));

		$cek_pass = $this->m_user->getPassword($this->account->iduser)[0]->pass;

		if (!password_verify($old_pass, $cek_pass))
		{
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Password lama salah',
				 	'status' => 'danger'
				]
			);
			
			$dataset = ['notif' => $alert];
			session()->setFlashdata($dataset);
			return redirect()->to('admin/profile');
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
			
			$dataset = ['notif' => $alert];
			session()->setFlashdata($dataset);
			return redirect()->to('admin/profile');
		}

		$dataset = ['pass' => password_hash($pass, PASSWORD_DEFAULT)];

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
		return redirect()->to('admin/profile');
	}
}