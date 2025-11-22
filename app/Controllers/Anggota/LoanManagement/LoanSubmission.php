<?php 
namespace App\Controllers\Anggota\LoanManagement;

class LoanSubmission extends BaseLoanController
{

    public function add_proc()
    {
        $cek_cicilan_aktif = $this->m_pinjaman->countPinjamanAktifByAnggota($this->account->iduser)[0]->hitung;
        $cek_cicilan = $this->request->getPost('angsuran_bulanan');
        $satuan_waktu = $this->request->getPost('satuan_waktu');
        
        $limits = $this->getEmployeeLimits();
        $batas_bulanan = $limits['batas_bulanan'];
        $batas_nominal = $limits['batas_nominal'];
        $cek_pegawai = $limits['status_pegawai'];

        $dataset = [];
        
        // Ambil parameter batas minimal sesuai status pegawai
        // ID 10: Batas Bulan Minimal Pinjaman Kontrak
        // ID 11: Batas Bulan Minimal Pinjaman Tetap
        $param_id = ($cek_pegawai == 'tetap') ? 11 : 10;
        $param_minimal_bulan = $this->m_param
            ->where('idparameter', $param_id)
            ->get()
            ->getResult()[0]->nilai;

        $pegawai_created_date = $this->m_user
            ->where('iduser', $this->account->iduser)
            ->get()
            ->getResult()[0]->created;

        // Buat object DateTime
        $date_created = new \DateTime($pegawai_created_date);
        $date_now = new \DateTime();

        // Hitung selisih
        $diff = $date_created->diff($date_now);

        // Selisih bulan total (tahun * 12 + bulan)
        $selisih_bulan = ($diff->y * 12) + $diff->m;

        if ($selisih_bulan < $param_minimal_bulan) {
            $this->sendAlert(
                'Tidak dapat mengajukan pinjaman: Pengajuan Pinjaman baru bisa dilakukan setelah '.$param_minimal_bulan.' bulan dari tanggal bergabung',
                'danger'
            );
            return redirect()->back();
        }

        if ($cek_cicilan_aktif != 0) {
            $this->sendAlert(
                'Tidak dapat mengajukan pinjaman: masih ada pinjaman yang sedang berlangsung',
                'danger'
            );
            return redirect()->back();
        }

        if ($satuan_waktu == 2) {
            $angsuran_bulanan = $cek_cicilan * 12;
        } else {
            $angsuran_bulanan = $cek_cicilan;
        }

        $dataset = [
            'nominal' => filter_var($this->request->getPost('nominal'), FILTER_SANITIZE_NUMBER_INT),
            'tipe_permohonan' => $this->request->getPost('tipe_permohonan'),
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
                'Tidak dapat mengajukan cicilan lebih dari Rp'. number_format($dataset['nominal'], 0, ',','.'),
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

            // Get the last inserted loan ID
            $last_pinjaman_id = $this->m_pinjaman->db->insertID();

            // Calculate and insert insurance data
            $this->insertInsuranceData($last_pinjaman_id, $angsuran_bulanan);

            $this->sendAlert('Berhasil mengajukan pinjaman', 'success');
            return redirect()->back();
        } else {
            session()->setFlashdata($dataset);
            return redirect()->back();
        }
    }

    public function generate_form($idpinjaman)
    {
        $detail_pinjaman = $this->m_pinjaman->getPinjamanById($idpinjaman)[0];
        $bunga = $this->m_param->getParamById(9)[0]->nilai/100;
        $provisi = $this->m_param->getParamById(5)[0]->nilai/100;

        $detail_pinjaman->nik_peminjam = ($detail_pinjaman->nik_peminjam != null || $detail_pinjaman->nik_peminjam != '')
            ? $detail_pinjaman->nik_peminjam : '';
        $detail_pinjaman->nik_admin = ($detail_pinjaman->nik_admin != null || $detail_pinjaman->nik_admin != '')
            ? $detail_pinjaman->nik_admin : '';
        $detail_pinjaman->nik_bendahara = ($detail_pinjaman->nik_bendahara != null || $detail_pinjaman->nik_bendahara != '')
            ? $detail_pinjaman->nik_bendahara : '';

        $data = [
            'detail_pinjaman' => $detail_pinjaman,
            'bunga' => $bunga,
            'provisi' => $provisi
        ];
        
        return view('anggota/partials/form-pinjaman', $data);
    }

    public function upload_form($idpinjaman)
    {
        $file_1 = $this->request->getFile('form_bukti');
        $file_2 = $this->request->getFile('slip_gaji');
        $status_pegawai = $this->account->status_pegawai;
        $file_3 = ($status_pegawai == 'kontrak') ? $this->request->getFile('form_kontrak') : false;
        
        $confirmation3 = true;
        $data = [];
        $data_session = [];

        if ($file_1->isValid()) {
            $allowed_types = ['application/pdf'];
            if (!in_array($file_1->getMimeType(), $allowed_types)) {
                $this->sendAlert('Tipe file tidak diizinkan', 'danger');
                return redirect()->back();
            }

            //cek ukuran
            if ($file_1->getSize() > 1000000) {
                $this->sendAlert('Ukuran file tidak diizinkan', 'danger');
                return redirect()->back();
            }

            //cek ekstensi
            if ($file_1->getExtension() !== 'pdf') {
                $this->sendAlert('Ekstensi file tidak diizinkan', 'danger');
                return redirect()->back();
            }
            
            $cek_bukti = $this->m_pinjaman->getPinjamanById($idpinjaman)[0]->form_bukti;
            
            if ($cek_bukti) {
                unlink(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/' . $cek_bukti);
            }

            $newName = $file_1->getRandomName();
            $file_1->move(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/', $newName);
            $form_bukti = $file_1->getName();
            
            $alert = $this->sendAlert('Form Persetujuan berhasil diunggah', 'success', false);
            $confirmation = true;
        } else {
            $alert = $this->sendAlert('Form Persetujuan gagal diunggah', 'danger', false);
            $confirmation = false;
        }

        if ($file_2->isValid()) {
            //cek tipe
            $allowed_types = ['application/pdf'];
            if (!in_array($file_2->getMimeType(), $allowed_types)) {
                $this->sendAlert('Tipe file tidak diizinkan', 'danger');
                return redirect()->back();
            }

            //cek ukuran
            if ($file_2->getSize() > 1000000) {
                $this->sendAlert('Ukuran file tidak diizinkan', 'danger');
                return redirect()->back();
            }

            //cek ekstensi
            if ($file_2->getExtension() !== 'pdf') {
                $this->sendAlert('Ekstensi file tidak diizinkan', 'danger');
                return redirect()->back();
            }

            $cek_gaji = $this->m_pinjaman->getPinjamanById($idpinjaman)[0]->slip_gaji;
            
            if ($cek_gaji) {
                unlink(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/' . $cek_gaji);
            }

            $newName = $file_2->getRandomName();
            $file_2->move(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/', $newName);
            $slip_gaji = $file_2->getName();
            
            $alert2 = $this->sendAlert('Slip gaji berhasil diunggah', 'success', false);
            $confirmation2 = true;
        } else {
            $alert2 = $this->sendAlert('Slip gaji gagal diunggah', 'danger', false);
            $confirmation2 = false;
        }
        
        if ($file_3){
            if ($file_3->isValid()) {	
                //cek tipe
                $allowed_types = ['application/pdf'];
                if (!in_array($file_3->getMimeType(), $allowed_types)) {
                    $this->sendAlert('Tipe file tidak diizinkan', 'danger');
                    return redirect()->back();
                }

                //cek ukuran
                if ($file_3->getSize() > 1000000) {
                    $this->sendAlert('Ukuran file tidak diizinkan', 'danger');
                    return redirect()->back();
                }

                //cek ekstensi
                if ($file_3->getExtension() !== 'pdf') {
                    $this->sendAlert('Ekstensi file tidak diizinkan', 'danger');
                    return redirect()->back();
                }

                $cek_kontrak = $this->m_pinjaman->getPinjamanById($idpinjaman)[0]->form_kontrak;
                
                if ($cek_kontrak) {
                    unlink(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/' . $cek_kontrak);
                }
    
                $newName = $file_3->getRandomName();
                $file_3->move(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/', $newName);                
                $form_kontrak = $file_3->getName();
                $data += ['form_kontrak' => $form_kontrak];

                $alert3 = $this->sendAlert('Bukti kontrak berhasil diunggah', 'success', false);
                $data_session += ['notif_kontrak' => $alert3];
                $confirmation3 = true;
            } else {
                $alert3 = $this->sendAlert('Bukti kontrak gagal diunggah', 'danger', false);

                $data_session += ['notif_kontrak' => $alert3];
                $confirmation3 = false;
            }	
        }

        if (!$confirmation || !$confirmation2 || !$confirmation3) {
            $data_session += [
                'notif' => $alert,
                'notif_gaji' => $alert2
            ];
        } else {
            $date_updated = date('Y-m-d H:i:s');

            $data += [
                'form_bukti' => $form_bukti,
                'slip_gaji' => $slip_gaji,
                'status' => 2,
                'date_updated' => $date_updated
            ];

            $this->m_pinjaman->updatePinjaman($idpinjaman, $data);

            $this->createNotification(
                $idpinjaman,
                'Pengajuan pinjaman dari anggota '. $this->account->nama_lengkap,
                1
            );
            
            $data_session += [
                'notif' => $alert,
                'notif_gaji' => $alert2
            ];
        }
        
        session()->setFlashdata($data_session);
        return redirect()->back();
    }
}
