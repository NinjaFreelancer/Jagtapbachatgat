<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class customer_login_controller extends Controller
{
    // Function For Login
    public function customer_login(Request $request)
    {
        $accont_no = $request->input('acc_no');
        $password = $request->input('password');

        try {
            $logdata = DB::select("SELECT * FROM `customers` WHERE `acc_no`= ?", [$accont_no]);
            if (count($logdata) == 0) {
                return back()->with('error', 'customers Data Not Found..');
            } else {
                foreach ($logdata as $data) {
                    if($data->is_active == 0){
                        return back()->with('error', 'Accont Is Closed.....');
                    }
                    if($data->status == 1){
                        return back()->with('error', 'Your Account Is Blocked, Please Contact To Admin.....');
                    }
                    if (($accont_no != $data->acc_no) || ($password != $data->pass)) {
                        return back()->with('error', 'Accont Number $ Password Is Wrong.....');
                    }
                    $request->session()->put('customer_id', $data->id);
                }

                return redirect('customers_dashboard')->with($request->session()->get('customer_id'));
            }
        } catch (Exception $e) {
            dd($e->getMessage());
            dd('Something Went Wrong');
        }
    }

    // fUNCTION fOR Logout Admin
    function customer_logout(Request $request)
    {
        try {
            $request->session()->forget('customer_id');

            $request->session()->flush();
            return redirect('/customer');
        } catch (Exception $e) {
            dd($e->getMessage());
            dd('Something Went Wrong');
        }
    }
}
