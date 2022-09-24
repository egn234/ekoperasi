            <div class="modal-header">
                <h5 class="modal-title" id="addPengajuanLabel">Unggah Bukti Bayar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= url_to('an_de_upbkttrf', $a->iddeposit) ?>" id="form_upload_bukti_<?$a->iddeposit?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label" for="bkt_trf">Bukti Transfer</label>
                        <input type="file" name="bukti_transfer" id="bkt_trf" class="form-control" accept="image/jpg, image/jpeg" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" form="form_upload_bukti_<?$a->iddeposit?>" class="btn btn-success">Unggah</button>
            </div>