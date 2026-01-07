<?php

namespace App\Models;

use CodeIgniter\Model;

class PostModel extends Model
{
  protected $table            = 'posts';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $returnType       = 'object';
  protected $useSoftDeletes   = true;
  protected $protectFields    = true;
  protected $allowedFields    = [
    'title',
    'slug',
    'excerpt',
    'content',
    'featured_image',
    'category',
    'author_id',
    'is_published',
    'is_public',
    'views_count',
    'published_at',
    'deleted_at'
  ];

  // Dates
  protected $useTimestamps = true;
  protected $dateFormat    = 'datetime';
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
  protected $deletedField  = 'deleted_at';

  public function getPublishedPosts($limit = 10, $offset = 0, $search = null)
  {
    $builder = $this->where('is_published', 1)
      ->where('published_at <=', date('Y-m-d H:i:s'));

    if ($search) {
      $builder->groupStart()
        ->like('title', $search)
        ->orLike('content', $search)
        ->groupEnd();
    }

    return $builder->orderBy('published_at', 'DESC')
      ->findAll($limit, $offset);
  }
}
