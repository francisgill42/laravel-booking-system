<?php $__env->startSection('content'); ?>
    <h3 class="page-title"><i class="fa fa-user-circle ifont"></i> <?php echo app('translator')->getFromJson('quickadmin.customtiming.title'); ?></h3>
    <?php echo Form::open(['method' => 'POST', 'route' => ['admin.employees_customtiming.store']]); ?>


    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo app('translator')->getFromJson('quickadmin.qa_create'); ?>
        </div>
        
        <div class="panel-body">
            <div class="row">
               <?php if(Session::has('flash_message')): ?>
    <div class="alert alert-block alert-warning">
        <i class=" fa fa-close cool-green "></i>
        <?php echo e(nl2br(Session::get('flash_message'))); ?>

    </div>
<?php endif; ?>
                
                 <div class="col-xs-6 form-group">
                    <input type="hidden" name="employee_id" value="<?php echo e($employee_id); ?>">
                    <?php echo Form::label('date', 'Date*', ['class' => 'control-label']); ?>

                    <?php echo Form::text('date', old('date'), ['class' => 'form-control date', 'placeholder' => '', 'required' => '','autocomplete'=>'off']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('date')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('date')); ?>

                        </p>
                    <?php endif; ?>
                </div>
               <div class="col-xs-6 form-group">
                    <?php echo Form::label('location', 'Location', ['class' => 'control-label']); ?>

                    <?php echo Form::Select('location_id',$locations, old('location_id'), ['class' => 'form-control', 'placeholder select2' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('location')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('location')); ?>

                        </p>
                    <?php endif; ?>
                </div>
                
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('start_time', 'Start time*', ['class' => 'control-label']); ?>

                    <?php echo Form::time('start_time', old('start_time'), ['class' => 'form-control ', 'placeholder' => '', 'required' => '','autocomplete'=>'off']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('start_time')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('start_time')); ?>

                        </p>
                    <?php endif; ?>
                </div>
            
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('finish_time', 'Finish time', ['class' => 'control-label']); ?>

                    <?php echo Form::time('end_time', old('end_time'), ['class' => 'form-control ', 'placeholder' => '' , 'required' => '','autocomplete'=>'off']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('finish_time')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('finish_time')); ?>

                        </p>
                    <?php endif; ?>
                </div>
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('Timing Type', 'Timing Type', ['class' => 'control-label']); ?>

                    <select name='timing_type' class="form-control">
                        <option value='available'>Available</option>
                        <option value='unavailable'>Unavailable</option>
                    </select>    
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