<?php $__env->startSection('content'); ?>
    <h3 class="page-title"><i class="fa fa-user-circle ifont"></i>  <?php echo app('translator')->getFromJson('quickadmin.rooms.title'); ?></h3>
    
    <?php echo Form::model($room, ['method' => 'PUT', 'route' => ['admin.rooms.update', $room->id]]); ?>


    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo app('translator')->getFromJson('quickadmin.qa_edit'); ?>
        </div>

         <div class="panel-body">
            <div class="row">
                <ul class="nav nav-tabs">
                 <li class="active"><a data-toggle="tab" href="#general">General</a></li>
                 <li><a data-toggle="tab" href="#cost">Availabilities</a></li>
                
              </ul>
               <div class="tab-content">
                    <div id="general" class="tab-pane fade in active">
                        <div class="col-xs-6 form-group">
                            <?php echo Form::label('name', 'Name*', ['class' => 'control-label']); ?>

                            <?php echo Form::text('room_name', old('room_name'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']); ?>

                            <p class="help-block"></p>
                            <?php if($errors->has('name')): ?>
                                <p class="help-block">
                                    <?php echo e($errors->first('name')); ?>

                                </p>
                            <?php endif; ?>
                        </div> 
                    </div>
                    <div id="cost" class="tab-pane fade">
                    <div class="col-sm-12">
                     
                    </div>
                    <div class="col-xs-12 table-responsive ">
                                <table class="table table-bordered table-striped" id="user_table">
                                       <thead>
                                        <tr>
                                            <th width="10%">Days</th>
                                            <th width="40%">Availabilities</th>
                                           
                                        </tr>

                                       </thead>
                                       <tbody>
                                        <?php $__currentLoopData = $working_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $values): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                           <tr>
                                            <td>
                                                <input type="hidden" name='day[]' value="<?php echo e($values); ?>"> <?php echo e($values); ?> 


                                            </td>
                                            <td><div class="row"> <div class="col-xs-5"><?php echo Form::time('booking_pricing_time_from',
                                            isset($empworkinghHours[$values]['start_time']) ? $empworkinghHours[$values]['start_time']:''

                                            , ['class' => 'form-control', 'placeholder' => '','name' => 'booking_pricing_time_from['.$values.']']); ?></div><div class="col-xs-1">To</div>
                                                <div class="col-xs-5">
                                                   <?php echo Form::time('booking_pricing_time_to', isset($empworkinghHours[$values]['finish_time']) ? $empworkinghHours[$values]['finish_time'] : '', ['class' => 'form-control', 'placeholder' => '','name' => 'booking_pricing_time_to['.$values.']']); ?>

                                                </div></div>
                                            </td>
                                           
                                          
                                           </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                       </tbody>
                                </table>
                            </div>
                     </div> 

              </div>  
        </div>
    </div>

    <?php echo Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']); ?>

    <?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>