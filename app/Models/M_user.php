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
    
    function countUser($username)
    {
        $sql = "SELECT count(username) AS hitung FROM tb_user WHERE username = '$username'";
        return $this->db->query($sql)->getResult();
    }
    
    function countNIK($nik)
    {
        $sql = "SELECT count(nik) AS hitung FROM tb_user WHERE nik = '$nik'";
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
                closebook_last_updated, 
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
    
    function getAllClosebookUser()
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
                closebook_last_updated, 
                closebook_param_count, 
                tb_user.flag AS user_flag, 
                idgroup,
                tb_group.keterangan AS group_type,
                tb_group.created AS group_assigned,
                tb_group.flag AS group_flag
            FROM tb_user 
            JOIN tb_group USING (idgroup)
            WHERE tb_user.closebook_request = 'closebook'
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
                closebook_last_updated, 
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

    function getAllAnggotaSaldo()
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
                closebook_last_updated, 
                closebook_param_count, 
                tb_user.flag AS user_flag, 
                idgroup,
                tb_group.keterangan AS group_type,
                tb_group.created AS group_assigned,
                tb_group.flag AS group_flag, 
                (
                    SELECT 
                    SUM(cash_in) - SUM(cash_out) 
                    FROM tb_deposit 
                    WHERE idanggota = iduser
                    AND tb_deposit.status = 'diterima'
                ) AS saldo 
            FROM tb_user
            JOIN tb_group USING (idgroup)
            WHERE idgroup = 4
        ";
        return $this->db->query($sql)->getResult();
    }

    function getPassword($iduser)
    {
        $sql = "SELECT pass FROM tb_user WHERE iduser = $iduser";
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

    function updateUser($iduser, $dataset)
    {
        $builder = $this->db->table('tb_user');
        $builder->where('iduser', $iduser);
        $builder->update($dataset);
    }

    function aktifkanUser($iduser)
    {
        $builder = $this->db->table('tb_user');
        $builder->set('closebook_request', null);
        $builder->set('flag', 1);
        $builder->set('updated', date('Y-m-d H:i:s'));
        $builder->where('iduser', $iduser);
        $builder->update();
    }

    function nonaktifkanUser($iduser)
    {
        $builder = $this->db->table('tb_user');
        $builder->set('flag', 0);
        $builder->where('iduser', $iduser);
        $builder->update();
    }

    function closebookCount($iduser, $status)
    {
        $builder = $this->db->table('tb_user');
        $builder->set('closebook_param_count', $status);
        $builder->where('iduser', $iduser);
        $builder->update();
    }
    
}