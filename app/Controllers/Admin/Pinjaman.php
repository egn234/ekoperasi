<?php 
namespace App\Controllers\Admin;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_pinjaman;
use App\Models\M_cicilan;
use App\Models\M_param;
use App\Models\M_notification;

use App\Controllers\Admin\Notifications;

class Pinjaman extends Controller
{

	function __construct()
	{
		$this->m_user = new M_user();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
		$this->m_pinjaman = new M_pinjaman();
		$this->m_cicilan = new M_cicilan();
		$this->m_param = new M_param();
		$this->m_notification = new M_notification();
		$this->notification = new Notifications();
	}

	public function index()
	{
		$list_pinjaman = $this->m_pinjaman->getPinjamanByStatus(2);
		$list_pinjaman2 = $this->m_pinjaman->getAllPinjaman();

		$data = [
			'title_meta' => view('admin/partials/title-meta', ['title' => 'Pinjaman']),
			'page_title' => view('admin/partials/page-title', ['title' => 'Pinjaman', 'li_1' => 'EKoperasi', 'li_2' => 'Pinjaman']),
			'notification_list' => $this->notification->index()['notification_list'],
			'notification_badges' => $this->notification->index()['notification_badges'],
			'duser' => $this->account,
			'list_pinjaman' => $list_pinjaman,
			'list_pinjaman2' => $list_pinjaman2
		];
		
		return view('admin/pinjaman/list-pinjaman', $data);
	}

	public function cancel_proc($idpinjaman = false)
	{
		$dataset = [
			'idadmin' => $this->account->iduser,
			'alasan_tolak' => $this->request->getPost('alasan_tolak'),
			'status' => 0,
			'date_updated' => date('Y-m-d H:i:s')
		];

		$this->m_pinjaman->updatePinjaman($idpinjaman, $dataset);
		
		$notification_data = [
			'admin_id' => $this->account->iduser,
			'anggota_id' => $this->m_pinjaman->where('idpinjaman', $idpinjaman)
											 ->get()
											 ->getResult()[0]
											 ->idanggota,
			'pinjaman_id' => $idpinjaman,
			'message' => 'Pengajuan pinjaman ditolak oleh admin '. $this->account->nama_lengkap,
			'timestamp' => date('Y-m-d H:i:s'),
			'group_type' => 4
		];

		$this->m_notification->insert($notification_data);

		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'Pengajuan pinjaman berhasil ditolak',
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
			'idadmin' => $this->account->iduser,
			'status' => 3,
			'date_updated' => date('Y-m-d H:i:s')
		];

		$this->m_pinjaman->updatePinjaman($idpinjaman, $dataset);
		
		$idanggota = $this->m_pinjaman->where('idpinjaman', $idpinjaman)
			->get()
			->getResult()[0]
			->idanggota;

		$notification_anggota = [
			'admin_id' => $this->account->iduser,
			'anggota_id' => $idanggota,
			'pinjaman_id' => $idpinjaman,
			'message' => 'Pengajuan pinjaman diterima oleh admin '. $this->account->nama_lengkap,
			'timestamp' => date('Y-m-d H:i:s'),
			'group_type' => 4
		];

		$this->m_notification->insert($notification_anggota);

		$notification_ketua = [
			'admin_id' => $this->account->iduser,
			'anggota_id' => $idanggota,
			'pinjaman_id' => $idpinjaman,
			'message' => 'Pengajuan pinjaman baru dari '. $this->m_user->where('iduser', $idanggota)->get()->getResult()[0]->nama_lengkap,
			'timestamp' => date('Y-m-d H:i:s'),
			'group_type' => 3
		];

		$this->m_notification->insert($notification_ketua);

		$notification_bendahara = [
			'admin_id' => $this->account->iduser,
			'anggota_id' => $idanggota,
			'pinjaman_id' => $idpinjaman,
			'message' => 'Pengajuan pinjaman baru dari '. $this->m_user->where('iduser', $idanggota)->get()->getResult()[0]->nama_lengkap,
			'timestamp' => date('Y-m-d H:i:s'),
			'group_type' => 2
		];

		$this->m_notification->insert($notification_bendahara);

		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'Pengajuan pinjaman berhasil disetujui',
			 	'status' => 'success'
			]
		);
		
		$data_session = ['notif' => $alert];
		session()->setFlashdata($data_session);
		return redirect()->back();
	}

	public function pelunasan_proc($idpinjaman = false)
	{
		$dataset = [
			'idadmin' => $this->account->iduser,
			'status' => 6
		];

		$this->m_pinjaman->updatePinjaman($idpinjaman, $dataset);
		
		$idanggota = $this->m_pinjaman->where('idpinjaman', $idpinjaman)
			->get()
			->getResult()[0]
			->idanggota;

		$notification_anggota = [
			'admin_id' => $this->account->iduser,
			'anggota_id' => $idanggota,
			'pinjaman_id' => $idpinjaman,
			'message' => 'Pengajuan pelunasan diproses oleh '. $this->account->nama_lengkap,
			'timestamp' => date('Y-m-d H:i:s'),
			'group_type' => 4
		];

		$this->m_notification->insert($notification_anggota);

		$notification_bendahara = [
			'admin_id' => $this->account->iduser,
			'anggota_id' => $idanggota,
			'pinjaman_id' => $idpinjaman,
			'message' => 'Pengajuan pelunasan baru dari '. $this->m_user->where('iduser', $idanggota)->get()->getResult()[0]->nama_lengkap,
			'timestamp' => date('Y-m-d H:i:s'),
			'group_type' => 2
		];

		$this->m_notification->insert($notification_bendahara);

		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'Pengajuan pelunasan berhasil dibuat',
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
			echo view('admin/pinjaman/part-pinjaman-mod-approval', $data);
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
			echo view('admin/pinjaman/part-pinjaman-mod-approval', $data);
		}
	}

	public function detail_pinjaman()
	{
		if ($_POST['rowid']) {
			$id = $_POST['rowid'];
			$pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
			$hitung_cicilan = $this->m_cicilan->select('COUNT(idcicilan) AS hitung, IFNULL(SUM(nominal),0) AS total_lunas')
				->where('idpinjaman', $id)
				->get()
				->getResult()[0];
			$data = [
				'a' => $pinjaman,
				'b' => $hitung_cicilan,
				'flag' => 1
			];
			echo view('admin/pinjaman/part-pinjaman-mod-detail', $data);
		}
	}

	public function pengajuan_lunas()
	{
		if ($_POST['rowid']) {
			$id = $_POST['rowid'];
			$pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
			$penalty_percent = $this->m_param->getParamById(6)[0]->nilai;
			$bebas_penalty = $this->m_param->getParamById(7)[0]->nilai;
			$hitung_cicilan = $this->m_cicilan->select('COUNT(idcicilan) AS hitung, IFNULL(SUM(nominal),0) AS total_lunas')
				->where('idpinjaman', $id)
				->get()
				->getResult()[0];
			$penalty = $hitung_cicilan->hitung <= $bebas_penalty ? ($pinjaman->nominal - $hitung_cicilan->total_lunas)*($penalty_percent/100) : 0;
			$data = [
				'idpinjaman' => $id,
				'penalty' => $penalty,
				'hitung_cicilan' => $hitung_cicilan,
				'bebas_penalty' => $bebas_penalty - $hitung_cicilan->hitung,
				'flag' => 1
			];
			echo view('admin/pinjaman/part-pinjaman-mod-lunasin', $data);
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
        $searchValue = $request->getPost('search')['value'] ?? '';

        // Fetch data from the model using $start and $length
		$model->select('a.status_pegawai AS status_pegawai');
		$model->select('a.username AS username_peminjam');
		$model->select('a.nama_lengkap AS nama_peminjam');
		$model->select('a.nik AS nik_peminjam');
		$model->select('tb_pinjaman.*');
		$model->select('(SELECT COUNT(idcicilan) FROM tb_cicilan WHERE idpinjaman = tb_pinjaman.idpinjaman) AS sisa_cicilan', false);
		$model->select('c.nama_lengkap AS nama_admin');
		$model->select('c.nik AS nik_admin');
		$model->select('d.nama_lengkap AS nama_bendahara');
		$model->select('d.nik AS nik_bendahara');
		$model->join('tb_user a', 'a.iduser = tb_pinjaman.idanggota');
		$model->join('tb_user c', 'c.iduser = tb_pinjaman.idadmin', 'left');
		$model->join('tb_user d', 'd.iduser = tb_pinjaman.idbendahara', 'left');
        $data = $model->asArray()->findAll($length, $start);

        // Total records (you can also use $model->countAll() for exact total)
        $recordsTotal = $model->countAllResults();

        // Records after filtering (if any)
        $recordsFiltered = $recordsTotal;

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