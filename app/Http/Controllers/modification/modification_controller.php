<?php

namespace App\Http\Controllers\modification;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class modification_controller extends Controller
{
    public function modify_customer_acc_dates()
    {
        $dates = DB::select('SELECT `id`,`acc_open_date`, `acc_expire_date` FROM `customers`');
        foreach ($dates as $dates_data) {
            $cuss_id = $dates_data->id;

            $acc_open_date = $dates_data->acc_open_date;
            $date = new DateTime($acc_open_date);
            $acc_open_date = $date->format('Y-m-d');

            $acc_expire_date = $dates_data->acc_expire_date;
            $date = new DateTime($acc_expire_date);
            $acc_expire_date = $date->format('Y-m-d');


            $check = DB::update('UPDATE `customers` SET `account_opening_date`= ?, `account_expiry_date`= ? WHERE id = ?', [$acc_open_date, $acc_expire_date, $cuss_id]);
        }
        return response()->json(['status' => 'sucess', 'statuscode' => 200, 'message' => 'Checking.', 'date' => $dates], 200);
    }

    public function modify_monthly_bachat_dates()
    {
        $dates = DB::select('SELECT `id`,`start_date`, `end_date`,`new_month_start_date` FROM `bachat_monthly`');
        foreach ($dates as $dates_data) {
            $month_id = $dates_data->id;

            $start_date = $dates_data->start_date;
            $date = new DateTime($start_date);
            $start_date = $date->format('Y-m-d');

            $end_date = $dates_data->end_date;
            $date = new DateTime($end_date);
            $end_date = $date->format('Y-m-d');

            $new_month_start_date = $dates_data->new_month_start_date;
            $date = new DateTime($new_month_start_date);
            $new_month_start_date = $date->format('Y-m-d');
            $check = DB::update('UPDATE `bachat_monthly` SET `month_start_date`= ?, `month_end_date`= ?, `next_month_start_date`= ? WHERE id = ?', [$start_date, $end_date, $new_month_start_date, $month_id]);
        }
        return response()->json(['status' => 'sucess', 'statuscode' => 200, 'message' => 'Checking.', 'data' => $dates, 'start_date' => $start_date, 'end_date' => $end_date, 'new_month_start_date' => $new_month_start_date], 200);
    }

    public function modify_bachat_statement_date_time()
    {
        $dates = DB::select('SELECT `id`,`date`,`time` FROM `statement`');
        foreach ($dates as $dates_data) {
            $statement_id = $dates_data->id;

            $tran_date = $dates_data->date;
            $date = new DateTime($tran_date);
            $tran_date = $date->format('Y-m-d');

            $time = $dates_data->time;
            $check = DB::update('UPDATE `statement` SET `trans_date`= ?, `trans_time`= ? WHERE id = ?', [$tran_date, $time, $statement_id]);
        }
        return response()->json(['status' => 'sucess', 'statuscode' => 200, 'message' => 'Checking.', 'data' => $dates], 200);
    }


    public function modify_loans_dates()
    {
        $dates = DB::select('SELECT `id`,`start_date`, `end_date` FROM `loan`');
        foreach ($dates as $dates_data) {
            $loan_id = $dates_data->id;

            $start_date = $dates_data->start_date;
            $date = new DateTime($start_date);
            $start_date = $date->format('Y-m-d');

            $end_date = $dates_data->end_date;
            $date = new DateTime($end_date);
            $end_date = $date->format('Y-m-d');


            $check = DB::update('UPDATE `loan` SET `loan_start_date`= ?, `loan_end_date`= ? WHERE id = ?', [$start_date, $end_date, $loan_id]);
        }
        return response()->json(['status' => 'sucess', 'statuscode' => 200, 'message' => 'Checking.', 'date' => $dates], 200);
    }
    public function modify_monthly_loan_dates()
    {
        $dates = DB::select('SELECT `id`,`start_date`, `end_date`,`new_month_start_date` FROM `loan_monthly_status`');
        foreach ($dates as $dates_data) {
            $month_id = $dates_data->id;

            $start_date = $dates_data->start_date;
            $date = new DateTime($start_date);
            $start_date = $date->format('Y-m-d');

            $end_date = $dates_data->end_date;
            $date = new DateTime($end_date);
            $end_date = $date->format('Y-m-d');

            $new_month_start_date = $dates_data->new_month_start_date;
            $date = new DateTime($new_month_start_date);
            $new_month_start_date = $date->format('Y-m-d');
            $check = DB::update('UPDATE `loan_monthly_status` SET `month_start_date`= ?, `month_end_date`= ?, `next_month_start_date`= ? WHERE id = ?', [$start_date, $end_date, $new_month_start_date, $month_id]);
        }
        return response()->json(['status' => 'sucess', 'statuscode' => 200, 'message' => 'Checking.', 'data' => $dates, 'start_date' => $start_date, 'end_date' => $end_date, 'new_month_start_date' => $new_month_start_date], 200);
    }

    public function add_loan_id_in_monthly_loan_dates()
    {
        $all_loan_data = DB::select('SELECT `id`,`customer_id`,`loan_no` FROM `loan`');
        foreach ($all_loan_data as $loan_data) {
            $loan_id = $loan_data->id;
            $customer_id = $loan_data->customer_id;
            $loan_no = $loan_data->loan_no;
            $check = DB::update('UPDATE `loan_monthly_status` SET `loan_id`= ? WHERE customer_id = ? AND loan_no = ?', [$loan_id, $customer_id, $loan_no]);
        }
        return response()->json(['status' => 'sucess', 'statuscode' => 200, 'message' => 'Checking.', 'data' => $all_loan_data], 200);
    }

    public function add_loan_id_in_loan_statement()
    {
        $all_loan_data = DB::select('SELECT `id`,`customer_id`,`loan_no` FROM `loan`');
        foreach ($all_loan_data as $loan_data) {
            $loan_id = $loan_data->id;
            $customer_id = $loan_data->customer_id;
            $loan_no = $loan_data->loan_no;
            $check = DB::update('UPDATE `loan_statement` SET `loan_id`= ? WHERE customer_id = ? AND loan_no = ?', [$loan_id, $customer_id, $loan_no]);
        }
        return response()->json(['status' => 'sucess', 'statuscode' => 200, 'message' => 'Checking.', 'data' => $all_loan_data], 200);
    }

    public function modify_loan_statement_date_time()
    {
        $dates = DB::select('SELECT `id`,`date`,`time` FROM `loan_statement`');
        foreach ($dates as $dates_data) {
            $statement_id = $dates_data->id;

            $tran_date = $dates_data->date;
            $date = new DateTime($tran_date);
            $tran_date = $date->format('Y-m-d');

            $time = $dates_data->time;
            $check = DB::update('UPDATE `loan_statement` SET `trans_date`= ?, `trans_time`= ? WHERE id = ?', [$tran_date, $time, $statement_id]);
        }
        return response()->json(['status' => 'sucess', 'statuscode' => 200, 'message' => 'Checking.', 'data' => $dates], 200);
    }

    public function month_end_operation_for_bachat()
    {

        $today = Date('Y-m-d');
        DB::update('UPDATE `bachat_monthly` SET `is_expire`=? WHERE `is_expire`=? AND `next_month_start_date`<=?', [1, 0, $today]);
    }
    public function create_month_of_bachat_status()
    {
        try {
            echo "checking";

            $customer_data = DB::select('SELECT * FROM customers WHERE `is_active` = ? AND `is_all_months_status_ready` = ? GROUP BY id ORDER BY id DESC LIMIT ?', [1, 0, 1]);
            if (count($customer_data) > 0) {

                foreach ($customer_data as $cuss_data) {
                    $cuss_id = $cuss_data->id;
                    $account_no = $cuss_data->acc_no;
                    $monthly_bachat_amount = $cuss_data->per_month_bachat;

                    $account_opening_date = $cuss_data->account_opening_date;
                    $account_expiry_date = $cuss_data->account_expiry_date;

                    $month_start_date = $account_opening_date;

                    $date = new DateTime($month_start_date);
                    $date->modify('+1 month');

                    $next_month_start_date = $date->format('Y-m-d');
                    $date->modify('-1 day');

                    $month_end_date = $date->format('Y-m-d');

                    while (1) {
                        $month_data = DB::select('SELECT * FROM bachat_monthly WHERE `customer_id` = ? AND `month_start_date` = ? AND `month_end_date` = ?', [$cuss_id, $month_start_date, $month_end_date]);
                        if (count($month_data) == 0) {
                            $save_cuur_month = array(
                                'customer_id' => $cuss_id,
                                'monthly_bachat_amount' => $monthly_bachat_amount,
                                'pending' => $monthly_bachat_amount,
                                'month_start_date' => $month_start_date,
                                'month_end_date' => $month_end_date,
                                'next_month_start_date' => $next_month_start_date
                            );
                            DB::table('bachat_monthly')->insert($save_cuur_month);
                        }

                        if ($account_expiry_date <= $next_month_start_date) {
                            DB::update('UPDATE `customers` SET `is_all_months_status_ready`=? WHERE id = ?', [1, $cuss_id]);
                            break;
                        }

                        $month_start_date = $next_month_start_date;

                        $date = new DateTime($month_start_date);
                        $date->modify('+1 month');

                        $next_month_start_date = $date->format('Y-m-d');
                        $date->modify('-1 day');

                        $month_end_date = $date->format('Y-m-d');
                    }
                }
            }

            return response()->json(['status' => 'sucess', 'statuscode' => 200, 'message' => 'Checking.', 'data' => $customer_data], 200);
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    public function create_month_of_loan_status()
    {
        echo "checking";
        $today = Date('Y-m-d');
        $loan_data = DB::select('SELECT * FROM loan_monthly_status WHERE `pending_loan` > ? AND `is_expire` = ? AND `next_month_start_date` <= ?', [0, 0, $today]);
        if (count($loan_data) > 0) {

            foreach ($loan_data as $data) {

                $month_start_date = $data->next_month_start_date;
                $date = new DateTime($month_start_date);
                $date->modify('+1 month');

                $next_month_start_date = $date->format('Y-m-d');
                $date->modify('-1 day');

                $month_end_date = $date->format('Y-m-d');

                $save_cuur_month = array(
                    'loan_id' => $data->loan_id,
                    'customer_id' => $data->customer_id,
                    'account_no' => $data->account_no,
                    'loan_amount' => $data->loan_amount,
                    'pending_loan' => $data->pending_loan,
                    'month_start_date' => $month_start_date,
                    'month_end_date' => $month_end_date,
                    'next_month_start_date' => $next_month_start_date,
                );
                DB::table('loan_monthly_status')->insertGetId($save_cuur_month);
                DB::update('UPDATE `loan_monthly_status` SET `is_expire`=? WHERE id = ?', [1, $data->id]);
            }
        }

        return response()->json(['status' => 'sucess', 'statuscode' => 200, 'message' => 'Checking.', 'data' => $loan_data], 200);
    }

    public function check_interest_calculated_date()
    {
        $today = Date('Y-m-d');
        $loan_data = DB::update('UPDATE `loan` SET `is_interest_calculated`=? WHERE `status`=? AND `interest_calculated_date` < ?', [0, 0, $today]);

        return response()->json(['status' => 'sucess', 'statuscode' => 200, 'message' => 'Checking.', 'data' => $loan_data], 200);
    }

    public function calculated_pending_penalty()
    {
        $customer_data = DB::select('SELECT * FROM customers WHERE `is_active` = ? AND `is_all_months_status_ready` = ?', [1, 1]);

        $today = Date('Y-m-d');
        if (count($customer_data) > 0) {


            foreach ($customer_data as $cuss_data) {
                $pending_months_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `customer_id` =? AND `is_received` = ? AND `next_month_start_date` <= ? AND `is_expire` = ? AND `is_penalty_applicable` = ? AND `has_the_penalty_been_calculated` = ? ', [$cuss_data->id, 0, $today, 1, 1, 0]);
                $penalty_amount = 0;
                foreach ($pending_months_data as $months_data) {
                    $pending = $months_data->pending;
                    $month_end_date = $months_data->month_end_date;
                    $pending_months = 0;

                    $date = new DateTime($month_end_date);
                    $date->modify('+1 month');
                    $month_end_date = $date->format('Y-m-d');

                    while ($month_end_date < $today) {
                        $pending_months++;
                        $date = new DateTime($month_end_date);
                        $date->modify('+1 month');
                        $month_end_date = $date->format('Y-m-d');
                    }

                    if ($pending_months > 0) {
                        $penalty = ((($pending / 100) * 2) * $pending_months);
                        $penalty_amount = $penalty_amount + $penalty;

                        $check = DB::update('UPDATE `bachat_monthly` SET `penalty_amount`=?, `bachat_pending_months`=?, `has_the_penalty_been_calculated`=?, `penalty_calculate_up_to`=? WHERE id = ?', [$penalty, $pending_months, 1, $today, $months_data->id]);
                    }
                }
                DB::update('UPDATE `customers` SET `total_penalty_amount`= ? WHERE id =?', [$penalty_amount, $cuss_data->id]);
            }
        }
    }

    public function calculated_penalty_regurarly()
    {
        $today = Date('Y-m-d');
        $month_end_data = DB::select('SELECT * FROM `next_month_start_date` = ?', [$today]);
        if (count($month_end_data) > 0) {
            foreach ($month_end_data as $month_data) {
                $pending_months_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `customer_id`=? AND `is_received` = ? AND `is_expire` = ? AND `is_penalty_applicable` = ? AND `penalty_calculate_up_to` < ?', [$month_data->customer_id, 0, 1, 1, $today]);

                $total_penalty_amount = 0;
                foreach ($pending_months_data as $months_data) {
                    $pending = $months_data->pending;
                    $penalty = (($pending / 100) * 2);
                    $total_penalty_amount = $total_penalty_amount + $penalty;
                    $check = DB::update('UPDATE `bachat_monthly` SET `penalty_amount`= `penalty_amount` + ?, `bachat_pending_months`= `bachat_pending_months` + ?, `has_the_penalty_been_calculated`=?, `penalty_calculate_up_to`=? WHERE id = ?', [$penalty, 1, 1, $today, $months_data->id]);
                }
                DB::update('UPDATE `customers` SET `total_penalty_amount`= `total_penalty_amount` + ? WHERE id =?', [$total_penalty_amount, $month_data->customer_id]);
            }
        }
    }

    public function CleanPendingBachatData()
    {
        try {
            DB::update('UPDATE `bachat_monthly` SET `pending`= ?, `is_received`= ?, `is_expire`=?, `penalty_amount`=?, `bachat_pending_months` = ?, `is_penalty_applicable` = ?, `has_the_penalty_been_calculated` = ?, `has_the_penalty_been_collected` =?, `penalty_calculate_up_to` = ?, `penalty_collected_date` = ?', [0, 0, 0, 0, 0, 0, 0, 0, null, null]);

            $today = Date('Y-m-d');
            DB::update('UPDATE `bachat_monthly` SET `is_expire`=? WHERE `month_end_date` < ?', [1, $today]);
            DB::update('UPDATE `bachat_monthly` SET `is_received`= ? WHERE monthly_bachat_amount <= credited', [1]);
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }
    public function ResetPendingBachatDataForExpiredMonths()
    {
        try {
            $bachat_monthly_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `is_expire` = ? AND `is_received` = ?', [1, 0]);
            if (count($bachat_monthly_data) > 0) {
                foreach ($bachat_monthly_data as $bachat_month) {
                    DB::update('UPDATE `bachat_monthly` SET `pending`= ? WHERE id = ?', [$bachat_month->monthly_bachat_amount - $bachat_month->credited, $bachat_month->id]);
                }
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function ResetPendingBachatDataForNonExpiredMonths()
    {
        try {

            $bachat_monthly_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `is_expire` = ?', [0]);
            if (count($bachat_monthly_data) > 0) {
                foreach ($bachat_monthly_data as $bachat_month) {
                    DB::update('UPDATE `bachat_monthly` SET `pending`= ? WHERE id = ?', [$bachat_month->monthly_bachat_amount - $bachat_month->credited, $bachat_month->id]);
                }
            }
            DB::update('UPDATE `bachat_monthly` SET `is_penalty_applicable`= ? WHERE pending > ?', [1, 0]);
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function CollectPendingBachatAmount()
    {
        try {
            // DB::select('UPDATE `customers` SET `extra_amount`= ? WHERE id =?', [0, 1]);
            // $updated_customer_data = DB::select('SELECT extra_amount FROM `customers` WHERE is_active = ?', [1]);
            // $updated_extra_amount = $updated_customer_data[0]->extra_amount;
            // print_r("Updated Extra Amount:");
            // print_r($updated_extra_amount);
            // print_r("\n");
            // die();
            $CustomerList = DB::select('SELECT * FROM `customers` WHERE id = ? AND is_active = ?', [1, 1]);
            if (count($CustomerList) > 0) {
                foreach ($CustomerList as $Customer) {

                    $bachat_months_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `customer_id`=? AND `is_expire` = ? GROUP BY id ASC', [$Customer->id, 1]);

                    foreach ($bachat_months_data as $bachat_month) {
                        $extra_amount = $bachat_month->credited - $bachat_month->monthly_bachat_amount;
                        if ($extra_amount > 0) {
                            DB::select('UPDATE `customers` SET `extra_amount`= `extra_amount` + ? WHERE id =?', [$extra_amount, $Customer->id]);
                        }
                        $pending_bachat_months_data = DB::select('SELECT * FROM `bachat_monthly` WHERE customer_id = ? AND is_received = ? AND is_expire = ? AND month_end_date <= ? GROUP BY id ASC', [$Customer->id, 0, 1, $bachat_month->month_end_date]);
                        if (count($pending_bachat_months_data) > 0) {
                            foreach ($pending_bachat_months_data as $pending_bachat_month) {
                                $updated_customer_data = DB::select('SELECT extra_amount FROM `customers` WHERE is_active = ?', [1]);
                                $updated_extra_amount = $updated_customer_data[0]->extra_amount;

                                if ($pending_bachat_month->pending <= $updated_extra_amount) {
                                    print_r("Updated Extra Amount:");
                                    print_r($updated_extra_amount);
                                    print_r("\n");
                                    DB::update('UPDATE `bachat_monthly` SET `is_received`= ?, `pending_amount_collected_on` = ? WHERE id = ?', [1, $bachat_month->month_end_date, $pending_bachat_month->id]);

                                    DB::update('UPDATE `customers` SET `extra_amount` = `extra_amount` - ? WHERE id =?', [$pending_bachat_month->pending, $Customer->id]);
                                    print_r("ACPA Extra Amount:");
                                    print_r($updated_extra_amount - $pending_bachat_month->pending);
                                    print_r("-------------------------------");
                                } else {
                                    break;
                                }
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function CalculatePanelty()
    {
        try {
            $CustomerList = DB::select('SELECT * FROM `customers` WHERE id = ? AND is_active = ?', [1, 1]);
            if (count($CustomerList) > 0) {
                foreach ($CustomerList as $Customer) {
                    $pending_bachat_months_data = DB::select('SELECT * FROM `bachat_monthly` WHERE customer_id = ? AND is_penalty_applicable = ? AND has_the_penalty_been_calculated = ?', [$Customer->id, 1, 0]);
                    if (count($pending_bachat_months_data) > 0) {
                        $total_penalty_amount = 0;
                        $today = Date('Y-m-d');
                        foreach ($pending_bachat_months_data as $months_data) {
                            $pending = $months_data->pending;
                            $bachat_pending_months = $this->CalculateMonths($months_data->month_start_date, $months_data->pending_amount_collected_on);
                            $penalty = ((($pending / 100) * 2) * $bachat_pending_months);
                            $total_penalty_amount = $total_penalty_amount + $penalty;
                            DB::update('UPDATE `bachat_monthly` SET `penalty_amount`= ?, `bachat_pending_months`= ?, `has_the_penalty_been_calculated`=?, `penalty_calculate_up_to`=? WHERE id = ?', [$penalty, $bachat_pending_months, 1, $months_data->pending_amount_collected_on, $months_data->id]);
                        }
                        DB::update('UPDATE `customers` SET `total_penalty_amount`= + ? WHERE id =?', [$total_penalty_amount, $Customer->id]);
                    }
                }
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }


    public function CalculateMonths($from, $to)
    {
        try {
            $date1 = $from;
            $date2 = $to;
            $d1 = new DateTime($date2);
            $d2 = new DateTime($date1);
            $diff = $d2->diff($d1);
            return (($diff->y) * 12) + ($diff->m) + 1;
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }
}
