@extends('layouts.app')

@section('content')
    <div class="row">
    <div class="col-md-6"><h3 class="page-title"><i class="fa fa-user-circle ifont"></i>  @lang('quickadmin.rooms.title')</h3></div>
    <div class="col-md-6 tright">
    @can('user_create')
    <p>
        <a href="{{ route('admin.rooms.create') }}" class="btn btn-success">@lang('quickadmin.qa_add_new')</a>
        
    </p>
    @endcan
    </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.qa_list')
        </div>

        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped {{ count($rooms) > 0 ? 'datatable' : '' }} @can('room_delete') dt-select @endcan">
                <thead>
                    <tr>
                        @can('room_delete')
                            <th class="hide" style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        @endcan

                        <th>@lang('quickadmin.rooms.fields.name')</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($rooms) > 0)
                        @foreach ($rooms as $user)
                            <tr data-entry-id="{{ $user->id }}">
                                @can('room_delete')                                 
                                    <td  class="hide" ></td>
                                @endcan

                                <td>{{ $user->room_name }}</td>
                                
                                <td>
                                   
                                    @can('room_edit')
                                    @if($user->id!=1)
                                    <a href="{{ route('admin.rooms.edit',[$user->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.qa_edit')</a>
                                    @endif
                                    @endcan
                                    @can('room_delete')
                                    @if($user->id!=1)
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.rooms.destroy', $user->id])) !!}
                                    {!! Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                    @endif
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="9">@lang('quickadmin.qa_no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@stop
 