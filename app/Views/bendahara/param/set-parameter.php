<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-8">
  <!-- Header -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
      <h1 class="text-3xl font-black text-slate-800 tracking-tight">Pengaturan Parameter</h1>
      <p class="text-slate-500 font-medium">Atur nilai parameter simpanan dan konfigurasi lainnya.</p>
    </div>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

    <!-- Parameter Simpanan Card -->
    <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
      <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
        <div class="w-12 h-12 rounded-2xl bg-theme-light text-theme-main flex items-center justify-center">
          <i data-lucide="piggy-bank" class="w-6 h-6"></i>
        </div>
        <div>
          <h3 class="text-xl font-black text-slate-900 tracking-tight">Parameter Simpanan</h3>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Nilai Wajib & Pokok</p>
        </div>
      </div>

      <?= session()->getFlashdata('notif_simp'); ?>

      <form action="<?= url_to('bendahara/parameter/set_param_simp') ?>" id="simpanan_param" method="post" class="space-y-6">
        <div class="space-y-5">
          <?php foreach ($param_simp as $a) : ?>
            <div class="group">
              <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2" for="param_simp<?= $a->idparameter ?>">
                <?= $a->parameter ?>
              </label>
              <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">Rp</span>
                <input type="number"
                  class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 font-bold rounded-xl focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition-all outline-none"
                  id="param_simp<?= $a->idparameter ?>"
                  name="param_nilai_simp[]"
                  value="<?= $a->nilai ?>"
                  required>
              </div>
              <input type="hidden" name="param_id[]" value="<?= $a->idparameter ?>">
            </div>
          <?php endforeach; ?>
        </div>

        <div class="pt-4">
          <button type="submit" class="w-full py-3.5 bg-theme-gradient text-white rounded-xl text-sm font-black uppercase tracking-widest hover:shadow-lg hover:shadow-indigo-200 transition-all hover:scale-[1.02] flex items-center justify-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
          </button>
        </div>
      </form>
    </div>

    <!-- Parameter Lainnya Card -->
    <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
      <div class="flex items-center gap-4 mb-6 pb-6 border-b border-slate-100">
        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
          <i data-lucide="sliders" class="w-6 h-6"></i>
        </div>
        <div>
          <h3 class="text-xl font-black text-slate-900 tracking-tight">Parameter Lainnya</h3>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Konfigurasi Tambahan</p>
        </div>
      </div>

      <?= session()->getFlashdata('notif_oth'); ?>

      <form action="<?= url_to('bendahara/parameter/set_param_oth') ?>" id="other_param" method="post" class="space-y-6">
        <div class="space-y-5">
          <?php foreach ($param_other as $a) : ?>
            <div class="group">
              <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2" for="param_oth<?= $a->idparameter ?>">
                <?= $a->parameter ?>
              </label>
              <div class="relative">
                <input type="number"
                  class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-800 font-bold rounded-xl focus:ring-2 focus:ring-amber-100 focus:border-amber-500 transition-all outline-none"
                  id="param_oth<?= $a->idparameter ?>"
                  name="param_nilai_oth[]"
                  value="<?= $a->nilai ?>"
                  required>
              </div>
              <input type="hidden" name="param_id[]" value="<?= $a->idparameter ?>">
            </div>
          <?php endforeach; ?>
        </div>

        <div class="pt-4">
          <button type="submit" class="w-full py-3.5 bg-slate-800 text-white rounded-xl text-sm font-black uppercase tracking-widest hover:bg-slate-900 shadow-lg shadow-slate-200 transition-all hover:scale-[1.02] flex items-center justify-center gap-2">
            <i data-lucide="save" class="w-4 h-4"></i> Simpan Parameter
          </button>
        </div>
      </form>
    </div>

  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  // Simple check to ensure icons are loaded if handled dynamically
  document.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();
  });
</script>
<?= $this->endSection() ?>