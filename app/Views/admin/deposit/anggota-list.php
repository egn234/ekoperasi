<?= $this->include('admin/partials/head-main') ?>

<head>
    <?= $title_meta ?>
    <?= $this->include('admin/partials/head-css') ?>
    
    <!-- Custom CSS for Admin Deposit -->
    <link href="<?= base_url() ?>/assets/css/admin/deposit-list.css" rel="stylesheet" type="text/css" />
</head>

<?= $this->include('admin/partials/body') ?>

<div id="layout-wrapper">
    <?= $this->include('admin/partials/menu') ?>
    
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <?= $page_title ?>
                
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="fas fa-users"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="card-title mb-1">Daftar Anggota Koperasi</h4>
                                        <p class="text-muted mb-0 small">Kelola data anggota dan akses riwayat transaksi</p>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <?=session()->getFlashdata('notif');?>
                                <div class="table-responsive">
                                    <table id="dataTable" class="table table-hover align-middle mb-0"></table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


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
                        <input type="file" class="form-control" name="file_import" accept=".csv, .xls, .xlsx" required>
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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<script src="<?=base_url()?>/assets/js/app.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#dataTable').DataTable({
            ajax: {
                url: "<?= base_url() ?>admin/deposit/data_user",
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
                        let href = '<?= base_url()?>admin/deposit/user/'+ row.iduser;
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