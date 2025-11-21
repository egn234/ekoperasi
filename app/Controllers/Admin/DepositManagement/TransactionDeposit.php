<?php 
namespace App\Controllers\Admin\DepositManagement;

/**
 * TransactionDeposit Controller
 * 
 * Mengelola transaksi simpanan:
 * - List transaksi simpanan
 * - Approve/reject transaksi
 * - Edit transaksi
 * - Detail transaksi
 * - DataTable transaksi
 */
class TransactionDeposit extends BaseDepositController
{
    /**
     * List transaksi simpanan
     * Route: /admin/deposit/list_transaksi
     */
    public function index()
    {
        $data = array_merge($this->getBaseViewData(), [
            'title_meta' => view('admin/partials/title-meta', ['title' => 'Kelola Transaksi Simpanan']),
            'page_title' => view('admin/partials/page-title', [
                'title' => 'Kelola Transaksi Simpanan', 
                'li_1' => 'EKoperasi', 
                'li_2' => 'Kelola Transaksi Simpanan'
            ])
        ]);
        
        return view('admin/deposit/deposit-list', $data);
    }

    /**
     * Edit form transaksi
     * Route: /admin/deposit/edit/{id}
     */
    public function edit_mutasi($id = false)
    {
        if (!$id) {
            $this->sendAlert('ID Transaksi tidak valid', 'danger');
            return redirect()->to('admin/deposit/list_transaksi');
        }

        $deposit = $this->m_deposit
            ->select('tb_deposit.*, tb_user.nama_lengkap, tb_user.nik')
            ->join('tb_user', 'tb_user.iduser = tb_deposit.idanggota')
            ->where('iddeposit', $id)
            ->get()
            ->getResult()[0];

        $data = array_merge($this->getBaseViewData(), [
            'title_meta' => view('admin/partials/title-meta', ['title' => 'Ubah Pengajuan Simpanan']),
            'page_title' => view('admin/partials/page-title', [
                'title' => 'Ubah Pengajuan Simpanan', 
                'li_1' => 'EKoperasi', 
                'li_2' => 'Ubah Pengajuan Simpanan'
            ]),
            'deposit' => $deposit
        ]);
        
        return view('admin/deposit/deposit-edit', $data);
    }

    /**
     * Update transaksi
     * Route: POST /admin/deposit/update_mutasi/{id}
     */
    public function update_mutasi($iddeposit = false)
    {
        if (!$iddeposit) {
            $this->sendAlert('ID Transaksi tidak valid', 'danger');
            return redirect()->back();
        }

        $iduser = request()->getPost('idanggota');
        $jenis_pengajuan = request()->getPost('jenis_pengajuan');
        
        if (empty($jenis_pengajuan)) {
            $this->sendAlert('Gagal membuat pengajuan: Pilih jenis pengajuan terlebih dahulu', 'warning');
            return redirect()->back();
        }

        $jenis_deposit = request()->getPost('jenis_deposit');
        $nominal = request()->getPost('nominal');

        $cash_in = ($jenis_pengajuan == 'penyimpanan') ? $nominal : 0;
        $cash_out = ($jenis_pengajuan == 'penarikan') ? $nominal : 0;

        $dataset = [
            'jenis_pengajuan' => $jenis_pengajuan,
            'jenis_deposit' => $jenis_deposit,
            'cash_in' => $cash_in,
            'cash_out' => $cash_out,
            'date_created' => date('Y-m-d H:i:s'),
            'idanggota' => $iduser,
            'idadmin' => $this->account->iduser
        ];

        $this->m_deposit->updateBuktiTransfer($iddeposit, $dataset);
        $this->sendAlert('Pengajuan berhasil diubah', 'success');
        
        return redirect()->back();
    }

    /**
     * Konfirmasi transaksi (approve)
     * Route: /admin/deposit/confirm/{id}
     */
    public function konfirmasi_mutasi($iddeposit = false)
    {
        if (!$iddeposit) {
            $this->sendAlert('ID Transaksi tidak valid', 'danger');
            return redirect()->back();
        }

        $deposit = $this->m_deposit->where('iddeposit', $iddeposit)->get()->getResult()[0];
        $idanggota = $deposit->idanggota;
        $jenis_pengajuan = $deposit->jenis_pengajuan;
        
        $nama_anggota = $this->m_user->where('iduser', $idanggota)->get()->getResult()[0]->nama_lengkap;

        $message = ($jenis_pengajuan == 'penarikan') ? 'penarikan' : 'penyimpanan';
        $cash_in = ($jenis_pengajuan == 'penyimpanan') 
            ? filter_var(request()->getPost('nominal_uang'), FILTER_SANITIZE_NUMBER_INT) 
            : 0;
        $cash_out = ($jenis_pengajuan == 'penarikan') 
            ? filter_var(request()->getPost('nominal_uang'), FILTER_SANITIZE_NUMBER_INT) 
            : 0;

        // Update status
        $dataset = [
            'idadmin' => $this->account->iduser,
            'status' => 'diproses bendahara',
            'cash_in' => $cash_in,
            'cash_out' => $cash_out,
            'date_updated' => date('Y-m-d H:i:s')
        ];

        $this->m_deposit->setStatus($iddeposit, $dataset);

        // Create notifications
        $this->createNotification(
            $this->account->iduser,
            $idanggota,
            $iddeposit,
            "Pengajuan {$message} manasuka baru dari anggota {$nama_anggota}",
            2 // Bendahara
        );

        $this->createNotification(
            $this->account->iduser,
            $idanggota,
            $iddeposit,
            "Pengajuan {$message} manasuka disetujui oleh admin {$this->account->nama_lengkap}",
            4 // Anggota
        );
        
        $this->sendAlert('Permohonan Berhasil Dikonfirmasi', 'success');
        return redirect()->back();
    }

    /**
     * Batalkan transaksi (reject)
     * Route: /admin/deposit/cancel/{id}
     */
    public function batalkan_mutasi($iddeposit = false)
    {
        if (!$iddeposit) {
            $this->sendAlert('ID Transaksi tidak valid', 'danger');
            return redirect()->back();
        }

        $dataset = [
            'idadmin' => $this->account->iduser,
            'alasan_tolak' => request()->getPost('alasan_tolak'),
            'status' => 'ditolak',
            'date_updated' => date('Y-m-d H:i:s')
        ];

        $this->m_deposit->setStatus($iddeposit, $dataset);

        $deposit = $this->m_deposit->where('iddeposit', $iddeposit)->get()->getResult()[0];
        $idanggota = $deposit->idanggota;
        $jenis_pengajuan = $deposit->jenis_pengajuan;

        $message = ($jenis_pengajuan == 'penarikan') ? 'penarikan' : 'penyimpanan';

        $this->createNotification(
            $this->account->iduser,
            $idanggota,
            $iddeposit,
            "Pengajuan {$message} manasuka ditolak oleh admin {$this->account->nama_lengkap}",
            4 // Anggota
        );
        
        $this->sendAlert('Permohonan Berhasil Ditolak', 'success');
        return redirect()->back();
    }

    /**
     * Detail transaksi modal (AJAX)
     * Route: POST /admin/deposit/detail_mutasi
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
            
            echo view('admin/deposit/part-depo-mod-detail', $data);
        }
    }

    /**
     * Cancel modal (AJAX)
     * Route: POST /admin/deposit/cancel-mnsk
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
            
            echo view('admin/deposit/part-depo-mod-cancel', $data);
        }
    }

    /**
     * Approve modal (AJAX)
     * Route: POST /admin/deposit/approve-mnsk
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

            // Check saldo for withdrawal
            $confirmation = false;
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
            
            $duser = $this->m_user->getUserById($deposit->idanggota)[0];

            $data = [
                'a' => $deposit,
                'flag' => 1,
                'duser' => $duser,
                'total_saldo' => $total_saldo,
                'confirmation' => $confirmation
            ];
            
            echo view('admin/deposit/part-depo-mod-approve', $data);
        }
    }

    /**
     * DataTable: Get all transactions
     * Route: /admin/deposit/data_transaksi
     */
    public function data_transaksi()
    {
        $request = service('request');
        $model = $this->m_deposit;

        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'];

        // Build query
        $model->select('tb_deposit.*, tb_user.username, tb_user.nama_lengkap, tb_user.email, tb_user.nomor_telepon, tb_user.email');
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

        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }

    /**
     * DataTable: Get pending transactions only
     * Route: /admin/deposit/data_transaksi_filter
     */
    public function data_transaksi_filter()
    {
        $request = service('request');
        $model = $this->m_deposit;

        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'];

        // Build query with filter
        $model->select('tb_deposit.*, tb_user.username, tb_user.nama_lengkap, tb_user.email');
        $model->where('tb_deposit.status', 'diproses admin');
        $model->groupStart()
            ->like('nama_lengkap', $searchValue)
            ->orLike('username', $searchValue)
            ->orLike('email', $searchValue)
            ->orLike('status', $searchValue);
        $model->groupEnd();
        $model->join('tb_user', 'tb_deposit.idanggota = tb_user.iduser');
        $model->orderBy('tb_deposit.date_created', 'DESC');
        $data = $model->asArray()->findAll($length, $start);

        $model->where('tb_deposit.status', 'diproses admin');
        $model->orderBy('tb_deposit.date_created', 'DESC');
        $recordsTotal = $model->countAllResults();

        $model->where('tb_deposit.status', 'diproses admin');
        $model->groupStart()
            ->like('nama_lengkap', $searchValue)
            ->orLike('username', $searchValue)
            ->orLike('email', $searchValue)
            ->orLike('status', $searchValue);
        $model->groupEnd();
        $model->join('tb_user', 'tb_deposit.idanggota = tb_user.iduser');
        $recordsFiltered = $model->countAllResults();

        return $this->response->setJSON([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ]);
    }
}
