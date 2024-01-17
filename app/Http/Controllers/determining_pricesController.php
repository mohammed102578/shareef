<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Log;
use App\Models\Determining_price;
use App\Models\Product;
use App\Models\Payment_method;
use App\Http\Requests\Determining_price_Request;
use App\Models\Permission;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;


define('PAGINATION_COUNT',10);
class determining_pricesController extends Controller
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
    






//=====determining_prices=====
public function determining_prices()
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
$determining_prices=Determining_price::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();


 $products=Product::where('company_id',Auth::user()->company_id)->selection()->get();
        


return view('pages.determining_prices',compact(['permissionsGroup','total_price','determining_prices','logs','products','payment_methods']));
    }
   






//=====determining_prices=====
public function determining_prices_en()
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
$determining_prices=Determining_price::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();


 $products=Product::where('company_id',Auth::user()->company_id)->selection()->get();
        


return view('pages_en.determining_prices',compact(['permissionsGroup','total_price','determining_prices','logs','products','payment_methods']));
    }
   //deter mine price






    //store
    public function store_determining_prices(Determining_price_Request $request)
    {
       
       
            try {
    

                
                Determining_price::create([
                    'product_id' => $request->product,
                    'price' => $request->price,
                    'company_id'=>Auth::user()->company_id

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
    public function update_determining_prices($id ,Determining_price_Request $request)
    {

            try {
                $Determining_price= Determining_price::find($id);
        
                if (!$Determining_price)
                    if(auth()->user()->lang !=='en'){

                    return back()->with('error', 'هذا المنتج غير موجود');
    
                }else{
                    return back()->with('error', 'This product does not exist');
    
                }        
                // update date
        
                Determining_price::where('id', $Determining_price->id)
                    ->update([
                        'product_id' => $request->product,
                        'price' => $request->price,
                       
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
    public function delete_determining_prices($id)
    {
        try {
            $Determining_price = Determining_price::find($id);
            if (!$Determining_price) {
        

                    if(auth()->user()->lang !=='en'){

                    return back()->with('error', 'هذا المنتج غير موجود');
    
                }else{
                    return back()->with('error', 'This product does not exist');
    
                }

            }
            $Determining_price->delete();

        
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



}
