<?php 
namespace App\Controllers\Anggota\DepositManagement;

use App\Models\M_param_manasuka_log;

class DepositList extends BaseDepositController
{

    public function index()
    {
        $m_param_manasuka_log = new M_param_manasuka_log();

        $depo_list = $this->m_deposit->getDepositByUserId($this->account->iduser);

        $currentpage = $this->request->getVar('page_grup1') ? $this->request->getVar('page_grup1') : 1;

        $saldoData = $this->getSaldoData();
    
        $param_manasuka = $this->m_param_manasuka->getParamByUserId($this->account->iduser);

        if ($m_param_manasuka_log->where('idmnskparam', $param_manasuka[0]->idmnskparam)->countAllResults() != 0) {
            $mnsk_param_log = $m_param_manasuka_log->where('idmnskparam', $param_manasuka[0]->idmnskparam)
                ->limit(1)
                ->get()
                ->getResult()[0]
                ->created_at;
        } else {
            $mnsk_param_log = date('Y-m-d H:i:s', strtotime('-3 months'));
        }

        $data = array_merge(
            $this->getBaseViewData('Simpanan'),
            $saldoData,
            [
            'deposit_list2' => $this->m_deposit_pag
                ->where('idanggota', $this->account->iduser)
                ->groupStart()
                    ->where('cash_in !=', 0)
                    ->orWhere('cash_out !=', 0)
                ->groupEnd()
                ->orderBy('date_created', 'DESC')
                ->paginate(10, 'grup1'),

            'pager' => $this->m_deposit_pag->pager,
            'currentpage' => $currentpage,
            'deposit_list' => $depo_list,
            'param_manasuka_cek' => $mnsk_param_log,
            'param_manasuka' => $param_manasuka
            ]
        );
        
        return view('anggota/deposit/deposit-list', $data);
    }

    public function detail_mutasi()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $user = $this->m_deposit->getDepositById($id)[0];
            $data = ['a' => $user, 'duser' => $this->account];
            
            echo view('anggota/deposit/part-depo-mod-detail', $data);
        }
    }

    public function up_mutasi()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $user = $this->m_deposit->getDepositById($id)[0];
            $data = ['a' => $user];
            echo view('anggota/deposit/part-depo-mod-upload', $data);
        }
    }
}
