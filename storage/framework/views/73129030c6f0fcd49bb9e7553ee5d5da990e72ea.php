<?php $__env->startSection('content'); ?>
    <div class="row">
    <div class="col-md-6"><h3 class="page-title"><i class="fa fa-user-circle ifont"></i>  <?php echo app('translator')->getFromJson('quickadmin.rooms.title'); ?></h3></div>
    <div class="col-md-6 tright">
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user_create')): ?>
    <p>
        <a href="<?php echo e(route('admin.rooms.create')); ?>" class="btn btn-success"><?php echo app('translator')->getFromJson('quickadmin.qa_add_new'); ?></a>
        
    </p>
    <?php endif; ?>
    </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo app('translator')->getFromJson('quickadmin.qa_list'); ?>
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped <?php echo e(count($rooms) > 0 ? 'datatable' : ''); ?> <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('room_delete')): ?> dt-select <?php endif; ?>">
                <thead>
                    <tr>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('room_delete')): ?>
                            <th class="hide" style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        <?php endif; ?>

                        <th><?php echo app('translator')->getFromJson('quickadmin.rooms.fields.name'); ?></th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php if(count($rooms) > 0): ?>
                        <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr data-entry-id="<?php echo e($user->id); ?>">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('room_delete')): ?>                                 
                                    <td  class="hide" ></td>
                                <?php endif; ?>

                                <td><?php echo e($user->room_name); ?></td>
                                
                                <td>
                                   
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('room_edit')): ?>
                                    <?php if($user->id!=1): ?>
                                    <a href="<?php echo e(route('admin.rooms.edit',[$user->id])); ?>" class="btn btn-xs btn-info"><?php echo app('translator')->getFromJson('quickadmin.qa_edit'); ?></a>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('room_delete')): ?>
                                    <?php if($user->id!=1): ?>
                                    <?php echo Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.rooms.destroy', $user->id])); ?>

                                    <?php echo Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')); ?>

                                    <?php echo Form::close(); ?>

                                    <?php endif; ?>
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