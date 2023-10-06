<div class="modal-header">
    <h5 class="modal-title" id="myModalLabel">Konfirmasi</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3">
        Konfirmasi status menjadi lunas?
        <br>
        <br>
        <?= ($penalty != 0)
            ?'<i class="text-primary">*(Akun ini masih dibawah batas bulan bebas penalty, akan dikenakan biaya sebesar Rp '.number_format($penalty, 0,'.',',').')</i><br>'
            :'';
        ?>
        <i class="text-danger">*KONFIRMASI PELUNASAN INI HARUS ADA PENGECEKAN DARI LUAR APLIKASI, PASTIKAN ANDA MENGECEK KEMBALI KETERSEDIAAN APPROVAL DARI PEMINJAM</i>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
    <a href="<?= url_to('admin_konfirmasi_lunas', $idpinjaman)?>" class="btn btn-success">Buat Pengajuan</a>
</div>
