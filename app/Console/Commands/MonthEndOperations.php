<?php

namespace App\Console\Commands;

use DateTime;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MonthEndOperations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:month_end_operations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'month end operations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            //Set Expired Account Status
            $today = Date('Y-m-d');
            DB::update('UPDATE `bachat_monthly` SET `is_expire`=? WHERE `is_expire`=? AND `next_month_start_date`<=?', [1, 0, $today]);

            //Create Mothly Data For New Account
            $customer_data = DB::select('SELECT * FROM customers WHERE `is_active` = ? AND `is_all_months_status_ready` = ? GROUP BY id ORDER BY id DESC LIMIT ?', [1, 0, 10]);
            if (count($customer_data) > 0) {
                foreach ($customer_data as $cuss_data) {
                    $cuss_id = $cuss_data->id;
                    $monthly_bachat_amount = $cuss_data->per_month_bachat;

                    $account_opening_date = $cuss_data->account_opening_date;
                    $account_expiry_date = $cuss_data->account_expiry_date;

                    $month_start_date = $account_opening_date;

                    $date = new DateTime($month_start_date);
                    $date->modify('+1 month');

                    $next_month_start_date = $date->format('Y-m-d');
                    $date->modify('-1 day');

                    $month_end_date = $date->format('Y-m-d');
                    $expected_bachat_total = $monthly_bachat_amount;
                    $month_index = 1;
                    while (1) {
                        $month_data = DB::select('SELECT * FROM bachat_monthly WHERE `customer_id` = ? AND `month_start_date` = ? AND `month_end_date` = ?', [$cuss_id, $month_start_date, $month_end_date]);
                        $pending = 0;
                        if ($month_start_date < $today) {
                            $pending = $monthly_bachat_amount;
                        }

                        if (count($month_data) == 0) {
                            $save_cuur_month = array(
                                'customer_id' => $cuss_id,
                                'month_index' => $month_index,
                                'monthly_bachat_amount' => $monthly_bachat_amount,
                                'expected_bachat_total' => $expected_bachat_total,
                                'pending' => $pending,
                                'month_start_date' => $month_start_date,
                                'month_end_date' => $month_end_date,
                                'next_month_start_date' => $next_month_start_date
                            );
                            DB::table('bachat_monthly')->insert($save_cuur_month);
                        }

                        if ($account_expiry_date <= $next_month_start_date) {
                            DB::update('UPDATE `customers` SET `is_all_months_status_ready`=?, `is_under_maintenance` = ? WHERE id = ?', [1, 0, $cuss_id]);
                            break;
                        } else {
                            $expected_bachat_total = $expected_bachat_total + $monthly_bachat_amount;
                            $month_index++;
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
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
        }

        // try {
        //     //Set Loan Months
        //     $today = Date('Y-m-d');
        //     $loan_data = DB::select('SELECT * FROM loan_monthly_status WHERE `pending_loan` > ? AND `is_expire` = ? AND `next_month_start_date` <= ?', [0, 0, $today]);
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
        //     }
        // } catch (Exception $e) {
        //     $exception_data = array('exception' => $e->getMessage());
        //     DB::table('failed_jobs')->insertGetId($exception_data);
        // }
        // try {
        //     DB::update('UPDATE `loan` SET `is_interest_calculated`=? WHERE `status`=? AND interest_calculated_date < ?', [0, 0, $today]);
        // } catch (Exception $e) {
        //     $exception_data = array('exception' => $e->getMessage());
        //     DB::table('failed_jobs')->insertGetId($exception_data);
        // }

        DB::update('UPDATE `bachat_monthly` SET `is_penalty_applicable`= ? WHERE `monthly_bachat_amount` > `credited` AND `is_expire` = ?', [1, 1]);


        //Clear account data if it is under maintainace
        $today = Date('Y-m-d');
        $CustomersDataList = DB::select('SELECT * FROM `customers` WHERE is_active = ? AND `is_under_maintenance` = ? GROUP BY id ASC LIMIT ?', [1, 1, 10]);
        try {

            foreach ($CustomersDataList as $Customer) {
                DB::update('UPDATE `customers` SET `balance`= ?,`extra_amount`= ?, `interest`=?, `interest_calculated_up_to`=?, `is_interest_calculated`=? WHERE `id` = ?', [0, 0, 0, null, 0, $Customer->id]);

                //Clean Pending Bachat And Penalty Details
                DB::update('UPDATE `bachat_monthly` SET is_received = ?, collection_upto_prev_month =?, `pending`=?, `interest`=?,`pending_amount_collected_on` = ?, `penalty_amount`=?, `bachat_pending_months` = ?, `is_penalty_applicable` = ?, `has_the_penalty_been_calculated` = ?, `has_the_penalty_been_collected` =?, `penalty_calculate_up_to` = ?, `penalty_collected_date` = ? WHERE `customer_id` = ?', [0, 0, 0, 0, null, 0, 0, 0, 0, 0, null, null, $Customer->id]);

                //calculate pending amount start
                DB::update('UPDATE `bachat_monthly` SET `pending`= `monthly_bachat_amount` - `credited` WHERE `is_expire` = ? AND `credited` <= `monthly_bachat_amount` AND customer_id = ?', [1, $Customer->id]);

                DB::update('UPDATE `bachat_monthly` SET `pending`= `monthly_bachat_amount` - `credited` WHERE `is_expire` = ? AND `month_start_date` <= ? AND `month_end_date` >= ? AND `credited` <= `monthly_bachat_amount` AND customer_id = ?', [0, $today, $today, $Customer->id]);

                DB::update('UPDATE `bachat_monthly` SET `pending`= 0 WHERE `is_expire` = ? AND `credited` >= `monthly_bachat_amount` AND customer_id = ?', [1, $Customer->id]);

                DB::update('UPDATE `bachat_monthly` SET `pending`= 0 WHERE `is_expire` = ? AND `month_start_date` <= ? AND `month_end_date` >= ? AND `credited` >= `monthly_bachat_amount` AND customer_id = ?', [0, $today, $today, $Customer->id]);
                //calculate pending amount end

                DB::update('UPDATE `bachat_monthly` SET `is_received` = ?  WHERE month_start_date <= ? AND `pending` <= ? AND `customer_id` = ?', [1, $today, 0, $Customer->id]);
                DB::update('UPDATE `bachat_monthly` SET `is_penalty_applicable` = ?  WHERE is_received = ? AND `month_start_date` <= ?', [1, 0, $today]);
            }
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
        }

        //Reset balance  amount
        try {
            foreach ($CustomersDataList as $Customer) {
                $bachat_months_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `customer_id`=? AND month_start_date <= ? GROUP BY id ASC', [$Customer->id, $today]);

                foreach ($bachat_months_data as $bachat_month) {
                    DB::select('UPDATE `customers` SET `balance`= `balance` + ? WHERE id =?', [$bachat_month->credited, $Customer->id]);
                }
            }
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
        }

        //Collect Pending Amount Using Extra Collected Amount 
        try {

            foreach ($CustomersDataList as $Customer) {
                $bachat_months_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `customer_id`=? AND `is_expire` = ? GROUP BY id ASC', [$Customer->id, 1]);

                foreach ($bachat_months_data as $bachat_month) {
                    $extra_amount = $bachat_month->credited - $bachat_month->monthly_bachat_amount;
                    if ($extra_amount >= 0) {
                        DB::select('UPDATE `customers` SET `extra_amount`= `extra_amount` + ? WHERE id =?', [$extra_amount, $Customer->id]);
                    }
                    $pending_bachat_months_data = DB::select('SELECT * FROM `bachat_monthly` WHERE customer_id = ? AND is_received = ? AND month_end_date < ? GROUP BY id ASC', [$Customer->id, 0, $bachat_month->next_month_start_date]);
                    foreach ($pending_bachat_months_data as $pending_bachat_month) {
                        $updated_customer_data = DB::select('SELECT extra_amount FROM `customers` WHERE `id` =?', [$Customer->id]);
                        $updated_extra_amount = $updated_customer_data[0]->extra_amount;

                        if ($pending_bachat_month->pending <= $updated_extra_amount) {
                            DB::update('UPDATE `bachat_monthly` SET `is_received`= ?, `pending_amount_collected_on` = ? WHERE id = ?', [1, $bachat_month->next_month_start_date, $pending_bachat_month->id]);

                            DB::update('UPDATE `customers` SET `extra_amount` = `extra_amount` - ? WHERE id =?', [$pending_bachat_month->pending, $Customer->id]);
                        } else {
                            break;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
        }

        //Calculate Penalty
        try {

            foreach ($CustomersDataList as $Customer) {
                $pending_bachat_months_data = DB::select('SELECT * FROM `bachat_monthly` WHERE customer_id = ? AND is_penalty_applicable = ? AND has_the_penalty_been_calculated = ?', [$Customer->id, 1, 0]);

                $total_penalty_amount = 0;

                foreach ($pending_bachat_months_data as $months_data) {
                    $pending = $months_data->pending;
                    $bachat_pending_months = $this->CalculateMonths($months_data->month_end_date, $months_data->pending_amount_collected_on);

                    $penalty = ((($pending / 100) * 2) * $bachat_pending_months);
                    $total_penalty_amount = $total_penalty_amount + $penalty;

                    $penalty_calculate_up_to = $months_data->pending_amount_collected_on;

                    if ($months_data->pending_amount_collected_on == null) {
                        $penalty_calculate_up_to = $today;
                    }
                    DB::update('UPDATE `bachat_monthly` SET `penalty_amount`= ?, `bachat_pending_months`= ?, `has_the_penalty_been_calculated`=?, `penalty_calculate_up_to`=? WHERE id = ?', [$penalty, $bachat_pending_months, 1, $penalty_calculate_up_to, $months_data->id]);
                }
                DB::update('UPDATE `customers` SET `total_penalty_amount`= ? WHERE id =?', [$total_penalty_amount, $Customer->id]);
            }
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
        }


        // Reset bachat month closing collection
        try {
            foreach ($CustomersDataList as $Customer) {
                $bachat_months_data = DB::select('SELECT * FROM `bachat_monthly` WHERE customer_id = ? AND next_month_start_date <= ?', [$Customer->id, $today]);
                $total_bachat = 0;
                foreach ($bachat_months_data as $months_data) {
                    DB::update('UPDATE `bachat_monthly` SET `collection_upto_prev_month`= ? WHERE id = ?', [$total_bachat, $months_data->id]);
                    $total_bachat += $months_data->credited;
                }
            }
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
        }

        // Update under maintenance status
        try {
            foreach ($CustomersDataList as $Customer) {
                DB::update('UPDATE `customers` SET `is_under_maintenance` = ? WHERE id =?', [0, $Customer->id]);
            }
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
        }

        // Month End Pending Amount Collection From Extra Amont AND Penalty Calculation
        // $time_now = mktime(date('G'));
        // $NowisTime = date('G:i', $time_now);
        // if ($NowisTime >= "18:00" && $NowisTime <= "18:10") {
        // }
        try {
            $today = Date('Y-m-d');
            $month_end_bachat_monthly_data = DB::select('SELECT * FROM `bachat_monthly` WHERE next_month_start_date = ?', [$today]);
            foreach ($month_end_bachat_monthly_data as $bachat_data) {
                $customer_data = DB::select('SELECT * FROM `customers` WHERE id = ? AND is_under_maintenance=?', [$bachat_data->customer_id, 0]);
                if (count($customer_data) > 0) {
                    $extra_collected_amount = $customer_data[0]->extra_amount;

                    $pending_bachat_months_data = DB::select('SELECT * FROM `bachat_monthly` WHERE customer_id = ? AND is_received = ? AND is_expire = ? GROUP BY id ASC', [$bachat_data->customer_id, 0, 1]);
                    $total_penalty_amount = 0;
                    foreach ($pending_bachat_months_data as $month_data) {

                        $pending = $month_data->pending;
                        if ($extra_collected_amount >= $pending) {
                            DB::update('UPDATE `bachat_monthly` SET `is_received`= ?, `pending_amount_collected_on` = ? WHERE id = ?', [1, $today, $month_data->id]);

                            DB::update('UPDATE `customers` SET `extra_amount` = `extra_amount` - ? WHERE id =?', [$pending, $bachat_data->customer_id]);

                            $penalty = (($pending / 100) * 2);
                            $total_penalty_amount = $total_penalty_amount + $penalty;
                            DB::update('UPDATE `bachat_monthly` SET `penalty_amount`= penalty_amount + ?, `bachat_pending_months`= bachat_pending_months + ?, `has_the_penalty_been_calculated`=?, `penalty_calculate_up_to`=? WHERE id = ?', [$penalty, 1, 1, $today, $month_data->id]);
                        } else {
                            break;
                        }
                    }
                    DB::update('UPDATE `customers` SET `total_penalty_amount`= total_penalty_amount + ? WHERE id =?', [$total_penalty_amount, $bachat_data->customer_id]);
                }
            }
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
        }

        //Month end closing amount updation
        try {
            $today = Date('Y-m-d');
            $month_end_bachat_data = DB::select('SELECT * FROM `bachat_monthly` WHERE next_month_start_date = ?', [$today]);
            foreach ($month_end_bachat_data as $bachat_data) {
                $total_month_closing = $bachat_data->collection_upto_prev_month + $bachat_data->credited;
                DB::update('UPDATE `bachat_monthly` SET `collection_upto_prev_month`= ?, `pending`= `monthly_bachat_amount` WHERE month_start_date = ? AND customer_id =?', [$total_month_closing, $today, $bachat_data->customer_id]);
            }
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
        }

        //Month End Penalty Calculation
        try {
            $today = Date('Y-m-d');
            $month_end_bachat_data = DB::select('SELECT * FROM `bachat_monthly` WHERE next_month_start_date = ?', [$today]);
            foreach ($month_end_bachat_data as $bachat_data) {
                $customer_data = DB::select('SELECT * FROM `customers` WHERE id = ? AND is_under_maintenance=?', [$bachat_data->customer_id, 0]);
                if (count($customer_data) > 0) {
                    $pending_bachat_months_data = DB::select('SELECT * FROM `bachat_monthly` WHERE customer_id = ? AND is_penalty_applicable = ? AND pending_amount_collected_on=? AND (penalty_calculate_up_to = ? OR penalty_calculate_up_to < ?)', [$bachat_data->customer_id, 1, null, null, $today]);
                    $total_penalty_amount = 0;
                    foreach ($pending_bachat_months_data as $month_data) {
                        $pending = $month_data->pending;
                        $penalty = (($pending / 100) * 2);
                        $total_penalty_amount = $total_penalty_amount + $penalty;
                        DB::update('UPDATE `bachat_monthly` SET `penalty_amount`= penalty_amount + ?, `bachat_pending_months`= bachat_pending_months + ?, `has_the_penalty_been_calculated`=?, `penalty_calculate_up_to`=? WHERE id = ?', [$penalty, 1, 1, $today, $month_data->id]);
                    }
                    DB::update('UPDATE `customers` SET `total_penalty_amount`= total_penalty_amount + ? WHERE id =?', [$total_penalty_amount, $bachat_data->customer_id]);
                }
            }
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
        }

        // //Clear loan data if it is under maintainace
        $today = Date('Y-m-d');
        $CustomersDataList = DB::select('SELECT * FROM `customers` WHERE `is_active` = ? AND `is_loan_under_maintenance` = ? GROUP BY id ASC LIMIT ?', [1, 1, 10]);
        try {
            foreach ($CustomersDataList as $Customer) {
                $LoanData = DB::select('SELECT * FROM `loan` WHERE `customer_id` = ? AND status = ?', [$Customer->id, 0]);

                foreach ($LoanData as $Loan) {
                    DB::update('UPDATE `loan` SET `pending_loan`= ?,`completed_months`= ?, `extra_days`=?, `interest`=?, `interest_calculated_up_to`=?, `is_interest_calculated`=?, `interest_calculated_date`=? WHERE `id` = ?', [0, 0, 0, 0, null, 0, null, $Loan->id]);
                    $pending_loan = $Loan->amount;
                    $total_paid_amount = 0;
                    $LoanMonthlyData = DB::select('SELECT * FROM `loan_monthly_status` WHERE loan_id = ? AND `is_expire` = ?', [$Loan->id, 0]);
                    foreach ($LoanMonthlyData as $LoanMonth) {
                        DB::update('UPDATE `loan_monthly_status` SET `pending_loan`= ? WHERE `id` = ?', [$pending_loan, $LoanMonth->id]);

                        $total_paid_amount = $total_paid_amount + $LoanMonth->amount_of_loan_paid_off;
                        $pending_loan = ($pending_loan >= $total_paid_amount) ? ($pending_loan - $total_paid_amount) : 0;
                    }
                    DB::update('UPDATE `loan` SET `pending_loan`= ? WHERE `id` = ?', [$pending_loan, $Loan->id]);
                }
                DB::update('UPDATE `customers` SET `is_loan_under_maintenance`= ? WHERE `id` = ?', [0, $Customer->id]);
            }
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
        }
        // /usr/local/bin/php /home/zb81qp0pk3rn/admin/artisan schedule:run
        // /usr/local/bin/php /home/upfq7dzpabro/public_html/Jagtapbachatgat/artisan schedule:run

    }
    public function CalculateMonths($from, $to)
    {
        try {
            $date1 = $from;
            $date2 = $to;
            $d1 = new DateTime($date2);
            $d2 = new DateTime($date1);
            $diff = $d2->diff($d1);
            return (($diff->y) * 12) + ($diff->m);
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }
}
