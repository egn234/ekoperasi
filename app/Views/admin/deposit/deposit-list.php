<?= $this->include('admin/partials/head-main') ?>

<head>
	<?= $title_meta ?>
  <?= $this->include('admin/partials/head-css') ?>
  
  <!-- Custom CSS for Admin Deposit -->
  <link href="<?= base_url() ?>/assets/css/admin/deposit-list.css" rel="stylesheet" type="text/css" />
  
	<style type="text/css">
		input::-webkit-outer-spin-button,
		input::-webkit-inner-spin-button {
			-webkit-appearance: none;
			margin: 0;
		}
	</style>
</head>

<?= $this->include('admin/partials/body') ?>

<div id="layout-wrapper">
	<?= $this->include('admin/partials/menu') ?>
	<div class="main-content">
		<div class="page-content">
			<div class="container-fluid">
				<?= $page_title ?>
				
				<!-- Pending Deposits Card -->
				<div class="row mb-4">
					<div class="col-12">
						<div class="card border-0 shadow-sm">
							<div class="card-header bg-gradient-warning text-white border-0">
								<div class="d-flex align-items-center">
									<div class="me-3">
										<div class="bg-white bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
											<i class="fas fa-hourglass-half text-white"></i>
										</div>
									</div>
									<div>
										<h4 class="card-title mb-1 text-white">Pengajuan Menunggu Persetujuan</h4>
										<p class="text-white-50 mb-0 small">Daftar pengajuan yang perlu diproses</p>
									</div>
								</div>
							</div>
							<div class="card-body">
								<?=session()->getFlashdata('notif');?>
								<div class="table-responsive">
									<table id="dt_list_filter" class="table table-hover align-middle mb-0">
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<!-- All Deposits Card -->
				<div class="row">
					<div class="col-12">
						<div class="card border-0 shadow-sm">
							<div class="card-header bg-white border-bottom">
								<div class="d-flex align-items-center">
									<div class="me-3">
										<div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
											<i class="fas fa-list-alt"></i>
										</div>
									</div>
									<div>
										<h4 class="card-title mb-1">Riwayat Semua Pengajuan</h4>
										<p class="text-muted mb-0 small">Semua data pengajuan simpanan anggota</p>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table id="dt_list" class="table table-hover align-middle mb-0">
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				</div>
			</div>
			<?= $this->include('admin/partials/footer') ?>
		</div>
	</div>

	<div id="approveMnsk" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<span id="fetched-data-approve"></span>
			</div>
		</div>
	</div><!-- /.modal -->

	<div id="tolakMnsk" class="modal fade" tabindex="-1">
		<div class="modal-dialog">
			<div class="modal-content">
				<span id="fetched-data-cancel"></span>
			</div>
		</div>
	</div><!-- /.modal -->

	<div id="detailMutasi" class="modal fade" tabindex="-1">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<span id="fetched-data"></span>
			</div>
		</div>
	</div><!-- /.modal -->

	<?= $this->include('admin/partials/right-sidebar') ?>

	<!-- JAVASCRIPT -->
	<?= $this->include('admin/partials/vendor-scripts') ?>

	<!-- Required datatable js -->
	<script src="<?=base_url()?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="<?=base_url()?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

	<!-- form mask -->
	<script src="<?=base_url()?>/assets/libs/imask/imask.min.js"></script>
	<script src="<?=base_url()?>/assets/js/app.js"></script>
	<script src="<?=base_url()?>/assets/js/pages/admin/deposit/deposit-list.js"></script>

</body>
</html>