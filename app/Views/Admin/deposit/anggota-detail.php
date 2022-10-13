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

<!-- <body data-layout="horizontal"> -->

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
                    
                    <div class="col-3">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h4 class="card-title">Detail Anggota</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h5 class="font-size-15">Nama:</h5>
                                    <div class="text-muted h3">
                                        <?=$duser->nama_lengkap?>
                                    </div>
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <h5 class="font-size-15">Saldo Simpanan Wajib:</h5>
                                    <div class="text-muted h3">
                                        Rp <?=number_format($total_saldo_wajib, 2, ',','.')?>
                                    </div>
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <h5 class="font-size-15">Saldo Simpanan Pokok:</h5>
                                    <div class="text-muted h3">
                                        Rp <?=number_format($total_saldo_pokok, 2, ',','.')?>
                                    </div>
                                </div>
                                <hr>
                                <div class="mb-3">
                                    <h5 class="font-size-15">Saldo Simpanan Manasuka:</h5>
                                    <div class="text-muted h3">
                                        Rp <?=number_format($total_saldo_manasuka, 2, ',','.')?>
                                    </div>
                                </div>
                                <hr>
                                <hr>
                                <div class="mb-3">
                                    <h5 class="font-size-15">Pascabayar Manasuka:</h5>
                                    <div class="text-muted h3">
                                        <?php if (!$param_manasuka) {
                                            echo 'Parameter belum di set';
                                        }else{?>
                                            Rp <?=number_format($param_manasuka[0]->nilai, 2, ',','.')?>
                                        <?php }?>
                                    </div>
                                </div>
                                <span class="mt-5 d-flex justify-content-center">
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#set_param_manasuka">Set Parameter Manasuka</button>
                                </span>
                            </div>
                        </div>
                    </div> <!-- end col -->
                    
                    <div class="col-9">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h4 class="card-title">Daftar Pengajuan Simpanan</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?=session()->getFlashdata('notif');?>
                                <table class="table table-sm dt-responsive dtable nowrap w-100">
                                    <thead>
                                        <th width="5%">No</th>
                                        <th>Jenis Pengajuan</th>
                                        <th>Jenis Simpanan</th>
                                        <th>Nilai</th>
                                        <th>Status</th>
                                        <th>Tanggal Pengajuan</th>
                                    </thead>
                                    <tbody>
                                        <?php $c = 1?>
                                        <?php foreach ($deposit_list as $a) {?>
                                            <tr>
                                                <td><?= $c ?></td>
                                                <td><?= $a->jenis_pengajuan ?></td>
                                                <td><?= $a->jenis_deposit ?></td>
                                                <td>
                                                    <?php if ($a->cash_in == 0) {?>
                                                        <span class="badge badge-soft-danger">- <?= number_format($a->cash_out, 2, ',', '.')?>
                                                    <?php }else{?>
                                                        <span class="badge badge-soft-success">+ <?= number_format($a->cash_in, 2, ',', '.')?>
                                                    <?php }?>
                                                </td>
                                                <td><?= $a->status ?></td>
                                                <td><?= $a->date_created ?></td>
                                            </tr>
                                        <?php $c++; ?>
                                        <?php }?>
                                    </tbody>
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

<div id="set_param_manasuka" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Set Nominal Pembayaran Manasuka</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?=($param_manasuka)?url_to('admin_set_parameter_manasuka', $param_manasuka[0]->idmnskparam):url_to('admin/deposit/create_param_manasuka')?>" id="formSheet" method="post">
                    <div class="mb-3">
                        <label for="nominal_param">Besarnya Nominal (Rp)</label>
                        <input type="number" class="form-control" id="nominal_param" name="nilai" value="<?=($param_manasuka)?$param_manasuka[0]->nilai:''?>" required>
                        <input type="text" id="iduser" name="iduser" value="<?=$duser->iduser?>" hidden>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" form="formSheet" class="btn btn-success">Set</button>
            </div>
        </div>
    </div>
</div><!-- /.modal -->

<div id="detailMutasi" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <span class="fetched-data"></span>
        </div>
    </div>
</div><!-- /.modal -->


<?= $this->include('admin/partials/right-sidebar') ?>

<!-- JAVASCRIPT -->
<?= $this->include('admin/partials/vendor-scripts') ?>

<!-- Required datatable js -->
<script src="<?=base_url()?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<script src="<?=base_url()?>/assets/js/app.js"></script>

<script type="text/javascript">
    $('.dtable').DataTable();
    $(document).ready(function() {
        $('#detailMutasi').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/admin/deposit/detail_mutasi',
                data: 'rowid=' + rowid,
                success: function(data) {
                    $('.fetched-data').html(data); //menampilkan data ke dalam modal
                }
            });
        });
    });
</script>

</body>

</html>