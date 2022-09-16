<?php 
namespace App\Models;

use CodeIgniter\Model;

class M_user extends Model
{
    protected $table      = 'tb_user';
    protected $primaryKey = 'iduser';

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
    
    function getUser($username)
    {
    	$sql = "SELECT * FROM tb_user WHERE username = '$username'";
    	return $this->db->query($sql)->getResult();
    }
    
    function countUsername($username)
    {
      $sql = "SELECT count(iduser) as hitung FROM tb_user WHERE username = '$username'";
      return $this->db->query($sql)->getResult();
    }
    
}