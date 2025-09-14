function numberFormat(number, decimals = 0, decimalSeparator = ',', thousandSeparator = '.') {
  number = parseFloat(number).toFixed(decimals);
  number = number.replace('.', decimalSeparator);
  var parts = number.split(decimalSeparator);
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator);
  return parts.join(decimalSeparator);
}

$(document).ready(function() {
  $('#dt_list').DataTable({
    ajax: {
      url: `${BASE_URL}admin/deposit/data_transaksi`,
      type: "POST",
      data: function (d) {
        d.length = d.length || 10; // Use the default if not provided
      }
    },
    autoWidth: false,
    scrollX: true,
    serverSide: true,
    searching: true,
    columnDefs: [{
      orderable: false,
      targets: "_all",
      defaultContent: "-",
    }],
    columns: [
      {
        title: "Username",
        data: "username"
      },
      {
        title: "Nama Lengkap",
        data: "nama_lengkap"
      },
      {
        title: "Jenis Pengajuan",
        data: "jenis_pengajuan"
      },
      {
        title: "Jenis Simpanan",
        data: "jenis_deposit"
      },
      {
        title: "Nominal",
        "render": function(data, type, row, meta) {
          let hasil = row.cash_in - row.cash_out;
          let html;
          if (hasil < 0) {
            html = '<span class="badge badge-soft-danger">- ';
          }else{
            html = '<span class="badge badge-soft-success">+ '
          }
          return html + numberFormat(Math.abs(hasil), 2);
        }
      },
      {
        title: "Status",
        data: "status"
      },
      {
        title: "Tanggal Pengajuan",
        data: "date_created"
      },
      {
        title: "Aksi",
        render: function(data, type, row, full) {
          return `
            <div class="btn-group d-flex justify-content-center">
              <a class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailMutasi" data-id="${row.iddeposit}">
                <i class="fa fa-file-alt"></i> detail
              </a>
              <a class="btn btn-info btn-sm" href="${BASE_URL}admin/deposit/edit/${row.iddeposit}">
                <i class="fa fa-edit"></i> Ubah
              </a>
            </div>
          `;
        }
      }
    ]
  });

  $('#dt_list_filter').DataTable({
    ajax: {
      url: `${BASE_URL}admin/deposit/data_transaksi_filter`,
      type: "POST",
      data: function (d) {
        d.length = d.length || 10; // Use the default if not provided
      }
    },
    autoWidth: false,
    scrollX: true,
    searching: true,
    serverSide: true,
    columnDefs: [{
      orderable: false,
      targets: "_all",
      defaultContent: "-",
    }],
    columns: [
      {
        title: "Username",
        data: "username"
      },
      {
        title: "Nama Lengkap",
        data: "nama_lengkap"
      },
      {
        title: "Jenis Pengajuan",
        data: "jenis_pengajuan"
      },
      {
        title: "Jenis Simpanan",
        data: "jenis_deposit"
      },
      {
        title: "Nominal",
        "render": function(data, type, row, meta) {
          let hasil = row.cash_in - row.cash_out;
          let html;
          if (hasil < 0) {
            html = '<span class="badge badge-soft-danger">- ';
          }else{
            html = '<span class="badge badge-soft-success">+ '
          }
          return html + numberFormat(Math.abs(hasil), 2);
        }
      },
      {
        title: "Status",
        data: "status"
      },
      {
        title: "Tanggal Pengajuan",
        data: "date_created"
      },
      {
        title: "Aksi",
        render: function(data, type, row, full) {
          let head = '<div class="btn-group d-flex justify-content-center">';
          let button1 = '<a class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailMutasi" data-id="'+row.iddeposit+'"><i class="fa fa-file-alt"></i> detail</a>';
          let button2 = '<a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#tolakMnsk" data-id="'+row.iddeposit+'"><i class="fa fa-file-alt"></i> Tolak</a>';
          let button3 = '<a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approveMnsk" data-id="'+row.iddeposit+'"><i class="fa fa-file-alt"></i> Setujui</a>';
          let tail = '</div>';

          return head + button1 + button2 + button3 + tail;
        }
      }
    ]
  });

  $('#detailMutasi').on('show.bs.modal', function(e) {
    var rowid = $(e.relatedTarget).data('id');
    $.ajax({
      type: 'POST',
      url: `${BASE_URL}admin/deposit/detail_mutasi`,
      data: 'rowid=' + rowid,
      success: function(data) {
        $('#fetched-data').html(data); //menampilkan data ke dalam modal
      }
    });
  });
  
  $('#approveMnsk').on('show.bs.modal', function(e) {
    var rowid = $(e.relatedTarget).data('id');
    $.ajax({
      type: 'POST',
      url: `${BASE_URL}admin/deposit/approve-mnsk`,
      data: 'rowid=' + rowid,
      success: function(data) {
        $('#fetched-data-approve').html(data);
      }
    });
  });

  $('#tolakMnsk').on('show.bs.modal', function(e) {
    var rowid = $(e.relatedTarget).data('id');
    $.ajax({
      type: 'POST',
      url: `${BASE_URL}admin/deposit/cancel-mnsk`,
      data: 'rowid=' + rowid,
      success: function(data) {
        $('#fetched-data-cancel').html(data);
      }
    });
  });
});

document.addEventListener('DOMContentLoaded', function () {
  var myModal = document.getElementById('approveMnsk');
  myModal.addEventListener('shown.bs.modal', function () {
    const nominalInput = document.getElementById('nominal_uang');
    const previewNominal = document.getElementById('preview_nominal');

    if (nominalInput) {
      // fungsi untuk update preview
      function updatePreview() {
        // Ambil angka aja (buang selain digit)
        const raw = nominalInput.value.replace(/[^\d]/g, "");

        if (raw) {
          // parse ke integer
          const num = parseInt(raw, 10);

          // format ribuan tanpa desimal
          const formatted = new Intl.NumberFormat("id-ID", {
            maximumFractionDigits: 0
          }).format(num);

          previewNominal.textContent = `Nominal Rp. ${formatted}`;
        } else {
          previewNominal.textContent = "";
        }
      }


      // update setiap user ketik
      nominalInput.addEventListener('input', updatePreview);

      // 🔥 inisialisasi awal pakai value dari DB
      updatePreview();
    }
  });
});