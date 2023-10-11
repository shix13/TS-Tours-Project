<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $vehiclesAssigned;
    public $rent;
    public $vehicles;
    public $drivers;
    public $tariff;


    /**
     * Create a new message instance.
     *
     * @param Booking $booking
     */
    public function __construct(Booking $booking, $vehiclesAssigned, Rent $rent, $vehicles, $drivers, Tariff $tariff)
{
    $this->booking = $booking;
    $this->vehiclesAssigned = $vehiclesAssigned;
    $this->rent = $rent;
    $this->vehicles = $vehicles;
    $this->drivers = $drivers;
    $this->tariff = $tariff;
}


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('tstoursduma@gmail.com', 'TS Tours Services')
                    ->view('emails.booking-approved')
                    ->subject('Booking Approved');
    }
}
