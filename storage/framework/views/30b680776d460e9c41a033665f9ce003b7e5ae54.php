<?php $__env->startSection('content'); ?>
    <h3 class="page-title">Tax Rate Class (Moneybrid)</h3>
  

    

    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo app('translator')->getFromJson('quickadmin.qa_list'); ?>
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                   
                    <th>Name</th>
                    <th>Percentage</th>
                    <th>Tax Rate Type</th>
                    <th>Active</th>
                    
                    
                    
                    
                    
                    
                    
                   
                </tr>
                </thead>
              
                <tbody>
                <?php if(count($appointments) > 0): ?>
                    <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr data-entry-id="<?php echo e($appointment->id); ?>">
                           
                            <td><?php echo e($appointment->name); ?></td>
                            <td><?php echo e($appointment->percentage); ?></td>
                            <td><?php echo e($appointment->tax_rate_type); ?></td>
                            
                            <td><?php echo e($appointment->active==1 ? 'Enable' : 'Disable'); ?></td>
                            
                           
                            
                            
                           
                           
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9"><?php echo app('translator')->getFromJson('quickadmin.qa_no_entries_in_table'); ?></td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>




<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>