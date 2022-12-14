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
            LEFT JOIN tb_user c ON c.iduser = b.idadmin
            LEFT JOIN tb_user d ON d.iduser = b.idbendahara
            WHERE b.idpinjaman = $idpinjaman
        ";

        return $this->db->query($sql)->getResult();
    }

    function getPinjamanByStatus($status)
    {
        $sql = "
            SELECT
                a.username AS username_peminjam,
                a.nama_lengkap AS nama_peminjam,
                a.nik AS nik_peminjam,
                b.*,
                c.nama_lengkap AS nama_admin,
                c.nik AS nik_admin,
                d.nama_lengkap AS nama_bendahara,
                d.nik AS nik_bendahara
            FROM tb_user a 
            JOIN tb_pinjaman b ON a.iduser = b.idanggota
            LEFT JOIN tb_user c ON c.iduser = b.idadmin
            LEFT JOIN tb_user d ON d.iduser = b.idbendahara
            WHERE b.status = $status
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
            LEFT JOIN tb_user c ON c.iduser = b.idadmin
            LEFT JOIN tb_user d ON d.iduser = b.idbendahara
            WHERE b.idanggota = $iduser
        ";

        return $this->db->query($sql)->getResult();
    }

    function countPinjamanAktifByAnggota($iduser)
    {
        $sql = "SELECT count(idpinjaman) AS hitung FROM tb_pinjaman WHERE idanggota = $iduser AND status = 3";
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

    function getAllPinjamanMember()
    {
        $sql = "
            SELECT 
                tb_user.nama_lengkap AS nama,
                tb_user.nik AS nik,
                tb_pinjaman.nominal AS pinjaman,
                IFNULL(SUM(tb_cicilan.nominal), 0) AS terbayar,
                (tb_pinjaman.nominal - IFNULL(SUM(tb_cicilan.nominal), 0)) AS sisa_bayar,
                COUNT(tb_cicilan.idcicilan) AS cicilan_ke,
                tb_pinjaman.angsuran_bulanan AS angsuran
            FROM `tb_pinjaman` 
                JOIN tb_user ON tb_user.iduser = tb_pinjaman.idanggota
                LEFT JOIN tb_cicilan USING (idpinjaman)
            WHERE status = 4
            GROUP BY tb_user.iduser;
        ";

        return $this->db->query($sql)->getResult();
    }
}