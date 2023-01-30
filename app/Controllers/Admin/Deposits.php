<?php 
namespace App\Controllers\Admin;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_deposit_pag;
use App\Models\M_param_manasuka;

use App\Controllers\Admin\Notifications;

class Deposits extends Controller
{

	function __construct()
	{
		$this->m_user = new M_user();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
		$this->m_deposit = new M_deposit();
		$this->m_deposit_pag = new M_deposit_pag();
		$this->m_param_manasuka = new M_param_manasuka();
		$this->notification = new Notifications();
	}

	public function index()
	{
		$anggota_list = $this->m_user->getAllAnggotaSaldo();

		$data = [
			'title_meta' => view('admin/partials/title-meta', ['title' => 'Kelola Anggota']),
			'page_title' => view('admin/partials/page-title', ['title' => 'Kelola Anggota', 'li_1' => 'EKoperasi', 'li_2' => 'Kelola Anggota']),
			'notification_list' => $this->notification->index()['notification_list'],
			'notification_badges' => $this->notification->index()['notification_badges'],
			'duser' => $this->account,
			'anggota_list' => $anggota_list
		];
		
		return view('admin/deposit/anggota-list', $data);
	}

	public function list_transaksi()
	{
		$transaksi_list = $this->m_deposit->getAllDeposit();
		$transaksi_list_filter = $this->m_deposit->getAllDepositFilter();
		
		$data = [
			'title_meta' => view('admin/partials/title-meta', ['title' => 'Kelola Transaksi Simpanan']),
			'page_title' => view('admin/partials/page-title', ['title' => 'Kelola Transaksi Simpanan', 'li_1' => 'EKoperasi', 'li_2' => 'Kelola Transaksi Simpanan']),
			'notification_list' => $this->notification->index()['notification_list'],
			'notification_badges' => $this->notification->index()['notification_badges'],
			'duser' => $this->account,
			'transaksi_list' => $transaksi_list,
			'transaksi_list_filter' => $transaksi_list_filter
		];
		
		return view('admin/deposit/deposit-list', $data);
	}

	public function detail_anggota($iduser = false)
	{
		$depo_list = $this->m_deposit->getDepositByUserId($iduser);
		$total_saldo_wajib = $this->m_deposit->getSaldoWajibByUserId($iduser)[0]->saldo;
		$total_saldo_pokok = $this->m_deposit->getSaldoPokokByUserId($iduser)[0]->saldo;
		$total_saldo_manasuka = $this->m_deposit->getSaldoManasukaByUserId($iduser)[0]->saldo;
		$detail_user = $this->m_user->getUserById($iduser)[0];
		$param_manasuka = $this->m_param_manasuka->getParamByUserId($iduser);
		$currentpage = $this->request->getVar('page_grup1') ? $this->request->getVar('page_grup1') : 1;

		$data = [
			'title_meta' => view('admin/partials/title-meta', ['title' => 'Detail Simpanan']),
			'page_title' => view('admin/partials/page-title', ['title' => 'Detail Simpanan', 'li_1' => 'EKoperasi', 'li_2' => 'Detail Simpanan']),
			'notification_list' => $this->notification->index()['notification_list'],
			'notification_badges' => $this->notification->index()['notification_badges'],
			'duser' => $this->account,
			'detail_user' => $detail_user,
			'deposit_list' => $depo_list,
			'total_saldo_wajib' => $total_saldo_wajib,
			'total_saldo_pokok' => $total_saldo_pokok,
			'total_saldo_manasuka' => $total_saldo_manasuka,
			'param_manasuka' => $param_manasuka,
			'deposit_list2' => $this->m_deposit_pag
				->where('idanggota', $iduser)
				->orderBy('date_created', 'DESC')
				->paginate(10, 'grup1'),

			'pager' => $this->m_deposit_pag->pager,
			'currentpage' => $currentpage
		];
		
		return view('admin/deposit/anggota-detail', $data);
	}

	public function add_proc()
	{

		$iduser = $this->request->getPost('iduser');
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
			return redirect()->to('admin/deposit/user/'.$iduser);
		}

		$jenis_deposit = 'manasuka';

		$nominal = filter_var($this->request->getPost('nominal'), FILTER_SANITIZE_NUMBER_INT);
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
			'status' => 'diterima',
			'date_created' => date('Y-m-d H:i:s'),
			'idanggota' => $iduser,
			'idadmin' => $this->account->iduser
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
		return redirect()->to('admin/deposit/user/'.$iduser);
	}

	public function create_param_manasuka()
	{
		$dataset = [
			'idanggota' => $this->request->getPost('iduser'),
			'nilai' => filter_var($this->request->getPost('nilai'), FILTER_SANITIZE_NUMBER_INT),
			'created' => date('Y-m-d H:i:s')
		];

		$this->m_param_manasuka->insertParamManasuka($dataset);
		
		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'Parameter Manasuka berhasil di set',
			 	'status' => 'success'
			]
		);
		
		$data_session = ['notif' => $alert];
		session()->setFlashdata($data_session);

		return redirect()->back();
	}

	public function set_param_manasuka($idmnskparam = false)
	{
		$dataset = [
			'nilai' => filter_var($this->request->getPost('nilai'), FILTER_SANITIZE_NUMBER_INT),
			'updated' => date('Y-m-d H:i:s')
		];


		if ($dataset['nilai'] > 50000) {
			$this->m_param_manasuka->updateParamManasuka($idmnskparam, $dataset);
			
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Parameter Manasuka berhasil di set',
				 	'status' => 'success'
				]
			);
		} else {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Pengajuan manasuka tidak boleh kurang dari Rp 50.000',
				 	'status' => 'warning'
				]
			);
		}
		
		$data_session = ['notif' => $alert];
		session()->setFlashdata($data_session);

		return redirect()->back();
	}

	public function cancel_param_manasuka($idmnskparam = false)
	{
		$iduser = $this->account->iduser;

		$dataset = [
			'nilai' => 0,
			'updated' => date('Y-m-d H:i:s')
		];
		
		$this->m_param_manasuka->updateParamManasuka($idmnskparam, $dataset);


		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'Pembatalan manasuka berhasil',
			 	'status' => 'success'
			]
		);
		
		$data_session = [
			'notif' => $alert
		];

		session()->setFlashdata($data_session);
		return redirect()->back();
	}

	public function konfirmasi_mutasi($iddeposit = false)
	{

		$dataset = [
			'idadmin' => $this->account->iduser,
			'status' => 'diterima',
			'date_updated' => date('Y-m-d H:i:s')
		];

		$this->m_deposit->setStatus($iddeposit, $dataset);
		
		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'Permohonan Berhasil Dikonfirmasi',
			 	'status' => 'success'
			]
		);
		
		$data_session = ['notif' => $alert];
		session()->setFlashdata($data_session);
		return redirect()->back();
	}

	public function batalkan_mutasi($iddeposit = false)
	{

		$dataset = [
			'idadmin' => $this->account->iduser,
			'status' => 'ditolak',
			'date_updated' => date('Y-m-d H:i:s')
		];

		$this->m_deposit->setStatus($iddeposit, $dataset);
		
		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'Permohonan Berhasil Dikonfirmasi',
			 	'status' => 'success'
			]
		);
		
		$dataset = ['notif' => $alert];
		session()->setFlashdata($dataset);
		return redirect()->back();
	}

	public function detail_mutasi()
	{
		if ($_POST['rowid']) {
			$id = $_POST['rowid'];
			

			$dsimpanan = $this->m_deposit->getDepositById($id)[0];
			
			$duser = $this->m_user->getUserById($dsimpanan->idanggota)[0];

			$total_saldo_wajib = $this->m_deposit->getSaldoWajibByUserId($dsimpanan->idanggota)[0]->saldo;
			$total_saldo_pokok = $this->m_deposit->getSaldoPokokByUserId($dsimpanan->idanggota)[0]->saldo;
			$total_saldo_manasuka = $this->m_deposit->getSaldoManasukaByUserId($dsimpanan->idanggota)[0]->saldo;
			$total_saldo = '';

			$confirmation = false;

			if ($dsimpanan->cash_in == 0) {
				if ($dsimpanan->jenis_deposit == 'wajib') {

					$total_saldo = $total_saldo_wajib;
					
					if ($total_saldo_wajib < $dsimpanan->cash_out) {
						$confirmation = true;
					}else{
						$confirmation = false;
					}
				
				}elseif ($dsimpanan->jenis_deposit == 'pokok') {
					
					$total_saldo = $total_saldo_pokok;
					
					if ($total_saldo_pokok < $dsimpanan->cash_out) {
						$confirmation = true;
					}else{
						$confirmation = false;
					}
				
				}elseif ($dsimpanan->jenis_deposit == 'manasuka') {
					
					$total_saldo = $total_saldo_manasuka;
					
					if ($total_saldo_manasuka < $dsimpanan->cash_out) {
						$confirmation = true;
					}else{
						$confirmation = false;
					}

				}
			}

			$data = [
				'a' => $dsimpanan,
				'duser' => $duser,
				'total_saldo' => $total_saldo,
				'confirmation' => $confirmation
			];
			echo view('admin/deposit/part-depo-mod-detail', $data);
		}
	}

}