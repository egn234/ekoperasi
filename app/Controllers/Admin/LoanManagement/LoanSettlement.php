<?php

namespace App\Controllers\Admin\LoanManagement;

use App\Models\M_pinjaman;

/**
 * LoanSettlement Controller
 * Handles loan settlement (pelunasan) - full and partial repayment
 */
class LoanSettlement extends BaseLoanController
{
    /**
     * Display list of settlement applications
     */
    public function index()
    {
        $data = $this->getBaseViewData('Pinjaman', 'Pelunasan Pinjaman');
        return view('admin/pinjaman/list-pelunasan', $data);
    }

    /**
     * Approve full settlement application
     */
    public function pelunasan_proc($idpinjaman = false)
    {
        $dataset = [
            'idadmin' => $this->account->iduser,
            'status' => 7
        ];

        $this->m_pinjaman->updatePinjaman($idpinjaman, $dataset);

        $idanggota = $this->m_pinjaman->where('idpinjaman', $idpinjaman)
            ->get()
            ->getResult()[0]
            ->idanggota;

        $this->createNotification(
            $idanggota,
            $idpinjaman,
            'Pengajuan pelunasan baru dari ' . $this->m_user->where('iduser', $idanggota)->get()->getResult()[0]->nama_lengkap,
            2
        );

        $this->sendAlert('Pengajuan pelunasan berhasil disetujui');
        return redirect()->to(base_url('admin/pinjaman/list_pelunasan'));
    }

    /**
     * Reject settlement application
     */
    public function tolak_pelunasan_proc($idpinjaman = false)
    {
        $dataset = [
            'idadmin' => $this->account->iduser,
            'status' => 4
        ];

        $this->m_pinjaman->updatePinjaman($idpinjaman, $dataset);

        $idanggota = $this->m_pinjaman->where('idpinjaman', $idpinjaman)
            ->get()
            ->getResult()[0]
            ->idanggota;

        $this->createNotification(
            $idanggota,
            $idpinjaman,
            'Pengajuan pelunasan ditolak oleh ' . $this->account->nama_lengkap,
            4
        );

        $this->sendAlert('Pengajuan pelunasan berhasil ditolak');
        return redirect()->to(base_url('admin/pinjaman/list_pelunasan'));
    }

    /**
     * Process partial settlement
     */
    public function pelunasan_partial_proc($idpinjaman)
    {
        $bulan_bayar = request()->getPost('bulan_bayar');
        $pin = $this->m_pinjaman->getPinjamanById($idpinjaman)[0];

        for ($i = 0; $i < $bulan_bayar; ++$i) {
            // Check existing installments
            $cek_cicilan = $this->m_cicilan->where('idpinjaman', $idpinjaman)
                ->countAllResults();

            if ($cek_cicilan == 0) {
                // First installment
                $provisi = $this->m_param->where('idparameter', 5)->get()->getResult()[0]->nilai / 100;

                $dataset_cicilan = [
                    'nominal' => ($pin->nominal / $pin->angsuran_bulanan),
                    'bunga' => 0,
                    'provisi' => ($pin->nominal * ($pin->angsuran_bulanan * $provisi)) / $pin->angsuran_bulanan,
                    'tipe_bayar' => 'langsung',
                    'date_created' => date('Y-m-d H:i:s'),
                    'idpinjaman' => $idpinjaman
                ];

                $this->m_cicilan->insertCicilan($dataset_cicilan);
            } elseif ($cek_cicilan == ($pin->angsuran_bulanan - 1)) {
                // Last installment
                $dataset_cicilan = [
                    'nominal' => ($pin->nominal / $pin->angsuran_bulanan),
                    'bunga' => 0,
                    'tipe_bayar' => 'langsung',
                    'date_created' => date('Y-m-d H:i:s'),
                    'idpinjaman' => $idpinjaman
                ];

                $this->m_cicilan->insertCicilan($dataset_cicilan);

                $status_pinjaman = ['status' => 5];
                $this->m_pinjaman->updatePinjaman($idpinjaman, $status_pinjaman);
            } elseif ($cek_cicilan != 0 && $cek_cicilan < $pin->angsuran_bulanan) {
                // Middle installments
                $dataset_cicilan = [
                    'nominal' => ($pin->nominal / $pin->angsuran_bulanan),
                    'bunga' => 0,
                    'tipe_bayar' => 'langsung',
                    'date_created' => date('Y-m-d H:i:s'),
                    'idpinjaman' => $idpinjaman
                ];

                $this->m_cicilan->insertCicilan($dataset_cicilan);
            }
        }

        $idanggota = $this->m_pinjaman->where('idpinjaman', $idpinjaman)
            ->get()
            ->getResult()[0]
            ->idanggota;

        $this->createNotification(
            $idanggota,
            $idpinjaman,
            'Pinjaman telah dilunasi sebagian oleh ' . $this->account->nama_lengkap,
            4
        );

        $this->sendAlert('Berhasil');
        return redirect()->to(base_url('admin/pinjaman/list'));
    }

    /**
     * Show full settlement modal (AJAX)
     */
    public function pengajuan_lunas()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
            $user_detail = $this->m_pinjaman->asArray()
                ->select('tb_user.username')
                ->select('tb_user.nama_lengkap')
                ->join('tb_user', 'tb_user.iduser = tb_pinjaman.idanggota')
                ->where('idpinjaman', $id)
                ->findAll();
            $penalty_percent = $this->m_param->getParamById(6)[0]->nilai;
            $bebas_penalty = $this->m_param->getParamById(7)[0]->nilai;
            $hitung_cicilan = $this->m_cicilan->select('COUNT(idcicilan) AS hitung, IFNULL(SUM(nominal),0) AS total_lunas')
                ->where('idpinjaman', $id)
                ->get()
                ->getResult()[0];
            $penalty = $hitung_cicilan->hitung <= $bebas_penalty ? ($pinjaman->nominal - $hitung_cicilan->total_lunas) * ($penalty_percent / 100) : 0;
            $data = [
                'idpinjaman' => $id,
                'penalty' => $penalty,
                'hitung_cicilan' => $hitung_cicilan,
                'bebas_penalty' => $bebas_penalty - $hitung_cicilan->hitung,
                'user' => $user_detail,
                'flag' => 1
            ];
            echo view('admin/pinjaman/part-pinjaman-mod-lunasin', $data);
        }
    }

    /**
     * Show reject settlement modal (AJAX)
     */
    public function tolak_pengajuan_lunas()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];
            $user_detail = $this->m_pinjaman->asArray()
                ->select('tb_user.username')
                ->select('tb_user.nama_lengkap')
                ->join('tb_user', 'tb_user.iduser = tb_pinjaman.idanggota')
                ->where('idpinjaman', $id)
                ->findAll();
            $penalty_percent = $this->m_param->getParamById(6)[0]->nilai;
            $bebas_penalty = $this->m_param->getParamById(7)[0]->nilai;
            $hitung_cicilan = $this->m_cicilan->select('COUNT(idcicilan) AS hitung, IFNULL(SUM(nominal),0) AS total_lunas')
                ->where('idpinjaman', $id)
                ->get()
                ->getResult()[0];
            $penalty = $hitung_cicilan->hitung <= $bebas_penalty ? ($pinjaman->nominal - $hitung_cicilan->total_lunas) * ($penalty_percent / 100) : 0;
            $data = [
                'idpinjaman' => $id,
                'penalty' => $penalty,
                'hitung_cicilan' => $hitung_cicilan,
                'bebas_penalty' => $bebas_penalty - $hitung_cicilan->hitung,
                'user' => $user_detail,
                'flag' => 1
            ];
            echo view('admin/pinjaman/part-pinjaman-mod-tolak-lunasin', $data);
        }
    }

    /**
     * Show partial settlement modal (AJAX)
     */
    public function pelunasan_partial()
    {
        if ($_POST['rowid']) {
            $id = $_POST['rowid'];
            $pinjaman = $this->m_pinjaman->getPinjamanById($id)[0];

            $info_cicilan = $this->m_cicilan->select('COUNT(idcicilan) AS hitung, sum(nominal) as terbayar')
                ->where('idpinjaman', $id)
                ->get()->getResult()[0];

            $user_detail = $this->m_pinjaman->asArray()
                ->select('tb_user.username')
                ->select('tb_user.nama_lengkap')
                ->join('tb_user', 'tb_user.iduser = tb_pinjaman.idanggota')
                ->where('idpinjaman', $id)
                ->findAll();

            $sisa_cicilan = $pinjaman->angsuran_bulanan - $info_cicilan->hitung;
            $sisa_pinjaman = $pinjaman->nominal - $info_cicilan->terbayar;
            $nominal_cicilan = $pinjaman->nominal / $pinjaman->angsuran_bulanan;

            $data = [
                'idpinjaman' => $id,
                'pinjaman' => $pinjaman,
                'sisa_cicilan' => $sisa_cicilan,
                'sisa_pinjaman' => $sisa_pinjaman,
                'nominal_cicilan' => $nominal_cicilan,
                'user' => $user_detail,
                'flag' => 1
            ];
            echo view('admin/pinjaman/part-pelunasan-mod-sebagian', $data);
        }
    }

    /**
     * DataTable: Settlement applications (status = 6)
     */
    public function data_pelunasan()
    {
        $request = service('request');
        $db = \Config\Database::connect();
        $model = $db->table('tb_pinjaman a');

        $start = $request->getPost('start') ?? 0;
        $length = $request->getPost('length') ?? 10;
        $draw = $request->getPost('draw');
        $searchValue = $request->getPost('search')['value'] ?? '';

        $model->select('a.idpinjaman');
        $model->select('b.username');
        $model->select('b.nama_lengkap');
        $model->select('a.nominal');
        $model->select('a.angsuran_bulanan');
        $model->select('(a.angsuran_bulanan - (SELECT COUNT(z.idcicilan) FROM tb_cicilan z WHERE z.idpinjaman = a.idpinjaman)) AS sisa_cicilan', false);
        $model->select('(a.nominal - (SELECT SUM(z.nominal) FROM tb_cicilan z WHERE z.idpinjaman = a.idpinjaman)) AS sisa_pinjaman', false);
        $model->select('a.date_updated');
        $model->select('a.bukti_tf');
        $model->join('tb_user b', 'a.idanggota = b.iduser');
        $model->where('a.status', 6);
        $model->groupStart()
            ->like('b.nama_lengkap', $searchValue)
            ->orLike('b.username', $searchValue);
        $model->groupEnd();
        $data = $model->limit($length, $start)->get()->getResult();

        $model->where('a.status', 6);
        $recordsTotal = $model->countAllResults();

        $model->where('a.status', 6);
        $model->groupStart()
            ->like('b.nama_lengkap', $searchValue)
            ->orLike('b.username', $searchValue);
        $model->groupEnd();
        $model->join('tb_user b', 'b.iduser = a.idanggota');
        $recordsFiltered = $model->countAllResults();

        $response = [
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
        ];

        return $this->response->setJSON($response);
    }
}
