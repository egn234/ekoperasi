<?php 
namespace App\Controllers\Anggota\LoanManagement;

class LoanPayment extends BaseLoanController
{
    public function lunasi_proc($idpinjaman)
    {
        $file_1 = $this->request->getFile('bukti_bayar');

        if ($file_1->isValid() && !$file_1->hasMoved()) {
            //cek tipe
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

            $cek_bukti = $this->m_pinjaman->getPinjamanById($idpinjaman)[0]->bukti_tf;
            
            if ($cek_bukti && file_exists(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/' . $cek_bukti)) {
                unlink(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/' . $cek_bukti);
            }

            $newName = $file_1->getRandomName();
            $file_1->move(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/', $newName);
            $bukti_tf = $file_1->getName();

            $dataset = [
                'bukti_tf' => $bukti_tf,
                'status' => 6
            ];

            $this->m_pinjaman->updatePinjaman($idpinjaman, $dataset);

            $this->createNotification(
                $idpinjaman,
                'Pengajuan pelunasan pinjaman dari anggota '. $this->account->nama_lengkap,
                1
            );

            $this->sendAlert('Berhasil mengajukan pelunasan pinjaman', 'success');
            return redirect()->back();
        } else {
            $this->sendAlert('Form Persetujuan gagal diunggah: format file tidak sesuai', 'danger');
            return redirect()->back();
        }
    }

    public function lunasi_pinjaman()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $user = $this->m_pinjaman->getPinjamanById($id)[0];
            $data = [
                'a' => $user,
                'duser' => $this->account
            ];
            echo view('anggota/pinjaman/part-pinj-mod-lunasin', $data);
        }
    }
}
