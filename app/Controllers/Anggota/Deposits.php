<?php 
namespace App\Controllers\Anggota;

use App\Controllers\BaseController;
use App\Controllers\Anggota\Notifications;

use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_deposit_pag;
use App\Models\M_param_manasuka;
use App\Models\M_param_manasuka_log;
use App\Models\M_notification;

class Deposits extends BaseController
{
	protected $m_user;
	protected $m_deposit;
	protected $m_deposit_pag;
	protected $m_param_manasuka;
	protected $m_notification;
	protected $account;
	protected $notification;

	function __construct()
	{
		$this->m_user = new M_user();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
		$this->m_deposit = new M_deposit();
		$this->m_deposit_pag = new M_deposit_pag();
		$this->m_param_manasuka = new M_param_manasuka();
		$this->m_notification = new M_notification();
		$this->notification = new Notifications();
	}

	public function index()
	{
		$m_param_manasuka_log = new M_param_manasuka_log();

		$depo_list = $this->m_deposit->getDepositByUserId($this->account->iduser);

		$currentpage = $this->request->getVar('page_grup1') ? $this->request->getVar('page_grup1') : 1;

		$total_saldo_wajib = $this->m_deposit->getSaldoWajibByUserId($this->account->iduser)[0]->saldo;
		$total_saldo_pokok = $this->m_deposit->getSaldoPokokByUserId($this->account->iduser)[0]->saldo;
		$total_saldo_manasuka = $this->m_deposit->getSaldoManasukaByUserId($this->account->iduser)[0]->saldo;
	
		$param_manasuka = $this->m_param_manasuka->getParamByUserId($this->account->iduser);

		$mnsk_param_log = $m_param_manasuka_log->select("COUNT(id) as hitung")
			->where('idmnskparam', $param_manasuka[0]->idmnskparam)
			->get()->getResult()[0]
			->hitung != 0
			? $m_param_manasuka_log->where('idmnskparam', $param_manasuka[0]->idmnskparam)
				->limit(1)
				->get()
				->getResult()[0]
				->created_at 
			: date('Y-m-d H:i:s', strtotime('-3 months'));

		$data = [
			'title_meta' => view('anggota/partials/title-meta', ['title' => 'Simpanan']),
			'page_title' => view('anggota/partials/page-title', ['title' => 'Simpanan', 'li_1' => 'EKoperasi', 'li_2' => 'Simpanan']),
			'notification_list' => $this->notification->index()['notification_list'],
			'notification_badges' => $this->notification->index()['notification_badges'],
			'duser' => $this->account,
			'deposit_list2' => $this->m_deposit_pag
				->where('idanggota', $this->account->iduser)
				->orderBy('date_created', 'DESC')
				->paginate(10, 'grup1'),

			'pager' => $this->m_deposit_pag->pager,
			'currentpage' => $currentpage,
			'deposit_list' => $depo_list,
			'total_saldo_wajib' => $total_saldo_wajib,
			'total_saldo_pokok' => $total_saldo_pokok,
			'total_saldo_manasuka' => $total_saldo_manasuka,
			'param_manasuka_cek' => $mnsk_param_log,
			'param_manasuka' => $param_manasuka
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

		$jenis_deposit = 'manasuka free';

		$nominal = filter_var($this->request->getPost('nominal'), FILTER_SANITIZE_NUMBER_INT);
		$deskripsi = $this->request->getPost('description');

		$cash_in = 0;
		$cash_out = 0;
		$status = false;

		if ($jenis_pengajuan == 'penyimpanan') {
			
			if ($nominal < 300000) {
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'Gagal membuat pengajuan: Penyimpanan tidak boleh kurang dari Rp 300.000',
					 	'status' => 'warning'
					]
				);
				
				$dataset = ['notif' => $alert];
				session()->setFlashdata($dataset);
				return redirect()->back();
			}

			$cash_in = $nominal;
			$status = 'upload bukti';
		}else{
			$cek_saldo = $this->m_deposit->cekSaldoManasukaByUser($this->account->iduser)[0]->saldo_manasuka;

			if ($cek_saldo < $nominal) {
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'Gagal membuat pengajuan: Saldo manasuka kurang untuk membuat pengajuan',
					 	'status' => 'warning'
					]
				);
				
				$dataset = ['notif' => $alert];
				session()->setFlashdata($dataset);
				return redirect()->back();
			}

			if ($nominal < 300000) {
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'Gagal membuat pengajuan: Penarikan tidak boleh kurang dari Rp 300.000',
					 	'status' => 'warning'
					]
				);
				
				$dataset = ['notif' => $alert];
				session()->setFlashdata($dataset);
				return redirect()->back();
			}

			$cek_status_penarikan = $this->m_deposit->select('COUNT(iddeposit) AS hitung')
				->where('status LIKE "diproses%"')
				->where('jenis_pengajuan', 'penarikan')
				->where('idanggota', $this->account->iduser)
				->where('jenis_deposit LIKE "manasuka%"')
				->get()->getResult()[0]
				->hitung;

			if ($cek_status_penarikan > 0) {
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'Gagal membuat pengajuan: Selesaikan dahulu pengajuan sebelumnya',
					 	'status' => 'warning'
					]
				);
				
				$dataset = ['notif' => $alert];
				session()->setFlashdata($dataset);
				return redirect()->back();
			}

			$cash_out = $nominal;
			$status = 'diproses admin';
		}

		$dataset = [
			'jenis_pengajuan' => $jenis_pengajuan,
			'jenis_deposit' => $jenis_deposit,
			'cash_in' => $cash_in,
			'cash_out' => $cash_out,
			'deskripsi' => $deskripsi,
			'status' => $status,
			'date_created' => date('Y-m-d H:i:s'),
			'idanggota' => $this->account->iduser
		];

		$this->m_deposit->insertDeposit($dataset);

		$new_deposit = $this->m_deposit->orderBy('date_created', 'DESC')
									   ->limit(1)
									   ->get()
									   ->getResult()[0];

		if ($new_deposit->status == 'diproses admin') {
			$notification_data = [
				'anggota_id' => $this->account->iduser,
				'deposit_id' => $new_deposit->iddeposit,
				'message' => 'Pengajuan penarikan manasuka dari anggota '. $this->account->nama_lengkap,
				'timestamp' => date('Y-m-d H:i:s'),
				'group_type' => 1
			];

			$this->m_notification->insert($notification_data);
		}

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

			$insertData = [
				'bukti_transfer' => $bukti_transfer,
				'status' => 'diproses admin',
				'date_updated' => date('Y-m-d H:i:s')
			];

			$this->m_deposit->updateBuktiTransfer($iddeposit, $insertData);

			$notification_data = [
				'anggota_id' => $this->account->iduser,
				'deposit_id' => $iddeposit,
				'message' => 'Pengajuan penyimpanan manasuka dari anggota '. $this->account->nama_lengkap,
				'timestamp' => date('Y-m-d H:i:s'),
				'group_type' => 1
			];

			$this->m_notification->insert($notification_data);
			
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
		return redirect()->back();
	}

	public function create_param_manasuka()
	{
		$dataset = [
			'idanggota' => $this->account->iduser,
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
		$m_param_manasuka_log = new M_param_manasuka_log();
		$dataset = [
			'nilai' => filter_var($this->request->getPost('nilai'), FILTER_SANITIZE_NUMBER_INT),
			'updated' => date('Y-m-d H:i:s')
		];

		if ($dataset['nilai'] > 50000) {
			$this->m_param_manasuka->updateParamManasuka($idmnskparam, $dataset);
			
			$temp_log = [
				'nominal' => $dataset['nilai'],
				'idmnskparam' => $idmnskparam,
				'created_at' => date('Y-m-d H:i:s')
			];
			
			$m_param_manasuka_log->insert($temp_log);
			
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
				'notif_text' => 'Pengajuan pembatalan manasuka berhasil',
			 	'status' => 'success'
			]
		);
		
		$data_session = [
			'notif' => $alert
		];

		session()->setFlashdata($data_session);
		return redirect()->back();
	}

	public function detail_mutasi()
	{
		$m_user = new M_user();
		$m_deposit = new M_deposit();

		$account = $m_user->getUserById(session()->get('iduser'))[0];

		if ($_POST['rowid']) {
			$id = $_POST['rowid'];
			$user = $m_deposit->getDepositById($id)[0];
			$data = [
				'a' => $user,
				'duser' => $account
			];
			echo view('anggota/deposit/part-depo-mod-detail', $data);
		}
	}

	public function up_mutasi()
	{
		$m_deposit = new M_deposit();
		if ($_POST['rowid']) {
			$id = $_POST['rowid'];
			$user = $m_deposit->getDepositById($id)[0];
			$data = ['a' => $user];
			echo view('anggota/deposit/part-depo-mod-upload', $data);
		}
	}
}