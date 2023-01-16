<?= $this->include('partials/head-main') ?>

    <head>

        <meta charset="utf-8" />
        <title>Register | Ekoperasi</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesbrand" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

            <?= $this->include('partials/head-css') ?>

</head>

<?= $this->include('partials/body') ?>

    <!-- <body data-layout="horizontal"> -->
        <div class="auth-page">
            <div class="container-fluid p-0">
                <div class="row g-0">
                    <div class="col-xxl-3 col-lg-2 col-md-2">
                    </div>
                    <div class="col-xxl-6 col-lg-8 col-md-8">
                        <div class="auth-full-page-content d-flex p-sm-5 p-4">
                            <div class="card card-body">
                                <div class="w-100">
                                    <div class="d-flex flex-column h-100">
                                        <div class="mb-4 mb-md-5 text-center">
                                            <a href="/" class="d-block auth-logo">
                                                <img src="<?= base_url() ?>/assets/images/logo-sm.svg" alt="" height="28"> <span class="logo-txt">Ekoperasi</span>
                                            </a>
                                        </div>
                                        <div class="auth-content my-auto">
                                            <div class="text-center">
                                                <h5 class="mb-0">Pembuatan Nominal Manasuka Bulanan</h5>
                                                <p class="text-muted mt-2">. : .</p>
                                            </div>
                                            <form action="<?= url_to('anggota/profile/set-manasuka-proc') ?>" method="post" class="needs-validation" id="set_manasuka_form" enctype="multipart/form-data">
                                                <div class="mb-3">
                                                    <label for="nominal_param">Besarnya Nominal (Rp)</label>
                                                    <input type="text" class="form-control" id="nominal_param" name="nilai" value="<?= $default_param ?>" required>
                                                    <input type="text" id="iduser" name="iduser" value="<?=$duser->iduser?>" hidden>
                                                </div>
                                                <a class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#konfirmasi">
                                                    Simpan
                                                </a>
                                            </form>
                                        </div>
                                        <div class="mt-4 mt-md-5 text-center">
                                            <p class="mb-0">© <script>document.write(new Date().getFullYear())</script> Ekoperasi   . Crafted with <i class="mdi mdi-heart text-danger"></i> by Ko+Lab</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end auth full page content -->
                    </div>
                    <!-- end col -->

                </div>
                <!-- end row -->
            </div>
            <!-- end container fluid -->
        </div>

        <div id="konfirmasi" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">PAKTA</h5>
                        <a class="btn-close" data-bs-dismiss="modal" aria-label="Close"></a>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <p>
                                dengan ini saya setuju dan sadar untuk mengajukan simpanan manasuka sebesar nominal berikut
                            </p>
                            <div class="mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="konfirmasi_check" form="set_manasuka_form" required>
                                    <label class="form-check-label" for="konfirmasi_check">
                                        <p class="mb-0">Bersedia dengan peraturan yang tertera </p>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</a>
                        <button type="submit" id="confirm_button" form="set_manasuka_form" class="btn btn-primary" disabled>Simpan</button>
                    </div>

                </div>
            </div>
        </div><!-- /.modal -->

        <!-- JAVASCRIPT -->
        <?= $this->include('partials/vendor-scripts') ?>

        <!-- validation init -->
        <script src="<?=base_url()?>/assets/libs/imask/imask.min.js"></script>
        <script src="<?= base_url() ?>/assets/js/pages/validation.init.js"></script>

        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function () {
                var currencyMask = IMask(document.getElementById('nominal_param'), {
                    mask: 'num',
                    blocks: {
                    num: {
                            // nested masks are available!
                            mask: Number,
                            thousandsSeparator: '.'
                        }
                    }
                });
            });

            $('#konfirmasi_check').click(function(){
                //If the checkbox is checked.
                if($(this).is(':checked')){
                    //Enable the submit button.
                    $('#confirm_button').attr("disabled", false);
                } else{
                    //If it is not checked, disable the button.
                    $('#confirm_button').attr("disabled", true);
                }
            });
        </script>

    </body>

</html>