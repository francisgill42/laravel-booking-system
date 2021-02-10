<?php $__env->startSection('content'); ?>
    <h3 class="page-title"><i class="fa fa-user-circle ifont"></i>  <?php echo app('translator')->getFromJson('quickadmin.locations.title'); ?></h3>
    
    <?php echo Form::model($location, ['method' => 'PUT', 'route' => ['admin.locations.update', $location->id]]); ?>


    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo app('translator')->getFromJson('quickadmin.qa_edit'); ?>
        </div>

         <div class="panel-body">
            <div class="row">
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('name', 'Name*', ['class' => 'control-label']); ?>

                    <?php echo Form::text('location_name', old('location_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('name')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('name')); ?>

                        </p>
                    <?php endif; ?>
                </div> 
                  <div class="col-xs-6 form-group">
                    <?php echo Form::label('room_id', 'Room*', ['class' => 'control-label']); ?>

                    <?php echo Form::select('room_id[]', $rooms, $location->rooms, ['class' => 'form-control js-example-basic-multiple select2', 'required' => '','multiple'=>"multiple"]); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('roomn_id')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('room_id')); ?>

                        </p>
                    <?php endif; ?>
                </div> 
             </div> 
             <div class="row">  
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('location_address', 'Location Address', ['class' => 'control-label']); ?>

                    <?php echo Form::text('location_address', old('location_address'), ['class' => 'form-control', 'placeholder' => '']); ?>

                    
                </div>
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('location_description', 'Route Description*', ['class' => 'control-label']); ?>

                    <?php echo Form::textarea('location_description',old('location_description'),['class'=>'form-control', 'rows' => 4, 'cols' => 20]); ?>

            <p class="help-block"></p>
                    <?php if($errors->has('location_description')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('location_description')); ?>

                        </p>
                    <?php endif; ?>
                </div>
        </div>
    </div>

    <?php echo Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']); ?>

    <?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>