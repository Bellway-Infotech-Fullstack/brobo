<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewCustomerRegistration extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $userId;

    public function __construct($userId,$email,$password)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $userId = $this->userId;
        $email = $this->email;
        $password = $this->password;
        return $this->view('email-templates.new-customer-registration',['id'=>$userId,'email' => $email,'password' => $password]);
    }
}
