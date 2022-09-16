<?php 
namespace App\Controllers\Admin;

use CodeIgniter\Controller;

class Dashboard extends Controller
{
	public function index()
	{
		$data = [
			'title_meta' => view('partials/title-meta', ['title' => 'Dashboard']),
			'page_title' => view('partials/page-title', ['title' => 'Dashboard', 'li_1' => 'Minia', 'li_2' => 'Dashboard'])
		];
		
		return view('admin/user_list', $data);
	}
}