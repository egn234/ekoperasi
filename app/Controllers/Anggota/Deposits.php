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
	public function index()
	{
		$m_user = new M_user();
		$m_deposit = new M_deposit();
		$m_param_manasuka = new M_param_manasuka();
		$m_deposit_pag = new M_deposit_pag();
		$notification = new Notifications();

		$account = $m_user->getUserById($this->session->get('iduser'))[0];
	
		$m_param_manasuka_log = new M_param_manasuka_log();

		$depo_list = $m_deposit->getDepositByUserId($account->iduser);

		$currentpage = $this->request->getVar('page_grup1') ? $this->request->getVar('page_grup1') : 1;

		$total_saldo_wajib = $m_deposit->getSaldoWajibByUserId($account->iduser)[0]->saldo;
		$total_saldo_pokok = $m_deposit->getSaldoPokokByUserId($account->iduser)[0]->saldo;
		$total_saldo_manasuka = $m_deposit->getSaldoManasukaByUserId($account->iduser)[0]->saldo;
	
		$param_manasuka = $m_param_manasuka->getParamByUserId($account->iduser);

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
			'notification_list' => $notification->index()['notification_list'],
			'notification_badges' => $notification->index()['notification_badges'],
			'duser' => $account,
			'deposit_list2' => $m_deposit_pag
				->where('idanggota', $account->iduser)
				->orderBy('date_created', 'DESC')
				->paginate(10, 'grup1'),

			'pager' => $m_deposit_pag->pager,
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
		$m_user = new M_user();
		$m_deposit = new M_deposit();
		$m_notification = new M_notification();

		$account = $m_user->getUserById($this->session->get('iduser'))[0];

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
			$this->session->setFlashdata($dataset);
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
				$this->session->setFlashdata($dataset);
				return redirect()->back();
			}

			$cash_in = $nominal;
			$status = 'upload bukti';
		}else{
			$cek_saldo = $m_deposit->cekSaldoManasukaByUser($account->iduser)[0]->saldo_manasuka;

			if ($cek_saldo < $nominal) {
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'Gagal membuat pengajuan: Saldo manasuka kurang untuk membuat pengajuan',
					 	'status' => 'warning'
					]
				);
				
				$dataset = ['notif' => $alert];
				$this->session->setFlashdata($dataset);
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
				$this->session->setFlashdata($dataset);
				return redirect()->back();
			}

			$cek_status_penarikan = $m_deposit->select('COUNT(iddeposit) AS hitung')
				->where('status LIKE "diproses%"')
				->where('jenis_pengajuan', 'penarikan')
				->where('idanggota', $account->iduser)
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
				$this->session->setFlashdata($dataset);
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
			'idanggota' => $account->iduser
		];

		$m_deposit->insertDeposit($dataset);

		$new_deposit = $m_deposit->orderBy('date_created', 'DESC')
									   ->limit(1)
									   ->get()
									   ->getResult()[0];

		if ($new_deposit->status == 'diproses admin') {
			$notification_data = [
				'anggota_id' => $account->iduser,
				'deposit_id' => $new_deposit->iddeposit,
				'message' => 'Pengajuan penarikan manasuka dari anggota '. $account->nama_lengkap,
				'timestamp' => date('Y-m-d H:i:s'),
				'group_type' => 1
			];

			$m_notification->insert($notification_data);
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

		$this->session->setFlashdata($data_session);
		return redirect()->to('anggota/deposit/list');
	}

	public function upload_bukti_transfer($iddeposit = false)
	{
		$m_user = new M_user();
		$m_deposit = new M_deposit();
		$m_notification = new M_notification();

		$account = $m_user->getUserById($this->session->get('iduser'))[0];

		$img = $this->request->getFile('bukti_transfer');

		if ($img->isValid()) {
			
			$cek_bukti = $m_deposit->getDepositById($iddeposit)[0]->bukti_transfer;
			
			if ($cek_bukti) {
				unlink(ROOTPATH . 'public/uploads/user/' . $account->username . '/tf/', $cek_bukti);
			}

			$newName = $img->getRandomName();
			$img->move(ROOTPATH . 'public/uploads/user/' . $account->username . '/tf/', $newName);
			$bukti_transfer = $img->getName();

			$insertData = [
				'bukti_transfer' => $bukti_transfer,
				'status' => 'diproses admin',
				'date_updated' => date('Y-m-d H:i:s')
			];

			$m_deposit->updateBuktiTransfer($iddeposit, $insertData);

			$notification_data = [
				'anggota_id' => $account->iduser,
				'deposit_id' => $iddeposit,
				'message' => 'Pengajuan penyimpanan manasuka dari anggota '. $account->nama_lengkap,
				'timestamp' => date('Y-m-d H:i:s'),
				'group_type' => 1
			];

			$m_notification->insert($notification_data);
			
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
		$this->session->setFlashdata($data_session);
		return redirect()->back();
	}

	public function create_param_manasuka()
	{
		$m_user = new M_user();
		$m_param_manasuka = new M_param_manasuka();

		$account = $m_user->getUserById($this->session->get('iduser'))[0];
		
		$dataset = [
			'idanggota' => $account->iduser,
			'nilai' => filter_var($this->request->getPost('nilai'), FILTER_SANITIZE_NUMBER_INT),
			'created' => date('Y-m-d H:i:s')
		];

		$m_param_manasuka->insertParamManasuka($dataset);
		
		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'Parameter Manasuka berhasil di set',
			 	'status' => 'success'
			]
		);
		
		$data_session = ['notif' => $alert];
		$this->session->setFlashdata($data_session);

		return redirect()->back();
	}

	public function set_param_manasuka($idmnskparam = false)
	{
		$m_param_manasuka = new M_param_manasuka();
		$m_param_manasuka_log = new M_param_manasuka_log();

		$dataset = [
			'nilai' => filter_var($this->request->getPost('nilai'), FILTER_SANITIZE_NUMBER_INT),
			'updated' => date('Y-m-d H:i:s')
		];

		if ($dataset['nilai'] > 50000) {
			$m_param_manasuka->updateParamManasuka($idmnskparam, $dataset);
			
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
		$this->session->setFlashdata($data_session);

		return redirect()->back();
	}

	public function cancel_param_manasuka($idmnskparam = false)
	{
		$m_param_manasuka = new M_param_manasuka();

		$dataset = [
			'nilai' => 0,
			'updated' => date('Y-m-d H:i:s')
		];
		
		$m_param_manasuka->updateParamManasuka($idmnskparam, $dataset);


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

		$this->session->setFlashdata($data_session);
		return redirect()->back();
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