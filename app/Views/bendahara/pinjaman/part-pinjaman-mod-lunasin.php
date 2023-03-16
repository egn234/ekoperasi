<div class="modal-header">
    <h5 class="modal-title" id="myModalLabel">Konfirmasi</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3">
        <?= ($flag == 1)? 'Approve pelunasan untuk pinjaman ini?':'Tolak pelunasan untuk pinjaman ini?'?>
        <br>
        <br>
        <?= ($penalty != 0)
            ?'<i class="text-primary">*(Akun ini masih dibawah batas bulan bebas penalty, akan dikenakan biaya sebesar Rp '.number_format($penalty, 0,'.',',').')</i><br>'
            :'<i class="text-primary">*(Akun ini telah melewati batas bulan bebas penalty, tidak dikenakan biaya tambahan)</i>';
        ?>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
    <?php if($flag == 1){?>
        <a href="<?= url_to('bendahara_konfirmasi_lunas', $idpinjaman)?>" class="btn btn-success">Approve</a>
    <?php }else{?>
        <a href="<?= url_to('bendahara_tolak_lunas', $idpinjaman)?>" class="btn btn-danger">Tolak</a>
    <?php }?>
</div>
