<div class="text-center mb-6">
    <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4">
        <i data-lucide="check-circle" class="w-8 h-8"></i>
    </div>
    <h3 class="text-xl font-black text-slate-900">Pelunasan Pinjaman</h3>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Ajukan pelunasan dipercepat</p>
</div>

<div class="bg-indigo-50 border border-indigo-200 rounded-2xl p-4 mb-6 flex gap-3">
    <i data-lucide="info" class="w-5 h-5 text-indigo-600 shrink-0 mt-0.5"></i>
    <div class="text-xs font-medium text-indigo-800 leading-relaxed">
        <p class="font-black uppercase tracking-tight mb-1">Instruksi:</p>
        <ul class="list-disc ml-4 space-y-1">
            <li>Gabungkan Form Pelunasan dan Bukti Transfer dalam 1 file PDF.</li>
            <li>Anda dapat mendapatkan form pelunasan di kantor Koperasi GIAT.</li>
        </ul>
    </div>
</div>

<form id="lunasiPinjamanForm_<?= $a->idpinjaman ?>" action="<?= base_url() ?>anggota/pinjaman/lunasi_proc/<?= $a->idpinjaman ?>" method="post" enctype="multipart/form-data">
    <div class="space-y-4">
        <label class="block text-xs font-black text-slate-500 uppercase tracking-wider">Unggah Berkas (.pdf, .jpg, .jpeg)</label>
        <div class="relative group">
            <input type="file" name="bukti_bayar" id="frmprstjn" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".pdf, .jpg, .jpeg" required onchange="updateFileName(this)">
            <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl p-6 flex flex-col items-center justify-center gap-3 group-hover:border-emerald-400 group-hover:bg-emerald-50/50 transition-all text-center">
                <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-sm text-slate-400 group-hover:text-emerald-600">
                    <i data-lucide="file-up" class="w-6 h-6"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-black text-slate-700 truncate file-name">Pilih file...</p>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Klik untuk telusuri</p>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="mt-8 pt-4 border-t border-slate-100 flex gap-3">
    <button onclick="ModalHelper.close()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">Batal</button>
    <button type="submit" form="lunasiPinjamanForm_<?= $a->idpinjaman ?>" class="flex-1 py-3 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all hover:scale-[1.02]">Kirim Pelunasan</button>
</div>

<script>
    function updateFileName(input) {
        const parent = input.closest('.relative');
        const fileNameDisplay = parent.querySelector('.file-name');
        if (input.files && input.files[0]) {
            fileNameDisplay.textContent = input.files[0].name;
            fileNameDisplay.classList.remove('text-slate-700');
            fileNameDisplay.classList.add('text-emerald-600');
        } else {
            fileNameDisplay.textContent = 'Pilih file...';
            fileNameDisplay.classList.add('text-slate-700');
            fileNameDisplay.classList.remove('text-emerald-600');
        }
    }
    if (window.lucide) window.lucide.createIcons();
</script>