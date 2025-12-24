<div class="text-center mb-6">
  <div class="w-16 h-16 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-4">
    <i data-lucide="x-circle" class="w-8 h-8"></i>
  </div>
  <h3 class="text-xl font-black text-slate-900">Tolak Pinjaman</h3>
  <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
    Tolak pengajuan dari <?= $a->nama_peminjam ?>
  </p>
</div>

<form action="<?= url_to('admin_cancel_pinjaman', $a->idpinjaman) ?>" id="formReject" method="post">
  <input type="hidden" name="return_url" value="<?= isset($return_url) ? $return_url : '' ?>">
  <div class="mb-6">
    <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Alasan Penolakan</label>
    <textarea name="alasan_tolak" class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-red-500 focus:border-red-500 block w-full p-4 font-medium placeholder-slate-400 resize-none h-32" placeholder="Jelaskan alasan penolakan secara singkat..." required></textarea>
  </div>
</form>

<div class="flex gap-3 pt-2 border-t border-slate-100">
  <button onclick="closeModal()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">
    Batal
  </button>
  <button type="submit" form="formReject" class="flex-1 py-3 bg-red-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-200 transition-all hover:scale-[1.02]">
    Konfirmasi Tolak
  </button>
</div>