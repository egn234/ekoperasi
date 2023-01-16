<?php 
namespace App\Controllers\Anggota;

use CodeIgniter\Controller;
use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_deposit_pag;
use App\Models\M_param_manasuka;

class Deposits extends Controller
{

	function __construct()
	{
		$this->m_user = new M_user();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
		$this->m_deposit = new M_deposit();
		$this->m_deposit_pag = new M_deposit_pag();
		$this->m_param_manasuka = new M_param_manasuka();
	}

	public function index()
	{
		$depo_list = $this->m_deposit->getDepositByUserId($this->account->iduser);

		$currentpage = $this->request->getVar('page_grup1') ? $this->request->getVar('page_grup1') : 1;

		$total_saldo_wajib = $this->m_deposit->getSaldoWajibByUserId($this->account->iduser)[0]->saldo;
		$total_saldo_pokok = $this->m_deposit->getSaldoPokokByUserId($this->account->iduser)[0]->saldo;
		$total_saldo_manasuka = $this->m_deposit->getSaldoManasukaByUserId($this->account->iduser)[0]->saldo;
	
		$param_manasuka = $this->m_param_manasuka->getParamByUserId($this->account->iduser);

		$data = [
			'title_meta' => view('anggota/partials/title-meta', ['title' => 'Simpanan']),
			'page_title' => view('anggota/partials/page-title', ['title' => 'Simpanan', 'li_1' => 'EKoperasi', 'li_2' => 'Simpanan']),
			'duser' => $this->account,
			'deposit_list' => $depo_list,
			'total_saldo_wajib' => $total_saldo_wajib,
			'total_saldo_pokok' => $total_saldo_pokok,
			'total_saldo_manasuka' => $total_saldo_manasuka,
			'param_manasuka' => $param_manasuka,

			'deposit_list2' => $this->m_deposit_pag
				->where('idanggota', $this->account->iduser)
				->orderBy('date_created', 'DESC')
				->paginate(10, 'grup1'),

			'pager' => $this->m_deposit_pag->pager,
			'currentpage' => $currentpage
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

		$jenis_deposit = 'manasuka';

		$nominal = filter_var($this->request->getPost('nominal'), FILTER_SANITIZE_NUMBER_INT);
		$deskripsi = $this->request->getPost('deskripsi');


		$cash_in = 0;
		$cash_out = 0;

		if ($jenis_pengajuan == 'penyimpanan') {
			$cash_in = $nominal;
		
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
				return redirect()->to('anggota/deposit/list');
			}
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
		$dataset = [
			'nilai' => filter_var($this->request->getPost('nilai'), FILTER_SANITIZE_NUMBER_INT),
			'updated' => date('Y-m-d H:i:s')
		];

		if ($dataset['nilai'] == 0 || $dataset['nilai'] > 50000) {
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