<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use App\Location;
use App\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreClientsRequest;
use App\Http\Requests\Admin\UpdateClientsRequest;
use App\User;
use App\EmailTemplate;
use DB; 
use \App;
use Moneybird; 
use Date;
use Carbon\Carbon;
use Validator;
use Illuminate\Support\Facades\Input;
use Mail;
use DataTables;

class ClientsController extends Controller
{
    /**
     * Display a listing of Client.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatables(Request $request)
    {
      
      
        // if ($request->ajax()) {

           $emailTemplate=array();
       $user = \Auth::user();   
     $start = $request->start;

     $length = $request->length;
          if($user->role_id == 3)
           {  
            $therapist = App\Employee::where('deleted_at','=',NULL)->where('user_id','=',$user->id)->get();
            $employee_id = $therapist[0]->id;
           
            /*$clients = Client::select('id','first_name','last_name','phone','email','created_at','comment','parent_id','moneybird_contact_id')->where('deleted_at','=',NULL)->where('moneybird_contact_id','!=',NULL)->where('add_by','=',$employee_id)->get();*/
            
           /* $clientsOther = DB::table('appointments')->join('clients', 'clients.id', '=', 'appointments.client_id')->select('clients.id','clients.first_name','clients.last_name','clients.phone','clients.email','moneybird_contact_id','clients.comment','clients.parent_id','clients.created_at')->where('employee_id','=',$employee_id)->groupBy('clients.email')->get();
              $data[] = $clientsOther;*/



               if(empty($request->input('search.value')))
                {     
                  /*$clients = Client::select('id','first_name','last_name','phone','email','created_at','comment','parent_id','moneybird_contact_id')->where('moneybird_contact_id','!=',NULL)->where('employee_id','=',$employee_id)->orWhere('clients.add_by', '=',$employee_id)->offset($start)
                                ->limit($length)-> orderBy('created_at', 'desc')->groupBy('clients.id')->get();
                   $clients=DB::table('appointments')->join('clients', 'clients.id', '=', 'appointments.client_id')->select('clients.id','clients.first_name','clients.last_name1','clients.phone','clients.email','moneybird_contact_id','clients.comment','clients.parent_id','clients.created_at')->where('moneybird_contact_id','!=',NULL)->where('employee_id','=',$employee_id)->orWhere('clients.add_by', '=',$employee_id)->groupBy('clients.id')->get();*/

                   $clients = Client::join('appointments', 'clients.id', '=', 'appointments.client_id')->select('clients.id','clients.first_name','clients.last_name','clients.phone','clients.email','moneybird_contact_id','clients.comment','clients.parent_id','clients.created_at')->where('moneybird_contact_id','!=',NULL)->where('employee_id','=',$employee_id)->orWhere('clients.add_by', '=',$employee_id)->offset($start)
                            ->limit($length)->groupBy('clients.id')->get();

                 }
                else
                {
                    $search = $request->input('search.value'); 
                    $clients = Client::select('id','first_name','last_name','phone','email','created_at','comment','parent_id','moneybird_contact_id')->where('moneybird_contact_id','!=',NULL)->where('employee_id','=',$employee_id)->orWhere('clients.add_by', '=',$employee_id)->Where('id','LIKE',"'%".$search."%'")
                                ->orWhere('first_name', 'LIKE',"'%".$search."%'")->orWhere('last_name', 'LIKE',"'%".$search."%'")->orWhere('phone', 'LIKE',"'%".$search."%'")->orWhere('email', 'LIKE',"%{$search}%")->orWhere('comment', 'LIKE',"'%".$search."%'")->orWhere('comment', 'LIKE',"'%".$search."%'")->offset($start)
                                ->limit($length)-> orderBy('created_at', 'desc')->groupBy('clients.id')->get();
                }                

                  $clientcnt = Client::join('appointments', 'clients.id', '=', 'appointments.client_id')->select('clients.id','clients.first_name','clients.last_name','clients.phone','clients.email','moneybird_contact_id','clients.comment','clients.parent_id','clients.created_at')->where('moneybird_contact_id','!=',NULL)->where('employee_id','=',$employee_id)->orWhere('clients.add_by', '=',$employee_id)->groupBy('clients.id')->get();
             $clientcnt = count($clientcnt);


              /* $relations = [
               'clientsOther' => $clientsOther,
               'emailTemplate' => json_encode (json_decode ("{}"))
              ];*/
  //dd($emailTemplate);
            }
          else
           {

             /* $clients = Client::select('id','first_name','last_name','phone','email','created_at','comment','parent_id','moneybird_contact_id')->where('moneybird_contact_id','!=',NULL)-> orderBy('created_at', 'desc')->offset($start)->limit($length)->get();*/
            if(empty($request->input('search.value')))
            {     
              $clients = Client::select('id','first_name','last_name','phone','email','created_at','comment','parent_id','moneybird_contact_id')->where('moneybird_contact_id','!=',NULL)->offset($start)
                            ->limit($length)-> orderBy('created_at', 'desc')->get();
             }
            else
            {
                $search = $request->input('search.value'); 
                $clients = Client::select('id','first_name','last_name','phone','email','created_at','comment','parent_id','moneybird_contact_id')->where('moneybird_contact_id','!=',NULL)->Where('id','LIKE',"'%".$search."%'")
                            ->orWhere('first_name', 'LIKE',"'%".$search."%'")->orWhere('last_name', 'LIKE',"'%".$search."%'")->orWhere('phone', 'LIKE',"'%".$search."%'")->orWhere('email', 'LIKE',"%{$search}%")->orWhere('comment', 'LIKE',"'%".$search."%'")->orWhere('comment', 'LIKE',"'%".$search."%'")->offset($start)
                            ->limit($length)-> orderBy('created_at', 'desc')->get();
            }                

              $clientcnt = Client::select('id','first_name','last_name','phone','email','created_at','comment','parent_id','moneybird_contact_id')->where('moneybird_contact_id','!=',NULL)->orderBy('created_at', 'desc')->count();
            //  $clients_letaet = Client::all();
              //EmailTemplate            
              $emailTemplate = \App\EmailTemplate::whereNull('email_type')->get()->toJson();
              //dd($emailTemplate);
              $relations = ['clientsOther' => array(),'emailTemplate' =>$emailTemplate];
           } 
        
        $cntClient = $clientcnt;
        return Datatables::of($clients)
                           ->editColumn('checkbox', function(Client $data) {

        return  $data->id ;
    })

                            ->editColumn('first_name', function(Client $data) {
                               $first_name = $data->first_name;
                               
                                return  $first_name;
                            })
                            ->editColumn('last_name', function(Client $data) {
                                 $price = $data->last_name ;
                                return  $price;
                            })
                            ->editColumn('phone', function(Client $data) {
                                return $data->phone;
                            })
                            ->editColumn('email', function(Client $data) {
                                return $data->email;
                            })
                            ->editColumn('created_date', function(Client $data) {
                                return date('Y-m-d',strtotime($data->created_at));
                            })
                            ->editColumn('comment', function(Client $data) {
                                return $data->comment;
                            })
                            ->editColumn('parent_name', function(Client $data) {
                                $clientParent = Client::where('id','=',$data->parent_id)->get();
                                $parentname = '-';
                                if(isset($clientParent[0]->first_name))
                                 $parentname = $clientParent[0]->first_name." ".$clientParent[0]->last_name;

                                return $parentname;
                            })
                            ->editColumn('moneybird_contact_id', function(Client $data) {
                                
                                return $data->moneybird_contact_id;
                            })
                            ->addColumn('action', function(Client $data) {
                                
                                

                                $StrVal =  '<a href="'.route('admin.clients.show',[$data->id]).'" class="btn btn-xs btn-primary">View</a><a href="'.route('admin.clients.edit',[$data->id]).'" class="btn btn-xs btn-info">Edit</a><a href="'.route('admin.client_destroy',[$data->id]).'" class="btn btn-xs btn-danger">Delete</a>';

                                /*$StrVal = $StrVal.'<a href="'.route('admin.client_copy',[$data->id]).'" class="btn btn-xs btn-success">Copy</a>';*/

                                 $StrVal = $StrVal.'<a onclick="loadcolydiew('.$data->id.')"  href="javascript:void(0)" data-toggle="modal" data-target="#calendarModal" class="btn btn-xs btn-info">Copy</a>';


                                if (Gate::allows('appointment_create')) 
                                  {
                                    $StrVal =  $StrVal.'<a href="'.route('admin.appointments.create',['client_id' =>$data->id]).'" class="btn btn-xs btn-info">New Booking</a>';
                                  } 
                                
                                  

                                return $StrVal;
                            })
                             ->with([
                             "recordsTotal"    => $cntClient,
                             "recordsFiltered"  => $cntClient,
                         ])->skipPaging()
                            ->toJson(); 
                            //--- Returning Json Data To Client Side

          /* 
            return Datatables::of($clients)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){
   
                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editProduct">Edit</a>';
   
                           $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteProduct">Delete</a>';
    
                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);*/
       // }

    }
    public function index()
    {
        if (! Gate::allows('client_access')) {
            return abort(401);
        }
  /*Date::setLocale('nl');
  echo Date::parse('2019-12-27')->format('l j F Y');
exit;*/

     //echo "Email ID ".$contactSearchObject->email;
  
      $clientsOther = $emailTemplate=array();
       $user = \Auth::user();   

       $employee_id=0;
     
        if($user->role_id == 3)
         {  
          $therapist = App\Employee::where('deleted_at','=',NULL)->where('user_id','=',$user->id)->get();
          $employee_id = $therapist[0]->id;
         
          $clients = Client::where('deleted_at','=',NULL)->where('moneybird_contact_id','!=',NULL)->where('add_by','=',$employee_id)->get();
          
          $clientsOther = DB::table('appointments')->join('clients', 'clients.id', '=', 'appointments.client_id')->select('clients.id','clients.first_name','clients.last_name','clients.phone','clients.email','moneybird_contact_id','clients.comment','clients.parent_id','clients.created_at')->where('employee_id','=',$employee_id)->groupBy('clients.email')->get();
            $emailTemplate = \App\EmailTemplate::whereNull('email_type')->get()->toJson();
          }
        else
         { $employee_id=0;
            $clients = Client::where('moneybird_contact_id','!=',NULL)->  orderBy('created_at', 'desc')->get();
            $emailTemplate = \App\EmailTemplate::whereNull('email_type')->get()->toJson();
         } 

         if($employee_id > 0)
           {  
              $parentClient = Client::select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')->where('deleted_at','=',NULL)->where('add_by','=',$employee_id)->where('parent_id','=',0)->get()->pluck('name', 'id')->prepend('Please select', 0);
           }
          else 
          {
              $parentClient = Client::select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')->where('deleted_at','=',NULL)->where('parent_id','=',0)->get()->pluck('name', 'id')->prepend('Please select', 0);
           }
           
          //dd($parentClient);

        $relations = ['clientsOther' => $clientsOther,'emailTemplate' =>$emailTemplate, 'parentClient'=>$parentClient];
        return view('admin.clients.index',compact('clients')+$relations);
    }

    /**
     * Show the form for creating new Client.
     *
     * @return \Illuminate\Http\Response
     */
    public function copy($id)
    {
        if (! Gate::allows('client_create')) { return abort(401); }
        $client = Client::findOrFail($id);
        if(!empty($client))
        {
          $RsData=array('message'=>'success','client'=>$client);
        }
        else
        {
          $RsData=array('message'=>'No Client Information Exist');
        }
        return json_encode($RsData,true);
        exit;
        /*
        $client_id = $id;
        $user = \Auth::user();   
        
        if($user->role_id == 3)
         {  
           $therapist = App\Employee::where('deleted_at','=',NULL)->where('user_id','=',$user->id)->get();
           $employee_id = $therapist[0]->id;
          
          $relations = [
              'locations' => \App\Location::get()->pluck('location_name', 'id'),
              'client_id'=>$client_id,
              'parentClient' => Client::select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')
 ->where('deleted_at','=',NULL)->where('add_by','=',$employee_id)->where('parent_id','=',$client->parent_id)->get()->pluck('name', 'id')->prepend('Please select', 0)
          ];
         }
        else {
             
         
          $relations = [
              'locations' => \App\Location::get(),
              'client_id'=>$client_id,
              'parentClient' => Client::select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')
 ->where('deleted_at','=',NULL)->where('parent_id','=',$client->parent_id)->get()->pluck('name', 'id')->prepend('Please select', 0)
          ];
         }
        return view('admin.clients.copy',compact('client')+$relations);
        
        */

        
    }
    public function create(Request $request)
    {
        if (! Gate::allows('client_create')) {
            return abort(401);
        }
        
        $user = \Auth::user();   
        
          if($user->role_id == 3)
           {  
             $therapist = App\Employee::where('deleted_at','=',NULL)->where('user_id','=',$user->id)->get();
             $employee_id = $therapist[0]->id;
            
            $relations = [
                'locations' => \App\Location::get()->pluck('location_name', 'id'),
                
                'parentClient' => Client::select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')
   ->where('deleted_at','=',NULL)->where('add_by','=',$employee_id)->where('parent_id','=',0)->get()->pluck('name', 'id')->prepend('Please select', 0)
            ];
           }
          else {
               
           
            $relations = [
                'locations' => \App\Location::get(),
                
                'parentClient' => Client::select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')
   ->where('deleted_at','=',NULL)->where('parent_id','=',0)->get()->pluck('name', 'id')->prepend('Please select', 0)
            ];
           }
           
          return view('admin.clients.create',$relations);
         
        
    }

public function clientwithoutmoneybird()
 {
    if (!Gate::allows('client_without_moneybird')) {
            return abort(401);
        }
  
       $emailTemplate=array();
       $user = \Auth::user();   
  
        if($user->role_id == 3)
         {  
           $therapist = App\Employee::where('deleted_at','=',NULL)->where('user_id','=',$user->id)->get();
          $employee_id = $therapist[0]->id;
         
          $clients = Client::where('deleted_at','=',NULL)->where('moneybird_contact_id','=',NULL)->where('add_by','=',$employee_id)->get();
          //dd($clients);
          $clientsOther = DB::table('appointments')->join('clients', 'clients.id', '=', 'appointments.client_id')->select('clients.id','clients.first_name','clients.last_name','clients.phone','clients.email','moneybird_contact_id','clients.comment','clients.parent_id','clients.created_at')->where('employee_id','=',$employee_id)->groupBy('clients.email')->get();
            
             $relations = [
             'clientsOther' => $clientsOther,
             'user_role_id' => $user->role_id ,
             'emailTemplate' => json_encode (json_decode ("{}"))
            ];

         }
        else
         {
            $clients = Client::where('moneybird_contact_id','=',NULL)->orderBy('created_at', 'desc')->get();
           
            $relations = [
                          'clientsOther' => array(),
                          'user_role_id' => $user->role_id ,
                          'emailTemplate' =>json_encode (json_decode ("{}"))
                         ];
         } 
      
        return view('admin.clients.withoutmoneybird',compact('clients')+$relations);

 }
    /**
     * Store a newly created Client in storage.
     *
     * @param  \App\Http\Requests\StoreClientsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function jsonstore(Request $request)
    {
      if (! Gate::allows('client_json_create')) {
            return abort(401);
        }
if($request->parent_id == 0)
        {
            $rules = array( 'first_name' => 'required','last_name' => 'required',
            'postcode' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:clients,email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',);
          // $validator = Validator::make(Input::all(), $rules);
        }
       else 
       {
          $rules = array( 'first_name' => 'required','last_name' => 'required',
            'postcode' => 'required',
            'phone' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',);
          // $validator = Validator::make(Input::all(), $rules);
       }

    /*$rules = array( 'first_name' => 'required','last_name' => 'required',
            'postcode' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:clients,email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',);*/
    $validator = Validator::make(Input::all(), $rules);

// Validate the input and return correct response
  if ($validator->fails())
  {
      return response()->json(array(
          'success' => false,
          'errors' => $validator->getMessageBag()->toArray()

      ), 200); // 400 being the HTTP code for an invalid request.
      //return response()->json(['success' => false,'client_id'=>$client->id,'name'=>$name]);
  }


      $name = $request->first_name.' '.$request->last_name;
   
       /* $user = User::create([
           'name' => $name,
           'email' => $request->email,
           'role_id'  => 4,
           'password' => bcrypt($request->password)
        ]);*/
       $usertest = \Auth::user();   

        if($request->parent_id == 0)
          {
              $user = User::create([
             'name' => $name,
             'email' => $request->email,
             'role_id'  => 4,
             'password' => bcrypt($request->password)
            ]);
              $user_id = $user->id;
              $emailId= $request->email;
         }
         else 
         {
            $user = Client::where('id','=',$request->parent_id)->get();
           
            $user_id = $user[0]->user_id;
            $emailId= $user[0]->email;
         }

     //Hash::make($password);
       // $clinet = App\Client::where('deleted_at','=',NULL)->where('add_by','=',$user->role_id)->count(); 
       
        if($usertest->role_id == 3)
         {  
           $therapist = App\Employee::where('deleted_at','=',NULL)->where('user_id','=',$usertest->id)->get();
           $add_by = $therapist[0]->id;
         } 
        else{
            $add_by =1;
         } 
      
        $client = Client::create([
           'user_id' => $user_id,
           'first_name' => $request->first_name,
           'last_name' => $request->last_name,
            'email' =>  $emailId,
           'postcode' => $request->postcode,
           'house_number' => $request->house_number,
           'address' => $request->address,
           'phone' => $request->phone,
           'company_name' => $request->company_name, 
           'city_name' => $request->city_name,            
           'add_by' => $add_by,  
           'comment' => isset($request->comment) ? $request->comment : '',
           
        ]); 
     $contactSearchObject = Moneybird::contact();
     
       // $moneybird_contact_id='271375336926610863'; 

       // $moneybird_contact_id='271375336926610863'; 
       //$contactSearchObject = $contactSearchObject->search($request->email);
     if($request->parent_id == 0)
          {
              $contactSearchObject = $contactSearchObject->search($request->email);
          }
         else
          {
             $contactSearchObject =  array();
          }

       if(empty($contactSearchObject))
       {
          $contactObject = Moneybird::contact();

          if(isset($request->company_name))
           {$contactObject->company_name = $request->company_name;}
          $contactObject->firstname = $request->first_name;
          $contactObject->lastname = $request->last_name;
          $contactObject->send_estimates_to_email = $emailId;
          $contactObject->send_invoices_to_email = $emailId;
          
          $addressSend=" ";
        
           if(isset($request->address))
              {
                if(isset($addressSend))
                  {$addressSend .= $request->address." ";}
                 
              }
              if(isset($request->house_number))
              {
                $addressSend .= $request->house_number;
              }   
                 

          if(isset($addressSend))
           {
            $contactObject->address1 = $addressSend;    
           }
          
         if(isset($request->phone))
          {$contactObject->phone = $request->phone;}
        if(isset($request->city_name))
          {$contactObject->city = $request->city_name;}
         if(isset($request->postcode))
          {$contactObject->zipcode = $request->postcode;}
           
          $contactObject->save();  
          $clientUpdate = Client::find($client->id);
          $clientUpdate->moneybird_contact_id= $contactObject->id;
          $clientUpdate->save();
       }
       else
        {
           $clientUpdate = Client::find($client->id);
           $clientUpdate->moneybird_contact_id= $contactSearchObject[0]->id;
           $clientUpdate->save();
        }
        $fname = isset($client->first_name) ? $client->first_name :'';
        $lname = isset($client->last_name) ? $client->last_name :'';
        $name =  $fname." ".$lname;
        //return response()->json(['status' => 'Saved successfully','client_id'=>$client->id,'name'=>$name]);
       return response()->json(array('success' => true,'client_id'=>$client->id,'name'=>$name), 200);

    }
  public function opertorjsonstore (Request $request )
    {

     if($request->parent_id == 0)
        {
            $rules = array( 'first_name' => 'required','last_name' => 'required',
            'postcode' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:clients,email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',);
          // $validator = Validator::make(Input::all(), $rules);
        }
       else 
       {
          $rules = array( 'first_name' => 'required','last_name' => 'required',
            'postcode' => 'required',
            'phone' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',);
          // $validator = Validator::make(Input::all(), $rules);
       }

    $validator = Validator::make(Input::all(), $rules);

  if ($validator->fails())
  {
      return response()->json(array(
          'success' => false,
          'errors' => $validator->getMessageBag()->toArray()

      ), 200); // 400 being the HTTP code for an invalid request.
      //return response()->json(['success' => false,'client_id'=>$client->id,'name'=>$name]);
  }


       $name = $request->first_name.' '.$request->last_name;
       /* $user = User::create([
           'name' => $name,
           'email' => $request->email,
           'role_id'  => 4,
           'password' => bcrypt($request->password)
        ]);*/
       $usertest = \Auth::user();   

        if($request->parent_id == 0)
          {
              $user = User::create([
             'name' => $name,
             'email' => $request->email,
             'role_id'  => 4,
             'password' => bcrypt($request->password)
            ]);
              $user_id = $user->id;
              $emailId= $request->email;
         }
         else 
         {
            $user = Client::where('id','=',$request->parent_id)->get();
           
            $user_id = $user[0]->user_id;
            $emailId= $user[0]->email;
         }

     //Hash::make($password);
       // $clinet = App\Client::where('deleted_at','=',NULL)->where('add_by','=',$user->role_id)->count(); 
      

       $verify_client_token = md5(time().$emailId);

       /*Moneybird Existed*/
       $OldCommentArr = "";
       if(!empty($request->comment))
       {
          $userLogin = \Auth::user();
          $OldCommentArr .= "<li> ".$userLogin->name." ".date("d-m-Y h:i:s")." ".$request->comment."</li>"; 
       }


       $client = Client::create([
                'user_id' => $user_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' =>  $emailId,
                'postcode' => $request->postcode,
                'house_number' => $request->house_number,
                'address' => $request->address,
                'phone' => $request->phone,
                'company_name' => $request->company_name, 
                'city_name' => $request->city_name,            
                'add_by' => $usertest->id,  
                'status' => 'pending',
                'email_verified'=> 0,
                'verify_link'  => $verify_client_token,
                'comment' => isset($request->comment) ? $request->comment : '',  

                'comment_log' => isset($OldCommentArr) ? $OldCommentArr : ''  
        ]); 
    
        $fname = isset($client->first_name) ? $client->first_name :'';
        $lname = isset($client->last_name) ? $client->last_name :'';
        $name =  $fname." ".$lname;
        //return response()->json(['status' => 'Saved successfully','client_id'=>$client->id,'name'=>$name]);
       return response()->json(array('success' => true,'client_id'=>$client->id,'name'=>$name), 200);


    }  
    public function store(Request $request)
    {
      if (! Gate::allows('client_create')) { return abort(401); }

      
      if($request->parent_id == 0)
        {

            $rules = array( 'first_name' => 'required','last_name' => 'required',
            'postcode' => 'required',
            'email' => 'required|email|unique:clients,email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',);
           $validator = Validator::make(Input::all(), $rules);

        }
       else 
       {
          $rules = array( 'first_name' => 'required','last_name' => 'required',
            'postcode' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',);
           $validator = Validator::make(Input::all(), $rules);
       }


        $name = $request->first_name.' '.$request->last_name;
        if($request->parent_id == 0)
          {
            $rules = array( 'first_name' => 'required','last_name' => 'required',
            'postcode' => 'required',
            'email' => 'required|email|unique:clients,email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',);
            
           $validator = Validator::make(Input::all(), $rules);


              $user = User::create([
             'name' => $name,
             'email' => $request->email,
             'role_id'  => 4,
             'password' => bcrypt($request->password)
            ]);
              $user_id = $user->id;
              $emailId= $request->email;
         }
         else 
         {
            $user = Client::where('id','=',$request->parent_id)->get();
           
            $user_id = $user[0]->user_id;
            $emailId= $user[0]->email;
         }
         
         
          $usertest = \Auth::user();   
     //Hash::make($password);
       // $clinet = App\Client::where('deleted_at','=',NULL)->where('add_by','=',$user->role_id)->count(); 
       
        if($usertest->role_id == 3)
         {  
           $therapist = App\Employee::where('deleted_at','=',NULL)->where('user_id','=',$usertest->id)->get();
           $add_by = $therapist[0]->id;
         } 
        else{
            $add_by =1;
         } 
 
        $client = Client::create([
           'user_id' => $user_id,
           'first_name' => $request->first_name,
           'last_name' => $request->last_name,
           'email' =>  $emailId,
           'postcode' => $request->postcode,
           'house_number' => $request->house_number,
           'address' => $request->address,
           'phone' => $request->phone,
           'company_name' => $request->company_name, 
           'city_name' => $request->city_name,            
           'parent_id' => $request->parent_id,            
           'add_by' => $add_by,  
        ]); 

        $contactSearchObject = Moneybird::contact();
       // $moneybird_contact_id='271375336926610863'; 

       // $moneybird_contact_id='271375336926610863'; 
        if($request->parent_id == 0)
          {
              $contactSearchObject = $contactSearchObject->search($request->email);
          }
         else
          {
             $contactSearchObject =  array();
          }


       if(empty($contactSearchObject))
       {  
          $contactObject = Moneybird::contact();
          if(isset($request->company_name))
           {$contactObject->company_name = $request->company_name;}
          $contactObject->firstname = $request->first_name;
          $contactObject->lastname = $request->last_name;

          $contactObject->send_estimates_to_email = $emailId;
          $contactObject->send_invoices_to_email = $emailId;
          
          $addressSend=" ";
        
           if(isset($request->address))
              {
                if(isset($addressSend))
                  {$addressSend .= $request->address." ";}
                 
              }
              if(isset($request->house_number))
              {
                $addressSend .= $request->house_number;
              }   
           /*if(isset($request->postcode))
              {
                if(isset($addressSend))
                  {$addressSend .= $request->postcode;}
                 else {
                    $addressSend .= $request->postcode;
                  }
                
              }*/      


          if(isset($addressSend))
           {//$contactObject->address1 = $request->address;

            $contactObject->address1 = $addressSend;    
           }
          /*if(isset($request->house_number) || isset($request->postcode))
           {$contactObject->address2 = isset($request->house_number) ? $request->house_number : '' ." , ".isset($request->postcode) ? $request->postcode : '';}*/
         if(isset($request->phone))
          {$contactObject->phone = $request->phone;}
        if(isset($request->city_name))
          {$contactObject->city = $request->city_name;}
         if(isset($request->postcode))
          {$contactObject->zipcode = $request->postcode;}
          
         
          
          $contactObject->save();  
          $clientUpdate = Client::find($client->id);
          $clientUpdate->moneybird_contact_id= $contactObject->id;
          $clientUpdate->save();
          
       }
       else
        {
        
           $clientUpdate = Client::find($client->id);
           $clientUpdate->moneybird_contact_id= $contactSearchObject[0]->id;
           $clientUpdate->save();
        }

        return redirect()->route('admin.clients.index');
    }

    /**
     * Show the form for editing Client.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (! Gate::allows('client_edit')) {
            return abort(401);
        }
        $client = Client::findOrFail($id);
        $relations = [
            'locations' => \App\Location::get()
        ];
        return view('admin.clients.edit', compact('client')+$relations);
    }

    /**
     * Update Client in storage.
     *
     * @param  \App\Http\Requests\UpdateClientsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClientsRequest $request, $id)
    {
        if (! Gate::allows('client_edit')) {
            return abort(401);
        }

        $client = Client::findOrFail($id);
        $user = User::where('id', $client->user_id)->get()->first();
        $name = $request->first_name.' '.$request->last_name;
        $user->name = $name;
        $user->email = $request->email; 
        if(isset($request->password))
          {$user->password =  bcrypt($request->password);}
        $user->save();

       /*Money bird edit process*/ 
        
          $contactSearchObject = Moneybird::contact();
         // $contactSearchObject = $contactSearchObject->search($request->email);
           
          // $client->moneybird_contact_id= $contactObject->id;
         // $checkAlreadyExist =  Moneybird::findByCustomerId($client->moneybird_contact_id);
       $emailId = $request->email;        
       if(empty($contactSearchObject) || empty($client->moneybird_contact_id))
       {  
          $contactObject = Moneybird::contact();
          if(isset($request->company_name))
           {$contactObject->company_name = $request->company_name;}
          $contactObject->firstname = $request->first_name;
          $contactObject->lastname = $request->last_name;

          $contactObject->send_estimates_to_email = $emailId;
          $contactObject->send_invoices_to_email = $emailId;
          
          $addressSend=" ";
        
           if(isset($request->address))
              {
                if(isset($addressSend))
                  {$addressSend .= $request->address." ";}
                 
              }
              if(isset($request->house_number))
              {
                $addressSend .= $request->house_number;
              }   
           /*if(isset($request->postcode))
              {
                if(isset($addressSend))
                  {$addressSend .= $request->postcode;}
                 else {
                    $addressSend .= $request->postcode;
                  }
                
              }*/      

          if(isset($addressSend))
           {//$contactObject->address1 = $request->address;
            $contactObject->address1 = $addressSend;    
           }
          /*if(isset($request->house_number) || isset($request->postcode))
           {$contactObject->address2 = isset($request->house_number) ? $request->house_number : '' ." , ".isset($request->postcode) ? $request->postcode : '';}*/
         if(isset($request->phone))
          {$contactObject->phone = $request->phone;}
        if(isset($request->city_name))
          {$contactObject->city = $request->city_name;}
         if(isset($request->postcode))
          {$contactObject->zipcode = $request->postcode;}
           
          $contactObject->save();  
          
       }
      else
      {
       // $contactObject = Moneybird::contact();
        $contactObject = $contactSearchObject->find($client->moneybird_contact_id);
           
          if(isset($request->company_name))
           {$contactObject->company_name = $request->company_name;}
          $contactObject->firstname = $request->first_name;
          $contactObject->lastname = $request->last_name;

          $contactObject->send_estimates_to_email = $emailId;
          $contactObject->send_invoices_to_email = $emailId;
          
          $addressSend=" ";
        
           if(isset($request->address))
              {
                if(isset($addressSend))
                  {$addressSend .= $request->address." ";}
                 
              }
              if(isset($request->house_number))
              {
                $addressSend .= $request->house_number;
              }   
           /*if(isset($request->postcode))
              {
                if(isset($addressSend))
                  {$addressSend .= $request->postcode;}
                 else {
                    $addressSend .= $request->postcode;
                  }
                
              }*/      

          if(isset($addressSend))
           {//$contactObject->address1 = $request->address;
            $contactObject->address1 = $addressSend;    
           }
          /*if(isset($request->house_number) || isset($request->postcode))
           {$contactObject->address2 = isset($request->house_number) ? $request->house_number : '' ." , ".isset($request->postcode) ? $request->postcode : '';}*/
         if(isset($request->phone))
          {$contactObject->phone = $request->phone;}
        if(isset($request->city_name))
          {$contactObject->city = $request->city_name;}
         if(isset($request->postcode))
          {$contactObject->zipcode = $request->postcode;}
           
          $contactObject->update();
      } 


       /*Moneybird Existed*/

        $client->update($request->all());
        return redirect()->route('admin.clients.index');
    }


    /**
     * Display Client.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! Gate::allows('client_view')) {
            return abort(401);
        }
        $relations = [
            'appointments' => \App\Appointment::where('client_id', $id)->get(),
        ];

        $client = Client::findOrFail($id);

        return view('admin.clients.show', compact('client') + $relations);
    }


    /**
     * Remove Client from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        if (! Gate::allows('client_delete')) {
            return abort(401);
        }
       
        $client = Client::findOrFail($id);

        $client_appointments = Appointment::where('client_id','=',$client->id)->whereNull('deleted_at')->get();
       if(count($client_appointments) == 0)
         {
            $Userdelete =  \App\User::where('id','=',$client->user_id)->forceDelete();
            $client->forceDelete();
            return redirect()->route('admin.clients.index');
         }
         else
         {

            return redirect()->back()->withErrors("This Customer alreday have an appointments, so please delete that appointment and then delete that customer")->withInput();
         }

        
    }

    /**
     * Delete all selected Client at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('client_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Client::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->forcedelete();
            }
        }
    }
    public function massSendEmail(Request $request)
    {
      //echo "<pre>";print_R($request->input('ids'));exit;
     
       if ($request->input('ids') && $request->input('email_template_id') > 0) {
            $entries = Client::whereIn('id', $request->input('ids'))->get();
            //echo $request->input('email_template_id');
             $email_templates = EmailTemplate::where('id','=', $request->input('email_template_id'))->get();
             //echo "<pre>";print_r($email_templates);
            $matter = $email_templates[0]->email_content;
            $email_subject = $email_templates[0]->email_subject;
            $email_attachment_path='';
            $email_filename_attachment='';
            if(!empty($email_templates[0]->attachment))
            {
                $email_filename_attachment = $email_templates[0]->attachment;
                $email_attachment_path = public_path('/upload/'.$email_filename_attachment);

            }

            foreach ($entries as $entry) {
               // $entry->delete();
              $clientname   = $entry->first_name ." ". $entry->last_name;
                $clientemail = $entry->email;
        $address = $entry->address;
        $clientphone = $entry->phone;


              if(!empty($clientname))  
                  {$matter = str_replace("{clientname}",$clientname,$matter);}
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
         /*echo "Email attachment ".$email_attachment_path;
         echo "<br>";
         echo "Client Email ".$clientemail;
         echo "<br>";
         echo "Email subject".$email_subject;
         echo "<br>";
         echo "Matter ".$matter;
         echo "<br>";
         
         exit;*/
        if($email_attachment_path=='')
            {

                Mail::send([], [], function ($message) use ($clientemail,$email_subject,$matter) {
                      $message->to($clientemail)
                       ->subject($email_subject)
                      ->setBody($matter, 'text/html'); // for HTML rich messages
                });
            } 
           else
           {
            
            $info = pathinfo($email_attachment_path);
            $ext = $info['extension'];
            $application = 'application/'.$ext;
              Mail::send([], [], function ($message) use ($clientemail,$email_subject,$matter,$email_attachment_path,$email_filename_attachment,$application) {
                      $message->to($clientemail)
                       ->subject($email_subject)
                       ->attach($email_attachment_path, [
                        'as' => $email_filename_attachment,
                        'mime' => $application,
                    ])
                      ->setBody($matter, 'text/html'); // for HTML rich messages
                });
           }  
           

            }
        }

    }

   public function GetLocation(Request $request)
   {
    $returnArray=array();
    $clients = DB::table('clients')->where('id', '=', $request->client_id)->get();
    $services = DB::table('appointments')->where('client_id', '=', $request->client_id)->where('deleted_at','=',NULL)->orderBy('id','desc') ->limit(1)->get();
   if(count($services) > 0)
     { $returnArray=array('employee_id'=>$services[0]->employee_id,'location_id'=>$services[0]->location_id,'service_id'=>$services[0]->service_id,'isappointment'=>true);    
      }
     else {
         $returnArray=array('employee_id'=>0,'location_id'=>$clients[0]->location_id,'service_id'=>0,'isappointment'=>false);    
      } 


   
   return $returnArray;
  }
  function generatePassword()
  {
     $random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&');
     $password = substr($random, 0, 10);
     return $password;
     
  }
    public function getallcontactsave()
    {
      
        // all tax class grab from Moneybrid and inserted into our database so when we create therapy we can add that tax class into and if appointment is booked we can sent that tax class id with that appointment and it will automatically created VAT class for that appointment on Moneybird
       /* $allcontactsCount = Moneybird::contact()->getAll();
        $totalNumber_contact = count($allcontactsCount); 
        $loops = $totalNumber_contact / 20;*/
        $loops=15;
        for($i=1; $i<=($loops+1);$i++)
         {


              $array=array('per_page'=>20,'page'=>$i);  
        $contacts = Moneybird::contact()->get($array);
       
        
       foreach ($contacts as $contact) {
             
            $name = $contact->firstname.' '.$contact->lastname;
            if($contact->email!='' && $contact->firstname!='' && $contact->lastname!='')
             {

                $emailAlredayExits = User::where('email','=',$contact->email)->get();
                $parent_id=0;
                $parenEmail= $contact->email;
              
                
               // echo count($emailAlredayExits);
               if(count($emailAlredayExits) == 0)
                 {
                    $user = User::create([
                   'name' => $name,
                   'email' => $contact->email,
                   'role_id'  => 4,
                   'password' => bcrypt('password')
                   ]);
                    $user_id=$user->id;
                 }
                else 
                {
                  $clients = Client::where('email','=',$contact->email)->get();
                  $contact->email."<br>";
                  $parent_id= $clients[0]->id;
                  $parenEmail=$contact->email;
                  $user_id=$emailAlredayExits[0]->id;
                }  
               

                 $clientAlreadyExits = Client::where('moneybird_contact_id', '=', $contact->id)->get();
                 echo count($clientAlreadyExits);
                 echo "<br>";
                if(count($clientAlreadyExits) > 0)
                    {
                      echo "<pre>";print_r($clientAlreadyExits);
                      echo "<br>";
                       //dd($clientAlreadyExits);
                    }
                 if(count($clientAlreadyExits) == 0)
                 {
                    $client = Client::create([
                     'user_id' => $user_id,
                     'first_name' => $contact->firstname,
                     'last_name' => $contact->lastname,
                     'email' => $parenEmail,
                     'postcode' => $contact->zipcode,
                     'address' => $contact->address1,
                     'phone' => $contact->phone,
                     'company_name' => $contact->company_name,
                     'add_by' => '1',
                     'parent_id' => $parent_id,
                     'city_name' => $contact->city,
                     'moneybird_contact_id' => $contact->id
                    ]); 
                 }
              }
         }
       } 
    }

    public function autocompleteAdd(Request $request)
    {
          
              //$pc   = "1234AB";
              $pc   = $request->text;
              $hn   = $request->house_number;
              $tv   = "a";
              //echo urlencode($tv);
              // https://bwnr.nl/postcode.php?pc=1234AB&hn=1&tv=a&tg=data&ac=pc2adres&ak=xQ78g07b69e0Z7@10r
             /*  $getadrlnk  = 'https://bwnr.nl/postcode.php?pc='.urlencode($pc).'&hn='.urlencode($hn).'&tv='.urlencode($tv).'&tg=data&ac=pc2adres&ak=xQ78g07b69e0Z7@10r';
*/
             //https://bwnr.nl/postcode.php?pc=1624GN&hn=3&tv=&tg=data
               $getadrlnk  = 'https://bwnr.nl/postcode.php?pc='.urlencode($pc).'&hn='.urlencode($hn).'&tv=&tg=data&ak=z400@V7p0l(0$R45Jf';

              $result = file_get_contents($getadrlnk);
           
              $jsonArr=array();
              if ($result=="Geen resultaat.") {$jsonArr=array('message'=>'error');} else {
                $adres = explode(";",$result);
                $str  = $adres[0];
                $pl = $adres[1];
                $lat  = $adres[2];
                $lon  = $adres[3];
                $gm = $adres[4];
               $jsonArr=array('address'=>$str,"city"=>ucwords($pl),'message'=>'success');
                //echo $str." ".$pl;
                /*echo "
                straat    : $str<br>
                plaats    : $pl<br>
                lat     : $lat<br>
                lon     : $lon<br>
                googlemaps  : $gm<br>";*/
              }
      echo json_encode($jsonArr);
    }
/* WITH OUT MONEYBIRD ID UPDATE DELETE SEND EMAIL*/

public function editwithoutmoneybird($id)
    {
        if (! Gate::allows('client_edit')) {
            return abort(401);
        }
        $client = Client::findOrFail($id);
        $relations = [
            'locations' => \App\Location::get()
        ];
        return view('admin.clients.withoutmoneybirdedit', compact('client')+$relations);
    }

 public function updatewithoutmoneybird(UpdateClientsRequest $request, $id)
    {
        if (! Gate::allows('client_edit')) {
            return abort(401);
        }

        $client = Client::findOrFail($id);
        $user = User::where('id', $client->user_id)->get()->first();
        $name = $request->first_name.' '.$request->last_name;
        $user->name = $name;
        $user->email = $request->email;
        if(!empty($request->password))
          {$user->password =  bcrypt($request->password);}
        $user->save();
       /*Moneybird Existed*/
       if(!empty($request->comment))
       {
          $OldCommentArr = "";
          if(!empty($client->comment_log)) {  $OldCommentArr = $client->comment_log; }
          $userLogin = \Auth::user();
          $OldCommentArr .= "<li>".$userLogin->name." ".date("d-m-Y h:i:s")." ".$request->comment."</li>"; 
          $request['comment_log'] = $OldCommentArr;  
       }
        
       

        $client->update($request->all());
        return redirect()->route('admin.clients.clientwithoutmoneybird');
    }


    /**
     * Display Client.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showwithoutmoneybird($id)
    {
        if (! Gate::allows('client_view')) {
            return abort(401);
        }
        $relations = [
            'appointments' => \App\Appointment::where('client_id', $id)->get(),
        ];

        $client = Client::findOrFail($id);

        return view('admin.clients.withoutmoneybirdshow', compact('client') + $relations);
    }


    /**
     * Remove Client from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroywithoutmoneybird($id)
    {
        if (! Gate::allows('client_delete')) {
            return abort(401);
        }
         $appointmentCnt = Appointment::where('client_id','=',$id)->get();
        if(count($appointmentCnt)== 0)
          {
            $client = Client::findOrFail($id);
            $Userdelete =  \App\User::where('id','=',$client->user_id)->forceDelete();
            $client->forceDelete();
            return redirect()->route('admin.clients.clientwithoutmoneybird');
          }
         else 
         {   $client = Client::findOrFail($id);
              $fname = $client->first_name;
              $lname = $client->last_name;
              $name = $fname." ".$lname;
             return redirect()->route('admin.clients.clientwithoutmoneybird')->with('msg', $name . ' alreday associate with appointments so first delete that appointment');
         }
        
    }

    /**
     * Delete all selected Client at once.
     *
     * @param Request $request
     */
    public function massDestroywithoutmoneybird(Request $request)
    {
        if (! Gate::allows('client_delete')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = Client::whereIn('id', $request->input('ids'))->get();
           $appointmentCnt = Appointment::where('client_id','=',$request->input('ids'))->get();
         if(count($appointmentCnt)== 0)
           {
              $client = Client::findOrFail($id);
              $client->forceDelete();
           }
          else
            {
               foreach ($entries as $entry) {
                $entry->forceDelete();
               }
            }
        }
    }
    
/*END WITHOUT MONEYBIRD*/

}
