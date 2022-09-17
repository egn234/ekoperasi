<?php
namespace App\Models;

use CodeIgniter\Model;

class m_group extends Model
{
    protected $table      = 'tb_group';
    protected $primaryKey = 'idgroup';

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

    function getAllGroup()
    {
    	$sql = "SELECT * FROM tb_group";
    	return $this->db->query($sql)->getResult();    
    }
}