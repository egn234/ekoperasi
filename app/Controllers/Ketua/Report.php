<?php 
namespace App\Controllers\Ketua;

require_once ROOTPATH.'vendor/autoload.php';

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_monthly_report;
use App\Models\M_param;
use App\Models\M_cicilan;
use App\Models\M_pinjaman;

use App\Controllers\Ketua\Notifications;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Report extends Controller
{
	protected $m_user;
	protected $account;
	protected $notification;

	function __construct()
	{
		$this->m_user = model(M_user::class);

		$this->notification = new Notifications();

		$config = new \Config\Encryption();
		$encrypter = \Config\Services::encrypter($config);

		$user = $this->m_user->getUserById(session()->get('iduser'));
		if (empty($user)) {
			$user = null;
		} else {
			$data = $user[0];
			
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
	}

	public function index()
	{
		$m_monthly_report = model(M_monthly_report::class);
		$list_report = $m_monthly_report->getAllMonthlyReport();
		$list_tahun = $m_monthly_report->select('YEAR(created) AS tahun')
											 ->groupBy('YEAR(created)', 'DESC')
											 ->get()
											 ->getResult();
		$YEAR = date('Y');
		$MONTH = date('m');
		$cek_report = $m_monthly_report->where('date_monthly', $YEAR.'-'.$MONTH)->countAllResults();
		
		$data = [
			'title_meta' => view('ketua/partials/title-meta', ['title' => 'Reporting']),
			'page_title' => view('ketua/partials/page-title', ['title' => 'Dashboard', 'li_1' => 'EKoperasi', 'li_2' => 'Report']),
			'notification_list' => $this->notification->index()['notification_list'],
			'notification_badges' => $this->notification->index()['notification_badges'],
			'duser' => $this->account,
			'list_report' => $list_report,
			'list_tahun' => $list_tahun,
			'cek_report' => $cek_report
		];
		
		return view('ketua/report/reporting-page', $data);
	}

	public function print_potongan_pinjaman()
	{
		$m_monthly_report = model(M_monthly_report::class);

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

		echo view('ketua/report/print-potongan-pinjaman', $report);
	}

	public function print_rekening_koran()
	{
		$m_param = model(M_param::class);
		$m_deposit = model(M_deposit::class);
		$m_pinjaman = model(M_pinjaman::class);
		$m_cicilan = model(M_cicilan::class);

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

		$user_list = $this->m_user->where('flag', '1')
								  ->where('idgroup', 4)
								  ->get()
								  ->getResult();
		
		$spreadsheet = new Spreadsheet();
		$spreadsheet->getDefaultStyle()->getFont()->setName('Bookman Old Style');
		
		foreach($user_list as $member){
			
			$sheet = $spreadsheet->createSheet();
			$sheet->setTitle($member->nama_lengkap);
			
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

	function generateReportTahunan()
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

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->setTitle('Laporan Tahun '.$tahun);

		// Header kolom
		$sheet->mergeCells('A1:A2'); // NO. URUT
		$sheet->setCellValue('A1', 'NO. URUT');

		$sheet->mergeCells('B1:B2'); // NAMA ANGGOTA KOPERASI
		$sheet->setCellValue('B1', 'NAMA ANGGOTA KOPERASI');

		$sheet->mergeCells('C1:E1'); // SIMPANAN (POKOK, WAJIB, MANASUKA)
		$sheet->setCellValue('C1', 'SIMPANAN');
		$sheet->setCellValue('C2', 'POKOK'); // Subkolom SIMPANAN
		$sheet->setCellValue('D2', 'WAJIB');
		$sheet->setCellValue('E2', 'MANASUKA');

		$sheet->mergeCells('F1:F2'); // SALDO SIMPANAN PER ...
		$sheet->setCellValue('F1', strtoupper('SALDO SIMPANAN PER ' . date('d F Y')));

		$sheet->mergeCells('G1:H1'); // PINJAMAN (JUMLAH, PEMBAYARAN POKOK)
		$sheet->setCellValue('G1', 'PINJAMAN');
		$sheet->setCellValue('G2', 'JUMLAH PINJAMAN'); // Subkolom PINJAMAN
		$sheet->setCellValue('H2', 'PEMBAYARAN POKOK');

		$sheet->mergeCells('I1:I2'); // SALDO PINJAMAN PER ...
		$sheet->setCellValue('I1', strtoupper('SALDO PINJAMAN PER ' . date('d F Y')));

		$sheet->mergeCells('J1:K1'); // KETERANGAN (JUMLAH CICILAN, SISA CICILAN)
		$sheet->setCellValue('J1', 'KETERANGAN');
		$sheet->setCellValue('J2', 'JUMLAH CICILAN'); // Subkolom KETERANGAN
		$sheet->setCellValue('K2', 'SISA CICILAN');

		$sheet->mergeCells('L1:L2'); // NOMOR ANGGOTA
		$sheet->setCellValue('L1', 'NOMOR ANGGOTA');

		// Tambahkan styling (opsional)
		$sheet->getStyle('A1:L2')->getFont()->setBold(true);
		$sheet->getStyle('A1:L2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('A1:L2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

		$list_pegawai = [
			'BUT' => $m_user->where('flag', 1)->where('instansi', 'YPT')->where('idgroup', 4)->get()->getResult(),
			'GIAT' => $m_user->where('flag', 1)->where('instansi', 'GIAT')->where('idgroup', 4)->get()->getResult(),
			'Telkom' => $m_user->where('flag', 1)->where('instansi', 'Telkom')->where('idgroup', 4)->get()->getResult(),
			'Trengginas Jaya' => $m_user->where('flag', 1)->where('instansi', 'Trengginas Jaya')->where('idgroup', 4)->get()->getResult(),
			'Telkom University' => $m_user->where('flag', 1)->where('instansi', 'Telkom University')->where('idgroup', 4)->get()->getResult(),
			'YPT' => $m_user->where('flag', 1)->where('instansi', 'YPT')->where('idgroup', 4)->get()->getResult()
		];

		$row = 3;
		$number = 1;

		$m_monthly_report = model(M_monthly_report::class);
		$m_deposit = model(M_deposit::class);
		$m_cicilan = model(M_cicilan::class);

		foreach ($list_pegawai as $instansi => $pegawai_list) {
			// Tambahkan baris pembatas untuk instansi
			$sheet->mergeCells('A' . $row . ':L' . $row); // Gabungkan kolom A sampai L
			$sheet->setCellValue('A' . $row, strtoupper('DAFTAR ' . $instansi)); // Nama instansi
			$sheet->getStyle('A' . $row)->getFont()->setBold(true); // Bold untuk pembatas
			$sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$row++;
			
			foreach ($pegawai_list as $index => $pegawai) {
				// Perhitungan Data
				$cicilan = $m_monthly_report->getHitunganPinjaman2($pegawai->iduser, $startDate, $endDate);
				$pinjaman = $m_monthly_report->getPinjamanAktifByAnggota($pegawai->iduser, $startDate, $endDate);
				
				if ($pinjaman){
					$count_cicilan = $m_monthly_report->countCicilanByPinjaman($pinjaman[0]->idpinjaman, $startDate, $endDate)[0]->hitung;
				} else {
					$count_cicilan = " - ";
				}

				$simpanan_pokok = $m_deposit->select("SUM(cash_in)-SUM(cash_out) AS nominal")
					->where('status', 'diterima')
					->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
					->where('jenis_deposit', 'pokok')
					->where('idanggota', $pegawai->iduser)
					->get()
					->getResult()[0]
					->nominal;

				$simpanan_wajib = $m_deposit->select("SUM(cash_in)-SUM(cash_out) AS nominal")
					->where('status', 'diterima')
					->whereNotIn('deskripsi', ['saldo wajib'])
					->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
					->where('jenis_deposit', 'wajib')
					->where('idanggota', $pegawai->iduser)
					->get()
					->getResult()[0]
					->nominal;

				$simpanan_manasuka = $m_deposit->select("SUM(cash_in)-SUM(cash_out) AS nominal")
					->where('status', 'diterima')
					->whereNotIn('deskripsi', ['saldo manasuka'])
					->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
					->whereIn('jenis_deposit', ['manasuka', 'manasuka free'])
					->where('idanggota', $pegawai->iduser)
					->get()
					->getResult()[0]
					->nominal;

				if($pinjaman){
					$cicilan_dalam = $m_cicilan->select("SUM(nominal) as nominal")
						->where('idpinjaman', $pinjaman[0]->idpinjaman)
						->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
						->get()->getResult()[0]
						->nominal;

					$cicilan_luar = $m_cicilan->select("SUM(nominal) as nominal")
						->where('idpinjaman', $pinjaman[0]->idpinjaman)
						->where("date_created < '".$startDate."'")
						->get()->getResult()[0]
						->nominal;

					$jumlah_pinjaman = $pinjaman[0]->nominal - $cicilan_luar;

				}else{
					$cicilan_dalam = 0;
					$cicilan_luar = 0;
					$jumlah_pinjaman = 0;
				}
				

				$manasuka = $m_monthly_report->getSumSimpanan2($pegawai->iduser, $startDate, $endDate)[0]->nominal;  
				$p_pokok = ($cicilan)?$cicilan[0]->nominal:0;

				// Tambahkan data ke dalam kolom sheet
				$sheet->setCellValue('A'.$row, $number);
				$sheet->setCellValue('B'.$row, $pegawai->nama_lengkap);
				$sheet->setCellValue('C'.$row, $simpanan_pokok);
				$sheet->setCellValue('D'.$row, $simpanan_wajib);
				$sheet->setCellValue('E'.$row, $simpanan_manasuka);
				$sheet->setCellValue('F'.$row, ($simpanan_manasuka+$simpanan_wajib+$simpanan_pokok));
				$sheet->setCellValue('G'.$row, $jumlah_pinjaman);
				$sheet->setCellValue('H'.$row, $cicilan_dalam);
				$sheet->setCellValue('I'.$row, $jumlah_pinjaman-$cicilan_dalam);
				$sheet->setCellValue('J'.$row, ($pinjaman)?$pinjaman[0]->angsuran_bulanan:' - ');
				$sheet->setCellValue('K'.$row, ($count_cicilan != " - ")?$pinjaman[0]->angsuran_bulanan - $count_cicilan:' - ');
				$sheet->setCellValue('L'.$row, $pegawai->username);

				$row++;
				$number++;
			}
		}
		// Format kolom sebagai currency (contoh untuk kolom Simpanan dan Pinjaman)
		$sheet->getStyle('C4:F' . ($row - 1))->getNumberFormat()->setFormatCode('#,##0.00'); // Kolom Simpanan
		$sheet->getStyle('G4:H' . ($row - 1))->getNumberFormat()->setFormatCode('#,##0.00'); // Kolom Pinjaman
		$sheet->getStyle('I4:I' . ($row - 1))->getNumberFormat()->setFormatCode('#,##0.00'); // Saldo Pinjaman
		// Set border untuk header (opsional)
		$sheet->getStyle('A1:L' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

		// Setelah data selesai ditulis ke sheet
		foreach (range('A', 'L') as $columnID) {
			$sheet->getColumnDimension($columnID)->setAutoSize(true);
		}

		// Streaming Excel ke browser
        $writer = new Xlsx($spreadsheet);
        $filename = 'REKAP_TAHUNAN_' . $tahun . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
	}
}