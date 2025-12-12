<?php

namespace App\Controllers\Ketua\ReportManagement;

use CodeIgniter\Controller;
use App\Models\M_user;
use App\Controllers\Ketua\Core\Notifications;

/**
 * Base controller for report management
 * Provides shared functionality for all report-related controllers
 */
abstract class BaseReportController extends Controller
{
    protected $account;
    protected $notification;

    public function __construct()
    {
        $m_user = model(M_user::class);
        $this->notification = new Notifications();
        $this->account = $m_user->getUserById(session()->get('iduser'))[0];
    }

    /**
     * Get base view data (common data for all views)
     */
    protected function getBaseViewData(string $title, string $subtitle = 'Report'): array
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
}
