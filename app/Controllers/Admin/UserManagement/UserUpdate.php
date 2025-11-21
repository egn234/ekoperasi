<?php 
namespace App\Controllers\Admin\UserManagement;

class UserUpdate extends BaseUserController
{
    public function update_proc($iduser = false)
    {
        $alamat = request()->getPost('alamat');
        $no_rek = request()->getPost('no_rek');
        $nomor_telepon = request()->getPost('nomor_telepon');

        $old_user = $this->m_user->getUserById($iduser)[0];

        $dataset = [
            'nama_lengkap' => strtoupper((string) request()->getPost('nama_lengkap')),
            'tempat_lahir' => request()->getPost('tempat_lahir'),
            'tanggal_lahir' => request()->getPost('tanggal_lahir'),
            'instansi' => request()->getPost('instansi'),
            'alamat' => $alamat,
            'nomor_telepon' => $nomor_telepon,
            'status_pegawai' => request()->getPost('status_pegawai'),
            'email' => request()->getPost('email'),
            'unit_kerja' => request()->getPost('unit_kerja'),
            'idgroup' => request()->getPost('idgroup'),
            'nama_bank' => strtoupper((string) request()->getPost('nama_bank')),
            'no_rek' => $no_rek,
        ];
        
        //check duplicate nip
        $nip_baru = request()->getPost('nip');

        if($nip_baru != null || $nip_baru != ''){
            $nip_awal = $old_user->nip;

            if($nip_awal != $nip_baru){
                $cek_nip = $this->m_user->select('count(iduser) as hitung')
                    ->where("nip = '".$nip_baru."' AND iduser != ".$iduser)
                    ->get()->getResult()[0]->hitung;

                if ($cek_nip == 0) {
                    $dataset += ['nip' => $nip_baru];
                }else{
                    $alert = view(
                        'partials/notification-alert', 
                        [
                            'notif_text' => 'NIP telah terdaftar',
                            'status' => 'danger'
                        ]
                    );
                    
                    $data_session = [ 'notif' => $alert ];

                    session()->setFlashdata($data_session);
                    return redirect()->back();
                }	
            }
        }

        //check duplicate nik
        $nik_baru = request()->getPost('nik');
        $nik_awal = $old_user->nik;

        if ($nik_baru != $nik_awal) {
            $cek_nik = $this->m_user->select('count(iduser) as hitung')
                ->where("nik = '".$nik_baru."' AND iduser != ".$iduser)
                ->get()->getResult()[0]->hitung;

            if ($cek_nik == 0) {
                $dataset += ['nik' => $nik_baru];
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

        $new_pass = md5(request()->getPost('pass'));
        $cek_pass = md5(request()->getPost('pass2'));

        if ($new_pass != "" || $new_pass != null)
        {
            if ($new_pass == $cek_pass) 
            {
                $dataset += ['pass' => password_hash($new_pass, PASSWORD_DEFAULT)];
            }
            else
            {
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'konfirmasi password tidak sesuai',
                        'status' => 'warning'
                    ]
                );
                
                $data_session = [
                    'notif' => $alert
                ];

                session()->setFlashdata($data_session);
                return redirect()->to('admin/user/'.$iduser);
            }
        }

        $img = request()->getFile('profil_pic');

        if ($img->isValid() && !$img->hasMoved()) {
            $allowedTypes = ['image/png', 'image/jpg', 'image/jpeg'];

            if (in_array($img->getMimeType(), $allowedTypes)) {
                $newName = $img->getRandomName();
                $img->move(ROOTPATH . 'public/uploads/user/' . $dataset['username'] . '/profil_pic/', $newName);
                $profile_pic = $img->getName();
                $dataset += ['profil_pic' => $profile_pic];	
            }
            else {
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Tipe file tidak diizinkan', 
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

        $dataset += ['updated' => date('Y-m-d H:i:s')];
        
        $this->m_user->updateUser($iduser, $dataset);
        
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
        return redirect()->to('admin/user/'.$iduser);
    }
}
