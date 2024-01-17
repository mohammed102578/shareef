<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

use Illuminate\Support\Facades\Hash;

use App\Models\License;
use App\Models\Log;
use App\Models\Payment_method;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

define('PAGINATION_COUNT',10);
class LicenseController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    //=====user=====



public function about_us()
{
  
        $date_tody=date('Y-m-d');
     $created_at= Auth::user()->company->created_at;
        $expiered = \DB::table('licenses')->latest("id")->first()->expiered_date;
         $created_at=date_format($created_at,"Y-m-d");
       $diff_expiered_created = strtotime($expiered) - strtotime($created_at);
       $diff = strtotime($expiered) - strtotime($date_tody);
if($diff_expiered_created < $diff){
    return redirect()->route('logout');
}
      $remaining_days= round($diff/ 86400);
       
       if($remaining_days<=0){
       
       return redirect()->route('license');
       }
       
 

    if(Auth::user()->group_id==5){
        $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
    
      }else{
    
        $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
    
      }
    

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

    return view('pages.about_us',compact(['permissionsGroup','total_price','logs','payment_methods']));

}


//about as




public function about_us_en()
{
    
        $date_tody=date('Y-m-d');
     $created_at= Auth::user()->company->created_at;
        $expiered = \DB::table('licenses')->latest("id")->first()->expiered_date;
         $created_at=date_format($created_at,"Y-m-d");
       $diff_expiered_created = strtotime($expiered) - strtotime($created_at);
       $diff = strtotime($expiered) - strtotime($date_tody);
if($diff_expiered_created < $diff){
    return redirect()->route('logout');
}
      $remaining_days= round($diff/ 86400);
       
       if($remaining_days<=0){
       
       return redirect()->route('license');
       }
       
 

    if(Auth::user()->group_id==5){
        $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
    
      }else{
    
        $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
    
      }
    

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

    return view('pages_en.about_us',compact(['permissionsGroup','total_price','logs','payment_methods']));

}



//about as en


public function license()
{
    
    return view('pages.license');
}

public function license_en()
{
    
    return view('pages_en.license');
}

//store
public function store_license(Request $request)
{
  
    //try{
$app_id=Auth::user()->company->app_id;  
 $company_id=Auth::user()->company_id;
$licens_request=$request->license;

 $response = Http::asForm()->post('https://etooplay.com/sherif_licenses.php', [
    'license' => $request->license,
   
]);
$apidata=json_decode( $response);

$apidata=json_decode( $response);
$api_company_id=$apidata->company_id;
$api_license=$apidata->license;
$expire_date=$apidata->expire_date;
$api_app_id=$apidata->app_id;

if($licens_request==$api_license && $company_id==$api_company_id && $app_id== $api_app_id){
    $company_id=Auth::user()->company_id;
  
    License::where('company_id',$company_id)->where('status',0)
    ->create([
        'license'=>$api_license,
        'app_id'=>$api_app_id,
        'company_id'=>$api_company_id,
        'expiered_date'=>$expire_date,
        
    ])->save();
if($request->lang=="ar"){
    return redirect()->route('home');
  
}else{
    return redirect()->route('home_en');
  
}
  
}else{

        if(auth()->user()->lang !=='en'){

        return back()->with('error',  'لا يوجد ترخيص بهذا الرقم');
      
      }else{
        return back()->with('error', 'There is no license with this number.');
      
      }    

}

     try{   } catch (\Exception $ex) {
                if(auth()->user()->lang !=='en'){

                    return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');

                }else{
                    return back()->with('error', 'Something went wrong');
  
                }
        }
    

}





//license en

public function license_system(){
  
        $date_tody=date('Y-m-d');
     $created_at= Auth::user()->company->created_at;
        $expiered = \DB::table('licenses')->latest("id")->first()->expiered_date;
         $created_at=date_format($created_at,"Y-m-d");
       $diff_expiered_created = strtotime($expiered) - strtotime($created_at);
       $diff = strtotime($expiered) - strtotime($date_tody);
if($diff_expiered_created < $diff){
    return redirect()->route('logout');
}
      $remaining_days= round($diff/ 86400);
       
       if($remaining_days<=0){
       
       return redirect()->route('license');
       }
       
 

    if(Auth::user()->group_id==5){
        $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
    
      }else{
    
        $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
    
      }
    

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

    $company= \DB::table('licenses')->latest("id")->first();
    return view('pages.license_system',compact(['company','permissionsGroup','total_price','logs','payment_methods']));


}

//licen system en



public function license_system_en(){
    
        $date_tody=date('Y-m-d');
     $created_at= Auth::user()->company->created_at;
        $expiered = \DB::table('licenses')->latest("id")->first()->expiered_date;
         $created_at=date_format($created_at,"Y-m-d");
       $diff_expiered_created = strtotime($expiered) - strtotime($created_at);
       $diff = strtotime($expiered) - strtotime($date_tody);
if($diff_expiered_created < $diff){
    return redirect()->route('logout');
}
      $remaining_days= round($diff/ 86400);
       
       if($remaining_days<=0){
       
       return redirect()->route('license');
       }
       
 

    if(Auth::user()->group_id==5){
        $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
    
      }else{
    
        $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
    
      }
    

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

    $company= \DB::table('licenses')->latest("id")->first();
    return view('pages_en.license_system',compact(['company','permissionsGroup','total_price','logs','payment_methods']));


}
//=====end user====================================================

}
