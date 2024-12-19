<?php 
namespace App\Controllers\Ketua;

use \CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_pinjaman;
use App\Models\M_notification;

use App\Controllers\Ketua\Notifications;

class Pinjaman extends Controller
{
	protected $m_user, $m_pinjaman, $m_notification;
	protected $account;
	protected $notification;
	
	function __construct()
	{
		$this->m_user = model(M_user::class);
		$this->m_pinjaman = model(M_pinjaman::class);
		$this->m_notification = model(M_notification::class);
		
		$this->notification = new Notifications();

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
		$list_pinjaman = $this->m_pinjaman->getPinjamanByStatus(3);

		$data = [
			'title_meta' => view('ketua/partials/title-meta', ['title' => 'Pinjaman']),
			'page_title' => view('ketua/partials/page-title', ['title' => 'Pinjaman', 'li_1' => 'EKoperasi', 'li_2' => 'Pinjaman']),
			'notification_list' => $this->notification->index()['notification_list'],
			'notification_badges' => $this->notification->index()['notification_badges'],
			'duser' => $this->account,
			'list_pinjaman' => $list_pinjaman
		];
		
		return view('ketua/pinjaman/list-pinjaman', $data);
	}

	public function cancel_proc($idpinjaman = false)
	{
		$dataset = [
			'idketua' => $this->account->iduser,
			'alasan_tolak' => request()->getPost('alasan_tolak'),
			'status' => 0,
			'date_updated' => date('Y-m-d H:i:s')
		];

		$this->m_pinjaman->updatePinjaman($idpinjaman, $dataset);
		
		$notification_anggota = [
			'ketua_id' => $this->account->iduser,
			'anggota_id' => $this->m_pinjaman->where('idpinjaman', $idpinjaman)
											 ->get()
											 ->getResult()[0]
											 ->idanggota,
			'pinjaman_id' => $idpinjaman,
			'message' => 'Pengajuan pinjaman ditolak oleh ketua '. $this->account->nama_lengkap,
			'timestamp' => date('Y-m-d H:i:s'),
			'group_type' => 4
		];

		$this->m_notification->insert($notification_anggota);
		
		$this->m_notification->where('pinjaman_id', $idpinjaman)
			->where('group_type', 2)
			->set('status', 'read')
			->update();

		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'Pengajuan pinjaman berhasil ditolak',
			 	'status' => 'success'
			]
		);
		
		$data_session = ['notif' => $alert];
		session()->setFlashdata($data_session);
		return redirect()->back();
	}

	public function approve_proc($idpinjaman = false)
	{
		$dataset = [
			'idketua' => $this->account->iduser,
			'status' => 4,
			'date_updated' => date('Y-m-d H:i:s'),
			'bln_perdana' => date('m', strtotime("+ 1 month")),
			'tanggal_bayar' => date('d')
		];

		$this->m_pinjaman->updatePinjaman($idpinjaman, $dataset);

		$anggota_id = $this->m_pinjaman->where('idpinjaman', $idpinjaman)
									   ->get()
									   ->getResult()[0]
									   ->idanggota;
		
		$notification_anggota = [
			'ketua_id' => $this->account->iduser,
			'anggota_id' => $anggota_id,
			'pinjaman_id' => $idpinjaman,
			'message' => 'Pengajuan pinjaman diterima oleh ketua '. $this->account->nama_lengkap,
			'timestamp' => date('Y-m-d H:i:s'),
			'group_type' => 4
		];

		$this->m_notification->insert($notification_anggota);
		
		$this->m_notification->where('pinjaman_id', $idpinjaman)
			->where('group_type', 2)
			->set('status', 'read')
			->update();

		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'Pengajuan pinjaman berhasil disetujui',
			 	'status' => 'success'
			]
		);
		
		$data_session = ['notif' => $alert];
		session()->setFlashdata($data_session);
		return redirect()->back();
	}

	public function cancel_loan()
	{
		if ($_POST['rowid']) {
			$id = $_POST['rowid'];
			$pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
			$data = [
				'a' => $pinjaman,
				'flag' => 0
			];
			echo view('ketua/pinjaman/part-pinjaman-mod-approval', $data);
		}
	}

	public function approve_loan()
	{
		if ($_POST['rowid']) {
			$id = $_POST['rowid'];
			$pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
			$data = [
				'a' => $pinjaman,
				'flag' => 1
			];
			echo view('ketua/pinjaman/part-pinjaman-mod-approval', $data);
		}
	}
}