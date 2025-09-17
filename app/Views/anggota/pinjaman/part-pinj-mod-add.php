<div class="modal-header">
  <h5 class="modal-title" id="addPengajuanLabel">Pengajuan Pinjaman</h5>
  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
  <form action="<?= url_to('anggota/pinjaman/add-req') ?>" id="formSheet" method="post">
    <div class="mb-3">
      <label class="form-label" for="m_pembayaran">Tipe Pengajuan</label>
      <select class="form-select" id="m_pembayaran" name="tipe_permohonan" required>
        <option value="" <?=(session()->getFlashdata('tipe_permohonan'))?'':'selected'?> disabled>Pilih Opsi...</option>
        <option value="pinjaman" <?=(session()->getFlashdata('tipe_permohonan') == 'pinjaman')?'selected':''?> >Pinjaman</option>
        <option value="pengadaan barang" <?=(session()->getFlashdata('tipe_permohonan') == 'pengadaan barang')?'selected':''?> >Pengadaan Barang</option>
        <option value="lain-lain" <?=(session()->getFlashdata('tipe_permohonan') == 'lain-lain')?'selected':''?> >Lainnya</option>
      </select>
      <div class="invalid-feedback">
        Pilih Terlebih dahulu
      </div>
    </div>
    <div class="mb-3">
      <label class="form-label" for="deskripsi">Keterangan</label>
      <input type="text" class="form-control" id="deskripsi" name="deskripsi" value="<?=session()->getFlashdata('deskripsi')?>">
    </div>
    <div class="mb-3">
      <label class="form-label" for="nominal">Nominal (Rp.)</label>
      <input type="number" class="form-control" id="nominal" name="nominal" value="<?=session()->getFlashdata('nominal')?>" required>
      <small id="preview_nominal" class="form-text text-muted"></small>
      <div class="invalid-feedback">
        Harus Diisi
      </div>
    </div>
    <div class="row mb-3">
      <label class="form-label" for="angsuran_bulanan">Lama Cicilan Pelunasan</label>
      <div class="col-6">
        <input 
          type="number"
          class="form-control"
          id="angsuran_bulanan"
          name="angsuran_bulanan"
          value="<?=session()->getFlashdata('angsuran_bulanan')?>"
          min="1"
          max="24"
          required
        >
        <div class="invalid-feedback">
          Harus Diisi
        </div>
      </div>
      <div class="col-6">
        <select class="form-select" id="bulanan_tahunan" name="satuan_waktu" required>
          <option value="1" <?=(session()->getFlashdata('satuan_waktu') == '1')?'selected':''?> >Bulan</option>
          <option value="2" <?=(session()->getFlashdata('satuan_waktu') == '2')?'selected':''?> >Tahun</option>
        </select>
        <div class="invalid-feedback">
          Pilih Terlebih dahulu
        </div>
      </div>
    </div>
    <span class="text-xs">
      <i>
        *Tanggal pencairan, penarikan dan pinjaman dilakukan setiap hari jum'at </br>
        <?=
          ($duser->status_pegawai == 'tetap')
          ?
          '*Maksimal permintaan pinjaman dalam 1 sesi hanya sampai dengan Rp50.000.000 </br>
          *Maksimal cicilan yang bisa diajukan sampai dengan 24 bulan'
          :'*Maksimal permintaan pinjaman dalam 1 sesi hanya sampai dengan Rp15.000.000 </br>
          *Maksimal cicilan yang bisa diajukan sampai dengan 12 bulan'
        ?>
      </i>
    </span>
  </form>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
  <button type="submit" form="formSheet" class="btn btn-success">Buat Pengajuan</button>
</div>