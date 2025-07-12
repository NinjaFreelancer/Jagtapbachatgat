<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mail;
use SebastianBergmann\Environment\Console;

class statement_controller extends Controller
{
    // Function For customer Data
    function get_customer_info($id)
    {
        $customer_data = DB::select('SELECT * FROM customers where id=?', [$id]);
        $cuss_data['cuss_data'] = $customer_data;
        echo json_encode($cuss_data);
        exit;
    }

    function get_pending_loan($id)
    {

        $loan = DB::select('SELECT `id` FROM `loan` WHERE `customer_id`=?', [$id]);
        if ($loan) {
            foreach ($loan as $loan_data) {
                $loan_id = $loan_data->id;
            }
            $pending_loan_data = DB::select('SELECT `id`,`pending_loan`,`month_start_date`,`month_end_date` FROM `loan_monthly_status` WHERE `loan_id`=? AND `is_expire`=?', [$loan_id, 0]);

            $pending_loan_data['pending_loan_data'] = $pending_loan_data;
            echo json_encode($pending_loan_data);
            exit;
        } else {
            $pending_loan_data['pending_loan_data'] = $loan;
            echo json_encode($pending_loan_data);
            exit;
        }
    }

    function get_pending_bachat($id)
    {
        //     $pending_bachat_data = DB::select('SELECT `id`,`pending`,`month_start_date`,`month_end_date` FROM `bachat_monthly` WHERE `customer_id`=? AND `is_received`=? ORDER BY id ASC LIMIT ?', [$id, 0, 1]);

        //     $pending_bachat_data['pending_bachat_data'] = $pending_bachat_data;
        //     echo json_encode($pending_bachat_data);

        $today = date("Y-m-d");
        $pending_bachat_amount = DB::select('SELECT sum(`pending`) as pending_bachat_amount FROM `bachat_monthly` WHERE `customer_id`=? AND `is_received`=? AND `month_start_date`<=?', [$id, 0, $today]);

        $current_month_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `customer_id`=? AND `month_start_date`<=? AND `month_end_date`>=?', [$id, $today, $today]);

        $pending_bachat_data['pending_bachat_amount'] = $pending_bachat_amount;
        $pending_bachat_data['current_month_data'] = $current_month_data;
        echo json_encode($pending_bachat_data);
        exit;
    }

    // public function re_calculate_monthly_pending_loan($customer_id, $loan_no)
    // {
    //     $loan_details = DB::select('SELECT * FROM loan WHERE `customer_id`=? AND `loan_no`=?', [$customer_id,$loan_no]);
    //     if (count($loan_details) != 0) {
    //         foreach ($loan_details as $loan_data) {
    //             $monthly_pending_loan = $loan_data-> amount;
    //         }
    //     } else {
    //         return false;
    //     }

    //     $all_months_loan_status = DB::select('SELECT * FROM loan_monthly_status WHERE `customer_id`=? AND `loan_no`=?', [$customer_id, $loan_no]);
    //     if (count($all_months_loan_status) != 0) {
    //         foreach ($all_months_loan_status as $loan_data) {
    //             $perticular_month_loan_id = $loan_data->id;
    //             $pending_loan = $monthly_pending_loan - ($loan_data->amount_of_loan_paid_off);
    //             DB::update(
    //                 'UPDATE `loan_monthly_status` SET `monthly_pending_loan`=?, `pending_loan`=?, `interest` =?, `interest_is_calculate` = ? WHERE id =?',
    //                 [$monthly_pending_loan, $pending_loan, 0, 0, $perticular_month_loan_id]
    //             );
    //             $monthly_pending_loan = $pending_loan;
    //         }
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    // Function For Submit Collection
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

                $new_pending_amount = 0;
                if ($amount > $pending_amount) {
                    $new_pending_amount = 0;
                    $extra_amount = $amount - $pending_amount;
                } else {
                    $new_pending_amount = $pending_amount - $amount;
                    $extra_amount = 0;
                }

                $is_received = 0;
                if ($new_pending_amount == 0) {
                    $is_received = 1;
                }

                DB::update('UPDATE `bachat_monthly` SET `credited`= `credited` + ?, `pending`=?, `is_received`=? WHERE id = ?', [$amount, $new_pending_amount, $is_received, $month_id]);

                $time = Date("H:i:s");
                $statement = array(
                    'month_id' => $month_id,
                    'customer_id' => $cuss_id,
                    'amount' => $amount,
                    'details' => $details,
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
            return back()->with('message', 'Amount collected successfully.....');
            $request->session()->put('email', $email);
            $request->session()->put('acc_no', $acc_no);
            $request->session()->put('conn_amount', $amount);
            $request->session()->put('conn_collection_of', $collection_of);
            $request->session()->put('collection_for', $collection_for);
            $request->session()->put('date', $date);
            $request->session()->put('time', $time);
            return redirect('send_email');
        } catch (Exception $exception) {
            $exception_data = array('exception' => $exception->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    // Function For show todays collection
    public function show_collection_pdf($collection_for_date)
    {
        $date = new DateTime($collection_for_date);
        $collection_for = $date->format('Y-m-d');
        $total_collection = DB::select("SELECT SUM(amount) as colle FROM `statement` WHERE `collection_date` = ?", [$collection_for]);

        foreach ($total_collection as $data) {
            if ($data->colle == null) {
                $total_colle = 0;
            } else {
                $total_colle = $data->colle;
            }
        }

        $total_loan_collection = DB::select("SELECT SUM(amount) as loan FROM `loan_statement` WHERE `collection_date` = ?", [$collection_for]);
        foreach ($total_loan_collection as $data) {
            if ($data->loan == null) {
                $total_loan_colle = 0;
            } else {
                $total_loan_colle = $data->loan;
            }
        }
        $todays_collection = DB::select(
            "SELECT * FROM customers cust
            LEFT JOIN
            (SELECT SUM(amount) as coll, customer_id FROM statement sub_stat WHERE sub_stat.collection_date = ? GROUP BY sub_stat.customer_id) as stat
            ON cust.id = stat.customer_id
            LEFT JOIN
            (SELECT SUM(amount) as loan, customer_id FROM loan_statement loan_stat WHERE loan_stat.collection_date = ? GROUP BY loan_stat.customer_id) as loan
            ON cust.id = loan.customer_id WHERE cust.is_active =?",
            [$collection_for, $collection_for, 1]
        );

        return view('admin.pages.collection.collection_pdf', array('date' => $collection_for, 'todays_collection' => $todays_collection, 'total_collection' => $total_colle, 'total_loan_collection' => $total_loan_colle));
    }

    public function show_monthwise_collection_pdf($collection_for_date)
    {
        $date = DateTime::createFromFormat('Y-m', $collection_for_date);

        $First_day_of_month = $date->format('Y-m-01');
        $month =  $date->format('Y-m');
        $date = new DateTime($First_day_of_month);
        $last_day_of_month = $date->format('Y-m-t');

        $total_collection = DB::select("SELECT SUM(amount) as colle FROM `statement` WHERE `collection_date` >= ? AND `collection_date` <= ?", [$First_day_of_month, $last_day_of_month]);

        foreach ($total_collection as $data) {
            if ($data->colle == null) {
                $total_colle = 0;
            } else {
                $total_colle = $data->colle;
            }
        }

        $total_loan_collection = DB::select("SELECT SUM(amount) as loan FROM `loan_statement` WHERE `collection_date` >= ? AND `collection_date` <= ?", [$First_day_of_month, $last_day_of_month]);
        foreach ($total_loan_collection as $data) {
            if ($data->loan == null) {
                $total_loan_colle = 0;
            } else {
                $total_loan_colle = $data->loan;
            }
        }

        $monthwise_collection = DB::select(
            "SELECT * FROM customers cust
            LEFT JOIN
            (SELECT SUM(amount) as coll, customer_id FROM statement sub_stat WHERE sub_stat.collection_date >= ? AND sub_stat.collection_date <= ? GROUP BY sub_stat.customer_id) as stat
            ON cust.id = stat.customer_id
            LEFT JOIN
            (SELECT SUM(amount) as loan, customer_id FROM loan_statement loan_stat WHERE loan_stat.collection_date >= ? AND loan_stat.collection_date <= ? GROUP BY loan_stat.customer_id) as loan
            ON cust.id = loan.customer_id WHERE cust.is_active =?",
            [$First_day_of_month, $last_day_of_month, $First_day_of_month, $last_day_of_month, 1]
        );

        return view('admin.pages.collection.monthwise_collection_pdf', array('date' => $month, 'monthwise_collection' => $monthwise_collection, 'total_collection' => $total_colle, 'total_loan_collection' => $total_loan_colle));
    }

    // Function For show todays collection
    public function show_todays_collection()
    {
        $today = Date('Y-m-d');
        $total_collection = DB::select("SELECT SUM(amount) as colle FROM `statement` WHERE `collection_date` = ?", [$today]);

        foreach ($total_collection as $data) {
            if ($data->colle == null) {
                $total_colle = 0;
            } else {
                $total_colle = $data->colle;
            }
        }

        $total_loan_collection = DB::select("SELECT SUM(amount) as loan FROM `loan_statement` WHERE `collection_date` = ?", [$today]);
        foreach ($total_loan_collection as $data) {
            if ($data->loan == null) {
                $total_loan_colle = 0;
            } else {
                $total_loan_colle = $data->loan;
            }
        }

        $datewise_collection = DB::select(
            "SELECT * FROM customers cust
            LEFT JOIN
            (SELECT SUM(amount) as coll, customer_id FROM statement sub_stat WHERE sub_stat.collection_date = ? GROUP BY sub_stat.customer_id) as stat
            ON cust.id = stat.customer_id
            LEFT JOIN
            (SELECT SUM(amount) as loan, customer_id FROM loan_statement loan_stat WHERE loan_stat.collection_date = ? GROUP BY loan_stat.customer_id) as loan
            ON cust.id = loan.customer_id WHERE cust.is_active =?",
            [$today, $today, 1]
        );

        return view('admin.pages.collection.datewise_collection', array('date' => $today, 'datewise_collection' => $datewise_collection, 'total_collection' => $total_colle, 'total_loan_collection' => $total_loan_colle));
    }

    public function show_datewise_collection(Request $request)
    {
        try {
            $collection_for_date = $request->input('collection_for');

            $date = new DateTime($collection_for_date);
            $collection_for = $date->format('Y-m-d');

            $total_collection = DB::select("SELECT SUM(amount) as colle FROM `statement` WHERE `collection_date` = ?", [$collection_for]);

            foreach ($total_collection as $data) {
                if ($data->colle == null) {
                    $total_colle = 0;
                } else {
                    $total_colle = $data->colle;
                }
            }

            $total_loan_collection = DB::select("SELECT SUM(amount) as loan FROM `loan_statement` WHERE `collection_date` = ?", [$collection_for]);
            foreach ($total_loan_collection as $data) {
                if ($data->loan == null) {
                    $total_loan_colle = 0;
                } else {
                    $total_loan_colle = $data->loan;
                }
            }

            $datewise_collection = DB::select(
                "SELECT * FROM customers cust
            LEFT JOIN
            (SELECT SUM(amount) as coll, customer_id FROM statement sub_stat WHERE sub_stat.collection_date = ? GROUP BY sub_stat.customer_id) as stat
            ON cust.id = stat.customer_id
            LEFT JOIN
            (SELECT SUM(amount) as loan, customer_id FROM loan_statement loan_stat WHERE loan_stat.collection_date = ? GROUP BY loan_stat.customer_id) as loan
            ON cust.id = loan.customer_id WHERE cust.is_active =?",
                [$collection_for, $collection_for, 1]
            );

            return view('admin.pages.collection.datewise_collection', array('date' => $collection_for, 'datewise_collection' => $datewise_collection, 'total_collection' => $total_colle, 'total_loan_collection' => $total_loan_colle));
        } catch (Exception $e) {
            print_r($e);
            die();
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    public function show_current_month_collection()
    {
        $month = Date('Y-m');
        $First_day_of_month = Date('Y-m-01');
        $date = new DateTime($First_day_of_month);
        $last_day_of_month = $date->format('Y-m-t');

        $total_collection = DB::select("SELECT SUM(amount) as colle FROM `statement` WHERE `collection_date` >= ? AND `collection_date` <= ?", [$First_day_of_month, $last_day_of_month]);

        foreach ($total_collection as $data) {
            if ($data->colle == null) {
                $total_colle = 0;
            } else {
                $total_colle = $data->colle;
            }
        }

        $total_loan_collection = DB::select("SELECT SUM(amount) as loan FROM `loan_statement` WHERE `collection_date` >= ? AND `collection_date` <= ?", [$First_day_of_month, $last_day_of_month]);
        foreach ($total_loan_collection as $data) {
            if ($data->loan == null) {
                $total_loan_colle = 0;
            } else {
                $total_loan_colle = $data->loan;
            }
        }

        $datewise_collection = DB::select(
            "SELECT * FROM customers cust
            LEFT JOIN
            (SELECT SUM(amount) as coll, customer_id FROM statement sub_stat WHERE sub_stat.collection_date >= ? AND sub_stat.collection_date <= ? GROUP BY sub_stat.customer_id) as stat
            ON cust.id = stat.customer_id
            LEFT JOIN
            (SELECT SUM(amount) as loan, customer_id FROM loan_statement loan_stat WHERE loan_stat.collection_date >= ? AND loan_stat.collection_date <= ? GROUP BY loan_stat.customer_id) as loan
            ON cust.id = loan.customer_id WHERE cust.is_active =?",
            [$First_day_of_month, $last_day_of_month, $First_day_of_month, $last_day_of_month, 1]
        );

        return view('admin.pages.collection.monthwise_collection', array('date' => $month, 'datewise_collection' => $datewise_collection, 'total_collection' => $total_colle, 'total_loan_collection' => $total_loan_colle));
    }

    public function show_monthwise_collection(Request $request)
    {

        $collection_for_date = $request->input('collection_for');

        $date = DateTime::createFromFormat('m-Y', $collection_for_date);
        $First_day_of_month = $date->format('Y-m-01');
        $month =  $date->format('Y-m');
        $date = new DateTime($First_day_of_month);
        $last_day_of_month = $date->format('Y-m-t');

        $total_collection = DB::select("SELECT SUM(amount) as colle FROM `statement` WHERE `collection_date` >= ? AND `collection_date` <= ?", [$First_day_of_month, $last_day_of_month]);

        foreach ($total_collection as $data) {
            if ($data->colle == null) {
                $total_colle = 0;
            } else {
                $total_colle = $data->colle;
            }
        }

        $total_loan_collection = DB::select("SELECT SUM(amount) as loan FROM `loan_statement` WHERE `collection_date` >= ? AND `collection_date` <= ?", [$First_day_of_month, $last_day_of_month]);
        foreach ($total_loan_collection as $data) {
            if ($data->loan == null) {
                $total_loan_colle = 0;
            } else {
                $total_loan_colle = $data->loan;
            }
        }

        $datewise_collection = DB::select(
            "SELECT * FROM customers cust
            LEFT JOIN
            (SELECT SUM(amount) as coll, customer_id FROM statement sub_stat WHERE sub_stat.collection_date >= ? AND sub_stat.collection_date <= ? GROUP BY sub_stat.customer_id) as stat
            ON cust.id = stat.customer_id
            LEFT JOIN
            (SELECT SUM(amount) as loan, customer_id FROM loan_statement loan_stat WHERE loan_stat.collection_date >= ? AND loan_stat.collection_date <= ? GROUP BY loan_stat.customer_id) as loan
            ON cust.id = loan.customer_id WHERE cust.is_active =?",
            [$First_day_of_month, $last_day_of_month, $First_day_of_month, $last_day_of_month, 1]
        );

        return view('admin.pages.collection.monthwise_collection', array('date' => $month, 'datewise_collection' => $datewise_collection, 'total_collection' => $total_colle, 'total_loan_collection' => $total_loan_colle));
    }

    // Function For Statement data
    public function get_statement($id)
    {
        $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$id]);
        $statement_data = DB::select('SELECT * FROM `statement` WHERE `customer_id`=? ORDER BY id DESC', [$id]);
        if ((count($customer_data) == 0 || ($statement_data) == 0)) {
            return back()->with('error', 'Data Not Found.....');
        } else {
            return view('admin.pages.customer.bachat.statement', array('customer_data' => $customer_data, 'sr' => 0, 'statement_data' => $statement_data, 'balance' => 0));
        }
    }
    // Function For cancel transaction
    public function cancel_transaction($id)
    {
        $statement_data = DB::select('SELECT * FROM `statement` WHERE `id`=?', [$id]);
        if (count($statement_data) == 0) {
            return back()->with('error', 'Data Not Found.....');
        }
        foreach ($statement_data as $stat_data) {
            $customer_id = $stat_data->customer_id;
            $month_id = $stat_data->month_id;
            $amount = $stat_data->amount;
        }

        $monthly_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `id`=? AND `customer_id`=?', [$month_id, $customer_id]);
        $customer_data = DB::select('SELECT * FROM `customers` WHERE `id`=?', [$customer_id]);
        if ((count($monthly_data) == 0) && (count($customer_data) == 0)) {
            return back()->with('error', 'Data Not Found.....');
        }
        foreach ($monthly_data as $month_data) {
            $month_id = $month_data->id;
            $prev_credited = $month_data->credited;
            $prev_pending = $month_data->pending;
        }
        $new_credited = $prev_credited - $amount;
        $new_pending = $prev_pending + $amount;

        $check = DB::delete('DELETE FROM `statement` WHERE id = ?', [$id]);
        if ($check) {
            $check = DB::update(
                'UPDATE `bachat_monthly` SET `is_received`= ?, `penalty_amount`= ?, `is_penalty_applicable`= ?, `has_the_penalty_been_calculated`= ?, `penalty_calculate_up_to`=?, `credited`= ?, `pending`= ? WHERE id = ?',
                [0, 0, 0, 0, null, $new_credited, $new_pending, $month_id]
            );
            if ($check) {
                foreach ($customer_data as $cust_data) {
                    $prev_balance = $cust_data->balance;
                }
                $new_balance = $prev_balance - $amount;
                $check = DB::update('UPDATE `customers` SET `balance`= ? WHERE id = ?', [$new_balance, $customer_id]);
                if ($check) {
                    return back()->with('message', 'Transaction canceled sucessfully.....');
                } else {
                    return back()->with('error', 'Something Went Wrong.....');
                }
            } else {
                return back()->with('error', 'Something Went Wrong.....');
            }
        } else {
            return back()->with('error', 'Month Is Expired.....');
        }
    }

    public function get_all_pending_bachat($id)
    {
        $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$id]);
        $pending_bachat_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `customer_id`=? AND `is_received`=? AND `is_expire`=?', [$id, 0, 1]);

        if ((count($customer_data) != 0) && (count($pending_bachat_data) != 0)) {
            return view('admin.pages.customer.bachat.pending_bachat', array('customer_data' => $customer_data, 'sr' => 0, 'pending_bachat_data' => $pending_bachat_data));
        } else {
            return redirect('profile/' . $id);
        }
    }

    // Function For Calculate Penelty
    public function calculate_penalty($id, Request $request)
    {
        $calculate_up_to = $request->input('calculate_up_to');
        $today = Date('Y-m-d');
        $date = new DateTime($calculate_up_to);
        $calculate_up_to = $date->format('Y-m-d');

        $penalty_month = DB::select('SELECT * FROM bachat_monthly WHERE `id`=?', [$id]);
        if (count($penalty_month) <= 0) {
            return back()->with('error', 'Data Not Found.....');
        }

        foreach ($penalty_month as $prev_month_bachat) {
            $pending = ($prev_month_bachat->monthly_bachat_amount - $prev_month_bachat->credited);
            $month_end_date = $prev_month_bachat->month_end_date;
        }

        $pending_months = 0;

        $date = new DateTime($month_end_date);
        $date->modify('+1 month');
        $month_end_date = $date->format('Y-m-d');

        while ($month_end_date < $calculate_up_to) {
            $pending_months++;
            $date = new DateTime($month_end_date);
            $date->modify('+1 month');
            $month_end_date = $date->format('Y-m-d');
        }

        if ($pending_months == 0) {
            return back()->with('error', 'Please Provide Valid Date.....');
        }

        $penalty = ((($pending / 100) * 2) * $pending_months);

        $check = DB::update('UPDATE `bachat_monthly` SET `penalty_amount`=?, `bachat_pending_months`=?, `is_penalty_applicable` = ?, `has_the_penalty_been_calculated`=?, `penalty_calculate_up_to`=? WHERE id = ?', [$penalty, $pending_months, 1, 1, $calculate_up_to, $id]);
        if ($check) {
            return back()->with('message', 'Penalty Calculated Successfully.....');
        } else {
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    // Function For Statement data
    public function collect_penalty(request $request, $id)
    {
        try {
            $penalty_month = DB::select('SELECT * FROM bachat_monthly WHERE `id`=? ', [$id]);
            if (count($penalty_month) <= 0) {
                return back()->with('error', 'Data Not Found.....');
            }
            foreach ($penalty_month as $month_data) {
                $cuss_id = $month_data->customer_id;
                $penalty_amount = $month_data->penalty_amount;
            }
            $customer_data = DB::select('SELECT * FROM `customers` WHERE id = ?', [$cuss_id]);
            if (count($customer_data) <= 0) {
                return back()->with('error', 'Data Not Found.....');
            }
            DB::update('UPDATE `customers` SET `total_penalty_amount`= `total_penalty_amount` + ? WHERE id =?', [$penalty_amount, $cuss_id]);
            DB::update('UPDATE `bachat_monthly` SET `has_the_penalty_been_collected`= ? WHERE id =?', [1, $id]);
            return back()->with('message', 'Penalty Collected Successfully.....');
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    // Function For Statement data
    public function submit_penalty(request $request, $penalty_month_id)
    {
        $conf_pin = $request->input('con_pin');
        $credited_amount = $request->input('pending_amount');

        // Check Pin
        $pin_data = DB::select('SELECT * FROM `admin`');
        if (count($pin_data) == 0) {
            return back()->with('error', 'Admin Data Not Found.....');
        }

        foreach ($pin_data as $admin_pin_data) {
            $current_pin = $admin_pin_data->pin;
        }
        if ($current_pin == $conf_pin) {
            return back()->with('error', 'Pin Not Match.....');
        }

        // Check credited Amount
        if ($credited_amount == 0) {
            return back()->with('error', 'Allready Amount Is Collected.....');
        }

        // Get month data
        $penalty_month_cerdit_amount = DB::select('SELECT * FROM `bachat_monthly` WHERE id = ?', [$penalty_month_id]);
        if (count($penalty_month_cerdit_amount) == 0) {
            return back()->with('error', 'Month Data Not Found.....');
        }

        foreach ($penalty_month_cerdit_amount as $credited_data) {
            $prev_creadited_amount = $credited_data->credited;
            $cuss_id = $credited_data->customer_id;
        }

        $curr_creadited_amount = $prev_creadited_amount + $credited_amount;

        // Get customer data
        $cuss_data = DB::select('SELECT * FROM `customers` WHERE id=?', [$cuss_id]);
        if (count($cuss_data) == 0) {
            return back()->with('error', 'Data Not Found.....');
        }
        foreach ($cuss_data as $a_data) {
            $prev_balance = $a_data->balance;
        }

        $new_balance = $prev_balance + $credited_amount;
        $check = DB::update('UPDATE `customers` SET `balance`= ? WHERE id =?', [$new_balance, $cuss_id]);
        if ($check) {
            date_default_timezone_set('Asia/Kolkata');
            $date = date("Y-m-d");
            $time = Date("H:i:s");
            $statement = array('month_id' => $penalty_month_id, 'customer_id' => $cuss_id, 'amount' => $credited_amount, 'trans_date' => $date, 'trans_time' => $time, 'status' => 'cr',);
            $check = DB::table('statement')->insert($statement);
            if ($check) {
                $today = Date('Y-m-d');
                $check = DB::update('UPDATE `bachat_monthly` SET `credited`= ?,`pending`= ?,`is_received`=?, `penalty_credited_date`=? WHERE id =?', [$curr_creadited_amount, 0, 1, $today, $penalty_month_id]);
                if ($check) {
                    return back()->with('message', 'Penalty And Pending Amount Collected Successfully.....');
                } else {
                    return back()->with('error', 'Something Went Wrong one.....');
                }
            } else {
                return back()->with('error', 'Something Went Wrong two.....');
            }
        } else {
            return back()->with('error', 'Something Went Wrong three.....');
        }
    }

    // Function For Monthly Statement data
    public function get_monthly_statement_data($id)
    {
        $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$id]);
        $monthly_statement_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `customer_id`=?', [$id]);
        if ((count($customer_data) == 0 || count($monthly_statement_data) == 0)) {
            return back()->with('error', 'Data Not Found.....');
        } else {
            $total_penalty_amount = DB::select('SELECT SUM(penalty_amount) FROM `bachat_monthly` WHERE `customer_id`=?', [$id]);

            return view('admin.pages.customer.bachat.monthly_bachat_statement', array('customer_data' => $customer_data, 'sr' => 0, 'monthly_statement_data' => $monthly_statement_data, 'total_penalty_amount' => $total_penalty_amount[0]->{'SUM(penalty_amount)'}));
        }
    }

    // Function For Monthly Statement data
    public function get_monthly_statement_pdf_data($id)
    {
        try {
            $today = Date('Y-m-d');
            $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$id]);
            $monthly_statement_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `customer_id`=? AND `month_start_date`<=?', [$id, $today]);
            if ((count($customer_data) == 0 || count($monthly_statement_data) == 0)) {
                return back()->with('error', 'Data Not Found.....');
            } else {
                $total_penalty_amount = DB::select('SELECT SUM(penalty_amount) FROM `bachat_monthly` WHERE `customer_id`=?', [$id]);

                return view('admin.pages.customer.bachat.monthly_bachat_statement_pdf', array('customer_data' => $customer_data, 'sr' => 0, 'monthly_statement_data' => $monthly_statement_data, 'total_penalty_amount' => $total_penalty_amount[0]->{'SUM(penalty_amount)'}));
            }
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    // Function For Monthly Statement data
    public function get_monthly_bachat_status($id)
    {
        try {
            $monthly_bachat_status_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `id`=?', [$id]);
            if (count($monthly_bachat_status_data) == 0) {
                return back()->with('error', 'Data Not Found.....');
            }
            $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$monthly_bachat_status_data[0]->customer_id]);
            if (count($customer_data) == 0) {
                return back()->with('error', 'Data Not Found.....');
            }
            return view('admin.pages.customer.bachat.monthly_bachat_status', array('customer_data' => $customer_data, 'monthly_bachat_status_data' => $monthly_bachat_status_data));
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }
}
