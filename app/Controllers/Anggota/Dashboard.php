<?php 
namespace App\Controllers\Anggota;

use CodeIgniter\Controller;
use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_param;

class Dashboard extends Controller
{

	function __construct()
	{
		$this->m_user = new M_user();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
		$this->m_deposit = new M_deposit();
		$this->m_param = new M_param();
	}

	public function index()
	{
		$cek_initial = $this->m_deposit->getInitialDeposit($this->account->iduser);
		$parameter_cutoff = $this->m_param->getParamById(8)[0]->nilai;

		if ($cek_initial[0]->status == 'diproses') {
			if (($parameter_cutoff - 3) >= date('d') && date('d') <= $parameter_cutoff) {
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'Segera konfirmasi bukti bayar untuk penyimpanan pokok sebelum tanggal '. $parameter_cutoff,
					 	'status' => 'warning'
					]
				);
				
				session()->setFlashdata('notif_pokok', $alert);
			}
		}

		if ($cek_initial[1]->status == 'diproses') {
			if (($parameter_cutoff - 3) >= date('d') && date('d') <= $parameter_cutoff) {
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'Segera konfirmasi bukti bayar untuk penyimpanan wajib sebelum tanggal '. $parameter_cutoff,
					 	'status' => 'warning'
					]
				);
				
				session()->setFlashdata('notif_wajib', $alert);
			}
		}

		$data = [
			'title_meta' => view('anggota/partials/title-meta', ['title' => 'Dashboard']),
			'page_title' => view('anggota/partials/page-title', ['title' => 'Dashboard', 'li_1' => 'EKoperasi', 'li_2' => 'Dashboard']),
			'duser' => $this->account
		];
		
		return view('anggota/dashboard', $data);
	}
}