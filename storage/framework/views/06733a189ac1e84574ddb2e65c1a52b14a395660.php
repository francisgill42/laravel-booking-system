<script type="text/javascript">
	var BASE_URL = "<?php echo e(url('')); ?>";
</script>
<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
<script src="//cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
<script src="https://code.jquery.com/ui/1.11.3/jquery-ui.min.js"></script>
<script src="<?php echo e(url('quickadmin/js')); ?>/bootstrap.min.js"></script>
<script src="<?php echo e(url('quickadmin/js')); ?>/select2.full.min.js"></script>
<script src="<?php echo e(url('quickadmin/js')); ?>/main.js"></script>

<script>
    window._token = '<?php echo e(csrf_token()); ?>';
     /*$(document).ready(function() {
            $(".hibutton").click(function(){
               alert("jjhkvbnfdkbhdjk nb"); 
            });
     });*/
</script>



<?php echo $__env->yieldContent('javascript'); ?>