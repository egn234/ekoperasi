<?php 
namespace App\Controllers\Bendahara\ReportManagement;

require_once ROOTPATH.'vendor/autoload.php';

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_monthly_report;
use App\Models\M_param;
use App\Models\M_cicilan;
use App\Models\M_pinjaman;

use App\Controllers\Bendahara\Core\Notifications;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * BaseReportController provides common functionality for report management
 * Shared methods and dependencies for report generation
 */
class BaseReportController extends Controller
{
    protected $m_user;
    protected $notification, $account;

    function __construct()
    {
        $this->m_user = new M_user();
        $this->notification = new Notifications();
        $this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
    }

    /**
     * Send flash alert message
     */
    protected function sendAlert($notif_key, $notif_text, $status)
    {
        $alert = view(
            'partials/notification-alert', 
            [
                'notif_text' => $notif_text,
                'status' => $status
            ]
        );
        
        $dataset_notif = [$notif_key => $alert];
        session()->setFlashdata($dataset_notif);
    }
}
