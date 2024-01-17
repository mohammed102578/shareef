<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Company;
use Illuminate\Support\Facades\App;

use App\Models\Determining_price;
use App\Models\Group;
use App\Models\Invoice;
use App\Models\Log;
use App\Models\Product;
use App\Models\Month;
use App\Models\Packing;
use App\Models\Page;
use App\Models\Payment_method;
use App\Models\Permission;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
use App\Http\Requests\Edit_user_Request;
use App\Http\Requests\Add_user_Request;

use Illuminate\Support\Facades\Auth;


define('PAGINATION_COUNT',10);
class UsersMangement extends Controller
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
    public function users()
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
          
$users=User::where('company_id',Auth::user()->company_id)->where('group_id','!=',5)->
orderBy('id', 'DESC')->selection()->get();
$route=Route::currentRouteName();
$groups=Group::where('company_id',Auth::user()->company_id)->selection()->get();
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

        return view('pages.users',compact(['permissionsGroup','total_price','users','logs','route','groups','payment_methods']));
    }
   



    //users en 


//=====user=====
public function users_en()
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
      
$users=User::where('company_id',Auth::user()->company_id)->where('group_id','!=',5)->
orderBy('id', 'DESC')->selection()->get();
$route=Route::currentRouteName();
$groups=Group::where('company_id',Auth::user()->company_id)->selection()->get();
$total_price= Log::where('company_id',Auth::user()->company_id)->sum('price');

$payment_methods=Payment_method::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();
$logs=Log::where('company_id',Auth::user()->company_id)->orderBy('id', 'DESC')->selection()->get();

    return view('pages_en.users',compact(['permissionsGroup','total_price','users','logs','route','groups','payment_methods']));
}

    //store
    public function store_users(Add_user_Request $request)
    {

       
        if (!$request->has('stop'))
        $request->request->add(['stop' => 0]);
    else
        $request->request->add(['stop' => 1]);
        
      $user=  User::create([
            'name' => $request->name,
            'username' => $request->username,
            'group_id' => $request->group,
            'company_id' => Auth::user()->company_id,
            'password' => bcrypt($request->password),
            'stop'=>$request->stop,
            'expiered_date'=>Auth::user()->expiered_date

        ]);
        $user->save();
            if(auth()->user()->lang !=='en'){

                    return back()->with('success', 'تم الاضافة بنجاح');

                }else{
                    return back()->with('success', 'Added successfully');
  
                }
        
            try {
    

                
            } catch (\Exception $ex) {
                    if(auth()->user()->lang !=='en'){

                    return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');

                }else{
                    return back()->with('error', 'Something went wrong');
  
                }
            }
        
    
    }
    
    //update
    public function update_users($user_id ,Edit_user_Request $request)
    {

            try {
                $user= User::find($user_id);
        
                if (!$user)
                    if(auth()->user()->lang !=='en'){

                    return back()->with('error', 'هذا المستخدم غير موجود');
        
                }else{
                    return back()->with('error', 'This user does not exist.');
        
                }           
                // update date
        
                if (!$request->has('stop'))
                $request->request->add(['stop' => 0]);
            else
                $request->request->add(['stop' => 1]);
                
        
                User::where('id', $user->id)
                    ->update([
                        'name' => $request->name,
                        'username' => $request->username,
                        'group_id' => $request->group,
                        'stop'=>$request->stop
                    ]);
        
        
                if ($request->has('password')) {
                    User::where('id', $user_id)
                    ->update([
                        'password' => bcrypt($request->password),
                       
                    ]);
        
                }
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
    public function delete_users($id)
    {
        try {
            $user = User::find($id);
            if (!$user) {
                    if(auth()->user()->lang !=='en'){

                    return back()->with('error', 'هذا المستخدم غير موجود');
        
                }else{
                    return back()->with('error', 'This user does not exist.');
        
                }          
            }
            Invoice::where('user_id',$user->id)->selection()->delete();
            Log::where('user_id',$user->id)->selection()->delete();
            $user->delete();

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
   


public function logout_expierd(Request $request) {
    Auth::logout();
    return redirect('/')->with('error','    عذرا الرجاء ضبط الزمن والتاريخ للجهاز');
  }


public function logout_stop(Request $request) {
    Auth::logout();
    return redirect('/')->with('error','عذرا لقد تم توقيفك من قبل الادارة ');
  }

public function logout(Request $request) {
    Auth::logout();
    return redirect('/');
  }


public function logout_mac(Request $request) {
    Auth::logout();
    return redirect('/')->with('error', 'عذرا هذا النظام مخصص لجهاز واحد فقط');
  }

}
