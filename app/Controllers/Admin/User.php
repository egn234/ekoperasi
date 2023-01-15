<?php 
namespace App\Controllers\Admin;

require_once ROOTPATH.'vendor/autoload.php';

use CodeIgniter\Controller;
use App\Models\M_user;
use App\Models\M_group;
use App\Models\M_deposit;
use App\Models\M_param;
use App\Models\M_param_manasuka;

class User extends Controller
{

	function __construct()
	{
		$this->m_user = new M_user();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
		$this->m_group = new M_group();
		$this->m_deposit = new M_deposit();
		$this->m_param = new M_param();
		$this->m_param_manasuka = new M_param_manasuka();
	}

	public function list()
	{	
		$user_list = $this->m_user->getAllUser();

		$data = [
			'title_meta' => view('admin/partials/title-meta', ['title' => 'User']),
			'page_title' => view('admin/partials/page-title', ['title' => 'User List', 'li_1' => 'EKoperasi', 'li_2' => 'User List']),
			'duser' => $this->account,
			'usr_list' => $user_list
		];
		
		echo view('admin/user/user-list', $data);
	}

	public function list_closebook()
	{	
		$user_list = $this->m_user->getAllClosebookUser();

		$data = [
			'title_meta' => view('admin/partials/title-meta', ['title' => 'Closebook User']),
			'page_title' => view('admin/partials/page-title', ['title' => 'CLosebook User List', 'li_1' => 'EKoperasi', 'li_2' => 'Closebook User List']),
			'duser' => $this->account,
			'usr_list' => $user_list
		];
		
		echo view('admin/user/user-closebook-list', $data);
	}

	public function add_user()
	{
		$group_list = $this->m_group->getAllGroup();

		$data = [
			'title_meta' => view('admin/partials/title-meta', ['title' => 'User']),
			'page_title' => view('admin/partials/page-title', ['title' => 'Tambah User', 'li_1' => 'EKoperasi', 'li_2' => 'New User']),
			'duser' => $this->account,
			'grp_list' => $group_list
		];
		
		echo view('admin/user/add-user', $data);
	}

	public function get_table_upload()
	{
		$table_file = $this->request->getFile('file_import');
		
		if ($table_file->isValid()) 
		{
			$ext = $table_file->guessExtension();
			$filepath = WRITEPATH.'uploads/'.$table_file->store();
			
			if ($ext == 'csv') {
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
			}
			elseif($ext == 'xlsx' || $ext == 'xls'){
				$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
			}

			$reader->setReadDataOnly(true);
			$reader->setReadEmptyCells(false);
			$spreadsheet = $reader->load($filepath);
			$err_count = 0;
			$baris_proc = 0;

			foreach ($spreadsheet->getWorksheetIterator() as $cell)
			{
				$baris = $cell->getHighestRow();
				$kolom = $cell->getHighestColumn();

				for ($i=2; $i <= $baris; $i++)
				{ 
					$dataset = [
						'username' => $cell->getCellByColumnAndRow(1, $i)->getValue(),
						'pass' => md5($cell->getCellByColumnAndRow(2, $i)->getValue()),
						'nik' => $cell->getCellByColumnAndRow(3, $i)->getValue(),
						'nama_lengkap' => $cell->getCellByColumnAndRow(4, $i)->getValue(),
						'tempat_lahir' => $cell->getCellByColumnAndRow(5, $i)->getValue(),
						'tanggal_lahir' => $cell->getCellByColumnAndRow(6, $i)->getValue(),
						'alamat' => $cell->getCellByColumnAndRow(7, $i)->getValue(),
						'instansi' => $cell->getCellByColumnAndRow(8, $i)->getValue(),
						'unit_kerja' => $cell->getCellByColumnAndRow(9, $i)->getValue(),
						'nomor_telepon' => $cell->getCellByColumnAndRow(10, $i)->getValue(),
						'email' => $cell->getCellByColumnAndRow(11, $i)->getValue(),
					];

					$saldo = [
						'saldo_pokok' => $cell->getCellByColumnAndRow(12, $i)->getValue(),
						'saldo_wajib' => $cell->getCellByColumnAndRow(13, $i)->getValue(),
						'saldo_manasuka' => $cell->getCellByColumnAndRow(14, $i)->getValue()
					];

					$cek_username = $this->m_user->countUsername($dataset['username'])[0]->hitung;
					if ($cek_username == 0)
					{
						$cek_nik = $this->m_user->countNIK($dataset['nik'])[0]->hitung;
						if ($cek_nik == 0)
						{
							$dataset += [
								'profil_pic' => 'image.jpg',
								'created' => date('Y-m-d H:i:s'),
								'closebook_param_count' => 0,
								'flag' => 1,
								'idgroup' => 4
							];

							$this->m_user->insertUser($dataset);

							$iduser_new = $this->m_user->getUser($dataset['username'])[0]->iduser;
							
							if ($saldo['saldo_pokok'] != null || $saldo['saldo_pokok'] != 0) {
								
								$saldo_pokok = [
									'jenis_pengajuan' => 'penyimpanan',
									'jenis_deposit' => 'pokok',
									'cash_in' => $saldo['saldo_pokok'],
									'cash_out' => 0,
									'deskripsi' => 'saldo pokok',
									'status' => 'diterima',
									'date_created' => date('Y-m-d H:i:s'),
									'idanggota' => $iduser_new
								];

								$this->m_deposit->insertDeposit($saldo_pokok);
							}else{

								$init_aktivasi = $this->m_param->getParamById(1)[0]->nilai;
								$saldo_pokok = [
									'jenis_pengajuan' => 'penyimpanan',
									'jenis_deposit' => 'pokok',
									'cash_in' => $init_aktivasi,
									'cash_out' => 0,
									'deskripsi' => 'biaya awal registrasi',
									'status' => 'diproses',
									'date_created' => date('Y-m-d H:i:s'),
									'idanggota' => $iduser_new
								];

								$this->m_deposit->insertDeposit($saldo_pokok);
							}

							if ($saldo['saldo_wajib'] != null || $saldo['saldo_wajib'] != 0) {

								$saldo_wajib = [
									'jenis_pengajuan' => 'penyimpanan',
									'jenis_deposit' => 'wajib',
									'cash_in' => $saldo['saldo_wajib'],
									'cash_out' => 0,
									'deskripsi' => 'saldo wajib',
									'status' => 'diterima',
									'date_created' => date('Y-m-d H:i:s'),
									'idanggota' => $iduser_new
								];

								$this->m_deposit->insertDeposit($saldo_wajib);
							}else{
								
								$init_aktivasi = $this->m_param->getParamById(2)[0]->nilai;
								$saldo_wajib = [
									'jenis_pengajuan' => 'penyimpanan',
									'jenis_deposit' => 'pokok',
									'cash_in' => $init_aktivasi,
									'cash_out' => 0,
									'deskripsi' => 'biaya awal registrasi',
									'status' => 'diproses',
									'date_created' => date('Y-m-d H:i:s'),
									'idanggota' => $iduser_new
								];

								$this->m_deposit->insertDeposit($saldo_wajib);
							}

							if ($saldo['saldo_manasuka'] != null || $saldo['saldo_manasuka'] != 0) {

								$saldo_manasuka = [
									'jenis_pengajuan' => 'penyimpanan',
									'jenis_deposit' => 'manasuka',
									'cash_in' => $saldo['saldo_manasuka'],
									'cash_out' => 0,
									'deskripsi' => 'saldo manasuka',
									'status' => 'diterima',
									'date_created' => date('Y-m-d H:i:s'),
									'idanggota' => $iduser_new
								];

								$this->m_deposit->insertDeposit($saldo_manasuka);
							}

							$param_r = [
								'idanggota' => $iduser_new,
								'nilai' => $this->m_param->getParamById(3)[0]->nilai,
								'created' => date('Y-m-d H:i:s')
							];

							$this->m_param_manasuka->insertParamManasuka($param_r);
						}
						else
						{
							$err_count++;
						}
					}
					else
					{
						$err_count++;
					}
					$baris_proc++;
				}
			}

			$total_count = $baris_proc - $err_count;

			if ($err_count > 0 && $total_count != 0) {
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'Berhasil mengimpor beberapa data user ('.$total_count.' berhasil, '.$err_count.' gagal)',
					 	'status' => 'warning'
					]
				);
				
				$data_session = [
					'notif' => $alert
				];
			}
			elseif ($err_count == $baris_proc) {
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'Gagal mengimpor data user ('.($total_count).' berhasil, '.$err_count.' gagal)',
					 	'status' => 'danger'
					]
				);
				
				$data_session = [
					'notif' => $alert
				];	
			}
			elseif ($err_count == 0) {
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'Berhasil mengimpor data user ('.$total_count.' berhasil, '.$err_count.' gagal)',
					 	'status' => 'success'
					]
				);
				
				$data_session = [
					'notif' => $alert
				];
			}
				
			unlink($filepath);
			session()->setFlashdata($data_session);
			return redirect()->to('admin/user/list');
		}
		else
		{
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Upload gagal',
				 	'status' => 'danger'
				]
			);
			
			$dataset = ['notif' => $alert];
			session()->setFlashdata($dataset);
			return redirect()->to('admin/user/list');
		}
	}

	public function export_table()
	{
		$user_list = $this->m_user->getAllUser();
		$data = [
			'page_title' => 'Ekspor',
			'usr_list' => $user_list
		];

		echo view('admin/user/export-user', $data);
	}

	public function add_user_proc()
	{
		$dataset = [
			'nama_lengkap' => $this->request->getPost('nama_lengkap'),
			'nik' => $this->request->getPost('nik'),
			'tempat_lahir' => $this->request->getPost('tempat_lahir'),
			'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
			'instansi' => $this->request->getPost('instansi'),
			'alamat' => $this->request->getPost('alamat'),
			'nomor_telepon' => $this->request->getPost('nomor_telepon'),
			'email' => $this->request->getPost('email'),
			'unit_kerja' => $this->request->getPost('unit_kerja'),
			'username' => $this->request->getPost('username'),
			'pass' => md5($this->request->getPost('pass')),
			'idgroup' => $this->request->getPost('idgroup')
		];

		$pass2 = md5($this->request->getPost('pass2'));

		if ($dataset['instansi'] == "") {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Pilih Institusi terlebih dahulu',
				 	'status' => 'warning'
				]
			);
			
			$dataset += ['notif' => $alert];
			session()->setFlashdata($dataset);
			return redirect()->to('admin/user/add');
		}

		$cek_username = $this->m_user->countUser($dataset['username'])[0]->hitung;

		if ($cek_username != 0) {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Username Telah Terpakai',
				 	'status' => 'warning'
				]
			);
			
			$dataset += ['notif' => $alert];
			session()->setFlashdata($dataset);
			return redirect()->to('admin/user/add');
		}

		$cek_nik = $this->m_user->countNIK($dataset['nik'])[0]->hitung;

		if ($cek_nik != 0) {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'NIK Telah Terdaftar',
				 	'status' => 'warning'
				]
			);
			
			$dataset += ['notif' => $alert];
			session()->setFlashdata($dataset);
			return redirect()->to('admin/user/add');
		}

		if ($dataset['pass'] != $pass2) {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Password tidak cocok',
				 	'status' => 'warning'
				]
			);
			
			$dataset += ['notif' => $alert];
			session()->setFlashdata($dataset);
			return redirect()->to('admin/user/add');			
		}

		if ($dataset['idgroup'] == "") {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Pilih Grup terlebih dahulu',
				 	'status' => 'warning'
				]
			);
			
			$dataset += ['notif' => $alert];
			session()->setFlashdata($dataset);
			return redirect()->to('admin/user/add');
		}

		$img = $this->request->getFile('profil_pic');
		
		if ($img->isValid()) {
			$newName = $img->getRandomName();
			$img->move(ROOTPATH . 'public/uploads/user/' . $dataset['username'] . '/profil_pic/', $newName);
			$profile_pic = $img->getName();
			$dataset += ['profil_pic' => $profile_pic];
		}

		$dataset += [
			'created' => date('Y-m-d H:i:s'),
			'closebook_param_count' => 0,
			'flag' => 1
		];
		
		$this->m_user->insertUser($dataset);

		if ($dataset['idgroup'] == 4)
		{
			$iduser_new = $this->m_user->getUser($dataset['username'])[0]->iduser;
			
			$init_aktivasi = [
				$this->m_param->getParamById(1)[0]->nilai,
				$this->m_param->getParamById(2)[0]->nilai
			];

			$j_deposit_r = ['pokok', 'wajib'];

			for ($i = 0; $i < count($init_aktivasi); $i++) {
				$dataset = [
					'jenis_pengajuan' => 'penyimpanan',
					'jenis_deposit' => $j_deposit_r[$i],
					'cash_in' => $init_aktivasi[$i],
					'cash_out' => 0,
					'deskripsi' => 'biaya awal registrasi',
					'status' => 'diproses',
					'date_created' => date('Y-m-d H:i:s'),
					'idanggota' => $iduser_new
				];

				$this->m_deposit->insertDeposit($dataset);
			}

			$param_r = [
				'idanggota' => $iduser_new,
				'nilai' => $this->m_param->getParamById(3)[0]->nilai,
				'created' => date('Y-m-d H:i:s')
			];

			$this->m_param_manasuka->insertParamManasuka($param_r);
		}
				
		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'User berhasil dibuat',
			 	'status' => 'success'
			]
		);
		
		$data_session = [
			'notif' => $alert
		];

		session()->setFlashdata($data_session);
		return redirect()->to('admin/user/list');
	}

	public function detail_user($iduser = false)
	{
		$detail_user = $this->m_user->getUserById($iduser)[0];
		$group_list = $this->m_group->getAllGroup();

		$data = [
			'title_meta' => view('admin/partials/title-meta', ['title' => 'Detail User']),
			'page_title' => view('admin/partials/page-title', ['title' => 'Detail User', 'li_1' => 'EKoperasi', 'li_2' => 'User Detail']),
			'duser' => $this->account,
			'det_user' => $detail_user,
			'grp_list' => $group_list
		];
		
		echo view('admin/user/user-detail', $data);
	}

	public function update_proc($iduser = false)
	{
		$old_user = $this->m_user->getUserById($iduser)[0];

		$dataset = [
			'nama_lengkap' => $this->request->getPost('nama_lengkap'),
			'tempat_lahir' => $this->request->getPost('tempat_lahir'),
			'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
			'instansi' => $this->request->getPost('instansi'),
			'alamat' => $this->request->getPost('alamat'),
			'nomor_telepon' => $this->request->getPost('nomor_telepon'),
			'email' => $this->request->getPost('email'),
			'unit_kerja' => $this->request->getPost('unit_kerja')
		];

		$new_pass = $this->request->getPost('pass');
		$cek_pass = $this->request->getPost('pass2');

		if ($new_pass != "" || !is_null($new_pass) )
		{
			if (md5($new_pass) == md5($cek_pass)) 
			{
				$dataset += ['pass' => md5($new_pass)];
			}
			else
			{
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'konfirmasi password tidak sesuai',
					 	'status' => 'warning'
					]
				);
				
				$data_session = [
					'notif' => $alert
				];

				session()->setFlashdata($data_session);
				return redirect()->to('admin/user/'.$iduser);
			}
		}

		$img = $this->request->getFile('profil_pic');

		if ($img->isValid())
		{
			unlink(ROOTPATH . "public/uploads/user/" . $old_user->username . "/profil_pic/" . $old_user->profil_pic );
			$newName = $img->getRandomName();
			$img->move(ROOTPATH . 'public/uploads/user/' . $old_user->username . '/profil_pic/', $newName);
			$profile_pic = $img->getName();
			$dataset += ['profil_pic' => $profile_pic];
		}

		$dataset += ['updated' => date('Y-m-d H:i:s')];
		
		$this->m_user->updateUser($iduser, $dataset);
		
		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'data pengguna berhasil diubah',
			 	'status' => 'success'
			]
		);
		
		$data_session = [
			'notif' => $alert
		];

		session()->setFlashdata($data_session);
		return redirect()->to('admin/user/'.$iduser);
	}

	public function flag_switch($iduser = false)
	{
		$user = $this->m_user->getUserById($iduser)[0];

		if ($user->user_flag == 0) {
			
			if ($user->closebook_param_count == 1) {

				$init_aktivasi = [
					$this->m_param->getParamById(1)[0]->nilai,
					$this->m_param->getParamById(2)[0]->nilai
				];

				$j_deposit_r = ['pokok', 'wajib'];

				for ($i = 0; $i < count($init_aktivasi); $i++) {
					$dataset = [
						'jenis_pengajuan' => 'penyimpanan',
						'jenis_deposit' => $j_deposit_r[$i],
						'cash_in' => $init_aktivasi[$i],
						'cash_out' => 0,
						'deskripsi' => 'biaya registrasi',
						'status' => 'diproses',
						'date_created' => date('Y-m-d H:i:s'),
						'idanggota' => $iduser
					];

					$this->m_deposit->insertDeposit($dataset);
				}

				$param_r = [
					'nilai' => $this->m_param->getParamById(3)[0]->nilai,
					'updated' => date('Y-m-d H:i:s')
				];

				$this->m_param_manasuka->setParamManasukaByUser($iduser, $param_r);
				
				$this->m_user->aktifkanUser($iduser);

				$alert = view('partials/notification-alert', 
					[
						'notif_text' => 'User Diaktifkan',
					 	'status' => 'success'
					]
				);
				
				session()->setFlashdata('notif', $alert);

			}elseif ($user->closebook_param_count == 2) {

				$alert = view('partials/notification-alert', 
					[
						'notif_text' => 'User Sudah melebihi batas aktivasi',
						'status' => 'danger'
					]
				);
				
				session()->setFlashdata('notif', $alert);
			}

		}elseif ($user->user_flag == 1) {

			$saldo_r = [
				$this->m_deposit->getSaldoWajibByUserId($iduser)[0]->saldo,
				$this->m_deposit->getSaldoPokokByUserId($iduser)[0]->saldo,
				$this->m_deposit->getSaldoManasukaByUserId($iduser)[0]->saldo
			];

			$j_deposit_r = ['wajib', 'pokok', 'manasuka'];

			for ($i = 0; $i < count($saldo_r); $i++) {
				$dataset = [
					'jenis_pengajuan' => 'penarikan',
					'jenis_deposit' => $j_deposit_r[$i],
					'cash_in' => 0,
					'cash_out' => ($saldo_r[$i] == null)?0:$saldo_r[$i],
					'deskripsi' => 'tutup buku',
					'status' => 'diterima',
					'date_created' => date('Y-m-d H:i:s'),
					'idanggota' => $iduser,
					'idadmin' => $this->account->iduser
				];

				$this->m_deposit->insertDeposit($dataset);
			}
			
			$param_r = [
				'nilai' => 0,
				'updated' => date('Y-m-d H:i:s')
			];

			$this->m_param_manasuka->setParamManasukaByUser($iduser, $param_r);
			$this->m_deposit->setStatusProses($iduser);
			$this->m_user->nonaktifkanUser($iduser);

			if ($user->closebook_param_count == 0) {
				$this->m_user->closebookCount($iduser, 1);
				
			}elseif ($user->closebook_param_count == 1) {
				$this->m_user->closebookCount($iduser, 2);
			}


			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'User Dinonaktifkan',
					'status' => 'success'
				]
			);

			session()->setFlashdata('notif', $alert);
		}

		return redirect()->back();
	}

	public function konfirSwitch()
	{
		if ($_POST['rowid']) {
			$id = $_POST['rowid'];
			$user = $this->m_user->getUserById($id)[0];
			$data = ['a' => $user];
			echo view('admin/user/part-user-mod-switch', $data);
		}
	}
}