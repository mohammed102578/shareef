<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

use App\Models\Group;

use App\Models\Log;
use App\Models\Permission;
use App\Models\Payment_method;

use App\Models\Page;
use App\Models\User;
use App\Http\Requests\Edit_user_Request;
use App\Http\Requests\Add_user_Request;

use Illuminate\Support\Facades\Auth;


define('PAGINATION_COUNT',10);
class GroupController extends Controller
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
    public function groups()
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
       
       


$groups=Group::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
  
if(Auth::user()->group_id==5){
    $permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();

  }else{

    $permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();

  }

$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

        return view('pages.groups',compact(['permissionsGroup','total_price','groups','logs','groups','payment_methods']));
    }
   

    //group




//=====user=====
public function groups_en()
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
       
   


$groups=Group::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

if(Auth::user()->group_id==5){
$permissionsGroup=Permission::where('group_id',Auth::user()->group_id)->selection()->get();

}else{

$permissionsGroup=Permission::where('company_id',Auth::user()->company_id)->where('group_id',Auth::user()->group_id)->selection()->get();

}

$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

    return view('pages_en.groups',compact(['permissionsGroup','total_price','groups','logs','groups','payment_methods']));
}

    //store
    public function store_groups(Request $request)
    {

        
     
        
            try {
    
  
        
                $group=  Group::create([
                    'group_name' => $request->name,
                    'company_id' => Auth::user()->company_id,
                    
                ]);
                $group->save();
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
    public function update_groups($id ,Request $request)
    {

            try {
                $group= Group::find($id);
        
                if (!$group)
                    if(auth()->user()->lang !=='en'){

                    return back()->with('error', 'هذا المجموعة غير موجود');
    
                }else{
                    return back()->with('error', 'This Group does not exist');
    
                }            
                // update date
        
                
                Group::where('id', $group->id)
                    ->update([
                        'group_name' => $request->name,
                        
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
    public function delete_groups($id)
    {
        try {
            $group = group::find($id);
            if (!$group) {
                    if(auth()->user()->lang !=='en'){

                    return back()->with('error', 'هذا المجموعة غير موجود');
    
                }else{
                    return back()->with('error', 'This Group does not exist');
    
                }        
            }

            User::where('group_id',$group->id)->select()->delete();
            Permission::where('group_id',$group->id)->select()->delete();
            $group->delete();

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



//=====end group====================================================
   


public function logout(Request $request) {
    Auth::logout();
    return redirect('/login');
  }

}
