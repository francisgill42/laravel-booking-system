<?php

namespace App\Http\Controllers\Admin;

use App\Appointment;
use App\EmailTemplate;
use App\Client;
use App\Employee;
use App\Service;
use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAppointmentsRequest;
use App\Http\Requests\Admin\UpdateAppointmentsRequest;
use Moneybird;  
use \App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Date;
//use Illuminate\Support\Facades\Casdr\Moneybird;

//use Picqer\Financials\Moneybird\Moneybird;
use DB;
use Mail;

class AppointmentsController extends Controller
{
    
    /**
     * Display a listing of Appointment.
     *
     * @return \Illuminate\Http\Response
     */
    
    
    public function index()
    {
        if (! Gate::allows('appointment_access')) {
            return abort(401);
        }

 
    // Moneybird::setAdministrationId($administrations[0]['id']);

//$contact->save();
       //   dd(Moneybird::contact());
        $user = \Auth::user();   
     //Hash::make($password);
       // $clinet = App\Client::where('deleted_at','=',NULL)->where('add_by','=',$user->role_id)->count(); 
        if($user->role_id == 3)
         {  
           $therapist = App\Employee::where('deleted_at','=',NULL)->where('user_id','=',$user->id)->get();
          $employee_id = $therapist[0]->id;
          
          $appointments = Appointment::where('employee_id','=',$employee_id)->orderBy('id','desc')->get();

          }
        else
         {$appointments = Appointment::orderBy('id','desc')->get();}

         $status= array('pending'=>'Pending','booking_confirmed'=>'Booking Confirm','booking_paid'=>'Booking Paid','booking_unpaid'=>'Booking Unpaid','cash_paid'=>'Cash payment');
          $relations = [
            'clients' => \App\Client::get(),
            'employees' => \App\Employee::get(),
            'services' => \App\Service::get(),
            'location' => \App\Location::get(),
            'room' => \App\Room::get(),
            'booking_status' => $status
        ];
        return view('admin.appointments.index', compact('appointments') + $relations);
    }

    /**
     * Show the form for creating new Appointment.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! Gate::allows('appointment_create')) {
            return abort(401);
        }
        $user = \Auth::user();   
     //Hash::make($password);
       // $clinet = App\Client::where('deleted_at','=',NULL)->where('add_by','=',$user->role_id)->count(); 
        if($user->role_id == 3)
         {  
          $therapist = App\Employee::where('deleted_at','=',NULL)->where('user_id','=',$user->id)->get();
          $employee_id = $therapist[0]->id;
          $clients = Client::where('deleted_at','=',NULL)->where('add_by','=',$employee_id)->get();
          $clientsOther = App\Appointment::join('clients', 'clients.id', '=', 'appointments.client_id')->select('clients.id','clients.first_name','clients.last_name','clients.phone','clients.email','moneybird_contact_id')->where('employee_id','=',$employee_id)->get();
           $result = $clients->merge($clientsOther);
           $clients = $result->all();
           $parentClient = Client::select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')
 ->where('deleted_at','=',NULL)->where('add_by','=',$employee_id)->where('parent_id','=',0)->get()->pluck('name', 'id')->prepend('Please select', 0);
         } 
         else
         {
          $clients =  \App\Client::get();
          $parentClient= Client::select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')
 ->where('deleted_at','=',NULL)->where('parent_id','=',0)->get()->pluck('name', 'id')->prepend('Please select', 0);
         }
        
        $relations = [
            'clients' => $clients,
            'employees' => \App\Employee::get(),
			'services' => \App\Service::get(),
            'locations' => \App\Location::get(),
            'parentClient' => $parentClient
        ];
        //dd($relations);

        return view('admin.appointments.create', $relations);
    }

    /**
     * Store a newly created Appointment in storage.
     *
     * @param  \App\Http\Requests\StoreAppointmentsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAppointmentsRequest $request)
    {
     
        if (! Gate::allows('appointment_create')) {
            return abort(401);
        }
        
		$employee = \App\Employee::find($request->employee_id);
        
   /* echo "Date ". date("d", strtotime($request->date));
    echo "<br>";
    echo " Time ".date("H:i:s", strtotime("".$request->starting_time.":00"));
    echo "<br>";
    echo "Employee Id ".$request->employee_id;*/

		$working_hours     = \App\WorkingHour::where('employee_id', $request->employee_id)->whereDate('date', '=', date("Y-m-d", strtotime($request->date)))->whereTime('start_time', '<=', date("H:i:s", strtotime("".$request->starting_time.":00")))->get();
  
  $working_custom_hours     = \App\EmployeeCustomtiming::where('employee_id', $request->employee_id)->whereDate('date', '=', date("Y-m-d", strtotime($request->date)))->whereTime('start_time', '<=', date("H:i:s", strtotime("".$request->starting_time.":00")))->get();

//dd($working_hours);
       // dd($employee);
		if(!$employee->provides_service($request->service_id))
        {
            return redirect()->back()->withErrors("This employee doesn't provide your selected service")->withInput();
        }

        if($working_hours->isEmpty() && $working_custom_hours->isEmpty()) return redirect()->back()->withErrors("This employee isn't working at your selected time")->withInput();

       $clientName = \App\Client::find($request->client_id);
    
        $client_name = $clientName->first_name." ".$clientName->last_name;
        $clientemail = $clientName->email;
        $clientphone = $clientName->phone;
        $client_house_number = $clientName->house_number;
        $client_address = $clientName->adderss;
        $moneybird_contact_id = $clientName->moneybird_contact_id;
       
        $thrapist_name = $employee->first_name." ".$employee->last_name; 
        $thrapist_email = $employee->email; 
        $therapisttelephone = $employee->phone; 
         

      $contactSearchObject = Moneybird::contact();
       // $moneybird_contact_id='271375336926610863'; 

       // $moneybird_contact_id='271375336926610863'; 
       $contactSearchObject = $contactSearchObject->search($clientName->email);
       if(empty($contactSearchObject))
       {
          $contactObject = Moneybird::contact();
          $contactObject->company_name = 'Ecybertech';
          $contactObject->firstname = $clientName->first_name;
          $contactObject->lastname = $clientName->last_name;
          $contactObject->send_estimates_to_email = $clientName->email;
          $contactObject->send_invoices_to_email = $clientName->email;
          $contactObject->save();  

          $clientName->moneybird_contact_id= $contactObject->id;
          $clientName->save();
       }
 $tharpy = \App\Service::find($request->service_id);
 //$countRoom =  DB::table('rooms_locations')->where('location_id', [$request->location_id])->get();
 /*default room order always 1
 echo "Location ID ".$request->location_id;
 echo "<br>";
 echo "Employe ID ".$request->employee_id;
 echo "<br>";*/
 $countRoom =  DB::table('employees_rooms')->where('location_id', [$request->location_id])->where('employee_id', [$request->employee_id])->orderBy('orders', 'asc')->get();
 $room_id = 0;
//echo "<pre>";print_r($countRoom);exit;
/*echo "<br>";
echo "location Id ".$request->location_id;
echo "<br>";*/
 if(count($countRoom) > 0)
    {
 
       $block_timing  = $tharpy->booking_block_duration;
       $tharpy_registration_no='';
       if(isset($employee->registration_no))
        {$tharpy_registration_no  = $employee->registration_no;}

       $therapistdes='';$therapistdes2='';
       if(isset($tharpy->description))
        {$therapistdes  = $tharpy->description;}
     if(isset($tharpy->description_second))
        {$therapistdes2  = $tharpy->description_second;}

       $no_of_block  = $request->no_of_block;
       $TimeTakenbytherapy_room = $no_of_block * $block_timing;
       $start_timeing_room = "".$request->date." ".$request->starting_time.":00";
       $end_Time_room = "".$request->date." ".date("H:i:s",strtotime("+".$TimeTakenbytherapy_room." minutes", strtotime($request->starting_time.":00")));;
      
      foreach ($countRoom as $key => $value) {
       /* echo "start_timeing_room ".$start_timeing_room;
        echo "<br>";
        echo "finish Time ".$end_Time_room;
         echo "<br>";*/
         $already_location_room_booking = DB::table('appointments')->select('*')
            ->where(function($query) use ($start_timeing_room,$end_Time_room){
              $query->Where('appointments.start_time', '>=', $start_timeing_room);
              $query->Where('appointments.finish_time', '<=', $end_Time_room);
          })
       ->where('appointments.location_id', [$request->location_id])->where('appointments.room_id', [$value->room_id])->get();
 
 //echo "<pre>";print_r($already_location_room_booking);
 //echo "<br>";
           if(count($already_location_room_booking)==0)
             {
                $room_id = $value->room_id;
                break;
             }

          }
      
    }
    else
    {
       return redirect()->back()->withErrors("Please assign to this location before add appointments")->withInput();
    }
  //echo $room_id;exit;
  if($room_id==0)
  {
    return redirect()->back()->withErrors("No room available for this appointment")->withInput();
  }
            
 
 
 $block_timing  = $tharpy->booking_block_duration;
 $no_of_block  = $request->no_of_block;
 $TimeTakenbytherapy = $no_of_block * $block_timing;
 
//echo "".$request->date." ".$request->starting_time.":00";exit;
		$appointment = new Appointment;
		$appointment->client_id = $request->client_id;
    $appointment->room_id = $room_id;
        $appointment->repeat_appointment = $request->repeat_appointment;
        $appointment->repeat_appointment_no = isset($request->repeat_appointment_no) ? $request->repeat_appointment_no : 0;
		$appointment->employee_id = $request->employee_id;
        $appointment->location_id = $request->location_id;
        $appointment->service_id = $request->service_id;
        $appointment->switched_off_reminder_email = $request->switched_off_reminder_email;
        $appointment->switched_off_confirmed_email = $request->switched_off_confirmed_email;
		$appointment->start_time = "".$request->date." ".$request->starting_time.":00";
        $endTime = date("H:i:s",strtotime("+".$TimeTakenbytherapy." minutes", strtotime($request->starting_time.":00")));
		$appointment->finish_time = "".$request->date." ".$endTime."";
		$appointment->comments = $request->comments;
        $appointment->price = $request->price;
        $appointment->booking_status = '';
        $appointment->status = 'booking_confirmed';
        // dd($appointment);
		$appointment->save();
    $dateArray=array();
    if( isset($request->repeated_number) &&  $request->repeated_number > 0)
      {
       
         for($i=1; $i <= $request->repeated_number; $i++)     
            {  
               
               
               $appointmentL = new Appointment;
               $appointmentL->client_id = $request->client_id;
               $appointmentL->repeat_appointment = '';
               $appointmentL->repeat_appointment_no = '-1';
               $appointmentL->employee_id = $request->employee_id;
               $appointmentL->location_id = $request->location_id;
               $appointmentL->service_id = $request->service_id;
               $appointmentL->switched_off_reminder_email = $request->switched_off_reminder_email;
               $appointmentL->switched_off_confirmed_email = $request->switched_off_confirmed_email;  
               if($request->repeat_appointment=='weekly')
                {
                  $days = 7 * $i;   
                  $appointmentstart_time = date('Y-m-d', strtotime($request->date. ' + '.$days.' days'));
                  $appointmentend_time = date('Y-m-d', strtotime($request->date. ' + '.$days.' days'));
                  $appointmentL->start_time = "".$appointmentstart_time." ".$request->starting_time.":00";
                  $appointmentL->finish_time = "".$appointmentend_time." ".$endTime."";
                }
               else if($request->repeat_appointment=='daily')
                {
                  $days = 1 * $i;   
                  $appointmentstart_time = date('Y-m-d', strtotime($request->date. ' + '.$days.' days'));
                  $appointmentend_time = date('Y-m-d', strtotime($request->date. ' + '.$days.' days'));
                  $appointmentL->start_time = "".$appointmentstart_time." ".$request->starting_time.":00";
                  $appointmentL->finish_time = "".$appointmentend_time." ".$endTime."";
                }
                else if($request->repeat_appointment=='monthly')
                {
                  $days = 30 * $i;   
                  $appointmentstart_time = date('Y-m-d', strtotime($request->date. ' + '.$days.' days'));
                  $appointmentend_time = date('Y-m-d', strtotime($request->date. ' + '.$days.' days'));
                  $appointmentL->start_time = "".$appointmentstart_time." ".$request->starting_time.":00";
                  $appointmentL->finish_time = "".$appointmentend_time." ".$endTime."";
                }
                else if($request->repeat_appointment=='gap')
                {
                  $days = 14 * $i;   
                  $appointmentstart_time = date('Y-m-d', strtotime($request->date. ' + '.$days.' days'));
                  $appointmentend_time = date('Y-m-d', strtotime($request->date. ' + '.$days.' days'));
                  $appointmentL->start_time = "".$appointmentstart_time." ".$request->starting_time.":00";
                  $appointmentL->finish_time = "".$appointmentend_time." ".$endTime."";
                }
                 $timeDate =$appointmentL->start_time;
               $appointmentL->comments = $request->comments;
               $appointmentL->price = $request->price;
               $appointmentL->booking_status = '';
               $appointmentL->status = 'booking_confirmed';
               $working_hours     = \App\WorkingHour::where('employee_id', $request->employee_id)->where('date', '=', $appointmentstart_time)->whereTime('start_time', '<=', date("H:i", strtotime("".$request->starting_time.":00")))->get();
              //echo "Working Hour ".$working_hours;
             

               $employee_leave  = \App\EmployeeLeave::where('employee_id', $request->employee_id)->where('leave_date', '=', $appointmentstart_time)->get();

               $appointment_date = \App\Appointment::where('employee_id', $request->employee_id)->whereDay('start_time', '=', $timeDate)->
                  get();
           
           if($working_hours->isEmpty() || count($employee_leave) > 0 || count($appointment_date) > 0)
                {
                    
                   $dateArray[]=$appointmentstart_time;
                }
               else {
               
                  $appointmentL->save();      # code...
                } 
               
            } 
      }
   
  
    

  
   $tharpy_price = DB::table('services')
         ->leftjoin('service_extra_cost','services.id','=','service_extra_cost.service_id')        
         ->where('services.id', '=', $request->service_id)
         ->get();
    //dd($tharpy_price);
        $totalCost=0; 
    foreach($tharpy_price as $tharpy_price)
          {
            $extrCost= true;
             $serviceName = $tharpy_price->name;
             $duration_block = $tharpy_price->booking_block_duration;
             $no_of_block = $tharpy_price->min_block_duration;
             $block_cost = $tharpy_price->block_cost;
             $block_types = $tharpy_price->booking_series_type;
             $extra_cost_unit = $tharpy_price->booking_block_cost_duration_type_unit;
             $extra_cost_price_startTime = $tharpy_price->booking_pricing_time_from;
             $sessionCost = $tharpy_price->block_cost *  $no_of_block;
             if($extrCost)
              { $totalCost = $tharpy_price->block_cost *  $no_of_block;}
            
             
             if($request->starting_time.":00" > $extra_cost_price_startTime && $extrCost)
                { 
                  $extrCost=false;
                   $totalCost = $totalCost  + ($tharpy_price->booking_block_pricing *  $no_of_block);
                }

             $extra_cost_price_endTime = $tharpy_price->booking_pricing_time_to;
          }
    $tharpyname = $tharpy->name;
    $locations= \App\Location::find($request->location_id);
    $locationname = $locations->location_name;
    $location_address = $locations->location_address;
    $locationdesc = $locations->location_description;
   
    $email_customer_template = DB::table('email_templates')
          ->select('*')
         ->where('email_templates.email_type', '=', 'confirmation_customer_email')->get();
    $matter = $email_customer_template[0]->email_content;
    $email_subject = $email_customer_template[0]->email_subject;
    $bcc_email_id = $email_customer_template[0]->email_id;

    if(!empty(trim($client_name)))
     {$matter = str_replace("{clientname}",$client_name,$matter);}
    else
      {$matter = str_replace("{clientname}",'',$matter);} 
   
   if(!empty(trim($thrapist_name)))
      {$matter = str_replace("{therapistname}",$thrapist_name,$matter);}
    else
      {$matter = str_replace("{therapistname}",'',$matter);}

    if(!empty(trim($clientemail)))
      {$matter = str_replace("{customeremail}",$clientemail,$matter);}
    else
      {$matter = str_replace("{customeremail}",'',$matter);}
    
    if(!empty(trim($clientphone)))
      {$matter = str_replace("{customertelephonenumber}",$clientphone,$matter);}
    else
      {$matter = str_replace("{customertelephonenumber}",'',$matter);}


 if(!empty(trim($therapistdes)))
      {$matter = str_replace("{therapistdes}",$therapistdes,$matter);}
    else 
      {$matter = str_replace("{therapistdes}",'',$matter);}

if(!empty(trim($tharpy_registration_no)))
      {$matter = str_replace("{therapistregistrations}",$tharpy_registration_no,$matter);}
    else 
      {$matter = str_replace("{therapistregistrations}",'',$matter);}
 

    if(!empty(trim($therapistdes2)))
      {$matter = str_replace("{therapistdes2}",$therapistdes2,$matter);}
    else 
      {$matter = str_replace("{therapistdes2}",'',$matter);}

    if(!empty(trim($tharpyname)))
      {$matter = str_replace("{thrapyname}",$tharpyname,$matter);}
    else 
      {$matter = str_replace("{thrapyname}",'',$matter);}
    
    if(!empty(trim($locationname)))
      {$matter = str_replace("{location}",$locationname,$matter);}
    else 
      $matter = str_replace("{location}",'',$matter);
    
    if(!empty(trim($totalCost)))
       {$matter = str_replace("{tharpycost}",$totalCost,$matter);}
     else 
      {$matter = str_replace("{tharpycost}",'',$matter);}

    if(!empty(trim($locationdesc)))  
     {$matter = str_replace("{route_directions}",$locationdesc,$matter);}
   else
    {$matter = str_replace("{route_directions}",'',$matter);}

   if(!empty(trim($location_address)))  
     {$matter = str_replace("{location_address}",$location_address,$matter);}
   else
      {$matter = str_replace("{location_address}",'',$matter);}

      if(!empty(trim($therapisttelephone)))
        {$matter = str_replace("{therapisttelephone}",$therapisttelephone,$matter);}
      else{
          {$matter = str_replace("{therapisttelephone}",'',$matter);}
        }  
   if(!empty(trim($sessionCost)))
     {$matter = str_replace("{session_costs_for_an_hour}",$sessionCost,$matter);}
   else 
     $matter = str_replace("{session_costs_for_an_hour}",'',$matter);
     
     Date::setLocale('nl');
   //$matter = str_replace("{booking_date}","".date('l d F Y',strtotime($request->date))." ".$request->starting_time,$matter);
 
    $matter = str_replace("{booking_date}","".Date::parse($request->date)->format('l j F Y'),$matter);
    $matter = str_replace("{booking_time}","".$request->starting_time,$matter);


    $email_therapist_template = DB::table('email_templates')
          ->select('*')
         ->where('email_templates.email_type', '=', 'confirmation_therapist_email')->get();
    $therapist_matter = $email_therapist_template[0]->email_content;
    $email_therapist_subject = $email_therapist_template[0]->email_subject;
  
   if(!empty(trim($client_name)))
    {$therapist_matter = str_replace("{clientname}",$client_name,$therapist_matter);}
   else 
     {$therapist_matter = str_replace("{clientname}",'',$therapist_matter);}

  if(!empty(trim($thrapist_name)))  
    {$therapist_matter = str_replace("{therapistname}",$thrapist_name,$therapist_matter);}
  else
    {$therapist_matter = str_replace("{therapistname}",'',$therapist_matter);}
  
  if(!empty(trim($tharpy_registration_no)))
      {$therapist_matter = str_replace("{therapistregistrations}",$tharpy_registration_no,$therapist_matter);}
    else 
      {$therapist_matter = str_replace("{therapistregistrations}",'',$therapist_matter);}
 
   if(!empty(trim($clientemail)))
      {$therapist_matter = str_replace("{customeremail}",$clientemail,$therapist_matter);}
    else
      {$therapist_matter = str_replace("{customeremail}",'',$therapist_matter);}
    
    if(!empty(trim($clientphone)))
      {$therapist_matter = str_replace("{customertelephonenumber}",$clientphone,$therapist_matter);}
    else
      {$therapist_matter = str_replace("{customertelephonenumber}",'',$therapist_matter);}



   if(!empty(trim($therapistdes)))
      {$therapist_matter = str_replace("{therapistdes}",$therapistdes,$therapist_matter);}
    else 
      {$therapist_matter = str_replace("{therapistdes}",'',$therapist_matter);}

    if(!empty(trim($therapistdes2)))
      {$therapist_matter = str_replace("{therapistdes2}",$therapistdes2,$therapist_matter);}
    else 
      {$therapist_matter = str_replace("{therapistdes2}",'',$therapist_matter);}

   if(!empty(trim($tharpyname)))  
    {$therapist_matter = str_replace("{thrapyname}",$tharpyname,$therapist_matter);}
   else 
     {$therapist_matter = str_replace("{thrapyname}",'',$therapist_matter);}

   if(!empty(trim($locationname)))
    {$therapist_matter = str_replace("{location}",$locationname,$therapist_matter);}
   else
    {$therapist_matter = str_replace("{location}",'',$therapist_matter);}

  if(!empty(trim($totalCost)))
    {$therapist_matter = str_replace("{tharpycost}",$totalCost,$therapist_matter);}
  else
    {$therapist_matter = str_replace("{tharpycost}",'',$therapist_matter);}

    if(!empty($locationdesc))  
     {$therapist_matter = str_replace("{route_directions}",$locationdesc,$therapist_matter);}
    else
     {$therapist_matter = str_replace("{route_directions}",'',$therapist_matter);}
      
    if(!empty($location_address))  
     {$therapist_matter = str_replace("{location_address}",$location_address,$therapist_matter);}
    else
      {$therapist_matter = str_replace("{location_address}",'',$therapist_matter);}

    if(!empty($therapisttelephone))
      {$therapist_matter = str_replace("{therapisttelephone}",$therapisttelephone,$therapist_matter);}
    else
      {$therapist_matter = str_replace("{therapisttelephone}",'',$therapist_matter);} 

   if(!empty($sessionCost))
     {$therapist_matter = str_replace("{session_costs_for_an_hour}",$sessionCost,$therapist_matter);}
    else
      {$therapist_matter = str_replace("{session_costs_for_an_hour}",'',$therapist_matter);}

    //$therapist_matter = str_replace("{booking_date}","".date('l d F Y',strtotime($request->date))." ".$request->starting_time,$therapist_matter);

  $therapist_matter = str_replace("{booking_date}","".Date::parse($request->date)->format('l j F Y'),$therapist_matter);
    $therapist_matter = str_replace("{booking_time}","".$request->starting_time,$therapist_matter);

    /*$data = array('name'=>"Virat Gandhi");
      Mail::send(['text'=>'mail'], $matter, function($message) {
         $message->to('bohra.shard@gmail.com', 'Tutorials Point')->subject
            ('Laravel HTML Testing Mail');
         $message->from('info@ecybertech.com','Sharad');
      });*/
 
  if(empty($request->switched_off_confirmed_email))
    {
     
         Mail::send([], [], function ($message) use ($clientemail,$email_subject,$matter) {
              $message->to($clientemail)
                ->subject($email_subject)
                ->setBody($matter, 'text/html'); // for HTML rich messages
            }); 
    }
   
   if(!empty($bcc_email_id))
     {
        Mail::send([], [], function ($message) use ($clientemail,$email_subject,$matter) {
              $message->to($clientemail)
                ->subject($email_subject)
                ->setBody($matter, 'text/html'); // for HTML rich messages
            });
         
          Mail::send([], [], function ($message) use ($thrapist_email,$email_therapist_subject,$therapist_matter) {
          $message->to($thrapist_email)
            ->subject($email_therapist_subject)
            ->setBody($therapist_matter, 'text/html'); // for HTML rich messages
        }); 

     }
 
    Mail::send([], [], function ($message) use ($thrapist_email,$email_therapist_subject,$therapist_matter) {
  $message->to($thrapist_email)
    ->subject($email_therapist_subject)
    ->setBody($therapist_matter, 'text/html'); // for HTML rich messages
});

    //echo $matter;exit;
      
        if(count($dateArray) > 0)     
            {
                $dates = implode(',', $dateArray);
                return redirect()->route('admin.appointments.index')->with('msg', 'Appointment did not created on dates due to therpist leave or bussy with another appointment'.$dates);
            }
           else {
               return redirect()->route('admin.appointments.index');
            } 
        
    }


    /**
     * Show the form for editing Appointment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('appointment_edit')) {
            return abort(401);
        }
        $relations = [
            'clients' => \App\Client::get()->pluck('first_name', 'id')->prepend('Please select', ''),
            'employees' => \App\Employee::get()->pluck('first_name', 'id')->prepend('Please select', ''),
            'services' => \App\Service::get()->pluck('name', 'id')->prepend('Please select', ''),  
            'locations' => \App\Location::get()->pluck('location_name', 'id')->prepend('Please select', ''),
        ];

        $appointment = Appointment::findOrFail($id);
        
        if(isset($appointment->booking_status) && ($appointment->booking_status !='booking_paid' &&  $appointment->booking_status !='cash_paid'))
         {return view('admin.appointments.edit', compact('appointment') + $relations);}
         else
         {return redirect()->route('admin.appointments.index');}
    }

    /**
     * Update Appointment in storage.
     *
     * @param  \App\Http\Requests\UpdateAppointmentsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAppointmentsRequest $request, $id)
    {
        if (! Gate::allows('appointment_edit')) {
            return abort(401);
        }

        $appointment = Appointment::findOrFail($id);
        $appointment->client_id = $request->client_id;
        $appointment->employee_id = $request->employee_id;
        $appointment->location_id = $request->location_id;
        $appointment->service_id = $request->service_id;
        $appointment->price = $request->price;
        $appointment->start_time = "".$request->date." ".$request->starting_time.":00";
        $endTime = date("H:i:s",strtotime("+60 minutes", strtotime($request->starting_time.":00")));
        $appointment->finish_time = "".$request->date." ".$endTime."";
        $appointment->comments = $request->comments;
      // dd($appointment);
        //$appointment->save();
          
        $appointment->update();



        return redirect()->route('admin.appointments.index');
    }


    /**
     * Display Appointment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('appointment_view')) {
            return abort(401);
        }
        $appointment = Appointment::findOrFail($id);

        $relations = [
            'employee_service' => \App\EmployeeService::where('service_id','=',$appointment->service_id)->where('employee_id','=',$appointment->employee_id)->whereNull('deleted_at')->get(),
            'email_templates' => \App\EmailTemplate::whereNull('email_type')->get()->pluck('email_subject', 'id')->prepend('Please select email template', ''),
        ];

  //dd($relations['employee_service']);

       return view('admin.appointments.show', compact('appointment') + $relations);
    }


    /**
     * Remove Appointment from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('appointment_delete')) {
            return abort(401);
        }
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->route('admin.appointments.index');
    }

    /**
     * Delete all selected Appointment at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('appointment_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Appointment::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

    public function sendcustomemail(Request $request)
    {
       $email_templates_id = $request->email_templates;
       $appointment_id = $request->appointment_id;

        $appointment = Appointment::where('id','=', $appointment_id)->get();
        $client_id = $appointment[0]->client_id;
        $employee_id = $appointment[0]->employee_id;
        $location_id = $appointment[0]->location_id;
        $booking_date = date('d-m-Y',strtotime($appointment[0]->start_time));
        $service_id = $appointment[0]->service_id;
        
        $clientdetail = Client::where('id','=', $client_id)->get(); 
        $clientname   = $clientdetail[0]->first_name ." ". isset($clientdetail[0]->last_name) ? $clientdetail[0]->last_name : '';
        $clientemail = $clientdetail[0]->email;
        $address = $clientdetail[0]->address;
        $clientphone = $clientdetail[0]->phone;

        $employeedetail = Employee::where('id','=', $employee_id)->get(); 
        $therapistname  = $employeedetail[0]->first_name." ". isset($employeedetail[0]->first_name) ? $employeedetail[0]->first_name : '';
        $therapisttelephone = $employeedetail[0]->phone;
        $locationstreetname = $employeedetail[0]->address;
        $thrapist_email = $employeedetail[0]->email;
        $therapistregistrations = $employeedetail[0]->registration_no;
        $servicedetail = Service::where('id','=', $service_id)->get(); 
        $thrapyname   = $servicedetail[0]->name;
        $therapistdes   = $servicedetail[0]->description;

        $locationdetail = Location::where('id','=', $location_id)->get(); 
        $location   = $locationdetail[0]->location_name;
        $route_directions   = $locationdetail[0]->location_description  ;
       
        
        $email_templates = EmailTemplate::where('id','=', $email_templates_id)->get();
        $matter = $email_templates[0]->email_content;
        $email_subject = $email_templates[0]->email_subject;

       /*  1 Customer name = {clientname} 
2 booking time and date = {booking_date} 
3 therapist name = {therapistname} 
4 therapy name = {thrapyname} 
5 therapist title = {therapistitle} 
6 therapist telephone number = {therapisttelephone} 
7 location streetname = {locationstreetname} 
8 location city = {location} 
9 route direction to location = {route_directions} 
10 therapist registrations = {therapistregistrations} 
11 therapy discription = {therapistdes} */
      if(!empty($client_name))  
        {$matter = str_replace("{clientname}",$client_name,$matter);}
      else
        {$matter = str_replace("{clientname}",'',$matter);}
      
      if(!empty(trim($clientemail)))
      {$matter = str_replace("{customeremail}",$clientemail,$matter);}
      else
      {$matter = str_replace("{customeremail}",'',$matter);}
    
    if(!empty(trim($clientphone)))
      {$matter = str_replace("{customertelephonenumber}",$clientphone,$matter);}
    else
      {$matter = str_replace("{customertelephonenumber}",'',$matter);}

       if(!empty($therapistname))
         {$matter = str_replace("{therapistname}",$therapistname,$matter);}
       else
         {$matter = str_replace("{therapistname}",'',$matter);}

       if(!empty($tharpyname))
         {$matter = str_replace("{thrapyname}",$tharpyname,$matter);}
        else
          {$matter = str_replace("{thrapyname}",'',$matter);}

       if(!empty($locationname))
         {$matter = str_replace("{location}",$locationname,$matter);}
        else 
         {$matter = str_replace("{location}",'',$matter);}

       if(!empty($route_directions))
         {$matter = str_replace("{route_directions}",$route_directions,$matter);}
       else
        {$matter = str_replace("{route_directions}",'',$matter);}
        
        if(!empty($therapisttelephone))
         {$matter = str_replace("{therapisttelephone}",$therapisttelephone,$matter);}
       else
        {$matter = str_replace("{therapisttelephone}",'',$matter);}

        if(!empty($therapistregistrations))
          {$matter = str_replace("{therapistregistrations}",$therapistregistrations,$matter);}
        else
          {$matter = str_replace("{therapistregistrations}",'',$matter);}

        if(!empty($therapistname))
         {$matter = str_replace("{therapistname}",$therapistname,$matter);}
        else
          {$matter = str_replace("{therapistname}",'',$matter);}

        
        if(!empty($therapistdes))
          {$matter = str_replace("{therapistdes}",$therapistdes,$matter);}
        else
          {$matter = str_replace("{therapistdes}",'',$matter);}

          Mail::send([], [], function ($message) use ($clientemail,$email_subject,$matter) {
  $message->to($clientemail)
    ->subject($email_subject)
    ->setBody($matter, 'text/html'); // for HTML rich messages
});

      return redirect()->route('admin.appointments.index');
        //dd($request);
    }
    public function changeinvoicestatus($id,$status)
    {
        
        if (! Gate::allows('appointment_view')) {
            return abort(401);
        }
      
      $appointment = Appointment::findOrFail($id);
      $clientdetail = \App\Client::where('id','=',$appointment->client_id)->get();
      $employedetail = \App\Employee::where('id','=',$appointment->employee_id)->get();
      $employeservice_details =  \App\EmployeeService::where('service_id','=',$appointment->service_id)->where('employee_id','=',$appointment->employee_id)->whereNull('deleted_at')->get();
      $service_tax_rate = \App\Service::where('id','=',$appointment->service_id)->whereNotNull('tax_rate_id_moneybrid')->get();
      
      //$tax_rate_id_moneybrid
       if($employeservice_details[0]->moneybird_username=='') return redirect()->back()->withErrors("Moneybird Username not created yet please create that and work on invoice")->withInput();

        if($clientdetail[0]->moneybird_contact_id=='') return redirect()->back()->withErrors("Contact id for this client is not generted on moneybird, please generate that id and work on invoice")->withInput();
     
     if($employedetail[0]->moneybird_key=='') return redirect()->back()->withErrors("Document Id for moneybrid did not associate with this employee so please add that document id in employee and then work on Moneybird Invoice creation ")->withInput();

         Date::setLocale('nl');
    
      #invoiceId='271466283083498633';
      if(!isset($appointment->moneybird_invoice_id) && !isset($appointment->moneybird_id))
      {

          $salesInvoice = Moneybird::salesInvoice();
          $documentID =  $employedetail[0]->moneybird_key;
          $descriptadd =  $employeservice_details[0]->moneybird_username;
          $salesInvoice->contact_id   = $clientdetail[0]->moneybird_contact_id;
          $salesInvoice->document_style_id   = $documentID;
          $salesInvoice->invoice_date = date('d F Y',strtotime($appointment->start_time));
          $salesInvoice->currency = 'EUR';
          $line = Moneybird::SalesInvoiceDetail();
          //$matter = str_replace("{booking_date}","".,$matter);

          $line->description = $descriptadd."<br/> Afspraakdatum: ".Date::parse($appointment->start_time)->format('d F Y')."";
          $line->price = $appointment->price;
          if(count($service_tax_rate) > 0)
           { $line->tax_rate_id = $service_tax_rate[0]->tax_rate_id_moneybrid; }

          $salesInvoice->details = [$line];
          $salesInvoice->save();  
          $salesInvoice->sendInvoice(); 
          $totalPayment = $salesInvoice->total_unpaid; 
          $moneybird_id = $salesInvoice->id; 
          $moneybird_invoice_id = $salesInvoice->invoice_id; 

      }
     else
       {
         $totalPayment = $appointment->total_unpaid; 
          $moneybird_id = $appointment->moneybird_id; 
          $moneybird_invoice_id = $appointment->moneybird_invoice_id; 
       } 
      
          if($status=='paid' || $status=='cash_paid')
          {
            $salesInvoicePayment = Moneybird::salesInvoicePayment();
            $salesInvoicePayment->price = $totalPayment;
            $salesInvoicePayment->payment_date = date('d-m-Y',strtotime($appointment->start_time));
            $salesInvoiceRegister = Moneybird::salesInvoice()->find($moneybird_id);
            $salesInvoiceRegister->registerPayment($salesInvoicePayment);
          }
        $appointment->moneybird_id = $moneybird_id;
        $appointment->moneybird_invoice_id = $moneybird_invoice_id;
        if($status=='paid')
        { $appointment->booking_status = 'booking_paid';}
       else if($status=='cash_paid')
        { $appointment->booking_status = 'cash_paid';}
       else
        { $appointment->booking_status = 'booking_unpaid';}
      $appointment->save();
     
      return redirect()->route('admin.appointments.index');
     
    }
    public function UpdateAppointmentStatus(Request $request)
    {
       $appointmentId =  $request->appointment_id;
       $appointment_status =  $request->appointment_status;
       $appointment = Appointment::findOrFail($appointmentId);
       $appointment->status = $request->appointment_status;
       $appointment->update();
       return "success";
      
    }
    public function afterMoneybirdAuth(Request $request)
    {
       //dd($request);
        $code = $request['code'];
        

    //  7c425890af924c32635efde7a0be03212a940c5ec1aa0bff8f4c1a9ccb9d05bc
     // $connection->setAuthorizationCode($code);
     //  dd(Moneybird::connect());
         /*   $connection = Moneybird::connect();
            $connection->setRedirectUrl(config('moneybird.redirect_uri'));
            $connection->setClientId(config('moneybird.client_id'));
            $connection->setClientSecret(config('moneybird.client_secret')); 
            $connection->setAuthorizationCode($code);
              try {
                $connection->connect();
            } catch (\Exception $e) {
                throw new Exception('Could not connect to Moneybird: ' . $e->getMessage());
            }
           $connection->setAccessToken($connection->getAccessToken());
            // Save the new tokens for next connections
           $moneybird = new Moneybird($connection);
           $administrations = $moneybird->administration()->getAll();
           dd($administrations);
           return $moneybird;*/

    }
    public function appointmentjson(Request $request)
    {
       $user =   Auth::getUser();
     /*
 $therapist = App\Employee::where('deleted_at','=',NULL)->where('user_id','=',$user->id)->get(); 
      $employee_id = $therapist[0]->id;  
     */
    // echo  $request->Dates;exit;
      $startDateArray =  explode("T", $request->Dates);
      $start_time = $startDateArray[0];
      $endDateArray =  explode("T", $request->end);
      $end_time = $endDateArray[0];
     if($request->location_id==0) 
      {
         $appointments = DB::table('appointments')->join('clients', 'appointments.client_id', '=', 'clients.id')->join('locations', 'appointments.location_id', '=', 'locations.id')->
           join('employees', 'appointments.employee_id', '=', 'employees.id')
           ->join('services', 'services.id', '=', 'appointments.service_id')
          ->select('appointments.id','clients.first_name','clients.last_name','appointments.room_id','locations.location_name','appointments.start_time','appointments.finish_time','employees.first_name AS emp_f_name','employees.last_name AS emp_l_name','services.name as service_name')->
        where('appointments.start_time','>=',$start_time)->where('appointments.finish_time','<=',$end_time)->get();
      }
     else{
        $appointments = DB::table('appointments')->join('clients', 'appointments.client_id', '=', 'clients.id')->join('locations', 'appointments.location_id', '=', 'locations.id')->
           join('employees', 'appointments.employee_id', '=', 'employees.id')->join('services', 'services.id', '=', 'appointments.service_id')->select('appointments.id','clients.first_name','clients.last_name','appointments.room_id','locations.location_name','appointments.start_time','appointments.finish_time','employees.first_name AS emp_f_name','employees.last_name AS emp_l_name','services.name as service_name')->
        where('appointments.start_time','>=',$start_time)->where('appointments.finish_time','<=',$end_time)->where('appointments.location_id','=',$request->location_id)->get();
      } 
   // echo "<pre>";print_r($appointments);exit;
      $dataJsonArray = array();
      foreach($appointments as $key => $value)
      { $fname=''; $lname=''; $emp_fname='';$emp_lname='';
           
           if(isset($value->first_name))
             {$fname=$value->first_name;}
          if(isset($value->last_name))
             {$lname=$value->last_name;}

            if(isset($value->emp_f_name))
             {$emp_fname=$value->emp_f_name;}
          if(isset($value->emp_l_name))
             {$emp_lname=$value->emp_l_name;} 
         $LastString =  isset($emp_lname[0]) ? strtoupper($emp_lname[0]):'';
            
         $thapistName = strtoupper($emp_fname[0])."".$LastString;

          $desc = '<span style="color:#D64535;font-weight:bold">Client Name : </span> <b>'.$fname.' '.$lname.'</b><br> <span style="color:#D64535;font-weight:bold">Therpist Name : </span><b>'. $emp_fname.' '.$emp_lname.'</b> <br> <span style="color:#D64535;font-weight:bold">Tharpy : </span> <b>'.$value->service_name.'</b> <br> <span style="color:#D64535;font-weight:bold">Location : </span> <b>'.$value->location_name.'</b>'; 
       $dataJsonArray[] = 
       array('resourceId'=>$value->room_id,'title'=> $thapistName." - ".$fname.' '.$lname.' - '.$value->location_name,'description'=> $desc,'start'=>$value->start_time,'end'=>$value->finish_time,'url'=> "appointments/$value->id");
           
      }
    return json_encode($dataJsonArray,true);
    }

}
