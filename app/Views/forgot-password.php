<?= $this->extend('layout/auth') ?>

<?= $this->section('content') ?>

<div class="min-h-screen bg-sunrise-animate relative p-4 md:p-8 flex items-center justify-center">
  <!-- Vanta Container -->
  <div id="vanta-bg" class="absolute inset-0 z-0 w-full h-full pointer-events-none"></div>
  <!-- Overlay -->
  <div class="absolute inset-0 bg-white/10 z-[1] backdrop-blur-[1px]"></div>

  <div class="relative z-10 w-full max-w-lg bg-white/90 backdrop-blur-3xl rounded-[2.5rem] shadow-2xl border border-white/60 p-8 md:p-12">

    <div class="text-center mb-10">
      <a href="/" class="inline-block mb-4">
        <img src="https://ekop.kopgiat.id//logo_giat.ico" alt="" class="h-12 w-auto mx-auto drop-shadow-md">
      </a>
      <h2 class="text-3xl font-black text-slate-900 tracking-tight">Reset Password</h2>
      <p class="text-slate-500 font-bold text-[10px] uppercase tracking-widest mt-2">Masukan data diri terdaftar anda</p>
    </div>

    <?php if (session()->getFlashdata('notif')): ?>
      <div class="bg-blue-50 text-blue-700 p-4 rounded-xl mb-6 text-sm font-bold text-center border border-blue-100">
        <?= session()->getFlashdata('notif') ?>
      </div>
    <?php endif; ?>

    <form action="<?= url_to('forgot_password_proc') ?>" method="post" class="space-y-5">

      <div>
        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Username</label>
        <div class="relative">
          <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 w-5 h-5"></i>
          <input type="text" name="username" class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 pl-12 pr-4 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-slate-900 transition-all placeholder:font-normal" value="<?= session()->getFlashdata('username') ?>" required placeholder="Username Anda">
        </div>
      </div>

      <div>
        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">E-mail</label>
        <div class="relative">
          <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 w-5 h-5"></i>
          <input type="email" name="email" class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 pl-12 pr-4 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-slate-900 transition-all placeholder:font-normal" value="<?= session()->getFlashdata('email') ?>" required placeholder="email@contoh.com">
        </div>
      </div>

      <div>
        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">NIK</label>
        <div class="relative">
          <i data-lucide="credit-card" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 w-5 h-5"></i>
          <input type="text" name="nik" class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 pl-12 pr-4 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-slate-900 transition-all placeholder:font-normal" value="<?= session()->getFlashdata('nik') ?>" required placeholder="Nomor Induk Kependudukan">
        </div>
      </div>

      <div>
        <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">No. Telepon Terdaftar</label>
        <div class="relative">
          <i data-lucide="phone" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 w-5 h-5"></i>
          <input type="text" name="nomor_telepon" class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 pl-12 pr-4 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-slate-900 transition-all placeholder:font-normal" value="<?= session()->getFlashdata('nomor_telepon') ?>" required placeholder="08xxxxxxxxxx">
        </div>
      </div>

      <button type="submit" class="w-full bg-animate-red-navy text-white py-4 rounded-[1.5rem] font-black text-sm uppercase tracking-widest shadow-xl shadow-orange-100 hover:scale-105 active:scale-95 transition-all mt-4">
        Reset Password
      </button>
    </form>

    <div class="mt-8 text-center pt-6 border-t border-slate-100">
      <a href="<?= url_to('/') ?>" class="text-xs font-bold text-slate-400 hover:text-orange-600 transition-colors uppercase tracking-widest">
        <i data-lucide="arrow-left" class="w-4 h-4 inline-block mr-1"></i> Kembali ke Login
      </a>
    </div>

  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
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
</script>
<?= $this->endSection() ?>