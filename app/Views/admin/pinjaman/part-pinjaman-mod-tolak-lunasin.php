<div class="modal-body p-0">
  <!-- Main Question -->
  <div class="px-4 py-4">
    <div class="text-center mb-4">
      <div class="mb-3">
        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center" 
             style="width: 60px; height: 60px; font-size: 24px;">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
      </div>
      <h6 class="mb-2">Tolak Pelunasan Pinjaman?</h6>
      <p class="text-muted mb-0">Untuk peminjam: <strong><?= $user[0]['username'] ?></strong></p>
    </div>

    <!-- Important Warning -->
    <div class="alert alert-warning d-flex align-items-start mb-3">
      <div class="me-2">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <div>
        <strong>Perhatian Penting!</strong><br>
        <small>Pastikan Anda telah mengecek kembali ketersediaan approval dari peminjam sebelum menolak pelunasan ini.</small>
      </div>
    </div>

    <div class="card border-0 bg-light">
      <div class="card-body p-3">
        <h6 class="card-title mb-2"><i class="fas fa-info-circle me-2 text-primary"></i>Informasi</h6>
        <p class="mb-0 text-muted small">
          Tindakan ini akan menolak pengajuan pelunasan dan pinjaman akan tetap dalam status aktif. Pastikan keputusan ini sudah tepat.
        </p>
      </div>
    </div>
  </div>
</div>
<div class="modal-footer border-top-0 bg-light">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
    <i class="fas fa-arrow-left me-1"></i>Kembali
  </button>
  <a href="<?= url_to('admin_tolak_lunas', $idpinjaman)?>" class="btn btn-danger">
    <i class="fas fa-times me-1"></i>Tolak Pelunasan
  </a>
</div>
