<?php
namespace App\Models;

use CodeIgniter\Model;

class m_deposit extends Model
{
    protected $table      = 'tb_deposit';
    protected $primaryKey = 'iddeposit';

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
            WHERE tb_deposit.status = 'diproses'
            ORDER BY tb_deposit.date_created DESC
        ";
        
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
        $sql = "SELECT * FROM tb_deposit WHERE idanggota = $iduser ORDER BY date_created DESC";
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
                AND jenis_deposit = 'manasuka'
        ";
        
        return $this->db->query($sql)->getResult();  
    }

    function insertDeposit($dataset)
    {
        $builder = $this->db->table('tb_deposit');
        $builder->insert($dataset);
    }
    
    function updateBuktiTransfer($iddeposit, $bukti_transfer)
    {
        $builder = $this->db->table('tb_deposit');
        $builder->set('bukti_transfer', $bukti_transfer);
        $builder->where('iddeposit', $iddeposit);
        $builder->update();
    }
    
    function setStatus($iddeposit, $dataset)
    {
        $builder = $this->db->table('tb_deposit');
        $builder->set($dataset);
        $builder->where('iddeposit', $iddeposit);
        $builder->update();
    }
}