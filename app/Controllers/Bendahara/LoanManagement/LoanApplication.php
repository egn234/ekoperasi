<?php 
namespace App\Controllers\Bendahara\LoanManagement;

/**
 * LoanApplication handles loan application processing
 * Manages loan approvals, rejections, and application data retrieval
 */
class LoanApplication extends BaseLoanController
{
    /**
     * Display loan list page
     */
    public function index()
    {
        $data = [
            'title_meta' => view('bendahara/partials/title-meta', ['title' => 'Pinjaman']),
            'page_title' => view('bendahara/partials/page-title', ['title' => 'Pinjaman', 'li_1' => 'EKoperasi', 'li_2' => 'Pinjaman']),
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account
        ];
        
        return view('bendahara/pinjaman/list-pinjaman', $data);
    }

    /**
     * Process loan rejection
     */
    public function cancel_proc($idpinjaman = false)
    {
        $dataset = [
            'idbendahara' => $this->account->iduser,
            'alasan_tolak' => request()->getPost('alasan_tolak'),
            'status' => 0,
            'date_updated' => date('Y-m-d H:i:s')
        ];

        $this->m_pinjaman->updatePinjaman($idpinjaman, $dataset);

        $anggota_id = $this->m_pinjaman->where('idpinjaman', $idpinjaman)
            ->get()
            ->getResult()[0]
            ->idanggota;

        $this->sendAlert(
            $anggota_id,
            $idpinjaman,
            'Pengajuan pinjaman ditolak oleh bendahara '. $this->account->nama_lengkap,
            'Pengajuan pinjaman berhasil ditolak',
            'success'
        );
        
        $this->markAdminNotificationsRead($idpinjaman);

        return redirect()->back();
    }

    /**
     * Process loan approval with proof of transfer
     */
    public function approve_proc($idpinjaman = false)
    {
        $dataset = [
            'idbendahara' => $this->account->iduser,
            'status' => 4,
            'date_updated' => date('Y-m-d H:i:s'),
            'bln_perdana' => date('m', strtotime("+ 1 month")),
            'tanggal_bayar' => date('d')
        ];

        $idanggota = $this->m_pinjaman->where('idpinjaman', $idpinjaman)
            ->get()
            ->getResult()[0]
            ->idanggota;
        
        $username_anggota = $this->m_user->where('iduser', $idanggota)
            ->get()
            ->getResult()[0]
            ->username;

        $bukti_tf = request()->getFile('bukti_tf') ? request()->getFile('bukti_tf') : false;
        $data_session = [];

        if($bukti_tf){
            if ($bukti_tf->isValid()) {	
                // Validate file type
                $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];

                if (!in_array($bukti_tf->getMimeType(), $allowed_types)) {
                    $alert = view(
                        'partials/notification-alert', 
                        [
                            'notif_text' => 'Tipe file tidak diizinkan', 
                            'status' => 'danger'
                        ]
                    );
                    
                    $data_session = ['notif' => $alert];
                    session()->setFlashdata($data_session);
                    return redirect()->back();
                }

                // Validate file size
                if ($bukti_tf->getSize() > 1000000) {
                    $alert = view(
                        'partials/notification-alert', 
                        [
                            'notif_text' => 'Ukuran file tidak diizinkan', 
                            'status' => 'danger'
                        ]
                    );
                    
                    $data_session = ['notif' => $alert];
                    session()->setFlashdata($data_session);
                    return redirect()->back();
                }

                // Validate file extension
                if ($bukti_tf->getExtension() !== 'jpg' && $bukti_tf->getExtension() !== 'jpeg' && $bukti_tf->getExtension() !== 'png') {
                    $alert = view(
                        'partials/notification-alert', 
                        [
                            'notif_text' => 'Ekstensi file tidak diizinkan', 
                            'status' => 'danger'
                        ]
                    );

                    $data_session = ['notif' => $alert];
                    session()->setFlashdata($data_session);
                    return redirect()->back();
                }

                // Delete old file if exists
                $cek_tf = $this->m_pinjaman->getPinjamanById($idpinjaman)[0]->bukti_tf;
                
                if ($cek_tf) {
                    unlink(ROOTPATH . 'public/uploads/user/' . $username_anggota . '/pinjaman/' . $cek_tf);
                }
    
                // Upload new file
                $newName = $bukti_tf->getRandomName();
                $bukti_tf->move(ROOTPATH . 'public/uploads/user/' . $username_anggota . '/pinjaman/', $newName);
                
                $bukti = $bukti_tf->getName();
                $dataset += ['bukti_tf' => $bukti];
                $alert3 = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Bukti transfer berhasil dikirim',
                        'status' => 'success'
                    ]
                );
                $data_session += ['notif_tf' => $alert3];
            } else {
                $alert3 = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Bukti kontrak gagal diunggah',
                        'status' => 'danger'
                    ]
                );

                $data_session = ['notif_kontrak' => $alert3];
                session()->setFlashdata($data_session);
                return redirect()->back();
            }
        }
        
        $this->m_pinjaman->updatePinjaman($idpinjaman, $dataset);

        $this->sendAlert(
            $idanggota,
            $idpinjaman,
            'Pengajuan pinjaman diterima oleh bendahara '. $this->account->nama_lengkap,
            'Pengajuan pinjaman berhasil disetujui',
            'success'
        );

        $this->markAdminNotificationsRead($idpinjaman);

        $data_session += ['notif' => 'Pengajuan pinjaman berhasil disetujui'];
        session()->setFlashdata($data_session);
        return redirect()->back();
    }

    /**
     * Load loan cancellation modal (AJAX)
     */
    public function cancel_loan()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
            $user = $this->m_user->getUserById($pinjaman->idanggota)[0];
            $data = [
                'a' => $pinjaman,
                'b' => $user,
                'flag' => 0
            ];

            echo view('bendahara/pinjaman/part-pinjaman-mod-approval', $data);
        }
    }

    /**
     * Load loan approval modal (AJAX)
     */
    public function approve_loan()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
            $user = $this->m_user->getUserById($pinjaman->idanggota)[0];
            $data = [
                'a' => $pinjaman,
                'b' => $user,
                'flag' => 1
            ];

            echo view('bendahara/pinjaman/part-pinjaman-mod-approval', $data);
        }
    }

    /**
     * Load loan detail modal (AJAX)
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

            echo view('bendahara/pinjaman/part-pinjaman-mod-detail', $data);
        }
    }

    /**
     * DataTable AJAX endpoint for loan applications
     */
    public function data_pinjaman()
    {
        $request = service('request');
        $model = $this->m_pinjaman;

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
        $model->where('tb_pinjaman.status', 3);

        $model->groupStart()
            ->like('a.nama_lengkap', $searchValue)
            ->orLike('a.username', $searchValue);
        $model->groupEnd();
        
        $model->join('tb_user a', 'a.iduser = tb_pinjaman.idanggota');
        $model->join('tb_user c', 'c.iduser = tb_pinjaman.idadmin', 'left');
        $model->join('tb_user d', 'd.iduser = tb_pinjaman.idbendahara', 'left');
        $data = $model->asArray()->findAll($length, $start);

        $model->where('tb_pinjaman.status', 3);
        $recordsTotal = $model->countAllResults();

        $model->where('tb_pinjaman.status', 3);
        
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
