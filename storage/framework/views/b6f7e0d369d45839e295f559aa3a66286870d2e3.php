<?php $__env->startSection('content'); ?>
    <div class="row">
    <div class="col-md-6"><h3 class="page-title"><i class="fa fa-user-circle ifont"></i> <?php echo e($emp_general_info[0]->first_name); ?> <?php echo app('translator')->getFromJson('quickadmin.customtiming.title'); ?></h3></div>
    <div class="col-md-6 tright">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee_custom_timing_create')): ?>
    <p>
        <a href="<?php echo e(route('admin.employees_customtiming.create',[$emp_general_info[0]->id])); ?>" class="btn btn-success"><?php echo app('translator')->getFromJson('quickadmin.qa_add_new'); ?></a>
        
    </p>
    <?php endif; ?>
    </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo app('translator')->getFromJson('quickadmin.qa_list'); ?>
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped <?php echo e(count($customtiming) > 0 ? 'datatable' : ''); ?> <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee_custom_timing_delete')): ?> dt-select <?php endif; ?>">
                <thead>
                    <tr>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee_custom_timing_delete')): ?>
                            <th class="hide" style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        <?php endif; ?>

                        <th><?php echo app('translator')->getFromJson('quickadmin.customtiming.fields.date'); ?></th>
                        <th><?php echo app('translator')->getFromJson('quickadmin.customtiming.fields.start-time'); ?>  </th>
                        <th><?php echo app('translator')->getFromJson('quickadmin.customtiming.fields.finish-time'); ?>  </th>
                        <th>Availablity</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php if(count($customtiming) > 0): ?>
                        <?php $__currentLoopData = $customtiming; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr data-entry-id="<?php echo e($user->id); ?>">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('leave_delete')): ?>                                 
                                    <td  class="hide" ></td>
                                <?php endif; ?>
                                <td><?php echo e($user->date); ?></td>
                                <td><?php echo e($user->start_time); ?></td>
                                <td><?php echo e($user->end_time); ?></td>
                                <td><?php echo e($user->timing_type); ?></td>
                                
                                <td>
                                   
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee_custom_timing_edit')): ?>
                                 
                                   
                                   
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee_custom_timing_delete')): ?>
                                    
                                    <?php echo Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.employees_customtiming.destroy', $user->id])); ?>

                                    <?php echo Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')); ?>

                                    <?php echo Form::close(); ?>

                                   
                                    <?php endif; ?>
                                </td>
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