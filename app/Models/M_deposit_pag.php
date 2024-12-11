<?php namespace App\Models;

use CodeIgniter\Model;

class M_deposit_pag extends Model
{
    protected $table      = 'tb_deposit';

    protected $allowedFields = [];

    protected $useTimestamps = true;
    protected $createdField  = 'date_created';
    protected $updatedField  = 'date_updated';

}