<?php

namespace App\Controllers\Anggota\AccountManagement;

use App\Controllers\BaseController;
use App\Controllers\Anggota\Core\Notifications;

use App\Models\M_user;
use App\Models\M_deposit;
use App\Models\M_param;
use App\Models\M_notification;
use App\Models\M_pinjaman;

class Closebook extends BaseController
{
    protected $m_user;
    protected $m_deposit;
    protected $m_param;
    protected $m_notification;
    protected $m_pinjaman;
    protected $account;
    protected $notification;

    function __construct()
    {
        $this->m_user = model(M_user::class);
        $this->m_deposit = model(M_deposit::class);
        $this->m_param = model(M_param::class);
        $this->m_notification = model(M_notification::class);
        $this->m_pinjaman = model(M_pinjaman::class);

        $this->notification = new Notifications();

        $user = $this->m_user->getUserById(session()->get('iduser'));
        $this->account = !empty($user) ? $user[0] : null;
    }

    public function index()
    {
        $total_saldo_wajib = $this->m_deposit->getSaldoWajibByUserId($this->account->iduser)[0]->saldo;
        $total_saldo_pokok = $this->m_deposit->getSaldoPokokByUserId($this->account->iduser)[0]->saldo;
        $total_saldo_manasuka = $this->m_deposit->getSaldoManasukaByUserId($this->account->iduser)[0]->saldo;

        // Cek pinjaman aktif
        $pinjaman_aktif = $this->m_pinjaman->countPinjamanAktifByAnggota($this->account->iduser);
        $jumlah_pinjaman_aktif = !empty($pinjaman_aktif) ? $pinjaman_aktif[0]->hitung : 0;

        // Cek deposit pending
        $deposit_pending = $this->m_deposit->countDepositPendingByUser($this->account->iduser);
        $jumlah_deposit_pending = !empty($deposit_pending) ? $deposit_pending[0]->hitung : 0;

        $data = [
            'title' => 'Tutup Buku',
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account,
            'total_saldo_wajib' => $total_saldo_wajib,
            'total_saldo_pokok' => $total_saldo_pokok,
            'total_saldo_manasuka' => $total_saldo_manasuka,
            'jumlah_pinjaman_aktif' => $jumlah_pinjaman_aktif,
            'jumlah_deposit_pending' => $jumlah_deposit_pending
        ];

        return view('anggota/closebook-page', $data);
    }

    public function closebook_proc()
    {
        // Validasi 1: Cek apakah ada pinjaman aktif
        $pinjaman_aktif = $this->m_pinjaman->countPinjamanAktifByAnggota($this->account->iduser);
        if (!empty($pinjaman_aktif) && $pinjaman_aktif[0]->hitung > 0) {
            $alert = view(
                'partials/notification-alert',
                [
                    'notif_text' => 'Tidak dapat mengajukan closebook. Anda masih memiliki ' . $pinjaman_aktif[0]->hitung . ' pinjaman aktif yang belum lunas.',
                    'status' => 'danger'
                ]
            );
            session()->setFlashdata('notif', $alert);
            return redirect()->back();
        }

        // Validasi 2: Cek apakah ada deposit yang masih diproses
        $deposit_pending = $this->m_deposit->countDepositPendingByUser($this->account->iduser);
        if (!empty($deposit_pending) && $deposit_pending[0]->hitung > 0) {
            $alert = view(
                'partials/notification-alert',
                [
                    'notif_text' => 'Tidak dapat mengajukan closebook. Anda masih memiliki ' . $deposit_pending[0]->hitung . ' transaksi deposit yang belum diproses.',
                    'status' => 'danger'
                ]
            );
            session()->setFlashdata('notif', $alert);
            return redirect()->back();
        }

        $dataset = [
            'closebook_request' => 'closebook',
            'closebook_request_date' => date('Y-m-d H:i:s')
        ];

        $this->m_user->updateUser($this->account->iduser, $dataset);

        $notification_data = [
            'anggota_id' => $this->account->iduser,
            'closebook' => '1',
            'message' => 'Pengajuan tutup buku dari anggota ' . $this->account->nama_lengkap,
            'timestamp' => date('Y-m-d H:i:s'),
            'group_type' => 1
        ];

        $this->m_notification->insert($notification_data);

        $alert = view(
            'partials/notification-alert',
            [
                'notif_text' => 'Berhasil mengajukan closebook. Tunggu konfirmasi dari admin.',
                'status' => 'success'
            ]
        );

        session()->setFlashdata('notif', $alert);
        return redirect()->back();
    }

    public function closebook_cancel()
    {
        $dataset = [
            'closebook_request' => null
        ];

        $this->m_user->updateUser($this->account->iduser, $dataset);

        $alert = view(
            'partials/notification-alert',
            [
                'notif_text' => 'closebook dibatalkan',
                'status' => 'warning'
            ]
        );

        session()->setFlashdata('notif', $alert);
        return redirect()->back();
    }
}
