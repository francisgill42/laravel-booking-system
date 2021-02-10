@extends('layouts.app')

@section('content')
    <h3 class="page-title"><i class="fa fa-users ifont"></i>  @lang('quickadmin.clients.title')</h3>    
    {!! Form::model($client, ['method' => 'PUT', 'route' => ['admin.clients.update', $client->id]]) !!}

    <div class="panel panel-default">
        <div class="panel-heading bold">
            @lang('quickadmin.qa_edit')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-xs-6 form-group">
                    {!! Form::label('first_name', 'First name*', ['class' => 'control-label']) !!}
                    {!! Form::text('first_name', old('first_name'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('first_name'))
                        <p class="help-block">
                            {{ $errors->first('first_name') }}
                        </p>
                    @endif
                </div> 
                <div class="col-xs-6 form-group">
                    {!! Form::label('last_name', 'Last name*', ['class' => 'control-label']) !!}
                    {!! Form::text('last_name', old('last_name'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('last_name'))
                        <p class="help-block">
                            {{ $errors->first('last_name') }}
                        </p>
                    @endif
                </div>
            </div>
           
            <div class="row">
                <div class="col-xs-6 form-group">
                    {!! Form::label('postcode', 'Postcode*', ['class' => 'control-label']) !!}
                    {!! Form::text('postcode', old('postcode'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('postcode'))
                        <p class="help-block">
                            {{ $errors->first('postcode') }}
                        </p>
                    @endif
                </div> 
                <div class="col-xs-6 form-group">
                    {!! Form::label('phone', 'Phone*', ['class' => 'control-label']) !!}
                    {!! Form::text('phone', old('phone'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('phone'))
                        <p class="help-block">
                            {{ $errors->first('phone') }}
                        </p>
                    @endif
                </div>
                
            </div>
            <div class="row">
                 <div class="col-xs-6 form-group">
                    {!! Form::label('email', 'Email*', ['class' => 'control-label']) !!}
                    {!! Form::email('email', old('email'), ['class' => 'form-control input-group', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('email'))
                        <p class="help-block">
                            {{ $errors->first('email') }}
                        </p>
                    @endif
                </div> 

                 <div class="col-xs-6 form-group">
                    {!! Form::label('house_number', 'House Number', ['class' => 'control-label']) !!}
                    {!! Form::text('house_number', old('house_number'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('house_number'))
                        <p class="help-block">
                            {{ $errors->first('house_number') }}
                        </p>
                    @endif
                </div>
                
            </div> 
           <div class="row">     
            <div class="col-xs-6 form-group">
                    {!! Form::label('city_name', 'City', ['class' => 'control-label']) !!}
                    {!! Form::text('city_name', old('city_name'), ['class' => 'form-control', 'placeholder' => '']) !!}
            
                    <p class="help-block"></p>
                    @if($errors->has('city_name'))
                        <p class="help-block">
                            {{ $errors->first('city_name') }}
                        </p>
                    @endif
                </div>
               <div class="col-xs-6 form-group">
                    {!! Form::label('address', 'Address', ['class' => 'control-label']) !!}
                    {!! Form::text('address', old('address'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('address'))
                        <p class="help-block">
                            {{ $errors->first('address') }}
                        </p>
                    @endif
                </div> 
           </div> 
            <div class="row">
               
                <div class="col-xs-6 form-group">
                    {!! Form::label('Company', 'Company Name', ['class' => 'control-label']) !!}
                    {!! Form::text('company_name', old('company_name'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('company_name'))
                        <p class="help-block">
                            {{ $errors->first('company_name') }}
                        </p>
                    @endif
                </div>                 
                 <div class="col-xs-6 form-group">
                    {!! Form::label('dob', 'DOB', ['class' => 'control-label']) !!}
                    {!! Form::text('dob', old('dob'), ['class' => 'form-control date', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('dob'))
                        <p class="help-block">
                            {{ $errors->first('dob') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="row">
                <div class="col-xs-6 form-group">
                    {!! Form::label('password', 'Password*', ['class' => 'control-label']) !!}
                    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => '', 'required' => '','id'=>'pass']) !!}<span class="btn btn-danger" name='password' id='passwordgenerate'>Generate Password</span><span id='passwordshow'></span>
                    <p class="help-block"></p>
                    @if($errors->has('password'))
                        <p class="help-block">
                            {{ $errors->first('password') }}
                        </p>
                    @endif
                </div>
                <div class="col-xs-6 form-group">
                    {!! Form::label('location', 'Location*', ['class' => 'control-label']) !!}
                    {!! Form::Select('location_id',$locations, old('location_id'), ['class' => 'form-control', 'placeholder select2' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('location'))
                        <p class="help-block">
                            {{ $errors->first('location') }}
                        </p>
                    @endif
                </div>
               
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.qa_update'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop
@section('javascript')
    @parent
    <script src="{{ url('quickadmin/js') }}/timepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script><script>
        $('.datetime').datetimepicker({
            autoclose: true,
            dateFormat: "{{ config('app.date_format_js') }}",
            timeFormat: "HH:mm:ss"
        });
   
        $('.date').datepicker({
            autoclose: true,
            dateFormat: "{{ config('app.date_format_js') }}"
        }).datepicker("setDate", "");
     


        $("#passwordgenerate").on('click',function(){
            alert('hi')
            $.ajax({
                    url: '{{ url("admin/generatepassword") }}',
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
     
    </script> 
@stop

