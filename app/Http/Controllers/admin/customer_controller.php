<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class customer_controller extends Controller
{
    public function create_Account_no($cuss_count)
    {
        $add1 = $cuss_count + 1;
        $len = strlen($add1);
        $final_user_acc = null;
        if ($len == 1 ||  strlen($add1) == 1) {
            $final_user_acc = 'SBG0000' . $add1;
        } else if ($len == 2 && strlen($add1) == 2) {
            $final_user_acc = 'SBG000' . $add1;
        } else if ($len == 3 && strlen($add1) == 3) {
            $final_user_acc = 'SBG00' . $add1;
        } else if ($len == 4 && strlen($add1) == 4) {
            $final_user_acc = 'SBG0' . $add1;
        } else if ($len == 5 && strlen($add1) == 5) {
            $final_user_acc = 'SBG' . $add1;
        }
        return $final_user_acc;
    }

    public function open_new_account(Request $request)
    {
        try {
            $cuss_last_id = DB::select("SELECT id FROM customers ORDER BY id DESC LIMIT ?", [1]);
            if (count($cuss_last_id) == 0) {
                $cuss_id = 0;
            } else {
                foreach ($cuss_last_id as $count) {
                    $cuss_id = $count->id;
                }
            }
            $final_user_account_no = $this->create_Account_no($cuss_id);
            return view('admin.pages.customer.new_cust', array('account_no' => $final_user_account_no, 'success_code' => 200));
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    // FUnction For Adding New Customer
    function add_new_customer(Request $request)
    {
        $acount_no = $request->input('account_no');
        $full_name = $request->input('full_name');
        $shop_name = $request->input('shop_name');
        $mobile = $request->input('mobile');
        $aadhaar = $request->input('aadhaar');
        $pan = $request->input('pan');
        $email = $request->input('email');
        $amount = $request->input('bachat_amount');
        $open_date = $request->input('date');

        $date = new DateTime($open_date);
        $open_date = $date->format('Y-m-d');

        $date = new DateTime($open_date);
        $date->modify('+5 years');
        $expre_date = $date->format('Y-m-d');

        $address = $request->input('address');
        try {
            $customer_data = DB::select('SELECT * FROM customers WHERE `acc_no`=?', [$acount_no]);
            if (count($customer_data) > 0) {
                foreach ($customer_data as $cust_data) {
                    if (($cust_data->is_account_ready_to_reuse) == 1) {
                        DB::update(
                            'UPDATE `customers` SET `full_name`=?,`shop_name`=?,`per_month_bachat`=?,`mobile_no`=?,`email`=?,`aadhaar`=?,`pan`=?,`address`=?,`account_opening_date`=?,`account_expiry_date`=?,`pass`=?,`is_active`=?,`is_account_ready_to_reuse`=? WHERE acc_no = ?',
                            [$full_name, $shop_name, $amount, $mobile, $email, $aadhaar, $pan, $address, $open_date, $expre_date, $mobile, 1, 0, $acount_no]
                        );
                        return back()->with('message', 'Account Open Successfully.....');
                    } else {
                        return back()->with('error', 'Account Number Is In Use.....');
                    }
                }
            } else {
                $customer_data = array(
                    'acc_no' => $acount_no,
                    'full_name' => $full_name,
                    'shop_name' => $shop_name,
                    'per_month_bachat' => $amount,
                    'mobile_no' => $mobile,
                    'email' => $email,
                    'aadhaar' => $aadhaar,
                    'pan' => $pan,
                    'aadhaar' => $aadhaar,
                    'address' => $address,
                    'account_opening_date' => $open_date,
                    'account_expiry_date' => $expre_date,
                    'pass' => $mobile
                );
                DB::table('customers')->insert($customer_data);
                return back()->with('message', 'Account Open Successfully.....');
            }
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    // Function For Display Customer List
    public function get_customers(Request $request)
    {
        try {
            $customer_list = DB::select('SELECT * FROM customers');
            return view('admin.pages.customer.customers', array('customer_list' => $customer_list));
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    // Function For Display Customer data
    public function get_customers_data_for_edit($id)
    {
        try {
            $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$id]);
            if (count($customer_data) == 0) {
                return view('admin.pages.customer.edit_profile', array('data' => 0));
            } else {
                return view('admin.pages.customer.edit_profile', array('customer_data' => $customer_data, 'data' => 1));
            }
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    // FUnction For Update Customer Data
    public function update_customer_info(Request $request, $id)
    {
        $full_name = $request->input('full_name');
        $shop_name = $request->input('shop_name');
        $mobile = $request->input('mobile');
        $email = $request->input('email');
        $aadhaar = $request->input('aadhaar');
        $pan = $request->input('pan');

        $address = $request->input('address');
        $pass = $request->input('pass');

        try {
            DB::update(
                'UPDATE `customers` SET `full_name` =?,  `shop_name` = ?,
                `mobile_no` =?, `email` =?, `aadhaar` =?, `pan` =?,`address` =?, `pass` =? WHERE id =?',
                [$full_name, $shop_name, $mobile, $email, $aadhaar, $pan, $address, $pass, $id]
            );

            return back()->with('message', 'Information Update Successfully.....');
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    public function block_customer_account($id)
    {
        try {
            DB::update('UPDATE `customers` SET `status` = ? WHERE id =?', [1, $id]);

            return back()->with('message', 'Account Block Successfully.....');
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    function unblock_customer_account(Request $request, $id)
    {
        try {
            DB::update(
                'UPDATE `customers` SET `status` =? WHERE id =?',
                [0, $id]
            );

            return back()->with('message', 'Account Unblock Successfully.....');
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }


    function calculate_monthly_bachat_interest(Request $request, $id)
    {
        try {
            $con_pin = $request->input('con_pin');
            $calculate_up_to = $request->input('calculate_up_to');
            $date = new DateTime($calculate_up_to);
            $calculate_up_to = $date->format('Y-m-d');

            $admin_data = DB::select('SELECT * FROM `admin` WHERE pin = ?', [$con_pin]);
            if (count($admin_data) == 0) {
                return back()->with('error', 'Pin mot matched.....');
            }

            $customers_data = DB::select('SELECT * FROM `customers` Where id=?', [$id]);
            foreach ($customers_data as $cust_data) {
                $balance = $cust_data->balance;
                $account_opening_date = $cust_data->account_opening_date;
                $account_expiry_date = $cust_data->account_expiry_date;
            }

            if ($account_opening_date >= $calculate_up_to) {
                return back()->with('error', 'Date should Be Greater Than Account Opening Date.....');
            }

            if ($account_expiry_date < $calculate_up_to) {
                return back()->with('error', 'Date should Be Less Than Account Expiry Date.....');
            }

            DB::update('UPDATE `bachat_monthly` SET `interest`=? WHERE `customer_id` = ?', [0, $id]);
            DB::update('UPDATE `customers` SET `interest` =?, `interest_calculated_up_to` = ?, `is_interest_calculated` = ? WHERE id =?', [0, null, 0, $id]);

            $bachat_monthly_list = DB::select('SELECT * FROM `bachat_monthly` Where customer_id=? AND month_start_date < ?', [$id, $calculate_up_to]);
            $total_interest =  0.0;
            $i = 0;
            foreach ($bachat_monthly_list as $bachat_month) {
                $amount = $bachat_month->collection_upto_prev_month; // Ensure amount is float
                $percentage = 1.1299549; // 1.13% interest rate

                $interest = (float) (($percentage / 100) * $amount); // Cast to float

                $total_interest += $interest;
                DB::update(
                    'UPDATE `bachat_monthly` SET `interest` =? WHERE id =?',
                    [$interest, $bachat_month->id]
                );
            }
            DB::update(
                'UPDATE `customers` SET `interest` =?, `interest_calculated_up_to` = ?, `is_interest_calculated` = ? WHERE id =?',
                [$total_interest, $calculate_up_to, 1, $id]
            );
            return back()->with('message', 'Interest Calculate Successfully.....');
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    function calculate_five_yers_bachat_interest(Request $request, $id)
    {
        try {
            $con_pin = $request->input('con_pin');

            $admin_data = DB::select('SELECT * FROM `admin` WHERE pin = ?', [$con_pin]);
            if (count($admin_data) == 0) {
                return back()->with('error', 'Pin mot matched.....');
            }

            DB::update('UPDATE `bachat_monthly` SET `interest`=? WHERE `customer_id` = ?', [0, $id]);
            DB::update('UPDATE `customers` SET `interest` =?, `interest_calculated_up_to` = ?, `is_interest_calculated` = ? WHERE id =?', [0, null, 0, $id]);

            $customers_data = DB::select('SELECT * FROM `customers` Where id=?', [$id]);
            foreach ($customers_data as $cust_data) {
                $balance = $cust_data->balance;
                $percentage = 33.3334;
                $interest = ($percentage / 100) * $balance;
                DB::update(
                    'UPDATE `customers` SET `interest` =?, `interest_calculated_up_to` = ?, `is_interest_calculated` = ? WHERE id =?',
                    [$interest, $cust_data->account_expiry_date, 1, $id]
                );
            }
            return back()->with('message', 'Interest Calculate Successfully.....');
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    public function close_account(Request $request, $id)
    {
        try {
            $pin = $request->input('pin');
            $admin_data = DB::select('SELECT * FROM `admin` Where pin=?', [$pin]);

            if (count($admin_data) == 0) {
                return back()->with('error', 'Pin Not Match.....');
            }

            $loan_data = DB::select('SELECT * FROM loan WHERE `customer_id`=? AND `status`=?', [$id, 0]);

            if (count($loan_data) != 0) {
                return back()->with('error', 'Please Close Loan First.....');
            }

            $total_loan_interst_collection = DB::select("SELECT SUM(interest) as loan_interest FROM `loan` WHERE `customer_id` = ?", [$id]);

            $total_loan_interst_colle = 0;
            foreach ($total_loan_interst_collection as $data) {
                if ($data->loan_interest != null) {
                    $total_loan_interst_colle = $data->loan_interest;
                }
            }

            $total_agent_commission_collection = DB::select("SELECT SUM(agent_commission) as agent_commission_total FROM `loan` WHERE `customer_id` = ?", [$id]);
            $agent_commission_total = 0;
            foreach ($total_agent_commission_collection as $data) {
                if ($data->agent_commission_total != null) {
                    $agent_commission_total = $data->agent_commission_total;
                }
            }

            DB::update('UPDATE `customers` SET `loan_revenue_from_loan`=? WHERE id = ?', [$total_loan_interst_colle + $agent_commission_total, $id]);

            DB::delete('DELETE FROM `bachat_monthly` WHERE `customer_id` = ?', [$id]);
            DB::delete('DELETE FROM `loan` WHERE `customer_id` = ?', [$id]);
            DB::delete('DELETE FROM `loan_monthly_status` WHERE `customer_id` = ?', [$id]);
            DB::delete('DELETE FROM `loan_statement` WHERE `customer_id` = ?', [$id]);
            DB::delete('DELETE FROM `statement` WHERE `customer_id` = ?', [$id]);

            DB::update('UPDATE `customers` SET `is_active`=? WHERE id = ?', [0, $id]);

            return back()->with('message', 'Account Closed Successfully.....');
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    public function clear_data(Request $request, $id)
    {
        try {
            $pin = $request->input('pin');
            $admin_data = DB::select('SELECT * FROM `admin` Where pin=?', [$pin]);

            if (count($admin_data) == 0) {
                return back()->with('error', 'Pin Not Match.....');
            }

            // $customers_data = DB::select('SELECT * FROM `customers` Where id=?',[$id]);
            // foreach ($customers_data as $cust_data) {
            //     if($cust_data->profile != null)
            //     {
            //         unlink('profile/'.$cust_data->profile);
            //     }
            // }
            $today = Date('Y-m-d');
            DB::update('UPDATE `customers` SET `full_name`=?,`shop_name`=?,`balance`=?,`per_month_bachat`=?,`mobile_no`=?,`email`=?,`aadhaar`=?,`pan`=?,`address`=?,`profile`=?,`account_opening_date`=?,`account_expiry_date`=?,`interest` =?, `interest_calculated_up_to` =?, `pass`=?,`is_active`=?,`is_account_ready_to_reuse`=?,`is_all_months_status_ready`=? WHERE id = ?', ['Not Defined', null, 0, 0, 0, 'Not Defined', 0, 'Not Defined', 'Not Defined', null, $today, $today, 0, null, 0, 0, 1, 0, $id]);

            return back()->with('message', 'Account Data Cleared Successfully.....');
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }
    public function reuse_account($final_user_account_no)
    {
        try {
            return view('admin.pages.customer.new_cust', array('account_no' => $final_user_account_no, 'success_code' => 200));
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }
}
