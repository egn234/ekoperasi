<?php 
namespace App\Controllers\Admin\DepositManagement;

/**
 * MemberDeposit Controller
 * 
 * Mengelola daftar anggota dan detail simpanan per anggota:
 * - List anggota
 * - Detail saldo anggota (pokok, wajib, manasuka)
 * - Riwayat transaksi anggota
 * - DataTable user untuk simpanan
 */
class MemberDeposit extends BaseDepositController
{
    /**
     * Daftar anggota
     * Route: /admin/deposit/list
     */
    public function index()
    {
        $data = array_merge($this->getBaseViewData(), [
            'title_meta' => view('admin/partials/title-meta', ['title' => 'Kelola Anggota']),
            'page_title' => view('admin/partials/page-title', [
                'title' => 'Kelola Anggota', 
                'li_1' => 'EKoperasi', 
                'li_2' => 'Kelola Anggota'
            ])
        ]);
        
        return view('admin/deposit/anggota-list', $data);
    }

    /**
     * Detail simpanan anggota
     * Route: /admin/deposit/user/{id}
     */
    public function detail_anggota($iduser = false)
    {
        if (!$iduser) {
            $this->sendAlert('ID User tidak valid', 'danger');
            return redirect()->to('admin/deposit/list');
        }

        // Get saldo information
        $total_saldo_wajib = $this->m_deposit->getSaldoWajibByUserId($iduser)[0]->saldo;
        $total_saldo_pokok = $this->m_deposit->getSaldoPokokByUserId($iduser)[0]->saldo;
        $total_saldo_manasuka = $this->m_deposit->getSaldoManasukaByUserId($iduser)[0]->saldo;
        
        // Get user detail
        $detail_user = $this->m_user->getUserById($iduser)[0];
        
        // Get manasuka parameter
        $param_manasuka = $this->m_param_manasuka->getParamByUserId($iduser) 
            ? $this->m_param_manasuka->getParamByUserId($iduser) 
            : false;
        
        $currentpage = request()->getVar('page_grup1') ? request()->getVar('page_grup1') : 1;

        // Get manasuka parameter log
        if ($param_manasuka) {
            $log_count = $this->m_param_manasuka_log
                ->select("COUNT(id) as hitung")
                ->where('idmnskparam', $param_manasuka[0]->idmnskparam)
                ->get()
                ->getResult()[0]
                ->hitung;
                
            $mnsk_param_log = $log_count != 0
                ? $this->m_param_manasuka_log
                    ->where('idmnskparam', $param_manasuka[0]->idmnskparam)
                    ->limit(1)
                    ->get()
                    ->getResult()[0]
                    ->created_at 
                : date('Y-m-d H:i:s', strtotime('-3 months'));			
        } else {
            $mnsk_param_log = date('Y-m-d H:i:s', strtotime('-3 months'));
        }

        // Get deposit history
        $deposit_list = $this->m_deposit_pag
            ->where('idanggota', $iduser)
            ->groupStart()
                ->where('cash_in !=', 0)
                ->orWhere('cash_out !=', 0)
            ->groupEnd()
            ->orderBy('date_created', 'DESC')
            ->paginate(10, 'grup1');

        $data = array_merge($this->getBaseViewData(), [
            'title_meta' => view('admin/partials/title-meta', ['title' => 'Detail Simpanan']),
            'page_title' => view('admin/partials/page-title', [
                'title' => 'Detail Simpanan', 
                'li_1' => 'EKoperasi', 
                'li_2' => 'Detail Simpanan'
            ]),
            'detail_user' => $detail_user,
            'total_saldo_wajib' => $total_saldo_wajib,
            'total_saldo_pokok' => $total_saldo_pokok,
            'total_saldo_manasuka' => $total_saldo_manasuka,
            'param_manasuka' => $param_manasuka,
            'param_manasuka_cek' => $mnsk_param_log,
            'deposit_list2' => $deposit_list,
            'pager' => $this->m_deposit_pag->pager,
            'currentpage' => $currentpage
        ]);
        
        return view('admin/deposit/anggota-detail', $data);
    }

    /**
     * Add manual deposit/withdrawal for member
     * Route: POST /admin/deposit/add_req
     */
    public function add_proc()
    {
        $iduser = request()->getPost('iduser');
        $jenis_pengajuan = request()->getPost('jenis_pengajuan');
        
        // Validation
        if (empty($jenis_pengajuan)) {
            $this->sendAlert('Gagal membuat pengajuan: Pilih jenis pengajuan terlebih dahulu', 'warning');
            return redirect()->back();
        }

        $jenis_deposit = 'manasuka free';
        $nominal = filter_var(request()->getPost('nominal'), FILTER_SANITIZE_NUMBER_INT);
        $deskripsi = request()->getPost('description');

        $cash_in = 0;
        $cash_out = 0;
        $status = 'diterima';

        if ($jenis_pengajuan == 'penyimpanan') {
            $cash_in = $nominal;
        } else {
            // Check saldo for withdrawal
            $cek_saldo = $this->m_deposit->cekSaldoManasukaByUser($iduser)[0]->saldo_manasuka;

            if ($cek_saldo < $nominal) {
                $this->sendAlert('Gagal membuat pengajuan: Saldo manasuka kurang untuk membuat pengajuan', 'warning');
                return redirect()->back();
            }

            if ($nominal < 300000) {
                $this->sendAlert('Gagal membuat pengajuan: Penarikan minimal Rp 300.000', 'warning');
                return redirect()->back();
            }

            $cash_out = $nominal;
        }

        // Insert deposit
        $dataset = [
            'jenis_pengajuan' => $jenis_pengajuan,
            'jenis_deposit' => $jenis_deposit,
            'cash_in' => $cash_in,
            'cash_out' => $cash_out,
            'deskripsi' => $deskripsi,
            'status' => $status,
            'date_created' => date('Y-m-d H:i:s'),
            'idanggota' => $iduser,
            'idadmin' => $this->account->iduser
        ];

        $this->m_deposit->insertDeposit($dataset);
        $this->sendAlert('Pengajuan berhasil dibuat', 'success');
        
        return redirect()->back();
    }

    /**
     * DataTable: Get user list
     * Route: /admin/deposit/data_user
     */
    public function data_user()
    {
        $request = service('request');
        $model = $this->m_user;

        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'];

        // Build query
        $model->select('iduser, username, nama_lengkap, instansi, email, nomor_telepon, flag')
            ->where('idgroup', 4)
            ->groupStart()
                ->like('username', $searchValue)
                ->orLike('nama_lengkap', $searchValue)
                ->orLike('nomor_telepon', $searchValue)
            ->groupEnd();

        $data = $model->asArray()->findAll($length, $start);
        
        // Count records
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

        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }
}
