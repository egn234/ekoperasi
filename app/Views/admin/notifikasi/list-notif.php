<?= $this->include('admin/partials/head-main') ?>

<head>

    <?= $title_meta ?>

    <?= $this->include('admin/partials/head-css') ?>

</head>

<?= $this->include('admin/partials/body') ?>

<!-- <body data-layout="horizontal"> -->

<!-- Begin page -->
<div id="layout-wrapper">

    <?= $this->include('admin/partials/menu') ?>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">

        <div class="page-content">
            <div class="container-fluid">

                <!-- start page title -->
                <?= $page_title ?>
                <!-- end page title -->

                <div class="row">
                  <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <h4>Notifikasi</h4>
                      <a href="<?= base_url('admin/notification/tbl/mark-all-read') ?>" class="btn btn-sm btn-outline-primary">Tandai semua sudah dibaca</a>
                    </div>

                    <div class="overflow-auto" style="max-height: 500px;">
                      <ul class="list-group">
                        <?php if (empty($daftar_notif)): ?>
                          <li class="list-group-item">Tidak ada notifikasi.</li>
                        <?php endif; ?>

                        <?php foreach ($daftar_notif as $notif): ?>
                          <li class="list-group-item d-flex justify-content-between align-items-start <?= ($notif['status'] == 'unread' ? 'bg-light' : '') ?>">
                            <div class="ms-2 me-auto">
                              <div class="fw-bold"><?= $notif['message'] ?></div>
                              <small class="text-muted"><?= date('d M Y H:i', strtotime($notif['timestamp'])) ?></small>
                            </div>
                            <?php if ($notif['status'] == 'unread'): ?>
                              <form method="post" action="<?= base_url('admin/notification/tbl/mark-as-read') ?>" class="ms-2">
                                <input type="hidden" name="id" value="<?= $notif['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-secondary">Tandai dibaca</button>
                              </form>
                            <?php endif; ?>
                          </li>
                        <?php endforeach; ?>
                      </ul>
                    </div>

                    <div class="mt-3">
                      <?= $pager->links('default', 'default_minia') ?>
                    </div>
                  </div>

                </div> <!-- end row -->

            </div> <!-- container-fluid -->
        </div>
        <!-- End Page-content -->


        <?= $this->include('admin/partials/footer') ?>
    </div>
    <!-- end main content-->

</div>
<!-- END layout-wrapper -->

<?= $this->include('admin/partials/right-sidebar') ?>

<!-- JAVASCRIPT -->
<?= $this->include('admin/partials/vendor-scripts') ?>

<!-- Required datatable js -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>

<script src="<?=base_url()?>/assets/js/app.js"></script>

</body>

</html>