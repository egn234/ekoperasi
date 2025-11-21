<?php

namespace App\Controllers\Admin\ReportManagement;

use App\Models\M_user;
use App\Models\M_monthly_report;
use App\Models\M_param;
use App\Models\M_deposit;
use App\Models\M_param_manasuka;
use App\Models\M_pinjaman;
use App\Models\M_cicilan;

/**
 * MonthlyReportGenerator Controller
 * Handles automatic monthly report generation with automation logic
 */
class MonthlyReportGenerator extends BaseReportController
{
    /**
     * Generate monthly report
     * Automates deposits and loan installments for all active members
     */
    public function gen_report()
    {
        $m_monthly_report = model(M_monthly_report::class);
        $m_user = model(M_user::class);
        $m_param = model(M_param::class);

        // 1. ambil konfigurasi awal
        $YEAR = date('Y');
        $MONTH = date('m');
        $startDate = $m_monthly_report->orderBy('idreportm', 'DESC')->get(1)->getResult()[0]->created;
        $endDate = date('Y-m-d H:i:s');
        $params = $this->fetchParams($m_param);

        // 2. ambil semua anggota aktif
        $list_anggota = $m_user->where('flag', '1')->where('idgroup', 4)->get()->getResult();

        $logReport = $m_monthly_report->where('date_monthly', $YEAR.'-'.$MONTH)->countAllResults();

        if ($logReport == 0 || !$logReport){

            // 3. Proses User Baru
            $this->handleNewUsers($startDate, $endDate);

            // 4. Proses Simpanan dan Pinjaman User
            foreach ($list_anggota as $a){
                $this->processPokok($a->iduser, $params['pokok']);
                $this->processWajib($a->iduser, $params['wajib'], $startDate, $endDate);
                $this->processManasuka($a->iduser);
                $this->processPinjaman($a->iduser, $params['bunga'], $params['provisi'], $startDate, $endDate);
            }
            
            // 5. Buat Log Report
            $monthly_log = [
                'date_monthly' => $YEAR.'-'.$MONTH,
                'flag' => 1
            ];

            $m_monthly_report->insert($monthly_log);
            
        } else {

            $this->sendAlert('Laporan bulan ini sudah dibuat');
            return redirect()->back();
        }

        $this->sendAlert('Laporan Bulan ini berhasil dibuat');
        return redirect()->back();
    }

    private function fetchParams($m_param)
    {
        return [
            'pokok' => $m_param->where('idparameter', 1)->get()->getResult()[0]->nilai,
            'wajib' => $m_param->where('idparameter', 2)->get()->getResult()[0]->nilai,
            'bunga' => $m_param->where('idparameter', 9)->get()->getResult()[0]->nilai / 100,
            'provisi' => $m_param->where('idparameter', 5)->get()->getResult()[0]->nilai / 100,
        ];
    }

    private function handleNewUsers($startDate, $endDate)
    {
        $m_deposit = model(M_deposit::class);

        //UPDATE STATUS POKOK UNTUK SEMUA ANGGOTA BARU BULAN INI
        $m_deposit->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
            ->where('status', 'diproses')
            ->where('jenis_deposit', 'pokok')
            ->where('deskripsi', 'biaya awal registrasi')
            ->where('idanggota IN (SELECT iduser FROM tb_user WHERE flag = 1)')
            ->set('status', 'diterima')
            ->set('idadmin', $this->account->iduser)
            ->update();

        //UPDATE STATUS WAJIB UNTUK SEMUA ANGGOTA BARU BULAN INI
        $m_deposit->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
            ->where('status', 'diproses')
            ->where('jenis_deposit', 'wajib')
            ->where('deskripsi', 'biaya awal registrasi')
            ->where('idanggota IN (SELECT iduser FROM tb_user WHERE flag = 1)')
            ->set('status', 'diterima')
            ->set('idadmin', $this->account->iduser)
            ->update();
    }

    private function processPokok($idUser, $paramPokok)
    {
        $m_deposit = model(M_deposit::class);
        $queryPokok = "(deskripsi='biaya awal registrasi' OR deskripsi='saldo pokok')";

        $cekPokok = $m_deposit->where('jenis_deposit', 'pokok')
            ->where('idanggota', $idUser)
            ->where($queryPokok)
            ->countAllResults();
        
        if ($cekPokok == 0)
        {
            $data_pokok = [
                'jenis_pengajuan' => 'penyimpanan',
                'jenis_deposit' => 'pokok',
                'cash_in' => $paramPokok,
                'cash_out' => 0,
                'deskripsi' => 'biaya awal registrasi',
                'status' => 'diterima',
                'date_created' => date('Y-m-d H:i:s'),
                'idanggota' => $idUser,
                'idadmin' => $this->account->iduser
            ];

            $m_deposit->insertDeposit($data_pokok);
        }	
    }

    private function processWajib($idUser, $paramWajib, $startDate, $endDate)
    {
        $m_deposit = model(M_deposit::class);
        $cekWajib = $m_deposit->where('jenis_deposit', 'wajib')
            ->where('idanggota', $idUser)
            ->where('date_created >', $startDate)
            ->where('date_created <=', $endDate)
            ->countAllResults();

        if ($cekWajib == 0)
        {
            $dataWajib = [
                'jenis_pengajuan' => 'penyimpanan',
                'jenis_deposit' => 'wajib',
                'cash_in' => $paramWajib,
                'cash_out' => 0,
                'deskripsi' => 'Diambil dari potongan gaji bulanan',
                'status' => 'diterima',
                'date_created' => date('Y-m-d H:i:s'),
                'idanggota' => $idUser,
                'idadmin' => $this->account->iduser
            ];

            $m_deposit->insertDeposit($dataWajib);
        }
    }

    private function processManasuka($idUser)
    {
        $m_deposit = model(M_deposit::class);
        $m_user = model(M_user::class);
        $m_param_manasuka = model(M_param_manasuka::class);
    
        // Ambil parameter manasuka untuk anggota
        $param_manasuka = $m_param_manasuka->where('idanggota', $idUser)->get(1)->getRow();
    
        // Validasi parameter
        if (!$param_manasuka) {
            $user = $m_user->getUserById($idUser)[0];
            log_message('error', "Param manasuka tidak ditemukan untuk anggota: $user->username");
            return;
        }
    
        $data_manasuka = [
            'jenis_pengajuan' => 'penyimpanan',
            'jenis_deposit' => 'manasuka',
            'cash_in' => $param_manasuka->nilai,
            'cash_out' => 0,
            'deskripsi' => 'Diambil dari potongan gaji bulanan',
            'status' => 'diterima',
            'date_created' => date('Y-m-d H:i:s'),
            'idanggota' => $idUser,
            'idadmin' => $this->account->iduser
        ];
    
        $result = $m_deposit->insertDeposit($data_manasuka);

        if (!$result) {
            $user = $m_user->getUserById($idUser)[0];
            log_message('error', "Gagal menyimpan manasuka untuk anggota: $user->username");
        }
    }

    private function processPinjaman($idUser, $paramBunga, $paramProvisi, $startDate, $endDate)
    {
        $m_pinjaman = model(M_pinjaman::class);
        $m_cicilan = model(M_cicilan::class);

        $cek_pinjaman = $m_pinjaman->where('status', 4)
            ->where('idanggota', $idUser)
            ->countAllResults();

        if ($cek_pinjaman != 0) {
            
            $pinjaman = $m_pinjaman->where('status', 4)
                ->where('idanggota', $idUser)
                ->orderBy('date_updated', 'DESC')
                ->get()
                ->getResult();

            //LOOP PINJAMAN
            foreach($pinjaman as $pin){

                //CEK VALIDASI CICILAN BULAN INI
                $validasi_cicilan = $m_cicilan->where('idpinjaman', $pin->idpinjaman)
                    ->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
                    ->where('tipe_bayar', 'otomatis')
                    ->countAllResults();

                if ($validasi_cicilan == 0) {
                    
                    $dataCicilan = [
                        'idpinjaman' => $pin->idpinjaman,
                        'nominal' => ($pin->nominal/$pin->angsuran_bulanan),
                        'bunga' => ($pin->nominal*($pin->angsuran_bulanan*$paramBunga))/$pin->angsuran_bulanan,
                        'date_created' => date('Y-m-d H:i:s'),
                    ];

                    //CEK CICILAN
                    $cek_cicilan = $m_cicilan->where('idpinjaman', $pin->idpinjaman)->countAllResults();

                    if ($cek_cicilan == 0) {

                        $dataCicilan += [
                            'provisi' => ($pin->nominal*($pin->angsuran_bulanan*$paramProvisi))/$pin->angsuran_bulanan
                        ];
                        
                    }elseif ($cek_cicilan == ($pin->angsuran_bulanan - 1)) {

                        $statusPinjaman = ['status' => 5];
                        $m_pinjaman->updatePinjaman($pin->idpinjaman, $statusPinjaman);
                    }

                    $m_cicilan->insertCicilan($dataCicilan);
                }
            }
        }
    }
}
