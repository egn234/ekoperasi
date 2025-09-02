<!-- TODO: LANJUTIN SETELAH OM JAWAB -->
<?= $this->include('admin/partials/head-main') ?>

<head>
	<?= $title_meta ?>
  <?= $this->include('admin/partials/head-css') ?>

	<style type="text/css">
		input::-webkit-outer-spin-button,
		input::-webkit-inner-spin-button {
			-webkit-appearance: none;
			margin: 0;
		}
	</style>
</head>

<?= $this->include('admin/partials/body') ?>

<!-- Begin page -->
<div id="layout-wrapper">

	<?= $this->include('admin/partials/menu') ?>

	<!-- ============================================================== -->
	<!-- Start right Content here -->
	<!-- ============================================================== -->
	<div class="main-content">
		<div class="page-content">
			<div class="container-fluid">
				<!-- start page title -->
				<?= $page_title ?>
				<!-- end page title -->

				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-header">
								<div class="row">
									<div class="col-sm-12">
										<h4 class="card-title">Ubah Pengajuan Simpanan</h4>
									</div>
								</div>
							</div>
							<div class="card-body">
								<?=session()->getFlashdata('notif');?>
								
							</div>
						</div>
					</div> <!-- end col -->
				</div> <!-- end row -->

			</div> <!-- container-fluid -->
		</div>
		<!-- End Page-content -->
		<?= $this->include('admin/partials/footer') ?>
	</div>
	<!-- end main content-->
</div>
<!-- END layout-wrapper -->

<?= $this->include('admin/partials/right-sidebar') ?>

<!-- JAVASCRIPT -->
<?= $this->include('admin/partials/vendor-scripts') ?>

<script src="<?=base_url()?>/assets/js/app.js"></script>

</body>
</html>