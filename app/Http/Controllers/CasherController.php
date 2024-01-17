<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Log;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Payment_method;
use App\Models\Permission;
use App\Models\Purchase;
use Illuminate\Support\Facades\App;

use Illuminate\Support\Facades\Auth;


define('PAGINATION_COUNT',10);
class CasherController extends Controller
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
    
     //home page
    


//=====user=====


public function cashers(Request $request)
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
    $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
    $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
    $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
    if($request->all()){

      //$request->$request->name

           if(!empty($request->product_name)){
            $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
            $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
           
          $date=date('Y-m-d');
       if(Auth::user()->group_id==5){
    $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();

  }else{

    $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();

  }

            $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
          $product= Product::where('company_id',Auth::user()->company_id)->where('product_name', 'LIKE', "%$request->product_name%")->get();
if(!empty($product[0])){
 $purchases=Purchase::where('company_id',Auth::user()->company_id)->where('product_id', $product[0]->id)->get();
if(empty($purchases[0])){

return back()->with('error',"هذا المنتج غير موجود في اي باتش");   

}




}  else{

  return back()->with('error',"لا يوجد هذا المنتج   في قائمة المنتجات ");  

}  
 return view('pages.cashers',compact(['permissionsGroup','date','total_price','purchases','payment_methods','logs'])); 
         

      
//$request->barcode
}elseif(!empty($request->barcode)){
              $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
              $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
             
            $date=date('Y-m-d');
         if(Auth::user()->group_id==5){
      $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
  
    }else{
  
      $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
  
    }
  
              $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
            $product= Product::where('company_id',Auth::user()->company_id)->where('barcode', 'LIKE', "%$request->barcode%")->get();
  if(!empty($product[0])){
   $purchases=Purchase::where('company_id',Auth::user()->company_id)->where('product_id', $product[0]->id)->get();
  if(empty($purchases[0])){
  
  return back()->with('error',"هذا المنتج غير موجود في اي باتش");   
  
  }
  
  
  
  
  }  else{
  
    return back()->with('error',"لا يوجد هذا المنتج   في قائمة المنتجات ");  
  
  }  
   return view('pages.cashers',compact(['permissionsGroup','date','total_price','purchases','payment_methods','logs'])); 
}

    }
    return view('pages.cashers',compact(['permissionsGroup','total_price','payment_methods','logs']));   

  }
//eng




public function cashers_en(Request $request)
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
$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
if($request->all()){

//$request->$request->product_name

     if(!empty($request->product_name)){
      $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
      $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
     
    $date=date('Y-m-d');
 if(Auth::user()->group_id==5){
$permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();

}else{

$permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();

}

      $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
    $product= Product::where('company_id',Auth::user()->company_id)->where('product_name', 'LIKE', "%$request->product_name%")->get();
if(!empty($product[0])){
$purchases=Purchase::where('company_id',Auth::user()->company_id)->where('product_id', $product[0]->id)->get();
if(empty($purchases[0])){

return back()->with('error',"This product is not found in any patch.");   

}




}  else{

return back()->with('error',"This product is not in the product list");  

}  
return view('pages_en.cashers',compact(['permissionsGroup','date','total_price','purchases','payment_methods','logs'])); 
    
//$request->barcode
}elseif(!empty($request->barcode)){
        $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
        $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
       
      $date=date('Y-m-d');
   if(Auth::user()->group_id==5){
$permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();

}else{

$permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();

}

        $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
      $product= Product::where('company_id',Auth::user()->company_id)->where('barcode', 'LIKE', "%$request->barcode%")->get();
if(!empty($product[0])){
$purchases=Purchase::where('company_id',Auth::user()->company_id)->where('product_id', $product[0]->id)->get();
if(empty($purchases[0])){

return back()->with('error',"This product is not found in any patch.");   

}




}  else{

return back()->with('error',"This product is not in the product list");  

}  
return view('pages_en.cashers',compact(['permissionsGroup','date','total_price','purchases','payment_methods','logs'])); 
}

}
return view('pages_en.cashers',compact(['permissionsGroup','total_price','payment_methods','logs']));   

    
}


}
