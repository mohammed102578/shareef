<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

use App\Models\Product;
use App\Models\Log;
use App\Models\Purchase;
use App\Http\Requests\Store_Request;
use App\Models\Payment_method;
use Illuminate\Support\Facades\Auth;
use App\Models\Permission;
use App\Models\Invoice;

define('PAGINATION_COUNT',10);
class StoreController extends Controller
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
    
    public function stores()
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
        
 $stores=Purchase::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();


$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

$products=Product::where('company_id',Auth::user()->company_id)->selection()->get();

//return $products;
$med_quantity=array();
foreach( $stores as $store)
{
    $med_quantity[]= $invoice=Invoice::where('purchase_id', $store->id)->select( \DB::raw("sum(quantity) as quantity"))->groupBy('purchase_id')->get();

}
//return $med_quantity;
 

        return view('pages.stores',compact(['med_quantity','permissionsGroup','total_price','stores','logs','products','payment_methods']));
    }
   


    public function stores_en()
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
        
 $stores=Purchase::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();


$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

$products=Product::where('company_id',Auth::user()->company_id)->selection()->get();

//return $products;
$med_quantity=array();
foreach( $stores as $store)
{
    $med_quantity[]= $invoice=Invoice::where('purchase_id', $store->id)->select( \DB::raw("sum(quantity) as quantity"))->groupBy('purchase_id')->get();

}
//return $med_quantity;
 

        return view('pages_en.stores',compact(['med_quantity','permissionsGroup','total_price','stores','logs','products','payment_methods']));
    }
   

//end of store en

    public function update_stores($id ,Store_Request $request)
    {
     
            try {
              
        $stores= Purchase::find($id);
        
        if (!$stores)
            if(auth()->user()->lang !=='en'){

            return back()->with('error', 'هذا العنصر غير موجود');

        }else{
            return back()->with('error', 'This Item does not exist.');

        }   
        // update date

        if (!$request->has('stop'))
        $request->request->add(['stop' => 0]);
    else
        $request->request->add(['stop' => 1]);
        

       

        Purchase::where('id', $stores->id)
            ->update([
            'product_id'=>$request->product_id,
            'stop'=>$request->stop,
         
            ]);


            if(auth()->user()->lang !=='en'){

                        return back()->with('success', 'تم ألتحديث بنجاح');
    
                    }else{
                        return back()->with('success', 'Updated successfully');
      
                    }

          
            } catch (\Exception $ex) {
                    if(auth()->user()->lang !=='en'){

                    return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');

                }else{
                    return back()->with('error', 'Something went wrong');
  
                }
        
               
            }
        
        }




}
