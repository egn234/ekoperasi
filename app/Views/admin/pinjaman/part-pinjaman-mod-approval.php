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
    <div class="mb-4">
      <h6 class="fw-semibold mb-3"><i class="fas fa-id-card me-2 text-info"></i>Preview KTP</h6>
      <?php if (!empty($b->ktp_file)) : ?>
        <?php 
        $ktp_path = base_url('uploads/user/' . $b->username . '/ktp/' . $b->ktp_file);
        $ktp_ext = strtolower(pathinfo($b->ktp_file, PATHINFO_EXTENSION));
        ?>
        <div class="card border-0">
          <div class="card-body p-3 text-center">
            <?php if (in_array($ktp_ext, ['jpg', 'jpeg', 'png'])) : ?>
              <img src="<?= $ktp_path ?>" 
                   alt="KTP Thumbnail" 
                   class="img-fluid rounded mb-2" 
                   style="max-height: 120px; border: 1px solid #dee2e6; cursor: pointer;"
                   onclick="previewKTPLoan('<?=$ktp_path?>', '<?=$ktp_ext?>')">
            <?php elseif ($ktp_ext === 'pdf') : ?>
              <div class="mb-2">
                <i class="fas fa-file-pdf text-danger" style="font-size: 48px;"></i>
              </div>
            <?php endif; ?>
            <div>
              <button type="button" class="btn btn-sm btn-primary" onclick="previewKTPLoan('<?=$ktp_path?>', '<?=$ktp_ext?>')">
                <i class="fas fa-eye"></i> Lihat KTP Lengkap
              </button>
              <a href="<?=$ktp_path?>" target="_blank" class="btn btn-sm btn-outline-secondary ms-1">
                <i class="fa fa-download"></i> Download
              </a>
            </div>
          </div>
        </div>
      <?php else : ?>
        <div class="alert alert-warning d-flex align-items-center">
          <i class="fas fa-exclamation-triangle me-2"></i>
          <div>
            <strong>Perhatian!</strong> Tidak ada file KTP yang diunggah.
          </div>
        </div>
      <?php endif; ?>
    </div>

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

<!-- Modal Preview KTP untuk Loan -->
<div class="modal fade" id="ktpPreviewModalLoan" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title"><i class="fas fa-id-card me-2"></i>Preview KTP - Detail Lengkap</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0 position-relative" style="min-height: 400px; background: #f8f9fa;">
        <div id="ktpImageContainerLoan" class="text-center p-3" style="overflow: hidden;">
          <!-- Image will be loaded here -->
        </div>
        <div id="ktpPdfContainerLoan" class="d-none">
          <!-- PDF will be loaded here -->
        </div>
        
        <!-- Zoom Controls -->
        <div id="zoomControlsLoan" class="position-absolute bottom-0 start-50 translate-middle-x mb-3">
          <div class="btn-group shadow-sm" role="group">
            <button type="button" class="btn btn-light btn-sm" onclick="zoomKTPLoan('out')" title="Zoom Out">
              <i class="fas fa-search-minus"></i>
            </button>
            <button type="button" class="btn btn-light btn-sm" onclick="resetZoomKTPLoan()" title="Reset">
              <i class="fas fa-sync-alt"></i> <span id="zoomLevelLoan">100%</span>
            </button>
            <button type="button" class="btn btn-light btn-sm" onclick="zoomKTPLoan('in')" title="Zoom In">
              <i class="fas fa-search-plus"></i>
            </button>
          </div>
        </div>
      </div>
      <div class="modal-footer bg-light">
        <small class="text-muted me-auto"><i class="fas fa-info-circle me-1"></i>Klik dan drag untuk menggeser gambar</small>
      </div>
    </div>
  </div>
</div>

<script>
let currentZoomLoan = 1;
let isDraggingLoan = false;
let startXLoan, startYLoan, scrollLeftLoan, scrollTopLoan;

function previewKTPLoan(path, ext) {
  const modal = new bootstrap.Modal(document.getElementById('ktpPreviewModalLoan'));
  const imageContainer = document.getElementById('ktpImageContainerLoan');
  const pdfContainer = document.getElementById('ktpPdfContainerLoan');
  const zoomControls = document.getElementById('zoomControlsLoan');
  
  // Reset
  imageContainer.innerHTML = '';
  pdfContainer.innerHTML = '';
  currentZoomLoan = 1;
  
  if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
    // Show image
    imageContainer.classList.remove('d-none');
    pdfContainer.classList.add('d-none');
    zoomControls.classList.remove('d-none');
    
    const img = document.createElement('img');
    img.src = path;
    img.id = 'ktpImageLoan';
    img.className = 'img-fluid';
    img.style.cursor = 'move';
    img.style.transition = 'transform 0.2s ease';
    img.style.maxWidth = '100%';
    img.style.height = 'auto';
    
    imageContainer.appendChild(img);
    
    // Enable drag
    imageContainer.style.cursor = 'grab';
    imageContainer.addEventListener('mousedown', startDragLoan);
    imageContainer.addEventListener('mousemove', dragLoan);
    imageContainer.addEventListener('mouseup', stopDragLoan);
    imageContainer.addEventListener('mouseleave', stopDragLoan);
    
  } else if (ext === 'pdf') {
    // Show PDF
    imageContainer.classList.add('d-none');
    pdfContainer.classList.remove('d-none');
    zoomControls.classList.add('d-none');
    
    pdfContainer.innerHTML = `
      <embed src="${path}" type="application/pdf" width="100%" height="600px" />
    `;
  }
  
  modal.show();
}

function zoomKTPLoan(direction) {
  const img = document.getElementById('ktpImageLoan');
  if (!img) return;
  
  if (direction === 'in') {
    currentZoomLoan = Math.min(currentZoomLoan + 0.25, 3);
  } else if (direction === 'out') {
    currentZoomLoan = Math.max(currentZoomLoan - 0.25, 0.5);
  }
  
  img.style.transform = `scale(${currentZoomLoan})`;
  document.getElementById('zoomLevelLoan').textContent = Math.round(currentZoomLoan * 100) + '%';
}

function resetZoomKTPLoan() {
  const img = document.getElementById('ktpImageLoan');
  if (!img) return;
  
  currentZoomLoan = 1;
  img.style.transform = 'scale(1)';
  document.getElementById('zoomLevelLoan').textContent = '100%';
  
  const container = document.getElementById('ktpImageContainerLoan');
  container.scrollLeft = 0;
  container.scrollTop = 0;
}

function startDragLoan(e) {
  isDraggingLoan = true;
  const container = e.currentTarget;
  container.style.cursor = 'grabbing';
  startXLoan = e.pageX - container.offsetLeft;
  startYLoan = e.pageY - container.offsetTop;
  scrollLeftLoan = container.scrollLeft;
  scrollTopLoan = container.scrollTop;
}

function dragLoan(e) {
  if (!isDraggingLoan) return;
  e.preventDefault();
  const container = e.currentTarget;
  const x = e.pageX - container.offsetLeft;
  const y = e.pageY - container.offsetTop;
  const walkX = (x - startXLoan) * 2;
  const walkY = (y - startYLoan) * 2;
  container.scrollLeft = scrollLeftLoan - walkX;
  container.scrollTop = scrollTopLoan - walkY;
}

function stopDragLoan(e) {
  isDraggingLoan = false;
  e.currentTarget.style.cursor = 'grab';
}
</script>