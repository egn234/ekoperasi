<?php 
namespace App\Controllers\Admin\UserManagement;

class UserClosebook extends BaseUserController
{
    public function flag_switch($iduser = false)
    {
        $user = $this->m_user->getUserById($iduser)[0];

        if ($user->user_flag == 0) {
            
            if ($user->closebook_param_count == 1) {

                $init_aktivasi = [
                    $this->m_param->getParamById(1)[0]->nilai,
                    $this->m_param->getParamById(2)[0]->nilai
                ];

                $j_deposit_r = ['pokok', 'wajib'];

                for ($i = 0; $i < count($init_aktivasi); $i++) {
                    $dataset = [
                        'jenis_pengajuan' => 'penyimpanan',
                        'jenis_deposit' => $j_deposit_r[$i],
                        'cash_in' => $init_aktivasi[$i],
                        'cash_out' => 0,
                        'deskripsi' => 'biaya registrasi',
                    'status' => 'diproses',
                        'date_created' => date('Y-m-d H:i:s'),
                        'idanggota' => $iduser
                    ];

                    $this->m_deposit->insertDeposit($dataset);
                }

                $param_r = [
                    'nilai' => $this->m_param->getParamById(3)[0]->nilai,
                    'updated' => date('Y-m-d H:i:s')
                ];

                $this->m_param_manasuka->setParamManasukaByUser($iduser, $param_r);
                
                $this->m_user->aktifkanUser($iduser);

                $alert = view('partials/notification-alert', 
                    [
                        'notif_text' => 'User Diaktifkan',
                        'status' => 'success'
                    ]
                );
                
                session()->setFlashdata('notif', $alert);

            }elseif ($user->closebook_param_count == 2) {

                $alert = view('partials/notification-alert', 
                    [
                        'notif_text' => 'User Sudah melebihi batas aktivasi',
                    'status' => 'danger'
                    ]
                );
                
                session()->setFlashdata('notif', $alert);
            }

        }elseif ($user->user_flag == 1) {

            // VALIDASI KETAT SEBELUM CLOSEBOOK
            
            // Validasi 1: Cek apakah ada pinjaman aktif
            $pinjaman_aktif = $this->m_pinjaman->countPinjamanAktifByAnggota($iduser);
            if (!empty($pinjaman_aktif) && $pinjaman_aktif[0]->hitung > 0) {
                $alert = view('partials/notification-alert', 
                    [
                        'notif_text' => 'Tidak dapat menonaktifkan user. User masih memiliki ' . $pinjaman_aktif[0]->hitung . ' pinjaman aktif yang belum lunas.',
                        'status' => 'danger'
                    ]
                );
                session()->setFlashdata('notif', $alert);
                return redirect()->back();
            }

            // Validasi 2: Cek apakah ada deposit yang masih diproses
            $deposit_pending = $this->m_deposit->countDepositPendingByUser($iduser);
            if (!empty($deposit_pending) && $deposit_pending[0]->hitung > 0) {
                $alert = view('partials/notification-alert', 
                    [
                        'notif_text' => 'Tidak dapat menonaktifkan user. User masih memiliki ' . $deposit_pending[0]->hitung . ' transaksi deposit yang belum diproses.',
                        'status' => 'danger'
                    ]
                );
                session()->setFlashdata('notif', $alert);
                return redirect()->back();
            }

            $saldo_r = [
                $this->m_deposit->getSaldoWajibByUserId($iduser)[0]->saldo,
                $this->m_deposit->getSaldoPokokByUserId($iduser)[0]->saldo,
                $this->m_deposit->getSaldoManasukaByUserId($iduser)[0]->saldo
            ];

            $j_deposit_r = ['wajib', 'pokok', 'manasuka'];

            for ($i = 0; $i < count($saldo_r); $i++) {
                $dataset = [
                    'jenis_pengajuan' => 'penarikan',
                    'jenis_deposit' => $j_deposit_r[$i],
                    'cash_in' => 0,
                    'cash_out' => ($saldo_r[$i] == null)?0:$saldo_r[$i],
                    'deskripsi' => 'tutup buku',
                    'status' => 'diterima',
                    'date_created' => date('Y-m-d H:i:s'),
                    'idanggota' => $iduser,
                    'idadmin' => $this->account->iduser
                ];

                $this->m_deposit->insertDeposit($dataset);
            }
            
            $param_r = [
                'nilai' => 0,
                'updated' => date('Y-m-d H:i:s')
            ];

            $this->m_param_manasuka->setParamManasukaByUser($iduser, $param_r);
            $this->m_deposit->setStatusProses($iduser);
            
            // Update closebook_last_updated timestamp
            $dataset_user = [
                'closebook_last_updated' => date('Y-m-d H:i:s')
            ];
            $this->m_user->updateUser($iduser, $dataset_user);
            
            $this->m_user->nonaktifkanUser($iduser);

            if ($user->closebook_param_count == 0) {
                $this->m_user->closebookCount($iduser, 1);
                
            }elseif ($user->closebook_param_count == 1) {
                $this->m_user->closebookCount($iduser, 2);
            }

            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'User berhasil dinonaktifkan (Closebook completed)',
                    'status' => 'success'
                ]
            );

            session()->setFlashdata('notif', $alert);
        }

        return redirect()->back();
    }
}
