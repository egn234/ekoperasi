<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="min-h-[80vh] flex items-center justify-center p-4">
  <div class="w-full max-w-xl">
    <!-- Header -->
    <div class="text-center mb-8">
      <h1 class="text-2xl font-bold text-slate-800">Edit Transaksi</h1>
      <p class="text-slate-500 mt-2 text-sm">Perbarui data pengajuan simpanan atau penarikan</p>
    </div>

    <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden relative">
      <!-- Decorative top bar -->
      <div class="h-2 bg-gradient-to-r from-blue-500 to-indigo-600 w-full"></div>

      <div class="p-8">
        <?= session()->getFlashdata('notif'); ?>

        <form action="<?= base_url('admin/deposit/update_mutasi/' . ($deposit->iddeposit ?? '')) ?>" method="post" id="editForm" class="space-y-6">
          <input type="hidden" name="idanggota" value="<?= $deposit->idanggota ?>">

          <!-- Jenis Pengajuan -->
          <div>
            <label for="jenis_pengajuan" class="block text-sm font-semibold text-slate-700 mb-2">Jenis Transaksi</label>
            <div class="relative">
              <i data-lucide="arrow-left-right" class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
              <select id="jenis_pengajuan" name="jenis_pengajuan" required class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 font-bold rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all outline-none appearance-none">
                <option value="penyimpanan" <?= ($deposit->jenis_pengajuan == 'penyimpanan' ? 'selected' : '') ?>>Penyimpanan (Cash In)</option>
                <option value="penarikan" <?= ($deposit->jenis_pengajuan == 'penarikan' ? 'selected' : '') ?>>Penarikan (Cash Out)</option>
              </select>
              <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
            </div>
          </div>

          <!-- Jenis Simpanan -->
          <div>
            <label for="jenis_deposit" class="block text-sm font-semibold text-slate-700 mb-2">Kategori Simpanan</label>
            <div class="relative">
              <i data-lucide="wallet" class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
              <select id="jenis_deposit" name="jenis_deposit" required class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 font-bold rounded-xl focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all outline-none appearance-none">
                <option value="pokok" <?= ($deposit->jenis_deposit == 'pokok' ? 'selected' : '') ?>>Pokok</option>
                <option value="manasuka" <?= ($deposit->jenis_deposit == 'manasuka' ? 'selected' : '') ?>>Manasuka</option>
                <option value="manasuka free" <?= ($deposit->jenis_deposit == 'manasuka free' ? 'selected' : '') ?>>Manasuka Free</option>
                <option value="wajib" <?= ($deposit->jenis_deposit == 'wajib' ? 'selected' : '') ?>>Wajib</option>
              </select>
              <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
            </div>
          </div>

          <!-- Nominal -->
          <div>
            <label for="nominal" class="block text-sm font-semibold text-slate-700 mb-2">Nominal (Rp)</label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">Rp</span>
              <input
                type="number"
                class="w-full pl-12 pr-4 py-3 bg-white border border-slate-200 text-slate-800 font-mono text-lg font-bold rounded-xl focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 transition-all outline-none"
                id="nominal"
                name="nominal"
                value="<?= number_format($deposit->cash_out == 0 ? $deposit->cash_in : $deposit->cash_out, 0, '', '') ?>"
                min="0"
                required
                placeholder="0">
            </div>
            <p id="preview_nominal" class="text-xs font-bold text-emerald-600 mt-2 text-right min-h-[1rem]"></p>
          </div>

          <div class="grid grid-cols-2 gap-4 mt-8 pt-6 border-t border-slate-100">
            <a href="<?= base_url('admin/deposit/list_transaksi') ?>" class="px-4 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-colors text-center text-sm uppercase tracking-wider">
              Batal
            </a>
            <button type="button" onclick="confirmSave()" class="px-4 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200 text-center text-sm uppercase tracking-wider flex items-center justify-center gap-2">
              <i data-lucide="save" class="w-4 h-4"></i> Simpan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('js/modal-native.js') ?>"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();

    const nominalInput = document.getElementById('nominal');
    const previewNominal = document.getElementById('preview_nominal');

    if (nominalInput) {
      function updatePreview() {
        const raw = nominalInput.value.replace(/[^\d]/g, "");
        if (raw) {
          const num = parseInt(raw, 10);
          const formatted = new Intl.NumberFormat("id-ID", {
            maximumFractionDigits: 0
          }).format(num);
          previewNominal.textContent = `Terbilang: Rp ${formatted}`;
        } else {
          previewNominal.textContent = "";
        }
      }
      nominalInput.addEventListener('input', updatePreview);
      updatePreview();
    }
  });

  function confirmSave() {
    const nominal = document.getElementById('nominal').value;
    const formatted = new Intl.NumberFormat("id-ID").format(nominal);

    const content = `
            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="alert-circle" class="w-8 h-8"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Konfirmasi Perubahan</h3>
                <p class="text-slate-600 mb-6">
                    Anda akan mengubah nominal menjadi <br>
                    <span class="font-bold text-xl text-blue-600">Rp ${formatted}</span>
                </p>
                <div class="flex gap-3">
                    <button onclick="ModalHelper.close()" class="flex-1 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button onclick="document.getElementById('editForm').submit()" class="flex-1 px-4 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-colors">
                        Ya, Simpan
                    </button>
                </div>
            </div>
        `;

    ModalHelper.openContent(content);
    lucide.createIcons();
  }
</script>
<?= $this->endSection() ?>