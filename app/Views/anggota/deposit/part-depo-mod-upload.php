<div class="text-center mb-8">
  <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
    <i data-lucide="cloud-upload" class="w-8 h-8"></i>
  </div>
  <h3 class="text-xl font-black text-slate-900">Upload Bukti Transfer</h3>
  <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Verifikasi Transaksi Anda</p>
</div>

<div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6 flex items-start gap-3">
  <i data-lucide="info" class="w-5 h-5 text-blue-600 shrink-0 mt-0.5"></i>
  <div>
    <h6 class="text-xs font-black text-blue-700 uppercase tracking-wide mb-1">Petunjuk Upload</h6>
    <ul class="text-xs font-medium text-blue-600 space-y-1 list-disc list-inside">
      <li>Format: PDF, JPG, atau JPEG</li>
      <li>Ukuran Maksimal: 5MB</li>
      <li>Pastikan bukti transfer terlihat jelas</li>
    </ul>
  </div>
</div>

<form action="<?= url_to('an_de_upbkttrf', $a->iddeposit) ?>" id="form_upload_bukti_<?= $a->iddeposit ?>" method="post" enctype="multipart/form-data">
  <div class="mb-4">
    <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">
      Pilih File Bukti
    </label>

    <div class="upload-area group relative w-full h-40 rounded-2xl border-2 border-dashed border-slate-200 hover:border-blue-500 hover:bg-blue-50/50 transition-all cursor-pointer flex flex-col items-center justify-center text-center">
      <input type="file"
        name="bukti_transfer"
        id="bkt_trf"
        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
        accept="image/jpg,image/jpeg,application/pdf"
        required>

      <div class="upload-content pointer-events-none group-hover:scale-110 transition-transform duration-300">
        <i data-lucide="upload-cloud" class="w-10 h-10 text-slate-300 group-hover:text-blue-500 mx-auto mb-3 transition-colors"></i>
        <p class="text-sm font-bold text-slate-600 group-hover:text-blue-600 transition-colors">Klik atau Geser File Kesini</p>
        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">JPG, JPEG, PDF (Max 5MB)</p>
      </div>

      <div class="upload-preview hidden pointer-events-none">
        <i data-lucide="file-check" class="w-10 h-10 text-emerald-500 mx-auto mb-3"></i>
        <p class="text-sm font-black text-emerald-600 file-name truncate max-w-[200px]"></p>
        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1 file-size"></p>
      </div>
    </div>
    <div class="text-[10px] text-red-500 font-bold mt-2 hidden" id="upload-error">File wajib diisi!</div>
  </div>
</form>

<div class="mt-8 pt-4 border-t border-slate-100 flex gap-3">
  <button onclick="ModalHelper.close()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">
    Batal
  </button>
  <button type="submit" form="form_upload_bukti_<?= $a->iddeposit ?>" class="flex-1 py-3 bg-blue-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all hover:scale-[1.02]">
    Upload
  </button>
</div>

<script>
  // Simple File Upload Logic scoped to this modal
  (function() {
    const input = document.getElementById('bkt_trf');
    const content = input.parentElement.querySelector('.upload-content');
    const preview = input.parentElement.querySelector('.upload-preview');
    const nameEl = preview.querySelector('.file-name');
    const sizeEl = preview.querySelector('.file-size');

    input.addEventListener('change', function(e) {
      const file = this.files[0];
      if (file) {
        content.classList.add('hidden');
        preview.classList.remove('hidden');
        nameEl.textContent = file.name;
        sizeEl.textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
      } else {
        content.classList.remove('hidden');
        preview.classList.add('hidden');
      }
    });
  })();
</script>