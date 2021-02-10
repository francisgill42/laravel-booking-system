@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.appointments.title')</h3>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_view')
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('quickadmin.appointments.fields.client')</th>
                            <td>{{ $appointment->client->first_name or '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.clients.fields.last-name')</th>
                            <td>{{ isset($appointment->client) ? $appointment->client->last_name : '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.clients.fields.phone')</th>
                            <td>{{ isset($appointment->client) ? $appointment->client->phone : '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.clients.fields.email')</th>
                            <td>{{ isset($appointment->client) ? $appointment->client->email : '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.appointments.fields.employee')</th>
                            <td>{{ $appointment->employee->first_name or '' }}</td>
                        </tr>
                         <tr>
                            <th>Room No</th>
                            <td>{!! isset($appointment->room) ? $appointment->room->room_name : '' !!} </td>
                        </tr>
                        
                        <tr>
                            <th>@lang('quickadmin.employees.fields.last-name')</th>
                            <td>{{ isset($appointment->employee) ? $appointment->employee->last_name : '' }}</td>
                        </tr>
                        <tr>
                            <th>Money Bird Username</th>
                            <td>
                            <textarea name="moneybird_username_show" class="moneybird_username" cols="10" rows="4">
                              {{ isset($employee_service[0]['moneybird_username']) ? trim($employee_service[0]['moneybird_username']) : '' }}

                            </textarea> 
                            </td>
                        </tr>
                       @php
                      $Isgreater='';

                      if(strtotime($appointment->start_time) < strtotime(NOW()))
                           $Isgreater='Yes';
                      else
                         $Isgreater='No';
                   @endphp
                     
                    @php
                      if($Isgreater=='No' || $appointment->booking_status=='booking_paid')
                         $editable=0;
                      else
                         $editable=1;
                    @endphp 
                     @if($Isgreater=='No' || $appointment->booking_status=='booking_paid') 
                        
                        <tr>
                           <input type="hidden" name="edittable1" class="edittable" value=0/>
                            <th>@lang('quickadmin.appointments.fields.start-time')</th>
                            <td>{{ $appointment->start_time }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.appointments.fields.finish-time')</th>
                            <td>{{ $appointment->finish_time }}</td>
                        </tr>
                      @else
                           
                        <tr>
                          <input type="hidden" name="edittable1" class="edittable" value=1/>
                            <th>@lang('quickadmin.appointments.fields.start-time')</th>
                            <td>
                              <input type="date" name="sdate1" class="date form-control" value="{{ date('Y-m-d',strtotime($appointment->start_time)) }}"/>
                            <input type="time" name="start_time1" class=" form-control start_time"  value="{{ date('H:i',strtotime($appointment->start_time)) }}"/>
                     </td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.appointments.fields.finish-time')</th>
                            <td><input type="time" name="finish_time1" class="finish_time form-control" value="{{ date('H:i',strtotime($appointment->finish_time)) }}"/></td>
                        </tr>
                        
                      @endif 
                        
                        
                        <tr>
                            <th>@lang('quickadmin.appointments.fields.comments')</th>
                            <td>{!! $appointment->comments !!}</td>
                        </tr>
                        @if($appointment->moneybird_id!='')
                        <tr>
                            <th>Invoice Link</th>
                            <td><a href="https://moneybird.com/218606266320159962/sales_invoices/{{ $appointment->moneybird_id }}" target="_blank">{!! $appointment->moneybird_id !!}</a></td>
                        </tr>
                        @endif
                        <tr>
                            <th>Price</th>
                             @if($appointment->booking_status=='')
                              <td><input type="text" name='pricesend' value="{{ $appointment->price }}" class="priceChange" /></td>
                             @else
                             <td>{!! $appointment->price !!}</td>
                             @endif
                        </tr>
                        <tr>
                            <th>Extra Added </th>
                             
                              <td><input type="text" name='extra_price_comment' value="{{ $appointment->extra_price_comment }}" class="form-control extra_price_comment" /></td>
                             
                        </tr>
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>
             <a href="{{ route('admin.appointments.index') }}" class="btn btn-default col-md-2">@lang('quickadmin.qa_back_to_list')</a>
           @if($appointment->booking_status=='')
            {!! Form::open(['method' => 'POST', 'route' => ['admin.appointments.changeinvoicestatusp'],'class'=>'col-md-2','name'=>'changeinvoicestatus','id'=>'changeinvoicestatus']) !!} 
              <input type="hidden" name="appointment_id" value="{{ $appointment->id  }}">
              <input type="hidden" name="app_status" value="paid">
              <input type="hidden" name="extra_price_comment" class="extra_price_comment_paid">
              <input type="hidden" name="latestP" class="latest_paid" value="{{ $appointment->price }}">
              <input type="hidden" name="edittable" class="edittablev" value="{{ $editable }}" />
              <input type="hidden" name="finish_time" class="finish_timev"  value="{{ date('H:i',strtotime($appointment->finish_time)) }}"/>
              <input type="hidden" name="start_time" class="start_timev"  value="{{ date('H:i',strtotime($appointment->start_time)) }}"/>
              <input type="hidden" name="sdate" class="sdatev" value="{{ date('Y-m-d',strtotime($appointment->start_time)) }}"/>

                            <input type="hidden" name="start_time1" class=" form-control start_time"  />
              <input type="hidden" name="moneybird_username" class="moneybirdusernamev" value=" {{ isset($employee_service[0]['moneybird_username']) ? trim($employee_service[0]['moneybird_username']) : '' }}" />
            {{-- <a href="{{ route('admin.appointments.changeinvoicestatus',[$appointment->id,'paid']) }}" class="btn btn-default"> --}}
                <button name="appointment" class="btn btn-default col-md-12">@lang('quickadmin.appointments.invoice_paid')</button>
              {!! Form::close() !!} 
            
            {!! Form::open(['method' => 'POST', 'route' => ['admin.appointments.changeinvoicestatusp'],'class'=>'col-md-2','name'=>'changeinvoicestatus','id'=>'changeinvoicestatus']) !!} 
              <input type="hidden" name="appointment_id" value="{{ $appointment->id  }}">
              <input type="hidden" name="app_status" value="unpaid">
              <input type="hidden" name="latestP" class="latest_unpaid" value="{{ $appointment->price }}">

              <input type="hidden" name="extra_price_comment" class="extra_price_comment_unpaid">
              <input type="hidden" name="latestP" class="latest_unpaid" value="{{ $appointment->price }}">
              <input type="hidden" name="edittable" class="edittablev" value="{{ $editable }}" />
              <input type="hidden" name="finish_time" class="finish_timev"  value="{{ date('H:i',strtotime($appointment->finish_time)) }}"/>
              <input type="hidden" name="start_time" class="start_timev"  value="{{ date('H:i',strtotime($appointment->start_time)) }}"/>
              <input type="hidden" name="sdate" class="sdatev" value="{{ date('Y-m-d',strtotime($appointment->start_time)) }}"/>
              <input type="hidden" name="moneybird_username" class="moneybirdusernamev" value=" {{ isset($employee_service[0]['moneybird_username']) ? trim($employee_service[0]['moneybird_username']) : '' }}"/>

                <button name="appointment" class="btn btn-default col-md-12">@lang('quickadmin.appointments.invoice_unpaid')</button>
              {!! Form::close() !!}
    {{--  <a href="{{ route('admin.appointments.changeinvoicestatus',[$appointment->id,'unpaid']) }}" class="btn btn-default">@lang('quickadmin.appointments.invoice_unpaid')</a> --}}

           {!! Form::open(['method' => 'POST', 'route' => ['admin.appointments.changeinvoicestatusp'],'class'=>'col-md-2','name'=>'changeinvoicestatus','id'=>'changeinvoicestatus']) !!} 
              <input type="hidden" name="appointment_id" value="{{ $appointment->id  }}">
              <input type="hidden" name="app_status" value="cash_paid">
              <input type="hidden" name="latestP" class="latest_cashpaid" value="{{ $appointment->price }}">
              <input type="hidden" name="extra_price_comment" class="extra_price_comment_cashpaid">
              <input type="hidden" name="edittable" class="edittablev" value="{{ $editable }}" />
              <input type="hidden" name="finish_time" class="finish_timev"  value="{{ date('H:i',strtotime($appointment->finish_time)) }}"/>
              <input type="hidden" name="start_time" class="start_timev"  value="{{ date('H:i',strtotime($appointment->start_time)) }}"/>
              <input type="hidden" name="sdate" class="sdatev" value="{{ date('Y-m-d',strtotime($appointment->start_time)) }}"/>
              <input type="hidden" name="moneybird_username" class="moneybirdusernamev" value=" {{ isset($employee_service[0]['moneybird_username']) ? trim($employee_service[0]['moneybird_username']) : '' }}"/>

                <button name="appointment" class="btn btn-default col-md-12">Cash Paid</button>
              {!! Form::close() !!}


            {{-- <a href="{{ route('admin.appointments.changeinvoicestatus',[$appointment->id,'cash_paid']) }}" class="btn btn-default">Cash Paid</a> --}}           
           @endif 
            

            {!! Form::open(['method' => 'POST', 'route' => ['admin.appointments.send_custom_email'],'class'=>'col-md-3','name'=>'send_custom_email','id'=>'send_custom_email']) !!} 
              <input type="hidden" name="appointment_id" value="{{ $appointment->id  }}">
              {!! Form::select('email_templates', $email_templates, old('email_templates'), ['class' => 'form-control select2 col-md-12', 'required' => '','onchange'=>'function submitform()','id'=>'email_template']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('email_templates'))
                        <p class="help-block">
                            {{ $errors->first('email_templates') }}
                        </p>
                    @endif
           
            {!! Form::close() !!}

            {{-- <a href="{{ route('admin.appointments.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a> --}}
        </div>
    </div>
@stop

@section('javascript')
    @parent
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
            dateFormat: "{{ config('app.date_format_js') }}"
        });
    </script>
    <script src="{{ url('quickadmin/js') }}/timepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>    <script>
        $('.timepicker').datetimepicker({
            autoclose: true,
            timeFormat: "HH:mm:ss",
            timeOnly: true
        });
    </script>
@stop    
