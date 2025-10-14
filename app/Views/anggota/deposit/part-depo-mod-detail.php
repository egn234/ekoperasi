<div class="modal-header border-0 pb-0">
  <div class="d-flex align-items-center">
    <div class="me-3">
      <div class="transaction-icon-modal rounded-circle d-flex align-items-center justify-content-center" 
           style="width: 48px; height: 48px; <?= $a->cash_in == 0 ? 'background-color: #fee2e2; color: #dc2626;' : 'background-color: #dcfce7; color: #16a34a;' ?>">
        <?php if ($a->cash_in == 0) : ?>
          <i class="fas fa-arrow-up"></i>
        <?php else : ?>
          <i class="fas fa-arrow-down"></i>
        <?php endif; ?>
      </div>
    </div>
    <div>
      <h5 class="modal-title mb-1">Detail Transaksi</h5>
      <p class="text-muted small mb-0"><?= ucwords($a->jenis_pengajuan . ' ' . $a->jenis_deposit) ?></p>
    </div>
  </div>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body pt-4">
  <!-- Transaction Summary Card -->
  <div class="card border-0 bg-light mb-4">
    <div class="card-body text-center py-4">
      <div class="transaction-amount">
        <?php if ($a->cash_in == 0) : ?>
          <div class="text-danger">
            <div class="h4 fw-bold mb-1">- Rp <?= number_format($a->cash_out, 0, ',', '.') ?></div>
            <small class="text-muted">Dana Keluar</small>
          </div>
        <?php else : ?>
          <div class="text-success">
            <div class="h4 fw-bold mb-1">+ Rp <?= number_format($a->cash_in, 0, ',', '.') ?></div>
            <small class="text-muted">Dana Masuk</small>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Transaction Details -->
  <div class="transaction-details">
    <div class="detail-item mb-3">
      <div class="d-flex justify-content-between align-items-start">
        <div class="detail-label">
          <i class="fas fa-tag me-2 text-muted"></i>
          <span class="fw-medium">Jenis Transaksi</span>
        </div>
        <div class="detail-value text-end">
          <span class="text-dark"><?= ucwords($a->jenis_pengajuan . ' ' . $a->jenis_deposit) ?></span>
        </div>
      </div>
    </div>

    <?php if ($a->deskripsi) : ?>
    <div class="detail-item mb-3">
      <div class="d-flex justify-content-between align-items-start">
        <div class="detail-label">
          <i class="fas fa-file-alt me-2 text-muted"></i>
          <span class="fw-medium">Deskripsi</span>
        </div>
        <div class="detail-value text-end">
          <span class="text-dark"><?= $a->deskripsi ?></span>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($a->jenis_deposit == "manasuka" || $a->jenis_deposit == "manasuka free") : ?>
    <div class="detail-item mb-3">
      <div class="d-flex justify-content-between align-items-center">
        <div class="detail-label">
          <i class="fas fa-info-circle me-2 text-muted"></i>
          <span class="fw-medium">Status</span>
        </div>
        <div class="detail-value">
          <span class="badge rounded-pill <?= 
            $a->status == 'diproses' ||
            $a->status == 'diproses bendahara' ||
            $a->status == 'diproses admin' ||
            $a->status == 'upload bukti' ? 'bg-warning' 
            : ($a->status == 'diterima' ? 'bg-success' 
            : 'bg-danger') 
          ?>">
            <?= ucwords($a->status) ?>
          </span>
        </div>
      </div>
    </div>

    <?php if($a->status == 'diterima') : ?>
    <div class="detail-item mb-3">
      <div class="d-flex justify-content-between align-items-start">
        <div class="detail-label">
          <i class="fas fa-user-check me-2 text-muted"></i>
          <span class="fw-medium">Diproses oleh</span>
        </div>
        <div class="detail-value text-end">
          <span class="text-dark"><?= $a->nama_admin ?></span>
        </div>
      </div>
    </div>
    <?php elseif($a->status == 'ditolak') : ?>
    <div class="detail-item mb-3">
      <div class="alert alert-danger py-2 mb-0">
        <div class="d-flex align-items-start">
          <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
          <div>
            <strong class="small">Alasan Ditolak:</strong>
            <div class="mt-1"><?= $a->alasan_tolak ?></div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <?php if($a->jenis_deposit == 'manasuka free' && $a->jenis_pengajuan == 'penyimpanan') : ?>
    <div class="detail-item mb-3">
      <div class="d-flex justify-content-between align-items-start">
        <div class="detail-label">
          <i class="fas fa-receipt me-2 text-muted"></i>
          <span class="fw-medium">Bukti Transfer</span>
        </div>
        <div class="detail-value text-end">
          <?php if(!$a->bukti_transfer) : ?>
            <span class="text-warning">
              <i class="fas fa-clock me-1"></i>Belum diunggah
            </span>
          <?php else : ?>
            <a href="<?=base_url()?>/uploads/user/<?=$duser->username?>/tf/<?=$a->bukti_transfer?>" 
               target="_blank" 
               class="btn btn-outline-primary btn-sm">
              <i class="fas fa-external-link-alt me-1"></i>Lihat Bukti
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
  </div>
</div>
<div class="modal-footer border-0 pt-0">
  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
    <i class="fas fa-times me-2"></i>Tutup
  </button>
</div>
