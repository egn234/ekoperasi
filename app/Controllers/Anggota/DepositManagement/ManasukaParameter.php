<?php 
namespace App\Controllers\Anggota\DepositManagement;

use App\Models\M_param_manasuka_log;

class ManasukaParameter extends BaseDepositController
{

    public function create_param_manasuka()
    {
        $dataset = [
            'idanggota' => $this->account->iduser,
            'nilai' => filter_var($this->request->getPost('nilai'), FILTER_SANITIZE_NUMBER_INT),
            'created' => date('Y-m-d H:i:s')
        ];

        $this->m_param_manasuka->insertParamManasuka($dataset);

        $idmnskparam = $this->m_param_manasuka
            ->where('idanggota', $this->account->iduser)
            ->orderBy('idmnskparam', 'desc')
            ->first()
            ->idmnskparam;

        $this->createNotification(
            null,
            $idmnskparam,
            $this->account->nama_lengkap . " menyetujui pembayaran bulanan manasuka sebesar Rp " . number_format($dataset['nilai'], 0, ',', '.'),
            1
        );

        $this->sendAlert('Parameter Manasuka berhasil di set', 'success');
        return redirect()->back();
    }

    public function set_param_manasuka($idmnskparam = false)
    {
        $m_param_manasuka_log = new M_param_manasuka_log();
        $dataset = [
            'nilai' => filter_var($this->request->getPost('nilai'), FILTER_SANITIZE_NUMBER_INT),
            'updated' => date('Y-m-d H:i:s')
        ];

        if ($dataset['nilai'] > 50000) {
            $this->m_param_manasuka->updateParamManasuka($idmnskparam, $dataset);
            
            $temp_log = [
                'nominal' => $dataset['nilai'],
                'idmnskparam' => $idmnskparam,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $m_param_manasuka_log->insert($temp_log);
            
            $message = $this->account->nama_lengkap . " mengubah pembayaran bulanan manasuka menjadi Rp " . number_format($dataset['nilai'], 0, ',', '.');
            $this->createNotification(null, $idmnskparam, $message, 1);
            
            $this->sendAlert('Parameter Manasuka berhasil di set', 'success');
        } else {
            $this->sendAlert('Pengajuan manasuka tidak boleh kurang dari Rp 50.000', 'warning');
        }
        return redirect()->back();
    }

    public function cancel_param_manasuka($idmnskparam = false)
    {
        $iduser = $this->account->iduser;

        $dataset = [
            'nilai' => 0,
            'updated' => date('Y-m-d H:i:s')
        ];
        
        $this->m_param_manasuka->updateParamManasuka($idmnskparam, $dataset);

        $this->createNotification(
            null,
            $idmnskparam,
            $this->account->nama_lengkap . " telah membatalkan pembayaran manasuka bulanan",
            1
        );

        $this->sendAlert('Pengajuan pembatalan manasuka berhasil', 'success');
        return redirect()->back();
    }
}
