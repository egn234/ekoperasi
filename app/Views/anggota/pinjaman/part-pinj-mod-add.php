<div class="text-center mb-6">
  <div class="w-16 h-16 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center mx-auto mb-4">
    <i data-lucide="wallet" class="w-8 h-8"></i>
  </div>
  <h3 class="text-xl font-black text-slate-900">Pengajuan Pinjaman</h3>
  <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Isi detail pinjaman yang Anda butuhkan</p>
</div>

<form action="<?= url_to('anggota/pinjaman/add-req') ?>" id="formSheet" method="post">
  <div class="space-y-4">
    <!-- Tipe Pengajuan -->
    <div>
      <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Tipe Pengajuan</label>
      <select class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block p-3 font-bold transition-all hover:bg-slate-100" id="m_pembayaran" name="tipe_permohonan" required>
        <option value="" <?= (session()->getFlashdata('tipe_permohonan')) ? '' : 'selected' ?> disabled>Pilih Opsi...</option>
        <option value="pinjaman" <?= (session()->getFlashdata('tipe_permohonan') == 'pinjaman') ? 'selected' : '' ?>>Pinjaman Tunai</option>
        <option value="pengadaan barang" <?= (session()->getFlashdata('tipe_permohonan') == 'pengadaan barang') ? 'selected' : '' ?>>Pengadaan Barang</option>
        <option value="lain-lain" <?= (session()->getFlashdata('tipe_permohonan') == 'lain-lain') ? 'selected' : '' ?>>Lainnya</option>
      </select>
    </div>

    <!-- Deskripsi -->
    <div>
      <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Keterangan</label>
      <input type="text" id="deskripsi" name="deskripsi" value="<?= session()->getFlashdata('deskripsi') ?>" class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 font-medium placeholder-slate-400" placeholder="Tujuan penggunaan dana...">
    </div>

    <!-- Nominal -->
    <div>
      <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Nominal (Rp)</label>
      <input type="number" id="nominal" name="nominal" value="<?= session()->getFlashdata('nominal') ?>" class="bg-slate-50 border border-slate-200 text-slate-900 text-lg rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 font-black placeholder-slate-300" placeholder="0" required>
      <p id="preview_nominal" class="text-xs font-bold text-indigo-600 mt-2 text-right min-h-[1rem]"></p>
    </div>

    <!-- Info Provisi -->
    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 flex gap-3 info-provisi">
      <i data-lucide="info" class="w-5 h-5 text-blue-600 shrink-0 mt-0.5"></i>
      <div>
        <h6 class="text-xs font-black text-blue-700 uppercase tracking-wide mb-1">Potongan Provisi</h6>
        <p class="text-sm font-medium text-blue-600">
          <span id="label_provisi_percent"><?= $provisi->nilai ?>%</span> dari nominal pinjaman.
        </p>
        <p class="text-[10px] text-blue-400 mt-1 font-bold">Provisi akan dipotong dari nominal yang dicairkan.</p>
      </div>
    </div>

    <!-- Tenor -->
    <div>
      <label class="block text-xs font-black text-slate-500 uppercase tracking-wider mb-2">Lama Angsuran</label>
      <div class="flex gap-3">
        <div class="w-1/2">
          <input type="number" id="angsuran_bulanan" name="angsuran_bulanan" value="<?= session()->getFlashdata('angsuran_bulanan') ?>" min="1" max="24" class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 font-bold text-center" placeholder="12" required>
        </div>
        <div class="w-1/2">
          <select id="bulanan_tahunan" name="satuan_waktu" class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full p-3 font-bold" required>
            <option value="1" <?= (session()->getFlashdata('satuan_waktu') == '1') ? 'selected' : '' ?>>Bulan</option>
            <option value="2" <?= (session()->getFlashdata('satuan_waktu') == '2') ? 'selected' : '' ?>>Tahun</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Info Asuransi -->
    <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 flex gap-3 info-asuransi">
      <i data-lucide="shield" class="w-5 h-5 text-amber-600 shrink-0 mt-0.5"></i>
      <div>
        <h6 class="text-xs font-black text-amber-700 uppercase tracking-wide mb-1">Asuransi Pinjaman</h6>
        <div class="text-xs font-medium text-amber-800 space-y-1">
          <p style="display:none;" id="param_kelipatan"><?= (int)$bulan_kelipatan_asuransi->nilai ?></p>
          <p style="display:none;" id="param_nominal_asuransi"><?= $nominal_asuransi->nilai ?></p>

          <p>Kelipatan per <strong><?= (int)$bulan_kelipatan_asuransi->nilai ?> bulan</strong>.</p>
          <p>Biaya: <strong>Rp <?= number_format($nominal_asuransi->nilai, 0, ',', '.') ?></strong> per kelipatan.</p>
        </div>
        <div id="preview_asuransi" class="mt-2 pt-2 border-t border-amber-100/50">
          <p class="text-[10px] text-amber-500 font-bold italic">Tentukan lama cicilan untuk estimasi biaya.</p>
        </div>
      </div>
    </div>

    <div class="text-[10px] text-slate-400 font-medium italic mt-4 space-y-1 border-t border-slate-100 pt-3">
      <p>* Pencairan dilakukan setiap hari Jum'at.</p>
      <?php if ($duser->status_pegawai == 'tetap'): ?>
        <p>* Max pinjaman Rp 50.000.000, Max tenor 24 bulan.</p>
      <?php else: ?>
        <p>* Max pinjaman Rp 15.000.000, Max tenor 12 bulan.</p>
      <?php endif; ?>
    </div>

  </div>
</form>

<div class="mt-8 pt-4 border-t border-slate-100 flex gap-3">
  <button onclick="ModalHelper.close()" class="flex-1 py-3 bg-slate-100 text-slate-500 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-colors">Batal</button>
  <button type="submit" form="formSheet" class="flex-1 py-3 bg-indigo-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all hover:scale-[1.02]">Kirim Pengajuan</button>
</div>