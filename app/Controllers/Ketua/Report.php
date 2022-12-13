<?php 
namespace App\Controllers\Ketua;

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
			'title_meta' => view('ketua/partials/title-meta', ['title' => 'Dashboard']),
			'page_title' => view('ketua/partials/page-title', ['title' => 'Dashboard', 'li_1' => 'EKoperasi', 'li_2' => 'Dashboard']),
			'duser' => $this->account,
			'list_report' => $list_report,
			'cek_report' => $cek_report
		];
		
		return view('ketua/report/reporting-page', $data);
	}

	public function generate_deposit_member()
	{
		$report = $this->m_deposit->getDepositMemberReport();
		$data = ['report' => $report];
		echo view('ketua/report/reporting-deposit-member', $data);
	}

	public function generate_loan_member()
	{
		$report = $this->m_pinjaman->getAllPinjamanMember();
		$data = ['report' => $report];
		echo view('ketua/report/reporting-loan-member', $data);
	}

	public function generate_loan_deposit_member()
	{
		$report = $this->m_user->getUserLoanDeposit();
		$data = ['report' => $report];
		echo view('ketua/report/reporting-loan-deposit-member', $data);
	}
	
}