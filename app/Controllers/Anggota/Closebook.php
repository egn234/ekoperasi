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
	public function index()
	{
		$m_user = new M_user();
		$m_deposit = new M_deposit();
		$notification = new notifications();

		$account = $m_user->getUserById(session()->get('iduser'))[0];

		$total_saldo_wajib = $m_deposit->getSaldoWajibByUserId($account->iduser)[0]->saldo;
		$total_saldo_pokok = $m_deposit->getSaldoPokokByUserId($account->iduser)[0]->saldo;
		$total_saldo_manasuka = $m_deposit->getSaldoManasukaByUserId($account->iduser)[0]->saldo;

		$data = [
			'title_meta' => view('anggota/partials/title-meta', ['title' => 'Tutup Buku']),
			'page_title' => view('anggota/partials/page-title', ['title' => 'Tutup Buku', 'li_1' => 'EKoperasi', 'li_2' => 'Tutup Buku']),
			'notification_list' => $notification->index()['notification_list'],
			'notification_badges' => $notification->index()['notification_badges'],
			'duser' => $account,
			'total_saldo_wajib' => $total_saldo_wajib,
			'total_saldo_pokok' => $total_saldo_pokok,
			'total_saldo_manasuka' => $total_saldo_manasuka
		];
		
		return view('anggota/closebook-page', $data);
	}

	public function closebook_proc()
	{
		$m_user = new M_user();
		$m_notification = new M_notification();
		
		$account = $m_user->getUserById(session()->get('iduser'))[0];

		$dataset = [
			'closebook_request' => 'closebook',
			'closebook_request_date' => date('Y-m-d H:i:s')
		];

		$m_user->updateUser($account->iduser, $dataset);

		$notification_data = [
			'anggota_id' => $account->iduser,
			'closebook' => '1',
			'message' => 'Pengajuan tutup buku dari anggota '. $account->nama_lengkap,
			'timestamp' => date('Y-m-d H:i:s'),
			'group_type' => 1
		];

		$m_notification->insert($notification_data);
		
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
		$m_user = new M_user();
		
		$account = $m_user->getUserById(session()->get('iduser'))[0];

		$dataset = [
			'closebook_request' => null
		];

		$m_user->updateUser($account->iduser, $dataset);
		
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