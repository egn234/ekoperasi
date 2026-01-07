<div class="text-center p-2">
  <!-- Icon -->
  <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-6">
    <i data-lucide="check-circle-2" class="w-8 h-8"></i>
  </div>

  <!-- Title & Desc -->
  <h3 class="text-xl font-black text-slate-900 mb-2">Setujui Pelunasan?</h3>
  <p class="text-sm text-slate-500 mb-6">
    Untuk peminjam: <strong class="text-slate-800"><?= $user[0]['username'] ?></strong>
  </p>

  <!-- Warning Card -->
  <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 mb-8 text-left">
    <div class="flex gap-3">
      <div class="text-amber-600 shrink-0">
        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
      </div>
      <div>
        <h6 class="text-xs font-black text-amber-700 uppercase tracking-wider mb-1">Perhatian!</h6>
        <p class="text-[10px] leading-relaxed font-bold text-amber-600">
          Tindakan ini akan menandai pinjaman sebagai lunas. Pastikan semua pembayaran telah valid.
        </p>
      </div>
    </div>
  </div>

  <!-- Action Buttons -->
  <div class="flex gap-3">
    <button onclick="closeModal()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">
      Batal
    </button>
    <a href="<?= url_to('admin_konfirmasi_lunas', $idpinjaman) ?>" class="flex-1 py-3 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all hover:scale-[1.02]">
      Setujui
    </a>
  </div>
</div>

<script>
  if (window.lucide) window.lucide.createIcons();
  // Assuming closeModal() is globally available from modal-native.js or parent view
</script>