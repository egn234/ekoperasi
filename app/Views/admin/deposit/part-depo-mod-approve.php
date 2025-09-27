<div class="modal-header">
  <h5 class="modal-title" id="myModalLabel">Konfirmasi</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
  <div class="mb-3">
    Terima pengajuan manasuka ini?
    <div class = "row mt-3">
      <?php if ($confirmation) {?>
        <i class="text-danger">*Pemohon tidak mempunyai cukup saldo untuk pengajuan penarikan transaksi ini</i><br>
        <i class="text-danger">**Saldo Pemohon saat ini: Rp <?= number_format($total_saldo, 2, ',', '.')?></i>
      <?php } ?>

      <?php if ($a->jenis_pengajuan == "penyimpanan") {?>
        <p>
          Bukti Transfer: 
          <a href="<?=base_url()?>/uploads/user/<?=$duser->username?>/tf/<?=$a->bukti_transfer?>" target="_blank">
            <?=$a->bukti_transfer?> <i class="fa fa-external-link-alt"></i>
          </a>
        </p>
      <?php } ?>

      <form action="<?= url_to('admin_konfirmasi_simpanan', $a->iddeposit)?>" id="formTerima" method="post">
        <div class="mb-3">
          <label class="form-label" for="nominal_uang">Nominal</label>
          <input 
            type="number"
            step="0.01"
            class="form-control"
            id="nominal_uang"
            name="nominal_uang"
            value="<?=number_format(($a->jenis_pengajuan == "penyimpanan") ? $a->cash_in : $a->cash_out, 0, '', '')?>"
          >
          <small id="preview_nominal" class="form-text text-muted"></small>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
  <button type="submit" form="formTerima" class="btn btn-success">Terima</button>
</div>