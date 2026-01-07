<?= $this->extend('layout/member') ?>

<?= $this->section('styles') ?>
<style type="text/css">
  /* Custom scroll for horizontal elements */
  .hide-scrollbar::-webkit-scrollbar {
    display: none;
  }

  .hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="space-y-8 pb-20 animate-fade-in-up">

  <!-- Header Section -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
      <h1 class="text-3xl font-black text-slate-800 tracking-tight">Riwayat Transaksi</h1>
      <p class="text-slate-500 font-medium">Kelola simpanan dan pantau arus kas Anda.</p>
    </div>
    <button onclick="openAddModal()" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all hover:scale-[1.02] flex items-center gap-2">
      <i data-lucide="plus-circle" class="w-5 h-5"></i>
      Tambah Pengajuan
    </button>
  </div>

  <?= session()->getFlashdata('notif'); ?>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Left Column: Transaction List -->
    <div class="lg:col-span-2 space-y-6">

      <!-- Filters / Month Selector (Optional, for now just list) -->

      <div class="space-y-4">
        <?php if (empty($deposit_list2)) : ?>
          <div class="bg-white rounded-[2rem] p-12 text-center shadow-soft border border-slate-50">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
              <i data-lucide="inbox" class="w-10 h-10 text-slate-300"></i>
            </div>
            <h3 class="text-lg font-black text-slate-800">Belum Ada Transaksi</h3>
            <p class="text-slate-400 text-sm mt-1">Pengajuan simpanan Anda akan muncul di sini.</p>
          </div>
        <?php else : ?>
          <?php foreach ($deposit_list2 as $k) : ?>
            <!-- Transaction Card -->
            <div class="bg-white rounded-[1.5rem] p-5 shadow-sm border border-slate-100 hover:shadow-md transition-all group cursor-pointer relative overflow-hidden">
              <!-- Status Stripe -->
              <div class="absolute left-0 top-0 bottom-0 w-1.5 <?=
                                                                $k['status'] == 'diterima' ? 'bg-emerald-500' : ($k['status'] == 'ditolak' ? 'bg-red-500' : 'bg-amber-400')
                                                                ?>"></div>

              <div class="flex items-center justify-between pl-4">
                <div class="flex items-center gap-4">
                  <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 <?= $k['cash_in'] == 0 ? 'bg-red-50 text-red-600' : 'bg-emerald-50 text-emerald-600' ?>">
                    <?php if ($k['cash_in'] == 0) : ?>
                      <i data-lucide="arrow-up" class="w-6 h-6"></i>
                    <?php else : ?>
                      <i data-lucide="arrow-down" class="w-6 h-6"></i>
                    <?php endif; ?>
                  </div>
                  <div>
                    <h4 class="font-black text-slate-800 text-sm md:text-base"><?= ucwords($k['jenis_pengajuan']) ?></h4>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1"><?= ucwords($k['jenis_deposit']) ?></p>
                    <div class="flex items-center gap-2">
                      <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-500 uppercase tracking-wide">
                        <?= date('d M Y', strtotime($k['date_created'])) ?>
                      </span>
                      <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wide <?=
                                                                                                      $k['status'] == 'diterima' ? 'bg-emerald-100 text-emerald-600' : ($k['status'] == 'ditolak' ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-600')
                                                                                                      ?>">
                        <?= ucwords($k['status']) ?>
                      </span>
                    </div>
                  </div>
                </div>

                <div class="text-right">
                  <?php if ($k['cash_in'] == 0) : ?>
                    <p class="font-black text-slate-800 text-sm md:text-lg">- Rp <?= number_format($k['cash_out'], 0, ',', '.') ?></p>
                  <?php else : ?>
                    <p class="font-black text-slate-800 text-sm md:text-lg">+ Rp <?= number_format($k['cash_in'], 0, ',', '.') ?></p>
                  <?php endif; ?>

                  <div class="mt-2 flex justify-end gap-2">
                    <button onclick="ModalHelper.open('<?= base_url('anggota/deposit/detail_mutasi') ?>', {rowid: '<?= $k['iddeposit'] ?>'})" class="p-2 rounded-xl bg-slate-50 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors" title="Detail">
                      <i data-lucide="file-text" class="w-4 h-4"></i>
                    </button>

                    <?php if (!$k['bukti_transfer'] && $k['jenis_deposit'] == 'manasuka free' && $k['jenis_pengajuan'] == 'penyimpanan' && $k['status'] != "diterima" && $k['status'] != "ditolak") : ?>
                      <button onclick="ModalHelper.open('<?= base_url('anggota/deposit/up_mutasi') ?>', {rowid: '<?= $k['iddeposit'] ?>'})" class="p-2 rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors animate-bounce-slow" title="Upload Bukti">
                        <i data-lucide="upload" class="w-4 h-4"></i>
                      </button>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <!-- Custom Pagination -->
      <?php if (!empty($deposit_list2)) : ?>
        <div class="mt-8">
          <?= $pager->links('grup1', 'default_minia') ?>
        </div>
      <?php endif; ?>

    </div>

    <!-- Right Column: Summary & Settings -->
    <div class="space-y-6">

      <!-- Summary Card -->
      <div class="bg-gradient-to-br from-indigo-600 to-violet-600 rounded-[2.5rem] p-8 text-white shadow-xl shadow-indigo-200/50 relative overflow-hidden">
        <div class="relative z-10">
          <p class="text-white font-bold opacity-80 text-xs uppercase tracking-widest mb-1">Total Saldo Simpanan</p>
          <h2 class="text-3xl font-black tracking-tight text-white mb-8">Rp <?= number_format(($total_saldo_manasuka + $total_saldo_pokok + $total_saldo_wajib), 0, ',', '.') ?></h2>

          <div class="space-y-4">
            <div class="flex justify-between items-center p-3 bg-white/10 rounded-2xl backdrop-blur-sm border border-white/10">
              <div class="flex items-center gap-3">
                <div class="bg-white/20 p-2 rounded-xl"><i data-lucide="wallet" class="w-4 h-4 text-white"></i></div>
                <span class="text-xs font-bold uppercase tracking-wider text-white">Pokok</span>
              </div>
              <span class="font-bold text-white">Rp <?= number_format($total_saldo_pokok, 0, ',', '.') ?></span>
            </div>
            <div class="flex justify-between items-center p-3 bg-white/10 rounded-2xl backdrop-blur-sm border border-white/10">
              <div class="flex items-center gap-3">
                <div class="bg-white/20 p-2 rounded-xl"><i data-lucide="piggy-bank" class="w-4 h-4 text-white"></i></div>
                <span class="text-xs font-bold uppercase tracking-wider text-white">Wajib</span>
              </div>
              <span class="font-bold text-white">Rp <?= number_format($total_saldo_wajib, 0, ',', '.') ?></span>
            </div>
            <div class="flex justify-between items-center p-3 bg-white/10 rounded-2xl backdrop-blur-sm border border-white/10">
              <div class="flex items-center gap-3">
                <div class="bg-white/20 p-2 rounded-xl"><i data-lucide="hand-coins" class="w-4 h-4 text-white"></i></div>
                <span class="text-xs font-bold uppercase tracking-wider text-white">Manasuka</span>
              </div>
              <span class="font-bold text-white">Rp <?= number_format($total_saldo_manasuka, 0, ',', '.') ?></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Settings Card -->
      <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
        <div class="flex items-center gap-3 mb-6">
          <div class="p-3 bg-slate-50 text-slate-500 rounded-2xl">
            <i data-lucide="settings" class="w-6 h-6"></i>
          </div>
          <div>
            <h3 class="text-lg font-black text-slate-800">Pengaturan</h3>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Simpanan Manasuka</p>
          </div>
        </div>

        <div class="mb-6">
          <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Setoran Bulanan</p>
          <?php if (!$param_manasuka) : ?>
            <p class="text-xl font-black text-yellow-500">Belum Diatur</p>
          <?php else : ?>
            <p class="text-xl font-black text-slate-800">Rp <?= number_format($param_manasuka[0]->nilai, 0, ',', '.') ?></p>
          <?php endif; ?>
        </div>

        <?php
        $datecheck = new DateTime($param_manasuka_cek);
        $month = new DateTime('-1 month');
        $isRecentlyUpdated = $datecheck >= $month;
        ?>

        <?php if ($isRecentlyUpdated) : ?>
          <div class="bg-blue-50 p-4 rounded-2xl mb-4 border border-blue-100 flex gap-3">
            <i data-lucide="clock" class="w-5 h-5 text-blue-600 shrink-0"></i>
            <p class="text-xs font-medium text-blue-800 leading-relaxed">
              Pengaturan baru saja diubah. Tunggu 1 bulan untuk mengubah kembali.
            </p>
          </div>
        <?php endif; ?>

        <div class="grid grid-cols-2 gap-3">
          <button onclick="openParamModal()" <?= $isRecentlyUpdated ? 'disabled' : '' ?> class="py-3 px-4 rounded-xl bg-slate-50 text-slate-600 font-bold text-xs uppercase tracking-wide hover:bg-slate-100 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
            Atur Nominal
          </button>
          <button onclick="openCancelParamModal()" <?= $isRecentlyUpdated ? 'disabled' : '' ?> class="py-3 px-4 rounded-xl bg-red-50 text-red-600 font-bold text-xs uppercase tracking-wide hover:bg-red-100 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
            Batalkan
          </button>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Hidden Modal Content Templates -->

<!-- 1. Add Pengajuan Modal Content -->
<div id="add-modal-content" class="hidden">
  <div class="text-center mb-6">
    <div class="w-16 h-16 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mx-auto mb-4">
      <i data-lucide="plus" class="w-8 h-8"></i>
    </div>
    <h3 class="text-xl font-black text-slate-900">Pengajuan Baru</h3>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Simpanan Manasuka</p>
  </div>

  <form action="<?= url_to('anggota/deposit/add_req') ?>" id="formSheet" method="post">
    <div class="space-y-4">
      <div>
        <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Jenis Pengajuan</label>
        <select class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 font-bold" name="jenis_pengajuan" required>
          <option value="" disabled selected>Pilih Opsi...</option>
          <option value="penarikan">Penarikan Saldo</option>
          <option value="penyimpanan">Penyimpanan (Top Up)</option>
        </select>
      </div>

      <div>
        <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Nominal (Rp)</label>
        <input type="number" id="nominal_add" name="nominal" class="bg-slate-50 border border-slate-200 text-slate-900 text-lg rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 font-black placeholder-slate-300" placeholder="0" required oninput="updatePreview(this, 'preview_nominal1')">
        <p id="preview_nominal1" class="text-xs font-bold text-blue-600 mt-2 text-right h-4"></p>
      </div>

      <div>
        <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Deskripsi</label>
        <input type="text" name="description" class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 font-medium" placeholder="Keterangan transaksi...">
      </div>
    </div>
  </form>

  <div class="mt-8 pt-4 border-t border-slate-100 flex gap-3">
    <button onclick="ModalHelper.close()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">Batal</button>
    <button type="submit" form="formSheet" class="flex-1 py-3 bg-blue-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all hover:scale-[1.02]">Kirim Pengajuan</button>
  </div>
</div>

<!-- 2. Set Parameter Modal Content -->
<div id="param-modal-content" class="hidden">
  <div class="text-center mb-6">
    <div class="w-16 h-16 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-4">
      <i data-lucide="settings-2" class="w-8 h-8"></i>
    </div>
    <h3 class="text-xl font-black text-slate-900">Atur Manasuka</h3>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Setoran Rutin Bulanan</p>
  </div>

  <form action="<?= ($param_manasuka) ? url_to('anggota_set_parameter_manasuka', $param_manasuka[0]->idmnskparam) : url_to('anggota/deposit/create_param_manasuka') ?>" id="formParam" method="post">
    <div class="space-y-4">
      <div>
        <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Nominal Rutin (Rp)</label>
        <input type="number" id="nominal_param" name="nilai" value="<?= ($param_manasuka) ? $param_manasuka[0]->nilai : '' ?>" class="bg-slate-50 border border-slate-200 text-slate-900 text-lg rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 font-black" required oninput="updatePreview(this, 'preview_nominal2')">
        <input type="text" name="iduser" value="<?= $duser->iduser ?>" hidden>
        <p id="preview_nominal2" class="text-xs font-bold text-indigo-600 mt-2 text-right h-4"></p>
      </div>

      <div class="flex items-start gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200">
        <input type="checkbox" id="konfirmasi_check" class="mt-1 w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500" required onchange="toggleParamSubmit(this)">
        <label for="konfirmasi_check" class="text-xs font-medium text-slate-600 cursor-pointer select-none">
          Saya setuju untuk mengubah pengaturan simpanan manasuka bulanan. Perubahan ini akan membatasi perubahan berikutnya selama 1 bulan.
        </label>
      </div>
    </div>
  </form>

  <div class="mt-8 pt-4 border-t border-slate-100 flex gap-3">
    <button onclick="ModalHelper.close()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">Batal</button>
    <button type="submit" id="confirm_param_btn" form="formParam" disabled class="flex-1 py-3 bg-indigo-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed">Simpan</button>
  </div>
</div>

<!-- 3. Cancel Parameter Modal Content -->
<div id="cancel-param-content" class="hidden">
  <div class="text-center mb-6">
    <div class="w-16 h-16 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto mb-4">
      <i data-lucide="alert-triangle" class="w-8 h-8"></i>
    </div>
    <h3 class="text-xl font-black text-slate-900">Batalkan Manasuka?</h3>
    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Konfirmasi Pembatalan</p>
  </div>

  <div class="bg-red-50 p-4 rounded-xl border border-red-100 mb-6">
    <p class="text-sm text-red-800 font-medium text-center">
      Anda akan berhenti melakukan setoran manasuka bulanan secara otomatis. Anda dapat mengaktifkannya kembali kapan saja (dengan masa tunggu 1 bulan).
    </p>
  </div>

  <div class="flex gap-3">
    <button onclick="ModalHelper.close()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">Urungkan</button>
    <?php if ($param_manasuka): ?>
      <a href="<?= url_to('anggota_cancel_parameter_manasuka', $param_manasuka[0]->idmnskparam) ?>" class="flex-1 py-3 bg-red-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-200 transition-all hover:scale-[1.02] text-center flex items-center justify-center">
        Ya, Batalkan
      </a>
    <?php endif; ?>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Native Modal Logic (Required) -->
<!-- Native Modal Logic (Already in Layout) -->

<script>
  // Logic for handling modals
  function openAddModal() {
    ModalHelper.openContent(document.getElementById('add-modal-content').innerHTML);
  }

  function openParamModal() {
    ModalHelper.openContent(document.getElementById('param-modal-content').innerHTML);
    // Re-attach listener for checkbox since we just injected HTML
    // Wait, onclick in HTML works global, but ID lookups inside modal need care if duplicates exist.
    // Since we are cloning, ensure IDs are not conflicting or just access them via event.
    // The original IDs (nominal_param) are unique in the page.
  }

  function openCancelParamModal() {
    ModalHelper.openContent(document.getElementById('cancel-param-content').innerHTML);
  }

  // Param Modal Submit Toggle
  window.toggleParamSubmit = function(checkbox) {
    // Since this checkbox is now inside the modal container, we need to find the button inside the modal container too.
    // The 'id' approach works if ids are unique.
    const btn = document.getElementById('confirm_param_btn');
    if (btn) btn.disabled = !checkbox.checked;
  }

  // Currency Formatter
  window.updatePreview = function(input, previewId) {
    const val = input.value;
    const preview = document.getElementById(previewId);
    if (val) {
      const formatted = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
      }).format(val);
      preview.textContent = formatted;
    } else {
      preview.textContent = '';
    }
  }
</script>
<?= $this->endSection() ?>