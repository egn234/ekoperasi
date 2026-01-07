<div class="text-center mb-6">
    <div class="w-16 h-16 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center mx-auto mb-4">
        <i data-lucide="upload-cloud" class="w-8 h-8"></i>
    </div>
    <h3 class="text-xl font-black text-slate-900">Unggah Dokumen</h3>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Lengkapi berkas pengajuan Anda</p>
</div>

<form action="<?= url_to('an_de_upfrmprstjn', $a->idpinjaman) ?>" id="form_upload_form_<?= $a->idpinjaman ?>" method="post" enctype="multipart/form-data">
    <div class="space-y-5">
        <!-- Form Persetujuan -->
        <div>
            <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Form Persetujuan (.pdf)</label>
            <div class="relative group">
                <input type="file" name="form_bukti" id="frmprstjn" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".pdf" required onchange="updateFileName(this)">
                <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl p-4 flex items-center gap-3 group-hover:border-indigo-400 group-hover:bg-indigo-50/50 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm text-slate-400 group-hover:text-indigo-600">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-700 truncate file-name">Pilih file...</p>
                        <p class="text-[10px] text-slate-400 font-medium">Klik untuk telusuri media</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slip Gaji -->
        <div>
            <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Slip Gaji (.pdf)</label>
            <div class="relative group">
                <input type="file" name="slip_gaji" id="slipgaji" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".pdf" required onchange="updateFileName(this)">
                <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl p-4 flex items-center gap-3 group-hover:border-indigo-400 group-hover:bg-indigo-50/50 transition-all">
                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm text-slate-400 group-hover:text-indigo-600">
                        <i data-lucide="file-spreadsheet" class="w-5 h-5"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-700 truncate file-name">Pilih file...</p>
                        <p class="text-[10px] text-slate-400 font-medium">Klik untuk telusuri media</p>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($duser->status_pegawai == 'kontrak') { ?>
            <!-- Bukti Kontrak -->
            <div>
                <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Bukti Kontrak (.pdf)</label>
                <div class="relative group">
                    <input type="file" name="form_kontrak" id="formKontrak" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" accept=".pdf" required onchange="updateFileName(this)">
                    <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl p-4 flex items-center gap-3 group-hover:border-indigo-400 group-hover:bg-indigo-50/50 transition-all">
                        <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm text-slate-400 group-hover:text-indigo-600">
                            <i data-lucide="file-check" class="w-5 h-5"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-700 truncate file-name">Pilih file...</p>
                            <p class="text-[10px] text-slate-400 font-medium">Klik untuk telusuri media</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</form>

<div class="mt-8 pt-4 border-t border-slate-100 flex gap-3">
    <button onclick="ModalHelper.close()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">Batal</button>
    <button type="submit" form="form_upload_form_<?= $a->idpinjaman ?>" class="flex-1 py-3 bg-yellow-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-yellow-700 shadow-lg shadow-yellow-200 transition-all hover:scale-[1.02]">Simpan Berkas</button>
</div>

<script>
    function updateFileName(input) {
        const parent = input.closest('.relative');
        const fileNameDisplay = parent.querySelector('.file-name');
        if (input.files && input.files[0]) {
            fileNameDisplay.textContent = input.files[0].name;
            fileNameDisplay.classList.remove('text-slate-700');
            fileNameDisplay.classList.add('text-indigo-600');
        } else {
            fileNameDisplay.textContent = 'Pilih file...';
            fileNameDisplay.classList.add('text-slate-700');
            fileNameDisplay.classList.remove('text-indigo-600');
        }
    }
    if (window.lucide) window.lucide.createIcons();
</script>