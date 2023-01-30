<?php 
namespace App\Controllers\Ketua;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_pinjaman;
use App\Models\M_Notification;

use App\Controllers\Ketua\Notifications;

class Pinjaman extends Controller
{

	function __construct()
	{
		$this->m_user = new M_user();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
		$this->m_pinjaman = new M_pinjaman();
		$this->m_notification = new M_notification();
		$this->notification = new Notifications();
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
			'alasan_tolak' => $this->request->getPost('alasan_tolak'),
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
			'date_updated' => date('Y-m-d H:i:s')
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
		
		$notification_admin = [
			'ketua_id' => $this->account->iduser,
			'anggota_id' => $anggota_id,
			'pinjaman_id' => $idpinjaman,
			'message' => 'Pengajuan pinjaman baru oleh anggota '. $this->m_user->where('iduser', $anggota_id)->get()->getResult()[0]->nama_lengkap,
			'timestamp' => date('Y-m-d H:i:s'),
			'group_type' => 1
		];

		$this->m_notification->insert($notification_admin);

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