 
  
<?php $__env->startSection('content'); ?>
    <h3 class="page-title"><i class="fa fa-hourglass ifont"></i> <?php echo app('translator')->getFromJson('quickadmin.services.title'); ?></h3>
    <?php echo Form::open(['method' => 'POST', 'route' => ['admin.services.store']]); ?>


    <div class="panel panel-default">
        <div class="panel-heading bold">
            <?php echo app('translator')->getFromJson('quickadmin.qa_create'); ?>
        </div>
        
        <div class="panel-body">
            <div class="row">
               <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#general">General</a></li>
                <li><a data-toggle="tab" href="#cost">Cost</a></li>
              </ul>

                <div class="tab-content">
                    <div id="general" class="tab-pane fade in active">
                        <div class="col-xs-12 form-group">
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
                            <?php echo Form::label('vat_status', 'VAT Status', ['class' => 'control-label']); ?>

                            <?php echo Form::select('vat_status', $vatstaus, old('vat_status'), ['class' => 'form-control', 'required' => '']); ?>

                            
                        </div>
                        <div class="col-xs-6 form-group">
                            <?php echo Form::label('tax_class', 'Tax Class', ['class' => 'control-label']); ?>

                          
                             <select id="tax_rate_id_moneybrid" name="tax_rate_id_moneybrid" class="form-control select2" required>
                                <option value="">Please select</option>
                                <?php $__currentLoopData = $taxrate; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $taxrates): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($taxrates->moneybrid_tax_id); ?>" <?php echo e((old("tax_rate_id_moneybrid") == $taxrates->moneybrid_tax_id ? "selected":"")); ?>><?php echo e($taxrates->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-xs-6 form-group">
                            <?php echo Form::label('booking_duration_block', 'Booking Duration Block time*', ['class' => 'control-label']); ?>

                            <?php echo Form::text('booking_block_duration', old('booking_block_duration'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']); ?>

                            
                        </div>
                        <div class="col-xs-6 form-group">
                            <?php echo Form::label('booking_duration_block_unit', 'Booking Duration Block Unit*', ['class' => 'control-label']); ?>

                             <?php echo Form::select('booking_block_duration_unit', $blockdurationunit, old('booking_block_duration_unit'), ['class' => 'form-control', 'required' => '']); ?>

                            
                        </div>
                        <div class="col-xs-6 form-group">
                            <?php echo Form::label('min_block_duration', 'Minimum duration*', ['class' => 'control-label']); ?>

                            <?php echo Form::text('min_block_duration', old('min_block_duration'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']); ?>

                            
                        </div>
                        <div class="col-xs-6 form-group">
                            <?php echo Form::label('max_block_duration', 'Maximum duration*', ['class' => 'control-label']); ?>

                            <?php echo Form::text('max_block_duration', old('max_block_duration'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']); ?>

                            
                        </div>
                        <div class="col-xs-6 form-group">
                    <?php echo Form::label('description', 'Description', ['class' => 'control-label']); ?>

                    <?php echo Form::textarea('description',old('description'),['class'=>'form-control', 'rows' => 5, 'cols' => 20]); ?>

            <p class="help-block"></p>
                    <?php if($errors->has('description')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('description')); ?>

                        </p>
                    <?php endif; ?>
                </div>
                    <div class="col-xs-6 form-group">
                        <?php echo Form::label('description2', 'Description 2', ['class' => 'control-label']); ?>

                        <?php echo Form::textarea('description_second',old('description_second'),['class'=>'form-control', 'rows' => 5, 'cols' => 20]); ?>

                <p class="help-block"></p>
                        <?php if($errors->has('description')): ?>
                            <p class="help-block">
                                <?php echo e($errors->first('description_second')); ?>

                            </p>
                        <?php endif; ?>
                    </div>
                    </div>
                    <div id="cost" class="tab-pane fade">
                        <div class="col-xs-6 form-group">
                            <?php echo Form::label('Basic costs', 'Basic costs', ['class' => 'control-label']); ?>

                            <?php echo Form::text('basic_cost', old('basic_cost'), ['class' => 'form-control', 'placeholder' => '']); ?>

                            <p class="help-block"></p>
                            <?php if($errors->has('basic_cost')): ?>
                                <p class="help-block">
                                    <?php echo e($errors->first('basic_cost')); ?>

                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="col-xs-6 form-group">
                            <?php echo Form::label('Block costs', 'Block costs*', ['class' => 'control-label']); ?>

                            <?php echo Form::text('block_cost', old('block_cost'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']); ?>

                            <p class="help-block"></p>
                            <?php if($errors->has('block_cost')): ?>
                                <p class="help-block">
                                    <?php echo e($errors->first('block_cost')); ?>

                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="col-xs-6 form-group">
                            <?php echo Form::label('Show costs', 'Show costs', ['class' => 'control-label']); ?>

                            <?php echo Form::text('show_cost', old('show_cost'), ['class' => 'form-control', 'placeholder' => '']); ?>

                            <p class="help-block"></p>
                            <?php if($errors->has('show_cost')): ?>
                                <p class="help-block">
                                    <?php echo e($errors->first('show_cost')); ?>

                                </p>
                            <?php endif; ?>
                        </div>
                       
                           <div class="col-xs-12 table-responsive ">
                                <table class="table table-bordered table-striped" id="user_table">
                                       <thead>
                                        <tr>
                                            <th width="20%">Series type </th>
                                            <th width="40%">Range</th>
                                            <th width="10%">Basic costs</th>
                                            <th width="10%">Block costs</th>
                                            <th width="10%">Remove</th>
                                        </tr>

                                       </thead>
                                       <tbody>

                                       </tbody>
                                </table>
                            </div>
                        
                       
                             <div class="col-xs-12 form-group"><button type="button" name="add" id="add" class="btn btn-success">Add Series Type</button></div>
                          
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
$(document).ready(function(){

 var count = 0;

 ///dynamic_field(count);

 function dynamic_field(number)
 {

  html = '<tr>';
        html += '<td><?php echo Form::select('booking_series_type', $bookingservicetype, old('booking_series_type'), ['class' => 'form-control', 'required' => '','name'=>'booking_series_type[]']); ?></td>';
        html += '<td><div class="row"> <div class="col-xs-5"><?php echo Form::time('booking_pricing_time_from', old('booking_pricing_time_from'), ['class' => 'form-control', 'placeholder' => '','name' => 'booking_pricing_time_from[]']); ?></div><div class="col-xs-1">To</div><div class="col-xs-5"><?php echo Form::time('booking_pricing_time_to', old('booking_pricing_time_to'), ['class' => 'form-control', 'placeholder' => '','name' => 'booking_pricing_time_to[]']); ?></div></div></td>';
         html += '<td>  <?php echo Form::select('booking_basic_cost_duration_type_unit', $basiccostdurationunit, old('booking_basic_cost_duration_type_unit'), ['class' => 'form-control','name'=>'booking_basic_cost_duration_type_unit[]']); ?><br/><?php echo Form::text('booking_basic_price', old('booking_basic_price'), ['class' => 'form-control', 'placeholder' => '','name' => 'booking_basic_price[]']); ?></td>';
          html += '<td> <?php echo Form::select('booking_block_cost_duration_type_unit', $blockcostdurationunit, old('booking_block_cost_duration_type_unit'), ['class' => 'form-control', 'required' => '','name'=>'booking_block_cost_duration_type_unit[]']); ?><br/><?php echo Form::text('booking_block_price', old('booking_block_price'), ['class' => 'form-control', 'placeholder' => '','name' => 'booking_block_price[]']); ?></td>';
       /* if(number > 1)
        {*/
            html += '<td><button type="button" name="remove" id="" class="btn btn-danger remove">Remove</button></td></tr>';
            $('tbody').append(html);
        /*}
        else
        {*/   
            //html += '<td></td></tr>';
            //alert(html);
           // $('tbody').html(html);
       // }
 }

 $(document).on('click', '#add', function(){
  count++;
  dynamic_field(count);
 });

 $(document).on('click', '.remove', function(){
  count--;
  $(this).closest("tr").remove();
 });

}); 
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>