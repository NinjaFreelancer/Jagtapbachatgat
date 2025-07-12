<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Gmail extends Mailable
{
    use Queueable, SerializesModels;

    public $details;
    /**
     * Create a new message instance.
     *
     *
     *  
     */

    //  @return void
    public function __construct($details)
    {
        //
        $this->details = $details;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->subject('Confirmation Of Collection')->view('admin.email.jagtapbachatgat_mail');
        $address = 'jagtapbachatgat.com@gmail.com';
        $subject = $this->details['subject'];
        $name = 'Jagtap Bachat Gat';

        return $this->view('admin.email.jagtapbachatgat_mail')
            ->from($address, $name)
            ->cc($address, $name)
            ->bcc($address, $name)
            ->replyTo($address, $name)
            ->subject($subject);
    }
}
