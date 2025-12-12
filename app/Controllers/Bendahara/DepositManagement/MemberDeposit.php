<?php 
namespace App\Controllers\Bendahara\DepositManagement;

/**
 * MemberDeposit handles member-specific deposit views and details
 * Manages member list, individual deposit details, and member data retrieval
 */
class MemberDeposit extends BaseDepositController
{
    /**
     * Display member list page
     */
    public function index()
    {
        $data = [
            'title_meta' => view('bendahara/partials/title-meta', ['title' => 'Kelola Anggota']),
            'page_title' => view('bendahara/partials/page-title', ['title' => 'Kelola Anggota', 'li_1' => 'EKoperasi', 'li_2' => 'Kelola Anggota']),
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account
        ];
        
        return view('bendahara/deposit/anggota-list', $data);
    }

    /**
     * Display detailed deposit information for a specific member
     */
    public function detail_anggota($iduser = false)
    {
        $depo_list = $this->m_deposit->getDepositByUserId($iduser);
        $total_saldo_wajib = $this->m_deposit->getSaldoWajibByUserId($iduser)[0]->saldo;
        $total_saldo_pokok = $this->m_deposit->getSaldoPokokByUserId($iduser)[0]->saldo;
        $total_saldo_manasuka = $this->m_deposit->getSaldoManasukaByUserId($iduser)[0]->saldo;
        $detail_user = $this->m_user->getUserById($iduser)[0];
        $param_manasuka = $this->m_param_manasuka->getParamByUserId($iduser);
        $currentpage = request()->getVar('page_grup1') ? request()->getVar('page_grup1') : 1;
        $deposit_list2 = $this->m_deposit_pag
            ->where('idanggota', $iduser)
            ->groupStart()
                ->where('cash_in !=', 0)
                ->orWhere('cash_out !=', 0)
            ->groupEnd()
            ->orderBy('date_created', 'DESC')
            ->paginate(10, 'grup1');

        $data = [
            'title_meta' => view('bendahara/partials/title-meta', ['title' => 'Detail Simpanan']),
            'page_title' => view('bendahara/partials/page-title', ['title' => 'Detail Simpanan', 'li_1' => 'EKoperasi', 'li_2' => 'Detail Simpanan']),
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account,
            'detail_user' => $detail_user,
            'deposit_list' => $depo_list,
            'total_saldo_wajib' => $total_saldo_wajib,
            'total_saldo_pokok' => $total_saldo_pokok,
            'total_saldo_manasuka' => $total_saldo_manasuka,
            'param_manasuka' => $param_manasuka,
            'deposit_list2' => $deposit_list2,
            'pager' => $this->m_deposit_pag->pager,
            'currentpage' => $currentpage,
        ];
        
        return view('bendahara/deposit/anggota-detail', $data);
    }

    /**
     * DataTable AJAX endpoint for member list
     */
    public function data_user()
    {
        $request = service('request');
        $model = $this->m_user;

        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'];

        $model->select('iduser, username, nama_lengkap, instansi, nomor_telepon, email, flag')
        ->where('idgroup', 4)
        ->groupStart()
            ->like('username', $searchValue)
            ->orLike('nama_lengkap', $searchValue)
            ->orLike('email')
        ->groupEnd();

        $data = $model->asArray()->findAll($length, $start);

        $model->select('iduser')->where('idgroup', 4);
        $recordsTotal = $model->countAllResults();

        $model->select('iduser')
        ->where('idgroup', 4)
        ->groupStart()
            ->like('username', $searchValue)
            ->orLike('nama_lengkap', $searchValue)
            ->orLike('nomor_telepon', $searchValue)
        ->groupEnd();
        
        $recordsFiltered = $model->countAllResults();

        $response = [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ];

        return $this->response->setJSON($response);
    }
}
