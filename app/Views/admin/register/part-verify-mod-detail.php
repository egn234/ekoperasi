<div class="modal-header">
  <h5 class="modal-title" id="myModalLabel">Verifikasi Anggota Baru</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
  <div class="mb-3">
    <table width="100%">
      <tr>
        <td>Nama</td>
        <td>:</td>
        <td><?=$a->nama_lengkap?></td>
      </tr>
      <tr>
        <td>NIK</td>
        <td>:</td>
        <td><?=$a->nik?></td>
      </tr>
      <tr>
        <td>NIP</td>
        <td>:</td>
        <td><?=$a->nip ? $a->nip : '-'?></td>
      </tr>
      <tr>
        <td>Tempat, Tanggal Lahir</td>
        <td>:</td>
        <td><?=$a->tempat_lahir?>, <?=$a->tanggal_lahir?></td>
      </tr>
      <tr>
        <td>Alamat</td>
        <td>:</td>
        <td><?=$a->alamat?></td>
      </tr>
      <tr>
        <td>Instansi</td>
        <td>:</td>
        <td><?=$a->instansi?></td>
      </tr>
      <tr>
        <td>Unit Kerja</td>
        <td>:</td>
        <td><?=$a->unit_kerja?></td>
      </tr>
      <tr>
        <td>Status Pegawai</td>
        <td>:</td>
        <td><?=$a->status_pegawai == 'kontrak' ? 'Kontrak' : 'Tetap'?></td>
      </tr>
      <tr>
        <td>No. Telepon</td>
        <td>:</td>
        <td><?=$a->nomor_telepon?></td>
      </tr>
      <tr>
        <td>Email</td>
        <td>:</td>
        <td><?=$a->email?></td>
      </tr>
      <tr>
        <td>KTP</td>
        <td>:</td>
        <td>
          <?php if (!empty($a->ktp_file)) : ?>
            <?php 
            $ktp_path = base_url('uploads/user/' . $a->username . '/ktp/' . $a->ktp_file);
            $ktp_ext = strtolower(pathinfo($a->ktp_file, PATHINFO_EXTENSION));
            ?>
            <button type="button" class="btn btn-sm btn-primary" onclick="previewKTP('<?=$ktp_path?>', '<?=$ktp_ext?>')">
              <i class="fas fa-eye"></i> Lihat KTP
            </button>
            <a href="<?=$ktp_path?>" target="_blank" class="btn btn-sm btn-outline-secondary ms-1">
              <i class="fa fa-download"></i>
            </a>
          <?php else : ?>
            <span class="text-muted">Tidak ada file</span>
          <?php endif; ?>
        </td>
      </tr>
      
    </table>
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
  <a href="<?=url_to('admin_verify_user', $a->iduser)?>" class="btn btn-success">Verifikasi Anggota</a>
</div>

<!-- Modal Preview KTP -->
<div class="modal fade" id="ktpPreviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="fas fa-id-card me-2"></i>Preview KTP</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0 position-relative" style="min-height: 400px; background: #f8f9fa;">
        <div id="ktpImageContainer" class="text-center p-3" style="overflow: hidden;">
          <!-- Image will be loaded here -->
        </div>
        <div id="ktpPdfContainer" class="d-none">
          <!-- PDF will be loaded here -->
        </div>
        
        <!-- Zoom Controls -->
        <div id="zoomControls" class="position-absolute bottom-0 start-50 translate-middle-x mb-3">
          <div class="btn-group shadow-sm" role="group">
            <button type="button" class="btn btn-light btn-sm" onclick="zoomKTP('out')" title="Zoom Out">
              <i class="fas fa-search-minus"></i>
            </button>
            <button type="button" class="btn btn-light btn-sm" onclick="resetZoomKTP()" title="Reset">
              <i class="fas fa-sync-alt"></i> <span id="zoomLevel">100%</span>
            </button>
            <button type="button" class="btn btn-light btn-sm" onclick="zoomKTP('in')" title="Zoom In">
              <i class="fas fa-search-plus"></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
let currentZoom = 1;
let isDragging = false;
let startX, startY, scrollLeft, scrollTop;

function previewKTP(path, ext) {
  const modal = new bootstrap.Modal(document.getElementById('ktpPreviewModal'));
  const imageContainer = document.getElementById('ktpImageContainer');
  const pdfContainer = document.getElementById('ktpPdfContainer');
  const zoomControls = document.getElementById('zoomControls');
  
  // Reset
  imageContainer.innerHTML = '';
  pdfContainer.innerHTML = '';
  currentZoom = 1;
  
  if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) {
    // Show image
    imageContainer.classList.remove('d-none');
    pdfContainer.classList.add('d-none');
    zoomControls.classList.remove('d-none');
    
    const img = document.createElement('img');
    img.src = path;
    img.id = 'ktpImage';
    img.className = 'img-fluid';
    img.style.cursor = 'move';
    img.style.transition = 'transform 0.2s ease';
    img.style.maxWidth = '100%';
    img.style.height = 'auto';
    
    imageContainer.appendChild(img);
    
    // Enable drag
    imageContainer.style.cursor = 'grab';
    imageContainer.addEventListener('mousedown', startDrag);
    imageContainer.addEventListener('mousemove', drag);
    imageContainer.addEventListener('mouseup', stopDrag);
    imageContainer.addEventListener('mouseleave', stopDrag);
    
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

function zoomKTP(direction) {
  const img = document.getElementById('ktpImage');
  if (!img) return;
  
  if (direction === 'in') {
    currentZoom = Math.min(currentZoom + 0.25, 3);
  } else if (direction === 'out') {
    currentZoom = Math.max(currentZoom - 0.25, 0.5);
  }
  
  img.style.transform = `scale(${currentZoom})`;
  document.getElementById('zoomLevel').textContent = Math.round(currentZoom * 100) + '%';
}

function resetZoomKTP() {
  const img = document.getElementById('ktpImage');
  if (!img) return;
  
  currentZoom = 1;
  img.style.transform = 'scale(1)';
  document.getElementById('zoomLevel').textContent = '100%';
  
  const container = document.getElementById('ktpImageContainer');
  container.scrollLeft = 0;
  container.scrollTop = 0;
}

function startDrag(e) {
  isDragging = true;
  const container = e.currentTarget;
  container.style.cursor = 'grabbing';
  startX = e.pageX - container.offsetLeft;
  startY = e.pageY - container.offsetTop;
  scrollLeft = container.scrollLeft;
  scrollTop = container.scrollTop;
}

function drag(e) {
  if (!isDragging) return;
  e.preventDefault();
  const container = e.currentTarget;
  const x = e.pageX - container.offsetLeft;
  const y = e.pageY - container.offsetTop;
  const walkX = (x - startX) * 2;
  const walkY = (y - startY) * 2;
  container.scrollLeft = scrollLeft - walkX;
  container.scrollTop = scrollTop - walkY;
}

function stopDrag(e) {
  isDragging = false;
  e.currentTarget.style.cursor = 'grab';
}
</script>
