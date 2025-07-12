<?php

namespace App\Http\Controllers\modification;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;

class PendingBachatMaintenance extends Controller
{
    public function CalculatePendingBachat()
    {
        try {
            DB::update('UPDATE `bachat_monthly` SET `pending`= `monthly_bachat_amount` - `credited` WHERE `is_expire` = 1 AND `credited` < `monthly_bachat_amount`');

            $today = Date('Y-m-d');
            DB::update('UPDATE `bachat_monthly` SET `pending`= `monthly_bachat_amount` - `credited` WHERE `is_expire` = ? AND `month_start_date` <= ? AND `month_end_date` >= ? AND `credited` < `monthly_bachat_amount`', [0, $today, $today]);

            DB::update('UPDATE `bachat_monthly` SET `pending`= ? WHERE `is_expire` = ? AND `month_start_date` <= ? AND `month_end_date` >= ? AND `credited` >= `monthly_bachat_amount`', [0, 0, $today, $today]);

            DB::update('UPDATE `bachat_monthly` SET `pending`= 0 WHERE `is_expire` = 1 AND `credited` >= `monthly_bachat_amount`');
        } catch (Exception $e) {
            print_r($e->getMessage());
        }
    }
    public function PendingBachatMaintenanceOperation()
    {
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
                    if ($extra_amount > 0) {
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
    }
    // public function ResetPendingBachatDataForExpiredMonths()
    // {
    //     try {
    //         $bachat_monthly_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `is_expire` = ? AND `is_received` = ?', [1, 0]);
    //         if (count($bachat_monthly_data) > 0) {
    //             foreach ($bachat_monthly_data as $bachat_month) {
    //                 DB::update('UPDATE `bachat_monthly` SET `pending`= ? WHERE id = ?', [$bachat_month->monthly_bachat_amount - $bachat_month->credited, $bachat_month->id]);
    //             }
    //         }
    //     } catch (Exception $e) {
    //         print_r($e->getMessage());
    //     }
    // }

    // public function ResetPendingBachatDataForNonExpiredMonths()
    // {
    //     try {

    //         $bachat_monthly_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `is_expire` = ?', [0]);
    //         if (count($bachat_monthly_data) > 0) {
    //             foreach ($bachat_monthly_data as $bachat_month) {
    //                 DB::update('UPDATE `bachat_monthly` SET `pending`= ? WHERE id = ?', [$bachat_month->monthly_bachat_amount - $bachat_month->credited, $bachat_month->id]);
    //             }
    //         }
    //         DB::update('UPDATE `bachat_monthly` SET `is_penalty_applicable`= ? WHERE pending > ?', [1, 0]);
    //     } catch (Exception $e) {
    //         print_r($e->getMessage());
    //     }
    // }

    // public function CollectPendingBachatAmount()
    // {
    //     try {
    //         // DB::select('UPDATE `customers` SET `extra_amount`= ? WHERE id =?', [0, 1]);
    //         // $updated_customer_data = DB::select('SELECT extra_amount FROM `customers` WHERE is_active = ?', [1]);
    //         // $updated_extra_amount = $updated_customer_data[0]->extra_amount;
    //         // print_r("Updated Extra Amount:");
    //         // print_r($updated_extra_amount);
    //         // print_r("\n");
    //         // die();
    //         $CustomerList = DB::select('SELECT * FROM `customers` WHERE id = ? AND is_active = ?', [1, 1]);
    //         if (count($CustomerList) > 0) {
    //             foreach ($CustomerList as $Customer) {

    //                 $bachat_months_data = DB::select('SELECT * FROM `bachat_monthly` WHERE `customer_id`=? AND `is_expire` = ? GROUP BY id ASC', [$Customer->id, 1]);

    //                 foreach ($bachat_months_data as $bachat_month) {
    //                     $extra_amount = $bachat_month->credited - $bachat_month->monthly_bachat_amount;
    //                     if ($extra_amount > 0) {
    //                         DB::select('UPDATE `customers` SET `extra_amount`= `extra_amount` + ? WHERE id =?', [$extra_amount, $Customer->id]);
    //                     }
    //                     $pending_bachat_months_data = DB::select('SELECT * FROM `bachat_monthly` WHERE customer_id = ? AND is_received = ? AND is_expire = ? AND month_end_date <= ? GROUP BY id ASC', [$Customer->id, 0, 1, $bachat_month->month_end_date]);
    //                     if (count($pending_bachat_months_data) > 0) {
    //                         foreach ($pending_bachat_months_data as $pending_bachat_month) {
    //                             $updated_customer_data = DB::select('SELECT extra_amount FROM `customers` WHERE is_active = ?', [1]);
    //                             $updated_extra_amount = $updated_customer_data[0]->extra_amount;

    //                             if ($pending_bachat_month->pending <= $updated_extra_amount) {
    //                                 DB::update('UPDATE `bachat_monthly` SET `is_received`= ?, `pending_amount_collected_on` = ? WHERE id = ?', [1, $bachat_month->month_end_date, $pending_bachat_month->id]);

    //                                 DB::update('UPDATE `customers` SET `extra_amount` = `extra_amount` - ? WHERE id =?', [$pending_bachat_month->pending, $Customer->id]);
    //                                 print_r("ACPA Extra Amount:");
    //                                 print_r($updated_extra_amount - $pending_bachat_month->pending);
    //                                 print_r("-------------------------------");
    //                             } else {
    //                                 break;
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     } catch (Exception $e) {
    //         print_r($e->getMessage());
    //     }
    // }

    // public function CalculatePanelty()
    // {
    //     try {
    //         $CustomerList = DB::select('SELECT * FROM `customers` WHERE id = ? AND is_active = ?', [1, 1]);
    //         if (count($CustomerList) > 0) {
    //             foreach ($CustomerList as $Customer) {
    //                 $pending_bachat_months_data = DB::select('SELECT * FROM `bachat_monthly` WHERE customer_id = ? AND is_penalty_applicable = ? AND has_the_penalty_been_calculated = ?', [$Customer->id, 1, 0]);
    //                 if (count($pending_bachat_months_data) > 0) {
    //                     $total_penalty_amount = 0;
    //                     $today = Date('Y-m-d');
    //                     foreach ($pending_bachat_months_data as $months_data) {
    //                         $pending = $months_data->pending;
    //                         $bachat_pending_months = $this->CalculateMonths($months_data->month_start_date, $months_data->pending_amount_collected_on);
    //                         $penalty = ((($pending / 100) * 2) * $bachat_pending_months);
    //                         $total_penalty_amount = $total_penalty_amount + $penalty;
    //                         DB::update('UPDATE `bachat_monthly` SET `penalty_amount`= ?, `bachat_pending_months`= ?, `has_the_penalty_been_calculated`=?, `penalty_calculate_up_to`=? WHERE id = ?', [$penalty, $bachat_pending_months, 1, $months_data->pending_amount_collected_on, $months_data->id]);
    //                     }
    //                     DB::update('UPDATE `customers` SET `total_penalty_amount`= + ? WHERE id =?', [$total_penalty_amount, $Customer->id]);
    //                 }
    //             }
    //         }
    //     } catch (Exception $e) {
    //         print_r($e->getMessage());
    //     }
    // }


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
