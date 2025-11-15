<div class="modal-body p-0">
  <!-- Main Question -->
  <div class="px-4 py-4">
    <div class="text-center mb-4">
      <div class="mb-3">
        <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center" 
             style="width: 60px; height: 60px; font-size: 24px;">
          <i class="fas fa-money-check-alt"></i>
        </div>
      </div>
      <h6 class="mb-2">Setujui Pelunasan Pinjaman?</h6>
      <p class="text-muted mb-0">Untuk peminjam: <strong><?= $user[0]['username'] ?></strong></p>
    </div>

    <!-- Important Warning -->
    <div class="alert alert-warning d-flex align-items-start mb-3">
      <div class="me-2">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <div>
        <strong>Perhatian Penting!</strong><br>
        <small>Pastikan Anda telah mengecek kembali ketersediaan approval dari peminjam sebelum menyetujui pelunasan ini.</small>
      </div>
    </div>

    <div class="card border-0 bg-light">
      <div class="card-body p-3">
        <h6 class="card-title mb-2"><i class="fas fa-info-circle me-2 text-primary"></i>Informasi</h6>
        <p class="mb-0 text-muted small">
          Tindakan ini akan menandai pinjaman sebagai lunas dan mengubah status menjadi selesai. Pastikan semua pembayaran telah diterima dengan benar.
        </p>
      </div>
    </div>
  </div>
</div>
<div class="modal-footer border-top-0 bg-light">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
    <i class="fas fa-times me-1"></i>Batal
  </button>
  <a href="<?= url_to('admin_konfirmasi_lunas', $idpinjaman)?>" class="btn btn-success">
    <i class="fas fa-check me-1"></i>Setujui Pelunasan
  </a>
</div>
