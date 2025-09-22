<?php 
namespace App\Controllers\Bendahara;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_deposit_pag;
use App\Models\M_param_manasuka;
use App\Models\M_notification;

use App\Controllers\Bendahara\Notifications;

class Deposits extends Controller
{
    protected $m_user, $m_deposit, $m_deposit_pag, $m_param_manasuka, $m_notification;
    protected $account, $notification;

    function __construct()
    {
        $this->m_user = model(M_user::class);
        $this->m_deposit = model(M_deposit::class);
        $this->m_deposit_pag = model(M_deposit_pag::class);
        $this->m_param_manasuka = model(M_param_manasuka::class);
        $this->m_notification = model(M_notification::class);

        $this->notification = new Notifications();

        $this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
    }

    public function index()
    {
        $data = [
            'title_meta' => view('bendahara/partials/title-meta', ['title' => 'Kelola Anggota']),
            'page_title' => view('bendahara/partials/page-title', ['title' => 'Kelola Anggota', 'li_1' => 'EKoperasi', 'li_2' => 'Kelola Anggota']),
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account
        ];
        
        return view('bendahara/deposit/anggota-list', $data);
    }

    public function list_transaksi()
    {
        $data = [
            'title_meta' => view('bendahara/partials/title-meta', ['title' => 'Kelola Transaksi Simpanan']),
            'page_title' => view('bendahara/partials/page-title', ['title' => 'Kelola Transaksi Simpanan', 'li_1' => 'EKoperasi', 'li_2' => 'Kelola Transaksi Simpanan']),
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account
        ];
        
        return view('bendahara/deposit/deposit-list', $data);
    }

    public function detail_anggota($iduser = false)
    {
        $depo_list = $this->m_deposit->getDepositByUserId($iduser);
        $total_saldo_wajib = $this->m_deposit->getSaldoWajibByUserId($iduser)[0]->saldo;
        $total_saldo_pokok = $this->m_deposit->getSaldoPokokByUserId($iduser)[0]->saldo;
        $total_saldo_manasuka = $this->m_deposit->getSaldoManasukaByUserId($iduser)[0]->saldo;
        $detail_user = $this->m_user->getUserById($iduser)[0];
        $param_manasuka = $this->m_param_manasuka->getParamByUserId($iduser);
        $currentpage = request()->getVar('page_grup1') ? request()->getVar('page_grup1') : 1;
        $deposit_list2 = $this->m_deposit_pag
            ->where('idanggota', $iduser)
            ->orderBy('date_created', 'DESC')
            ->paginate(10, 'grup1');

        $data = [
            'title_meta' => view('bendahara/partials/title-meta', ['title' => 'Detail Simpanan']),
            'page_title' => view('bendahara/partials/page-title', ['title' => 'Detail Simpanan', 'li_1' => 'EKoperasi', 'li_2' => 'Detail Simpanan']),
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account,
            'detail_user' => $detail_user,
            'deposit_list' => $depo_list,
            'total_saldo_wajib' => $total_saldo_wajib,
            'total_saldo_pokok' => $total_saldo_pokok,
            'total_saldo_manasuka' => $total_saldo_manasuka,
            'param_manasuka' => $param_manasuka,
            'deposit_list2' => $deposit_list2,
            'pager' => $this->m_deposit_pag->pager,
            'currentpage' => $currentpage,
        ];
        
        return view('bendahara/deposit/anggota-detail', $data);
    }

    public function konfirmasi_mutasi($iddeposit = false)
    {
        $dataset = [
            'idbendahara' => $this->account->iduser,
            'status' => 'diterima',
            'date_updated' => date('Y-m-d H:i:s')
        ];

        $this->m_deposit->setStatus($iddeposit, $dataset);

        $idanggota = $this->m_deposit->where('iddeposit', $iddeposit)->get()->getResult()[0]->idanggota;
        $jenis_pengajuan = $this->m_deposit->where('iddeposit', $iddeposit)->get()->getResult()[0]->jenis_deposit;

        $message = false;
        if ($jenis_pengajuan == 'penarikan') {
            $message = 'penarikan';
        } else {
            $message = 'penyimpanan';
        };

        $notification_anggota = [
            'bendahara_id' => $this->account->iduser,
            'anggota_id' => $idanggota,
            'deposit_id' => $iddeposit,
            'message' => 'Pengajuan '. $message .' manasuka disetujui oleh bendahara '. $this->account->nama_lengkap,
            'timestamp' => date('Y-m-d H:i:s'),
            'group_type' => 4
        ];

        $this->m_notification->insert($notification_anggota);
        
        $alert = view(
            'partials/notification-alert', 
            [
                'notif_text' => 'Permohonan Berhasil Dikonfirmasi',
                'status' => 'success'
            ]
        );
        
        $data_session = ['notif' => $alert];
        session()->setFlashdata($data_session);
        return redirect()->back();
    }

    public function batalkan_mutasi($iddeposit = false)
    {
        $dataset = [
            'idbendahara' => $this->account->iduser,
            'alasan_tolak' => request()->getPost('alasan_tolak'),
            'status' => 'ditolak',
            'date_updated' => date('Y-m-d H:i:s')
        ];

        $this->m_deposit->setStatus($iddeposit, $dataset);
        
        $idanggota = $this->m_deposit->where('iddeposit', $iddeposit)->get()->getResult()[0]->idanggota;
        $jenis_pengajuan = $this->m_deposit->where('iddeposit', $iddeposit)->get()->getResult()[0]->jenis_deposit;

        $message = false;
        if ($jenis_pengajuan == 'penarikan') {
            $message = 'penarikan';
        } else {
            $message = 'penyimpanan';
        };

        $notification_anggota = [
            'bendahara_id' => $this->account->iduser,
            'anggota_id' => $idanggota,
            'deposit_id' => $iddeposit,
            'message' => 'Pengajuan '. $message .' manasuka ditolak oleh bendahara '. $this->account->nama_lengkap,
            'timestamp' => date('Y-m-d H:i:s'),
            'group_type' => 4
        ];

        $this->m_notification->insert($notification_anggota);

        $alert = view(
            'partials/notification-alert', 
            [
                'notif_text' => 'Permohonan Berhasil Ditolak',
                'status' => 'success'
            ]
        );
        
        $dataset = ['notif' => $alert];
        session()->setFlashdata($dataset);
        return redirect()->back();
    }

    public function detail_mutasi()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $dsimpanan = $this->m_deposit->getDepositById($id)[0];
            $duser = $this->m_user->getUserById($dsimpanan->idanggota)[0];
            $data = [
                'a' => $dsimpanan,
                'duser' => $duser
            ];
            echo view('bendahara/deposit/part-depo-mod-detail', $data);
        }
    }

    public function cancel_mnsk()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $deposit = $this->m_deposit->getDepositById($id)[0];
            $data = [
                'a' => $deposit,
                'flag' => 0
            ];
            echo view('bendahara/deposit/part-depo-mod-approval', $data);
        }
    }

    public function approve_mnsk()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $deposit = $this->m_deposit->getDepositById($id)[0];

            $total_saldo_wajib = $this->m_deposit->getSaldoWajibByUserId($deposit->idanggota)[0]->saldo;
            $total_saldo_pokok = $this->m_deposit->getSaldoPokokByUserId($deposit->idanggota)[0]->saldo;
            $total_saldo_manasuka = $this->m_deposit->getSaldoManasukaByUserId($deposit->idanggota)[0]->saldo;
            $total_saldo = '';

            $confirmation = false;

            if ($deposit->cash_in == 0) {
                if ($deposit->jenis_deposit == 'wajib') {
                    $total_saldo = $total_saldo_wajib;
                    
                    if ($total_saldo_wajib < $deposit->cash_out) {
                        $confirmation = true;
                    } else {
                        $confirmation = false;
                    }
                } elseif ($deposit->jenis_deposit == 'pokok') {
                    $total_saldo = $total_saldo_pokok;
                    
                    if ($total_saldo_pokok < $deposit->cash_out) {
                        $confirmation = true;
                    } else {
                        $confirmation = false;
                    }
                } elseif ($deposit->jenis_deposit == 'manasuka') {
                    $total_saldo = $total_saldo_manasuka;
                    
                    if ($total_saldo_manasuka < $deposit->cash_out) {
                        $confirmation = true;
                    } else {
                        $confirmation = false;
                    }
                }
            }

            $data = [
                'a' => $deposit,
                'flag' => 1,
                'total_saldo' => $total_saldo,
                'confirmation' => $confirmation
            ];
            
            echo view('bendahara/deposit/part-depo-mod-approval', $data);
        }
    }

    public function data_transaksi()
    {
        $request = service('request');
        $model = $this->m_deposit;

        // Parameters from the DataTable
        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'];

        // Fetch data from the model using $start and $length
        $model->select('tb_deposit.*, tb_user.username, tb_user.nama_lengkap, tb_user.email');
        $model->like('nama_lengkap', $searchValue);
        $model->orLike('username', $searchValue);
        $model->orLike('email', $searchValue);
        $model->orLike('status', $searchValue);
        $model->join('tb_user', 'tb_deposit.idanggota = tb_user.iduser');
        $model->orderBy('tb_deposit.date_created', 'DESC');
        $data = $model->asArray()->findAll($length, $start);

        // Total records (you can also use $model->countAll() for exact total)
        $recordsTotal = $model->countAllResults();

        // Records after filtering (if any)
        $model->like('nama_lengkap', $searchValue);
        $model->orLike('username', $searchValue);
        $model->orLike('email', $searchValue);
        $model->orLike('status', $searchValue);
        $model->join('tb_user', 'tb_deposit.idanggota = tb_user.iduser');
        $recordsFiltered = $model->countAllResults();

        // Prepare the response in the DataTable format
        $response = [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ];

        return $this->response->setJSON($response);
    }

    public function data_transaksi_filter()
    {
        $request = service('request');
        $model = $this->m_deposit;

        // Parameters from the DataTable
        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'];

        // Fetch data from the model using $start and $length
        $model->select('tb_deposit.*, tb_user.username, tb_user.nama_lengkap, tb_user.nik, tb_user.email');
        $model->where('tb_deposit.status', 'diproses bendahara');
       
        $model->groupStart()
            ->like('nama_lengkap', $searchValue)
            ->orLike('username', $searchValue)
            ->orLike('email', $searchValue)
            ->orLike('status', $searchValue);
        $model->groupEnd();
       
        $model->join('tb_user', 'tb_deposit.idanggota = tb_user.iduser');
        $model->orderBy('tb_deposit.date_created', 'DESC');
        $data = $model->asArray()->findAll($length, $start);

        // Total records (you can also use $model->countAll() for exact total)
        $model->where('tb_deposit.status', 'diproses bendahara');
        $model->orderBy('tb_deposit.date_created', 'DESC');
        $recordsTotal = $model->countAllResults();

        // Records after filtering (if any)
        $model->where('tb_deposit.status', 'diproses bendahara');
        
        $model->groupStart()
            ->like('nama_lengkap', $searchValue)
            ->orLike('username', $searchValue)
            ->orLike('email', $searchValue)
            ->orLike('status', $searchValue);
        $model->groupEnd();
        
        $model->join('tb_user', 'tb_deposit.idanggota = tb_user.iduser');
        $recordsFiltered = $model->countAllResults();

        // Prepare the response in the DataTable format
        $response = [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ];

        return $this->response->setJSON($response);
    }

    public function data_user()
    {
        $config = new \Config\Encryption();
        $encrypter = \Config\Services::encrypter($config);

        $request = service('request');
        $model = $this->m_user; // Replace with your actual model name

        // Parameters from the DataTable
        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'];

        // Start building the query for filtering
        $model->select('iduser, username, nama_lengkap, instansi, nomor_telepon, email, flag')
        ->where('idgroup', 4)
        ->groupStart()
            ->like('username', $searchValue)
            ->orLike('nama_lengkap', $searchValue)
            ->orLike('email')
        ->groupEnd();

        // Fetch data from the model using $start and $length
        $data = $model->asArray()->findAll($length, $start);
        
        // Decrypt `nomor_telepon` for each user
        foreach ($data as &$row) {
            if (!empty($row['nomor_telepon'])) {
                $row['nomor_telepon'] = $encrypter->decrypt(base64_decode($row['nomor_telepon']));
            }
        }

        // Total records (you can also use $model->countAll() for exact total)
        $model->select('iduser')->where('idgroup', 4);
        $recordsTotal = $model->countAllResults();

        // Records after filtering (if any)
        $model->select('iduser')
        ->where('idgroup', 4)
        ->groupStart()
            ->like('username', $searchValue)
            ->orLike('nama_lengkap', $searchValue)
            ->orLike('nomor_telepon', $searchValue)
        ->groupEnd();
        
        $recordsFiltered = $model->countAllResults();

        // Prepare the response in the DataTable format
        $response = [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ];

        return $this->response->setJSON($response);
    }
}