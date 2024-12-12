<?php 
namespace App\Controllers\Admin;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_monthly_report;
use App\Models\M_pinjaman;

use App\Controllers\Admin\Notifications;

class Dashboard extends Controller
{
	protected $m_user;
	protected $m_deposit;
	protected $m_monthly_report;
	protected $m_pinjaman;
	protected $notification;
	protected $account;
	
	function __construct()
	{
		$this->m_user = new M_user();
		$this->m_deposit = new M_deposit();
		$this->m_monthly_report = new M_monthly_report();
		$this->m_pinjaman = new M_pinjaman();
		$this->notification = new Notifications();
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
		$monthly_graph = $this->m_deposit->dashboard_getMonthlyGraphic();
		$list_pinjaman = $this->m_pinjaman->getPinjamanByStatus(2);

		$data = [
			'title_meta' => view('admin/partials/title-meta', ['title' => 'Dashboard']),
			'page_title' => view('admin/partials/page-title', ['title' => 'Dashboard', 'li_1' => 'EKoperasi', 'li_2' => 'Dashboard']),
			'notification_list' => $this->notification->index()['notification_list'],
			'notification_badges' => $this->notification->index()['notification_badges'],
			'total_anggota' => $total_anggota,
			'monthly_user' => $monthly_user,
			'uang_giat' => $uang_giat,
			'monthly_income' => $monthly_income,
			'monthly_outcome' => $monthly_outcome,
			'anggota_pinjaman' => $anggota_pinjaman,
			'monthly_graph' => $monthly_graph,
			'list_pinjaman' => $list_pinjaman,
			'duser' => $this->account
		];
		
		return view('admin/dashboard', $data);
	}
}