<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="space-y-8 pb-20">
  <!-- Header -->
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
      <h1 class="text-3xl font-black text-slate-800 tracking-tight">Notifikasi</h1>
      <p class="text-slate-500 font-medium">Pusat informasi dan pembaruan sistem</p>
    </div>
    <div>
      <a href="<?= base_url('admin/notification/tbl/mark-all-read') ?>" class="inline-flex items-center space-x-2 px-5 py-2.5 bg-blue-50 text-blue-600 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all shadow-sm hover:shadow-blue-200">
        <i data-lucide="check-check" class="w-4 h-4"></i>
        <span>Tandai Semua Dibaca</span>
      </a>
    </div>
  </div>

  <!-- Notification List -->
  <div class="bg-white rounded-[2.5rem] shadow-soft border border-slate-50 overflow-hidden">
    <div class="p-8 border-b border-slate-100 flex justify-between items-center">
      <h3 class="text-xl font-black text-slate-900 tracking-tight">Daftar Notifikasi</h3>
      <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-lg text-xs font-bold uppercase tracking-wider"><?= $badge_notif ?> Belum Dibaca</span>
    </div>

    <div class="divide-y divide-slate-50">
      <?php if (empty($daftar_notif)): ?>
        <div class="p-12 text-center">
          <div class="w-20 h-20 rounded-full bg-slate-50 text-slate-300 flex items-center justify-center mx-auto mb-4">
            <i data-lucide="bell-off" class="w-10 h-10"></i>
          </div>
          <h4 class="text-lg font-bold text-slate-900 mb-1">Tidak Ada Notifikasi</h4>
          <p class="text-slate-400 text-sm">Semua notifikasi telah dibaca or belum ada notifikasi baru.</p>
        </div>
      <?php endif; ?>

      <?php foreach ($daftar_notif as $notif): ?>
        <?php $isUnread = ($notif['status'] == 'unread'); ?>
        <div class="p-6 transition-all hover:bg-slate-50/50 group <?= $isUnread ? 'bg-blue-50/30' : '' ?>">
          <div class="flex gap-4 items-start">
            <!-- Icon -->
            <div class="flex-shrink-0">
              <div class="w-12 h-12 rounded-2xl flex items-center justify-center <?= $isUnread ? 'bg-blue-100 text-blue-600 shadow-lg shadow-blue-100' : 'bg-slate-100 text-slate-400' ?>">
                <i data-lucide="<?= $isUnread ? 'bell-ring' : 'bell' ?>" class="w-6 h-6"></i>
              </div>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
              <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-2 mb-2">
                <h5 class="text-sm font-bold text-slate-900 <?= $isUnread ? 'text-blue-900' : '' ?>">
                  <?= $notif['message'] ?>
                </h5>
                <span class="flex-shrink-0 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                  <?= date('d M Y H:i', strtotime($notif['timestamp'])) ?>
                </span>
              </div>

              <!-- Action Wrapper -->
              <div class="flex justify-end mt-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <?php if ($isUnread): ?>
                  <form method="post" action="<?= base_url('admin/notification/tbl/mark-as-read') ?>">
                    <input type="hidden" name="id" value="<?= $notif['id'] ?>">
                    <button type="submit" class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline flex items-center gap-1">
                      <i data-lucide="check" class="w-3 h-3"></i> Tandai Dibaca
                    </button>
                  </form>
                <?php else: ?>
                  <span class="text-xs font-bold text-slate-300 flex items-center gap-1 cursor-default">
                    <i data-lucide="check-circle" class="w-3 h-3"></i> Dibaca
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
<!-- No specific JS needed for simple notif list other than Lucide -->
<?= $this->endSection() ?>