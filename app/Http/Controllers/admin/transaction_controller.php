<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Mail;
use SebastianBergmann\Environment\Console;

class transaction_controller extends Controller
{
    public function submit_cutomers_collection(Request $request)
    {
        try {
            $cuss_id = $request->input('customer_id');
            $collection_of = $request->input('collection');
            $amount = $request->input('amount');
            $details = $request->input('details');
            $con_pin = $request->input('con_pin');
            $status = "cr";

            $admin_data = DB::select('SELECT * FROM `admin` WHERE pin = ?', [$con_pin]);
            if (count($admin_data) == 0) {
                return back()->with('error', 'Pin mot matched.....');
            }

            $cuss_data = DB::select('SELECT * FROM `customers` WHERE id=? AND is_active = ?', [$cuss_id, 1]);
            if (count($cuss_data) == 0) {
                return back()->with('error', 'Provide valid customer ID.....');
            }

            foreach ($cuss_data as $data) {
                $acc_no = $data->acc_no;
                $email = $data->email;
            }

            if ($collection_of == 1) {
                date_default_timezone_set('Asia/Kolkata');
                $today = date("Y-m-d");
                $month_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `customer_id` = ? AND `month_start_date` <= ?
                AND `month_end_date` >= ?', [$cuss_id, $today, $today]);
                if (count($month_data) == 0) {
                    return back()->with('error', 'Account under maintenance, please try after some time.....');
                }

                foreach ($month_data as $data) {
                    $month_id = $data->id;
                    $pending_amount = $data->pending;
                }

                if ($amount >= $pending_amount) {
                    $extra_amount = $amount - $pending_amount;
                } else {
                    $extra_amount = 0;
                }

                DB::update('UPDATE `bachat_monthly` SET `credited`= `credited` + ? WHERE id = ?', [$amount, $month_id]);

                DB::update('UPDATE `bachat_monthly` SET `pending`= `pending` - ? WHERE id = ? AND `pending` >= `credited`', [$amount, $month_id]);

                DB::update('UPDATE `bachat_monthly` SET `pending`=? WHERE id = ? AND `pending` <= `credited`', [0, $month_id]);

                DB::update('UPDATE `bachat_monthly` SET `is_received`=? WHERE id = ? AND `pending` =? ', [1, $month_id, 0]);

                $time = Date("H:i:s");
                $statement = array(
                    'month_id' => $month_id,
                    'customer_id' => $cuss_id,
                    'amount' => $amount,
                    'details' => $details,
                    'collection_date' => $today,
                    'trans_date' => $today,
                    'trans_time' => $time,
                    'status' => $status,
                );
                DB::table('statement')->insert($statement);
                DB::update('UPDATE `customers` SET `balance`= `balance` + ?, `extra_amount` = `extra_amount` + ? WHERE id = ?', [$amount, $extra_amount, $cuss_id]);

                $collection_for = 'Daily Collection';
            } elseif ($collection_of == 2) {
                date_default_timezone_set('Asia/Kolkata');
                $today = date("Y-m-d");
                $time = Date("H:i:s");

                $perticular_month_loan_data = DB::select('SELECT * FROM loan_monthly_status WHERE `customer_id`=? AND `month_start_date` <= ? AND `month_end_date` >= ? AND `pending_loan` > ? ORDER BY `id` DESC LIMIT ?', [$cuss_id, $today, $today, 0, 1]);
                if (count($perticular_month_loan_data) == 0) {
                    return back()->with('error', 'Loan Data Not Found.....');
                }
                foreach ($perticular_month_loan_data as $loan_data) {
                    $perticular_month_loan_id = $loan_data->id;
                    $loan_id = $loan_data->loan_id;
                    $customer_id = $loan_data->customer_id;
                    $amount_of_loan_paid_off = $loan_data->amount_of_loan_paid_off;
                    $prev_pending_loan = $loan_data->pending_loan;
                }

                $loan_details = DB::select('SELECT * FROM loan WHERE `id`= ?', [$loan_id]);
                if (count($loan_details) == 0) {
                    return back()->with('error', 'Loan Data Not Found.....');
                }
                foreach ($loan_details as $loan_data) {
                    $loan_id = $loan_data->id;
                    $pending_loan = $loan_data->pending_loan;
                }
                if ($pending_loan - $amount < 0) {
                    return back()->with('error', 'Amount should be less than pending amount.....');
                }
                $check = DB::update(
                    'UPDATE `loan_monthly_status` SET `amount_of_loan_paid_off` = ?, `pending_loan`=?, `interest` = ? WHERE id =?',
                    [$amount_of_loan_paid_off + $amount, $prev_pending_loan - $amount, 0, $perticular_month_loan_id]
                );
                if ($check) {

                    $check = DB::update(
                        'UPDATE `loan` SET `pending_loan` = ?, `interest` =?, `is_interest_calculated`=? WHERE id =?',
                        [$pending_loan - $amount, 0, 0, $loan_id]
                    );
                    if ($check) {

                        $loan_statement = array(
                            'loan_id' => $loan_id,
                            'customer_id' => $customer_id,
                            'month_id' => $perticular_month_loan_id,
                            'amount' => $amount,
                            'details' => $details,
                            'collection_date' => $today,
                            'trans_date' => $today,
                            'trans_time' => $time
                        );
                        $check = DB::table('loan_statement')->insert($loan_statement);
                        if (!$check) {
                            return back()->with('error', 'Somethin Went Wrong.....');
                        }
                        $collection_for = 'Loan Collection';
                    } else {
                        return back()->with('error', 'Something Went Wrong.....');
                    }
                } else {
                    return back()->with('error', 'Something Went Wrong.....');
                }
            }
            return back()->with('message', 'Collection collected successfully.....');
            $request->session()->put('email', $email);
            $request->session()->put('acc_no', $acc_no);
            $request->session()->put('conn_amount', $amount);
            $request->session()->put('conn_collection_of', $collection_of);
            $request->session()->put('collection_for', $collection_for);
            $request->session()->put('date', $date);
            $request->session()->put('time', $time);
            return redirect('send_email');
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    // Function For cancel transaction
    public function cancel_transaction($id)
    {
        try {
            $statement_data = DB::select('SELECT * FROM `statement` WHERE `id`=?', [$id]);
            if (count($statement_data) == 0) {
                return back()->with('error', 'Transaction not found.....');
            }
            foreach ($statement_data as $stat_data) {
                $customer_id = $stat_data->customer_id;
                $month_id = $stat_data->month_id;
                $amount = $stat_data->amount;
            }

            $monthly_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `id`=?', [$month_id]);
            $customer_data = DB::select('SELECT * FROM `customers` WHERE `id`=?', [$customer_id]);
            if ((count($monthly_data) == 0) && (count($customer_data) == 0)) {
                return back()->with('error', 'Customers data not found.....');
            }

            DB::delete('DELETE FROM `statement` WHERE id = ?', [$id]);
            DB::update('UPDATE `bachat_monthly` SET `credited`= credited - ? WHERE id = ?', [$amount, $month_id]);

            DB::update('UPDATE `customers` SET `balance`= `balance` - ?, `is_under_maintenance` = ? WHERE id = ?', [$amount, 1, $customer_id]);

            return back()->with('message', 'Transaction canceled sucessfully.....');
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something eent wrong.....');
        }
    }

    public function get_bachat_collection_form_details($bachat_month_id)
    {
        try {
            $bachat_month_data = DB::select('SELECT * FROM bachat_monthly WHERE `id`=?', [$bachat_month_id]);
            if (count($bachat_month_data) != 0) {
                foreach ($bachat_month_data as $bachat_data) {
                    $customer_id = $bachat_data->customer_id;
                }
                $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$customer_id]);
                if (count($customer_data) == 0) {
                    return back()->with('error', 'Data Not Found.....');
                } else {
                    return view('admin.pages.customer.bachat.collect_missed_bachat', array('customer_data' => $customer_data, 'customer_id' => $customer_data[0]->id, 'month_id' => $bachat_month_id));
                }
            } else {
                return back()->with('error', 'Data Not Found.....');
            }
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something eent wrong.....');
        }
    }

    public function show_transaction_to_modify(Request $request, $transaction_id)
    {
        try {
            $transaction_data = DB::select('SELECT * FROM `statement` WHERE id=?', [$transaction_id]);
            return view('admin.pages.customer.bachat.modify_bachat_transaction', array('transaction_id' => $transaction_id, 'transaction_data' => $transaction_data));
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something eent wrong.....');
        }
    }

    public function submit_modified_bachat_collection(Request $request, $transaction_id)
    {
        try {
            $customer_id = $request->input('customer_id');
            $amount = $request->input('amount');
            $details = $request->input('details');
            $collection_date = $request->input('collection_date');

            $date = new DateTime($collection_date);
            $collection_date = $date->format('Y-m-d');
            $con_pin = $request->input('con_pin');
            $admin_data = DB::select('SELECT * FROM `admin` WHERE pin = ?', [$con_pin]);
            if (count($admin_data) == 0) {
                return back()->with('error', 'Pin mot matched.....');
            }

            $transaction_data = DB::select('SELECT * FROM `statement` WHERE id = ?', [$transaction_id]);
            if (count($transaction_data) == 0) {
                return back()->with('error', 'Transaction not found.....');
            }

            foreach ($transaction_data as $data) {
                $month_id = $data->month_id;
                $Prev_customer_id = $data->customer_id;
                $prev_amount = $data->amount;
            }

            $new_month_data = DB::select('SELECT * FROM `bachat_monthly` WHERE customer_id=? AND month_start_date <= ? AND month_end_date >= ?', [$customer_id, $collection_date, $collection_date]);
            if (count($new_month_data) == 0) {
                return back()->with('error', 'Collection month not found, please select valid date.....');
            }

            foreach ($new_month_data as $data) {
                $new_month_id = $data->id;
            }

            DB::update('UPDATE `bachat_monthly` SET `credited`= credited - ? WHERE `id` = ?', [$prev_amount, $month_id]);
            DB::update('UPDATE `customers` SET `balance`= balance - ?, `is_under_maintenance`=? WHERE `id` = ?', [$prev_amount, 1, $Prev_customer_id]);

            DB::update('UPDATE `bachat_monthly` SET `credited`= credited + ? WHERE `id` = ?', [$amount, $new_month_id]);
            DB::update('UPDATE `customers` SET `balance`= balance + ?, `is_under_maintenance`=? WHERE `id` = ?', [$amount, 1, $customer_id]);

            DB::update('UPDATE `statement` SET `month_id`=?, `customer_id`=?, `amount`= ?, `details`= ?, `collection_date`= ? WHERE `id` = ?', [$new_month_id, $customer_id, $amount, $details, $collection_date, $transaction_id]);
            return back()->with('message', 'Collection modified successfully.....');
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something eent wrong.....');
        }
    }

    public function submit_missed_bachat_collection(Request $request)
    {
        try {
            $cuss_id = $request->input('customer_id');
            $amount = $request->input('amount');
            $details = $request->input('details');
            $collection_date = $request->input('collection_date');

            $date = new DateTime($collection_date);
            $collection_date = $date->format('Y-m-d');
            $con_pin = $request->input('con_pin');
            $status = "cr";
            $admin_data = DB::select('SELECT * FROM `admin` WHERE pin = ?', [$con_pin]);
            if (count($admin_data) == 0) {
                return back()->with('error', 'Pin mot matched.....');
            }

            $today = date("Y-m-d");
            if ($collection_date > $today) {
                return back()->with('error', "Collection date must be earlier than today.....");
            }

            $customer_data = DB::select('SELECT * FROM `customers` WHERE id=? AND account_opening_date >= ?', [$cuss_id, $collection_date]);
            if (count($customer_data) > 0) {
                return back()->with('error', 'Collection date must be later than the account opening date.....');
            }

            $month_data = DB::select('SELECT * FROM `bachat_monthly` WHERE customer_id=? AND month_start_date <= ? AND month_end_date >= ?', [$cuss_id, $collection_date, $collection_date]);
            if (count($month_data) == 0) {
                return back()->with('error', 'Account under maintenance, please try after some time.....');
            }

            foreach ($month_data as $data) {
                $month_id = $data->id;
                $customer_id = $data->customer_id;
            }

            DB::update('UPDATE `bachat_monthly` SET `credited`= credited + ? WHERE `id` = ?', [$amount, $month_id]);

            DB::update('UPDATE `customers` SET `balance`= `balance` + ?, `is_under_maintenance` = ? WHERE id = ?', [$amount, 1, $customer_id]);
            date_default_timezone_set('Asia/Kolkata');
            $today = date("Y-m-d");
            $time = Date("H:i:s");
            $statement = array(
                'month_id' => $month_id,
                'customer_id' => $customer_id,
                'amount' => $amount,
                'details' => $details,
                'collection_date' => $collection_date,
                'trans_date' => $today,
                'trans_time' => $time,
                'status' => $status,
            );
            DB::table('statement')->insert($statement);
            return back()->with('message', 'Collection collected successfully.....');
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something eent wrong.....');
        }
    }
}
