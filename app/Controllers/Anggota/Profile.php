<?php 
namespace App\Controllers\Anggota;

use App\Controllers\BaseController;
use App\Controllers\Anggota\Notifications;

use App\Models\M_user;
use App\Models\M_group;
use App\Models\M_param;
use App\Models\M_param_manasuka;

class Profile extends BaseController
{
    protected $m_user;
    protected $m_group;
    protected $m_param;
    protected $m_param_manasuka;
    protected $notification;
    protected $account;

    function __construct()
    {
        $this->m_user = model(M_user::class);
        $this->m_group = model(M_group::class);
        $this->m_param = model(M_param::class);
        $this->m_param_manasuka = model(M_param_manasuka::class);

        $this->notification = new Notifications();
        
        $config = new \Config\Encryption();
        $encrypter = \Config\Services::encrypter($config);

        $user = $this->m_user->getUserById(session()->get('iduser'));
        if (empty($user)) {
            $user = null;
        } else {
            $data = $user[0];
            
            $nik = ($data->nik != null || $data->nik != '') ? $encrypter->decrypt(base64_decode($data->nik)) : '';
            $nip = ($data->nip != null || $data->nip != '') ? $encrypter->decrypt(base64_decode($data->nip)) : '';
            $no_rek = ($data->no_rek != null || $data->no_rek != '') ? $encrypter->decrypt(base64_decode($data->no_rek)) : '';
            $nomor_telepon = ($data->nomor_telepon != null || $data->nomor_telepon != '') ? $encrypter->decrypt(base64_decode($data->nomor_telepon)) : '';
            $alamat = ($data->alamat != null || $data->alamat != '') ? $encrypter->decrypt(base64_decode($data->alamat)) : '';

            $this->account = (object) [
                'iduser' => $data->iduser,
                'username' => $data->username,
                'nik' => $nik,
                'nip' => $nip,
                'nama_lengkap' => $data->nama_lengkap,
                'tempat_lahir' => $data->tempat_lahir,
                'tanggal_lahir' => $data->tanggal_lahir,
                'status_pegawai' => $data->status_pegawai,
                'nama_bank' => $data->nama_bank,
                'no_rek' => $no_rek,
                'alamat' => $alamat,
                'instansi' => $data->instansi,
                'unit_kerja' => $data->unit_kerja,
                'nomor_telepon' => $nomor_telepon,
                'email' => $data->email,
                'profil_pic' => $data->profil_pic,
                'user_created' => $data->user_created,
                'user_updated' => $data->user_updated,
                'closebook_request' => $data->closebook_request,
                'closebook_request_date' => $data->closebook_request_date,
                'closebook_last_updated' => $data->closebook_last_updated,
                'closebook_param_count' => $data->closebook_param_count,
                'user_flag' => $data->user_flag,
                'idgroup' => $data->idgroup,
                'group_type' => $data->group_type,
                'group_assigned' => $data->group_assigned,
                'group_flag' => $data->group_flag
            ];
        }
    }

    public function index()
    {
        $data = [
            'title_meta' => view('anggota/partials/title-meta', ['title' => 'Profile']),
            'page_title' => view('anggota/partials/page-title', ['title' => 'Profile', 'li_1' => 'EKoperasi', 'li_2' => 'Profile']),
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account
        ];
        
        return view('anggota/prof/prof-detail', $data);
    }

    public function update_proc()
    {
        $config = new \Config\Encryption();
        $encrypter = \Config\Services::encrypter($config);

        $alamat = request()->getPost('alamat');
        $nomor_telepon = request()->getPost('nomor_telepon');
        $no_rek = request()->getPost('no_rek');

        $dataset = [
            'nama_lengkap' => strtoupper(request()->getPost('nama_lengkap')),
            'tempat_lahir' => request()->getPost('tempat_lahir'),
            'tanggal_lahir' => request()->getPost('tanggal_lahir'),
            'status_pegawai' => request()->getPost('status_pegawai'),
            'instansi' => request()->getPost('instansi'),
            'alamat' => ($alamat != null || $alamat != '') ? base64_encode($encrypter->encrypt($alamat)) : '',
            'nama_bank' => strtoupper(request()->getPost('nama_bank')),
            'no_rek' => ($no_rek != null || $no_rek != '') ? base64_encode($encrypter->encrypt($no_rek)) : '',
            'nomor_telepon' => ($nomor_telepon != null || $nomor_telepon != '') ? base64_encode($encrypter->encrypt($nomor_telepon)) : '',
            'email' => request()->getPost('email'),
            'unit_kerja' => request()->getPost('unit_kerja')
        ];
        
        //check duplicate nip
        $nip_baru = $this->request->getPost('nip');

        if($nip_baru != null || $nip_baru != ''){
            $nip_baru_enc = base64_encode($encrypter->encrypt($nip_baru));
            $nip_awal = $this->account->nip;

            if($nip_awal != $nip_baru){
                $cek_nip = $this->m_user->select('count(iduser) as hitung')
                    ->where("nip = '".$nip_baru_enc."' AND iduser != ".$this->account->iduser)
                    ->get()->getResult()[0]->hitung;

                if ($cek_nip == 0) {
                    $dataset += ['nip' => $nip_baru_enc];
                }else{
                    $alert = view(
                        'partials/notification-alert', 
                        [
                            'notif_text' => 'NIP telah terdaftar',
                             'status' => 'danger'
                        ]
                    );
                    
                    $data_session = [
                        'notif' => $alert
                    ];

                    session()->setFlashdata($data_session);
                    return redirect()->back();
                }	
            }
        }

        //check duplicate nik
        $nik_baru = request()->getPost('nik');
        $nik_baru_enc = base64_encode($encrypter->encrypt($nik_baru));
        $nik_awal = $this->account->nik;

        if ($nik_baru != $nik_awal) {
            $cek_nik = $this->m_user->select('count(iduser) as hitung')
                ->where("nik = '".$nik_baru_enc."' AND iduser != ".$this->account->iduser)
                ->get()->getResult()[0]->hitung;

            if ($cek_nik == 0) {
                $dataset += ['nik' => $nik_baru_enc];
            }else{
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'NIK telah terdaftar',
                         'status' => 'danger'
                    ]
                );
                
                $data_session = [
                    'notif' => $alert
                ];

                session()->setFlashdata($data_session);
                return redirect()->back();
            }
        }

        $img = $this->request->getFile('profil_pic');

        if ($img->isValid() && !$img->hasMoved()) {
            // cek tipe
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!in_array($img->getMimeType(), $allowed_types)) {
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'format gambar tidak sesuai', 
                         'status' => 'danger'
                    ]
                );
                $data_session = [
                    'notif' => $alert
                ];

                session()->setFlashdata($data_session);
                return redirect()->back();
            }
            
            // cek ukuran
            if ($img->getSize() > 1000000) {
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'ukuran gambar tidak sesuai', 
                         'status' => 'danger'
                    ]
                );
                $data_session = [
                    'notif' => $alert
                ];

                session()->setFlashdata($data_session);
                return redirect()->back();
            }

            $oldFile = ROOTPATH . "public/uploads/user/" . $this->account->username . "/profil_pic/" . $this->account->profil_pic;
            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
            
            $newName = $img->getRandomName();
            $img->move(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/profil_pic/', $newName);
            $profile_pic = $img->getName();
            $dataset += ['profil_pic' => $profile_pic];
        }

        $dataset += [
            'updated' => date('Y-m-d H:i:s')
        ];
        
        $this->m_user->updateUser($this->account->iduser, $dataset);
        
        $alert = view(
            'partials/notification-alert', 
            [
                'notif_text' => 'data pengguna berhasil diubah',
                 'status' => 'success'
            ]
        );
        
        $data_session = [
            'notif' => $alert
        ];

        session()->setFlashdata($data_session);
        return redirect()->to('anggota/profile');
    }

    public function update_pass()
    {
        $old_pass = md5($this->request->getPost('old_pass'));

        $pass = md5($this->request->getPost('pass'));
        $pass2 = md5($this->request->getPost('pass2'));

        $cek_pass = $this->m_user->getPassword($this->account->iduser)[0]->pass;

        if (!password_verify($old_pass, $cek_pass))
        {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Password lama salah',
                     'status' => 'danger'
                ]
            );
            
            $dataset = ['notif' => $alert];
            session()->setFlashdata($dataset);
            return redirect()->to('anggota/profile');
        }

        if ($pass != $pass2)
        {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Konfirmasi password tidak sesuai',
                     'status' => 'danger'
                ]
            );
            
            $dataset = ['notif' => $alert];
            session()->setFlashdata($dataset);
            return redirect()->to('anggota/profile');
        }

        $dataset = ['pass' => password_hash($pass, PASSWORD_DEFAULT)];

        $this->m_user->updateUser($this->account->iduser, $dataset);

        $alert = view(
            'partials/notification-alert', 
            [
                'notif_text' => 'Password berhasil diubah',
                 'status' => 'success'
            ]
        );
        
        $dataset += ['notif' => $alert];
        session()->setFlashdata($dataset);
        return redirect()->to('anggota/profile');
    }

    public function set_manasuka()
    {
        $default_param = $this->m_param->getParamById(3)[0]->nilai;

        $data = [
            'title_meta' => view('anggota/partials/title-meta', ['title' => 'Profile']),
            'page_title' => view('anggota/partials/page-title', ['title' => 'Profile', 'li_1' => 'EKoperasi', 'li_2' => 'Profile']),
            'duser' => $this->account,
            'default_param' => $default_param
        ];
        
        return view('anggota/prof/set-manasuka', $data);
    }

    public function set_manasuka_proc()
    {
        $iduser = $this->request->getPost('iduser');
        $nominal_param = filter_var($this->request->getPost('nilai'), FILTER_SANITIZE_NUMBER_INT);

        $param_r = [
            'idanggota' => $iduser,
            'nilai' => $nominal_param,
            'created' => date('Y-m-d H:i:s')
        ];

        $this->m_param_manasuka->insertParamManasuka($param_r);

        return redirect()->to('anggota/dashboard');
    }
}