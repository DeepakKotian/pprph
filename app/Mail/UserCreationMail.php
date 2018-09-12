<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreationMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    protected $theme = 'default';
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        $subject = 'User Creation';
     
        return $this->markdown('vendor.emails.emailusercreation')->subject($subject);
    }
}
