<?php

namespace App\Controllers\Ketua\ReportManagement;

use App\Controllers\Ketua\ReportManagement\BaseReportController;
use App\Models\M_monthly_report;

/**
 * ReportManagement Controller
 * Handles report listing and management UI for Ketua role
 */
class ReportManagement extends BaseReportController
{
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
        
        $data = array_merge($this->getBaseViewData('Reporting', 'Report'), [
            'list_report' => $list_report,
            'list_tahun' => $list_tahun,
            'cek_report' => $cek_report
        ]);
        
        return view('ketua/report/reporting-page', $data);
    }
}
