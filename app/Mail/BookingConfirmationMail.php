<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reserveID;
    public $customerFirstName;
    public $customerLastName;

    public function __construct($reserveID, $customerFirstName, $customerLastName)
    {
        $this->reserveID = $reserveID;
        $this->customerFirstName = $customerFirstName;
        $this->customerLastName = $customerLastName;
    }

    public function build()
    {
        return $this->from('tstoursduma@gmail.com', 'TS Tours Services')
                    ->view('emails.pre-approval-notification')
                    ->subject('Booking Confirmation');
    }
}
