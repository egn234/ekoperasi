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
    	$sql = "SELECT * FROM tb_deposit";
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

    function getSaldoByUserId($iduser)
    {
        $sql = "SELECT SUM(cash_in)-SUM(cash_out) AS saldo FROM tb_deposit WHERE idanggota = $iduser AND status = 'diterima'";
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
}