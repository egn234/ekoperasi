<?php 
namespace App\Controllers\Anggota;

use App\Controllers\BaseController;
use App\Controllers\Anggota\Notifications;

use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_param;
use App\Models\M_notification;

class Closebook extends BaseController
{
	protected $m_user;
	protected $m_deposit;
	protected $m_param;
	protected $m_notification;
	protected $account;
	protected $notification;

	function __construct()
	{
		$this->m_user = new M_user();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
		$this->m_deposit = new M_deposit();
		$this->m_param = new M_param();
		$this->m_notification = new M_notification();
		$this->notification = new Notifications();
	}

	public function index()
	{
		$total_saldo_wajib = $this->m_deposit->getSaldoWajibByUserId($this->account->iduser)[0]->saldo;
		$total_saldo_pokok = $this->m_deposit->getSaldoPokokByUserId($this->account->iduser)[0]->saldo;
		$total_saldo_manasuka = $this->m_deposit->getSaldoManasukaByUserId($this->account->iduser)[0]->saldo;

		$data = [
			'title_meta' => view('anggota/partials/title-meta', ['title' => 'Tutup Buku']),
			'page_title' => view('anggota/partials/page-title', ['title' => 'Tutup Buku', 'li_1' => 'EKoperasi', 'li_2' => 'Tutup Buku']),
			'notification_list' => $this->notification->index()['notification_list'],
			'notification_badges' => $this->notification->index()['notification_badges'],
			'duser' => $this->account,
			'total_saldo_wajib' => $total_saldo_wajib,
			'total_saldo_pokok' => $total_saldo_pokok,
			'total_saldo_manasuka' => $total_saldo_manasuka
		];
		
		return view('anggota/closebook-page', $data);
	}

	public function closebook_proc()
	{
		$dataset = [
			'closebook_request' => 'closebook',
			'closebook_request_date' => date('Y-m-d H:i:s')
		];

		$this->m_user->updateUser($this->account->iduser, $dataset);

		$notification_data = [
			'anggota_id' => $this->account->iduser,
			'closebook' => '1',
			'message' => 'Pengajuan tutup buku dari anggota '. $this->account->nama_lengkap,
			'timestamp' => date('Y-m-d H:i:s'),
			'group_type' => 1
		];

		$this->m_notification->insert($notification_data);
		
		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'berhasil mengajukan closebook',
			 	'status' => 'warning'
			]
		);
		
		session()->setFlashdata('notif', $alert);
		return redirect()->back();
	}

	public function closebook_cancel()
	{
		$dataset = [
			'closebook_request' => null
		];

		$this->m_user->updateUser($this->account->iduser, $dataset);
		
		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'closebook dibatalkan',
			 	'status' => 'warning'
			]
		);
		
		session()->setFlashdata('notif', $alert);
		return redirect()->back();
	}
}