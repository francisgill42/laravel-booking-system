@inject('request', 'Illuminate\Http\Request')
<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu"
            data-keep-expanded="false"
            data-auto-scroll="true"
            data-slide-speed="200">
            
            <li class="{{ $request->segment(1) == 'home' ? 'active' : '' }}">
                <a href="{{ url('/') }}">
                    <i class="fa fa-home"></i>
                    <span class="title">@lang('quickadmin.qa_dashboard')</span>
                </a>
            </li>
            
             @can('user_access')
            <li class="{{ $request->segment(2) == 'users' ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}">
                    <i class="fa fa-user-circle"></i>
                    <span class="title">@lang('quickadmin.users.title')</span>
                </a>
            </li>
            @endcan
            
             @can('availability_access')
            <li class="{{ $request->segment(2) == 'availability' ? 'active' : '' }}">
                <a href="{{ route('admin.availability.index') }}">
                    <i class="fa fa-user-circle"></i>
                    <span class="title">Therapist Availability</span>
                </a>
            </li>
            @endcan

            @can('client_access')
            <li class="{{ $request->segment(2) == 'clients' ? 'active' : '' }}">
                <a href="{{ route('admin.clients.index') }}">
                    <i class="fa fa-users"></i>
                    <span class="title">@lang('quickadmin.clients.title')</span>
                </a>
            </li>
            @endcan
            
           @can('calandar_access')
            <li class="{{ $request->segment(2) == 'calandar' ? 'active' : '' }}">
                <a href="{{ route('admin.calandar.index') }}">
                    <i class="fa fa-calendar"></i>
                    <span class="title">Calandar</span>
                </a>
            </li>
            @endcan
            

            @can('employee_access')
            <li class="{{ $request->segment(2) == 'employees' ? 'active' : '' }}">
                <a href="{{ route('admin.employees.index') }}">
                    <i class="fa fa-user"></i>
                    <span class="title">@lang('quickadmin.employees.title')</span>
                </a>
            </li>
            @endcan
            
            @can('service_access')
            <li class="{{ $request->segment(2) == 'services' ? 'active' : '' }}">
                <a href="{{ route('admin.services.index') }}">
                    <i class="fa fa-hourglass"></i>
                    <span class="title">@lang('quickadmin.services.title')</span>
                </a>
            </li>
            @endcan	
            
            @can('room_access')
            <li class="{{ $request->segment(2) == 'rooms' ? 'active' : '' }}">
                <a href="{{ route('admin.rooms.index') }}">
                    <i class="fa fa-hourglass"></i>
                    <span class="title">@lang('quickadmin.rooms.title')</span>
                </a>
            </li>
            @endcan 

             @can('location_access')
            <li class="{{ $request->segment(2) == 'locations' ? 'active' : '' }}">
                <a href="{{ route('admin.locations.index') }}">
                    <i class="fa fa-hourglass"></i>
                    <span class="title">@lang('quickadmin.locations.title')</span>
                </a>
            </li>
            @endcan 

             @can('working_hour_access')
            {{-- <li class="{{ $request->segment(2) == 'working_hours' ? 'active' : '' }}">
                <a href="{{ route('admin.working_hours.index') }}">
                    <i class="fa fa-hourglass"></i>
                    <span class="title">@lang('quickadmin.working-hours.title')</span>
                </a>
            </li> --}}
            @endcan
            @can('thrapist_working_hour_create')
             <li class="{{ $request->segment(2) == 'employees_working_hour' ? 'active' : '' }}">
                <a href="{{ route('admin.employees_working_hour.create',getEmployeeId(Auth::user()->id)) }}">
                    <i class="fa fa-hourglass"></i>
                    <span class="title">Working Hours</span>
                </a>
            </li> 
            @endcan
            @can('leave_access')
             
                @if(Auth::user()->role_id == 3)
                    <li class="{{ $request->segment(2) == 'leave' ? 'active' : '' }}">
                        <a href="{{ route('admin.leave.leavelist',getEmployeeId(Auth::user()->id)) }}">
                            <i class="fa fa-calendar"></i>
                            <span class="title">Leaves</span>
                        </a>
                   </li>
                
             @can('employee_custom_timing_access')
                                    
                                     <li class="{{ $request->segment(2) == 'employeecustomtiming' ? 'active' : '' }}">
                        <a href="{{ route('admin.employeecustomtiming.employeecustomtiminglist',getEmployeeId(Auth::user()->id)) }}">
                            <i class="fa fa-calendar"></i>
                            <span class="title">Custom Timing</span>
                        </a>
                   </li>
                                    @endcan  
            @endif                            
           {{-- @if(auth()->user()->role->id==3) --}}
             {{-- <li class="{{ $request->segment(2) == 'leave' ? 'active' : '' }}">
                <a href="{{ route('admin.leave.leavelist',[$employee->id]) }}">
                    <i class="fa fa-calendar"></i>
                    <span class="title">Leaves</span>
                </a>
            </li>  --}}
            @endcan 

            @can('appointment_access')
            <li class="{{ $request->segment(2) == 'appointments' ? 'active' : '' }}">
                <a href="{{ route('admin.appointments.index') }}">
                    <i class="fa fa-calendar"></i>
                    <span class="title">@lang('quickadmin.appointments.title')</span>
                </a>
            </li>
            @endcan 
              @can('taxrate_access')
            <li class="{{ $request->segment(2) == 'taxrate' ? 'active' : '' }}">
                <a href="{{ route('admin.taxrate.index') }}">
                    <i class="fa fa-hourglass"></i>
                    <span class="title">TaxRate</span>
                </a>
            </li>
            @endcan 		  
             @can('emailtemplate_access')
            <li class="{{ $request->segment(2) == 'emailtemplate' ? 'active' : '' }}">
                <a href="{{ route('admin.emailtemplates.index') }}">
                    <i class="fa fa-calendar"></i>
                    <span class="title">@lang('quickadmin.emailtemplates.title')</span>
                </a>
            </li>
            @endcan

             <li class="{{ $request->segment(1) == 'change_password' ? 'active' : '' }}">
                <a href="{{ route('auth.change_password') }}">
                    <i class="fa fa-key"></i>
                    <span class="title">Change password</span>
                </a>
            </li>
       
            <li>
                <a href="#logout" onclick="$('#logout').submit();">
                    <i class="fa fa-arrow-left"></i>
                    <span class="title">@lang('quickadmin.qa_logout')</span>
                </a>
            </li>
        </ul>
    </div>
</div>
{!! Form::open(['route' => 'auth.logout', 'style' => 'display:none;', 'id' => 'logout']) !!}
<button type="submit">@lang('quickadmin.logout')</button>
{!! Form::close() !!}
