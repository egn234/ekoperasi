<!-- MODAL CANCEL -->
<div class="modal-header">
	<h5 class="modal-title" id="myModalLabel">Konfirmasi</h5>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
	<div class="mb-3">
		Tolak pengajuan manasuka ini?
		<div class = "row mt-3">
			<form action="<?= url_to('admin_batalkan_simpanan', $a->iddeposit)?>" id="formTolak" method="post">
				<div class="mb-3">
					<label class="form-label" for="alasan_tolak">Alasan Ditolak</label>
					<input type="text" class="form-control" id="alasan_tolak" name="alasan_tolak">
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
	<button type="submit" form="formTolak" class="btn btn-danger">Tolak</button>
</div>