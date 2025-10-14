<div class="modal-header bg-gradient-success text-white border-0">
  <h5 class="modal-title text-white d-flex align-items-center" id="myModalLabel">
    <i class="fas fa-check-circle me-2"></i>Konfirmasi Persetujuan
  </h5>
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body p-0">
  <!-- Main Question -->
  <div class="px-4 py-4">
    <div class="text-center mb-4">
      <div class="mb-3">
        <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center" 
             style="width: 60px; height: 60px; font-size: 24px;">
          <i class="fas fa-question"></i>
        </div>
      </div>
      <h6 class="mb-2">Terima Pengajuan Manasuka?</h6>
      <p class="text-muted mb-0">Pastikan semua data telah sesuai sebelum menyetujui</p>
    </div>

    <!-- Warnings -->
    <?php if ($confirmation) : ?>
      <div class="alert alert-danger d-flex align-items-start mb-3">
        <div class="me-2">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div>
          <strong>Peringatan Saldo!</strong><br>
          <small>Pemohon tidak mempunyai cukup saldo untuk pengajuan penarikan transaksi ini</small><br>
          <small><strong>Saldo Pemohon saat ini:</strong> Rp <?= number_format($total_saldo, 0, ',', '.')?></small>
        </div>
      </div>
    <?php endif; ?>

    <!-- Bukti Transfer -->
    <?php if ($a->jenis_pengajuan == "penyimpanan") : ?>
      <div class="card bg-light border-0 mb-3">
        <div class="card-body p-3">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <h6 class="mb-1">Bukti Transfer</h6>
              <small class="text-muted">Dokumen yang diunggah pemohon</small>
            </div>
            <div>
              <a href="<?=base_url()?>/uploads/user/<?=$duser->username?>/tf/<?=$a->bukti_transfer?>" 
                 target="_blank" 
                 class="btn btn-outline-primary btn-sm">
                <i class="fas fa-file-image me-1"></i>Lihat Bukti
                <i class="fas fa-external-link-alt ms-1"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Form -->
    <form action="<?= url_to('admin_konfirmasi_simpanan', $a->iddeposit)?>" id="formTerima" method="post">
      <div class="card border-0 bg-light">
        <div class="card-body">
          <label class="form-label fw-semibold" for="nominal_uang">
            <i class="fas fa-money-bill-wave me-2 text-success"></i>Nominal Transaksi
          </label>
          <div class="input-group">
            <span class="input-group-text bg-white">Rp</span>
            <input 
              type="number"
              step="0.01"
              class="form-control"
              id="nominal_uang"
              name="nominal_uang"
              value="<?=number_format(($a->jenis_pengajuan == "penyimpanan") ? $a->cash_in : $a->cash_out, 0, '', '')?>"
              placeholder="Masukkan nominal"
            >
          </div>
          <div id="preview_nominal" class="form-text text-muted mt-2">
            <i class="fas fa-calculator me-1"></i>Preview akan muncul saat Anda mengetik
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<div class="modal-footer border-top-0 bg-light">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
    <i class="fas fa-times me-1"></i>Batal
  </button>
  <button type="submit" form="formTerima" class="btn btn-success">
    <i class="fas fa-check me-1"></i>Terima Pengajuan
  </button>
</div>