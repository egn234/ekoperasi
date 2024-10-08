<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_user;
use App\Models\M_pinjaman;
use App\Models\M_cicilan;
use App\Models\M_monthly_report;
use App\Models\M_param;
use App\Models\M_param_manasuka;

class test_field extends Controller
{
	public function index()
	{
		$m_user = new M_user();
		$m_param_manasuka = new M_param_manasuka();
		$m_monthly_report = new M_monthly_report();
		$list_anggota = $m_user->where('flag', '1')
									 ->where('idgroup', 4)
									 ->get()
									 ->getResult();

		// echo "<pre>";
		// foreach ($list_anggota as $a){
		// 	$param_manasuka = $m_param_manasuka->where('idanggota', $a->iduser)->get()->getResult();
		// 	foreach($param_manasuka as $pm){
		// 		$data_manasuka = [
		// 			'jenis_pengajuan' => 'penyimpanan',
		// 			'jenis_deposit' => 'manasuka',
		// 			'cash_in' => $pm->nilai,
		// 			'cash_out' => 0,
		// 			'deskripsi' => 'Diambil dari potongan gaji bulanan',
		// 			'status' => 'diterima',
		// 			'date_created' => date('Y-m-d H:i:s'),
		// 			'idanggota' => $a->iduser,
		// 			'idadmin' => $a->iduser
		// 		];	
		// 	}
		// 	print_r($data_manasuka);
		// }
		$date_monthly = '2024-09';
		$getDay = $m_monthly_report->select('DAY(created) AS day')
				->where('date_monthly', $date_monthly)
				->get()
				->getResult()[0]->day;

		$endDate = $date_monthly.'-'.($getDay+1);
		$startDate = date('Y-m-d', strtotime('-1 month', strtotime($endDate)));

		echo $endDate;
		echo $startDate;

		echo "</pre>";
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

	public function gen_sisa_cicilan()
	{
		$m_pinjaman = new M_pinjaman();
		$m_monthly_report = new M_monthly_report();
		$m_user = new M_user();
		$m_cicilan = new M_cicilan();
		$m_param = new M_param();

		$YEAR = date('Y');
		$MONTH = date('m');

		// $setDay = $this->m_param->where('idparameter', 8)->get()->getResult()[0]->nilai+1;
		// $endDate = date('Y-m-').$setDay;
		// $startDate = date('Y-m-d', strtotime('-1 month', strtotime($endDate)));

		$startDate = $m_monthly_report->where('idreportm', 3)->get()->getResult()[0]->created;
		$endDate = date('Y-m-d H:i:s');
		// echo "Start: ".$startDate.", End: ".$endDate;

		$list_anggota = $m_user->where('flag', '1')
									 ->where('idgroup', 4)
									 ->get()
									 ->getResult();

		//LOOPING LIST ANGGOTA
		foreach ($list_anggota as $a){
									 
			//PENGECEKAN PINJAMAN
			$cek_pinjaman = $m_pinjaman->where('status', 4)
												->where('idanggota', $a->iduser)
												->countAllResults();
			if ($cek_pinjaman != 0) {
				
				$pinjaman = $m_pinjaman->where('status', 4)
												->where('idanggota', $a->iduser)
												->orderBy('date_updated', 'DESC')
												->get()
												->getResult();
				//LOOP PINJAMAN
				foreach($pinjaman as $pin){

					//CEK VALIDASI CICILAN BULAN INI
					$validasi_cicilan = $m_cicilan->where('idpinjaman', $pin->idpinjaman)
														->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
														->countAllResults();
					if ($validasi_cicilan == 0) {
						//CEK CICILAN
						$cek_cicilan = $m_cicilan->where('idpinjaman', $pin->idpinjaman)
														->countAllResults();
						if ($cek_cicilan == 0) {

							$bunga = $m_param->where('idparameter', 9)->get()->getResult()[0]->nilai/100;
							$provisi = $m_param->where('idparameter', 5)->get()->getResult()[0]->nilai/100;

							$dataset_cicilan = [
								'nominal' => ($pin->nominal/$pin->angsuran_bulanan),
								'bunga' => ($pin->nominal*($pin->angsuran_bulanan*$bunga))/$pin->angsuran_bulanan,
								'provisi' => ($pin->nominal*($pin->angsuran_bulanan*$provisi))/$pin->angsuran_bulanan,
								'date_created' => date('Y-m-d H:i:s'),
								'idpinjaman' => $pin->idpinjaman
							];

							$m_cicilan->insertCicilan($dataset_cicilan);
							
						}elseif ($cek_cicilan == ($pin->angsuran_bulanan - 1)) {

							$bunga = $m_param->where('idparameter', 9)->get()->getResult()[0]->nilai/100;

							$dataset_cicilan = [
								'nominal' => ($pin->nominal/$pin->angsuran_bulanan),
								'bunga' => ($pin->nominal*($pin->angsuran_bulanan*$bunga))/$pin->angsuran_bulanan,
								'date_created' => date('Y-m-d H:i:s'),
								'idpinjaman' => $pin->idpinjaman
							];

							$m_cicilan->insertCicilan($dataset_cicilan);

							$status_pinjaman = ['status' => 5];
							$m_pinjaman->updatePinjaman($pin->idpinjaman, $status_pinjaman);

						}elseif ($cek_cicilan != 0 && $cek_cicilan < $pin->angsuran_bulanan) {

							$bunga = $m_param->where('idparameter', 9)->get()->getResult()[0]->nilai/100;

							$dataset_cicilan = [
								'nominal' => ($pin->nominal/$pin->angsuran_bulanan),
								'bunga' => ($pin->nominal*($pin->angsuran_bulanan*$bunga))/$pin->angsuran_bulanan,
								'date_created' => date('Y-m-d H:i:s'),
								'idpinjaman' => $pin->idpinjaman
							];

							$m_cicilan->insertCicilan($dataset_cicilan);
						}
					}
				}
			}
		}

		echo "success";
	}

	
	public function test_db()
	{
		try {
			$db = \Config\Database::connect();
			$builder = $db->table('tb_user');
			$query = $builder->get(1);
			$results = $query->getResult();
			if ($results) {
				echo "success";
			}
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
	}
}
?>