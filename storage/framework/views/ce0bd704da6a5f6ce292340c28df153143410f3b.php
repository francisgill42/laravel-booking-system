<?php $__env->startSection('content'); ?>
    <div class="row">
    <div class="col-md-6"><h3 class="page-title"><i class="fa fa-user ifont"></i>  <?php echo app('translator')->getFromJson('quickadmin.emailtemplates.title'); ?></h3></div>
    <div class="col-md-6 tright">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('emailtemplate_create')): ?>
    <p>
        <a href="<?php echo e(route('admin.emailtemplates.create')); ?>" class="btn btn-success"><?php echo app('translator')->getFromJson('quickadmin.qa_add_new'); ?></a>
        
    </p>
    <?php endif; ?>
    </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading bold">
            <?php echo app('translator')->getFromJson('quickadmin.qa_list'); ?>
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped <?php echo e(count($emailtemplates) > 0 ? 'datatable' : ''); ?> ">
                <thead>
                    <tr>
                       
                        <th><?php echo app('translator')->getFromJson('quickadmin.emailtemplates.fields.subject'); ?></th>
                        <th><?php echo app('translator')->getFromJson('quickadmin.emailtemplates.fields.email_type'); ?></th>
                        
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php if(count($emailtemplates) > 0): ?>
                        <?php $__currentLoopData = $emailtemplates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr data-entry-id="<?php echo e($employee->id); ?>">
                               
                                <td><?php echo e($employee->email_subject); ?></td>
                                <td><?php echo e($employee->email_type); ?></td>
                                <td>
                                  
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('emailtemplate_edit')): ?>
                                    <a href="<?php echo e(route('admin.emailtemplates.edit',[$employee->id])); ?>" class="btn btn-xs btn-info"><?php echo app('translator')->getFromJson('quickadmin.qa_edit'); ?></a>
                                    <?php endif; ?>
                                  
                                    
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8"><?php echo app('translator')->getFromJson('quickadmin.qa_no_entries_in_table'); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>