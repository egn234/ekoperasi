<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?php
$total_saldo = $total_saldo_pokok + $total_saldo_wajib + $total_saldo_manasuka;
?>

<div class="space-y-8 pb-12 animate-fade-in-up">

  <!-- Welcome Header -->
  <div class="flex items-center justify-between px-2">
    <div class="flex items-center space-x-4">
      <div class="w-12 h-12 rounded-[1.25rem] bg-slate-200 overflow-hidden border-2 border-white shadow-soft group cursor-pointer">
        <!-- Fallback avatar if no image -->
        <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400">
          <i data-lucide="user" class="w-6 h-6"></i>
        </div>
      </div>
      <div>
        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest mb-0.5">Selamat Datang,</p>
        <h2 class="text-lg font-black text-slate-900 tracking-tight"><?= $duser->nama_lengkap ?? 'Anggota' ?></h2>
      </div>
    </div>
  </div>

  <!-- Notification Flashes -->
  <?php if (session()->getFlashdata('notif_pokok') || session()->getFlashdata('notif_wajib') || session()->getFlashdata('notif_cb')): ?>
    <div class="space-y-2">
      <?= session()->getFlashdata('notif_pokok') ?>
      <?= session()->getFlashdata('notif_wajib') ?>
      <?= session()->getFlashdata('notif_cb') ?>
    </div>
  <?php endif; ?>

  <!-- Total Saldo Banner -->
  <div class="bg-animate-blue rounded-[3rem] p-10 text-white shadow-2xl relative overflow-hidden transition-all hover:scale-[1.01]">
    <div class="relative z-10">
      <p class="text-blue-100 text-xs font-black mb-2 opacity-80 uppercase tracking-widest">Total Saldo Terkumpul</p>
      <h1 class="text-4xl sm:text-5xl font-black mb-6 tracking-tighter">Rp <?= number_format($total_saldo, 0, ',', '.') ?></h1>
      <div class="flex items-center space-x-3 bg-white/10 w-fit px-6 py-3.5 rounded-3xl border border-white/10 backdrop-blur-md">
        <i data-lucide="circle-dollar-sign" class="w-5 h-5 text-rose-300"></i>
        <!-- Placeholder for Loan Balance if avaiable, else just generic text -->
        <p class="text-[10px] font-black text-white uppercase tracking-widest">Saldo Aktif</p>
      </div>
    </div>
    <!-- Decor -->
    <div class="absolute -right-10 -bottom-10 opacity-20 pointer-events-none">
      <i data-lucide="wallet" class="w-64 h-64 text-white"></i>
    </div>
  </div>

  <!-- Dompet Simpanan (Horizontal Scroll) -->
  <section class="space-y-4">
    <h3 class="text-xl font-black text-slate-800 tracking-tight px-4">Dompet Simpanan</h3>
    <div class="overflow-x-auto snap-x hide-scrollbar px-2 pb-4">
      <div class="flex space-x-6 w-fit">
        <!-- Simpanan Pokok -->
        <div class="flex-shrink-0 w-64 h-44 bg-blue-gradient rounded-[2.5rem] p-8 text-white shadow-lg flex flex-col justify-start relative overflow-hidden snap-center transition-transform hover:scale-105">
          <div class="p-3 bg-white/20 rounded-2xl w-fit mb-4"><i data-lucide="wallet" class="w-6 h-6"></i></div>
          <h4 class="text-[10px] font-black opacity-80 uppercase tracking-[0.2em] mb-1">Simpanan Pokok</h4>
          <p class="text-2xl font-black truncate tracking-tighter">Rp <?= number_format($total_saldo_pokok, 0, ',', '.') ?></p>
        </div>

        <!-- Simpanan Wajib -->
        <div class="flex-shrink-0 w-64 h-44 bg-red-gradient rounded-[2.5rem] p-8 text-white shadow-lg flex flex-col justify-start relative overflow-hidden snap-center transition-transform hover:scale-105">
          <div class="p-3 bg-white/20 rounded-2xl w-fit mb-4"><i data-lucide="piggy-bank" class="w-6 h-6"></i></div>
          <h4 class="text-[10px] font-black opacity-80 uppercase tracking-[0.2em] mb-1">Simpanan Wajib</h4>
          <p class="text-2xl font-black truncate tracking-tighter">Rp <?= number_format($total_saldo_wajib, 0, ',', '.') ?></p>
        </div>

        <!-- Simpanan Manasuka -->
        <a href="<?= url_to('anggota/deposit/list') ?>" class="flex-shrink-0 w-64 h-44 bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-lg flex flex-col justify-start relative overflow-hidden snap-center cursor-pointer transition-transform hover:scale-105 group">
          <div class="p-3 bg-white/20 rounded-2xl w-fit mb-4 group-hover:bg-white/30 transition-colors"><i data-lucide="plus-circle" class="w-6 h-6"></i></div>
          <h4 class="text-[10px] font-black opacity-80 uppercase tracking-[0.2em] mb-1">Simpanan Manasuka</h4>
          <p class="text-2xl font-black truncate tracking-tighter">Rp <?= number_format($total_saldo_manasuka, 0, ',', '.') ?></p>
        </a>
      </div>
    </div>
  </section>

  <!-- Quick Actions Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Buttons -->
    <section class="bg-white p-10 rounded-[3rem] shadow-soft border border-slate-50 flex justify-around items-center">
      <a href="<?= url_to('anggota/deposit/list') ?>" class="flex flex-col items-center space-y-2 group">
        <div class="p-6 rounded-[2rem] bg-blue-50 text-blue-600 transition-all group-hover:scale-110 group-hover:shadow-blue-100 group-hover:shadow-lg">
          <i data-lucide="history" class="w-7 h-7"></i>
        </div>
        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-blue-600 transition-colors">Mutasi</span>
      </a>

      <a href="<?= url_to('anggota/pinjaman/list') ?>" class="flex flex-col items-center space-y-2 group">
        <div class="p-6 rounded-[2rem] bg-slate-50 text-slate-400 transition-all group-hover:scale-110 group-hover:bg-blue-50 group-hover:text-blue-600 group-hover:shadow-lg">
          <i data-lucide="credit-card" class="w-7 h-7"></i>
        </div>
        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-blue-600 transition-colors">Pinjaman</span>
      </a>

      <a href="#" class="flex flex-col items-center space-y-2 group">
        <div class="p-6 rounded-[2rem] bg-slate-50 text-slate-400 transition-all group-hover:scale-110 group-hover:bg-blue-50 group-hover:text-blue-600 group-hover:shadow-lg">
          <i data-lucide="info" class="w-7 h-7"></i>
        </div>
        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 group-hover:text-blue-600 transition-colors">Bantuan</span>
      </a>
    </section>

    <!-- Simulation -->
    <section class="bg-white p-10 rounded-[3rem] shadow-soft border border-slate-50">
      <h3 class="text-lg font-black text-slate-800 mb-6 uppercase tracking-wider">Simulasi Pinjaman</h3>
      <div class="space-y-4">
        <input type="number" id="sim-amount" placeholder="Nominal Pinjaman (Rp)" class="w-full bg-slate-50 border border-slate-100 rounded-2xl py-4 px-5 font-black text-sm outline-none focus:ring-2 focus:ring-blue-600 transition-all placeholder:font-medium">
        <button onclick="calculateSim()" class="w-full bg-red-gradient text-white py-4 rounded-2xl font-black text-xs shadow-xl flex items-center justify-center space-x-3 active:scale-95 transition-all uppercase tracking-widest hover:shadow-red-200 hover:shadow-2xl">
          <span>Cek Simulasi</span>
          <i data-lucide="arrow-right" class="w-4 h-4"></i>
        </button>
        <div id="sim-result" class="hidden text-center pt-2">
          <p class="text-[10px] text-slate-400 uppercase tracking-widest">Estimasi Angsuran (12 Bln)</p>
          <p class="text-xl font-black text-slate-800 mt-1" id="sim-value">Rp 0</p>
        </div>
      </div>
    </section>
  </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  function calculateSim() {
    const amount = document.getElementById('sim-amount').value;
    const resultDiv = document.getElementById('sim-result');
    const valueP = document.getElementById('sim-value');

    if (amount && amount > 0) {
      // Simple logic: 10% interest / 12 months (Example)
      // Or just flat division for dummy demo
      const interest = 0.10;
      const total = parseInt(amount) * (1 + interest);
      const monthly = total / 12;

      valueP.textContent = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
      }).format(monthly);

      resultDiv.classList.remove('hidden');
      resultDiv.classList.add('animate-fade-in-up');
    }
  }
</script>
<?= $this->endSection() ?>