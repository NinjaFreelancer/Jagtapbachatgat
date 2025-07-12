<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class coll_colle_controller extends Controller
{
    //open new account
    public function create_Account_no($cuss_count)
    {
        $add1 = $cuss_count + 1;
        $final_cc_acc = null;
        $final_cc_acc = 'SBGCC' . $add1;
        return $final_cc_acc;
    }

    public function open_new_cc_account(Request $request)
    {
        try {
            $cuss_last_id = DB::select("SELECT id FROM collection_collector ORDER BY id DESC LIMIT ?", [1]);
            if (count($cuss_last_id) == 0) {
                $cuss_id = 0;
            } else {
                if(count($cuss_last_id)<=4)
                {
                    foreach ($cuss_last_id as $count) {
                        $cuss_id = $count->id;
                    }
                }else{
                    return back()->with('error', 'Can Not Create New Account, Limit Extended!.....');
                }
            }
            $final_cc_account_no = $this->create_Account_no($cuss_id);
            return view('admin.pages.collection collector.add_new_cc', array('account_no' => $final_cc_account_no, 'success_code' => 200));
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    // Function For Adding New Collection Collector
    function add_new_coll_colle(Request $request)
    {
        $acount_no = $request->input('account_no');
        $full_name = $request->input('full_name');
        $mobile = $request->input('mobile');
        $email = $request->input('email');
        $address = $request->input('address');
        $pin = $request->input('pin');
        try {
            $coll_colle_data = DB::select('SELECT * FROM collection_collector WHERE `acc_no`=?', [$acount_no]);
            if (count($coll_colle_data) > 0) {
                foreach ($coll_colle_data as $cust_data) {
                    if(($cust_data ->is_account_ready_to_reuse )==1){
                        $profile = time() . '.' . $request->photo->getClientOriginalExtension();
                        $img = $request->photo->move(('profile'), $profile);
                        DB::update('UPDATE `collection_collector` SET `full_name`=?,`mobile_no`=?,`email`=?,`address`=?,`profile`=?,`pin`=?,`pass`=?,`is_active`=?,`is_account_ready_to_reuse`=? WHERE acc_no = ?',
                            [$full_name,$mobile,$email,$address,$profile,$pin,$mobile,1,0,$acount_no]);

                        return back()->with('message', 'Account Open Successfully.....');
                    }else{
                        return back()->with('error', 'Account Number Is In Use.....');
                    }
                }
            }else{
                $profile = time() . '.' . $request->photo->getClientOriginalExtension();
                $request->photo->move(('profile'), $profile);
                $customer_data = array(
                    'acc_no' => $acount_no, 'full_name' => $full_name,'mobile_no' => $mobile, 'email' => $email,
                    'address' => $address, 'profile' => $profile,'pin' => $pin,'pass' => $mobile
                );
                DB::table('collection_collector')->insert($customer_data);
                return back()->with('message', 'Account Open Successfully.....');
            }
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    // Function For Display Collection Collector List
    public function get_collection_collectors(Request $request)
    {
        try {
            $colle_cdollector_list = DB::select('SELECT * FROM collection_collector');
            return view('admin.pages.collection collector.collection_collectors', array('colle_cdollector_list' => $colle_cdollector_list));
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }

    public function get_colle_collector_data($id)
    {
        try {
            $collection_collector_data = DB::select('SELECT * FROM collection_collector WHERE `id`=?', [$id]);
            return view('admin.pages.customer.profile', array('collection_collector_data' => $collection_collector_data));
        } catch (Exception $e) {
            $exception_data = array('exception' => $e->getMessage());
            DB::table('failed_jobs')->insertGetId($exception_data);
            return back()->with('error', 'Something Went Wrong.....');
        }
    }
}
