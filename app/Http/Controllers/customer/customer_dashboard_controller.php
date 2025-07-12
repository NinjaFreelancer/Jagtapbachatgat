<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class customer_dashboard_controller extends Controller
{
    // Function For Show Dashboard
    public function customer_dashboard(Request $request)
    {
        try {
            $customer_id = $request->session()->get('customer_id');

            $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$customer_id]);

            $today = Date('Y-m-d');
            $curr_month_bachat_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `customer_id`=? AND `month_start_date`<=? AND `month_end_date`>=?', [$customer_id, $today, $today]);
            // $curr_month_bachat_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `customer_id`=? AND `is_expire`=?', [$customer_id, 0]);
            $prev_month_bachat_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `customer_id`=? AND `is_received`=? AND `is_expire`=?', [$customer_id, 0, 1]);
            //status=0 loan is active
            $loan_data = DB::select('SELECT * FROM `loan` WHERE `customer_id`=? AND `status`=?', [$customer_id, 0]);

            $prev_loan_data = DB::select('SELECT * FROM `loan` WHERE `customer_id`=? AND `status`=?', [$customer_id, 1]);

            if ((count($customer_data) == 0)) {
                return back()->with('error', 'Something Went Wrong.....');
            } else {
                return view('customer.dashboard', array('customer_data' => $customer_data, 'current_month_bachat_data' => $curr_month_bachat_data, 'previous_month_bachat_data' => $prev_month_bachat_data, 'current_loan_data' => $loan_data, 'previous_loan_data' => $prev_loan_data));
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}