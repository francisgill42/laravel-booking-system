<style>
    .select2-container{width:100% !important;}
</style>    
<?php $__env->startSection('content'); ?>

    <h3 class="page-title">Working Hours</h3>
    <?php echo Form::open(['method' => 'POST', 'route' => ['admin.employees_working_hour.store']]); ?>


    <div class="panel panel-default">
        <div class="panel-body">
            <div class="row">
              
              <div class="tab-content">
              
                    <div class="col-sm-12">
                      <p> It will create Schedule for 1 year From Today i.e (<?php echo e(date('d-m-Y')); ?> To <?php echo e(date('d-m-Y', strtotime('+1 year'))); ?> ) .</p>
                    </div>
                    <input type="hidden" name="employee_id" value="<?php echo e($employee_id); ?>">
                    <div class="col-xs-12 table-responsive ">
                                <table class="table table-bordered table-striped" id="user_table">
                                       <thead>
                                        <tr>
                                            <th width="10%">Days</th>
                                            <th width="40%">Working Hours</th>
                                            <th width="30%">Repeated</th>
                                            <th width="20%">Location</th>
                                            
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
                                            <td>
                                                <div class="col-xs-12">
                                                <div class="col-xs-2"><?php echo Form::checkbox('repeated[]', old('repeated'),null,array('id' => $key,'class'=>'checkbox')); ?></div>
                                                <div class="col-xs-10">

                                                <?php echo Form::text('repeated_number', old('repeated_number'),['class' => 'form-control hide','style'=>'width:50%','placeholder' => '','placeholder'=>' Repeated Number','name' => 'repeated_number['.$values.']','id'=>'repeatedValue'.$key]); ?></div>
                                                 </div>
                                            </td>
                                            <td class="col-sm-12">
                                                <?php echo Form::select('working_location_id', $locations,isset($empworkinghHours[$values]['location_id']) ? $empworkinghHours[$values]['location_id'] : '' , ['class' => 'form-control col-sm-12 select2',  'name' => 'working_location_id['.$values.']']); ?>


                                            </td> 
                                          
                                           </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                       </tbody>
                                </table>
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

 var count = 1;
 
          
       
// dynamic_field(count);
$(".checkbox").on('click',function(){
    //alert($(this).attr('id'));
    if($(this).prop("checked") == true)
         {
           $("#repeatedValue"+$(this).attr('id')).removeClass('hide').slow();
         }
     else
       {
         $("#repeatedValue"+$(this).attr('id')).addClass('hide').slow();
       }    
})

$("#passwordgenerate").on('click',function(){
            $.ajax({
                    url: '<?php echo e(url("admin/generatepassword")); ?>',
                    type: 'GET',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {service_id:'1'},
                    success:function(option){
                        //alert(option);
                        $("#pass").val(option);
                        $("#confpass").val(option);
                        $("#passwordshow").html('&nbsp;'+option);

                        
                    }
                });
        })
    
            // page is now ready, initialize the calendar...
    
      




 function dynamic_field(number)
 {

  html = '<tr>';
        html += '<td><?php echo Form::select('working_type', $working_type, old('working_type'), ['class' => 'form-control', 'required' => '','name'=>'working_type[]']); ?></td>';
        html += '<td><div class="row"> <div class="col-xs-5"><?php echo Form::time('booking_pricing_time_from', old('booking_pricing_time_from'), ['class' => 'form-control', 'placeholder' => '','name' => 'booking_pricing_time_from[]']); ?></div><div class="col-xs-1">To</div><div class="col-xs-5"><?php echo Form::time('booking_pricing_time_to', old('booking_pricing_time_to'), ['class' => 'form-control', 'placeholder' => '','name' => 'booking_pricing_time_to[]']); ?></div></div></td>';
         
        if(number > 1)
        {
            html += '<td><button type="button" name="remove" id="" class="btn btn-danger remove">Remove</button></td></tr>';
            $('tbody').append(html);
        }
        else
        {   
            html += '<td></td></tr>';
            //alert(html);
            $('tbody').html(html);
       }
 }

 $(document).on('click', '#add', function(){
  count++;
  dynamic_field(count);
 });

 $(document).on('click', '.remove', function(){
  count--;
  $(this).closest("tr").remove();
 });
 $("#passwordgenerate").trigger('click');

}); 
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>