<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<style>
  /* Custom Scrollbar for horizontal scrolling if needed */
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

<div class="space-y-8 pb-20">
  <!-- Header -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
      <div class="flex items-center gap-3 mb-1">
        <a href="<?= base_url('bendahara/deposit/user') ?>" class="p-2 -ml-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
          <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">Detail Transaksi</h1>
      </div>
      <p class="text-slate-500 font-medium pl-10">Kelola simpanan dan verifikasi mutasi anggota.</p>
    </div>
  </div>

  <?= session()->getFlashdata('notif'); ?>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Left Column: Transaction List -->
    <div class="lg:col-span-2 space-y-6">

      <div class="space-y-4">
        <?php if (empty($deposit_list2)) : ?>
          <div class="bg-white rounded-[2rem] p-12 text-center shadow-soft border border-slate-50">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
              <i data-lucide="inbox" class="w-10 h-10 text-slate-300"></i>
            </div>
            <h3 class="text-lg font-black text-slate-800">Belum Ada Transaksi</h3>
            <p class="text-slate-400 text-sm mt-1">Anggota ini belum memiliki riwayat transaksi.</p>
          </div>
        <?php else : ?>
          <?php foreach ($deposit_list2 as $k) : ?>
            <!-- Transaction Card -->
            <div class="bg-white rounded-[1.5rem] p-5 shadow-sm border border-slate-100 hover:shadow-md transition-all group relative overflow-hidden">
              <!-- Status Border -->
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
                    <button onclick="openDetailModal('<?= $k['iddeposit'] ?>')" class="p-2 rounded-xl bg-slate-50 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors" title="Detail">
                      <i data-lucide="file-text" class="w-4 h-4"></i>
                    </button>

                    <?php if ($k['status'] == 'diproses bendahara') : ?>
                      <button onclick="openApproveModal('<?= $k['iddeposit'] ?>')" class="p-2 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors" title="Setujui">
                        <i data-lucide="check" class="w-4 h-4"></i>
                      </button>
                      <button onclick="openRejectModal('<?= $k['iddeposit'] ?>')" class="p-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Tolak">
                        <i data-lucide="x" class="w-4 h-4"></i>
                      </button>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <!-- Pagination -->
      <?php if (!empty($deposit_list2)) : ?>
        <div class="flex justify-between items-center bg-white rounded-2xl p-4 shadow-sm border border-slate-50">
          <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">
            Menampilkan <?= count($deposit_list2) ?> dari <?= $total_rows ?? 0 ?> transaksi
          </p>
          <div>
            <?= $pager->links('grup1', 'default_minia') ?>
          </div>
        </div>
      <?php endif; ?>

    </div>

    <!-- Right Column: Member Profile & Balance -->
    <div class="space-y-6">

      <!-- Member Profile Card -->
      <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50 text-center relative overflow-hidden group">
        <div class="absolute top-0 left-0 w-full h-24 bg-indigo-gradient opacity-10"></div>

        <div class="relative z-10">
          <div class="w-24 h-24 rounded-full bg-white p-1 mx-auto mb-4 shadow-lg border-4 border-indigo-50">
            <img src="<?= ($detail_user->profil_pic) ? base_url() . '/uploads/user/' . $detail_user->username . '/profil_pic/' . $detail_user->profil_pic : base_url() . '/assets/images/users/avatar-1.png' ?>" alt="Avatar" class="rounded-full w-full h-full object-cover">
          </div>
          <h2 class="text-xl font-black text-slate-800"><?= $detail_user->nama_lengkap ?></h2>
          <p class="text-sm font-bold text-slate-400 uppercase tracking-widest mt-1"><?= $detail_user->username ?></p>

          <div class="mt-6 pt-6 border-t border-slate-100">
            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Saldo Simpanan</p>
            <h3 class="text-3xl font-black text-indigo-600 tracking-tight">
              Rp <?= number_format(($total_saldo_manasuka + $total_saldo_wajib + $total_saldo_pokok), 0, ',', '.') ?>
            </h3>
          </div>
        </div>
      </div>

      <!-- Balances Detail Card -->
      <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
        <h3 class="text-lg font-black text-slate-800 mb-6">Rincian Saldo</h3>

        <div class="space-y-4">
          <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-slate-50 transition-colors">
            <div class="flex items-center gap-3">
              <div class="p-2 rounded-xl bg-emerald-50 text-emerald-600"><i data-lucide="piggy-bank" class="w-5 h-5"></i></div>
              <div>
                <p class="text-sm font-bold text-slate-700">Pokok</p>
              </div>
            </div>
            <p class="font-black text-slate-800">Rp <?= number_format($total_saldo_pokok, 0, ',', '.') ?></p>
          </div>

          <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-slate-50 transition-colors">
            <div class="flex items-center gap-3">
              <div class="p-2 rounded-xl bg-blue-50 text-blue-600"><i data-lucide="calendar-check" class="w-5 h-5"></i></div>
              <div>
                <p class="text-sm font-bold text-slate-700">Wajib</p>
              </div>
            </div>
            <p class="font-black text-slate-800">Rp <?= number_format($total_saldo_wajib, 0, ',', '.') ?></p>
          </div>

          <div class="flex items-center justify-between p-3 rounded-2xl hover:bg-slate-50 transition-colors">
            <div class="flex items-center gap-3">
              <div class="p-2 rounded-xl bg-amber-50 text-amber-600"><i data-lucide="banknote" class="w-5 h-5"></i></div>
              <div>
                <p class="text-sm font-bold text-slate-700">Manasuka</p>
              </div>
            </div>
            <p class="font-black text-slate-800">Rp <?= number_format($total_saldo_manasuka, 0, ',', '.') ?></p>
          </div>

          <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100">
            <div class="flex items-center gap-3">
              <div class="p-2 rounded-xl bg-slate-200 text-slate-600"><i data-lucide="settings-2" class="w-5 h-5"></i></div>
              <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Setoran Rutin</p>
              </div>
            </div>
            <?php if (!$param_manasuka) : ?>
              <span class="text-xs font-bold text-amber-500 italic">Belum diatur</span>
            <?php else : ?>
              <p class="font-black text-slate-800">Rp <?= number_format($param_manasuka[0]->nilai, 0, ',', '.') ?></p>
            <?php endif; ?>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Native Modal Container -->
<div id="dynamic-modal-overlay" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
<div id="dynamic-modal-content" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-50 hidden w-full max-w-lg bg-white rounded-[2rem] shadow-2xl p-6 md:p-8 max-h-[90vh] overflow-y-auto">
  <div id="modal-container"></div>
  <button onclick="closeNativeModal()" class="absolute top-6 right-6 p-2 hover:bg-slate-100 rounded-full transition-colors">
    <i data-lucide="x" class="w-5 h-5 text-slate-400"></i>
  </button>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Native Modal Logic -->
<script src="<?= base_url('js/modal-native.js') ?>"></script>
<script>
  // Just mapping the openNativeModal to ModalHelper since we have ModalHelper active
  // Or we can just use ModalHelper directly in the onclicks above

  function openDetailModal(id) {
    ModalHelper.open('<?= base_url() ?>/bendahara/deposit/detail_mutasi', {
      rowid: id
    });
  }

  function openApproveModal(id) {
    ModalHelper.open('<?= base_url() ?>/bendahara/deposit/approve-mnsk', {
      rowid: id
    }, function() {
      // Init validation or scripts if needed after load
      // For example, the input formatting script
      initApprovalScripts();
    });
  }

  function openRejectModal(id) {
    ModalHelper.open('<?= base_url() ?>/bendahara/deposit/cancel-mnsk', {
      rowid: id
    });
  }

  // Close function for reference
  function closeNativeModal() {
    ModalHelper.close();
  }

  // Approval Scripts Logic (formatting currency)
  function initApprovalScripts() {
    const nominalInput = document.getElementById('nominal_uang');
    const previewNominal = document.getElementById('preview_nominal');

    if (nominalInput) {
      nominalInput.addEventListener('input', function() {
        const raw = this.value.replace(/[^\d]/g, "");
        if (raw) {
          const formatted = new Intl.NumberFormat("id-ID").format(parseInt(raw));
          previewNominal.innerHTML = `Rp ${formatted}`;
        } else {
          previewNominal.textContent = "";
        }
      });
    }
  }
</script>
<?= $this->endSection() ?>