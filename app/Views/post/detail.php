<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="space-y-8 pb-20 animate-[fade-in_0.5s_ease-in-out]">
  <!-- Top Nav -->
  <div class="flex items-center justify-between px-2">
    <a href="<?= base_url('informasi') ?>" class="flex items-center space-x-2 text-slate-400 hover:text-blue-600 font-black text-xs uppercase tracking-widest transition-colors">
      <i data-lucide="chevron-left" class="w-4 h-4"></i>
      <span>Kembali ke Berita</span>
    </a>
    <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest"><?= date('d M Y', strtotime($post->published_at)) ?></span>
  </div>

  <!-- Hero Section -->
  <div class="bg-white rounded-[3rem] shadow-soft border border-slate-50 overflow-hidden relative">
    <div class="h-64 sm:h-[450px] w-full relative group">
      <?php $imgUrl = $post->featured_image ? base_url($post->featured_image) : 'https://placehold.co/1200x600/1e293b/475569?text=Cover+Image'; ?>
      <img src="<?= $imgUrl ?>" class="w-full h-full object-cover" alt="<?= esc($post->title) ?>" />
      <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent"></div>

      <div class="absolute bottom-10 left-8 right-8 md:bottom-12 md:left-12 md:right-12">
        <div class="px-4 py-1.5 bg-white text-slate-900 text-[10px] font-black rounded-full uppercase tracking-widest w-fit mb-4 shadow-lg">
          <?= esc($post->category) ?>
        </div>
        <h1 class="text-3xl sm:text-5xl font-black text-white leading-tight tracking-tight max-w-4xl drop-shadow-sm">
          <?= esc($post->title) ?>
        </h1>
      </div>
    </div>

    <!-- Content Body -->
    <div class="p-8 md:p-16 max-w-5xl mx-auto">
      <!-- Lead Paragraph (Excerpt) -->
      <?php if ($post->excerpt): ?>
        <p class="text-xl md:text-2xl font-bold text-slate-800 leading-relaxed mb-10 border-l-4 border-blue-600 pl-6">
          <?= esc(strip_tags($post->excerpt)) ?>
        </p>
      <?php endif; ?>

      <!-- Main Content -->
      <div class="prose prose-lg prose-slate max-w-none text-slate-600 font-medium prose-headings:font-black prose-headings:text-slate-900 prose-a:text-blue-600 prose-img:rounded-[2rem]">
        <?= $post->content ?>
      </div>

      <!-- Media Attachments (if any) -->
      <?php if (!empty($media)): ?>
        <div class="mt-12 space-y-4">
          <h3 class="text-lg font-black text-slate-900">Lampiran</h3>
          <?php foreach ($media as $item): ?>
            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-100">
              <!-- Simple file download link -->
              <a href="<?= base_url($item->file_path) ?>" target="_blank" class="flex items-center gap-2 text-blue-600 font-bold hover:underline">
                <i data-lucide="file" class="w-4 h-4"></i>
                <?= esc($item->file_name ?: 'Download File') ?>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- Footer -->
      <div class="mt-20 pt-10 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-6">
        <div class="flex items-center space-x-4">
          <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400">
            <i data-lucide="info" class="w-5 h-5"></i>
          </div>
          <div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Penulis</p>
            <p class="text-sm font-black text-slate-900">Admin Koperasi</p>
          </div>
        </div>
        <button onclick="history.back()" class="bg-slate-900 text-white px-8 py-4 rounded-2xl text-xs font-black shadow-lg shadow-slate-200 uppercase tracking-widest hover:scale-105 transition-all">
          Tutup Berita
        </button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>