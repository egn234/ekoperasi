<?php 
namespace App\Controllers\Anggota;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_param;

use App\Controllers\Anggota\Notifications;

class Dashboard extends Controller
{

	function __construct()
	{
		$this->m_user = new M_user();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
		$this->m_deposit = new M_deposit();
		$this->m_param = new M_param();
		$this->notification = new Notifications();
	}

	public function index()
	{
		$parameter_cutoff = $this->m_param->getParamById(8)[0]->nilai;
		$count_initial = $this->m_deposit->countInitialDeposit($this->account->iduser)[0]->hitung;

		$total_saldo_wajib = $this->m_deposit->getSaldoWajibByUserId($this->account->iduser)[0]->saldo;
		$total_saldo_pokok = $this->m_deposit->getSaldoPokokByUserId($this->account->iduser)[0]->saldo;
		$total_saldo_manasuka = $this->m_deposit->getSaldoManasukaByUserId($this->account->iduser)[0]->saldo;
		
		// if ($count_initial != 0) {	
		// 	$cek_initial = $this->m_deposit->getInitialDeposit($this->account->iduser);
		// 	if ($cek_initial[0]->status == 'diproses') {
		// 		if (($parameter_cutoff - 3) >= date('d') && date('d') <= $parameter_cutoff) {
		// 			$alert = view(
		// 				'partials/notification-alert', 
		// 				[
		// 					'notif_text' => 'Segera konfirmasi bukti bayar untuk penyimpanan pokok sebelum tanggal '. $parameter_cutoff,
		// 				 	'status' => 'warning'
		// 				]
		// 			);
					
		// 			session()->setFlashdata('notif_pokok', $alert);
		// 		}
		// 	}

		// 	if ($cek_initial[1]->status == 'diproses') {
		// 		if (($parameter_cutoff - 3) >= date('d') && date('d') <= $parameter_cutoff) {
		// 			$alert = view(
		// 				'partials/notification-alert', 
		// 				[
		// 					'notif_text' => 'Segera konfirmasi bukti bayar untuk penyimpanan wajib sebelum tanggal '. $parameter_cutoff,
		// 				 	'status' => 'warning'
		// 				]
		// 			);
					
		// 			session()->setFlashdata('notif_wajib', $alert);
		// 		}
		// 	}
		// }

		if ($this->account->closebook_request == 'closebook') {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Akun ini sedang dalam masa proses closebook, pengajuan closebook masih dapat dibatalkan',
				 	'status' => 'warning'
				]
			);
			
			session()->setFlashdata('notif_cb', $alert);
		}

		$data = [
			'title_meta' => view('anggota/partials/title-meta', ['title' => 'Dashboard']),
			'page_title' => view('anggota/partials/page-title', ['title' => 'Dashboard', 'li_1' => 'EKoperasi', 'li_2' => 'Dashboard']),
			'notification_list' => $this->notification->index()['notification_list'],
			'notification_badges' => $this->notification->index()['notification_badges'],
			'duser' => $this->account,
			'total_saldo_pokok' => $total_saldo_pokok,
			'total_saldo_wajib' => $total_saldo_wajib,
			'total_saldo_manasuka' => $total_saldo_manasuka
		];
		
		return view('anggota/dashboard', $data);
	}
}