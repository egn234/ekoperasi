<?php 
namespace App\Controllers\Admin;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_monthly_report;
use App\Models\M_pinjaman;

use App\Controllers\Admin\Notifications;

class Registrasi extends Controller
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
        $model = $this->m_user; // Replace with your actual model name

        // Parameters from the DataTable
        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'];

        $model->select('iduser, username, nama_lengkap, instansi, email, nomor_telepon, flag, closebook_request');

        // Start building the query for filtering
        $model->where('verified', 0)
            ->groupStart()
            ->like('username', $searchValue)
                ->orLike('nama_lengkap', $searchValue)
            ->groupEnd();

        // for filtering closebook request
        if(isset($_GET['closebook'])) {
            $model->where('closebook_request', 'closebook');
        }

        $recordsFiltered = $model->countAllResults(false);

        // Fetch data from the model using $start and $length
        $data = $model->asArray()->findAll($length, $start);

        // Total records (you can also use $model->countAll() for exact total)
        $recordsTotal = $model->countAll();

        // Records after filtering (if any)
        $recordsFiltered = $recordsTotal;

        // Prepare the response in the DataTable format
        $response = [
            'draw' => $draw,
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
                'notif_text' => 'Anggota '.$user->nama_lengkap.' telah diverifikasi',
                'status' => 'success',
            ]
        );
                
        $data_session = [ 'notif' => $alert ];
        session()->setFlashdata($data_session);
        return redirect()->back();
    }

    public function detail_user()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $user = $this->m_user->getUserById($id)[0];
            $data = ['a' => $user];
            echo view('admin/register/part-verify-mod-detail', $data);
        }
    }
}