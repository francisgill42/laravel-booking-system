<?php $__env->startSection('content'); ?>
<?php if($message = Session::get('error')): ?>
    <div class="col-sm-12">   
        <div class="note note-danger" role="alert">
          <?php echo e($message); ?>

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
        </div>
    </div>
<?php endif; ?>

    <h3 class="page-title"><i class="fa fa-user-circle ifont"></i> <?php echo app('translator')->getFromJson('quickadmin.leaves.title'); ?></h3>
    
 
    <?php echo Form::open(['method' => 'POST', 'route' => ['admin.employees_leaves.store']]); ?>


    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo app('translator')->getFromJson('quickadmin.qa_create'); ?>
        </div>
         
        <div class="panel-body">
           
            <div class="row">
                <div class="col-xs-6 form-group">
                     <input type="hidden" name="employee_id" value="<?php echo e($employee_id); ?>">
                    <?php echo Form::label('name', 'Leave Title*', ['class' => 'control-label']); ?>

                    <?php echo Form::text('leave_title', old('leave_title'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('name')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('name')); ?>

                        </p>
                    <?php endif; ?>
                </div> 
                 <div class="col-xs-6 form-group">
                    <?php echo Form::label('Comments', 'Leave Comments', ['class' => 'control-label']); ?>

                    <?php echo Form::text('leave_comment', old('leave_comment'), ['class' => 'form-control', 'placeholder' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('leave_comment')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('leave_comment')); ?>

                        </p>
                    <?php endif; ?>
                </div>
                <div class="col-xs-6 form-group">
                    <div class="col-xs-6 form-group">
                    <?php echo Form::label('date', 'Leave From Date*', ['class' => 'control-label']); ?>

                    <?php echo Form::text('leave_date', old('leave_date'), ['class' => 'form-control date ', 'placeholder' => '', 'required' => '','autocomplete'=>'off']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('leave_date')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('leave_date')); ?>

                        </p>
                    <?php endif; ?>
                   </div>
                   
                </div>
                  <div class="col-xs-6 form-group">
                    <div class="col-xs-6 form-group">
                    <?php echo Form::label('date', 'Leave To Date*', ['class' => 'control-label']); ?>

                    <?php echo Form::text('leave_to_date', old('leave_to_date'), ['class' => 'form-control date timepicker ', 'placeholder' => '', 'required' => '','autocomplete'=>'off']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('leave_to_date')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('leave_to_date')); ?>

                        </p>
                    <?php endif; ?>
                   </div>
                   
                </div>
            </div>
             

        </div>
    </div>

    <?php echo Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']); ?>

    <?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
    ##parent-placeholder-b6e13ad53d8ec41b034c49f131c64e99cf25207a##
    <script>
        $('.date').datepicker({
            autoclose: true,
            dateFormat: "<?php echo e(config('app.date_format_js')); ?>"
        });
    </script>
    <script src="<?php echo e(url('quickadmin/js')); ?>/timepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>    <script>
        $('.timepicker').datetimepicker({
            autoclose: true,
            timeFormat: "HH:mm:ss",
            timeOnly: true
        });
    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>