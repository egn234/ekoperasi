<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_user;


class test_field extends Controller
{
	public function index()
	{
		$m_user = new M_user();
		$datauser = $m_user->where('iduser', 69)
								 ->get()
								 ->getResult()[0]
								 ->status_pegawai;
		print_r($datauser);
	}
}
?>