<?php
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=REKAP_TAHUNAN_PER_TGL_" . date('d_F_Y') . ".xls"); 
?>
<?php
    use App\Models\M_monthly_report;
    use App\Models\M_deposit;
    use App\Models\M_pinjaman;
    use App\Models\M_cicilan;

    $this->m_monthly_report = new M_monthly_report();
    $this->m_deposit = new M_deposit();
    $this->m_pinjaman = new M_pinjaman();
    $this->m_cicilan = new M_cicilan();
?>

<table border = "1" width="100%">
    <thead>
        <tr>
            <th rowspan="2">NO. URUT</th>
            <th rowspan="2">NAMA ANGGOTA KOPERASI</th>
            <th colspan="3">SIMPANAN</th>
            <th rowspan="2"><?= strtoupper('SALDO SIMPANAN PER '.date('d F Y'))?></th>
            <th colspan="2">PINJAMAN</th>
            <th rowspan="2"><?= strtoupper('SALDO PINJAMAN PER '.date('d F Y'))?></th>
            <th colspan="2">KETERANGAN</th>
            <th rowspan="2">NOMOR ANGGOTA</th>
        </tr>
        <tr>
            <th>POKOK</th>
            <th>WAJIB</th>
            <th>MANASUKA</th>
            <th>JUMLAH PINJAMAN</th>
            <th>PEMBAYARAN POKOK</th>
            <th>JUMLAH CICILAN</th>
            <th>SISA CICILAN</th>
        </tr>
    </thead>

    <tbody>

        <?php 
            $list_pegawai = [
                $pegawai_but,
                $pegawai_giat,
                $pegawai_telkom,
                $pegawai_trengginas,
                $pegawai_telyu,
                $pegawai_ypt,
            ];

            $nama_pegawai = [
                'PEG. BUT',
                'PEG. GIAT',
                'PEG. Telkom',
                'PEG. Trengginas Jaya',
                'PEG. Telkom University',
                'PEG. YPT'
            ];
            $c = 1;
        ?>

        <?php for ($i = 0; $i < count($list_pegawai); ++$i) {?>
            <tr>
                <td colspan="2"><b><?=$nama_pegawai[$i]?></b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <?php 
                foreach ($list_pegawai[$i] as $a) {
                    $cicilan = $this->m_monthly_report->getHitunganPinjaman2($a->iduser, $startDate, $endDate);
                    $pinjaman = $this->m_monthly_report->getPinjamanAktifByAnggota($a->iduser);

                    if ($pinjaman) {
                        $count_cicilan = $this->m_monthly_report->countCicilanByPinjaman($pinjaman[0]->idpinjaman)[0]->hitung;
                    }else{
                        $count_cicilan = " - ";
                    }

                    $simpanan_pokok = $this->m_deposit->select("SUM(cash_in)-SUM(cash_out) AS nominal")
                                                      ->where('status', 'diterima')
                                                      ->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
                                                      ->where('jenis_deposit', 'pokok')
                                                      ->where('idanggota', $a->iduser)
                                                      ->get()
                                                      ->getResult()[0]
                                                      ->nominal;

                    $simpanan_wajib = $this->m_deposit->select("SUM(cash_in)-SUM(cash_out) AS nominal")
                                                      ->where('status', 'diterima')
                                                      ->whereNotIn('deskripsi', ['saldo wajib'])
                                                      ->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
                                                      ->where('jenis_deposit', 'wajib')
                                                      ->where('idanggota', $a->iduser)
                                                      ->get()
                                                      ->getResult()[0]
                                                      ->nominal;

                    $simpanan_manasuka = $this->m_deposit->select("SUM(cash_in)-SUM(cash_out) AS nominal")
                                                         ->where('status', 'diterima')
                                                         ->whereNotIn('deskripsi', ['saldo manasuka'])
                                                         ->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
                                                         ->whereIn('jenis_deposit', ['manasuka', 'manasuka free'])
                                                         ->where('idanggota', $a->iduser)
                                                         ->get()
                                                         ->getResult()[0]
                                                         ->nominal;

                    if($pinjaman){
                        $cicilan_dalam = $this->m_cicilan->select("SUM(nominal) as nominal")
                            ->where('idpinjaman', $pinjaman[0]->idpinjaman)
                            ->where("date_created BETWEEN '".$startDate."' AND '".$endDate."'")
                            ->get()->getResult()[0]
                            ->nominal;

                        $cicilan_luar = $this->m_cicilan->select("SUM(nominal) as nominal")
                            ->where('idpinjaman', $pinjaman[0]->idpinjaman)
                            ->where("date_created < '".$startDate."'")
                            ->get()->getResult()[0]
                            ->nominal;

                        $jumlah_pinjaman = $pinjaman[0]->nominal - $cicilan_luar;

                    }else{
                        $cicilan_dalam = 0;
                        $cicilan_luar = 0;
                        $jumlah_pinjaman = 0;
                    }
                    

                    $manasuka = $this->m_monthly_report->getSumSimpanan2($a->iduser, $startDate, $endDate)[0]->nominal;  
                    $p_pokok = ($cicilan)?$cicilan[0]->nominal:0;

            ?>
                <tr>
                    <td><?= $c; ?></td>
                    <td><?= $a->nama_lengkap; ?></td>
                    <td><?= $simpanan_pokok; ?></td>
                    <td><?= $simpanan_wajib; ?></td>
                    <td><?= $simpanan_manasuka; ?></td>
                    <td><?= ($simpanan_manasuka+$simpanan_wajib+$simpanan_pokok); ?></td>
                    <td><?= $jumlah_pinjaman; ?></td>
                    <td><?= $cicilan_dalam; ?></td>
                    <td><?= $jumlah_pinjaman-$cicilan_dalam?></td>
                    <td><?= ($pinjaman)?$pinjaman[0]->angsuran_bulanan:' - ';?></td>
                    <td><?= ($count_cicilan != " - ")?$pinjaman[0]->angsuran_bulanan - $count_cicilan:' - '; ?></td>
                    <td><?=$a->username?></td>
                </tr>
                <?php $c++; ?>
            <?php } ?>

            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        <?php }?>
    </tbody>
</table>