<script src="<?=base_url()?>/assets/libs/jquery/jquery.min.js"></script>
<script src="<?=base_url()?>/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?=base_url()?>/assets/libs/metismenu/metisMenu.min.js"></script>
<script src="<?=base_url()?>/assets/libs/simplebar/simplebar.min.js"></script>
<script src="<?=base_url()?>/assets/libs/node-waves/waves.min.js"></script>
<script src="<?=base_url()?>/assets/libs/feather-icons/feather.min.js"></script>
<!-- pace js -->
<script src="<?=base_url()?>/assets/libs/pace-js/pace.min.js"></script>

<script>
	const BASE_URL = '<?= base_url() ?>';
	$(document).ready(function(){
		// Mark as read via AJAX
		$(".update-notification").click(function(e){
			var id = $(this).data('id');
			if(id) {
				$.ajax({
					url: BASE_URL + "admin/notification/mark-as-read/",
					type: "POST",
					data: {id: id},
				});
			}
			// Auto-close dropdown on click
			var dropdown = $(this).closest('.dropdown-menu');
			if(dropdown.length) {
				setTimeout(function(){
					dropdown.removeClass('show');
					$('.dropdown-backdrop').remove();
				}, 100);
			}
		});
	});
</script>