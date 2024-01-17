<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;


use App\Models\Invoice;
use App\Models\Log;
use App\Models\Purchase;
use App\Models\Product;

use App\Models\Permission;
use App\Models\Payment_method;
use Illuminate\Support\Facades\Auth;

use DB;
use App\Quotation;

define('PAGINATION_COUNT',10);
class BillController extends Controller
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
    
   


    


//=====bill=====
    public function bills()
    {
       
        
      $expiered_date_product=date('y-m-d', strtotime('+90 day', time()));


     
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
        

 $bills=Invoice::where('company_id',Auth::user()->company_id)->orderBy('created_at','DESC')
->select('invoice_no','created_at','payment_method_id',\DB::raw("count(purchase_id) as count"),
\DB::raw("sum(price) as total"))
->groupBy('invoice_no','created_at','payment_method_id')->get();

$products= Product::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

$purchases=Purchase::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$total= $bills->sum('total');





$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

        return view('pages.bills',compact(['purchases','products','permissionsGroup','bills','total','total_price','payment_methods','logs']));
    }
   
    //bill_en






    public function bills_en()
    {
       
      $expiered_date_product=date('y-m-d', strtotime('+90 day', time()));


     
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
      

$bills=Invoice::where('company_id',Auth::user()->company_id)->orderBy('created_at','DESC')
->select('invoice_no','created_at','payment_method_id',\DB::raw("count(purchase_id) as count"),
\DB::raw("sum(price) as total"))
->groupBy('invoice_no','created_at','payment_method_id')->get();

$products= Product::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

$purchases=Purchase::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$total= $bills->sum('total');





$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

      return view('pages_en.bills',compact(['purchases','products','permissionsGroup','bills','total','total_price','payment_methods','logs']));
  }


    public function bills_tody_en()
    {
   
      $expiered_date_product=date('y-m-d', strtotime('+90 day', time()));


     
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
        

 $bills=Invoice::where('company_id',Auth::user()->company_id)->where('created_at',date('y-m-d'))->orderBy('created_at','DESC')
->select('invoice_no','created_at','payment_method_id',\DB::raw("count(purchase_id) as count"),
\DB::raw("sum(price) as total"))
->groupBy('invoice_no','created_at','payment_method_id')->get();



$products= Product::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

$purchases=Purchase::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$total= $bills->sum('total');





$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

        return view('pages_en.bills',compact(['products','purchases','permissionsGroup','bills','total','total_price','payment_methods','logs']));
    }


    //bills
    public function bills_tody()
    {
   
      $expiered_date_product=date('y-m-d', strtotime('+90 day', time()));


     
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
        
          $bills=Invoice::where('company_id',Auth::user()->company_id)->where('created_at',date('y-m-d'))->orderBy('created_at','DESC')
          ->select('invoice_no','created_at','payment_method_id',\DB::raw("count(purchase_id) as count"),
          \DB::raw("sum(price) as total"))
          ->groupBy('invoice_no','created_at','payment_method_id')->get();
          
          

          $products= Product::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

          $purchases=Purchase::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
          $total= $bills->sum('total');
          
          


$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

        return view('pages.bills',compact(['products','purchases','permissionsGroup','bills','total','total_price','payment_methods','logs']));
    }





//details bills
    public function details_bills($id)
    {

        try {

  

            if(Auth::user()->group_id==5){
                $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
            
              }else{
            
                $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
            
              }

$bills=Invoice::where('invoice_no',$id)->selection()->get();
        
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

$total= $bills->sum('price');



$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

        return view('pages.invoices',compact(['permissionsGroup','bills','total','total_price','payment_methods','logs']));
   
    
               
            } catch (\Exception $ex) {
                    if(auth()->user()->lang !=='en'){

                    return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');

                }else{
                    return back()->with('error', 'Something went wrong');
  
                }
            }
        
    
     }
    


//details bills
    public function details_bills_en($id)
    {

        try {

  

            if(Auth::user()->group_id==5){
                $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
            
              }else{
            
                $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
            
              }

$bills=Invoice::where('invoice_no',$id)->selection()->get();
        
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

$total= $bills->sum('price');



$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

        return view('pages_en.invoices',compact(['permissionsGroup','bills','total','total_price','payment_methods','logs']));
   
    
               
            } catch (\Exception $ex) {
                    if(auth()->user()->lang !=='en'){

                    return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');

                }else{
                    return back()->with('error', 'Something went wrong');
  
                }
            }
        
    
     }
    

}
