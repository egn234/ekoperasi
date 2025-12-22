<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<!-- DataTables CSS -->
<link href="<?= base_url() ?>/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<style>
  div.dataTables_wrapper div.dataTables_filter input {
    border-radius: 0.5rem;
    padding: 0.5rem;
    border: 1px solid #e2e8f0;
    font-size: 0.875rem;
  }

  div.dataTables_wrapper div.dataTables_length select {
    border-radius: 0.5rem;
    padding: 0.25rem 2rem 0.25rem 0.5rem;
    border: 1px solid #e2e8f0;
    font-size: 0.875rem;
  }

  table.dataTable thead th {
    border-bottom: 2px solid #e2e8f0 !important;
    color: #475569;
    font-weight: 900;
    text-transform: uppercase;
    font-size: 0.7rem;
    letter-spacing: 0.05em;
    padding: 1rem !important;
  }

  table.dataTable tbody td {
    padding: 1rem !important;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    color: #1e293b;
    font-size: 0.875rem;
  }

  table.dataTable tr:hover td {
    background-color: #f8fafc;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="space-y-8 pb-20">
  <!-- Header -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
      <h1 class="text-3xl font-black text-slate-800 tracking-tight">Data Pinjaman</h1>
      <p class="text-slate-500 font-medium">Kelola pengajuan, persetujuan, dan riwayat pinjaman anggota.</p>
    </div>
  </div>

  <?= session()->getFlashdata('notif'); ?>

  <!-- Table 1: Menunggu Persetujuan (Status = 2) -->
  <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
    <div class="mb-6 flex items-center gap-3">
      <div class="p-2 bg-amber-50 text-amber-600 rounded-xl">
        <i data-lucide="clock" class="w-6 h-6"></i>
      </div>
      <div>
        <h3 class="text-xl font-black text-slate-900 tracking-tight">Menunggu Persetujuan</h3>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Perlu Tindakan Segera</p>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table id="dt_list_filter" class="w-full whitespace-nowrap">
        <thead>
          <tr>
            <th>No</th>
            <th>Peminjam</th>
            <th>Tipe</th>
            <th>Nominal</th>
            <th>Tenor</th>
            <th>Dokumen</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

  <!-- Table 2: Semua Pinjaman -->
  <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
    <div class="mb-6 flex items-center gap-3">
      <div class="p-2 bg-blue-50 text-blue-600 rounded-xl">
        <i data-lucide="list" class="w-6 h-6"></i>
      </div>
      <div>
        <h3 class="text-xl font-black text-slate-900 tracking-tight">Riwayat Pinjaman</h3>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Daftar Semua Transaksi</p>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table id="dt_list" class="w-full whitespace-nowrap">
        <thead>
          <tr>
            <th>No</th>
            <th>Peminjam</th>
            <th>Tipe</th>
            <th>Nominal</th>
            <th>Tenor</th>
            <th>Status</th>
            <th>Validasi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
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
<script src="<?= base_url() ?>/assets/libs/jquery/jquery.min.js"></script>
<script src="<?= base_url() ?>/assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
<!-- Native Modal Logic -->
<script src="<?= base_url('js/modal-native.js') ?>"></script>

<script type="text/javascript">
  function closeNativeModal() {
    $('#dynamic-modal-overlay, #dynamic-modal-content').addClass('hidden');
  }

  // Open Modal Helpers
  function openApproveModal(id) {
    ModalHelper.open('<?= base_url() ?>admin/pinjaman/approve-pinjaman', {
      rowid: id
    });
  }

  function openRejectModal(id) {
    ModalHelper.open('<?= base_url() ?>admin/pinjaman/cancel-pinjaman', {
      rowid: id
    });
  }

  function openDetailModal(id) {
    ModalHelper.open('<?= base_url() ?>admin/pinjaman/detail-pinjaman', {
      rowid: id
    });
  }

  $(document).ready(function() {
    // Table 1: Filtered (Pending)
    $('#dt_list_filter').DataTable({
      ajax: {
        url: "<?= base_url() ?>admin/pinjaman/data_pinjaman_filter",
        type: "POST"
      },
      autoWidth: false,
      scrollX: true,
      serverSide: true,
      language: {
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
          data: null,
          render: function(data, type, row, meta) {
            return `<span class="font-bold text-slate-500">${meta.row + 1}</span>`;
          }
        },
        {
          data: null,
          render: function(data, type, row) {
            return `<div class="flex items-center gap-3">
                      <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs">
                        <i data-lucide="user" class="w-4 h-4"></i>
                      </div>
                      <div>
                        <p class="font-bold text-slate-900 text-sm">${row.nama_peminjam}</p>
                        <p class="text-xs text-slate-400">${row.nik_peminjam}</p>
                      </div>
                    </div>`;
          }
        },
        {
          data: "tipe_permohonan",
          render: function(d) {
            return `<span class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded text-[10px] font-black uppercase tracking-wider">${d}</span>`;
          }
        },
        {
          data: "nominal",
          render: function(d) {
            return `<span class="font-bold text-emerald-600">Rp ${parseInt(d).toLocaleString('id-ID')}</span>`;
          }
        },
        {
          data: "angsuran_bulanan",
          render: function(d) {
            return `<span class="font-bold text-slate-700">${d} Bulan</span>`;
          }
        },
        {
          data: null,
          render: function(data, type, row) {
            return `<div class="flex flex-col gap-1 w-fit">
                      <a href="<?= base_url() ?>uploads/user/${row.username_peminjam}/pinjaman/${row.form_bukti}" target="_blank" class="flex items-center gap-1.5 px-2 py-1 rounded bg-slate-50 text-[10px] font-bold text-slate-500 hover:text-blue-600 transition-colors">
                        <i data-lucide="file-text" class="w-3 h-3"></i> Form
                      </a>
                      <a href="<?= base_url() ?>uploads/user/${row.username_peminjam}/pinjaman/${row.slip_gaji}" target="_blank" class="flex items-center gap-1.5 px-2 py-1 rounded bg-slate-50 text-[10px] font-bold text-slate-500 hover:text-emerald-600 transition-colors">
                        <i data-lucide="receipt" class="w-3 h-3"></i> Slip
                      </a>
                    </div>`;
          }
        },
        {
          data: null,
          className: "text-right",
          render: function(data, type, row) {
            return `<div class="flex justify-end gap-2">
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
      }
    });

    // Table 2: All Data
    $('#dt_list').DataTable({
      ajax: {
        url: "<?= base_url() ?>admin/pinjaman/data_pinjaman",
        type: "POST"
      },
      autoWidth: false,
      scrollX: true,
      serverSide: true,
      language: {
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
          data: null,
          render: function(data, type, row, meta) {
            return `<span class="font-bold text-slate-500">${meta.row + 1}</span>`;
          }
        },
        {
          data: null,
          render: function(data, type, row) {
            return `<div class="flex items-center gap-3">
                      <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs">
                        ${row.nama_peminjam.substring(0,2).toUpperCase()}
                      </div>
                      <div>
                        <p class="font-bold text-slate-900 text-sm">${row.nama_peminjam}</p>
                        <p class="text-xs text-slate-400">${row.nik_peminjam}</p>
                      </div>
                    </div>`;
          }
        },
        {
          data: "tipe_permohonan"
        },
        {
          data: "nominal",
          render: function(d) {
            return `Rp ${parseInt(d).toLocaleString('id-ID')}`;
          }
        },
        {
          data: "angsuran_bulanan",
          render: function(d) {
            return d + ' Bulan';
          }
        },
        {
          data: "status",
          render: function(d) {
            const statusMap = {
              '0': '<span class="px-2 py-1 bg-red-50 text-red-600 rounded text-[10px] font-black uppercase tracking-wider">Ditolak</span>',
              '1': '<span class="px-2 py-1 bg-yellow-50 text-yellow-600 rounded text-[10px] font-black uppercase tracking-wider">Upload Dokumen</span>',
              '2': '<span class="px-2 py-1 bg-amber-50 text-amber-600 rounded text-[10px] font-black uppercase tracking-wider">Verifikasi Admin</span>',
              '3': '<span class="px-2 py-1 bg-blue-50 text-blue-600 rounded text-[10px] font-black uppercase tracking-wider">Approval Bendahara</span>',
              '4': '<span class="px-2 py-1 bg-emerald-50 text-emerald-600 rounded text-[10px] font-black uppercase tracking-wider">Disetujui</span>',
              '5': '<span class="px-2 py-1 bg-purple-50 text-purple-600 rounded text-[10px] font-black uppercase tracking-wider">Lunas</span>',
              '6': '<span class="px-2 py-1 bg-cyan-50 text-cyan-600 rounded text-[10px] font-black uppercase tracking-wider">Konfirmasi Pelunasan</span>'
            };
            return statusMap[d] || d;
          }
        },
        {
          data: null,
          render: function(data, type, row) {
            let admin = row.nama_admin ? `<span class="text-xs text-blue-600 lowercase" title="Admin">@${row.nama_admin}</span>` : '';
            let bendahara = row.nama_bendahara ? `<span class="text-xs text-emerald-600 lowercase" title="Bendahara">@${row.nama_bendahara}</span>` : '';
            return `<div class="flex gap-1">${admin} ${bendahara}</div>`;
          }
        },
        {
          data: null,
          className: "text-right",
          render: function(data, type, row) {
            return `<button onclick="openDetailModal('${row.idpinjaman}')" class="p-2 rounded-xl bg-slate-50 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors" title="Detail">
                      <i data-lucide="search" class="w-4 h-4"></i>
                    </button>`;
          }
        }
      ],
      drawCallback: function() {
        if (window.lucide) window.lucide.createIcons();
      }
    });
  });
</script>
<?= $this->endSection() ?>