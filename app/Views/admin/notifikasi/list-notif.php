<?= $this->include('admin/partials/head-main') ?>

<head>
  <?= $title_meta ?>
  <?= $this->include('admin/partials/head-css') ?>
</head>

<?= $this->include('admin/partials/body') ?>

<div id="layout-wrapper">
  <?= $this->include('admin/partials/menu') ?>
  <div class="main-content">
    <div class="page-content">
      <div class="container-fluid">
        <?= $page_title ?>
        <div class="row">
          <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h4>Notifikasi</h4>
              <a href="<?= base_url('admin/notification/tbl/mark-all-read') ?>" class="btn btn-sm btn-outline-primary">Tandai semua sudah dibaca</a>
            </div>

            <div class="overflow-auto" style="max-height: 500px;">
              <ul class="list-group notif-list">
              <?php if (empty($daftar_notif)): ?>
                <li class="list-group-item">Tidak ada notifikasi.</li>
              <?php endif; ?>

              <?php foreach ($daftar_notif as $notif): ?>
                <li class="list-group-item d-flex justify-content-between align-items-start notif-item
                  <?= ($notif['status'] == 'unread' ? 'notif-unread' : 'notif-read') ?>">
                  <div class="ms-2 me-auto">
                    <div class="fw-bold mb-1">
                      <?= $notif['message'] ?>
                      <?php if ($notif['status'] == 'unread'): ?>
                        <span class="badge bg-primary ms-2 align-middle">Baru</span>
                      <?php endif; ?>
                    </div>
                    <small class="text-muted"><?= date('d M Y H:i', strtotime($notif['timestamp'])) ?></small>
                  </div>
                  <?php if ($notif['status'] == 'unread'): ?>
                    <form method="post" action="<?= base_url('admin/notification/tbl/mark-as-read') ?>" class="ms-2">
                      <input type="hidden" name="id" value="<?= $notif['id'] ?>">
                      <button type="submit" class="btn btn-xs btn-outline-primary px-2 py-1" style="font-size: 0.8rem;">Tandai dibaca</button>
                    </form>
                  <?php endif; ?>
                </li>
              <?php endforeach; ?>
              </ul>
            </div>
            <style>
              .notif-list .notif-item {
                transition: box-shadow 0.2s, background 0.2s;
                border-left: 4px solid transparent;
              }
              .notif-list .notif-item.notif-unread {
                background: #f0f6ff;
                border-left: 4px solid #0d6efd;
              }
              .notif-list .notif-item.notif-read {
                color: #6c757d;
                background: #fff;
                opacity: 0.85;
              }
              .notif-list .notif-item:hover {
                box-shadow: 0 2px 8px rgba(13,110,253,0.08);
                background: #e9f2ff;
                z-index: 2;
              }
              .notif-list .badge {
                font-size: 0.75em;
                vertical-align: middle;
              }
              .btn-xs {
                padding: 0.15rem 0.5rem;
                font-size: 0.75rem;
                line-height: 1.2;
                border-radius: 0.2rem;
              }
            </style>

            <div class="mt-3">
              <?= $pager->links('default', 'default_minia') ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?= $this->include('admin/partials/footer') ?>
  </div>
</div>

<?= $this->include('admin/partials/right-sidebar') ?>

<!-- JAVASCRIPT -->
<?= $this->include('admin/partials/vendor-scripts') ?>

<!-- Required datatable js -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<script src="<?=base_url()?>/assets/js/app.js"></script>

</body>
</html>