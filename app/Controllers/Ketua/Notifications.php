<?php
namespace App\Controllers\Ketua;

use App\Controllers\BaseController;

use App\Models\M_user;
use App\Models\M_notification;

class Notifications extends BaseController
{
	protected $m_user, $m_notification;
	protected $account;

	function __construct(){
		$this->m_user = model(M_user::class);
		$this->m_notification = model(M_notification::class);

		$config = new \Config\Encryption();
		$encrypter = \Config\Services::encrypter($config);

		$user = $this->m_user->getUserById(session()->get('iduser'));
		if (empty($user)) {
			$user = null;
		} else {
			$data = $user[0];
			
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
	}

	public function index()
	{
		$notification_list = $this->m_notification->where('group_type', '3')
											->orderBy('timestamp', 'DESC')
											->get()
											->getResult();

		$notification_badges = $this->m_notification->where('group_type', '3')
											->where('status', 'unread')
											->get()
											->getResult();
		
		$notification = [
			'notification_list' => $notification_list,
			'notification_badges' => count($notification_badges)
		];
		
		return $notification;
	}

	public function mark_all_read()
	{
		$this->m_notification->where('group_type', '3')
							 ->where('status', 'unread')
							 ->set('status', 'read')
							 ->update();

		return redirect()->back();
	}

	public function mark_as_read()
	{
		if ($_POST['id']) {
			$this->m_notification->where('id', $_POST['id'])
								 ->set('status', 'read')
								 ->update();
		}
	}
}