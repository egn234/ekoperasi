<?php 
namespace App\Controllers\Anggota;

use App\Controllers\BaseController;
use App\Controllers\Anggota\Notifications;

use App\Models\M_user;
use App\Models\M_deposit;
// use App\Models\M_param;

class Dashboard extends BaseController
{
	public function index()
	{
		$m_user = new M_user();
		$m_deposit = new M_deposit();
		// $m_param = new M_param();
		$notification = new Notifications();

		$account = $m_user->getUserById($this->session->get('iduser'))[0];

		// $parameter_cutoff = $m_param->getParamById(8)[0]->nilai;
		// $count_initial = $m_deposit->countInitialDeposit($account->iduser)[0]->hitung;

		$total_saldo_wajib = $m_deposit->getSaldoWajibByUserId($account->iduser)[0]->saldo;
		$total_saldo_pokok = $m_deposit->getSaldoPokokByUserId($account->iduser)[0]->saldo;
		$total_saldo_manasuka = $m_deposit->getSaldoManasukaByUserId($account->iduser)[0]->saldo;
		
		if ($account->closebook_request == 'closebook') {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Akun ini sedang dalam masa proses closebook, pengajuan closebook masih dapat dibatalkan',
				 	'status' => 'warning'
				]
			);
			
			$this->session->setFlashdata('notif_cb', $alert);
		}

		$data = [
			'title_meta' => view('anggota/partials/title-meta', ['title' => 'Dashboard']),
			'page_title' => view('anggota/partials/page-title', ['title' => 'Dashboard', 'li_1' => 'EKoperasi', 'li_2' => 'Dashboard']),
			'notification_list' => $notification->index()['notification_list'],
			'notification_badges' => $notification->index()['notification_badges'],
			'duser' => $account,
			'total_saldo_pokok' => $total_saldo_pokok,
			'total_saldo_wajib' => $total_saldo_wajib,
			'total_saldo_manasuka' => $total_saldo_manasuka
		];
		
		return view('anggota/dashboard', $data);
	}
}