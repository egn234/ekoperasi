<!-- MODAL CANCEL -->
<div class="modal-header bg-gradient-danger text-white border-0">
	<h5 class="modal-title text-white d-flex align-items-center" id="myModalLabel">
		<i class="fas fa-times-circle me-2"></i>Konfirmasi Penolakan
	</h5>
	<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
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
			<h6 class="mb-2">Tolak Pengajuan Manasuka?</h6>
			<p class="text-muted mb-0">Pengajuan yang ditolak tidak dapat dibatalkan</p>
		</div>

		<!-- Form -->
		<form action="<?= url_to('admin_batalkan_simpanan', $a->iddeposit)?>" id="formTolak" method="post">
			<div class="card border-0 bg-light">
				<div class="card-body">
					<label class="form-label fw-semibold" for="alasan_tolak">
						<i class="fas fa-comment-alt me-2 text-danger"></i>Alasan Penolakan
					</label>
					<textarea 
						class="form-control" 
						id="alasan_tolak" 
						name="alasan_tolak" 
						rows="3"
						placeholder="Jelaskan alasan mengapa pengajuan ini ditolak..."
						required
					></textarea>
					<div class="form-text text-muted">
						<i class="fas fa-info-circle me-1"></i>Alasan ini akan dikirim ke pemohon sebagai notifikasi
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="modal-footer border-top-0 bg-light">
	<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
		<i class="fas fa-arrow-left me-1"></i>Kembali
	</button>
	<button type="submit" form="formTolak" class="btn btn-danger">
		<i class="fas fa-times me-1"></i>Tolak Pengajuan
	</button>
</div>