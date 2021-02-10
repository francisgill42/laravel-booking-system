<?php $__env->startSection('content'); ?>

    <div class="row">
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('appointment_create')): ?>
          <p>
              <a href="<?php echo e(route('admin.appointments.create')); ?>"
                 class="btn btn-success pull-right"><?php echo app('translator')->getFromJson('quickadmin.qa_add_new'); ?> Booking</a>

          </p>
      <?php endif; ?>

    <div class="col-sm-12">        
       <div class="row">
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('location', 'Location*', ['class' => 'control-label']); ?>

                    <?php echo Form::Select('location_id',$locations, old('location_id'), ['class' => 'form-control locationId', 'placeholder select2'] ); ?>

                    <input type="hidden" name='location_id' id='location_id_latest' value='<?php echo e($location_id); ?>'/>    
                </div>
                <div class="col-xs-6 form-group">
                    <?php echo Form::label('date', 'Date*', ['class' => 'control-label']); ?>

                    <?php echo Form::text('date', old('date'), ['class' => 'form-control date', 'placeholder' => '']); ?>

                  
                </div>
            </div>
   <div id='calendar'></div>
    </div>
     
<link href='<?php echo e(url('quickadmin/fullcal')); ?>/core/main.css' rel='stylesheet' />
<link href='<?php echo e(url('quickadmin/fullcal')); ?>/daygrid/main.css' rel='stylesheet' />
<link href='<?php echo e(url('quickadmin/fullcal')); ?>/timegrid/main.css' rel='stylesheet' />
<link href='<?php echo e(url('quickadmin/fullcal')); ?>/timeline/main.css' rel='stylesheet' />
<link href='<?php echo e(url('quickadmin/fullcal')); ?>/resource-timeline/main.css' rel='stylesheet' />
<?php $__env->startSection('javascript'); ?>
    ##parent-placeholder-b6e13ad53d8ec41b034c49f131c64e99cf25207a##
<script src='<?php echo e(url('quickadmin/fullcal')); ?>/core/main.js'></script>
<script src='<?php echo e(url('quickadmin/fullcal')); ?>/interaction/main.js'></script>
<script src='<?php echo e(url('quickadmin/fullcal')); ?>/daygrid/main.js'></script>
<script src='<?php echo e(url('quickadmin/fullcal')); ?>/timegrid/main.js'></script>
<script src='<?php echo e(url('quickadmin/fullcal')); ?>/timeline/main.js'></script>
<script src='<?php echo e(url('quickadmin/fullcal')); ?>/resource-common/main.js'></script>
<script src='<?php echo e(url('quickadmin/fullcal')); ?>/resource-daygrid/main.js'></script>
<script src='<?php echo e(url('quickadmin/fullcal')); ?>/resource-timegrid/main.js'></script>
 <script src="<?php echo e(url('quickadmin/js')); ?>/dateTimePicker.js"></script>

 <script src="<?php echo e(url('quickadmin/js')); ?>/popper.min.js"></script>
 <script src="<?php echo e(url('quickadmin/js')); ?>/tooltip.min.js"></script>

<script>
 
  
   $('.date').datepicker({
    autoclose: true,
    dateFormat: "<?php echo e(config('app.date_format_js')); ?>"
  }).datepicker("setDate", "0");
 
 

//alert(newdate)
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
   var location_id =  document.getElementById('location_id_latest').value;
   
    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: [ 'resourceTimeGrid', 'dayGrid','timeGrid' ],
       schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
      height: 'parent',
      timeZone: 'UTC',
     /* defaultView: 'resourceTimeGridDay',
     defaultView: 'resourceTimeGridThirtyDay',*/
     defaultView: 'resourceTimeGridDay',
     header: {
      left: 'prev,next',
      center: 'title',
      right: 'resourceTimeGridDay,resourceTimeGridFourDay,resourceTimeGridSevanDay'
    },
    titleFormat: {   month: 'long',
    year: 'numeric',
    day: 'numeric',
    weekday: 'long'},
    views: {
      resourceTimeGridSevanDay: {
        type: 'resourceTimeGrid',
        duration: { days: 7 },
        buttonText: 'Week'
      },
      resourceTimeGridFourDay: {
        type: 'resourceTimeGrid',
        duration: { days: 4 },
        buttonText: '4 Days'
      }
    },
      resources: <?php echo $rooms; ?>,

      events: { url:'<?php echo e(url("admin/get-all-appointments")); ?>',
       startParam  : 'Dates',
      extraParams: function() { // a function that returns an object
        return {
          location_id: document.getElementById('location_id_latest').value
         };
      },
     },
      eventClick: function(event) {
        if (event.url) {
            //window.open(event.url);
            window.open(event.url , "_blank");
            return false;
        }
    },
      eventRender: function(info) {
       console.log(info.event.extendedProps.description);
        var tooltip = new Tooltip(info.el, {
          title: info.event.extendedProps.description,
          placement: 'top',
          trigger: 'hover',
          container: 'body',
          html: true,
        });
      }
   
    });

    calendar.render();
    var calendarEve = calendar.getEventSourceById('calendar');


    $('.locationId').on('change',function(){
     $('#location_id_latest').val($(this).val());
     //var eventSource = calendar.getEventSourceById('calendar');
      calendar.refetchEvents();  
      //calendar.refetch();
    })

    $('.date').on('change',function(){
     //$('#location_id_latest').val($(this).val());
     //var eventSource = calendar.getEventSourceById('calendar');
      //calendar.refetchEvents();  
      calendar.gotoDate( $(this).val())
      calendar.changeView('resourceTimeGridDay');

      //calendar.fullCalendar('gotoDate', $(this).val());
      //calendar.refetch();
    })

  
  });

</script>
<?php $__env->stopSection(); ?>
<style>

  body {
    margin: 0;
    padding: 0;
    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
    font-size: 14px;
  }

  #calendar {
    max-width: 1300px;
    margin: 20px auto;
  }

  /*
  i wish this required CSS was better documented :(
  https://github.com/FezVrasta/popper.js/issues/674
  derived from this CSS on this page: https://popper.js.org/tooltip-examples.html
  */

  .popper,
  .tooltip {
    position: absolute;
    z-index: 9999;
    background: #FFC107;
    color: black;
    width: 350px !important;
    border-radius: 1px !important;
    padding: 2px;
    text-align: left;
    opacity: 1 !important;
  }
  .tooltip-inner{
    max-width:350px !important;
    text-align: left !important;
  }
  .style5 .tooltip {
    background: #1E252B;
    color: #FFFFFF;
    max-width: 200px;
    width: auto;
    font-size: .8rem;
    padding: .5em 1em;
  }
  .popper .popper__arrow,
  .tooltip .tooltip-arrow {
    width: 0;
    height: 0;
    border-style: solid;
    position: absolute;
    margin: 5px;
  }

  .tooltip .tooltip-arrow,
  .popper .popper__arrow {
    border-color: #FFC107;
  }
  .style5 .tooltip .tooltip-arrow {
    border-color: #1E252B;
  }
  .popper[x-placement^="top"],
  .tooltip[x-placement^="top"] {
    margin-bottom: 5px;
  }
  .popper[x-placement^="top"] .popper__arrow,
  .tooltip[x-placement^="top"] .tooltip-arrow {
    border-width: 5px 5px 0 5px;
    border-left-color: transparent;
    border-right-color: transparent;
    border-bottom-color: transparent;
    bottom: -5px;
    left: calc(50% - 5px);
    margin-top: 0;
    margin-bottom: 0;
  }
  .popper[x-placement^="bottom"],
  .tooltip[x-placement^="bottom"] {
    margin-top: 5px;
  }
  .tooltip[x-placement^="bottom"] .tooltip-arrow,
  .popper[x-placement^="bottom"] .popper__arrow {
    border-width: 0 5px 5px 5px;
    border-left-color: transparent;
    border-right-color: transparent;
    border-top-color: transparent;
    top: -5px;
    left: calc(50% - 5px);
    margin-top: 0;
    margin-bottom: 0;
  }
  .tooltip[x-placement^="right"],
  .popper[x-placement^="right"] {
    margin-left: 5px;
  }
  .popper[x-placement^="right"] .popper__arrow,
  .tooltip[x-placement^="right"] .tooltip-arrow {
    border-width: 5px 5px 5px 0;
    border-left-color: transparent;
    border-top-color: transparent;
    border-bottom-color: transparent;
    left: -5px;
    top: calc(50% - 5px);
    margin-left: 0;
    margin-right: 0;
  }
  .popper[x-placement^="left"],
  .tooltip[x-placement^="left"] {
    margin-right: 5px;
  }
  .popper[x-placement^="left"] .popper__arrow,
  .tooltip[x-placement^="left"] .tooltip-arrow {
    border-width: 5px 0 5px 5px;
    border-top-color: transparent;
    border-right-color: transparent;
    border-bottom-color: transparent;
    right: -5px;
    top: calc(50% - 5px);
    margin-left: 0;
    margin-right: 0;
  }

</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>