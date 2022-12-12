<?php 
namespace App\Controllers\Anggota;

use CodeIgniter\Controller;
use App\Models\M_user;
use App\Models\M_pinjaman;
use App\Models\M_cicilan;
use App\Models\M_param;

class Pinjaman extends Controller
{

	function __construct()
	{
		$this->m_user = new M_user();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
		$this->m_pinjaman = new M_pinjaman();
		$this->m_cicilan = new M_cicilan();
		$this->m_param = new M_param();
	}

	public function index()
	{
		$list_pinjaman = $this->m_pinjaman->getPinjamanByIdAnggota($this->account->iduser);

		$data = [
			'title_meta' => view('anggota/partials/title-meta', ['title' => 'Pinjaman']),
			'page_title' => view('anggota/partials/page-title', ['title' => 'Pinjaman', 'li_1' => 'EKoperasi', 'li_2' => 'Pinjaman']),
			'duser' => $this->account,
			'list_pinjaman' => $list_pinjaman
		];
		
		return view('anggota/pinjaman/list-pinjaman', $data);
	}

	function detail($idpinjaman = false)
	{
		$detail_pinjaman = $this->m_pinjaman->getPinjamanById($idpinjaman)[0];
		$list_cicilan = $this->m_cicilan->getCicilanByIdPinjaman($idpinjaman);
		$tagihan_lunas = $this->m_cicilan->getSaldoTerbayarByIdPinjaman($idpinjaman)[0];

		$data = [
			'title_meta' => view('anggota/partials/title-meta', ['title' => 'Pinjaman']),
			'page_title' => view('anggota/partials/page-title', ['title' => 'Pinjaman', 'li_1' => 'EKoperasi', 'li_2' => 'Pinjaman']),
			'duser' => $this->account,
			'detail_pinjaman' => $detail_pinjaman,
			'list_cicilan' => $list_cicilan
		];
		
		return view('anggota/pinjaman/list-cicilan', $data);	
	}

	public function add_proc()
	{
		$cek_cicilan_aktif = $this->m_pinjaman->countPinjamanAktifByAnggota($this->account->iduser)[0]->hitung;

		if ($cek_cicilan_aktif != 0) {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Tidak dapat mengajukan pinjaman: masih ada pinjaman yang sedang berlangsung',
				 	'status' => 'danger'
				]
			);
			
			$dataset += ['notif' => $alert];
			session()->setFlashdata($dataset);
			return redirect()->back();
		}

		$cek_cicilan = $this->request->getPost('angsuran_bulanan');
		$satuan_waktu = $this->request->getPost('satuan_waktu');

		if ($satuan_waktu == 2) {
			$angsuran_bulanan = $cek_cicilan * 12;
		}else{
			$angsuran_bulanan = $cek_cicilan;
		}
		
		$dataset = [
			'nominal' => $this->request->getPost('nominal'),
			'tipe_permohonan' => $this->request->getPost('tipe_permohonan'),
			'deskripsi' => $this->request->getPost('deskripsi'),
			'angsuran_bulanan' => $angsuran_bulanan
		];

		if ($dataset['tipe_permohonan'] == "") {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Pilih Tipe Permohonan Terlebih Dahulu',
				 	'status' => 'warning'
				]
			);
			
			$dataset += ['notif' => $alert];
			session()->setFlashdata($dataset);
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
		session()->setFlashdata($dataset);
		return redirect()->back();
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
		$file = $this->request->getFile('form_bukti');

		if ($file->isValid()) {
			
			$cek_bukti = $this->m_pinjaman->getPinjamanById($idpinjaman)[0]->form_bukti;
			
			if ($cek_bukti) {
				unlink(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/', $cek_bukti);
			}

			$newName = $file->getRandomName();
			$file->move(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/', $newName);
			
			$form_bukti = $file->getName();
			$date_updated = date('Y-m-d H:i:s');

			$data = [
				'form_bukti' => $form_bukti,
				'status' => 2,
				'date_updated' => $date_updated
			];

			$this->m_pinjaman->updatePinjaman($idpinjaman, $data);
			
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Form Persetujuan berhasil diunggah',
				 	'status' => 'success'
				]
			);

		}else{
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Form Persetujuan gagal diunggah',
				 	'status' => 'danger'
				]
			);
			
		}
		
		$data_session = [
			'notif' => $alert
		];
		session()->setFlashdata($data_session);
		return redirect()->to('anggota/pinjaman/list');
	}

	public function up_form()
	{
		if ($_POST['rowid']) {
			$id = $_POST['rowid'];
			$user = $this->m_pinjaman->getPinjamanById($id)[0];
			$data = ['a' => $user];
			echo view('anggota/pinjaman/part-pinj-mod-upload', $data);
		}
	}
}