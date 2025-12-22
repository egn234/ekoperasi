function numberFormat(number, decimals = 0, decimalSeparator = ',', thousandSeparator = '.') {
  number = parseFloat(number).toFixed(decimals);
  number = number.replace('.', decimalSeparator);
  var parts = number.split(decimalSeparator);
  parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator);
  return parts.join(decimalSeparator);
}

$(document).ready(function () {
  $('#dt_list').DataTable({
    ajax: {
      url: `${BASE_URL}admin/pinjaman/data_pinjaman`,
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
        data: "username_peminjam"
      },
      {
        title: "Nama Lengkap",
        data: "nama_peminjam"
      },
      {
        title: "Nominal",
        "render": function (data, type, row, meta) {
          return 'Rp ' + numberFormat(row.nominal, 2);
        }
      },
      {
        title: "Asuransi",
        "render": function (data, type, row, meta) {
          return '<a href="#" class="btn btn-sm btn-outline-info view-asuransi" data-id="' + row.idpinjaman + '">Lihat Asuransi</a>';
        }
      },
      {
        title: "Status",
        "render": function (data, type, row, meta) {
          let output;

          if (row.status == 0) {
            output = 'Ditolak';
          } else if (row.status == 1) {
            output = 'Upload Kelengkapan Form';
          } else if (row.status == 2) {
            output = 'Menunggu Verifikasi';
          } else if (row.status == 3) {
            output = 'Menunggu Approval Sekretariat';
          } else if (row.status == 4) {
            output = 'Sedang Berlangsung';
          } else if (row.status == 5) {
            output = 'Lunas';
          } else if (row.status == 6) {
            output = 'Pelunasan diproses admin';
          } else if (row.status == 7) {
            output = 'Pelunasan diproses bendahara';
          }

          return output;
        }
      },
      {
        title: "Tanggal Pengajuan",
        data: "date_created"
      },
      {
        title: "Aksi",
        render: function (data, type, row, full) {
          let head = '<div class="btn-group d-flex justify-content-center">';
          let btn_a = '<a class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailPinjaman" data-id="' + row.idpinjaman + '"><i class="fa fa-file-alt"></i> Detail</a>';
          let btn_b = '';
          let tail = '</div>';
          if (row.status == 4) {
            btn_b = '<a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#lunasiPartial" data-id="' + row.idpinjaman + '"><i class="fa fa-file-alt"></i> Lunasi Sebagian</a>';
          }

          return head + btn_a + btn_b + tail;
        }
      }
    ]
  });

  $('#dt_list_filter').DataTable({
    ajax: {
      url: `${BASE_URL}admin/pinjaman/data_pinjaman_filter`,
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
        data: "username_peminjam"
      },
      {
        title: "Nama Lengkap",
        data: "nama_peminjam"
      },
      {
        title: "Tipe",
        data: "tipe_permohonan"
      },
      {
        title: "Nominal",
        render: function (data, type, row, meta) {
          return 'Rp ' + numberFormat(row.nominal, 2);
        }
      },
      {
        title: "Asuransi",
        render: function (data, type, row, meta) {
          return '<a href="#" class="btn btn-sm btn-outline-info view-asuransi" data-id="' + row.idpinjaman + '">Lihat Asuransi</a>';
        }
      },
      {
        title: "Tanggal Pengajuan",
        data: "date_created"
      },
      {
        title: "Lama Angsuran (bulan)",
        data: "angsuran_bulanan"
      },
      {
        title: "Form Persetujuan",
        render: function (data, type, row, full) {
          let link_a = '<a href="' + BASE_URL + 'uploads/user/' + row.username_peminjam + '/pinjaman/' + row.form_bukti + '" target="_blank"><i class="fa fa-download"></i> Form SDM</a><br>';
          let link_b = '<a href="' + BASE_URL + 'uploads/user/' + row.username_peminjam + '/pinjaman/' + row.slip_gaji + '" target="_blank"><i class="fa fa-download"></i> Slip Gaji</a><br>';
          let link_c = '';

          if (row.status_pegawai === 'kontrak') {
            link_c = '<a href="' + BASE_URL + 'uploads/user/' + row.username_peminjam + '/pinjaman/' + row.form_kontrak + '" target="_blank"><i class="fa fa-download"></i> Bukti Kontrak</a>';
          }

          return link_a + link_b + link_c;
        }
      },
      {
        title: "Aksi",
        render: function (data, type, row, full) {
          let head = '<div class="btn-group d-flex justify-content-center">'
          let tolak_btn = '<a class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#tolakPinjaman" data-id="' + row.idpinjaman + '"><i class="fa fa-file-alt"></i> Tolak</a>';
          let terima_btn = '<a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approvePinjaman" data-id="' + row.idpinjaman + '"><i class="fa fa-file-alt"></i> Setujui</a>';
          let tail = '</div>';

          return head + tolak_btn + terima_btn + tail;
        }
      }
    ]
  });

  $('#approvePinjaman').on('show.bs.modal', function (e) {
    var rowid = $(e.relatedTarget).data('id');
    $.ajax({
      type: 'POST',
      url: `${BASE_URL}admin/pinjaman/approve-pinjaman`,
      data: 'rowid=' + rowid,
      success: function (data) {
        $('#fetched-data-approvePinjaman').html(data); //menampilkan data ke dalam modal
      }
    });
  });

  $('#tolakPinjaman').on('show.bs.modal', function (e) {
    var rowid = $(e.relatedTarget).data('id');
    $.ajax({
      type: 'POST',
      url: `${BASE_URL}admin/pinjaman/cancel-pinjaman`,
      data: 'rowid=' + rowid,
      success: function (data) {
        $('#fetched-data-tolakPinjaman').html(data); //menampilkan data ke dalam modal
      }
    });
  });

  $('#detailPinjaman').on('show.bs.modal', function (e) {
    var rowid = $(e.relatedTarget).data('id');
    $.ajax({
      type: 'POST',
      url: `${BASE_URL}admin/pinjaman/detail-pinjaman`,
      data: 'rowid=' + rowid,
      success: function (data) {
        $('#fetched-data-detailPinjaman').html(data); //menampilkan data ke dalam modal
      }
    });
  });

  $('#lunasiPartial').on('show.bs.modal', function (e) {
    var rowid = $(e.relatedTarget).data('id');
    $.ajax({
      type: 'POST',
      url: `${BASE_URL}admin/pinjaman/lunasi-partial`,
      data: 'rowid=' + rowid,
      success: function (data) {
        $('#fetched-data-lunasiPartial').html(data); //menampilkan data ke dalam modal
      }
    });
  });

  // Handler for viewing asuransi
  $(document).on('click', '.view-asuransi', function (e) {
    e.preventDefault();
    var idpinjaman = $(this).data('id');

    $.ajax({
      type: 'GET',
      url: `${BASE_URL}admin/pinjaman/get_asuransi/${idpinjaman}`,
      success: function (response) {
        if (response.status === 'success') {
          let content = '';
          if (response.data.length > 0) {
            content = '<div class="table-responsive"><table class="table table-sm">';
            content += '<thead><tr><th>Periode (Bulan)</th><th>Nilai Asuransi</th></tr></thead>';
            content += '<tbody>';

            response.data.forEach(function (item) {
              content += `<tr>
                                <td>${item.bulan_kumulatif} bulan</td>
                                <td>Rp ${numberFormat(item.nilai_asuransi, 2)}</td>
                              </tr>`;
            }); content += '</tbody>';
            content += `<tfoot><tr><th>Total</th><th>Rp ${numberFormat(response.total_asuransi, 2)}</th></tr></tfoot>`;
            content += '</table></div>';
            content += '<div class="mt-2"><a href="https://bit.ly/polis-koperasi-giat" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-file-contract"></i> Lihat Polis Asuransi</a></div>';
          } else {
            content = '<div class="alert alert-info">Tidak ada data asuransi untuk pinjaman ini.</div>';
          }

          $('#asuransi-content').html(content);
          $('#asuransiModal').modal('show');
        } else {
          alert('Error: ' + response.message);
        }
      },
      error: function () {
        alert('Gagal mengambil data asuransi');
      }
    });
  });
});

document.addEventListener('DOMContentLoaded', function () {
  var myModal = document.getElementById('approvePinjaman');
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