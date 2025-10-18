<?= $this->include('partials/head-main') ?>

  <head>
    <meta charset="utf-8" />
    <title>Login | EKOPERASI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    
    <link rel="shortcut icon" href="<?=base_url()?>/favicon.ico">

    <?= $this->include('partials/head-css') ?>
    <style type="text/css">
      .grecaptcha-badge {
        visibility: hidden;
      }
    </style>
  </head>

  <?= $this->include('partials/body') ?>
    <div class="auth-page">
      <div class="container-fluid p-0">
        <div class="row g-0">
          <div class="col-xxl-3 col-lg-4 col-md-5">
            <div class="auth-full-page-content d-flex p-sm-5 p-4">
              <div class="w-100">
                <div class="d-flex flex-column h-100">
                  <div class="mb-4 mb-md-5 text-center">
                    <a href="/" class="d-block auth-logo">
                      <img src="<?=base_url()?>/logo_giat.ico" alt="" height="28"> <span class="logo-txt">Ekoperasi</span>
                    </a>
                  </div>
                  <div class="auth-content my-auto">
                    <div class="text-center">
                      <img src="<?=base_url()?>/logo_giat.ico" alt="" height="100">
                      <h5 class="mb-0"> </h5>
                      <p class="text-muted mt-2">Mulai login untuk masuk Ekoperasi</p>
                    </div>
                    <form class="custom-form mt-4 pt-2" action="<?= url_to('auth_login_proc'); ?>" method="post">
                      <?=session()->getFlashdata('notif_login')?>
                      <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?=session()->getFlashdata('username')?>" placeholder="Masukkan username">
                      </div>
                      <div class="mb-3">
                        <div class="d-flex align-items-start">
                          <div class="flex-grow-1">
                            <label class="form-label">Password</label>
                          </div>
                          <div class="flex-shrink-0">
                          </div>
                        </div>
                        <div class="input-group auth-pass-inputgroup">
                          <input type="password" class="form-control" placeholder="Masukkan password" aria-label="Password" name="password" id="password" aria-describedby="password-addon">
                          <button class="btn btn-light ms-0" type="button" id="password-toggle"><i class="mdi mdi-eye-outline"></i></button>
                        </div>
                      </div>
                      
                      <!-- reCAPTCHA Widget -->
                      <input type="hidden" name="recaptcha_token" id="g-recaptcha-response"/>

                      <div class="mb-3">
                        <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">Log In</button>
                      </div>
                      <div class="mt-5 text-center">
                        <p class="text-muted mb-0">Lupa Password? <a href="<?= url_to('forgot_password') ?>" class="text-primary fw-semibold"> Klik Disini </a> </p>
                      </div>
                    </form>

                    <div class="md-5 text-center">
                      <p class="text-muted mb-0">
                        Belum mendaftar sebagai anggota Ekoperasi?
                        <a href="<?= url_to('registrasi') ?>" class="text-primary fw-semibold">
                          Klik Disini
                        </a>
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
          <div class="col-xxl-9 col-lg-8 col-md-7">
            <div class="auth-bg pt-md-5 p-4 d-flex">
              <div class="bg-overlay bg-secondary"></div>
              <ul class="bg-bubbles">
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://www.google.com/recaptcha/api.js?render=<?= getenv('RECAPTCHA_SITE_KEY') ?>"></script>
    <script>
      grecaptcha.ready(function() {
        grecaptcha.execute("<?= getenv('RECAPTCHA_SITE_KEY') ?>", {action: 'login'})
        .then(function(token) {
          // Simpan token dalam input tersembunyi
          document.getElementById('g-recaptcha-response').value = token;
        });
      });
    </script>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const passwordInput = document.getElementById("password");
        const passwordToggle = document.getElementById("password-toggle");

        passwordToggle.addEventListener("click", function () {
          if (passwordInput.type === "password") {
            passwordInput.type = "text";
            passwordToggle.innerHTML = '<i class="mdi mdi-eye-off-outline"></i>';
          } else {
            passwordInput.type = "password";
            passwordToggle.innerHTML = '<i class="mdi mdi-eye-outline"></i>';
          }
        });
      });
    </script>

    <?= $this->include('partials/vendor-scripts') ?>
  </body>
</html>
