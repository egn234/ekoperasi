<?= $this->include('bendahara/partials/head-main') ?>

<head>

  <?= $title_meta ?>
  <link href="<?=base_url()?>/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
  <?= $this->include('bendahara/partials/head-css') ?>

</head>

<?= $this->include('bendahara/partials/body') ?>

<div id="layout-wrapper">
  <?= $this->include('bendahara/partials/menu') ?>
  <div class="main-content">

    <div class="page-content">
      <div class="container-fluid">
        <?= $page_title ?>
        <div class="row">
          <div class="col-xl-6 col-md-12">
            <div class="row">
              <div class="col-xl-6 col-md-12">
                <div class="card card-h-100">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-12">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Jumlah Anggota Koperasi</span>
                        <h4 class="mb-3">
                          <?=$total_anggota?> orang
                        </h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-6 col-md-12">
                <div class="card card-h-100">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-12">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Anggota Baru Bulan ini</span>
                        <h4 class="mb-3">
                          <?=$monthly_user?> orang
                        </h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-6 col-md-12">
                <div class="card card-h-100">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-12">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Anggota yang masih memiliki pinjaman</span>
                        <h4 class="mb-3">
                          <?=$anggota_pinjaman?> orang
                        </h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-6 col-md-12">
                <div class="card card-h-100">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-12">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Deposit GIAT</span>
                        <h4 class="mb-3">
                          Rp <?=number_format($uang_giat, 0, ',', '.')?>
                        </h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-6 col-md-12">
                <div class="card card-h-100">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-12">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Income Bulan Ini</span>
                        <h4 class="mb-3">
                          Rp <?=number_format($monthly_income, 0, ',', '.')?>
                        </h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-xl-6 col-md-12">
                <div class="card card-h-100">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-12">
                        <span class="text-muted mb-3 lh-1 d-block text-truncate">Outcome Bulan Ini</span>
                        <h4 class="mb-3">
                          Rp <?=number_format($monthly_outcome, 0, ',', '.')?>
                        </h4>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xl-6">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title mb-0">Grafik Deposit 5 Bulan Terakhir</h4>
              </div>
              <div class="card-body">
                <div id="spline_area" data-colors='["#5156be", "#2ab57d"]' class="apex-charts" dir="ltr"></div>
              </div>
            </div>
          </div>
        </div>
        
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <div class="row">
                  <div class="col-sm-6">
                    <h4 class="card-title">Daftar Pengajuan Pinjaman</h4>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <?=session()->getFlashdata('notif');?>
                <?=session()->getFlashdata('notif_tf');?>
                <table id="dt_list_filter" class="table table-sm table-striped nowrap w-100">
                </table>
              </div>
            </div>
          </div> <!-- end col -->
        </div> <!-- end row -->
      </div>
      <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    <?= $this->include('bendahara/partials/footer') ?>
  </div>
  <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<div id="tolakPinjaman" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <span id="tolak-data"></span>
    </div>
  </div>
</div><!-- /.modal -->

<div id="approvePinjaman" class="modal fade" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <span id="terima-data"></span>
    </div>
  </div>
</div><!-- /.modal -->

<?= $this->include('bendahara/partials/right-sidebar') ?>

<?= $this->include('bendahara/partials/vendor-scripts') ?>

<!-- apexcharts -->
<script src="<?=base_url()?>/assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- Plugins js-->
<script src="<?=base_url()?>/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?=base_url()?>/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>
<!-- dashboard init -->
<script src="<?=base_url()?>/assets/js/pages/dashboard.init.js"></script>

<!-- Required datatable js -->
<script src="<?=base_url()?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<!-- apexcharts js -->
<script src="<?=base_url()?>/assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- App js -->
<script src="<?=base_url()?>/assets/js/app.js"></script>

<script type="text/javascript">
  function numberFormat(number, decimals = 0, decimalSeparator = ',', thousandSeparator = '.') {
    number = parseFloat(number).toFixed(decimals);
    number = number.replace('.', decimalSeparator);
    var parts = number.split(decimalSeparator);
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator);
    return parts.join(decimalSeparator);
  }
  
  function getChartColorsArray(chartId) {
    var colors = $(chartId).attr('data-colors');
    var colors = JSON.parse(colors);
    return colors.map(function(value){
      var newValue = value.replace(' ', '');
      if(newValue.indexOf('--') != -1) {
        var color = getComputedStyle(document.documentElement).getPropertyValue(newValue);
        if(color) return color;
      } else {
        return newValue;
      }
    })
  }

  $(document).ready(function() {
    $('#dt_list_filter').DataTable({
      ajax: {
        url: "<?= base_url() ?>bendahara/pinjaman/data_pinjaman",
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
          data: "username_peminjam"
        },
        {
          title: "Nama Lengkap",
          data: "nama_peminjam"
        },
        {
          title: "Tipe",
          data: "tipe_permohonan"
        },
        {
          title: "Nominal",
          render: function(data, type, row, meta) {
            return 'Rp '+numberFormat(row.nominal, 2);
          }
        },
        {
          title: "Tanggal Pengajuan",
          data: "date_created"
        },
        {
          title: "Lama Angsuran (bulan)",
          data: "angsuran_bulanan"
        },
        {
          title: "Form Persetujuan",
          render: function(data, type, row, full) {
            let link_a = '<a href="<?=base_url()?>/uploads/user/'+row.username_peminjam+'/pinjaman/'+row.form_bukti+'" target="_blank"><i class="fa fa-download"></i> Form SDM</a><br>';
            let link_b = '<a href="<?=base_url()?>/uploads/user/'+row.username_peminjam+'/pinjaman/'+row.slip_gaji+'" target="_blank"><i class="fa fa-download"></i> Slip Gaji</a><br>';
            let link_c = '';

            if (row.status_pegawai === 'kontrak') {
              link_c = '<a href="<?=base_url()?>/uploads/user/'+row.username_peminjam+'/pinjaman/'+row.form_kontrak+'" target="_blank"><i class="fa fa-download"></i> Bukti Kontrak</a>';
            }
                            
            return link_a + link_b + link_c;
          }
        },
        {
          title: "Aksi",
          render: function(data, type, row, full) {
            let head = '<div class="btn-group d-flex justify-content-center">'
            let tolak_btn = '<a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#tolakPinjaman" data-id="'+row.idpinjaman+'"><i class="fa fa-file-alt"></i> Tolak</a>';
            let terima_btn = '<a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approvePinjaman" data-id="'+row.idpinjaman+'"><i class="fa fa-file-alt"></i> Setujui</a>';
            let tail = '</div>';

            return head + tolak_btn + terima_btn + tail;
          }
        }
      ]
    });

    $('#tolakPinjaman').on('show.bs.modal', function(e) {
      var rowid = $(e.relatedTarget).data('id');
      $.ajax({
        type: 'POST',
        url: '<?= base_url() ?>/bendahara/pinjaman/cancel-pinjaman',
        data: 'rowid=' + rowid,
        success: function(data) {
          $('#tolak-data').html(data); //menampilkan data ke dalam modal
        }
      });
    });
    $('#approvePinjaman').on('show.bs.modal', function(e) {
      var rowid = $(e.relatedTarget).data('id');
      $.ajax({
        type: 'POST',
        url: '<?= base_url() ?>/bendahara/pinjaman/approve-pinjaman',
        data: 'rowid=' + rowid,
        success: function(data) {
          $('#terima-data').html(data); //menampilkan data ke dalam modal
        }
      });
    });
    
    var splneAreaColors = getChartColorsArray("#spline_area");
    var options = {
    chart: {
      height: 350,
      type: 'area',
      toolbar: {
        show: false,
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'straight',
      width: 3,
    },
    series: [{
      name: 'Saldo',
      data: [<?php foreach ($monthly_graph as $saldo){echo $saldo->saldo.',';}?>]
    }],
    colors: splneAreaColors,
    xaxis: {
      type: 'string',
      categories: [<?php foreach ($monthly_graph as $month){echo '"'.$month->month.'",';}?>],
      title: {
        text: 'Month',
        rotate: 0,
        style: {
          fontSize: '15px'
        }
      }
    },
    yaxis: {
      axisTicks: {
        show: true,
        borderType: 'solid',
        color: '#000000',
        width: 6,
        offsetX: 0,
        offsetY: 0
      },
      decimalsInFloat: 0,
      showAlways: false,
      labels: {
        rotate: -45,
      },
      title: {
        text: 'Deposit',
        rotate: -90,
        style: {
          fontSize: '15px'
        }
      }
    },
    grid: {
      borderColor: '#000000',
      position: 'front',
      xaxis: {
        lines: {
          show: false
        }
      }
    },
    tooltip: {
      x: {
        format: 'yy/MM'
      },
    }
    }

    var chart = new ApexCharts(document.querySelector("#spline_area"), options);

    chart.render();
  });
</script>

</body>

</html>