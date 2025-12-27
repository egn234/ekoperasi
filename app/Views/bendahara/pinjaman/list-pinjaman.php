<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-8 pb-20">
  <!-- Header -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
      <h1 class="text-3xl font-black text-slate-800 tracking-tight">Verifikasi Pinjaman</h1>
      <p class="text-slate-500 font-medium">Validasi pengajuan pinjaman dari anggota.</p>
    </div>
  </div>

  <?= session()->getFlashdata('notif'); ?>

  <!-- Table: Menunggu Persetujuan (Status = 3) -->
  <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
    <div class="mb-6 flex items-center gap-3">
      <div class="p-2 bg-theme-light text-theme-main rounded-xl">
        <i data-lucide="check-circle" class="w-6 h-6"></i>
      </div>
      <div>
        <h3 class="text-xl font-black text-slate-900 tracking-tight">Daftar Pengajuan</h3>
        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Status: Approval Bendahara</p>
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
            <th>Dokumen</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script type="text/javascript">
  // Open Modal Helpers
  function openApproveModal(id) {
    ModalHelper.open('<?= base_url() ?>bendahara/pinjaman/approve-pinjaman', {
      rowid: id,
      return_url: window.location.href
    });
  }

  function openRejectModal(id) {
    ModalHelper.open('<?= base_url() ?>bendahara/pinjaman/cancel-pinjaman', {
      rowid: id,
      return_url: window.location.href
    });
  }

  function openDetailModal(id) {
    ModalHelper.open('<?= base_url() ?>bendahara/pinjaman/detail-pinjaman', {
      rowid: id
    });
  }

  $(document).ready(function() {
    $('#dt_list').DataTable({
      ajax: {
        url: "<?= base_url() ?>bendahara/pinjaman/data_pinjaman",
        type: "POST"
      },
      autoWidth: false,
      serverSide: true,
      // Unified DOM Layout
      dom: '<"flex justify-between items-center gap-4 mb-4"lf><"rounded-xl border border-slate-100"t><"flex justify-between items-center gap-4 mt-4"ip>',
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
        targets: [0, 6],
        searchable: false
      }],
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
            return `<div class="flex items-center gap-3">
                      <div class="w-9 h-9 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold text-xs ring-2 ring-white shadow-sm overflow-hidden">
                        <i data-lucide="user" class="w-4 h-4"></i>
                      </div>
                      <div>
                        <p class="font-bold text-slate-900 text-sm leading-tight">${row.nama_peminjam}</p>
                        <p class="text-[11px] font-medium text-slate-400">@${row.username_peminjam}</p>
                      </div>
                    </div>`;
          }
        },
        {
          data: "tipe_permohonan",
          render: function(d) {
            return `<span class="px-2.5 py-1 bg-theme-light text-theme-main rounded-lg text-[10px] font-black uppercase tracking-wider border border-indigo-100/50">${d}</span>`;
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
            return `<span class="text-xs font-bold text-slate-600">${d} Bulan</span>`;
          }
        },
        {
          data: null,
          render: function(data, type, row) {
            return `<div class="flex flex-col gap-1 w-fit">
                      <a href="<?= base_url() ?>uploads/user/${row.username_peminjam}/pinjaman/${row.form_bukti}" target="_blank" class="flex items-center gap-1.5 px-2 py-1 rounded-lg bg-slate-50 text-[10px] font-bold text-slate-500 hover:text-theme-main transition-all border border-slate-100">
                        <i data-lucide="file-text" class="w-3 h-3"></i> Form
                      </a>
                      <a href="<?= base_url() ?>uploads/user/${row.username_peminjam}/pinjaman/${row.slip_gaji}" target="_blank" class="flex items-center gap-1.5 px-2 py-1 rounded-lg bg-slate-50 text-[10px] font-bold text-slate-500 hover:text-emerald-600 transition-all border border-slate-100">
                        <i data-lucide="receipt" class="w-3 h-3"></i> Slip
                      </a>
                    </div>`;
          }
        },
        {
          data: null,
          className: "text-right",
          render: function(data, type, row) {
            return `<div class="flex justify-end gap-1.5">
                      <button onclick="openRejectModal('${row.idpinjaman}')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all flex items-center justify-center border border-red-100 shadow-sm" title="Tolak">
                        <i data-lucide="x" class="w-4 h-4"></i>
                      </button>
                      <button onclick="openApproveModal('${row.idpinjaman}')" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all flex items-center justify-center border border-emerald-100 shadow-sm" title="Setujui">
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
  });
</script>
<?= $this->endSection() ?>