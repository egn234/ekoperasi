<div class="modal-header">
	<h5 class="modal-title" id="myModalLabel">Konfirmasi</h5>
	<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
	<div class="mb-3">
		Terima pengajuan pinjaman ini?
		<div class = "row mt-3">
			<form action="<?= url_to('admin_approve_pinjaman', $a->idpinjaman)?>" id="formTerima" method="post">
				<div class="mb-3">
					<label class="form-label" for="nominal_uang">Nominal</label>
					<input 
						type="number"
						step="0.01"
						class="form-control"
						id="nominal_uang"
						name="nominal_uang"
						value="<?= number_format($a->nominal, 0, '', '') ?>"
					>
					<small id="preview_nominal" class="form-text text-muted"></small>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
	<button type="submit" form="formTerima" class="btn btn-success">Terima</button>
</div>