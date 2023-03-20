<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_user;
use App\Models\M_pinjaman;
use App\Models\M_cicilan;
use App\Models\M_param;

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

	public function insert_cicilan()
	{
		$m_pinjaman = new M_pinjaman();
		$m_cicilan = new M_cicilan();
		$m_param = new M_param();

		$pin = $m_pinjaman->where('idpinjaman', 5)->get()->getResult()[0];
		$bunga = $m_param->where('idparameter', 9)->get()->getResult()[0]->nilai/100;
		$provisi = $m_param->where('idparameter', 5)->get()->getResult()[0]->nilai/100;

		for ($i=0; $i < 10; $i++) {
			$dataset_cicilan = [
				'nominal' => ($pin->nominal/$pin->angsuran_bulanan),
				'bunga' => ($pin->nominal*($pin->angsuran_bulanan*$bunga))/$pin->angsuran_bulanan,
				'provisi' => ($pin->nominal*($pin->angsuran_bulanan*$provisi))/$pin->angsuran_bulanan,
				'date_created' => date('Y-m-d H:i:s'),
				'idpinjaman' => $pin->idpinjaman
			];
	
			$m_cicilan->insertCicilan($dataset_cicilan);
			$provisi = 0;
		}

		return redirect()->back();
	}
}
?>