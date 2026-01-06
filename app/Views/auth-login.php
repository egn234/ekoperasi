<?= $this->extend('layout/auth') ?>

<?= $this->section('content') ?>

<!-- SPLASH SCREEN SECTION -->
<div id="splash-screen" class="flex flex-col min-h-[100dvh] px-10 py-12 justify-between relative z-10 bg-white transition-opacity duration-500 ease-in-out">
  <div class="flex flex-col items-center animate-fade-in-down">
    <img src="https://ekop.kopgiat.id//logo_giat.ico" alt="Logo Koperasi" class="w-32 h-32 object-contain">
  </div>

  <div class="flex-1 flex flex-col items-center justify-center">
    <div class="w-full max-w-[320px] aspect-square relative mb-8">
      <iframe
        src="https://lottie.host/embed/92817b01-9ddc-4604-8def-9a66f1f41829/wt5lJsTIFU.lottie"
        className="w-full h-full"
        style="border: none; width: 100%; height: 100%;"
        title="Welcome Animation"></iframe>
    </div>

    <div class="text-center w-full space-y-3">
      <h1 class="text-4xl font-black text-slate-900 tracking-tight">
        eKoperasi <span class="text-rose-600">Portal</span>
      </h1>
      <p class="text-slate-500 max-w-[280px] mx-auto leading-relaxed font-medium">
        Pengalaman perbankan koperasi digital yang lebih modern, aman, dan transparan.
      </p>
    </div>
  </div>

  <div class="flex flex-col space-y-10">
    <button
      onclick="showLoginForm()"
      class="w-full bg-animate-red-navy text-white py-5 rounded-[2rem] font-black text-lg shadow-2xl flex items-center justify-center space-x-2 transition-transform active:scale-95 group">
      <span>Mulai Sekarang</span>
      <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
    </button>

    <div class="text-center">
      <p class="text-[10px] font-bold text-slate-300 uppercase tracking-[0.25em]">
        Powered by Ko+Lab Creative Hub
      </p>
    </div>
  </div>
</div>

<!-- LOGIN SCREEN SECTION -->
<div id="login-screen" class="hidden opacity-0 flex-col min-h-[100dvh] relative bg-sunrise-animate transition-opacity duration-500 ease-in-out">

  <!-- Vanta Container -->
  <div id="vanta-bg" class="absolute inset-0 z-0 w-full h-full pointer-events-none"></div>

  <!-- Overlay -->
  <div class="absolute inset-0 bg-white/10 z-[1] backdrop-blur-[1px]"></div>

  <div class="flex flex-col min-h-[100dvh] p-6 md:p-12 justify-center relative z-10">
    <div class="max-w-md mx-auto w-full bg-white/90 backdrop-blur-3xl p-10 rounded-[3rem] shadow-2xl border border-white/60 relative">
      <button
        onclick="showSplashScreen()"
        class="absolute top-8 left-8 p-2 text-slate-400 hover:text-orange-600 transition-colors">
        <i data-lucide="chevron-left" class="w-6 h-6"></i>
      </button>

      <div class="mb-10 text-center pt-4">
        <img src="https://ekop.kopgiat.id/logo_giat.ico" alt="Logo" class="w-14 h-14 mx-auto mb-4 object-contain">
        <h2 class="text-3xl font-black text-slate-900 tracking-tight text-center">Login Anggota</h2>
        <p class="text-slate-500 font-bold text-[10px] uppercase tracking-widest mt-1">APLIKASI DIGITAL KOPERASI GIAT</p>
      </div>

      <!-- Display Error Messages -->
      <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm font-bold text-center border border-red-100">
          <?= session()->getFlashdata('error') ?>
        </div>
      <?php endif; ?>

      <form action="<?= url_to('auth_login_proc') ?>" method="post" class="space-y-6" onsubmit="handleLoginSubmit(this)">
        <?= csrf_field() ?>

        <div>
          <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 block">Username / ID Anggota</label>
          <div class="relative">
            <i data-lucide="user" class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 w-[18px] h-[18px]"></i>
            <input
              type="text"
              name="username"
              class="w-full bg-white/50 border border-slate-100 rounded-2xl py-4 pl-14 pr-4 focus:ring-2 focus:ring-orange-500 outline-none transition-all font-bold text-slate-900 placeholder-slate-300"
              placeholder="GIATXXXX"
              value="<?= old('username') ?>"
              required />
          </div>
        </div>

        <div>
          <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 block">Kata Sandi</label>
          <div class="relative">
            <i data-lucide="lock" class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 w-[18px] h-[18px]"></i>
            <input
              type="password"
              name="password"
              class="w-full bg-white/50 border border-slate-100 rounded-2xl py-4 pl-14 pr-4 focus:ring-2 focus:ring-orange-500 outline-none transition-all font-bold text-slate-900 placeholder-slate-300"
              placeholder="••••••••"
              required />
          </div>
        </div>

        <div class="flex justify-end">
          <a href="<?= base_url('forgot-password') ?>" class="text-[10px] font-black text-orange-600 uppercase tracking-widest hover:underline transition-all">Lupa Password?</a>
        </div>

        <button
          type="submit"
          id="btn-login"
          class="w-full bg-animate-red-navy text-white py-5 rounded-[2rem] font-black text-lg shadow-xl shadow-orange-100 flex items-center justify-center space-x-2 active:opacity-90 transition-all disabled:opacity-70">
          <span id="btn-text">Masuk Portal</span>
          <i data-lucide="log-in" class="w-5 h-5" id="btn-icon"></i>
          <!-- Loading Spinner (Hidden by default) -->
          <div id="btn-loading" class="hidden w-6 h-6 border-2 border-white/20 border-t-white rounded-full animate-spin"></div>
        </button>
      </form>

      <div class="mt-8 text-center">
        <a href="<?= base_url('registrasi') ?>" class="text-sm font-bold text-slate-500 hover:text-slate-800 transition-colors">
          Belum punya akun? <span class="text-animate-red-navy font-black underline underline-offset-4">Daftar sekarang</span>
        </a>
      </div>

      <div class="mt-8 pt-8 border-t border-slate-100 text-center">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
          Punya kendala akses? <span class="text-orange-600 cursor-pointer font-black underline underline-offset-4">Hubungi Admin</span>
        </p>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  let vantaEffect = null;

  function initVanta() {
    if (!vantaEffect && window.VANTA) {
      vantaEffect = window.VANTA.WAVES({
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
  }

  function destroyVanta() {
    if (vantaEffect) {
      vantaEffect.destroy();
      vantaEffect = null;
    }
  }

  function showLoginForm() {
    const splash = document.getElementById('splash-screen');
    const login = document.getElementById('login-screen');

    splash.classList.add('opacity-0', '-translate-x-full', 'absolute');
    setTimeout(() => {
      splash.classList.add('hidden');
      splash.classList.remove('absolute');

      login.classList.remove('hidden');
      // Trigger reflow
      void login.offsetWidth;
      login.classList.remove('opacity-0');

      // Init Vanta after transition
      setTimeout(initVanta, 100);
    }, 500);
  }

  function showSplashScreen() {
    const splash = document.getElementById('splash-screen');
    const login = document.getElementById('login-screen');

    login.classList.add('opacity-0');
    setTimeout(() => {
      login.classList.add('hidden');
      destroyVanta();

      splash.classList.remove('hidden', 'absolute', '-translate-x-full');
      // Trigger reflow
      void splash.offsetWidth;
      splash.classList.remove('opacity-0');
    }, 500);
  }

  function handleLoginSubmit(form) {
    const btn = document.getElementById('btn-login');
    const text = document.getElementById('btn-text');
    const icon = document.getElementById('btn-icon');
    const loading = document.getElementById('btn-loading');

    btn.disabled = true;
    text.classList.add('hidden');
    if (icon) icon.classList.add('hidden');
    loading.classList.remove('hidden');

    // Allow form submission to proceed
    return true;
  }
</script>
<?= $this->endSection() ?>