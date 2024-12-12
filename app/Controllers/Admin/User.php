<?php 
namespace App\Controllers\Admin;

require_once ROOTPATH.'vendor/autoload.php';

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_group;
use App\Models\M_deposit;
use App\Models\M_param;
use App\Models\M_param_manasuka;
use App\Models\M_pinjaman;
use App\Models\M_cicilan;


use App\Controllers\Admin\Notifications;

class User extends Controller
{
	protected $m_user;
	protected $m_group;
	protected $m_deposit;
	protected $m_param;
	protected $m_param_manasuka;
	protected $m_pinjaman;
	protected $m_cicilan;
	protected $notification;
	protected $account;

	function __construct()
	{
		$this->m_user = new M_user();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
		$this->m_group = new M_group();
		$this->m_deposit = new M_deposit();
		$this->m_param = new M_param();
		$this->m_param_manasuka = new M_param_manasuka();
		$this->m_pinjaman = new M_pinjaman();
		$this->m_cicilan = new M_cicilan();
		$this->notification = new Notifications();
	}

	public function list()
	{
		$data = [
			'title_meta' => view('admin/partials/title-meta', ['title' => 'User']),
			'page_title' => view('admin/partials/page-title', ['title' => 'User List', 'li_1' => 'EKoperasi', 'li_2' => 'User List']),
			'notification_list' => $this->notification->index()['notification_list'],
			'notification_badges' => $this->notification->index()['notification_badges'],
			'duser' => $this->account
		];
		
		echo view('admin/user/user-list', $data);
	}

	public function data_user()
	{
        $request = service('request');
        $model = new M_user(); // Replace with your actual model name

        // Parameters from the DataTable
        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'];

        // Start building the query for filtering
	    $model->like('username', $searchValue)
	          ->orLike('nama_lengkap', $searchValue)
	          ->orLike('nomor_telepon', $searchValue);

        // Fetch data from the model using $start and $length
        $data = $model->asArray()->findAll($length, $start);

        // Total records (you can also use $model->countAll() for exact total)
        $recordsTotal = $model->countAll();

        // Records after filtering (if any)
        $recordsFiltered = $recordsTotal;

        // Prepare the response in the DataTable format
        $response = [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        return $this->response->setJSON($response);
	}

	public function list_closebook()
	{
		$user_list = $this->m_user->getAllClosebookUser();

		$data = [
			'title_meta' => view('admin/partials/title-meta', ['title' => 'Closebook User']),
			'page_title' => view('admin/partials/page-title', ['title' => 'CLosebook User List', 'li_1' => 'EKoperasi', 'li_2' => 'Closebook User List']),
			'notification_list' => $this->notification->index()['notification_list'],
			'notification_badges' => $this->notification->index()['notification_badges'],
			'duser' => $this->account,
			'usr_list' => $user_list
		];
		
		echo view('admin/user/user-closebook-list', $data);
	}

	public function add_user()
	{
		$group_list = $this->m_group->getAllGroup();

		$cek_username = $this->m_user->getUsernameGiat()[0]->username;
		$filter_int = filter_var($cek_username, FILTER_SANITIZE_NUMBER_INT);
		$clean_int = intval($filter_int);

		if ($clean_int >= 1000) {
			$username = 'GIAT'.($clean_int+1);
		}elseif ($clean_int >= 100) {
			$username = 'GIAT0'.($clean_int+1);
		}elseif ($clean_int >= 10) {
			$username = 'GIAT00'.($clean_int+1);
		}elseif ($clean_int >= 1) {
			$username = 'GIAT000'.($clean_int+1);
		}

		$data = [
			'title_meta' => view('admin/partials/title-meta', ['title' => 'User']),
			'page_title' => view('admin/partials/page-title', ['title' => 'Tambah User', 'li_1' => 'EKoperasi', 'li_2' => 'New User']),
			'notification_list' => $this->notification->index()['notification_list'],
			'notification_badges' => $this->notification->index()['notification_badges'],
			'duser' => $this->account,
			'grp_list' => $group_list,
			'username' => $username
		];
		
		echo view('admin/user/add-user', $data);
	}

	public function get_table_upload()
	{
		$table_file = request()->getFile('file_import');
		
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
					$cek_username = ($this->m_user->getUsernameGiat())?$this->m_user->getUsernameGiat()[0]->username:'GIAT0000';

					$filter_int = (int) filter_var($cek_username, FILTER_SANITIZE_NUMBER_INT);
					$clean_int = intval($filter_int);

					if ($clean_int >= 999) {
						$username = 'GIAT'.($clean_int+1);
					}elseif ($clean_int >= 99) {
						$username = 'GIAT0'.($clean_int+1);
					}elseif ($clean_int >= 9) {
						$username = 'GIAT00'.($clean_int+1);
					}elseif ($clean_int >= 0) {
						$username = 'GIAT000'.($clean_int+1);
					}

					$dataset = [
						'username' => $username,
						'pass' => md5($cell->getCellByColumnAndRow(2, $i)->getValue()),
						'nik' => $cell->getCellByColumnAndRow(3, $i)->getValue(),
						'nama_lengkap' => strtoupper($cell->getCellByColumnAndRow(4, $i)->getValue()),
						'tempat_lahir' => $cell->getCellByColumnAndRow(5, $i)->getValue(),
						'tanggal_lahir' => date('Y-m-d', strtotime($cell->getCellByColumnAndRow(6, $i)->getValue())),
						'alamat' => $cell->getCellByColumnAndRow(7, $i)->getValue(),
						'instansi' => $cell->getCellByColumnAndRow(8, $i)->getValue(),
						'unit_kerja' => $cell->getCellByColumnAndRow(9, $i)->getValue(),
						'status_pegawai' => $cell->getCellByColumnAndRow(10, $i)->getValue(),
						'nomor_telepon' => $cell->getCellByColumnAndRow(11, $i)->getValue(),
						'email' => $cell->getCellByColumnAndRow(12, $i)->getValue(),
						'nama_bank' => strtoupper($cell->getCellByColumnAndRow(13, $i)->getValue()),
						'no_rek' => $cell->getCellByColumnAndRow(14, $i)->getValue(),
					];

					$saldo = [
						'saldo_pokok' => $cell->getCellByColumnAndRow(15, $i)->getValue(),
						'saldo_wajib' => $cell->getCellByColumnAndRow(16, $i)->getValue(),
						'saldo_manasuka' => $cell->getCellByColumnAndRow(17, $i)->getValue()
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
							
							helper('filesystem');
							$imgSource = FCPATH . 'assets/images/users/image.jpg';

							mkdir(FCPATH . 'uploads/user/'.$dataset['username'], 0777);
							mkdir(FCPATH . 'uploads/user/'.$dataset['username'].'/profil_pic', 0777);
							
							$imgDest = FCPATH . 'uploads/user/'.$dataset['username'].'/profil_pic/image.jpg';
							copy($imgSource, $imgDest);

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
									'jenis_deposit' => 'wajib',
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
								'created' => date('Y-m-d H:i:s')
							];

							$param_mnsk = $cell->getCellByColumnAndRow(21, $i)->getValue();

							if ($param_mnsk != "" || $param_mnsk != null) {
								$param_r += ['nilai' => $param_mnsk];
							}else{
								$param_r += ['nilai' => $this->m_param->getParamById(3)[0]->nilai];
							}

							$this->m_param_manasuka->insertParamManasuka($param_r);

							$pinjaman = [
								'nominal' => (int) $cell->getCellByColumnAndRow(18, $i)->getValue(),
								'angsuran_bulanan' => (int) $cell->getCellByColumnAndRow(19, $i)->getValue(),
							];

							$cicilan_ke = (int) $cell->getCellByColumnAndRow(20, $i)->getValue();

							if($pinjaman['nominal'] != 0 || $pinjaman['angsuran_bulanan'] != 0 || $cicilan_ke != 0){
								
								$tanggal_report = $this->m_param->where('idparameter', 8)->get()->getResult()[0]->nilai;
								$today = new \DateTime();
								$monthInterval = new \DateInterval('P'.$cicilan_ke.'M'); // P25M represents a period of 25 months
								$monthAgo = $today->sub($monthInterval);
								$year = $monthAgo->format('Y'); // Get the year value
								$month = $monthAgo->format('m');
								$date_pinjaman = sprintf('%d-%02d-%02d 00:00:00', $year, $month, $tanggal_report);
								
								$pinjaman += [
									'tipe_permohonan' => 'pinjaman',
									'deskripsi' => 'impor otomatis sistem',
									'status' => 4,
									'date_created' => $date_pinjaman,
									'date_updated' => $date_pinjaman,
									'idbendahara' => $this->m_user->where('idgroup', 2)->get()->getResult()[0]->iduser,
									'idketua' => $this->m_user->where('idgroup', 3)->get()->getResult()[0]->iduser,
									'idanggota' => $iduser_new,
									'idadmin' => $this->account->iduser,
								];

								$this->m_pinjaman->insertPinjaman($pinjaman);
								$idpinjaman = $this->m_pinjaman->insertID();

								$nominal_cicilan = $pinjaman['nominal'] / $pinjaman['angsuran_bulanan'];

								$bunga = $this->m_param->where('idparameter', 9)->get()->getResult()[0]->nilai/100;
								$provisi = $this->m_param->where('idparameter', 5)->get()->getResult()[0]->nilai/100;

								for ($k = 0; $k < $cicilan_ke; ++$k) { 
								    $formattedDate = sprintf('%d-%02d-%02d 00:00:00', $year, $month, $tanggal_report);

								    $cek_cicilan = $this->m_cicilan->where('idpinjaman', $idpinjaman)
																   ->countAllResults();
									if ($cek_cicilan == 0) {

										$dataset_cicilan = [
											'nominal' => $pinjaman['nominal'],
											'bunga' => ($pinjaman['nominal']*($pinjaman['angsuran_bulanan']*$bunga))/$pinjaman['angsuran_bulanan'],
											'provisi' => ($pinjaman['nominal']*($pinjaman['angsuran_bulanan']*$provisi))/$pinjaman['angsuran_bulanan'],
											'date_created' => $formattedDate,
											'idpinjaman' => $idpinjaman
										];

										$this->m_cicilan->insertCicilan($dataset_cicilan);
										
									}elseif ($cek_cicilan == ($pinjaman['angsuran_bulanan'] - 1)) {

										$dataset_cicilan = [
											'nominal' => ($pinjaman['nominal']/$pinjaman['angsuran_bulanan']),
											'bunga' => ($pinjaman['nominal']*($pinjaman['angsuran_bulanan']*$bunga))/$pinjaman['angsuran_bulanan'],
											'date_created' => $formattedDate,
											'idpinjaman' => $idpinjaman
										];

										$this->m_cicilan->insertCicilan($dataset_cicilan);

										$status_pinjaman = ['status' => 5];
										$this->m_pinjaman->updatePinjaman($idpinjaman, $status_pinjaman);

									}elseif ($cek_cicilan != 0 && $cek_cicilan < $pinjaman['angsuran_bulanan']) {

										$dataset_cicilan = [
											'nominal' => $pinjaman['nominal'],
											'bunga' => ($pinjaman['nominal']*($pinjaman['angsuran_bulanan']*$bunga))/$pinjaman['angsuran_bulanan'],
											'date_created' => $formattedDate,
											'idpinjaman' => $idpinjaman
										];

										$this->m_cicilan->insertCicilan($dataset_cicilan);
									}

								    // Decrement the month (and year if necessary) for the next iteration
								    if ($month == 12) {
								        $year++;
								        $month = 1;
								    } else {
								        $month++;
								    }
								}
							}
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
			'nama_lengkap' => strtoupper((string) request()->getPost('nama_lengkap')),
			'nik' => request()->getPost('nik'),
			'tempat_lahir' => request()->getPost('tempat_lahir'),
			'tanggal_lahir' => request()->getPost('tanggal_lahir'),
			'instansi' => request()->getPost('instansi'),
			'alamat' => request()->getPost('alamat'),
			'nomor_telepon' => request()->getPost('nomor_telepon'),
			'status_pegawai' => request()->getPost('status_pegawai'),
			'email' => request()->getPost('email'),
			'unit_kerja' => request()->getPost('unit_kerja'),
			'username' => request()->getPost('username'),
			'pass' => md5(request()->getPost('pass')),
			'idgroup' => request()->getPost('idgroup'),
			'nama_bank' => strtoupper((string) request()->getPost('nama_bank')),
			'no_rek' => request()->getPost('no_rek'),
		];

		$pass2 = md5(request()->getPost('pass2'));

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
		
		//check duplicate nip
		$nip = request()->getPost('nip');

		if($nip != null || $nip != ''){
			$cek_nip = $this->m_user->select('count(iduser) as hitung')
				->where('nip', $nip)
				->get()
				->getResult()[0]
				->hitung;

			if($cek_nip != 0){
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'NIP Telah Terdaftar',
					 	'status' => 'warning'
					]
				);
				
				$dataset += [
					'nip' => $nip,
					'notif' => $alert
				];
				
				session()->setFlashdata($dataset);
				return redirect()->back();
			}else{
				$dataset += ['nip' => $nip];
			}
		}

		//check duplicate nik
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

		$img = request()->getFile('profil_pic');
		
		if ($img->isValid() && !$img->hasMoved()) {
			$allowedTypes = ['image/png', 'image/jpg', 'image/jpeg'];

			if (in_array($img->getMimeType(), $allowedTypes)) {
				$newName = $img->getRandomName();
				$img->move(ROOTPATH . 'public/uploads/user/' . $dataset['username'] . '/profil_pic/', $newName);
				$profile_pic = $img->getName();
				$dataset += ['profil_pic' => $profile_pic];	
			}
			else {
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'Tipe file tidak diizinkan', 
					 	'status' => 'danger'
					]
				);
				
				$data_session = [
					'notif' => $alert
				];

				session()->setFlashdata($data_session);
				return redirect()->back();
			}
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
			'notification_list' => $this->notification->index()['notification_list'],
			'notification_badges' => $this->notification->index()['notification_badges'],
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
			'nama_lengkap' => strtoupper((string) request()->getPost('nama_lengkap')),
			'nik' => request()->getPost('nik'),
			'tempat_lahir' => request()->getPost('tempat_lahir'),
			'tanggal_lahir' => request()->getPost('tanggal_lahir'),
			'instansi' => request()->getPost('instansi'),
			'alamat' => request()->getPost('alamat'),
			'nomor_telepon' => request()->getPost('nomor_telepon'),
			'status_pegawai' => request()->getPost('status_pegawai'),
			'email' => request()->getPost('email'),
			'unit_kerja' => request()->getPost('unit_kerja'),
			'pass' => md5(request()->getPost('pass')),
			'idgroup' => request()->getPost('idgroup'),
			'nama_bank' => strtoupper((string) request()->getPost('nama_bank')),
			'no_rek' => request()->getPost('no_rek'),
		];
		
		//check duplicate nip
		$nip_baru = request()->getPost('nip');

		if($nip_baru != null || $nip_baru != ''){
			$nip_awal = $this->account->nip;

			if($nip_awal != $nip_baru){
				$cek_nip = $this->m_user->select('count(iduser) as hitung')
					->where("nip = '".$nip_baru."' AND iduser != ".$iduser)
					->get()->getResult()[0]->hitung;

				if ($cek_nip == 0) {
					$dataset += ['nip' => $nip_baru];
				}else{
					$alert = view(
						'partials/notification-alert', 
						[
							'notif_text' => 'NIP telah terdaftar',
						 	'status' => 'danger'
						]
					);
					
					$data_session = [
						'notif' => $alert
					];

					session()->setFlashdata($data_session);
					return redirect()->back();
				}	
			}
		}

		//check duplicate nik
		$nik_baru = request()->getPost('nik');
		$nik_awal = $this->account->nik;

		if ($nik_baru != $nik_awal) {
			$cek_nik = $this->m_user->select('count(iduser) as hitung')
				->where("nik = '".$nik_baru."' AND iduser != ".$iduser)
				->get()->getResult()[0]->hitung;

			if ($cek_nik == 0) {
				$dataset += ['nik' => $nik_baru];
			}else{
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'NIK telah terdaftar',
					 	'status' => 'danger'
					]
				);
				
				$data_session = [
					'notif' => $alert
				];

				session()->setFlashdata($data_session);
				return redirect()->back();
			}
		}

		$new_pass = request()->getPost('pass');
		$cek_pass = request()->getPost('pass2');

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

		$img = request()->getFile('profil_pic');

		if ($img->isValid() && !$img->hasMoved()) {
			$allowedTypes = ['image/png', 'image/jpg', 'image/jpeg'];

			if (in_array($img->getMimeType(), $allowedTypes)) {
				$newName = $img->getRandomName();
				$img->move(ROOTPATH . 'public/uploads/user/' . $dataset['username'] . '/profil_pic/', $newName);
				$profile_pic = $img->getName();
				$dataset += ['profil_pic' => $profile_pic];	
			}
			else {
				$alert = view(
					'partials/notification-alert', 
					[
						'notif_text' => 'Tipe file tidak diizinkan', 
					 	'status' => 'danger'
					]
				);
				
				$data_session = [
					'notif' => $alert
				];

				session()->setFlashdata($data_session);
				return redirect()->back();
			}
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