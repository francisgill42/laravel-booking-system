<?php $__env->startSection('content'); ?>
    <h3 class="page-title"><i class="fa fa-users ifont"></i>  <?php echo app('translator')->getFromJson('quickadmin.clients.title'); ?></h3>
    <?php echo Form::open(['method' => 'POST', 'route' => ['admin.clients.store']]); ?>


    <div class="panel panel-default">
        <div class="panel-heading bold">
            <?php echo app('translator')->getFromJson('quickadmin.qa_create'); ?>
        </div>
        
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('first_name', 'First name*', ['class' => 'control-label']); ?>

                    <?php echo Form::text('first_name', old('first_name'), ['class' => 'form-control', 'placeholder' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('first_name')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('first_name')); ?>

                        </p>
                    <?php endif; ?>
                </div>
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('last_name', 'Last name*', ['class' => 'control-label']); ?>

                    <?php echo Form::text('last_name', old('last_name'), ['class' => 'form-control', 'placeholder' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('last_name')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('last_name')); ?>

                        </p>
                    <?php endif; ?>
                </div>
            </div> 
            <div class="row">  
             <div class="col-xs-6 form-group">
                    <?php echo Form::label('postcode', 'Postcode*', ['class' => 'control-label']); ?>

                    <?php echo Form::text('postcode', old('postcode'), ['class' => 'form-control', 'placeholder' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('postcode')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('postcode')); ?>

                        </p>
                    <?php endif; ?>
                </div>
            <div class="col-xs-6 form-group">
                    <?php echo Form::label('house_number', 'House Number', ['class' => 'control-label']); ?>

                    <?php echo Form::text('house_number', old('house_number'), ['class' => 'form-control', 'placeholder' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('house_number')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('house_number')); ?>

                        </p>
                    <?php endif; ?>
                </div>   
              
           </div> 
            <div class="row">
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('address', 'Address', ['class' => 'control-label']); ?>

                    <?php echo Form::text('address', old('address'), ['class' => 'form-control', 'placeholder' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('address')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('address')); ?>

                        </p>
                    <?php endif; ?>
                </div> 
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('city_name', 'City', ['class' => 'control-label']); ?>

                    <?php echo Form::text('city_name', old('city_name'), ['class' => 'form-control', 'placeholder' => '','id'=>'city_name']); ?>

            
                    <p class="help-block"></p>
                    <?php if($errors->has('city_name')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('city_name')); ?>

                        </p>
                    <?php endif; ?>
                </div>

                 
            </div>
            <div class="row">
                 <div class="col-xs-6 form-group">
                    <?php echo Form::label('Parent', 'Parent', ['class' => 'control-label']); ?>

                    <?php echo Form::Select('parent_id',$parentClient, old('parent_id'), ['class' => 'form-control select2 parent_id', 'placeholder ' => '']); ?>

                </div>

                 <div class="col-xs-6 form-group email">
                    <?php echo Form::label('email', 'Email', ['class' => 'control-label']); ?>

                    <?php echo Form::email('email', old('email'), ['class' => 'form-control input-group ', 'placeholder' => '']); ?>

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
                    <?php echo Form::label('Company', 'Company Name', ['class' => 'control-label']); ?>

                    <?php echo Form::text('company_name', old('company_name'), ['class' => 'form-control', 'placeholder' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('company_name')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('company_name')); ?>

                        </p>
                    <?php endif; ?>
                </div>
            

                <div class="col-xs-6 form-group">
                    <?php echo Form::label('phone', 'Phone*', ['class' => 'control-label']); ?>

                    <?php echo Form::text('phone', old('phone'), ['class' => 'form-control', 'placeholder' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('phone')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('phone')); ?>

                        </p>
                    <?php endif; ?>
                </div>
                
            </div>
             
           
             
            <div class="row">
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('password', 'Password*', ['class' => 'control-label']); ?>

                    <?php echo Form::password('password', ['class' => 'form-control', 'placeholder' => '', 'required' => '','id'=>'pass']); ?><span class="btn btn-danger" name='password' id='passwordgenerate'>Generate Password</span><span id='passwordshow'></span>
                    <p class="help-block"></p>
                    <?php if($errors->has('password')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('password')); ?>

                        </p>
                    <?php endif; ?>
                </div>
             
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('confirm_password', 'Confirm Password*', ['class' => 'control-label']); ?>

                    <?php echo Form::password('confirm_password', ['class' => 'form-control', 'placeholder' => '', 'required' => '','id'=>'confpass']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('confirm_password')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('confirm_password')); ?>

                        </p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('dob', 'DOB', ['class' => 'control-label']); ?>

                    <?php echo Form::text('dob', old('dob'), ['class' => 'form-control date', 'placeholder' => '']); ?>

                    <p class="help-block"></p>
                    <?php if($errors->has('dob')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('dob')); ?>

                        </p>
                    <?php endif; ?>
                </div>
               
                
                 <div class="col-xs-6 form-group">
                    <?php echo Form::label('comment', 'Comment', ['class' => 'control-label']); ?>

                    <?php echo Form::textarea('comment',old('comment'),['class'=>'form-control', 'rows' => 5, 'cols' => 20]); ?>

            <p class="help-block"></p>
                    <?php if($errors->has('comment')): ?>
                        <p class="help-block">
                            <?php echo e($errors->first('comment')); ?>

                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php echo Form::submit(trans('quickadmin.qa_save'), ['class' => 'btn btn-danger']); ?>

    <?php echo Form::close(); ?>

    
<?php $__env->stopSection(); ?>
<?php $__env->startSection('javascript'); ?>
    ##parent-placeholder-b6e13ad53d8ec41b034c49f131c64e99cf25207a##
    <script src="<?php echo e(url('quickadmin/js')); ?>/timepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
    <script>
        $(document).ready(function(){
           $("#passwordgenerate").trigger('click');
           $('.parent_id').on('change',function(){
           
              if($(this).val() > 0)
                 { $('.email').hide();}
              else
                 {$('.email').show();}

           })
        })
        $('.datetime').datetimepicker({
            autoclose: true,
            dateFormat: "<?php echo e(config('app.date_format_js')); ?>",
            timeFormat: "HH:mm:ss"
        });
   
        $('.date').datepicker({
            autoclose: true,
            dateFormat: "<?php echo e(config('app.date_format_js')); ?>"
        }).datepicker("setDate", "");
        
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
         $("#house_number").on("blur",function(){

                    $.ajax({
                    url: '<?php echo e(url("admin/get-autocomplete")); ?>',
                    type: 'GET',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: {text:$("#postcode").val(),house_number:$(this).val()},
                    success:function(option){
                        console.log(option);
                                var obj = $.parseJSON(option);
                               

                                if(obj.message=='success')
                                {
                                   $("#address").val(obj.address);
                                      
                                  $("#location_id option[rel='"+obj.city+"']").attr("selected","selected");

                                  $('#location_id option[rel="'+obj.city+'"]').prop('selected', true);
                                  $("#city_name").val(obj.city);
                                   //   $("#location").val(obj.address);
                                }
                        
                    }
                });

        })
        $("#postcode").on("blur",function(){
               if($("#house_number").val()!='')
                     {
                         $.ajax({
                            url: '<?php echo e(url("admin/get-autocomplete")); ?>',
                            type: 'GET',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data: {text:$(this).val(),house_number:$("#house_number").val()},
                            dataType: 'json',
                            success:function(option){
                                
                                var obj = jQuery.parseJSON(option);
                               
                                if(option.message=='success')
                                {
                                     $("#address").val(option.address);
                                     
                                  $("#location_id option[rel='"+obj.city+"']").attr("selected","selected");

                                  $('#location_id option[rel="'+obj.city+'"]').prop('selected', true);
                                  $("#city_name").val(obj.city);
                                }
                                /*$("#start_time").show();
                                $(".innerHtml").empty();
                                $(".innerHtml").html(option);
        */
                                
                            }
                        });
                     }

        })
    </script> 
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>