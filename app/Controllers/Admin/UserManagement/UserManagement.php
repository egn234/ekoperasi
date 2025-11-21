<?php

namespace App\Controllers\Admin\UserManagement;

/**
 * UserManagement Controller
 * Handles user listing and detail viewing
 */
class UserManagement extends BaseUserController
{
    /**
     * Display user list
     */
    public function list()
    {
        $data = $this->getBaseViewData('User', 'User List');
        return view('admin/user/user-list', $data);
    }

    /**
     * DataTable: Get user data
     */
    public function data_user()
    {	
        $request = service('request');
        $model = $this->m_user;

        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'];

        $model->select('iduser, username, nama_lengkap, instansi, email, nomor_telepon, flag, closebook_request');

        // Filter out soft deleted users
        $model->where('deleted', null);

        $model->groupStart()
            ->like('username', $searchValue)
            ->orLike('nama_lengkap', $searchValue)
            ->groupEnd();

        // Filter for closebook request
        if (isset($_GET['closebook'])) {
            $model->where('closebook_request', 'closebook');
        }

        $recordsFiltered = $model->countAllResults(false);
        $data = $model->asArray()->findAll($length, $start);
        $recordsTotal = $model->countAll();

        $response = [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        return $this->response->setJSON($response);
    }

    /**
     * Display closebook user list
     */
    public function list_closebook()
    {
        $user_list = $this->m_user->getAllClosebookUser();

        $data = $this->getBaseViewData('Closebook User', 'Closebook User List');
        $data['usr_list'] = $user_list;
        
        return view('admin/user/user-closebook-list', $data);
    }

    /**
     * Display user detail
     */
    public function detail_user($iduser = false)
    {
        $group_list = $this->m_group->getAllGroup();
        $detail_user = $this->m_user->getUserById($iduser)[0];

        $data = $this->getBaseViewData('Detail User', 'User Detail');
        $data['det_user'] = $detail_user;
        $data['grp_list'] = $group_list;
        
        return view('admin/user/user-detail', $data);
    }

    /**
     * Show user flag switch confirmation modal (AJAX)
     */
    public function konfirSwitch()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $user = $this->m_user->getUserById($id)[0];
            $data = ['a' => $user];
            echo view('admin/user/part-user-mod-switch', $data);
        }
    }

    /**
     * Show delete user confirmation modal (AJAX)
     */
    public function delete_user_confirm()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $user = $this->m_user->getUserById($id)[0];
            $data = ['a' => $user];
            echo view('admin/user/part-user-mod-delete', $data);
        }
    }

    /**
     * Delete single user (soft delete)
     */
    public function delete_user($iduser = false)
    {
        if (!$iduser) {
            return redirect()->back()->with('notif', view('partials/notification-alert', [
                'notif_text' => 'User ID tidak valid',
                'status' => 'danger'
            ]));
        }

        $user = $this->m_user->getUserById($iduser)[0];

        // Validasi: user harus nonaktif dulu
        if ($user->user_flag == 1) {
            $alert = view('partials/notification-alert', [
                'notif_text' => 'Tidak dapat menghapus user aktif. Silakan nonaktifkan user terlebih dahulu.',
                'status' => 'danger'
            ]);
            session()->setFlashdata('notif', $alert);
            return redirect()->back();
        }

        // Soft delete user
        $this->m_user->softDeleteUser($iduser);

        $alert = view('partials/notification-alert', [
            'notif_text' => 'User ' . $user->nama_lengkap . ' berhasil dihapus',
            'status' => 'success'
        ]);
        session()->setFlashdata('notif', $alert);
        
        return redirect()->back();
    }

    /**
     * Clean all inactive users (batch soft delete)
     */
    public function clean_inactive_users()
    {
        $db = \Config\Database::connect();
        
        // Get all inactive users (flag = 0 and not deleted yet)
        $builder = $db->table('tb_user');
        $builder->where('flag', 0);
        $builder->where('deleted', null);
        $inactiveUsers = $builder->get()->getResult();
        
        $count = count($inactiveUsers);
        
        if ($count == 0) {
            $alert = view('partials/notification-alert', [
                'notif_text' => 'Tidak ada user nonaktif yang dapat dihapus',
                'status' => 'info'
            ]);
            session()->setFlashdata('notif', $alert);
            return redirect()->back();
        }

        // Soft delete all inactive users
        foreach ($inactiveUsers as $user) {
            $this->m_user->softDeleteUser($user->iduser);
        }

        $alert = view('partials/notification-alert', [
            'notif_text' => 'Berhasil menghapus ' . $count . ' user nonaktif',
            'status' => 'success'
        ]);
        session()->setFlashdata('notif', $alert);
        
        return redirect()->back();
    }
}
