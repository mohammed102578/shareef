<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

use App\Models\Product;
use App\Models\User;
use App\Models\Company;
use App\Models\Permission;
use App\Models\Payment_method;
use App\Http\Requests\Companies_Request;


use Illuminate\Support\Facades\Auth;


define('PAGINATION_COUNT',10);
class CompaniesController extends Controller
{



    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
   
//=====user=====
public function companies()
{
    
    return view('auth.company');
}


//store
public function store_companies(Companies_Request $request)
{
   
   
   $expiered_date_product=date('y-m-d', strtotime('+90 day', time()));


   
$mac=exec('getmac');
$mac=strtok($mac,' ');

           
        $company=Company::create([
        'company_name' => $request->company_name,
        'address'=>$request->address,
        'vat_number'=>$request->vat_number,
        'companies_id'=>$request->companies_id,
        'app_id'=>$request->app_id,
        'mac_address'=>$mac,
        ]);
        $company->save();

        $last_row = \DB::table('companies')->latest("id")->first();

        $lastid=$last_row ->companies_id;
        // User::where('id',Auth::user()->id)->update(['company_id' => $last_row]);


            return redirect()->route('registeration',$lastid);
            try {

        } catch (\Exception $ex) {

                    return back()->with('error',  'حدث خطا ما الرجاء  المحاوله لاحقا');

        }
    

}


//=====end companies====================================================




}
