<?php

use App\Http\Controllers\admin\coll_colle_controller;
use App\Http\Controllers\admin\customer_controller;
use App\Http\Controllers\admin\EmailController;
use App\Http\Controllers\admin\expenses_controller;
use App\Http\Controllers\admin\fixed_deposit_controller;
use App\Http\Controllers\admin\loan_controller;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\login_controller;
use App\Http\Controllers\admin\profile_controller;
use App\Http\Controllers\admin\setting_controller;
use App\Http\Controllers\admin\statement_controller;
use App\Http\Controllers\admin\transaction_controller;
use App\Http\Controllers\customer\customer_dashboard_controller;
use App\Http\Controllers\customer\customer_loan_controller;
use App\Http\Controllers\customer\customer_login_controller;
use App\Http\Controllers\customer\customer_setting_controller;
use App\Http\Controllers\customer\customer_statement_controller;
use App\Http\Controllers\modification\modification_controller;
use App\Http\Controllers\modification\month_end_operation_controller;
use App\Http\Controllers\modification\PendingBachatMaintenance;
use App\Http\Middleware\admin_login;
use App\Http\Middleware\customer_login;
use App\Http\Middleware\send_email;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('customer.login');
});

Route::get('/admin', function () {
    return view('admin.login');
});

Route::post('/login_admin', [login_controller::class, 'admin_login']);

//route for login varification
Route::get('/logout_admin', [login_controller::class, 'logout_admin']);


Route::get('/dashboard', [login_controller::class, 'dashboard'])->Middleware(admin_login::class);

//----------------------------------collection--------------------------------------------------------

Route::get('/collection', function () {
    return view('admin.pages.collection.collection');
})->Middleware(admin_login::class);

Route::post("/submit_collection", [transaction_controller::class, 'submit_cutomers_collection'])->Middleware(admin_login::class);

Route::get('/send_email', [EmailController::class, 'sendEmail'])->Middleware(send_email::class);

Route::get("/todays_collection", [statement_controller::class, 'show_todays_collection'])->Middleware(admin_login::class);

Route::post("/datewise_collection", [statement_controller::class, 'show_datewise_collection'])->Middleware(admin_login::class);

Route::get("/collection_pdf/{date}", [statement_controller::class, 'show_collection_pdf'])->Middleware(admin_login::class);

Route::get("/current_month_collection", [statement_controller::class, 'show_current_month_collection'])->Middleware(admin_login::class);

Route::post("/monthwise_collection", [statement_controller::class, 'show_monthwise_collection'])->Middleware(admin_login::class);

Route::get("/mothwise_collection_pdf/{date}", [statement_controller::class, 'show_monthwise_collection_pdf'])->Middleware(admin_login::class);

// ------------------------------------------- customer -------------------------------------------------------------------
Route::get('/new_cust', [customer_controller::class, 'open_new_account'])->Middleware(admin_login::class);

Route::post('/open_account', [customer_controller::class, 'add_new_customer'])->Middleware(admin_login::class);

Route::get('/customers', [customer_controller::class, 'get_customers'])->Middleware(admin_login::class);

Route::get("/get_customer_data/{id}", [statement_controller::class, 'get_customer_info'])->Middleware(admin_login::class);

Route::get("/get_pending_loan/{id}", [statement_controller::class, 'get_pending_loan'])->Middleware(admin_login::class);

Route::get("/get_pending_bachat/{id}", [statement_controller::class, 'get_pending_bachat'])->Middleware(admin_login::class);

Route::get("/pending_loans", [loan_controller::class, 'show_pending_loans'])->Middleware(admin_login::class);


Route::get("/profile/{id}", [profile_controller::class, 'get_customers_data'])->Middleware(admin_login::class);

Route::post("/customer_profile", [profile_controller::class, 'get_profile_data'])->Middleware(admin_login::class);

Route::get("/pending_bachat/{id}", [statement_controller::class, 'get_all_pending_bachat'])->Middleware(admin_login::class);

Route::post("/calculate_penalty/{id}", [statement_controller::class, 'calculate_penalty'])->Middleware(admin_login::class);

Route::get("/collect_penalty/{id}", [statement_controller::class, 'collect_penalty'])->Middleware(admin_login::class);

Route::post("/submit_penalty/{id}", [statement_controller::class, 'submit_penalty'])->Middleware(admin_login::class);

Route::get("/monthly_statement/{id}", [statement_controller::class, 'get_monthly_statement_data'])->Middleware(admin_login::class);

Route::get("/monthly_bachat_statement_pdf/{id}", [statement_controller::class, 'get_monthly_statement_pdf_data'])->Middleware(admin_login::class);

Route::get("/monthly_bachat_status/{id}", [statement_controller::class, 'get_monthly_bachat_status'])->Middleware(admin_login::class);

// Route::get("/missed_bachat_collection", [transaction_controller::class, 'get_bachat_collection_form_details'])->Middleware(admin_login::class);
Route::get('/missed_bachat_collection', function () {
    return view('admin.pages.collection.collect_missed_bachat');
})->Middleware(admin_login::class);

Route::post("/submit_missed_bachat_collection", [transaction_controller::class, 'submit_missed_bachat_collection'])->Middleware(admin_login::class);

// Route::get("/missed_bachat_collection/{id}", [transaction_controller::class, 'get_bachat_collection_form_details'])->Middleware(admin_login::class);

// Route::post("/submit_missed_bachat_collection/{id}", [transaction_controller::class, 'submit_missed_bachat_collection'])->Middleware(admin_login::class);

Route::get("/statement/{id}", [statement_controller::class, 'get_statement'])->Middleware(admin_login::class);

Route::get("/cancel_transaction/{id}", [transaction_controller::class, 'cancel_transaction'])->Middleware(admin_login::class);

Route::get("/modify_transaction/{id}", [transaction_controller::class, 'show_transaction_to_modify'])->Middleware(admin_login::class);

Route::post("/submit_modified_bachat_collection/{id}", [transaction_controller::class, 'submit_modified_bachat_collection'])->Middleware(admin_login::class);

Route::post('/close_account/{id}', [customer_controller::class, 'close_account'])->Middleware(admin_login::class);

Route::post('/calculate_monthly_bachat_interest/{id}', [customer_controller::class, 'calculate_monthly_bachat_interest'])->Middleware(admin_login::class);

Route::post('/calculate_bachat_interest/{id}', [customer_controller::class, 'calculate_five_yers_bachat_interest'])->Middleware(admin_login::class);

Route::post('/clear_data/{id}', [customer_controller::class, 'clear_data'])->Middleware(admin_login::class);

Route::get('/reuse_account/{acc_no}', [customer_controller::class, 'reuse_account'])->Middleware(admin_login::class);



// ------------------------------------------- Loan -----------------------------------------------------------------------------
Route::get("/give_a_loan/{id}", [loan_controller::class, 'give_a_new_loan'])->Middleware(admin_login::class);

Route::post("/submit_loan/{id}", [loan_controller::class, 'submit_loan_to_customer'])->Middleware(admin_login::class);

Route::get("/loan_statement/{loan_id}", [loan_controller::class, 'get_loan_statement'])->Middleware(admin_login::class);

Route::get("/cancel_loan_transaction/{id}", [loan_controller::class, 'cancel_loan_transaction'])->Middleware(admin_login::class);

Route::get("/monthly_loan_statement/{loan_id}", [loan_controller::class, 'get_monthly_loan_statement'])->Middleware(admin_login::class);

Route::get("/monthly_loan_statement_pdf/{loan_id}", [loan_controller::class, 'get_monthly_loan_statement_pdf_data'])->Middleware(admin_login::class);

Route::get("/remove_month/{month_id}", [loan_controller::class, 'remove_month'])->Middleware(admin_login::class);

Route::get("/add_month/{id}", [loan_controller::class, 'add_month'])->Middleware(admin_login::class);

Route::get("/missed_loan_collection/{id}", [loan_controller::class, 'get_collection_form_details'])->Middleware(admin_login::class);

Route::post("/submit_missed_loan_collection/{id}", [loan_controller::class, 'submit_missed_loan_collection'])->Middleware(admin_login::class);

Route::get('/show_loan_details/{id}', [loan_controller::class, 'show_loan_details'])->Middleware(admin_login::class);

Route::post("/calculate_interest/{id}", [loan_controller::class, 'calculate_interest_of_loan'])->Middleware(admin_login::class);

Route::get("/collect_all_loan/{id}", [loan_controller::class, 'collect_all_loan_amount'])->Middleware(admin_login::class);

Route::post("/submit_all_loan/{id}", [loan_controller::class, 'submit_all_loan'])->Middleware(admin_login::class);

Route::get("/previous_loans/{id}", [loan_controller::class, 'get_previous_loans'])->Middleware(admin_login::class);

Route::get("/remove_loan/{loan_id}", [loan_controller::class, 'remove_loan'])->Middleware(admin_login::class);

Route::get('/edit_profile/{id}', [customer_controller::class, 'get_customers_data_for_edit'])->Middleware(admin_login::class);

Route::post('/update_customer_info/{id}', [customer_controller::class, 'update_customer_info'])->Middleware(admin_login::class);

Route::get('/block_customer/{id}', [customer_controller::class, 'block_customer_account'])->Middleware(admin_login::class);

Route::get('/unblock_customer/{id}', [customer_controller::class, 'unblock_customer_account'])->Middleware(admin_login::class);



Route::get('/work_in_progress', function () {
    return view('admin.pages.work_in_progress');
})->Middleware(admin_login::class);
// -------------------------------------- Fixed Deposit-------------------------------------------------------------

Route::get('/add_fixed_deposit', function () {
    return view('admin.pages.fixed deposit.add_fixed_deposit');
})->Middleware(admin_login::class);

Route::post('/submit_fixed_deposit', [fixed_deposit_controller::class, 'submit_fixed_deposit'])->Middleware(admin_login::class);

Route::get('/show_active_fd_statement', [fixed_deposit_controller::class, 'get_active_fd_statement'])->Middleware(admin_login::class);

Route::get('/remove_fixed_deposit/{id}', [fixed_deposit_controller::class, 'remove_fixed_deposit'])->Middleware(admin_login::class);

Route::get('/show_history_of_fd_statement', [fixed_deposit_controller::class, 'get_history_of_fd_statement'])->Middleware(admin_login::class);

Route::get('/show_fd_details/{id}', [fixed_deposit_controller::class, 'show_fd_details'])->Middleware(admin_login::class);

Route::post("/calculate_fd_interest/{id}", [fixed_deposit_controller::class, 'calculate_fd_interest'])->Middleware(admin_login::class);

Route::post("/disburse_fd_amount/{id}", [fixed_deposit_controller::class, 'disburse_fd_amount'])->Middleware(admin_login::class);

// -------------------------------------- Expencess ----------------------------------------------------------------

Route::get('/add_expense', function () {
    return view('admin.pages.expense.add_expense');
})->Middleware(admin_login::class);

Route::post('/submit_expense', [expenses_controller::class, 'submit_expense'])->Middleware(admin_login::class);

Route::get('/show_expenses', [expenses_controller::class, 'get_expenses'])->Middleware(admin_login::class);

Route::get('/remove_expense/{id}', [expenses_controller::class, 'remove_expense'])->Middleware(admin_login::class);

// -------------------------------------- Collection Collector -----------------------------------------------------

Route::get('/add_new_cc', [coll_colle_controller::class, 'open_new_cc_account'])->Middleware(admin_login::class);

Route::post('/open_colle_colle_account', [coll_colle_controller::class, 'add_new_coll_colle'])->Middleware(admin_login::class);

Route::get('/collection_collectors', [coll_colle_controller::class, 'get_collection_collectors'])->Middleware(admin_login::class);

Route::get('/collection_collectors', [coll_colle_controller::class, 'get_collection_collectors'])->Middleware(admin_login::class);

// -------------------------------------- Admin Setting -------------------------------------------------------------
Route::get('/change_pin', function () {
    return view('admin.pages.setting.change_pin');
})->Middleware(admin_login::class);

Route::post('/update_pin', [setting_controller::class, 'update_admin_pin'])->Middleware(admin_login::class);


Route::get('/change_pass', function () {
    return view('admin.pages.setting.change_pass');
})->Middleware(admin_login::class);

Route::post('/update_password', [setting_controller::class, 'update_admin_pass'])->Middleware(admin_login::class);

Route::post('/update_password', [setting_controller::class, 'update_admin_pass'])->Middleware(admin_login::class);

// ------------------------------------------------------------------------------------------------------------------



// ------------------------------------------------User---------------------------------------------------------

Route::get('/customer', function () {
    return view('customer.login');
});

Route::post('/customer_login', [customer_login_controller::class, 'customer_login']);

//route for login varification
Route::get('/customer_logout', [customer_login_controller::class, 'customer_logout']);


Route::get('/customers_dashboard', [customer_dashboard_controller::class, 'customer_dashboard'])->Middleware(customer_login::class);


Route::get("/customer_statement", [customer_statement_controller::class, 'get_customer_bachat_statement'])->Middleware(customer_login::class);

Route::get("/customer_monthly_statement", [customer_statement_controller::class, 'get_customer_monthly_statement_data'])->Middleware(customer_login::class);


Route::get("/customer_loan_statement", [customer_loan_controller::class, 'get_customer_Loan_statement'])->Middleware(customer_login::class);

Route::get("/customer_monthly_loan_statement", [customer_loan_controller::class, 'get_customer_prev_monthly_loan_statement'])->Middleware(customer_login::class);

Route::get("/customer_prev_loan_statement/{id}", [customer_loan_controller::class, 'get_customer_prev_Loan_statement'])->Middleware(customer_login::class);

Route::get("/customer_prev_monthly_loan_statement/{id}", [customer_loan_controller::class, 'get_customer_prev_monthly_loan_statement'])->Middleware(customer_login::class);


Route::get('/customers_change_profile', [customer_setting_controller::class, 'customers_change_profile'])->Middleware(customer_login::class);

Route::post('/customer_update_profile', [customer_setting_controller::class, 'customer_update_profile'])->Middleware(customer_login::class);

Route::get('/customers_change_pass', [customer_setting_controller::class, 'customers_change_pass'])->Middleware(customer_login::class);

Route::post('/customer_update_password', [customer_setting_controller::class, 'update_customer_pass'])->Middleware(customer_login::class);
// -------------------------------------------------------------------------------------------------------------

Route::get("/modify_customer_acc_dates", [modification_controller::class, 'modify_customer_acc_dates']);
Route::get("/modify_monthly_bachat_dates", [modification_controller::class, 'modify_monthly_bachat_dates']);
Route::get("/modify_bachat_statement_date_time", [modification_controller::class, 'modify_bachat_statement_date_time']);

Route::get("/modify_loans_dates", [modification_controller::class, 'modify_loans_dates']);
Route::get("/modify_monthly_loan_dates", [modification_controller::class, 'modify_monthly_loan_dates']);
Route::get("/modify_loan_statement_date_time", [modification_controller::class, 'modify_loan_statement_date_time']);

Route::get("/create_month_of_bachat_status", [modification_controller::class, 'create_month_of_bachat_status']);
Route::get("/month_end_operation_for_bachat", [modification_controller::class, 'month_end_operation_for_bachat']);
Route::get("/create_month_of_loan_status", [modification_controller::class, 'create_month_of_loan_status']);
Route::get("/add_loan_id_in_monthly_loan_dates", [modification_controller::class, 'add_loan_id_in_monthly_loan_dates']);
Route::get("/add_loan_id_in_loan_statement", [modification_controller::class, 'add_loan_id_in_loan_statement']);
Route::get("/check_interest_calculated_date", [modification_controller::class, 'check_interest_calculated_date']);
Route::get("/calculated_pending_penalty", [modification_controller::class, 'calculated_pending_penalty']);

Route::get("/CreateMonthData", [month_end_operation_controller::class, 'PrepairMonths']);

Route::get("/CleanPendingBachatData", [modification_controller::class, 'CleanPendingBachatData']);
Route::get("/ResetPendingBachatDataForExpiredMonths", [modification_controller::class, 'ResetPendingBachatDataForExpiredMonths']);
Route::get("/ResetPendingBachatDataForNonExpiredMonths", [modification_controller::class, 'ResetPendingBachatDataForNonExpiredMonths']);

Route::get("/CollectPendingBachatAmount", [modification_controller::class, 'CollectPendingBachatAmount']);
Route::get("/CalculatePanelty", [modification_controller::class, 'CalculatePanelty']);

Route::get("/CalculatePendingBachat", [PendingBachatMaintenance::class, 'CalculatePendingBachat']);
Route::get("/PendingBachatMaintenanceOperation", [PendingBachatMaintenance::class, 'PendingBachatMaintenanceOperation']);
Route::get("/CollectPendingAmountOnMonthEnd", [month_end_operation_controller::class, 'CollectPendingAmountOnMonthEnd']);
Route::get("/CalculatePaneltyOnMonthEnd", [month_end_operation_controller::class, 'CalculatePaneltyOnMonthEnd']);
Route::get("/CheckTime", [month_end_operation_controller::class, 'CheckTime']);

// route for clear all cache data
Route::get('/cache_data', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('event:clear');
    Artisan::call('route:clear');
    return "Cache is cleared";
});
