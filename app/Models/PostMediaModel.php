<?php

namespace App\Models;

use CodeIgniter\Model;

class PostMediaModel extends Model
{
  protected $table            = 'post_media';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $returnType       = 'object';
  protected $allowedFields    = [
    'post_id',
    'media_type',
    'file_path',
    'file_name',
    'file_size',
    'display_order',
    'created_at'
  ];

  protected $useTimestamps = true;
  protected $createdField  = 'created_at';
  protected $updatedField  = ''; // No updated_at
}
