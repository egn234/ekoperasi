<?= $this->include('admin/partials/head-main') ?>

<head>

    <?= $title_meta ?>

    <?= $this->include('admin/partials/head-css') ?>

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
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h4 class="card-title">Daftar user yang terdaftar</h4>
                                    </div>
                                    <div class="col-sm-6">
                                        <a href="<?= url_to('admin/user/add') ?>" class="btn btn-primary float-end">
                                            Tambah user baru
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?=session()->getFlashdata('notif');?>
                                <table class="table table-bordered dt-responsive dtable nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            <th>Instansi</th>
                                            <th>Alamat Email</th>
                                            <th>No. Telpon</th>
                                            <th>Status Akun</th>
                                            <th>Grup Pengguna</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>


                                    <tbody>
                                        <?php $c = 1?>
                                        <?php foreach ($usr_list as $a) {?>
                                            <tr>
                                                <td><?= $c ?></td>
                                                <td><?= $a->nama_lengkap ?></td>
                                                <td><?= $a->instansi ?></td>
                                                <td><?= $a->email ?></td>
                                                <td><?= $a->nomor_telepon ?></td>
                                                <td><?=($a->user_flag == 1)?'Aktif':'Tidak Aktif'?></td>
                                                <td><?= $a->group_type ?></td>
                                                <td align="center">
                                                    <a href="" class="btn btn-primary"><i class="fa fa-search"></i> Detail</a>
                                                </td>
                                            </tr>
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


<?= $this->include('admin/partials/right-sidebar') ?>

<!-- JAVASCRIPT -->
<?= $this->include('admin/partials/vendor-scripts') ?>

<!-- Required datatable js -->
<script src="<?=base_url()?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<script src="<?=base_url()?>/assets/js/app.js"></script>
<script type="text/javascript">
    $('.dtable').DataTable();
</script>
</body>

</html>