<?= $this->include('partials/head-main') ?>

<head>
  <meta charset="utf-8" />
  <title>Lupa Password | Ekoperasi</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
  <meta content="Themesbrand" name="author" />
  <link rel="shortcut icon" href="assets/images/favicon.ico">
  <?= $this->include('partials/head-css') ?>
</head>

<?= $this->include('partials/body') ?>

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
                      <img src="<?=base_url()?>/logo_giat.ico" alt="" height="28"> <span class="logo-txt">Ekoperasi</span>
                    </a>
                  </div>
                  <div class="auth-content my-auto">
                    <div class="text-center">
                      <h5 class="mb-0">Reset Password</h5>
                      <p class="text-muted mt-2">Masukkan info yang telah terdaftar pada akun anda</p>
                    </div>
                    <form action="<?= url_to('forgot_password_proc') ?>" method="post" class="needs-validation">
                      <?=session()->getFlashdata('notif');?>
                      
                      <div class="mb-3">
                        <label class="form-label" for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?=session()->getFlashdata('username')?>" required>
                        <div class="invalid-feedback">
                          Harus Diisi
                        </div>
                      </div>

                      <div class="mb-3">
                        <label class="form-label" for="email">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?=session()->getFlashdata('email')?>" required>
                        <div class="invalid-feedback">
                          Harus Diisi
                        </div>
                      </div>

                      <div class="mb-3">
                        <label class="form-label" for="nik">NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik" value="<?=session()->getFlashdata('nik')?>" required>
                        <div class="invalid-feedback">
                          Harus Diisi
                        </div>
                      </div>

                      <div class="mb-3">
                        <label class="form-label" for="nomor_telepon">No. Telepon yang telah terdaftar</label>
                        <input type="text" class="form-control" id="nomor_telepon" name="nomor_telepon" value="<?=session()->getFlashdata('nomor_telepon')?>" required>
                        <div class="invalid-feedback">
                          Harus Diisi
                        </div>
                      </div>

                      <!-- reCAPTCHA Widget -->
                      <input type="hidden" name="recaptcha_token" id="g-recaptcha-response"/>

                      <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Lupa Password</button>
                    </form>
                    <div class="mt-5 text-center">
                      <p class="text-muted mb-0">Sudah terdaftar menjadi anggota ? 
                        <a href="<?= url_to('/')?>" class="text-primary fw-semibold"> Login Disini </a> 
                      </p>
                    </div>
                  </div>
                  <div class="mt-4 mt-md-5 text-center">
                    <p class="mb-0">
                      © <script>document.write(new Date().getFullYear())</script> Ekoperasi. Designed by 
                      <img src="<?=base_url()?>/assets/images/logo_kolab.jpg" alt="" height="28">
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JAVASCRIPT -->
  <?= $this->include('partials/vendor-scripts') ?>

  <!-- validation init -->
  <script src="assets/js/pages/validation.init.js"></script>
  <script src="https://www.google.com/recaptcha/api.js?render=<?= getenv('RECAPTCHA_SITE_KEY') ?>"></script>
  <script>
    setInterval(()=> {
      grecaptcha.ready(function() {
        grecaptcha.execute("<?= getenv('RECAPTCHA_SITE_KEY') ?>", {action: 'register'}).then(function(token) {
          // Simpan token dalam input tersembunyi
          document.getElementById('g-recaptcha-response').value = token;
        });
      });
    }, 120000);

    document.getElementById('confirm_button').addEventListener('click', (event) => {
      event.preventDefault();
      grecaptcha.ready(() => {
        grecaptcha.execute("<?= getenv('RECAPTCHA_SITE_KEY') ?>", {action: 'register'}).then((token) => {
          document.getElementById('g-recaptcha-response').value = token;
          document.getElementById('register_form').submit();
        });
      });
    });
  </script>

</body>
</html>