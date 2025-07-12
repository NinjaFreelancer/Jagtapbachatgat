<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class loan_controller extends Controller
{

    // Function For Loan Form
    public function give_a_new_loan($id)
    {
        $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$id]);
        $loan_data = DB::select('SELECT * FROM loan WHERE `customer_id`=?', [$id]);
        $loan_no = count($loan_data);
        if (count($customer_data) == 0) {
            return back()->with('error', 'Data Not Found.....');
        } else {
            if ($loan_no == 0) {
                $loan_no = 1;
            } else {
                $loan_no++;
            }
            return view('admin.pages.customer.loan.give_a_loan', array('customer_data' => $customer_data, 'loan_no' => $loan_no));
        }
    }

    // Function For Submit Loan To Customer
    public function submit_loan_to_customer(Request $request, $id)
    {
        $loan_no = $request->input('loan_no');
        $loan_start_date = $request->input('start_date');
        $old_loan_end_date = $request->input('old_loan_end_date');
        $loan_amount = $request->input('loan_amount');
        $shares_amount = $request->input('shares_amount');
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

        $loan_data = DB::select('SELECT * FROM loan WHERE `customer_id`=? AND `status`=?', [$id, 0]);

        if (count($loan_data) != 0) {
            return back()->with('error', 'Allready Loan Is Active.....');
        }
        $cust_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$id]);
        if (count($cust_data) == 0) {
            return back()->with('error', 'Customer Not Found.....');
        }

        $date = new DateTime($loan_start_date);
        $start_date = $date->format('Y-m-d');

        $date = new DateTime($old_loan_end_date);
        $old_loan_end_date = $date->format('Y-m-d');

        $date = new DateTime($start_date);
        $date->modify('+10 month');
        $loan_end_date = $date->format('Y-m-d');

        $date = new DateTime($start_date);
        $date = \Carbon\Carbon::parse($date)->endOfMonth();

        $end_date = $date->format('Y-m-d');

        $date->modify('+1 day');
        $new_month_start_date = $date->format('Y-m-d');

        $date->modify('-1 day');
        $end_date = $date->format('Y-m-d');

        $today = Date('Y-m-d');

        // $date = new DateTime($today);
        // $date->modify('-30 days');
        // $check_date = $date->format('Y-m-d');

        try {
            // if ($start_date < $check_date) {
            //     return back()->with('error', 'Loan Start Date Should Near Of Todays Date.....');
            // }
            if ($start_date > $today) {
                return back()->with('error', 'Loan Start Date Should Not Greater Than Todays Date.....');
            }

            foreach ($cust_data as $cust_data) {
                $acc_no = $cust_data->acc_no;
            }
            $monthly_emi = ($loan_amount / 10);

            $agent_commission = ((1 / 100) * $loan_amount);
            $loan_data = array(
                'customer_id' => $id,
                'loan_no' => $loan_no,
                'amount' => $loan_amount,
                'pending_loan' => $loan_amount,
                'monthly_emi' => $monthly_emi,
                'shares_amount' => $shares_amount,
                'agent_commission' => $agent_commission,
                'interest_calculated_date' => $start_date,
                'loan_start_date' => $start_date,
                'loan_end_date' => $loan_end_date
            );
            $loan_id = DB::table('loan')->insertGetId($loan_data);
            if ($loan_id) {
                // $monthly_loan_data = array(
                //     'loan_id' => $loan_id,
                //     'customer_id' => $id,
                //     'pending_loan' => $loan_amount,
                //     'month_start_date' => $start_date,
                //     'month_end_date' => $end_date,
                //     'next_month_start_date' => $new_month_start_date
                // );
                // $check = DB::table('loan_monthly_status')->insert($monthly_loan_data);

                // old loan data
                while ($start_date < $old_loan_end_date) {
                    $monthly_loan_data = array(
                        'loan_id' => $loan_id,
                        'customer_id' => $id,
                        'pending_loan' => 0,
                        'month_start_date' => $start_date,
                        'month_end_date' => $end_date,
                        'next_month_start_date' => $new_month_start_date
                    );
                    $start_date = $new_month_start_date;
                    $date = new DateTime($start_date);
                    $date = \Carbon\Carbon::parse($date)->endOfMonth();

                    $end_date = $date->format('Y-m-d');

                    $date->modify('+1 day');
                    $new_month_start_date = $date->format('Y-m-d');

                    $date->modify('-1 day');
                    $end_date = $date->format('Y-m-d');
                    DB::table('loan_monthly_status')->insert($monthly_loan_data);
                }
                return back()->with('message', 'Loan Amount Submitted Sucessfully.....');
            } else {
                return back()->with('error', 'Something Went Wrong.....');
            }
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    // Function For show Pending Loan
    public function show_pending_loans()
    {
        $loan_data = DB::select('SELECT loan.customer_id, loan.account_no, loan.amount, loan.pending_loan, loan.monthly_emi, loan.completed_months, customers.full_name, customers.is_active FROM loan INNER JOIN customers ON loan.customer_id = customers.id');
        return view('admin.pages.customer.loan.pending_loan', array('loan_data' => $loan_data));
    }

    public function show_loan_details($loan_id)
    {
        $loan_data = DB::select('SELECT * FROM `loan` WHERE `id`=?', [$loan_id]);

        if (count($loan_data) == 0) {
            return back()->with('error', 'Loan Data Not Found.....');
        }
        return view('admin.pages.customer.loan.show_loan_details', array('loan_data' => $loan_data));
    }

    public function calculate_interest_of_loan(Request $request, $loan_id)
    {
        try {
            $calculate_up_to = $request->input('calculate_up_to');
            $today = Date('Y-m-d');
            $interest_rate = $request->input('interest_rate');
            $date = new DateTime($calculate_up_to);
            $calculate_up_to = $date->format('Y-m-d');

            $loan_data = DB::select('SELECT * FROM `loan` WHERE `id`=?', [$loan_id]);

            if (count($loan_data) == 0) {
                return back()->with('error', 'Loan Data Not Found.....');
            }
            foreach ($loan_data as $loan_data) {
                $customer_id = $loan_data->customer_id;
                $loan_id = $loan_data->id;
                $loan_start_date = $loan_data->loan_start_date;
            }
            $check_month = DB::select('SELECT * FROM loan_monthly_status WHERE `loan_id` = ? AND `customer_id` = ? AND `month_start_date` <= ? AND `month_end_date` >= ? ORDER BY id DESC LIMIT ?', [$loan_id, $customer_id, $calculate_up_to, $calculate_up_to, 1]);
            if (count($check_month) == 0) {
                return back()->with('error', 'Date Should Be Between Loan Ending Month......');
            }
            // $check_moth = DB::select('SELECT * FROM loan_monthly_status WHERE `loan_id` = ? AND `customer_id` = ? ORDER BY id DESC LIMIT ?', [$loan_id, $customer_id, 1]);
            // if (count($check_moth) != 0) {
            //     foreach ($check_moth as $loan_data) {
            //         $month_start_date = $loan_data->month_start_date;
            //         $month_end_date = $loan_data->month_end_date;
            //     }
            //     if(!(($month_start_date <= $calculate_up_to) && ($month_end_date >= $calculate_up_to)))
            //     {
            //         return back()->with('error', 'Date Should Be Between Loan Ending Month......');
            //     }

            // } else {
            //     return back()->with('error', 'Loan Monthly Data Not Found.....');
            // }

            $all_months_loan_status = DB::select('SELECT * FROM loan_monthly_status WHERE `loan_id`=? AND `customer_id`=?', [$loan_id, $customer_id]);

            $total_interest = 0;
            $total_extra_days = 0;
            $month_count = 0;
            foreach ($all_months_loan_status as $months_loan_data) {
                $monthly_status_loan_id = $months_loan_data->id;
                $pending_loan = $months_loan_data->pending_loan;
                $start_date = $months_loan_data->month_start_date;
                $end_date = $months_loan_data->month_end_date;

                if ($loan_start_date == $start_date) {
                    $extra_days = $this->calculate_days($start_date, $end_date);
                    $interest = (($pending_loan / 100) * $interest_rate);
                    $interest = (($interest / 30) * $extra_days);
                } elseif (($start_date <= $calculate_up_to) && ($end_date >= $calculate_up_to)) {
                    $extra_days = $this->calculate_days($start_date, $calculate_up_to);
                    $interest = (($pending_loan / 100) * $interest_rate);
                    $interest = (($interest / 30) * $extra_days);
                } else {
                    $month_count++;
                    $interest = (($pending_loan / 100) * $interest_rate);
                }
                DB::update('UPDATE `loan_monthly_status` SET `interest`= ?, `is_interest_calculated`= ? WHERE id = ?', [$interest, 1, $monthly_status_loan_id]);

                $total_interest = $total_interest + $interest;
                $total_extra_days = $total_extra_days  + $extra_days;
                if ($total_extra_days >= 30) {
                    $month_count++;
                    $total_extra_days = $total_extra_days - 30;
                }
                $extra_days = 0;
            }
            DB::update('UPDATE `loan` SET `interest_calculated_up_to` = ?, `interest_calculated_date` = ?, `interest`= ?, `completed_months`=?, `extra_days`=?, `is_interest_calculated`=? WHERE id = ?', [$calculate_up_to, $today, $total_interest, $month_count, $total_extra_days, 1, $loan_id]);
            return back()->with('message', 'Interest Calculeted Successfully.....');
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    // Function For collect all loan amount
    public function collect_all_loan_amount($id)
    {
        $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$id]);
        $loan_data = DB::select('SELECT * FROM loan WHERE `customer_id`=? AND `status`=?', [$id, 0]);
        if ((count($loan_data) != 0) && (count($customer_data) != 0)) {
            return view('admin.pages.customer.loan.collect_all_loan', array('customer_data' => $customer_data, 'loan_data' => $loan_data));

            // foreach ($loan_data as $curr_loan_data) {
            //     $loan_no = $curr_loan_data->loan_no;
            //     $pending_loan = $curr_loan_data->pending_loan;
            //     $interest = $curr_loan_data->interest;
            // }
            // $monthly_loan_data = DB::select('SELECT * FROM `loan_monthly_status` WHERE `customer_id`=? AND `loan_no`= ? AND `is_expire`=?', [$id, $loan_no, 0]);
            // if (count($monthly_loan_data) != 0) {
            //     foreach ($monthly_loan_data as $curr_month_loan_data) {
            //         $pending_month_id = $curr_month_loan_data->id;
            //         $interest_is_calculate = $curr_month_loan_data->interest_is_calculate;
            //         $pending_loan = $curr_month_loan_data->pending_loan;
            //     }
            //     $total = $pending_loan + $interest;
            //     return view('admin.pages.collect_all_loan', array('customer_data' => $customer_data, 'pending_month_id' => $pending_month_id, 'interest_is_calculate' => $interest_is_calculate,  'pending' => $pending_loan, 'interest' => $interest, 'total' => $total));
            // } else {
            //     return back()->with('error', 'Data Not Found.....');
            // }
        } else {
            return back()->with('error', 'Data Not Found.....');
        }
    }

    public function calculate_days($start_date, $end_date)
    {

        $date1 = DateTime::createFromFormat('Y-m-d', $start_date);
        $date2 = DateTime::createFromFormat('Y-m-d', $end_date);

        $extra_days = $date1->diff($date2)->d;
        $date = new DateTime($end_date);
        if (!($date->format('d') == 31)) {
            $extra_days++;
        }

        return $extra_days;
    }

    // Function For Loan Statement data
    public function get_Loan_statement($loan_id)
    {
        $loan_data = DB::select('SELECT * FROM loan WHERE `id`=?', [$loan_id]);
        if (count($loan_data) != 0) {
            foreach ($loan_data as $loan_data) {
                $customer_id = $loan_data->customer_id;
                $pending_loan = $loan_data->amount;
                $interest = $loan_data->interest;
                $status = $loan_data->status;
            }

            $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$customer_id]);
            $loan_statement_data = DB::select('SELECT * FROM `loan_statement` WHERE `loan_id`=? AND `customer_id`=?', [$loan_id, $customer_id]);

            if (count($customer_data) != 0) {
                if ($status == 0) {
                    return view('admin.pages.customer.loan.loan_statement', array('customer_data' => $customer_data, 'sr' => 0, 'loan_data' => $loan_data, 'loan_statement_data' => $loan_statement_data, 'pending' => $pending_loan));
                } else {
                    $pending_loan = $pending_loan + $interest;
                    return view('admin.pages.customer.loan.previous_loan_statment', array('customer_data' => $customer_data, 'sr' => 0, 'loan_data' => $loan_data, 'loan_statement_data' => $loan_statement_data, 'pending' => $pending_loan));
                }
            } else {
                return back()->with('error', 'Data Not Found.....');
            }
        } else {
            return back()->with('error', 'Data Not Found.....');
        }
    }

    // Function For Monthly Loan Statement data
    public function get_monthly_loan_statement($loan_id)
    {
        $all_loan_data = DB::select('SELECT * FROM loan WHERE `id`=?', [$loan_id]);
        if (count($all_loan_data) != 0) {
            foreach ($all_loan_data as $loan_data) {
                $customer_id = $loan_data->customer_id;
                $status = $loan_data->status;
            }
            $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$customer_id]);
            $monthly_loan_statement_data = DB::select('SELECT * FROM `loan_monthly_status` WHERE `loan_id`=? AND `customer_id`=?', [$loan_id, $customer_id]);
            if ((count($customer_data) == 0) || (count($monthly_loan_statement_data) == 0)) {
                return back()->with('error', 'Data Not Found.....');
            } else {
                if ($status == 0) {
                    return view('admin.pages.customer.loan.monthly_loan_statement', array('loan_id' => $loan_id, 'customer_data' => $customer_data, 'sr' => 0, 'monthly_loan_statement' => $monthly_loan_statement_data));
                } else {
                    return view('admin.pages.customer.loan.previous_loan_monthly_statment', array('customer_data' => $customer_data, 'sr' => 0, 'monthly_loan_statement' => $monthly_loan_statement_data));
                }
            }
        } else {
            return back()->with('error', 'Data Not Found.....');
        }
    }

    // Function For Monthly Loan Statement data
    public function get_monthly_loan_statement_pdf_data($loan_id)
    {
        $all_loan_data = DB::select('SELECT * FROM loan WHERE `id`=?', [$loan_id]);
        if (count($all_loan_data) != 0) {
            foreach ($all_loan_data as $loan_data) {
                $customer_id = $loan_data->customer_id;
                $status = $loan_data->status;
            }
            $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$customer_id]);
            $monthly_loan_statement_data = DB::select('SELECT * FROM `loan_monthly_status` WHERE `loan_id`=? AND `customer_id`=?', [$loan_id, $customer_id]);
            if ((count($customer_data) == 0) || (count($monthly_loan_statement_data) == 0)) {
                return back()->with('error', 'Data Not Found.....');
            } else {
                if ($status == 0) {
                    return view('admin.pages.customer.loan.monthly_loan_statement_pdf', array('loan_id' => $loan_id, 'customer_data' => $customer_data, 'sr' => 0, 'monthly_loan_statement' => $monthly_loan_statement_data));
                } else {
                    return view('admin.pages.customer.loan.previous_loan_monthly_statment', array('customer_data' => $customer_data, 'sr' => 0, 'monthly_loan_statement' => $monthly_loan_statement_data));
                }
            }
        } else {
            return back()->with('error', 'Data Not Found.....');
        }
    }

    public function remove_month($month_id)
    {
        try {
            $check = DB::delete('DELETE FROM `loan_monthly_status` WHERE id = ?', [$month_id]);

            return back()->with('message', 'Month Removed Sucessfully.....');
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    // public function add_month($loan_id)
    // {
    //     $loan_data = DB::select('SELECT * FROM loan_monthly_status WHERE loan_id = ? GROUP BY id ORDER BY id DESC LIMIT ?', [$loan_id, 1]);
    //     if (count($loan_data) > 0) {

    //         foreach ($loan_data as $data) {
    //             $month_start_date = $data->next_month_start_date;
    //             $date = new DateTime($month_start_date);
    //             $date->modify('+1 month');

    //             $next_month_start_date = $date->format('Y-m-d');
    //             $date->modify('-1 day');

    //             $month_end_date = $date->format('Y-m-d');

    //             $save_cuur_month = array(
    //                 'loan_id' => $data->loan_id,
    //                 'customer_id' => $data->customer_id,
    //                 'pending_loan' => $data->pending_loan,
    //                 'month_start_date' => $month_start_date,
    //                 'month_end_date' => $month_end_date,
    //                 'next_month_start_date' => $next_month_start_date,
    //             );
    //             DB::table('loan_monthly_status')->insertGetId($save_cuur_month);
    //             DB::update('UPDATE `loan_monthly_status` SET `is_expire`=? WHERE id = ?', [1, $data->id]);
    //             return back()->with('message', 'Month Added Successfully.....');
    //         }
    //     } else {
    //         return back()->with('error', 'Loan Data Not Found.....');
    //     }
    // }

    // public function get_collection_form_details($customer_id)
    // {
    //     $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$customer_id]);
    //     if (count($customer_data) == 0) {
    //         return back()->with('error', 'Data Not Found.....');
    //     } else {
    //         return view('admin.pages.customer.loan.collect_missed_loan', array('customer_data' => $customer_data));
    //     }
    // }

    public function get_collection_form_details($loan_id)
    {
        $loan_data = DB::select('SELECT * FROM loan WHERE `id`=?', [$loan_id]);
        if (count($loan_data) != 0) {
            foreach ($loan_data as $loan_data) {
                $customer_id = $loan_data->customer_id;
                $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$customer_id]);
                if (count($customer_data) == 0) {
                    return back()->with('error', 'Data Not Found.....');
                } else {
                    return view('admin.pages.customer.loan.collect_missed_loan', array('customer_data' => $customer_data, 'loan_id' => $loan_id));
                }
            }
        } else {
            return back()->with('error', 'Data Not Found.....');
        }
    }

    public function check_pin($conf_pin)
    {
        $admin_data = DB::select('SELECT * FROM `admin`');
        foreach ($admin_data as $a_data) {
            $pin = $a_data->pin;
        }
        if ($conf_pin == $pin) {
            return true;
        } else {
            return false;
        }
    }

    // public function re_calculate_monthly_pending_loan($customer_id, $loan_id)
    // {
    //     $loan_details = DB::select('SELECT * FROM loan WHERE `customer_id`=? AND `id`=?', [$customer_id, $loan_id]);
    //     if (count($loan_details) != 0) {
    //         foreach ($loan_details as $loan_data) {
    //             $monthly_pending_loan = $loan_data->amount;
    //         }
    //     } else {
    //         return false;
    //     }

    //     $all_months_loan_status = DB::select('SELECT * FROM loan_monthly_status WHERE `customer_id`=? AND `loan_id`=?', [$customer_id, $loan_id]);
    //     if (count($all_months_loan_status) != 0) {
    //         foreach ($all_months_loan_status as $loan_data) {
    //             $perticular_month_loan_id = $loan_data->id;
    //             $pending_loan = $monthly_pending_loan - ($loan_data->amount_of_loan_paid_off);
    //             DB::update(
    //                 'UPDATE `loan_monthly_status` SET `monthly_pending_loan`=?, `pending_loan`=?, `interest` =?, `is_interest_calculated` = ? WHERE id =?',
    //                 [$monthly_pending_loan, $pending_loan, 0, 0, $perticular_month_loan_id]
    //             );
    //             $monthly_pending_loan = $pending_loan;
    //         }
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    // public function submit_missed_loan_collection(Request $request, $loan_month_id)
    // {
    //     $amount = $request->input('amount');
    //     $details = $request->input('details');
    //     $collection_date = $request->input('collection_date');
    //     $con_pin = $request->input('con_pin');

    //     $date = new DateTime($collection_date);
    //     $collection_date = $date->format('Y-m-d');

    //     $check = $this->check_pin($con_pin);
    //     if (!$check) {
    //         return back()->with('error', 'Pin Not Match.....');
    //     }
    //     $perticular_month_loan_data = DB::select('SELECT * FROM loan_monthly_status WHERE `id`=?', [$loan_month_id]);
    //     if (count($perticular_month_loan_data) != 0) {
    //         foreach ($perticular_month_loan_data as $loan_data) {
    //             $customer_id = $loan_data->customer_id;
    //             $loan_id = $loan_data->loan_id;
    //             $amount_of_loan_paid_off = $loan_data->amount_of_loan_paid_off;
    //         }

    //         $loan_details = DB::select('SELECT * FROM loan WHERE `customer_id`=? AND `id`=?', [$customer_id, $loan_id]);
    //         if (count($loan_details) != 0) {
    //             foreach ($loan_details as $loan_data) {
    //                 $pending_loan = $loan_data->pending_loan;
    //             }
    //             if ($pending_loan - $amount < 0) {
    //                 return back()->with('error', 'Amount should be less than pending amount.....');
    //             }
    //             $check = DB::update(
    //                 'UPDATE `loan_monthly_status` SET `amount_of_loan_paid_off` = ? WHERE id =?',
    //                 [$amount_of_loan_paid_off + $amount, $loan_month_id]
    //             );
    //             if ($check) {

    //                 $check = $this->re_calculate_monthly_pending_loan($customer_id, $loan_id);
    //                 if (!$check) {
    //                     return back()->with('error', 'Somethin Went Wrong.....');
    //                 }

    //                 $check = DB::update(
    //                     'UPDATE `loan` SET `pending_loan` = ?, `interest` =?, `is_interest_calculated`=? WHERE id =?',
    //                     [$pending_loan - $amount, 0, 0, $loan_id]
    //                 );
    //                 if ($check) {
    //                     date_default_timezone_set('Asia/Kolkata');
    //                     $today = date("Y-m-d");
    //                     $time = Date("H:i:s");
    //                     $loan_statement = array(
    //                         'loan_id' => $loan_id,
    //                         'customer_id' => $customer_id,
    //                         'month_id' => $loan_month_id,
    //                         'amount' => $amount,
    //                         'details' => $details,
    //                         'collection_date' => $collection_date,
    //                         'trans_date' => $today,
    //                         'trans_time' => $time
    //                     );
    //                     $check = DB::table('loan_statement')->insert($loan_statement);
    //                     if ($check) {
    //                         return back()->with('message', 'Loan Collection Added Successfully.....');
    //                     } else {
    //                         return back()->with('error', 'Somethin Went Wrong.....');
    //                     }
    //                 } else {
    //                     return back()->with('error', 'Something Went Wrong.....');
    //                 }
    //             } else {
    //                 return back()->with('error', 'Something Went Wrong.....');
    //             }
    //         } else {
    //             return back()->with('error', 'Loan Data Not Found.....');
    //         }
    //     } else {
    //         return back()->with('error', 'Data Not Found.....');
    //     }
    // }

    public function submit_missed_loan_collection(Request $request, $loan_id)
    {
        try {
            $amount = $request->input('amount');
            $details = $request->input('details');
            $collection_date = $request->input('collection_date');
            $con_pin = $request->input('con_pin');

            $date = new DateTime($collection_date);
            $collection_date = $date->format('Y-m-d');

            $check = $this->check_pin($con_pin);
            if (!$check) {
                return back()->with('error', 'Pin Not Match.....');
            }
            $perticular_month_loan_data = DB::select('SELECT * FROM loan_monthly_status WHERE `month_start_date`<=? AND `month_end_date`>=? AND `loan_id`=?', [$collection_date, $collection_date, $loan_id]);
            if (count($perticular_month_loan_data) != 0) {
                foreach ($perticular_month_loan_data as $loan_data) {
                    $customer_id = $loan_data->customer_id;
                    $loan_month_id = $loan_data->id;
                    $amount_of_loan_paid_off = $loan_data->amount_of_loan_paid_off;

                    $loan_details = DB::select('SELECT * FROM loan WHERE `customer_id`=? AND `id`=?', [$customer_id, $loan_id]);
                    if (count($loan_details) != 0) {
                        foreach ($loan_details as $loan_data) {
                            $pending_loan = $loan_data->pending_loan;
                        }
                        if ($pending_loan - $amount < 0) {
                            return back()->with('error', 'Amount should be less than pending amount.....');
                        }
                        DB::update('UPDATE `loan_monthly_status` SET `amount_of_loan_paid_off` = ? WHERE id =?', [$amount_of_loan_paid_off + $amount, $loan_month_id]);

                        DB::update('UPDATE `loan` SET `pending_loan` = ?, `interest` =?, `is_interest_calculated`=? WHERE id =?', [$pending_loan - $amount, 0, 0, $loan_id]);

                        date_default_timezone_set('Asia/Kolkata');
                        $today = date("Y-m-d");
                        $time = Date("H:i:s");
                        $loan_statement = array(
                            'loan_id' => $loan_id,
                            'customer_id' => $customer_id,
                            'month_id' => $loan_month_id,
                            'amount' => $amount,
                            'details' => $details,
                            'collection_date' => $collection_date,
                            'trans_date' => $today,
                            'trans_time' => $time
                        );
                        DB::table('loan_statement')->insert($loan_statement);
                        DB::update('UPDATE `customers` SET `is_loan_under_maintenance`= ? WHERE `id` = ?', [1, $customer_id]);
                        return back()->with('message', 'Loan Collection Added Successfully.....');
                    } else {
                        return back()->with('error', 'Loan Not Found.....');
                    }
                }
            } else {
                return back()->with('error', 'Loan Month Not Found.....');
            }
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    // // Function For cancel transaction
    // public function cancel_loan_transaction($id)
    // {
    //     $loan_statement_data = DB::select('SELECT * FROM `loan_statement` WHERE `id`=?', [$id]);
    //     if (count($loan_statement_data) != 0) {
    //         foreach ($loan_statement_data as $stat_data) {
    //             $loan_id = $stat_data->loan_id;
    //             $customer_id = $stat_data->customer_id;
    //             $amount = $stat_data->amount;
    //             $satement_month_id = $stat_data->month_id;
    //         }
    //         $curr_month_loan_data = DB::select('SELECT * FROM `loan_monthly_status` WHERE `id`=?', [$satement_month_id]);
    //         $loan_data = DB::select('SELECT * FROM `loan` WHERE `id`=? AND `customer_id`=?', [$loan_id, $customer_id]);
    //         if ((count($curr_month_loan_data) != 0)) {
    //             foreach ($curr_month_loan_data as $curr_month_data) {
    //                 $month_id = $curr_month_data->id;
    //                 $prev_amount_of_loan_paid_off = $curr_month_data->amount_of_loan_paid_off;
    //                 $new_amount_of_loan_paid_off = $prev_amount_of_loan_paid_off - $amount;

    //                 $check = DB::update('UPDATE `loan_monthly_status` SET `amount_of_loan_paid_off`= ?,`interest`= ?,`is_interest_calculated`= ? WHERE id = ?', [$new_amount_of_loan_paid_off, 0, 0, $month_id]);
    //                 if ($check) {
    //                     $check = $this->re_calculate_monthly_pending_loan($customer_id, $loan_id);
    //                     if (!$check) {
    //                         return back()->with('error', 'Somethin Went Wrong.....');
    //                     }

    //                     foreach ($loan_data as $curr_loan_data) {
    //                         $new_pending = $curr_loan_data->pending_loan + $amount;
    //                     }
    //                     $check = DB::update('UPDATE `loan` SET `pending_loan`= ?,`interest`= ?, `is_interest_calculated`=? WHERE id = ?', [$new_pending, 0, 0, $loan_id]);
    //                     if ($check) {
    //                         $check = DB::delete('DELETE FROM `loan_statement` WHERE id = ?', [$id]);
    //                         if ($check) {
    //                             return back()->with('message', 'Transaction Cancel Sucessfully.....');
    //                         } else {
    //                             return back()->with('error', 'Something Went Wrong.....');
    //                         }
    //                     } else {
    //                         return back()->with('error', 'Something Went Wrong.....');
    //                     }
    //                 } else {
    //                     return back()->with('error', 'Something Went Wrong.....');
    //                 }
    //             }
    //         } else {
    //             return back()->with('error', 'Data Not Found One.....');
    //         }
    //     } else {
    //         return back()->with('error', 'Data Not Found Two.....');
    //     }
    // }

    // Function For cancel transaction
    public function cancel_loan_transaction($id)
    {
        try {
            $loan_statement_data = DB::select('SELECT * FROM `loan_statement` WHERE `id`=?', [$id]);
            if (count($loan_statement_data) != 0) {
                foreach ($loan_statement_data as $stat_data) {
                    $loan_id = $stat_data->loan_id;
                    $customer_id = $stat_data->customer_id;
                    $amount = $stat_data->amount;
                    $satement_month_id = $stat_data->month_id;

                    $curr_month_loan_data = DB::select('SELECT * FROM `loan_monthly_status` WHERE `id`=?', [$satement_month_id]);
                    $loan_data = DB::select('SELECT * FROM `loan` WHERE `id`=? AND `customer_id`=?', [$loan_id, $customer_id]);

                    if ((count($curr_month_loan_data) == 0) || (count($loan_data) == 0)) {
                        return back()->with('error', 'Data Not Found One.....');
                    }
                    DB::delete('DELETE FROM `loan_statement` WHERE id = ?', [$id]);
                    DB::update('UPDATE `loan_monthly_status` SET `amount_of_loan_paid_off` = `amount_of_loan_paid_off` - ? WHERE id = ?', [$amount, $satement_month_id]);
                    DB::update('UPDATE `loan` SET `pending_loan`= pending_loan + ? WHERE `id` = ?', [$amount, $loan_id]);
                    DB::update('UPDATE `customers` SET `is_loan_under_maintenance`= ? WHERE `id` = ?', [1, $customer_id]);
                    return back()->with('message', 'Transaction Cancel Sucessfully.....');
                }
            } else {
                return back()->with('error', 'Data Not Found Two.....');
            }
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    // Function For submit all loan
    public function submit_all_loan(Request $request, $id)
    {
        try {
            $con_pin = $request->input('con_pin');
            $shares_amount = $request->input('shares_amount');
            $total = $request->input('total_amount');

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
            $loan_data = DB::select('SELECT * FROM `loan` WHERE `id`=?', [$id]);
            if (count($loan_data) == 0) {
                return back()->with('error', 'Loan Data Not Found.....');
            }
            foreach ($loan_data as $curr_loan_data) {
                $customer_id = $curr_loan_data->customer_id;
                $status = $curr_loan_data->status;
                $agent_commission = $curr_loan_data->agent_commission;
            }
            $total = $total - $agent_commission;
            $customer_data = DB::select('SELECT * FROM `customers` WHERE `id`=?', [$customer_id]);
            if ($status == 0) {
                $last_month_loan_data = DB::select('SELECT * FROM `loan_monthly_status` WHERE `loan_id` = ? AND `customer_id` = ? ORDER BY id DESC LIMIT ?', [$id, $customer_id, 1]);
                if ((count($last_month_loan_data) != 0) && (count($customer_data) != 0)) {
                    foreach ($last_month_loan_data as $curr_loan_data) {
                        $amount_of_loan_paid_off = $curr_loan_data->amount_of_loan_paid_off;
                        $last_month_id = $curr_loan_data->id;
                    }
                    $new_amount_of_loan_paid_off = $amount_of_loan_paid_off + $total;
                    DB::update('UPDATE `loan_monthly_status` SET `pending_loan`= ?,`amount_of_loan_paid_off`= ?,`is_expire`= ? WHERE id = ?', [0, $new_amount_of_loan_paid_off, 1, $last_month_id]);
                    DB::update('UPDATE `loan` SET `pending_loan`=?,`status`=? WHERE id = ?', [0, 1, $id]);
                    $date = date("Y-m-d");
                    $time = Date("H:i:s");
                    $amount = $total + $shares_amount;
                    $loan_statement = array('loan_id' => $id, 'customer_id' => $customer_id, 'month_id' => $id, 'amount' => $amount, 'trans_date' => $date, 'trans_time' => $time,);
                    DB::table('loan_statement')->insert($loan_statement);
                    $loan_statement = array('loan_id' => $id, 'customer_id' => $customer_id, 'details' => "Agent Commission", 'amount' => $agent_commission, 'trans_date' => $date, 'trans_time' => $time,);
                    DB::table('loan_statement')->insert($loan_statement);
                    $request->session()->put('message', 'Loan Amount Collected Successfully.....');
                    return view('admin.pages.customer.loan.collect_all_loan', array('customer_data' => $customer_data, 'loan_data' =>  $loan_data));
                } else {
                    return back()->with('error', 'Monthly Loan Data Not Found.....');
                }
            } else {
                $request->session()->put('message', 'Allready Loan Is Collected.....');
                return view('admin.pages.customer.loan.collect_all_loan', array('customer_data' => $customer_data, 'loan_data' =>  $loan_data));
            }
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    public function get_previous_loans($id)
    {
        $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$id]);
        $prev_loan_data = DB::select('SELECT * FROM `loan` WHERE `customer_id`=? AND `status`=?', [$id, 1]);

        if ((count($customer_data) != 0) && (count($prev_loan_data) != 0)) {
            return view('admin.pages.customer.loan.previous_loans', array('customer_data' => $customer_data, 'sr' => 0, 'prev_loan_data' => $prev_loan_data));
        } else {
            return view('admin.pages.customer.loan.previous_loans', array('customer_data' => $customer_data, 'sr' => 0, 'prev_loan_data' => $prev_loan_data));
        }
    }

    public function remove_loan($loan_id)
    {
        try {
            $check = DB::delete('DELETE FROM `loan` WHERE id = ?', [$loan_id]);
            $check = DB::delete('DELETE FROM `loan_monthly_status` WHERE loan_id = ?', [$loan_id]);
            $check = DB::delete('DELETE FROM `loan_statement` WHERE loan_id = ?', [$loan_id]);

            return back()->with('message', 'Loan Removed Sucessfully.....');
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }
}
