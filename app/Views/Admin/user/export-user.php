<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Daftar_pengguna.xls");
?>

<table border = "1">
    <thead>
        <tr>
            <th>NO.</th>
            <th>Nama Lengkap</th>
            <th>NIK</th>
            <th>Tempat, Tanggal Lahir</th>
            <th>Alamat</th>
            <th>Institusi</th>
            <th>Unit Kerja</th>
            <th>Nomor Telepon</th>
            <th>Email</th>
            <th>Username</th>
            <th>Tipe Akun</th>
        </tr>
    </thead>
    <tbody>
        <?php $c = 0; ?>
        <?php foreach ($usr_list as $a) {?>
            <tr>
                <td><?= $c; ?></td>
                <td><?= $a->nama_lengkap; ?></td>
                <td><?= $a->nik; ?></td>
                <td><?= $a->tempat_lahir; ?>, <?= $a->tanggal_lahir ?></td>
                <td><?= $a->alamat; ?></td>
                <td><?= $a->instansi; ?></td>
                <td><?= $a->unit_kerja; ?></td>
                <td><?= $a->nomor_telepon; ?></td>
                <td><?= $a->email; ?></td>
                <td><?= $a->username; ?></td>
                <td><?= $a->group_type; ?></td>
            </tr>
            <?php $c++; ?>
        <?php } ?>
    </tbody>
</table>
