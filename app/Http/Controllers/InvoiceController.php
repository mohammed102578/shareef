<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;
use App\Models\Purchase;
use App\Models\Determining_price;
use PhpParser\Node\Stmt\Global_;
use Psy\Command\ListCommand\GlobalVariableEnumerator;
use App\Models\Permission;
use Illuminate\Support\Facades\App;
use DB;
class InvoiceController extends Controller
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
    
   

public function invoice_number(){
    return $invoice_no = date('Y-m')."-".Auth::user()->id."-".rand(1000,9999);
     

}
    
   

//=====tickt=====
    public function tickt($invoice_no)
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

        
        $invoices =   Invoice::where('invoice_no',"$invoice_no")->selection()->get();
        $sum_price= Invoice::where('invoice_no',$invoice_no)->sum('price');



          return view('pages.tickt',compact(['permissionsGroup','invoices','sum_price']));    
    

    }




//=====tickt=====
public function tickt_en($invoice_no)
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

    
    $invoices =   Invoice::where('invoice_no',"$invoice_no")->selection()->get();
    $sum_price= Invoice::where('invoice_no',$invoice_no)->sum('price');



      return view('pages_en.tickt',compact(['permissionsGroup','invoices','sum_price']));    


}


    //a4 function
    public function A4($invoice_no)
    {
        
          
 if(Auth::user()->group_id==5){
    $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();

  }else{

    $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();

  }

        
        $invoices =   Invoice::where('invoice_no',"$invoice_no")->selection()->get();
        $sum_price= Invoice::where('invoice_no',$invoice_no)->sum('price');



          return view('pages.A4',compact(['permissionsGroup','invoices','sum_price']));    
    

    }



    //a4 function
    public function A4_en($invoice_no)
    {
        
          
 if(Auth::user()->group_id==5){
    $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();

  }else{

    $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();

  }

        
        $invoices =   Invoice::where('invoice_no',"$invoice_no")->selection()->get();
        $sum_price= Invoice::where('invoice_no',$invoice_no)->sum('price');



          return view('pages_en.A4',compact(['permissionsGroup','invoices','sum_price']));    
    

    }

//end of A4 function








   
    //==================================================================store
    public function store_invoices(Request $request)
    {
     // try{
      //set timeZone to compatible with sudan 
   
     date_default_timezone_set("Africa/Cairo");

 if($request->time){

   $time_request= date("g:i A", strtotime($time=$request->time));
if(\str_contains($time_request,"PM")){
$time=date("H:i ", strtotime($request->time));
}else{
   $time=date("h:i ", strtotime($request->time));

   if(\str_contains($request->time,"12")){
    $time=date("H:i ", strtotime($request->time));
  
          }
  

}


}else{
 $time_request= date("g:i: A");



  if(\str_contains($time_request,"PM")){
    $time=date("H:i ");
    }else{
     $time=date("h:i ");
    
   if(\str_contains($time_request,"12")){
    $time=date("H:i ");
  
          }
    }

}
    

        $invoice_no= $this->invoice_number();
    
    // return $request->all();

        if(isset($request->print)){
          
 if(isset($request->quantity)){
  DB::beginTransaction();
  $invoice_create=array();
$invoice=array();
$purchase=array();
$price=array();
$quantity=array();
$determining_prices=array();
for ($i = 0; $i < count($request->quantity); $i++) {
  
  //return quantity for this item by id $request->purchase_id[$i]
  if(isset($request->created_at)){
  $purchase[$i]=Purchase::where('id',$request->purchase_id[$i])->
  where('product_id',$request->product_id[$i])->select()->get();
  }

 
//return from log
  if(isset($request->price[$i])){

    $price[]=$request->price[$i];
   }else{

//check if this product found in any batch

if(isset($purchase[$i][0])){


    //return from sale
  $determining_prices[$i]=Determining_price::where('product_id',$request->product_id[$i])->select('price')->get();
if(isset($determining_prices[$i][0]->price)){
    $price[]=$determining_prices[$i][0]->price * $request->quantity[$i];


//if found in any batch



}else{



  if(auth()->user()->lang !=='en'){

    return back()->with("error","لم يتم تحديد سعر البيع لاحدى المنتجات المدخلة");
  
  }else{
    return back()->with('error', 'The selling price of one of the entered drugs has not been determined.');
  
  }   
  

  
}



}else{

  if(auth()->user()->lang !=='en'){

    return back()->with("error"," احدى المنتجات المدخلة غير موجودة في اي باتش");
  
  }else{
    return back()->with('error', 'One of the products entered is not found in any patch.');
  
  }   
  
  

}

   }

  //return total quantity of invoice for same $request->purchase_id[$i]
   $invoices=Invoice::where('purchase_id',$request->purchase_id[$i])->sum('quantity');

   if(isset($invoices)){
    $invoice[$i]=$invoices;
   }else{
    $invoice[$i]=0;
   }



}

//return $invoice[0];
    for ($i = 0; $i < count($request->quantity); $i++) {
     // $purchase[$i][0]->quantity - $invoice[0];
     if(isset($invoice[0])){
$count_quantity=$invoice[0];
     }else{
      $count_quantity=0;
     }


     //check if quantity in stock greater than request acording to created at
if(isset($request->created_at)){
$quantity[]=$purchase[$i][0]->quantity - $count_quantity >=  $request->quantity[$i];
}else{
   $quantity[]=$request->quantity[$i];
}


      if($quantity[$i]){
          
if($request->created_at){
  $date=$request->created_at.date(" G:i:s");
}else{
  $date=date('y-m-d H:i:s');
}
       $invoice_create[]= [
            'purchase_id'=> $request->purchase_id[$i],
            'invoice_no'=> $invoice_no,
            'quantity' => $request->quantity[$i],
            'price' => $price[$i] ,
            'user_id' => Auth::user()->id,
            'company_id'=>Auth::user()->company_id,
            'payment_method_id'=>$request->payment_method_id,
            'created_at'=>$date,
            'time'=>$time
        ];  
      

 




      }else{
        if(auth()->user()->lang !=='en'){

          return redirect()->back()->with("error"," الكمية المدخلة لاحدى المنتجات  غير متوفرة");
        
        }else{
          return back()->with('error', '  The quantity entered for one of the products is not available.');
        
        }   
        
      }
     //end of the check if quantity of the item -quantity



     }
     Invoice::insert($invoice_create);
     DB::commit();
     $log = Log::where('user_id',Auth::user()->id);
    
    $log->delete();
if($request->print==1){
    if($request->lang=="ar"){

      return redirect()->route('tickt',$invoice_no);

    }else{
      return redirect()->route('tickt_en',$invoice_no);
 
    }

  }else{

    if($request->lang=="ar"){
  
      return redirect()->route('A4',$invoice_no);

    }else{
      return redirect()->route('A4_en',$invoice_no);
 
    }

  }

 }
        
       

     if(auth()->user()->lang !=='en'){

  return back()->with('error',  '   لم تقم باضافة اي دواء  ');

}else{
  return back()->with('error', 'You have not added any medication');

}   




}
try{
}catch (\Exception $ex) {
  if(auth()->user()->lang !=='en'){

  return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');

}else{
  return back()->with('error', 'Something went wrong');

} 

    }
  }
}
