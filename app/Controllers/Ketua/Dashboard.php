<?php 
namespace App\Controllers\Ketua;

use App\Controllers\BaseController;

use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_monthly_report;
use App\Models\M_pinjaman;

use App\Controllers\Ketua\Notifications;

class Dashboard extends BaseController
{
	protected $m_user, $m_deposit, $m_monthly_report, $m_pinjaman;
	protected $notification;
	protected $account;
	
	function __construct()
	{
		$this->m_user = model(M_user::class);
		$this->m_deposit = model(M_deposit::class);
		$this->m_monthly_report = model(M_monthly_report::class);
		$this->m_pinjaman = model(M_pinjaman::class);

		$this->notification = new Notifications();

		$config = new \Config\Encryption();
		$encrypter = \Config\Services::encrypter($config);

		$user = $this->m_user->getUserById(session()->get('iduser'));
		if (empty($user)) {
			$user = null;
		} else {
			$data = $user[0];
			
			$nik = ($data->nik != null || $data->nik != '') ? $encrypter->decrypt(base64_decode($data->nik)) : '';
			$nip = ($data->nip != null || $data->nip != '') ? $encrypter->decrypt(base64_decode($data->nip)) : '';
			$no_rek = ($data->no_rek != null || $data->no_rek != '') ? $encrypter->decrypt(base64_decode($data->no_rek)) : '';
			$nomor_telepon = ($data->nomor_telepon != null || $data->nomor_telepon != '') ? $encrypter->decrypt(base64_decode($data->nomor_telepon)) : '';
			$alamat = ($data->alamat != null || $data->alamat != '') ? $encrypter->decrypt(base64_decode($data->alamat)) : '';

			$this->account = (object) [
				'iduser' => $data->iduser,
				'username' => $data->username,
				'nik' => $nik,
				'nip' => $nip,
				'nama_lengkap' => $data->nama_lengkap,
				'tempat_lahir' => $data->tempat_lahir,
				'tanggal_lahir' => $data->tanggal_lahir,
				'status_pegawai' => $data->status_pegawai,
				'nama_bank' => $data->nama_bank,
				'no_rek' => $no_rek,
				'alamat' => $alamat,
				'instansi' => $data->instansi,
				'unit_kerja' => $data->unit_kerja,
				'nomor_telepon' => $nomor_telepon,
				'email' => $data->email,
				'profil_pic' => $data->profil_pic,
				'user_created' => $data->user_created,
				'user_updated' => $data->user_updated,
				'closebook_request' => $data->closebook_request,
				'closebook_request_date' => $data->closebook_request_date,
				'closebook_last_updated' => $data->closebook_last_updated,
				'closebook_param_count' => $data->closebook_param_count,
				'user_flag' => $data->user_flag,
				'idgroup' => $data->idgroup,
				'group_type' => $data->group_type,
				'group_assigned' => $data->group_assigned,
				'group_flag' => $data->group_flag
			];
		}
	}

	public function index()
	{
		$total_anggota = $this->m_user->countAnggotaAktif()[0]->hitung;
		$monthly_user = $this->m_user->countMonthlyUser()[0]->hitung;
		$uang_giat = $this->m_deposit->sumDeposit()[0]->hitung;
		$monthly_income = $this->m_monthly_report->sumMonthlyIncome()[0]->hitung;
		$monthly_outcome = $this->m_monthly_report->sumMonthlyOutcome()[0]->hitung;
		$anggota_pinjaman = $this->m_monthly_report->countMonthlyAnggotaPinjaman()[0]->hitung;
		$monthly_graph = $this->m_deposit->dashboard_getMonthlyGraphic();
		$list_pinjaman = $this->m_pinjaman->getPinjamanByStatus(3);

		$data = [
			'title_meta' => view('ketua/partials/title-meta', ['title' => 'Dashboard']),
			'page_title' => view('ketua/partials/page-title', ['title' => 'Dashboard', 'li_1' => 'EKoperasi', 'li_2' => 'Dashboard']),
			'notification_list' => $this->notification->index()['notification_list'],
			'notification_badges' => $this->notification->index()['notification_badges'],
			'total_anggota' => $total_anggota,
			'monthly_user' => $monthly_user,
			'uang_giat' => $uang_giat,
			'monthly_income' => $monthly_income,
			'monthly_outcome' => $monthly_outcome,
			'anggota_pinjaman' => $anggota_pinjaman,
			'monthly_graph' => $monthly_graph,
			'list_pinjaman' => $list_pinjaman,
			'duser' => $this->account
		];
		
		return view('ketua/dashboard', $data);
	}
}