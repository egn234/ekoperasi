<?= $this->extend('layout/member') ?>

<?= $this->section('styles') ?>
<!-- Custom Styles if needed -->
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="space-y-8 pb-20 animate-fade-in-up">

  <!-- Header Section -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
      <h1 class="text-3xl font-black text-slate-800 tracking-tight">Pengajuan Pinjaman</h1>
      <p class="text-slate-500 font-medium">Kelola pinjaman dan lihat riwayat status pengajuan Anda.</p>
    </div>
    <button id="btnAddPengajuan" class="bg-indigo-600 text-white px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all hover:scale-[1.02] flex items-center gap-2">
      <i data-lucide="plus-circle" class="w-5 h-5"></i>
      Buat Pengajuan
    </button>
  </div>

  <?= session()->getFlashdata('notif'); ?>
  <?= session()->getFlashdata('notif_bulanan'); ?>
  <?= session()->getFlashdata('notif_gaji'); ?>
  <?= session()->getFlashdata('notif_kontrak'); ?>

  <!-- Active Loans List -->
  <div class="space-y-6">
    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-[2rem] shadow-sm border border-slate-50 flex flex-col md:flex-row gap-4 items-center justify-between">
      <div class="relative w-full md:w-96">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
          <i data-lucide="search" class="w-5 h-5 text-slate-400"></i>
        </div>
        <input type="text" id="searchInput" class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full pl-11 p-3 font-bold placeholder-slate-400 transition-all" placeholder="Cari pinjaman...">
      </div>
      <div class="flex items-center gap-2 w-full md:w-auto">
        <button id="toggleRiwayatBtn" class="flex-1 md:flex-none py-3 px-5 bg-slate-50 text-slate-500 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-slate-100 hover:text-slate-700 transition-colors flex items-center justify-center gap-2">
          <i data-lucide="history" class="w-4 h-4"></i> Riwayat
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="hidden py-20 text-center">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
      <p class="mt-2 text-xs font-bold text-slate-400 uppercase tracking-wider">Memuat Data...</p>
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="hidden bg-white rounded-[2.5rem] p-12 text-center shadow-soft border border-slate-50">
      <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
        <i data-lucide="file-x-2" class="w-10 h-10 text-slate-300"></i>
      </div>
      <h3 class="text-lg font-black text-slate-800">Tidak Ada Pinjaman</h3>
      <p class="text-slate-400 text-sm mt-1">Belum ada data pengajuan pinjaman yang ditemukan.</p>
    </div>

    <!-- Card Grid -- Container for JS Render -->
    <div id="loanListContainer" class="grid grid-cols-1 gap-6">
      <!-- Cards will be injected here -->
    </div>

    <!-- Pagination -->
    <div id="paginationContainer" class="hidden flex flex-col md:flex-row justify-between items-center gap-4 bg-white p-6 rounded-[2rem] shadow-sm border border-slate-50">
      <span id="pageInfo" class="text-xs font-bold text-slate-400 uppercase tracking-widest">Menampilkan 0-0 dari 0 data</span>
      <div class="flex items-center gap-2">
        <button id="prevBtn" class="p-2 rounded-xl bg-slate-50 text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
          <i data-lucide="chevron-left" class="w-5 h-5"></i>
        </button>
        <button id="nextBtn" class="p-2 rounded-xl bg-slate-50 text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
          <i data-lucide="chevron-right" class="w-5 h-5"></i>
        </button>
      </div>
    </div>
  </div>

  <!-- Rejected History Section (Collapsible) -->
  <div id="wrapper-riwayat" class="hidden transition-all duration-300">
    <div class="bg-red-50 rounded-[2.5rem] p-8 shadow-inner border border-red-100 mt-6">
      <div class="mb-6 flex items-center gap-3">
        <div class="p-2 bg-red-100 text-red-600 rounded-xl">
          <i data-lucide="history" class="w-6 h-6"></i>
        </div>
        <div>
          <h3 class="text-xl font-black text-slate-900 tracking-tight">Riwayat Penolakan</h3>
          <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mt-1">Daftar Pengajuan yang Ditolak</p>
        </div>
      </div>

      <!-- Container for JS Render -->
      <div id="riwayatListContainer" class="space-y-4">
        <!-- History Cards will be injected here -->
      </div>

      <!-- Empty State for History -->
      <div id="riwayatEmptyState" class="hidden text-center py-8">
        <p class="text-sm font-bold text-slate-400">Tidak ada riwayat penolakan.</p>
      </div>

      <!-- Loading State for History -->
      <div id="riwayatLoadingState" class="hidden text-center py-8">
        <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-red-600"></div>
      </div>
    </div>
  </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Dependencies -->
<script src="<?= base_url() ?>/assets/libs/imask/imask.min.js"></script>

<!-- Custom JS for this page -->
<script src="<?= base_url() ?>/assets/js/pages/anggota/pinjaman/list-pinjaman-custom.js"></script>
<script>
  // Simple Toggle for Riwayat
  $('#toggleRiwayatBtn').on('click', function() {
    $('#wrapper-riwayat').toggleClass('hidden');
  });

  if (window.lucide) window.lucide.createIcons();
</script>
<?= $this->endSection() ?>