<?php 
namespace App\Controllers\Admin\UserManagement;

require_once ROOTPATH.'vendor/autoload.php';

class UserImport extends BaseUserController
{
    public function get_table_upload()
    {
        $table_file = request()->getFile('file_import');
        
        if ($table_file->isValid()) 
        {
            $ext = $table_file->guessExtension();
            $filepath = WRITEPATH.'uploads/'.$table_file->store();
            
            if ($ext == 'csv') {
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            }
            elseif($ext == 'xlsx' || $ext == 'xls'){
                $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            }

            $reader->setReadDataOnly(true);
            $reader->setReadEmptyCells(false);
            $spreadsheet = $reader->load($filepath);
            $err_count = 0;
            $baris_proc = 0;

            foreach ($spreadsheet->getWorksheetIterator() as $cell)
            {
                $baris = $cell->getHighestRow();
                $kolom = $cell->getHighestColumn();

                for ($i=2; $i <= $baris; $i++)
                { 
                    $cek_username = ($this->m_user->getUsernameGiat())?$this->m_user->getUsernameGiat()[0]->username:'GIAT0000';

                    $filter_int = (int) filter_var($cek_username, FILTER_SANITIZE_NUMBER_INT);
                    $clean_int = intval($filter_int);

                    if ($clean_int >= 999) {
                        $username = 'GIAT'.($clean_int+1);
                    }elseif ($clean_int >= 99) {
                        $username = 'GIAT0'.($clean_int+1);
                    }elseif ($clean_int >= 9) {
                        $username = 'GIAT00'.($clean_int+1);
                    }elseif ($clean_int >= 0) {
                        $username = 'GIAT000'.($clean_int+1);
                    }

                    $nik = $cell->getCell('C'.$i)->getValue();
                    $alamat = $cell->getCell('G'.$i)->getValue();
                    $pass = md5($cell->getCell('B'.$i)->getValue());
                    $no_rek = $cell->getCell('N'.$i)->getValue();

                    $dataset = [
                        'username' => $username,
                        'pass' => password_hash($pass, PASSWORD_DEFAULT),
                        'nik' => $nik,
                        'nama_lengkap' => strtoupper($cell->getCell('D'.$i)->getValue()),
                        'tempat_lahir' => $cell->getCell('E'.$i)->getValue(),
                        'tanggal_lahir' => date('Y-m-d', strtotime($cell->getCell('F'.$i)->getValue())),
                        'alamat' => $alamat,
                        'instansi' => $cell->getCell('H'.$i)->getValue(),
                        'unit_kerja' => $cell->getCell('I'.$i)->getValue(),
                        'status_pegawai' => $cell->getCell('J'.$i)->getValue(),
                        'nomor_telepon' => $cell->getCell('K'.$i)->getValue(),
                        'email' => $cell->getCell('L'.$i)->getValue(),
                        'nama_bank' => strtoupper($cell->getCell('M'.$i)->getValue()),
                        'no_rek' => $no_rek,
                    ];

                    $saldo = [
                        'saldo_pokok' => $cell->getCell('O'.$i)->getValue(),
                        'saldo_wajib' => $cell->getCell('P'.$i)->getValue(),
                        'saldo_manasuka' => $cell->getCell('Q'.$i)->getValue()
                    ];

                    $cek_username = $this->m_user->countUsername($dataset['username'])[0]->hitung;
                    if ($cek_username == 0)
                    {
                        $cek_nik = $this->m_user->countNIK($dataset['nik'])[0]->hitung;
                        if ($cek_nik == 0)
                        {
                            $dataset += [
                                'profil_pic' => 'image.jpg',
                                'created' => date('Y-m-d H:i:s'),
                                'closebook_param_count' => 0,
                                'flag' => 1,
                                'idgroup' => 4
                            ];

                            $this->m_user->insertUser($dataset);

                            $iduser_new = $this->m_user->getUser($dataset['username'])[0]->iduser;
                            
                            helper('filesystem');
                            $imgSource = FCPATH . 'assets/images/users/image.jpg';

                            mkdir(FCPATH . 'uploads/user/'.$dataset['username'], 0777);
                            mkdir(FCPATH . 'uploads/user/'.$dataset['username'].'/profil_pic', 0777);
                            
                            $imgDest = FCPATH . 'uploads/user/'.$dataset['username'].'/profil_pic/image.jpg';
                            copy($imgSource, $imgDest);

                            if ($saldo['saldo_pokok'] != null || $saldo['saldo_pokok'] != 0) {
                                
                                $saldo_pokok = [
                                    'jenis_pengajuan' => 'penyimpanan',
                                    'jenis_deposit' => 'pokok',
                                    'cash_in' => $saldo['saldo_pokok'],
                                    'cash_out' => 0,
                                    'deskripsi' => 'saldo pokok',
                                'status' => 'diterima',
                                    'date_created' => date('Y-m-d H:i:s'),
                                    'idanggota' => $iduser_new
                                ];

                                $this->m_deposit->insertDeposit($saldo_pokok);
                            }else{

                                $init_aktivasi = $this->m_param->getParamById(1)[0]->nilai;
                                $saldo_pokok = [
                                    'jenis_pengajuan' => 'penyimpanan',
                                    'jenis_deposit' => 'pokok',
                                    'cash_in' => $init_aktivasi,
                                    'cash_out' => 0,
                                    'deskripsi' => 'biaya awal registrasi',
                                'status' => 'diproses',
                                    'date_created' => date('Y-m-d H:i:s'),
                                    'idanggota' => $iduser_new
                                ];

                                $this->m_deposit->insertDeposit($saldo_pokok);
                            }

                            if ($saldo['saldo_wajib'] != null || $saldo['saldo_wajib'] != 0) {

                                $saldo_wajib = [
                                    'jenis_pengajuan' => 'penyimpanan',
                                    'jenis_deposit' => 'wajib',
                                    'cash_in' => $saldo['saldo_wajib'],
                                    'cash_out' => 0,
                                    'deskripsi' => 'saldo wajib',
                                'status' => 'diterima',
                                    'date_created' => date('Y-m-d H:i:s'),
                                    'idanggota' => $iduser_new
                                ];

                                $this->m_deposit->insertDeposit($saldo_wajib);
                            }else{
                                
                                $init_aktivasi = $this->m_param->getParamById(2)[0]->nilai;
                                $saldo_wajib = [
                                    'jenis_pengajuan' => 'penyimpanan',
                                    'jenis_deposit' => 'wajib',
                                    'cash_in' => $init_aktivasi,
                                    'cash_out' => 0,
                                    'deskripsi' => 'biaya awal registrasi',
                                'status' => 'diproses',
                                    'date_created' => date('Y-m-d H:i:s'),
                                    'idanggota' => $iduser_new
                                ];

                                $this->m_deposit->insertDeposit($saldo_wajib);
                            }

                            if ($saldo['saldo_manasuka'] != null || $saldo['saldo_manasuka'] != 0) {

                                $saldo_manasuka = [
                                    'jenis_pengajuan' => 'penyimpanan',
                                    'jenis_deposit' => 'manasuka',
                                    'cash_in' => $saldo['saldo_manasuka'],
                                    'cash_out' => 0,
                                    'deskripsi' => 'saldo manasuka',
                                'status' => 'diterima',
                                    'date_created' => date('Y-m-d H:i:s'),
                                    'idanggota' => $iduser_new
                                ];

                                $this->m_deposit->insertDeposit($saldo_manasuka);
                            }

                            $param_r = [
                                'idanggota' => $iduser_new,
                                'created' => date('Y-m-d H:i:s')
                            ];

                            $param_mnsk = $cell->getCell('U'.$i)->getValue();

                            if ($param_mnsk != "" || $param_mnsk != null) {
                                $param_r += ['nilai' => $param_mnsk];
                            }else{
                                $param_r += ['nilai' => $this->m_param->getParamById(3)[0]->nilai];
                            }

                            $this->m_param_manasuka->insertParamManasuka($param_r);

                            $pinjaman = [
                                'nominal' => (int) $cell->getCell('R'.$i)->getValue(),
                                'angsuran_bulanan' => (int) $cell->getCell('S'.$i)->getValue(),
                            ];

                            $cicilan_ke = (int) $cell->getCell('T'.$i)->getValue();

                            if($pinjaman['nominal'] != 0 || $pinjaman['angsuran_bulanan'] != 0 || $cicilan_ke != 0){
                                
                                $tanggal_report = $this->m_param->where('idparameter', 8)->get()->getResult()[0]->nilai;
                                $today = new \DateTime();
                                $monthInterval = new \DateInterval('P'.$cicilan_ke.'M'); // P25M represents a period of 25 months
                                $monthAgo = $today->sub($monthInterval);
                                $year = $monthAgo->format('Y'); // Get the year value
                                $month = $monthAgo->format('m');
                                $date_pinjaman = sprintf('%d-%02d-%02d 00:00:00', $year, $month, $tanggal_report);
                                
                                $pinjaman += [
                                    'tipe_permohonan' => 'pinjaman',
                                    'deskripsi' => 'impor otomatis sistem',
                                'status' => 4,
                                    'date_created' => $date_pinjaman,
                                    'date_updated' => $date_pinjaman,
                                    'idbendahara' => $this->m_user->where('idgroup', 2)->get()->getResult()[0]->iduser,
                                    'idketua' => $this->m_user->where('idgroup', 3)->get()->getResult()[0]->iduser,
                                    'idanggota' => $iduser_new,
                                    'idadmin' => $this->account->iduser,
                                ];

                                $this->m_pinjaman->insertPinjaman($pinjaman);
                                $idpinjaman = $this->m_pinjaman->insertID();

                                $nominal_cicilan = $pinjaman['nominal'] / $pinjaman['angsuran_bulanan'];

                                $bunga = $this->m_param->where('idparameter', 9)->get()->getResult()[0]->nilai/100;
                                $provisi = $this->m_param->where('idparameter', 5)->get()->getResult()[0]->nilai/100;

                                for ($k = 0; $k < $cicilan_ke; ++$k) { 
                                    $formattedDate = sprintf('%d-%02d-%02d 00:00:00', $year, $month, $tanggal_report);

                                    $cek_cicilan = $this->m_cicilan->where('idpinjaman', $idpinjaman)
                                        ->countAllResults();
                                    
                                    if ($cek_cicilan == 0) {

                                        $dataset_cicilan = [
                                            'nominal' => $pinjaman['nominal'],
                                            'bunga' => ($pinjaman['nominal']*($pinjaman['angsuran_bulanan']*$bunga))/$pinjaman['angsuran_bulanan'],
                                            'provisi' => ($pinjaman['nominal']*($pinjaman['angsuran_bulanan']*$provisi))/$pinjaman['angsuran_bulanan'],
                                            'date_created' => $formattedDate,
                                            'idpinjaman' => $idpinjaman
                                        ];

                                        $this->m_cicilan->insertCicilan($dataset_cicilan);
     
                                    }elseif ($cek_cicilan == ($pinjaman['angsuran_bulanan'] - 1)) {

                                        $dataset_cicilan = [
                                            'nominal' => ($pinjaman['nominal']/$pinjaman['angsuran_bulanan']),
                                            'bunga' => ($pinjaman['nominal']*($pinjaman['angsuran_bulanan']*$bunga))/$pinjaman['angsuran_bulanan'],
                                            'date_created' => $formattedDate,
                                            'idpinjaman' => $idpinjaman
                                        ];

                                        $this->m_cicilan->insertCicilan($dataset_cicilan);

                                        $status_pinjaman = ['status' => 5];
                                        $this->m_pinjaman->updatePinjaman($idpinjaman, $status_pinjaman);

                                    }elseif ($cek_cicilan != 0 && $cek_cicilan < $pinjaman['angsuran_bulanan']) {

                                        $dataset_cicilan = [
                                            'nominal' => $pinjaman['nominal'],
                                            'bunga' => ($pinjaman['nominal']*($pinjaman['angsuran_bulanan']*$bunga))/$pinjaman['angsuran_bulanan'],
                                            'date_created' => $formattedDate,
                                            'idpinjaman' => $idpinjaman
                                        ];

                                        $this->m_cicilan->insertCicilan($dataset_cicilan);
                                    }

                                    // Decrement the month (and year if necessary) for the next iteration
                                    if ($month == 12) {
                                        $year++;
                                        $month = 1;
                                    } else {
                                        $month++;
                                    }
                                }
                            }
                        }
                        else
                        {
                            $err_count++;
                        }
                    }
                    else
                    {
                        $err_count++;
                    }
                    $baris_proc++;
                }
            }

            $total_count = $baris_proc - $err_count;

            if ($err_count > 0 && $total_count != 0) {
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Berhasil mengimpor beberapa data user ('.$total_count.' berhasil, '.$err_count.' gagal)',
                    'status' => 'warning'
                    ]
                );
                
                $data_session = [
                    'notif' => $alert
                ];
            }
            elseif ($err_count == $baris_proc) {
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Gagal mengimpor data user ('.($total_count).' berhasil, '.$err_count.' gagal)',
                        'status' => 'danger'
                    ]
                );
                
                $data_session = [
                    'notif' => $alert
                ];	
            }
            elseif ($err_count == 0) {
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Berhasil mengimpor data user ('.$total_count.' berhasil, '.$err_count.' gagal)',
                    'status' => 'success'
                    ]
                );
                
                $data_session = [
                    'notif' => $alert
                ];
            }
                
            unlink($filepath);
            session()->setFlashdata($data_session);
            return redirect()->to('admin/user/list');
        }
        else
        {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Upload gagal',
                    'status' => 'danger'
                ]
            );
            
            $dataset = ['notif' => $alert];
            session()->setFlashdata($dataset);
            return redirect()->to('admin/user/list');
        }
    }
}
