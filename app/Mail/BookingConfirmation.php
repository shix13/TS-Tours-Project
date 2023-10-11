<?php

// BookingConfirmation.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $bookingData;

    /**
     * Create a new message instance.
     *
     * @param array $bookingData
     */
    public function __construct($bookingData)
    {
        $this->bookingData = $bookingData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('tstoursduma@gmail.com', 'TS Tours Services')
                    ->subject('Booking Confirmation')
                    ->view('emails.booking_confirmation');
    }
}
