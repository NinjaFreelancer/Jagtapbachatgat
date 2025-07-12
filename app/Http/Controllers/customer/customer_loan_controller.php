<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class customer_loan_controller extends Controller
{
    // Function For Loan Statement data
    public function get_customer_Loan_statement(Request $request)
    {
        $customer_id = $request->session()->get('customer_id');

        $loan_data = DB::select('SELECT * FROM loan WHERE `customer_id`=? AND `status`=?', [$customer_id,0]);
        if (count($loan_data) != 0) {
            foreach ($loan_data as $loan_data) {
                $pending_loan = $loan_data->amount;
                $loan_id = $loan_data->id;
            }

            $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$customer_id]);
            $loan_statement_data = DB::select('SELECT * FROM `loan_statement` WHERE `loan_id`=? AND `customer_id`=?', [$loan_id, $customer_id]);

            if (count($customer_data) != 0) {
                return view('customer.pages.loan_statement', array('customer_data' => $customer_data, 'sr' => 0, 'current_loan_data' => $loan_data, 'loan_statement_data' => $loan_statement_data, 'pending' => $pending_loan));
            } else {
                return back()->with('error', 'Your Data Not Found.....');
            }
        } else {
            return back()->with('error', 'Loan Data Not Found.....');
        }
    }

    // Function For Monthly Loan Statement data
    public function get_customer_monthly_loan_statement(Request $request)
    {
        $customer_id = $request->session()->get('customer_id');

        $loan_data = DB::select('SELECT * FROM loan WHERE `customer_id`=? AND `status`=?', [$customer_id,0]);
        if (count($loan_data) != 0) {
            foreach ($loan_data as $all_loan) {
                $status = $all_loan->status;
                $loan_id = $all_loan->id;
            }

            $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$customer_id]);
            $monthly_loan_statement_data = DB::select('SELECT * FROM `loan_monthly_status` WHERE `loan_id`=? AND `customer_id`=?', [$loan_id, $customer_id]);

            if (count($customer_data) == 0) {
                return back()->with('error', 'Customer Data Not Found.....');
            } else {
                return view('customer.pages.monthly_loan_statement', array('status' => $status, 'customer_data' => $customer_data, 'sr' => 0, 'current_loan_data' => $loan_data, 'monthly_loan_statement' => $monthly_loan_statement_data));
            }
        } else {
            return back()->with('error', 'Loan Data Not Found.....');
        }
    }

    public function get_customer_prev_Loan_statement(Request $request, $id)
    {
        $customer_id = $request->session()->get('customer_id');

        $loan_data = DB::select('SELECT * FROM loan WHERE `customer_id`=? AND `id`=?', [$customer_id, $id]);
        if (count($loan_data) != 0) {
            foreach ($loan_data as $loan_data) {
                $pending_loan = $loan_data->amount;
                $interest = $loan_data->interest;
                $loan_id = $loan_data->id;
            }

            $pending_loan = $pending_loan + $interest;
            $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$customer_id]);
            $loan_statement_data = DB::select('SELECT * FROM `loan_statement` WHERE `loan_id`=? AND `customer_id`=?', [$loan_id, $customer_id]);

            if (count($customer_data) != 0) {
                return view('customer.pages.prev_loan_statement', array('customer_data' => $customer_data, 'sr' => 0, 'current_loan_data' => $loan_data, 'loan_statement_data' => $loan_statement_data, 'pending' => $pending_loan));
            } else {
                return back()->with('error', 'Your Data Not Found.....');
            }
        } else {
            return back()->with('error', 'Loan Data Not Found.....');
        }
    }

    // Function For Monthly Loan Statement data
    public function get_customer_prev_monthly_loan_statement(Request $request,$id)
    {
        $customer_id = $request->session()->get('customer_id');

        $loan_data = DB::select('SELECT * FROM loan WHERE `customer_id`=? AND `id`=?', [$customer_id,$id]);
        if (count($loan_data) != 0) {
            foreach ($loan_data as $all_loan) {
                $loan_id = $all_loan->id;
            }

            $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$customer_id]);
            $monthly_loan_statement_data = DB::select('SELECT * FROM `loan_monthly_status` WHERE `loan_id`=? AND `customer_id`=?', [$loan_id, $customer_id]);

            if (count($customer_data) == 0) {
                return back()->with('error', 'Customer Data Not Found.....');
            } else {
                return view('customer.pages.prev_monthly_loan_statement', array('customer_data' => $customer_data, 'sr' => 0, 'current_loan_data' => $loan_data, 'monthly_loan_statement' => $monthly_loan_statement_data));
            }
        } else {
            return back()->with('error', 'Loan Data Not Found.....');
        }
    }
}
