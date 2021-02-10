<?php $__env->startSection('content'); ?>
    <div class="row">
    <div class="col-md-6"><h3 class="page-title"><i class="fa fa-user ifont"></i>  <?php echo app('translator')->getFromJson('quickadmin.employees-service.title'); ?></h3></div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading bold">
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
                <div class="col-md-6 tright">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee_service_create')): ?>
                    <p>
                        <a href="<?php echo e(route('admin.employees_services.create',[$employee->id])); ?>" class="btn btn-success"><?php echo app('translator')->getFromJson('quickadmin.qa_add_new'); ?></a>
                        
                    </p>
                    <?php endif; ?>
                </div>
            </div><!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    
<li role="presentation" class="active"><a href="#services" aria-controls="services" role="tab" data-toggle="tab">Services</a></li> 
</ul>
<!-- Tab panes -->
<div class="tab-content">    
<div role="tabpanel" class="tab-pane active" id="services">
<table class="table table-bordered table-striped <?php echo e(count($employee_services) > 0 ? 'datatable' : ''); ?>">
    <thead>
        <tr>
            
            <th><?php echo app('translator')->getFromJson('quickadmin.employees-service.fields.service'); ?></th>
            <th>Moneybird User</th>
            
            <th><?php echo app('translator')->getFromJson('quickadmin.employees-service.fields.discount'); ?></th>
            

            <th>&nbsp;</th>
        </tr>
    </thead>

    <tbody>
        
        <?php if(count($employee_services) > 0): ?>
            <?php $__currentLoopData = $employee_services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>  
                <tr data-entry-id="<?php echo e($service->id); ?>">
                                       

                    <td><?php echo e($service->services->name); ?> </td>
                    <td><?php echo e($service->moneybird_username); ?></td>
                  
                    <td><?php echo e($service->discount); ?></td>
                    
                    <td> 
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee_service_edit')): ?>
                        <a href="<?php echo e(route('admin.employees_services.edit',[$service->id])); ?>" class="btn btn-xs btn-info"><?php echo app('translator')->getFromJson('quickadmin.qa_edit'); ?></a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee_service_delete')): ?>
                        <?php echo Form::open(array(
                            'style' => 'display: inline-block;',
                            'method' => 'DELETE',
                            'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                            'route' => ['admin.employees_services.destroy', $service->id])); ?>

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
 
</div>

            <p>&nbsp;</p>

            <a href="<?php echo e(route('admin.employees.index')); ?>" class="btn btn-default"><?php echo app('translator')->getFromJson('quickadmin.qa_back_to_list'); ?></a>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>