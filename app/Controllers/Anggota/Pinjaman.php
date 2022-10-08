<?php 
namespace App\Controllers\Anggota;

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
		$list_pinjaman = $this->m_pinjaman->getPinjamanByIdAnggota($this->account->iduser);

		$data = [
			'title_meta' => view('anggota/partials/title-meta', ['title' => 'Pinjaman']),
			'page_title' => view('anggota/partials/page-title', ['title' => 'Pinjaman', 'li_1' => 'EKoperasi', 'li_2' => 'Pinjaman']),
			'duser' => $this->account,
			'list_pinjaman' => $list_pinjaman
		];
		
		return view('anggota/pinjaman/list-pinjaman', $data);
	}

	public function add_proc()
	{
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
}