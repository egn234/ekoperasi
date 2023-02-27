<?php 

namespace App\Models;

use CodeIgniter\Model;

class M_param extends Model
{
    protected $table      = 'tb_parameter';
    protected $primaryKey = 'idparameter';

    protected $returnType = 'array';

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

    function getAllParam()
    {
        $sql = "SELECT * FROM tb_parameter";
        return $this->db->query($sql)->getResult();
    }

    function getParamById($idparam)
    {
        $sql = "SELECT * FROM tb_parameter WHERE idparameter = $idparam";
        return $this->db->query($sql)->getResult();
    }

    function getParamSimp()
    {
        $sql = "
            SELECT * FROM tb_parameter 
            WHERE idparameter = 1
            OR idparameter = 2
            OR idparameter = 3
        ";
        return $this->db->query($sql)->getResult();
    }

    function updateParamSimp($idparameter, $nilai, $date)
    {
        $builder = $this->db->table('tb_parameter');
        $builder->set('nilai', $nilai);
        $builder->set('updated', $date);
        $builder->where('idparameter', $idparameter);
        $builder->update();
    }

    function getParamOther()
    {
        $sql = "
            SELECT * FROM tb_parameter 
            WHERE idparameter != 1
            AND idparameter != 2
            AND idparameter != 3
        ";
        return $this->db->query($sql)->getResult();
    }
}