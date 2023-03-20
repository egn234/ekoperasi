<div class="modal-header">
    <h5 class="modal-title" id="myModalLabel">Konfirmasi</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3">
        <div class="border p-2">
            <table width="100%">
                <tr>
                    <td>Nama Peminjam</td>
                    <td>:</td>
                    <td><?=$a->nama_peminjam?></td>
                </tr>
                <tr>
                    <td>Jenis Permohonan</td>
                    <td>:</td>
                    <td><?=$a->tipe_permohonan?></td>
                </tr>
                <tr>
                    <td>Tanggal Pengajuan</td>
                    <td>:</td>
                    <td><?=$a->date_created?></td>
                </tr>
                <tr>
                    <td>Deskripsi</td>
                    <td>:</td>
                    <td><?=$a->deskripsi?></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>
                        <?php if($a->status == 0){?>
                            Ditolak
                        <?php }elseif($a->status == 1){?>
                            Upload Kelengkapan Form
                        <?php }elseif($a->status == 2){?>
                            Menunggu Verifikasi
                        <?php }elseif($a->status == 3){?>
                            Menunggu Approval Sekretariat
                        <?php }elseif($a->status == 4){?>
                            Sedang Berlangsung
                        <?php }elseif($a->status == 5){?>
                            Lunas
                        <?php }elseif($a->status == 6){?>    
                            Konfirmasi Pelunasan
                        <?php }?>
                    </td>
                </tr>
                <?php if($a->status == '0'){?>
                    <tr>
                        <td>Alasan Ditolak</td>
                        <td>:</td>
                        <td><?=$a->alasan_tolak?></td>
                    </tr>
                <?php }?>
                <?php if($a->status > 1){?>
                    <tr><td colspan="3">&nbsp;</td></tr>
                    <tr>
                        <td>Form Persetujuan</td>
                        <td>:</td>
                        <td>
                            <a href="<?=base_url()?>/uploads/user/<?=$a->username_peminjam?>/pinjaman/<?=$a->form_bukti?>" target="_blank">
                                <i class="fa fa-download"></i> <?= $a->form_bukti ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Slip Gaji</td>
                        <td>:</td>
                        <td>
                            <a href="<?=base_url()?>/uploads/user/<?=$a->username_peminjam?>/pinjaman/<?=$a->slip_gaji?>" target="_blank">
                                <i class="fa fa-download"></i> <?= $a->slip_gaji ?>
                            </a>
                        </td>
                    </tr>
                    <?php if($a->status_pegawai == 'kontrak'){?>
                        <tr>
                            <td>Form Kontrak</td>
                            <td>:</td>
                            <td>
                                <a href="<?=base_url()?>/uploads/user/<?=$a->username_peminjam?>/pinjaman/<?=$a->form_kontrak?>" target="_blank">
                                    <i class="fa fa-download"></i> <?= $a->form_kontrak ?>
                                </a>
                            </td>
                        </tr>
                    <?php }?>
                <?php }?>
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr>
                    <td>Nominal</td>
                    <td>:</td>
                    <td>Rp <?=number_format($a->nominal, 0, '.', ',')?></td>
                </tr>
                <?php if($a->potongan_topup > 0){?>
                    <tr>
                        <td>Saldo Pinjaman</td>
                        <td>:</td>
                        <td>Rp <?=number_format($a->potongan_topup, 0, '.', ',')?></td>
                    </tr>
                    <tr class="fw-bold">
                        <td>Total uang yang harus dipinjamkan</td>
                        <td>:</td>
                        <td>Rp <?=number_format($a->nominal - $a->potongan_topup, 0, '.', ',')?></td>
                    </tr>
                <?php } ?>
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr>
                    <td>Lama Cicilan</td>
                    <td>:</td>
                    <td><?=$a->angsuran_bulanan?> Bulan</td>
                </tr>
            </table>
        </div>
        <div class="mt-3">
            <?=($flag == 1)?'Terima':'Tolak'?> pengajuan pinjaman ini?<br>
            <?php if ($flag == 0) {?>
                <form action="<?= url_to('bendahara_cancel_pinjaman', $a->idpinjaman) ?>" id="formSheet" method="post">
                    <div class="mb-3">
                        <label class="form-label" for="alasan_tolak">Alasan Ditolak</label>
                        <input type="text" class="form-control" id="alasan_tolak" name="alasan_tolak">
                    </div>
                </form>
            <?php }else{?>
                <form action="<?= url_to('bendahara_approve_pinjaman', $a->idpinjaman) ?>" id="formSheet2" enctype="multipart/form-data" method="post">
                    <div class="mb-3">
                        <label class="form-label" for="bukti_tf">Kwitansi/bukti transfer (.jpg)</label>
                        <input type="file" name="bukti_tf" id="bukti_tf" class="form-control" accept=".jpeg, .jpg">
                    </div>
                </form>
            <?php }?>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
    <?php if($flag == 1){?>
        <button type="submit" form="formSheet2" class="btn btn-success">Terima</button>
    <?php }else{?>
        <button type="submit" form="formSheet" class="btn btn-danger">Tolak</button>
    <?php }?>
</div>
