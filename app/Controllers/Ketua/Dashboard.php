<?php 
namespace App\Controllers\Ketua;

use CodeIgniter\Controller;
use App\Models\M_user;

class Dashboard extends Controller
{

	function __construct()
	{
		$this->m_user = new M_user();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
	}

	public function index()
	{
		$data = [
			'title_meta' => view('ketua/partials/title-meta', ['title' => 'Dashboard']),
			'page_title' => view('ketua/partials/page-title', ['title' => 'Dashboard', 'li_1' => 'EKoperasi', 'li_2' => 'Dashboard']),
			'duser' => $this->account
		];
		
		return view('ketua/dashboard', $data);
	}
}