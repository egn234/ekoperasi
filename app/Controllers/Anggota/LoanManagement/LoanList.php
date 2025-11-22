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
}
