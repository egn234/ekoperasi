<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="space-y-8 pb-20">
  <!-- Header -->
  <div class="flex items-center gap-3">
    <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl">
      <i data-lucide="wallet" class="w-8 h-8"></i>
    </div>
    <div>
      <h1 class="text-3xl font-black text-slate-900 tracking-tight">Kelola Simpanan Anggota</h1>
      <p class="text-slate-500 font-medium">Daftar anggota untuk pengelolaan simpanan pokok, wajib, dan manasuka.</p>
    </div>
  </div>

  <?= session()->getFlashdata('notif'); ?>

  <!-- Main Card -->
  <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
    <div class="flex items-center justify-between mb-8 pb-8 border-b border-slate-50">
      <div class="flex items-center gap-3">
        <div class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">
          <i data-lucide="users" class="w-6 h-6"></i>
        </div>
        <h3 class="text-xl font-black text-slate-900">Daftar Anggota</h3>
      </div>

      <!-- Import Button -->
      <button onclick="openModal('importUserModal')" class="px-5 py-2.5 bg-slate-800 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-900 shadow-lg shadow-slate-200 transition-all hover:scale-[1.02] flex items-center gap-2">
        <i data-lucide="upload" class="w-4 h-4"></i>
        Import User
      </button>
    </div>

    <div class="overflow-hidden">
      <table id="dataTable" class="w-full text-left border-collapse">
        <thead class="bg-slate-50">
          <tr>
            <th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-left rounded-l-xl">Username</th>
            <th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-left">Nama</th>
            <th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-left">Institusi</th>
            <th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-left">Email</th>
            <th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-left">No. Telepon</th>
            <th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-left">Status</th>
            <th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-center rounded-r-xl">Aksi</th>
          </tr>
        </thead>
        <tbody class="text-sm text-slate-600 font-medium">
          <!-- DataTables will populate this -->
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Import Modal -->
<div id="modal-overlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden transition-opacity"></div>
<div id="importUserModal" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-50 hidden w-full max-w-lg bg-white rounded-[2rem] shadow-2xl p-8">
  <div class="flex justify-between items-center mb-6 pb-6 border-b border-slate-100">
    <h3 class="text-xl font-black text-slate-900">Import User Simpanan</h3>
    <button onclick="closeModal('importUserModal')" class="p-2 bg-slate-50 text-slate-400 rounded-full hover:bg-slate-100 transition-colors">
      <i data-lucide="x" class="w-5 h-5"></i>
    </button>
  </div>

  <form action="<?= url_to('admin/user/tab_upload') ?>" method="post" enctype="multipart/form-data" class="space-y-6">
    <div>
      <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">File Spreadsheet <span class="text-red-500">*</span></label>
      <input type="file" name="file_import" accept=".csv, .xls, .xlsx" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-black file:uppercase file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100" required>
      <p class="text-[10px] text-slate-400 mt-2">Format: .xls, .xlsx, .csv. <a href="<?= base_url() ?>assets/import_format.xlsx" class="text-blue-600 hover:underline font-bold">Unduh Template</a></p>
    </div>

    <div class="flex gap-3 pt-4">
      <button type="button" onclick="closeModal('importUserModal')" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200">Batal</button>
      <button type="submit" class="flex-1 py-3 bg-emerald-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-200">Import Data</button>
    </div>
  </form>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
  function openModal(id) {
    document.getElementById('modal-overlay').classList.remove('hidden');
    document.getElementById(id).classList.remove('hidden');
  }

  function closeModal(id) {
    document.getElementById('modal-overlay').classList.add('hidden');
    document.getElementById(id).classList.add('hidden');
  }

  $(document).ready(function() {
    $('#dataTable').DataTable({
      ajax: {
        url: "<?= base_url() ?>admin/deposit/data_user",
        type: "POST",
        data: function(d) {
          d.length = d.length || 10;
        }
      },
      processing: true,
      serverSide: true,
      autoWidth: false,
      ordering: false, // Disable ordering for simpler implementation match
      language: {
        search: "",
        searchPlaceholder: "Cari Anggota...",
        lengthMenu: "_MENU_ per halaman",
        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
        paginate: {
          first: '<i class="lucide-chevrons-left"></i>',
          last: '<i class="lucide-chevrons-right"></i>',
          next: '<i class="lucide-chevron-right"></i>',
          previous: '<i class="lucide-chevron-left"></i>'
        }
      },
      dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-6"f><"overflow-x-auto rounded-xl border border-slate-100"t><"flex flex-col md:flex-row justify-between items-center gap-4 mt-6"ip>',
      columns: [{
          data: "username",
          className: "px-4 py-3 font-bold text-slate-800 border-b border-slate-50"
        },
        {
          data: "nama_lengkap",
          className: "px-4 py-3 border-b border-slate-50"
        },
        {
          data: "instansi",
          className: "px-4 py-3 border-b border-slate-50"
        },
        {
          data: "email",
          className: "px-4 py-3 border-b border-slate-50"
        },
        {
          data: "nomor_telepon",
          className: "px-4 py-3 border-b border-slate-50 font-mono text-xs"
        },
        {
          data: "flag",
          className: "px-4 py-3 border-b border-slate-50",
          render: function(data) {
            return data == "1" ?
              `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800">Aktif</span>` :
              `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800">Nonaktif</span>`;
          }
        },
        {
          data: "iduser",
          className: "px-4 py-3 border-b border-slate-50 text-center",
          render: function(data) {
            return `<a href="<?= base_url() ?>admin/deposit/user/${data}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </a>`;
          }
        }
      ],
      drawCallback: function() {
        lucide.createIcons(); // Re-init icons on draw

        // Style search input
        $('.dataTables_filter input').addClass('px-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent');

        // Style pagination buttons (generic approach, might need specific css targeting if datatables styles override)
      }
    });
  });
</script>

<?= $this->endSection() ?>