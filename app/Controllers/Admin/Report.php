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
	}

	public function index()
	{
		$list_report = $this->m_monthly_report->getAllMonthlyReport();
		$cek_report = $this->m_monthly_report->countReportCurrentMonth()[0]->hitung;

		$data = [
			'title_meta' => view('admin/partials/title-meta', ['title' => 'Dashboard']),
			'page_title' => view('admin/partials/page-title', ['title' => 'Dashboard', 'li_1' => 'EKoperasi', 'li_2' => 'Dashboard']),
			'duser' => $this->account,
			'list_report' => $list_report,
			'cek_report' => $cek_report
		];
		
		return view('admin/report/reporting-page', $data);
	}

	public function generate_report_monthly()
	{
		$report = [
			'date_monthly' => date('Y-m-d H:i:s'),
			'flag' => 1
		];
		
		$this->m_monthly_report->insertMonthlyReport($report);

		$month = date('m');

		$list_anggota = $this->m_monthly_report->getAllIdAnggotaAktif();

		foreach ($list_anggota as $member) {

			$cek_new_user = $this->m_monthly_report->countNewWajibMonthlyByUser($member->iduser)[0]->hitung;

			if ($cek_new_user != 0) {
			
				$this->m_monthly_report->setNewWajibMonthlyByUser($member->iduser);

				$param_manasuka = $this->m_param_manasuka->getParamByUserId($member->iduser)[0]->nilai;

				$dataset_deposit = [
					'jenis_pengajuan' => 'penyimpanan',
					'jenis_deposit' => 'manasuka',
					'cash_in' => $param_manasuka,
					'cash_out' => 0,
					'deskripsi' => 'Diambil dari potongan gaji bulanan',
					'status' => 'diterima',
					'date_created' => date('Y-m-d H:i:s'),				
					'idanggota' => $member->iduser,
					'idadmin' => $this->account->iduser
				];

				$this->m_deposit->insertDeposit($dataset_deposit);
			}else{
				
				$saldo_mutasi = [
					$this->m_param->getParamById(2)[0]->nilai,
					$this->m_param_manasuka->getParamByUserId($member->iduser)[0]->nilai
				];

				$jenis_deposit = ['wajib', 'manasuka'];

				for ($i = 0; $i < 2; $i++) {
					$dataset_deposit = [
						'jenis_pengajuan' => 'penyimpanan',
						'jenis_deposit' => $jenis_deposit[$i],
						'cash_in' => $saldo_mutasi[$i],
						'cash_out' => 0,
						'deskripsi' => 'Diambil dari potongan gaji bulanan',
						'status' => 'diterima',
						'date_created' => date('Y-m-d H:i:s'),				
						'idanggota' => $member->iduser,
						'idadmin' => $this->account->iduser
					];

					$this->m_deposit->insertDeposit($dataset_deposit);
				}
			}
			
			$cek_list_pinjaman = $this->m_monthly_report->countPinjamanAktifByAnggota($member->iduser)[0]->hitung;

			if ($cek_list_pinjaman != 0) {
				
				$list_pinjaman = $this->m_monthly_report->getPinjamanAktifByAnggota($member->iduser);
				foreach($list_pinjaman AS $pinjaman) {
					
					$cek_cicilan = $this->m_monthly_report->countCicilanByPinjaman($pinjaman->idpinjaman)[0]->hitung;
					if ($cek_cicilan == 0) {

						$bunga = $this->m_param->getParamById(9)[0]->nilai/100;
						$provisi = $this->m_param->getParamById(5)[0]->nilai/100;

						$dataset_cicilan = [
							'nominal' => ($pinjaman->nominal/$pinjaman->angsuran_bulanan),
							'bunga' => ($pinjaman->nominal*($pinjaman->angsuran_bulanan*$bunga))/$pinjaman->angsuran_bulanan,
							'provisi' => ($pinjaman->nominal*($pinjaman->angsuran_bulanan*$bunga))/$pinjaman->angsuran_bulanan,
							'date_created' => date('Y-m-d H:i:s'),
							'idpinjaman' => $pinjaman->idpinjaman
						];

						$this->m_cicilan->insertCicilan($dataset_cicilan);
						
					}elseif ($cek_cicilan == ($pinjaman->angsuran_bulanan - 1)) {

						$bunga = $this->m_param->getParamById(9)[0]->nilai/100;

						$dataset_cicilan = [
							'nominal' => ($pinjaman->nominal/$pinjaman->angsuran_bulanan),
							'bunga' => ($pinjaman->nominal*($pinjaman->angsuran_bulanan*$bunga))/$pinjaman->angsuran_bulanan,
							'date_created' => date('Y-m-d H:i:s'),
							'idpinjaman' => $pinjaman->idpinjaman
						];

						$this->m_cicilan->insertCicilan($dataset_cicilan);

						$status_pinjaman = ['status' => 6];
						$this->m_pinjaman->updatePinjaman($idpinjaman, $status_pinjaman);

					}elseif ($cek_cicilan != 0 && $cek_cicilan < $pinjaman->angsuran_bulanan) {

						$bunga = $this->m_param->getParamById(9)[0]->nilai/100;

						$dataset_cicilan = [
							'nominal' => ($pinjaman->nominal/$pinjaman->angsuran_bulanan),
							'bunga' => ($pinjaman->nominal*($pinjaman->angsuran_bulanan*$bunga))/$pinjaman->angsuran_bulanan,
							'date_created' => date('Y-m-d H:i:s'),
							'idpinjaman' => $pinjaman->idpinjaman
						];

						$this->m_cicilan->insertCicilan($dataset_cicilan);
					}
				}
			}
		}

		//////////////////////////////////////////////////////////////////////////////////////
		$monthly_rep = $this->m_monthly_report->getMonthlyReportByDate(date('Y-m-d'))[0];
		$get_date = $monthly_rep->date_monthly;

		$month = date('m', strtotime($get_date));
		$user_list = $this->m_monthly_report->getAllIdAnggotaAktif();

		$data_excel = [
			'page_title' => 'Ekspor',
			'usr_list' => $user_list,
			'datetime' => $get_date
		];
		
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
		$spreadsheet = $reader->loadFromString(view('admin/report/export-monthly-report', $data_excel));

		$filename = 'monthly_report_' . date('Ymd') . '.xlsx';
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save(ROOTPATH . 'public/uploads/report/' . $filename);

		$update_report = ['file' => $filename];
		$this->m_monthly_report->updateMonthlyReport($monthly_rep->idreportm, $update_report);

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

	public function generate_deposit_member()
	{
		$report = $this->m_deposit->getDepositMemberReport();
		$data = ['report' => $report];
		echo view('admin/report/reporting-deposit-member', $data);
	}

	public function generate_loan_member()
	{
		$report = $this->m_pinjaman->getAllPinjamanMember();
		$data = ['report' => $report];
		echo view('admin/report/reporting-loan-member', $data);
	}

	public function generate_loan_deposit_member()
	{
		$report = $this->m_user->getUserLoanDeposit();
		$data = ['report' => $report];
		echo view('admin/report/reporting-loan-deposit-member', $data);
	}
	
}