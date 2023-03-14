<?php
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=REKAP_TAHUNAN_PER_TGL_" . date('d_F_Y') . ".xls"); 
?>
<?php
    use App\Models\M_monthly_report;
    use App\Models\M_deposit;
    use App\Models\M_pinjaman;

    $this->m_monthly_report = new M_monthly_report();
    $this->m_deposit = new M_deposit();
    $this->m_pinjaman = new M_pinjaman();
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
        <tr>
            <td colspan="2"><b>PEG. BUT</b></td>
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
        <?php $c = 1; ?>
        <?php 
            foreach ($pegawai_but as $a) {
                $cicilan = $this->m_monthly_report->getHitunganPinjaman($a->iduser, $startDate, $endDate);
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
                
                $jumlah_pinjaman = $this->m_pinjaman->select("SUM(nominal) AS saldo_pinjaman")
                                                    ->where('status', '5')
                                                    ->where("date_updated BETWEEN '".$startDate."' AND '".$endDate."'")
                                                    ->where('idanggota', $a->iduser)
                                                    ->get()
                                                    ->getResult()[0]
                                                    ->saldo_pinjaman;

                $manasuka = $this->m_monthly_report->getSumSimpanan2($a->iduser, $startDate, $endDate)[0]->nominal;  
                $p_pokok = ($cicilan)?$cicilan[0]->nominal:0;

        ?>
            <tr>
                <td><?= $c; ?></td>
                <td><?= $a->nama_lengkap; ?></td>
                <td><?= number_format($simpanan_pokok, 0, '.', ','); ?></td>
                <td><?= number_format($simpanan_wajib, 0, '.', ','); ?></td>
                <td><?= number_format($simpanan_manasuka, 0, '.', ','); ?></td>
                <td><?= number_format(($simpanan_manasuka+$simpanan_wajib+$simpanan_pokok), 0, '.', ','); ?></td>
                <td><?= number_format($jumlah_pinjaman, 0, '.', ','); ?></td>
                <td><?= number_format($p_pokok, 0, '.', ','); ?></td>
                <td><?= number_format($jumlah_pinjaman-$p_pokok, 0, '.', ',')?></td>
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

        <tr>
            <td colspan="2"><b>PEG. GIAT</b></td>
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
            foreach ($pegawai_giat as $a) {
                $cicilan = $this->m_monthly_report->getHitunganPinjaman($a->iduser, $startDate, $endDate);
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

                $jumlah_pinjaman = $this->m_pinjaman->select("SUM(nominal) AS saldo_pinjaman")
                                                    ->where('status', '5')
                                                    ->where("date_updated BETWEEN '".$startDate."' AND '".$endDate."'")
                                                    ->where('idanggota', $a->iduser)
                                                    ->get()
                                                    ->getResult()[0]
                                                    ->saldo_pinjaman;

                $manasuka = $this->m_monthly_report->getSumSimpanan2($a->iduser, $startDate, $endDate)[0]->nominal;  
                $p_pokok = ($cicilan)?$cicilan[0]->nominal:0;

        ?>
            <tr>
                <td><?= $c; ?></td>
                <td><?= $a->nama_lengkap; ?></td>
                <td><?= number_format($simpanan_pokok, 0, '.', ','); ?></td>
                <td><?= number_format($simpanan_wajib, 0, '.', ','); ?></td>
                <td><?= number_format($simpanan_manasuka, 0, '.', ','); ?></td>
                <td><?= number_format(($simpanan_manasuka+$simpanan_wajib+$simpanan_pokok), 0, '.', ','); ?></td>
                <td><?= number_format($jumlah_pinjaman, 0, '.', ','); ?></td>
                <td><?= number_format($p_pokok, 0, '.', ','); ?></td>
                <td><?= number_format($jumlah_pinjaman-$p_pokok, 0, '.', ',')?></td>
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

        <tr>
            <td colspan="2"><b>PEG. Telkom</b></td>
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
            foreach ($pegawai_telkom as $a) {
                $cicilan = $this->m_monthly_report->getHitunganPinjaman($a->iduser, $startDate, $endDate);
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

                $jumlah_pinjaman = $this->m_pinjaman->select("SUM(nominal) AS saldo_pinjaman")
                                                    ->where('status', '5')
                                                    ->where("date_updated BETWEEN '".$startDate."' AND '".$endDate."'")
                                                    ->where('idanggota', $a->iduser)
                                                    ->get()
                                                    ->getResult()[0]
                                                    ->saldo_pinjaman;

                $manasuka = $this->m_monthly_report->getSumSimpanan2($a->iduser, $startDate, $endDate)[0]->nominal;  
                $p_pokok = ($cicilan)?$cicilan[0]->nominal:0;

        ?>
            <tr>
                <td><?= $c; ?></td>
                <td><?= $a->nama_lengkap; ?></td>
                <td><?= number_format($simpanan_pokok, 0, '.', ','); ?></td>
                <td><?= number_format($simpanan_wajib, 0, '.', ','); ?></td>
                <td><?= number_format($simpanan_manasuka, 0, '.', ','); ?></td>
                <td><?= number_format(($simpanan_manasuka+$simpanan_wajib+$simpanan_pokok), 0, '.', ','); ?></td>
                <td><?= number_format($jumlah_pinjaman, 0, '.', ','); ?></td>
                <td><?= number_format($p_pokok, 0, '.', ','); ?></td>
                <td><?= number_format($jumlah_pinjaman-$p_pokok, 0, '.', ',')?></td>
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

        <tr>
            <td colspan="2"><b>PEG. Trengginas Jaya</b></td>
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
            foreach ($pegawai_trengginas as $a) {
                $cicilan = $this->m_monthly_report->getHitunganPinjaman($a->iduser, $startDate, $endDate);
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

                $jumlah_pinjaman = $this->m_pinjaman->select("SUM(nominal) AS saldo_pinjaman")
                                                    ->where('status', '5')
                                                    ->where("date_updated BETWEEN '".$startDate."' AND '".$endDate."'")
                                                    ->where('idanggota', $a->iduser)
                                                    ->get()
                                                    ->getResult()[0]
                                                    ->saldo_pinjaman;

                $manasuka = $this->m_monthly_report->getSumSimpanan2($a->iduser, $startDate, $endDate)[0]->nominal;  
                $p_pokok = ($cicilan)?$cicilan[0]->nominal:0;

        ?>
            <tr>
                <td><?= $c; ?></td>
                <td><?= $a->nama_lengkap; ?></td>
                <td><?= number_format($simpanan_pokok, 0, '.', ','); ?></td>
                <td><?= number_format($simpanan_wajib, 0, '.', ','); ?></td>
                <td><?= number_format($simpanan_manasuka, 0, '.', ','); ?></td>
                <td><?= number_format(($simpanan_manasuka+$simpanan_wajib+$simpanan_pokok), 0, '.', ','); ?></td>
                <td><?= number_format($jumlah_pinjaman, 0, '.', ','); ?></td>
                <td><?= number_format($p_pokok, 0, '.', ','); ?></td>
                <td><?= number_format($jumlah_pinjaman-$p_pokok, 0, '.', ',')?></td>
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

        <tr>
            <td colspan="2"><b>PEG. Telkom University</b></td>
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
            foreach ($pegawai_telyu as $a) {
                $cicilan = $this->m_monthly_report->getHitunganPinjaman($a->iduser, $startDate, $endDate);
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

                $jumlah_pinjaman = $this->m_pinjaman->select("SUM(nominal) AS saldo_pinjaman")
                                                    ->where('status', '5')
                                                    ->where("date_updated BETWEEN '".$startDate."' AND '".$endDate."'")
                                                    ->where('idanggota', $a->iduser)
                                                    ->get()
                                                    ->getResult()[0]
                                                    ->saldo_pinjaman;

                $manasuka = $this->m_monthly_report->getSumSimpanan2($a->iduser, $startDate, $endDate)[0]->nominal;  
                $p_pokok = ($cicilan)?$cicilan[0]->nominal:0;

        ?>
            <tr>
                <td><?= $c; ?></td>
                <td><?= $a->nama_lengkap; ?></td>
                <td><?= number_format($simpanan_pokok, 0, '.', ','); ?></td>
                <td><?= number_format($simpanan_wajib, 0, '.', ','); ?></td>
                <td><?= number_format($simpanan_manasuka, 0, '.', ','); ?></td>
                <td><?= number_format(($simpanan_manasuka+$simpanan_wajib+$simpanan_pokok), 0, '.', ','); ?></td>
                <td><?= number_format($jumlah_pinjaman, 0, '.', ','); ?></td>
                <td><?= number_format($p_pokok, 0, '.', ','); ?></td>
                <td><?= number_format($jumlah_pinjaman-$p_pokok, 0, '.', ',')?></td>
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

        <tr>
            <td colspan="2"><b>PEG. YPT</b></td>
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
            foreach ($pegawai_ypt as $a) {
                $cicilan = $this->m_monthly_report->getHitunganPinjaman($a->iduser, $startDate, $endDate);
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

                $jumlah_pinjaman = $this->m_pinjaman->select("SUM(nominal) AS saldo_pinjaman")
                                                    ->where('status', '5')
                                                    ->where("date_updated BETWEEN '".$startDate."' AND '".$endDate."'")
                                                    ->where('idanggota', $a->iduser)
                                                    ->get()
                                                    ->getResult()[0]
                                                    ->saldo_pinjaman;

                $manasuka = $this->m_monthly_report->getSumSimpanan2($a->iduser, $startDate, $endDate)[0]->nominal;  
                $p_pokok = ($cicilan)?$cicilan[0]->nominal:0;

        ?>
            <tr>
                <td><?= $c; ?></td>
                <td><?= $a->nama_lengkap; ?></td>
                <td><?= number_format($simpanan_pokok, 0, '.', ','); ?></td>
                <td><?= number_format($simpanan_wajib, 0, '.', ','); ?></td>
                <td><?= number_format($simpanan_manasuka, 0, '.', ','); ?></td>
                <td><?= number_format(($simpanan_manasuka+$simpanan_wajib+$simpanan_pokok), 0, '.', ','); ?></td>
                <td><?= number_format($jumlah_pinjaman, 0, '.', ','); ?></td>
                <td><?= number_format($p_pokok, 0, '.', ','); ?></td>
                <td><?= number_format($jumlah_pinjaman-$p_pokok, 0, '.', ',')?></td>
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
    </tbody>
</table>
