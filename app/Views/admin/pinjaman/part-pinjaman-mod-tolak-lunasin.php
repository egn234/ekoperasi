<div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm bg-white rounded-[2rem] shadow-2xl p-8 text-center z-50">
  <!-- Icon -->
  <div class="w-16 h-16 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-6">
    <i data-lucide="x-circle" class="w-8 h-8"></i>
  </div>

  <!-- Title & Desc -->
  <h3 class="text-xl font-black text-slate-900 mb-2">Tolak Pelunasan?</h3>
  <p class="text-sm text-slate-500 mb-6">
    Untuk peminjam: <strong class="text-slate-800"><?= $user[0]['username'] ?></strong>
  </p>

  <!-- Warning Card -->
  <div class="bg-red-50 border border-red-100 rounded-xl p-4 mb-8 text-left">
    <div class="flex gap-3">
      <div class="text-red-600 shrink-0">
        <i data-lucide="alert-octagon" class="w-5 h-5"></i>
      </div>
      <div>
        <h6 class="text-xs font-black text-red-700 uppercase tracking-wider mb-1">Konfirmasi Tolak</h6>
        <p class="text-[10px] leading-relaxed font-bold text-red-600">
          Pengajuan pelunasan ini akan dibatalkan dan pinjaman akan tetap berjalan seperti biasa.
        </p>
      </div>
    </div>
  </div>

  <!-- Action Buttons -->
  <div class="flex gap-3">
    <button onclick="closeModal()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">
      Batal
    </button>
    <a href="<?= url_to('admin_tolak_lunas', $idpinjaman) ?>" class="flex-1 py-3 bg-red-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-200 transition-all hover:scale-[1.02]">
      Tolak
    </a>
  </div>
</div>

<script>
  if (window.lucide) window.lucide.createIcons();
</script>