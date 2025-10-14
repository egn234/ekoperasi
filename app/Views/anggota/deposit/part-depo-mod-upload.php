            <div class="modal-header border-0 pb-0">
  <div class="d-flex align-items-center">
    <div class="me-3">
      <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" 
           style="width: 48px; height: 48px;">
        <i class="fas fa-cloud-upload-alt"></i>
      </div>
    </div>
    <div>
      <h5 class="modal-title mb-1">Upload Bukti Transfer</h5>
      <p class="text-muted small mb-0">Unggah bukti pembayaran untuk verifikasi transaksi</p>
    </div>
  </div>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body pt-4">
  <!-- Upload Instructions -->
  <div class="alert alert-info border-0 mb-4" style="background-color: #e0f2fe;">
    <div class="d-flex">
      <i class="fas fa-info-circle me-3 text-info mt-1"></i>
      <div>
        <strong class="text-info">Petunjuk Upload:</strong>
        <ul class="mb-0 mt-2 text-info">
          <li>Format file: JPG atau JPEG</li>
          <li>Ukuran maksimal: 5MB</li>
          <li>Pastikan bukti transfer jelas dan terbaca</li>
        </ul>
      </div>
    </div>
  </div>

  <form action="<?= url_to('an_de_upbkttrf', $a->iddeposit) ?>" id="form_upload_bukti_<?=$a->iddeposit?>" method="post" enctype="multipart/form-data">
    <div class="mb-4">
      <label class="form-label fw-medium mb-3">
        <i class="fas fa-file-image me-2 text-primary"></i>Pilih File Bukti Transfer
      </label>
      
      <div class="upload-area border border-2 border-dashed rounded-3 p-4 text-center position-relative" 
           style="border-color: #e2e8f0 !important; transition: all 0.3s ease;">
        <input type="file" 
               name="bukti_transfer" 
               id="bkt_trf" 
               class="form-control position-absolute w-100 h-100 opacity-0" 
               style="top: 0; left: 0; cursor: pointer;"
               accept="image/jpg,image/jpeg" 
               required>
        
        <div class="upload-content">
          <i class="fas fa-cloud-upload-alt text-muted mb-3" style="font-size: 2rem;"></i>
          <h6 class="text-muted mb-2">Klik atau seret file ke sini</h6>
          <p class="text-muted small mb-0">Format: JPG, JPEG (Maks. 5MB)</p>
        </div>
        
        <div class="upload-preview d-none">
          <i class="fas fa-file-image text-success mb-2" style="font-size: 2rem;"></i>
          <p class="mb-0 text-success fw-medium file-name"></p>
          <small class="text-muted file-size"></small>
        </div>
      </div>
      
      <div class="invalid-feedback">
        Silakan pilih file bukti transfer
      </div>
    </div>
  </form>

  <style>
    .upload-area:hover {
      border-color: #3b82f6 !important;
      background-color: #f8faff;
    }
    
    .upload-area.dragover {
      border-color: #3b82f6 !important;
      background-color: #e0f2fe;
    }
  </style>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const uploadArea = document.querySelector('.upload-area');
      const fileInput = document.getElementById('bkt_trf');
      const uploadContent = document.querySelector('.upload-content');
      const uploadPreview = document.querySelector('.upload-preview');
      const fileName = document.querySelector('.file-name');
      const fileSize = document.querySelector('.file-size');

      // Drag and drop functionality
      uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
      });

      uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
      });

      uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
          fileInput.files = files;
          handleFileSelect();
        }
      });

      // File input change
      fileInput.addEventListener('change', handleFileSelect);

      function handleFileSelect() {
        const file = fileInput.files[0];
        if (file) {
          fileName.textContent = file.name;
          fileSize.textContent = formatFileSize(file.size);
          uploadContent.classList.add('d-none');
          uploadPreview.classList.remove('d-none');
        } else {
          uploadContent.classList.remove('d-none');
          uploadPreview.classList.add('d-none');
        }
      }

      function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
      }
    });
  </script>
</div>
<div class="modal-footer border-0 pt-0">
  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
    <i class="fas fa-times me-2"></i>Batal
  </button>
  <button type="submit" form="form_upload_bukti_<?=$a->iddeposit?>" class="btn btn-primary">
    <i class="fas fa-cloud-upload-alt me-2"></i>Upload Bukti
  </button>
</div>