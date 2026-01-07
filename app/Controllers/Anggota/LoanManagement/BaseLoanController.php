<?php

namespace App\Controllers\Anggota\LoanManagement;

use CodeIgniter\Controller;

use App\Models\M_user;
use App\Models\M_pinjaman;
use App\Models\M_cicilan;
use App\Models\M_cicilan_pag;
use App\Models\M_param;
use App\Models\M_notification;
use App\Models\M_asuransi;

use App\Controllers\Anggota\Core\Notifications;

/**
 * Base controller for loan management (Anggota)
 * Provides shared functionality for all loan-related controllers
 */
abstract class BaseLoanController extends Controller
{
    protected $m_user;
    protected $m_pinjaman;
    protected $m_cicilan;
    protected $m_cicilan_pag;
    protected $m_param;
    protected $m_notification;
    protected $m_asuransi;
    protected $notification;
    protected $account;

    public function __construct()
    {
        $this->m_user = model(M_user::class);
        $this->m_pinjaman = model(M_pinjaman::class);
        $this->m_cicilan = model(M_cicilan::class);
        $this->m_cicilan_pag = model(M_cicilan_pag::class);
        $this->m_param = model(M_param::class);
        $this->m_notification = model(M_notification::class);
        $this->m_asuransi = model(M_asuransi::class);

        $this->notification = new Notifications();
        $this->account = $this->m_user->getUserById(session()->get('iduser'))[0];
    }

    /**
     * Get base view data (common data for all views)
     */
    protected function getBaseViewData(string $title, string $subtitle = 'Pinjaman'): array
    {
        return [
            'title' => $title,
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account
        ];
    }

    /**
     * Send alert message via flashdata
     * @param bool $returnView If true, returns the view instead of setting flashdata
     */
    protected function sendAlert(string $message, string $status = 'success', bool $returnView = true): mixed
    {
        $alert = view('partials/notification-alert', [
            'notif_text' => $message,
            'status' => $status
        ]);

        if ($returnView) {
            session()->setFlashdata('notif', $alert);
            return null;
        } else {
            return $alert;
        }
    }

    /**
     * Create notification for a loan action
     */
    protected function createNotification(int $pinjamanId, string $message, int $groupType): void
    {
        $notificationData = [
            'anggota_id' => $this->account->iduser,
            'pinjaman_id' => $pinjamanId,
            'message' => $message,
            'timestamp' => date('Y-m-d H:i:s'),
            'group_type' => $groupType
        ];

        $this->m_notification->insert($notificationData);
    }

    /**
     * Get employee limits based on status
     */
    protected function getEmployeeLimits(): array
    {
        $status_pegawai = $this->account->status_pegawai;

        if ($status_pegawai == 'tetap') {
            return [
                'batas_bulanan' => 24,
                'batas_nominal' => 50000000,
                'status_pegawai' => 'tetap'
            ];
        } else {
            return [
                'batas_bulanan' => 12,
                'batas_nominal' => 15000000,
                'status_pegawai' => 'kontrak'
            ];
        }
    }

    /**
     * Calculate insurance data and insert into database
     */
    protected function insertInsuranceData(int $pinjamanId, int $angsuranBulanan): void
    {
        // ID 12: Bulan Kelipatan Asuransi
        // ID 13: Nominal Asuransi
        $bulan_kelipatan = (int)$this->m_param->getParamById(12)[0]->nilai;
        $nominal_asuransi = (float)$this->m_param->getParamById(13)[0]->nilai;
        // ID 8: Tanggal Cut-off
        $cutoff_day = (int)$this->m_param->getParamById(8)[0]->nilai;

        // Calculate Start Date and End Date
        $today_day = (int)date('d');
        $current_month = (int)date('m');
        $current_year = (int)date('Y');

        if ($today_day <= $cutoff_day) {
            $start_date_ts = mktime(0, 0, 0, $current_month, $cutoff_day, $current_year);
        } else {
            $start_date_ts = mktime(0, 0, 0, $current_month + 1, $cutoff_day, $current_year);
        }

        $start_date = date('Y-m-d H:i:s', $start_date_ts);
        $end_date = date('Y-m-d H:i:s', strtotime('+1 year', $start_date_ts));

        log_message('info', 'Insurance calculation - Angsuran: ' . $angsuranBulanan . ', Kelipatan: ' . $bulan_kelipatan . ', Nominal: ' . $nominal_asuransi);

        $asuransi_data = $this->m_asuransi->calculateAsuransi($angsuranBulanan, $nominal_asuransi, $bulan_kelipatan);

        log_message('info', 'Insurance data to insert: ' . json_encode($asuransi_data));

        foreach ($asuransi_data as $asuransi) {
            $asuransi_insert_data = [
                'idpinjaman' => $pinjamanId,
                'bulan_kumulatif' => $asuransi['bulan_kumulatif'],
                'nilai_asuransi' => $asuransi['nilai_asuransi'],
                'status' => 'aktif',
                'start_date' => $start_date,
                'end_date' => $end_date
            ];

            log_message('info', 'Inserting asuransi: ' . json_encode($asuransi_insert_data));
            $this->m_asuransi->insertAsuransi($asuransi_insert_data);
        }
    }
}
