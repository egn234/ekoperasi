<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-8 pb-20">
  <!-- Header -->
  <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">
    <div class="flex items-center gap-3">
      <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl">
        <i data-lucide="wallet" class="w-8 h-8"></i>
      </div>
      <div>
        <h1 class="text-3xl font-black text-slate-900 tracking-tight">Kelola Simpanan</h1>
        <p class="text-slate-500 font-medium">Manajemen simpanan pokok, wajib, dan manasuka per anggota.</p>
      </div>
    </div>

    <button onclick="openModal('importUserModal')" class="px-5 py-2.5 bg-slate-800 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-900 shadow-lg shadow-slate-200 transition-all hover:scale-[1.02] flex items-center gap-2">
      <i data-lucide="upload" class="w-4 h-4"></i>
      Import Data Anggota
    </button>
  </div>

  <?= session()->getFlashdata('notif'); ?>

  <!-- Main Card -->
  <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
    <div class="mb-6">
      <h3 class="text-xl font-black text-slate-900 tracking-tight">Daftar Anggota</h3>
      <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Status Keaktifan & Simpanan</p>
    </div>

    <div class="overflow-x-auto">
      <table id="dataTable" class="w-full whitespace-nowrap">
        <thead>
          <tr>
            <th>No</th>
            <th>User</th>
            <th>Kontak</th>
            <th>Instansi</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

<!-- Import Modal Template -->
<template id="tmpl-import">
  <div class="flex justify-between items-center mb-6 pb-6 border-b border-slate-100">
    <h3 class="text-xl font-black text-slate-900">Import Simpanan</h3>
    <button onclick="ModalHelper.close()" class="p-2 bg-slate-50 text-slate-400 rounded-full hover:bg-slate-100 transition-colors">
      <i data-lucide="x" class="w-5 h-5"></i>
    </button>
  </div>

  <form action="<?= url_to('admin_user_import') ?>" method="post" enctype="multipart/form-data" class="space-y-6">
    <div>
      <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">File Spreadsheet <span class="text-red-500">*</span></label>
      <div class="relative group">
        <input type="file" name="file_import" accept=".csv, .xls, .xlsx" class="w-full bg-slate-50 border-2 border-dashed border-slate-200 rounded-[1.5rem] px-6 py-10 text-center text-sm cursor-pointer group-hover:border-blue-400 group-hover:bg-blue-50/30 transition-all file:hidden" id="file_import" required onchange="updateFilePreview(this)">
        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none" id="file_placeholder">
          <i data-lucide="cloud-upload" class="w-8 h-8 text-slate-400 mb-2 group-hover:text-blue-500 transition-colors"></i>
          <p class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Pilih atau Drag File</p>
          <p class="text-[10px] text-slate-400 mt-1">.xls, .xlsx, atau .csv</p>
        </div>
      </div>
      <p class="text-[11px] text-slate-400 mt-4 text-center">Belum punya template? <a href="<?= base_url() ?>assets/import_format.xlsx" class="text-blue-600 hover:underline font-black uppercase tracking-widest">Unduh Template</a></p>
    </div>

    <div class="flex gap-3">
      <button type="button" onclick="ModalHelper.close()" class="flex-1 py-4 bg-slate-100 text-slate-500 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">Batal</button>
      <button type="submit" class="flex-1 py-4 bg-emerald-600 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all hover:scale-[1.02]">Import Data</button>
    </div>
  </form>
</template>

<!-- Scripts -->
<script>
  function openModal(id) {
    if (id === 'importUserModal') {
      ModalHelper.openContent(document.getElementById('tmpl-import').innerHTML, function() {
        // Re-init lucide icons inside modal
        if (window.lucide) lucide.createIcons();
      });
    }
  }

  function updateFilePreview(input) {
    const fileName = $(input).val().split('\\').pop();
    if (fileName) {
      $('#file_placeholder').html(`
          <i data-lucide="file-check" class="w-8 h-8 text-emerald-500 mb-2"></i>
          <p class="text-[11px] font-black text-emerald-600 uppercase tracking-widest text-center">${fileName}</p>
          <p class="text-[10px] text-slate-400 mt-1">File siap diupload</p>
        `);
      if (window.lucide) lucide.createIcons();
    }
  }

  $(document).ready(function() {
    $('#dataTable').DataTable({
      ajax: {
        url: "<?= base_url() ?>admin/deposit/data_user",
        type: "POST"
      },
      serverSide: true,
      autoWidth: false,
      ordering: false,
      // Unified DOM Layout
      dom: '<"flex justify-between items-center gap-4 mb-4"lf><"rounded-xl border border-slate-100"t><"flex justify-between items-center gap-4 mt-4"ip>',
      language: {
        search: "",
        searchPlaceholder: "Cari Anggota...",
        lengthMenu: "_MENU_",
        info: "_START_ - _END_ dari _TOTAL_",
        paginate: {
          first: '<<',
          last: '>>',
          next: '>',
          previous: '<'
        }
      },
      columnDefs: [{
        orderable: false,
        targets: [0, 5],
        searchable: false
      }],
      drawCallback: function() {
        if (window.lucide) window.lucide.createIcons();
        $('.dataTables_filter input').addClass('px-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500');
      },
      columns: [{
          data: null,
          width: "50px",
          className: "text-center",
          render: function(data, type, row, meta) {
            return `<span class="font-bold text-slate-400 text-xs">${meta.row + 1}</span>`;
          }
        },
        {
          data: null,
          render: function(data, type, row) {
            return `<div class="flex items-center gap-4">
                      <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs ring-2 ring-white shadow-sm overflow-hidden">
                        ${row.nama_lengkap.substring(0,2).toUpperCase()}
                      </div>
                      <div class="flex flex-col">
                        <p class="font-bold text-slate-900 text-sm leading-tight">${row.nama_lengkap}</p>
                        <p class="text-[11px] font-medium text-slate-400">@${row.username}</p>
                      </div>
                    </div>`;
          }
        },
        {
          data: null,
          render: function(data, type, row) {
            return `<div class="flex flex-col gap-0.5">
                      <div class="flex items-center gap-1.5 text-slate-600">
                        <i data-lucide="mail" class="w-3 h-3 text-slate-400"></i>
                        <p class="text-xs font-medium truncate max-w-[150px]">${row.email || '-'}</p>
                      </div>
                      <div class="flex items-center gap-1.5 text-slate-400 font-mono">
                        <i data-lucide="phone" class="w-3 h-3 text-slate-300"></i>
                        <p class="text-[10px] font-bold tracking-tight">${row.nomor_telepon || '-'}</p>
                      </div>
                    </div>`;
          }
        },
        {
          data: "instansi",
          render: function(d) {
            return `<div class="flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-slate-200"></div>
                        <span class="text-xs font-bold text-slate-600 tracking-tight">${d || '-'}</span>
                    </div>`;
          }
        },
        {
          data: "flag",
          className: "text-center",
          render: function(data) {
            return data == "1" ?
              `<span class='inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-black uppercase tracking-wider border border-emerald-100/50'><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>Aktif</span>` :
              `<span class='inline-flex items-center px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-[10px] font-black uppercase tracking-wider border border-red-100/50'><span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span>Nonaktif</span>`;
          }
        },
        {
          data: "iduser",
          className: "text-right",
          render: function(data) {
            return `<div class="flex justify-end">
                      <a href="<?= base_url() ?>admin/deposit/user/${data}" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-blue-600 hover:text-white transition-all flex items-center justify-center border border-slate-100 shadow-sm" title="Lihat Simpanan">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                      </a>
                    </div>`;
          }
        }
      ]
    });
  });
</script>

<?= $this->endSection() ?>