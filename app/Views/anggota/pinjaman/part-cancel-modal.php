<div class="modal-header bg-danger text-white">
    <h5 class="modal-title"><i class="mdi mdi-cancel me-2"></i>Batalkan Pengajuan Pinjaman</h5>
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<form action="<?= base_url('anggota/pinjaman/cancel-proc/'.$pinjaman->idpinjaman) ?>" method="POST">
    <div class="modal-body">
        <div class="alert alert-warning" role="alert">
            <i class="mdi mdi-alert-outline me-2"></i>
            <strong>Perhatian!</strong> Tindakan ini tidak dapat dibatalkan. Pengajuan pinjaman akan dipindahkan ke riwayat penolakan.
        </div>
        
        <div class="mb-3">
            <h6 class="mb-3">Detail Pinjaman yang akan dibatalkan:</h6>
            <div class="card border">
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td width="40%" class="text-muted">Tanggal Pengajuan</td>
                            <td width="5%">:</td>
                            <td><strong><?= date('d/m/Y H:i', strtotime($pinjaman->date_created)) ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tipe Permohonan</td>
                            <td>:</td>
                            <td><strong><?= $pinjaman->tipe_permohonan ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Nominal</td>
                            <td>:</td>
                            <td><strong class="text-primary">Rp <?= number_format($pinjaman->nominal, 0, ',', '.') ?></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Lama Angsuran</td>
                            <td>:</td>
                            <td><strong><?= $pinjaman->angsuran_bulanan ?> bulan</strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status Saat Ini</td>
                            <td>:</td>
                            <td>
                                <?php
                                $statusLabel = '';
                                switch($pinjaman->status) {
                                    case 1:
                                        $statusLabel = '<span class="badge bg-warning">Upload Kelengkapan Form</span>';
                                        break;
                                    case 2:
                                        $statusLabel = '<span class="badge bg-info">Menunggu Verifikasi</span>';
                                        break;
                                    case 3:
                                        $statusLabel = '<span class="badge bg-primary">Menunggu Approval Sekretariat</span>';
                                        break;
                                }
                                echo $statusLabel;
                                ?>
                            </td>
                        </tr>
                        <?php if (!empty($pinjaman->deskripsi)): ?>
                        <tr>
                            <td class="text-muted">Deskripsi</td>
                            <td>:</td>
                            <td><?= $pinjaman->deskripsi ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>

        <div class="alert alert-danger mb-0" role="alert">
            <i class="mdi mdi-help-circle-outline me-2"></i>
            <strong>Apakah Anda yakin ingin membatalkan pengajuan pinjaman ini?</strong>
            <p class="mb-0 mt-2 small">Data pinjaman akan disimpan di riwayat penolakan dengan keterangan "Dibatalkan oleh anggota".</p>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="mdi mdi-arrow-left me-1"></i>Tidak, Kembali
        </button>
        <button type="submit" class="btn btn-danger">
            <i class="mdi mdi-cancel me-1"></i>Ya, Batalkan Pinjaman
        </button>
    </div>
</form>
