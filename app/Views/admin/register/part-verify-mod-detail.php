<div class="modal-header">
  <h5 class="modal-title" id="myModalLabel">Verifikasi Anggota Baru</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
  <div class="mb-3">
    <table width="100%">
      <tr>
        <td>Nama</td>
        <td>:</td>
        <td><?=$a->nama_lengkap?></td>
      </tr>
      <tr>
        <td>NIK</td>
        <td>:</td>
        <td><?=$a->nik?></td>
      </tr>
      <tr>
        <td>NIP</td>
        <td>:</td>
        <td><?=$a->nip ? $a->nip : '-'?></td>
      </tr>
      <tr>
        <td>Tempat, Tanggal Lahir</td>
        <td>:</td>
        <td><?=$a->tempat_lahir?>, <?=$a->tanggal_lahir?></td>
      </tr>
      <tr>
        <td>Alamat</td>
        <td>:</td>
        <td><?=$a->alamat?></td>
      </tr>
      <tr>
        <td>Instansi</td>
        <td>:</td>
        <td><?=$a->instansi?></td>
      </tr>
      <tr>
        <td>Unit Kerja</td>
        <td>:</td>
        <td><?=$a->unit_kerja?></td>
      </tr>
      <tr>
        <td>Status Pegawai</td>
        <td>:</td>
        <td><?=$a->status_pegawai == 'kontrak' ? 'Kontrak' : 'Tetap'?></td>
      </tr>
      <tr>
        <td>No. Telepon</td>
        <td>:</td>
        <td><?=$a->nomor_telepon?></td>
      </tr>
      <tr>
        <td>Email</td>
        <td>:</td>
        <td><?=$a->email?></td>
      </tr>
      <tr>
        <td>KTP</td>
        <td>:</td>
        <td>
          <a href="<?=base_url()?>/uploads/user/<?=$a->username?>/ktp/<?=$a->ktp_file?>" target="_blank">
            <i class="fa fa-download"></i> <?= $a->ktp_file ?>
          </a>
        </td>
      </tr>
      
    </table>
  </div>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
  <a href="<?=url_to('admin_verify_user', $a->iduser)?>" class="btn btn-success">Verifikasi Anggota</a>
</div>
