<div class="text-center mb-6">
    <div class="w-16 h-16 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-4">
        <i data-lucide="trash-2" class="w-8 h-8"></i>
    </div>
    <h3 class="text-xl font-black text-slate-900">Hapus User?</h3>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
        Konfirmasi penghapusan akun <strong><?= $user[0]['username'] ?></strong>
    </p>
</div>

<div class="bg-red-50 border border-red-100 rounded-xl p-4 mb-6 text-center">
    <p class="text-xs font-bold text-red-600 uppercase tracking-wide">Tindakan ini tidak dapat dibatalkan</p>
</div>

<div class="flex gap-3">
    <button onclick="ModalHelper.close()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">
        Batal
    </button>
    <a href="<?= url_to('admin_delete_user', $iduser) ?>" class="flex-1 py-3 bg-red-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-200 text-center transition-all hover:scale-[1.02]">
        Ya, Hapus
    </a>
</div>