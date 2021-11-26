<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()){
           return redirect()->to('/dashboard');
        }
        else{
        return view('admin/login');
    }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         if (auth()->user()){
           return redirect()->to('/dashboard');
        }
        else{
            return view('admin/login');
    }    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // echo "string";;
        // die();

       //$user = User::where('email','admin11@gmail.com')->update(['email'=>'admin11@gmail.com']);
        // print_r($user);
         //die();

        if(auth()->attempt(array('email' => $input['email'], 'password' => $input['password'])))
        {
            // if (auth()->user()->is_admin == 2) {
            //     return redirect()->to('/exam');
            // }
              return redirect()->to('/dashboard');
        }else{
           return back()->withErrors([
               'message' => 'Invalid Email and Password.'
          ]);
        }
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //Logout
        auth()->logout();
        return redirect()->to('/');
    }
}
