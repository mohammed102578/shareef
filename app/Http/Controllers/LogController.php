<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use Illuminate\Support\Facades\App;

use App\Models\Log;
use App\Models\Payment_method;
use App\Models\Permission;
use App\Http\Requests\Edit_user_Request;
use App\Http\Requests\Add_user_Request;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;


class LogController extends Controller
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
    
   



//=====log=====
    public function logs()
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
        
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
        return view('pages.cashers',compact(['permissionsGroup','total_price','logs','payment_methods']));
    }
   
    //store
    public function store_logs(Request $request)
    {

         try {

            
if($request->quantity > $request->store_quantity)
{

        if(auth()->user()->lang !=='en'){

        return back()->with('error', 'هذه الكمية غير متوفرة في المخزن');

    }else{
        return back()->with('error', 'This quantity is not available in stock.');

    }



}
else{
    $purchases=Purchase::where('id',"$request->purchase_id")->selection()->get();
        
   // $products=Product::where('id',"$request->purchase_id")->selection()->get();
        //return $request->all();
      $log=  Log::create([
            'purchase_id' => $request->purchase_id,
            'quantity' => $request->quantity,
            'price' => $request->price * $request->quantity,
            'user_id' => Auth::user()->id,
            'company_id' => Auth::user()->company_id, 

        ]);
        $log->save();

       
            if(auth()->user()->lang !=='en'){

                    return back()->with('success', 'تم الاضافة بنجاح');

                }else{
                    return back()->with('success', 'Added successfully');
  
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
    public function delete_logs($id)
    {
        try {
            $log = Log::find($id);
            if (!$log) {
                    if(auth()->user()->lang !=='en'){

                    return back()->with('error', 'هذا السجل غير موجود');

                }else{
                    return back()->with('error', 'This record Dose not exist  ');
  
                }
            }
            $log->delete();

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
    public function delete_all_logs()
    {
        try {
           Log::select()->delete();
          
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
    

//=====end log====================================================
   

}
