<?php 
namespace App\Controllers\Anggota\LoanManagement;

class LoanList extends BaseLoanController
{
    public function index()
    {
        $list_pinjaman = $this->m_pinjaman->getPinjamanByIdAnggota($this->account->iduser);

        $data = array_merge(
            $this->getBaseViewData('Pinjaman'),
            ['list_pinjaman' => $list_pinjaman]
        );
        
        return view('anggota/pinjaman/list-pinjaman', $data);
    }

    function detail($idpinjaman = false)
    {
        $detail_pinjaman = $this->m_pinjaman->getPinjamanById($idpinjaman)[0];
        $tagihan_lunas = $this->m_cicilan->getSaldoTerbayarByIdPinjaman($idpinjaman)[0];
        $asuransi_data = $this->m_asuransi->getAsuransiByIdPinjaman($idpinjaman);
        $currentpage = $this->request->getVar('page_grup1') ? $this->request->getVar('page_grup1') : 1;

        // Get total count of paid installments (not paginated)
        $total_paid_installments = $this->m_cicilan->select('COUNT(idcicilan) as total_count')
            ->where('idpinjaman', $idpinjaman)
            ->get()
            ->getResult()[0]
            ->total_count;

        $list_cicilan2 =  $this->m_cicilan_pag
            ->select('
                (
                    SELECT SUM(nominal)
                    FROM tb_cicilan b WHERE b.date_created <= tb_cicilan.date_created
                    AND idpinjaman = tb_cicilan.idpinjaman
                ) AS saldo,
                DATE_FORMAT(date_created, "%Y-%m-%d") as date,
                (
                    SELECT COUNT(idcicilan)
                    FROM tb_cicilan c WHERE c.date_created <= tb_cicilan.date_created
                    AND idpinjaman = tb_cicilan.idpinjaman
                ) AS counter,
                tb_cicilan.*,
                SUM(tb_cicilan.nominal) as total_saldo'
            )
            ->where('idpinjaman', $idpinjaman)
            ->orderBy('date_created', 'DESC')
            ->groupBy('date')
            ->paginate(10, 'grup1');

        $data = array_merge(
            $this->getBaseViewData('Pinjaman'),
            [
                'list_cicilan2' => $list_cicilan2,
                'pager' => $this->m_cicilan_pag->pager,
                'currentpage' => $currentpage,
                'detail_pinjaman' => $detail_pinjaman,
                'tagihan_lunas' => $tagihan_lunas,
                'asuransi_data' => $asuransi_data,
                'total_paid_installments' => $total_paid_installments
            ]
        );
        
        return view('anggota/pinjaman/list-cicilan', $data);	
    }

    /**
     * Show cancel loan modal
     */
    public function cancel_modal()
    {
        if ($this->request->getPost('rowid')) {
            $id = $this->request->getPost('rowid');
            $pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
            
            $data = [
                'pinjaman' => $pinjaman,
                'duser' => $this->account
            ];
            
            return view('anggota/pinjaman/part-cancel-modal', $data);
        }
    }

    /**
     * Process loan cancellation
     */
    public function cancel_proc($idpinjaman)
    {
        $pinjaman = $this->m_pinjaman->getPinjamanById($idpinjaman);
        
        if (empty($pinjaman)) {
            $this->sendAlert('Pinjaman tidak ditemukan', 'danger');
            return redirect()->to('anggota/pinjaman/list');
        }
        
        $pinjaman = $pinjaman[0];
        
        // Validasi: hanya bisa membatalkan jika status 1, 2, atau 3
        // Status 1: Upload Kelengkapan Form
        // Status 2: Menunggu Verifikasi
        // Status 3: Menunggu Approval Sekretariat
        if (!in_array($pinjaman->status, [1, 2, 3])) {
            $this->sendAlert('Pinjaman tidak dapat dibatalkan. Status pinjaman sudah melewati tahap approval.', 'danger');
            return redirect()->to('anggota/pinjaman/list');
        }
        
        // Validasi: hanya pemilik pinjaman yang bisa membatalkan
        if ($pinjaman->idanggota != $this->account->iduser) {
            $this->sendAlert('Anda tidak memiliki akses untuk membatalkan pinjaman ini', 'danger');
            return redirect()->to('anggota/pinjaman/list');
        }
        
        // Update status pinjaman menjadi 0 (ditolak/dibatalkan)
        $data = [
            'status' => 0,
            'alasan_tolak' => 'Dibatalkan oleh anggota',
            'date_updated' => date('Y-m-d H:i:s')
        ];
        
        $this->m_pinjaman->updatePinjaman($idpinjaman, $data);
        
        // Hapus data asuransi terkait
        $this->m_asuransi->where('idpinjaman', $idpinjaman)->delete();
        
        $this->sendAlert('Pengajuan pinjaman berhasil dibatalkan', 'success');
        return redirect()->to('anggota/pinjaman/list');
    }
}
