$(function() {
  var numberFormat = function(number, decimals = 2, decimalSeparator = ',', thousandSeparator = '.') {
    number = parseFloat(number).toFixed(decimals);
    number = number.replace('.', decimalSeparator);
    var parts = number.split(decimalSeparator);
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandSeparator);
    return parts.join(decimalSeparator);
  };

  // fungsi untuk tabel utama
  $('#dataTable').DataTable({
    ajax: {
      url: BASE_URL + "anggota/pinjaman/data_pinjaman",
      type: "POST",
      data: function (d) {
        d.length = d.length || 10; // Use the default if not provided
      }
    },
    autoWidth: false,
    scrollX: true,
    serverSide: true,
    searching: false,
    columnDefs: [{
      orderable: false,
      targets: "_all",
      defaultContent: "-",
    }],
    columns: [
      {
        title: "Tanggal Pengajuan",
        data: "date_created"
      },
      {
        title: "Tipe",
        data: "tipe_permohonan"
      },
      {
        title: "Nominal",
        render: function(data, type, row, meta) {
          return 'Rp '+numberFormat(row.nominal, 2);
        }
      },
      {
        title: "Status",
        render: function(data, type, row, meta) {
          let output;
          
          if(row.status == 0){
            output = 'Ditolak';
          }else if(row.status == 1){
            output = 'Upload Kelengkapan Form';
          }else if(row.status == 2){
            output = 'Menunggu Verifikasi';
          }else if(row.status == 3){
            output = 'Menunggu Approval Sekretariat';
          }else if(row.status == 4){
            output = 'Sedang Berlangsung';
          }else if(row.status == 5){
            output = 'Lunas';
          }else if(row.status == 6){
            output = 'Pelunasan diproses admin';
          }else if(row.status == 7){
            output = 'Pelunasan diproses bendahara';
          }

          return output;
        }
      },
      {
        title: "Lama Angsuran (bulan)",
        data: "angsuran_bulanan"
      },
      {
        title: "Aksi",
        render: function(data, type, row, full) {
          let head = '<div class="btn-group d-flex justify-content-center">';
          let button_a = '';
          let button_b = '';
          let button_c = '';
          let button_d = '';
          let tail = '</div>';

          if(row.status >= 4){
            button_a = '<a href="'+BASE_URL+'anggota/pinjaman/detail/'+row.idpinjaman+'" class="btn btn-info btn-sm"><i class="fa fa-file-alt"></i> Detail</a>';
          }

          if(row.status == 1){
            button_b = '<a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#uploadBT" data-id="'+row.idpinjaman+'"><i class="fa fa-upload"></i> Upload form</a>';
            button_c = '<a href="'+BASE_URL+'anggota/pinjaman/generate-form/'+row.idpinjaman+'" class="btn btn-info btn-sm" target="_blank"><i class="fa fa-file-alt"></i> Print form</a>';
          }

          if(row.status == 4){
            button_d = '<a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#lunasiPinjaman" data-id="'+row.idpinjaman+'"> Lunasi Pinjaman</a>';
          }

          return head + button_a + button_b + button_c + button_d + tail;
        }
      }
    ]
  });

  const riwayatDT = $('#riwayat_penolakan').DataTable({
    ajax: {
      url: BASE_URL + "anggota/pinjaman/riwayat_penolakan",
      type: "POST",
      data: function (d) {
        d.length = d.length || 10; // Use the default if not provided
      }
    },
    autoWidth: false,
    scrollX: true,
    serverSide: true,
    searching: false,
    columnDefs: [{
      orderable: false,
      targets: "_all",
      defaultContent: "-",
    }],
    columns: [
      {
        title: "Tanggal Pengajuan",
        data: "date_created"
      },
      {
        title: "Tipe",
        data: "tipe_permohonan"
      },
      {
        title: "Nominal",
        render: function(data, type, row, meta) {
          return 'Rp '+numberFormat(row.nominal, 2);
        }
      },
      {
        title: "Status",
        render: function(data, type, row, meta) {
          let output;
          
          if(row.status == 0){
            output = 'Ditolak';
          }else if(row.status == 1){
            output = 'Upload Kelengkapan Form';
          }else if(row.status == 2){
            output = 'Menunggu Verifikasi';
          }else if(row.status == 3){
            output = 'Menunggu Approval Sekretariat';
          }else if(row.status == 4){
            output = 'Sedang Berlangsung';
          }else if(row.status == 5){
            output = 'Lunas';
          }else if(row.status == 6){
            output = 'Pelunasan diproses admin';
          }else if(row.status == 7){
            output = 'Pelunasan diproses bendahara';
          }

          return output;
        }
      },
      {
        title: "Lama Angsuran (bulan)",
        data: "angsuran_bulanan"
      },
      {
        title: "Aksi",
        render: (data, type, row) => {
          let head = '<div class="btn-group d-flex justify-content-center">';
          let tail = '</div>';
          let button_d = '<a class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#detailTolak" data-id="'+row.idpinjaman+'"> Detail</a>';
          return head + button_d + tail;
        }
      }
    ]
  });

  $('#wrapper-riwayat').on('shown.bs.collapse', function () {
    if (riwayatDT) {
      riwayatDT.columns.adjust().draw();
    }
  });

  $('#uploadBT').on('show.bs.modal', function(e) {
    var rowid = $(e.relatedTarget).data('id');
    $.ajax({
      type: 'POST',
      url: BASE_URL + 'anggota/pinjaman/up_form',
      data: 'rowid=' + rowid,
      success: function(data) {
        $('#fetched-data-uploadBT').html(data); //menampilkan data ke dalam modal
      }
    });
  });

  $('#lunasiPinjaman').on('show.bs.modal', function(e) {
    var rowid = $(e.relatedTarget).data('id');
    $.ajax({
      type: 'POST',
      url: BASE_URL + 'anggota/pinjaman/lunasi_pinjaman',
      data: 'rowid=' + rowid,
      success: function(data) {
        $('#fetched-data-lunasiPinjaman').html(data); //menampilkan data ke dalam modal
      }
    });
  });

  $('#detailTolak').on('show.bs.modal', function(e) {
    var rowid = $(e.relatedTarget).data('id');
    $.ajax({
      type: 'POST',
      url: BASE_URL + 'anggota/pinjaman/detail_tolak',
      data: 'rowid=' + rowid,
      success: function(data) {
        $('#fetched-data-detailTolak').html(data); //menampilkan data ke dalam modal
      }
    });
  });
}); 

