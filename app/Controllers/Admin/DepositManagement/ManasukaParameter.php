<?php 
namespace App\Controllers\Admin\DepositManagement;

/**
 * ManasukaParameter Controller
 * 
 * Mengelola parameter simpanan manasuka:
 * - Create parameter manasuka untuk anggota baru
 * - Update parameter manasuka
 * - Cancel parameter manasuka
 * - Log perubahan parameter
 */
class ManasukaParameter extends BaseDepositController
{
    /**
     * Create parameter manasuka baru
     * Route: POST /admin/deposit/create_param_manasuka
     */
    public function create()
    {
        $nilai = filter_var(request()->getPost('nilai'), FILTER_SANITIZE_NUMBER_INT);
        $iduser = request()->getPost('iduser');

        if (!$iduser) {
            $this->sendAlert('ID User tidak valid', 'danger');
            return redirect()->back();
        }

        if ($nilai < 50000) {
            $this->sendAlert('Parameter manasuka tidak boleh kurang dari Rp 50.000', 'warning');
            return redirect()->back();
        }

        $dataset = [
            'idanggota' => $iduser,
            'nilai' => $nilai,
            'created' => date('Y-m-d H:i:s')
        ];

        $this->m_param_manasuka->insertParamManasuka($dataset);
        $this->sendAlert('Parameter Manasuka berhasil di set', 'success');

        return redirect()->back();
    }

    /**
     * Update parameter manasuka
     * Route: /admin/deposit/set_param_manasuka/{id}
     */
    public function update($idmnskparam = false)
    {
        if (!$idmnskparam) {
            $this->sendAlert('ID Parameter tidak valid', 'danger');
            return redirect()->back();
        }

        $nilai = filter_var(request()->getPost('nilai'), FILTER_SANITIZE_NUMBER_INT);

        if ($nilai < 50000) {
            $this->sendAlert('Pengajuan manasuka tidak boleh kurang dari Rp 50.000', 'warning');
            return redirect()->back();
        }

        $dataset = [
            'nilai' => $nilai,
            'updated' => date('Y-m-d H:i:s')
        ];

        $this->m_param_manasuka->updateParamManasuka($idmnskparam, $dataset);
        
        // Log the change
        $temp_log = [
            'nominal' => $nilai,
            'idmnskparam' => $idmnskparam,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $this->m_param_manasuka_log->insert($temp_log);
        $this->sendAlert('Parameter Manasuka berhasil di set', 'success');

        return redirect()->back();
    }

    /**
     * Cancel/reset parameter manasuka
     * Route: /admin/deposit/cancel_param_manasuka/{id}
     */
    public function cancel($idmnskparam = false)
    {
        if (!$idmnskparam) {
            $this->sendAlert('ID Parameter tidak valid', 'danger');
            return redirect()->back();
        }

        $dataset = [
            'nilai' => 0,
            'updated' => date('Y-m-d H:i:s')
        ];
        
        $this->m_param_manasuka->updateParamManasuka($idmnskparam, $dataset);
        $this->sendAlert('Pembatalan manasuka berhasil', 'success');
        
        return redirect()->back();
    }
}
