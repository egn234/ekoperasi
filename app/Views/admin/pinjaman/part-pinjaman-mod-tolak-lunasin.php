<div class="modal-header">
    <h5 class="modal-title" id="myModalLabel">Konfirmasi</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3">
        Tolak pengajuan pelunasan untuk <?= $user[0]['username'] ?>?<br>
        <i class="text-danger">*PASTIKAN ANDA MENGECEK KEMBALI KETERSEDIAAN APPROVAL DARI PEMINJAM</i>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
    <a href="<?= url_to('admin_tolak_lunas', $idpinjaman)?>" class="btn btn-danger">Tolak</a>
</div>
