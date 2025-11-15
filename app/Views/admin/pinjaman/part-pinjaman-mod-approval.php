<div class="modal-body p-0">
  <!-- Main Question -->
  <div class="px-4 py-4">
    <div class="text-center mb-4">
      <div class="mb-3">
        <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center" 
             style="width: 60px; height: 60px; font-size: 24px;">
          <i class="fas fa-handshake"></i>
        </div>
      </div>
      <h6 class="mb-2">Setujui Pengajuan Pinjaman?</h6>
      <p class="text-muted mb-0">Pastikan semua dokumen telah diverifikasi dengan benar</p>
    </div>

    <!-- Loan Details Summary -->
    <div class="card border-0 bg-light mb-4">
      <div class="card-body p-3">
        <h6 class="card-title mb-3"><i class="fas fa-info-circle me-2 text-primary"></i>Ringkasan Pinjaman</h6>
        <div class="row g-2">
          <div class="col-6">
            <div class="d-flex justify-content-between py-1">
              <span class="text-muted small">Peminjam:</span>
              <span class="fw-medium small"><?=$a->nama_peminjam?></span>
            </div>
          </div>
          <div class="col-6">
            <div class="d-flex justify-content-between py-1">
              <span class="text-muted small">Jenis:</span>
              <span class="fw-medium small"><?=$a->tipe_permohonan?></span>
            </div>
          </div>
          <div class="col-6">
            <div class="d-flex justify-content-between py-1">
              <span class="text-muted small">Tanggal:</span>
              <span class="fw-medium small"><?=$a->date_created?></span>
            </div>
          </div>
          <div class="col-6">
            <div class="d-flex justify-content-between py-1">
              <span class="text-muted small">Cicilan:</span>
              <span class="fw-medium small"><?=$a->angsuran_bulanan?> Bulan</span>
            </div>
          </div>
          <div class="col-12">
            <div class="d-flex justify-content-between py-1 border-top pt-2 mt-2">
              <span class="text-muted">Deskripsi:</span>
              <span class="fw-medium text-end" style="max-width: 200px;"><?=$a->deskripsi?></span>
            </div>
          </div>
          <div class="col-12">
            <div class="d-flex justify-content-between py-1 border-top pt-2 mt-2">
              <span class="text-muted">Nominal:</span>
              <span class="fw-bold text-primary">Rp <?=number_format($a->nominal, 0, ',', '.')?></span>
            </div>
          </div>
          <?php if($a->potongan_topup > 0) : ?>
            <div class="col-12">
              <div class="alert alert-info p-2 mb-0 mt-2">
                <small>
                  <strong>Info:</strong> Terdapat potongan saldo pinjaman sebelumnya<br>
                  Saldo Pinjaman: Rp <?=number_format($a->potongan_topup, 0, ',', '.')?><br>
                  <strong>Total yang dipinjamkan: Rp <?=number_format($a->nominal - $a->potongan_topup, 0, ',', '.')?></strong>
                </small>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Documents Review -->
    <?php if($a->status > 1) : ?>
      <div class="mb-4">
        <h6 class="fw-semibold mb-3"><i class="fas fa-folder-open me-2 text-primary"></i>Dokumen yang Diunggah</h6>
        <div class="row g-2">
          <div class="col-md-4">
            <div class="card bg-light border-0 h-100">
              <div class="card-body p-2 text-center">
                <i class="fas fa-file-alt text-primary mb-1"></i>
                <div class="small">
                  <a href="<?=base_url()?>/uploads/user/<?=$a->username_peminjam?>/pinjaman/<?=$a->form_bukti?>" 
                     target="_blank" 
                     class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-external-link-alt me-1"></i>Form Persetujuan
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card bg-light border-0 h-100">
              <div class="card-body p-2 text-center">
                <i class="fas fa-file-invoice text-success mb-1"></i>
                <div class="small">
                  <a href="<?=base_url()?>/uploads/user/<?=$a->username_peminjam?>/pinjaman/<?=$a->slip_gaji?>" 
                     target="_blank" 
                     class="btn btn-outline-success btn-sm">
                    <i class="fas fa-external-link-alt me-1"></i>Slip Gaji
                  </a>
                </div>
              </div>
            </div>
          </div>
          <?php if($a->status_pegawai == 'kontrak') : ?>
            <div class="col-md-4">
              <div class="card bg-light border-0 h-100">
                <div class="card-body p-2 text-center">
                  <i class="fas fa-file-contract text-warning mb-1"></i>
                  <div class="small">
                    <a href="<?=base_url()?>/uploads/user/<?=$a->username_peminjam?>/pinjaman/<?=$a->form_kontrak?>" 
                       target="_blank" 
                       class="btn btn-outline-warning btn-sm">
                      <i class="fas fa-external-link-alt me-1"></i>Kontrak Kerja
                    </a>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- KTP Preview -->
    <?php if (!empty($b->ktp_file)) : ?>
      <div class="mb-4">
        <h6 class="fw-semibold mb-3"><i class="fas fa-id-card me-2 text-info"></i>Preview KTP</h6>
        <div class="card border-0">
          <div class="card-body p-3">
            <?php if (in_array(pathinfo($b->ktp_file, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png'])) : ?>
              <div class="text-center">
                <img src="<?= base_url('uploads/user/' . $b->username . '/ktp/' . $b->ktp_file) ?>" 
                     alt="KTP Preview" 
                     class="img-fluid rounded" 
                     style="max-height: 200px; border: 1px solid #dee2e6;">
              </div>
            <?php elseif (pathinfo($b->ktp_file, PATHINFO_EXTENSION) === 'pdf') : ?>
              <div class="text-center">
                <embed src="<?= base_url('uploads/user/' . $b->username . '/ktp/' . $b->ktp_file) ?>" 
                       type="application/pdf" 
                       width="100%" 
                       height="300px" 
                       class="rounded">
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php else : ?>
      <div class="mb-4">
        <div class="alert alert-warning d-flex align-items-center">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <div>
            <strong>Perhatian!</strong> Tidak ada file KTP yang diunggah.
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Form -->
    <form action="<?= url_to('admin_approve_pinjaman', $a->idpinjaman)?>"
          id="formTerima" 
          method="post">
      <div class="card border-0 bg-light">
        <div class="card-body">
          <label class="form-label fw-semibold" for="nominal_uang">
            <i class="fas fa-money-bill-wave me-2 text-success"></i>Nominal Pinjaman
          </label>
          <div class="input-group">
            <span class="input-group-text bg-white">Rp</span>
            <input 
              type="number"
              step="0.01"
              class="form-control"
              id="nominal_uang"
              name="nominal_uang"
              value="<?= number_format($a->nominal, 0, '', '') ?>"
              placeholder="Masukkan nominal pinjaman"
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
    <i class="fas fa-check me-1"></i>Setujui Pinjaman
  </button>
</div>