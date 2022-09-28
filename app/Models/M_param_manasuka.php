<?php 

namespace App\Models;

use CodeIgniter\Model;

class M_param_manasuka extends Model
{
    protected $table      = 'tb_param_manasuka';
    protected $primaryKey = 'idmnskparam';

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

    function getAllParam()
    {
        $sql = "SELECT * FROM tb_param_manasuka";
        return $this->db->query($sql)->getResult();
    }

    function getParamByUserId($iduser)
    {
        $sql = "SELECT * FROM tb_param_manasuka WHERE idanggota = $iduser";
        return $this->db->query($sql)->getResult();
    }

    function insertParamManasuka($dataset)
    {
        $builder = $this->db->table('tb_param_manasuka');
        $builder->insert($dataset);
    }

    function updateParamManasuka($idmnskparam, $dataset)
    {
        $builder = $this->db->table('tb_param_manasuka');
        $builder->where('idmnskparam',$idmnskparam);
        $builder->update($dataset);
    }
}