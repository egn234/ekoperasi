<?php
namespace App\Models;

use CodeIgniter\Model;

class m_pinjaman extends Model
{
    protected $table      = 'tb_pinjaman';
    protected $primaryKey = 'idpinjaman';

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

    function getAllPinjaman()
    {
    	$sql = "SELECT * FROM tb_pinjaman";
    	return $this->db->query($sql)->getResult();    
    }

    function getPinjamanById($idpinjaman)
    {
        $sql = "
            SELECT
                a.nama_lengkap AS nama_peminjam,
                a.nik AS nik_peminjam,
                b.*,
                c.nama_lengkap AS nama_admin,
                c.nik AS nik_admin,
                d.nama_lengkap AS nama_bendahara,
                d.nik AS nik_bendahara
            FROM tb_user a 
            JOIN tb_pinjaman b ON a.iduser = b.idanggota
            JOIN tb_user c ON c.iduser = b.idadmin
            JOIN tb_user d ON d.iduser = b.idbendahara
            WHERE b.idpinjaman = $idpinjaman
        ";

        return $this->db->query($sql)->getResult();
    }

    function getPinjamanByIdAnggota($iduser)
    {
        $sql = "
            SELECT
                a.nama_lengkap AS nama_peminjam,
                a.nik AS nik_peminjam,
                b.*,
                c.nama_lengkap AS nama_admin,
                c.nik AS nik_admin,
                d.nama_lengkap AS nama_bendahara,
                d.nik AS nik_bendahara
            FROM tb_user a 
            JOIN tb_pinjaman b ON a.iduser = b.idanggota
            JOIN tb_user c ON c.iduser = b.idadmin
            JOIN tb_user d ON d.iduser = b.idbendahara
            WHERE b.idanggota = $iduser
        ";

        return $this->db->query($sql)->getResult();
    }
}