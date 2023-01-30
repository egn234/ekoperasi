<script src="<?=base_url()?>/assets/libs/jquery/jquery.min.js"></script>
<script src="<?=base_url()?>/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?=base_url()?>/assets/libs/metismenu/metisMenu.min.js"></script>
<script src="<?=base_url()?>/assets/libs/simplebar/simplebar.min.js"></script>
<script src="<?=base_url()?>/assets/libs/node-waves/waves.min.js"></script>
<script src="<?=base_url()?>/assets/libs/feather-icons/feather.min.js"></script>
<!-- pace js -->
<script src="<?=base_url()?>/assets/libs/pace-js/pace.min.js"></script>

<script>
    $(document).ready(function(){
        $(".update-notification").click(function(){
            var id = $(this).data('id');
            $.ajax({
                url: "<?php echo base_url('anggota/notification/mark-as-read/'); ?>",
                type: "POST",
                data: "id="+id,
                success: function(response) {
                    "<?php echo 'success' ?>"
                }
            });
        });
    });
</script>