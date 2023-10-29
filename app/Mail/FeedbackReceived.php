<?php

// app/Mail/FeedbackReceived.php
namespace App\Mail;

use Illuminate\Mail\Mailable;
use App\Models\Feedback;
use App\Models\Rent;


class FeedbackReceived extends Mailable
{
    public $feedback,$rent;

    public function __construct(Feedback $feedback, Rent $rent)
{
    $this->feedback = $feedback;
    $this->rent = $rent;
}


    public function build()
    {
        return $this->subject('New Feedback Received')
            ->view('emails.feedback-received');
    }
}

