<?php

namespace App\Http\Controllers\modification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;

class month_end_operation_controller extends Controller
{
    public function PrepairMonths()
    {
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
                    if (count($month_data) == 0) {
                        $save_cuur_month = array(
                            'customer_id' => $cuss_id,
                            'month_index' => $month_index,
                            'monthly_bachat_amount' => $monthly_bachat_amount,
                            'expected_bachat_total' => $expected_bachat_total,
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
    }
    public function CollectPendingAmountOnMonthEnd()
    {
        try {
            $today = Date('Y-m-d');
            $month_end_bachat_monthly_data = DB::select('SELECT * FROM `bachat_monthly` WHERE next_month_start_date = ?', [$today]);
            foreach ($month_end_bachat_monthly_data as $bachat_data) {
                $customer_data = DB::select('SELECT * FROM `customers` WHERE id = ?', [$bachat_data->customer_id]);
                $extra_collected_amount = $customer_data[0]->extra_amount;

                $pending_bachat_months_data = DB::select('SELECT * FROM `bachat_monthly` WHERE customer_id = ? AND is_received = ? AND is_expire = ? GROUP BY id ASC', [$bachat_data->customer_id, 0, 1]);
                $total_penalty_amount = 0;
                foreach ($pending_bachat_months_data as $month_data) {

                    $pending = $month_data->pending;
                    if ($extra_collected_amount <= $pending) {
                        DB::update('UPDATE `bachat_monthly` SET `is_received`= ?, `pending_amount_collected_on` = ? WHERE id = ?', [1, $today, $month_data->id]);

                        DB::update('UPDATE `customers` SET `extra_amount` = `extra_amount` - ? WHERE id =?', [$pending, $bachat_data->customer_id]);

                        $extra_collected_amount -= $pending;

                        $penalty = (($pending / 100) * 2);
                        $total_penalty_amount = $total_penalty_amount + $penalty;
                        DB::update('UPDATE `bachat_monthly` SET `penalty_amount`= penalty_amount + ?, `bachat_pending_months`= bachat_pending_months + ?, `has_the_penalty_been_calculated`=?, `penalty_calculate_up_to`=? WHERE id = ?', [$penalty, 1, 1, $today, $month_data->id]);
                    } else {
                        break;
                    }
                }
                DB::update('UPDATE `customers` SET `total_penalty_amount`= total_penalty_amount + ? WHERE id =?', [$total_penalty_amount, $bachat_data->customer_id]);
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }
    public function CalculatePaneltyOnMonthEnd()
    {
        try {
            $today = Date('Y-m-d');
            $month_end_bachat_data = DB::select('SELECT * FROM `bachat_monthly` WHERE next_month_start_date = ?', [$today]);
            foreach ($month_end_bachat_data as $bachat_data) {
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
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function CheckTime()
    {
        try {
            $todaydate = date('Y-m-d');
            $time_now = mktime(date('G'));

            $NowisTime = date('G:i', $time_now);
            // print_r($todaydate);
            print_r($NowisTime);
            if ($NowisTime == "20:54") {
                echo "OK";
            } else {
                echo "Not OK";
            }
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }
}
