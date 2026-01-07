<?php

namespace App\Controllers\Admin\UserManagement;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_monthly_report;
use App\Models\M_pinjaman;

use App\Controllers\Admin\Core\Notifications;

class Registration extends Controller
{
    protected $m_user, $m_deposit, $m_monthly_report, $m_pinjaman;
    protected $notification;
    protected $account;

    function __construct()
    {
        $this->m_user = model(M_user::class);
        $this->m_deposit = model(M_deposit::class);
        $this->m_monthly_report = model(M_monthly_report::class);
        $this->m_pinjaman = model(M_pinjaman::class);

        $this->notification = new Notifications();
        $this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
    }

    public function list()
    {
        $data = [
            'title_meta' => view('admin/partials/title-meta', ['title' => 'Register']),
            'page_title' => view('admin/partials/page-title', ['title' => 'Register List', 'li_1' => 'EKoperasi', 'li_2' => 'Verify Register']),
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account
        ];

        echo view('admin/register/user-list', $data);
    }

    public function data_user()
    {
        $request = service('request');
        $model = $this->m_user;

        // Parameters from the DataTable
        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'] ?? '';

        // 1. Total records (only unverified members)
        $recordsTotal = $model->where('verified', 0)
            ->where('deleted', null)
            ->countAllResults();

        // 2. Build query for filtered count and data fetch
        $model->select('iduser, username, nama_lengkap, instansi, email, nomor_telepon, flag, closebook_request, profil_pic');
        $model->where('verified', 0)
            ->where('deleted', null);

        // Apply search filter if present
        if (!empty($searchValue)) {
            $model->groupStart()
                ->like('username', $searchValue)
                ->orLike('nama_lengkap', $searchValue)
                ->groupEnd();
        }

        // 3. Records after filtering (if any search term is applied)
        $recordsFiltered = $model->countAllResults(false);

        // 4. Fetch data with pagination
        $data = $model->asArray()->findAll($length, $start);

        // Prepare the response in the DataTable format
        $response = [
            'draw' => (int)$draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ];

        return $this->response->setJSON($response);
    }

    public function verify_user($id)
    {
        $user = $this->m_user->getUserById($id)[0];
        $db = \Config\Database::connect();
        $builder = $db->table('tb_user');
        $builder->set('verified', 1);
        $builder->where('iduser', $id);
        $builder->update();

        $alert = view(
            'partials/notification-alert',
            [
                'notif_text' => 'Anggota ' . $user->nama_lengkap . ' telah diverifikasi',
                'status' => 'success',
            ]
        );

        $data_session = ['notif' => $alert];
        session()->setFlashdata($data_session);
        return redirect()->to('admin/register/list');
    }

    public function detail_user()
    {
        $id = $this->request->getPost('rowid');
        if ($id) {
            $user = $this->m_user->getUserById($id)[0];
            $data = ['a' => $user];
            echo view('admin/register/part-verify-mod-detail', $data);
        }
    }
}
