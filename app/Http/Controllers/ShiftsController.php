<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Log;
use App\Models\Shift;
use App\Models\Payment_method;
use App\Models\Purchase;
use App\Http\Requests\Supplier_Request;
use App\Models\Permission;
use Illuminate\Support\Facades\App;

use Illuminate\Support\Facades\Auth;


define('PAGINATION_COUNT',10);
class ShiftsController extends Controller
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
public function shifts()
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
    
$shifts=Shift::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

    return view('pages.shifts',compact(['permissionsGroup','total_price','shifts','logs','payment_methods']));
}



 //=====SUPPLIER=====
 public function shifts_en()
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
     
 $shifts=Shift::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
 $total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');
 
 $payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
 $logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
 
     return view('pages_en.shifts',compact(['permissionsGroup','total_price','shifts','logs','payment_methods']));
 }


//store
public function store_shifts(Request $request)
{
    try {
     

      //  return $request->all();


//handel start time 
      $start= date("g:i A", strtotime($request->start_time));
      if(\str_contains($start,"PM")){
     $start_time=date("H:i ", strtotime($request->start_time));
      }else{
        $start_time=date("h:i ", strtotime($request->start_time));
      

        if(\str_contains($start,"12")){
          $start_time=date("H:i ", strtotime($request->start_time));
          
                  }
          

      }


      //handel end time
      $end= date("g:i A", strtotime($request->end_time));
      if(\str_contains($end,"PM")){



     $end_time=date("H:i ", strtotime($request->end_time));
      }else{

        $end_time=date("h:i ", strtotime($request->end_time));


        if(\str_contains($end,"12")){
  $end_time=date("H:i ", strtotime($request->end_time));

        }

      
      }

     

            $Shifts=Shift::create([
        
                'shift_name'=>$request->shift_name,
                'start_time'=>$start_time,
                'end_time'=>$end_time,
                'company_id'=>Auth::user()->company_id,
                
                ]);
                $Shifts->save();
            
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
public function update_shifts($shift_id ,Request $request)
{

        try{
            $shift= Shift::find($shift_id );
    
            if (!$shift)
                if(auth()->user()->lang !=='en'){

                return back()->with('error', 'هذا العنصر غير موجود');
    
            }else{
                return back()->with('error', 'This Item does not exist.');
    
            }       
            // update date
            
//handel start time 
      $start= date("g:i A", strtotime($request->start_time));
      if(\str_contains($start,"PM")){
     $start_time=date("H:i ", strtotime($request->start_time));
      }else{
        $start_time=date("h:i ", strtotime($request->start_time));
      

        if(\str_contains($start,"12")){
          $start_time=date("H:i ", strtotime($request->start_time));
          
                  }
          

      }


      //handel end time
      $end= date("g:i A", strtotime($request->end_time));
      if(\str_contains($end,"PM")){



     $end_time=date("H:i ", strtotime($request->end_time));
      }else{

        $end_time=date("h:i ", strtotime($request->end_time));


        if(\str_contains($end,"12")){
  $end_time=date("H:i ", strtotime($request->end_time));

        }

      
      }
             
    
            Shift::where('id', $shift_id )
                ->update([
        
                    'shift_name'=>$request->shift_name,
                    'start_time'=>$start_time,
                    'end_time'=>$end_time,
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
public function delete_shifts($id)
{
    try {
        $shift=Shift::find($id);
        if (!$shift){
                if(auth()->user()->lang !=='en'){

                return back()->with('error', 'هذا العنصر غير موجود');
    
            }else{
                return back()->with('error', 'This Item does not exist.');
    
            }   

        }
     
        
        $shift->delete();

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
