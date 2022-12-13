<?php
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=member_deposit_report_" . date('YmdHis') . ".xls"); 
?>
<table border = "1">
    <thead>
        <tr>
            <th>NO.</th>
            <th>NAMA</th>
            <th>NIK</th>
            <th>SIMPANAN POKOK</th>
            <th>SIMPANAN WAJIB </th>
            <th>SIMPANAN MANASUKA</th>
            <th>TOTAL SALDO</th>
        </tr>
    </thead>
    <tbody>
        <?php $c = 1; ?>
        <?php foreach ($report as $a) {?>   
            <tr>
                <td><?= $c; ?></td>
                <td><?= $a->nama; ?></td>
                <td>'<?= $a->nik; ?></td>
                <td><?= number_format($a->pokok, 0, '.', ','); ?></td>
                <td><?= number_format($a->wajib, 0, '.', ','); ?></td>
                <td><?= number_format($a->manasuka, 0, '.', ','); ?></td>
                <td><?= number_format($a->total_saldo, 0, '.', ','); ?></td>
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