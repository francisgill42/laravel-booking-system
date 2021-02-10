<?php $request = app('Illuminate\Http\Request'); ?>
<div class="page-sidebar-wrapper">
    <div class="page-sidebar navbar-collapse collapse">
        <ul class="page-sidebar-menu"
            data-keep-expanded="false"
            data-auto-scroll="true"
            data-slide-speed="200">
            
            <li class="<?php echo e($request->segment(1) == 'home' ? 'active' : ''); ?>">
                <a href="<?php echo e(url('/')); ?>">
                    <i class="fa fa-home"></i>
                    <span class="title"><?php echo app('translator')->getFromJson('quickadmin.qa_dashboard'); ?></span>
                </a>
            </li>
            
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('user_access')): ?>
            <li class="<?php echo e($request->segment(2) == 'users' ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.users.index')); ?>">
                    <i class="fa fa-user-circle"></i>
                    <span class="title"><?php echo app('translator')->getFromJson('quickadmin.users.title'); ?></span>
                </a>
            </li>
            <?php endif; ?>
            
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('availability_access')): ?>
            <li class="<?php echo e($request->segment(2) == 'availability' ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.availability.index')); ?>">
                    <i class="fa fa-user-circle"></i>
                    <span class="title">Therapist Availability</span>
                </a>
            </li>
            <?php endif; ?>
             
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('client_access')): ?>
            <li class="<?php echo e($request->segment(2) == 'clients' ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.clients.index')); ?>">
                    <i class="fa fa-users"></i>
                    <span class="title"><?php echo app('translator')->getFromJson('quickadmin.clients.title'); ?></span>
                </a>
            </li>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('client_without_moneybird')): ?>
             
             <li class="<?php echo e($request->segment(2) == 'clientwithoutmoneybird' ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.clients.clientwithoutmoneybird')); ?>">
                    <i class="fa fa-users"></i>
                    <span class="title">Client Without Moneybird</span>
                </a>
            </li>
             <?php endif; ?>
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('calandar_access')): ?>
            <li class="<?php echo e($request->segment(2) == 'calandar' ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.calandar.index')); ?>" target="_blank">
                    <i class="fa fa-calendar"></i>
                    <span class="title">Calandar</span>
                </a>
            </li>
            <?php endif; ?>
            

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee_access')): ?>
            <li class="<?php echo e($request->segment(2) == 'employees' ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.employees.index')); ?>">
                    <i class="fa fa-user"></i>
                    <span class="title"><?php echo app('translator')->getFromJson('quickadmin.employees.title'); ?></span>
                </a>
            </li>
            <?php endif; ?>
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('service_access')): ?>
            <li class="<?php echo e($request->segment(2) == 'services' ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.services.index')); ?>">
                    <i class="fa fa-hourglass"></i>
                    <span class="title"><?php echo app('translator')->getFromJson('quickadmin.services.title'); ?></span>
                </a>
            </li>
            <?php endif; ?>	
           <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('page_access')): ?>
            <li class="<?php echo e($request->segment(2) == 'pages' ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.pages.index')); ?>">
                    <i class="fa fa-hourglass"></i>
                    <span class="title">Pages</span>
                </a>
            </li>
            <?php endif; ?>    
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('room_access')): ?>
            <li class="<?php echo e($request->segment(2) == 'rooms' ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.rooms.index')); ?>">
                    <i class="fa fa-hourglass"></i>
                    <span class="title"><?php echo app('translator')->getFromJson('quickadmin.rooms.title'); ?></span>
                </a>
            </li>
            <?php endif; ?> 

             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('location_access')): ?>
            <li class="<?php echo e($request->segment(2) == 'locations' ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.locations.index')); ?>">
                    <i class="fa fa-hourglass"></i>
                    <span class="title"><?php echo app('translator')->getFromJson('quickadmin.locations.title'); ?></span>
                </a>
            </li>
            <?php endif; ?> 

             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('working_hour_access')): ?>
            
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('thrapist_working_hour_create')): ?>
             <li class="<?php echo e($request->segment(2) == 'employees_working_hour' ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.employees_working_hour.create',getEmployeeId(Auth::user()->id))); ?>">
                    <i class="fa fa-hourglass"></i>
                    <span class="title">Working Hours</span>
                </a>
            </li> 
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('page_present_view')): ?>
             <li class="<?php echo e($request->segment(2) == 'show' ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.pages.show',1)); ?>">
                    <i class="fa fa-hourglass"></i>
                    <span class="title"><?php echo getPageTitle(1);?></span>
                </a>
            </li> 
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('leave_access')): ?>
             
                <?php if(Auth::user()->role_id == 3): ?>
                    <li class="<?php echo e($request->segment(2) == 'leave' ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.leave.leavelist',getEmployeeId(Auth::user()->id))); ?>">
                            <i class="fa fa-calendar"></i>
                            <span class="title">Leaves</span>
                        </a>
                   </li>
                
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee_custom_timing_access')): ?>
                                    
                                     <li class="<?php echo e($request->segment(2) == 'employeecustomtiming' ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.employeecustomtiming.employeecustomtiminglist',getEmployeeId(Auth::user()->id))); ?>">
                            <i class="fa fa-calendar"></i>
                            <span class="title">Custom Timing</span>
                        </a>
                   </li>
                                    <?php endif; ?>  
            <?php endif; ?>                            
           
             
            <?php endif; ?> 
           
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('appointment_access')): ?>
            <li class="<?php echo e($request->segment(2) == 'appointments' ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.appointments.index')); ?>">
                    <i class="fa fa-calendar"></i>
                    <span class="title"><?php echo app('translator')->getFromJson('quickadmin.appointments.title'); ?></span>
                </a>
            </li>
            <?php endif; ?> 
            
            <?php if(Auth::user()->role_id == 2): ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('oappointment_create')): ?>
                <li>
                    <a href="<?php echo e(route('admin.opertorappointments.create')); ?>">
                        <i class="fa fa-calendar"></i>
                        <span class="title"><?php echo app('translator')->getFromJson('quickadmin.qa_add_new'); ?> Booking</span>
                    </a>
                </li>
                <?php endif; ?>
            <?php else: ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('appointment_create')): ?>
                <li>
                    <a href="<?php echo e(route('admin.appointments.create')); ?>">
                        <i class="fa fa-calendar"></i>
                        <span class="title"><?php echo app('translator')->getFromJson('quickadmin.qa_add_new'); ?> Booking</span>
                    </a>
                </li>
                <?php endif; ?>
            <?php endif; ?>

             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('oappointment_access')): ?>
            <li class="<?php echo e($request->segment(2) == 'opertorappointments' ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.opertorappointments.index')); ?>">
                    <i class="fa fa-calendar"></i>
                    <span class="title"><?php echo app('translator')->getFromJson('quickadmin.appointments.title'); ?></span>
                </a>
            </li>
            <?php endif; ?> 
              <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('taxrate_access')): ?>
            <li class="<?php echo e($request->segment(2) == 'taxrate' ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.taxrate.index')); ?>">
                    <i class="fa fa-hourglass"></i>
                    <span class="title">TaxRate</span>
                </a>
            </li>
            <?php endif; ?> 		  
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('emailtemplate_access')): ?>
            <li class="<?php echo e($request->segment(2) == 'emailtemplate' ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.emailtemplates.index')); ?>">
                    <i class="fa fa-calendar"></i>
                    <span class="title"><?php echo app('translator')->getFromJson('quickadmin.emailtemplates.title'); ?></span>
                </a>
            </li>
            <?php endif; ?>
             <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('employee_small_info')): ?>
             <li class="<?php echo e($request->segment(2) == 'smallinfoedit' ? 'active' : ''); ?>">
                <a href="<?php echo e(route('admin.employees.smallinfoedit',[getEmployeeId(Auth::user()->id )])); ?>">    
                    <i class="fa fa-hourglass"></i>
                    <span class="title">Small Info</span>
                </a>
            </li>
             <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('google_calendar_access')): ?>
                <li class="<?php echo e($request->segment(2) == 'gcalendar' ? 'active' : ''); ?>">
                    <a href="<?php echo e(route('admin.gcalendar.index')); ?>">
                        <i class="fa fa-hourglass"></i>
                        <span class="title">Sync Appointments</span>
                    </a>
                </li>
            <?php endif; ?>
             <li class="<?php echo e($request->segment(1) == 'change_password' ? 'active' : ''); ?>">
                <a href="<?php echo e(route('auth.change_password')); ?>">
                    <i class="fa fa-key"></i>
                    <span class="title">Change password</span>
                </a>
            </li>
       
            <li>
                <a href="#logout" onclick="$('#logout').submit();">
                    <i class="fa fa-arrow-left"></i>
                    <span class="title"><?php echo app('translator')->getFromJson('quickadmin.qa_logout'); ?></span>
                </a>
            </li>
        </ul>
    </div>
</div>
<?php echo Form::open(['route' => 'auth.logout', 'style' => 'display:none;', 'id' => 'logout']); ?>

<button type="submit"><?php echo app('translator')->getFromJson('quickadmin.logout'); ?></button>
<?php echo Form::close(); ?>

