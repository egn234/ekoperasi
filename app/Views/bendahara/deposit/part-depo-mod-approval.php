<div class="modal-header">
    <h5 class="modal-title" id="myModalLabel">Konfirmasi</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3">
        <?=($flag == 1)?'Terima':'Tolak'?> pengajuan manasuka ini?
        <br>
        <?php if ($flag == 0) {?>
            <form action="<?= url_to('bendahara_batalkan_simpanan', $a->iddeposit)?>" id="formSheet" method="post">
                <div class="mb-3">
                    <label class="form-label" for="alasan_tolak">Alasan Ditolak</label>
                    <input type="text" class="form-control" id="alasan_tolak" name="alasan_tolak">
                </div>
            </form>
        <?php }else{?>
            <?php if ($confirmation) {?>
                <i class="text-danger">*Pemohon tidak mempunyai cukup saldo untuk pengajuan penarikan transaksi ini</i><br>
                <i class="text-danger">**Saldo Pemohon saat ini: Rp <?= number_format($total_saldo, 2, ',', '.')?></i>
                <?php } ?>
        <?php } ?>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
    <?php if($flag == 1){?>
        <a href="<?= url_to('bendahara_konfirmasi_simpanan', $a->iddeposit)?>" class="btn btn-success">Terima</a>
    <?php }else{?>
        <button type="submit" form="formSheet" class="btn btn-danger">Tolak</button>
    <?php }?>
</div>
