<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class fixed_deposit_controller extends Controller
{
    //
    public function submit_fixed_deposit(Request $request)
    {
        try {
            $full_name = $request->input('full_name');
            $mobile_no = $request->input('mobile');
            $fixed_deposit_amount = $request->input('fixed_deposit_amount');
            $date_of_deposit = $request->input('date_of_deposit');
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

            $date = new DateTime($date_of_deposit);
            $date_of_deposit = $date->format('Y-m-d');

            date_default_timezone_set('Asia/Kolkata');
            $today = date("Y-m-d");
            $time = Date("H:i:s");

            $fd_data = array(
                'customer_name' => $full_name,
                'mobile_no' => $mobile_no,
                'fd_amount' => $fixed_deposit_amount,
                'date_of_deposit' => $date_of_deposit,
                'trans_date' => $today,
                'trans_time' => $time
            );
            DB::table('fixed_deposit')->insertGetId($fd_data);

            return back()->with('message', 'Fixed Deposit Amount Added Sucessfully.....');
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    // Active FD Statement
    public function get_active_fd_statement(Request $request)
    {
        try {
            $active_fd_statement = DB::select('SELECT * FROM `fixed_deposit` WHERE is_fd_amount_disbursed=?', [0]);
            return view('admin.pages.fixed deposit.active_fixed_deposit', array('sr' => 0, 'active_fixed_deposit' => $active_fd_statement));
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    // Remove FD Entry
    public function show_fd_details(Request $request, $id)
    {
        try {
            $fixed_deposit_data = DB::select('SELECT * FROM `fixed_deposit` WHERE id = ?', [$id]);
            return view('admin.pages.fixed deposit.show_fd_details', array('fixed_deposit_data' => $fixed_deposit_data));
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    public function calculate_days($start_date, $end_date)
    {
        $date1_ts = strtotime($start_date);
        $date2_ts = strtotime($end_date);
        $diff = $date2_ts - $date1_ts;
        return round($diff / 86400);
    }

    // calculate fd interest
    public function calculate_fd_interest(Request $request, $id)
    {
        try {

            $InterestFor = $request->input('interest_for');
            $interest_rate = $request->input('interest_rate');
            $calculate_up_to = $request->input('calculate_up_to');
            // print_r($InterestFor);

            // print_r($interest_rate);

            // print_r($calculate_up_to);
            // die();
            $fixed_deposit_data = DB::select('SELECT * FROM `fixed_deposit` WHERE id = ?', [$id]);
            foreach ($fixed_deposit_data as $data) {
                $FD_amount = $data->FD_amount;
                $date_of_deposit = $data->date_of_deposit;
            }
            if ($InterestFor == 1) {

                $date = new DateTime($date_of_deposit);
                $date_of_deposit = $date->format('Y-m-d');

                $today = date("Y-m-d");

                $date1 = DateTime::createFromFormat('Y-m-d', $date_of_deposit);
                $date2 = DateTime::createFromFormat('Y-m-d', $today);

                $completed_years = $date1->diff($date2)->y;


                if ($completed_years == 0) {
                    return back()->with('error', 'Year is not completed yet');
                }

                $end_date = new DateTime($date_of_deposit);
                $end_date->modify('+' . $completed_years . ' year');
                $calculate_up_to = $end_date->format('Y-m-d');

                $total_interest = ((($FD_amount / 100) *  $interest_rate) * $completed_years);
                DB::update(
                    'UPDATE `fixed_deposit` SET `interest_rate`=?,`completed_months`=?,`extra_days`=?, `is_interest_calculated`=?,`interest_calculated_up_to`=?,`interest`=?, `completed_years`=?, `is_interest_calculate_by_yearly` = ? WHERE id = ?',
                    [$interest_rate, 0, 0, 1, $calculate_up_to, $total_interest, $completed_years, 1, $id]
                );
            } else {
                $date = new DateTime($calculate_up_to);
                $calculate_up_to = $date->format('Y-m-d');

                $date1 = DateTime::createFromFormat('Y-m-d', $date_of_deposit);
                $date2 = DateTime::createFromFormat('Y-m-d', $calculate_up_to);

                $completed_years = $date1->diff($date2)->y;
                $completed_months = ($completed_years * 12) + $date1->diff($date2)->m;
                $extra_days = $date1->diff($date2)->d;

                $interest_of_one_months = (($FD_amount / 100) * ($interest_rate / 12));
                $interest_of_completed_months = $interest_of_one_months * $completed_months;
                $interest_of_extra_days = ($interest_of_one_months / 30) * $extra_days;

                $total_interest = $interest_of_completed_months + $interest_of_extra_days;
                DB::update(
                    'UPDATE `fixed_deposit` SET `interest_rate`=?,`completed_months`=?,`extra_days`=?,`is_interest_calculated`=?,`interest_calculated_up_to`=?,`interest`=?, `completed_years`=?, `is_interest_calculate_by_yearly` = ? WHERE id = ?',
                    [$interest_rate, $completed_months, $extra_days, 1, $calculate_up_to, $total_interest, 0, 0, $id]
                );
            }

            return back()->with('message', 'Interest Calculayed Sucessfully.....');
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    // Remove FD Entry
    public function disburse_fd_amount(Request $request, $id)
    {
        try {
            $disbursal_Date = $request->input('disbursal_Date');
            $pin = $request->input('con_pin');

            $admin_data = DB::select('SELECT * FROM `admin` Where pin=?', [$pin]);

            if (count($admin_data) == 0) {
                return back()->with('error', 'Pin Not Match.....');
            }
            $fixed_deposit_data = DB::select('SELECT * FROM `fixed_deposit` WHERE id = ?', [$id]);
            foreach ($fixed_deposit_data as $data) {
                $FD_amount = $data->FD_amount;
                $interest = $data->interest;
            }

            DB::update('UPDATE `fixed_deposit` SET `is_fd_amount_disbursed`=? WHERE id = ?', [1, $id]);

            date_default_timezone_set('Asia/Kolkata');
            $today = date("Y-m-d");
            $time = Date("H:i:s");

            $date = new DateTime($disbursal_Date);
            $disbursal_Date = $date->format('Y-m-d');

            $fd_data = array(
                'fd_id' => $id,
                'amount' => ($FD_amount + $interest),
                'disbursed_date' => $disbursal_Date,
                'trans_date' => $today,
                'trans_time' => $time
            );
            DB::table('fd_amount_disbursed_transaction')->insertGetId($fd_data);

            return back()->with('message', 'Fixed Deposite Amount Disbused Sucessfully.....');
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    // History Of FD Statement
    public function get_history_of_fd_statement(Request $request)
    {
        try {
            $history_of_fixed_deposit = DB::select('SELECT * FROM `fixed_deposit` WHERE is_fd_amount_disbursed=?', [1]);
            return view('admin.pages.fixed deposit.history_of_fixed_deposit', array('sr' => 0, 'history_of_fixed_deposit' => $history_of_fixed_deposit));
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    // Remove FD Entry
    public function remove_fixed_deposit(Request $request, $id)
    {
        try {
            DB::delete('DELETE FROM `fixed_deposit` WHERE id = ?', [$id]);
            return back()->with('message', 'Fixed Deposit Entry Removed Sucessfully.....');
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }
}
