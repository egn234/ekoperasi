<?php namespace App\Models;

use CodeIgniter\Model;

class m_notification extends Model
{
    protected $table      = 'notification_log';
    protected $primaryKey = 'id';

    protected $returnType = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'cicilan_id',
        'deposit_id',
        'admin_id',
        'bendahara_id',
        'ketua_id',
        'anggota_id',
        'parameter_id',
        'pinjaman_id',
        'closebook',
        'message',
        'timestamp',
        'group_type',
        'status'
    ];

    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}