<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <title>Sedang Dalam Pemeliharaan | Ekoperasi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sistem Informasi Koperasi" name="description" />
    <meta content="Ekoperasi" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= base_url() ?>/assets/images/favicon.ico">

    <!-- Bootstrap Css -->
    <link href="<?= base_url() ?>/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?= base_url() ?>/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?= base_url() ?>/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

    <style>
        body {
            background-color: #f8f8fb;
        }

        .maintenance-img {
            height: 300px;
            object-fit: contain;
        }
    </style>
</head>

<body>
    <div class="account-pages my-5 pt-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card overflow-hidden shadow-lg border-0">
                        <div class="bg-primary">
                            <div class="row">
                                <div class="col-7">
                                    <div class="text-white p-4">
                                        <h5 class="text-white">Sistem Sedang Maintenance</h5>
                                        <p class="text-white-50 mb-0">Mohon maaf atas ketidaknyamanan ini.</p>
                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <div class="p-3">
                                        <i class="fas fa-tools fa-4x text-white-50 opacity-25"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="p-2 mt-4 text-center">
                                <div class="avatar-lg mx-auto mb-4">
                                    <span class="avatar-title rounded-circle bg-light text-primary font-size-24">
                                        <i class="mdi mdi-alert-circle-outline"></i>
                                    </span>
                                </div>

                                <div class="maintenance-icon mb-4">
                                    <i class="bx bx-cog bx-spin text-success display-3"></i>
                                    <i class="bx bx-wrench text-primary display-4 ms-2"></i>
                                </div>

                                <h4 class="text-dark">Situs sedang dalam perbaikan</h4>
                                <p class="text-muted mb-4">
                                    Kami sedang melakukan pemeliharaan sistem rutin untuk meningkatkan performa dan
                                    layanan.
                                    Silakan kembali lagi dalam beberapa saat.
                                </p>

                                <div class="mt-4">
                                    <!-- Optional: Add a refresh button or contact info -->
                                    <a href="<?= base_url() ?>" class="btn btn-primary waves-effect waves-light">
                                        <i class="mdi mdi-refresh me-1"></i> Coba lagi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <p>©
                            <?= date('Y') ?> Ekoperasi. Dibuat dengan <i class="mdi mdi-heart text-danger"></i> oleh
                            Ko+Lab.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="<?= base_url() ?>/assets/libs/jquery/jquery.min.js"></script>
    <script src="<?= base_url() ?>/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url() ?>/assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="<?= base_url() ?>/assets/libs/simplebar/simplebar.min.js"></script>
    <script src="<?= base_url() ?>/assets/libs/node-waves/waves.min.js"></script>
    <script src="<?= base_url() ?>/assets/js/app.js"></script>
</body>

</html>