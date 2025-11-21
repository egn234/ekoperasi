<?php

namespace App\Controllers\Admin\UserManagement;

/**
 * UserCreation Controller
 * Handles user creation (manual form-based registration)
 */
class UserCreation extends BaseUserController
{
    /**
     * Display add user form
     */
    public function add_user()
    {
        $group_list = $this->m_group->getAllGroup();
        $username = $this->generateGiatUsername();

        $data = $this->getBaseViewData('User', 'New User');
        $data['grp_list'] = $group_list;
        $data['username'] = $username;
        
        return view('admin/user/add-user', $data);
    }

    /**
     * Process add user form submission
     */
    public function add_user_proc()
    {
        $nik = request()->getPost('nik');
        $alamat = request()->getPost('alamat');
        $no_rek = request()->getPost('no_rek');
        $nomor_telepon = request()->getPost('nomor_telepon');
        $pass = md5(request()->getPost('pass'));
        $pass2 = md5(request()->getPost('pass2'));

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
            'username' => request()->getPost('username'),
            'idgroup' => request()->getPost('idgroup'),
            'nama_bank' => strtoupper((string) request()->getPost('nama_bank')),
            'no_rek' => $no_rek,
        ];

        // Validate instansi
        if ($dataset['instansi'] == "") {
            $this->sendAlert('Pilih Institusi terlebih dahulu', 'warning');
            session()->setFlashdata($dataset);
            return redirect()->to('admin/user/add');
        }

        // Check username
        $cek_username = $this->m_user->countUser($dataset['username'])[0]->hitung;
        if ($cek_username != 0) {
            $this->sendAlert('Username Telah Terpakai', 'warning');
            session()->setFlashdata($dataset);
            return redirect()->to('admin/user/add');
        }
        
        // Check duplicate NIP
        $nip = request()->getPost('nip');
        if ($nip != null && $nip != '') {
            $cek_nip = $this->m_user->select('count(iduser) as hitung')
                ->where('nip', $nip)
                ->get()
                ->getResult()[0]
                ->hitung;

            if ($cek_nip != 0) {
                $this->sendAlert('NIP Telah Terdaftar', 'warning');
                $dataset['nip'] = $nip;
                session()->setFlashdata($dataset);
                return redirect()->back();
            } else {
                $dataset['nip'] = $nip;
            }
        }

        // Check duplicate NIK
        if ($nik != null && $nik != '') {
            $cek_nik = $this->m_user->countNIK($nik)[0]->hitung;

            if ($cek_nik != 0) {
                $this->sendAlert('NIK Telah Terdaftar', 'warning');
                session()->setFlashdata($dataset);
                return redirect()->to('admin/user/add');
            } else {
                $dataset['nik'] = $nik;
            }
        } else {
            $this->sendAlert('NIK tidak boleh kosong', 'warning');
            session()->setFlashdata($dataset);
            return redirect()->to('admin/user/add');
        }

        // Validate password
        if ($pass != $pass2) {
            $this->sendAlert('Password tidak cocok', 'warning');
            session()->setFlashdata($dataset);
            return redirect()->to('admin/user/add');			
        } else {
            $dataset['pass'] = password_hash($pass, PASSWORD_DEFAULT);
        }

        // Validate group
        if ($dataset['idgroup'] == "") {
            $this->sendAlert('Pilih Grup terlebih dahulu', 'warning');
            session()->setFlashdata($dataset);
            return redirect()->to('admin/user/add');
        }

        // Handle profile picture upload
        $img = request()->getFile('profil_pic');
        if ($img->isValid() && !$img->hasMoved()) {
            $allowedTypes = ['image/png', 'image/jpg', 'image/jpeg'];

            if (in_array($img->getMimeType(), $allowedTypes)) {
                $newName = $img->getRandomName();
                $img->move(ROOTPATH . 'public/uploads/user/' . $dataset['username'] . '/profil_pic/', $newName);
                $profile_pic = $img->getName();
                $dataset['profil_pic'] = $profile_pic;	
            } else {
                $this->sendAlert('Tipe file tidak diizinkan', 'danger');
                return redirect()->back();
            }
        }

        $dataset['created'] = date('Y-m-d H:i:s');
        $dataset['closebook_param_count'] = 0;
        $dataset['flag'] = 1;
        
        $this->m_user->insertUser($dataset);

        // Create initial deposits for member group (idgroup == 4)
        if ($dataset['idgroup'] == 4) {
            $iduser_new = $this->m_user->getUser($dataset['username'])[0]->iduser;
            $this->createInitialDeposits($iduser_new);
            $this->createInitialManasukaParam($iduser_new);
        }
                
        $this->sendAlert('User berhasil dibuat');
        return redirect()->to('admin/user/list');
    }
}
