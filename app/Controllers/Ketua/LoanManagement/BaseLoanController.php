<?php

namespace App\Controllers\Ketua\LoanManagement;

use CodeIgniter\Controller;
use App\Models\M_user;
use App\Models\M_pinjaman;
use App\Models\M_cicilan;
use App\Models\M_param;
use App\Models\M_notification;
use App\Models\M_asuransi;
use App\Controllers\Ketua\Core\Notifications;

/**
 * Base controller for loan management
 * Provides shared functionality for all loan-related controllers
 */
abstract class BaseLoanController extends Controller
{
    protected $m_user;
    protected $m_pinjaman;
    protected $m_cicilan;
    protected $m_param;
    protected $m_notification;
    protected $m_asuransi;
    protected $notification;
    protected $account;

    public function __construct()
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
     * Get base view data (common data for all views)
     */
    protected function getBaseViewData(string $title, string $subtitle = 'Pinjaman'): array
    {
        return [
            'title_meta' => view('ketua/partials/title-meta', ['title' => $title]),
            'page_title' => view('ketua/partials/page-title', [
                'title' => $title,
                'li_1' => 'EKoperasi',
                'li_2' => $subtitle
            ]),
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account
        ];
    }

    /**
     * Send alert message via flashdata
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
     * Create notification for a loan action
     */
    protected function createNotification(int $anggotaId, int $pinjamanId, string $message, int $groupType): void
    {
        $notificationData = [
            'ketua_id' => $this->account->iduser,
            'anggota_id' => $anggotaId,
            'pinjaman_id' => $pinjamanId,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s'),
            'group_type' => $groupType
        ];

        $this->m_notification->insert($notificationData);
    }
}
