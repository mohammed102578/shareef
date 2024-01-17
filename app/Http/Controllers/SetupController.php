<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Artisan;

class SetupController extends Controller
{

    /*
    var string
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    
    //=====user=====



public function welcome()
{
  
       
    return view('pages.welcome');
}



public function database()
{
  
       
    return view('pages.database');
}



public function setup()
{
  
       
    return view('pages.setup');
}




public function store_database(Request $request)
{
  
    //return $request->all();
  
    try{
    $username = $request->username;
    $password = $request->username;
   
    
   
  
if($request->username=='root' && $request->password==""){

//check db connection name
if(DB::connection()->getDatabaseName() =="test"){
    $database="sherif";
    Artisan::call("config:cache");
   
    
  //check if file exist
    $path = base_path('.env');
    $key = 'sherif';
    if (file_exists($path)) {
      file_put_contents($path, str_replace(
        'DB_DATABASE='.config('app.database'), 'DB_DATABASE='.$key, file_get_contents($path)
      ));
    }

   
// Create connection
   DB::statement("CREATE DATABASE IF NOT EXISTS $database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
 

//import file in to database
 $sql = file_get_contents('/opt/lampp/htdocs/sharif64/app/Http/Controllers/sherif.sql');

 DB::unprepared($sql);
 

 
//return redirect
 return Redirect::to('http://localhost/sharif64/companies');
 Artisan::call("route:clear");
}else{
    return redirect()->back()
    ->with('error', 'عذرا لقد قمت بتثبيت النظام من قبل');
}



}else{
    //if enter user name and password not correct
    return redirect()->back()
    ->with('error', 'البريد الالكتروني وكلمة السر غير متطابقين');
}
    }catch (\Exception $ex) {
        

        return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');

    }
}

//store_database


//=====end user====================================================

}
