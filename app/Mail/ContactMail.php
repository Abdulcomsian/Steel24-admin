<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
{
    use Queueable, SerializesModels;
    public $username;
    public $email;
    public $msg;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($username , $email , $msg)
    {
        $this->username = $username;
        $this->email = $email;
        $this->msg = $msg;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.contact');
    }
}
