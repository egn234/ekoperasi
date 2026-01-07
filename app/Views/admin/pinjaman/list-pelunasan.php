<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-8 pb-20">
  <!-- Header -->
  <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">
    <div>
      <h1 class="text-3xl font-black text-slate-800 tracking-tight">Pelunasan Pinjaman</h1>
      <p class="text-slate-500 font-medium">Manajemen persetujuan pelunasan pinjaman anggota.</p>
    </div>
  </div>

  <?= session()->getFlashdata('notif') ?>

  <!-- Table Card -->
  <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
    <div class="mb-6">
      <h3 class="text-xl font-black text-slate-900 tracking-tight">Daftar Pengajuan</h3>
      <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Status: Menunggu Persetujuan</p>
    </div>

    <div class="overflow-hidden">
      <table id="dt_list" class="w-full text-left border-collapse">
        <thead class="bg-slate-50">
          <tr>
            <th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-left rounded-l-xl">Username</th>
            <th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-left">Nama Lengkap</th>
            <th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-left">Nominal Pinjaman</th>
            <th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-left">Sisa Bayar</th>
            <th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-left">Tenor</th>
            <th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-left">Sisa Tenor</th>
            <th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-left">Tanggal</th>
            <th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-center">Bukti</th>
            <th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-center rounded-r-xl">Aksi</th>
          </tr>
        </thead>
        <tbody class="text-sm text-slate-600 font-medium"></tbody>
      </table>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="text/javascript">
  function numberFormat(number, decimals = 0, decimalSeparator = ',', thousandSeparator = '.') {
    number = parseFloat(number).toFixed(decimals);
    number = number.replace('.', decimalSeparator);
    var parts = number.split(decimalSeparator);
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator);
    return parts.join(decimalSeparator);
  }

  // Modal helper
  function openApproveModal(id) {
    ModalHelper.open('<?= base_url() ?>/admin/pinjaman/approve-pelunasan', {
      rowid: id
    });
  }

  function openRejectModal(id) {
    ModalHelper.open('<?= base_url() ?>/admin/pinjaman/cancel-pelunasan', {
      rowid: id
    });
  }

  $(document).ready(function() {
    $('#dt_list').DataTable({
      ajax: {
        url: "<?= base_url() ?>admin/pinjaman/data_pelunasan",
        type: "POST",
        data: function(d) {
          d.length = d.length || 10;
        }
      },
      autoWidth: false,
      scrollX: true,
      serverSide: true,
      searching: true,
      language: {
        paginate: {
          first: '<<',
          last: '>>',
          next: '>',
          previous: '<'
        }
      },
      columnDefs: [{
        orderable: false,
        targets: "_all",
        defaultContent: "-",
      }],
      columns: [{
          data: "username",
          className: "p-4"
        },
        {
          data: "nama_lengkap",
          className: "p-4",
          render: function(data) {
            return `<span class="font-bold text-slate-700">${data}</span>`;
          }
        },
        {
          data: "nominal",
          className: "p-4",
          render: function(data) {
            return `<span class="font-bold text-blue-600">Rp ${numberFormat(data, 2)}</span>`;
          }
        },
        {
          data: "sisa_pinjaman",
          className: "p-4",
          render: function(data) {
            return `<span class="font-bold text-orange-500">Rp ${numberFormat(data, 2)}</span>`;
          }
        },
        {
          data: "angsuran_bulanan",
          className: "p-4 text-center",
          render: function(data) {
            return `${data} Bln`;
          }
        },
        {
          data: "sisa_cicilan",
          className: "p-4 text-center",
          render: function(data) {
            return `<span class="font-bold text-slate-800">${data} Bln</span>`;
          }
        },
        {
          data: "date_updated",
          className: "p-4",
          render: function(data) {
            return `<span class="text-xs text-slate-500">${data}</span>`;
          }
        },
        {
          data: "bukti_tf",
          className: "p-4 text-center",
          render: function(data, type, row) {
            if (row.bukti_tf) {
              return `<a href="<?= base_url() ?>/uploads/user/${row.username}/pinjaman/${row.bukti_tf}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-bold hover:bg-indigo-100 transition-colors">
                        <i data-lucide="download" class="w-3 h-3"></i> Bukti
                       </a>`;
            }
            return '<span class="text-slate-300">-</span>';
          }
        },
        {
          className: "p-4 text-center",
          render: function(data, type, row) {
            return `<div class="flex justify-center gap-2">
                      <button onclick="openRejectModal('${row.idpinjaman}')" class="p-2 rounded-xl bg-red-50 text-red-600 hover:bg-red-100 transition-colors" title="Tolak">
                        <i data-lucide="x" class="w-4 h-4"></i>
                      </button>
                      <button onclick="openApproveModal('${row.idpinjaman}')" class="p-2 rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors" title="Setujui">
                        <i data-lucide="check" class="w-4 h-4"></i>
                      </button>
                    </div>`;
          }
        }
      ],
      drawCallback: function() {
        if (window.lucide) window.lucide.createIcons();
        $('.dataTables_filter input').addClass('focus:ring-2 focus:ring-blue-500 focus:outline-none');
      }
    });
  });
</script>
<?= $this->endSection() ?>