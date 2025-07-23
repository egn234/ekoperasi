<?php
    
namespace App\Models;

use CodeIgniter\Model;

class M_param_manasuka_log extends Model
{
    protected $table      = 'tb_param_manasuka_log';
    protected $primaryKey = 'id';
    
    protected $allowedFields = [
      'nominal',
      'created_at',
      'idmnskparam'
    ];

    protected $returnType = 'array';

    // Dates
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
}