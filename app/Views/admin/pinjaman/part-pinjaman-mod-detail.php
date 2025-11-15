<div class="modal-header bg-gradient-primary text-white border-0">
  <h5 class="modal-title text-white d-flex align-items-center" id="myModalLabel">
    <i class="fas fa-file-contract me-2"></i>Detail Pengajuan Pinjaman
  </h5>
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body p-0">
  <!-- Member Info Header -->
  <div class="bg-light border-bottom px-4 py-3">
    <div class="d-flex align-items-center">
      <div class="me-3">
        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" 
             style="width: 45px; height: 45px;">
          <i class="fas fa-user"></i>
        </div>
      </div>
      <div>
        <h6 class="mb-0 fw-semibold"><?=$a->nama_peminjam?></h6>
        <small class="text-muted">Anggota Koperasi</small>
      </div>
    </div>
  </div>
  
  <!-- Transaction Details -->
  <div class="px-4 py-3">
    <div class="row g-3">
      <div class="col-12">
        <div class="detail-item d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted">Jenis Permohonan</span>
          <span class="fw-medium text-capitalize"><?=$a->tipe_permohonan?></span>
        </div>
      </div>
      
      <div class="col-12">
        <div class="detail-item d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted">Tanggal Pengajuan</span>
          <span class="fw-medium"><?=$a->date_created?></span>
        </div>
      </div>
      
      <div class="col-12">
        <div class="detail-item d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted">Deskripsi</span>
          <span class="fw-medium text-end" style="max-width: 250px;"><?=$a->deskripsi?></span>
        </div>
      </div>
      
      <div class="col-12">
        <div class="detail-item d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted">Status</span>
          <span class="fw-medium">
            <?php 
              $status_badge = '';
              switch($a->status) {
                case 0:
                  $status_badge = '<span class="badge bg-danger">Ditolak</span>';
                  break;
                case 1:
                  $status_badge = '<span class="badge bg-warning">Upload Kelengkapan Form</span>';
                  break;
                case 2:
                  $status_badge = '<span class="badge bg-info">Menunggu Verifikasi</span>';
                  break;
                case 3:
                  $status_badge = '<span class="badge bg-secondary">Menunggu Approval Sekretariat</span>';
                  break;
                case 4:
                  $status_badge = '<span class="badge bg-primary">Sedang Berlangsung</span>';
                  break;
                case 5:
                  $status_badge = '<span class="badge bg-success">Lunas</span>';
                  break;
                case 6:
                  $status_badge = '<span class="badge bg-warning">Konfirmasi Pelunasan</span>';
                  break;
                default:
                  $status_badge = '<span class="badge bg-secondary">Unknown</span>';
              }
              echo $status_badge;
            ?>
          </span>
        </div>
      </div>
      
      <?php if($a->status == '0') : ?>
        <div class="col-12">
          <div class="detail-item d-flex justify-content-between py-2 border-bottom">
            <span class="text-muted">Alasan Ditolak</span>
            <span class="fw-medium text-danger text-end" style="max-width: 250px;"><?=$a->alasan_tolak?></span>
          </div>
        </div>
      <?php endif; ?>
      
      <!-- Documents Section -->
      <?php if($a->status > 1) : ?>
        <div class="col-12">
          <div class="mt-3">
            <h6 class="fw-semibold mb-3"><i class="fas fa-folder-open me-2 text-primary"></i>Dokumen Pendukung</h6>
            <div class="row g-2">
              <div class="col-12">
                <div class="card bg-light border-0">
                  <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <h6 class="mb-1">Form Persetujuan</h6>
                        <small class="text-muted">Dokumen persetujuan pinjaman</small>
                      </div>
                      <div>
                        <a href="<?=base_url()?>/uploads/user/<?=$a->username_peminjam?>/pinjaman/<?=$a->form_bukti?>" 
                           target="_blank" 
                           class="btn btn-outline-primary btn-sm">
                          <i class="fas fa-file-alt me-1"></i>Lihat Dokumen
                          <i class="fas fa-external-link-alt ms-1"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="col-12">
                <div class="card bg-light border-0">
                  <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <h6 class="mb-1">Slip Gaji</h6>
                        <small class="text-muted">Bukti penghasilan peminjam</small>
                      </div>
                      <div>
                        <a href="<?=base_url()?>/uploads/user/<?=$a->username_peminjam?>/pinjaman/<?=$a->slip_gaji?>" 
                           target="_blank" 
                           class="btn btn-outline-primary btn-sm">
                          <i class="fas fa-file-invoice me-1"></i>Lihat Slip
                          <i class="fas fa-external-link-alt ms-1"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <?php if($a->status_pegawai == 'kontrak') : ?>
                <div class="col-12">
                  <div class="card bg-light border-0">
                    <div class="card-body p-3">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <h6 class="mb-1">Form Kontrak</h6>
                          <small class="text-muted">Kontrak kerja pegawai</small>
                        </div>
                        <div>
                          <a href="<?=base_url()?>/uploads/user/<?=$a->username_peminjam?>/pinjaman/<?=$a->form_kontrak?>" 
                             target="_blank" 
                             class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-file-contract me-1"></i>Lihat Kontrak
                            <i class="fas fa-external-link-alt ms-1"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      <?php endif; ?>
      
      <!-- Financial Details -->
      <div class="col-12">
        <div class="mt-3">
          <h6 class="fw-semibold mb-3"><i class="fas fa-money-bill-wave me-2 text-success"></i>Detail Keuangan</h6>
        </div>
      </div>
      
      <div class="col-12">
        <div class="detail-item d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted">Nominal Pinjaman</span>
          <span class="fw-bold text-primary">Rp <?=number_format($a->nominal, 0, ',', '.')?></span>
        </div>
      </div>
      
      <?php if($a->status == '4') : ?>
        <div class="col-12">
          <div class="detail-item d-flex justify-content-between py-2 border-bottom">
            <span class="text-muted">Cicilan Lunas</span>
            <span class="fw-medium text-success">Rp <?=number_format($b->total_lunas, 0, ',', '.')?></span>
          </div>
        </div>
        
        <div class="col-12">
          <div class="detail-item d-flex justify-content-between py-2 border-bottom">
            <span class="text-muted">Saldo Pinjaman</span>
            <span class="fw-medium text-warning">Rp <?=number_format($a->nominal - $b->total_lunas, 0, ',', '.')?></span>
          </div>
        </div>
      <?php endif; ?>
      
      <div class="col-12">
        <div class="detail-item d-flex justify-content-between py-2 border-bottom">
          <span class="text-muted">Lama Cicilan</span>
          <span class="fw-medium"><?=$a->angsuran_bulanan?> Kali</span>
        </div>
      </div>
      
      <?php if($a->status == '4') : ?>
        <div class="col-12">
          <div class="detail-item d-flex justify-content-between py-2 border-bottom">
            <span class="text-muted">Sudah Lunas</span>
            <span class="fw-medium text-success"><?=$b->hitung?> Kali</span>
          </div>
        </div>
        
        <div class="col-12">
          <div class="detail-item d-flex justify-content-between py-2">
            <span class="text-muted">Sisa Cicilan</span>
            <span class="fw-medium text-warning"><?=$a->angsuran_bulanan - $b->hitung?> Kali</span>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
<div class="modal-footer border-top-0 bg-light">
  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
    <i class="fas fa-times me-1"></i>Tutup
  </button>
</div>
