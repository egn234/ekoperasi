<?php
namespace App\Models;

use CodeIgniter\Model;

class m_monthly_report extends Model
{
    protected $table      = 'tb_monthly_report';
    protected $primaryKey = 'idreportm';

    protected $returnType = 'array';

    protected $allowedFields = [
        'date_monthly',
        'file',
        'flag'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created';
    protected $updatedField  = 'updated';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    function __construct()
    {
    	$this->db = db_connect();
    }

    function getPrevMonth($idreportm){
        $sql = "SELECT * FROM tb_monthly_report
            WHERE idreportm < (SELECT idreportm FROM tb_monthly_report where idreportm = $idreportm)
            ORDER BY created DESC
            LIMIT 1";
            
        return $this->db->query($sql)->getResult();
    }

    function getAllMonthlyReport()
    {
    	$sql = "SELECT * FROM tb_monthly_report ORDER BY date_monthly DESC";
    	return $this->db->query($sql)->getResult();    
    }

    function getAllIdAnggotaAktif()
    {
        $sql = "SELECT * FROM tb_user WHERE idgroup = 4 AND flag = 1";
        return $this->db->query($sql)->getResult();
    }

    function getMonthlyReportById($idreportm)
    {
        $sql = "SELECT * FROM tb_monthly_report WHERE idreportm = $idreportm";
        return $this->db->query($sql)->getResult();
    }

    function getMonthlyReportByDate($date)
    {
        $sql = "SELECT * FROM tb_monthly_report WHERE date_monthly LIKE '$date%'";
        return $this->db->query($sql)->getResult();
    }

    function getPinjamanAktifByAnggota($iduser, $startDate, $endDate)
    {
        $sql = "
            select p.* from tb_pinjaman p 
            join tb_cicilan c on p.idpinjaman = c.idpinjaman
            where c.date_created between '$startDate' and '$endDate'
            and p.idanggota = $iduser;
        ";
        return $this->db->query($sql)->getResult();
    }

    function countPinjamanAktifByAnggota($iduser)
    {
        $sql = "SELECT count(idpinjaman) AS hitung FROM tb_pinjaman WHERE idanggota = $iduser AND status = 4";
        return $this->db->query($sql)->getResult();
    }

    function countCicilanByPinjaman($idpinjaman, $startDate, $endDate)
    {
        $sql = "
            select
                (
                    select count(c1.idcicilan) from tb_cicilan c1
                    where c1.idpinjaman = p.idpinjaman
                    and date_created between p.date_created and '$endDate'
                ) as hitung
            from tb_pinjaman p 
            join tb_cicilan c on p.idpinjaman = c.idpinjaman
            where c.date_created between '$startDate' and '$endDate'
            and p.idpinjaman = $idpinjaman
        ";
        return $this->db->query($sql)->getResult();        
    }

    function countReportCurrentMonth()
    {
        $bulan = date('m');
        $sql = "
            SELECT count(idreportm) AS hitung 
            FROM tb_monthly_report 
            WHERE MONTH(date_monthly) = $bulan
        ";

        return $this->db->query($sql)->getResult();
    }

    function countNewWajibMonthlyByUser($iduser)
    {
        $year = date('Y');
        $month = date('m');
        $sql = "
            SELECT count(idanggota) AS hitung FROM tb_deposit 
                WHERE MONTH(date_created) = $month
                AND YEAR(date_created) = $year
                AND deskripsi = 'biaya awal registrasi'
                AND status = 'diproses'
                AND idanggota = $iduser
        ";

        return $this->db->query($sql)->getResult();
    }

    function setNewWajibMonthlyByUser($iduser)
    {
        $year = date('Y');
        $month = date('m');
        $now = date('Y-m-d H:i:s');

        $sql = "
            UPDATE tb_deposit 
                SET status = 'diterima',
                    date_updated = '$now'
                WHERE MONTH(date_created) = $month
                AND YEAR(date_created) = $year
                AND deskripsi = 'biaya awal registrasi'
                AND idanggota = $iduser
        ";

        $this->db->query($sql);
    }

    function insertMonthlyReport($data)
    {
        $builder = $this->db->table('tb_monthly_report');
        $builder->insert($data);
    }

    function updateMonthlyReport($idreportm, $data)
    {
        $builder = $this->db->table('tb_monthly_report');
        $builder->where('idreportm', $idreportm);
        $builder->update($data);
    }

    function sumMonthlyIncome()
    {
        $year = date('Y');
        $month = date('m');
        $sql = "
            SELECT SUM(cash_in) 
                + IFNULL(
                    (SELECT 
                        SUM(nominal) AS total_cicilan 
                        FROM tb_cicilan 
                        WHERE YEAR(date_created) = $year 
                        AND MONTH(date_created) = $month 
                    ), 0
                ) 
            AS hitung FROM tb_deposit
            WHERE status = 'diterima'
            AND YEAR(date_created) = $year 
            AND MONTH(date_created) = $month
        ";
        
        return $this->db->query($sql)->getResult();
    }

    function sumMonthlyOutcome()
    {
        $year = date('Y');
        $month = date('m');

        $sql = "
            SELECT SUM(cash_out) 
                + IFNULL(
                    (SELECT 
                        SUM(nominal) AS total_pinjaman 
                        FROM tb_pinjaman 
                        WHERE status > 2 
                        AND YEAR(date_created) = $year 
                        AND MONTH(date_created) = $month 
                    ), 0
                )
            AS hitung FROM tb_deposit 
            WHERE status = 'diterima'
            AND YEAR(date_created) = $year 
            AND MONTH(date_created) = $month
        ";

        return $this->db->query($sql)->getResult();
    }

    function countMonthlyAnggotaPinjaman()
    {
        $sql = "
            SELECT count(iduser) AS hitung 
            FROM tb_user 
            JOIN tb_pinjaman ON tb_user.iduser = tb_pinjaman.idanggota 
            WHERE status = 4
        ";

        return $this->db->query($sql)->getResult();
    }

    //FUNCTION UNTUK DOWNLOAD EXCEL
    function getSumSimpanan1($iduser, $startDate, $endDate)
    {
        $sql = "
            SELECT 
                SUM(cash_in)-SUM(cash_out) AS nominal 
                FROM tb_deposit 
                WHERE idanggota = $iduser 
                    AND status = 'diterima'
                    AND deskripsi NOT IN ('saldo pokok', 'saldo wajib')
                    AND date_created BETWEEN '$startDate' AND '$endDate'
                    AND jenis_deposit IN ('pokok', 'wajib')
        ";

        return $this->db->query($sql)->getResult();
    }

    function getSumSimpanan2($iduser, $startDate, $endDate)
    {
        $sql = "
            SELECT 
                SUM(cash_in) AS nominal 
                FROM tb_deposit 
                WHERE idanggota = $iduser 
                    AND status = 'diterima'
                    AND deskripsi != 'saldo manasuka'
                    AND date_created BETWEEN '$startDate' AND '$endDate'
                    AND jenis_deposit IN ('manasuka', 'manasuka free')
        ";

        return $this->db->query($sql)->getResult();
    }

    function getHitunganPinjaman($iduser, $startDate, $endDate)
    {
        $sql = "
            SELECT 
                (SELECT SUM(nominal) FROM tb_cicilan WHERE tb_cicilan.idpinjaman = tb_pinjaman.idpinjaman AND tipe_bayar = 'otomatis' AND date_created BETWEEN '$startDate' AND '$endDate' LIMIT 1) AS nominal,
                (SELECT SUM(bunga) FROM tb_cicilan WHERE tb_cicilan.idpinjaman = tb_pinjaman.idpinjaman AND tipe_bayar = 'otomatis' AND date_created BETWEEN '$startDate' AND '$endDate' LIMIT 1) AS bunga,
                (SELECT SUM(provisi) FROM tb_cicilan WHERE tb_cicilan.idpinjaman = tb_pinjaman.idpinjaman AND tipe_bayar = 'otomatis' AND date_created BETWEEN '$startDate' AND '$endDate' LIMIT 1) AS provisi
            FROM tb_pinjaman WHERE idanggota = $iduser
            AND status = 4;
        ";

        return $this->db->query($sql)->getResult();
    }
    
    function getHitunganPinjaman2($iduser, $startDate, $endDate)
    {
        $sql = "
            SELECT 
                (SELECT SUM(nominal) FROM tb_cicilan WHERE tb_cicilan.idpinjaman = tb_pinjaman.idpinjaman AND date_created BETWEEN '$startDate' AND '$endDate' LIMIT 1) AS nominal,
                (SELECT SUM(bunga) FROM tb_cicilan WHERE tb_cicilan.idpinjaman = tb_pinjaman.idpinjaman AND date_created BETWEEN '$startDate' AND '$endDate' LIMIT 1) AS bunga,
                (SELECT SUM(provisi) FROM tb_cicilan WHERE tb_cicilan.idpinjaman = tb_pinjaman.idpinjaman AND date_created BETWEEN '$startDate' AND '$endDate' LIMIT 1) AS provisi
            FROM tb_pinjaman 
            WHERE idanggota = $iduser
            AND status = 4;
        ";

        return $this->db->query($sql)->getResult();
    }
}