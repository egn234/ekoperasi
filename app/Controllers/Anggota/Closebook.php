<?php 
namespace App\Controllers\Anggota;

use CodeIgniter\Controller;
use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_param;

class Closebook extends Controller
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
		$total_saldo_wajib = $this->m_deposit->getSaldoWajibByUserId($this->account->iduser)[0]->saldo;
		$total_saldo_pokok = $this->m_deposit->getSaldoPokokByUserId($this->account->iduser)[0]->saldo;
		$total_saldo_manasuka = $this->m_deposit->getSaldoManasukaByUserId($this->account->iduser)[0]->saldo;

		$data = [
			'title_meta' => view('anggota/partials/title-meta', ['title' => 'Tutup Buku']),
			'page_title' => view('anggota/partials/page-title', ['title' => 'Tutup Buku', 'li_1' => 'EKoperasi', 'li_2' => 'Tutup Buku']),
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