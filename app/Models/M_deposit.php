<?php
namespace App\Models;

use CodeIgniter\Model;

class m_deposit extends Model
{
    protected $table      = 'tb_deposit';
    protected $primaryKey = 'iddeposit';

    protected $returnType = 'array';

    protected $allowedFields = [
        'status'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'date_created';
    protected $updatedField  = 'date_updated';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    function getAllDeposit()
    {
    	$sql = "
            SELECT 
                tb_deposit.*,
                tb_user.nama_lengkap,
                tb_user.nik,
                tb_user.email 
            FROM tb_deposit 
            JOIN tb_user ON tb_deposit.idanggota = tb_user.iduser
            ORDER BY tb_deposit.date_created DESC
        ";

    	return $this->db->query($sql)->getResult();    
    }

    function getAllDepositFilter()
    {
        $sql = "
            SELECT 
                tb_deposit.*,
                tb_user.nama_lengkap,
                tb_user.nik,
                tb_user.email 
            FROM tb_deposit 
            JOIN tb_user ON tb_deposit.idanggota = tb_user.iduser 
            WHERE tb_deposit.status = 'diproses admin'
            ORDER BY tb_deposit.date_created DESC
        ";
        
        return $this->db->query($sql)->getResult();    
    }

    function getAllDepositFilterBendahara()
    {
        $sql = "
            SELECT 
                tb_deposit.*,
                tb_user.nama_lengkap,
                tb_user.nik,
                tb_user.email 
            FROM tb_deposit 
            JOIN tb_user ON tb_deposit.idanggota = tb_user.iduser 
            WHERE tb_deposit.status = 'diproses bendahara'
            ORDER BY tb_deposit.date_created DESC
        ";
        
        return $this->db->query($sql)->getResult();    
    }

    function countInitialDeposit($iduser)
    {
        $sql = "SELECT count(iddeposit) AS hitung FROM tb_deposit WHERE idanggota = $iduser AND deskripsi = 'biaya awal registrasi'";
        return $this->db->query($sql)->getResult();
    }

    function getInitialDeposit($iduser)
    {
        $sql = "SELECT * FROM tb_deposit WHERE idanggota = $iduser AND deskripsi = 'biaya awal registrasi'";
        return $this->db->query($sql)->getResult();
    }

    function getDepositById($iddeposit)
    {
        $sql = "
            SELECT 
                tb_deposit.*,
                tb_user.nama_lengkap AS nama_admin,
                tb_user.nik AS nik_admin
            FROM tb_deposit 
            LEFT JOIN tb_user ON tb_user.iduser = tb_deposit.idadmin
            WHERE iddeposit = $iddeposit;
        ";
        
        return $this->db->query($sql)->getResult();  
    }

    function getDepositByUserId($iduser)
    {
        $sql = "SELECT * FROM tb_deposit WHERE idanggota = $iduser AND (cash_in != 0 OR cash_out != 0) ORDER BY date_created DESC";
        return $this->db->query($sql)->getResult();  
    }

    function getSaldoWajibByUserId($iduser)
    {
        $sql = "
            SELECT SUM(cash_in)-SUM(cash_out) AS saldo 
            FROM tb_deposit 
            WHERE idanggota = $iduser 
                AND status = 'diterima' 
                AND jenis_deposit = 'wajib'
        ";
        
        return $this->db->query($sql)->getResult();  
    }

    function getSaldoPokokByUserId($iduser)
    {
        $sql = "
            SELECT SUM(cash_in)-SUM(cash_out) AS saldo 
            FROM tb_deposit 
            WHERE idanggota = $iduser 
                AND status = 'diterima' 
                AND jenis_deposit = 'pokok'
        ";
        
        return $this->db->query($sql)->getResult();  
    }

    function getSaldoManasukaByUserId($iduser)
    {
        $sql = "
            SELECT SUM(cash_in)-SUM(cash_out) AS saldo 
            FROM tb_deposit 
            WHERE idanggota = $iduser 
                AND status = 'diterima' 
                AND jenis_deposit LIKE 'manasuka%'
        ";
        
        return $this->db->query($sql)->getResult();  
    }

    function setStatusProses($iduser)
    {
        $builder = $this->db->table('tb_deposit');
        $builder->set('status', 'ditolak');
        $builder->where('idanggota', $iduser);
        $builder->where('status', 'diproses');
        $builder->update();
    }

    function insertDeposit($dataset)
    {
        $builder = $this->db->table('tb_deposit');
        $builder->insert($dataset);
    }
    
    function updateBuktiTransfer($iddeposit, $data)
    {
        $builder = $this->db->table('tb_deposit');
        $builder->where('iddeposit', $iddeposit);
        $builder->update($data);
    }
    
    function setStatus($iddeposit, $dataset)
    {
        $builder = $this->db->table('tb_deposit');
        $builder->set($dataset);
        $builder->where('iddeposit', $iddeposit);
        $builder->update();
    }

    function sumDeposit()
    {
        $sql = "
            SELECT (SUM(cash_in) - SUM(cash_out)) 
                + IFNULL((SELECT SUM(nominal) AS total_cicilan FROM tb_cicilan), 0) 
                - IFNULL((SELECT SUM(nominal) AS total_pinjaman FROM tb_pinjaman WHERE status > 2), 0) 
            AS hitung FROM tb_deposit 
            WHERE status = 'diterima'
        ";
        
        return $this->db->query($sql)->getResult();
    }

    function cekSaldoManasukaByUser($iduser)
    {
        $sql = "
            SELECT SUM(cash_in) - SUM(cash_out) AS saldo_manasuka 
            FROM tb_deposit 
            WHERE idanggota = $iduser 
            AND jenis_deposit LIKE 'manasuka%' 
            AND status = 'diterima'
        ";

        return $this->db->query($sql)->getResult();
    }

    function countDepositPendingByUser($iduser)
    {
        $sql = "
            SELECT COUNT(iddeposit) AS hitung 
            FROM tb_deposit 
            WHERE idanggota = $iduser 
            AND status IN ('diproses', 'diproses admin', 'diproses bendahara')
        ";

        return $this->db->query($sql)->getResult();
    }

    function getDepositMemberReport()
    {
        $sql = "
            SELECT 
                tb_user.nama_lengkap AS nama,
                tb_user.nik AS nik,
                (SELECT IFNULL(SUM(sub_a.cash_in), 0) - IFNULL(SUM(sub_a.cash_out), 0) 
                    FROM tb_deposit sub_a 
                    WHERE sub_a.jenis_deposit = 'wajib' 
                    AND sub_a.idanggota = tb_user.iduser
                ) AS wajib, 
                (SELECT IFNULL(SUM(sub_b.cash_in), 0) - IFNULL(SUM(sub_b.cash_out), 0) 
                    FROM tb_deposit sub_b 
                    WHERE sub_b.jenis_deposit = 'pokok' 
                    AND sub_b.idanggota = tb_user.iduser
                ) AS pokok,
                (SELECT IFNULL(SUM(sub_c.cash_in), 0) - IFNULL(SUM(sub_c.cash_out), 0) 
                    FROM tb_deposit sub_c 
                    WHERE sub_c.jenis_deposit = 'manasuka' 
                    AND sub_c.idanggota = tb_user.iduser
                ) AS manasuka,
                IFNULL((SUM(tb_deposit.cash_in) - SUM(tb_deposit.cash_out)), 0) AS total_saldo
            FROM tb_deposit JOIN tb_user ON tb_user.iduser = tb_deposit.idanggota
            GROUP BY tb_user.iduser;
        ";
        return $this->db->query($sql)->getResult();
    }

    function dashboard_getMonthlyGraphic()
    {
        $sql = "
            SELECT *
            FROM(
                SELECT 
                    SUM(cash_in)
                    + (
                        SELECT SUM(nominal)+SUM(bunga)+SUM(provisi) FROM tb_cicilan
                        WHERE DATE_FORMAT(date_created, '%Y-%m') = DATE_FORMAT(a.date_created, '%Y-%m')
                    )
                    - SUM(cash_out)
                    - (
                        SELECT IFNULL(SUM(nominal), 0)
                        FROM tb_pinjaman
                        WHERE DATE_FORMAT(date_created, '%Y-%m') = DATE_FORMAT(a.date_created, '%Y-%m')
                        AND status = 4
                    ) AS saldo,
                    DATE_FORMAT(date_created, '%Y-%m') AS month
                FROM tb_deposit a
                WHERE a.status = 'diterima'
                GROUP BY month
                ORDER BY month DESC
                LIMIT 5
            ) t
            ORDER BY month ASC
        ";
        return $this->db->query($sql)->getResult();
    }

    function getDepositChartByMonths($months = 6)
    {
        $sql = "
            SELECT *
            FROM(
                SELECT 
                    SUM(cash_in)
                    + (
                        SELECT IFNULL(SUM(nominal)+SUM(bunga)+SUM(provisi), 0) FROM tb_cicilan
                        WHERE DATE_FORMAT(date_created, '%Y-%m') = DATE_FORMAT(a.date_created, '%Y-%m')
                    )
                    - SUM(cash_out)
                    - (
                        SELECT IFNULL(SUM(nominal), 0)
                        FROM tb_pinjaman
                        WHERE DATE_FORMAT(date_created, '%Y-%m') = DATE_FORMAT(a.date_created, '%Y-%m')
                        AND status = 4
                    ) AS saldo,
                    COUNT(*) AS count,
                    DATE_FORMAT(date_created, '%Y-%m') AS month,
                    DATE_FORMAT(date_created, '%M %Y') AS month_name
                FROM tb_deposit a
                WHERE a.status = 'diterima'
                AND date_created >= DATE_SUB(NOW(), INTERVAL ? MONTH)
                GROUP BY month
                ORDER BY month DESC
                LIMIT ?
            ) t
            ORDER BY month ASC
        ";
        return $this->db->query($sql, [$months, $months])->getResult();
    }

    function getDepositChartByDateRange($startDate, $endDate)
    {
        $sql = "
            SELECT *
            FROM(
                SELECT 
                    SUM(cash_in)
                    + (
                        SELECT IFNULL(SUM(nominal)+SUM(bunga)+SUM(provisi), 0) FROM tb_cicilan
                        WHERE DATE_FORMAT(date_created, '%Y-%m') = DATE_FORMAT(a.date_created, '%Y-%m')
                    )
                    - SUM(cash_out)
                    - (
                        SELECT IFNULL(SUM(nominal), 0)
                        FROM tb_pinjaman
                        WHERE DATE_FORMAT(date_created, '%Y-%m') = DATE_FORMAT(a.date_created, '%Y-%m')
                        AND status = 4
                    ) AS saldo,
                    COUNT(*) AS count,
                    DATE_FORMAT(date_created, '%Y-%m') AS month,
                    DATE_FORMAT(date_created, '%M %Y') AS month_name
                FROM tb_deposit a
                WHERE a.status = 'diterima'
                AND DATE(date_created) BETWEEN ? AND ?
                GROUP BY month
                ORDER BY month ASC
            ) t
        ";
        return $this->db->query($sql, [$startDate, $endDate])->getResult();
    }
}