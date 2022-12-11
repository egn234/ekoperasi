<?php 
namespace App\Controllers\Bendahara;

use CodeIgniter\Controller;
use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_monthly_report;

class Dashboard extends Controller
{

	function __construct()
	{
		$this->m_user = new M_user();
		$this->m_deposit = new M_deposit();
		$this->m_monthly_report = new M_monthly_report();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
	}

	public function index()
	{

		$total_anggota = $this->m_user->countAnggotaAktif()[0]->hitung;
		$monthly_user = $this->m_user->countMonthlyUser()[0]->hitung;
		$uang_giat = $this->m_deposit->sumDeposit()[0]->hitung;
		$monthly_income = $this->m_monthly_report->sumMonthlyIncome()[0]->hitung;
		$monthly_outcome = $this->m_monthly_report->sumMonthlyOutcome()[0]->hitung;
		$anggota_pinjaman = $this->m_monthly_report->countMonthlyAnggotaPinjaman()[0]->hitung;

		$data = [
			'title_meta' => view('bendahara/partials/title-meta', ['title' => 'Dashboard']),
			'page_title' => view('bendahara/partials/page-title', ['title' => 'Dashboard', 'li_1' => 'EKoperasi', 'li_2' => 'Dashboard']),
			'total_anggota' => $total_anggota,
			'monthly_user' => $monthly_user,
			'uang_giat' => $uang_giat,
			'monthly_income' => $monthly_income,
			'monthly_outcome' => $monthly_outcome,
			'anggota_pinjaman' => $anggota_pinjaman,
			'duser' => $this->account
		];
		
		return view('bendahara/dashboard', $data);
	}
}