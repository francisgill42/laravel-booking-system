<style type="text/css">
    .select2 select2-container select2-container--default{
        width:400px !important;
    }

</style>
<?php $__env->startSection('content'); ?>
    <h3 class="page-title"><i class="fa fa-user ifont"></i>  <?php echo app('translator')->getFromJson('quickadmin.employees.title'); ?></h3>
    
    <?php echo Form::model($employee, ['method' => 'PUT', 'route' => ['admin.employees.update', $employee->id]]); ?>


    <div class="panel panel-default">
        <div class="panel-heading bold">
            <?php echo app('translator')->getFromJson('quickadmin.qa_edit'); ?>
        </div>

        <div class="panel-body">
              <div class="row">
              <ul class="nav nav-tabs">
                 <li class="active"><a data-toggle="tab" href="#general">General</a></li>
                 <li><a data-toggle="tab" href="#roomlocation">Preferred Rooms</a></li>
                 <li><a data-toggle="tab" href="#cost">Working Hours</a></li>
                
              </ul>
              <div class="tab-content">
                    <div id="general" class="tab-pane fade in active">
           
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('first_name', 'First name', ['class' => 'control-label']); ?>

                    <?php echo Form::text('first_name', old('first_name'), ['class' => 'form-control', 'placeholder' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('first_name')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('first_name')); ?>

                        </p>
                    <?php endif; ?>
                </div> 
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('last_name', 'Last name', ['class' => 'control-label']); ?>

                    <?php echo Form::text('last_name', old('last_name'), ['class' => 'form-control', 'placeholder' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('last_name')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('last_name')); ?>

                        </p>
                    <?php endif; ?>
                </div>
           
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('phone', 'Phone', ['class' => 'control-label']); ?>

                    <?php echo Form::text('phone', old('phone'), ['class' => 'form-control', 'placeholder' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('phone')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('phone')); ?>

                        </p>
                    <?php endif; ?>
                </div> 
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('email', 'Email', ['class' => 'control-label']); ?>

                    <?php echo Form::email('email', old('email'), ['class' => 'form-control', 'placeholder' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('email')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('email')); ?>

                        </p>
                    <?php endif; ?>
                </div>
                             
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('moneybird_key', 'Money Bird Key (Document Id)', ['class' => 'control-label']); ?>

                     <?php echo Form::text('moneybird_key', old('moneybird_key'), ['class' => 'form-control', 'placeholder' => '']); ?>


                    <p class="help-block"></p>
                    <?php if($errors->has('moneybird_key')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('moneybird_key')); ?>

                        </p>
                    <?php endif; ?>
                </div>
                 
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('registration_no', 'Registration no', ['class' => 'control-label']); ?>

                    
                    <?php echo Form::textarea('registration_no',old('registration_no'),['class'=>'form-control', 'rows' => 5, 'cols' => 20]); ?>

                    
                    
                  <p class="help-block"></p>
                    <?php if($errors->has('registration_no')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('registration_no')); ?>

                        </p>
                    <?php endif; ?>
                </div> 

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

               <div class="col-xs-6 form-group">
                    <?php echo Form::label('address', 'Address', ['class' => 'control-label']); ?>

                    <?php echo Form::textarea('address',old('address'),['class'=>'form-control', 'rows' => 5, 'cols' => 20]); ?>

            <p class="help-block"></p>
                    <?php if($errors->has('address')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('address')); ?>

                        </p>
                    <?php endif; ?>
                </div>
               
                 <div class="col-xs-6 form-group">
                    <?php echo Form::label('password', 'Password*', ['class' => 'control-label']); ?>

                    <?php echo Form::password('password', ['class' => 'form-control', 'placeholder' => '','id' => 'pass']); ?>

                    <span class="btn btn-danger" name='password' id='passwordgenerate'>Generate Password</span><span id='passwordshow'></span>
                    <p class="help-block"></p>
                    <?php if($errors->has('password')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('password')); ?>

                        </p>
                    <?php endif; ?>
                </div>  
            </div>
            <div id="roomlocation" class="tab-pane fade">
              <?php $i = 1; ?>
             
              <?php $__currentLoopData = $roomLoctions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $roomLoction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="row_<?php echo $i; ?> rows">
                    <div class="col-sm-12">
                   
                      <div class="col-xs-5 form-group" rel="<?php echo e($i); ?>">
                        <?php echo Form::label('location', 'Location*', ['class' => 'control-label ']); ?>

                        <?php echo Form::Select('location_id',$locations, $key, ['class' => 'form-control location newselect2', 'placeholder ' => '']); ?>

                        <p class="help-block"></p>
                        <?php if($errors->has('location')): ?>
                            <p class="help-block">
                                <?php echo e($errors->first('location')); ?>

                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="col-xs-5 form-group selectOp_<?php echo e($i); ?>" rel="<?php echo e($i); ?>">
                        <?php echo Form::label('room_id', 'Room*', ['class' => 'control-label']); ?>

           
                        <select name="room_id[]" class="form-control js-example-basic-multiple newselect2 optionAdd" multiple>
                            
                       
                          <?php $__currentLoopData = DB::table('rooms')->select('rooms.id','rooms.room_name')->leftjoin('employees_rooms',function ($join) use ($employeeId,$key) {                            
            $join->on('employees_rooms.room_id', '=', 'rooms.id')
                 ->where('employees_rooms.employee_id', '=', $employeeId)
                 ->where('employees_rooms.location_id', '=', $key);
                   })->where('rooms.deleted_at','=',NULL)->orderByRaw('IF(employees_rooms.orders IS NULL , 1 , 0)')->orderByRaw('employees_rooms.orders ASC')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                             <option value="<?php echo e($room->id); ?>_<?php echo e($key); ?>" <?php echo e(in_array($room->id,$roomLoction['room'])?'selected':''); ?>><?php echo e($room->room_name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                        </select>    
                        <p class="help-block"></p>
                        <?php if($errors->has('id')): ?>
                            <p class="help-block">
                                <?php echo e($errors->first('id')); ?>

                            </p>
                        <?php endif; ?> 
                    </div>

                        <?php if($i==1): ?>  
                         <div class="col-xs-2 form-group"><button type="button" name="add" id="add" class="btn btn-success add">Add</button></div>
                         <?php else: ?>
                          <div class="col-xs-2 form-group"><button type="button" name="remove" id="<?php echo e($i); ?>" class="btn btn-danger remove">Remove</button></div>  
                        <?php endif; ?>
                    </div>
                </div>
            
             <?php $i++; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
            
             
            </div>    
              <div id="cost" class="tab-pane fade">
                    <div class="col-sm-12">
                      <p> It will create Schedule for 7 Days From Today i.e (<?php echo e(date('d-m-Y')); ?> To <?php echo e(date('d-m-Y', strtotime('+7 days'))); ?> ) .</p>
                    </div>
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
    </div>

    <?php echo Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']); ?>

    <?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('javascript'); ?>
    ##parent-placeholder-b6e13ad53d8ec41b034c49f131c64e99cf25207a##
<script>
$(document).ready(function(){
 
 $('.newselect2').select2({tags: true});
 $('.select2').css('width','100%');
  //$(".newselect2").val(["Kamer H-108", "Ruimte H-006"]).trigger("change");
  //select2:opening
 function selectItem(target, id) { // refactored this a bit, don't pay attention to this being a function
  var option = $(target).children('[value='+id+']');
  option.detach();
  $(target).append(option).change();
} 

function customPreSelect() {
  let items = $('#selected_items').val().split(',');
  $("select").val('').change();
  initSelect(items);
}

function initSelect(items) { // pre-select items
  items.forEach(item => { // iterate through array of items that need to be pre-selected
    let value = $('select option[value='+item+']').text(); // get items inner text
    $('select option[value='+item+']').remove(); // remove current item from DOM
    $('select').append(new Option(value, item, true, true)); // append it, making it selected by default
  });
}


 

 $(".newselect2").on("select2:select", function (evt) {
          /*var element = evt.params.data.element;
        
          var $element = $(element);
          
          $element.detach();
          $(this).append($element);
          $(this).trigger("change");*/
          selectItem(evt.target, evt.params.data.id);
        });

var count = <?php echo e(count($roomLoctions)); ?>;
if(count==0)
 {dynamic_field_location(count);}

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
 function dynamic_field_location(number)
 {
  html = '<div class="row_'+number+' row"><div class="col-sm-12"><div class="col-xs-5 form-group" rel="'+number+'">';
        html += '<?php echo Form::label('location', 'Location*', ['class' => 'control-label ']); ?><?php echo Form::Select('emp_location_id[]',$locations, old('location_id'), ['class' => 'form-control location', 'placeholder newselect2' => '']); ?> </div>';
 html += '<div class="col-xs-5 form-group selectOp_'+number+'" rel="'+number+'"><?php echo Form::label('room_id', 'Room*', ['class' => 'control-label']); ?><?php echo Form::Select('room_id[]', $rooms, $employee->rooms, ['class' => 'form-control js-example-basic-multiple newselect2 optionAdd', 'required' => '','multiple'=>"multiple"]); ?> </div>';
         
        if(number > 1)
        {
            html += '<div class="col-xs-2 form-group"><button type="button" name="remove" id="'+number+'" class="btn btn-danger remove">Remove</button></div></div></div>';
            $('#roomlocation').append(html);
        }
        else
        {   
            html += '<div class="col-xs-2 form-group"><button type="button" name="add" id="add" class="btn btn-success add">Add</button></div></div></div>';
            //alert(html);
            $('#roomlocation').html(html);
       }
       $('.newselect2').select2({tags: true});
       $('.select2').css('width','100%');
   $(".newselect2").on("select2:select", function (evt) {
          var element = evt.params.data.element;
          var $element = $(element);
          
          $element.detach();
          $(this).append($element);
          $(this).trigger("change");
        });
       
 }

 function UpdateRoom(locationId,rowCount)
     {

         if(locationId != "") {
                $.ajax({
                    url: '<?php echo e(url("admin/get-rooms-location")); ?>',
                    type: 'GET',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {location_id:locationId},
                    success:function(option){
                        $('.selectOp_'+rowCount+' > select.optionAdd').html(option.html);
                        }   
                    });
            }
    }

 function dynamic_field(number)
 {

  html = '<tr>';
        html += '<td><?php echo Form::select('working_type', $working_type, old('working_type'), ['class' => 'form-control', 'required' => '','name'=>'working_type[]']); ?></td>';
        html += '<td><div class="row"> <div class="col-xs-5"><?php echo Form::time('booking_pricing_time_from', old('booking_pricing_time_from'), ['class' => 'form-control', 'placeholder' => '','name' => 'booking_pricing_time_from[]']); ?></div><div class="col-xs-1">To</div><div class="col-xs-5"><?php echo Form::time('booking_pricing_time_to', old('booking_pricing_time_to'), ['class' => 'form-control', 'placeholder' => '','name' => 'booking_pricing_time_to[]']); ?></div></div></td>';
         
        if(number > 1)
        {
            html += '<td><button type="button" name="remove" id="'+number+'" class="btn btn-danger remove">Remove</button></td></tr>';
            $('tbody').append(html);
        }
        else
        {   
            html += '<td></td></tr>';
            //alert(html);
            $('tbody').html(html);
       }
 }
$(document).on('change', '.location', function(){
      
    UpdateRoom($(this).val(),$(this).parent().attr('rel'));
 });

 $(document).on('click', '#add', function(){
  count++;
  dynamic_field_location(count);
 });

 $(document).on('click', '.remove', function(){
  count--;
  //$(this).closest(".row").remove();
  var IdVal = $(this).attr('id');
  // $(this).closest('div.row_'+IdVal).remove();
  //alert('.row_'+IdVal);
  $(this).parent().parent().remove();
 });

}); 
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>