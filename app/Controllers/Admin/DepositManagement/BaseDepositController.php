<?php 
namespace App\Controllers\Admin\DepositManagement;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_deposit_pag;
use App\Models\M_param_manasuka;
use App\Models\M_param_manasuka_log;
use App\Models\M_notification;

use App\Controllers\Admin\Core\Notifications;

/**
 * Base Controller untuk Deposit Management
 * Menyediakan shared functionality untuk semua deposit controllers
 */
abstract class BaseDepositController extends Controller
{
    protected $m_user;
    protected $m_deposit;
    protected $m_deposit_pag;
    protected $m_param_manasuka;
    protected $m_param_manasuka_log;
    protected $m_notification;
    protected $notification;
    protected $account;

    public function __construct()
    {
        $this->m_user = model(M_user::class);
        $this->m_deposit = model(M_deposit::class);
        $this->m_deposit_pag = model(M_deposit_pag::class);
        $this->m_param_manasuka = model(M_param_manasuka::class);
        $this->m_param_manasuka_log = model(M_param_manasuka_log::class);
        $this->m_notification = model(M_notification::class);

        $this->notification = new Notifications();
        $this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
    }

    /**
     * Get common view data
     */
    protected function getBaseViewData(): array
    {
        return [
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account
        ];
    }

    /**
     * Send notification alert
     */
    protected function sendAlert(string $message, string $status = 'success')
    {
        $alert = view('partials/notification-alert', [
            'notif_text' => $message,
            'status' => $status
        ]);
        
        session()->setFlashdata('notif', $alert);
    }

    /**
     * Create notification
     */
    protected function createNotification(int $adminId, int $anggotaId, ?int $depositId, string $message, int $groupType)
    {
        $this->m_notification->insert([
            'admin_id' => $adminId,
            'anggota_id' => $anggotaId,
            'deposit_id' => $depositId,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s'),
            'group_type' => $groupType
        ]);
    }
}
