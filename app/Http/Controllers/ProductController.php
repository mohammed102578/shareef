<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Log;
use App\Models\Purchase;
use App\Models\Determining_price;
use App\Models\Product;
use App\Models\Invoice;
use App\Models\Permission;
use App\Models\Packing;
use App\Models\Payment_method;

use App\Models\Supplier;

use App\Http\Requests\product_Request;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;


class ProductController extends Controller
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
  
   
     

//=====medicin=====
    public function products()
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
        
$products=Product::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$packings=Packing::where('company_id',Auth::user()->company_id)->selection()->get();
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

        return view('pages.products',compact(['permissionsGroup','total_price','products','logs','packings','payment_methods']));
    }





//=====medicin_en=====
public function products_en()
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
    
$products=Product::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$packings=Packing::where('company_id',Auth::user()->company_id)->selection()->get();
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

    return view('pages_en.products',compact(['permissionsGroup','total_price','products','logs','packings','payment_methods']));
}




//expier_store_products

    public function expier_store_products()
    {
          
        if(Auth::user()->group_id==5){
            $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
        
          }else{
        
            $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
        
          }
        
$products=Product::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$packings=Packing::where('company_id',Auth::user()->company_id)->selection()->get();
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
$stores=Purchase::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

        return view('pages.expier_store_products',compact(['stores','permissionsGroup','total_price','products','logs','packings','payment_methods']));
    }


    //expier store 

//expier_store_products

public function expier_store_products_en()
{
      
    if(Auth::user()->group_id==5){
        $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
    
      }else{
    
        $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
    
      }
    
$products=Product::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$packings=Packing::where('company_id',Auth::user()->company_id)->selection()->get();
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
$stores=Purchase::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

    return view('pages_en.expier_store_products',compact(['stores','permissionsGroup','total_price','products','logs','packings','payment_methods']));
}

//expier_date_products

    public function expier_date_products()
    {
        $expier_date_tody=date('y-m-d', strtotime('+90 day', time()));


        if(Auth::user()->group_id==5){
            $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
        
          }else{
        
            $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
        
          }
        
$products=Product::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$packings=Packing::where('company_id',Auth::user()->company_id)->selection()->get();
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
$purchase_exp_date=Purchase::where('company_id',Auth::user()->company_id)->where('exp_date' , "<","$expier_date_tody")->get();

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

        return view('pages.expier_date_products',compact(['purchase_exp_date','permissionsGroup','total_price','products','logs','packings','payment_methods']));
    }
   

//expier date 


public function expier_date_products_en()
{
    $expier_date_tody=date('y-m-d', strtotime('+90 day', time()));


    if(Auth::user()->group_id==5){
        $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();
    
      }else{
    
        $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();
    
      }
    
$products=Product::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$packings=Packing::where('company_id',Auth::user()->company_id)->selection()->get();
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
$purchase_exp_date=Purchase::where('company_id',Auth::user()->company_id)->where('exp_date' , "<","$expier_date_tody")->get();

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

    return view('pages_en.expier_date_products',compact(['purchase_exp_date','permissionsGroup','total_price','products','logs','packings','payment_methods']));
}


    //store
    public function store_products(product_Request $request)
    {
       
            try {
    

               
                Product::create([
                    'product_name' => $request->product_name,
                    'packing_id' => $request->packing,
                    'barcode' => $request->barcode,
                    'company_id' => Auth::user()->company_id,
                    

                ]);
              
                        if(auth()->user()->lang !=='en'){

                    return back()->with('success', 'تم الاضافة بنجاح');

                }else{
                    return back()->with('success', 'Added successfully');
  
                }

               
            } catch (\Exception $ex) {
                    if(auth()->user()->lang !=='en'){

                    return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');

                }else{
                    return back()->with('error', 'Something went wrong');
  
                }
                
            }
        
    
    }
    
    //update
    public function update_products($id ,product_Request $request)
    {
        

            try {
                $product= Product::find($id);
        
                if (!$product)

                    if(auth()->user()->lang !=='en'){

                    return back()->with('error', 'هذا المنتج غير موجود');
        
                }else{
                    return back()->with('error', 'This product does not exist.');
        
                }        
                // update date
        
        
                Product::where('id', $id)
                    ->update([
                    'product_name' => $request->product_name,
                    'packing_id' => $request->packing,
                    'barcode' => $request->barcode,
                    
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




    
    //delete
    public function delete_products($id)
    {

    

     try {
           

        $product= Product::find($id);

        if (!$product)

            if(auth()->user()->lang !=='en'){

            return back()->with('error', 'هذا المنتج غير موجود');

        }else{
            return back()->with('error', 'This product does not exist.');

        }

        $product_id= $product->id;
        Determining_price::where('product_id',$product_id)->selection()->delete();
       Purchase::where('product_id',$product_id)->selection()->delete();

        $product->delete();
        
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



//=====end user====================================================
   

}
