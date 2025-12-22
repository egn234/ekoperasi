<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<!-- DataTables CSS -->
<link href="<?= base_url() ?>/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<style>
  /* Custom DataTable Styling to Match Tailwind */
  div.dataTables_wrapper div.dataTables_filter input {
    border-radius: 0.5rem;
    padding: 0.5rem;
    border: 1px solid #e2e8f0;
  }

  div.dataTables_wrapper div.dataTables_length select {
    border-radius: 0.5rem;
    padding: 0.25rem 2rem 0.25rem 0.5rem;
    border: 1px solid #e2e8f0;
  }

  table.dataTable thead th {
    border-bottom: 1px solid #e2e8f0 !important;
    color: #94a3b8;
    font-weight: 900;
    text-transform: uppercase;
    font-size: 0.65rem;
    letter-spacing: 0.05em;
  }

  table.dataTable tbody td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #f8fafc;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="space-y-8">

  <!-- Welcome Section -->
  <div class="bg-indigo-gradient rounded-[2rem] p-8 md:p-10 relative overflow-hidden shadow-xl shadow-indigo-200/50 text-white">
    <!-- Background Decor -->
    <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none">
      <i data-lucide="calculator" class="w-64 h-64"></i>
    </div>

    <div class="relative z-10">
      <h1 class="text-3xl font-black tracking-tight mb-2">Dashboard Bendahara</h1>
      <p class="font-medium text-indigo-100 max-w-2xl">
        Kelola keuangan koperasi dengan presisi. Verifikasi transaksi dan pantau arus kas secara real-time.
      </p>
    </div>
  </div>

  <!-- Stats Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Card 1 -->
    <div class="bg-white rounded-[2rem] p-6 shadow-soft hover:shadow-lg transition-shadow border border-slate-100 group">
      <div class="flex justify-between items-start mb-4">
        <div class="p-3 bg-blue-50 rounded-2xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-colors">
          <i data-lucide="users" class="w-6 h-6"></i>
        </div>
        <span class="bg-slate-50 text-slate-400 text-[10px] font-black uppercase tracking-wider px-2 py-1 rounded-lg">TOTAL</span>
      </div>
      <h3 class="text-3xl font-black text-slate-800 mb-1"><?= $total_anggota ?></h3>
      <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Anggota Koperasi</p>
    </div>

    <!-- Card 2 -->
    <div class="bg-white rounded-[2rem] p-6 shadow-soft hover:shadow-lg transition-shadow border border-slate-100 group">
      <div class="flex justify-between items-start mb-4">
        <div class="p-3 bg-emerald-50 rounded-2xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
          <i data-lucide="user-plus" class="w-6 h-6"></i>
        </div>
        <span class="bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-wider px-2 py-1 rounded-lg">+<?= $monthly_user ?></span>
      </div>
      <h3 class="text-3xl font-black text-slate-800 mb-1"><?= $monthly_user ?></h3>
      <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Anggota Baru (Bln Ini)</p>
    </div>

    <!-- Card 3 -->
    <div class="bg-white rounded-[2rem] p-6 shadow-soft hover:shadow-lg transition-shadow border border-slate-100 group">
      <div class="flex justify-between items-start mb-4">
        <div class="p-3 bg-orange-50 rounded-2xl text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors">
          <i data-lucide="hand-coins" class="w-6 h-6"></i>
        </div>
        <span class="bg-slate-50 text-slate-400 text-[10px] font-black uppercase tracking-wider px-2 py-1 rounded-lg">AKTIF</span>
      </div>
      <h3 class="text-3xl font-black text-slate-800 mb-1"><?= $anggota_pinjaman ?></h3>
      <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Peminjam Aktif</p>
    </div>

    <!-- Card 4 -->
    <div class="bg-white rounded-[2rem] p-6 shadow-soft hover:shadow-lg transition-shadow border border-slate-100 group">
      <div class="flex justify-between items-start mb-4">
        <div class="p-3 bg-indigo-50 rounded-2xl text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
          <i data-lucide="wallet" class="w-6 h-6"></i>
        </div>
      </div>
      <h3 class="text-2xl font-black text-slate-800 mb-1">Rp <?= number_format($uang_giat, 0, ',', '.') ?></h3>
      <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Deposit GIAT</p>
    </div>

    <!-- Card 5 (Income) -->
    <div class="bg-white rounded-[2rem] p-6 shadow-soft hover:shadow-lg transition-shadow border border-slate-100 group">
      <div class="flex justify-between items-start mb-4">
        <div class="p-3 bg-emerald-100 rounded-2xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
          <i data-lucide="trending-up" class="w-6 h-6"></i>
        </div>
      </div>
      <h3 class="text-2xl font-black text-slate-800 mb-1">Rp <?= number_format($monthly_income, 0, ',', '.') ?></h3>
      <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Income Bulan Ini</p>
    </div>

    <!-- Card 6 (Outcome) -->
    <div class="bg-white rounded-[2rem] p-6 shadow-soft hover:shadow-lg transition-shadow border border-slate-100 group">
      <div class="flex justify-between items-start mb-4">
        <div class="p-3 bg-rose-100 rounded-2xl text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-colors">
          <i data-lucide="trending-down" class="w-6 h-6"></i>
        </div>
      </div>
      <h3 class="text-2xl font-black text-slate-800 mb-1">Rp <?= number_format($monthly_outcome, 0, ',', '.') ?></h3>
      <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Outcome Bulan Ini</p>
    </div>
  </div>

  <!-- Chart Section -->
  <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-100">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
      <div>
        <h3 class="text-xl font-black text-slate-900 tracking-tight">Grafik Trends</h3>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Analisis Data Koperasi</p>
      </div>

      <div class="flex flex-wrap gap-3 items-center">
        <select id="chartType" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs font-bold uppercase tracking-wider outline-none focus:ring-2 focus:ring-indigo-500">
          <option value="deposit">Deposit</option>
          <option value="loan">Pinjaman</option>
          <option value="member">Anggota</option>
        </select>

        <select id="chartRange" class="bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-xs font-bold uppercase tracking-wider outline-none focus:ring-2 focus:ring-indigo-500">
          <option value="3months">3 Bulan</option>
          <option value="6months" selected>6 Bulan</option>
          <option value="12months">12 Bulan</option>
          <option value="2years">2 Tahun</option>
        </select>

        <button id="refreshChart" class="p-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-colors">
          <i data-lucide="refresh-cw" class="w-4 h-4"></i>
        </button>
      </div>
    </div>

    <div id="chartLoading" class="hidden text-center py-12">
      <div class="w-8 h-8 border-4 border-indigo-100 border-t-indigo-600 rounded-full animate-spin mx-auto mb-3"></div>
      <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Memuat Grafik...</p>
    </div>

    <div id="spline_area" data-colors='["#6366f1", "#10b981", "#f59e0b"]' class="w-full min-h-[350px]"></div>
  </div>

  <!-- Table Section with DataTable -->
  <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-100">
    <div class="mb-6">
      <h3 class="text-xl font-black text-slate-900 tracking-tight">Verifikasi Pengajuan Pinjaman</h3>
      <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Kelola Pengajuan yang Masuk</p>
    </div>

    <?= session()->getFlashdata('notif'); ?>
    <?= session()->getFlashdata('notif_tf'); ?>

    <div class="overflow-x-auto">
      <table id="dt_list_filter" class="w-full whitespace-nowrap">
        <!-- Header will be generated by DataTable -->
      </table>
    </div>
  </div>
</div>

<!-- Modal Container (Native/jQuery Hybrid) -->
<div id="dynamic-modal-overlay" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
<div id="dynamic-modal-content" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-50 hidden w-full max-w-lg bg-white rounded-[2rem] shadow-2xl p-6 md:p-8 max-h-[90vh] overflow-y-auto">
  <div id="modal-container"></div>
  <button id="close-modal-btn" class="absolute top-6 right-6 p-2 hover:bg-slate-100 rounded-full transition-colors">
    <i data-lucide="x" class="w-5 h-5 text-slate-400"></i>
  </button>
</div>

<!-- Legacy Modals needed for DataTable triggers if we don't rewrite 100% -->
<!-- We will use custom logic to intercept the clicks from DataTable -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Dependencies for DataTable -->
<script src="<?= base_url() ?>/assets/libs/jquery/jquery.min.js"></script>
<script src="<?= base_url() ?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<!-- Chart Logic -->
<script type="text/javascript">
  window.baseUrl = '<?= base_url() ?>';
</script>
<script src="<?= base_url() ?>/assets/js/pages/dashboard-chart.js"></script>

<script>
  $(document).ready(function() {
    function numberFormat(number, decimals = 0, decimalSeparator = ',', thousandSeparator = '.') {
      number = parseFloat(number).toFixed(decimals);
      number = number.replace('.', decimalSeparator);
      var parts = number.split(decimalSeparator);
      parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator);
      return parts.join(decimalSeparator);
    }

    // Initialize DataTable
    var table = $('#dt_list_filter').DataTable({
      ajax: {
        url: "<?= base_url() ?>bendahara/pinjaman/data_pinjaman",
        type: "POST",
        data: function(d) {
          d.length = d.length || 10;
        }
      },
      autoWidth: false,
      scrollX: true,
      serverSide: true,
      searching: true,
      responsive: true,
      pageLength: 10,
      lengthMenu: [
        [10, 25, 50, -1],
        [10, 25, 50, "Semua"]
      ],
      language: {
        search: "_INPUT_",
        searchPlaceholder: "Cari pengajuan...",
        lengthMenu: "_MENU_",
        paginate: {
          first: "First",
          last: "Last",
          next: "Next",
          previous: "Prev"
        }
      },
      columnDefs: [{
        orderable: false,
        targets: "_all",
        defaultContent: "-"
      }],
      columns: [{
          title: "No",
          render: function(data, type, row, meta) {
            return '<span class="bg-blue-50 text-blue-600 px-2 py-1 rounded-lg text-xs font-black">' + (meta.row + 1) + '</span>';
          }
        },
        {
          title: "Nama",
          render: function(data, type, row) {
            return '<div class="flex items-center gap-2"><div><p class="text-sm font-bold text-slate-900">' + row.nama_peminjam + '</p><p class="text-[10px] text-slate-400 font-bold uppercase">' + row.username_peminjam + '</p></div></div>';
          }
        },
        {
          title: "Tipe",
          render: function(data, type, row) {
            return '<span class="px-2 py-1 rounded-lg bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase">' + row.tipe_permohonan + '</span>';
          }
        },
        {
          title: "Nominal",
          render: function(data, type, row) {
            return '<strong class="text-emerald-600 font-black">Rp ' + numberFormat(row.nominal, 0) + '</strong>';
          }
        },
        {
          title: "Tanggal",
          render: function(data, type, row) {
            let date = new Date(row.date_created);
            return date.toLocaleDateString('id-ID', {
              day: 'numeric',
              month: 'short',
              year: 'numeric'
            });
          }
        },
        {
          title: "Tenor",
          render: function(data, type, row) {
            return '<span class="font-bold text-slate-700">' + row.angsuran_bulanan + ' Bln</span>';
          }
        },
        {
          title: "Dokumen",
          render: function(data, type, row) {
            let links = '<div class="flex flex-col gap-1 w-fit">';
            links += '<a href="<?= base_url() ?>/uploads/user/' + row.username_peminjam + '/pinjaman/' + row.form_bukti + '" target="_blank" class="flex items-center gap-1 px-2 py-1 rounded border border-slate-200 text-[10px] font-bold text-slate-500 hover:text-blue-600"><i data-lucide="file-text" class="w-3 h-3"></i> Form</a>';
            return links + '</div>';
          }
        },
        {
          title: "Aksi",
          render: function(data, type, row) {
            return '<div class="flex gap-2 justify-end">' +
              '<button class="action-btn-tolak p-2 rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-sm" data-id="' + row.idpinjaman + '"><i data-lucide="x" class="w-4 h-4"></i></button>' +
              '<button class="action-btn-terima p-2 rounded-xl bg-emerald-50 text-emerald-500 hover:bg-emerald-500 hover:text-white transition-all shadow-sm" data-id="' + row.idpinjaman + '"><i data-lucide="check" class="w-4 h-4"></i></button>' +
              '</div>';
          }
        }
      ],
      drawCallback: function() {
        lucide.createIcons(); // Re-init icons

        // Re-bind click events
        $('.action-btn-tolak').off('click').on('click', function() {
          openNativeModal('tolak', $(this).data('id'));
        });
        $('.action-btn-terima').off('click').on('click', function() {
          openNativeModal('terima', $(this).data('id'));
        });
      }
    });

    // Native Modal Logic adapted for jQuery triggers
    function openNativeModal(type, id) {
      $('#dynamic-modal-overlay, #dynamic-modal-content').removeClass('hidden');
      $('#modal-container').html('<div class="text-center py-12"><div class="w-10 h-10 border-4 border-slate-100 border-t-indigo-600 rounded-full animate-spin mx-auto"></div></div>');

      let url = (type === 'terima') ? '<?= base_url() ?>/bendahara/pinjaman/approve-pinjaman' : '<?= base_url() ?>/bendahara/pinjaman/cancel-pinjaman';

      $.ajax({
        type: 'POST',
        url: url,
        data: {
          rowid: id
        },
        success: function(data) {
          $('#modal-container').html(data);
        },
        error: function() {
          $('#modal-container').html('<p class="text-red-500 font-bold text-center">Gagal memuat data</p>');
        }
      });
    }

    $('#close-modal-btn, #dynamic-modal-overlay').on('click', function() {
      $('#dynamic-modal-overlay, #dynamic-modal-content').addClass('hidden');
    });
  });
</script>
<?= $this->endSection() ?>