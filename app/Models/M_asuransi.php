<?php namespace App\Models;

use CodeIgniter\Model;

class M_asuransi extends Model
{
    protected $table      = 'tb_asuransi_pinjaman';

    protected $allowedFields = ['idpinjaman', 'bulan_kumulatif', 'nilai_asuransi', 'status'];

    protected $useTimestamps = true;
    protected $createdField  = 'date_created';
    protected $updatedField  = 'date_updated';

    /**
     * Insert asuransi record
     */
    public function insertAsuransi($data)
    {
        $builder = $this->db->table($this->table);
        $builder->insert($data);
    }

    /**
     * Get asuransi by pinjaman ID
     */
    public function getAsuransiByIdPinjaman($idpinjaman)
    {
        return $this->where('idpinjaman', $idpinjaman)->get()->getResult();
    }

    /**
     * Get asuransi detail by pinjaman ID
     */
    public function getAsuransiDetailByIdPinjaman($idpinjaman)
    {
        $sql = "
            SELECT 
                SUM(nilai_asuransi) as total_asuransi,
                bulan_kumulatif,
                status
            FROM tb_asuransi_pinjaman
            WHERE idpinjaman = $idpinjaman
            GROUP BY bulan_kumulatif
            ORDER BY bulan_kumulatif ASC
        ";
        return $this->db->query($sql)->getResult();
    }

    /**
     * Calculate insurance based on installment months
     * Menggunakan ceil() untuk menghitung kelipatan, tapi hanya buat 1 record
     * dengan total bulan cicilan
     * 
     * @param int $angsuran_bulanan Total installment months
     * @param float $nominal_asuransi Insurance nominal per period
     * @param int $bulan_kelipatan Insurance period multiplier
     * @return array Array of insurance data
     */
    public function calculateAsuransi($angsuran_bulanan, $nominal_asuransi, $bulan_kelipatan)
    {
        $insurance_data = [];
        
        // Calculate number of insurance periods using ceil for rounding up
        $jumlahKelipatan = ceil($angsuran_bulanan / $bulan_kelipatan);
        
        // Create only ONE insurance record with total installment months and total insurance value
        if ($jumlahKelipatan > 0) {
            $total_asuransi = $jumlahKelipatan * $nominal_asuransi;
            $insurance_data[] = [
                'bulan_kumulatif' => $angsuran_bulanan, // Use actual installment months
                'nilai_asuransi' => $total_asuransi,    // Total insurance amount
                'periode' => $jumlahKelipatan
            ];
        }
        
        return $insurance_data;
    }
}