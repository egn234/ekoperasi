<?= $this->include('ketua/partials/head-main') ?>

<head>

    <?= $title_meta ?>

    <link href="<?=base_url()?>/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />

    <?= $this->include('ketua/partials/head-css') ?>

</head>

<?= $this->include('ketua/partials/body') ?>

<!-- Begin page -->
<div id="layout-wrapper">

    <?= $this->include('ketua/partials/menu') ?>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <?= $page_title ?>

                <div class="row">

                    <div class="col-xl-6 col-md-12">
                        <div class="row">
                            <div class="col-xl-6 col-md-12">
                                <!-- card -->
                                <div class="card card-h-100">
                                    <!-- card body -->
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <span class="text-muted mb-3 lh-1 d-block text-truncate">Jumlah Anggota Koperasi</span>
                                                <h4 class="mb-3">
                                                    <?=$total_anggota?> orang
                                                </h4>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->

                            <div class="col-xl-6 col-md-12">
                                <!-- card -->
                                <div class="card card-h-100">
                                    <!-- card body -->
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <span class="text-muted mb-3 lh-1 d-block text-truncate">Anggota Baru Bulan ini</span>
                                                <h4 class="mb-3">
                                                    <?=$monthly_user?> orang
                                                </h4>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->

                            <div class="col-xl-6 col-md-12">
                                <!-- card -->
                                <div class="card card-h-100">
                                    <!-- card body -->
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <span class="text-muted mb-3 lh-1 d-block text-truncate">Anggota yang masih memiliki pinjaman</span>
                                                <h4 class="mb-3">
                                                    <?=$anggota_pinjaman?> orang
                                                </h4>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->

                            <div class="col-xl-6 col-md-12">
                                <!-- card -->
                                <div class="card card-h-100">
                                    <!-- card body -->
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <span class="text-muted mb-3 lh-1 d-block text-truncate">Total Deposit GIAT</span>
                                                <h4 class="mb-3">
                                                    Rp <?=number_format($uang_giat, 0, ',', '.')?>
                                                </h4>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->

                            <div class="col-xl-6 col-md-12">
                                <!-- card -->
                                <div class="card card-h-100">
                                    <!-- card body -->
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <span class="text-muted mb-3 lh-1 d-block text-truncate">Income Bulan Ini</span>
                                                <h4 class="mb-3">
                                                    Rp <?=number_format($monthly_income, 0, ',', '.')?>
                                                </h4>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->

                            <div class="col-xl-6 col-md-12">
                                <!-- card -->
                                <div class="card card-h-100">
                                    <!-- card body -->
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <span class="text-muted mb-3 lh-1 d-block text-truncate">Outcome Bulan Ini</span>
                                                <h4 class="mb-3">
                                                    Rp <?=number_format($monthly_outcome, 0, ',', '.')?>
                                                </h4>
                                            </div>
                                        </div>
                                    </div><!-- end card body -->
                                </div><!-- end card -->
                            </div><!-- end col -->
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
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <?= $this->include('ketua/partials/footer') ?>
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<?= $this->include('ketua/partials/right-sidebar') ?>

<?= $this->include('ketua/partials/vendor-scripts') ?>

<!-- apexcharts -->
<script src="<?=base_url()?>/assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- Plugins js-->
<script src="<?=base_url()?>/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?=base_url()?>/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js"></script>
<!-- dashboard init -->
<script src="<?=base_url()?>/assets/js/pages/dashboard.init.js"></script>

<!-- apexcharts js -->
<script src="<?=base_url()?>/assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- App js -->
<script src="<?=base_url()?>/assets/js/app.js"></script>

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