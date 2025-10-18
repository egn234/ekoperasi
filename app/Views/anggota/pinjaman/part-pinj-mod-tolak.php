<div class="modal-header">
    <h5 class="modal-title" id="myModalLabel">Detail Pinjaman</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <p>Tanggal Pengajuan: <b><?= $a->date_created ?></b></p>
    <p>Peminjam: <b><?= $a->nama_peminjam ?></b></p>
    <p>Jenis Pinjaman: <b><?= $a->tipe_permohonan ?></b></p>
    <p>Nominal Pinjaman: <b>Rp <?= number_format($a->nominal, 2, ',', '.') ?></b></p>
    <p>Formulir Pinjaman: 
        <b>
            <?php if ($a->form_bukti != null): ?>
                <a href="<?= base_url() ?>/uploads/user/<?= $a->username_peminjam ?>/pinjaman/<?= $a->form_bukti ?>" target="_blank">Lihat</a>
            <?php else: ?>
                -
            <?php endif; ?>
        </b>
    </p>
    <p>Slip Gaji: 
        <b>
            <?php if ($a->slip_gaji != null): ?>
                <a href="<?= base_url() ?>/uploads/user/<?= $a->username_peminjam ?>/pinjaman/<?= $a->slip_gaji ?>" target="_blank">Lihat</a>
            <?php else: ?>
                -
            <?php endif; ?>
        </b>
    </p>
    <p>Formulir Kontrak:
        <b>
            <?php if ($a->form_kontrak != null): ?>
                <a href="<?= base_url() ?>/uploads/user/<?= $a->username_peminjam ?>/pinjaman/<?= $a->form_kontrak ?>" target="_blank">Lihat</a>
            <?php else: ?>
                -
            <?php endif; ?>
        </b>
    </p>
    <p>Deskripsi: <b><?= $a->deskripsi ?></b></p>
    <p>Alasan Ditolak: <b><?= $a->alasan_tolak ?></b></p>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
</div>
