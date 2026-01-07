<?php

namespace App\Controllers\Anggota\Core;

use App\Controllers\BaseController;

use App\Models\M_user;
use App\Models\M_notification;

class Notifications extends BaseController
{
    protected $m_user;
    protected $account;
    protected $m_notification;

    function __construct()
    {
        $this->m_user = model(M_user::class);
        $this->m_notification = model(M_notification::class);

        $user = $this->m_user->getUserById(session()->get('iduser'));
        $this->account = !empty($user) ? $user[0] : null;
    }

    public function index()
    {
        $notification_list = $this->m_notification
            ->where('anggota_id', $this->account->iduser)
            ->orderBy('timestamp', 'DESC')
            ->where('group_type', '4')
            ->get()
            ->getResult();

        $notification_badges = $this->m_notification
            ->where('anggota_id', $this->account->iduser)
            ->where('group_type', '4')
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
        $this->m_notification->where('anggota_id', $this->account->iduser)
            ->where('group_type', '4')
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
    public function notification_list()
    {
        $perPage = 10;
        $page = request()->getVar('page') ?? 1;

        $notifications = $this->m_notification
            ->where('anggota_id', $this->account->iduser)
            ->where('group_type', '4')
            ->orderBy('timestamp', 'DESC')
            ->paginate($perPage);

        $pager = $this->m_notification->pager;

        $unread_count = $this->m_notification
            ->where('anggota_id', $this->account->iduser)
            ->where('group_type', '4')
            ->where('status', 'unread')
            ->countAllResults();

        $data = [
            'title' => 'Notifikasi',
            'notification_list' => $this->index()['notification_list'],
            'notification_badges' => $this->index()['notification_badges'],
            'daftar_notif' => $notifications,
            'pager' => $pager,
            'badge_notif' => $unread_count,
            'duser' => $this->account
        ];

        return view('anggota/notifikasi/list-notif', $data);
    }

    public function mark_all_read_table()
    {
        $this->m_notification->where('anggota_id', $this->account->iduser)
            ->where('group_type', '4')
            ->where('status', 'unread')
            ->set('status', 'read')
            ->update();

        return redirect()->back();
    }

    public function mark_as_read_table()
    {
        $id = request()->getPost('id');

        if ($id == null || $id == '') {
            return redirect()->back();
        }

        $this->m_notification->where('id', $id)
            ->set('status', 'read')
            ->update();

        return redirect()->back();
    }
}
