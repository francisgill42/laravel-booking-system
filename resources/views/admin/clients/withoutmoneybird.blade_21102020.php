@extends('layouts.app')

@section('content')
    <div class="row">
    <div class="col-md-6"><h3 class="page-title"><i class="fa fa-users ifont"></i>  With Out Moneybird</h3></div>
    <div class="col-md-12 ">
    @if(Session::has('msg'))
   <div class="alert alert-success">
        <ul>
            <li>{!! \Session::get('msg') !!}</li>
        </ul>
    </div>
@endif
    </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading bold">
            @lang('quickadmin.qa_list')
        </div>
        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($clients) > 0 ? 'datatable' : '' }} {{ count($clientsOther) > 0 ? 'datatable' : '' }} @can('client_delete') dt-select @endcan">
                <thead>
                    <tr>
                        @can('client_delete')
                            <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        @endcan

                        <th>@lang('quickadmin.clients.fields.first-name')</th>
                        <th>@lang('quickadmin.clients.fields.last-name')</th>
                        <th>@lang('quickadmin.clients.fields.phone')</th>
                        <th>@lang('quickadmin.clients.fields.email')</th>
                        <th>Created Date </th>
                        <th>Comment </th>
                        <th>Parent Name</th>
                        <th>Money Bird Contact Id</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($clients) > 0)
                        @foreach ($clients as $client)
                            <tr data-entry-id="{{ $client->id }}">
                                @can('client_delete')
                                    <td></td>
                                @endcan

                                <td>{{ $client->first_name }}</td>
                                <td>{{ $client->last_name }}</td>
                                <td>{{ $client->phone }}</td>
                                <td>{{ $client->email }}</td>
                                <td>{{ date('d-m-Y',strtotime($client->created_at)) }}</td>
                                <td>{{ $client->comment }}</td>
                                <td>{{ getParentDetails($client->parent_id) }}</td>

                                <td>{{ $client->moneybird_contact_id }}</td>
                                <td>
                                    @can('client_view')
                                    <a href="{{ route('admin.client.showwithoutmoneybird',[$client->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('client_edit')
                                    <a href="{{ route('admin.clients.editwithoutmoneybird',[$client->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    @can('client_delete')
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.clients.destroywithoutmoneybird', $client->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    @endif

                    @if (count($clientsOther) > 0)
                        @foreach ($clientsOther as $client)
                            <tr data-entry-id="{{ $client->id }}">
                                @can('client_delete')
                                    <td></td>
                                @endcan

                                <td>{{ $client->first_name }}</td>
                                <td>{{ $client->last_name }}</td>
                                <td>{{ $client->phone }}</td>
                                <td>{{ $client->email }}</td>
                                <td>{{ date('d-m-Y',strtotime($client->created_at)) }}</td>
                                <td>{{ $client->comment }}</td>
                                <td>{{ getParentDetails($client->parent_id) }}</td>

                                <td>{{ $client->moneybird_contact_id }}</td>
                                <td>
                                    @can('client_view')
                                    <a href="{{ route('admin.clients.show',[$client->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.qa_view')</a>
                                    @endcan
                                    @can('client_edit')
                                    <a href="{{ route('admin.clients.edit',[$client->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endcan
                                    @can('client_delete')
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.clients.destroy', $client->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                     @endif
                </tbody> 
            </table>
        </div>
    </div>
@stop

@section('javascript') 
    <script>
        @can('client_delete')
            window.route_mass_crud_entries_destroy = '{{ route('admin.clients.mass_destroy') }}';
        @endcan
       
        window.route_mass_send_email = '{{ route('admin.clients_mass_email_send') }}';
        window.email_templates = {!! $emailTemplate !!};
 </script>
@endsection