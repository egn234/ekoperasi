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
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-sm-6">
                    <h4 class="card-title">Daftar Pengajuan Pelunasan</h4>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <?=session()->getFlashdata('notif');?>
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

<div id="tolakPelunasan" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <span id="fetched-data-tolakPelunasan"></span>
    </div>
  </div>
</div><!-- /.modal -->

<div id="approvePelunasan" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <span id="fetched-data-approvePelunasan"></span>
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
  function numberFormat(number, decimals = 0, decimalSeparator = ',', thousandSeparator = '.') {
    number = parseFloat(number).toFixed(decimals);
    number = number.replace('.', decimalSeparator);
    var parts = number.split(decimalSeparator);
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator);
    return parts.join(decimalSeparator);
  }

  $(document).ready(function() {
    $('#dt_list').DataTable({
      ajax: {
        url: "<?= base_url() ?>admin/pinjaman/data_pelunasan",
        type: "POST",
        data: function (d) {
          d.length = d.length || 10; // Use the default if not provided
        }
      },
      autoWidth: false,
      scrollX: true,
      serverSide: true,
      searching: true,
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
          title: "Nama Lengkap",
          data: "nama_lengkap"
        },
        {
          title: "Nominal",
          "render": function(data, type, row, meta) {
            return 'Rp '+numberFormat(row.nominal, 2);
          }
        },
        {
          title: "Sisa bayar",
          "render": function(data, type, row, meta) {
            return 'Rp '+numberFormat(row.sisa_pinjaman, 2);
          }
        },
        {
          title: "Lama cicilan (bln)",
          data: "angsuran_bulanan"
        },
        {
          title: "Sisa cicilan (bln)",
          data: "sisa_cicilan"
        },
        {
          title: "Tanggal Pengajuan",
          data: "date_updated"
        },
        {
          title: "File",
          "render": function(data, type, row, meta) {
            let html_link = '<a href="<?=base_url()?>/uploads/user/'+row.username+'/pinjaman/'+row.bukti_tf+'" target="_blank"><i class="fa fa-download"></i> Bukti Bayar</a><br>';
            return html_link;
          }
        },
        {
          title: "Aksi",
          render: function(data, type, row, full) {
            let head = '<div class="btn-group d-flex justify-content-center">';
            let btn_a = '<a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#tolakPelunasan" data-id="'+row.idpinjaman+'"><i class="fa fa-file-alt"></i> Tolak</a>';
            let btn_b = '<a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approvePelunasan" data-id="'+row.idpinjaman+'"><i class="fa fa-file-alt"></i> Approve</a>';
            let tail = '</div>';

            return head + btn_a + btn_b + tail;
          }
        }
      ]
    });

    $('#approvePelunasan').on('show.bs.modal', function(e) {
      var rowid = $(e.relatedTarget).data('id');
      $.ajax({
        type: 'POST',
        url: '<?= base_url() ?>/admin/pinjaman/approve-pelunasan',
        data: 'rowid=' + rowid,
        success: function(data) {
          $('#fetched-data-approvePelunasan').html(data); //menampilkan data ke dalam modal
        }
      });
    });
    $('#tolakPelunasan').on('show.bs.modal', function(e) {
      var rowid = $(e.relatedTarget).data('id');
      $.ajax({
        type: 'POST',
        url: '<?= base_url() ?>/admin/pinjaman/cancel-pelunasan',
        data: 'rowid=' + rowid,
        success: function(data) {
          $('#fetched-data-tolakPelunasan').html(data); //menampilkan data ke dalam modal
        }
      });
    });
  });
</script>

</body>

</html>