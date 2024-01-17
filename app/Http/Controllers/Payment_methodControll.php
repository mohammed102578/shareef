<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

use App\Models\Log;
use App\Models\Payment_method;
use App\Models\Invoice;
use App\Http\Requests\Payment_method_Request;

use App\Models\Permission;
use Illuminate\Support\Facades\Auth;


define('PAGINATION_COUNT',10);
class Payment_methodControll extends Controller
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
    
   
//=====store_payment methods=====
public function payment_methods()
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
    
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

    return view('pages.Payment_method',compact(['permissionsGroup','total_price','payment_methods','logs']));
}

 
//=====store_payment methods=====
public function payment_methods_en()
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
    
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

    return view('pages_en.Payment_method',compact(['permissionsGroup','total_price','payment_methods','logs']));
}

//store
public function store_payment_methods(Payment_method_Request $request)
{
   
        try {

           
    $payment_method=Payment_method::create([
        'payment_method_name' => $request->payment_method_name,
        'company_id'=>Auth::user()->company_id,
        
        ]);
        $payment_method->save();
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
public function update_payment_methods($id ,Payment_method_Request $request)
{

    
        try {
            $payment_method= Payment_method::find($id);
    
            if (!$payment_method)

                if(auth()->user()->lang !=='en'){

                return back()->with('error', 'هذا العنصر غير موجود');
    
            }else{
                return back()->with('error', 'This Item does not exist.');
    
            }    
            // update date
    
    
             Payment_method::where('id', $payment_method->id)
                ->update([
                    'payment_method_name' =>$request->payment_method_name  
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
public function delete_payment_methods($id)
{
    $payment_method=Payment_method::find($id);
    try {
        
        if (!$payment_method)

            if(auth()->user()->lang !=='en'){

            return back()->with('error', 'هذا العنصر غير موجود');

        }else{
            return back()->with('error', 'This Item does not exist.');

        }
        Invoice::where('payment_method_id',$payment_method->id)->selection()->delete();

        
        $payment_method->delete();

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
