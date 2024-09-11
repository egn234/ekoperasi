<?php 
namespace App\Controllers\Anggota;

use App\Controllers\BaseController;
use App\Controllers\Anggota\Notifications;

use App\Models\M_user;
use App\Models\M_pinjaman;
use App\Models\M_cicilan;
use App\Models\M_cicilan_pag;
use App\Models\M_param;
use App\Models\M_notification;

class Pinjaman extends BaseController
{
	protected $m_user;
	protected $account;
	protected $m_pinjaman;
	protected $m_cicilan;
	protected $m_cicilan_pag;
	protected $m_param;
	protected $m_notification;
	protected $notification;

	function __construct()
	{
		$this->m_user = new M_user();
		$this->account = $this->m_user->getUserById($this->session->get('iduser'))[0];
		$this->m_pinjaman = new M_pinjaman();
		$this->m_cicilan = new M_cicilan();
		$this->m_cicilan_pag = new M_cicilan_pag();
		$this->m_param = new M_param();
		$this->m_notification = new M_notification();
		$this->notification = new Notifications();
	}

	public function index()
	{
		$list_pinjaman = $this->m_pinjaman->getPinjamanByIdAnggota($this->account->iduser);

		$data = [
			'title_meta' => view('anggota/partials/title-meta', ['title' => 'Pinjaman']),
			'page_title' => view('anggota/partials/page-title', ['title' => 'Pinjaman', 'li_1' => 'EKoperasi', 'li_2' => 'Pinjaman']),
			'notification_list' => $this->notification->index()['notification_list'],
			'notification_badges' => $this->notification->index()['notification_badges'],
			'duser' => $this->account,
			'list_pinjaman' => $list_pinjaman
		];
		
		return view('anggota/pinjaman/list-pinjaman', $data);
	}

	function detail($idpinjaman = false)
	{
		$detail_pinjaman = $this->m_pinjaman->getPinjamanById($idpinjaman)[0];
		$tagihan_lunas = $this->m_cicilan->getSaldoTerbayarByIdPinjaman($idpinjaman)[0];
		$currentpage = $this->request->getVar('page_grup1') ? $this->request->getVar('page_grup1') : 1;

		$data = [
			'title_meta' => view('anggota/partials/title-meta', ['title' => 'Pinjaman']),
			'page_title' => view('anggota/partials/page-title', ['title' => 'Pinjaman', 'li_1' => 'EKoperasi', 'li_2' => 'Pinjaman']),
			'notification_list' => $this->notification->index()['notification_list'],
			'notification_badges' => $this->notification->index()['notification_badges'],
			'duser' => $this->account,
			'list_cicilan2' => $this->m_cicilan_pag
				->select('
					(
						SELECT SUM(nominal)
						FROM tb_cicilan b WHERE b.date_created <= tb_cicilan.date_created
        				AND idpinjaman = tb_cicilan.idpinjaman
					) AS saldo,
					DATE_FORMAT(date_created, "%Y-%m-%d") as date,
					(
						SELECT COUNT(idcicilan)
						FROM tb_cicilan c WHERE c.date_created <= tb_cicilan.date_created
                        AND idpinjaman = tb_cicilan.idpinjaman
					) AS counter,
					tb_cicilan.*,
					SUM(tb_cicilan.nominal) as total_saldo'
				)
				->where('idpinjaman', $idpinjaman)
				->orderBy('date_created', 'DESC')
				->groupBy('date')
				->paginate(10, 'grup1'),

			'pager' => $this->m_cicilan_pag->pager,
			'currentpage' => $currentpage,
			'detail_pinjaman' => $detail_pinjaman,
			'tagihan_lunas' => $tagihan_lunas
		];
		
		return view('anggota/pinjaman/list-cicilan', $data);	
	}

	public function add_proc()
	{
		$cek_cicilan_aktif = $this->m_pinjaman->countPinjamanAktifByAnggota($this->account->iduser)[0]->hitung;
		$cek_cicilan = $this->request->getPost('angsuran_bulanan');
		$satuan_waktu = $this->request->getPost('satuan_waktu');
		$cek_pegawai = $this->m_user->where('iduser', $this->account->iduser)
									->get()
									->getResult()[0]
									->status_pegawai;
		if($cek_pegawai == 'tetap'){
			$batas_bulanan = 24;
			$batas_nominal = 50000000;
		}else{
			$batas_bulanan = 12;
			$batas_nominal = 15000000;
		}

		$dataset = [];
		if ($cek_cicilan_aktif != 0) {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Tidak dapat mengajukan pinjaman: masih ada pinjaman yang sedang berlangsung',
				 	'status' => 'danger'
				]
			);
			
			$dataset += ['notif' => $alert];
			$this->session->setFlashdata($dataset);
			return redirect()->back();
		}

		if ($satuan_waktu == 2) {
			$angsuran_bulanan = $cek_cicilan * 12;
		}else{
			$angsuran_bulanan = $cek_cicilan;
		}

		$dataset = [
			'nominal' => filter_var($this->request->getPost('nominal'), FILTER_SANITIZE_NUMBER_INT),
			'tipe_permohonan' => $this->request->getPost('tipe_permohonan'),
			'deskripsi' => $this->request->getPost('deskripsi'),
			'angsuran_bulanan' => $angsuran_bulanan
		];

		if ($angsuran_bulanan > $batas_bulanan) {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Tidak dapat mengajukan cicilan lebih dari '. $angsuran_bulanan .' bulan',
				 	'status' => 'warning'
				]
			);
			
			$dataset += ['notif_bulanan' => $alert];
			$confirmation = false;
		}else{
			$confirmation = true;
		}

		if ($dataset['nominal'] > $batas_nominal) {
			
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Tidak dapat mengajukan cicilan lebih dari Rp'. number_format($dataset['nominal'], 0, ',','.'),
				 	'status' => 'warning'
				]
			);
			
			$dataset += ['notif' => $alert];
			$confirmation = false;
		}else{
			$confirmation = true;
		}
		
		if ($confirmation) {

			if ($dataset['tipe_permohonan'] == "") {
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'Pilih Tipe Permohonan Terlebih Dahulu',
					 	'status' => 'warning'
					]
				);
				
				$dataset += ['notif' => $alert];
				$this->session->setFlashdata($dataset);
				return redirect()->back();
			}

			$dataset += [
				'date_created' => date('Y-m-d H:i:s'),
				'status' => 1,
				'idanggota' => $this->account->iduser
			];

			$this->m_pinjaman->insertPinjaman($dataset);

			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Berhasil mengajukan pinjaman',
				 	'status' => 'success'
				]
			);
			
			$dataset += ['notif' => $alert];
			$this->session->setFlashdata($dataset);
			return redirect()->back();
		}else{
			$this->session->setFlashdata($dataset);
			return redirect()->back();
		}
	}

	public function top_up_proc($idpinjaman = false)
	{
		$cek_cicilan = $this->request->getPost('angsuran_bulanan');
		$satuan_waktu = $this->request->getPost('satuan_waktu');
		$cek_pegawai = $this->m_user->where('iduser', $this->account->iduser)
									->get()
									->getResult()[0]
									->status_pegawai;
		if($cek_pegawai == 'tetap'){
			$batas_bulanan = 24;
			$batas_nominal = 50000000;
		}else{
			$batas_bulanan = 12;
			$batas_nominal = 15000000;
		}

		if ($satuan_waktu == 2) {
			$angsuran_bulanan = $cek_cicilan * 12;
		}else{
			$angsuran_bulanan = $cek_cicilan;
		}

		$nominal = filter_var($this->request->getPost('nominal'), FILTER_SANITIZE_NUMBER_INT);
		$saldo_pinjaman = $this->request->getPost('sisa_cicilan');

		$dataset = [
			'nominal' => $nominal,
			'tipe_permohonan' => $this->request->getPost('tipe_permohonan'),
			'potongan_topup' => $saldo_pinjaman,
			'deskripsi' => $this->request->getPost('deskripsi'),
			'angsuran_bulanan' => $angsuran_bulanan
		];

		if ($angsuran_bulanan > $batas_bulanan) {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Tidak dapat mengajukan cicilan lebih dari '. $angsuran_bulanan .' bulan',
				 	'status' => 'warning'
				]
			);
			
			$dataset += ['notif_bulanan' => $alert];
			$confirmation = false;
		}else{
			$confirmation = true;
		}

		if ($dataset['nominal'] > $batas_nominal) {
			
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Tidak dapat mengajukan cicilan lebih dari Rp '. number_format($dataset['nominal'], 0, ',','.'),
				 	'status' => 'warning'
				]
			);
			
			$dataset += ['notif' => $alert];
			$confirmation = false;
		}else{
			$confirmation = true;
		}
		
		if ($confirmation) {

			if ($dataset['tipe_permohonan'] == "") {
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'Pilih Tipe Permohonan Terlebih Dahulu',
					 	'status' => 'warning'
					]
				);
				
				$dataset += ['notif' => $alert];
				$this->session->setFlashdata($dataset);
				return redirect()->back();
			}

			$dataset += [
				'date_created' => date('Y-m-d H:i:s'),
				'status' => 1,
				'idanggota' => $this->account->iduser
			];

			$this->m_pinjaman->insertPinjaman($dataset);

			$dataset_pinjaman = [
				'status' => 5,
				'date_updated' => date('Y-m-d H:i:s')
			];

			$this->m_pinjaman->updatePinjaman($idpinjaman, $dataset_pinjaman);

			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Berhasil mengajukan top up',
				 	'status' => 'success'
				]
			);
			
			$dataset += ['notif' => $alert];
			$this->session->setFlashdata($dataset);
			return redirect()->to('anggota/pinjaman/list');
		}else{
			$this->session->setFlashdata($dataset);
			return redirect()->back();
		}
	}

	public function generate_form($idpinjaman)
	{
		$detail_pinjaman = $this->m_pinjaman->getPinjamanById($idpinjaman)[0];
		$bunga = $this->m_param->getParamById(9)[0]->nilai/100;
		$provisi = $this->m_param->getParamById(5)[0]->nilai/100;

		$data = [
			'detail_pinjaman' => $detail_pinjaman,
			'bunga' => $bunga,
			'provisi' => $provisi
		];
		
		return view('anggota/partials/form-pinjaman', $data);
	}

	public function upload_form($idpinjaman)
	{
		$file_1 = $this->request->getFile('form_bukti');
		$file_2 = $this->request->getFile('slip_gaji');
		$status_pegawai = $this->account->status_pegawai;
		$file_3 = ($status_pegawai == 'kontrak')?$this->request->getFile('form_kontrak'): false;
		
		$confirmation3 = true;
		$data = [];
		$data_session = [];

		if ($file_1->isValid()) {	
			$cek_bukti = $this->m_pinjaman->getPinjamanById($idpinjaman)[0]->form_bukti;
			
			if ($cek_bukti) {
				unlink(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/' . $cek_bukti);
			}

			$newName = $file_1->getRandomName();
			$file_1->move(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/', $newName);
			
			$form_bukti = $file_1->getName();
			
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Form Persetujuan berhasil diunggah',
				 	'status' => 'success'
				]
			);
			$confirmation = true;

		}else{
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Form Persetujuan gagal diunggah',
				 	'status' => 'danger'
				]
			);
			$confirmation = false;
		}

		if ($file_2->isValid()) {	
			$cek_gaji = $this->m_pinjaman->getPinjamanById($idpinjaman)[0]->slip_gaji;
			
			if ($cek_gaji) {
				unlink(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/' . $cek_gaji);
			}

			$newName = $file_2->getRandomName();
			$file_2->move(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/', $newName);
			
			$slip_gaji = $file_2->getName();
			
			$alert2 = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Slip gaji berhasil diunggah',
				 	'status' => 'success'
				]
			);
			$confirmation2 = true;

		}else{
			$alert2 = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Slip gaji gagal diunggah',
				 	'status' => 'danger'
				]
			);
			$confirmation2 = false;
		}
		
		if ($file_3){
			if ($file_3->isValid()) {	
				$cek_kontrak = $this->m_pinjaman->getPinjamanById($idpinjaman)[0]->form_kontrak;
				
				if ($cek_kontrak) {
					unlink(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/' . $cek_kontrak);
				}
	
				$newName = $file_3->getRandomName();
				$file_3->move(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/', $newName);
				
				$form_kontrak = $file_3->getName();
				$data += ['form_kontrak' => $form_kontrak];
				$alert3 = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'Bukti kontrak berhasil diunggah',
						 'status' => 'success'
					]
				);
				$data_session += ['notif_kontrak' => $alert3];
				$confirmation3 = true;
	
			}else{
				$alert3 = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'Bukti kontrak gagal diunggah',
						 'status' => 'danger'
					]
				);
				$data_session += ['notif_kontrak' => $alert3];
				$confirmation3 = false;
			}	
		}

		if (!$confirmation || !$confirmation2 || !$confirmation3) {
			$data_session += [
				'notif' => $alert,
				'notif_gaji' => $alert2
			];
		}else{
			$date_updated = date('Y-m-d H:i:s');

			$data += [
				'form_bukti' => $form_bukti,
				'slip_gaji' => $slip_gaji,
				'status' => 2,
				'date_updated' => $date_updated
			];

			$this->m_pinjaman->updatePinjaman($idpinjaman, $data);

			$notification_data = [
				'anggota_id' => $this->account->iduser,
				'pinjaman_id' => $idpinjaman,
				'message' => 'Pengajuan pinjaman dari anggota '. $this->account->nama_lengkap,
				'timestamp' => date('Y-m-d H:i:s'),
				'group_type' => 1
			];

			$this->m_notification->insert($notification_data);
			
			$data_session += [
				'notif' => $alert,
				'notif_gaji' => $alert2
			];
		}
		
		$this->session->setFlashdata($data_session);
		return redirect()->back();
	}

	public function lunasi_proc($idpinjaman)
	{
		$file_1 = $this->request->getFile('bukti_bayar');

		if ($file_1->isValid()) {	
			$cek_bukti = $this->m_pinjaman->getPinjamanById($idpinjaman)[0]->bukti_tf;
			
			if ($cek_bukti) {
				unlink(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/' . $cek_bukti);
			}

			$newName = $file_1->getRandomName();
			$file_1->move(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/', $newName);
			
			$bukti_tf = $file_1->getName();

			$dataset = [
				'bukti_tf' => $bukti_tf,
				'status' => 6
			];

			$this->m_pinjaman->updatePinjaman($idpinjaman, $dataset);

			$notification_data = [
				'anggota_id' => $this->account->iduser,
				'pinjaman_id' => $idpinjaman,
				'message' => 'Pengajuan pelunasan pinjaman dari anggota '. $this->account->nama_lengkap,
				'timestamp' => date('Y-m-d H:i:s'),
				'group_type' => 1
			];

			$this->m_notification->insert($notification_data);

			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Berhasil mengajukan pelunasan pinjaman',
				 	'status' => 'success'
				]
			);
			
			$dataset += ['notif' => $alert];
			$this->session->setFlashdata($dataset);
			return redirect()->back();
		}else{
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Form Persetujuan gagal diunggah: format file tidak sesuai',
				 	'status' => 'danger'
				]
			);
			$confirmation = false;
		}
	}

	public function up_form()
	{
		if ($_POST['rowid']) {
			$id = $_POST['rowid'];
			$user = $this->m_pinjaman->getPinjamanById($id)[0];
			$data = [
				'a' => $user,
				'duser' => $this->account
			];
			echo view('anggota/pinjaman/part-pinj-mod-upload', $data);
		}
	}

	public function lunasi_pinjaman()
	{
		if ($_POST['rowid']) {
			$id = $_POST['rowid'];
			$user = $this->m_pinjaman->getPinjamanById($id)[0];
			$data = [
				'a' => $user,
				'duser' => $this->account
			];
			echo view('anggota/pinjaman/part-pinj-mod-lunasin', $data);
		}
	}

	public function top_up()
	{
		if ($_POST['rowid']) {
			$id = $_POST['rowid'];
			$user = $this->m_pinjaman->getPinjamanById($id)[0];
			$penalty = $this->m_param->getParamById(6)[0]->nilai;
			$sum_cicilan = $this->m_cicilan->select("SUM(nominal) as saldo")
				->where('idpinjaman', $id)
				->get()
				->getResult()[0]
				->saldo;
			$sisa_pinjaman = $user->nominal - $sum_cicilan;
			$data = [
				'a' => $user,
				'sisa' => $sisa_pinjaman,
				'duser' => $this->account,
				'penalty' => $penalty
			];
			echo view('anggota/pinjaman/part-cicil-mod-topup', $data);
		}
	}

	public function data_pinjaman()
	{
		$request = service('request');
        $model = new M_pinjaman();

        // Parameters from the DataTable
        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'];

        // Fetch data from the model using $start and $length
		$model->where('idanggota', $this->account->iduser);
        $data = $model->asArray()->findAll($length, $start);

        // Total records (you can also use $model->countAll() for exact total)
		$model->where('idanggota', $this->account->iduser);
        $recordsTotal = $model->countAllResults();

        // Records after filtering (if any)
		$model->where('idanggota', $this->account->iduser);
        $recordsFiltered = $model->countAllResults();

        // Prepare the response in the DataTable format
        $response = [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ];

        return $this->response->setJSON($response);
	}
}