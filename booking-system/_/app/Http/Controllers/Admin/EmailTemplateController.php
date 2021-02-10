<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\EmailTemplate;
use App\Roles;

class EmailTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

         if (! Gate::allows('emailtemplate_access')) {
            return abort(401);
        }

        $emailtemplates = EmailTemplate::all();
        return view('admin.emailtemplates.index', compact('emailtemplates') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
      if (! Gate::allows('emailtemplate_create')) {
            return abort(401);
        }
        $relations = [
            'email_user_type' => \App\Role::whereIn('id', array(3, 4))->get()->pluck('title', 'id')
        ];
     

        return view('admin.emailtemplates.create',$relations);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
           if (! Gate::allows('emailtemplate_create')) {
            return abort(401);
        }
        
         $emailTemplate = EmailTemplate::create($request->all()); 
        
        return redirect()->route('admin.emailtemplates.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //

        if (! Gate::allows('emailtemplate_edit')) {
            return abort(401);
        }
        $emailtemplates = EmailTemplate::findOrFail($id);
        //$location = $location->prepend($location->rooms);
         $relations = [
            'email_user_type' => \App\Role::whereIn('id', array(3, 4))->get()->pluck('title', 'id')
        ];
        return view('admin.emailtemplates.edit', compact('emailtemplates')+$relations);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        if (! Gate::allows('emailtemplate_edit')) {
            return abort(401);
        }
        $emailtemplates = EmailTemplate::findOrFail($id);
        $emailtemplates->update($request->all()); 
        return redirect()->route('admin.emailtemplates.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
