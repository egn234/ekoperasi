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
                d.nama_lengkap AS nama_admin,
                d.nik AS nik_admin,
                e.nama_lengkap AS nama_bendahara,
                e.nik AS nik_bendahara
            FROM tb_user a 
            JOIN tb_pinjaman b ON a.iduser = b.idanggota
            LEFT JOIN tb_user c ON c.iduser = b.idadmin
            LEFT JOIN tb_user d ON d.iduser = b.idketua
            LEFT JOIN tb_user e ON e.iduser = b.idbendahara
            WHERE b.idpinjaman = $idpinjaman
        ";

        return $this->db->query($sql)->getResult();
    }

    function getAllPinjamanAdmin()
    {
        $sql = "
            SELECT
                a.nama_lengkap AS nama_peminjam,
                a.nik AS nik_peminjam,
                b.*,
                c.nama_lengkap AS nama_admin,
                c.nik AS nik_admin,
                d.nama_lengkap AS nama_admin,
                d.nik AS nik_admin,
                e.nama_lengkap AS nama_bendahara,
                e.nik AS nik_bendahara
            FROM tb_user a 
            JOIN tb_pinjaman b ON a.iduser = b.idanggota
            LEFT JOIN tb_user c ON c.iduser = b.idadmin
            LEFT JOIN tb_user d ON d.iduser = b.idketua
            LEFT JOIN tb_user e ON e.iduser = b.idbendahara
            WHERE b.status = 1
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
                d.nama_lengkap AS nama_admin,
                d.nik AS nik_admin,
                e.nama_lengkap AS nama_bendahara,
                e.nik AS nik_bendahara
            FROM tb_user a 
            JOIN tb_pinjaman b ON a.iduser = b.idanggota
            LEFT JOIN tb_user c ON c.iduser = b.idadmin
            LEFT JOIN tb_user d ON d.iduser = b.idketua
            LEFT JOIN tb_user e ON e.iduser = b.idbendahara
            WHERE b.idanggota = $iduser
        ";

        return $this->db->query($sql)->getResult();
    }

    function insertPinjaman($data)
    {
        $builder = $this->db->table('tb_pinjaman');
        $builder->insert($data);
    }

    function updatePinjaman($idpinjaman, $data)
    {
        $builder = $this->db->table('tb_pinjaman');
        $builder->where('idpinjaman', $idpinjaman);
        $builder->update($data);
    }
}