<?= $this->include('admin/partials/head-main') ?>

<head>
  <?= $title_meta ?>
  <link href="<?=base_url()?>/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />
  <?= $this->include('admin/partials/head-css') ?>
</head>

<?= $this->include('admin/partials/body') ?>

<div id="layout-wrapper">
  <?= $this->include('admin/partials/menu') ?>
  <div class="main-content">
    <div class="page-content">
      <div class="container-fluid">
        <?= $page_title ?>
        <div class="row">
          <?= session()->getFlashdata('register_notif'); ?>
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
            <!--end card-->
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
                <table class="table table-sm table-bordered table-striped dt-responsive dtable nowrap w-100">
                  <thead>
                    <th width="5%">No</th>
                    <th>Nama Pemohon</th>
                    <th>Tipe</th>
                    <th>Nominal</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Lama Angsuran (bulan)</th>
                    <th>Form Persetujuan</th>
                    <th>Aksi</th>
                  </thead>
                  <tbody>
                    <?php $c = 1?>
                    <?php foreach ($list_pinjaman as $a) {?>
                      <tr>
                        <td><?= $c ?></td>
                        <td><?= $a->nama_peminjam ?></td>
                        <td><?= $a->tipe_permohonan ?></td>
                        <td>Rp <?= number_format($a->nominal, 2, ',', '.') ?></td>
                        <td><?= date('d F Y', strtotime($a->date_created)) ?></td>
                        <td><?= $a->angsuran_bulanan ?></td>
                        <td>
                          <a href="<?=base_url()?>/uploads/user/<?=$a->username_peminjam?>/pinjaman/<?=$a->form_bukti?>" target="_blank">
                            <i class="fa fa-download"></i> Form SDM
                          </a><br>
                          <a href="<?=base_url()?>/uploads/user/<?=$a->username_peminjam?>/pinjaman/<?=$a->slip_gaji?>" target="_blank">
                            <i class="fa fa-download"></i> Slip Gaji
                          </a><br>
                          <?php if($a->status_pegawai == 'kontrak'){?>
                            <a href="<?=base_url()?>/uploads/user/<?=$a->username_peminjam?>/pinjaman/<?=$a->form_kontrak?>" target="_blank">
                              <i class="fa fa-download"></i> Bukti Kontrak
                            </a>
                          <?php } ?>
                        </td>
                        <td>
                          <div class="btn-group d-flex justify-content-center">
                            <a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#tolakPinjaman" data-id="<?=$a->idpinjaman?>">
                              <i class="fa fa-file-alt"></i> Tolak
                            </a>
                            <a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approvePinjaman" data-id="<?=$a->idpinjaman?>">
                              <i class="fa fa-file-alt"></i> Setujui
                            </a>
                          </div>
                        </td>
                      </tr>
                      <?php $c++; ?>
                    <?php }?>
                  </tbody>
                </table>
              </div>
            </div>
          </div> 
        </div>
      </div>
    </div>
    <?= $this->include('admin/partials/footer') ?>
  </div>
</div>

<div id="tolakPinjaman" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <span id="fetched-data-tolakPinjaman"></span>
    </div>
  </div>
</div><!-- /.modal -->

<div id="approvePinjaman" class="modal fade" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <span id="fetched-data-approvePinjaman"></span>
    </div>
  </div>
</div><!-- /.modal -->

<?= $this->include('admin/partials/right-sidebar') ?>
<?= $this->include('admin/partials/vendor-scripts') ?>

<!-- apexcharts -->
<script src="<?=base_url()?>/assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- Plugins js-->
<script src="<?=base_url()?>/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?=base_url()?>/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>

<!-- apexcharts js -->
<script src="<?=base_url()?>/assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- Required datatable js -->
<script src="<?=base_url()?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<!-- App js -->
<script src="<?=base_url()?>/assets/js/app.js"></script>

<script type="text/javascript">
  $('.dtable').DataTable();

  $(document).ready(function() {
    $('#approvePinjaman').on('show.bs.modal', function(e) {
      var rowid = $(e.relatedTarget).data('id');
      $.ajax({
        type: 'POST',
        url: '<?= base_url() ?>/admin/pinjaman/approve-pinjaman',
        data: 'rowid=' + rowid,
        success: function(data) {
          $('#fetched-data-approvePinjaman').html(data);
        }
      });
    });

    $('#tolakPinjaman').on('show.bs.modal', function(e) {
      var rowid = $(e.relatedTarget).data('id');
      $.ajax({
        type: 'POST',
        url: '<?= base_url() ?>/admin/pinjaman/cancel-pinjaman',
        data: 'rowid=' + rowid,
        success: function(data) {
          $('#fetched-data-tolakPinjaman').html(data);
        }
      });
    });
  });

  document.addEventListener('DOMContentLoaded', function () {
    var myModal = document.getElementById('approvePinjaman');
    myModal.addEventListener('shown.bs.modal', function () {
      const nominalInput = document.getElementById('nominal_uang');
      const previewNominal = document.getElementById('preview_nominal');

      if (nominalInput) {
        // fungsi untuk update preview
        function updatePreview() {
          // Ambil angka aja (buang selain digit)
          const raw = nominalInput.value.replace(/[^\d]/g, "");

          if (raw) {
            // parse ke integer
            const num = parseInt(raw, 10);

            // format ribuan tanpa desimal
            const formatted = new Intl.NumberFormat("id-ID", {
              maximumFractionDigits: 0
            }).format(num);

            previewNominal.textContent = `Nominal Rp. ${formatted}`;
          } else {
            previewNominal.textContent = "";
          }
        }

        // update setiap user ketik
        nominalInput.addEventListener('input', updatePreview);

        // 🔥 inisialisasi awal pakai value dari DB
        updatePreview();
      }
    });
  });
</script>

<script type="text/javascript">
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
</script>

</body>

</html>