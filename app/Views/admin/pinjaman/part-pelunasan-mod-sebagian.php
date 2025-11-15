<div class="modal-body p-0">
  <!-- Main Content -->
  <div class="px-4 py-4">
    <div class="text-center mb-4">
      <div class="mb-3">
        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
             style="width: 60px; height: 60px; font-size: 24px;">
          <i class="fas fa-coins"></i>
        </div>
      </div>
      <h6 class="mb-2">Hitung Pelunasan Sebagian</h6>
      <p class="text-muted mb-0">Masukkan jumlah cicilan yang akan dilunasi</p>
    </div>

    <!-- Loan Summary -->
    <div class="card border-0 bg-light mb-4">
      <div class="card-body p-3">
        <h6 class="card-title mb-3"><i class="fas fa-info-circle me-2 text-primary"></i>Informasi Pinjaman</h6>
        <div class="row g-2">
          <div class="col-6">
            <div class="d-flex justify-content-between py-1">
              <span class="text-muted small">Lama Cicilan:</span>
              <span class="fw-medium small"><?= $pinjaman->angsuran_bulanan ?> Bulan</span>
            </div>
          </div>
          <div class="col-6">
            <div class="d-flex justify-content-between py-1">
              <span class="text-muted small">Sisa Cicilan:</span>
              <span class="fw-medium small"><?= $sisa_cicilan ?> Bulan</span>
            </div>
          </div>
          <div class="col-12">
            <div class="d-flex justify-content-between py-1 border-top pt-2 mt-2">
              <span class="text-muted">Jumlah Pinjaman:</span>
              <span class="fw-bold text-primary">Rp <?= number_format($pinjaman->nominal, 0, ',', '.') ?></span>
            </div>
          </div>
          <div class="col-12">
            <div class="d-flex justify-content-between py-1">
              <span class="text-muted">Sisa Pinjaman:</span>
              <span class="fw-bold text-warning">Rp <?= number_format($sisa_pinjaman, 0, ',', '.')?></span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Form -->
    <form action="<?=base_url()?>admin/pinjaman/lunasi-partial/<?=$idpinjaman?>" 
          id="pelunasan_partial_<?=$idpinjaman?>" 
          method="post" 
          enctype="multipart/form-data">
      <div class="card border-0 bg-light">
        <div class="card-body">
          <label class="form-label fw-semibold" for="bulan_<?=$idpinjaman?>">
            <i class="fas fa-calendar-alt me-2 text-success"></i>Jumlah Cicilan yang Dilunasi
          </label>
          <div class="input-group">
            <input 
              type="number" 
              class="form-control" 
              id="bulan_<?=$idpinjaman?>" 
              name="bulan_bayar" 
              min="1" 
              max="<?=$sisa_cicilan?>" 
              placeholder="Masukkan jumlah cicilan"
              required
            >
            <span class="input-group-text bg-white">Bulan</span>
          </div>
          <div class="form-text text-muted">
            <i class="fas fa-info-circle me-1"></i>Maksimal <?=$sisa_cicilan?> bulan sesuai sisa cicilan
          </div>
        </div>
      </div>
    </form>

    <!-- Calculation Result -->
    <div class="card border-0 bg-success bg-opacity-10 mt-3">
      <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h6 class="mb-1 text-success"><i class="fas fa-calculator me-2"></i>Total Pembayaran</h6>
            <small class="text-muted">Akan diperbarui otomatis saat Anda mengetik</small>
          </div>
          <div class="text-end">
            <h5 class="mb-0 fw-bold text-success">Rp <span id="perkalian_<?=$idpinjaman?>">0</span></h5>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal-footer border-top-0 bg-light">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
    <i class="fas fa-times me-1"></i>Batal
  </button>
  <button type="submit" form="pelunasan_partial_<?=$idpinjaman?>" class="btn btn-success">
    <i class="fas fa-check me-1"></i>Lunasi Sebagian
  </button>
</div>

<script type="text/javascript">
    $('#bulan_<?=$idpinjaman?>').on("input", function() {
        // Get the value of the input
        var value = parseFloat($('#bulan_<?=$idpinjaman?>').val()); // Parse input as a floating-point number
        console.log(value); // Debug: Check if value is correctly extracted

        // Perform the calculation
        var calculatedResult = value * <?= $nominal_cicilan ?>;

        // Update the text element with the calculated result
        $('#perkalian_<?=$idpinjaman?>').text(numberFormat(calculatedResult, 0));
    });
</script>
