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
    
    function getAllUser()
    {  
        $sql = "
            SELECT 
                iduser,
                username, 
                nik, 
                nama_lengkap, 
                tempat_lahir, 
                tanggal_lahir, 
                alamat, 
                instansi, 
                unit_kerja, 
                nomor_telepon, 
                email, 
                profil_pic, 
                tb_user.created AS user_created, 
                tb_user.updated AS user_updated, 
                closebook_request, 
                closebook_request_date, 
                closebook_param_count, 
                tb_user.flag AS user_flag, 
                idgroup,
                tb_group.keterangan AS group_type,
                tb_group.created AS group_assigned,
                tb_group.flag AS group_flag
            FROM tb_user 
            JOIN tb_group USING (idgroup)
        ";
        return $this->db->query($sql)->getResult();
    }
    
    function getUserById($iduser)
    {  
        $sql = "
            SELECT 
                iduser,
                username, 
                nik, 
                nama_lengkap, 
                tempat_lahir, 
                tanggal_lahir, 
                alamat, 
                instansi, 
                unit_kerja, 
                nomor_telepon, 
                email, 
                profil_pic, 
                tb_user.created AS user_created, 
                tb_user.updated AS user_updated, 
                closebook_request, 
                closebook_request_date, 
                closebook_param_count, 
                tb_user.flag AS user_flag, 
                idgroup,
                tb_group.keterangan AS group_type,
                tb_group.created AS group_assigned,
                tb_group.flag AS group_flag
            FROM tb_user 
            JOIN tb_group USING (idgroup)
            WHERE iduser = $iduser
        ";
        return $this->db->query($sql)->getResult();
    }

    function countUsername($username)
    {
        $sql = "SELECT count(iduser) as hitung FROM tb_user WHERE username = '$username'";
        return $this->db->query($sql)->getResult();
    }
    
    function insertUser($data)
    {
      $builder = $this->db->table('tb_user');
      $builder->insert($data);
    }
    
}