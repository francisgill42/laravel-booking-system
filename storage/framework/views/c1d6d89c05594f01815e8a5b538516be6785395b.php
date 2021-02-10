<style type="text/css">
    .select2 select2-container select2-container--default{
        width:400px !important;
    }

</style>
<?php $__env->startSection('content'); ?>
    <h3 class="page-title"><i class="fa fa-user ifont"></i>  <?php echo app('translator')->getFromJson('quickadmin.employees.title'); ?></h3>
    
    <?php echo Form::model($employee, ['method' => 'PUT', 'route' => ['admin.employees.smallinfoupdate', $employee->id]]); ?>

     


    <div class="panel panel-default">
        <div class="panel-heading bold">
            Small Info Edit
        </div>

        <div class="panel-body">
              <div class="row">
              
              <div class="tab-content">
                  
                
               <div class="col-xs-6 form-group">
                    <?php echo Form::label('small_info', 'Small info', ['class' => 'control-label']); ?>

                    
                    <?php echo Form::textarea('small_info',old('small_info'),['class'=>'form-control', 'rows' => 1, 'cols' => 5]); ?>

                    
                    
                  <p class="help-block"></p>
                    <?php if($errors->has('registration_no')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('small_info')); ?>

                        </p>
                    <?php endif; ?>
                </div> 

                 
            </div>
              
        </div>
    </div>
</div>
    <?php echo Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']); ?>

    <?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('javascript'); ?>
    ##parent-placeholder-b6e13ad53d8ec41b034c49f131c64e99cf25207a##
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>