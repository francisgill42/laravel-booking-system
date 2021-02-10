<?php $__env->startSection('content'); ?>
    <h3 class="page-title"><i class="fa fa-user ifont"></i>  <?php echo app('translator')->getFromJson('quickadmin.employees.title'); ?></h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo app('translator')->getFromJson('quickadmin.qa_view'); ?>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th><?php echo app('translator')->getFromJson('quickadmin.employees.fields.first-name'); ?></th>
                            <td><?php echo e($employee->first_name); ?></td>
                        </tr>
                        <tr>
                            <th><?php echo app('translator')->getFromJson('quickadmin.employees.fields.last-name'); ?></th>
                            <td><?php echo e($employee->last_name); ?></td>
                        </tr>
                        <tr>
                            <th><?php echo app('translator')->getFromJson('quickadmin.employees.fields.phone'); ?></th>
                            <td><?php echo e($employee->phone); ?></td>
                        </tr>
                        <tr>
                            <th><?php echo app('translator')->getFromJson('quickadmin.employees.fields.email'); ?></th>
                            <td><?php echo e($employee->email); ?></td>
                        </tr>
                    </table>
                </div>
            </div><!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    
<li role="presentation" class="active"><a href="#workinghours" aria-controls="workinghours" role="tab" data-toggle="tab">Working hours</a></li>
<li role="presentation" class=""><a href="#appointments" aria-controls="appointments" role="tab" data-toggle="tab">Appointments</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    
<div role="tabpanel" class="tab-pane active" id="workinghours">
<table class="table table-bordered table-striped <?php echo e(count($working_hours) > 0 ? 'datatable' : ''); ?>">
    <thead>
        <tr>
            <th><?php echo app('translator')->getFromJson('quickadmin.working-hours.fields.employee'); ?></th>
                        <th><?php echo app('translator')->getFromJson('quickadmin.employees.fields.last-name'); ?></th>
                        <th><?php echo app('translator')->getFromJson('quickadmin.working-hours.fields.date'); ?></th>
                        <th><?php echo app('translator')->getFromJson('quickadmin.working-hours.fields.start-time'); ?></th>
                        <th><?php echo app('translator')->getFromJson('quickadmin.working-hours.fields.finish-time'); ?></th>
                        <th>&nbsp;</th>
        </tr>
    </thead>

    <tbody>
        <?php if(count($working_hours) > 0): ?>
            <?php $__currentLoopData = $working_hours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $working_hour): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr data-entry-id="<?php echo e($working_hour->id); ?>">
                    <td><?php echo e(isset($working_hour->employee->first_name) ? $working_hour->employee->first_name : ''); ?></td>
<td><?php echo e(isset($working_hour->employee) ? $working_hour->employee->last_name : ''); ?></td>
                                <td><?php echo e($working_hour->date); ?></td>
                                <td><?php echo e($working_hour->start_time); ?></td>
                                <td><?php echo e($working_hour->finish_time); ?></td>
                                <td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('working_hour_view')): ?>
                                    <a href="<?php echo e(route('admin.working_hours.show',[$working_hour->id])); ?>" class="btn btn-xs btn-primary"><?php echo app('translator')->getFromJson('quickadmin.qa_view'); ?></a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('working_hour_edit')): ?>
                                    <a href="<?php echo e(route('admin.working_hours.edit',[$working_hour->id])); ?>" class="btn btn-xs btn-info"><?php echo app('translator')->getFromJson('quickadmin.qa_edit'); ?></a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('working_hour_delete')): ?>
                                    <?php echo Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.working_hours.destroy', $working_hour->id])); ?>

                                    <?php echo Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')); ?>

                                    <?php echo Form::close(); ?>

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
<div role="tabpanel" class="tab-pane " id="appointments">
<table class="table table-bordered table-striped <?php echo e(count($appointments) > 0 ? 'datatable' : ''); ?>">
    <thead>
        <tr>
            <th><?php echo app('translator')->getFromJson('quickadmin.appointments.fields.client'); ?></th>
                        <th><?php echo app('translator')->getFromJson('quickadmin.clients.fields.last-name'); ?></th>
                        <th><?php echo app('translator')->getFromJson('quickadmin.clients.fields.phone'); ?></th>
                        <th><?php echo app('translator')->getFromJson('quickadmin.clients.fields.email'); ?></th>
                        <th><?php echo app('translator')->getFromJson('quickadmin.appointments.fields.employee'); ?></th>
                        <th><?php echo app('translator')->getFromJson('quickadmin.employees.fields.last-name'); ?></th>
                        <th><?php echo app('translator')->getFromJson('quickadmin.appointments.fields.start-time'); ?></th>
                        <th><?php echo app('translator')->getFromJson('quickadmin.appointments.fields.finish-time'); ?></th>
                        <th><?php echo app('translator')->getFromJson('quickadmin.appointments.fields.comments'); ?></th>
                        <th>&nbsp;</th>
        </tr>
    </thead>

    <tbody>
        <?php if(count($appointments) > 0): ?>
            <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr data-entry-id="<?php echo e($appointment->id); ?>">
                    <td><?php echo e(isset($appointment->client->first_name) ? $appointment->client->first_name : ''); ?></td>
<td><?php echo e(isset($appointment->client) ? $appointment->client->last_name : ''); ?></td>
<td><?php echo e(isset($appointment->client) ? $appointment->client->phone : ''); ?></td>
<td><?php echo e(isset($appointment->client) ? $appointment->client->email : ''); ?></td>
                                <td><?php echo e(isset($appointment->employee->first_name) ? $appointment->employee->first_name : ''); ?></td>
<td><?php echo e(isset($appointment->employee) ? $appointment->employee->last_name : ''); ?></td>
                                <td><?php echo e($appointment->start_time); ?></td>
                                <td><?php echo e($appointment->finish_time); ?></td>
                                <td><?php echo $appointment->comments; ?></td>
                                <td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('appointment_view')): ?>
                                    <a href="<?php echo e(route('admin.appointments.show',[$appointment->id])); ?>" class="btn btn-xs btn-primary"><?php echo app('translator')->getFromJson('quickadmin.qa_view'); ?></a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('appointment_edit')): ?>
                                    <a href="<?php echo e(route('admin.appointments.edit',[$appointment->id])); ?>" class="btn btn-xs btn-info"><?php echo app('translator')->getFromJson('quickadmin.qa_edit'); ?></a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('appointment_delete')): ?>
                                    <?php echo Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.appointments.destroy', $appointment->id])); ?>

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

            <p>&nbsp;</p>

            <a href="<?php echo e(route('admin.employees.index')); ?>" class="btn btn-default"><?php echo app('translator')->getFromJson('quickadmin.qa_back_to_list'); ?></a>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>