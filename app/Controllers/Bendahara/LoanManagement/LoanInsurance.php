<?php 
namespace App\Controllers\Bendahara\LoanManagement;

/**
 * LoanInsurance handles loan insurance data retrieval
 * Provides insurance information for active loans
 */
class LoanInsurance extends BaseLoanController
{
    /**
     * Get insurance data for a specific loan (AJAX JSON endpoint)
     * Returns insurance records and total for a given loan ID
     */
    public function get_asuransi($idpinjaman)
    {
        try {
            log_message('info', 'Getting asuransi for idpinjaman: ' . $idpinjaman);
            
            // Check if the loan exists
            $pinjaman = $this->m_pinjaman->getPinjamanById($idpinjaman);
            if (empty($pinjaman)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Pinjaman tidak ditemukan'
                ]);
            }
            
            $asuransi_data = $this->m_asuransi->getAsuransiByIdPinjaman($idpinjaman);
            
            log_message('info', 'Asuransi data count: ' . count($asuransi_data));
            
            if (empty($asuransi_data)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => [],
                    'message' => 'Tidak ada data asuransi untuk pinjaman ini',
                    'total_asuransi' => 0,
                    'debug' => [
                        'idpinjaman' => $idpinjaman,
                        'sql_query' => 'SELECT * FROM tb_asuransi_pinjaman WHERE idpinjaman = ' . $idpinjaman
                    ]
                ]);
            }

            // Calculate total insurance
            $total_asuransi = 0;
            foreach ($asuransi_data as $item) {
                $total_asuransi += $item->nilai_asuransi;
            }

            return $this->response->setJSON([
                'status' => 'success',
                'data' => $asuransi_data,
                'total_asuransi' => $total_asuransi
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in get_asuransi: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal mengambil data asuransi: ' . $e->getMessage()
            ]);
        }
    }
}
