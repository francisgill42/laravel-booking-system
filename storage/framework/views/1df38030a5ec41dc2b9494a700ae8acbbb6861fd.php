<?php $__env->startSection('content'); ?>
    <h3 class="page-title"><i class="fa fa-user ifont"></i>  <?php echo app('translator')->getFromJson('quickadmin.employees-service.title'); ?></h3>
    <?php echo Form::open(['method' => 'POST', 'route' => ['admin.employees_services.store']]); ?>


    <div class="panel panel-default">
        <div class="panel-heading bold">
            <?php echo app('translator')->getFromJson('quickadmin.qa_create'); ?>
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('employee_id', 'Employee*', ['class' => 'control-label']); ?>

                    <?php echo Form::hidden('employee_id', $employee->id); ?>

                    <div class="form-control" readonly='readonly'><?php echo e($employee->first_name); ?> <?php echo e($employee->last_name); ?></div>  
                </div>
               <div class="col-xs-6 form-group">
                    <?php echo Form::label('service_id', 'Service*', ['class' => 'control-label']); ?>

                    <?php echo Form::select('service_id', $services, old('service_id'), ['class' => 'form-control', 'required' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('service_id')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('service_id')); ?>

                        </p>
                    <?php endif; ?>
                </div>  
                
            </div>
           
             <div class="row">
                 <div class="col-xs-6 form-group">
                    <?php echo Form::label('discount', 'Discount*', ['class' => 'control-label']); ?>

                    <?php echo Form::text('discount', old('discount'), ['class' => 'form-control', 'placeholder' => '']); ?>

                    <span>Only number of % </span>
                    <p class="help-block"></p>
                    <?php if($errors->has('discount')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('discount')); ?>

                        </p>
                    <?php endif; ?>
                </div> 
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('moneybird_username', 'MoneyBird Username*', ['class' => 'control-label']); ?>

                    <?php echo Form::text('moneybird_username', old('moneybird_username'), ['class' => 'form-control', 'placeholder' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('moneybird_username')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('moneybird_username')); ?>

                        </p>
                    <?php endif; ?>
                </div> 
            </div>
            
            
        </div>
    </div>
<a href="<?php echo e(route('admin.employees.services',[$employee->id])); ?>" class="btn btn-default"><?php echo app('translator')->getFromJson('quickadmin.qa_back_to_list'); ?></a>
    <?php echo Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']); ?>

    <?php echo Form::close(); ?>

    
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>