<?php 
namespace App\Controllers\Ketua;

use CodeIgniter\Controller;
use App\Models\M_user;
use App\Models\M_pinjaman;

class Pinjaman extends Controller
{

	function __construct()
	{
		$this->m_user = new M_user();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
		$this->m_pinjaman = new M_pinjaman();
	}

	public function index()
	{
		$list_pinjaman = $this->m_pinjaman->getPinjamanByStatus(2);

		$data = [
			'title_meta' => view('ketua/partials/title-meta', ['title' => 'Pinjaman']),
			'page_title' => view('ketua/partials/page-title', ['title' => 'Pinjaman', 'li_1' => 'EKoperasi', 'li_2' => 'Pinjaman']),
			'duser' => $this->account,
			'list_pinjaman' => $list_pinjaman
		];
		
		return view('ketua/pinjaman/list-pinjaman', $data);
	}

	public function cancel_proc($idpinjaman = false)
	{
		$dataset = [
			'idketua' => $this->account->iduser,
			'status' => 0,
			'date_updated' => date('Y-m-d H:i:s')
		];

		$this->m_pinjaman->updatePinjaman($idpinjaman, $dataset);

		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'Pengajuan pinjaman barhasil ditolak',
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
			'status' => 3,
			'date_updated' => date('Y-m-d H:i:s')
		];

		$this->m_pinjaman->updatePinjaman($idpinjaman, $dataset);

		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'Pengajuan pinjaman barhasil disetujui',
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