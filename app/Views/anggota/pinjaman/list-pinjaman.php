<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<!-- DataTables CSS -->
<link href="<?= base_url() ?>/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
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

  <!-- Active Loans Table Card -->
  <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
    <div class="mb-6 flex justify-between items-center">
      <div class="flex items-center gap-3">
        <div class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">
          <i data-lucide="file-text" class="w-6 h-6"></i>
        </div>
        <div>
          <h3 class="text-xl font-black text-slate-900 tracking-tight">Daftar Pengajuan</h3>
          <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Status Pinjaman Anda</p>
        </div>
      </div>
      <!-- Toggle Riwayat -->
      <button class="text-xs font-bold text-slate-400 hover:text-slate-600 uppercase tracking-wider flex items-center gap-2" id="toggleRiwayatBtn">
        <i data-lucide="history" class="w-4 h-4"></i> Riwayat Penolakan
      </button>
    </div>

    <div class="overflow-hidden">
      <table id="dataTable" class="w-full whitespace-nowrap"></table>
    </div>
  </div>

  <!-- Rejected History Section (Collapsible) -->
  <div id="wrapper-riwayat" class="hidden transition-all duration-300">
    <div class="bg-red-50 rounded-[2.5rem] p-8 shadow-inner border border-red-100 mt-6">
      <div class="mb-4">
        <h3 class="text-lg font-black text-red-800 tracking-tight flex items-center gap-2">
          <i data-lucide="alert-circle" class="w-5 h-5"></i> Riwayat Penolakan
        </h3>
      </div>
      <div class="overflow-hidden">
        <table id="riwayat_penolakan" class="w-full whitespace-nowrap"></table>
      </div>
    </div>
  </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Dependencies -->
<script src="<?= base_url() ?>/assets/libs/jquery/jquery.min.js"></script>
<script src="<?= base_url() ?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url() ?>/assets/libs/imask/imask.min.js"></script>

<!-- Native Modal Logic -->
<script src="<?= base_url('js/modal-native.js') ?>"></script>

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