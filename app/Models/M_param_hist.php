<?php 

namespace App\Models;

use CodeIgniter\Model;

class M_param_hist extends Model
{
    protected $table      = 'tb_parameter_history';
    protected $primaryKey = 'idparameterhistory';

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

    function getAllParamH()
    {
        $sql = "SELECT * FROM tb_parameter_history";
        return $this->db->query($sql)->getResult();
    }

    function getParamById($idparam)
    {
        $sql = "SELECT * FROM tb_parameter_history WHERE idparameter = $idparam";
        return $this->db->query($sql)->getResult();
    }

    function getParamSimp()
    {
        $sql = "
            SELECT * FROM tb_parameter_history 
            WHERE idparameter = 1
            OR idparameter = 2
            OR idparameter = 3
        ";
        return $this->db->query($sql)->getResult();
    }

    function insertParamHist($data)
    {
      $builder = $this->db->table('tb_parameter_history');
      $builder->insert($data);
    }
}