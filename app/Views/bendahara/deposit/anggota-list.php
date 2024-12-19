<?= $this->include('bendahara/partials/head-main') ?>

<head>

    <?= $title_meta ?>

    <?= $this->include('bendahara/partials/head-css') ?>

</head>

<?= $this->include('bendahara/partials/body') ?>

<!-- <body data-layout="horizontal"> -->

<!-- Begin page -->
<div id="layout-wrapper">

    <?= $this->include('bendahara/partials/menu') ?>

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
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?=session()->getFlashdata('notif');?>
                                <table id="dataTable" class="table table-sm table-striped nowrap w-100">
                                    
                                </table>
                            </div>
                        </div>
                    </div> <!-- end col -->
                </div> <!-- end row -->


            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->


        <?= $this->include('bendahara/partials/footer') ?>
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

<?= $this->include('bendahara/partials/right-sidebar') ?>

<!-- JAVASCRIPT -->
<?= $this->include('bendahara/partials/vendor-scripts') ?>

<!-- Required datatable js -->
<script src="<?=base_url()?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<script src="<?=base_url()?>/assets/js/app.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#dataTable').DataTable({
            ajax: {
                url: "<?= base_url() ?>bendahara/deposit/data_user",
                type: "POST",
                data: function (d) {
                    d.length = d.length || 10; // Use the default if not provided
                }
            },
            "autoWidth": false,
            "scrollX": true,
            serverSide: true,
            columnDefs: [{
                orderable: false,
                targets: "_all",
                defaultContent: "-",
            }],
            columns: [
                {
                    title: "Username",
                    data: "username"
                },
                {
                    title: "Nama",
                    data: "nama_lengkap"
                },
                {
                    title: "instansi",
                    data: "instansi"
                },
                {
                    title: "Alamat Email",
                    data: "email"
                },
                {
                    title: "No. Telpon",
                    data: "nomor_telepon"
                },
                {
                    title: "Status Akun",
                    render: function(data, type, row) {
                        let flag = row.flag
                        let statakun

                        if (flag == "1") {
                            statakun = "<span class='badge bg-success'>Aktif</span>"
                        }else{
                            statakun = "<span class='badge bg-danger'>Nonaktif</span>"
                        }

                        return statakun;
                    }
                },
                {
                    title: "Aksi",
                    render: function(data, type, row, full) {
                        let href = '<?= base_url()?>bendahara/deposit/user/'+ row.iduser;
                        let html = '<div class="row"><div class="btn-group d-flex justify-content-center">';
                        let close = '</div>';        
                        let button_a = '<a href="' + href + '" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Detail</a>';

                        return html + button_a + close + close;
                    }
                }
            ]
        });

        $('#switchUser').on('show.bs.modal', function(e) {
            var rowid = $(e.relatedTarget).data('id');
            $.ajax({
                type: 'POST',
                url: '<?= base_url() ?>/bendahara/user/switch_user_confirm',
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