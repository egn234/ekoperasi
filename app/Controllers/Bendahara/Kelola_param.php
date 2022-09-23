<?php 
namespace App\Controllers\Bendahara;

use CodeIgniter\Controller;
use App\Models\M_user;
use App\Models\M_param;
use App\Models\M_param_hist;

class Kelola_param extends Controller
{

	function __construct()
	{
		$this->m_user = new M_user();
		$this->m_param = new M_param();
		$this->m_param_hist = new M_param_hist();
		$this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
	}

	public function index()
	{
		$param_simp = $this->m_param->getParamSimp();
		$param_other = $this->m_param->getParamOther();

		$data = [
			'title_meta' => view('bendahara/partials/title-meta', ['title' => 'Set Parameter']),
			'page_title' => view('bendahara/partials/page-title', ['title' => 'Parameter', 'li_1' => 'EKoperasi', 'li_2' => 'Parameter']),
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