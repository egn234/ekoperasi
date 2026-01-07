<div class="text-center mb-6">
    <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
        <i data-lucide="refresh-cw" class="w-8 h-8"></i>
    </div>
    <h3 class="text-xl font-black text-slate-900">Ubah Status?</h3>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">
        Ubah status akun <strong><?= $user[0]->username ?></strong> menjadi
        <span class="<?= $user[0]->user_flag == 0 ? 'text-emerald-500' : 'text-red-500' ?>"><?= $user[0]->user_flag == 0 ? 'AKTIF' : 'NONAKTIF' ?></span>?
    </p>
</div>

<div class="flex gap-3">
    <button onclick="ModalHelper.close()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">
        Batal
    </button>
    <a href="<?= url_to('admin_user_switch', $user[0]->iduser) ?>" class="flex-1 py-3 bg-blue-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 shadow-lg shadow-blue-200 text-center transition-all hover:scale-[1.02]">
        Konfirmasi
    </a>
</div>