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
										<h4 class="card-title">Daftar Pengajuan Simpanan yang Diproses</h4>
									</div>
								</div>
							</div>
							<div class="card-body">
								<?=session()->getFlashdata('notif');?>
								<table id="dt_list_filter" class="table table-sm table-striped nowrap w-100">
								</table>
							</div>
						</div>
					</div> <!-- end col -->
				</div> <!-- end row -->

				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-header">
								<div class="row">
									<div class="col-sm-12">
										<h4 class="card-title">Daftar Semua Pengajuan Simpanan</h4>
									</div>
								</div>
							</div>
							<div class="card-body">
								<table id="dt_list" class="table table-sm table-striped nowrap w-100">
								</table>
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