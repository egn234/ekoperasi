<?php 
namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\M_user;

class Dashboard extends Controller
{

	function __construct()
	{
		$this->m_user = new M_user();
	}

	public function index()
	{
		$account = $this->m_user->getUserById(session()->get('iduser'))[0];
		$data = [
			'title_meta' => view('admin/partials/title-meta', ['title' => 'Dashboard']),
			'page_title' => view('admin/partials/page-title', ['title' => 'Dashboard', 'li_1' => 'EKoperasi', 'li_2' => 'Dashboard']),
			'duser' => $account
		];
		
		return view('admin/dashboard', $data);
	}
}