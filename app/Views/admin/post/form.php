<?= $this->extend('layout/admin') ?>

<?= $this->section('styles') ?>
<!-- Summernote or TinyMCE can be added here if needed, defaulting to standard textarea or simple CDN -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<!-- Select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
  .note-editor {
    border-radius: 1rem;
    border-color: #f1f5f9;
    box-shadow: none;
  }

  .note-toolbar {
    border-bottom: 1px solid #f1f5f9;
    background: #f8fafc;
    border-radius: 1rem 1rem 0 0;
  }

  .select2-container .select2-selection--single,
  .select2-container .select2-selection--multiple {
    height: auto !important;
    padding: 0.5rem;
    border-radius: 0.75rem;
    border-color: #e2e8f0;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto space-y-8 pb-20">
  <div class="flex items-center gap-4">
    <a href="<?= base_url('admin/posts') ?>" class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-slate-900 transition-all">
      <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </a>
    <div>
      <h1 class="text-2xl font-black text-slate-800 tracking-tight"><?= isset($post) ? 'Edit Postingan' : 'Buat Postingan Baru' ?></h1>
      <p class="text-slate-500 font-medium text-sm">Masukan detail informasi yang ingin dipublikasikan.</p>
    </div>
  </div>

  <form action="<?= isset($post) ? base_url('admin/posts/update/' . $post->id) : base_url('admin/posts/store') ?>" method="post" enctype="multipart/form-data" class="space-y-6">

    <?= session()->getFlashdata('notif'); ?>

    <?php if (session()->getFlashdata('errors')): ?>
      <div class="bg-red-50 text-red-600 p-4 rounded-xl border border-red-100 font-bold text-sm">
        <ul>
          <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li>- <?= esc($error) ?></li>
          <?php endforeach ?>
        </ul>
      </div>
    <?php endif; ?>

    <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50 space-y-6">

      <!-- Title -->
      <div>
        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Judul Postingan</label>
        <input type="text" name="title" value="<?= old('title', $post->title ?? '') ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-900 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder:text-slate-300" placeholder="Contoh: Pengumuman Pembagian SHU Tahun 2025" required>
      </div>

      <!-- Category -->
      <div>
        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Kategori</label>
        <select name="category" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-900 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
          <?php
          $cats = ['Berita', 'Pengumuman', 'Edukasi', 'Laporan', 'Promo'];
          $selCat = old('category', $post->category ?? '');
          ?>
          <option value="">Pilih Kategori</option>
          <?php foreach ($cats as $cat): ?>
            <option value="<?= $cat ?>" <?= $selCat === $cat ? 'selected' : '' ?>><?= $cat ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Featured Image -->
      <div>
        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Gambar Utama</label>
        <?php if (isset($post->featured_image) && $post->featured_image): ?>
          <div class="mb-2">
            <img src="<?= base_url($post->featured_image) ?>" alt="Current Image" class="h-32 rounded-xl object-cover">
            <input type="hidden" name="old_featured_image" value="<?= $post->featured_image ?>">
          </div>
        <?php endif; ?>
        <input type="file" name="featured_image" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2 text-sm font-medium text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
      </div>

      <!-- Content -->
      <div>
        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Konten</label>
        <textarea id="summernote" name="content"><?= old('content', $post->content ?? '') ?></textarea>
      </div>

    </div>

    <div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50 space-y-6">
      <h3 class="text-lg font-black text-slate-800">Target & Publikasi</h3>

      <!-- Target Type -->
      <div class="grid grid-cols-3 gap-4">
        <label class="cursor-pointer">
          <input type="radio" name="target_type" value="public" class="peer sr-only" <?= (old('target_type', $target_type ?? 'public') === 'public') ? 'checked' : '' ?> onchange="toggleTargets()">
          <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50 peer-checked:bg-blue-50 peer-checked:border-blue-200 peer-checked:text-blue-700 text-center transition-all">
            <span class="block text-xs font-black uppercase tracking-widest">Publik</span>
          </div>
        </label>
        <label class="cursor-pointer">
          <input type="radio" name="target_type" value="group" class="peer sr-only" <?= (old('target_type', $target_type ?? '') === 'group') ? 'checked' : '' ?> onchange="toggleTargets()">
          <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50 peer-checked:bg-blue-50 peer-checked:border-blue-200 peer-checked:text-blue-700 text-center transition-all">
            <span class="block text-xs font-black uppercase tracking-widest">Grup</span>
          </div>
        </label>
        <label class="cursor-pointer">
          <input type="radio" name="target_type" value="user" class="peer sr-only" <?= (old('target_type', $target_type ?? '') === 'user') ? 'checked' : '' ?> onchange="toggleTargets()">
          <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50 peer-checked:bg-blue-50 peer-checked:border-blue-200 peer-checked:text-blue-700 text-center transition-all">
            <span class="block text-xs font-black uppercase tracking-widest">User Spesifik</span>
          </div>
        </label>
      </div>

      <!-- Target Group Input -->
      <div id="target_group_container" class="hidden">
        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Grup</label>
        <select name="target_group_id[]" class="w-full select2-basic" multiple="multiple">
          <?php if (isset($groups)): foreach ($groups as $group): ?>
              <option value="<?= $group->idgroup ?>" <?= (in_array($group->idgroup, old('target_group_id', $selected_groups ?? []))) ? 'selected' : '' ?>>
                <?= $group->keterangan ?>
              </option>
          <?php endforeach;
          endif; ?>
        </select>
      </div>

      <!-- Target User Input -->
      <div id="target_user_container" class="hidden">
        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih User (Cari Nama/NIK)</label>
        <!-- We should implement ajax select2 for users, but for now standard select (might be heavy if many users) -->
        <!-- Improvement: Use AJAX for user search -->
        <select name="target_user_id[]" class="w-full select2-users" multiple="multiple">
          <?php if (!empty($selected_users_data)): ?>
            <?php foreach ($selected_users_data as $u): ?>
              <option value="<?= $u['iduser'] ?>" selected>
                <?= $u['nama_lengkap'] . ' (' . $u['username'] . ') - ' . $u['instansi'] ?>
              </option>
            <?php endforeach; ?>
          <?php endif; ?>
        </select>
        <p class="text-[10px] text-slate-400 mt-1">*Ketik nama untuk mencari</p>
      </div>

      <!-- Publish Status -->
      <div class="flex items-center gap-4 border-t border-slate-50 pt-6">
        <label class="relative inline-flex items-center cursor-pointer">
          <input type="checkbox" name="status" value="published" class="sr-only peer" <?= (old('status', isset($post) && $post->is_published ? 'published' : '')) === 'published' ? 'checked' : '' ?>>
          <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-100 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
          <span class="ml-3 text-sm font-bold text-slate-700">Publikasikan Langsung</span>
        </label>
      </div>
    </div>

    <div class="flex gap-3">
      <?php if (isset($post)): ?>
        <button type="submit" class="flex-1 py-4 bg-blue-600 text-white rounded-2xl text-sm font-black uppercase tracking-widest hover:bg-blue-700 shadow-xl shadow-blue-200 transition-all hover:scale-[1.01]">
          Simpan Perubahan
        </button>
      <?php else: ?>
        <button type="submit" name="save_action" value="save_edit" class="flex-1 py-4 bg-blue-600 text-white rounded-2xl text-sm font-black uppercase tracking-widest hover:bg-blue-700 shadow-xl shadow-blue-200 transition-all hover:scale-[1.01]">
          Simpan Postingan
        </button>
        <button type="submit" name="save_action" value="save_new" class="flex-1 py-4 bg-slate-800 text-white rounded-2xl text-sm font-black uppercase tracking-widest hover:bg-slate-900 shadow-xl shadow-slate-200 transition-all hover:scale-[1.01]">
          Simpan & Buat Baru
        </button>
      <?php endif; ?>
    </div>

  </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  $(document).ready(function() {
    $('#summernote').summernote({
      placeholder: 'Tulis konten postingan disini...',
      tabsize: 2,
      height: 300,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ],
      callbacks: {
        onImageUpload: function(files) {
          // Implement image upload handler via AJAX
          // uploadImage(files[0]);
        }
      }
    });

    $('.select2-basic').select2();

    // AJAX Select2 for Users
    $('.select2-users').select2({
      placeholder: 'Ketik nama user...',
      minimumInputLength: 2,
      ajax: {
        url: '<?= base_url('admin/user/search') ?>',
        dataType: 'json',
        delay: 250,
        data: function(params) {
          return {
            term: params.term // search term
          };
        },
        processResults: function(data) {
          return {
            results: data.results
          };
        },
        cache: true
      }
    });

    toggleTargets();
  });

  function toggleTargets() {
    const type = $('input[name="target_type"]:checked').val();
    $('#target_group_container').addClass('hidden');
    $('#target_user_container').addClass('hidden');

    if (type === 'group') {
      $('#target_group_container').removeClass('hidden');
    } else if (type === 'user') {
      $('#target_user_container').removeClass('hidden');
    }
  }
</script>
<?= $this->endSection() ?>