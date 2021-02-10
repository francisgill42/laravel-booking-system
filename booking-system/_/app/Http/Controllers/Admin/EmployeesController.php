<?php

namespace App\Http\Controllers\Admin;

use App\Employee;
use App\Service;
use App\WorkingHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmployeesRequest;
use App\Http\Requests\Admin\UpdateEmployeesRequest;
use DB;
use Carbon\Carbon;
use App\User;

class EmployeesController extends Controller
{
    /**
     * Display a listing of Employee.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('employee_access')) {
            return abort(401);
        }

        $employees = Employee::all();
//dd($employees);

        return view('admin.employees.index', compact('employees'));
    }

    /**
     * Show the form for creating new Employee.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('employee_create')) {
            return abort(401);
        }
 
        $relations = [
         
            /*'working_type' => array('wholeweek'=>'Time Series(entire week)','weekend'=>'Weekend'),*/
             'locations' => \App\Location::get()->pluck('location_name', 'id')->prepend('All Location', '0'),
             'working_type' => array('1'=>'Monday','2'=>'Tuesday','3'=>'Wednesday','4'=>'Thursday','5'=>'Friday','6'=>'Saturday','7'=>'Sunday'),
             'rooms' => \App\Room::get(),
             'roomLoctions' => array()
        ];

        return view('admin.employees.create',$relations);
    }

    /**
     * Store a newly created Employee in storage.
     *
     * @param  \App\Http\Requests\StoreEmployeesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeesRequest $request)
    {
        if (! Gate::allows('employee_create')) {
            return abort(401);
        }
      //echo $date = date('l');    
     
     $name = $request->first_name.' '.$request->last_name;
        $user = User::create([
           'name' => $name,
           'email' => $request->email,
           'role_id'  => 3,
           'password' => bcrypt($request->password)
        ]);
        $employee = Employee::create([
           'user_id' => $user->id,
           'first_name' => $request->first_name,
           'last_name' => $request->last_name,
           'email' => $request->email,
           'phone' => $request->phone,
        ]);

    // Start date
    $date = date('Y-m-d');
    // End date
    $end_date = date('Y-m-d', strtotime('+365 days'));
    $lookup=['Sunday'=>0,'Monday'=>1,'Tuesday'=>2,'Wednesday'=>3,'Thursday'=>4,'Friday'=>5,'Saturday'=>6];
    //$startday = date('l'); //current day 
   
    while (strtotime($date) <= strtotime($end_date)) {
              // foreach($request->booking_pricing_time_from as $key => $value)
            //    {
                    
                    $dayName =  date('l',strtotime($date));
                    $locationId =  $request->working_location_id[$dayName];
                    $start_time =  $request->booking_pricing_time_from[$dayName];
                     $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
  /*                $noOfdays1 = $lookup[$key]-$lookup[$startday];
                  $noOfdays= $noOfdays1 < 1 ? $noOfdays1+7: $noOfdays1; 

                  if($noOfdays1==0)
                     { $start_date = date('Y-m-d');}
                    else {
                       $start_date = date('Y-m-d', strtotime('+'.$noOfdays.' days'));
                     } */
                  
                   $working_hour = WorkingHour::create([
                   'employee_id' => $employee->id,                                                                    
                   'date' => $date,
                   'days' => $dayName,
                     
                   'location_id' => $request->location_id[$dayName],  
                   'start_time' => isset($request->booking_pricing_time_from[$dayName]) ? $request->booking_pricing_time_from[$dayName].":00" : "00:00:00",
                   'finish_time' => isset($Request->booking_pricing_time_to[$dayName]) ? $request->booking_pricing_time_to[$dayName].":00" : "00:00:00"
                  
                  ]); 

                  
                //}
    }
    $oldvalue=0; $Orderid=0;
    if(isset($request->room_id))
    {
       foreach($request->room_id as $room_id)
         {
            $roomLoc =  explode('_', $room_id);
            $roomId = $roomLoc[0];
            $location_id = $roomLoc[1];
            if($oldvalue==$location_id)
              {$Orderid++;$default='N';}
            else
              {$Orderid=1; $default='Y';}
            $oldvalue=$location_id;
            

            DB::table('employees_rooms')->insert(
                    ['employee_id' => $employee->id, 'location_id' => $location_id,'room_id' => $roomId,'orders'=>$Orderid,'default'=>$default]
                );
           
           
         }
    }
    
               
        return redirect()->route('admin.employees.index');
    } 

    /**
     * Show the form for editing Employee.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('employee_edit')) {
            return abort(401);
        }
        $employee = Employee::findOrFail($id);  
        $WorkingHorsArray =  \App\WorkingHour::where('employee_id','=',$id)->where('date','>=',date('Y-m-d'))->get();
       $accordingDays = array();
       if($WorkingHorsArray->count() > 0)
       {
         foreach($WorkingHorsArray as $WorkingHorsArray)
               {
                   $accordingDays[$WorkingHorsArray->days]=array(
                     'start_time' => $WorkingHorsArray->start_time,
                     'finish_time' => $WorkingHorsArray->finish_time,
                     'repeated' => $WorkingHorsArray->repeated,
                     'location_id' => $WorkingHorsArray->location_id,
                   );

               }
       }
    $roomLocations = DB::table('employees_rooms')->select('rooms.room_name','employees_rooms.location_id','employees_rooms.room_id')->join('rooms','employees_rooms.room_id','=','rooms.id')->join('locations','employees_rooms.location_id','=','locations.id')->where('employee_id','=',$employee->id)->get();
    $roomLocationSelect=array();
    //$roomSelect = array();
    $oldvalue=0;
    //echo "<pre>";print_r($roomLocations);exit;
       foreach($roomLocations as $roomLocation)
          {
             if($oldvalue==$roomLocation->location_id)
              {
                $roomSelect[]=$roomLocation->room_id; 
              }
            else
              {
                $roomSelect = array();
                $roomSelect[]=$roomLocation->room_id;
              }
            $oldvalue=$roomLocation->location_id;
          $roomLocationSelect[$roomLocation->location_id]=array('room'=>$roomSelect);
          }
//echo "<pre>";print_r($roomLocationSelect);exit;
/*          echo "<pre>";print_r($roomLocationSelect);
          
          foreach ($roomLocationSelect as $key => $value) {

                print_r(json_decode($value['room']));           
          }

          exit;*/
        $relations = [
         
            'locations' => \App\Location::get()->pluck('location_name', 'id')->prepend('All Location', '0'),
             'working_type' => array('1'=>'Monday','2'=>'Tuesday','3'=>'Wednesday','4'=>'Thursday','5'=>'Friday','6'=>'Saturday','7'=>'Sunday'),
             'empworkinghHours' =>$accordingDays,
             'rooms' => \App\Room::get(),
             'roomLoctions' => $roomLocationSelect
        ];
     
        return view('admin.employees.edit', compact('employee')+ $relations);
    }

    /**
     * Update Employee in storage.
     *
     * @param  \App\Http\Requests\UpdateEmployeesRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeesRequest $request, $id)
    {
        if (! Gate::allows('employee_edit')) {
            return abort(401);
        }
     /*echo "<pre>";
        print_r($request->emp_location_id);
        echo "<pre>";
        print_r($request->room_id);
        exit;*/

        $employee = Employee::findOrFail($id);
        $user = User::where('id', $employee->user_id)->get()->first();
        $name = $request->first_name.' '.$request->last_name;
        $user->name = $name;
        $user->email = $request->email; 
         if(isset($request->password))
          {$user->password =  bcrypt($request->password);}
        $user->save();
       
    $RoomDelete =  DB::table('employees_rooms')->where('employee_id','=',$id)->delete();
    $oldvalue=0; $Orderid=0;
    foreach($request->room_id as $room_id)
         {
            $roomLoc =  explode('_', $room_id);
            $roomId = $roomLoc[0];
            $location_id = $roomLoc[1];
            if($oldvalue==$location_id)
              {$Orderid++;$default='N';}
            else
              {$Orderid=1; $default='Y';}
            $oldvalue=$location_id;
            

            DB::table('employees_rooms')->insert(
                    ['employee_id' => $id, 'location_id' => $location_id,'room_id' => $roomId,'orders'=>$Orderid,'default'=>$default]
                );
           
           
         }

       /*
        foreach($request->working_type as $key => $value)
        { 

           $working_hour = WorkingHour::where('employee_id', $employee->id)->get()->first();
        
          $working_hour->working_type = $value; 
          $working_hour->start_time   = $request->booking_pricing_time_from[$key];
          $working_hour->finish_time  = $request->booking_pricing_time_to[$key];
          $working_hour->save();
        }*/

        // Start date

     $WorkingHorsArray =  \App\WorkingHour::where('employee_id','=',$id)->where('date','>=',date('Y-m-d'))->forceDelete();
        
    //$date = date('Y-m-d');
    // End date
    //$end_date = date('Y-m-d', strtotime('+7 days'));
    //$lookup=['Sunday'=>0,'Monday'=>1,'Tuesday'=>2,'Wednesday'=>3,'Thursday'=>4,'Friday'=>5,'Saturday'=>6];
    //$startday = date('l'); //current day 
  
    //while (strtotime($date) <= strtotime($end_date)) {
              /* foreach($request->booking_pricing_time_from as $key => $value)
                {
                  
                  $noOfdays1 = $lookup[$key]-$lookup[$startday];
                  $noOfdays= $noOfdays1 < 1 ? $noOfdays1+7: $noOfdays1; 

                  if($noOfdays1==0)
                     { $start_date = date('Y-m-d');}
                    else {
                       $start_date = date('Y-m-d', strtotime('+'.$noOfdays.' days'));
                     } 
                    
                     $dayL = date('l', strtotime($start_date));
                     $workingHoursDays = WorkingHour::where('employee_id','=',$id)->where('days','=',$dayL)->where('date','=',$start_date)->get(); 
                       
                       if($workingHoursDays->count() == 0)
                       {
                         $working_hour = WorkingHour::create([
                       'employee_id' => $id,
                       'date' => $start_date,
                       'days' => $key,
                       'working_type' => $value,  
                       'location_id' => $request->working_location_id[$key],  
                       'start_time' => isset($request->booking_pricing_time_from[$key]) ? date('H:i:s',strtotime($request->booking_pricing_time_from[$key])) : "00:00:00",
                       'finish_time' => isset($request->booking_pricing_time_to[$key]) ? date('H:i:s',strtotime($request->booking_pricing_time_to[$key])) : "00:00:00"
                      
                      ]);
                       }
                    

                  
                }*/
             $date = date('Y-m-d');
    // End date
    $end_date = date('Y-m-d', strtotime('+365 days'));
    $lookup=['Sunday'=>0,'Monday'=>1,'Tuesday'=>2,'Wednesday'=>3,'Thursday'=>4,'Friday'=>5,'Saturday'=>6];
    //$startday = date('l'); //current day 
   //echo "End Date ".$end_date;exit;
    while (strtotime($date) <= strtotime($end_date)) {
              // foreach($request->booking_pricing_time_from as $key => $value)
            //    {
                    
                    $dayName =  date('l',strtotime($date));
                    $locationId =  $request->working_location_id[$dayName];
                    $start_time =  $request->booking_pricing_time_from[$dayName];
                   
                   $workingHoursDays = WorkingHour::where('employee_id','=',$id)->where('days','=',$dayName)->where('date','=',$date)->get(); 

                    

                   // $date = date ("Y-m-d", strtotime($date));
             if($workingHoursDays->count() == 0)
                       {

                         if($request->booking_pricing_time_from[$dayName]!='00:00:00' && $request->booking_pricing_time_to[$dayName])
                          {
                             $working_hour = WorkingHour::create([
                             'employee_id' => $id,
                             'date' => $date,
                             'days' => $dayName,
                               
                             'location_id' => $request->working_location_id[$dayName],  
                             'start_time' => isset($request->booking_pricing_time_from[$dayName]) ? date('H:i:s',strtotime($request->booking_pricing_time_from[$dayName])) : "00:00:00",
                             'finish_time' => isset($request->booking_pricing_time_to[$dayName]) ? date('H:i:s',strtotime($request->booking_pricing_time_to[$dayName])) : "00:00:00"
                            
                            ]);
                          } 
                         
                       }
                       $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
    }

        $employee->update($request->all()); 
        return redirect()->route('admin.employees.index');
    }


    /**
     * Display Employee.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('employee_view')) {
            return abort(401);
        }
        $relations = [
            'working_hours' => \App\WorkingHour::where('employee_id', $id)->get(),
            'appointments' => \App\Appointment::where('employee_id', $id)->get(),
        ];

        $employee = Employee::findOrFail($id);

        return view('admin.employees.show', compact('employee') + $relations);
    }

    public function services($id){

        if (! Gate::allows('employee_service')) {
            return abort(401);
        }
        $relations = [
            'employee_services' => \App\EmployeeService::where('employee_id', $id)->get(), 
        ];
       
        $employee = Employee::findOrFail($id);
      
        return view('admin.employees.service', compact('employee') + $relations);
    }

    /**
     * Remove Employee from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('employee_delete')) {
            return abort(401);
        }
        $employee = Employee::findOrFail($id);
        $WorkingHorsArray =  \App\WorkingHour::where('employee_id','=',$id)->forceDelete();
        $employee->delete();

        return redirect()->route('admin.employees.index');
    }

    /**
     * Delete all selected Employee at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('employee_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Employee::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }
    public function GetEmployeeTimeSlotEdit(Request $request)
  {

    $service = \App\Service::find($request->service_id);
    $workingDay = date('l', strtotime($request->date));
    $booking_block_duration = $service->booking_block_duration;
    $min_block_duration     = $service->min_block_duration;
    $appointment_time     = $request->appointment_time;
  

//echo "<pre>";print_r($totalRoombookedArray);
 /*End location and room validation*/
//echo "<pre>";print_r($additionRoomArray);
//echo "<pre>";print_r($subtractRoomArray);



 /*  $employe_WorkingHour = DB::table('working_hours')
          ->select('*')
         ->where('working_hours.date', '=', $request->date)
         ->where('working_hours.start_time', '!=', '00:00:00')
         ->where('working_hours.finish_time', '!=', '00:00:00')
         ->where('working_hours.days', '=', $workingDay)
         ->where('working_hours.employee_id', '=', $request->employee_id)
         ->whereIn('working_hours.location_id', ['0',$request->location_id])->get();*/
           $employe_WorkingHour = DB::table('working_hours')
          ->select('*')
         ->where('working_hours.date', '=', $request->date)
         ->where('working_hours.start_time', '!=', '00:00:00')
         ->where('working_hours.finish_time', '!=', '00:00:00')
         ->where('working_hours.days', '=', $workingDay)
         ->where('working_hours.employee_id', '=', $request->employee_id)->get();
$html="";
  
 if(count($employe_WorkingHour) > 0)
      {
          $start_time  = $employe_WorkingHour[0]->start_time;
          $finish_time = $employe_WorkingHour[0]->finish_time;
          $starttimestamp = strtotime($start_time);
          $endtimestamp = strtotime($finish_time);
          $total_working_hours = abs($endtimestamp - $starttimestamp)/3600;
          $total_working_block = ($total_working_hours * 60)/$booking_block_duration;
               $start_str = strtotime($start_time);
    $end_str = strtotime($finish_time);
    $now_str = $start_str;
    $midTime = strtotime('12:00:00');

    $data = [];
    $preTime = '';
    $index = '';
    $interval = '+15 minutes';

    while ($now_str <= $end_str) {
        if ($now_str <= $midTime)
            $index = 'AM';
        else
            $index = 'PM';
        if ($preTime) {
           // $data[$index][] = $preTime . '-' . date('H:i:s', $now_str);
          //if(!isset($alreday_book_start_timing[$preTime]))
            //{
              $data[] = $preTime;
            //}
        }
        $preTime = date('H:i', $now_str);
        $now_str = strtotime($interval, $now_str);
    }
         
   //  echo "<pre>";print_r($data);exit;    
          $html="<div class='col-xs-12 col-md-6'>";
          $html.="";
          $iT=1;
         foreach($data as $key => $Values) 
           {
             $defaultCls='borderTimeing';$title="";
            /* if(count($already_location_room_booking) > $location_room_count)
                 {  if(isset($totalRoombookedArray[$Values]))
                    {$defaultCls='borderlocationBookedTimeing'; $title="rooms booked";}
                }*/
                $selectDiv='';
                if($appointment_time == $Values)
                    {  $selectDiv ='selectedDiv';}
             $html.="<div class='col-xs-3 col-md-3 ".$defaultCls." ".$selectDiv." '> $Values</div>";
             if($iT%4==0)
              {  $html.="</div><div class='col-xs-12 col-md-6'>"; }
             $iT++;
            }

             $html.="</div>";
      }
  return $html;
  }
  public function GetEmployeeTimeSlot(Request $request)
  {

   $service = \App\Service::find($request->service_id);
   $location_room_count =DB::table('rooms_locations')->select('*')->where('rooms_locations.location_id', '=', $request->location_id)->count();
   
  $booking_block_duration = $service->booking_block_duration;
    $min_block_duration     = $service->min_block_duration;
    $workingDay = date('l', strtotime($request->date));
  $locationId=$request->location_id;
  $therapistId=$request->employee_id;
  $no_of_block=$request->no_of_block;
    /*$already_booking = DB::table('appointments')->select('*')->where('appointments.start_time', '>', $request->date."00:00:00")->where('appointments.employee_id', '=', $request->employee_id)
         ->whereIn('appointments.location_id', ['0',$request->location_id])->get();
*/
        // Checking Location with therpist which is alreday booked  
        $check_localtion_count_bookings = DB::table('appointments')->select('*')->where('appointments.start_time', '>', $request->date." 00:00:00")
         ->where('appointments.location_id','=' ,$request->location_id)->get();
       
      $therapist_id_array_which_already_booked =array();
         foreach ($check_localtion_count_bookings as $check_localtion_count_booking) {
           $therapist_id_array_which_already_booked[$check_localtion_count_booking->employee_id]=$check_localtion_count_booking->employee_id;
         }
      //echo "<pre>";print_r($therapist_id_array_which_already_booked);exit;
      
         $already_booking = DB::table('appointments')->select('*')->where('appointments.start_time', '>', $request->date." 00:00:00")->where(function($already_booking) use ($locationId,$therapistId){
                 $already_booking->where('appointments.employee_id', '=', $therapistId);
                 $already_booking->Where('appointments.location_id', '=', $locationId);
             })->where('deleted_at','=',NULL)->get();

  $alreday_book_start_timing=array();
       if(count($already_booking) > 0)
          {
               foreach( $already_booking as $already_booking_rel)
                   {
                    if(array_key_exists($already_booking_rel->employee_id,$therapist_id_array_which_already_booked))
                    {
                      $alreday_book_start_timing[date('H:i',strtotime($already_booking_rel->start_time))]   = date('H:i',strtotime($already_booking_rel->start_time));
                    }
                            
                   }
          }
 $additionArray = array(); $subtractArray = array();
 $totalArray = array();
//echo "<pre>";print_r($alreday_book_start_timing);exit;
//Addition tie slot is added due to time taken by therapist for each services arround 45 mintues to 1 hour
 //echo "<pre>";print_r($alreday_book_start_timing);
 foreach($alreday_book_start_timing as $key => $startTimingVal)
    {   
         for($i=1;$i<=($no_of_block-1);$i++)
         { 
              $jkN =15*$i;
              $intervalP = "+".$jkN." minutes"; $intervalM = "-".$jkN." minutes";                  
              $additionArray[date('H:i',strtotime($intervalP, strtotime($startTimingVal)))] = date('H:i',strtotime($intervalP, strtotime($startTimingVal)));
              $subtractArray[date('H:i',strtotime($intervalM, strtotime($startTimingVal)))] = date('H:i',strtotime($intervalM, strtotime($startTimingVal)));
         }
    }
//echo "<pre>";print_R($alreday_book_start_timing);exit;
$totalArray = array_merge($subtractArray,$additionArray);

$alreday_book_start_timing = array_merge($alreday_book_start_timing,$totalArray);
//echo "<pre>";print_r($alreday_book_start_timing);exit;
 /*Location With Room Validation, This location is already booked with room */   
  /*$already_location_room_booking = DB::table('appointments')->select('*')->where('appointments.start_time', '>', $request->date." 00:00:00")
         ->where('appointments.location_id', [$request->location_id])->get();*/
         // need added 
          $tomarrow_date =  new \Carbon\Carbon($request->date);
         $tomarrow_date = $tomarrow_date->addDays(1);
      $already_location_room_booking = DB::table('appointments')->select('*')->where('appointments.start_time', '>', $request->date." 00:00:00")->where('appointments.start_time', '<', $tomarrow_date." 00:00:00")->where('location_id','=',$request->location_id)->where('deleted_at','=',NULL)->get();   
//echo "<pre>";print_r($already_location_room_booking);exit;

   $alreday_book_room_start_timing=array();
       if(count($already_location_room_booking) > 0)
          {
               foreach($already_location_room_booking as $already_lbooking_rel)
                   {
                    
                    $alreday_book_room_start_timing[$already_lbooking_rel->employee_id][date('H:i',strtotime($already_lbooking_rel->start_time))]   = date('H:i',strtotime($already_lbooking_rel->start_time));
                            
                   }
          }
 $additionRoomArray = array(); $subtractRoomArray = array();
 $totalRoombookedArray = array();
//  echo "<pre>";print_r($alreday_book_room_start_timing);exit;
//Addition tie slot is added due to time taken by therapist for each services arround 45 mintues to 1 hour
 foreach($alreday_book_room_start_timing as $key => $startTimingVal)
    {   
       foreach($startTimingVal as $startTimingVal)
         {
           for($i=1;$i<=3;$i++)
           { 
                $jkN =15*$i;
                $intervalP = "+".$jkN." minutes"; $intervalM = "-".$jkN." minutes";                  
                $additionRoomArray[date('H:i',strtotime($intervalP, strtotime($startTimingVal)))] = date('H:i',strtotime($intervalP, strtotime($startTimingVal)));
                $subtractRoomArray[date('H:i',strtotime($intervalM, strtotime($startTimingVal)))] = date('H:i',strtotime($intervalM, strtotime($startTimingVal)));
           }
        } 
    }
$totalRoombookedArray = array_merge($subtractRoomArray,$additionRoomArray);
//echo "<pre>";print_r($totalRoombookedArray);exit;
//echo "<pre>";print_r($totalRoombookedArray);exit;
 /*End location and room validation*/
//echo "<pre>";print_r($additionRoomArray);
//echo "<pre>";print_r($alreday_book_start_timing);exit;


/*
   $employe_WorkingHour = DB::table('working_hours')
          ->select('*')
         ->where('working_hours.date', '=', $request->date)
         ->where('working_hours.start_time', '!=', '00:00:00')
         ->where('working_hours.finish_time', '!=', '00:00:00')
         ->where('working_hours.days', '=', $workingDay)
         ->where('working_hours.employee_id', '=', $request->employee_id)
         ->whereIn('working_hours.location_id', ['0',$request->location_id])->get();*/

   $employe_WorkingHour = DB::table('working_hours')
          ->select('*')
         ->where('working_hours.date', '=', $request->date)
         ->where('working_hours.start_time', '!=', '00:00:00')
         ->where('working_hours.finish_time', '!=', '00:00:00')
         ->where('working_hours.days', '=', $workingDay)
         ->where('working_hours.employee_id', '=', $request->employee_id)->get();

   $employe_custom_WorkingHour = DB::table('employee_customtimings')
          ->select('*')
         ->where('employee_customtimings.date', '=', $request->date)
         ->where('employee_customtimings.start_time', '!=', '00:00:00')
         ->where('employee_customtimings.end_time', '!=', '00:00:00')
         ->where('employee_customtimings.employee_id', '=', $request->employee_id)->get();      
$html="";
$customTimingArray=array();
$customadditionArray = array();
$customsubtractArray = array();
//echo "<pre>";print_r($employe_custom_WorkingHour);exit;
if(isset($employe_custom_WorkingHour))
 {
    foreach($employe_custom_WorkingHour as $employe_custom_Workingho)
     {
      if($employe_custom_Workingho->timing_type=='unavailable')
      {
            $customTimingArray[date('H:i',strtotime($employe_custom_Workingho->start_time))]   = date('H:i',strtotime($employe_custom_Workingho->start_time));
      }
    

          // $customTimingArray[]
     }

 }
 
if(isset($customTimingArray) && count($customTimingArray) > 0)
  {
      foreach($customTimingArray as $key => $startTimingVal)
    {   
         for($i=1;$i<=($no_of_block-1);$i++)
         { 
              $jkN =15*$i;
              $intervalP = "+".$jkN." minutes"; $intervalM = "-".$jkN." minutes";                  
              $customadditionArray[date('H:i',strtotime($intervalP, strtotime($startTimingVal)))] = date('H:i',strtotime($intervalP, strtotime($startTimingVal)));
              $customsubtractArray[date('H:i',strtotime($intervalM, strtotime($startTimingVal)))] = date('H:i',strtotime($intervalM, strtotime($startTimingVal)));
         }
    }
  }
  

  $totalCustomArray = array_merge($customsubtractArray,$customadditionArray);
 
$customTimingArray = array_merge($customTimingArray,$totalCustomArray);
//echo "<pre>";print_r( $customTimingArray);
  
 //  exit;
 if(count($employe_WorkingHour) > 0)
      {
          $start_time  = $employe_WorkingHour[0]->start_time;
          $finish_time = $employe_WorkingHour[0]->finish_time;
          $starttimestamp = strtotime($start_time);
          $endtimestamp = strtotime($finish_time);
          $total_working_hours = abs($endtimestamp - $starttimestamp)/3600;
          $total_working_block = ($total_working_hours * 60)/$booking_block_duration;
               $start_str = strtotime($start_time);
    //$end_str = strtotime($finish_time);
    $mintuesMinusW= ($no_of_block -1) * 15;
                $end_str = strtotime('- '.$mintuesMinusW.' minute' , strtotime ( $finish_time));
    $now_str = $start_str;
    $midTime = strtotime('12:00:00');

    $data = [];
    $preTime = '';
    $index = '';
    $interval = '+15 minutes';
//echo "<pre>";print_r($alreday_book_start_timing);
//echo "<pre>";print_r($customTimingArray);exit;
    while ($now_str <= $end_str) {
        if ($now_str <= $midTime)
            $index = 'AM';
        else
            $index = 'PM';
        if ($preTime) {
           // $data[$index][] = $preTime . '-' . date('H:i:s', $now_str);
         // echo $preTime."<br>";
          // we sat the priporties for timing if alreday have booked if not booked than we are checking the custom timing thing is not avalibel
          if(!isset($alreday_book_start_timing[$preTime]) )
            {
              
              if(!isset($customTimingArray[$preTime])) 
                {
                  $data[] = $preTime;
                }
            }
           
        }
        $preTime = date('H:i', $now_str);
        $now_str = strtotime($interval, $now_str);
    }
         
 //echo "<pre>";print_r($data);exit;    
          $html="<div class='col-xs-12 col-md-6'>";
          $html.="";
          $iT=1;
         foreach($data as $key => $Values) 
           {
             $defaultCls='borderTimeing';$title="";
             if(count($already_location_room_booking) >= $location_room_count)
                 {  if(isset($totalRoombookedArray[$Values]))
                    {$defaultCls='borderlocationBookedTimeing'; $title="rooms booked";}
                }
             $html.="<div class='col-xs-3 col-md-3 ".$defaultCls." ' title='".$title."'> $Values</div>";
             if($iT%4==0)
              {  $html.="</div><div class='col-xs-12 col-md-6'>"; }
             $iT++;
            }
            if(count($data)==0)
             {$html.="</div>";  }
      }
    elseif(count($employe_custom_WorkingHour) > 0)
     {
      // for multiple custom timing like 10-11 and the again 3-4 like that
      $data = array();
      foreach($employe_custom_WorkingHour as $employe_custom_singleWorkingHour)
           {
                //$start_time  = $employe_custom_WorkingHour[0]->start_time;
                $start_time  = $employe_custom_singleWorkingHour->start_time;

                //$finish_time = $employe_custom_WorkingHour[0]->end_time;
                $finish_time = $employe_custom_singleWorkingHour->end_time;
               
              
                $starttimestamp = strtotime($start_time);
                $endtimestamp = strtotime('-1 hour' , strtotime ( $finish_time));
                $total_working_hours = abs($endtimestamp - $starttimestamp)/3600;
                $total_working_block = ($total_working_hours * 60)/$booking_block_duration;
                
                $reminderVal = $total_working_block%$no_of_block;
                $dividedVal = $total_working_block/$no_of_block;
                $start_str = strtotime($start_time);
              //  $end_str = strtotime($finish_time); 
                // last hour appontment will 11:00 not like 11:15,11:30 like that
                $mintuesMinus= ($no_of_block -1) * 15;
                $end_str = strtotime('- '.$mintuesMinus.' minute' , strtotime ( $finish_time));
                $now_str = $start_str;
                  $midTime = strtotime('12:00:00');

                  
                  $preTime = '';
                  $index = '';
                  $interval = '+15 minutes';
                 //echo "<pre>";print_r($alreday_book_start_timing);
            while ($now_str <= $end_str) {
                if ($now_str <= $midTime)
                    $index = 'AM';
                else
                    $index = 'PM';
                if ($preTime) {
                   // $data[$index][] = $preTime . '-' . date('H:i:s', $now_str);
                 // echo $preTime."<br>";
                  if(!isset($alreday_book_start_timing[$preTime]))
                    {
                      
                      $data[] = $preTime;
                    }
                }
                $preTime = date('H:i', $now_str);
                $now_str = strtotime($interval, $now_str);
            }
           }
         
    
          $html="<div class='col-xs-12 col-md-6'>";
          $html.="";
          $iT=1;
         foreach($data as $key => $Values) 
           {
             $defaultCls='borderTimeing';$title="";
             if(count($already_location_room_booking) >= $location_room_count)
                 {  if(isset($totalRoombookedArray[$Values]))
                    {$defaultCls='borderlocationBookedTimeing'; $title="rooms booked";}
                }
             $html.="<div class='col-xs-3 col-md-3 ".$defaultCls." '> $Values</div>";
             if($iT%4==0)
              {  $html.="</div><div class='col-xs-12 col-md-6'>"; }
             $iT++;
            }
            if(count($data)==0)
             {$html.="</div>";  }

     }  
     else {
         $html="<div style='color:red'>There is no working hours is allocate with this therapist , May be he is not wokring on this location on this particular day</div>";
      } 
  return $html;
  }
  public function GetEmployees(Request $request)
  {
    /*$employees = DB::table('employees')->join('working_hours', function ($join) use ($request) {
      $join->on('employees.id', '=', 'working_hours.employee_id')
      ->where('working_hours.date', '=', $request->date)
      ->where('working_hours.start_time', '!=', '00:00:00')
      ->where('working_hours.finish_time', '!=', '00:00:00')      
      ->where('working_hours.location_id', '=', $request->location_id);     
    })->orwhere('employees.location_id', '=', $request->location_id)->get();*/
    
    $employees = DB::table('employees')
         ->join('working_hours','employees.id','=','working_hours.employee_id')
        ->join('employee_services','employee_services.employee_id','=','employees.id')
         ->where('working_hours.date', '=', $request->date)
         ->where('working_hours.start_time', '!=', '00:00:00')
         ->where('working_hours.finish_time', '!=', '00:00:00')
         ->whereIn('working_hours.location_id', ['0',$request->location_id])
         ->where('employee_services.service_id', '=', $request->service_id)
         ->groupBy('employees.user_id','employees.id')
         ->get();
    
    $employees_customtiming = DB::table('employees')
         ->join('employee_customtimings','employees.id','=','employee_customtimings.employee_id')
        ->join('employee_services','employee_services.employee_id','=','employees.id')
        ->select('employee_customtimings.start_time as sTime','employee_customtimings.end_time','employees.first_name','employees.last_name','employee_customtimings.employee_id','employees.id')
         ->where('employee_customtimings.date', '=', $request->date)
         ->where('employee_customtimings.start_time', '!=', '00:00:00')
         ->where('employee_customtimings.end_time', '!=', '00:00:00')
         ->whereIn('employee_customtimings.location_id', ['0',$request->location_id])
         ->where('employee_services.service_id', '=', $request->service_id)
         ->get();
     
   /*  $service = DB::table('services')
            ->join('employee_services', 'services.id', '=', 'employee_services.service_id')
            ->select('services.*', 'employee_services.moneybird_username')
            ->where('employee_services.service_id', '=', $request->service_id)
            ->get();*/
         
    $service = \App\Service::find($request->service_id);
    $html = "";
    $html .= "<div class='row employees'>";
    $html .= "<div class='col-xs-12 form-group'>";
    $html .= "<label class='control-label'>No Of Blocks* (Each Block Have ".$service->booking_block_duration." Min) </label>";
    $html .= "<input type='text' class='form-control' data-block-time='".$service->booking_block_duration."' size='10' name='no_of_block' id='no_of_block' value='".$service->min_block_duration."'>";
    $html .= "</div>";
    $html .= "</div>";
    $html .= "<div class='row employees'>";
    $html .= "<div class='col-xs-12 form-group'>";
    $html .= "<label class='control-label'>Employee*</label>";
    $html .= "<ul class='list-inline'>";
    $arrEmployeeIn=array();
    if(is_object($employees) && count($employees) > 0 ):
    foreach($employees as $employee) :
      $arrEmployeeIn[]=$employee->employee_id;
      $html .= "<li><label><input type='radio' name='employee_id' class='employee_id' value='".$employee->employee_id."'> ".$employee->first_name." ".$employee->last_name." (<span class='starting_hour_$employee->id'>".date("H", strtotime($employee->start_time))."</span>:<span class='starting_minute_$employee->id'>".date("i", strtotime($employee->start_time))."</span> - <span class='finish_hour_$employee->id'>".date("H", strtotime($employee->finish_time))."</span>:<span class='finish_minute_$employee->id'>".date("i", strtotime($employee->finish_time))."</span>)</label></li>";
    endforeach;
    endif;
 
            
     $oldvalue=0;$Orderid=0;       
    if(is_object($employees_customtiming) && count($employees_customtiming) > 0 ):
    foreach($employees_customtiming as $employee) :
      if(in_array($employee->employee_id, $arrEmployeeIn))      
      {
       $html .= ""; 
      }
     else
     {
      if($oldvalue!=$employee->employee_id)
         {
           $html .= "<li><label><input type='radio' name='employee_id' class='employee_id' value='".$employee->employee_id."'> ".$employee->first_name." ".$employee->last_name." (<span class='starting_hour_$employee->id'>".date("H", strtotime($employee->sTime))."</span>:<span class='starting_minute_$employee->id'>".date("i", strtotime($employee->sTime))."</span> - <span class='finish_hour_$employee->id'>".date("H", strtotime($employee->end_time))."</span>:<span class='finish_minute_$employee->id'>".date("i", strtotime($employee->end_time))."</span>)</label></li>";
         }
        else{
           $html.="";
         } 
       $oldvalue=$employee->employee_id;
     }
      
    endforeach;  
   endif;
    if(count($employees_customtiming) == 0  &&  count($employees) == 0 )
     {$html .= "<li>No employees working on your selected date</li>";}
    $html .= "</ul>";
    $html .= "</div>";
    $html .= "</div>";
    return $html;
  }
  public function therpistWorkinghour($id)
   { 
      if (! Gate::allows('thrapist_working_hour_create')) {
            return abort(401);
        }
      // $employee = Employee::findOrFail($id);  
        $WorkingHorsArray =  \App\WorkingHour::where('employee_id','=',$id)->where('date','>=',date('Y-m-d'))->get();
       $accordingDays = array();
       if($WorkingHorsArray->count() > 0)
       {
         foreach($WorkingHorsArray as $WorkingHorsArray)
               {
                   $accordingDays[$WorkingHorsArray->days]=array(
                     'start_time' => $WorkingHorsArray->start_time,
                     'finish_time' => $WorkingHorsArray->finish_time,
                     'repeated' => $WorkingHorsArray->repeated,
                     'location_id' => $WorkingHorsArray->location_id,
                   );

               }
       }
        

        $relations = [
         
            'locations' => \App\Location::get()->pluck('location_name', 'id')->prepend('All Location', '0'),
             'working_type' => array('1'=>'Monday','2'=>'Tuesday','3'=>'Wednesday','4'=>'Thursday','5'=>'Friday','6'=>'Saturday','7'=>'Sunday'),
             'empworkinghHours' =>$accordingDays,
             'employee_id' => $id
        ];
       return view('admin.employees.working_hour', $relations);

   }
   public function therpistsaveWorkinghour(Request $request)
   { 
     if (! Gate::allows('thrapist_working_hour_save')) {
            return abort(401);
        }
     $WorkingHorsArray =  \App\WorkingHour::where('employee_id','=',$request->employee_id)->where('date','>=',date('Y-m-d'))->forceDelete();
    
             $date = date('Y-m-d');
    
    $end_date = date('Y-m-d', strtotime('+365 days'));
    $lookup=['Sunday'=>0,'Monday'=>1,'Tuesday'=>2,'Wednesday'=>3,'Thursday'=>4,'Friday'=>5,'Saturday'=>6];
    
    while (strtotime($date) <= strtotime($end_date)) {
     
                    
                    $dayName =  date('l',strtotime($date));
                    $locationId =  $request->working_location_id[$dayName];
                    $start_time =  $request->booking_pricing_time_from[$dayName];
                   
                   $workingHoursDays = WorkingHour::where('employee_id','=',$request->employee_id)->where('days','=',$dayName)->where('date','=',$date)->get(); 

                    

                   // $date = date ("Y-m-d", strtotime($date));
             if($workingHoursDays->count() == 0)
                       {
                         $working_hour = WorkingHour::create([
                       'employee_id' => $request->employee_id,
                       'date' => $date,
                       'days' => $dayName,
                         
                       'location_id' => $request->working_location_id[$dayName],  
                       'start_time' => isset($request->booking_pricing_time_from[$dayName]) ? date('H:i:s',strtotime($request->booking_pricing_time_from[$dayName])) : "00:00:00",
                       'finish_time' => isset($request->booking_pricing_time_to[$dayName]) ? date('H:i:s',strtotime($request->booking_pricing_time_to[$dayName])) : "00:00:00"
                      
                      ]);
                       }
                       $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
    }
      return redirect()->route('admin.employees_working_hour.create',[$request->employee_id]);

   }
   public function GetRoomsLocation(Request $request)
   {
           $html = '';
            //$rooms = City::where('country_id', $request->country_id)->get();
            $rooms = DB::table('rooms')->join('rooms_locations','rooms_locations.room_id','=','rooms.id')
           ->select('rooms.id as room_id','room_name')->where('rooms_locations.location_id','=',$request->location_id)->get();
            foreach ($rooms as $room) {
                $html .= '<option value="'.$room->room_id.'_'.$request->location_id.'" >'.$room->room_name.'</option>';
            }
        
        return response()->json(['html' => $html]);
   }
}
