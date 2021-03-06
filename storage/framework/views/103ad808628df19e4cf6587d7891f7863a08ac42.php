<link rel="stylesheet" href="<?php echo e(url('quickadmin/css')); ?>/style.css"/>
<link rel="stylesheet" href="<?php echo e(url('quickadmin/css')); ?>/dateTimePicker.css"/>

<?php $__env->startSection('content'); ?>
    <h3 class="page-title"><?php echo app('translator')->getFromJson('quickadmin.appointments.title'); ?></h3>
    
    <?php echo Form::model($appointment, ['method' => 'PUT', 'route' => ['admin.appointments.update', $appointment->id]]); ?>

  
    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo app('translator')->getFromJson('quickadmin.qa_edit'); ?>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    <?php echo Form::label('client_id', 'Client*', ['class' => 'control-label']); ?>

                    <?php echo Form::select('client_id', $clients, old('client_id'), ['class' => 'form-control select2', 'required' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('client_id')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('client_id')); ?>

                        </p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    <?php echo Form::label('service_id', 'Service*', ['class' => 'control-label']); ?>

                     <?php echo Form::select('service_id', $services, old('service_id'), ['class' => 'form-control select2', 'required' => '']); ?>

                  
                    <p class="help-block"></p>
                    <?php if($errors->has('service_id')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('service_id')); ?>

                        </p>
                    <?php endif; ?>
                    <input type="hidden" id="price" value="0">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    <?php echo Form::label('location_id', 'Location*', ['class' => 'control-label']); ?>

                    <?php echo Form::select('location_id', $locations, old('location_id'), ['class' => 'form-control select2', 'required' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('location_id')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('location_id')); ?>

                        </p>
                    <?php endif; ?>
                </div>
            </div>


           

            <div class="row">
                <div class="col-xs-12 form-group">
                   <?php echo Form::label('date', 'Date*', ['class' => 'control-label']); ?>   
                  <div id="basic" data-toggle="calendar"></div>
                </div>
                
             </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    <input type="hidden" name="date" id="date" value="<?php echo e(date('Y-m-d',strtotime($appointment->start_time))); ?>"/>
                    
                </div>
            </div>
          <div class="row" id="start_time" style="display: none;">
                <div class="col-xs-12 form-group">
                    <?php echo Form::label('start_time', 'Start time*', ['class' => 'control-label']); ?>

                    <input type="hidden" name="starting_time" id="starting_time" class="form-control" value="<?php echo e(date('H:i',strtotime($appointment->start_time))); ?>"/>
                    <input type="hidden" name="appointment_time" id="appointment_time" class="form-control" value="<?php echo e(date('H:i',strtotime($appointment->start_time))); ?>" />
                    <div class="form-inline innerHtml">
                  
                    </div>
                </div>
            </div>
          
            <div class="row">
                <div class="col-xs-12 form-group">
                    <?php echo Form::label('Price', 'Price', ['class' => 'control-label']); ?>

                    <?php echo Form::text('price', old('price'), ['class' => 'form-control ', 'placeholder' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('price')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('price')); ?>

                        </p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 form-group">
                    <?php echo Form::label('comments', 'Comments', ['class' => 'control-label']); ?>

                    <?php echo Form::textarea('comments', old('comments'), ['class' => 'form-control ', 'placeholder' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('comments')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('comments')); ?>

                        </p>
                    <?php endif; ?>
                </div>
            </div>
            
        </div>
    </div>

    <?php echo Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']); ?>

    <?php echo Form::close(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
    ##parent-placeholder-b6e13ad53d8ec41b034c49f131c64e99cf25207a##
    <script src="<?php echo e(url('quickadmin/js')); ?>/timepicker.js"></script>
    <script src="<?php echo e(url('quickadmin/js')); ?>/dateTimePicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>    <script>
        $('.datetime').datetimepicker({
            autoclose: true,
            dateFormat: "<?php echo e(config('app.date_format_js')); ?>",
            timeFormat: "HH:mm:ss"
        });
  
        $('#basic').calendar(
    {

      onSelectDate: function(date, month, year)
        {

            var GivenDate =  year+"-"+month+"-"+date;
            var CurrentDate = new Date();
            GivenDate = new Date(GivenDate);
            var unselected=false;
            if(GivenDate >= CurrentDate){
            }else{
                alert('Given date is not greater than the current date.');
                unselected=true;
            }
           $("td.cur-month").each(function(){
                 if($(this).text() == date)
                   {  
                      if(!unselected)
                         {$(this).addClass('selectedDate')}
                    }
                 else
                   {$(this).removeClass('selectedDate')}
  
           })

            var service_id = $("#service_id").val();
            var location_id = $("#location_id").val();
            var date =  year+"-"+month+"-"+date;
            if(!unselected)
              {
                $("#date").val(date); 
                UpdateEmployees(service_id, date,location_id);
              }
  
         
        }

    });

    </script>
   <script>
   
   onloadChange();  
     function onloadChange()
      {
         
         setTimeout(function(){ $('#client_id').trigger('change'); }, 100);
      }
        $("#service_id").on("change", function() {
            $("#price").val($('option:selected', this).attr('data-price'));
            var date = $("#date").val();
            var service_id = $("#service_id").val();
            var location_id = $("#location_id").val();

            UpdateEmployees(service_id, date,location_id);
        });
    
        $("#client_id").on("change", function() {
            var client_id = $(this).val();
            UpdateLocation(client_id);
        });
      $("#location_id").on("change", function() {
        
            var date = $("#date").val();
            var service_id = $("#service_id").val();
            var location_id = $("#location_id").val();
            UpdateEmployees(service_id, date,location_id);
        });
      
        $("#date").change(function() {
            var service_id = $("#service_id").val();
            var date = $("#date").val();
            UpdateEmployees(service_id, date);
        });
        
        $("#starting_hour, #finish_hour, #starting_minute, #finish_minute").on("change", function () {
            CountPrice();       
        });

        $('body').on("change", "input[type=radio][name=employee_id]", function() {
            var employee_id = $(this).val();
            var date        = $("#date").val();
            var location_id = $("#location_id").val();
            var service_id  = $("#service_id").val();
            var appointment_time  = $("#appointment_time").val();
           
              if(service_id != "" && date != "" && location_id != "" && employee_id!="") {
                $.ajax({
                    url: '<?php echo e(url("admin/get-employees-edit-time-slot")); ?>',
                    type: 'GET',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {service_id:service_id, date:date,location_id:location_id,employee_id:employee_id,appointment_time:appointment_time},
                    success:function(option){
                        //alert(option);
                        $("#start_time").show();
                        $(".innerHtml").empty();
                        $(".innerHtml").html(option);

                        
                    }
                });
            }

           $("#start_time").on('click',".borderTimeing",function(e){
                            //alert($(".borderTimeing").length());
                             e.preventDefault();
                             $('.selectedDiv').not(this).removeClass('selectedDiv');
                                $(this).addClass('selectedDiv');
                               //$(this).addClass('selectedDiv');
                            
                               $("#starting_time").val($(this).text());

                        })
            /*var starting_hour = parseInt($(".starting_hour_"+employee_id).text());
            var starting_minute = $(".starting_minute_"+employee_id).text();
            var finish_hour = starting_hour+1;
            if(finish_hour < 10) {
                finish_hour = "0"+finish_hour;
            }
            if(starting_hour < 10) {
                starting_hour = "0"+starting_hour;
            }
            $('#starting_hour option[value='+starting_hour+']').prop('selected','true');
            $('#starting_minute option[value='+starting_minute+']').prop('selected','true');
            $('#finish_hour option[value='+finish_hour+']').prop('selected','true');
            $('#finish_minute option[value='+starting_minute+']').prop('selected','true');
            $("#start_time, #finish_time").show();
            CountPrice();*/
        });
        
        function CountPrice() {
            var start_hour = parseInt($("#starting_hour").val());
            var start_minutes = parseInt($("#starting_minute").val());
            var finish_hour = parseInt($("#finish_hour").val());
            var finish_minutes = parseInt($("#finish_minute").val());
            var total_hours = (((finish_hour*60+finish_minutes)-(start_hour*60+start_minutes))/60);
            var price = parseFloat($("#price").val());
            $("#price_total").html(price*total_hours);
            $("#time").html(total_hours);
            if(start_hour != -1 && start_minutes != -1 && finish_hour != -1 && finish_minutes != -1) {
                $("#results").show();
            }
        }

    function UpdateLocation(client_id)
     {

         if(client_id != "") {
                $.ajax({
                    url: '<?php echo e(url("admin/get-client-location")); ?>',
                    type: 'GET',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {client_id:client_id},
                    success:function(option){
                        if(!option['isappointment'])
                          {$('#location_id').val(option['location_id']).trigger('change');}
                       else
                         {
                         $('#location_id').val(option['location_id']).trigger('change');
                         
                           $('#service_id').val(option['service_id']).trigger('change');    

                        setTimeout(function(){
                        //alert("hello");
                        $("input[name=employee_id][value=" + option['employee_id'] + "]").prop('checked', true).trigger('change'); 
                        // $(".employee_id").val(option['employee_id']).prop("checked", true);
                        }, 300);
    
                        
                        
                        }   
                    //  $('#location_id').val(option);
                     }
                });
            }

     } 

        function UpdateEmployees(service_id, date,location_id)
        {
            if(service_id != "" && date != "" && location_id != "") {
                $.ajax({
                    url: '<?php echo e(url("admin/get-employees")); ?>',
                    type: 'GET',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {service_id:service_id, date:date,location_id:location_id},
                    success:function(option){
                        //alert(option);
                        $(".employees").remove();
                        $("#date").closest(".row").after(option);
                        $("#start_time, #finish_time").hide();
                        $("#results").hide();
                    }
                });
            }
        }
        
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>