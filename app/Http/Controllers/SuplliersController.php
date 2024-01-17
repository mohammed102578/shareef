<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Log;
use App\Models\Supplier;
use App\Models\Payment_method;
use App\Models\Purchase;
use App\Http\Requests\Supplier_Request;
use App\Models\Permission;
use Illuminate\Support\Facades\App;

use Illuminate\Support\Facades\Auth;


define('PAGINATION_COUNT',10);
class SuplliersController extends Controller
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
    
    //=====SUPPLIER=====
public function Suppliers()
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
    
$suppliers=Supplier::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

    return view('pages.suppliers',compact(['permissionsGroup','total_price','suppliers','logs','payment_methods']));
}



 //=====SUPPLIER=====
 public function Suppliers_en()
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
     
 $suppliers=Supplier::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
 $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
 
 $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
 $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
 
     return view('pages_en.suppliers',compact(['permissionsGroup','total_price','suppliers','logs','payment_methods']));
 }


//store
public function store_suppliers(Supplier_Request $request)
{
 
        try {

            $Supplier=Supplier::create([
        
                'supplier_name'=>$request->supplier_name,
                'email'=>$request->email,
                'address'=>$request->address,
                'supplier_id' => $request->supplier,
                'contact_number'=>$request->contact_number,
                'company_id'=>Auth::user()->company_id,
                
                ]);
                $Supplier->save();
            
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
public function update_Suppliers($supplier_id ,Supplier_Request $request)
{

        try {
            $supplier= Supplier::find($supplier_id);
    
            if (!$supplier)
                if(auth()->user()->lang !=='en'){

                return back()->with('error', 'هذا العنصر غير موجود');
    
            }else{
                return back()->with('error', 'This Item does not exist.');
    
            }       
            // update date
    
    
            Supplier::where('id', $supplier_id)
                ->update([
        'supplier_name'=>$request->supplier_name,
        'email'=>$request->email,
        'contact_number'=>$request->contact_number,
        'address'=>$request->address,
                    
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
public function delete_suppliers($id)
{
    try {
        $supplier=Supplier::find($id);
        if (!$supplier){
                if(auth()->user()->lang !=='en'){

                return back()->with('error', 'هذا العنصر غير موجود');
    
            }else{
                return back()->with('error', 'This Item does not exist.');
    
            }   

        }
     
    Purchase::where('supplier_id',$supplier->id)->select()->delete();
        
        $supplier->delete();

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
