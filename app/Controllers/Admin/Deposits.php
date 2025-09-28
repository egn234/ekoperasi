<?php 
namespace App\Controllers\Admin;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_deposit_pag;
use App\Models\M_param_manasuka;
use App\Models\M_param_manasuka_log;
use App\Models\M_notification;

use App\Controllers\Admin\Notifications;

class Deposits extends Controller
{
    protected $m_user, $m_deposit, $m_deposit_pag, $m_param_manasuka, $m_param_manasuka_log, $m_notification;
    protected $notification;
    protected $account;

    function __construct()
    {
        $this->m_user = model(M_user::class);
        $this->m_deposit = model(M_deposit::class);
        $this->m_deposit_pag = model(M_deposit_pag::class);
        $this->m_param_manasuka = model(M_param_manasuka::class);
        $this->m_param_manasuka_log = model(M_param_manasuka_log::class);
        $this->m_notification = model(M_notification::class);

        $this->notification = new Notifications();

        $this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
    }

    // DAFTAR ANGGOTA
    public function index()
    {
        $data = [
            'title_meta' => view('admin/partials/title-meta', ['title' => 'Kelola Anggota']),
            'page_title' => view('admin/partials/page-title', ['title' => 'Kelola Anggota', 'li_1' => 'EKoperasi', 'li_2' => 'Kelola Anggota']),
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account
        ];
        
        return view('admin/deposit/anggota-list', $data);
    }

    public function detail_anggota($iduser = false)
    {
        $depo_list = $this->m_deposit->getDepositByUserId($iduser);
        $total_saldo_wajib = $this->m_deposit->getSaldoWajibByUserId($iduser)[0]->saldo;
        $total_saldo_pokok = $this->m_deposit->getSaldoPokokByUserId($iduser)[0]->saldo;
        $total_saldo_manasuka = $this->m_deposit->getSaldoManasukaByUserId($iduser)[0]->saldo;
        $detail_user = $this->m_user->getUserById($iduser)[0];
        $param_manasuka = $this->m_param_manasuka->getParamByUserId($iduser) ? $this->m_param_manasuka->getParamByUserId($iduser) : false;
        $currentpage = request()->getVar('page_grup1') ? request()->getVar('page_grup1') : 1;

        if ($param_manasuka) {
            $mnsk_param_log = $this->m_param_manasuka_log->select("COUNT(id) as hitung")
            ->where('idmnskparam', $param_manasuka[0]->idmnskparam)
            ->get()->getResult()[0]
            ->hitung != 0
            ? $this->m_param_manasuka_log->where('idmnskparam', $param_manasuka[0]->idmnskparam)
                ->limit(1)
                ->get()
                ->getResult()[0]
                ->created_at 
            : date('Y-m-d H:i:s', strtotime('-3 months'));			
        } else {
            $mnsk_param_log = date('Y-m-d H:i:s', strtotime('-3 months'));
        }

        $data = [
            'title_meta' => view('admin/partials/title-meta', ['title' => 'Detail Simpanan']),
            'page_title' => view('admin/partials/page-title', ['title' => 'Detail Simpanan', 'li_1' => 'EKoperasi', 'li_2' => 'Detail Simpanan']),
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account,
            'detail_user' => $detail_user,
            'deposit_list' => $depo_list,
            'total_saldo_wajib' => $total_saldo_wajib,
            'total_saldo_pokok' => $total_saldo_pokok,
            'total_saldo_manasuka' => $total_saldo_manasuka,
            'param_manasuka' => $param_manasuka,
            'param_manasuka_cek' => $mnsk_param_log,
            'deposit_list2' => $this->m_deposit_pag
                ->where('idanggota', $iduser)
                ->orderBy('date_created', 'DESC')
                ->paginate(10, 'grup1'),

            'pager' => $this->m_deposit_pag->pager,
            'currentpage' => $currentpage
        ];
        
        return view('admin/deposit/anggota-detail', $data);
    }

    public function add_proc()
    {
        $iduser = request()->getPost('iduser');
        $jenis_pengajuan = request()->getPost('jenis_pengajuan');
        if ($jenis_pengajuan == "") {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Gagal membuat pengajuan: Pilih jenis pengajuan terlebih dahulu',
                    'status' => 'warning'
                ]
            );
            
            $dataset = ['notif' => $alert];
            session()->setFlashdata($dataset);
            return redirect()->back();
        }

        $jenis_deposit = 'manasuka free';

        $nominal = filter_var(request()->getPost('nominal'), FILTER_SANITIZE_NUMBER_INT);
        $deskripsi = request()->getPost('description');

        $cash_in = 0;
        $cash_out = 0;
        $status = 'diterima';

        if ($jenis_pengajuan == 'penyimpanan') {
            $cash_in = $nominal;
        }else{
            $cek_saldo = $this->m_deposit->cekSaldoManasukaByUser($iduser)[0]->saldo_manasuka;

            if ($cek_saldo < $nominal) {
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Gagal membuat pengajuan: Saldo manasuka kurang untuk membuat pengajuan',
                        'status' => 'warning'
                    ]
                );
                
                $dataset = ['notif' => $alert];
                session()->setFlashdata($dataset);
                return redirect()->back();
            }

            if ($nominal < 300000) {
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Gagal membuat pengajuan: Penarikan minimal Rp 300.000',
                        'status' => 'warning'
                    ]
                );
                
                $dataset = ['notif' => $alert];
                session()->setFlashdata($dataset);
                return redirect()->back();
            }

            $cash_out = $nominal;
        }

        $dataset = [
            'jenis_pengajuan' => $jenis_pengajuan,
            'jenis_deposit' => $jenis_deposit,
            'cash_in' => $cash_in,
            'cash_out' => $cash_out,
            'deskripsi' => $deskripsi,
            'status' => $status,
            'date_created' => date('Y-m-d H:i:s'),
            'idanggota' => $iduser,
            'idadmin' => $this->account->iduser
        ];

        $this->m_deposit->insertDeposit($dataset);

        $alert = view(
            'partials/notification-alert', 
            [
                'notif_text' => 'Pengajuan berhasil dibuat',
                 'status' => 'success'
            ]
        );
        
        $data_session = [ 'notif' => $alert ];
        session()->setFlashdata($data_session);
        return redirect()->back();
    }

    public function create_param_manasuka()
    {
        $dataset = [
            'idanggota' => request()->getPost('iduser'),
            'nilai' => filter_var(request()->getPost('nilai'), FILTER_SANITIZE_NUMBER_INT),
            'created' => date('Y-m-d H:i:s')
        ];

        $this->m_param_manasuka->insertParamManasuka($dataset);
        
        $alert = view(
            'partials/notification-alert', 
            [
                'notif_text' => 'Parameter Manasuka berhasil di set',
                'status' => 'success'
            ]
        );
        
        $data_session = ['notif' => $alert];
        session()->setFlashdata($data_session);

        return redirect()->back();
    }

    public function set_param_manasuka($idmnskparam = false)
    {
        $dataset = [
            'nilai' => filter_var(request()->getPost('nilai'), FILTER_SANITIZE_NUMBER_INT),
            'updated' => date('Y-m-d H:i:s')
        ];


        if ($dataset['nilai'] > 50000) {
            $this->m_param_manasuka->updateParamManasuka($idmnskparam, $dataset);
            
            $temp_log = [
                'nominal' => $dataset['nilai'],
                'idmnskparam' => $idmnskparam,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $this->m_param_manasuka_log->insert($temp_log);
            
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Parameter Manasuka berhasil di set',
                     'status' => 'success'
                ]
            );
        } else {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Pengajuan manasuka tidak boleh kurang dari Rp 50.000',
                     'status' => 'warning'
                ]
            );
        }
        
        $data_session = ['notif' => $alert];
        session()->setFlashdata($data_session);

        return redirect()->back();
    }

    public function cancel_param_manasuka($idmnskparam = false)
    {
        $dataset = [
            'nilai' => 0,
            'updated' => date('Y-m-d H:i:s')
        ];
        
        $this->m_param_manasuka->updateParamManasuka($idmnskparam, $dataset);


        $alert = view(
            'partials/notification-alert', 
            [
                'notif_text' => 'Pembatalan manasuka berhasil',
                'status' => 'success'
            ]
        );
        
        $data_session = [
            'notif' => $alert
        ];

        session()->setFlashdata($data_session);
        return redirect()->back();
    }

    // DAFTAR TRANSAKSI
    public function list_transaksi()
    {
        $data = [
            'title_meta' => view('admin/partials/title-meta', ['title' => 'Kelola Transaksi Simpanan']),
            'page_title' => view('admin/partials/page-title', ['title' => 'Kelola Transaksi Simpanan', 'li_1' => 'EKoperasi', 'li_2' => 'Kelola Transaksi Simpanan']),
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account
        ];
        
        return view('admin/deposit/deposit-list', $data);
    }

    public function edit_mutasi($id = false)
    {
        $deposit = $this->m_deposit
            ->select('tb_deposit.*, tb_user.nama_lengkap, tb_user.nik')
            ->join('tb_user', 'tb_user.iduser = tb_deposit.idanggota')
            ->where('iddeposit', $id)
            ->get()
            ->getResult()[0];

        $data = [
            'title_meta' => view('admin/partials/title-meta', ['title' => 'Ubah Pengajuan Simpanan']),
            'page_title' => view('admin/partials/page-title', ['title' => 'Ubah Pengajuan Simpanan', 'li_1' => 'EKoperasi', 'li_2' => 'Ubah Pengajuan Simpanan']),
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account,
            'deposit' => $deposit
        ];
        
        return view('admin/deposit/deposit-edit', $data);
    }

    public function update_mutasi($iddeposit = false)
    {
        $iduser = request()->getPost('idanggota');
        $jenis_pengajuan = request()->getPost('jenis_pengajuan');
        if ($jenis_pengajuan == "") {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Gagal membuat pengajuan: Pilih jenis pengajuan terlebih dahulu',
                    'status' => 'warning'
                ]
            );
            
            $dataset = ['notif' => $alert];
            session()->setFlashdata($dataset);
            return redirect()->back();
        }

        $jenis_deposit = request()->getPost('jenis_deposit');

        $nominal = request()->getPost('nominal');

        $cash_in = 0;
        $cash_out = 0;

        if ($jenis_pengajuan == 'penyimpanan') {
            $cash_in = $nominal;
        }else{
            $cash_out = $nominal;
        }

        $dataset = [
            'jenis_pengajuan' => $jenis_pengajuan,
            'jenis_deposit' => $jenis_deposit,
            'cash_in' => $cash_in,
            'cash_out' => $cash_out,
            'date_created' => date('Y-m-d H:i:s'),
            'idanggota' => $iduser,
            'idadmin' => $this->account->iduser
        ];

        // print_r($dataset); exit;

        // anggap ini update bukti transfer
        $this->m_deposit->updateBuktiTransfer($iddeposit, $dataset);
        
        $alert = view(
            'partials/notification-alert', 
            [
                'notif_text' => 'Pengajuan berhasil diubah',
                'status' => 'success'
            ]
        );
        
        $data_session = [
            'notif' => $alert
        ];

        session()->setFlashdata($data_session);
        return redirect()->back();
    }

    public function konfirmasi_mutasi($iddeposit = false)
    {
        $idanggota = $this->m_deposit->where('iddeposit', $iddeposit)->get()->getResult()[0]->idanggota;
        $nama_anggota = $this->m_user->where('iduser', $idanggota)->get()->getResult()[0]->nama_lengkap;
        $jenis_pengajuan = $this->m_deposit->where('iddeposit', $iddeposit)->get()->getResult()[0]->jenis_pengajuan;

        $message = false;
        if ($jenis_pengajuan == 'penarikan') {
            $message = 'penarikan';
            $cash_in = 0;
            $cash_out = filter_var(request()->getPost('nominal_uang'), FILTER_SANITIZE_NUMBER_INT);
        }else{
            $message = 'penyimpanan';
            $cash_in = filter_var(request()->getPost('nominal_uang'), FILTER_SANITIZE_NUMBER_INT);
            $cash_out = 0;
        };

        log_message('error', $cash_in." ".$cash_out);
        
        $dataset = [
            'idadmin' => $this->account->iduser,
            'status' => 'diproses bendahara',
            'cash_in' => $cash_in,
            'cash_out' => $cash_out,
            'date_updated' => date('Y-m-d H:i:s')
        ];

        $this->m_deposit->setStatus($iddeposit, $dataset);

        $notification_bendahara = [
            'admin_id' => $this->account->iduser,
            'anggota_id' => $idanggota,
            'deposit_id' => $iddeposit,
            'message' => 'Pengajuan '. $message .' manasuka baru dari anggota '. $nama_anggota,
            'timestamp' => date('Y-m-d H:i:s'),
            'group_type' => 2
        ];

        $this->m_notification->insert($notification_bendahara);

        $notification_anggota = [
            'admin_id' => $this->account->iduser,
            'anggota_id' => $idanggota,
            'deposit_id' => $iddeposit,
            'message' => 'Pengajuan '. $message .' manasuka disetujui oleh admin '. $this->account->nama_lengkap,
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
            'idadmin' => $this->account->iduser,
            'alasan_tolak' => request()->getPost('alasan_tolak'),
            'status' => 'ditolak',
            'date_updated' => date('Y-m-d H:i:s')
        ];

        $this->m_deposit->setStatus($iddeposit, $dataset);

        $idanggota = $this->m_deposit->where('iddeposit', $iddeposit)->get()->getResult()[0]->idanggota;
        $jenis_pengajuan = $this->m_deposit->where('iddeposit', $iddeposit)->get()->getResult()[0]->jenis_pengajuan;

        $message = false;
        if ($jenis_pengajuan == 'penarikan') {
            $message = 'penarikan';
        }else{
            $message = 'penyimpanan';
        };

        $notification_anggota = [
            'admin_id' => $this->account->iduser,
            'anggota_id' => $idanggota,
            'deposit_id' => $iddeposit,
            'message' => 'Pengajuan '. $message .' manasuka ditolak oleh admin '. $this->account->nama_lengkap,
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
            echo view('admin/deposit/part-depo-mod-detail', $data);
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
            echo view('admin/deposit/part-depo-mod-cancel', $data);
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

            // pengecekan saldo
            $confirmation = false;
            if ($deposit->cash_in == 0) {
                if ($deposit->jenis_deposit == 'wajib') {

                    $total_saldo = $total_saldo_wajib;
                    
                    if ($total_saldo_wajib < $deposit->cash_out) {
                        $confirmation = true;
                    }else{
                        $confirmation = false;
                    }
                
                }elseif ($deposit->jenis_deposit == 'pokok') {
                    
                    $total_saldo = $total_saldo_pokok;
                    
                    if ($total_saldo_pokok < $deposit->cash_out) {
                        $confirmation = true;
                    }else{
                        $confirmation = false;
                    }
                
                }elseif ($deposit->jenis_deposit == 'manasuka') {
                    
                    $total_saldo = $total_saldo_manasuka;
                    
                    if ($total_saldo_manasuka < $deposit->cash_out) {
                        $confirmation = true;
                    }else{
                        $confirmation = false;
                    }
                }
            }
            
            $duser = $this->m_user->getUserById($deposit->idanggota)[0];

            $data = [
                'a' => $deposit,
                'flag' => 1,
                'duser' => $duser,
                'total_saldo' => $total_saldo,
                'confirmation' => $confirmation
            ];
            echo view('admin/deposit/part-depo-mod-approve', $data);
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
        $model->select('tb_deposit.*, tb_user.username, tb_user.nama_lengkap, tb_user.email, tb_user.nomor_telepon, tb_user.email');
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
        $model->select('tb_deposit.*, tb_user.username, tb_user.nama_lengkap, tb_user.email');
        $model->where('tb_deposit.status', 'diproses admin');
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
        $model->where('tb_deposit.status', 'diproses admin');
        $model->orderBy('tb_deposit.date_created', 'DESC');
        $recordsTotal = $model->countAllResults();

        // Records after filtering (if any)
        $model->where('tb_deposit.status', 'diproses admin');
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
        $request = service('request');
        $model = $this->m_user;

        // Parameters from the DataTable
        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'];

        // Start building the query for filtering
        $model->select('iduser, username, nama_lengkap, instansi, email, nomor_telepon, flag')
            ->where('idgroup', 4)
            ->groupStart()
                ->like('username', $searchValue)
                    ->orLike('nama_lengkap', $searchValue)
                    ->orLike('nomor_telepon', $searchValue)
            ->groupEnd();

        // Fetch data from the model using $start and $length
        $data = $model->asArray()->findAll($length, $start);
        
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