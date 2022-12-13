<?php
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=member_loan_report_" . date('YmdHis') . ".xls"); 
?>
<table border = "1">
    <thead>
        <tr>
            <th>NO.</th>
            <th>NAMA</th>
            <th>NIK</th>
            <th>NOMINAL PINJAMAN</th>
            <th>TERBAYAR</th>
            <th>SISA BAYAR</th>
            <th>CICILAN KE</th>
            <th>TOTAL CICILAN</th>
        </tr>
    </thead>
    <tbody>
        <?php $c = 1; ?>
        <?php foreach ($report as $a) {?>   
            <tr>
                <td><?= $c; ?></td>
                <td><?= $a->nama; ?></td>
                <td>'<?= $a->nik; ?></td>
                <td><?= number_format($a->pinjaman, 0, '.', ','); ?></td>
                <td><?= number_format($a->terbayar, 0, '.', ','); ?></td>
                <td><?= number_format($a->sisa_bayar, 0, '.', ','); ?></td>
                <td><?= $a->cicilan_ke; ?></td>
                <td><?= $a->angsuran; ?></td>
            </tr>
        <?php $c++; ?>
        <?php }?>
    </tbody>
</table>

<br>
<br>
<br>
<br>
<i>Dibuat pada tanggal <?=date('d F Y H:i:s')?></i>