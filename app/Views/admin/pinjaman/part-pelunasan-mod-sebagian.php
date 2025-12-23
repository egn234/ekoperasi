<div class="text-center mb-6">
  <div class="w-16 h-16 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-4">
    <i data-lucide="coins" class="w-8 h-8"></i>
  </div>
  <h3 class="text-xl font-black text-slate-900">Pelunasan Sebagian</h3>
  <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
    <?= $user[0]['nama_lengkap'] ?>
  </p>
</div>

<!-- Loan Summary Card -->
<div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 mb-6">
  <h6 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Ringkasan Pinjaman</h6>
  <div class="grid grid-cols-2 gap-4">
    <div class="bg-white p-3 rounded-xl border border-slate-200/50">
      <p class="text-[9px] font-bold text-slate-400 uppercase">Tenor</p>
      <p class="text-sm font-black text-slate-700"><?= $pinjaman->angsuran_bulanan ?> Bln</p>
    </div>
    <div class="bg-white p-3 rounded-xl border border-slate-200/50 text-right">
      <p class="text-[9px] font-bold text-slate-400 uppercase">Sisa</p>
      <p class="text-sm font-black text-slate-700"><?= $sisa_cicilan ?> Bln</p>
    </div>
    <div class="col-span-2 bg-white p-4 rounded-xl border border-slate-200/50 flex justify-between items-center">
      <div>
        <p class="text-[9px] font-bold text-slate-400 uppercase">Sisa Pinjaman</p>
        <p class="text-lg font-black text-slate-900">Rp <?= number_format($sisa_pinjaman, 0, ',', '.') ?></p>
      </div>
      <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
        <i data-lucide="wallet" class="w-5 h-5"></i>
      </div>
    </div>
  </div>
</div>

<form action="<?= base_url() ?>admin/pinjaman/lunasi-partial/<?= $idpinjaman ?>" id="formPartial" method="post">
  <div class="mb-6">
    <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Jumlah Cicilan yang Dilunasi</label>
    <div class="relative">
      <input type="number"
        id="bulan_<?= $idpinjaman ?>"
        name="bulan_bayar"
        min="1"
        max="<?= $sisa_cicilan ?>"
        class="w-full pl-4 pr-16 py-4 bg-white border-2 border-slate-100 rounded-2xl text-lg font-black text-slate-900 focus:outline-none focus:border-blue-500 transition-colors"
        placeholder="0"
        required>
      <span class="absolute right-4 top-4.5 text-xs font-black text-slate-400 uppercase tracking-widest">Bulan</span>
    </div>
    <p class="text-[10px] text-slate-400 mt-2 italic">*Maksimal <?= $sisa_cicilan ?> bulan</p>
  </div>
</form>

<!-- Calculation Result -->
<div class="bg-emerald-600 rounded-[2rem] p-6 text-white shadow-lg shadow-emerald-100 mb-8 flex justify-between items-center">
  <div class="flex items-center gap-3">
    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
      <i data-lucide="calculator" class="w-5 h-5"></i>
    </div>
    <div>
      <p class="text-[10px] font-bold text-white/70 uppercase tracking-widest leading-none mb-1">Total Bayar</p>
      <p class="text-xs font-bold text-white opacity-80">Otomatis Terkalkulasi</p>
    </div>
  </div>
  <div class="text-right">
    <h4 class="text-xl font-black tracking-tight">Rp <span id="perkalian_<?= $idpinjaman ?>">0</span></h4>
  </div>
</div>

<div class="flex gap-3 pt-2 border-t border-slate-100">
  <button onclick="closeModal()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">
    Batal
  </button>
  <button type="submit" form="formPartial" class="flex-1 py-3 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all hover:scale-[1.02]">
    Bayar Sekarang
  </button>
</div>

<script type="text/javascript">
  $('#bulan_<?= $idpinjaman ?>').on("input", function() {
    var value = parseFloat($(this).val()) || 0;
    var calculatedResult = value * <?= $nominal_cicilan ?>;
    $('#perkalian_<?= $idpinjaman ?>').text(new Intl.NumberFormat('id-ID').format(calculatedResult));
  });
  if (window.lucide) window.lucide.createIcons();
</script>