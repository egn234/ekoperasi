<?php

namespace App\Controllers\Admin\ReportManagement;

use App\Models\M_monthly_report;
use App\Models\M_param;

/**
 * ReportManagement Controller
 * Handles report listing and UI management
 */
class ReportManagement extends BaseReportController
{
    /**
     * Display report list page
     */
    public function index()
    {
        $m_monthly_report = model(M_monthly_report::class);
        $m_param = model(M_param::class);

        $list_report = $m_monthly_report->orderBy('created', 'DESC')
            ->get()
            ->getResult();

        $list_tahun = $m_monthly_report->select('YEAR(created) AS tahun')
            ->groupBy('YEAR(created)')
            ->orderBy('YEAR(created)', 'DESC')
            ->get()
            ->getResult();
        
        $YEAR = date('Y');
        $MONTH = date('m');
        $getDay = $m_param->where('idparameter', 8)->get()->getResult()[0]->nilai;
        $cek_report = $m_monthly_report->where('date_monthly', $YEAR.'-'.$MONTH)->countAllResults();
        
        $data = $this->getBaseViewData('Reporting', 'Report');
        $data['list_report'] = $list_report;
        $data['list_tahun'] = $list_tahun;
        $data['cek_report'] = $cek_report;
        $data['getDay'] = $getDay;
        
        return view('admin/report/reporting-page', $data);
    }
}
