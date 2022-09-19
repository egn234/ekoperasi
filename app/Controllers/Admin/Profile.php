<?php 

namespace App\Controllers\Admin;

use CodeIgniter\Controller;
use App\Models\M_user;
use App\Models\M_group;

class Profile extends Controller
{

	function __construct()
	{
		$this->m_user = new M_user();
		$this->m_group = new M_group();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
	}

	public function index()
	{
		$data = [
			'title_meta' => view('admin/partials/title-meta', ['title' => 'Profile']),
			'page_title' => view('admin/partials/page-title', ['title' => 'Profile', 'li_1' => 'EKoperasi', 'li_2' => 'Profile']),
			'duser' => $this->account
		];
		
		return view('admin/prof/detail', $data);
	}
}