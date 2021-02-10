<?php $__env->startSection('content'); ?>
    <h3 class="page-title"><?php echo app('translator')->getFromJson('quickadmin.appointments.title'); ?></h3>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('oappointment_create')): ?>
        <p>
            <a href="<?php echo e(route('admin.opertorappointments.create')); ?>"
               class="btn btn-success"><?php echo app('translator')->getFromJson('quickadmin.qa_add_new'); ?></a>

        </p>
    <?php endif; ?>

    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />
   <div id='calendar'></div>

    <br />

    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo app('translator')->getFromJson('quickadmin.qa_list'); ?>
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped <?php echo e(count($appointments) > 0 ? 'datatable' : ''); ?> <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('appointment_delete')): ?> dt-select <?php endif; ?>">
                <thead>
                <tr>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('appointment_delete')): ?>
                        <th style="text-align:center;"><input type="checkbox" id="select-all"/></th>
                    <?php endif; ?>
                    <th>Status</th>
                    <th><?php echo app('translator')->getFromJson('quickadmin.appointments.fields.start-time'); ?></th>
                    <th><?php echo app('translator')->getFromJson('quickadmin.appointments.fields.finish-time'); ?></th>
                    <th>Price</th>
                    <th>Customer Name</th>
                    
                    <th><?php echo app('translator')->getFromJson('quickadmin.clients.fields.phone'); ?></th>
                    <th>Location</th>
                    
                    <th>Therapy Name</th>
                    <th>Therapist Name</th>
                    <th>Room No</th>
                    
                    
                    
                    
                    <th><?php echo app('translator')->getFromJson('quickadmin.appointments.fields.moneybird_status'); ?></th>
                    <th>Booking status</th>
                    
                    <th>&nbsp;</th>
                </tr>
                </thead>
              
                <tbody>
                <?php if(count($appointments) > 0): ?>
                    <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(isClientVerified($appointment->client_id)): ?>
                         <tr data-entry-id="<?php echo e($appointment->id); ?>">
                        <?php else: ?>
                          <tr data-entry-id="<?php echo e($appointment->id); ?>" style="background-color:red ">
                        <?php endif; ?>    
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('appointment_delete')): ?>
                                <td></td>
                            <?php endif; ?>
                            <td width="20%">
                             <?php if($appointment->booking_status == 'booking_confirmed' || empty($appointment->booking_status)): ?> 
                                 <!-- <select id="appointment_status" name="appointment_status" class="form-control select2 appointment_status" required rel="<?php echo e($appointment->id); ?>">
                                        <option value="">Please select</option>
                                        <?php $__currentLoopData = $booking_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $booking_statu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e($appointment->status == $key ? "selected":""); ?>><?php echo e($booking_statu); ?> </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select> --><?php echo e($appointment->booking_status); ?>

                                 <?php else: ?>
                                 <?php echo $appointment->booking_status; ?>

                             <?php endif; ?>    

                            </td>
                            <td><?php echo e(date('d M Y H:i',strtotime($appointment->start_time))); ?></td>
                            <td><?php echo e(date('d M Y H:i',strtotime($appointment->finish_time))); ?></td>
                            <td>€ <?php echo e($appointment->price); ?></td>
                            
                            <td><?php echo e(isset($appointment->client->first_name) ? $appointment->client->first_name : ''); ?> <?php echo e(isset($appointment->client) ? $appointment->client->last_name : ''); ?></td>
                           
                            <td><?php echo e(isset($appointment->client) ? $appointment->client->phone : ''); ?></td>
                            
                            <td> <?php echo e(isset($appointment->location->location_name) ? $appointment->location->location_name : ''); ?> </td>
                            <td> <?php echo e(isset($appointment->service->name) ? $appointment->service->name :''); ?></td>
                            <td><?php echo e(isset($appointment->employee->first_name) ? $appointment->employee->first_name : ''); ?> <?php echo e(isset($appointment->employee) ? $appointment->employee->last_name : ''); ?></td>
                           
                            
                            
                            <td><?php echo isset($appointment->room) ? $appointment->room->room_name : ''; ?> </td>
                            <td><?php echo $appointment->booking_status; ?></td>
                            
                            <td><?php echo $appointment->status; ?></td>
                            <td>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('appointment_view')): ?>
                                    <a href="<?php echo e(route('admin.appointments.show',[$appointment->id])); ?>"
                                       class="btn btn-xs btn-primary"><?php echo app('translator')->getFromJson('quickadmin.qa_view'); ?></a>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('appointment_edit')): ?>
                                <?php if($appointment->booking_status !='booking_paid' && $appointment->booking_status !='cash_paid'  && $appointment->booking_status !='booking_unpaid'): ?>
                                    <!-- <a href="<?php echo e(route('admin.opertorappointments.edit',[$appointment->id])); ?>"
                                       class="btn btn-xs btn-info"><?php echo app('translator')->getFromJson('quickadmin.qa_edit'); ?></a> -->
                                 <?php endif; ?>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('appointment_delete')): ?>
                                    <?php echo Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.appointments.destroy', $appointment->id])); ?>

                                    <?php echo Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')); ?>

                                    <?php echo Form::close(); ?>

                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9"><?php echo app('translator')->getFromJson('quickadmin.qa_no_entries_in_table'); ?></td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
    <script>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('appointment_delete')): ?>
            window.route_mass_crud_entries_destroy = '<?php echo e(route('admin.appointments.mass_destroy')); ?>';
        <?php endif; ?>

    </script>
     <script src="<?php echo e(url('quickadmin/js')); ?>/timepicker.js"></script>
      <script>
        $('.datetime').datetimepicker({
            autoclose: true,
            dateFormat: "<?php echo e(config('app.date_format_js')); ?>",
            timeFormat: "HH:mm:ss"
        });
    </script>
    <script>
  $(".appointment_status").on('change',function(){
    var appointment_status = $(this).val();
    var appointment_id = $(this).attr('rel');
    if(appointment_id != 0  && appointment_status!=0 )
     {

if(appointment_status=='booking_unpaid') 
        {
          
          window.location.href="appointments/changeinvoicestatus/"+appointment_id+"/unpaid";
          
        }
        else if(appointment_status=='booking_paid') 
        {
          window.location.href="appointments/changeinvoicestatus/"+appointment_id+"/paid";
         
        } 
         else if(appointment_status=='cash_paid') 
        {
          window.location.href="appointments/changeinvoicestatus/"+appointment_id+"/cash_paid";
         
        }  
        else
        {
              $.ajax({
                    url: '<?php echo e(url("admin/update-appointment-status")); ?>',
                    type: 'GET',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {appointment_id:appointment_id, appointment_status:appointment_status},
                    success:function(option){
                      
                        location.reload();

                    }
                });  
        } 
     }
      
  })
      

    $('.date').datepicker({
        autoclose: true,
        dateFormat: "<?php echo e(config('app.date_format_js')); ?>"
    }).datepicker("setDate", "0");
    </script>
    <script>
        $("#service_id").on("change", function() {
            $("#price").val($('option:selected', this).attr('data-price'));
            var date = $("#date").val();
            var service_id = $("#service_id").val();
            UpdateEmployees(service_id, date);
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
            var starting_hour = parseInt($(".starting_hour_"+employee_id).text());
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
            CountPrice();
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
        
        function UpdateEmployees(service_id, date)
        {
            if(service_id != "" && date != "") {
                $.ajax({
                    url: '<?php echo e(url("admin/get-employees")); ?>',
                    type: 'GET',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {service_id:service_id, date:date},
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

    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
    <script>
        $(document).ready(function() {
           
            // page is now ready, initialize the calendar...
            $('#calendar').fullCalendar({
                // put your options and callbacks here

                  header: {
      left: 'prev,next today',
      center: 'title',
       right: 'month,basicWeek,basicDay'
    },
     dayClick: function(date, jsEvent, view) {

     if (moment().format('YYYY-MM-DD') === date.format('YYYY-MM-DD') || date.isAfter(moment())) {
        // This allows today and future date
            $(".dateshow").val(date.format())
                //alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
                //alert('Current view: ' + view.name);
           $('#calendarModal').modal();
             //$(this).css('background-color', 'red');
            } else {
                // Else part is for past dates
              }

      },events : [
                        <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    {
                        title : '<?php echo e($appointment->client->first_name . ' ' . $appointment->client->last_name); ?>',
                        start : '<?php echo e($appointment->start_time); ?>',
                        <?php if($appointment->finish_time): ?>
                                end: '<?php echo e($appointment->finish_time); ?>',
                        <?php endif; ?>
                        url : '<?php echo e(route('admin.appointments.edit', $appointment->id)); ?>'
                    },
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                ]
            })
             // once ajax done we need to refersh events
           // $('#calendar').fullCalendar( 'refetchEvents' );
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>