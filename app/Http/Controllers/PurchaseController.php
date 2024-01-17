<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Log;
use App\Models\Product;
use App\Models\Permission;
use App\Models\Purchase;
use App\Models\Payment_method;
use App\Models\Supplier;
use App\Models\Invoice;
use App\Http\Requests\Purchase_Request;
use Illuminate\Support\Facades\App;


use Illuminate\Support\Facades\Auth;


define('PAGINATION_COUNT',10);
class PurchaseController extends Controller
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
    
    
//=====purrchase=====
    public function purchases()
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
        $suppliers=Supplier::where('company_id',Auth::user()->company_id)->selection()->get();
$purchases=Purchase::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

$products=Product::where('company_id',Auth::user()->company_id)->selection()->get();
$total= Purchase::where('company_id',Auth::user()->company_id)->sum('price');

        return view('pages.purchases',compact(['permissionsGroup','suppliers','total_price','total','logs','purchases','products','payment_methods']));
    }



//=====purrchase=====
public function purchases_en()
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
    $suppliers=Supplier::where('company_id',Auth::user()->company_id)->selection()->get();
$purchases=Purchase::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

$products=Product::where('company_id',Auth::user()->company_id)->selection()->get();
$total= Purchase::where('company_id',Auth::user()->company_id)->sum('price');

    return view('pages_en.purchases',compact(['permissionsGroup','suppliers','total_price','total','logs','purchases','products','payment_methods']));
}




//purchase today



//=====purrchase=====
public function purchases_tody_en()
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
    $suppliers=Supplier::where('company_id',Auth::user()->company_id)->selection()->get();
$purchases=Purchase::where('company_id',Auth::user()->company_id)->where('purchase_date',date('y-m-d'))->orderBy('id', 'DESC')->selection()->get();
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

$products=Product::where('company_id',Auth::user()->company_id)->selection()->get();
$total= Purchase::where('company_id',Auth::user()->company_id)->sum('price');

    return view('pages_en.purchases',compact(['permissionsGroup','suppliers','total_price','total','logs','purchases','products','payment_methods']));
}

//purchase tody




//=====purrchase=====
public function purchases_tody()
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
    $suppliers=Supplier::where('company_id',Auth::user()->company_id)->selection()->get();
$purchases=Purchase::where('company_id',Auth::user()->company_id)->where('purchase_date',date('y-m-d'))->orderBy('id', 'DESC')->selection()->get();
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

$products=Product::where('company_id',Auth::user()->company_id)->selection()->get();
$total= Purchase::where('company_id',Auth::user()->company_id)->sum('price');

    return view('pages.purchases',compact(['permissionsGroup','suppliers','total_price','total','logs','purchases','products','payment_methods']));
}


   
    //store
    public function store_purchases(Purchase_Request $request)
    {
   
      

           
               
                
              try{    


        if(isset($request->supplier)){
            for ($i = 0; $i < count($request->product); $i++) {
            


            Purchase::create([
                'product_id'=>$request->product[$i],
                'exp_date'=>$request->exp_date[$i],
                'quantity'=>$request->quantity[$i],
                'supplier_id' => $request->supplier,
                'purchase_date'=>$request->purchase_date,
                'batch'=>$request->batch[$i],
                'price'=>$request->price[$i],
                'invoice_number'=>$request->invoice_number,
                'company_id' => Auth::user()->company_id,
            ]);
           
        } 

            if(auth()->user()->lang !=='en'){

                    return back()->with('success', 'تم الاضافة بنجاح');

                }else{
                    return back()->with('success', 'Added successfully');
  
                }
    }

    }
    catch (\Exception $ex) {
                    if(auth()->user()->lang !=='en'){

                    return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');

                }else{
                    return back()->with('error', 'Something went wrong');
  
                }
            }
        
    
    }
    
    //update
    public function update_purchases($id ,Purchase_Request $request)
    {

   
            try {

             
                if(!empty($request->supplier)){
                    $purchases= Purchase::find($id);
            
                    if (!$purchases)
                        if(auth()->user()->lang !=='en'){

                        return back()->with('error', 'هذا العنصر غير موجود');
            
                    }else{
                        return back()->with('error', 'This Item does not exist.');
            
                    }            
                    // update date
            
                   
                    
                              
                        Purchase::where('id', $purchases->id)
                        ->update([
                       
                        'product_id'=>$request->product,
                        'exp_date'=>$request->exp_date,
                        'quantity'=>$request->quantity,
                        'batch'=>$request->batch,
                        'purchase_date'=>$request->purchase_date,
                        'supplier_id' => $request->supplier,
                        'price'=>$request->price,
                        'invoice_number'=>$request->invoice_number,
                        'company_id' => Auth::user()->company_id,
                        ]);
            
                     
                
                    
            
                        if(auth()->user()->lang !=='en'){

                        return back()->with('success', 'تم ألتحديث بنجاح');
    
                    }else{
                        return back()->with('success', 'Updated successfully');
      
                    }
            
                    }



            } catch (\Exception $ex) {
                    if(auth()->user()->lang !=='en'){

                    return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');

                }else{
                    return back()->with('error', 'Something went wrong');
  
                }
        
               
            }
        
        }




    
    //delete
    public function delete_purchases($id)
    {
        try {
            $purchases = Purchase::find($id);
            if (!$purchases) {
                    if(auth()->user()->lang !=='en'){

                    return back()->with('error', 'هذا العنصر غير موجود');
        
                }else{
                    return back()->with('error', 'This Item does not exist.');
        
                }        
            }
            Invoice::where('purchase_id',$id)->selection()->delete();
            Log::where('purchase_id',$id)->selection()->delete();

            $purchases->delete();

               if(auth()->user()->lang !=='en'){

                return back()->with('success', 'تم الحذف بنجاح');

            }else{
                return back()->with('success', 'Deleted successfully');

            }
        
        } catch (\Exception $ex) {
                if(auth()->user()->lang !=='en'){

                return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');

            }else{
                return back()->with('error', 'Something went wrong');

            }
            
        }
    }


    //purchas invoices 
      
   
    public function invoice_purchases($invoice_no)
    {
        try {



            
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
            $suppliers=Supplier::where('company_id',Auth::user()->company_id)->selection()->get();
    $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
    $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
    $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
    
    $products=Product::where('company_id',Auth::user()->company_id)->selection()->get();
    $total= Purchase::where('company_id',Auth::user()->company_id)->sum('price');
    

      $purchase = \DB::table('purchases')->where('invoice_number',$invoice_no)->latest("id")->first();
  
     $supplier_id=$purchase->supplier_id;
     $supplier = \DB::table('suppliers')->where('id',$supplier_id)->latest("id")->first();
            $purchases = Purchase::where('invoice_number',$invoice_no)->get();
            if (!$purchases) {
                    if(auth()->user()->lang !=='en'){

                    return back()->with('error', 'هذا العنصر غير موجود');
        
                }else{
                    return back()->with('error', 'This Item does not exist.');
        
                }        
            }else{
                return view('pages.invoice_purchases',compact(['supplier','purchase','permissionsGroup','suppliers','total_price','total','logs','purchases','products','payment_methods']));

            }
           
        
        } catch (\Exception $ex) {
                if(auth()->user()->lang !=='en'){

                return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');

            }else{
                return back()->with('error', 'Something went wrong');

            }
            
        }
    }
//purchase invoices
  
   //purchas invoices en
      
   
   public function invoice_purchases_en($invoice_no)
   {
       try {



           
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
           $suppliers=Supplier::where('company_id',Auth::user()->company_id)->selection()->get();
   $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
   $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
   $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
   
   $products=Product::where('company_id',Auth::user()->company_id)->selection()->get();
   $total= Purchase::where('company_id',Auth::user()->company_id)->sum('price');
   

     $purchase = \DB::table('purchases')->where('invoice_number',$invoice_no)->latest("id")->first();
 
    $supplier_id=$purchase->supplier_id;
    $supplier = \DB::table('suppliers')->where('id',$supplier_id)->latest("id")->first();
           $purchases = Purchase::where('invoice_number',$invoice_no)->get();
           if (!$purchases) {
                   if(auth()->user()->lang !=='en'){

                   return back()->with('error', 'هذا العنصر غير موجود');
       
               }else{
                   return back()->with('error', 'This Item does not exist.');
       
               }        
           }else{
               return view('pages_en.invoice_purchases',compact(['supplier','purchase','permissionsGroup','suppliers','total_price','total','logs','purchases','products','payment_methods']));

           }
          
       
       } catch (\Exception $ex) {
               if(auth()->user()->lang !=='en'){

               return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');

           }else{
               return back()->with('error', 'Something went wrong');

           }
           
       }
   }
//purchase invoices
 
//=====end _purchases====================================================
   
}
