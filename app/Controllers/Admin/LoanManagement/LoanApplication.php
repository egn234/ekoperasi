<?php

namespace App\Controllers\Admin\LoanManagement;

/**
 * LoanApplication Controller
 * Handles loan application listing, approval, and cancellation
 */
class LoanApplication extends BaseLoanController
{
    /**
     * Display list of loan applications
     */
    public function index()
    {
        $data = $this->getBaseViewData('Pinjaman');
        return view('admin/pinjaman/list-pinjaman', $data);
    }

    /**
     * Cancel/reject a loan application
     */
    /**
     * Cancel/reject a loan application
     */
    public function cancel_proc($idpinjaman = false)
    {
        $returnUrl = request()->getPost('return_url');

        $dataset = [
            'idadmin' => $this->account->iduser,
            'alasan_tolak' => request()->getPost('alasan_tolak'),
            'status' => 0,
            'date_updated' => date('Y-m-d H:i:s')
        ];

        $this->m_pinjaman->updatePinjaman($idpinjaman, $dataset);

        $anggota_id = $this->m_pinjaman->where('idpinjaman', $idpinjaman)
            ->get()
            ->getResult()[0]
            ->idanggota;

        $this->createNotification(
            $anggota_id,
            $idpinjaman,
            'Pengajuan pinjaman ditolak oleh admin ' . $this->account->nama_lengkap,
            4
        );

        $this->sendAlert('Pengajuan pinjaman berhasil ditolak');

        if ($returnUrl) {
            return redirect()->to($returnUrl);
        }
        return redirect()->back();
    }

    /**
     * Approve a loan application
     */
    public function approve_proc($idpinjaman = false)
    {
        $returnUrl = request()->getPost('return_url');

        $dataset = [
            'idadmin' => $this->account->iduser,
            'nominal' => request()->getPost('nominal_uang'),
            'status' => 3,
            'date_updated' => date('Y-m-d H:i:s')
        ];

        $this->m_pinjaman->updatePinjaman($idpinjaman, $dataset);

        $idanggota = $this->m_pinjaman->where('idpinjaman', $idpinjaman)
            ->get()
            ->getResult()[0]
            ->idanggota;

        // Notify member
        $this->createNotification(
            $idanggota,
            $idpinjaman,
            'Pengajuan pinjaman diterima oleh admin ' . $this->account->nama_lengkap,
            4
        );

        // Notify chairman
        $this->createNotification(
            $idanggota,
            $idpinjaman,
            'Pengajuan pinjaman baru dari ' . $this->m_user->where('iduser', $idanggota)->get()->getResult()[0]->nama_lengkap,
            3
        );

        // Notify treasurer
        $this->createNotification(
            $idanggota,
            $idpinjaman,
            'Pengajuan pinjaman baru dari ' . $this->m_user->where('iduser', $idanggota)->get()->getResult()[0]->nama_lengkap,
            2
        );

        $this->sendAlert('Pengajuan pinjaman berhasil disetujui');

        if ($returnUrl) {
            return redirect()->to($returnUrl);
        }
        return redirect()->back();
    }

    /**
     * Show cancel loan modal (AJAX)
     */
    public function cancel_loan()
    {
        if ($this->request->getPost('rowid')) {
            $id = $this->request->getPost('rowid');
            // Capture return_url passed from AJAX
            $returnUrl = $this->request->getPost('return_url');

            $pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
            $data = [
                'a' => $pinjaman,
                'flag' => 0,
                'return_url' => $returnUrl
            ];
            echo view('admin/pinjaman/part-pinjaman-mod-cancel', $data);
        }
    }

    /**
     * Show approve loan modal (AJAX)
     */
    public function approve_loan()
    {
        if ($this->request->getPost('rowid')) {
            $id = $this->request->getPost('rowid');
            // Capture return_url passed from AJAX
            $returnUrl = $this->request->getPost('return_url');

            $pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
            $user = $this->m_user->getUserById($pinjaman->idanggota)[0];
            $data = [
                'a' => $pinjaman,
                'b' => $user,
                'flag' => 1,
                'return_url' => $returnUrl
            ];

            echo view('admin/pinjaman/part-pinjaman-mod-approval', $data);
        }
    }

    /**
     * Show loan detail modal (AJAX)
     */
    public function detail_pinjaman()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
            $hitung_cicilan = $this->m_cicilan->select('COUNT(idcicilan) AS hitung, IFNULL(SUM(nominal),0) AS total_lunas')
                ->where('idpinjaman', $id)
                ->get()
                ->getResult()[0];
            $data = [
                'a' => $pinjaman,
                'b' => $hitung_cicilan,
                'flag' => 1
            ];
            echo view('admin/pinjaman/part-pinjaman-mod-detail', $data);
        }
    }

    /**
     * DataTable: All loans
     */
    public function data_pinjaman()
    {
        $request = service('request');
        $model = model('App\Models\M_pinjaman');

        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'];

        $model->select('a.status_pegawai AS status_pegawai');
        $model->select('a.username AS username_peminjam');
        $model->select('a.nama_lengkap AS nama_peminjam');
        $model->select('a.nik AS nik_peminjam');
        $model->select('tb_pinjaman.*');
        $model->select('(SELECT COUNT(idcicilan) FROM tb_cicilan WHERE idpinjaman = tb_pinjaman.idpinjaman) AS sisa_cicilan', false);
        $model->select('c.nama_lengkap AS nama_admin');
        $model->select('c.nik AS nik_admin');
        $model->select('d.nama_lengkap AS nama_bendahara');
        $model->select('d.nik AS nik_bendahara');
        $model->groupStart()
            ->like('a.nama_lengkap', $searchValue)
            ->orLike('a.username', $searchValue);
        $model->groupEnd();
        $model->join('tb_user a', 'a.iduser = tb_pinjaman.idanggota');
        $model->join('tb_user c', 'c.iduser = tb_pinjaman.idadmin', 'left');
        $model->join('tb_user d', 'd.iduser = tb_pinjaman.idbendahara', 'left');
        $model->orderBy('tb_pinjaman.date_updated', 'DESC');
        $data = $model->asArray()->findAll($length, $start);

        $model->orderBy('tb_pinjaman.date_updated', 'DESC');
        $recordsTotal = $model->countAllResults();

        $model->like('a.nama_lengkap', $searchValue)
            ->orLike('a.username', $searchValue);
        $model->join('tb_user a', 'a.iduser = tb_pinjaman.idanggota');
        $model->orderBy('tb_pinjaman.date_updated', 'DESC');
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
     * DataTable: Filtered loans (status = 2)
     */
    public function data_pinjaman_filter()
    {
        $request = service('request');
        $model = new \App\Models\M_pinjaman();

        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'];

        $model->select('a.status_pegawai AS status_pegawai');
        $model->select('a.username AS username_peminjam');
        $model->select('a.nama_lengkap AS nama_peminjam');
        $model->select('a.nik AS nik_peminjam');
        $model->select('tb_pinjaman.*');
        $model->select('(SELECT COUNT(idcicilan) FROM tb_cicilan WHERE idpinjaman = tb_pinjaman.idpinjaman) AS sisa_cicilan', false);
        $model->select('c.nama_lengkap AS nama_admin');
        $model->select('c.nik AS nik_admin');
        $model->select('d.nama_lengkap AS nama_bendahara');
        $model->select('d.nik AS nik_bendahara');
        $model->where('tb_pinjaman.status', 2);
        $model->groupStart()
            ->like('a.nama_lengkap', $searchValue)
            ->orLike('a.username', $searchValue);
        $model->groupEnd();
        $model->join('tb_user a', 'a.iduser = tb_pinjaman.idanggota');
        $model->join('tb_user c', 'c.iduser = tb_pinjaman.idadmin', 'left');
        $model->join('tb_user d', 'd.iduser = tb_pinjaman.idbendahara', 'left');
        $data = $model->asArray()->findAll($length, $start);

        $model->where('tb_pinjaman.status', 2);
        $recordsTotal = $model->countAllResults();

        $model->where('tb_pinjaman.status', 2);
        $model->groupStart()
            ->like('a.nama_lengkap', $searchValue)
            ->orLike('a.username', $searchValue);
        $model->groupEnd();
        $model->join('tb_user a', 'a.iduser = tb_pinjaman.idanggota');
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
