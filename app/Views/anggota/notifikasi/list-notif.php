<?= $this->extend('layout/member') ?>

<?= $this->section('content') ?>

<div class="space-y-8 pb-20">
  <!-- Header -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
      <h1 class="text-3xl font-black text-slate-800 tracking-tight">Notifikasi</h1>
      <p class="text-slate-500 font-medium">Pusat informasi dan pembaruan sistem</p>
    </div>
    <div>
      <a href="<?= base_url('anggota/notification/tbl/mark-all-read') ?>" class="inline-flex items-center space-x-2 px-5 py-2.5 bg-blue-50 text-blue-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all shadow-sm hover:shadow-blue-200">
        <i data-lucide="check-check" class="w-4 h-4"></i>
        <span>Tandai Semua Dibaca</span>
      </a>
    </div>
  </div>

  <!-- Notification List -->
  <div class="bg-white md:rounded-[2.5rem] shadow-none md:shadow-soft border-t md:border border-slate-50 overflow-hidden -mx-4 md:mx-0">

    <!-- Mobile Sticky Header -->
    <div class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-slate-100 p-4 md:p-8 flex justify-between items-center">
      <h3 class="text-lg md:text-xl font-black text-slate-900 tracking-tight">Daftar Notifikasi</h3>
      <span class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-[10px] md:text-xs font-black uppercase tracking-wider md:rounded-lg">
        <?= $badge_notif ?> Baru
      </span>
    </div>

    <div class="divide-y divide-slate-50">
      <?php if (empty($daftar_notif)): ?>
        <div class="py-20 md:p-12 text-center">
          <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-slate-50 text-slate-300 flex items-center justify-center mx-auto mb-4">
            <i data-lucide="bell-off" class="w-8 h-8 md:w-10 md:h-10"></i>
          </div>
          <h4 class="text-base md:text-lg font-bold text-slate-900 mb-1">Tidak Ada Notifikasi</h4>
          <p class="text-slate-400 text-xs md:text-sm">Semua aman, belum ada info baru.</p>
        </div>
      <?php endif; ?>

      <?php foreach ($daftar_notif as $notif): ?>
        <?php $isUnread = ($notif['status'] == 'unread'); ?>
        <div class="p-4 md:p-6 transition-all hover:bg-slate-50 relative <?= $isUnread ? 'bg-blue-50/50' : 'bg-white' ?> group">

          <?php if ($isUnread): ?>
            <!-- Stronger Left Indicator -->
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-600 shadow-[2px_0_8px_rgba(37,99,235,0.3)]"></div>
          <?php endif; ?>

          <div class="flex gap-3 md:gap-4 items-start">
            <!-- Icon -->
            <div class="flex-shrink-0 mt-1">
              <div class="w-10 h-10 md:w-12 md:h-12 rounded-2xl flex items-center justify-center border-2 <?= $isUnread ? 'bg-blue-600 text-white border-blue-200 shadow-lg shadow-blue-100' : 'bg-slate-50 text-slate-400 border-white shadow-sm' ?> transition-all duration-300">
                <i data-lucide="<?= $isUnread ? 'bell-ring' : 'bell' ?>" class="w-5 h-5 md:w-6 md:h-6 <?= $isUnread ? 'animate-bounce' : '' ?>"></i>
              </div>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0 pt-0.5">
              <div class="flex justify-between items-start gap-2 mb-1">
                <div class="flex items-center gap-2">
                  <p class="text-[10px] md:text-xs font-black <?= $isUnread ? 'text-blue-600' : 'text-slate-400' ?> uppercase tracking-widest">
                    <?= date('d M, H:i', strtotime($notif['timestamp'])) ?>
                  </p>
                  <?php if ($isUnread): ?>
                    <span class="flex h-2 w-2 rounded-full bg-blue-600 animate-pulse"></span>
                    <span class="text-[9px] font-black text-blue-600 uppercase tracking-tighter">Baru</span>
                  <?php endif; ?>
                </div>

                <!-- Mobile Action (Always Visible if Unread) -->
                <?php if ($isUnread): ?>
                  <form method="post" action="<?= base_url('anggota/notification/tbl/mark-as-read') ?>" class="md:hidden">
                    <input type="hidden" name="id" value="<?= $notif['id'] ?>">
                    <button type="submit" class="p-2 -mr-2 text-blue-600 bg-blue-100 rounded-lg active:scale-90 transition-transform">
                      <i data-lucide="check" class="w-4 h-4"></i>
                    </button>
                  </form>
                <?php endif; ?>
              </div>

              <h5 class="text-sm md:text-base font-bold leading-snug mb-3 <?= $isUnread ? 'text-slate-900 drop-shadow-sm' : 'text-slate-500 font-medium' ?>">
                <?= $notif['message'] ?>
              </h5>

              <!-- Action Buttons -->
              <div class="flex justify-end opacity-0 group-hover:opacity-100 transition-opacity">
                <?php if ($isUnread): ?>
                  <form method="post" action="<?= base_url('anggota/notification/tbl/mark-as-read') ?>">
                    <input type="hidden" name="id" value="<?= $notif['id'] ?>">
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all active:scale-95">
                      <i data-lucide="check-check" class="w-3 h-3"></i> Tandai Dibaca
                    </button>
                  </form>
                <?php else: ?>
                  <span class="text-xs font-bold text-slate-300 flex items-center gap-1 cursor-default">
                    <i data-lucide="check-circle" class="w-3 h-3"></i> Sudah Dibaca
                  </span>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Footer / Pagination -->
    <?php if ($pager): ?>
      <div class="p-6 border-t border-slate-50 bg-slate-50/30">
        <?= $pager->links('default', 'default_minia') ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- No specific JS needed -->
<?= $this->endSection() ?>