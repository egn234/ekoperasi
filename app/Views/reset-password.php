<?= $this->extend('layout/auth') ?>

<?= $this->section('content') ?>

<div class="min-h-screen bg-sunrise-animate relative p-4 md:p-8 flex items-center justify-center">
  <!-- Vanta Container -->
  <div id="vanta-bg" class="absolute inset-0 z-0 w-full h-full pointer-events-none"></div>
  <!-- Overlay -->
  <div class="absolute inset-0 bg-white/10 z-[1] backdrop-blur-[1px]"></div>

  <div class="relative z-10 w-full max-w-md bg-white/90 backdrop-blur-3xl rounded-[2.5rem] shadow-2xl border border-white/60 p-8 md:p-12">

    <div class="text-center mb-10">
      <a href="/" class="inline-block mb-4">
        <img src="https://ekop.kopgiat.id//logo_giat.ico" alt="" class="h-12 w-auto mx-auto drop-shadow-md">
      </a>
      <h2 class="text-3xl font-black text-slate-900 tracking-tight">Buat Password Baru</h2>
      <p class="text-slate-500 font-bold text-[10px] uppercase tracking-widest mt-2">Amankan akun anda sekarang</p>
    </div>

    <?php if (session()->getFlashdata('notif')): ?>
      <div class="bg-blue-50 text-blue-700 p-4 rounded-xl mb-6 text-sm font-bold text-center border border-blue-100">
        <?= session()->getFlashdata('notif') ?>
      </div>
    <?php endif; ?>

    <form action="<?= url_to('update_password', $token) ?>" method="post" class="space-y-5" id="reset-form">
      <?php if (ENVIRONMENT !== 'development'): ?>
        <!-- Hidden reCAPTCHA Token Input via JS -->
      <?php else: ?>
        <input type="hidden" name="recaptcha_token" value="dev-bypass-token">
      <?php endif; ?>

      <div class="space-y-4">
        <div class="relative">
          <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Password Baru <span class="text-red-500">*</span></label>
          <div class="relative">
            <input type="password" name="pass" id="pass" minlength="8" class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 pl-4 pr-12 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-slate-800 transition-all" required placeholder="Minimal 8 karakter">
            <button type="button" onclick="togglePassword('pass', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 mt-3">
              <i data-lucide="eye" class="w-5 h-5"></i>
            </button>
          </div>
        </div>

        <div class="relative">
          <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Ulangi Password <span class="text-red-500">*</span></label>
          <div class="relative">
            <input type="password" name="pass2" id="pass2" minlength="8" class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 pl-4 pr-12 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-slate-800 transition-all" required placeholder="Konfirmasi password">
            <button type="button" onclick="togglePassword('pass2', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 mt-3">
              <i data-lucide="eye" class="w-5 h-5"></i>
            </button>
          </div>
        </div>
      </div>

      <button type="submit" class="w-full bg-animate-red-navy text-white py-4 rounded-[1.5rem] font-black text-sm uppercase tracking-widest shadow-xl shadow-orange-100 hover:scale-105 active:scale-95 transition-all mt-4">
        Simpan Password
      </button>
    </form>

    <div class="mt-8 text-center pt-6 border-t border-slate-100">
      <a href="<?= url_to('/') ?>" class="text-xs font-bold text-slate-400 hover:text-orange-600 transition-colors uppercase tracking-widest">
        <i data-lucide="arrow-left" class="w-4 h-4 inline-block mr-1"></i> Batal & Kembali
      </a>
    </div>

  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if (ENVIRONMENT !== 'development'): ?>
  <script src="https://www.google.com/recaptcha/api.js?render=<?= getenv('RECAPTCHA_SITE_KEY') ?>"></script>
  <script>
    document.getElementById('reset-form').addEventListener('submit', function(e) {
      e.preventDefault();
      const form = this;
      grecaptcha.ready(function() {
        grecaptcha.execute('<?= getenv('RECAPTCHA_SITE_KEY') ?>', {
          action: 'reset_password'
        }).then(function(token) {
          const input = document.createElement('input');
          input.type = 'hidden';
          input.name = 'recaptcha_token';
          input.value = token;
          form.appendChild(input);
          form.submit();
        });
      });
    });
  </script>
<?php endif; ?>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    if (window.VANTA) {
      window.VANTA.WAVES({
        el: "#vanta-bg",
        THREE: window.THREE,
        mouseControls: true,
        touchControls: true,
        gyroControls: false,
        minHeight: 200.0,
        minWidth: 200.0,
        scale: 1.0,
        scaleMobile: 1.0,
        color: 0xffa500,
        shininess: 45,
        waveHeight: 20,
        waveSpeed: 0.6,
        zoom: 1.1
      });
    }
  });

  function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
      input.type = 'text';
      btn.classList.add('text-orange-600');
    } else {
      input.type = 'password';
      btn.classList.remove('text-orange-600');
    }
  }
</script>
<?= $this->endSection() ?>