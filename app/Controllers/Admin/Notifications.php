<?php
namespace App\Controllers\Admin;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_notification;

class Notifications extends Controller
{
	protected $m_user, $m_notification;
	protected $account;

	function __construct(){
		$this->m_user = model(M_user::class);

		$this->m_notification = new M_notification();

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
		$notification_list = $this->m_notification->where('group_type', '1')
											->orderBy('timestamp', 'DESC')
											->get()
											->getResult();

		$notification_badges = $this->m_notification->where('group_type', '1')
											->where('status', 'unread')
											->get()
											->getResult();
		
		$notification = [
			'notification_list' => $notification_list,
			'notification_badges' => count($notification_badges)
		];
		
		return $notification;
	}

	public function notification_list()
	{
		$perPage = 10;
		$page = request()->getVar('page') ?? 1;

		$notifications = $this->m_notification
			->where('group_type', '1') // ganti sesuai role
			->orderBy('timestamp', 'DESC')
			->paginate($perPage);

		$pager = $this->m_notification->pager;

		$unread_count = $this->m_notification
			->where('group_type', '1')
			->where('status', 'unread')
			->countAllResults();

		$data = [
			'title_meta' => view('admin/partials/title-meta', ['title' => 'Pinjaman']),
			'page_title' => view('admin/partials/page-title', ['title' => 'Pinjaman', 'li_1' => 'EKoperasi', 'li_2' => 'Pinjaman']),
			'notification_list' => $this->index()['notification_list'],
			'notification_badges' => $this->index()['notification_badges'],
			'daftar_notif' => $notifications,
			'pager' => $pager,
			'badge_notif' => $unread_count,
			'duser' => $this->account
		];
		
		return view('admin/notifikasi/list-notif', $data);
	}

	public function mark_all_read_table()
	{
		$this->m_notification->where('group_type', '1')
							 ->where('status', 'unread')
							 ->set('status', 'read')
							 ->update();

		return redirect()->back();
	}

	public function mark_as_read_table()
	{
		$id = request()->getPost('id');
		
		if ($id == null || $id == '') {
			return redirect()->back();
		}

		$this->m_notification->where('id', $id)
							 ->set('status', 'read')
							 ->update();

		return redirect()->back();
	}

	public function mark_all_read()
	{
		$this->m_notification->where('group_type', '1')
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