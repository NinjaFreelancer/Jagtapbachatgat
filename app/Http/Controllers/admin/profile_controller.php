<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class profile_controller extends Controller
{
    // Function For Display Customer data
    public function get_customers_data($id)
    {
        $today = Date('Y-m-d');
        $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$id]);

        if (count($customer_data) == 0) {
            return back()->with('error', 'Customer Data Not Found.....');
        }
        foreach($customer_data as $cust_data){
            $is_active = $cust_data->is_active;
        }
        if($is_active == 0){
            return view('admin.pages.customer.closed_account_profile', array('customer_data' => $customer_data));

        }

        $curr_month_bachat_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `customer_id`=? AND `month_start_date`<=? AND `month_end_date`>=?', [$id, $today, $today]);
        $prev_month_bachat_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `customer_id`=? AND `is_received`=? AND `is_expire`=?', [$id, 0, 1]);
        //status=0 loan is active
        $loan_data = DB::select('SELECT * FROM `loan` WHERE `customer_id`=? AND `status`=?', [$id, 0]);

        $prev_loan_data = DB::select('SELECT * FROM `loan` WHERE `customer_id`=? AND `status`=?', [$id, 1]);

        return view('admin.pages.customer.profile', array('customer_data' => $customer_data, 'current_month_bachat_data' => $curr_month_bachat_data, 'previous_month_bachat_data' => $prev_month_bachat_data, 'current_loan_data' => $loan_data, 'previous_loan_data' => $prev_loan_data));
    }

    // Function For Display Customer data
    public function get_profile_data(Request $request)
    {
        $acc_no = $request->input('acc_no');
        $acc_no = strtoupper($acc_no);
        // echo $acc_no;
        // die;
        $customer_data = DB::select('SELECT * FROM customers WHERE `acc_no`=?', [$acc_no]);
        if (count($customer_data) == 0) {
            return back()->with('error', 'Please Provide Valid Account Number.....');
        }
        foreach($customer_data as $cust_data){
            $cust_id = $cust_data->id;
        }
        $today = Date('Y-m-d');
        $curr_month_bachat_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `customer_id`=? AND `month_start_date`<=? AND `month_end_date`>=?', [$cust_id, $today, $today]);
        $prev_month_bachat_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `customer_id`=? AND `is_received`=? AND `is_expire`=?', [$cust_id, 0, 1]);
        //status=0 loan is active
        $loan_data = DB::select('SELECT * FROM `loan` WHERE `customer_id`=? AND `status`=?', [$cust_id, 0]);

        $prev_loan_data = DB::select('SELECT * FROM `loan` WHERE `customer_id`=? AND `status`=?', [$cust_id, 1]);

            return view(
                'admin.pages.customer.profile',
                array('customer_data' => $customer_data, 'current_month_bachat_data' => $curr_month_bachat_data, 'previous_month_bachat_data' => $prev_month_bachat_data, 'current_loan_data' => $loan_data, 'previous_loan_data' => $prev_loan_data)
            );

    }
}
