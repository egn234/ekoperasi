<?php

namespace App\Controllers\Bendahara\DepositManagement;

/**
 * TransactionDeposit handles deposit transaction processing
 * Manages transaction approvals, rejections, and transaction data retrieval
 */
class TransactionDeposit extends BaseDepositController
{
    /**
     * Display transaction list page
     */
    public function list_transaksi()
    {
        $data = [
            'title_meta' => view('bendahara/partials/title-meta', ['title' => 'Kelola Transaksi Simpanan']),
            'page_title' => view('bendahara/partials/page-title', ['title' => 'Kelola Transaksi Simpanan', 'li_1' => 'EKoperasi', 'li_2' => 'Kelola Transaksi Simpanan']),
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account
        ];

        return view('bendahara/deposit/deposit-list', $data);
    }

    /**
     * Process deposit/withdrawal confirmation
     */
    public function konfirmasi_mutasi($iddeposit = false)
    {
        $dataset = [
            'idbendahara' => $this->account->iduser,
            'status' => 'diterima',
            'date_updated' => date('Y-m-d H:i:s')
        ];

        $this->m_deposit->setStatus($iddeposit, $dataset);

        $idanggota = $this->m_deposit->where('iddeposit', $iddeposit)->get()->getResult()[0]->idanggota;
        $jenis_pengajuan = $this->m_deposit->where('iddeposit', $iddeposit)->get()->getResult()[0]->jenis_deposit;

        $message = ($jenis_pengajuan == 'penarikan') ? 'penarikan' : 'penyimpanan';

        $this->sendAlert(
            $idanggota,
            $iddeposit,
            'Pengajuan ' . $message . ' manasuka disetujui oleh bendahara ' . $this->account->nama_lengkap,
            'Permohonan Berhasil Dikonfirmasi',
            'success'
        );

        return redirect()->back();
    }

    /**
     * Process deposit/withdrawal cancellation
     */
    public function batalkan_mutasi($iddeposit = false)
    {
        $dataset = [
            'idbendahara' => $this->account->iduser,
            'alasan_tolak' => request()->getPost('alasan_tolak'),
            'status' => 'ditolak',
            'date_updated' => date('Y-m-d H:i:s')
        ];

        $this->m_deposit->setStatus($iddeposit, $dataset);

        $idanggota = $this->m_deposit->where('iddeposit', $iddeposit)->get()->getResult()[0]->idanggota;
        $jenis_pengajuan = $this->m_deposit->where('iddeposit', $iddeposit)->get()->getResult()[0]->jenis_deposit;

        $message = ($jenis_pengajuan == 'penarikan') ? 'penarikan' : 'penyimpanan';

        $this->sendAlert(
            $idanggota,
            $iddeposit,
            'Pengajuan ' . $message . ' manasuka ditolak oleh bendahara ' . $this->account->nama_lengkap,
            'Permohonan Berhasil Ditolak',
            'success'
        );

        return redirect()->back();
    }

    /**
     * Load deposit detail modal (AJAX)
     */
    public function detail_mutasi()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $dsimpanan = $this->m_deposit->getDepositById($id)[0];
            $duser = $this->m_user->getUserById($dsimpanan->idanggota)[0];
            $data = [
                'a' => $dsimpanan,
                'duser' => $duser
            ];
            echo view('bendahara/deposit/part-depo-mod-detail', $data);
        }
    }

    /**
     * Load deposit cancellation modal (AJAX)
     */
    public function cancel_mnsk()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $deposit = $this->m_deposit->getDepositById($id)[0];
            $data = [
                'a' => $deposit,
                'flag' => 0
            ];
            echo view('bendahara/deposit/part-depo-mod-approval', $data);
        }
    }

    /**
     * Load deposit approval modal with balance validation (AJAX)
     */
    public function approve_mnsk()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $deposit = $this->m_deposit->getDepositById($id)[0];

            $total_saldo_wajib = $this->m_deposit->getSaldoWajibByUserId($deposit->idanggota)[0]->saldo;
            $total_saldo_pokok = $this->m_deposit->getSaldoPokokByUserId($deposit->idanggota)[0]->saldo;
            $total_saldo_manasuka = $this->m_deposit->getSaldoManasukaByUserId($deposit->idanggota)[0]->saldo;
            $total_saldo = '';

            $confirmation = false;

            // Check balance sufficiency for withdrawals
            if ($deposit->cash_in == 0) {
                if ($deposit->jenis_deposit == 'wajib') {
                    $total_saldo = $total_saldo_wajib;
                    $confirmation = ($total_saldo_wajib < $deposit->cash_out);
                } elseif ($deposit->jenis_deposit == 'pokok') {
                    $total_saldo = $total_saldo_pokok;
                    $confirmation = ($total_saldo_pokok < $deposit->cash_out);
                } elseif ($deposit->jenis_deposit == 'manasuka') {
                    $total_saldo = $total_saldo_manasuka;
                    $confirmation = ($total_saldo_manasuka < $deposit->cash_out);
                }
            }

            $data = [
                'a' => $deposit,
                'flag' => 1,
                'total_saldo' => $total_saldo,
                'confirmation' => $confirmation
            ];

            echo view('bendahara/deposit/part-depo-mod-approval', $data);
        }
    }

    /**
     * DataTable AJAX endpoint for all transactions
     */
    public function data_transaksi()
    {
        $request = service('request');
        $model = $this->m_deposit;

        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'];

        $model->select('tb_deposit.*, tb_user.username, tb_user.nama_lengkap, tb_user.email');
        $model->like('nama_lengkap', $searchValue);
        $model->orLike('username', $searchValue);
        $model->orLike('email', $searchValue);
        $model->orLike('status', $searchValue);
        $model->join('tb_user', 'tb_deposit.idanggota = tb_user.iduser');
        $model->orderBy('tb_deposit.date_created', 'DESC');
        $data = $model->asArray()->findAll($length, $start);

        $recordsTotal = $model->countAllResults();

        $model->like('nama_lengkap', $searchValue);
        $model->orLike('username', $searchValue);
        $model->orLike('email', $searchValue);
        $model->orLike('status', $searchValue);
        $model->join('tb_user', 'tb_deposit.idanggota = tb_user.iduser');
        $recordsFiltered = $model->countAllResults();

        $response = [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ];

        return $this->response->setJSON($response);
    }

    /**
     * DataTable AJAX endpoint for pending transactions
     */
    public function data_transaksi_filter()
    {
        $request = service('request');
        $model = $this->m_deposit;

        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'];

        $model->select('tb_deposit.*, tb_user.username, tb_user.nama_lengkap, tb_user.nik, tb_user.email');
        $model->where('tb_deposit.status', 'diproses bendahara');

        $model->groupStart()
            ->like('nama_lengkap', $searchValue)
            ->orLike('username', $searchValue)
            ->orLike('email', $searchValue)
            ->orLike('status', $searchValue);
        $model->groupEnd();

        $model->join('tb_user', 'tb_deposit.idanggota = tb_user.iduser');
        $model->orderBy('tb_deposit.date_created', 'DESC');
        $data = $model->asArray()->findAll($length, $start);

        $model->where('tb_deposit.status', 'diproses bendahara');
        $model->orderBy('tb_deposit.date_created', 'DESC');
        $recordsTotal = $model->countAllResults();

        $model->where('tb_deposit.status', 'diproses bendahara');

        $model->groupStart()
            ->like('nama_lengkap', $searchValue)
            ->orLike('username', $searchValue)
            ->orLike('email', $searchValue)
            ->orLike('status', $searchValue);
        $model->groupEnd();

        $model->join('tb_user', 'tb_deposit.idanggota = tb_user.iduser');
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
