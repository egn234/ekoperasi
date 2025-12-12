<?php 
namespace App\Controllers\Bendahara\DepositManagement;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_deposit_pag;
use App\Models\M_param_manasuka;
use App\Models\M_notification;

use App\Controllers\Bendahara\Core\Notifications;

/**
 * BaseDepositController provides common functionality for deposit management
 * Shared methods for member deposits and transaction processing
 */
class BaseDepositController extends Controller
{
    protected $m_user, $m_deposit, $m_deposit_pag, $m_param_manasuka, $m_notification;
    protected $account, $notification;

    function __construct()
    {
        $this->m_user = model(M_user::class);
        $this->m_deposit = model(M_deposit::class);
        $this->m_deposit_pag = model(M_deposit_pag::class);
        $this->m_param_manasuka = model(M_param_manasuka::class);
        $this->m_notification = model(M_notification::class);

        $this->notification = new Notifications();

        $this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
    }

    /**
     * Send alert notification and create notification record
     */
    protected function sendAlert($anggota_id, $deposit_id, $message, $alert_text, $alert_status)
    {
        $notification_anggota = [
            'bendahara_id' => $this->account->iduser,
            'anggota_id' => $anggota_id,
            'deposit_id' => $deposit_id,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s'),
            'group_type' => 4
        ];

        $this->m_notification->insert($notification_anggota);
        
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
}
