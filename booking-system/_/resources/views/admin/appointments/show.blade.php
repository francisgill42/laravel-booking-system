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
                            <td>{{ isset($employee_service[0]['moneybird_username']) ? $employee_service[0]['moneybird_username'] : '' }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.appointments.fields.start-time')</th>
                            <td>{{ $appointment->start_time }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.appointments.fields.finish-time')</th>
                            <td>{{ $appointment->finish_time }}</td>
                        </tr>
                        <tr>
                            <th>@lang('quickadmin.appointments.fields.comments')</th>
                            <td>{!! $appointment->comments !!}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <p>&nbsp;</p>

            <a href="{{ route('admin.appointments.changeinvoicestatus',[$appointment->id,'paid']) }}" class="btn btn-default">
            @lang('quickadmin.appointments.invoice_paid')</a>
            <a href="{{ route('admin.appointments.changeinvoicestatus',[$appointment->id,'unpaid']) }}" class="btn btn-default">@lang('quickadmin.appointments.invoice_unpaid')</a>
            <a href="{{ route('admin.appointments.index') }}" class="btn btn-default">@lang('quickadmin.qa_back_to_list')</a>
            {!! Form::open(['method' => 'POST', 'route' => ['admin.appointments.send_custom_email'],'class'=>'col-md-4','name'=>'send_custom_email','id'=>'send_custom_email']) !!} 
              <input type="hidden" name="appointment_id" value="{{ $appointment->id  }}">
              {!! Form::select('email_templates', $email_templates, old('email_templates'), ['class' => 'form-control select2', 'required' => '','onchange'=>'function submitform()','id'=>'email_template']) !!}
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
    </script>
@stop    