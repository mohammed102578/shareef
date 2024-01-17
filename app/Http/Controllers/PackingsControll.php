<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Log;
use App\Models\Packing;
use App\Models\Permission;
use App\Models\Payment_method;
use App\Http\Requests\packing_Request;


use Illuminate\Support\Facades\Auth;


define('PAGINATION_COUNT',10);
class PackingsControll extends Controller
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
    
   
//=====user=====
public function packings()
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

$packings=Packing::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

    return view('pages.packings',compact(['permissionsGroup','total_price','packings','logs','payment_methods']));
}



//=====user=====
public function packings_en()
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

$packings=Packing::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

    return view('pages_en.packings',compact(['permissionsGroup','total_price','packings','logs','payment_methods']));
}


//store
public function store_packings(packing_Request $request)
{
   
        try {

           
    $packing=Packing::create([
        'packing_name' => $request->name,
        'company_id'=>Auth::user()->company_id,
        ]);
        $packing->save();
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
public function update_packings($packing_id ,packing_Request $request)
{

        try {
            $packing= Packing::find($packing_id);
    
            if (!$packing)

                if(auth()->user()->lang !=='en'){

                return back()->with('error', 'هذا العنصر غير موجود');
    
            }else{
                return back()->with('error', 'This Item does not exist.');
    
            }    
            // update date
    
    
            Packing::where('id', $packing_id)
                ->update([
                    'packing_name' => $request->name,
                    'company_id'=>Auth::user()->company_id,
                    
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
public function delete_packings($id)
{
    $packing=Packing::find($id);
    try {
        
        if (!$packing)

            if(auth()->user()->lang !=='en'){

            return back()->with('error', 'هذا العنصر غير موجود');

        }else{
            return back()->with('error', 'This Item does not exist.');

        }
    
        Product::where('packing_id',$packing->id)->selection()->delete();
        $packing->delete();

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
