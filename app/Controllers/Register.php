<?php
namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_param;
use App\Models\M_param_manasuka;
use App\Models\M_deposit;

class register extends Controller
{
    protected $m_user;
    protected $m_param;
    protected $m_deposit;
    protected $m_param_manasuka;

    function __construct()
    {
        $this->m_user = model(M_user::class);	
        $this->m_param = model(M_param::class);	
        $this->m_deposit = model(M_deposit::class);	
        $this->m_param_manasuka = model(M_param_manasuka::class);	
    }

    public function index()
    {
        $simpanan_pokok = $this->m_param->getParamById(1)[0];
        $simpanan_wajib = $this->m_param->getParamById(2)[0];
        
        $cek_username = $this->m_user->getUsernameGiat()[0]->username;
        $filter_int = filter_var($cek_username, FILTER_SANITIZE_NUMBER_INT);
        $clean_int = intval($filter_int);

        if ($clean_int >= 1000) {
            $username = 'GIAT'.($clean_int+1);
        } elseif ($clean_int >= 100) {
            $username = 'GIAT0'.($clean_int+1);
        } elseif ($clean_int >= 10) {
            $username = 'GIAT00'.($clean_int+1);
        } elseif ($clean_int >= 1) {
            $username = 'GIAT000'.($clean_int+1);
        }

        $data = [
            'title_meta' => view('partials/title-meta', ['title' => 'Register']),
            'simp_pokok' => $simpanan_pokok,
            'simp_wajib' => $simpanan_wajib,
            'username' => $username
        ];
        return view('auth-register', $data);		
    }

    public function register_proc()
    {
        $nik = request()->getPost('nik');
        $alamat = request()->getPost('alamat');
        $nomor_telepon = request()->getPost('nomor_telepon');
        $no_rek = request()->getPost('no_rek');

        // TODO: CAPTCHA here
        
        // note: kode lama melakukan md5 sebelum password_hash; dipertahankan agar minimal perubahan
        $pass = md5(request()->getPost('pass'));
        $pass2 = md5(request()->getPost('pass2'));
        
        $dataset = [
            'nama_lengkap' => strtoupper(request()->getPost('nama_lengkap')),
            'nik' => $nik,
            'tempat_lahir' => request()->getPost('tempat_lahir'),
            'tanggal_lahir' => request()->getPost('tanggal_lahir'),
            'instansi' => request()->getPost('instansi'),
            'unit_kerja' => request()->getPost('unit_kerja'),
            'status_pegawai' => request()->getPost('status_pegawai'),
            'alamat' => $alamat,
            'nama_bank' => strtoupper(request()->getPost('nama_bank')),
            'no_rek' => $no_rek,
            'nomor_telepon' => $nomor_telepon,
            'email' => request()->getPost('email'),
            'username' => request()->getPost('username'),
            'idgroup' => 4
        ];

        // Load reCAPTCHA helper
        helper('recaptcha');
        
        // Validate reCAPTCHA
        $recaptchaToken = request()->getPost('recaptcha_token');
        $validation = validate_recaptcha($recaptchaToken);
        
        if (!$validation['success']) {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Captcha tidak sesuai',
                    'status' => 'warning'
                ]
            );
            
            $dataset['notif'] = $alert;
            $dataset['nik'] = $nik;
            $dataset['alamat'] = $alamat;
            $dataset['nomor_telepon'] = $nomor_telepon;
            $dataset['no_rek'] = $no_rek;

            $dataset += ['notif' => $alert];
            session()->setFlashdata($dataset);
            return redirect()->to('registrasi');
        }

        if ($dataset['instansi'] == "") {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Pilih Institusi terlebih dahulu',
                     'status' => 'warning'
                ]
            );
            
            $dataset['notif'] = $alert;
            $dataset['nik'] = $nik;
            $dataset['nip'] = request()->getPost('nip');
            $dataset['alamat'] = $alamat;
            $dataset['nomor_telepon'] = $nomor_telepon;
            $dataset['no_rek'] = $no_rek;

            session()->setFlashdata($dataset);
            return redirect()->to('registrasi');
        }

        if ($dataset['status_pegawai'] == "") {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Pilih status status_pegawai terlebih dahulu',
                    'status' => 'warning'
                ]
            );
            
            $dataset['notif'] = $alert;
            $dataset['nik'] = $nik;
            $dataset['nip'] = request()->getPost('nip');
            $dataset['alamat'] = $alamat;
            $dataset['nomor_telepon'] = $nomor_telepon;
            $dataset['no_rek'] = $no_rek;

            session()->setFlashdata($dataset);
            return redirect()->to('registrasi');
        }

        $cek_username = $this->m_user->countUser($dataset['username'])[0]->hitung;

        if ($cek_username != 0) {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Username Telah Terpakai',
                    'status' => 'warning'
                ]
            );
            
            $dataset['notif'] = $alert;
            $dataset['nik'] = $nik;
            $dataset['nip'] = request()->getPost('nip');
            $dataset['alamat'] = $alamat;
            $dataset['nomor_telepon'] = $nomor_telepon;
            $dataset['no_rek'] = $no_rek;

            session()->setFlashdata($dataset);
            return redirect()->to('registrasi');
        }

        //check duplicate nip
        $nip = request()->getPost('nip');

        if($nip != null || $nip != ''){
            $cek_nip = $this->m_user->select('count(iduser) as hitung')
                ->where('nip', $nip)
                ->get()
                ->getResult()[0]
                ->hitung;

            if($cek_nip != 0){
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'NIP Telah Terdaftar',
                        'status' => 'warning'
                    ]
                );
                
                $dataset['notif'] = $alert;
                $dataset['nik'] = $nik;
                $dataset['nip'] = $nip;
                $dataset['alamat'] = $alamat;
                $dataset['nomor_telepon'] = $nomor_telepon;
                $dataset['no_rek'] = $no_rek;
    
                session()->setFlashdata($dataset);
                return redirect()->back();
            }else{
                $dataset += ['nip' => $nip];
            }
        }

        $cek_nik = $this->m_user->countNIK($dataset['nik'])[0]->hitung;

        if ($cek_nik != 0) {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'NIK Telah Terdaftar',
                    'status' => 'warning'
                ]
            );
            
            $dataset['notif'] = $alert;
            $dataset['nik'] = $nik;
            $dataset['nip'] = $nip;
            $dataset['alamat'] = $alamat;
            $dataset['nomor_telepon'] = $nomor_telepon;
            $dataset['no_rek'] = $no_rek;

            session()->setFlashdata($dataset);
            return redirect()->to('registrasi');
        }

        if ($pass != $pass2) {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Password tidak cocok',
                    'status' => 'warning'
                ]
            );
            
            $dataset['notif'] = $alert;
            $dataset['nik'] = $nik;
            $dataset['nip'] = $nip;
            $dataset['alamat'] = $alamat;
            $dataset['nomor_telepon'] = $nomor_telepon;
            $dataset['no_rek'] = $no_rek;

            session()->setFlashdata($dataset);
            return redirect()->to('registrasi');			
        } else {
            $dataset += ['pass' => password_hash($pass, PASSWORD_DEFAULT)];
        }

        // PROFILE PICTURE (sama seperti sebelumnya)
        $img = request()->getFile('profil_pic');

        if ($img->isValid()) {
            // Validation rules
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $allowedExtensions = ['jpg', 'jpeg', 'gif'];
            $maxSize = 2048; // Max size in KB (e.g., 2MB)
            $minSize = 128; // Min size in KB

            // Validate MIME Type and Extension
            if (!in_array($img->getMimeType(), $allowedTypes) || 
                        !in_array(strtolower($img->getExtension()), $allowedExtensions)) {
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Tipe Gambar Tidak Diizinkan (jpg, jpeg)',
                        'status' => 'warning'
                    ]
                );
                
                $dataset['notif'] = $alert;
                $dataset['nik'] = $nik;
                $dataset['nip'] = $nip;
                $dataset['alamat'] = $alamat;
                $dataset['nomor_telepon'] = $nomor_telepon;
                $dataset['no_rek'] = $no_rek;

                session()->setFlashdata($dataset);
                return redirect()->to('registrasi');
            }

            $imageInfo = @getimagesize($img->getTempName());
            if ($imageInfo === false) {
                // Handle invalid image
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Gambar Tidak Valid',
                        'status' => 'warning'
                    ]
                );
                
                $dataset['notif'] = $alert;
                $dataset['nik'] = $nik;
                $dataset['nip'] = $nip;
                $dataset['alamat'] = $alamat;
                $dataset['nomor_telepon'] = $nomor_telepon;
                $dataset['no_rek'] = $no_rek;

                session()->setFlashdata($dataset);
                return redirect()->to('registrasi');
            }
            
            // Validate File Size
            if ($img->getSize() / 1024 > $maxSize) {
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Gambar Terlalu Besar, Maks 2MB',
                        'status' => 'warning'
                    ]
                );
                
                $dataset['notif'] = $alert;
                $dataset['nik'] = $nik;
                $dataset['nip'] = $nip;
                $dataset['alamat'] = $alamat;
                $dataset['nomor_telepon'] = $nomor_telepon;
                $dataset['no_rek'] = $no_rek;

                session()->setFlashdata($dataset);
                return redirect()->to('registrasi');
            }

            if ($img->getSize() / 1024 < $minSize) {
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Gambar Terlalu Kecil, Min 128KB',
                        'status' => 'warning'
                    ]
                );
                
                $dataset['notif'] = $alert;
                $dataset['nik'] = $nik;
                $dataset['nip'] = $nip;
                $dataset['alamat'] = $alamat;
                $dataset['nomor_telepon'] = $nomor_telepon;
                $dataset['no_rek'] = $no_rek;

                session()->setFlashdata($dataset);
                return redirect()->to('registrasi');
            }

            // Move file to its destination if all validations pass
            $newName = $img->getRandomName();
            $img->move(ROOTPATH . 'public/uploads/user/' . $dataset['username'] . '/profil_pic/', $newName);
            $profile_pic = $img->getName();
            $dataset += ['profil_pic' => $profile_pic];
        } else {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Gambar Tidak Valid',
                    'status' => 'warning'
                ]
            );
            
            $dataset['notif'] = $alert;
            $dataset['nik'] = $nik;
            $dataset['nip'] = $nip;
            $dataset['alamat'] = $alamat;
            $dataset['nomor_telepon'] = $nomor_telepon;
            $dataset['no_rek'] = $no_rek;

            session()->setFlashdata($dataset);
            return redirect()->to('registrasi');
        }

        // --- NEW: KTP FILE HANDLING ---
        $ktp = request()->getFile('ktp_file');

        if ($ktp && $ktp->isValid()) {
            // Accept jpg/jpeg and pdf as KTP formats
            $allowedKtpMime = ['image/jpeg', 'image/png', 'application/pdf'];
            $allowedKtpExt = ['jpg', 'jpeg', 'png', 'pdf'];
            $maxKtpSize = 4096; // 4MB max for KTP

            if (!in_array($ktp->getMimeType(), $allowedKtpMime) || !in_array(strtolower($ktp->getExtension()), $allowedKtpExt)) {
                $alert = view('partials/notification-alert', ['notif_text' => 'File KTP harus JPG/JPEG/PNG/PDF', 'status' => 'warning']);
                $dataset['notif'] = $alert;
                session()->setFlashdata($dataset);
                return redirect()->to('registrasi');
            }

            if ($ktp->getSize() / 1024 > $maxKtpSize) {
                $alert = view('partials/notification-alert', ['notif_text' => 'File KTP terlalu besar, maksimal 4MB', 'status' => 'warning']);
                $dataset['notif'] = $alert;
                session()->setFlashdata($dataset);
                return redirect()->to('registrasi');
            }

            // Move KTP file to user folder
            $ktpNewName = $ktp->getRandomName();
            $ktp->move(ROOTPATH . 'public/uploads/user/' . $dataset['username'] . '/ktp/', $ktpNewName);
            $dataset += ['ktp_file' => $ktp->getName()];
        } else {
            $alert = view('partials/notification-alert', ['notif_text' => 'KTP harus diupload', 'status' => 'warning']);
            $dataset['notif'] = $alert;
            session()->setFlashdata($dataset);
            return redirect()->to('registrasi');
        }

        // --- set created and flags ---
        $dataset += [
            'created' => date('Y-m-d H:i:s'),
            'closebook_param_count' => 0,
            'flag' => 1,
            // important: set to 0 (non aktif) until admin verifikasi KTP
            'verified' => 0
        ];
        
        $this->m_user->insertUser($dataset);

        $iduser_new = $this->m_user->getUser($dataset['username'])[0]->iduser;
        
        $init_aktivasi = [
            $this->m_param->getParamById(1)[0]->nilai,
            $this->m_param->getParamById(2)[0]->nilai
        ];

        $j_deposit_r = ['pokok', 'wajib'];

        for ($i = 0; $i < count($init_aktivasi); $i++) {
            $dataset_deposit = [
                'jenis_pengajuan' => 'penyimpanan',
                'jenis_deposit' => $j_deposit_r[$i],
                'cash_in' => $init_aktivasi[$i],
                'cash_out' => 0,
                'deskripsi' => 'biaya awal registrasi',
                'status' => 'diproses',
                'date_created' => date('Y-m-d H:i:s'),
                'idanggota' => $iduser_new
            ];

            $this->m_deposit->insertDeposit($dataset_deposit);
        }
        
        $alert = view(
            'partials/notification-alert', 
            [
                'notif_text' => 'User berhasil dibuat. Akun sementara tidak aktif. Menunggu verifikasi KTP oleh admin.',
                'status' => 'success'
            ]
        );
        
        $data_session = [
            'username' => request()->getPost('username'),
            'notif_login' => $alert
        ];

        session()->setFlashdata($data_session);
        return redirect()->to('/');
    }
}