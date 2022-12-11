<?php
namespace App\Models;

use CodeIgniter\Model;

class m_cicilan extends Model
{
    protected $table      = 'tb_cicilan';
    protected $primaryKey = 'idcicilan';

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

    function getAllCicilan()
    {
    	$sql = "SELECT * FROM tb_cicilan";
    	return $this->db->query($sql)->getResult();    
    }

    function getCicilanById($idcicilan)
    {
        $sql = "SELECT * FROM tb_cicilan WHERE idcicilan = $idcicilan";
        return $this->db->query($sql)->getResult();
    }

    function getCicilanByIdPinjaman($idpinjaman)
    {
        $sql = "SELECT * FROM tb_cicilan WHERE idpinjaman = $idpinjaman";
        return $this->db->query($sql)->getResult(); 
    }

    function getSaldoTerbayarByIdPinjaman($idpinjaman)
    {
        $sql = "
            SELECT sum(nominal) AS tagihan_lunas 
            FROM tb_cicilan 
            WHERE idpinjaman = $idpinjaman
        ";
       
        return $this->db->query($sql)->getResult();   
    }
    
    function insertCicilan($data)
    {
        $builder = $this->db->table('tb_cicilan');
        $builder->insert($data);
    }

    function updateCicilan($idcicilan, $data)
    {
        $builder = $this->db->table('tb_cicilan');
        $builder->where('idcicilan', $idcicilan);
        $builder->update($data);
    }
}