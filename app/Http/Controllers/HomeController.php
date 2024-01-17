<?php

namespace App\Http\Controllers;
use App\Models\Company;
use App\Models\Determining_price;
use App\Models\Group;
use App\Models\Invoice;
use App\Models\Log;
use App\Models\Product;
use App\Models\Month;
use App\Models\Packing;
use App\Models\Page;
use App\Models\Payment_method;
use App\Models\Permission;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Date_log;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;



define('PAGINATION_COUNT',10);
class HomeController extends Controller
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
    


  


    
    public function sorry()
    {
      Auth::logout();
        return view('auth.sorry');
    }

   

     //home page
    public function index()
    {

 //start permission

//start change language
App::setLocale('ar');
$local= App::getLocale();
User::where('id',Auth::user()->id)->update(['lang' => $local ]);
//end change language   
   



//start get macaddress
$mac=exec('getmac');
$mac=strtok($mac,' ');
$mac="00-24-1D-BD-75-4A";
$company_id=auth::user()->company_id;
$mac_address=Company::where('companies_id',$company_id)->get()->first()->mac_address;
if($mac_address !== $mac){
return redirect()->route('logout_mac');
}
//end get mac address




//start handel date_log
$date_log= Date_log::select()->get()->count();
if($date_log==0){
Date_log::create(['date'=>date('y-m-d')]);
}else{
$last_id = \DB::table('date_logs')->latest("id")->first()->id;
$date_log_last_id= Date_log::where('id',$last_id )->select('date')->get()->first()->date;
$date_tody= date('y-m-d');
$diff_between_last_day_tody =  strtotime($date_tody)-strtotime($date_log_last_id);

 $remaining_days= round( $diff_between_last_day_tody / 86400);

 if($remaining_days < 0){
  return redirect()->route('logout_expierd');
  } 

 if($remaining_days==0){
    Date_log::where('id',$last_id )->update(['date'=>$date_tody]);
 }else{
    Date_log::create(['date'=>date('y-m-d')]);
 }



}

//end date_log



  
//check start if user allready stoped
  if(Auth::user()->stop==1){
    return redirect()->route('logout_stop');
   
  }
 //check end 



  //date today
 $date_tody=date('Y-m-d');

 //date when created company
 $created_at= Auth::user()->company->created_at;
    
    $count = \DB::table('licenses')->latest("id")->get()->count();
    if($count == 0){
      return redirect()->route('license');
    }else{
      //select system expierd date
    $expiered = \DB::table('licenses')->latest("id")->first()->expiered_date;

    //change format date company create
    $created_at=date_format($created_at,"Y-m-d");


   //return diffrent between date company created and expiered
    $diff_expiered_created = strtotime($expiered) - strtotime($created_at);

  //return diffrent between date tody and expiered
   $diff = strtotime($expiered) - strtotime($date_tody);


if($diff_expiered_created < $diff){
return redirect()->route('logout_expierd');
}
  $remaining_days= round($diff/ 86400);
   
   if($remaining_days<=0 ){
   
   return redirect()->route('license');
   }
  }

  $expiered_date_product=date('y-m-d', strtotime('+90 day', time()));
 //end permission
  if(Auth::user()->group_id==5){
    $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();

  }else{

    $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();

  }

        $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
       
        $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

 
        $invoice=Invoice::where('company_id',Auth::user()->company_id)->where('created_at','LIKE',"%$date_tody%")->orderBy('created_at','DESC')
        ->select('invoice_no','created_at','payment_method_id',\DB::raw("count(purchase_id) as count"),
        \DB::raw("sum(price) as total"))
        ->groupBy('invoice_no','created_at','payment_method_id')->get()->count();
         
        $purchase=Purchase::where('company_id',Auth::user()->company_id)->where('purchase_date','LIKE',"%$date_tody%")->select()->count();
         $bills=Invoice::where('company_id',Auth::user()->company_id)->orderBy('created_at','DESC')
         ->select('invoice_no','created_at','payment_method_id',\DB::raw("count(purchase_id) as count"),
         \DB::raw("sum(price) as total"))
         ->groupBy('invoice_no','created_at','payment_method_id')->get()->count();
         $suppliers=Supplier::where('company_id',Auth::user()->company_id)->select()->count();
         $products=Product::where('company_id',Auth::user()->company_id)->select()->count();
         $purchase_exp_date=Purchase::where('company_id',Auth::user()->company_id)->where('exp_date' , "<","$expiered_date_product")->orderBy('id', 'DESC')->selection()->get();
         $stores=Purchase::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();


        return view('pages.home',compact(['stores','permissionsGroup','logs','payment_methods','invoice','purchase','bills','suppliers','products','purchase_exp_date']));
    }












    public function index_en()
    {
    //start permission

//start change language
App::setLocale('en');
$local= App::getLocale();
User::where('id',Auth::user()->id)->update(['lang' => $local ]);
//end change language   
   



//start get macaddress
$mac=exec('getmac');
$mac=strtok($mac,' ');
$mac="00-24-1D-BD-75-4A";
$company_id=auth::user()->company_id;
$mac_address=Company::where('companies_id',$company_id)->get()->first()->mac_address;
if($mac_address !== $mac){
return redirect()->route('logout_mac');
}
//end get mac address




//start handel date_log
$date_log= Date_log::select()->get()->count();
if($date_log==0){
Date_log::create(['date'=>date('y-m-d')]);
}else{
$last_id = \DB::table('date_logs')->latest("id")->first()->id;
$date_log_last_id= Date_log::where('id',$last_id )->select('date')->get()->first()->date;
$date_tody= date('y-m-d');
$diff_between_last_day_tody =  strtotime($date_tody)-strtotime($date_log_last_id);

 $remaining_days= round( $diff_between_last_day_tody / 86400);

 if($remaining_days < 0){
  return redirect()->route('logout_expierd');
  } 

 if($remaining_days==0){
    Date_log::where('id',$last_id )->update(['date'=>$date_tody]);
 }else{
    Date_log::create(['date'=>date('y-m-d')]);
 }



}

//end date_log



  
//check start if user allready stoped
  if(Auth::user()->stop==1){
    return redirect()->route('logout_stop');
   
  }
 //check end 



  //date today
 $date_tody=date('Y-m-d');

 //date when created company
 $created_at= Auth::user()->company->created_at;
    
    $count = \DB::table('licenses')->latest("id")->get()->count();
    if($count == 0){
      return redirect()->route('license');
    }else{
      //select system expierd date
    $expiered = \DB::table('licenses')->latest("id")->first()->expiered_date;

    //change format date company create
    $created_at=date_format($created_at,"Y-m-d");


   //return diffrent between date company created and expiered
    $diff_expiered_created = strtotime($expiered) - strtotime($created_at);

  //return diffrent between date tody and expiered
   $diff = strtotime($expiered) - strtotime($date_tody);


if($diff_expiered_created < $diff){
return redirect()->route('logout_expierd');
}
  $remaining_days= round($diff/ 86400);
   
   if($remaining_days<=0 ){
   
   return redirect()->route('license');
   }
  }

  $expiered_date_product=date('y-m-d', strtotime('+90 day', time()));
 //end permission

  if(Auth::user()->group_id==5){
    $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();

  }else{

    $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();

  }

        $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
       
        $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
         
         $invoice=Invoice::where('company_id',Auth::user()->company_id)->where('created_at','LIKE',"%$date_tody%")->orderBy('created_at','DESC')
         ->select('invoice_no','created_at','payment_method_id',\DB::raw("count(purchase_id) as count"),
         \DB::raw("sum(price) as total"))
         ->groupBy('invoice_no','created_at','payment_method_id')->get()->count();
         
         $purchase=Purchase::where('company_id',Auth::user()->company_id)->where('purchase_date','LIKE',"%$date_tody%")->select()->count();
         $bills=Invoice::where('company_id',Auth::user()->company_id)->orderBy('created_at','DESC')
         ->select('invoice_no','created_at','payment_method_id',\DB::raw("count(purchase_id) as count"),
         \DB::raw("sum(price) as total"))
         ->groupBy('invoice_no','created_at','payment_method_id')->get()->count();
         $suppliers=Supplier::where('company_id',Auth::user()->company_id)->select()->count();
         $products=Product::where('company_id',Auth::user()->company_id)->select()->count();
         $purchase_exp_date=Purchase::where('company_id',Auth::user()->company_id)->where('exp_date' , "<","$expiered_date_product")->orderBy('id', 'DESC')->selection()->get();
         $stores=Purchase::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();


        return view('pages_en.home_en',compact(['stores','permissionsGroup','logs','payment_methods','invoice','purchase','bills','suppliers','products','purchase_exp_date']));
    }











    public function tables()
    {
          
if(Auth::user()->group_id==5){
            $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
        
          }else{
        
            $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
        
          }
        $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
        $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
        $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
        return view('pages.tables',compact(['permissionsGroup','total_price','logs','payment_methods']));
    }

    //tablen en


    public function tables_en()
    {
          
if(Auth::user()->group_id==5){
            $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
        
          }else{
        
            $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
        
          }
        $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
        $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
        $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
        return view('pages_en.tables',compact(['permissionsGroup','total_price','logs','payment_methods']));
    }
//table en

//report en
    public function reports()
    {
          
if(Auth::user()->group_id==5){
            $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
        
          }else{
        
            $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
        
          }
        $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
        $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
        $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
        return view('pages.reports',compact(['permissionsGroup','total_price','logs','payment_methods']));
    }


//report en


//report en
public function reports_en()
{
      
if(Auth::user()->group_id==5){
        $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
    
      }else{
    
        $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
    
      }
    $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
    $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
    $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
    return view('pages_en.reports',compact(['permissionsGroup','total_price','logs','payment_methods']));
}


//report en



//settings
public function settings()
{
      
if(Auth::user()->group_id==5){
        $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
    
      }else{
    
        $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
    
      }
    $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
    $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
    $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
    return view('pages.settings',compact(['permissionsGroup','total_price','logs','payment_methods']));
}
//settings

//setting_en
public function settings_en()
{
      
if(Auth::user()->group_id==5){
        $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
    
      }else{
    
        $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
    
      }
    $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
    $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
    $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
    return view('pages_en.settings',compact(['permissionsGroup','total_price','logs','payment_methods']));
}

//settings_en
}
