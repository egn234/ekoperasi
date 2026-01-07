<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-8 pb-20">
  <!-- Header -->
  <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">
    <div>
      <h1 class="text-3xl font-black text-slate-800 tracking-tight">Verifikasi Anggota Baru</h1>
      <p class="text-slate-500 font-medium">Daftar pengguna baru yang menunggu verifikasi admin.</p>
    </div>
  </div>

  <?= session()->getFlashdata('notif'); ?>

  <!-- Table Card -->
  <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
    <div class="mb-6 flex items-center gap-3">
      <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
        <i data-lucide="user-plus" class="w-6 h-6"></i>
      </div>
      <div>
        <h3 class="text-xl font-black text-slate-900 tracking-tight">Daftar Pendaftar</h3>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Perlu Tindakan</p>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table id="dataTable" class="w-full whitespace-nowrap">
        <thead>
          <tr>
            <th>No</th>
            <th>User</th>
            <th>Kontak</th>
            <th>Instansi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modals managed by modal-native.js -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  function closeNativeModal() {
    ModalHelper.close();
  }

  function openDetailModal(id) {
    ModalHelper.open('<?= base_url() ?>/admin/register/detail_user', {
      rowid: id
    });
  }

  $(document).ready(function() {
    $('#dataTable').DataTable({
      ajax: {
        url: "<?= base_url() ?>admin/register/data_user",
        type: "POST"
      },
      autoWidth: false,
      serverSide: true,
      // Unified DOM Layout
      dom: '<"flex justify-between items-center gap-4 mb-4"lf><"rounded-xl border border-slate-100"t><"flex justify-between items-center gap-4 mt-4"ip>',
      language: {
        search: "",
        searchPlaceholder: "Cari Pendaftar...",
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
      },
      columnDefs: [{
        orderable: false,
        targets: [0, 4],
        searchable: false
      }],
      columns: [{
          title: "No",
          width: "50px",
          className: "text-center",
          render: function(data, type, row, meta) {
            return `<span class="font-bold text-slate-400 text-xs">${meta.row + 1}</span>`;
          }
        },
        {
          title: "User",
          render: function(data, type, row) {
            let profilePic = row.profil_pic ? `<?= base_url() ?>/uploads/user/${row.username}/profil_pic/${row.profil_pic}` : `<?= base_url('assets/images/users/avatar-1.jpg') ?>`;
            return `<div class="flex items-center gap-4">
                      <div class="relative group">
                        <div class="absolute -inset-0.5 bg-gradient-to-tr from-blue-600 to-indigo-600 rounded-full opacity-20 group-hover:opacity-40 transition-opacity blur"></div>
                        <div class="relative w-11 h-11 rounded-full bg-slate-100 flex items-center justify-center overflow-hidden border-2 border-white shadow-sm">
                          <img src="${profilePic}" 
                               alt="${row.username}" 
                               class="w-full h-full object-cover"
                               onerror="this.src='<?= base_url('assets/images/users/avatar-1.jpg') ?>'">
                        </div>
                      </div>
                      <div class="flex flex-col">
                        <p class="font-bold text-slate-900 text-sm leading-tight">${row.nama_lengkap}</p>
                        <p class="text-[11px] font-medium text-slate-400">@${row.username}</p>
                      </div>
                    </div>`;
          }
        },
        {
          title: "Kontak",
          render: function(data, type, row) {
            return `<div class="flex flex-col gap-0.5">
                      <div class="flex items-center gap-1.5 text-slate-600">
                        <i data-lucide="mail" class="w-3 h-3"></i>
                        <p class="text-xs font-medium truncate max-w-[150px]">${row.email || '-'}</p>
                      </div>
                      <div class="flex items-center gap-1.5 text-slate-400">
                        <i data-lucide="phone" class="w-3 h-3 text-slate-300"></i>
                        <p class="text-[10px] font-bold tracking-tight">${row.nomor_telepon || '-'}</p>
                      </div>
                    </div>`;
          }
        },
        {
          title: "Instansi",
          data: "instansi",
          render: function(d) {
            return `<div class="flex items-center gap-2">
                      <div class="w-1.5 h-1.5 rounded-full bg-slate-200"></div>
                      <span class="text-xs font-bold text-slate-600 tracking-tight">${d || '-'}</span>
                    </div>`;
          }
        },
        {
          title: "Aksi",
          className: "text-right",
          render: function(data, type, row, full) {
            return `<button onclick="openDetailModal('${row.iduser}')" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-blue-600 hover:text-white transition-all flex items-center justify-center border border-slate-100 shadow-sm ml-auto" title="Detail Verifikasi Registration">
                      <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                    </button>`;
          }
        }
      ]
    });
  });
</script>
<?= $this->endSection() ?>