<?php 
namespace App\Controllers\Admin\UserManagement;

class UserExport extends BaseUserController
{
    public function export_table()
    {
        $user_list = $this->m_user->getAllUser();
        $data = [
            'page_title' => 'Ekspor',
            'usr_list' => $user_list
        ];

        echo view('admin/user/export-user', $data);
    }
}
