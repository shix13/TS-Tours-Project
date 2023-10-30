<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FeedbackEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking,$rent;

    public function __construct($booking,$rent)
    {
        $this->booking = $booking;
        $this->rent= $rent;
    }

    public function build()
    {
        return $this->from('tstoursduma@gmail.com', 'TS Tours Services')
            ->view('emails.feedback')
            ->subject('Customer Feedback');
    }
}
