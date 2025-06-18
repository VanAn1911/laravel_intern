<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $email_message;

    public function __construct($email_message)
    {
        $this->email_message = $email_message;
    }

    public function build()
    {
        return $this->subject('Test Email from Laravel')
                    ->view('test')
                    ->with(['email_message' => $this->email_message]);
    }
}