<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class expenses_controller extends Controller
{
    // Add FD
    public function submit_expense(Request $request)
    {
        try {
            $amount = $request->input('expense_amount');
            $details = $request->input('details');
            $expenses_date = $request->input('expenses_date');
            $con_pin = $request->input('con_pin');

            $admin_data = DB::select('SELECT * FROM `admin`');
            if (count($admin_data) == 0) {
                return back()->with('error', 'Admin Data Not Found.....');
            }
            foreach ($admin_data as $admin_data) {
                $pin = $admin_data->pin;
            }
            if ($con_pin != $pin) {
                return back()->with('error', 'Pin Not Match.....');
            }

            $date = new DateTime($expenses_date);
            $expenses_date = $date->format('Y-m-d');

            date_default_timezone_set('Asia/Kolkata');
            $today = date("Y-m-d");
            $time = Date("H:i:s");

            $expense_data = array(
                'amount' => $amount, 'details' => $details, 'expenses_date' => $expenses_date, 'trans_date' => $today, 'trans_time' => $time
            );
            DB::table('expenses')->insertGetId($expense_data);

            return back()->with('message', 'Expense Data Added Sucessfully.....');

        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    // Show Expeses
    public function get_expenses(Request $request)
    {
        try {
            $expenses_data = DB::select('SELECT * FROM `expenses`');
            return view('admin.pages.expense.show_expenses', array('sr' => 0, 'expenses_data' => $expenses_data));
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    // Remove Expeses
    public function remove_expense(Request $request, $id)
    {
        try {
            DB::delete('DELETE FROM `expenses` WHERE id = ?', [$id]);
            return back()->with('message', 'Expense Removed Sucessfully.....');
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }
}
