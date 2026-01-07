<?php

namespace App\Controllers\Anggota\DepositManagement;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_deposit_pag;
use App\Models\M_param_manasuka;
use App\Models\M_param_manasuka_log;
use App\Models\M_notification;

use App\Controllers\Anggota\Core\Notifications;

/**
 * Base Controller untuk Deposit Management (Anggota)
 * Menyediakan shared functionality untuk semua deposit controllers
 */
abstract class BaseDepositController extends Controller
{
    protected $m_user;
    protected $m_deposit;
    protected $m_deposit_pag;
    protected $m_param_manasuka;
    protected $m_notification;
    protected $notification;
    protected $account;

    public function __construct()
    {
        $this->m_user = model(M_user::class);
        $this->m_deposit = model(M_deposit::class);
        $this->m_deposit_pag = model(M_deposit_pag::class);
        $this->m_param_manasuka = model(M_param_manasuka::class);
        $this->m_notification = model(M_notification::class);

        $this->notification = new Notifications();

        $user = $this->m_user->getUserById(session()->get('iduser'));
        $this->account = !empty($user) ? $user[0] : null;
    }

    /**
     * Get common view data
     */
    protected function getBaseViewData(string $title, string $subtitle = 'Simpanan'): array
    {
        return [
            'title' => $title,
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account
        ];
    }

    /**
     * Send notification alert
     */
    protected function sendAlert(string $message, string $status = 'success'): void
    {
        $alert = view('partials/notification-alert', [
            'notif_text' => $message,
            'status' => $status
        ]);

        session()->setFlashdata('notif', $alert);
    }

    /**
     * Create notification for deposit action
     */
    protected function createNotification(?int $depositId, ?int $parameterId, string $message, int $groupType): void
    {
        $notificationData = [
            'anggota_id' => $this->account->iduser,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s'),
            'group_type' => $groupType
        ];

        if ($depositId !== null) {
            $notificationData['deposit_id'] = $depositId;
        }

        if ($parameterId !== null) {
            $notificationData['parameter_id'] = $parameterId;
        }

        $this->m_notification->insert($notificationData);
    }

    /**
     * Get saldo data for user
     */
    protected function getSaldoData(): array
    {
        return [
            'total_saldo_wajib' => $this->m_deposit->getSaldoWajibByUserId($this->account->iduser)[0]->saldo,
            'total_saldo_pokok' => $this->m_deposit->getSaldoPokokByUserId($this->account->iduser)[0]->saldo,
            'total_saldo_manasuka' => $this->m_deposit->getSaldoManasukaByUserId($this->account->iduser)[0]->saldo
        ];
    }
}
