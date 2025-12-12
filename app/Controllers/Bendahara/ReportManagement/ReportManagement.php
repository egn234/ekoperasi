<?php 
namespace App\Controllers\Bendahara\ReportManagement;

use App\Models\M_monthly_report;

/**
 * ReportManagement handles report viewing and listing
 * Displays available reports and manages report interface
 */
class ReportManagement extends BaseReportController
{
    /**
     * Display report management page
     * Shows list of monthly reports and allows report selection
     */
    public function index()
    {
        $m_monthly_report = model(M_monthly_report::class);
        $list_report = $m_monthly_report->getAllMonthlyReport();
        $list_tahun = $m_monthly_report->select('YEAR(created) AS tahun')
            ->groupBy('YEAR(created)')
            ->orderBy('YEAR(created)', 'DESC')
            ->get()
            ->getResult();
        
        $YEAR = date('Y');
        $MONTH = date('m');
        $cek_report = $m_monthly_report->where('date_monthly', $YEAR.'-'.$MONTH)->countAllResults();
        
        $data = [
            'title_meta' => view('bendahara/partials/title-meta', ['title' => 'Reporting']),
            'page_title' => view('bendahara/partials/page-title', ['title' => 'Dashboard', 'li_1' => 'EKoperasi', 'li_2' => 'Report']),
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account,
            'list_report' => $list_report,
            'list_tahun' => $list_tahun,
            'cek_report' => $cek_report
        ];
        
        return view('bendahara/report/reporting-page', $data);
    }
}
