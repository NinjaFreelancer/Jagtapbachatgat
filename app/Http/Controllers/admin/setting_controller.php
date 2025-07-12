<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class setting_controller extends Controller
{
    // Function For Change Admin Password
    public function update_admin_pass(Request $request)
    {
        try {
            $old_pass = $request->input('old_pass');
            $new_pass = $request->input('new_pass');
            $con_pass = $request->input('con_pass');
            if ($new_pass == $con_pass) {
                $data = DB::select('SELECT * FROM `admin`');
                if (count($data) != 0) {
                    foreach ($data as $log_data) {
                        if ($log_data->password == $old_pass) {
                            $check = DB::update('UPDATE `admin` SET `password`= ? WHERE id = ?', [$con_pass, 1]);
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
                return back()->with('error', 'Password Not Match.');
            }
        } catch (Exception $th) {
            dd($th->getMessage());
        }
    }

    // Function For Change Admin Pin
    public function update_admin_pin(Request $request)
    {
        try {
            $old_pin = $request->input('old_pin');
            $new_pin = $request->input('new_pin');
            $con_pin = $request->input('con_pin');
            if ($new_pin == $con_pin) {
                $data = DB::select('SELECT * FROM `admin`');
                if (count($data) != 0) {
                    foreach ($data as $log_data) {
                        if ($log_data->pin == $old_pin) {
                            $check = DB::update('UPDATE `admin` SET `pin`= ? WHERE id = ?', [$con_pin, 1]);
                            if ($check) {
                                return back()->with('message', 'Pin Update Successfully.');
                            } else {
                                return back()->with('error', 'Something Went Wrong.....');
                            }
                        } else {
                            return back()->with('error', 'Old Pin Not Match.');
                        }
                    }
                } else {
                    return back()->with('error', 'User Not Found.');
                }
            } else {
                return back()->with('error', 'Pin Not Match.');
            }
        } catch (Exception $th) {
            dd($th->getMessage());
        }
    }
}