<?php 
namespace App\Controllers\Bendahara\LoanManagement;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_pinjaman;
use App\Models\M_cicilan;
use App\Models\M_param;
use App\Models\M_notification;
use App\Models\M_asuransi;

use App\Controllers\Bendahara\Core\Notifications;

/**
 * BaseLoanController provides common functionality for loan management
 * Shared methods for loan applications and settlements
 */
class BaseLoanController extends Controller
{
    protected $m_user, $m_pinjaman, $m_cicilan, $m_param, $m_notification, $m_asuransi;
    protected $account;
    protected $notification;

    function __construct()
    {
        $this->m_user = model(M_user::class);
        $this->m_pinjaman = model(M_pinjaman::class);
        $this->m_cicilan = model(M_cicilan::class);
        $this->m_param = model(M_param::class);
        $this->m_notification = model(M_notification::class);
        $this->m_asuransi = model(M_asuransi::class);
        $this->notification = new Notifications();
        $this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
    }

    /**
     * Send alert notification and create notification record
     */
    protected function sendAlert($anggota_id, $pinjaman_id, $message, $alert_text, $alert_status)
    {
        $notification_data = [
            'bendahara_id' => $this->account->iduser,
            'anggota_id' => $anggota_id,
            'pinjaman_id' => $pinjaman_id,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s'),
            'group_type' => 4
        ];

        $this->m_notification->insert($notification_data);
        
        $alert = view(
            'partials/notification-alert', 
            [
                'notif_text' => $alert_text,
                'status' => $alert_status
            ]
        );
        
        $data_session = ['notif' => $alert];
        session()->setFlashdata($data_session);
    }

    /**
     * Mark admin notifications as read for a specific loan
     */
    protected function markAdminNotificationsRead($pinjaman_id)
    {
        $this->m_notification->where('pinjaman_id', $pinjaman_id)
            ->where('group_type', 3)
            ->set('status', 'read')
            ->update();
    }
}
