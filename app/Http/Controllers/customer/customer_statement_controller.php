<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class customer_statement_controller extends Controller
{
    // Function For Statement data
    public function get_customer_bachat_statement(Request $request)
    {
        $customer_id = $request->session()->get('customer_id');
        $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$customer_id]);
        $statement_data = DB::select('SELECT * FROM `statement` WHERE `customer_id`=?', [$customer_id]);
        if ((count($customer_data) == 0 || ($statement_data) == 0)) {
            return back()->with('error', 'Data Not Found.....');
        } else {
            return view('customer.pages.statement', array('customer_data' => $customer_data, 'sr' => 0, 'statement_data' => $statement_data, 'balance' => 0));
        }
    }

    // Function For Monthly Statement data
    public function get_customer_monthly_statement_data(Request $request)
    {
        $customer_id = $request->session()->get('customer_id');
        $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$customer_id]);
        $monthly_statement_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `customer_id`=?', [$customer_id]);
        if ((count($customer_data) == 0 || ($monthly_statement_data) == 0)) {
            return back()->with('error', 'Data Not Found.....');
        } else {
            return view('customer.pages.monthly_bachat_statement', array('customer_data' => $customer_data, 'sr' => 0, 'monthly_statement_data' => $monthly_statement_data));
        }
    }
}
