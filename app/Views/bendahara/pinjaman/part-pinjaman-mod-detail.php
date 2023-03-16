<div class="modal-header">
    <h5 class="modal-title" id="myModalLabel">Konfirmasi</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    <div class="mb-3">
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
                <tr><td colspan="3"><hr></td></tr>
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
            <tr><td colspan="3"><hr></td></tr>
            <tr>
                <td>Nominal</td>
                <td>:</td>
                <td>Rp <?=number_format($a->nominal, 0, '.', ',')?></td>
            </tr>
            <tr>
                <td>Cicilan Lunas</td>
                <td>:</td>
                <td>Rp <?=number_format($b->total_lunas, 0, '.', ',')?></td>
            </tr>
            <tr>
                <td>Saldo Pinjaman</td>
                <td>:</td>
                <td>Rp <?=number_format($a->nominal - $b->total_lunas, 0, '.', ',')?></td>
            </tr>
            <tr><td colspan="3"><hr></td></tr>
            <tr>
                <td>Lama Cicilan</td>
                <td>:</td>
                <td><?=$a->angsuran_bulanan?> Kali</td>
            </tr>
            <tr>
                <td>Sudah Lunas</td>
                <td>:</td>
                <td><?=$b->hitung?> Kali</td>
            </tr>
            <tr>
                <td>Sisa Cicilan</td>
                <td>:</td>
                <td><?=$a->angsuran_bulanan - $b->hitung?> Kali</td>
                </tr>
        </table>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
</div>
