<?php
namespace App\Models;

use CodeIgniter\Model;

class m_monthly_report extends Model
{
    protected $table      = 'tb_monthly_report';
    protected $primaryKey = 'idreportm';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    function __construct()
    {
    	$this->db = db_connect();
    }

    function getAllMonthlyReport()
    {
    	$sql = "SELECT * FROM tb_monthly_report";
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

    function getPinjamanAktifByAnggota($iduser)
    {
        $sql = "SELECT * FROM tb_pinjaman WHERE idanggota = $iduser AND status = 3";
        return $this->db->query($sql)->getResult();
    }

    function countPinjamanAktifByAnggota($iduser)
    {
        $sql = "SELECT count(idpinjaman) AS hitung FROM tb_pinjaman WHERE idanggota = $iduser AND status = 3";
        return $this->db->query($sql)->getResult();
    }

    function countCicilanByPinjaman($idpinjaman)
    {
        $sql = "SELECT count(idcicilan) AS hitung FROM tb_cicilan WHERE idpinjaman = $idpinjaman";
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

    //FUNCTION UNTUK DOWNLOAD EXCEL
    function getSumSimpanan1($iduser, $datetime)
    {
        $bulan = date('m', strtotime($datetime));
        $sql = "
            SELECT 
                SUM(cash_in)-SUM(cash_out) AS nominal 
                FROM tb_deposit 
                WHERE idanggota = $iduser 
                    AND status = 'diterima'
                    AND MONTH(date_created) = $bulan
                    AND 
                    (
                        jenis_deposit = 'pokok'
                        OR jenis_deposit = 'wajib'
                    )
        ";

        return $this->db->query($sql)->getResult();
    }

    function getSumSimpanan2($iduser, $datetime)
    {
        $bulan = date('m', strtotime($datetime));
        $sql = "
            SELECT 
                SUM(cash_in)-SUM(cash_out) AS nominal 
                FROM tb_deposit 
                WHERE idanggota = $iduser 
                    AND status = 'diterima'
                    AND MONTH(date_created) = $bulan
                    AND jenis_deposit = 'manasuka'
        ";

        return $this->db->query($sql)->getResult();
    }

    function getHitunganPinjaman($iduser, $datetime)
    {
        $bulan = date('m', strtotime($datetime));
        $sql = "
            SELECT 
                (SELECT nominal FROM tb_cicilan WHERE tb_cicilan.idpinjaman = tb_pinjaman.idpinjaman AND MONTH(date_created) = $bulan LIMIT 1) AS nominal,
                (SELECT bunga FROM tb_cicilan WHERE tb_cicilan.idpinjaman = tb_pinjaman.idpinjaman AND MONTH(date_created) = $bulan LIMIT 1) AS bunga,
                (SELECT provisi FROM tb_cicilan WHERE tb_cicilan.idpinjaman = tb_pinjaman.idpinjaman AND MONTH(date_created) = $bulan LIMIT 1) AS provisi
            FROM tb_pinjaman WHERE idanggota = $iduser;
        ";

        return $this->db->query($sql)->getResult();
    }
}