<?php

namespace App\Models;

use CodeIgniter\Model;

class PostTargetModel extends Model
{
  protected $table            = 'post_targets';
  protected $primaryKey       = 'id';
  protected $useAutoIncrement = true;
  protected $returnType       = 'object';
  protected $useSoftDeletes   = false;
  protected $protectFields    = true;
  protected $allowedFields    = [
    'post_id',
    'target_type',
    'target_group_id',
    'target_user_id'
  ];

  // Validation
  protected $validationRules = [
    'post_id'     => 'required|integer',
    'target_type' => 'required|in_list[group,user]',
  ];

  protected $validationMessages = [];

  protected $skipValidation = false;

  // Custom check before insert/update
  protected $beforeInsert = ['validateTargetExclusivity'];
  protected $beforeUpdate = ['validateTargetExclusivity'];

  protected function validateTargetExclusivity(array $data)
  {
    $dataData = $data['data'];

    $type = $dataData['target_type'] ?? null;
    $groupId = $dataData['target_group_id'] ?? null;
    $userId = $dataData['target_user_id'] ?? null;

    if ($type === 'group') {
      if (empty($groupId) || !empty($userId)) {
        throw new \RuntimeException('If target_type is group, target_group_id must be set and target_user_id must be null.');
      }
      // Ensure userId is strictly null for DB insertion if not set
      $data['data']['target_user_id'] = null;
    } elseif ($type === 'user') {
      if (empty($userId) || !empty($groupId)) {
        throw new \RuntimeException('If target_type is user, target_user_id must be set and target_group_id must be null.');
      }
      // Ensure groupId is strictly null for DB insertion if not set
      $data['data']['target_group_id'] = null;
    }

    return $data;
  }
}
