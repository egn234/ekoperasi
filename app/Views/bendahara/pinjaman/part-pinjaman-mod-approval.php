<div class="modal-header">
    <h5 class="modal-title" id="myModalLabel">Konfirmasi</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3">
        <?=($flag == 1)?'Terima':'Tolak'?> pengajuan pinjaman ini?
<br>
        <?php if ($flag == 0) {?>
            <form action="<?= url_to('bendahara_cancel_pinjaman', $a->idpinjaman) ?>" id="formSheet" method="post">
                <div class="mb-3">
                    <label class="form-label" for="alasan_tolak">Alasan Ditolak</label>
                    <input type="text" class="form-control" id="alasan_tolak" name="alasan_tolak">
                </div>
            </form>
        <?php }?>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
    <?php if($flag == 1){?>
        <a href="<?= url_to('bendahara_approve_pinjaman', $a->idpinjaman) ?>" class="btn btn-success">Terima</a>
    <?php }else{?>
        <button type="submit" form="formSheet" class="btn btn-danger">Tolak</button>
    <?php }?>
</div>
