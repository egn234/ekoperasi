<?php
namespace App\Controllers\Anggota;

use App\Controllers\BaseController;

use App\Models\M_user;
use App\Models\M_notification;

class Notifications extends BaseController
{
	public function index()
	{
		$m_user = new M_user();
		$m_notification = new M_notification();

		$account = $m_user->getUserById($this->session->get('iduser'))[0];
		$notification_list = $m_notification->where('anggota_id', $account->iduser)
											->orderBy('timestamp', 'DESC')
											->where('group_type', '4')
											->get()
											->getResult();

		$notification_badges = $m_notification->where('anggota_id', $account->iduser)
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
		$m_user = new M_user();
		$m_notification = new M_notification();

		$account = $m_user->getUserById($this->session->get('iduser'))[0];
		$m_notification->where('anggota_id', $account->iduser)
							 ->where('group_type', '4')
							 ->where('status', 'unread')
							 ->set('status', 'read')
							 ->update();

		return redirect()->back();
	}

	public function mark_as_read()
	{
		$m_notification = new M_notification();
		if ($_POST['id']) {
			$m_notification->where('id', $_POST['id'])
							->set('status', 'read')
							->update();
		}
	}
}