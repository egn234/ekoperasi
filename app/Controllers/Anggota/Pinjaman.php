<?php 
namespace App\Controllers\Anggota;

use App\Controllers\BaseController;
use App\Controllers\Anggota\Notifications;

use App\Models\M_user;
use App\Models\M_pinjaman;
use App\Models\M_cicilan;
use App\Models\M_cicilan_pag;
use App\Models\M_param;
use App\Models\M_notification;
use App\Models\M_asuransi;

class Pinjaman extends BaseController
{
    protected $m_user;
    protected $account;
    protected $m_pinjaman;
    protected $m_cicilan;
    protected $m_cicilan_pag;
    protected $m_param;
    protected $m_notification;
    protected $m_asuransi;
    protected $notification;

    function __construct()
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

    public function index()
    {
        $list_pinjaman = $this->m_pinjaman->getPinjamanByIdAnggota($this->account->iduser);

        $data = [
            'title_meta' => view('anggota/partials/title-meta', ['title' => 'Pinjaman']),
            'page_title' => view('anggota/partials/page-title', ['title' => 'Pinjaman', 'li_1' => 'EKoperasi', 'li_2' => 'Pinjaman']),
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account,
            'list_pinjaman' => $list_pinjaman
        ];
        
        return view('anggota/pinjaman/list-pinjaman', $data);
    }

    function detail($idpinjaman = false)
    {
        $detail_pinjaman = $this->m_pinjaman->getPinjamanById($idpinjaman)[0];
        $tagihan_lunas = $this->m_cicilan->getSaldoTerbayarByIdPinjaman($idpinjaman)[0];
        $asuransi_data = $this->m_asuransi->getAsuransiByIdPinjaman($idpinjaman);
        $currentpage = $this->request->getVar('page_grup1') ? $this->request->getVar('page_grup1') : 1;

        // Get total count of paid installments (not paginated)
        $total_paid_installments = $this->m_cicilan->select('COUNT(idcicilan) as total_count')
            ->where('idpinjaman', $idpinjaman)
            ->get()
            ->getResult()[0]
            ->total_count;

        $list_cicilan2 =  $this->m_cicilan_pag
            ->select('
                (
                    SELECT SUM(nominal)
                    FROM tb_cicilan b WHERE b.date_created <= tb_cicilan.date_created
                    AND idpinjaman = tb_cicilan.idpinjaman
                ) AS saldo,
                DATE_FORMAT(date_created, "%Y-%m-%d") as date,
                (
                    SELECT COUNT(idcicilan)
                    FROM tb_cicilan c WHERE c.date_created <= tb_cicilan.date_created
                    AND idpinjaman = tb_cicilan.idpinjaman
                ) AS counter,
                tb_cicilan.*,
                SUM(tb_cicilan.nominal) as total_saldo'
            )
            ->where('idpinjaman', $idpinjaman)
            ->orderBy('date_created', 'DESC')
            ->groupBy('date')
            ->paginate(10, 'grup1');

        $data = [
            'title_meta' => view('anggota/partials/title-meta', ['title' => 'Pinjaman']),
            'page_title' => view('anggota/partials/page-title', ['title' => 'Pinjaman', 'li_1' => 'EKoperasi', 'li_2' => 'Pinjaman']),
            'notification_list' => $this->notification->index()['notification_list'],
            'notification_badges' => $this->notification->index()['notification_badges'],
            'duser' => $this->account,
            'list_cicilan2' => $list_cicilan2,
            'pager' => $this->m_cicilan_pag->pager,
            'currentpage' => $currentpage,
            'detail_pinjaman' => $detail_pinjaman,
            'tagihan_lunas' => $tagihan_lunas,
            'asuransi_data' => $asuransi_data,
            'total_paid_installments' => $total_paid_installments
        ];
        
        return view('anggota/pinjaman/list-cicilan', $data);	
    }

    public function add_proc()
    {
        $cek_cicilan_aktif = $this->m_pinjaman->countPinjamanAktifByAnggota($this->account->iduser)[0]->hitung;
        $cek_cicilan = $this->request->getPost('angsuran_bulanan');
        $satuan_waktu = $this->request->getPost('satuan_waktu');
        
        $cek_pegawai = $this->m_user->where('iduser', $this->account->iduser)
            ->get()
            ->getResult()[0]
            ->status_pegawai;

        if($cek_pegawai == 'tetap'){
            $batas_bulanan = 24;
            $batas_nominal = 50000000;
        } else {
            $batas_bulanan = 12;
            $batas_nominal = 15000000;
        }

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
            $alert = view(
                'partials/notification-alert',
                [
                    'notif_text' => 'Tidak dapat mengajukan pinjaman: Pengajuan Pinjaman baru bisa dilakukan setelah '.$param_minimal_bulan.' bulan dari tanggal bergabung',
                    'status' => 'danger'
                ]
            );

            $dataset += ['notif' => $alert];
            session()->setFlashdata($dataset);
            return redirect()->back();
        }

        if ($cek_cicilan_aktif != 0) {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Tidak dapat mengajukan pinjaman: masih ada pinjaman yang sedang berlangsung',
                    'status' => 'danger'
                ]
            );
            
            $dataset += ['notif' => $alert];
            session()->setFlashdata($dataset);
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
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Tidak dapat mengajukan cicilan lebih dari '. $angsuran_bulanan .' bulan',
                    'status' => 'warning'
                ]
            );
            
            $dataset += ['notif_bulanan' => $alert];
            $confirmation = false;
        } else {
            $confirmation = true;
        }

        if ($dataset['nominal'] > $batas_nominal) {
            
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Tidak dapat mengajukan cicilan lebih dari Rp'. number_format($dataset['nominal'], 0, ',','.'),
                    'status' => 'warning'
                ]
            );
            
            $dataset += ['notif' => $alert];
            $confirmation = false;
        } else {
            $confirmation = true;
        }
        
        if ($confirmation) {

            if ($dataset['tipe_permohonan'] == "") {
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Pilih Tipe Permohonan Terlebih Dahulu',
                        'status' => 'warning'
                    ]
                );
                
                $dataset += ['notif' => $alert];
                session()->setFlashdata($dataset);
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

            // Calculate and insert insurance
            // ID 12: Bulan Kelipatan Asuransi
            // ID 13: Nominal Asuransi
            $bulan_kelipatan = (int)$this->m_param->getParamById(12)[0]->nilai;
            $nominal_asuransi = (float)$this->m_param->getParamById(13)[0]->nilai;

            log_message('info', 'Insurance calculation - Angsuran: ' . $angsuran_bulanan . ', Kelipatan: ' . $bulan_kelipatan . ', Nominal: ' . $nominal_asuransi);

            $asuransi_data = $this->m_asuransi->calculateAsuransi($angsuran_bulanan, $nominal_asuransi, $bulan_kelipatan);

            log_message('info', 'Insurance data to insert: ' . json_encode($asuransi_data));

            foreach ($asuransi_data as $asuransi) {
                $asuransi_insert_data = [
                    'idpinjaman' => $last_pinjaman_id,
                    'bulan_kumulatif' => $asuransi['bulan_kumulatif'],
                    'nilai_asuransi' => $asuransi['nilai_asuransi'],
                    'status' => 'aktif'
                ];
                
                log_message('info', 'Inserting asuransi: ' . json_encode($asuransi_insert_data));
                $this->m_asuransi->insertAsuransi($asuransi_insert_data);
            }

            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Berhasil mengajukan pinjaman',
                    'status' => 'success'
                ]
            );
            
            $dataset += ['notif' => $alert];
            session()->setFlashdata($dataset);
            return redirect()->back();
        } else {
            session()->setFlashdata($dataset);
            return redirect()->back();
        }
    }

    public function top_up_proc($idpinjaman = false)
    {
        // Validate loan eligibility for top up
        $loan_detail = $this->m_pinjaman->getPinjamanById($idpinjaman)[0];
        
        // Check if loan is completed
        if ($loan_detail->status == 5) {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Tidak dapat melakukan top up. Pinjaman sudah lunas.',
                    'status' => 'warning'
                ]
            );
            
            $dataset = ['notif' => $alert];
            session()->setFlashdata($dataset);
            return redirect()->back();
        }
        
        // Check if loan is active
        if ($loan_detail->status != 4) {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Tidak dapat melakukan top up. Pinjaman belum aktif atau sudah selesai.',
                    'status' => 'warning'
                ]
            );
            
            $dataset = ['notif' => $alert];
            session()->setFlashdata($dataset);
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
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Top up hanya dapat dilakukan ketika sisa cicilan kurang dari atau sama dengan 2 bulan. Sisa cicilan saat ini: ' . $sisa_cicilan . ' bulan.',
                    'status' => 'warning'
                ]
            );
            
            $dataset = ['notif' => $alert];
            session()->setFlashdata($dataset);
            return redirect()->back();
        }
        
        $cek_cicilan = $this->request->getPost('angsuran_bulanan');
        $satuan_waktu = $this->request->getPost('satuan_waktu');
        $cek_pegawai = $this->m_user->where('iduser', $this->account->iduser)
            ->get()
            ->getResult()[0]
            ->status_pegawai;

        if($cek_pegawai == 'tetap'){
            $batas_bulanan = 24;
            $batas_nominal = 50000000;
        } else {
            $batas_bulanan = 12;
            $batas_nominal = 15000000;
        }

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
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Tidak dapat mengajukan cicilan lebih dari '. $angsuran_bulanan .' bulan',
                    'status' => 'warning'
                ]
            );
            
            $dataset += ['notif_bulanan' => $alert];
            $confirmation = false;
        } else {
            $confirmation = true;
        }

        if ($dataset['nominal'] > $batas_nominal) {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Tidak dapat mengajukan cicilan lebih dari Rp '. number_format($dataset['nominal'], 0, ',','.'),
                    'status' => 'warning'
                ]
            );
            
            $dataset += ['notif' => $alert];
            $confirmation = false;
        } else {
            $confirmation = true;
        }
        
        if ($confirmation) {
            if ($dataset['tipe_permohonan'] == "") {
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Pilih Tipe Permohonan Terlebih Dahulu',
                        'status' => 'warning'
                    ]
                );
                
                $dataset += ['notif' => $alert];
                session()->setFlashdata($dataset);
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
            $bulan_kelipatan = (int)$this->m_param->getParamById(11)[0]->nilai;
            $nominal_asuransi = (float)$this->m_param->getParamById(12)[0]->nilai;

            $asuransi_data = $this->m_asuransi->calculateAsuransi($angsuran_bulanan, $nominal_asuransi, $bulan_kelipatan);

            foreach ($asuransi_data as $asuransi) {
                $asuransi_insert_data = [
                    'idpinjaman' => $last_topup_id,
                    'bulan_kumulatif' => $asuransi['bulan_kumulatif'],
                    'nilai_asuransi' => $asuransi['nilai_asuransi'],
                    'status' => 'aktif'
                ];
                $this->m_asuransi->insertAsuransi($asuransi_insert_data);
            }

            $dataset_pinjaman = [
                'status' => 5,
                'date_updated' => date('Y-m-d H:i:s')
            ];

            $this->m_pinjaman->updatePinjaman($idpinjaman, $dataset_pinjaman);

            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Berhasil mengajukan top up',
                    'status' => 'success'
                ]
            );
            
            $dataset += ['notif' => $alert];
            session()->setFlashdata($dataset);
            return redirect()->to('anggota/pinjaman/list');
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
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Tipe file tidak diizinkan', 
                        'status' => 'danger'
                    ]
                );
                
                $data_session = ['notif' => $alert];
                session()->setFlashdata($data_session);
                return redirect()->back();
            }

            //cek ukuran
            if ($file_1->getSize() > 1000000) {
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Ukuran file tidak diizinkan', 
                        'status' => 'danger'
                    ]
                );
                
                $data_session = ['notif' => $alert];
                session()->setFlashdata($data_session);
                return redirect()->back();
            }

            //cek ekstensi
            if ($file_1->getExtension() !== 'pdf') {
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Ekstensi file tidak diizinkan', 
                        'status' => 'danger'
                    ]
                );

                $data_session = ['notif' => $alert];
                session()->setFlashdata($data_session);
                return redirect()->back();
            }
            
            $cek_bukti = $this->m_pinjaman->getPinjamanById($idpinjaman)[0]->form_bukti;
            
            if ($cek_bukti) {
                unlink(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/' . $cek_bukti);
            }

            $newName = $file_1->getRandomName();
            $file_1->move(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/', $newName);
            $form_bukti = $file_1->getName();
            
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Form Persetujuan berhasil diunggah',
                     'status' => 'success'
                ]
            );

            $confirmation = true;
        } else {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Form Persetujuan gagal diunggah',
                    'status' => 'danger'
                ]
            );
            $confirmation = false;
        }

        if ($file_2->isValid()) {
            //cek tipe
            $allowed_types = ['application/pdf'];
            if (!in_array($file_2->getMimeType(), $allowed_types)) {
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Tipe file tidak diizinkan', 
                        'status' => 'danger'
                    ]
                );
                
                $data_session = ['notif' => $alert];
                session()->setFlashdata($data_session);
                return redirect()->back();
            }

            //cek ukuran
            if ($file_2->getSize() > 1000000) {
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Ukuran file tidak diizinkan', 
                        'status' => 'danger'
                    ]
                );
                
                $data_session = ['notif' => $alert];
                session()->setFlashdata($data_session);
                return redirect()->back();
            }

            //cek ekstensi
            if ($file_2->getExtension() !== 'pdf') {
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Ekstensi file tidak diizinkan', 
                        'status' => 'danger'
                    ]
                );

                $data_session = ['notif' => $alert];
                session()->setFlashdata($data_session);
                return redirect()->back();
            }

            $cek_gaji = $this->m_pinjaman->getPinjamanById($idpinjaman)[0]->slip_gaji;
            
            if ($cek_gaji) {
                unlink(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/' . $cek_gaji);
            }

            $newName = $file_2->getRandomName();
            $file_2->move(ROOTPATH . 'public/uploads/user/' . $this->account->username . '/pinjaman/', $newName);
            $slip_gaji = $file_2->getName();
            
            $alert2 = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Slip gaji berhasil diunggah',
                    'status' => 'success'
                ]
            );

            $confirmation2 = true;
        } else {
            $alert2 = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Slip gaji gagal diunggah',
                    'status' => 'danger'
                ]
            );

            $confirmation2 = false;
        }
        
        if ($file_3){
            if ($file_3->isValid()) {	
                //cek tipe
                $allowed_types = ['application/pdf'];
                if (!in_array($file_3->getMimeType(), $allowed_types)) {
                    $alert = view(
                        'partials/notification-alert', 
                        [
                            'notif_text' => 'Tipe file tidak diizinkan', 
                            'status' => 'danger'
                        ]
                    );
                    
                    $data_session = ['notif' => $alert];
                    session()->setFlashdata($data_session);
                    return redirect()->back();
                }

                //cek ukuran
                if ($file_3->getSize() > 1000000) {
                    $alert = view(
                        'partials/notification-alert', 
                        [
                            'notif_text' => 'Ukuran file tidak diizinkan', 
                            'status' => 'danger'
                        ]
                    );
                    
                    $data_session = ['notif' => $alert];
                    session()->setFlashdata($data_session);
                    return redirect()->back();
                }

                //cek ekstensi
                if ($file_3->getExtension() !== 'pdf') {
                    $alert = view(
                        'partials/notification-alert', 
                        [
                            'notif_text' => 'Ekstensi file tidak diizinkan', 
                            'status' => 'danger'
                        ]
                    );

                    $data_session = ['notif' => $alert];
                    session()->setFlashdata($data_session);
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

                $alert3 = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Bukti kontrak berhasil diunggah',
                        'status' => 'success'
                    ]
                );

                $data_session += ['notif_kontrak' => $alert3];
                $confirmation3 = true;
            } else {
                $alert3 = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Bukti kontrak gagal diunggah',
                        'status' => 'danger'
                    ]
                );

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

            $notification_data = [
                'anggota_id' => $this->account->iduser,
                'pinjaman_id' => $idpinjaman,
                'message' => 'Pengajuan pinjaman dari anggota '. $this->account->nama_lengkap,
                'timestamp' => date('Y-m-d H:i:s'),
                'group_type' => 1
            ];

            $this->m_notification->insert($notification_data);
            
            $data_session += [
                'notif' => $alert,
                'notif_gaji' => $alert2
            ];
        }
        
        session()->setFlashdata($data_session);
        return redirect()->back();
    }

    public function lunasi_proc($idpinjaman)
    {
        $file_1 = $this->request->getFile('bukti_bayar');

        if ($file_1->isValid() && !$file_1->hasMoved()) {
            //cek tipe
            $allowed_types = ['application/pdf'];
            if (!in_array($file_1->getMimeType(), $allowed_types)) {
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Tipe file tidak diizinkan', 
                        'status' => 'danger'
                    ]
                );
                
                $data_session = ['notif' => $alert];
                session()->setFlashdata($data_session);
                return redirect()->back();
            }

            //cek ukuran
            if ($file_1->getSize() > 1000000) {
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Ukuran file tidak diizinkan', 
                        'status' => 'danger'
                    ]
                );
                
                $data_session = ['notif' => $alert];
                session()->setFlashdata($data_session);
                return redirect()->back();
            }

            //cek ekstensi
            if ($file_1->getExtension() !== 'pdf') {
                $alert = view(
                    'partials/notification-alert', 
                    [
                        'notif_text' => 'Ekstensi file tidak diizinkan', 
                        'status' => 'danger'
                    ]
                );

                $data_session = ['notif' => $alert];
                session()->setFlashdata($data_session);
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

            $notification_data = [
                'anggota_id' => $this->account->iduser,
                'pinjaman_id' => $idpinjaman,
                'message' => 'Pengajuan pelunasan pinjaman dari anggota '. $this->account->nama_lengkap,
                'timestamp' => date('Y-m-d H:i:s'),
                'group_type' => 1
            ];

            $this->m_notification->insert($notification_data);

            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Berhasil mengajukan pelunasan pinjaman',
                    'status' => 'success'
                ]
            );
            
            $dataset += ['notif' => $alert];
            session()->setFlashdata($dataset);
            return redirect()->back();
        } else {
            $alert = view(
                'partials/notification-alert', 
                [
                    'notif_text' => 'Form Persetujuan gagal diunggah: format file tidak sesuai',
                    'status' => 'danger'
                ]
            );
            
            $dataset = ['notif' => $alert];
            session()->setFlashdata($dataset);
            return redirect()->back();
        }
    }

    public function up_form()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $user = $this->m_pinjaman->getPinjamanById($id)[0];
            $data = [
                'a' => $user,
                'duser' => $this->account
            ];
            echo view('anggota/pinjaman/part-pinj-mod-upload', $data);
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

    public function detail_tolak()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
            $data = [
                'a' => $pinjaman,
                'duser' => $this->account
            ];
            echo view('anggota/pinjaman/part-pinj-mod-tolak', $data);
        }
    }
    
    public function add_pengajuan()
    {
        if ($_POST['id']) {
            $id = $_POST['id'];
            
            // Get parameters for display
            $provisi = $this->m_param->getParamById(5)[0]; // Provisi
            $bulan_kelipatan_asuransi = $this->m_param->getParamById(11)[0]; // Bulan kelipatan asuransi
            $nominal_asuransi = $this->m_param->getParamById(12)[0]; // Nominal asuransi
            
            $data = [
                'duser' => $this->account,
                'provisi' => $provisi,
                'bulan_kelipatan_asuransi' => $bulan_kelipatan_asuransi,
                'nominal_asuransi' => $nominal_asuransi
            ];
            echo view('anggota/pinjaman/part-pinj-mod-add', $data);
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

    public function data_pinjaman()
    {
        $request = service('request');
        $model = new M_pinjaman();

        // Parameters from the DataTable
        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'];

        // Fetch data from the model using $start and $length
        $model->where('idanggota', $this->account->iduser);
        $model->whereNotIn('status', [0]);
        $data = $model->asArray()->findAll($length, $start);

        // Total records (you can also use $model->countAll() for exact total)
        $model->where('idanggota', $this->account->iduser);
        $recordsTotal = $model->countAllResults();

        // Records after filtering (if any)
        $model->where('idanggota', $this->account->iduser);
        $recordsFiltered = $model->countAllResults();

        // Prepare the response in the DataTable format
        $response = [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ];

        return $this->response->setJSON($response);
    }

    public function riwayat_penolakan()
    {
        $request = service('request');
        $model = new M_pinjaman();

        // Parameters from the DataTable
        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'];

        // Fetch data from the model using $start and $length
        $model->where('idanggota', $this->account->iduser);
        $model->where('status', 0);
        $data = $model->asArray()->findAll($length, $start);

        // Total records (you can also use $model->countAll() for exact total)
        $model->where('idanggota', $this->account->iduser);
        $recordsTotal = $model->countAllResults();

        // Records after filtering (if any)
        $model->where('idanggota', $this->account->iduser);
        $recordsFiltered = $model->countAllResults();

        // Prepare the response in the DataTable format
        $response = [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ];

        return $this->response->setJSON($response);
    }

    /**
     * Get insurance details for a specific loan
     */
    public function get_asuransi($idpinjaman)
    {
        try {
            // Debug log
            log_message('info', 'Getting asuransi for idpinjaman: ' . $idpinjaman);
            
            // First check if the loan exists
            $pinjaman = $this->m_pinjaman->getPinjamanById($idpinjaman);
            if (empty($pinjaman)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Pinjaman tidak ditemukan'
                ]);
            }
            
            $asuransi_data = $this->m_asuransi->getAsuransiByIdPinjaman($idpinjaman);
            
            log_message('info', 'Asuransi data count: ' . count($asuransi_data));
            
            if (empty($asuransi_data)) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'data' => [],
                    'message' => 'Tidak ada data asuransi untuk pinjaman ini',
                    'total_asuransi' => 0,
                    'debug' => [
                        'idpinjaman' => $idpinjaman,
                        'sql_query' => 'SELECT * FROM tb_asuransi_pinjaman WHERE idpinjaman = ' . $idpinjaman
                    ]
                ]);
            }

            // Calculate total insurance
            $total_asuransi = 0;
            foreach ($asuransi_data as $item) {
                $total_asuransi += $item->nilai_asuransi;
            }

            return $this->response->setJSON([
                'status' => 'success',
                'data' => $asuransi_data,
                'total_asuransi' => $total_asuransi
            ]);
            
        } catch (\Exception $e) {
            log_message('error', 'Error in get_asuransi: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal mengambil data asuransi: ' . $e->getMessage()
            ]);
        }
    }
}