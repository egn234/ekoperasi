<?php
namespace App\Controllers\Admin;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_notification;

class Notifications extends Controller
{
    protected $m_user, $m_notification;
    protected $account;

    function __construct(){
        $this->m_user = model(M_user::class);
        $this->m_notification = new M_notification();
        $this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
    }

    public function index()
    {
        $notification_list = $this->m_notification->where('group_type', '1')
            ->orderBy('timestamp', 'DESC')
            ->get()
            ->getResult();

        $notification_badges = $this->m_notification->where('group_type', '1')
            ->where('status', 'unread')
            ->get()
            ->getResult();
        
        $notification = [
            'notification_list' => $notification_list,
            'notification_badges' => count($notification_badges)
        ];
        
        return $notification;
    }

    public function notification_list()
    {
        $perPage = 10;
        $page = request()->getVar('page') ?? 1;

        $notifications = $this->m_notification
            ->where('group_type', '1') // ganti sesuai role
            ->orderBy('timestamp', 'DESC')
            ->paginate($perPage);

        $pager = $this->m_notification->pager;

        $unread_count = $this->m_notification
            ->where('group_type', '1')
            ->where('status', 'unread')
            ->countAllResults();

        $data = [
            'title_meta' => view('admin/partials/title-meta', ['title' => 'Pinjaman']),
            'page_title' => view('admin/partials/page-title', ['title' => 'Pinjaman', 'li_1' => 'EKoperasi', 'li_2' => 'Pinjaman']),
            'notification_list' => $this->index()['notification_list'],
            'notification_badges' => $this->index()['notification_badges'],
            'daftar_notif' => $notifications,
            'pager' => $pager,
            'badge_notif' => $unread_count,
            'duser' => $this->account
        ];
        
        return view('admin/notifikasi/list-notif', $data);
    }

    public function mark_all_read_table()
    {
        $this->m_notification->where('group_type', '1')
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

    public function mark_all_read()
    {
        $this->m_notification->where('group_type', '1')
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