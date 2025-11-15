<?php 
namespace App\Models;

use CodeIgniter\Model;

class M_user extends Model
{
    protected $table      = 'tb_user';
    protected $primaryKey = 'iduser';

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'iduser',
        'username',
        'pass',
        'nik',
        'nip',
        'nama_lengkap',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'instansi',
        'unit_kerja',
        'status_pegawai',
        'nomor_telepon',
        'email',
        'nama_bank',
        'no_rek',
        'profil_pic',
        'ktp_file',
        'pass_reset_token',
        'pass_reset_status,',
        'closebook_request',
        'closebook_request_date',
        'closebook_last_updated',
        'closebook_param_count',
        'flag',
        'verified',
        'idgroup',
        'created',
        'updated'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created';
    protected $updatedField  = 'updated';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
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
                nip,
                nama_lengkap, 
                tempat_lahir, 
                tanggal_lahir, 
                status_pegawai, 
                nama_bank, 
                no_rek, 
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
                nip,
                nama_lengkap, 
                tempat_lahir, 
                tanggal_lahir, 
                status_pegawai, 
                nama_bank, 
                no_rek, 
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
                nip,
                nama_lengkap, 
                tempat_lahir, 
                tanggal_lahir, 
                status_pegawai, 
                nama_bank, 
                no_rek, 
                alamat, 
                instansi, 
                unit_kerja, 
                nomor_telepon, 
                email, 
                profil_pic, 
                ktp_file,
                verified,
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
                nip,
                nama_lengkap, 
                tempat_lahir, 
                tanggal_lahir, 
                status_pegawai, 
                nama_bank, 
                no_rek, 
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

    function countAnggotaAKtif()
    {
        $sql = "SELECT count(iduser) AS hitung FROM tb_user WHERE idgroup = 4 AND flag = 1";
        return $this->db->query($sql)->getResult();
    }

    function countMonthlyUser()
    {
        $month = date('m');
        $year = date('Y');
        $sql = "SELECT count(iduser) AS hitung FROM tb_user WHERE idgroup = 4 AND flag = 1 AND MONTH(created) = $month AND YEAR(created) = $year";
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

    function updateUserByUsername($username, $dataset)
    {
        $builder = $this->db->table('tb_user');
        $builder->where('username', $username);
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

    function getUserLoanDeposit()
    {
        $sql = "
            SELECT 
                tb_user.nama_lengkap AS nama,
                tb_user.nik AS nik,
                (SELECT IFNULL(SUM(sub_a.cash_in), 0) - IFNULL(SUM(sub_a.cash_out), 0) 
                    FROM tb_deposit sub_a 
                    WHERE sub_a.jenis_deposit = 'wajib' 
                    AND sub_a.idanggota = tb_user.iduser
                ) AS wajib, 
                (SELECT IFNULL(SUM(sub_b.cash_in), 0) - IFNULL(SUM(sub_b.cash_out), 0) 
                    FROM tb_deposit sub_b 
                    WHERE sub_b.jenis_deposit = 'pokok' 
                    AND sub_b.idanggota = tb_user.iduser
                ) AS pokok,
                (SELECT IFNULL(SUM(sub_c.cash_in), 0) - IFNULL(SUM(sub_c.cash_out), 0) 
                    FROM tb_deposit sub_c 
                    WHERE sub_c.jenis_deposit = 'manasuka' 
                    AND sub_c.idanggota = tb_user.iduser
                ) AS manasuka,
                IFNULL((SUM(tb_deposit.cash_in) - SUM(tb_deposit.cash_out)), 0) AS total_saldo,
                (SELECT sub_d.nominal 
                    FROM tb_pinjaman sub_d 
                    WHERE sub_d.idanggota = tb_user.iduser 
                    AND sub_d.status = 4
                ) AS pinjaman,
                (SELECT IFNULL(SUM(sub_e_a.nominal), 0) 
                    FROM tb_pinjaman sub_e 
                    LEFT JOIN tb_cicilan sub_e_a ON sub_e.idpinjaman = sub_e_a.idpinjaman 
                    WHERE sub_e.idanggota = tb_user.iduser 
                    AND sub_e.status = 4
                ) AS terbayar,
                (SELECT (sub_f.nominal - IFNULL(SUM(sub_f_a.nominal), 0)) 
                    FROM tb_pinjaman sub_f 
                    LEFT JOIN tb_cicilan sub_f_a ON sub_f.idpinjaman = sub_f_a.idpinjaman 
                    WHERE sub_f.idanggota = tb_user.iduser AND sub_f.status = 4
                ) AS sisa_bayar,
                (SELECT COUNT(sub_g_a.idcicilan) 
                    FROM tb_pinjaman sub_g 
                    LEFT JOIN tb_cicilan sub_g_a ON sub_g.idpinjaman = sub_g_a.idpinjaman 
                    WHERE sub_g.idanggota = tb_user.iduser 
                    AND sub_g.status = 4
                ) AS cicilan_ke,
                (SELECT sub_h.angsuran_bulanan 
                    FROM tb_pinjaman sub_h 
                    WHERE sub_h.idanggota = tb_user.iduser 
                    AND sub_h.status = 4
                ) AS angsuran
            FROM tb_user
                LEFT JOIN tb_deposit ON tb_user.iduser = tb_deposit.idanggota
            WHERE tb_user.idgroup = 4
            GROUP BY tb_user.iduser
        ";

        return $this->db->query($sql)->getResult();
    }

    function getUsernameGiat()
    {
        $sql = "SELECT username from tb_user WHERE username LIKE 'GIAT%' ORDER BY username DESC LIMIT 1";
        return $this->db->query($sql)->getResult();
    }

    function getMemberChartByMonths($months = 6)
    {
        $sql = "
            SELECT 
                COUNT(*) AS count,
                COUNT(*) AS saldo,
                DATE_FORMAT(date_created, '%Y-%m') AS month,
                DATE_FORMAT(date_created, '%M %Y') AS month_name
            FROM tb_user
            WHERE verified = 1
            AND date_created >= DATE_SUB(NOW(), INTERVAL ? MONTH)
            GROUP BY month
            ORDER BY month ASC
            LIMIT ?
        ";
        return $this->db->query($sql, [$months, $months])->getResult();
    }

    function getMemberChartByDateRange($startDate, $endDate)
    {
        $sql = "
            SELECT 
                COUNT(*) AS count,
                COUNT(*) AS saldo,
                DATE_FORMAT(date_created, '%Y-%m') AS month,
                DATE_FORMAT(date_created, '%M %Y') AS month_name
            FROM tb_user
            WHERE verified = 1
            AND DATE(date_created) BETWEEN ? AND ?
            GROUP BY month
            ORDER BY month ASC
        ";
        return $this->db->query($sql, [$startDate, $endDate])->getResult();
    }
    
}