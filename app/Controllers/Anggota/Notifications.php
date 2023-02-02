<?php
namespace App\Controllers\Anggota;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_notification;

class Notifications extends Controller
{

	function __construct(){
		$this->m_user = new M_user();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
		$this->m_notification = new M_notification();
	}

	public function index()
	{
		$notification_list = $this->m_notification->where('anggota_id', $this->account->iduser)
											->orderBy('timestamp', 'DESC')
											->where('group_type', '4')
											->get()
											->getResult();

		$notification_badges = $this->m_notification->where('anggota_id', $this->account->iduser)
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
}