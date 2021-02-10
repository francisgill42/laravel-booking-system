<?php

namespace App\Http\Controllers\Admin;

use App\Client;
use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreClientsRequest;
use App\Http\Requests\Admin\UpdateClientsRequest;
use App\User;
use DB;
use \App;
use Moneybird; 
use Date;
use Carbon\Carbon;
use Validator;
use Illuminate\Support\Facades\Input;

class ClientsController extends Controller
{
    /**
     * Display a listing of Client.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! Gate::allows('client_access')) {
            return abort(401);
        }
  /*Date::setLocale('nl');
  echo Date::parse('2019-12-27')->format('l j F Y');
exit;*/

     //echo "Email ID ".$contactSearchObject->email;
  
       $user = \Auth::user();   
     //Hash::make($password);
       // $clinet = App\Client::where('deleted_at','=',NULL)->where('add_by','=',$user->role_id)->count(); 
        if($user->role_id == 3)
         {  
          $therapist = App\Employee::where('deleted_at','=',NULL)->where('user_id','=',$user->id)->get();
          $employee_id = $therapist[0]->id;
          
          $clients = Client::where('deleted_at','=',NULL)->where('add_by','=',$employee_id)->get();
          $clientsOther = DB::table('appointments')->join('clients', 'clients.id', '=', 'appointments.client_id')->select('clients.id','clients.first_name','clients.last_name','clients.phone','clients.email','moneybird_contact_id')->where('employee_id','=',$employee_id)->groupBy('clients.email')->get();
          
             $relations = [
             'clientsOther' => $clientsOther,
             
            ];

          }
        else
         {$clients = Client::all();
          
            $relations = ['clientsOther' => array()];
         } 
      
        return view('admin.clients.index',compact('clients')+$relations);
    }

    /**
     * Show the form for creating new Client.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
                $parentClient = Client::select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')
 ->where('deleted_at','=',NULL)->where('add_by','=',$employee_id)->where('parent_id','=',0)->get()->pluck('name', 'id')->prepend('Please select', 0)
          ];
         }
        else {
             
         
          $relations = [
              'locations' => \App\Location::get()->pluck('location_name', 'id'),
              'parentClient' => Client::select(DB::raw("CONCAT(first_name,' ',last_name) AS name"),'id')
 ->where('deleted_at','=',NULL)->where('parent_id','=',0)->get()->pluck('name', 'id')->prepend('Please select', 0)
          ];
         }
         
        return view('admin.clients.create',$relations);
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
    public function store(Request $request)
    { 
        if (! Gate::allows('client_create')) {
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
           $validator = Validator::make(Input::all(), $rules);
        }
       else 
       {
          $rules = array( 'first_name' => 'required','last_name' => 'required',
            'postcode' => 'required',
            'phone' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:password',);
           $validator = Validator::make(Input::all(), $rules);
       }


        $name = $request->first_name.' '.$request->last_name;
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
            'locations' => \App\Location::get()->pluck('location_name', 'id')
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
        $client->delete();

        return redirect()->route('admin.clients.index');
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
                $entry->delete();
            }
        }
    }

   public function GetLocation(Request $request)
   {
    $returnArray=array();
    $clients = DB::table('clients')->where('id', '=', $request->client_id)->get();
    $services = DB::table('appointments')->where('client_id', '=', $request->client_id)->orderBy('id','desc') ->limit(1)->get();
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
       ini_set('memory_limit', '2048M');
      //$contacts = Moneybird::contact()->get();

      //echo "<pre>";
      //print_r($contacts);exit;
        // all tax class grab from Moneybrid and inserted into our database so when we create therapy we can add that tax class into and if appointment is booked we can sent that tax class id with that appointment and it will automatically created VAT class for that appointment on Moneybird
       /* $allcontactsCount = Moneybird::contact()->getAll();
        $totalNumber_contact = count($allcontactsCount); 
        $loops = $totalNumber_contact / 20;*/
        $loops=2020;
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
               //  echo count($clientAlreadyExits);
                 //echo "<br>";
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
}
