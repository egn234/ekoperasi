<?php 
namespace App\Controllers;

use CodeIgniter\Controller;

class login extends Controller
{
	public function index()
	{
		$data = [
			'title_meta' => view('partials/title-meta', ['title' => 'Login'])
		];
		return view('auth-login', $data);
	}
}