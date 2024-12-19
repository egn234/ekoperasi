<?php namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\M_user;
use App\Models\M_pinjaman;
use App\Models\M_cicilan;
use App\Models\M_monthly_report;
use App\Models\M_param;
use App\Models\M_param_manasuka;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;

class test_field extends BaseController
{
	private $m_user;
	protected $m_pinjaman;
	protected $m_cicilan;
	protected $m_monthly_report;
	protected $m_param;
	protected $m_param_manasuka;

	function __construct()
	{
		$this->m_user = new M_user();
		$this->m_pinjaman = new M_pinjaman();
		$this->m_cicilan = new M_cicilan();
		$this->m_monthly_report = new M_monthly_report();
		$this->m_param = new M_param();
		$this->m_param_manasuka = new M_param_manasuka();
	}

	public function index()
	{
		$list_anggota = $this->m_user->where('flag', '1')
									 ->where('idgroup', 4)
									 ->get()
									 ->getResult();

		echo "<pre>";
		foreach ($list_anggota as $a){
			$param_manasuka = $this->m_param_manasuka->where('idanggota', $a->iduser)->get()->getResult();
			foreach($param_manasuka as $pm){
				$data_manasuka = [
					'jenis_pengajuan' => 'penyimpanan',
					'jenis_deposit' => 'manasuka',
					'cash_in' => $pm->nilai,
					'cash_out' => 0,
					'deskripsi' => 'Diambil dari potongan gaji bulanan',
					'status' => 'diterima',
					'date_created' => date('Y-m-d H:i:s'),
					'idanggota' => $a->iduser,
					'idadmin' => $a->iduser
				];	
			}
			print_r($data_manasuka);
		}

		$date_monthly = '2024-09';
		$getDay = $this->m_monthly_report->select('DAY(created) AS day')
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
		$pin = $this->m_pinjaman->where('idpinjaman', 5)->get()->getResult()[0];
		$bunga = $this->m_param->where('idparameter', 9)->get()->getResult()[0]->nilai/100;
		$provisi = $this->m_param->where('idparameter', 5)->get()->getResult()[0]->nilai/100;

		for ($i=0; $i < 10; $i++) {
			$dataset_cicilan = [
				'nominal' => ($pin->nominal/$pin->angsuran_bulanan),
				'bunga' => ($pin->nominal*($pin->angsuran_bulanan*$bunga))/$pin->angsuran_bulanan,
				'provisi' => ($pin->nominal*($pin->angsuran_bulanan*$provisi))/$pin->angsuran_bulanan,
				'date_created' => date('Y-m-d H:i:s'),
				'idpinjaman' => $pin->idpinjaman
			];
	
			$this->m_cicilan->insertCicilan($dataset_cicilan);
			$provisi = 0;
		}

		return redirect()->back();
	}

	public function gen_sisa_cicilan()
	{
		$YEAR = date('Y');
		$MONTH = date('m');

		// $setDay = $this->m_param->where('idparameter', 8)->get()->getResult()[0]->nilai+1;
		// $endDate = date('Y-m-').$setDay;
		// $startDate = date('Y-m-d', strtotime('-1 month', strtotime($endDate)));

		$startDate = $this->m_monthly_report->where('idreportm', 3)->get()->getResult()[0]->created;
		$endDate = date('Y-m-d H:i:s');
		// echo "Start: ".$startDate.", End: ".$endDate;

		$list_anggota = $this->m_user->where('flag', '1')
									 ->where('idgroup', 4)
									 ->get()
									 ->getResult();

		//LOOPING LIST ANGGOTA
		foreach ($list_anggota as $a){
									 
			//PENGECEKAN PINJAMAN
			$cek_pinjaman = $this->m_pinjaman->where('status', 4)
												->where('idanggota', $a->iduser)
												->countAllResults();
			if ($cek_pinjaman != 0) {
				
				$pinjaman = $this->m_pinjaman->where('status', 4)
												->where('idanggota', $a->iduser)
												->orderBy('date_updated', 'DESC')
												->get()
												->getResult();
				//LOOP PINJAMAN
				foreach($pinjaman as $pin){

					//CEK VALIDASI CICILAN BULAN INI
					$validasi_cicilan = $this->m_cicilan->where('idpinjaman', $pin->idpinjaman)
														->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
														->countAllResults();
					if ($validasi_cicilan == 0) {
						//CEK CICILAN
						$cek_cicilan = $this->m_cicilan->where('idpinjaman', $pin->idpinjaman)
														->countAllResults();
						if ($cek_cicilan == 0) {

							$bunga = $this->m_param->where('idparameter', 9)->get()->getResult()[0]->nilai/100;
							$provisi = $this->m_param->where('idparameter', 5)->get()->getResult()[0]->nilai/100;

							$dataset_cicilan = [
								'nominal' => ($pin->nominal/$pin->angsuran_bulanan),
								'bunga' => ($pin->nominal*($pin->angsuran_bulanan*$bunga))/$pin->angsuran_bulanan,
								'provisi' => ($pin->nominal*($pin->angsuran_bulanan*$provisi))/$pin->angsuran_bulanan,
								'date_created' => date('Y-m-d H:i:s'),
								'idpinjaman' => $pin->idpinjaman
							];

							$this->m_cicilan->insertCicilan($dataset_cicilan);
							
						}elseif ($cek_cicilan == ($pin->angsuran_bulanan - 1)) {

							$bunga = $this->m_param->where('idparameter', 9)->get()->getResult()[0]->nilai/100;

							$dataset_cicilan = [
								'nominal' => ($pin->nominal/$pin->angsuran_bulanan),
								'bunga' => ($pin->nominal*($pin->angsuran_bulanan*$bunga))/$pin->angsuran_bulanan,
								'date_created' => date('Y-m-d H:i:s'),
								'idpinjaman' => $pin->idpinjaman
							];

							$this->m_cicilan->insertCicilan($dataset_cicilan);

							$status_pinjaman = ['status' => 5];
							$this->m_pinjaman->updatePinjaman($pin->idpinjaman, $status_pinjaman);

						}elseif ($cek_cicilan != 0 && $cek_cicilan < $pin->angsuran_bulanan) {

							$bunga = $this->m_param->where('idparameter', 9)->get()->getResult()[0]->nilai/100;

							$dataset_cicilan = [
								'nominal' => ($pin->nominal/$pin->angsuran_bulanan),
								'bunga' => ($pin->nominal*($pin->angsuran_bulanan*$bunga))/$pin->angsuran_bulanan,
								'date_created' => date('Y-m-d H:i:s'),
								'idpinjaman' => $pin->idpinjaman
							];

							$this->m_cicilan->insertCicilan($dataset_cicilan);
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

	public function encryption_meth()
	{
		$config = new \Config\Encryption();
		$encrypter = \Config\Services::encrypter($config);
		
		$users = $this->m_user->getAllUser();

		foreach ($users as $user) {
			// echo "<pre>";
			// echo $user->nip . "<br>";
			// echo $user->nik . "<br>";
			// echo $user->no_rek . "<br>";
			// echo "</pre>";
			// Increase execution time and memory limit
			ini_set('max_execution_time', 600); // 10 minutes
			ini_set('memory_limit', '512M'); // 512MB

			if ($user->nip != null) {
				$decrypted_nip = $encrypter->decrypt(base64_decode($user->nip));
			} else {
				$decrypted_nip = null;
			}
	
			if ($user->nik != null) {
				$decrypted_nik = $encrypter->decrypt(base64_decode($user->nik));
			} else {	
				$decrypted_nik = null;
			}
	
			if ($user->no_rek != null) {
				$decrypted_no_rek = $encrypter->decrypt(base64_decode($user->no_rek));
			} else {
				$decrypted_no_rek = null;
			}
	
			if ($user->alamat != null) {
				$decrypted_alamat = $encrypter->decrypt(base64_decode($user->alamat));
			} else {
				$decrypted_alamat = null;
			}
	
			if ($user->nomor_telepon != null) {
				$decrypted_nomor_telepon = $encrypter->decrypt(base64_decode($user->nomor_telepon));
			} else {
				$decrypted_nomor_telepon = null;
			}

			if (password_verify(md5('admingiat123'), $user->pass)) {
				echo "success";
			} else{
				echo "failed";
			}
			
			echo "<br>";
			echo $decrypted_nip. "<br>";
			echo $decrypted_nik. "<br>";
			echo $decrypted_no_rek . "<br>";
			echo $decrypted_alamat . "<br>";
			echo $decrypted_nomor_telepon . "<br>";
			echo $user->pass;
		}
	}

	public function binary_search()
	{
		$nik = (string) "6ijaGegAhgSc6mEIJccOOsVz9+rslOw7hQrwsPSxO4TBxZAb5XsbXirryWK7+8g+NI+ECvTEed9ASoh1DshCnOawJnWBIVpgjnh73iv3Q5RUOuF1zyTzP5G4YYlLryjA";
		$cek_nik = $this->m_user->select('nik')->where('nik = "'. $nik.'"')->get()->getResult();

		print_r($cek_nik);
	}

	public function convert_sensitive_data()
	{
		// Increase execution time and memory limit
		ini_set('max_execution_time', 600); // 10 minutes
		ini_set('memory_limit', '512M'); // 512MB

		$config = new \Config\Encryption();
		$encrypter = \Config\Services::encrypter($config);
		$users = $this->m_user->getAllUser();

		foreach ($users as $user) {
			if ($user->nip != null) {
				$encrypted_nip = base64_encode($encrypter->encrypt($user->nip));
			} else {
				$encrypted_nip = null;
			}

			if ($user->nik != null) {
				$encrypted_nik = base64_encode($encrypter->encrypt($user->nik));
			} else {
				$encrypted_nik = null;
			}

			if ($user->no_rek != null){
				$encrypted_no_rek = base64_encode($encrypter->encrypt($user->no_rek));
			} else {
				$encrypted_no_rek = null;
			}

			if ($user->nomor_telepon != null) {
				$encrypted_nomor_telepon = base64_encode($encrypter->encrypt($user->nomor_telepon));
			} else {
				$encrypted_nomor_telepon = null;
			}

			if ($user->alamat != null) {
				$encrypted_alamat = base64_encode($encrypter->encrypt($user->alamat));
			} else {
				$encrypted_alamat = null;
			}

			# THIS IS FROM MD5 ALGORITHM FIRST!! MAKE SURE TO USE MD5 BEFORE HASH CHECK!!
			$hashed_password = password_hash($user->pass, PASSWORD_DEFAULT);
			
			$dataset = [
				'nip' => $encrypted_nip,
				'nik' => $encrypted_nik,
				'no_rek' => $encrypted_no_rek,
				'nomor_telepon' => $encrypted_nomor_telepon,
				'alamat' => $encrypted_alamat,
				'pass' => $hashed_password
			];

			$this->m_user->updateUser($user->iduser, $dataset);
		}
	}
}