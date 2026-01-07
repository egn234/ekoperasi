<?php

namespace App\Models;

use CodeIgniter\Model;

class PostViewModel extends Model
{
  protected $table            = 'post_views';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $returnType       = 'object';
  protected $allowedFields    = [
    'post_id',
    'user_id',
    'ip_address',
    'viewed_at'
  ];

  protected $useTimestamps = false; // Custom viewed_at handling mainly
  // Actually we can use created_at feature if we map it, but manual control is fine too.

  public function logView($postId, $userId)
  {
    // Check if exists
    $exists = $this->where('post_id', $postId)
      ->where('user_id', $userId)
      ->first();

    if (!$exists) {
      $this->insert([
        'post_id'    => $postId,
        'user_id'    => $userId,
        'ip_address' => \Config\Services::request()->getIPAddress(),
        'viewed_at'  => date('Y-m-d H:i:s'),
      ]);

      // Increment posts view count
      $postModel = new PostModel();
      $postModel->where('id', $postId)->increment('views_count');
    }
  }
}
