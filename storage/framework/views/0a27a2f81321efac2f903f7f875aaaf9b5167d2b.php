<?php $__env->startSection('content'); ?>
    <h3 class="page-title"><?php echo app('translator')->getFromJson('quickadmin.appointments.title'); ?></h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            <?php echo app('translator')->getFromJson('quickadmin.qa_view'); ?>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th><?php echo app('translator')->getFromJson('quickadmin.appointments.fields.client'); ?></th>
                            <td><?php echo e(isset($appointment->client->first_name) ? $appointment->client->first_name : ''); ?></td>
                        </tr>
                        <tr>
                            <th><?php echo app('translator')->getFromJson('quickadmin.clients.fields.last-name'); ?></th>
                            <td><?php echo e(isset($appointment->client) ? $appointment->client->last_name : ''); ?></td>
                        </tr>
                        <tr>
                            <th><?php echo app('translator')->getFromJson('quickadmin.clients.fields.phone'); ?></th>
                            <td><?php echo e(isset($appointment->client) ? $appointment->client->phone : ''); ?></td>
                        </tr>
                        <tr>
                            <th><?php echo app('translator')->getFromJson('quickadmin.clients.fields.email'); ?></th>
                            <td><?php echo e(isset($appointment->client) ? $appointment->client->email : ''); ?></td>
                        </tr>
                        <tr>
                            <th><?php echo app('translator')->getFromJson('quickadmin.appointments.fields.employee'); ?></th>
                            <td><?php echo e(isset($appointment->employee->first_name) ? $appointment->employee->first_name : ''); ?></td>
                        </tr>
                         <tr>
                            <th>Room No</th>
                            <td><?php echo isset($appointment->room) ? $appointment->room->room_name : ''; ?> </td>
                        </tr>
                        
                        <tr>
                            <th><?php echo app('translator')->getFromJson('quickadmin.employees.fields.last-name'); ?></th>
                            <td><?php echo e(isset($appointment->employee) ? $appointment->employee->last_name : ''); ?></td>
                        </tr>
                        <tr>
                            <th>Money Bird Username</th>
                            <td>
                            <textarea name="moneybird_username_show" class="moneybird_username" cols="10" rows="4">
                              <?php echo e(isset($employee_service[0]['moneybird_username']) ? trim($employee_service[0]['moneybird_username']) : ''); ?>


                            </textarea> 
                            </td>
                        </tr>
                       <?php
                      $Isgreater='';

                      if(strtotime($appointment->start_time) < strtotime(NOW()))
                           $Isgreater='Yes';
                      else
                         $Isgreater='No';
                   ?>
                     
                    <?php
                      if($Isgreater=='No' || $appointment->booking_status=='booking_paid')
                         $editable=0;
                      else
                         $editable=1;
                    ?> 
                     <?php if($Isgreater=='No' || $appointment->booking_status=='booking_paid'): ?> 
                        
                        <tr>
                           <input type="hidden" name="edittable1" class="edittable" value=0/>
                            <th><?php echo app('translator')->getFromJson('quickadmin.appointments.fields.start-time'); ?></th>
                            <td><?php echo e($appointment->start_time); ?></td>
                        </tr>
                        <tr>
                            <th><?php echo app('translator')->getFromJson('quickadmin.appointments.fields.finish-time'); ?></th>
                            <td><?php echo e($appointment->finish_time); ?></td>
                        </tr>
                      <?php else: ?>
                           
                        <tr>
                          <input type="hidden" name="edittable1" class="edittable" value=1/>
                            <th><?php echo app('translator')->getFromJson('quickadmin.appointments.fields.start-time'); ?></th>
                            <td>
                              <input type="date" name="sdate1" class="date form-control" value="<?php echo e(date('Y-m-d',strtotime($appointment->start_time))); ?>"/>
                            <input type="time" name="start_time1" class=" form-control start_time"  value="<?php echo e(date('H:i',strtotime($appointment->start_time))); ?>"/>
                     </td>
                        </tr>
                        <tr>
                            <th><?php echo app('translator')->getFromJson('quickadmin.appointments.fields.finish-time'); ?></th>
                            <td><input type="time" name="finish_time1" class="finish_time form-control" value="<?php echo e(date('H:i',strtotime($appointment->finish_time))); ?>"/></td>
                        </tr>
                        
                      <?php endif; ?> 
                        
                        
                        <tr>
                            <th><?php echo app('translator')->getFromJson('quickadmin.appointments.fields.comments'); ?></th>
                            <td><?php echo $appointment->comments; ?></td>
                        </tr>
                        <?php if($appointment->moneybird_id!=''): ?>
                        <tr>
                            <th>Invoice Link</th>
                            <td><a href="https://moneybird.com/218606266320159962/sales_invoices/<?php echo e($appointment->moneybird_id); ?>" target="_blank"><?php echo $appointment->moneybird_id; ?></a></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th>Price</th>
                             <?php if($appointment->booking_status==''): ?>
                              <td><input type="text" name='pricesend' value="<?php echo e($appointment->price); ?>" class="priceChange" /></td>
                             <?php else: ?>
                             <td><?php echo $appointment->price; ?></td>
                             <?php endif; ?>
                        </tr>
                        <tr>
                            <th>Extra Added </th>
                             
                              <td><input type="text" name='extra_price_comment' value="<?php echo e($appointment->extra_price_comment); ?>" class="form-control extra_price_comment" /></td>
                             
                        </tr>
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>
             <a href="<?php echo e(route('admin.appointments.index')); ?>" class="btn btn-default col-md-2"><?php echo app('translator')->getFromJson('quickadmin.qa_back_to_list'); ?></a>
           <?php if($appointment->booking_status==''): ?>
            <?php echo Form::open(['method' => 'POST', 'route' => ['admin.appointments.changeinvoicestatusp'],'class'=>'col-md-2','name'=>'changeinvoicestatus','id'=>'changeinvoicestatus']); ?> 
              <input type="hidden" name="appointment_id" value="<?php echo e($appointment->id); ?>">
              <input type="hidden" name="app_status" value="paid">
              <input type="hidden" name="extra_price_comment" class="extra_price_comment_paid">
              <input type="hidden" name="latestP" class="latest_paid" value="<?php echo e($appointment->price); ?>">
              <input type="hidden" name="edittable" class="edittablev" value="<?php echo e($editable); ?>" />
              <input type="hidden" name="finish_time" class="finish_timev"  value="<?php echo e(date('H:i',strtotime($appointment->finish_time))); ?>"/>
              <input type="hidden" name="start_time" class="start_timev"  value="<?php echo e(date('H:i',strtotime($appointment->start_time))); ?>"/>
              <input type="hidden" name="sdate" class="sdatev" value="<?php echo e(date('Y-m-d',strtotime($appointment->start_time))); ?>"/>

                            <input type="hidden" name="start_time1" class=" form-control start_time"  />
              <input type="hidden" name="moneybird_username" class="moneybirdusernamev" value=" <?php echo e(isset($employee_service[0]['moneybird_username']) ? trim($employee_service[0]['moneybird_username']) : ''); ?>" />
            
                <button name="appointment" class="btn btn-default col-md-12"><?php echo app('translator')->getFromJson('quickadmin.appointments.invoice_paid'); ?></button>
              <?php echo Form::close(); ?> 
            
            <?php echo Form::open(['method' => 'POST', 'route' => ['admin.appointments.changeinvoicestatusp'],'class'=>'col-md-2','name'=>'changeinvoicestatus','id'=>'changeinvoicestatus']); ?> 
              <input type="hidden" name="appointment_id" value="<?php echo e($appointment->id); ?>">
              <input type="hidden" name="app_status" value="unpaid">
              <input type="hidden" name="latestP" class="latest_unpaid" value="<?php echo e($appointment->price); ?>">

              <input type="hidden" name="extra_price_comment" class="extra_price_comment_unpaid">
              <input type="hidden" name="latestP" class="latest_unpaid" value="<?php echo e($appointment->price); ?>">
              <input type="hidden" name="edittable" class="edittablev" value="<?php echo e($editable); ?>" />
              <input type="hidden" name="finish_time" class="finish_timev"  value="<?php echo e(date('H:i',strtotime($appointment->finish_time))); ?>"/>
              <input type="hidden" name="start_time" class="start_timev"  value="<?php echo e(date('H:i',strtotime($appointment->start_time))); ?>"/>
              <input type="hidden" name="sdate" class="sdatev" value="<?php echo e(date('Y-m-d',strtotime($appointment->start_time))); ?>"/>
              <input type="hidden" name="moneybird_username" class="moneybirdusernamev" value=" <?php echo e(isset($employee_service[0]['moneybird_username']) ? trim($employee_service[0]['moneybird_username']) : ''); ?>"/>

                <button name="appointment" class="btn btn-default col-md-12"><?php echo app('translator')->getFromJson('quickadmin.appointments.invoice_unpaid'); ?></button>
              <?php echo Form::close(); ?>

    

           <?php echo Form::open(['method' => 'POST', 'route' => ['admin.appointments.changeinvoicestatusp'],'class'=>'col-md-2','name'=>'changeinvoicestatus','id'=>'changeinvoicestatus']); ?> 
              <input type="hidden" name="appointment_id" value="<?php echo e($appointment->id); ?>">
              <input type="hidden" name="app_status" value="cash_paid">
              <input type="hidden" name="latestP" class="latest_cashpaid" value="<?php echo e($appointment->price); ?>">
              <input type="hidden" name="extra_price_comment" class="extra_price_comment_cashpaid">
              <input type="hidden" name="edittable" class="edittablev" value="<?php echo e($editable); ?>" />
              <input type="hidden" name="finish_time" class="finish_timev"  value="<?php echo e(date('H:i',strtotime($appointment->finish_time))); ?>"/>
              <input type="hidden" name="start_time" class="start_timev"  value="<?php echo e(date('H:i',strtotime($appointment->start_time))); ?>"/>
              <input type="hidden" name="sdate" class="sdatev" value="<?php echo e(date('Y-m-d',strtotime($appointment->start_time))); ?>"/>
              <input type="hidden" name="moneybird_username" class="moneybirdusernamev" value=" <?php echo e(isset($employee_service[0]['moneybird_username']) ? trim($employee_service[0]['moneybird_username']) : ''); ?>"/>

                <button name="appointment" class="btn btn-default col-md-12">Cash Paid</button>
              <?php echo Form::close(); ?>



                       
           <?php endif; ?> 
            

            <?php echo Form::open(['method' => 'POST', 'route' => ['admin.appointments.send_custom_email'],'class'=>'col-md-3','name'=>'send_custom_email','id'=>'send_custom_email']); ?> 
              <input type="hidden" name="appointment_id" value="<?php echo e($appointment->id); ?>">
              <?php echo Form::select('email_templates', $email_templates, old('email_templates'), ['class' => 'form-control select2 col-md-12', 'required' => '','onchange'=>'function submitform()','id'=>'email_template']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('email_templates')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('email_templates')); ?>

                        </p>
                    <?php endif; ?>
           
            <?php echo Form::close(); ?>


            
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?>
    ##parent-placeholder-b6e13ad53d8ec41b034c49f131c64e99cf25207a##
    <script>
        $("#email_template").on("change", function() {
             $("#send_custom_email").submit();
        });
        $(".extra_price_comment").on("blur",function(){
             $('.extra_price_comment_paid').val($(this).val());
             $('.extra_price_comment_unpaid').val($(this).val());
             $('.extra_price_comment_cashpaid').val($(this).val());
        })
        $(".moneybird_username").on('blur',function(){
          $(".moneybirdusernamev").val($(this).val());
        })
        $(".finish_time").on('blur',function(){
          $(".finish_timev").val($(this).val());
        })
        $(".start_time").on('blur',function(){
          $(".start_timev").val($(this).val());
        })
        $(".date").on('change',function(){
          $(".sdatev").val($(this).val());
        })
        $(".priceChange").on("blur",function(){
             
                if((!$.isNumeric($(this).val())) || ($(this).val() == 0))
                  {
                      alert("Please enter proper value in Integer Only and grater then 0");
                     return false;   
                 }
                 
             $('.latest_unpaid').val($(this).val());
             $('.latest_paid').val($(this).val());
             $('.latest_cashpaid').val($(this).val());
        })
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