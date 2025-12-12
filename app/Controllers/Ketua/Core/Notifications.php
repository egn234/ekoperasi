<?php
namespace App\Controllers\Ketua\Core;

use App\Controllers\BaseController;

use App\Models\M_user;
use App\Models\M_notification;

/**
 * Notifications Controller
 * Handles notification operations for Ketua role
 */
class Notifications extends BaseController
{
    protected $m_user, $m_notification;
    protected $account;

    function __construct(){
        $this->m_user = model(M_user::class);
        $this->m_notification = model(M_notification::class);
        $this->account = $this->m_user->getUserById(session()->get('iduser'));
    }

    public function index()
    {
        $notification_list = $this->m_notification->where('group_type', '3')
            ->orderBy('timestamp', 'DESC')
            ->get()
            ->getResult();

        $notification_badges = $this->m_notification->where('group_type', '3')
            ->where('status', 'unread')
            ->get()
            ->getResult();
        
        $notification = [
            'notification_list' => $notification_list,
            'notification_badges' => count($notification_badges)
        ];
        
        return $notification;
    }

    public function mark_all_read()
    {
        $this->m_notification->where('group_type', '3')
            ->where('status', 'unread')
            ->set('status', 'read')
            ->update();

        return redirect()->back();
    }

    public function mark_as_read()
    {
        if ($_POST['id']) {
            $this->m_notification->where('id', $_POST['id'])
                ->set('status', 'read')
                ->update();
        }
    }
}
