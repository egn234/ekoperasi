<?php

namespace App\Controllers\Admin\UserManagement;

require_once ROOTPATH.'vendor/autoload.php';

use CodeIgniter\Controller;
use App\Models\M_user;
use App\Models\M_group;
use App\Models\M_deposit;
use App\Models\M_param;
use App\Models\M_param_manasuka;
use App\Models\M_pinjaman;
use App\Models\M_cicilan;
use App\Controllers\Admin\Core\Notifications;

/**
 * Base controller for user management
 * Provides shared functionality for all user-related controllers
 */
abstract class BaseUserController extends Controller
{
    protected $m_user;
    protected $m_group;
    protected $m_deposit;
    protected $m_param;
    protected $m_param_manasuka;
    protected $m_pinjaman;
    protected $m_cicilan;
    protected $notification;
    protected $account;

    public function __construct()
    {
        $this->m_user = model(M_user::class);
        $this->m_group = model(M_group::class);
        $this->m_deposit = model(M_deposit::class);
        $this->m_param = model(M_param::class);
        $this->m_param_manasuka = model(M_param_manasuka::class);
        $this->m_pinjaman = model(M_pinjaman::class);
        $this->m_cicilan = model(M_cicilan::class);
        $this->notification = new Notifications();
        $this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
    }

    /**
     * Get base view data (common data for all views)
     */
    protected function getBaseViewData(string $title, string $subtitle = 'User'): array
    {
        return [
            'title_meta' => view('admin/partials/title-meta', ['title' => $title]),
            'page_title' => view('admin/partials/page-title', [
                'title' => $title,
                'li_1' => 'EKoperasi',
                'li_2' => $subtitle
            ]),
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account
        ];
    }

    /**
     * Send alert message via flashdata
     */
    protected function sendAlert(string $message, string $status = 'success'): void
    {
        $alert = view('partials/notification-alert', [
            'notif_text' => $message,
            'status' => $status
        ]);
        
        session()->setFlashdata('notif', $alert);
    }

    /**
     * Generate next GIAT username
     */
    protected function generateGiatUsername(): string
    {
        $cek_username = $this->m_user->getUsernameGiat() ? 
            $this->m_user->getUsernameGiat()[0]->username : 'GIAT0000';
        
        $filter_int = filter_var($cek_username, FILTER_SANITIZE_NUMBER_INT);
        $clean_int = intval($filter_int);

        if ($clean_int >= 1000) {
            return 'GIAT' . ($clean_int + 1);
        } elseif ($clean_int >= 100) {
            return 'GIAT0' . ($clean_int + 1);
        } elseif ($clean_int >= 10) {
            return 'GIAT00' . ($clean_int + 1);
        } else {
            return 'GIAT000' . ($clean_int + 1);
        }
    }

    /**
     * Create initial deposits for new member
     */
    protected function createInitialDeposits(int $iduser): void
    {
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
                'deskripsi' => 'biaya awal registrasi',
                'status' => 'diproses',
                'date_created' => date('Y-m-d H:i:s'),
                'idanggota' => $iduser
            ];

            $this->m_deposit->insertDeposit($dataset);
        }
    }

    /**
     * Create initial manasuka parameter for new member
     */
    protected function createInitialManasukaParam(int $iduser): void
    {
        $param_r = [
            'idanggota' => $iduser,
            'nilai' => $this->m_param->getParamById(3)[0]->nilai,
            'created' => date('Y-m-d H:i:s')
        ];

        $this->m_param_manasuka->insertParamManasuka($param_r);
    }
}
