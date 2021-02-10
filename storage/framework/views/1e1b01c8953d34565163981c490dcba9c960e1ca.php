<?php $__env->startSection('content'); ?>
    <h3 class="page-title"><i class="fa fa-user-circle ifont"></i>  <?php echo app('translator')->getFromJson('quickadmin.users.title'); ?></h3>
    
    <?php echo Form::model($user, ['method' => 'PUT', 'route' => ['admin.users.update', $user->id]]); ?>


    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo app('translator')->getFromJson('quickadmin.qa_edit'); ?>
        </div>

         <div class="panel-body">
            <div class="row">
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('name', 'Name*', ['class' => 'control-label']); ?>

                    <?php echo Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('name')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('name')); ?>

                        </p>
                    <?php endif; ?>
                </div> 
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('email', 'Email*', ['class' => 'control-label']); ?>

                    <?php echo Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('email')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('email')); ?>

                        </p>
                    <?php endif; ?>
                </div>
            </div>
            
             <div class="row">
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('role_id', 'Role*', ['class' => 'control-label']); ?>

                    <?php echo Form::select('role_id', $roles, old('role_id'), ['class' => 'form-control', 'required' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('role_id')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('role_id')); ?>

                        </p>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

    <?php echo Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']); ?>

    <?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>