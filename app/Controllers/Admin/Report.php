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
	protected $account;
	protected $notification;

	function __construct()
	{
		$m_user = model(M_user::class);

		$this->notification = new Notifications();

		$config = new \Config\Encryption();
		$encrypter = \Config\Services::encrypter($config);

		$data = $m_user->getUserById(session()->get('iduser'))[0];
		
		$nik = ($data->nik != null || $data->nik != '') ? $encrypter->decrypt(base64_decode($data->nik)) : '';
		$nip = ($data->nip != null || $data->nip != '') ? $encrypter->decrypt(base64_decode($data->nip)) : '';
		$no_rek = ($data->no_rek != null || $data->no_rek != '') ? $encrypter->decrypt(base64_decode($data->no_rek)) : '';
		$nomor_telepon = ($data->nomor_telepon != null || $data->nomor_telepon != '') ? $encrypter->decrypt(base64_decode($data->nomor_telepon)) : '';
		$alamat = ($data->alamat != null || $data->alamat != '') ? $encrypter->decrypt(base64_decode($data->alamat)) : '';

		$this->account = (object) [
			'iduser' => $data->iduser,
			'username' => $data->username,
			'nik' => $nik,
			'nip' => $nip,
			'nama_lengkap' => $data->nama_lengkap,
			'tempat_lahir' => $data->tempat_lahir,
			'tanggal_lahir' => $data->tanggal_lahir,
			'status_pegawai' => $data->status_pegawai,
			'nama_bank' => $data->nama_bank,
			'no_rek' => $no_rek,
			'alamat' => $alamat,
			'instansi' => $data->instansi,
			'unit_kerja' => $data->unit_kerja,
			'nomor_telepon' => $nomor_telepon,
			'email' => $data->email,
			'profil_pic' => $data->profil_pic,
			'user_created' => $data->user_created,
			'user_updated' => $data->user_updated,
			'closebook_request' => $data->closebook_request,
			'closebook_request_date' => $data->closebook_request_date,
			'closebook_last_updated' => $data->closebook_last_updated,
			'closebook_param_count' => $data->closebook_param_count,
			'user_flag' => $data->user_flag,
			'idgroup' => $data->idgroup,
			'group_type' => $data->group_type,
			'group_assigned' => $data->group_assigned,
			'group_flag' => $data->group_flag
		];
	}

	public function index()
	{
		$m_monthly_report = model(M_monthly_report::class);
		$m_param = model(M_param::class);

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
			'notification_list' => $this->notification->index()['notification_list'],
			'notification_badges' => $this->notification->index()['notification_badges'],
			'duser' => $this->account,
			'list_report' => $list_report,
			'list_tahun' => $list_tahun,
			'cek_report' => $cek_report,
			'getDay' => $getDay
		];
		
		return view('admin/report/reporting-page', $data);
	}
	
	public function print_potongan_pinjaman()
	{
		$m_monthly_report = model(M_monthly_report::class);
		$m_user = model(M_user::class);

		$idreportm = request()->getPost('idreportm');

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
		
		$instansi = request()->getPost('instansi');

		if ($instansi == '0') {
			$user_list = $m_user->where('flag', 1)
									  ->where('idgroup', 4)
									  ->get()
									  ->getResult();
		}else{
			$user_list = $m_user->where('instansi', $instansi)
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
		$m_user = model(M_user::class);
		$tahun = request()->getPost('tahun');
		
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

		$pegawai_but = $m_user->where('flag', 1)
									->where('instansi', 'BUT')
									->where('idgroup', 4)
									->get()
									->getResult();

		$pegawai_giat = $m_user->where('flag', 1)
									->where('instansi', 'GIAT')
									->where('idgroup', 4)
									->get()
									->getResult();
		
		$pegawai_telkom = $m_user->where('flag', 1)
									   ->where('instansi', 'Telkom')
									   ->where('idgroup', 4)
									   ->get()
									   ->getResult();

		$pegawai_trengginas = $m_user->where('flag', 1)
										   ->where('instansi', 'Trengginas Jaya')
										   ->where('idgroup', 4)
										   ->get()
										   ->getResult();

		$pegawai_telyu = $m_user->where('flag', 1)
									  ->where('instansi', 'Universitas Telkom')
									  ->where('idgroup', 4)
									  ->get()
									  ->getResult();

		$pegawai_ypt = $m_user->where('flag', 1)
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
		$m_param = model(M_param::class);
		$m_deposit = model(M_deposit::class);
		$m_pinjaman = model(M_pinjaman::class);
		$m_cicilan = model(M_cicilan::class);

		$m_user = model(M_user::class);

		$tahun = request()->getPost('tahun');
		
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

		$setDay = $m_param->where('idparameter', 8)->get()->getResult()[0]->nilai + 1;

		$user_list = $m_user->where('flag', '1')
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
			$SaldoPokokTahunan = $m_deposit->select('SUM(cash_in) - SUM(cash_out) AS saldo')
												 ->where("date_created BETWEEN '".$lastYear."' AND '".$currentYear."'")
												 ->where('status', 'diterima')
                                            	 ->where('jenis_deposit', 'pokok')
                                            	 ->where('idanggota', $member->iduser)
                                            	 ->get()->getResult()[0]->saldo;

			$SaldoWajibTahunan = $m_deposit->select('SUM(cash_in) - SUM(cash_out) AS saldo')
												 ->where("date_created BETWEEN '".$lastYear."' AND '".$currentYear."'")
												 ->where('status', 'diterima')
                                            	 ->where('jenis_deposit', 'wajib')
                                            	 ->where('idanggota', $member->iduser)
                                            	 ->get()->getResult()[0]->saldo;

			$SaldoManasukaTahunan = $m_deposit->select('SUM(cash_in) - SUM(cash_out) AS saldo')
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
            $pinjamanTahunan = $m_pinjaman->getPinjamanTahunan($member->iduser, $lastYear, $currentYear);
            
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

					$saldo = $m_deposit->select('SUM(cash_in) - SUM(cash_out) AS saldo')
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

				$jum_pinjaman = $m_pinjaman->select("SUM(nominal) as nominal, angsuran_bulanan, DATE(CONCAT(YEAR('".$startDate."'),'-',(bln_perdana - 1),'-',tanggal_bayar)) as tgl")
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

					$saldo = $m_cicilan->select('ROUND(SUM(tb_cicilan.'.$tipe.')) AS saldo')
											 ->join('tb_pinjaman', 'tb_cicilan.idpinjaman = tb_pinjaman.idpinjaman')
											 ->where("tb_cicilan.date_created BETWEEN '".$startDate."' AND '".$endDate."'")
                                             ->where('idanggota', $member->iduser)
                                             ->get()->getResult();

					$sheet->setCellValue($column.$row, ($saldo)?$saldo[0]->saldo:'0');
					$row++;
				}
			}

			//SISA CICILAN
			$pinjaman_aktif = $m_pinjaman->where('status', '4')
											   ->where('idanggota', $member->iduser)
											   ->get()->getResult();
			$row = 10;
			if ($pinjaman_aktif) {
				for ($i = 1; $i < 13; ++$i) {
					$startDate = date('Y-m-d', strtotime($pinjaman_aktif[0]->date_updated));
					$endDate = $tahun.'-'.$i.'-'.$setDay;

					$cicilan = $m_cicilan->select('COUNT(idcicilan) AS hitung')
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

	public function gen_report()
	{
		$m_monthly_report = model(M_monthly_report::class);
		$m_user = model(M_user::class);
		$m_param = model(M_param::class);

		// 1. ambil konfigurasi awal
		$YEAR = date('Y');
		$MONTH = date('m');
		$startDate = $m_monthly_report->orderBy('idreportm', 'DESC')->get(1)->getResult()[0]->created;
		$endDate = date('Y-m-d H:i:s');
		$params = $this->fetchParams($m_param);

		// 2. ambil semua anggota aktif
		$list_anggota = $m_user->where('flag', '1')->where('idgroup', 4)->get()->getResult();

		$logReport = $m_monthly_report->where('date_monthly', $YEAR.'-'.$MONTH)->countAllResults();

		if ($logReport == 0 || !$logReport){

			// 3. Buat Log Report
			$monthly_log = [
				'date_monthly' => $YEAR.'-'.$MONTH,
				'flag' => 1
			];

			$m_monthly_report->insert($monthly_log);

			// 4. Proses User Baru
			$this->handleNewUsers($startDate, $endDate);

			// 5. Proses Simpanan dan Pinjaman User
			foreach ($list_anggota as $a){
				$this->processPokok($a->iduser, $params['pokok']);
				$this->processWajib($a->iduser, $params['wajib'], $startDate, $endDate);
				$this->processManasuka($a->iduser);
				$this->processPinjaman($a->iduser, $params['bunga'], $params['provisi'], $startDate, $endDate);
			}
			
			// echo "new_gen_report function true passed <br>";

		} else {

			$alert = view(
				'partials/notification-alert', 
				[
					'notif_text' => 'Laporan bulan ini sudah dibuat',
					 'status' => 'success'
				]
			);
			
			$dataset_notif = ['notif' => $alert];
			session()->setFlashdata($dataset_notif);
			return redirect()->back();

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

	private function fetchParams($m_param)
	{
		return [
			'pokok' => $m_param->where('idparameter', 1)->get()->getResult()[0]->nilai,
			'wajib' => $m_param->where('idparameter', 2)->get()->getResult()[0]->nilai,
			'bunga' => $m_param->where('idparameter', 9)->get()->getResult()[0]->nilai / 100,
			'provisi' => $m_param->where('idparameter', 5)->get()->getResult()[0]->nilai / 100,
		];
	}

	private function handleNewUsers($startDate, $endDate)
	{
		$m_deposit = model(M_deposit::class);

		//UPDATE STATUS POKOK UNTUK SEMUA ANGGOTA BARU BULAN INI
		$m_deposit->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
			->where('status', 'diproses')
			->where('jenis_deposit', 'pokok')
			->where('deskripsi', 'biaya awal registrasi')
			->where('idanggota IN (SELECT iduser FROM tb_user WHERE flag = 1)')
			->set('status', 'diterima')
			->set('idadmin', $this->account->iduser)
			->update();

		//UPDATE STATUS WAJIB UNTUK SEMUA ANGGOTA BARU BULAN INI
		$m_deposit->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
			->where('status', 'diproses')
			->where('jenis_deposit', 'wajib')
			->where('deskripsi', 'biaya awal registrasi')
			->where('idanggota IN (SELECT iduser FROM tb_user WHERE flag = 1)')
			->set('status', 'diterima')
			->set('idadmin', $this->account->iduser)
			->update();

		// echo "New users processed <br>";
	}

	private function processPokok($idUser, $paramPokok)
	{
		$m_deposit = model(M_deposit::class);
		$queryPokok = "(deskripsi='biaya awal registrasi' OR deskripsi='saldo pokok')";

		$cekPokok = $m_deposit->where('jenis_deposit', 'pokok')
			->where('idanggota', $idUser)
			->where($queryPokok)
			->countAllResults();
		
		if ($cekPokok == 0)
		{
			$data_pokok = [
				'jenis_pengajuan' => 'penyimpanan',
				'jenis_deposit' => 'pokok',
				'cash_in' => $paramPokok->nilai,
				'cash_out' => 0,
				'deskripsi' => 'biaya awal registrasi',
				'status' => 'diterima',
				'date_created' => date('Y-m-d H:i:s'),
				'idanggota' => $idUser,
				'idadmin' => $this->account->iduser
			];

			$m_deposit->insertDeposit($data_pokok);
			// echo "New pokok processed for user ".$idUser."<br>";
		}	
	}

	private function processWajib($idUser, $paramWajib, $startDate, $endDate)
	{
		$m_deposit = model(M_deposit::class);
		$cekWajib = $m_deposit->where('jenis_deposit', 'wajib')
			->where('idanggota', $idUser)
			->where('date_created >=', $startDate)
			->where('date_created <=', $endDate)
			->countAllResults();

		if ($cekWajib == 0)
		{
			$dataWajib = [
				'jenis_pengajuan' => 'penyimpanan',
				'jenis_deposit' => 'wajib',
				'cash_in' => $paramWajib->nilai,
				'cash_out' => 0,
				'deskripsi' => 'Diambil dari potongan gaji bulanan',
				'status' => 'diterima',
				'date_created' => date('Y-m-d H:i:s'),
				'idanggota' => $idUser,
				'idadmin' => $this->account->iduser
			];

			// $m_deposit->insertDeposit($dataWajib);
			echo "New simpanan wajib processed for user ".$idUser."<br>";
		}
	}

	private function processManasuka($idUser)
	{
		$m_deposit = model(M_deposit::class);
		$m_user = model(M_user::class);
		$m_param_manasuka = model(M_param_manasuka::class);
	
		// Ambil parameter manasuka untuk anggota
		$param_manasuka = $m_param_manasuka->where('idanggota', $idUser)->get(1)->getRow();
	
		// Validasi parameter
		if (!$param_manasuka) {
			$user = $m_user->getUserById($idUser)[0];
			log_message('error', "Param manasuka tidak ditemukan untuk anggota: $user->username");
			return;
		}
	
		$data_manasuka = [
			'jenis_pengajuan' => 'penyimpanan',
			'jenis_deposit' => 'manasuka',
			'cash_in' => $param_manasuka->nilai,
			'cash_out' => 0,
			'deskripsi' => 'Diambil dari potongan gaji bulanan',
			'status' => 'diterima',
			'date_created' => date('Y-m-d H:i:s'),
			'idanggota' => $idUser,
			'idadmin' => $this->account->iduser
		];
	
		$result = $m_deposit->insertDeposit($data_manasuka);
		// echo "New manasuka processed for user ".$idUser."<br>";

		if (!$result) {
			$user = $m_user->getUserById($idUser)[0];
			log_message('error', "Gagal menyimpan manasuka untuk anggota: $user->username");
		}
	}

	private function processPinjaman($idUser, $paramBunga, $paramProvisi, $startDate, $endDate)
	{
		$m_pinjaman = model(M_pinjaman::class);
		$m_cicilan = model(M_cicilan::class);

		$cek_pinjaman = $m_pinjaman->where('status', 4)
			->where('idanggota', $idUser)
			->countAllResults();

		if ($cek_pinjaman != 0) {
			
			$pinjaman = $m_pinjaman->where('status', 4)
				->where('idanggota', $idUser)
				->orderBy('date_updated', 'DESC')
				->get()
				->getResult();

			//LOOP PINJAMAN
			foreach($pinjaman as $pin){

				//CEK VALIDASI CICILAN BULAN INI
				$validasi_cicilan = $m_cicilan->where('idpinjaman', $pin->idpinjaman)
					->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
					->where('tipe_bayar', 'otomatis')
					->countAllResults();

				if ($validasi_cicilan == 0) {
					
					$dataCicilan = [
						'idpinjaman' => $pin->idpinjaman,
						'nominal' => ($pin->nominal/$pin->angsuran_bulanan),
						'bunga' => ($pin->nominal*($pin->angsuran_bulanan*$paramBunga))/$pin->angsuran_bulanan,
						'date_created' => date('Y-m-d H:i:s'),
					];

					//CEK CICILAN
					$cek_cicilan = $m_cicilan->where('idpinjaman', $pin->idpinjaman)->countAllResults();

					if ($cek_cicilan == 0) {

						$dataCicilan += [
							'provisi' => ($pin->nominal*($pin->angsuran_bulanan*$paramProvisi))/$pin->angsuran_bulanan
						];
						
					}elseif ($cek_cicilan == ($pin->angsuran_bulanan - 1)) {

						$statusPinjaman = ['status' => 5];
						$m_pinjaman->updatePinjaman($pin->idpinjaman, $statusPinjaman);
						// echo "Cicilan for pinjaman ".$pin->idpinjaman." lunas for user ".$idUser."<br>";

					}

					$m_cicilan->insertCicilan($dataCicilan);
					// echo "New cicilan processed for pinjaman ".$pin->idpinjaman." at user ".$idUser."<br>";
				}
			}
		}
	}
	
}