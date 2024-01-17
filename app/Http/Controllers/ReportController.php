<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Determining_price;
use App\Models\Group;
use Illuminate\Support\Facades\App;

use App\Models\Shift;

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
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


define('PAGINATION_COUNT',10);
class ReportController extends Controller
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
   


    public function reports()
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

     if(Auth::user()->group_id==5){
                $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
            
              }else{
            
                $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
            
              }
  }

        $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
        $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
        $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
        return view('pages.reports',compact(['permissionsGroup','total_price','logs','payment_methods']));
    }

    //report en

    public function reports_en()
    {
try{
      
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

     if(Auth::user()->group_id==5){
                $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
            
              }else{
            
                $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
            
              }
  }

        $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
        $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
        $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
       
        return view('pages_en.reports',compact(['permissionsGroup','total_price','logs','payment_methods']));
      } catch (\Exception $ex) {
            if(auth()->user()->lang !=='en'){

                    return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');

                }else{
                    return back()->with('error', 'Something went wrong');
  
                }
    }}


    //===========================================report sales 
    public function reports_sales(Request $request)
    {
      try{
        $shifts=Shift::where('company_id',Auth::user()->company_id)->selection()->get();
  
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
          
           $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
           $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
           $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
           $shifts=Shift::where('company_id',Auth::user()->company_id)->selection()->get();
          
           if($request->all()){
  
          
        if(Auth::user()->group_id==5){
       $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
   
     }else{
   
        if(Auth::user()->group_id==5){
                   $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
               
                 }else{
               
                   $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
               
                 }
     }
   
    
    
    if(isset($request->shift)){
    $shift=Shift::where('company_id',Auth::user()->company_id)->
    where('id',$request->shift)->selection()->get();
  
    $start_time=$shift[0]->start_time;
    $end_time=$shift[0]->end_time;
  
  
  
    $bills=Invoice::where('company_id',Auth::user()->company_id)->
    where('created_at',"<=","$request->to")->where('created_at',">=","$request->from")->
    orderBy('created_at','DESC')
    ->select('invoice_no','created_at','payment_method_id','time',\DB::raw("count(purchase_id) as count"),
    \DB::raw("sum(price) as total"))->where('time',">=",$start_time)->where('time',"<",$end_time)
    ->groupBy('invoice_no','created_at','payment_method_id','time')->get();
    
  
  
  
    }else{
  
  
      $bills=Invoice::where('company_id',Auth::user()->company_id)->
      where('created_at',"<=","$request->to")->where('created_at',">=","$request->from")->
      orderBy('created_at','DESC')
      ->select('invoice_no','created_at','payment_method_id',\DB::raw("count(purchase_id) as count"),
      \DB::raw("sum(price) as total"))->groupBy('invoice_no','created_at','payment_method_id')->get();
      
    
  
    }
  
  
  
    
               $total= $bills->sum('total');
               $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
               $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
               $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
              
    
    
    
   
    return view('pages.sales_report',compact(['shifts','permissionsGroup','bills','total','total_price','payment_methods','logs']));
   
           }
            return view('pages.sales_report',compact(['shifts','permissionsGroup','total_price','payment_methods','logs']));
          
         
          }catch (\Exception $ex) {
                if(auth()->user()->lang !=='en'){
  
                      return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');
  
                  }else{
                      return back()->with('error', 'Something went wrong');
    
                  }
        }}
   
  
  

//==================================================================report sale en
     public function reports_sales_en(Request $request)
     {
 
      try{
      $shifts=Shift::where('company_id',Auth::user()->company_id)->selection()->get();

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
        
         $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
         $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
         $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
         $shifts=Shift::where('company_id',Auth::user()->company_id)->selection()->get();
        
         if($request->all()){

        
      if(Auth::user()->group_id==5){
     $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
 
   }else{
 
      if(Auth::user()->group_id==5){
                 $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
             
               }else{
             
                 $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
             
               }
   }
 
  // $bills=Invoice::where('company_id',Auth::user()->company_id)->
  // where('created_at',"<=","$request->to")->where('created_at',">=","$request->from")->
  // orderBy('created_at','DESC')
  // ->select('invoice_no','created_at','payment_method_id',\DB::raw("count(purchase_id) as count"),
  // \DB::raw("sum(price) as total"))
  // ->groupBy('invoice_no','created_at','payment_method_id')->get();
  
  
  if(isset($request->shift)){
  $shift=Shift::where('company_id',Auth::user()->company_id)->
  where('id',$request->shift)->selection()->get();

  $start_time=$shift[0]->start_time;
  $end_time=$shift[0]->end_time;



  $bills=Invoice::where('company_id',Auth::user()->company_id)->
  where('created_at',"<=","$request->to")->where('created_at',">=","$request->from")->
  orderBy('created_at','DESC')
  ->select('invoice_no','created_at','payment_method_id','time',\DB::raw("count(purchase_id) as count"),
  \DB::raw("sum(price) as total"))->where('time',">=",$start_time)->where('time',"<",$end_time)
  ->groupBy('invoice_no','created_at','payment_method_id','time')->get();
  



  }else{


    $bills=Invoice::where('company_id',Auth::user()->company_id)->
    where('created_at',"<=","$request->to")->where('created_at',">=","$request->from")->
    orderBy('created_at','DESC')
    ->select('invoice_no','created_at','payment_method_id',\DB::raw("count(purchase_id) as count"),
    \DB::raw("sum(price) as total"))->groupBy('invoice_no','created_at','payment_method_id')->get();
    
  

  }



  
             $total= $bills->sum('total');
             $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
             $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
             $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
            
  
  
  
 
  return view('pages_en.sales_report',compact(['shifts','permissionsGroup','bills','total','total_price','payment_methods','logs']));
 
         }
          return view('pages_en.sales_report',compact(['shifts','permissionsGroup','total_price','payment_methods','logs']));
        
       
        }catch (\Exception $ex) {
              if(auth()->user()->lang !=='en'){

                    return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');

                }else{
                    return back()->with('error', 'Something went wrong');
  
                }
      }}
 




//========================================================repore_purchase
    public function reports_purchas(Request $request)
    {
try{

      
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
       


$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
 if(Auth::user()->group_id==5){
                $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
            
              }else{
            
                $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
            
              }
$products=Product::where('company_id',Auth::user()->company_id)->selection()->get();
$total= Purchase::where('company_id',Auth::user()->company_id)->sum('price');
if($request->all()){
     if(Auth::user()->group_id==5){
                $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
            
              }else{
            
                $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
            
              }
    
    $purchases=Purchase::where('company_id',Auth::user()->company_id)->
    where('created_at',"<=","$request->to")->where('created_at',">=","$request->from")->
    orderBy('id', 'DESC')->selection()->get();
    $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
    $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
    $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
    
    $products=Product::where('company_id',Auth::user()->company_id)->selection()->get();
    $total= Purchase::where('company_id',Auth::user()->company_id)->sum('price');



    return view('pages.purchases_report',compact(['permissionsGroup','total_price','total','logs','purchases','products','payment_methods']));
}
      



return view('pages.purchases_report',compact(['permissionsGroup','total_price','total','logs','products','payment_methods']));


}catch (\Exception $ex) {
      if(auth()->user()->lang !=='en'){

                    return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');

                }else{
                    return back()->with('error', 'Something went wrong');
  
                }
}}
   
   
    //purchase en 

     //=================================repore_purchase
     public function reports_purchas_en(Request $request)
     {
 try{
 
      
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
       
 
 
 $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
 $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
 $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
  if(Auth::user()->group_id==5){
                 $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
             
               }else{
             
                 $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
             
               }
 $products=Product::where('company_id',Auth::user()->company_id)->selection()->get();
 $total= Purchase::where('company_id',Auth::user()->company_id)->sum('price');
 if($request->all()){
      if(Auth::user()->group_id==5){
                 $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
             
               }else{
             
                 $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
             
               }
     
     $purchases=Purchase::where('company_id',Auth::user()->company_id)->
     where('created_at',"<=","$request->to")->where('created_at',">=","$request->from")->
     orderBy('id', 'DESC')->selection()->get();
     $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
     $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
     $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
     
     $products=Product::where('company_id',Auth::user()->company_id)->selection()->get();
     $total= Purchase::where('company_id',Auth::user()->company_id)->sum('price');
 
 
 
     return view('pages_en.purchases_report',compact(['permissionsGroup','total_price','total','logs','purchases','products','payment_methods']));
 }
       
 

 
 return view('pages_en.purchases_report',compact(['permissionsGroup','total_price','total','logs','products','payment_methods']));
}catch (\Exception $ex) {
      if(auth()->user()->lang !=='en'){

                    return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');

                }else{
                    return back()->with('error', 'Something went wrong');
  
                }
}}
    
   
     //===================================================end of th purchase


    public function reports_percent_product(Request $request)
    {
try{
      
        $date_tody=date('Y-m-d');
     $created_at= Auth::user()->company->created_at;
        $expiered = \DB::table('licenses')->latest("id")->first()->expiered_date;
         $created_at=date_format($created_at,"Y-m-d");
       $diff_expiered_created = strtotime($expiered) - strtotime($created_at);
       $diff = strtotime($expiered) - strtotime($date_tody);
       $purchases=Purchase::where('company_id',Auth::user()->company_id)->select()->get();

if($diff_expiered_created < $diff){
    return redirect()->route('logout');
}
      $remaining_days= round($diff/ 86400);
       
       if($remaining_days<=0){
       
       return redirect()->route('license');
       }
       



        $products=Product::where('company_id',Auth::user()->company_id)
        ->orderBy('id', 'DESC')->selection()->get();

        $purchases=Purchase::where('company_id',Auth::user()->company_id)->select()->get();


        $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
        $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
        $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
 if(Auth::user()->group_id==5){
    $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();

  }else{

     if(Auth::user()->group_id==5){
                $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
            
              }else{
            
                $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
            
              }
  }
        $show_style=$request->show_style;
      
       if(!empty($request->show_style)){
      
        $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
        $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
        $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
        $products=Product::where('company_id',Auth::user()->company_id)
        ->orderBy('id', 'DESC')->selection()->get();
//work pace


 $purchases=Purchase::where('company_id',Auth::user()->company_id)->select()->get();


   $product_graph=Invoice::where('purchase_id',$request->purchase_id)->where('company_id',Auth::user()->company_id)->orderBy('created_at','ASC')
    ->select('purchase_id',\DB::raw("sum(quantity) as quantity"),\DB::raw('MONTH(created_at) month'))
    ->groupBy('purchase_id','month')->get()->toArray();

  
   $month = array_column($product_graph, 'month');


   

    $product_month=array();
    $product_month_en=array();

foreach($month as $product_monthes){

  if($product_monthes==1){

   $product_month_en[]="january";
   $product_month[]="يناير";
 
  }if($product_monthes==2){
     $product_month[]="فبراير";
     $product_month_en[]="February";
 
  }if($product_monthes==3){
   $product_month_en[]="March ";
 
     $product_month[]="مارس";
  }if($product_monthes==4){
     $product_month[]="ابريل";
     $product_month_en[]="April ";
 
  }if($product_monthes==5){
   $product_month_en[]="يناير";
 
     $product_month[]="May";
  }if($product_monthes==6){
   $product_month_en[]="June";
 
     $product_month[]="يونيو";
  }if($product_monthes==7){
     $product_month[]="يوليو";
     $product_month_en[]="July ";
 
  }if($product_monthes==8){
   $product_month_en[]="August";
 
     $product_month[]="اغسطس";
  }if($product_monthes==9){
   $product_month_en[]="September";
 
     $product_month[]="سبتمبر";
  }if($product_monthes==10){
     $product_month[]="اكتوبر";
     $product_month_en[]="October";
 
  }if($product_monthes==11){
   $product_month_en[]="November ";
 
     $product_month[]="نوفمبر";
  }if($product_monthes==12){
   $product_month_en[]="December ";
 
     $product_month[]="ديسمبر";

}
}

     $product_month;

     $product_graph = array_column($product_graph, 'quantity');
     $pie_data=array();
   for($i=0; $i<count($product_graph);$i++ ){



      $json_data[]=json_encode("{name : '$product_month_en[$i]' , y : $product_graph[$i]}");
     
   
   }


$js=json_encode($json_data);
$jso=str_replace('"\"',"",$js);

$pie_data=str_replace('\""',"",$jso);


   $product_graphs= json_encode($product_graph,JSON_NUMERIC_CHECK);

    $product_months= json_encode($product_month,JSON_NUMERIC_CHECK);

    
   $month= json_encode($month);



//workspace
        return view('pages.reports_percent_product',compact(['purchases','pie_data','month','product_months','show_style','product_graphs','products','permissionsGroup','total_price','logs','payment_methods']));

       }
       
        return view('pages.reports_percent_product',compact(['purchases','products','permissionsGroup','total_price','logs','payment_methods']));
   
      }catch (\Exception $ex) {
            if(auth()->user()->lang !=='en'){

                    return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');

                }else{
                    return back()->with('error', 'Something went wrong');
  
                }
    }}
//report product of th sale


public function reports_percent_product_en(Request $request)
{
  try{
      
    $date_tody=date('Y-m-d');
 $created_at= Auth::user()->company->created_at;
    $expiered = \DB::table('licenses')->latest("id")->first()->expiered_date;
     $created_at=date_format($created_at,"Y-m-d");
   $diff_expiered_created = strtotime($expiered) - strtotime($created_at);
   $diff = strtotime($expiered) - strtotime($date_tody);
   $purchases=Purchase::where('company_id',Auth::user()->company_id)->select()->get();

if($diff_expiered_created < $diff){
return redirect()->route('logout');
}
  $remaining_days= round($diff/ 86400);
   
   if($remaining_days<=0){
   
   return redirect()->route('license');
   }
   



    $products=Product::where('company_id',Auth::user()->company_id)
    ->orderBy('id', 'DESC')->selection()->get();

    $purchases=Purchase::where('company_id',Auth::user()->company_id)->select()->get();


    $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
    $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
    $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
if(Auth::user()->group_id==5){
$permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();

}else{

 if(Auth::user()->group_id==5){
            $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
        
          }else{
        
            $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
        
          }
}
    $show_style=$request->show_style;
  
   if(!empty($request->show_style)){
  
    $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
    $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
    $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
    $products=Product::where('company_id',Auth::user()->company_id)
    ->orderBy('id', 'DESC')->selection()->get();
//work pace


$purchases=Purchase::where('company_id',Auth::user()->company_id)->select()->get();


$product_graph=Invoice::where('purchase_id',$request->purchase_id)->where('company_id',Auth::user()->company_id)->orderBy('created_at','ASC')
->select('purchase_id',\DB::raw("sum(quantity) as quantity"),\DB::raw('MONTH(created_at) month'))
->groupBy('purchase_id','month')->get()->toArray();


$month = array_column($product_graph, 'month');




$product_month=array();
$product_month_en=array();

foreach($month as $product_monthes){

if($product_monthes==1){

$product_month_en[]="january";
$product_month[]="يناير";

}if($product_monthes==2){
 $product_month[]="فبراير";
 $product_month_en[]="February";

}if($product_monthes==3){
$product_month_en[]="March ";

 $product_month[]="مارس";
}if($product_monthes==4){
 $product_month[]="ابريل";
 $product_month_en[]="April ";

}if($product_monthes==5){
$product_month_en[]="يناير";

 $product_month[]="May";
}if($product_monthes==6){
$product_month_en[]="June";

 $product_month[]="يونيو";
}if($product_monthes==7){
 $product_month[]="يوليو";
 $product_month_en[]="July ";

}if($product_monthes==8){
$product_month_en[]="August";

 $product_month[]="اغسطس";
}if($product_monthes==9){
$product_month_en[]="September";

 $product_month[]="سبتمبر";
}if($product_monthes==10){
 $product_month[]="اكتوبر";
 $product_month_en[]="October";

}if($product_monthes==11){
$product_month_en[]="November ";

 $product_month[]="نوفمبر";
}if($product_monthes==12){
$product_month_en[]="December ";

 $product_month[]="ديسمبر";

}
}

 $product_month;

 $product_graph = array_column($product_graph, 'quantity');
 $pie_data=array();
for($i=0; $i<count($product_graph);$i++ ){



  $json_data[]=json_encode("{name : '$product_month_en[$i]' , y : $product_graph[$i]}");
 

}


$js=json_encode($json_data);
$jso=str_replace('"\"',"",$js);

$pie_data=str_replace('\""',"",$jso);


$product_graphs= json_encode($product_graph,JSON_NUMERIC_CHECK);

$product_months= json_encode($product_month_en,JSON_NUMERIC_CHECK);


$month= json_encode($month);



//workspace
    return view('pages_en.reports_percent_product',compact(['purchases','pie_data','month','product_months','show_style','product_graphs','products','permissionsGroup','total_price','logs','payment_methods']));

   }
   
    return view('pages_en.reports_percent_product',compact(['purchases','products','permissionsGroup','total_price','logs','payment_methods']));
  }catch (\Exception $ex) {
        if(auth()->user()->lang !=='en'){

                return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');

            }else{
                return back()->with('error', 'Something went wrong');

            }
}}

}
