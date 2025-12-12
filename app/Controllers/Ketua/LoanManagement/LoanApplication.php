<?php 
namespace App\Controllers\Ketua\LoanManagement;

use App\Controllers\Ketua\LoanManagement\BaseLoanController;

/**
 * LoanApplication Controller
 * Handles loan approval/rejection operations for Ketua role
 * 
 * NOTE: The loan approval workflow for Ketua (status 3 to 4) is currently not used
 * in production. The current workflow is: Admin -> Bendahara directly.
 * This code is kept for potential future use or reference.
 */
class LoanApplication extends BaseLoanController
{
    public function index()
    {
        // Get loans awaiting Ketua approval (status 3)
        // NOTE: This workflow is not currently active in production
        $list_pinjaman = $this->m_pinjaman->getPinjamanByStatus(3);

        $data = array_merge($this->getBaseViewData('Pinjaman', 'Pinjaman'), [
            'list_pinjaman' => $list_pinjaman
        ]);
        
        return view('ketua/pinjaman/list-pinjaman', $data);
    }

    /**
     * Reject loan application
     * NOTE: This workflow is not currently active in production
     */
    public function cancel_proc($idpinjaman = false)
    {
        $dataset = [
            'idketua' => $this->account->iduser,
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
            'Pengajuan pinjaman ditolak oleh ketua ' . $this->account->nama_lengkap,
            4
        );
        
        // Mark related notifications as read
        $this->m_notification->where('pinjaman_id', $idpinjaman)
            ->where('group_type', 2)
            ->set('status', 'read')
            ->update();

        $this->sendAlert('Pengajuan pinjaman berhasil ditolak', 'success');
        return redirect()->back();
    }

    /**
     * Approve loan application (status 3 to 4)
     * NOTE: This workflow is not currently active in production
     */
    public function approve_proc($idpinjaman = false)
    {
        $dataset = [
            'idketua' => $this->account->iduser,
            'status' => 4,
            'date_updated' => date('Y-m-d H:i:s'),
            'bln_perdana' => date('m', strtotime("+ 1 month")),
            'tanggal_bayar' => date('d')
        ];

        $this->m_pinjaman->updatePinjaman($idpinjaman, $dataset);

        $anggota_id = $this->m_pinjaman->where('idpinjaman', $idpinjaman)
            ->get()
            ->getResult()[0]
            ->idanggota;
        
        $this->createNotification(
            $anggota_id,
            $idpinjaman,
            'Pengajuan pinjaman diterima oleh ketua ' . $this->account->nama_lengkap,
            4
        );
        
        // Mark related notifications as read
        $this->m_notification->where('pinjaman_id', $idpinjaman)
            ->where('group_type', 2)
            ->set('status', 'read')
            ->update();

        $this->sendAlert('Pengajuan pinjaman berhasil disetujui', 'success');
        return redirect()->back();
    }

    /**
     * Show cancel loan modal
     */
    public function cancel_loan()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
            $data = [
                'a' => $pinjaman,
                'flag' => 0
            ];
            echo view('ketua/pinjaman/part-pinjaman-mod-approval', $data);
        }
    }

    /**
     * Show approve loan modal
     */
    public function approve_loan()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
            $data = [
                'a' => $pinjaman,
                'flag' => 1
            ];
            echo view('ketua/pinjaman/part-pinjaman-mod-approval', $data);
        }
    }
}
