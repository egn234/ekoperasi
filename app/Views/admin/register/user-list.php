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
  <div class="main-content">
    <div class="page-content">
      <div class="container-fluid">
        <?= $page_title ?>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-sm-6">
                    <h4 class="card-title">Daftar user yang belum di verifikasi</h4>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <?=session()->getFlashdata('notif');?>
                <table id="dataTable" class="table table-sm table-striped nowrap w-100"></table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?= $this->include('admin/partials/footer') ?>
  </div>
</div>

<div id="detailUser" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <span class="fetched-data"></span>
    </div>
  </div>
</div>

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
        url: "<?= base_url() ?>admin/register/data_user",
        type: "POST"
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
          title: "No",
          "render": function(data, type, row, meta) {
            return meta.row + 1;
          }
        },
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
          title: "Aksi",
          render: function(data, type, row, full) {
            let admin_user = "<?=$duser->iduser?>"
            let rowdiv = '<div class="row">'
            let justify_content = '<div class="btn-group d-flex justify-content-center">'
            let close = '</div>'
            let button_status = '<a class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detailUser" data-id="'+row.iduser+'">Detail</a>'
            return rowdiv + justify_content + button_status + close + close
          }
        }
      ]
    });
    
    $('#detailUser').on('show.bs.modal', function(e) {
      var rowid = $(e.relatedTarget).data('id');
      $.ajax({
        type: 'POST',
        url: '<?= base_url() ?>/admin/register/detail_user',
        data: 'rowid=' + rowid,
        success: function(data) {
          $('.fetched-data').html(data);
        }
      });
    });
  });
</script>
</body>

</html>