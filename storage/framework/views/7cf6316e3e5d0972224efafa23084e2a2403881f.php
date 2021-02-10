<?php $__env->startSection('content'); ?>
    <!-- <h3 class="page-title"><i class="fa fa-user-circle ifont"></i> <?php echo e($page->page_subject); ?></h3>
 -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo e($page->page_subject); ?>

        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <table class=" ">
                        
                        <tr>
                            <td><?php echo $page->page_content; ?></td>
                        </tr>
                        
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>