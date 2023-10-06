<?php
    use App\Models\M_monthly_report;
    $this->m_monthly_report = new M_monthly_report();
?>
<table border = "1" width="100%">
    <thead>
        <tr>
            <th rowspan="2">NO.</th>
            <th rowspan="2">NAMA</th>
            <th rowspan="2">NIK</th>
            <th rowspan="2">SIMPANAN WAJIB + POKOK</th>
            <th rowspan="2">SIMPANAN MANASUKA</th>
            <th colspan="4">PINJAMAN</th>
            <th rowspan="2">TOTAL POTONGAN</th>
            <th colspan="2">CICILAN PINJAMAN UANG</th>
        </tr>
        <tr>
            <th>PINJAMAN POKOK</th>
            <th>BUNGA PINJAMAN</th>
            <th>PROVISI</th>
            <th>JUMLAH</th>
            <th>JML</th>
            <th>KE</th>
        </tr>
    </thead>
    <tbody>
        <?php $c = 1; ?>
        <?php 
            foreach ($usr_list as $a) {
                $cicilan = $this->m_monthly_report->getHitunganPinjaman($a->iduser, $startDate, $endDate);
                $pinjaman = $this->m_monthly_report->getPinjamanAktifByAnggota($a->iduser);

                if ($pinjaman) {
                    $count_cicilan = $this->m_monthly_report->countCicilanByPinjaman($pinjaman[0]->idpinjaman)[0]->hitung;
                }else{
                    $count_cicilan = " - ";
                }
                $pokok_wajib = $this->m_monthly_report->getSumSimpanan1($a->iduser, $startDate, $endDate)[0]->nominal;  
                $manasuka = $this->m_monthly_report->getSumSimpanan2($a->iduser, $startDate, $endDate)[0]->nominal;  
                $p_pokok = ($cicilan)?$cicilan[0]->nominal:0;
                $p_bunga = ($cicilan)?$cicilan[0]->bunga:0;
                $p_provisi = ($cicilan)?$cicilan[0]->provisi:0;

                $total_potongan = ($pokok_wajib + $manasuka + $p_pokok + $p_bunga + $p_provisi);
        ?>
            <tr>
                <td><?= $c; ?></td>
                <td><?= $a->nama_lengkap; ?></td>
                <td>'<?= $a->nik; ?></td>
                <td><?= $pokok_wajib; ?></td>
                <td><?= $manasuka; ?></td>
                <td><?= $p_pokok; ?></td>
                <td><?= $p_bunga; ?></td>
                <td><?= $p_provisi; ?></td>
                <td><?= ($p_pokok + $p_bunga + $p_provisi); ?></td>
                <td><?= $total_potongan; ?></td>
                <td><?= ($pinjaman)?$pinjaman[0]->angsuran_bulanan:' - ';?></td>
                <td><?= $count_cicilan; ?></td>
            </tr>
            <?php $c++; ?>
        <?php } ?>
    </tbody>
</table>
