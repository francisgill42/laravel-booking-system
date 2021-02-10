<?php $__env->startSection('content'); ?>
    <div class="row">
    <div class="col-md-6"><h3 class="page-title"><i class="fa fa-user ifont"></i>  <?php echo app('translator')->getFromJson('quickadmin.employees.title'); ?></h3></div>
    <div class="col-md-6 tright">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee_create')): ?>
    <p>
        <a href="<?php echo e(route('admin.employees.create')); ?>" class="btn btn-success"><?php echo app('translator')->getFromJson('quickadmin.qa_add_new'); ?></a>
        
    </p>
    <?php endif; ?>
    </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading bold">
            <?php echo app('translator')->getFromJson('quickadmin.qa_list'); ?>
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped <?php echo e(count($employees) > 0 ? 'datatable' : ''); ?> <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee_delete')): ?> dt-select <?php endif; ?>">
                <thead>
                    <tr>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee_delete')): ?>
                            <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        <?php endif; ?> 
                        <th><?php echo app('translator')->getFromJson('quickadmin.employees.fields.first-name'); ?></th>
                        <th><?php echo app('translator')->getFromJson('quickadmin.employees.fields.last-name'); ?></th>
                        <th><?php echo app('translator')->getFromJson('quickadmin.employees.fields.phone'); ?></th>
                        <th><?php echo app('translator')->getFromJson('quickadmin.employees.fields.email'); ?></th> 
                        <th>MoneyBird Key(Doc Id)</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php if(count($employees) > 0): ?>
                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr data-entry-id="<?php echo e($employee->id); ?>">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee_delete')): ?>
                                    <td></td>
                                <?php endif; ?> 
                                <td><?php echo e($employee->first_name); ?></td>
                                <td><?php echo e($employee->last_name); ?></td>
                                <td><?php echo e($employee->phone); ?></td>
                                <td><?php echo e($employee->email); ?></td> 								
                                <td><?php echo e($employee->moneybird_key); ?></td>                                 
                                <td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee_view')): ?>
                                    <a href="<?php echo e(route('admin.employees.show',[$employee->id])); ?>" class="btn btn-xs btn-primary"><?php echo app('translator')->getFromJson('quickadmin.qa_view'); ?></a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee_edit')): ?>
                                    <a href="<?php echo e(route('admin.employees.edit',[$employee->id])); ?>" class="btn btn-xs btn-info"><?php echo app('translator')->getFromJson('quickadmin.qa_edit'); ?></a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee_delete')): ?>
                                    <?php echo Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.employees.destroy', $employee->id])); ?>

                                    <?php echo Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')); ?>

                                    <?php echo Form::close(); ?>

                                    <?php endif; ?> 
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee_service')): ?>
                                    <a href="<?php echo e(route('admin.employees.services',[$employee->id])); ?>" class="btn btn-xs btn-warning"><?php echo app('translator')->getFromJson('quickadmin.qa_service'); ?></a>
                                    <?php endif; ?>
                                     <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('leave_access')): ?>
                                    <a href="<?php echo e(route('admin.leave.leavelist',[$employee->id])); ?>" class="btn btn-xs btn-warning"><?php echo app('translator')->getFromJson('quickadmin.qa_leave'); ?></a>
                                    <?php endif; ?>
                                     <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee_custom_timing_access')): ?>
                                    <a href="<?php echo e(route('admin.employeecustomtiming.employeecustomtiminglist',[$employee->id])); ?>" class="btn btn-xs btn-warning"><?php echo app('translator')->getFromJson('quickadmin.qa_custom_timing'); ?></a>
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

<?php $__env->startSection('javascript'); ?> 
    <script>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee_delete')): ?>
            window.route_mass_crud_entries_destroy = '<?php echo e(route('admin.employees.mass_destroy')); ?>';
        <?php endif; ?>

    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>