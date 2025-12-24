<?= $this->extend('layout/admin') ?>

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

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script type="text/javascript">
  // Open Modal Helpers
  function openApproveModal(id) {
    ModalHelper.open('<?= base_url() ?>admin/pinjaman/approve-pinjaman', {
      rowid: id,
      return_url: window.location.href
    });
  }

  function openRejectModal(id) {
    ModalHelper.open('<?= base_url() ?>admin/pinjaman/cancel-pinjaman', {
      rowid: id,
      return_url: window.location.href
    });
  }

  function openDetailModal(id) {
    ModalHelper.open('<?= base_url() ?>admin/pinjaman/detail-pinjaman', {
      rowid: id
    });
  }

  // Global function for partial payment calculation (called from modal)
  window.calculatePartial = function(element, nominal, id) {
    const value = parseFloat(element.value) || 0;
    const total = value * parseFloat(nominal);
    const displayTotal = document.getElementById('perkalian_' + id);
    const displayLabel = document.getElementById('label_kalkulasi_' + id);

    // Format to IDR
    const formatIDR = (val) => new Intl.NumberFormat('id-ID', {
      maximumFractionDigits: 0
    }).format(val);

    if (displayTotal) {
      displayTotal.textContent = formatIDR(total);
    }

    if (displayLabel) {
      if (value > 0) {
        displayLabel.textContent = value + ' x Rp ' + formatIDR(nominal);
        displayLabel.classList.remove('opacity-80');
        displayLabel.classList.add('text-white');
      } else {
        displayLabel.textContent = 'Masukkan jumlah bulan';
        displayLabel.classList.remove('text-white');
        displayLabel.classList.add('opacity-80');
      }
    }
  };

  function openPartialModal(id) {
    ModalHelper.open('<?= base_url() ?>admin/pinjaman/lunasi-partial', {
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
            return `<span class="px-2.5 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-black uppercase tracking-wider border border-indigo-100/50">${d}</span>`;
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
                      <a href="<?= base_url() ?>uploads/user/${row.username_peminjam}/pinjaman/${row.form_bukti}" target="_blank" class="flex items-center gap-1.5 px-2 py-1 rounded-lg bg-slate-50 text-[10px] font-bold text-slate-500 hover:text-blue-600 transition-all border border-slate-100">
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

    // Table 2: All Data
    $('#dt_list').DataTable({
      ajax: {
        url: "<?= base_url() ?>admin/pinjaman/data_pinjaman",
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
        targets: [0, 7],
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
                        ${row.nama_peminjam.substring(0,2).toUpperCase()}
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
            return `<span class="text-xs font-bold text-slate-600">${d}</span>`;
          }
        },
        {
          data: "nominal",
          render: function(d) {
            return `<span class="font-bold text-slate-700">Rp ${parseInt(d).toLocaleString('id-ID')}</span>`;
          }
        },
        {
          data: "angsuran_bulanan",
          render: function(d, type, row) {
            return `<div class="flex flex-col">
                      <span class="text-xs font-bold text-slate-700">${d} Bulan</span>
                      <span class="text-[10px] font-medium text-slate-400">Sudah bayar: ${row.sisa_cicilan}x</span>
                    </div>`;
          }
        },
        {
          data: "status",
          render: function(d) {
            const statusMap = {
              '0': `<span class="px-2.5 py-1 bg-red-50 text-red-600 rounded-lg text-[10px] font-black uppercase tracking-wider border border-red-100/50">Ditolak</span>`,
              '1': `<span class="px-2.5 py-1 bg-slate-50 text-slate-600 rounded-lg text-[10px] font-black uppercase tracking-wider border border-slate-100/50">Upload Dokumen</span>`,
              '2': `<span class="px-2.5 py-1 bg-amber-50 text-amber-600 rounded-lg text-[10px] font-black uppercase tracking-wider border border-amber-100/50">Verifikasi Admin</span>`,
              '3': `<span class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-black uppercase tracking-wider border border-blue-100/50">Approval Bendahara</span>`,
              '4': `<span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-black uppercase tracking-wider border border-emerald-100/50">Disetujui</span>`,
              '5': `<span class="px-2.5 py-1 bg-slate-900 text-white rounded-lg text-[10px] font-black uppercase tracking-wider border border-slate-800 shadow-sm">Lunas</span>`,
              '6': `<span class="px-2.5 py-1 bg-purple-50 text-purple-600 rounded-lg text-[10px] font-black uppercase tracking-wider border border-purple-100/50 animate-pulse">Konfirmasi Pelunasan</span>`
            };
            return statusMap[d] || d;
          }
        },
        {
          data: null,
          render: function(data, type, row) {
            let admin = row.nama_admin ? `<div class="flex items-center gap-1">
                      <div class="w-1.5 h-1.5 rounded-full bg-blue-400"></div><span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter" title="Admin">@${row.username_admin || 'admin'}</span>
                    </div>` : '';
            let bendahara = row.nama_bendahara ? `<div class="flex items-center gap-1">
                      <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div><span class="text-[10px] font-bold text-slate-500 uppercase tracking-tighter" title="Bendahara">@${row.username_bendahara || 'bendahara'}</span>
                    </div>` : '';
            return `<div class="flex flex-col gap-0.5">${admin} ${bendahara}</div>`;
          }
        },
        {
          data: null,
          className: "text-right",
          render: function(data, type, row) {
            let btns = `<div class="flex justify-end gap-1.5">`;

            // Lunasi Sebagian (Only for status 4 - Approved/Active)
            if (row.status == "4") {
              btns += `<button onclick="openPartialModal('${row.idpinjaman}')" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all flex items-center justify-center border border-emerald-100 shadow-sm" title="Lunasi Sebagian">
                        <i data-lucide="credit-card" class="w-4 h-4"></i>
                      </button>`;
            }

            btns += `<button onclick="openDetailModal('${row.idpinjaman}')" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-blue-600 hover:text-white transition-all flex items-center justify-center border border-slate-100 shadow-sm" title="Detail">
                      <i data-lucide="eye" class="w-4 h-4"></i>
                    </button>`;

            btns += `</div>`;
            return btns;
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