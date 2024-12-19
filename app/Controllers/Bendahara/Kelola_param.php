<?php 
namespace App\Controllers\Bendahara;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_param;
use App\Models\M_param_hist;

use App\Controllers\Bendahara\Notifications;

class Kelola_param extends Controller
{
	protected $m_user, $m_param, $m_param_hist;
	protected $notification, $account;

	function __construct()
	{
		$this->m_user = model(M_user::class);
		$this->m_param = model(M_param::class);
		$this->m_param_hist = model(M_param_hist::class);
		$this->notification = model(Notifications::class);
		
		$config = new \Config\Encryption();
		$encrypter = \Config\Services::encrypter($config);

		$data = $this->m_user->getUserById(session()->get('iduser'))[0];
		
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

	public function index()
	{
		$param_simp = $this->m_param->getParamSimp();
		$param_other = $this->m_param->getParamOther();

		$data = [
			'title_meta' => view('bendahara/partials/title-meta', ['title' => 'Set Parameter']),
			'page_title' => view('bendahara/partials/page-title', ['title' => 'Parameter', 'li_1' => 'EKoperasi', 'li_2' => 'Parameter']),
			'notification_list' => $this->notification->index()['notification_list'],
			'notification_badges' => $this->notification->index()['notification_badges'],
			'duser' => $this->account,
			'param_simp' => $param_simp,
			'param_other' => $param_other
		];
		
		return view('bendahara/param/set-parameter', $data);
	}

	public function set_param_simp()
	{
		$count_param = count($this->m_param->getParamSimp());
		$idparameter = $_POST['param_id'];
		$nilai =  $_POST['param_nilai_simp'];

		for ($i = 0; $i < $count_param; $i++){
			
			$temp = $this->m_param->getParamById($idparameter[$i])[0];

			if ($temp->nilai != $nilai[$i]) {
				$history = [
					'parameter' => $temp->parameter,
					'nilai' => $temp->nilai,
					'deskripsi' => $temp->deskripsi,
					'update_date' => date('Y-m-d H:i:s'),
					'idparameter' => $temp->idparameter
				];
				
				$this->m_param_hist->insertParamHist($history);
				$this->m_param->updateParamSimp($idparameter[$i], $nilai[$i], date('Y-m-d H:i:s'));
			}
		}

		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'Parameter Simpanan berhasil diperbaharui',
			 	'status' => 'success'
			]
		);
		
		$dataset = ['notif_simp' => $alert];
		session()->setFlashdata($dataset);
		return redirect()->to('bendahara/parameter');
	}

	public function set_param_other()
	{
		$count_param = count($this->m_param->getParamOther());
		$idparameter = $_POST['param_id'];
		$nilai =  $_POST['param_nilai_oth'];

		for ($i = 0; $i < $count_param; $i++){

			$temp = $this->m_param->getParamById($idparameter[$i])[0];

			if ($temp->nilai != $nilai[$i]) {
				$history = [
					'parameter' => $temp->parameter,
					'nilai' => $temp->nilai,
					'deskripsi' => $temp->deskripsi,
					'update_date' => date('Y-m-d H:i:s'),
					'idparameter' => $temp->idparameter
				];
				
				$this->m_param_hist->insertParamHist($history);
				$this->m_param->updateParamSimp($idparameter[$i], $nilai[$i], date('Y-m-d H:i:s'));
			}
		}

		$alert = view(
			'partials/notification-alert', 
			[
				'notif_text' => 'Parameter Lainnya berhasil diperbaharui',
			 	'status' => 'success'
			]
		);
		
		$dataset = ['notif_oth' => $alert];
		session()->setFlashdata($dataset);
		return redirect()->to('bendahara/parameter');
	}
}