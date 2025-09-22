<?php 
namespace App\Controllers\Admin;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_pinjaman;
use App\Models\M_cicilan;
use App\Models\M_param;
use App\Models\M_notification;

use App\Controllers\Admin\Notifications;

class Pinjaman extends Controller
{
    protected $m_user, $m_pinjaman, $m_cicilan, $m_param, $m_notification;
    protected $notification;
    protected $account;

    function __construct()
    {
        $this->m_user = model(M_user::class);
        $this->m_pinjaman = model(M_pinjaman::class);
        $this->m_cicilan = model(M_cicilan::class);
        $this->m_param = model(M_param::class);
        $this->m_notification = model(M_notification::class);

        $this->notification = new Notifications();

        $this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
    }

    public function index()
    {
        $data = [
            'title_meta' => view('admin/partials/title-meta', ['title' => 'Pinjaman']),
            'page_title' => view('admin/partials/page-title', ['title' => 'Pinjaman', 'li_1' => 'EKoperasi', 'li_2' => 'Pinjaman']),
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account
        ];
        
        return view('admin/pinjaman/list-pinjaman', $data);
    }

    public function list_pelunasan()
    {
        $data = [
            'title_meta' => view('admin/partials/title-meta', ['title' => 'Pinjaman']),
            'page_title' => view('admin/partials/page-title', ['title' => 'Pinjaman', 'li_1' => 'EKoperasi', 'li_2' => 'Pelunasan Pinjaman']),
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account
        ];
        
        return view('admin/pinjaman/list-pelunasan', $data);
    }

    public function cancel_proc($idpinjaman = false)
    {
        $dataset = [
            'idadmin' => $this->account->iduser,
            'alasan_tolak' => request()->getPost('alasan_tolak'),
            'status' => 0,
            'date_updated' => date('Y-m-d H:i:s')
        ];

        $this->m_pinjaman->updatePinjaman($idpinjaman, $dataset);
        $anggota_id = $this->m_pinjaman->where('idpinjaman', $idpinjaman)
            ->get()
            ->getResult()[0]
            ->idanggota;

        $notification_data = [
            'admin_id' => $this->account->iduser,
            'anggota_id' => $anggota_id,
            'pinjaman_id' => $idpinjaman,
            'message' => 'Pengajuan pinjaman ditolak oleh admin '. $this->account->nama_lengkap,
            'timestamp' => date('Y-m-d H:i:s'),
            'group_type' => 4
        ];

        $this->m_notification->insert($notification_data);

        $alert = view(
            'partials/notification-alert', 
            [
                'notif_text' => 'Pengajuan pinjaman berhasil ditolak',
                'status' => 'success'
            ]
        );
        
        $data_session = ['notif' => $alert];
        session()->setFlashdata($data_session);
        return redirect()->back();
    }

    public function approve_proc($idpinjaman = false)
    {
        $dataset = [
            'idadmin' => $this->account->iduser,
            'nominal' => request()->getPost('nominal_uang'),
            'status' => 3,
            'date_updated' => date('Y-m-d H:i:s')
        ];

        // print_r($dataset);
        $this->m_pinjaman->updatePinjaman($idpinjaman, $dataset);
        
        $idanggota = $this->m_pinjaman->where('idpinjaman', $idpinjaman)
            ->get()
            ->getResult()[0]
            ->idanggota;

        $notification_anggota = [
            'admin_id' => $this->account->iduser,
            'anggota_id' => $idanggota,
            'pinjaman_id' => $idpinjaman,
            'message' => 'Pengajuan pinjaman diterima oleh admin '. $this->account->nama_lengkap,
            'timestamp' => date('Y-m-d H:i:s'),
            'group_type' => 4
        ];

        $this->m_notification->insert($notification_anggota);

        $notification_ketua = [
            'admin_id' => $this->account->iduser,
            'anggota_id' => $idanggota,
            'pinjaman_id' => $idpinjaman,
            'message' => 'Pengajuan pinjaman baru dari '. $this->m_user->where('iduser', $idanggota)->get()->getResult()[0]->nama_lengkap,
            'timestamp' => date('Y-m-d H:i:s'),
            'group_type' => 3
        ];

        $this->m_notification->insert($notification_ketua);

        $notification_bendahara = [
            'admin_id' => $this->account->iduser,
            'anggota_id' => $idanggota,
            'pinjaman_id' => $idpinjaman,
            'message' => 'Pengajuan pinjaman baru dari '. $this->m_user->where('iduser', $idanggota)->get()->getResult()[0]->nama_lengkap,
            'timestamp' => date('Y-m-d H:i:s'),
            'group_type' => 2
        ];

        $this->m_notification->insert($notification_bendahara);

        $alert = view(
            'partials/notification-alert', 
            [
                'notif_text' => 'Pengajuan pinjaman berhasil disetujui',
                'status' => 'success'
            ]
        );
        
        $data_session = ['notif' => $alert];
        session()->setFlashdata($data_session);
        return redirect()->back();
    }

    public function pelunasan_proc($idpinjaman = false)
    {
        $dataset = [
            'idadmin' => $this->account->iduser,
            'status' => 7
        ];

        $this->m_pinjaman->updatePinjaman($idpinjaman, $dataset);
        
        $idanggota = $this->m_pinjaman->where('idpinjaman', $idpinjaman)
            ->get()
            ->getResult()[0]
            ->idanggota;

        $notification_bendahara = [
            'admin_id' => $this->account->iduser,
            'anggota_id' => $idanggota,
            'pinjaman_id' => $idpinjaman,
            'message' => 'Pengajuan pelunasan baru dari '. $this->m_user->where('iduser', $idanggota)->get()->getResult()[0]->nama_lengkap,
            'timestamp' => date('Y-m-d H:i:s'),
            'group_type' => 2
        ];

        $this->m_notification->insert($notification_bendahara);

        $alert = view(
            'partials/notification-alert', 
            [
                'notif_text' => 'Pengajuan pelunasan berhasil disetujui',
                'status' => 'success'
            ]
        );
        
        $data_session = ['notif' => $alert];
        session()->setFlashdata($data_session);
        return redirect()->back();
    }

    public function tolak_pelunasan_proc($idpinjaman = false)
    {
        $dataset = [
            'idadmin' => $this->account->iduser,
            'status' => 4
        ];

        $this->m_pinjaman->updatePinjaman($idpinjaman, $dataset);
        
        $idanggota = $this->m_pinjaman->where('idpinjaman', $idpinjaman)
            ->get()
            ->getResult()[0]
            ->idanggota;

        $notification_anggota = [
            'admin_id' => $this->account->iduser,
            'anggota_id' => $idanggota,
            'pinjaman_id' => $idpinjaman,
            'message' => 'Pengajuan pelunasan ditolak oleh '. $this->account->nama_lengkap,
            'timestamp' => date('Y-m-d H:i:s'),
            'group_type' => 4
        ];

        $this->m_notification->insert($notification_anggota);

        $alert = view(
            'partials/notification-alert', 
            [
                'notif_text' => 'Pengajuan pelunasan berhasil ditolak',
                'status' => 'success'
            ]
        );
        
        $data_session = ['notif' => $alert];
        session()->setFlashdata($data_session);
        return redirect()->back();
    }

    public function pelunasan_partial_proc($idpinjaman)
    {
        $bulan_bayar = request()->getPost('bulan_bayar');
        $pin = $this->m_pinjaman->getPinjamanById($idpinjaman)[0];

        for ($i = 0; $i < $bulan_bayar; ++$i) {
            //CEK CICILAN
            $cek_cicilan = $this->m_cicilan->where('idpinjaman', $idpinjaman)
                                             ->countAllResults();
            if ($cek_cicilan == 0) {
                // $bunga = $this->m_param->where('idparameter', 9)->get()->getResult()[0]->nilai/100;
                $provisi = $this->m_param->where('idparameter', 5)->get()->getResult()[0]->nilai/100;

                $dataset_cicilan = [
                    'nominal' => ($pin->nominal/$pin->angsuran_bulanan),
                    'bunga' => 0,
                    'provisi' => ($pin->nominal*($pin->angsuran_bulanan*$provisi))/$pin->angsuran_bulanan,
                    'tipe_bayar' => 'langsung',
                    'date_created' => date('Y-m-d H:i:s'),
                    'idpinjaman' => $idpinjaman
                ];

                $this->m_cicilan->insertCicilan($dataset_cicilan);
                
            }elseif ($cek_cicilan == ($pin->angsuran_bulanan - 1)) {
                // $bunga = $this->m_param->where('idparameter', 9)->get()->getResult()[0]->nilai/100;

                $dataset_cicilan = [
                    'nominal' => ($pin->nominal/$pin->angsuran_bulanan),
                    'bunga' => 0,
                    'tipe_bayar' => 'langsung',
                    'date_created' => date('Y-m-d H:i:s'),
                    'idpinjaman' => $idpinjaman
                ];

                $this->m_cicilan->insertCicilan($dataset_cicilan);

                $status_pinjaman = ['status' => 5];
                $this->m_pinjaman->updatePinjaman($idpinjaman, $status_pinjaman);

            }elseif ($cek_cicilan != 0 && $cek_cicilan < $pin->angsuran_bulanan) {
                // $bunga = $this->m_param->where('idparameter', 9)->get()->getResult()[0]->nilai/100;

                $dataset_cicilan = [
                    'nominal' => ($pin->nominal/$pin->angsuran_bulanan),
                    'bunga' => 0,
                    'tipe_bayar' => 'langsung',
                    'date_created' => date('Y-m-d H:i:s'),
                    'idpinjaman' => $idpinjaman
                ];

                $this->m_cicilan->insertCicilan($dataset_cicilan);
            }
        }

        $idanggota = $this->m_pinjaman->where('idpinjaman', $idpinjaman)
            ->get()
            ->getResult()[0]
            ->idanggota;

        $notification_anggota = [
            'admin_id' => $this->account->iduser,
            'anggota_id' => $idanggota,
            'pinjaman_id' => $idpinjaman,
            'message' => 'Pinjaman telah dilunasi sebagian oleh '. $this->account->nama_lengkap,
            'timestamp' => date('Y-m-d H:i:s'),
            'group_type' => 4
        ];

        $this->m_notification->insert($notification_anggota);

        $alert = view(
            'partials/notification-alert', 
            [
                'notif_text' => 'Berhasil ',
                'status' => 'success'
            ]
        );
        
        $data_session = ['notif' => $alert];
        session()->setFlashdata($data_session);
        return redirect()->back();
    }

    public function cancel_loan()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
            $data = [
                'a' => $pinjaman,
                'flag' => 0
            ];
            echo view('admin/pinjaman/part-pinjaman-mod-cancel', $data);
        }
    }

    public function approve_loan()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
            $data = [
                'a' => $pinjaman,
                'flag' => 1
            ];
            echo view('admin/pinjaman/part-pinjaman-mod-approval', $data);
        }
    }

    public function detail_pinjaman()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
            $hitung_cicilan = $this->m_cicilan->select('COUNT(idcicilan) AS hitung, IFNULL(SUM(nominal),0) AS total_lunas')
                ->where('idpinjaman', $id)
                ->get()
                ->getResult()[0];
            $data = [
                'a' => $pinjaman,
                'b' => $hitung_cicilan,
                'flag' => 1
            ];
            echo view('admin/pinjaman/part-pinjaman-mod-detail', $data);
        }
    }

    public function pengajuan_lunas()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
            $user_detail = $this->m_pinjaman->asArray()
                ->select('tb_user.username')
                ->select('tb_user.nama_lengkap')
                ->join('tb_user', 'tb_user.iduser = tb_pinjaman.idanggota')
                ->where('idpinjaman', $id)
                ->findAll();
            $penalty_percent = $this->m_param->getParamById(6)[0]->nilai;
            $bebas_penalty = $this->m_param->getParamById(7)[0]->nilai;
            $hitung_cicilan = $this->m_cicilan->select('COUNT(idcicilan) AS hitung, IFNULL(SUM(nominal),0) AS total_lunas')
                ->where('idpinjaman', $id)
                ->get()
                ->getResult()[0];
            $penalty = $hitung_cicilan->hitung <= $bebas_penalty ? ($pinjaman->nominal - $hitung_cicilan->total_lunas)*($penalty_percent/100) : 0;
            $data = [
                'idpinjaman' => $id,
                'penalty' => $penalty,
                'hitung_cicilan' => $hitung_cicilan,
                'bebas_penalty' => $bebas_penalty - $hitung_cicilan->hitung,
                'user' => $user_detail,
                'flag' => 1
            ];
            echo view('admin/pinjaman/part-pinjaman-mod-lunasin', $data);
        }
    }

    public function tolak_pengajuan_lunas()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
            $user_detail = $this->m_pinjaman->asArray()
                ->select('tb_user.username')
                ->select('tb_user.nama_lengkap')
                ->join('tb_user', 'tb_user.iduser = tb_pinjaman.idanggota')
                ->where('idpinjaman', $id)
                ->findAll();
            $penalty_percent = $this->m_param->getParamById(6)[0]->nilai;
            $bebas_penalty = $this->m_param->getParamById(7)[0]->nilai;
            $hitung_cicilan = $this->m_cicilan->select('COUNT(idcicilan) AS hitung, IFNULL(SUM(nominal),0) AS total_lunas')
                ->where('idpinjaman', $id)
                ->get()
                ->getResult()[0];
            $penalty = $hitung_cicilan->hitung <= $bebas_penalty ? ($pinjaman->nominal - $hitung_cicilan->total_lunas)*($penalty_percent/100) : 0;
            $data = [
                'idpinjaman' => $id,
                'penalty' => $penalty,
                'hitung_cicilan' => $hitung_cicilan,
                'bebas_penalty' => $bebas_penalty - $hitung_cicilan->hitung,
                'user' => $user_detail,
                'flag' => 1
            ];
            echo view('admin/pinjaman/part-pinjaman-mod-tolak-lunasin', $data);
        }
    }

    public function pelunasan_partial()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
            
            $info_cicilan = $this->m_cicilan->select('COUNT(idcicilan) AS hitung, sum(nominal) as terbayar')
                ->where('idpinjaman', $id)
                ->get()->getResult()[0];

            $user_detail = $this->m_pinjaman->asArray()
                ->select('tb_user.username')
                ->select('tb_user.nama_lengkap')
                ->join('tb_user', 'tb_user.iduser = tb_pinjaman.idanggota')
                ->where('idpinjaman', $id)
                ->findAll();
            
            $sisa_cicilan = $pinjaman->angsuran_bulanan - $info_cicilan->hitung;
            $sisa_pinjaman = $pinjaman->nominal - $info_cicilan->terbayar;
            $nominal_cicilan = $pinjaman->nominal / $pinjaman->angsuran_bulanan;
            
            $data = [
                'idpinjaman' => $id,
                'pinjaman' => $pinjaman,
                'sisa_cicilan' => $sisa_cicilan,
                'sisa_pinjaman' => $sisa_pinjaman,
                'nominal_cicilan' => $nominal_cicilan,
                'user' => $user_detail,
                'flag' => 1
            ];
            echo view('admin/pinjaman/part-pelunasan-mod-sebagian', $data);
        }
    }

    public function data_pinjaman()
    {
        $request = service('request');
        $model = model(M_pinjaman::class);

        // Parameters from the DataTable
        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'];

        // Fetch data from the model using $start and $length
        $model->select('a.status_pegawai AS status_pegawai');
        $model->select('a.username AS username_peminjam');
        $model->select('a.nama_lengkap AS nama_peminjam');
        $model->select('a.nik AS nik_peminjam');
        $model->select('tb_pinjaman.*');
        $model->select('(SELECT COUNT(idcicilan) FROM tb_cicilan WHERE idpinjaman = tb_pinjaman.idpinjaman) AS sisa_cicilan', false);
        $model->select('c.nama_lengkap AS nama_admin');
        $model->select('c.nik AS nik_admin');
        $model->select('d.nama_lengkap AS nama_bendahara');
        $model->select('d.nik AS nik_bendahara');
        $model->groupStart()
            ->like('a.nama_lengkap', $searchValue)
            ->orLike('a.username', $searchValue);
        $model->groupEnd();
        $model->join('tb_user a', 'a.iduser = tb_pinjaman.idanggota');
        $model->join('tb_user c', 'c.iduser = tb_pinjaman.idadmin', 'left');
        $model->join('tb_user d', 'd.iduser = tb_pinjaman.idbendahara', 'left');
        $model->orderBy('tb_pinjaman.date_updated', 'DESC');
        $data = $model->asArray()->findAll($length, $start);

        // Total records (you can also use $model->countAll() for exact total)
        $model->orderBy('tb_pinjaman.date_updated', 'DESC');
        $recordsTotal = $model->countAllResults();

        // Records after filtering (if any)
        $model->like('a.nama_lengkap', $searchValue)
            ->orLike('a.username', $searchValue);
        $model->join('tb_user a', 'a.iduser = tb_pinjaman.idanggota');
        $model->orderBy('tb_pinjaman.date_updated', 'DESC');
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

    public function data_pinjaman_filter()
    {
        $request = service('request');
        $model = new M_pinjaman();

        // Parameters from the DataTable
        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'];

        // Fetch data from the model using $start and $length
        $model->select('a.status_pegawai AS status_pegawai');
        $model->select('a.username AS username_peminjam');
        $model->select('a.nama_lengkap AS nama_peminjam');
        $model->select('a.nik AS nik_peminjam');
        $model->select('tb_pinjaman.*');
        $model->select('(SELECT COUNT(idcicilan) FROM tb_cicilan WHERE idpinjaman = tb_pinjaman.idpinjaman) AS sisa_cicilan', false);
        $model->select('c.nama_lengkap AS nama_admin');
        $model->select('c.nik AS nik_admin');
        $model->select('d.nama_lengkap AS nama_bendahara');
        $model->select('d.nik AS nik_bendahara');
        $model->where('tb_pinjaman.status', 2);
        $model->groupStart()
            ->like('a.nama_lengkap', $searchValue)
            ->orLike('a.username', $searchValue);
        $model->groupEnd();
        $model->join('tb_user a', 'a.iduser = tb_pinjaman.idanggota');
        $model->join('tb_user c', 'c.iduser = tb_pinjaman.idadmin', 'left');
        $model->join('tb_user d', 'd.iduser = tb_pinjaman.idbendahara', 'left');
        $data = $model->asArray()->findAll($length, $start);

        // Total records (you can also use $model->countAll() for exact total)
        $model->where('tb_pinjaman.status', 2);
        $recordsTotal = $model->countAllResults();

        // Records after filtering (if any)
        $model->where('tb_pinjaman.status', 2);
        $model->groupStart()
            ->like('a.nama_lengkap', $searchValue)
            ->orLike('a.username', $searchValue);
        $model->groupEnd();
        $model->join('tb_user a', 'a.iduser = tb_pinjaman.idanggota');
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

    public function data_pelunasan()
    {
        $request = service('request');
        $db = \Config\Database::connect();
        $model = $db->table('tb_pinjaman a');

        // Parameters from the DataTable
        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value']??'';

        // Fetch data from the model using $start and $length
        $model->select('a.idpinjaman');
        $model->select('b.username');
        $model->select('b.nama_lengkap');
        $model->select('a.nominal');
        $model->select('a.angsuran_bulanan');
        $model->select('(a.angsuran_bulanan - (SELECT COUNT(z.idcicilan) FROM tb_cicilan z WHERE z.idpinjaman = a.idpinjaman)) AS sisa_cicilan', false);
        $model->select('(a.nominal - (SELECT SUM(z.nominal) FROM tb_cicilan z WHERE z.idpinjaman = a.idpinjaman)) AS sisa_pinjaman', false);
        $model->select('a.date_updated');
        $model->select('a.bukti_tf');
        $model->join('tb_user b', 'a.idanggota = b.iduser');
        $model->where('a.status', 6);
        $model->groupStart()
            ->like('b.nama_lengkap', $searchValue)
            ->orLike('b.username', $searchValue);
        $model->groupEnd();
        $data = $model->limit($length, $start)->get()->getResult();

        // Total records (you can also use $model->countAll() for exact total)
        $model->where('a.status', 6);
        $recordsTotal = $model->countAllResults();

        // Records after filtering (if any)
        $model->where('a.status', 6);
        $model->groupStart()
            ->like('b.nama_lengkap', $searchValue)
            ->orLike('b.username', $searchValue);
        $model->groupEnd();
        $model->join('tb_user b', 'b.iduser = a.idanggota');
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