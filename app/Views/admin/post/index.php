<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-8 pb-20">
  <!-- Header -->
  <div class="flex flex-col xl:flex-row justify-between items-start xl:items-center gap-4">
    <div>
      <h1 class="text-3xl font-black text-slate-800 tracking-tight">Postingan / Berita</h1>
      <p class="text-slate-500 font-medium">Kelola informasi, berita, dan pengumuman untuk anggota.</p>
    </div>

    <div class="flex flex-wrap gap-2">
      <a href="<?= base_url('admin/posts/create') ?>" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all hover:scale-[1.02] flex items-center gap-2">
        <i data-lucide="plus" class="w-4 h-4"></i> Tambah Postingan
      </a>
    </div>
  </div>

  <?= session()->getFlashdata('notif'); ?>
  <?php if (session()->getFlashdata('success')): ?>
    <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl border border-emerald-100 font-bold text-sm mb-4">
      <?= session()->getFlashdata('success') ?>
    </div>
  <?php endif; ?>
  <?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-50 text-red-600 p-4 rounded-xl border border-red-100 font-bold text-sm mb-4">
      <?= session()->getFlashdata('error') ?>
    </div>
  <?php endif; ?>
  <?= session()->getFlashdata('notif'); ?>

  <!-- Table Card -->
  <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
    <div class="mb-2">
      <h3 class="text-xl font-black text-slate-900 tracking-tight">Daftar Postingan</h3>
    </div>

    <div class="overflow-x-auto">
      <table id="dataTable" class="w-full whitespace-nowrap">
        <thead>
          <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Kategori</th>
            <th>Status</th>
            <th>Target</th>
            <th>Tanggal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $index => $post): ?>
              <tr>
                <td class="text-center font-bold text-slate-400 text-xs"><?= $index + 1 ?></td>
                <td>
                  <div class="flex flex-col">
                    <span class="font-bold text-slate-900 text-sm leading-tight line-clamp-1"><?= esc($post->title) ?></span>
                    <span class="text-[11px] font-medium text-slate-400">Views: <?= $post->views_count ?></span>
                  </div>
                </td>
                <td>
                  <span class="text-xs font-bold text-slate-600 tracking-tight"><?= esc($post->category) ?></span>
                </td>
                <td class="text-center">
                  <?php if ($post->is_published): ?>
                    <span class='inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[10px] font-black uppercase tracking-wider border border-emerald-100/50'>Published</span>
                  <?php else: ?>
                    <span class='inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-600 rounded-lg text-[10px] font-black uppercase tracking-wider border border-amber-100/50'>Draft</span>
                  <?php endif; ?>
                </td>
                <td>
                  <?php if ($post->is_public): ?>
                    <span class="text-xs font-bold text-blue-600">Publik</span>
                  <?php else: ?>
                    <span class="text-xs font-bold text-purple-600">Targeted</span>
                  <?php endif; ?>
                </td>
                <td>
                  <span class="text-xs font-bold text-slate-500"><?= $post->published_at ? date('d M Y', strtotime($post->published_at)) : '-' ?></span>
                </td>
                <td class="text-right">
                  <div class="flex justify-end gap-1.5">
                    <a href="<?= base_url('admin/posts/edit/' . $post->id) ?>" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-blue-600 hover:text-white transition-all flex items-center justify-center border border-slate-100 shadow-sm" title="Edit"><i data-lucide="edit" class="w-4 h-4"></i></a>
                    <a href="<?= base_url('admin/posts/delete/' . $post->id) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus postingan ini?')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all flex items-center justify-center border border-red-100 shadow-sm" title="Hapus"><i data-lucide="trash-2" class="w-4 h-4"></i></a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  $(document).ready(function() {
    $('#dataTable').DataTable({
      responsive: true,
      dom: '<"flex justify-between items-center gap-4 mb-4"lf><"rounded-xl border border-slate-100"t><"flex justify-between items-center gap-4 mt-4"ip>',
      language: {
        search: "",
        searchPlaceholder: "Cari Postingan...",
        lengthMenu: "_MENU_",
        info: "_START_ - _END_ dari _TOTAL_",
        paginate: {
          first: '<<',
          last: '>>',
          next: '>',
          previous: '<'
        }
      },
      drawCallback: function() {
        if (window.lucide) window.lucide.createIcons();
        $('.dataTables_filter input').addClass('px-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500');
      }
    });
  });
</script>
<?= $this->endSection() ?>