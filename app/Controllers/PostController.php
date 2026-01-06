<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PostModel;
use App\Models\PostTargetModel;
use App\Models\PostViewModel;
use App\Models\PostMediaModel;

class PostController extends BaseController
{
  protected $postModel;
  protected $postTargetModel;
  protected $postViewModel;
  protected $postMediaModel;

  public function __construct()
  {
    $this->postModel = new PostModel();
    $this->postTargetModel = new PostTargetModel();
    $this->postViewModel = new PostViewModel();
    $this->postMediaModel = new PostMediaModel();
  }

  public function index()
  {
    $currentUserId = session()->get('iduser');
    $currentUserGroup = session()->get('idgroup');

    $search = $this->request->getGet('search');

    // Complex query to get relevant posts
    // 1. Get Public Posts
    // 2. Get Group Targeted Posts
    // 3. Get User Targeted Posts

    $db = \Config\Database::connect();
    $builder = $db->table('posts');
    $builder->select('posts.*, tb_user.nama_lengkap as author_name');
    $builder->join('tb_user', 'tb_user.iduser = posts.author_id');
    $builder->where('posts.is_published', 1);
    $builder->where('posts.deleted_at', null); // Soft delete check if using query builder directly
    // Ensure published_at is passed
    $builder->where('posts.published_at <=', date('Y-m-d H:i:s'));

    $builder->groupStart();
    $builder->where('posts.is_public', 1);
    if ($currentUserId) {
      // If logged in, check targets
      $builder->orWhereIn('posts.id', function ($subQuery) use ($currentUserId, $currentUserGroup) {
        $subQuery->select('post_id')->from('post_targets');
        $subQuery->groupStart();
        if ($currentUserGroup) $subQuery->where('target_group_id', $currentUserGroup);
        $subQuery->orWhere('target_user_id', $currentUserId);
        $subQuery->groupEnd();
      });
    }
    $builder->groupEnd();

    if ($search) {
      $builder->groupStart()
        ->like('posts.title', $search)
        ->orLike('posts.content', $search)
        ->orLike('posts.category', $search)
        ->groupEnd();
    }

    $builder->orderBy('posts.published_at', 'DESC');

    // Pagination logic could be added here, simplified for now
    $posts = $builder->get()->getResult();

    $data = [
      'title' => 'Pusat Informasi',
      'posts' => $posts,
      'search' => $search
    ];

    return view('post/index', $data);
  }

  public function detail($slug)
  {
    $currentUserId = session()->get('iduser');

    $post = $this->postModel->where('slug', $slug)
      ->where('is_published', 1)
      ->first();

    if (!$post) {
      throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    // Access Check
    if (!$post->is_public) {
      if (!$currentUserId) {
        return redirect()->to('login')->with('error', 'Silakan login untuk mengakses konten ini.');
      }
      // Verify target
      $hasAccess = false;
      // Check if user is author (admin/bendahara)? Or just check targets?
      // Assuming admin/bendahara can view everything? 
      // For now stick to target logic. 
      // If user is Admin (1) or Bendahara (2), maybe allow?
      $groupId = session()->get('idgroup');
      if (in_array($groupId, [1, 2])) {
        $hasAccess = true;
      } else {
        // Check DB targets
        $target = $this->postTargetModel->groupStart()
          ->where('post_id', $post->id)
          ->groupStart()
          ->where('target_group_id', $groupId)
          ->orWhere('target_user_id', $currentUserId)
          ->groupEnd()
          ->groupEnd()
          ->countAllResults();
        if ($target > 0) $hasAccess = true;
      }

      if (!$hasAccess) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
      }
    }

    // Increment Views
    if ($currentUserId) {
      $this->postViewModel->logView($post->id, $currentUserId);
    }

    // Get Media
    $media = $this->postMediaModel->where('post_id', $post->id)->orderBy('display_order', 'ASC')->findAll();

    $data = [
      'title' => $post->title,
      'post' => $post,
      'media' => $media
    ];

    return view('post/detail', $data);
  }
}
