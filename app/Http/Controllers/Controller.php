<?php

namespace App\Http\Controllers;

use App\Http\Requests\loginRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function index(){
        return view('pages.index');
    }

   // check the admin admin login
public function login(loginRequest $request){

   //return $request->user_name;
    
    // if (auth()->attempt(['user_name' => $request->user_name, 'password' => $request->password])) {
      
    //     return "good";
    // }else{
    //     return "bad";
    // }



    $credentials=['user_name' => $request->user_name, 'password' => $request->password];


    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        return redirect()->intended('dashboard');
    }

    return back()->withErrors([
        'user_name' => 'The provided credentials do not match our records.',
    ])->onlyInput('user_name');






}


// public function logout(Request $request) {
//     Auth::logout();
//     return redirect('admin/login');
//   }

}
