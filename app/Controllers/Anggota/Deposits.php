<?php 
namespace App\Controllers\Anggota;

use CodeIgniter\Controller;
use App\Models\M_user;
use App\Models\M_deposit;

class Deposits extends Controller
{

	function __construct()
	{
		$this->m_user = new M_user();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
		$this->m_deposit = new M_deposit();
	}

	public function index()
	{
		$depo_list = $this->m_deposit->getDepositByUserId($this->account->iduser);
		$total_saldo = $this->m_deposit->getSaldoByUserId($this->account->iduser)[0]->saldo;

		$data = [
			'title_meta' => view('anggota/partials/title-meta', ['title' => 'Dashboard']),
			'page_title' => view('anggota/partials/page-title', ['title' => 'Dashboard', 'li_1' => 'EKoperasi', 'li_2' => 'Dashboard']),
			'duser' => $this->account,
			'deposit_list' => $depo_list,
			'total_saldo' => $total_saldo
		];
		
		return view('anggota/deposit/deposit-list', $data);
	}

	public function add_proc()
	{

		$jenis_pengajuan = $this->request->getPost('jenis_pengajuan');
		if ($jenis_pengajuan == "") {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Gagal membuat pengajuan: Pilih jenis pengajuan terlebih dahulu',
				 	'status' => 'warning'
				]
			);
			
			$dataset = ['notif' => $alert];
			session()->setFlashdata($dataset);
			return redirect()->to('anggota/deposit/list');
		}

		$jenis_deposit = $this->request->getPost('jenis_deposit');
		if ($jenis_deposit == "") {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Gagal membuat pengajuan: Pilih jenis simpanan terlebih dahulu',
				 	'status' => 'warning'
				]
			);
			
			$dataset = ['notif' => $alert];
			session()->setFlashdata($dataset);
			return redirect()->to('anggota/deposit/list');
		}

		$nominal = $this->request->getPost('nominal');
		$deskripsi = $this->request->getPost('deskripsi');

		$cash_in = 0;
		$cash_out = 0;

		if ($jenis_pengajuan == 'penyimpanan') {
			$cash_in = $nominal;
		}else{
			$cash_out = $nominal;
		}

		$dataset = [
			'jenis_pengajuan' => $jenis_pengajuan,
			'jenis_deposit' => $jenis_deposit,
			'cash_in' => $cash_in,
			'cash_out' => $cash_out,
			'deskripsi' => $deskripsi,
			'status' => 'diproses',
			'date_created' => date('Y-m-d H:i:s'),
			'idanggota' => $this->account->iduser
		];

		$this->m_deposit->insertDeposit($dataset);

		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'Pengajuan berhasil dibuat',
			 	'status' => 'success'
			]
		);
		
		$data_session = [
			'notif' => $alert
		];

		session()->setFlashdata($data_session);
		return redirect()->to('anggota/deposit/list');
	}

	public function upload_bukti_transfer($iddeposit = false)
	{
		$img = $this->request->getFile('bukti_transfer');

		if ($img->isValid()) {
			
			$cek_bukti = $this->m_deposit->getDepositById($iddeposit)[0]->bukti_transfer;
			
			if ($cek_bukti) {
				unlink(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/tf/', $cek_bukti);
			}

			$newName = $img->getRandomName();
			$img->move(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/tf/', $newName);
			$bukti_transfer = $img->getName();

			$this->m_deposit->updateBuktiTransfer($iddeposit, $bukti_transfer);
			
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Bukti bayar berhasil diunggah',
				 	'status' => 'success'
				]
			);

		}else{
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Bukti bayar gagal diunggah',
				 	'status' => 'danger'
				]
			);
			
		}
		
		$data_session = [
			'notif' => $alert
		];
		session()->setFlashdata($data_session);
		return redirect()->to('anggota/deposit/list');
	}

	public function detail_mutasi()
	{
		if ($_POST['rowid']) {
			$id = $_POST['rowid'];
			$user = $this->m_deposit->getDepositById($id)[0];
			$data = [
				'a' => $user,
				'duser' => $this->account
			];
			echo view('anggota/deposit/part-depo-mod-detail', $data);
		}
	}

	public function up_mutasi()
	{
		if ($_POST['rowid']) {
			$id = $_POST['rowid'];
			$user = $this->m_deposit->getDepositById($id)[0];
			$data = ['a' => $user];
			echo view('anggota/deposit/part-depo-mod-upload', $data);
		}
	}
}