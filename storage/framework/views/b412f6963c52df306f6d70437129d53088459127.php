<?php $__env->startSection('content'); ?>
    <div class="row">
    <div class="col-md-6"><h3 class="page-title"><i class="fa fa-users ifont"></i>  With Out Moneybird</h3></div>
    <div class="col-md-12 ">
    <?php if(Session::has('msg')): ?>
   <div class="alert alert-success">
        <ul>
            <li><?php echo \Session::get('msg'); ?></li>
        </ul>
    </div>
<?php endif; ?>
    </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading bold">
            <?php echo app('translator')->getFromJson('quickadmin.qa_list'); ?>
        </div>
        <div class="panel-body table-responsive">
            <table class="table table-bordered table-striped <?php echo e(count($clients) > 0 ? 'datatable' : ''); ?> <?php echo e(count($clientsOther) > 0 ? 'datatable' : ''); ?> <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('client_delete')): ?> dt-select <?php endif; ?>">
                <thead>
                    <tr>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('client_delete')): ?>
                            <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        <?php endif; ?>

                        <th><?php echo app('translator')->getFromJson('quickadmin.clients.fields.first-name'); ?></th>
                        <th><?php echo app('translator')->getFromJson('quickadmin.clients.fields.last-name'); ?></th>
                        <th><?php echo app('translator')->getFromJson('quickadmin.clients.fields.phone'); ?></th>
                        <th><?php echo app('translator')->getFromJson('quickadmin.clients.fields.email'); ?></th>
                        <th>Created Date </th>
                        <th>Comment </th>
                        <th>Parent Name</th>
                        <th>Money Bird Contact Id</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php if(count($clients) > 0): ?>
                        <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr data-entry-id="<?php echo e($client->id); ?>">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('client_delete')): ?>
                                    <td></td>
                                <?php endif; ?>

                                <td><?php echo e($client->first_name); ?></td>
                                <td><?php echo e($client->last_name); ?></td>
                                <td><?php echo e($client->phone); ?></td>
                                <td><?php echo e($client->email); ?></td>
                                <td><?php echo e(date('d-m-Y',strtotime($client->created_at))); ?></td>
                                <td>
                                    <?php if(!empty($client->comment_log)): ?>
                                    <ul class="comment_log_view">
                                        <?php echo $client->comment_log; ?>

                                    </ul>        
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e(getParentDetails($client->parent_id)); ?></td>

                                <td><?php echo e($client->moneybird_contact_id); ?></td>
                                <td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('client_view')): ?>
                                    <a href="<?php echo e(route('admin.client.showwithoutmoneybird',[$client->id])); ?>" class="btn btn-xs btn-primary"><?php echo app('translator')->getFromJson('quickadmin.qa_view'); ?></a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('client_edit')): ?>
                                    <a href="<?php echo e(route('admin.clients.editwithoutmoneybird',[$client->id])); ?>" class="btn btn-xs btn-info"><?php echo app('translator')->getFromJson('quickadmin.qa_edit'); ?></a>
                                    <?php endif; ?>

                                    
                                    <?php if($user_role_id == 2): ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('oappointment_create')): ?>
                                        <a href="<?php echo e(route('admin.opertorappointments.create',['client_id' => $client->id])); ?>" class="btn btn-xs btn-info">
                                            <i class="fa fa-calendar"></i>
                                            <span class="title"><?php echo app('translator')->getFromJson('quickadmin.qa_add_new'); ?> Booking</span>
                                        </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('appointment_create')): ?>
                                            <a href="<?php echo e(route('admin.appointments.create',['client_id' => $client->id])); ?>"
                                               class="btn btn-xs btn-info"><i class="fa fa-calendar"></i><span class="title"><?php echo app('translator')->getFromJson('quickadmin.qa_add_new'); ?> Booking</span></a>
                                        <?php endif; ?>
                                    <?php endif; ?>


                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('client_delete')): ?>
                                    <?php echo Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.clients.destroywithoutmoneybird', $client->id])); ?>

                                    <?php echo Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')); ?>

                                    <?php echo Form::close(); ?>

                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>

                    <?php if(count($clientsOther) > 0): ?>
                        <?php $__currentLoopData = $clientsOther; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr data-entry-id="<?php echo e($client->id); ?>">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('client_delete')): ?>
                                    <td></td>
                                <?php endif; ?>

                                <td><?php echo e($client->first_name); ?></td>
                                <td><?php echo e($client->last_name); ?></td>
                                <td><?php echo e($client->phone); ?></td>
                                <td><?php echo e($client->email); ?></td>
                                <td><?php echo e(date('d-m-Y',strtotime($client->created_at))); ?></td>
                                <td>
                                    <?php if(!empty($client->comment_log)): ?>
                                    <ul>
                                        <?php echo $client->comment_log; ?>

                                    </ul>        
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e(getParentDetails($client->parent_id)); ?></td>

                                <td><?php echo e($client->moneybird_contact_id); ?></td>
                                <td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('client_view')): ?>
                                    <a href="<?php echo e(route('admin.clients.show',[$client->id])); ?>" class="btn btn-xs btn-primary"><?php echo app('translator')->getFromJson('quickadmin.qa_view'); ?></a>
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('client_edit')): ?>
                                    <a href="<?php echo e(route('admin.clients.edit',[$client->id])); ?>" class="btn btn-xs btn-info"><?php echo app('translator')->getFromJson('quickadmin.qa_edit'); ?></a>
                                    <?php endif; ?>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('oappointment_create')): ?>
                                    <a href="<?php echo e(route('admin.opertorappointments.create',['client_id' => $client->id])); ?>" class="btn btn-xs btn-info">
                                        <i class="fa fa-calendar"></i>
                                        <span class="title"><?php echo app('translator')->getFromJson('quickadmin.qa_add_new'); ?> Booking</span>
                                    </a>
                                    <?php endif; ?>




                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('client_delete')): ?>
                                    <?php echo Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.qa_are_you_sure")."');",
                                        'route' => ['admin.clients.destroy', $client->id])); ?>

                                    <?php echo Form::submit(trans('quickadmin.qa_delete'), array('class' => 'btn btn-xs btn-danger')); ?>

                                    <?php echo Form::close(); ?>

                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                     <?php endif; ?>
                </tbody> 
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('javascript'); ?> 
    <script>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('client_delete')): ?>
            window.route_mass_crud_entries_destroy = '<?php echo e(route('admin.clients.mass_destroy')); ?>';
        <?php endif; ?>
       
        window.route_mass_send_email = '<?php echo e(route('admin.clients_mass_email_send')); ?>';
        window.email_templates = <?php echo $emailTemplate; ?>;
 </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>