<?= $this->extend('layout/admin') ?>

<?= $this->section('content') ?>

<div class="space-y-8 pb-20">
	<!-- Header -->
	<div class="flex items-center gap-3">
		<div class="p-3 bg-blue-50 text-blue-600 rounded-2xl">
			<i data-lucide="wallet" class="w-8 h-8"></i>
		</div>
		<div>
			<h1 class="text-3xl font-black text-slate-900 tracking-tight">Kelola Transaksi Simpanan</h1>
			<p class="text-slate-500 font-medium">Validasi dan riwayat transaksi simpanan anggota.</p>
		</div>
	</div>

	<?= session()->getFlashdata('notif'); ?>

	<!-- Pending Transactions -->
	<div class="bg-amber-50 rounded-[2.5rem] p-8 shadow-soft border border-amber-100">
		<div class="flex items-center gap-3 mb-8 pb-8 border-b border-amber-100">
			<div class="p-2 bg-amber-100 text-amber-600 rounded-xl">
				<i data-lucide="clock" class="w-6 h-6"></i>
			</div>
			<div>
				<h3 class="text-xl font-black text-amber-900">Menunggu Persetujuan</h3>
				<p class="text-amber-700 text-sm font-medium">Pengajuan yang perlu diproses.</p>
			</div>
		</div>

		<div>
			<table id="dt_list_filter" class="w-full text-left border-collapse">
				<thead class="bg-amber-100/50">
					<tr>
						<th class="p-4 text-xs font-black text-amber-900 uppercase tracking-wider text-left rounded-l-xl">Tanggal</th>
						<th class="p-4 text-xs font-black text-amber-900 uppercase tracking-wider text-left">Nama Anggota</th>
						<th class="p-4 text-xs font-black text-amber-900 uppercase tracking-wider text-left">Jenis</th>
						<th class="p-4 text-xs font-black text-amber-900 uppercase tracking-wider text-left">Nominal</th>
						<th class="p-4 text-xs font-black text-amber-900 uppercase tracking-wider text-left">Status</th>
						<th class="p-4 text-xs font-black text-amber-900 uppercase tracking-wider text-center rounded-r-xl">Aksi</th>
					</tr>
				</thead>
				<tbody class="text-sm text-slate-600 font-medium"></tbody>
			</table>
		</div>
	</div>

	<!-- All Transactions -->
	<div class="bg-white rounded-[2.5rem] p-8 shadow-soft border border-slate-50">
		<div class="flex items-center gap-3 mb-8 pb-8 border-b border-slate-50">
			<div class="p-2 bg-indigo-50 text-indigo-600 rounded-xl">
				<i data-lucide="history" class="w-6 h-6"></i>
			</div>
			<h3 class="text-xl font-black text-slate-900">Riwayat Semua Transaksi</h3>
		</div>

		<div>
			<table id="dt_list" class="w-full text-left border-collapse">
				<thead class="bg-slate-50">
					<tr>
						<th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-left rounded-l-xl">Tanggal</th>
						<th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-left">Nama Anggota</th>
						<th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-left">Jenis</th>
						<th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-left">Nominal</th>
						<th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-left">Status</th>
						<th class="p-4 text-xs font-black text-slate-400 uppercase tracking-wider text-center rounded-r-xl">Aksi</th>
					</tr>
				</thead>
				<tbody class="text-sm text-slate-600 font-medium"></tbody>
			</table>
		</div>
	</div>
</div>

<!-- Modal Container -->
<div id="modal-overlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden transition-opacity"></div>
<div id="dynamicModalContainer"></div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
	(function initDepositList() {
		// Wait for jQuery to be available
		if (typeof jQuery === 'undefined') {
			console.warn('jQuery not ready yet, retrying...');
			setTimeout(initDepositList, 50);
			return;
		}

		function closeModal() {
			document.getElementById('modal-overlay').classList.add('hidden');
			document.getElementById('dynamicModalContainer').innerHTML = '';
		}

		function showDynamicModal(title, content) {
			const container = document.getElementById('dynamicModalContainer');
			const modalHtml = `
            <div id="ajaxModal" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-50 w-full max-w-lg bg-white rounded-[2rem] shadow-2xl p-8 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6 pb-6 border-b border-slate-100">
                    <h3 class="text-xl font-black text-slate-900">${title}</h3>
                    <button onclick="closeModal()" class="p-2 bg-slate-50 text-slate-400 rounded-full hover:bg-slate-100 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <div class="modal-content-body">
                    ${content}
                </div>
            </div>
        `;
			container.innerHTML = modalHtml;
			document.getElementById('modal-overlay').classList.remove('hidden');
			if (window.lucide) window.lucide.createIcons();

			document.getElementById('modal-overlay').onclick = closeModal;
		}

		window.closeModal = closeModal;
		window.showDynamicModal = showDynamicModal;

		window.loadDetailMutasi = function(id) {
			jQuery.ajax({
				type: 'POST',
				url: '<?= base_url() ?>admin/deposit/detail_mutasi',
				data: {
					rowid: id
				},
				success: function(data) {
					showDynamicModal('Detail Transaksi', data);
				}
			});
		};

		window.loadApproveMnsk = function(id) {
			jQuery.ajax({
				type: 'POST',
				url: '<?= base_url() ?>admin/deposit/approve-mnsk',
				data: {
					rowid: id
				},
				success: function(data) {
					showDynamicModal('Konfirmasi Persetujuan', data);
				}
			});
		};

		window.loadTolakMnsk = function(id) {
			jQuery.ajax({
				type: 'POST',
				url: '<?= base_url() ?>admin/deposit/cancel-mnsk',
				data: {
					rowid: id
				},
				success: function(data) {
					showDynamicModal('Tolak Pengajuan', data);
				}
			});
		};

		jQuery(document).ready(function($) {
			// Table 1: Pending Transactions
			var tableFilter = $('#dt_list_filter').DataTable({
				ajax: {
					url: "<?= base_url() ?>admin/deposit/data_transaksi_filter",
					type: "POST"
				},
				processing: true,
				serverSide: true,
				autoWidth: false,
				ordering: false,
				scrolling: false,
				scrollCollapse: false,
				paging: true,
				pageLength: 10,
				language: {
					search: "",
					searchPlaceholder: "Cari...",
					lengthMenu: "_MENU_",
					info: "_START_-_END_ dari _TOTAL_",
					paginate: {
						first: '<<',
						last: '>>',
						next: '>',
						previous: '<'
					}
				},
				dom: '<"flex justify-between items-center gap-4 mb-4"lf><"rounded-xl border border-amber-100"t><"flex justify-between items-center gap-4 mt-4"ip>',
				columns: [{
						data: "date_created",
						className: "px-4 py-3 border-b border-amber-100",
						render: (data) => data.split(' ')[0]
					},
					{
						data: "nama_lengkap",
						className: "px-4 py-3 border-b border-amber-100 font-bold"
					},
					{
						data: "jenis_pengajuan",
						className: "px-4 py-3 border-b border-amber-100 capitalize"
					},
					{
						data: "cash_in",
						className: "px-4 py-3 border-b border-amber-100",
						render: function(data, type, row) {
							return row.jenis_pengajuan == 'penyimpanan' ?
								`<span class="text-emerald-600 font-bold">+ ${parseInt(row.cash_in).toLocaleString('id-ID')}</span>` :
								`<span class="text-red-600 font-bold">- ${parseInt(row.cash_out).toLocaleString('id-ID')}</span>`;
						}
					},
					{
						data: "status",
						className: "px-4 py-3 border-b border-amber-100",
						render: function(data) {
							return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-200 text-amber-800 capitalize">${data}</span>`;
						}
					},
					{
						data: "iddeposit",
						className: "px-4 py-3 border-b border-amber-100 text-center",
						render: function(data) {
							return `
                        <div class="flex items-center justify-center gap-2">
                             <button onclick="loadDetailMutasi(${data})" class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100"><i data-lucide="search" class="w-4 h-4"></i></button>
                             <button onclick="loadApproveMnsk(${data})" class="p-2 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100"><i data-lucide="check" class="w-4 h-4"></i></button>
                             <button onclick="loadTolakMnsk(${data})" class="p-2 rounded-lg bg-red-50 text-red-600 hover:bg-red-100"><i data-lucide="x" class="w-4 h-4"></i></button>
                        </div>`;
						}
					}
				],
				drawCallback: function() {
					if (window.lucide) window.lucide.createIcons();

					// FORCE KILL SCROLLBAR
					$('.dataTables_wrapper, .dataTables_scroll, .dataTables_scrollBody, div.dataTables_scrollBody').each(function() {
						$(this).css({
							'overflow': 'visible',
							'overflow-y': 'visible',
							'overflow-x': 'visible',
							'height': 'auto',
							'max-height': 'none'
						});
					});
				}
			});

			// Table 2: All Transactions
			var tableAll = $('#dt_list').DataTable({
				ajax: {
					url: "<?= base_url() ?>admin/deposit/data_transaksi",
					type: "POST"
				},
				processing: true,
				serverSide: true,
				autoWidth: false,
				ordering: false,
				scrolling: false,
				scrollCollapse: false,
				paging: true,
				pageLength: 10,
				language: {
					search: "",
					searchPlaceholder: "Cari Transaksi...",
					lengthMenu: "_MENU_",
					info: "_START_-_END_ dari _TOTAL_",
					paginate: {
						first: '<<',
						last: '>>',
						next: '>',
						previous: '<'
					}
				},
				dom: '<"flex flex-col md:flex-row justify-between items-center gap-4 mb-6"lf><"rounded-xl border border-slate-100"t><"flex flex-col md:flex-row justify-between items-center gap-4 mt-6"ip>',
				columns: [{
						data: "date_created",
						className: "px-4 py-3 border-b border-slate-50",
						render: (data) => data.split(' ')[0]
					},
					{
						data: "nama_lengkap",
						className: "px-4 py-3 border-b border-slate-50 font-bold"
					},
					{
						data: "jenis_pengajuan",
						className: "px-4 py-3 border-b border-slate-50 capitalize"
					},
					{
						data: "cash_in",
						className: "px-4 py-3 border-b border-slate-50",
						render: function(data, type, row) {
							let amount = 0;
							let isPlus = true;
							if (row.jenis_pengajuan == 'penyimpanan' || row.cash_in > 0) {
								amount = row.cash_in;
							} else {
								amount = row.cash_out;
								isPlus = false;
							}

							return isPlus ?
								`<span class="text-emerald-600 font-bold">+ ${parseInt(amount).toLocaleString('id-ID')}</span>` :
								`<span class="text-red-600 font-bold">- ${parseInt(amount).toLocaleString('id-ID')}</span>`;
						}
					},
					{
						data: "status",
						className: "px-4 py-3 border-b border-slate-50",
						render: function(data) {
							let color = 'bg-slate-100 text-slate-600';
							if (data == 'diterima') color = 'bg-emerald-100 text-emerald-800';
							else if (data == 'ditolak') color = 'bg-red-100 text-red-800';
							else if (data.includes('diproses')) color = 'bg-amber-100 text-amber-800';

							return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold ${color} capitalize">${data}</span>`;
						}
					},
					{
						data: "iddeposit",
						className: "px-4 py-3 border-b border-slate-50 text-center",
						render: function(data) {
							return `<button onclick="loadDetailMutasi(${data})" class="p-2 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors"><i data-lucide="search" class="w-4 h-4"></i></button>`;
						}
					}
				],
				drawCallback: function() {
					if (window.lucide) window.lucide.createIcons();
					$('.dataTables_filter input').addClass('px-4 py-2 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500');

					// FORCE KILL SCROLLBAR
					$('.dataTables_wrapper, .dataTables_scroll, .dataTables_scrollBody, div.dataTables_scrollBody').each(function() {
						$(this).css({
							'overflow': 'visible',
							'overflow-y': 'visible',
							'overflow-x': 'visible',
							'height': 'auto',
							'max-height': 'none'
						});
					});
				}
			});
		});
	})();
</script>
<?= $this->endSection() ?>