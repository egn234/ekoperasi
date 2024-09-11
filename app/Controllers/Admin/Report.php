<?php 
namespace App\Controllers\Admin;

require_once ROOTPATH.'vendor/autoload.php';

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_monthly_report;
use App\Models\M_param;
use App\Models\M_param_manasuka;
use App\Models\M_cicilan;
use App\Models\M_pinjaman;

use App\Controllers\Admin\Notifications;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Report extends Controller
{

	function __construct()
	{
		$this->m_user = new M_user();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
		$this->m_deposit = new M_deposit();
		$this->m_monthly_report = new M_monthly_report();
		$this->m_param = new M_param();
		$this->m_param_manasuka = new M_param_manasuka();
		$this->m_cicilan = new M_cicilan();
		$this->m_pinjaman = new M_pinjaman();
		$this->notification = new Notifications();
	}

	public function index()
	{
		$m_monthly_report = new M_monthly_report();
		$m_param = new M_param();
		$notification = new Notifications();

		$list_report = $m_monthly_report->orderBy('created', 'DESC')
										->get()
										->getResult();

		$list_tahun = $m_monthly_report->select('YEAR(created) AS tahun')
											 ->groupBy('YEAR(created)', 'DESC')
											 ->get()
											 ->getResult();
		$YEAR = date('Y');
		$MONTH = date('m');
		$getDay = $m_param->where('idparameter', 8)->get()->getResult()[0]->nilai;
		$cek_report = $m_monthly_report->where('date_monthly', $YEAR.'-'.$MONTH)->countAllResults();
		
		$data = [
			'title_meta' => view('admin/partials/title-meta', ['title' => 'Reporting']),
			'page_title' => view('admin/partials/page-title', ['title' => 'Dashboard', 'li_1' => 'EKoperasi', 'li_2' => 'Report']),
			'notification_list' => $notification->index()['notification_list'],
			'notification_badges' => $notification->index()['notification_badges'],
			'duser' => $this->account,
			'list_report' => $list_report,
			'list_tahun' => $list_tahun,
			'cek_report' => $cek_report,
			'getDay' => $getDay
		];
		
		return view('admin/report/reporting-page', $data);
	}

	public function gen_report()
	{
		$YEAR = date('Y');
		$MONTH = date('m');

		// $setDay = $this->m_param->where('idparameter', 8)->get()->getResult()[0]->nilai+1;
		// $endDate = date('Y-m-').$setDay;
		// $startDate = date('Y-m-d', strtotime('-1 month', strtotime($endDate)));

		$startDate = $this->m_monthly_report->orderBy('idreportm', 'DESC')->get(1)->getResult()[0]->created;
		$endDate = date('Y-m-d H:i:s');
		// echo "Start: ".$startDate.", End: ".$endDate;

		$list_anggota = $this->m_user->where('flag', '1')
									 ->where('idgroup', 4)
									 ->get()
									 ->getResult();

		$param_pokok = $this->m_param->where('idparameter', 1)->get()->getResult()[0];
		$param_wajib = $this->m_param->where('idparameter', 2)->get()->getResult()[0];

		//PENGECEKAN DATABASE UNTUK LOG REPORT BULANAN
		$log_report = $this->m_monthly_report->where('date_monthly', $YEAR.'-'.$MONTH)
											 ->countAllResults();

		if (!$log_report) {

			//UPDATE STATUS POKOK UNTUK SEMUA ANGGOTA BARU BULAN INI
			$this->m_deposit->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
							->where('status', 'diproses')
							->where('jenis_deposit', 'pokok')
							->where('deskripsi', 'biaya awal registrasi')
							->where('idanggota IN (SELECT iduser FROM tb_user WHERE flag = 1)')
							->set('status', 'diterima')
							->set('idadmin', $this->account->iduser)
							->update();

			//UPDATE STATUS WAJIB UNTUK SEMUA ANGGOTA BARU BULAN INI
			$this->m_deposit->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
							->where('status', 'diproses')
							->where('jenis_deposit', 'wajib')
							->where('deskripsi', 'biaya awal registrasi')
							->where('idanggota IN (SELECT iduser FROM tb_user WHERE flag = 1)')
							->set('status', 'diterima')
							->set('idadmin', $this->account->iduser)
							->update();
			
			//LOOPING LIST ANGGOTA
			foreach ($list_anggota as $a){

				//CEK POKOK ANGGOTA
				$query_pokok = "(deskripsi='biaya awal registrasi' OR deskripsi='saldo pokok')";
				$cek_pokok = $this->m_deposit->where('jenis_deposit', 'pokok')
											 ->where('idanggota', $a->iduser)
											 ->where($query_pokok)
											 ->countAllResults();
				
				//JIKA TIDAK ADA POKOK SAMA SEKALI
				if ($cek_pokok == 0) {
				
					$data_pokok = [
						'jenis_pengajuan' => 'penyimpanan',
						'jenis_deposit' => 'pokok',
						'cash_in' => $param_pokok->nilai,
						'cash_out' => 0,
						'deskripsi' => 'biaya awal registrasi',
						'status' => 'diterima',
						'date_created' => date('Y-m-d H:i:s'),
						'idanggota' => $a->iduser,
						'idadmin' => $this->account->iduser
					];

					$this->m_deposit->insertDeposit($data_pokok);
				}

				//CEK WAJIB ANGGOTA
				$query_wajib = "(deskripsi='biaya awal registrasi')";
				$cek_wajib = $this->m_deposit->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
											 ->where('jenis_deposit', 'wajib')
											 ->where('idanggota', $a->iduser)
											 ->where($query_wajib)
											 ->countAllResults();

				//JIKA BELUM ADA WAJIB
				if ($cek_wajib == 0) {
					
					$data_wajib = [
						'jenis_pengajuan' => 'penyimpanan',
						'jenis_deposit' => 'wajib',
						'cash_in' => $param_wajib->nilai,
						'cash_out' => 0,
						'deskripsi' => 'Diambil dari potongan gaji bulanan',
						'status' => 'diterima',
						'date_created' => date('Y-m-d H:i:s'),
						'idanggota' => $a->iduser,
						'idadmin' => $this->account->iduser
					];

					$this->m_deposit->insertDeposit($data_wajib);
				}

				//PEMBUATAN MANASUKA
				$param_manasuka = $this->m_param_manasuka->where('idanggota', $a->iduser)->get()->getResult();
				foreach($param_manasuka as $pm){
					$data_manasuka = [
						'jenis_pengajuan' => 'penyimpanan',
						'jenis_deposit' => 'manasuka',
						'cash_in' => $pm->nilai,
						'cash_out' => 0,
						'deskripsi' => 'Diambil dari potongan gaji bulanan',
						'status' => 'diterima',
						'date_created' => date('Y-m-d H:i:s'),
						'idanggota' => $a->iduser,
						'idadmin' => $this->account->iduser
					];	
				}

				$this->m_deposit->insertDeposit($data_manasuka);

				//PENGECEKAN PINJAMAN
				$cek_pinjaman = $this->m_pinjaman->where('status', 4)
												 ->where('idanggota', $a->iduser)
												 ->countAllResults();
				if ($cek_pinjaman != 0) {
					
					$pinjaman = $this->m_pinjaman->where('status', 4)
												 ->where('idanggota', $a->iduser)
												 ->orderBy('date_updated', 'DESC')
												 ->get()
												 ->getResult();
					//LOOP PINJAMAN
					foreach($pinjaman as $pin){

						//CEK VALIDASI CICILAN BULAN INI
						$validasi_cicilan = $this->m_cicilan->where('idpinjaman', $pin->idpinjaman)
													   		->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
															->where('tipe_bayar', 'otomatis')
															->countAllResults();
						if ($validasi_cicilan == 0) {
							//CEK CICILAN
							$cek_cicilan = $this->m_cicilan->where('idpinjaman', $pin->idpinjaman)
														   ->countAllResults();
							if ($cek_cicilan == 0) {

								$bunga = $this->m_param->where('idparameter', 9)->get()->getResult()[0]->nilai/100;
								$provisi = $this->m_param->where('idparameter', 5)->get()->getResult()[0]->nilai/100;

								$dataset_cicilan = [
									'nominal' => ($pin->nominal/$pin->angsuran_bulanan),
									'bunga' => ($pin->nominal*($pin->angsuran_bulanan*$bunga))/$pin->angsuran_bulanan,
									'provisi' => ($pin->nominal*($pin->angsuran_bulanan*$provisi))/$pin->angsuran_bulanan,
									'date_created' => date('Y-m-d H:i:s'),
									'idpinjaman' => $pin->idpinjaman
								];

								$this->m_cicilan->insertCicilan($dataset_cicilan);
								
							}elseif ($cek_cicilan == ($pin->angsuran_bulanan - 1)) {

								$bunga = $this->m_param->where('idparameter', 9)->get()->getResult()[0]->nilai/100;

								$dataset_cicilan = [
									'nominal' => ($pin->nominal/$pin->angsuran_bulanan),
									'bunga' => ($pin->nominal*($pin->angsuran_bulanan*$bunga))/$pin->angsuran_bulanan,
									'date_created' => date('Y-m-d H:i:s'),
									'idpinjaman' => $pin->idpinjaman
								];

								$this->m_cicilan->insertCicilan($dataset_cicilan);

								$status_pinjaman = ['status' => 5];
								$this->m_pinjaman->updatePinjaman($pin->idpinjaman, $status_pinjaman);

							}elseif ($cek_cicilan != 0 && $cek_cicilan < $pin->angsuran_bulanan) {

								$bunga = $this->m_param->where('idparameter', 9)->get()->getResult()[0]->nilai/100;

								$dataset_cicilan = [
									'nominal' => ($pin->nominal/$pin->angsuran_bulanan),
									'bunga' => ($pin->nominal*($pin->angsuran_bulanan*$bunga))/$pin->angsuran_bulanan,
									'date_created' => date('Y-m-d H:i:s'),
									'idpinjaman' => $pin->idpinjaman
								];

								$this->m_cicilan->insertCicilan($dataset_cicilan);
							}
						}
					}
				}
			}

			$monthly_log = [
				'date_monthly' => $YEAR.'-'.$MONTH,
				'flag' => 1
			];
			$this->m_monthly_report->insert($monthly_log);
		}
		
		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'Laporan Bulan ini berhasil dibuat',
			 	'status' => 'success'
			]
		);
		
		$dataset_notif = ['notif' => $alert];
		session()->setFlashdata($dataset_notif);
		return redirect()->back();
	}

	public function print_potongan_pinjaman()
	{
		$m_monthly_report = new M_monthly_report();
		$idreportm = $this->request->getPost('idreportm');

		if($idreportm == 0){
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Belum memilih bulan laporan',
				 	'status' => 'warning'
				]
			);
			
			$dataset_notif = ['notif_print' => $alert];
			session()->setFlashdata($dataset_notif);
			return redirect()->back();
		}
		
		$report_data = $m_monthly_report->where('idreportm', $idreportm)->get()->getResult()[0];
		
		$endDate = $report_data->created;
		$startDate = $m_monthly_report->getPrevMonth($idreportm)[0]->created;
		
		$instansi = $this->request->getPost('instansi');

		if ($instansi == '0') {
			$user_list = $this->m_user->where('flag', 1)
									  ->where('idgroup', 4)
									  ->get()
									  ->getResult();
		}else{
			$user_list = $this->m_user->where('instansi', $instansi)
									  ->where('flag', 1)
									  ->where('idgroup', 4)
									  ->get()
									  ->getResult();
		}

		$report = [
			'page_title' => 'Ekspor',
			'usr_list' => $user_list,
			'endDate' => $endDate,
			'startDate' => $startDate
		];

		header("Content-type: application/vnd.ms-excel");
		header('Content-Disposition: attachment;filename="cutoff_'.$instansi.'_'.$report_data->created.'.xls"');
		header('Cache-Control: max-age=0');

		echo view('admin/report/print-potongan-pinjaman', $report);
	}

	public function print_rekap_tahunan()
	{
		$tahun = $this->request->getPost('tahun');
		
		if ($tahun == '0') {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Belum memilih tahun laporan',
				 	'status' => 'warning'
				]
			);
			
			$dataset_notif = ['notif_print' => $alert];
			session()->setFlashdata($dataset_notif);
			return redirect()->back();
		}

		$endDate = $tahun.'-'.date('m-d', strtotime("+1 day"));
		$startDate = date('Y-m-d', strtotime('-1 year', strtotime($endDate)));

		$pegawai_but = $this->m_user->where('flag', 1)
									->where('instansi', 'BUT')
									->where('idgroup', 4)
									->get()
									->getResult();

		$pegawai_giat = $this->m_user->where('flag', 1)
									->where('instansi', 'GIAT')
									->where('idgroup', 4)
									->get()
									->getResult();
		
		$pegawai_telkom = $this->m_user->where('flag', 1)
									   ->where('instansi', 'Telkom')
									   ->where('idgroup', 4)
									   ->get()
									   ->getResult();

		$pegawai_trengginas = $this->m_user->where('flag', 1)
										   ->where('instansi', 'Trengginas Jaya')
										   ->where('idgroup', 4)
										   ->get()
										   ->getResult();

		$pegawai_telyu = $this->m_user->where('flag', 1)
									  ->where('instansi', 'Universitas Telkom')
									  ->where('idgroup', 4)
									  ->get()
									  ->getResult();

		$pegawai_ypt = $this->m_user->where('flag', 1)
									->where('instansi', 'YPT')
									->where('idgroup', 4)
									->get()
									->getResult();

		$report = [
			'page_title' => 'rekap tahunan',
			'pegawai_but' => $pegawai_but,
			'pegawai_giat' => $pegawai_giat,
			'pegawai_telkom' => $pegawai_telkom,
			'pegawai_trengginas' => $pegawai_trengginas,
			'pegawai_telyu' => $pegawai_telyu,
			'pegawai_ypt' => $pegawai_ypt,
			'startDate' => $startDate,
			'endDate' => $endDate
		];

		echo view('admin/report/print-rekap-tahunan', $report);
	}

	public function print_rekening_koran()
	{

		$tahun = $this->request->getPost('tahun');
		
		if ($tahun == '0') {
			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Belum memilih tahun laporan',
				 	'status' => 'warning'
				]
			);
			
			$dataset_notif = ['notif_print' => $alert];
			session()->setFlashdata($dataset_notif);
			return redirect()->back();
		}

		$setDay = $this->m_param->where('idparameter', 8)->get()->getResult()[0]->nilai + 1;

		$user_list = $this->m_user->where('flag', '1')
								  ->where('idgroup', 4)
								  ->get()
								  ->getResult();
		
		$spreadsheet = new Spreadsheet();
		$spreadsheet->getDefaultStyle()->getFont()->setName('Bookman Old Style');
		
		foreach($user_list as $member){
			
			$sheet = $spreadsheet->createSheet();
			$sheet->setTitle($member->username);
			
			$sheet->setCellValue('A1', 'DAFTAR SIMPAN PINJAM ANGGOTA KOPERASI "GIAT"');
			$sheet->setCellValue('A2', 'UNIVERSITAS TELKOM BANDUNG');
			$sheet->setCellValue('A3', 'Nama');
			$sheet->setCellValue('B3', ': '.$member->nama_lengkap);
			$sheet->setCellValue('A4', 'Karyawan');
			$sheet->setCellValue('B4', ': '.$member->instansi);
			
			$sheet->setCellValue('A6', 'TANGGAL');
			$sheet->setCellValue('B6', 'URAIAN');
			$sheet->setCellValue('C6', 'SIMPANAN');
			$sheet->setCellValue('C7', 'POKOK');
			$sheet->setCellValue('D7', 'WAJIB');
			$sheet->setCellValue('E7', 'MANASUKA');
			$sheet->setCellValue('F6', 'JUMLAH SIMPANAN');
			$sheet->setCellValue('G6', 'PINJAMAN');
			$sheet->setCellValue('G7', 'JUMLAH PINJAMAN');
			$sheet->setCellValue('H7', 'PEMBAYARAN');
			$sheet->setCellValue('H8', 'POKOK');
			$sheet->setCellValue('I8', 'JASA');
			$sheet->setCellValue('J8', 'PROVISI');
			$sheet->setCellValue('K8', 'PENALTY');
			$sheet->setCellValue('L6', 'SALDO PINJAMAN');
			$sheet->setCellValue('M6', 'KETERANGAN');
			$sheet->setCellValue('M7', 'Jumlah Cicilan');
			$sheet->setCellValue('N7', 'Sisa Cicilan');

			$sheet->setCellValue('A9', 'Saldo Per 01 Januari '.$tahun);

			$sheet->setCellValue('A10', 'January');
			$sheet->setCellValue('A11', 'February');
			$sheet->setCellValue('A12', 'March');
			$sheet->setCellValue('A13', 'April');
			$sheet->setCellValue('A14', 'May');
			$sheet->setCellValue('A15', 'June');
			$sheet->setCellValue('A16', 'July');
			$sheet->setCellValue('A17', 'August');
			$sheet->setCellValue('A18', 'September');
			$sheet->setCellValue('A19', 'October');
			$sheet->setCellValue('A20', 'November');
			$sheet->setCellValue('A21', 'December');

			$sheet->setCellValue('B10', "Potongan Koperasi Jan'".substr($tahun,2));
			$sheet->setCellValue('B11', "Potongan Koperasi Feb'".substr($tahun,2));
			$sheet->setCellValue('B12', "Potongan Koperasi Mar'".substr($tahun,2));
			$sheet->setCellValue('B13', "Potongan Koperasi Apr'".substr($tahun,2));
			$sheet->setCellValue('B14', "Potongan Koperasi May'".substr($tahun,2));
			$sheet->setCellValue('B15', "Potongan Koperasi Jun'".substr($tahun,2));
			$sheet->setCellValue('B16', "Potongan Koperasi Jul'".substr($tahun,2));
			$sheet->setCellValue('B17', "Potongan Koperasi Aug'".substr($tahun,2));
			$sheet->setCellValue('B18', "Potongan Koperasi Sep'".substr($tahun,2));
			$sheet->setCellValue('B19', "Potongan Koperasi Oct'".substr($tahun,2));
			$sheet->setCellValue('B20', "Potongan Koperasi Nov'".substr($tahun,2));
			$sheet->setCellValue('B21', "Potongan Koperasi Dec'".substr($tahun,2));

			$currentYear = date('Y-m-d', strtotime('1 January '.$tahun));
			$lastYear = date('Y-m-d', strtotime($currentYear.' -1 year'));
			
			//SALDO SIMPANAN 1 JANUARI
			$SaldoPokokTahunan = $this->m_deposit->select('SUM(cash_in) - SUM(cash_out) AS saldo')
												 ->where("date_created BETWEEN '".$lastYear."' AND '".$currentYear."'")
												 ->where('status', 'diterima')
                                            	 ->where('jenis_deposit', 'pokok')
                                            	 ->where('idanggota', $member->iduser)
                                            	 ->get()->getResult()[0]->saldo;

			$SaldoWajibTahunan = $this->m_deposit->select('SUM(cash_in) - SUM(cash_out) AS saldo')
												 ->where("date_created BETWEEN '".$lastYear."' AND '".$currentYear."'")
												 ->where('status', 'diterima')
                                            	 ->where('jenis_deposit', 'wajib')
                                            	 ->where('idanggota', $member->iduser)
                                            	 ->get()->getResult()[0]->saldo;

			$SaldoManasukaTahunan = $this->m_deposit->select('SUM(cash_in) - SUM(cash_out) AS saldo')
													->where("date_created BETWEEN '".$lastYear."' AND '".$currentYear."'")
													->where('status', 'diterima')
                                            		->whereIn('jenis_deposit', ['manasuka', 'manasuka free'])
                                            		->where('idanggota', $member->iduser)
                                            		->get()->getResult()[0]->saldo;

            $sheet->setCellValue('C9', ($SaldoPokokTahunan)?$SaldoPokokTahunan:'0');
            $sheet->setCellValue('D9', ($SaldoWajibTahunan)?$SaldoWajibTahunan:'0');
            $sheet->setCellValue('E9', ($SaldoManasukaTahunan)?$SaldoManasukaTahunan:'0');
            $sheet->setCellValue('F9', '=SUM(C9:E9)');

            //JUMLAH PINJAMAN PER 1 JANUARI
            $pinjamanTahunan = $this->m_pinjaman->getPinjamanTahunan($member->iduser, $lastYear, $currentYear);
            
            $sheet->setCellValue('G9', ($pinjamanTahunan)?$pinjamanTahunan[0]->jumlah_pinjaman:'0');
            $sheet->setCellValue('L9', '=G9-SUM(H9:K9)');
            $sheet->setCellValue('M9', ($pinjamanTahunan)?$pinjamanTahunan[0]->jumlah_cicilan:'0');
            $sheet->setCellValue('N9', ($pinjamanTahunan)?$pinjamanTahunan[0]->hitungan_cicilan:'0');


           	//KOLOM PINJAMAN
           	foreach(range('C', 'E') as $column){
           		$row = 10;
           		
           		if ($column == 'C') {
           			$jenis_deposit = ['pokok'];
           		}
           		elseif ($column == 'D'){
           			$jenis_deposit = ['wajib'];
           		}
           		elseif ($column == 'E'){
           			$jenis_deposit = ['manasuka', 'manasuka free'];
           		}

				for ($i = 1; $i < 13; ++$i) {
					
					$endDate = $tahun.'-'.$i.'-'.$setDay;
					$startDate = date('Y-m-d', strtotime('-1 month', strtotime($endDate)));

					$saldo = $this->m_deposit->select('SUM(cash_in) - SUM(cash_out) AS saldo')
												 ->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
												 ->where('status', 'diterima')
                                            	 ->whereIn('jenis_deposit', $jenis_deposit)
                                            	 ->where('idanggota', $member->iduser)
                                            	 ->get()->getResult();

					$sheet->setCellValue($column.$row, ($saldo)?$saldo[0]->saldo:'');
					$row++;
				}
           	}
			
           	//SUM JUMLAH SIMPANAN
			for ($i = 10; $i < 22; ++$i) {
				$prev_row = $i - 1;	
				$sheet->setCellValue("F".$i, '=F'.$prev_row.'+SUM(C'.$i.':E'.$i.')');
			}

			//JUMLAH PINJAMAN
			$row = 10;
			for ($i = 1; $i < 13; ++$i) {
				$endDate = $tahun.'-'.$i.'-'.$setDay;
				$startDate = date('Y-m-d', strtotime('-1 month', strtotime($endDate)));

				$jum_pinjaman = $this->m_pinjaman->select("SUM(nominal) as nominal, angsuran_bulanan, DATE(CONCAT(YEAR('".$startDate."'),'-',(bln_perdana - 1),'-',tanggal_bayar)) as tgl")
												 ->where('status BETWEEN 4 AND 5')
												 ->where("DATE(CONCAT(YEAR('".$startDate."'),'-',bln_perdana-1,'-',tanggal_bayar)) BETWEEN '".$startDate."' AND '".$endDate."'")
												 ->where('idanggota', $member->iduser)
												 ->groupBy('tgl')
												 ->get()->getResult();

				$sheet->setCellValue('G'.$row, ($jum_pinjaman)?$jum_pinjaman[0]->nominal:'0');
				$sheet->setCellValue('L'.$row, '=IFERROR(L'.($row-1).'+G'.$row.'-H'.$row.', L'.($row-1).')');
				$sheet->setCellValue('M'.$row, ($jum_pinjaman)?$jum_pinjaman[0]->angsuran_bulanan:'=M'.($row-1));
				$row++;
			}

			//CICILAN BULANAN
			foreach(range('H', 'J') as $column){
				$row = 10;

				if ($column == 'H') {
					$tipe = 'nominal';
				}
				elseif ($column == 'I') {
					$tipe = 'bunga';
				}
				elseif ($column == 'J') {
					$tipe = 'provisi';
				}

				for ($i = 1; $i < 13; $i++) {					
					$endDate = $tahun.'-'.$i.'-'.$setDay;
					$startDate = date('Y-m-d', strtotime('-1 month', strtotime($endDate)));

					$saldo = $this->m_cicilan->select('ROUND(SUM(tb_cicilan.'.$tipe.')) AS saldo')
											 ->join('tb_pinjaman', 'tb_cicilan.idpinjaman = tb_pinjaman.idpinjaman')
											 ->where("tb_cicilan.date_created BETWEEN '".$startDate."' AND '".$endDate."'")
                                             ->where('idanggota', $member->iduser)
                                             ->get()->getResult();

					$sheet->setCellValue($column.$row, ($saldo)?$saldo[0]->saldo:'0');
					$row++;
				}
			}

			//SISA CICILAN
			$pinjaman_aktif = $this->m_pinjaman->where('status', '4')
											   ->where('idanggota', $member->iduser)
											   ->get()->getResult();
			$row = 10;
			if ($pinjaman_aktif) {
				for ($i = 1; $i < 13; ++$i) {
					$startDate = date('Y-m-d', strtotime($pinjaman_aktif[0]->date_updated));
					$endDate = $tahun.'-'.$i.'-'.$setDay;

					$cicilan = $this->m_cicilan->select('COUNT(idcicilan) AS hitung')
											   ->join('tb_pinjaman', 'tb_cicilan.idpinjaman = tb_pinjaman.idpinjaman')
											   ->where("tb_cicilan.date_created BETWEEN '".$startDate."' AND '".$endDate."'")
											   ->where('idanggota', $member->iduser)
											   ->get()->getResult();
	
					$sheet->setCellValue("N".$row, "=M".$row."-".$cicilan[0]->hitung);
					$row++;
				}
			}

			//SUM SEMUA
			$sheet->setCellValue("A24", "SALDO PER 31 DECEMBER ".$tahun);
			$sheet->setCellValue("C24", "=SUM(C9:C23)");
			$sheet->setCellValue("D24", "=SUM(D9:D23)");
			$sheet->setCellValue("E24", "=SUM(E9:E23)");
			$sheet->setCellValue("F24", "=SUM(F9:F23)");
			$sheet->setCellValue("G24", "=SUM(G9:G23)");
			$sheet->setCellValue("H24", "=SUM(H9:H23)");
			$sheet->setCellValue("I24", "=SUM(I9:I23)");
			$sheet->setCellValue("J24", "=SUM(J9:J23)");
			$sheet->setCellValue("K24", "=SUM(K9:K23)");
			$sheet->setCellValue("L24", "=G24-H24");

			//STYLING
			$defStyle = [
				'alignment' => [
					'wrapText' => true
				],
			    'borders' => [
			        'allBorders' => [
			            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
			            'color' => ['argb' => 'FF000000'],
			        ],
			    ],
			];

			$sheet->getStyle('A6:N24')->applyFromArray($defStyle);
			$sheet->getStyle('A6:N8')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
			$sheet->getStyle('A6:N8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('A6:N8')->getFont()->setBold(true);
			$sheet->getStyle('A24:N24')->getFont()->setBold(true);

			foreach (range('B', 'N') as $col) {
			   $sheet->getColumnDimension($col)->setAutoSize(true);
			}

			$sheet->mergeCells('A6:A8');
			$sheet->mergeCells('B6:B8');
			$sheet->mergeCells('C6:E6');
			$sheet->mergeCells('C7:C8');
			$sheet->mergeCells('D7:D8');
			$sheet->mergeCells('E7:E8');
			$sheet->mergeCells('F6:F8');
			$sheet->mergeCells('G6:K6');
			$sheet->mergeCells('G7:G8');
			$sheet->mergeCells('H7:K7');
			$sheet->mergeCells('L6:L8');
			$sheet->mergeCells('M6:N6');
			$sheet->mergeCells('M7:M8');
			$sheet->mergeCells('N7:N8');
			$sheet->mergeCells('A9:B9');
			$sheet->mergeCells('A24:B24');
		}

	
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="REKENING KORAN PER TAHUN '.$tahun.'.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');
	}

}