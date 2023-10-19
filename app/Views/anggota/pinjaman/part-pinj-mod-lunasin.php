<div class="modal-header">
    <h5 class="modal-title" id="myModalLabel">Permintaan Pelunasan Pinjaman</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <p>
        *Untuk form pelunasan pinjaman dan bukti bayar dijadikan 1 file pdf<br>
        **Untuk pelunasan pinjaman, anda bisa meminta form pelunasan terlebih dahulu ke koperasi GIAT, lalu upload bukti form nya disini
    </p>
    <form id="lunasiPinjamanForm_<?= $a->idpinjaman ?>" action="<?= base_url() ?>anggota/pinjaman/lunasi_proc/<?= $a->idpinjaman ?>" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label" for="frmprstjn">Form Pelunasan + Bukti Bayar (.pdf, .jpeg)</label>
            <input type="file" name="bukti_bayar" id="frmprstjn" class="form-control" accept=".pdf, .jpg, .jpeg" required>
        </div>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
    <button type="submit" form="lunasiPinjamanForm_<?= $a->idpinjaman ?>" class="btn btn-success">Buat Pengajuan Pelunasan</a>
</div>
