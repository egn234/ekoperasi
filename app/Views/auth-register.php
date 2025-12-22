<?= $this->extend('layout/auth') ?>

<?= $this->section('content') ?>

<div class="min-h-screen bg-sunrise-animate relative p-4 md:p-8 flex items-center justify-center">
  <!-- Vanta Container -->
  <div id="vanta-bg" class="absolute inset-0 z-0 w-full h-full pointer-events-none"></div>
  <!-- Overlay -->
  <div class="absolute inset-0 bg-white/10 z-[1] backdrop-blur-[1px]"></div>

  <div class="relative z-10 w-full max-w-4xl bg-white/90 backdrop-blur-3xl rounded-[2.5rem] shadow-2xl border border-white/60 overflow-hidden">

    <div class="p-8 md:p-12">
      <!-- Header -->
      <div class="text-center mb-10">
        <a href="/" class="inline-block mb-4">
          <img src="https://ekop.kopgiat.id//logo_giat.ico" alt="" class="h-12 w-auto mx-auto drop-shadow-md">
        </a>
        <h2 class="text-3xl font-black text-slate-900 tracking-tight">Registrasi Anggota</h2>
        <p class="text-slate-500 font-bold text-[10px] uppercase tracking-widest mt-2">Bergabung dengan Ekoperasi Giat</p>
      </div>

      <!-- Notification -->
      <?php if (session()->getFlashdata('notif')): ?>
        <div class="bg-blue-50 text-blue-700 p-4 rounded-2xl mb-8 text-sm font-medium border border-blue-100 flex items-center gap-3">
          <i data-lucide="info" class="w-5 h-5 flex-shrink-0"></i>
          <div><?= session()->getFlashdata('notif') ?></div>
        </div>
      <?php endif; ?>

      <form action="<?= url_to('reg_proc') ?>" method="post" id="register_form" enctype="multipart/form-data" class="space-y-6">
        <!-- Hidden reCAPTCHA -->
        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

        <!-- 2 Column Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

          <!-- Left Column -->
          <div class="space-y-6">
            <div>
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Nama Lengkap <span class="text-red-500">*</span></label>
              <input type="text" name="nama_lengkap" class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-slate-800 transition-all placeholder:font-normal" value="<?= session()->getFlashdata('nama_lengkap') ?>" required>
            </div>

            <div>
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Nomor KTP/NIK <span class="text-red-500">*</span></label>
              <input type="number" name="nik" class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-slate-800 transition-all placeholder:font-normal" value="<?= session()->getFlashdata('nik') ?>" required>
            </div>

            <div>
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">NIP</label>
              <input type="number" name="nip" class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-slate-800 transition-all placeholder:font-normal" value="<?= session()->getFlashdata('nip') ?>">
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Tempat Lahir <span class="text-red-500">*</span></label>
                <input type="text" name="tempat_lahir" class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-slate-800 transition-all" value="<?= session()->getFlashdata('tempat_lahir') ?>" required>
              </div>
              <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Tgl Lahir <span class="text-red-500">*</span></label>
                <input type="date" name="tanggal_lahir" class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-slate-800 transition-all" value="<?= session()->getFlashdata('tanggal_lahir') ?>" required>
              </div>
            </div>

            <div>
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Instansi <span class="text-red-500">*</span></label>
              <div class="relative">
                <select name="instansi" class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-slate-800 appearance-none bg-none transition-all" required>
                  <option value="" disabled selected>Pilih Instansi...</option>
                  <option value="YPT" <?= (session()->getFlashdata('instansi') == 'YPT') ? 'selected' : '' ?>>YPT</option>
                  <option value="Universitas Telkom" <?= (session()->getFlashdata('instansi') == 'Universitas Telkom') ? 'selected' : '' ?>>Universitas Telkom</option>
                  <option value="Trengginas Jaya" <?= (session()->getFlashdata('instansi') == 'Trengginas Jaya') ? 'selected' : '' ?>>Trengginas Jaya</option>
                  <option value="BUT" <?= (session()->getFlashdata('instansi') == 'BUT') ? 'selected' : '' ?>>BUT</option>
                  <option value="Telkom" <?= (session()->getFlashdata('instansi') == 'Telkom') ? 'selected' : '' ?>>Telkom</option>
                  <option value="GIAT" <?= (session()->getFlashdata('instansi') == 'GIAT') ? 'selected' : '' ?>>GIAT</option>
                </select>
                <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4 pointer-events-none"></i>
              </div>
            </div>
          </div>

          <!-- Right Column -->
          <div class="space-y-6">
            <div>
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Unit Kerja/Divisi <span class="text-red-500">*</span></label>
              <input type="text" name="unit_kerja" class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-slate-800 transition-all" value="<?= session()->getFlashdata('unit_kerja') ?>" required>
            </div>

            <div>
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Jenis Kepegawaian <span class="text-red-500">*</span></label>
              <div class="relative">
                <select name="status_pegawai" class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-slate-800 appearance-none transition-all" required>
                  <option value="" disabled selected>Pilih...</option>
                  <option value="tetap" <?= (session()->getFlashdata('status_pegawai') == 'tetap') ? 'selected' : '' ?>>Tetap</option>
                  <option value="kontrak" <?= (session()->getFlashdata('status_pegawai') == 'kontrak') ? 'selected' : '' ?>>Kontrak</option>
                </select>
                <i data-lucide="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4 pointer-events-none"></i>
              </div>
            </div>

            <div>
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Alamat <span class="text-red-500">*</span></label>
              <textarea name="alamat" rows="2" class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-slate-800 resize-none transition-all" required><?= session()->getFlashdata('alamat') ?></textarea>
            </div>

            <div class="grid grid-cols-3 gap-4">
              <div class="col-span-1">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Bank <span class="text-red-500">*</span></label>
                <input type="text" name="nama_bank" class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-slate-800 transition-all" value="<?= session()->getFlashdata('nama_bank') ?>" required>
              </div>
              <div class="col-span-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">No Rekening <span class="text-red-500">*</span></label>
                <input type="number" name="no_rek" class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-slate-800 transition-all" value="<?= session()->getFlashdata('no_rek') ?>" required>
              </div>
            </div>
          </div>
        </div>

        <div class="h-px bg-slate-100 my-8"></div>

        <!-- Account & File Uploads -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="space-y-6">
            <div>
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">No. Telepon / WA <span class="text-red-500">*</span></label>
              <input type="number" name="nomor_telepon" class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-slate-800 transition-all" value="<?= session()->getFlashdata('nomor_telepon') ?>" required>
            </div>
            <div>
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Email <span class="text-red-500">*</span></label>
              <input type="email" name="email" class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 px-4 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-slate-800 transition-all" value="<?= session()->getFlashdata('email') ?>" required>
            </div>
            <div>
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Username <span class="text-red-500">*</span></label>
              <input type="text" name="username" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-3 px-4 font-bold text-slate-500 cursor-not-allowed" value="<?= $username ?>" readonly required>
              <p class="text-[10px] text-slate-400 mt-1">*Generated automatically</p>
            </div>
          </div>

          <div class="space-y-6">
            <div class="relative">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Password <span class="text-red-500">*</span></label>
              <div class="relative">
                <input type="password" name="pass" id="pass" minlength="8" class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 pl-4 pr-12 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-slate-800 transition-all" required>
                <button type="button" onclick="togglePassword('pass', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                  <i data-lucide="eye" class="w-5 h-5"></i>
                </button>
              </div>
            </div>

            <div class="relative">
              <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2 block">Ulangi Password <span class="text-red-500">*</span></label>
              <div class="relative">
                <input type="password" name="pass2" id="pass2" minlength="8" class="w-full bg-white/50 border border-slate-200 rounded-2xl py-3 pl-4 pr-12 focus:ring-2 focus:ring-orange-500 outline-none font-bold text-slate-800 transition-all" required>
                <button type="button" onclick="togglePassword('pass2', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                  <i data-lucide="eye" class="w-5 h-5"></i>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Uplods -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
          <div class="border-2 border-dashed border-slate-200 rounded-3xl p-6 text-center hover:bg-slate-50 transition-colors">
            <i data-lucide="image" class="w-8 h-8 mx-auto text-slate-400 mb-2"></i>
            <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] block mb-2 cursor-pointer hover:text-orange-600">
              Foto Profil
              <input type="file" name="profil_pic" class="hidden" accept="image/jpg, image/jpeg" required onchange="updateFileName(this)">
            </label>
            <p class="text-[10px] text-slate-400 file-name">JPG/JPEG, Max 2MB</p>
          </div>

          <div class="border-2 border-dashed border-slate-200 rounded-3xl p-6 text-center hover:bg-slate-50 transition-colors">
            <i data-lucide="file-check" class="w-8 h-8 mx-auto text-slate-400 mb-2"></i>
            <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] block mb-2 cursor-pointer hover:text-orange-600">
              Upload KTP / Identitas
              <input type="file" name="ktp_file" class="hidden" accept="image/jpg, image/jpeg, image/png, application/pdf" required onchange="updateFileName(this)">
            </label>
            <p class="text-[10px] text-slate-400 file-name">PDF/IMG, Max 4MB</p>
          </div>
        </div>


        <!-- Actions -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-6 pt-6 mt-6 border-t border-slate-100">
          <a href="<?= url_to('/') ?>" class="text-sm font-bold text-slate-400 hover:text-orange-600 transition-colors">
            Sudah punya akun? Login
          </a>

          <button type="button" onclick="openPaktaModal()" class="w-full md:w-auto bg-animate-red-navy text-white px-8 py-4 rounded-[1.5rem] font-black text-sm uppercase tracking-widest shadow-xl shadow-orange-100 hover:scale-105 active:scale-95 transition-all">
            Lanjut Registrasi
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

<!-- PAKTA MODAL -->
<div id="pakta-modal" class="fixed inset-0 z-50 hidden">
  <!-- Overlay -->
  <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closePaktaModal()"></div>

  <!-- Modal Content -->
  <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-2xl bg-white rounded-[2rem] shadow-2xl p-8 max-h-[90vh] overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
      <h3 class="text-xl font-black text-slate-900 tracking-tight">PAKTA INTEGRITAS</h3>
      <button onclick="closePaktaModal()" class="p-2 hover:bg-slate-100 rounded-full transition-colors">
        <i data-lucide="x" class="w-6 h-6 text-slate-400"></i>
      </button>
    </div>

    <div class="prose prose-sm prose-slate mb-8 bg-slate-50 p-6 rounded-2xl border border-slate-100">
      <p class="font-bold text-slate-700">BERSEDIA MENJADI ANGGOTA KOPERASI GIAT SEJAHTERA BERSAMA DAN SANGGUP MEMATUHI SEGALA PERATURAN YANG BERLAKU DI KOPERASI.</p>
      <p class="mb-4">BERSAMA INI SAYA BERSEDIA MENAATI PERATURAN BERIKUT:</p>
      <ul class="space-y-2 list-none pl-0">
        <li class="flex gap-3">
          <span class="font-black text-orange-500">1.</span>
          <span>MEMBAYAR IURAN POKOK SEBESAR <span class="font-bold text-slate-900">Rp. <?= number_format($simp_pokok->nilai ?? 0, 0, ',', '.') ?></span></span>
        </li>
        <li class="flex gap-3">
          <span class="font-black text-orange-500">2.</span>
          <span>MEMBAYAR IURAN WAJIB SEBESAR <span class="font-bold text-slate-900">Rp. <?= number_format($simp_wajib->nilai ?? 0, 0, ',', '.') ?></span></span>
        </li>
        <li class="flex gap-3">
          <span class="font-black text-orange-500">3.</span>
          <span>BUKAN PEGAWAI DENGAN STATUS TENAGA LEPAS HARIAN</span>
        </li>
      </ul>
      <p class="mt-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Demikian permohonan ini saya buat dengan sebenarnya tanpa paksaan dari siapapun.</p>
    </div>

    <div class="space-y-6">
      <label class="flex items-start gap-4 p-4 rounded-2xl border-2 border-slate-100 cursor-pointer hover:border-orange-200 transition-colors">
        <input type="checkbox" id="konfirmasi_check" class="w-5 h-5 mt-1 rounded text-orange-600 focus:ring-orange-500 border-slate-300" onchange="toggleSubmitBtn(this)">
        <span class="text-sm font-bold text-slate-600 select-none">Saya telah membaca dan menyetujui seluruh syarat dan ketentuan yang berlaku.</span>
      </label>

      <button type="submit" id="btn-submit-final" form="register_form" disabled class="w-full bg-slate-200 text-slate-400 py-4 rounded-[1.5rem] font-black text-sm uppercase tracking-widest transition-all disabled:cursor-not-allowed">
        Setuju & Daftar
      </button>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php if (ENVIRONMENT !== 'development'): ?>
  <script src="https://www.google.com/recaptcha/api.js?render=<?= getenv('RECAPTCHA_SITE_KEY') ?>"></script>
  <script>
    const SITE_KEY = "<?= getenv('RECAPTCHA_SITE_KEY') ?>";

    function refreshCaptcha() {
      grecaptcha.ready(function() {
        grecaptcha.execute(SITE_KEY, {
          action: 'register'
        }).then(function(token) {
          document.getElementById('g-recaptcha-response').value = token;
        });
      });
    }

    setInterval(refreshCaptcha, 120000);

    document.getElementById('register_form').addEventListener('submit', function(e) {
      e.preventDefault();
      grecaptcha.ready(function() {
        grecaptcha.execute(SITE_KEY, {
          action: 'register'
        }).then(function(token) {
          document.getElementById('g-recaptcha-response').value = token;
          document.getElementById('register_form').submit();
        });
      });
    });
  </script>
<?php else: ?>
  <script>
    document.getElementById('g-recaptcha-response').value = 'dev-bypass-token';
  </script>
<?php endif; ?>

<script>
  // Init Vanta
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

  // Helper functions
  function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
      input.type = 'text';
      // icon not changing in Lucide automatically, need to remove/add data-lucide or innerHTML
      // For simplicity, we assume eye icon is fine, key is visibility functionality
      btn.classList.add('text-orange-600');
    } else {
      input.type = 'password';
      btn.classList.remove('text-orange-600');
    }
  }

  function updateFileName(input) {
    const p = input.parentElement.nextElementSibling;
    if (input.files.length > 0) {
      p.textContent = input.files[0].name;
      p.classList.add('text-emerald-500', 'font-bold');
    }
  }

  function openPaktaModal() {
    // Validate form first
    const form = document.getElementById('register_form');
    if (form.checkValidity()) {
      const modal = document.getElementById('pakta-modal');
      modal.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    } else {
      form.reportValidity();
    }
  }

  function closePaktaModal() {
    const modal = document.getElementById('pakta-modal');
    modal.classList.add('hidden');
    document.body.style.overflow = '';
  }

  function toggleSubmitBtn(checkbox) {
    const btn = document.getElementById('btn-submit-final');
    if (checkbox.checked) {
      btn.disabled = false;
      btn.classList.remove('bg-slate-200', 'text-slate-400');
      btn.classList.add('bg-animate-red-navy', 'text-white', 'shadow-xl');
    } else {
      btn.disabled = true;
      btn.classList.add('bg-slate-200', 'text-slate-400');
      btn.classList.remove('bg-animate-red-navy', 'text-white', 'shadow-xl');
    }
  }
</script>
<?= $this->endSection() ?>