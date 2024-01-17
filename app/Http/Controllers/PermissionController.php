<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Log;
use Illuminate\Support\Facades\App;

use App\Models\Page;
use App\Models\Permission;
use App\Models\Payment_method;
use App\Http\Requests\Supplier_Request;
use App\Models\Group;

use Illuminate\Support\Facades\Auth;


define('PAGINATION_COUNT',10);
class PermissionController extends Controller
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


public function permissions($id)
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
       
      
   
$permissions=Permission::where('company_id',Auth::user()->company_id)->where('group_id',"$id")->selection()->get();

$group=Group::where('id',"$id")->where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();


if(Auth::user()->group_id==5){
    $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();

  }else{

    $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();

  }


$pages=Page::orderBy('id','ASC')->selection()->get();

$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

    return view('pages.permissions',compact(['permissionsGroup','permissionsGroup','permissions','group','total_price','pages','logs','payment_methods']));
}

public function permissions_en($id)
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
       
      
   
$permissions=Permission::where('company_id',Auth::user()->company_id)->where('group_id',"$id")->selection()->get();

$group=Group::where('id',"$id")->where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();


if(Auth::user()->group_id==5){
    $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();

  }else{

    $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();

  }


$pages=Page::orderBy('id','ASC')->selection()->get();

$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

    return view('pages_en.permissions',compact(['permissionsGroup','permissionsGroup','permissions','group','total_price','pages','logs','payment_methods']));
}

//store
public function store_permissions(Request $request)
{
 
   

    
    
        try {
            if(isset($request->page_id)){

                $page_count=count($request->page_id);
                Permission::where('group_id',$request->group_id)->delete();
                
                
                
                
                
                    for ($i = 0; $i <= $page_count-1; $i++) {
                
                        
                         
                        Permission::create([
                            'group_id'=>$request->group_id,
                            'page_id'=>$request->page_id[$i],
                            'company_id'=>Auth::user()->company_id,
                            
                            ]);
                
                            
                     }
                
                   
                         if(auth()->user()->lang !=='en'){

                    return back()->with('success', 'تم الاضافة بنجاح');

                }else{
                    return back()->with('success', 'Added successfully');
  
                }
                
                   
                }else{


                        if(auth()->user()->lang !=='en'){

                        return back()->with('error', ' لم تقم باضافة اي صلاحية ');
            
                    }else{
                        return back()->with('error', 'You have not added any permissions');
            
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


//=====end user====================================================

}
