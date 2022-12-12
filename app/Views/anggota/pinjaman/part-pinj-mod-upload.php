            <div class="modal-header">
                <h5 class="modal-title" id="addPengajuanLabel">Unggah Form Persetujuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= url_to('an_de_upfrmprstjn', $a->idpinjaman) ?>" id="form_upload_form_<?$a->idpinjaman?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label" for="frmprstjn">Form persetujuan yang sudah di tanda tangan (.pdf)</label>
                        <input type="file" name="form_bukti" id="frmprstjn" class="form-control" accept=".pdf" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" form="form_upload_form_<?$a->idpinjaman?>" class="btn btn-success">Unggah</button>
            </div>