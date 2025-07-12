<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\Gmail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        try {
            $send_to = $request->session()->get('email');
            $conn_collection_of = $request->session()->get('conn_collection_of');

            $to = $send_to;
            if ($conn_collection_of == 1) {
                $subject = "Confirmation Of Daily Collection";
            } else if ($conn_collection_of == 2) {
                $subject = "Confirmation Of Loan Collection";
            } else {
                $subject = "Confirmation Of Pending Collection";
            }
            $message = view('admin.email.jagtapbachatgat_mail')->render();


            require 'PHPMailer/vendor/autoload.php';
            $mail = new PHPMailer(true);
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->Host       = env('EMAIL_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('EMAIL_USERNAME');
            $mail->Password   = env('EMAIL_PASSWORD');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->setFrom('jagtapbachatgat.com@gmail.com', 'Jagtap Bachat Gat');
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject =  $subject;
            $mail->Body    = $message;
            $dt = $mail->send();

            if ($dt) {
                $request->session()->forget('email');
                $request->session()->forget('conn_amount');
                $request->session()->forget('acc_no');
                $request->session()->forget('time');
                $request->session()->forget('date');
                $request->session()->forget('collection_for');

                if ($conn_collection_of == 1) {
                    $request->session()->forget('conn_collection_of');
                    $request->session()->put('message', 'Collection Added Successfully.....');
                    return view('admin.pages.collection');
                } else if ($conn_collection_of == 2) {
                    $request->session()->forget('conn_collection_of');
                    $request->session()->put('message', 'Loan Collection Added Successfully.....');
                    return view('admin.pages.collection');
                } else {
                    $request->session()->forget('conn_collection_of');
                    $request->session()->put('message', 'Pending Collection Added Successfully.....');
                    return view('admin.pages.collection');
                }
            } else {
                echo 'Something went wrong';
            }
        } catch (Exception $e) {
            dd($e->getMessage());
            dd('Something Went Wrong');
        }
    }
}
