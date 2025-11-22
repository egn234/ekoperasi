<?php 
namespace App\Controllers\Anggota\LoanManagement;

class LoanTopUp extends BaseLoanController
{
    public function top_up_proc($idpinjaman = false)
    {
        // Validate loan eligibility for top up
        $loan_detail = $this->m_pinjaman->getPinjamanById($idpinjaman)[0];
        
        // Check if loan is completed
        if ($loan_detail->status == 5) {
            $this->sendAlert('Tidak dapat melakukan top up. Pinjaman sudah lunas.', 'warning');
            return redirect()->back();
        }
        
        // Check if loan is active
        if ($loan_detail->status != 4) {
            $this->sendAlert('Tidak dapat melakukan top up. Pinjaman belum aktif atau sudah selesai.', 'warning');
            return redirect()->back();
        }
        
        // Calculate remaining installments
        $count_cicilan = $this->m_cicilan->select('COUNT(idcicilan) as hitung')
            ->where('idpinjaman', $idpinjaman)
            ->get()
            ->getResult()[0]
            ->hitung;
        
        $sisa_cicilan = $loan_detail->angsuran_bulanan - $count_cicilan;
        
        // Check if remaining installments are more than 2 (top up only allowed when ≤ 2)
        if ($sisa_cicilan > 2) {
            $this->sendAlert(
                'Top up hanya dapat dilakukan ketika sisa cicilan kurang dari atau sama dengan 2 bulan. Sisa cicilan saat ini: ' . $sisa_cicilan . ' bulan.',
                'warning'
            );
            return redirect()->back();
        }
        
        $cek_cicilan = $this->request->getPost('angsuran_bulanan');
        $satuan_waktu = $this->request->getPost('satuan_waktu');
        
        $limits = $this->getEmployeeLimits();
        $batas_bulanan = $limits['batas_bulanan'];
        $batas_nominal = $limits['batas_nominal'];

        if ($satuan_waktu == 2) {
            $angsuran_bulanan = $cek_cicilan * 12;
        } else {
            $angsuran_bulanan = $cek_cicilan;
        }

        $nominal = filter_var($this->request->getPost('nominal'), FILTER_SANITIZE_NUMBER_INT);
        $saldo_pinjaman = $this->request->getPost('sisa_cicilan');

        $dataset = [
            'nominal' => $nominal,
            'tipe_permohonan' => $this->request->getPost('tipe_permohonan'),
            'potongan_topup' => $saldo_pinjaman,
            'deskripsi' => $this->request->getPost('deskripsi'),
            'angsuran_bulanan' => $angsuran_bulanan
        ];

        if ($angsuran_bulanan > $batas_bulanan) {
            $this->sendAlert(
                'Tidak dapat mengajukan cicilan lebih dari '. $angsuran_bulanan .' bulan',
                'warning'
            );
            $confirmation = false;
        } else {
            $confirmation = true;
        }

        if ($dataset['nominal'] > $batas_nominal) {
            $this->sendAlert(
                'Tidak dapat mengajukan cicilan lebih dari Rp '. number_format($dataset['nominal'], 0, ',','.'),
                'warning'
            );
            $confirmation = false;
        } else {
            $confirmation = true;
        }
        
        if ($confirmation) {
            if ($dataset['tipe_permohonan'] == "") {
                $this->sendAlert(
                    'Pilih Tipe Permohonan Terlebih Dahulu',
                    'warning'
                );
                return redirect()->back();
            }

            $dataset += [
                'date_created' => date('Y-m-d H:i:s'),
                'status' => 1,
                'idanggota' => $this->account->iduser
            ];

            $this->m_pinjaman->insertPinjaman($dataset);

            // Get the last inserted loan ID for top up
            $last_topup_id = $this->m_pinjaman->db->insertID();

            // Calculate and insert insurance for top up
            $this->insertInsuranceData($last_topup_id, $angsuran_bulanan);

            $dataset_pinjaman = [
                'status' => 5,
                'date_updated' => date('Y-m-d H:i:s')
            ];

            $this->m_pinjaman->updatePinjaman($idpinjaman, $dataset_pinjaman);

            $this->sendAlert('Berhasil mengajukan top up', 'success');
            return redirect()->to('anggota/pinjaman/list');
        } else {
            session()->setFlashdata($dataset);
            return redirect()->back();
        }
    }

    public function top_up()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $user = $this->m_pinjaman->getPinjamanById($id)[0];
            
            // Check if loan is completed
            if ($user->status == 5) {
                echo view('anggota/pinjaman/part-cicil-mod-topup-error', [
                    'error_message' => 'Tidak dapat melakukan top up. Pinjaman sudah lunas.',
                    'error_type' => 'completed'
                ]);
                return;
            }
            
            // Check if loan is active
            if ($user->status != 4) {
                echo view('anggota/pinjaman/part-cicil-mod-topup-error', [
                    'error_message' => 'Tidak dapat melakukan top up. Pinjaman belum aktif atau sudah selesai.',
                    'error_type' => 'inactive'
                ]);
                return;
            }
            
            // Calculate remaining installments
            $count_cicilan = $this->m_cicilan->select('COUNT(idcicilan) as hitung')
                ->where('idpinjaman', $id)
                ->get()
                ->getResult()[0]
                ->hitung;
            
            $sisa_cicilan = $user->angsuran_bulanan - $count_cicilan;
            
            // Check if remaining installments are more than 2 (top up only allowed when ≤ 2)
            if ($sisa_cicilan > 2) {
                echo view('anggota/pinjaman/part-cicil-mod-topup-error', [
                    'error_message' => 'Top up hanya dapat dilakukan ketika sisa cicilan kurang dari atau sama dengan 2 bulan. Sisa cicilan saat ini: ' . $sisa_cicilan . ' bulan.',
                    'error_type' => 'too_many_remaining',
                    'remaining_installments' => $sisa_cicilan
                ]);
                return;
            }
            
            $penalty = $this->m_param->getParamById(6)[0]->nilai;
            $provisi = $this->m_param->getParamById(5)[0]; // Provisi
            $bulan_kelipatan_asuransi = $this->m_param->getParamById(11)[0]; // Bulan kelipatan asuransi
            $nominal_asuransi = $this->m_param->getParamById(12)[0]; // Nominal asuransi
            
            $sum_cicilan = $this->m_cicilan->select("SUM(nominal) as saldo")
                ->where('idpinjaman', $id)
                ->get()
                ->getResult()[0]
                ->saldo;
            $sisa_pinjaman = $user->nominal - $sum_cicilan;
            $data = [
                'a' => $user,
                'sisa' => $sisa_pinjaman,
                'duser' => $this->account,
                'penalty' => $penalty,
                'sisa_cicilan' => $sisa_cicilan,
                'provisi' => $provisi,
                'bulan_kelipatan_asuransi' => $bulan_kelipatan_asuransi,
                'nominal_asuransi' => $nominal_asuransi
            ];
            echo view('anggota/pinjaman/part-cicil-mod-topup', $data);
        }
    }
}
