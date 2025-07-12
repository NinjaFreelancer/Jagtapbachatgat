<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class customer_setting_controller extends Controller
{
    // Function For Change Admin Password
    public function customers_change_profile(Request $request)
    {
        try {
            $customer_id = $request->session()->get('customer_id');
            $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$customer_id]);
            if ($customer_data) {
                return view('customer.pages.change_profile', array('customer_data' => $customer_data));
            } else {
                return back()->with('error', 'Customer Data Not Found.');
            }
        } catch (Exception $th) {
            dd($th->getMessage());
        }
    }

    public function customer_update_profile(Request $request)
    {
        try {
            $customer_id = $request->session()->get('customer_id');
            if ($request->photo !== null) {
                $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$customer_id]);
                foreach ($customer_data as $cust_data) {
                    if($cust_data->profile != 'default.jpg')
                    {
                        unlink('profile/'.$cust_data->profile);
                    }
                }
                $profile = time() . '.' . $request->photo->getClientOriginalExtension();
                $img = $request->photo->move(('profile'), $profile);
                if ($img) {
                    $check = DB::update('UPDATE `customers` SET `profile`= ? WHERE id = ?', [$profile, $customer_id]);

                    if ($check) {
                        return back()->with('message', 'Profile Update Successfully.');
                    } else {
                        return back()->with('error', 'Something Went Wrong......');
                    }
                }
            }else {
                    return back()->with('error', 'Select Profile First......');
            }
        } catch (Exception $th) {
            dd($th->getMessage());
        }
    }

    // Function For Change Admin Password
    public function customers_change_pass(Request $request)
    {
        try {
            $customer_id = $request->session()->get('customer_id');
            $customer_data = DB::select('SELECT * FROM customers WHERE `id`=?', [$customer_id]);

            if ($customer_data) {
                return view('customer.pages.change_pass', array('customer_data' => $customer_data));
            } else {
                return back()->with('error', 'Customer Data Not Found.');
            }
        } catch (Exception $th) {
            dd($th->getMessage());
        }
    }

    // Function For Change Admin Password
    public function update_customer_pass(Request $request)
    {
        try {
            $customer_id = $request->session()->get('customer_id');

            $old_pass = $request->input('old_pass');
            $new_pass = $request->input('new_pass');
            $con_pass = $request->input('con_pass');
            if ($new_pass == $con_pass) {
                $data = DB::select('SELECT * FROM `customers` where id=?', [$customer_id]);
                if (count($data) != 0) {
                    foreach ($data as $log_data) {
                        if ($log_data->pass == $old_pass) {
                            $check = DB::update('UPDATE `customers` SET `pass`= ? WHERE id = ?', [$con_pass, $customer_id]);
                            if ($check) {
                                return back()->with('message', 'Password Update Successfully.');
                            } else {
                                return back()->with('error', 'Something Went Wrong......');
                            }
                        } else {
                            return back()->with('error', 'Old Password Not Match.');
                        }
                    }
                } else {
                    return back()->with('error', 'User Not Found.');
                }
            } else {
                return back()->with('error', 'Old Password And Confirm Password Not Match.');
            }
        } catch (Exception $th) {
            dd($th->getMessage());
        }
    }
}