<?php 
namespace App\Controllers\Bendahara;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_monthly_report;
use App\Models\M_pinjaman;

use App\Controllers\Bendahara\Notifications;

class Dashboard extends Controller
{
    protected $m_user, $m_deposit, $m_monthly_report, $m_pinjaman;
    protected $notification, $account;

    function __construct()
    {
        $this->m_user = model(M_user::class);
        $this->m_deposit = model(M_deposit::class);
        $this->m_monthly_report = model(M_monthly_report::class);
        $this->m_pinjaman = model(M_pinjaman::class);

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
        $list_pinjaman = $this->m_pinjaman->getPinjamanByStatus(3);

        $data = [
            'title_meta' => view('bendahara/partials/title-meta', ['title' => 'Dashboard']),
            'page_title' => view('bendahara/partials/page-title', ['title' => 'Dashboard', 'li_1' => 'EKoperasi', 'li_2' => 'Dashboard']),
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
        
        return view('bendahara/dashboard', $data);
    }

    public function getChartData()
    {
        $range = $this->request->getGet('range') ?? '6months';
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $chartType = $this->request->getGet('type') ?? 'deposit';

        try {
            $chartData = [];
            
            switch ($chartType) {
                case 'deposit':
                    $chartData = $this->getDepositChartData($range, $startDate, $endDate);
                    break;
                case 'loan':
                    $chartData = $this->getLoanChartData($range, $startDate, $endDate);
                    break;
                case 'member':
                    $chartData = $this->getMemberChartData($range, $startDate, $endDate);
                    break;
                default:
                    $chartData = $this->getDepositChartData($range, $startDate, $endDate);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'data' => $chartData,
                'range' => $range,
                'type' => $chartType
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to fetch chart data: ' . $e->getMessage()
            ]);
        }
    }

    private function getDepositChartData($range, $startDate = null, $endDate = null)
    {
        if ($startDate && $endDate) {
            return $this->m_deposit->getDepositChartByDateRange($startDate, $endDate);
        }

        switch ($range) {
            case '3months':
                return $this->m_deposit->getDepositChartByMonths(3);
            case '6months':
                return $this->m_deposit->getDepositChartByMonths(6);
            case '12months':
                return $this->m_deposit->getDepositChartByMonths(12);
            case '2years':
                return $this->m_deposit->getDepositChartByMonths(24);
            default:
                return $this->m_deposit->getDepositChartByMonths(6);
        }
    }

    private function getLoanChartData($range, $startDate = null, $endDate = null)
    {
        if ($startDate && $endDate) {
            return $this->m_pinjaman->getLoanChartByDateRange($startDate, $endDate);
        }

        switch ($range) {
            case '3months':
                return $this->m_pinjaman->getLoanChartByMonths(3);
            case '6months':
                return $this->m_pinjaman->getLoanChartByMonths(6);
            case '12months':
                return $this->m_pinjaman->getLoanChartByMonths(12);
            case '2years':
                return $this->m_pinjaman->getLoanChartByMonths(24);
            default:
                return $this->m_pinjaman->getLoanChartByMonths(6);
        }
    }

    private function getMemberChartData($range, $startDate = null, $endDate = null)
    {
        if ($startDate && $endDate) {
            return $this->m_user->getMemberChartByDateRange($startDate, $endDate);
        }

        switch ($range) {
            case '3months':
                return $this->m_user->getMemberChartByMonths(3);
            case '6months':
                return $this->m_user->getMemberChartByMonths(6);
            case '12months':
                return $this->m_user->getMemberChartByMonths(12);
            case '2years':
                return $this->m_user->getMemberChartByMonths(24);
            default:
                return $this->m_user->getMemberChartByMonths(6);
        }
    }
}