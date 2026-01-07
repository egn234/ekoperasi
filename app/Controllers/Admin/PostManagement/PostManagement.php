<?php

namespace App\Controllers\Admin\PostManagement;

use App\Controllers\BaseController;
use App\Models\PostModel;
use App\Models\PostTargetModel;
use App\Models\PostMediaModel;
use App\Models\M_user;
use App\Models\M_group;

class PostManagement extends BaseController
{
  protected $postModel;
  protected $postTargetModel;
  protected $postMediaModel;
  protected $userModel;
  protected $groupModel;

  public function __construct()
  {
    $this->postModel = new PostModel();
    $this->postTargetModel = new PostTargetModel();
    $this->postMediaModel = new PostMediaModel();
    $this->userModel = new M_user(); // Assuming M_user exists from previous context
    $this->groupModel = new M_group(); // Assuming M_group exists
  }

  public function index()
  {
    $data = [
      'title' => 'Daftar Postingan',
      'posts' => $this->postModel->where('deleted_at', null)->orderBy('created_at', 'DESC')->findAll(),
    ];

    // Wrap in proper layout data structure if needed, but for now simple view
    // Ideally we follow the existing admin layout pattern
    return view('admin/post/index', $data);
  }

  public function create()
  {
    $data = [
      'title' => 'Buat Postingan Baru',
      'groups' => $this->groupModel->getAllGroup(), // Check M_group method
      // 'users' => $this->userModel->getAllUser() // Might be too heavy, better use ajax search
    ];
    return view('admin/post/form', $data);
  }

  public function store()
  {
    $rules = [
      'title' => 'required|min_length[3]|max_length[255]',
      'content' => 'required',
      'category' => 'required',
      'target_type' => 'required|in_list[public,group,user]',
      // 'featured_image' => 'uploaded[featured_image]|max_size[featured_image,2048]|is_image[featured_image]' // Optional
    ];

    if (!$this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $db = \Config\Database::connect();
    $db->transStart();

    try {
      // 1. Handle File Upload
      $featuredImagePath = null;
      $file = $this->request->getFile('featured_image');
      if ($file && $file->isValid() && !$file->hasMoved()) {
        $newName = $file->getRandomName();
        $file->move('uploads/posts', $newName);
        $featuredImagePath = 'uploads/posts/' . $newName;
      }

      // 2. Save Post
      $slug = url_title($this->request->getPost('title'), '-', true);
      // Ensure unique slug
      if ($this->postModel->where('slug', $slug)->countAllResults() > 0) {
        $slug .= '-' . time();
      }

      $currentUserId = session()->get('iduser');

      $postData = [
        'title' => $this->request->getPost('title'),
        'slug' => $slug,
        'excerpt' => $this->request->getPost('excerpt') ?: substr(strip_tags($this->request->getPost('content')), 0, 150),
        'content' => $this->request->getPost('content'), // XSS protection might be needed
        'featured_image' => $featuredImagePath,
        'category' => $this->request->getPost('category'),
        'author_id' => $currentUserId,
        'is_published' => !empty($this->request->getPost('status')) ? 1 : 0,
        'is_public' => $this->request->getPost('target_type') === 'public' ? 1 : 0,
        'published_at' => !empty($this->request->getPost('status')) ? date('Y-m-d H:i:s') : null,
      ];

      $this->postModel->insert($postData);
      $postId = $this->postModel->getInsertID();

      // 3. Save Targets
      $targetType = $this->request->getPost('target_type');
      if ($targetType !== 'public') {
        $targets = [];
        if ($targetType === 'group') {
          $groupIds = $this->request->getPost('target_group_id'); // Expecting array
          if (!empty($groupIds)) {
            if (!is_array($groupIds)) $groupIds = [$groupIds];
            foreach ($groupIds as $gid) {
              $targets[] = [
                'post_id' => $postId,
                'target_type' => 'group',
                'target_group_id' => $gid,
                'target_user_id' => null
              ];
            }
          }
        } elseif ($targetType === 'user') {
          $userIds = $this->request->getPost('target_user_id'); // Expecting array or comma separated string handled by Select2
          // If select2 sends array
          if (!empty($userIds)) {
            if (!is_array($userIds)) $userIds = [$userIds];
            foreach ($userIds as $uid) {
              $targets[] = [
                'post_id' => $postId,
                'target_type' => 'user',
                'target_group_id' => null,
                'target_user_id' => $uid
              ];
            }
          }
        }

        if (!empty($targets)) {
          $this->postTargetModel->insertBatch($targets);
        }
      }

      $db->transComplete();

      if ($db->transStatus() === false) {
        return redirect()->back()->withInput()->with('notif', view('partials/notification-alert', [
          'notif_text' => 'Gagal menyimpan postingan via transaction',
          'status' => 'danger'
        ]));
      }

      $saveAction = $this->request->getPost('save_action');
      $notif = view('partials/notification-alert', [
        'notif_text' => 'Postingan berhasil dibuat',
        'status' => 'success'
      ]);

      if ($saveAction === 'save_new') {
        return redirect()->to(base_url('admin/posts/create'))->with('notif', $notif);
      } else {
        return redirect()->to(base_url('admin/posts/edit/' . $postId))->with('notif', $notif);
      }
    } catch (\Exception $e) {
      return redirect()->back()->withInput()->with('notif', view('partials/notification-alert', [
        'notif_text' => 'Gagal menyimpan postingan: ' . $e->getMessage(),
        'status' => 'danger'
      ]));
    }
  }


  public function edit($id)
  {
    $post = $this->postModel->where('id', $id)->first();
    if (!$post) {
      return redirect()->to(base_url('admin/posts'))->with('error', 'Post not found');
    }

    // Get targets
    $targets = $this->postTargetModel->where('post_id', $id)->findAll();
    $targetType = 'public';
    $selectedGroups = [];
    $selectedUsers = [];

    if (!$post->is_public && count($targets) > 0) {
      $targetType = $targets[0]->target_type;
      foreach ($targets as $t) {
        if ($t->target_group_id) $selectedGroups[] = $t->target_group_id;
        if ($t->target_user_id) $selectedUsers[] = $t->target_user_id;
      }
    } elseif (!$post->is_public) {
      // Edge case: targeted but no targets found? 
      $targetType = 'group'; // Default fallback
    }

    $selectedUsersData = [];
    if (!empty($selectedUsers)) {
      $selectedUsersData = $this->userModel->whereIn('iduser', $selectedUsers)->findAll();
    }

    $data = [
      'title' => 'Edit Postingan',
      'post' => $post,
      'groups' => $this->groupModel->getAllGroup(),
      'target_type' => $targetType,
      'selected_groups' => $selectedGroups,
      'selected_users' => $selectedUsers,
      'selected_users_data' => $selectedUsersData
    ];
    return view('admin/post/form', $data);
  }

  public function update($id)
  {
    $rules = [
      'title' => 'required|min_length[3]|max_length[255]',
      'content' => 'required',
      'category' => 'required',
      'target_type' => 'required|in_list[public,group,user]',
    ];

    if (!$this->validate($rules)) {
      return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
    }

    $db = \Config\Database::connect();
    $db->transStart();

    try {
      // 1. Handle File Upload
      $featuredImagePath = $this->request->getPost('old_featured_image');
      $file = $this->request->getFile('featured_image');
      if ($file && $file->isValid() && !$file->hasMoved()) {
        $newName = $file->getRandomName();
        $file->move('uploads/posts', $newName);
        $featuredImagePath = 'uploads/posts/' . $newName;
      }

      // 2. Update Post
      $isPublished = !empty($this->request->getPost('status'));

      $postData = [
        'title' => $this->request->getPost('title'),
        'excerpt' => $this->request->getPost('excerpt') ?: substr(strip_tags($this->request->getPost('content')), 0, 150),
        'content' => $this->request->getPost('content'),
        'featured_image' => $featuredImagePath,
        'category' => $this->request->getPost('category'),
        'is_published' => $isPublished ? 1 : 0,
        'is_public' => $this->request->getPost('target_type') === 'public' ? 1 : 0,
        'updated_at' => date('Y-m-d H:i:s'),
      ];

      // Only update published_at if checking status change to published? 
      // Or just keep original published_at. Let's keep original unless purely unpublished.
      // Optimizing: Check existing post strictly first
      $existingPost = $this->postModel->where('id', $id)->first();
      if ($isPublished && empty($existingPost->published_at)) {
        $postData['published_at'] = date('Y-m-d H:i:s');
      }

      $db->table('posts')->where('id', $id)->update($postData);

      // 3. Update Targets (Delete all and re-insert)
      $this->postTargetModel->where('post_id', $id)->delete(true); // Hard delete targets

      $targetType = $this->request->getPost('target_type');
      if ($targetType !== 'public') {
        $targets = [];
        if ($targetType === 'group') {
          $groupIds = $this->request->getPost('target_group_id');
          if (!empty($groupIds)) {
            if (!is_array($groupIds)) $groupIds = [$groupIds];
            foreach ($groupIds as $gid) {
              $targets[] = [
                'post_id' => $id,
                'target_type' => 'group',
                'target_group_id' => $gid,
                'target_user_id' => null
              ];
            }
          }
        } elseif ($targetType === 'user') {
          $userIds = $this->request->getPost('target_user_id');
          if (!empty($userIds)) {
            if (!is_array($userIds)) $userIds = [$userIds];
            foreach ($userIds as $uid) {
              $targets[] = [
                'post_id' => $id,
                'target_type' => 'user',
                'target_group_id' => null,
                'target_user_id' => $uid
              ];
            }
          }
        }

        if (!empty($targets)) {
          $this->postTargetModel->insertBatch($targets);
        }
      }

      $db->transComplete();

      if ($db->transStatus() === false) {
        return redirect()->back()->withInput()->with('notif', view('partials/notification-alert', [
          'notif_text' => 'Gagal memperbarui postingan',
          'status' => 'danger'
        ]));
      }

      return redirect()->to(base_url('admin/posts'))->with('notif', view('partials/notification-alert', [
        'notif_text' => 'Postingan berhasil diperbarui',
        'status' => 'success'
      ]));
    } catch (\Exception $e) {
      return redirect()->back()->withInput()->with('notif', view('partials/notification-alert', [
        'notif_text' => 'Gagal memperbarui postingan: ' . $e->getMessage(),
        'status' => 'danger'
      ]));
    }
  }

  public function delete($id)
  {
    $this->postModel->delete($id);
    return redirect()->to(base_url('admin/posts'))->with('notif', view('partials/notification-alert', [
      'notif_text' => 'Postingan berhasil dihapus',
      'status' => 'success'
    ]));
  }

  public function uploadImage()
  {
    // For Summernote/TinyMCE image processing
    $file = $this->request->getFile('file'); // Adjust based on editor
    if ($file && $file->isValid() && !$file->hasMoved()) {
      $newName = $file->getRandomName();
      $file->move('uploads/posts/content', $newName);
      return $this->response->setJSON(['location' => base_url('uploads/posts/content/' . $newName)]);
    }
    return $this->response->setStatusCode(400)->setJSON(['error' => 'Upload failed']);
  }
}
