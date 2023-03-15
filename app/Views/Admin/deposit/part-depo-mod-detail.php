<div class="modal-header">
    <h5 class="modal-title" id="myModalLabel">Konfirmasi</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3">
        <table width="100%">
            <tr>
                <td>Nama Lengkap Anggota</td>
                <td>:</td>
                <td><?=$duser->nama_lengkap?></td>
            </tr>
            <tr>
                <td>Jenis Pengajuan</td>
                <td>:</td>
                <td><?=$a->jenis_pengajuan?></td>
            </tr>
            <tr>
                <td>Jenis Simpanan</td>
                <td>:</td>
                <td><?=$a->jenis_deposit?></td>
            </tr>
            <tr>
                <td>Nominal</td>
                <td>:</td>
                <td>
                    <?php if ($a->cash_in == 0) { ?>                    
                       Rp <?=number_format($a->cash_out, 2, ',', '.')?>
                    <?php }else{?>
                       Rp <?=number_format($a->cash_in, 2, ',', '.')?>
                    <?php }?>
                </td>
            </tr>
            <?php if ($a->jenis_deposit == "manasuka" || $a->jenis_deposit == "manasuka free") {?>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td><?=$a->status?></td>
                </tr>
                <?php if($a->status == 'diterima'){?>
                    <tr>
                        <td>Telah diproses oleh</td>
                        <td>:</td>
                        <td><?=$a->nama_admin?></td>
                    </tr>
                <?php }?>
                <?php if($a->jenis_deposit == 'manasuka free' && $a->jenis_pengajuan == 'penyimpanan'){?>
                    <tr style=" vertical-align: top;">
                        <td>Bukti transfer</td>
                        <td>:</td>
                        <td>
                            <?php if(!$a->bukti_transfer){?>
                                Belum mengunggah bukti bayar
                            <?php }else{?>
                                <a href="<?=base_url()?>/uploads/user/<?=$duser->username?>/tf/<?=$a->bukti_transfer?>" target="_blank">
                                    <?=$a->bukti_transfer?> <i class="fa fa-external-link-alt"></i>
                                </a>
                            <?php }?>
                        </td>
                    </tr>
                <?php }?>
            <?php }?>
        </table>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
</div>
