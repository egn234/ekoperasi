<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-8 pb-20">
  <!-- Header -->
  <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">
    <div>
      <h1 class="text-3xl font-black text-slate-800 tracking-tight">Laporan & Log Bulanan</h1>
      <p class="text-slate-500 font-medium">Generate dan cetak laporan bulanan serta pantau log aktivitas.</p>
    </div>
  </div>

  <?= session()->getFlashdata('notif') ?>
  <?= session()->getFlashdata('notif_print') ?>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Log Report Card -->
    <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50 h-full flex flex-col">
      <div class="mb-6 flex justify-between items-center">
        <div>
          <h3 class="text-xl font-black text-slate-900 tracking-tight">Log Laporan</h3>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Riwayat Generate</p>
        </div>
        <!-- Logic generated button -->
        <a href="#" id="btnGenerate" class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all <?= ($cek_report != 0) ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' ?>">
          <i data-lucide="zap" class="w-4 h-4 inline-block mr-1"></i> Generate Bulan Ini
        </a>
      </div>

      <div class="flex-1 overflow-hidden">
        <table class="dtable w-full text-left border-collapse">
          <thead class="bg-slate-50">
            <tr>
              <th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-left rounded-l-xl">No</th>
              <th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-left">Report Log</th>
              <th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-left">Created</th>
              <th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-center rounded-r-xl">Status</th>
            </tr>
          </thead>
          <tbody class="text-sm text-slate-600 font-medium">
            <?php $c = 1 ?>
            <?php foreach ($list_report as $a): ?>
              <tr class="hover:bg-slate-50/50 transition-colors border-b border-slate-100 last:border-0">
                <td class="p-4 font-bold text-slate-800"><?= $c ?></td>
                <td class="p-4">
                  <span class="font-bold text-slate-700">Log <?= date('F Y', strtotime($a->date_monthly)) ?></span>
                </td>
                <td class="p-4 text-slate-500 text-xs"><?= $a->created ?></td>
                <td class="p-4 text-center">
                  <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest <?= ($a->flag) ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' ?>">
                    <?= ($a->flag) ? 'Success' : 'Failed' ?>
                  </span>
                </td>
              </tr>
              <?php $c++; ?>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Print Forms Card -->
    <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50 h-full flex flex-col">
      <div class="mb-6">
        <h3 class="text-xl font-black text-slate-900 tracking-tight">Cetak Dokumen</h3>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Unduh Laporan & Rekap</p>
      </div>

      <div class="space-y-8 flex-1">
        <!-- Form 1 -->
        <div class="group">
          <h5 class="flex items-center gap-2 text-sm font-black text-slate-800 mb-4 pb-2 border-b border-slate-100">
            <i data-lucide="file-text" class="w-4 h-4 text-blue-500"></i> Laporan Potongan Pinjaman
          </h5>
          <form action="<?= url_to('admin/report/print-potongan-pinjaman') ?>" method="post" class="grid grid-cols-12 gap-3 items-end">
            <div class="col-span-12 md:col-span-5">
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Instansi</label>
              <select class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" name="instansi">
                <option value="0">-Semua-</option>
                <option value="YPT">YPT</option>
                <option value="Universitas Telkom">Universitas Telkom</option>
                <option value="Trengginas Jaya">Trengginas Jaya</option>
                <option value="BUT">BUT</option>
                <option value="Telkom">Telkom</option>
                <option value="GIAT">GIAT</option>
              </select>
            </div>
            <div class="col-span-12 md:col-span-5">
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Periode</label>
              <select class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" name="idreportm" required>
                <option value="0">-Pilih Bulan-</option>
                <?php foreach ($list_report as $v): ?>
                  <option value="<?= $v->idreportm ?>"><?= date('F Y', strtotime($v->date_monthly)) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-span-12 md:col-span-2">
              <button type="submit" class="w-full bg-blue-600 text-white rounded-xl py-2.5 shadow-lg shadow-blue-200 hover:bg-blue-700 hover:scale-[1.02] transition-all flex items-center justify-center">
                <i data-lucide="printer" class="w-4 h-4"></i>
              </button>
            </div>
          </form>
        </div>

        <!-- Form 2 -->
        <div class="group">
          <h5 class="flex items-center gap-2 text-sm font-black text-slate-800 mb-4 pb-2 border-b border-slate-100">
            <i data-lucide="book-open" class="w-4 h-4 text-purple-500"></i> Rekening Koran
          </h5>
          <form action="<?= url_to('admin/report/print-rekening-koran') ?>" method="post" class="grid grid-cols-12 gap-3 items-end">
            <div class="col-span-12 md:col-span-5">
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Instansi</label>
              <select class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all" name="instansi">
                <option value="0">-Pilih Instansi-</option>
                <option value="YPT">YPT</option>
                <option value="Universitas Telkom">Universitas Telkom</option>
                <option value="Trengginas Jaya">Trengginas Jaya</option>
                <option value="BUT">BUT</option>
                <option value="Telkom">Telkom</option>
                <option value="GIAT">GIAT</option>
              </select>
            </div>
            <div class="col-span-12 md:col-span-5">
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Tahun Laporan</label>
              <select class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-purple-500 transition-all" name="tahun" required>
                <option value="0">-Pilih Tahun-</option>
                <?php foreach ($list_tahun as $t): ?>
                  <option value="<?= $t->tahun ?>">Tahun <?= $t->tahun ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-span-12 md:col-span-2">
              <button type="submit" class="w-full bg-purple-600 text-white rounded-xl py-2.5 shadow-lg shadow-purple-200 hover:bg-purple-700 hover:scale-[1.02] transition-all flex items-center justify-center">
                <i data-lucide="printer" class="w-4 h-4"></i>
              </button>
            </div>
          </form>
        </div>

        <!-- Form 3 -->
        <div class="group">
          <h5 class="flex items-center gap-2 text-sm font-black text-slate-800 mb-4 pb-2 border-b border-slate-100">
            <i data-lucide="archive" class="w-4 h-4 text-orange-500"></i> Rekap Tahunan
          </h5>
          <form action="<?= url_to('admin/report/print-rekap-tahunan') ?>" method="post" class="grid grid-cols-12 gap-3 items-end">
            <div class="col-span-12 md:col-span-10">
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1">Tahun Laporan</label>
              <select class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-orange-500 transition-all" name="tahun" required>
                <option>-Pilih Tahun-</option>
                <?php foreach ($list_tahun as $t): ?>
                  <option value="<?= $t->tahun ?>">Tahun <?= $t->tahun ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-span-12 md:col-span-2">
              <button type="submit" class="w-full bg-orange-600 text-white rounded-xl py-2.5 shadow-lg shadow-orange-200 hover:bg-orange-700 hover:scale-[1.02] transition-all flex items-center justify-center">
                <i data-lucide="printer" class="w-4 h-4"></i>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>



<!-- Modal Templates (Hidden) -->
<template id="tmpl-modal-warning">
  <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm bg-white rounded-[2rem] shadow-2xl p-8 text-center z-50">
    <div class="w-16 h-16 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center mx-auto mb-6">
      <i data-lucide="alert-triangle" class="w-8 h-8"></i>
    </div>
    <h3 class="text-xl font-black text-slate-900 mb-2">Peringatan</h3>
    <p class="text-sm text-slate-500 mb-6 font-bold">
      Tanggal generate belum terpenuhi (Tgl <?= $getDay ?>).<br>
      Apakah Anda yakin ingin tetap generate laporan bulan ini?
    </p>
    <div class="flex gap-3">
      <button onclick="closeModal()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">Batal</button>
      <button onclick="showConfirmModal()" class="flex-1 py-3 bg-amber-500 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-amber-600 shadow-lg shadow-amber-200 transition-all hover:scale-[1.02]">Tetap Generate</button>
    </div>
  </div>
</template>

<template id="tmpl-modal-confirm">
  <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm bg-white rounded-[2rem] shadow-2xl p-8 text-center z-50">
    <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center mx-auto mb-6">
      <i data-lucide="zap" class="w-8 h-8"></i>
    </div>
    <h3 class="text-xl font-black text-slate-900 mb-2">Konfirmasi Generate</h3>
    <p class="text-sm text-slate-500 mb-6 font-bold">
      Generate laporan untuk bulan ini sekarang?
    </p>
    <div class="flex gap-3">
      <button onclick="closeModal()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">Batal</button>
      <a href="<?= url_to('admin/report/generate-monthly-report') ?>" class="flex-1 py-3 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all hover:scale-[1.02] flex items-center justify-center">Ya, Generate</a>
    </div>
  </div>
</template>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="text/javascript">
  $('.dtable').DataTable({
    autoWidth: false,
    // Unified DOM Layout
    dom: '<"flex justify-between items-center gap-4 mb-4"lf><"rounded-xl border border-slate-100"t><"flex justify-between items-center gap-4 mt-4"ip>',
    language: {
      search: "",
      searchPlaceholder: "Cari Log...",
      lengthMenu: "_MENU_",
      info: "_START_ - _END_ dari _TOTAL_",
      paginate: {
        first: '<<',
        last: '>>',
        next: '>',
        previous: '<'
      }
    },
    drawCallback: function() {
      if (window.lucide) window.lucide.createIcons();
      $('.dataTables_filter input').addClass('px-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500');
    }
  });

  // Modal Logic via ModalHelper
  function closeModal() {
    ModalHelper.close();
  }

  function showWarningModal() {
    ModalHelper.openContent(document.getElementById('tmpl-modal-warning').innerHTML);
  }

  function showConfirmModal() {
    ModalHelper.openContent(document.getElementById('tmpl-modal-confirm').innerHTML);
  }

  // Bind Button
  document.getElementById("btnGenerate")?.addEventListener("click", function(e) {
    e.preventDefault();
    var today = <?= date('d') ?>;
    var targetDay = <?= $getDay ?>;
    var sudahGenerate = <?= $cek_report ?>;

    if (sudahGenerate != 0) return false;

    if (today < targetDay) {
      showWarningModal();
    } else {
      showConfirmModal();
    }
  });
</script>
<?= $this->endSection() ?>