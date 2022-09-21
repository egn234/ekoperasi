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
                                        <div class="btn-group float-end">
                                            <a href="<?= url_to('admin/user/add') ?>" class="btn btn-primary">
                                                Tambah User Baru
                                            </a>
                                            <a class="btn btn-success" data-bs-toggle="modal" data-bs-target="#upload_sheet">
                                                Impor User
                                            </a>
                                            <a class="btn btn-success" href="<?= url_to('admin/user/export_table') ?>" target="_blank">
                                                Ekspor user
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?=session()->getFlashdata('notif');?>
                                <table class="table table-sm table-bordered table-striped dt-responsive dtable nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
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
                                                    <?php if ($a->iduser == $duser->iduser) { ?>
                                                        <div class="row">
                                                            <div class="btn-group d-flex justify-content-center">
                                                                <a href="<?= url_to('admin/profile') ?>" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Detail</a>
                                                            </div>
                                                        </div>                                                        
                                                    <?php }else{?>
                                                        <div class="row">
                                                            <div class="btn-group d-flex justify-content-center">
                                                                <a href="<?= url_to('user_detail', $a->iduser) ?>" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Detail</a>
                                                                <?php if($a->user_flag == 1){?>
                                                                    <a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#switchUser" data-id="<?=$a->iduser?>">
                                                                        Nonaktifkan
                                                                    </a>
                                                                <?php }else{?>
                                                                    <a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#switchUser" data-id="<?=$a->iduser?>">
                                                                        Aktifkan
                                                                    </a>
                                                                <?php }?>
                                                            </div>
                                                        </div>
                                                    <?php }?>
                                                </td>
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

<div id="switchUser" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <span class="fetched-data"></span>
        </div>
    </div>
</div><!-- /.modal -->

<div id="upload_sheet" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Import User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?=url_to('admin/user/tab_upload')?>" id="formSheet" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="sheet_upload">File Spreadsheet (.xls .xlsx .csv) <i>*<a href="<?=base_url()?>/assets/import_format.xlsx" target="_blank">Klik disini untuk mengunduh format template</a></i></label>
                        <input type="file" class="form-control" name="file_import" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" form="formSheet" class="btn btn-success">Import</button>
            </div>
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
        $('#switchUser').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/admin/user/switch_user_confirm',
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