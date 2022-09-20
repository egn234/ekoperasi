<?php 
namespace App\Controllers\Bendahara;

use CodeIgniter\Controller;
use App\Models\M_user;
use App\MOdels\M_param;

class Kelola_param extends Controller
{

	function __construct()
	{
		$this->m_user = new M_user();
		$this->m_param = new M_param();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
	}

	public function index()
	{
		$data = [
			'title_meta' => view('bendahara/partials/title-meta', ['title' => 'Dashboard']),
			'page_title' => view('bendahara/partials/page-title', ['title' => 'Dashboard', 'li_1' => 'EKoperasi', 'li_2' => 'Dashboard']),
			'duser' => $this->account
		];
		
		return view('bendahara/kelola-parameter', $data);
	}
}