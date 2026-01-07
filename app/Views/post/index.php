<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="space-y-10 pb-20 animate-[fade-in_0.5s_ease-in-out]">
  <!-- Header & Search -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 px-2">
    <h2 class="text-2xl font-black text-slate-900 tracking-tight">Pusat Informasi</h2>
    <form action="" method="get" class="relative w-full md:w-80">
      <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4"></i>
      <input
        type="text"
        name="search"
        placeholder="Cari berita..."
        value="<?= esc($search ?? '') ?>"
        class="w-full bg-white border border-slate-100 rounded-2xl py-4 pl-12 pr-4 shadow-soft focus:ring-2 focus:ring-blue-600 outline-none transition-all font-bold" />
    </form>
  </div>

  <!-- Grid -->
  <?php if (empty($posts)): ?>
    <div class="text-center py-20 bg-white rounded-[2.5rem] border border-slate-50 shadow-soft">
      <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
        <i data-lucide="inbox" class="w-8 h-8"></i>
      </div>
      <h3 class="text-lg font-black text-slate-800">Tidak ada informasi ditemukan</h3>
      <p class="text-slate-500 text-sm font-medium mt-1">Coba kata kunci lain atau kembali lagi nanti.</p>
    </div>
  <?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php foreach ($posts as $post): ?>
        <?php
        // Dynamic tag color based on simple hash or hardcoded categories
        $catColors = [
          'Berita' => 'text-blue-600',
          'Pengumuman' => 'text-red-600',
          'Edukasi' => 'text-emerald-600',
          'Promo' => 'text-purple-600',
          'Laporan' => 'text-amber-600'
        ];
        $tagColor = $catColors[$post->category] ?? 'text-slate-600';
        $imgUrl = $post->featured_image ? base_url($post->featured_image) : 'https://placehold.co/600x400/f1f5f9/94a3b8?text=No+Image';
        ?>
        <a href="<?= base_url('informasi/' . $post->slug) ?>" class="flex flex-col w-full rounded-[2.5rem] bg-white border border-slate-50 shadow-soft relative overflow-hidden group cursor-pointer transition-all hover:shadow-2xl hover:-translate-y-1">
          <div class="h-44 w-full overflow-hidden relative bg-slate-100">
            <img src="<?= $imgUrl ?>" alt="<?= esc($post->title) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" loading="lazy" />
            <div class="absolute top-4 left-4 px-4 py-1.5 bg-white <?= $tagColor ?> text-[8px] font-black rounded-full uppercase tracking-widest shadow-lg">
              <?= esc($post->category) ?>
            </div>
          </div>
          <div class="p-8 flex flex-col flex-1 justify-between">
            <div>
              <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest block mb-2">
                <?= date('d M Y', strtotime($post->published_at)) ?>
              </span>
              <h4 class="text-lg font-black text-slate-900 mb-3 leading-tight group-hover:text-blue-700 transition-colors line-clamp-2">
                <?= esc($post->title) ?>
              </h4>
              <p class="text-xs text-slate-500 font-medium leading-relaxed line-clamp-2 opacity-90 mb-6">
                <?= esc(strip_tags($post->excerpt ?: substr($post->content, 0, 100))) ?>
              </p>
            </div>
            <div class="flex items-center justify-between border-t border-slate-50 pt-5 mt-auto">
              <span class="text-[10px] font-black text-blue-700 uppercase tracking-widest">Baca Detail</span>
              <div class="w-10 h-10 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-300 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
              </div>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?= $this->endSection() ?>