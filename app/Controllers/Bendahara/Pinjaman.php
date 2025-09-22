<?php 
namespace App\Controllers\Bendahara;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_pinjaman;
use App\Models\M_cicilan;
use App\Models\M_param;
use App\Models\M_notification;

use App\Controllers\Bendahara\Notifications;

class Pinjaman extends Controller
{
    protected $m_user, $m_pinjaman, $m_cicilan, $m_param, $m_notification;
    protected $account;
    protected $notification;

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
            'title_meta' => view('bendahara/partials/title-meta', ['title' => 'Pinjaman']),
            'page_title' => view('bendahara/partials/page-title', ['title' => 'Pinjaman', 'li_1' => 'EKoperasi', 'li_2' => 'Pinjaman']),
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account
        ];
        
        return view('bendahara/pinjaman/list-pinjaman', $data);
    }

    public function cancel_proc($idpinjaman = false)
    {
        $dataset = [
            'idbendahara' => $this->account->iduser,
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
            'bendahara_id' => $this->account->iduser,
            'anggota_id' => $anggota_id,
            'pinjaman_id' => $idpinjaman,
            'message' => 'Pengajuan pinjaman ditolak oleh bendahara '. $this->account->nama_lengkap,
            'timestamp' => date('Y-m-d H:i:s'),
            'group_type' => 4
        ];

        $this->m_notification->insert($notification_data);
        
        $this->m_notification->where('pinjaman_id', $idpinjaman)
            ->where('group_type', 3)
            ->set('status', 'read')
            ->update();

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
            'idbendahara' => $this->account->iduser,
            'status' => 4,
            'date_updated' => date('Y-m-d H:i:s'),
            'bln_perdana' => date('m', strtotime("+ 1 month")),
            'tanggal_bayar' => date('d')
        ];

        $idanggota = $this->m_pinjaman->where('idpinjaman', $idpinjaman)
            ->get()
            ->getResult()[0]
            ->idanggota;
        
        $username_anggota = $this->m_user->where('iduser', $idanggota)
            ->get()
            ->getResult()[0]
            ->username;

        $bukti_tf = request()->getFile('bukti_tf') ? request()->getFile('bukti_tf') : false;
        $data_session = [];

        if($bukti_tf){
            if ($bukti_tf->isValid()) {	
                //cek tipe
                $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];

                if (!in_array($bukti_tf->getMimeType(), $allowed_types)) {
                    $alert = view(
                        'partials/notification-alert', 
                        [
                            'notif_text' => 'Tipe file tidak diizinkan', 
                            'status' => 'danger'
                        ]
                    );
                    
                    $data_session = ['notif' => $alert];
                    session()->setFlashdata($data_session);
                    return redirect()->back();
                }

                //cek ukuran
                if ($bukti_tf->getSize() > 1000000) {
                    $alert = view(
                        'partials/notification-alert', 
                        [
                            'notif_text' => 'Ukuran file tidak diizinkan', 
                            'status' => 'danger'
                        ]
                    );
                    
                    $data_session = ['notif' => $alert];
                    session()->setFlashdata($data_session);
                    return redirect()->back();
                }

                //cek ekstensi
                if ($bukti_tf->getExtension() !== 'jpg' && $bukti_tf->getExtension() !== 'jpeg' && $bukti_tf->getExtension() !== 'png') {
                    $alert = view(
                        'partials/notification-alert', 
                        [
                            'notif_text' => 'Ekstensi file tidak diizinkan', 
                            'status' => 'danger'
                        ]
                    );

                    $data_session = ['notif' => $alert];
                    session()->setFlashdata($data_session);
                    return redirect()->back();
                }

                $cek_tf = $this->m_pinjaman->getPinjamanById($idpinjaman)[0]->bukti_tf;
                
                if ($cek_tf) {
                    unlink(ROOTPATH . 'public/uploads/user/' . $username_anggota . '/pinjaman/' . $cek_tf);
                }
    
                $newName = $bukti_tf->getRandomName();
                $bukti_tf->move(ROOTPATH . 'public/uploads/user/' . $username_anggota . '/pinjaman/', $newName);
                
                $bukti = $bukti_tf->getName();
                $dataset += ['bukti_tf' => $bukti];
                $alert3 = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Bukti transfer berhasil dikirim',
                        'status' => 'success'
                    ]
                );
                $data_session += ['notif_tf' => $alert3];
                // $confirmation3 = true;
            } else {
                $alert3 = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Bukti kontrak gagal diunggah',
                        'status' => 'danger'
                    ]
                );

                $data_session = ['notif_kontrak' => $alert3];
                session()->setFlashdata($data_session);
                return redirect()->back();
            }
        }
        
        $this->m_pinjaman->updatePinjaman($idpinjaman, $dataset);

        $notification_anggota = [
            'bendahara_id' => $this->account->iduser,
            'anggota_id' => $idanggota,
            'pinjaman_id' => $idpinjaman,
            'message' => 'Pengajuan pinjaman diterima oleh bendahara '. $this->account->nama_lengkap,
            'timestamp' => date('Y-m-d H:i:s'),
            'group_type' => 4
        ];

        $this->m_notification->insert($notification_anggota);

        $this->m_notification->where('pinjaman_id', $idpinjaman)
            ->where('group_type', 3)
            ->set('status', 'read')
            ->update();

        $alert = view(
            'partials/notification-alert', 
            [
                'notif_text' => 'Pengajuan pinjaman berhasil disetujui',
                'status' => 'success'
            ]
        );
        
        $data_session += ['notif' => $alert];
        session()->setFlashdata($data_session);
        return redirect()->back();
    }

    public function pelunasan_proc($idpinjaman = false)
    {
        $pin = $this->m_pinjaman->getPinjamanById($idpinjaman)[0];
        $bulan_bayar = $pin->angsuran_bulanan - $this->m_cicilan->select('COUNT(idcicilan) as hitung')
            ->where('idpinjaman', $idpinjaman)
            ->get()->getResult()[0]
            ->hitung;

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
                    'date_created' => date('Y-m-d H:i:s'),
                    'idpinjaman' => $idpinjaman
                ];

                $this->m_cicilan->insertCicilan($dataset_cicilan);
                
            } elseif ($cek_cicilan == ($pin->angsuran_bulanan - 1)) {
                // $bunga = $this->m_param->where('idparameter', 9)->get()->getResult()[0]->nilai/100;
                $dataset_cicilan = [
                    'nominal' => ($pin->nominal/$pin->angsuran_bulanan),
                    'bunga' => 0,
                    'date_created' => date('Y-m-d H:i:s'),
                    'idpinjaman' => $idpinjaman
                ];

                $this->m_cicilan->insertCicilan($dataset_cicilan);

                $status_pinjaman = ['status' => 5];
                $this->m_pinjaman->updatePinjaman($idpinjaman, $status_pinjaman);
            } elseif ($cek_cicilan != 0 && $cek_cicilan < $pin->angsuran_bulanan) {
                // $bunga = $this->m_param->where('idparameter', 9)->get()->getResult()[0]->nilai/100;

                $dataset_cicilan = [
                    'nominal' => ($pin->nominal/$pin->angsuran_bulanan),
                    'bunga' => 0,
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
            'message' => 'Pengajuan pelunasan diterima oleh '. $this->account->nama_lengkap,
            'timestamp' => date('Y-m-d H:i:s'),
            'group_type' => 4
        ];

        $this->m_notification->insert($notification_anggota);
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
            'idbendahara' => $this->account->iduser,
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

    public function cancel_loan()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
            $data = [
                'a' => $pinjaman,
                'flag' => 0
            ];

            echo view('bendahara/pinjaman/part-pinjaman-mod-approval', $data);
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

            echo view('bendahara/pinjaman/part-pinjaman-mod-approval', $data);
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

            echo view('bendahara/pinjaman/part-pinjaman-mod-detail', $data);
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

            echo view('bendahara/pinjaman/part-pinjaman-mod-lunasin', $data);
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

            echo view('bendahara/pinjaman/part-pinjaman-mod-tolak-lunasin', $data);
        }
    }

    public function data_pinjaman()
    {
        $request = service('request');
        $model = $this->m_pinjaman;

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
        $model->where('tb_pinjaman.status', 3);

        $model->groupStart()
            ->like('a.nama_lengkap', $searchValue)
            ->orLike('a.username', $searchValue);
        $model->groupEnd();
        
        $model->join('tb_user a', 'a.iduser = tb_pinjaman.idanggota');
        $model->join('tb_user c', 'c.iduser = tb_pinjaman.idadmin', 'left');
        $model->join('tb_user d', 'd.iduser = tb_pinjaman.idbendahara', 'left');
        $data = $model->asArray()->findAll($length, $start);

        // Total records (you can also use $model->countAll() for exact total)
        $model->where('tb_pinjaman.status', 3);
        $recordsTotal = $model->countAllResults();

        // Records after filtering (if any)
        $model->where('tb_pinjaman.status', 3);
        
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
        $model->where('a.status', 7);
        $model->groupStart()
            ->like('b.nama_lengkap', $searchValue)
            ->orLike('b.username', $searchValue);
        $model->groupEnd();
        $data = $model->limit($length, $start)->get()->getResult();

        // Total records (you can also use $model->countAll() for exact total)
        $model->where('a.status', 7);
        $recordsTotal = $model->countAllResults();

        // Records after filtering (if any)
        $model->where('a.status', 7);
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