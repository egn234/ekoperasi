<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_user;
use App\Models\M_pinjaman;
use App\Models\M_cicilan;
use App\Models\M_monthly_report;
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
}
?>