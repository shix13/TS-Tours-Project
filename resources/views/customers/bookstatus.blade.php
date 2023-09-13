@extends('layouts.app')

@section('content')
<div class="container">
<h1>Payment Success</h1>
    <div class="row">
        <p>You have paid the downpayment for the booking successfully. Advisory Letter(?) Cancellation of bookings can only take place up until 3 days before pickup date and time and is non-refundable.
    </div>
    <div class="row">
        <div class="col">
            <h4>Booking Details</h4>
            <div class="container">
                <div class="row">
                    <div class="col">
                        Schedule Date
                        <div class="row">
                            <div class="col">
                                Start date {{ $bookingData['startDate']}}
                            </div>
                            <div class="col">
                                End date {{ $bookingData['startDate']}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        Subtotal
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        Phone Number
                    </div>
                    <div class="col">
                        {{ $bookingData['mobileNum']}}
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        Pickup Address
                    </div>
                    <div class="col">
                        {{ $bookingData['pickUp_Address']}}
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        Additional Notes
                    </div>
                    <div class="col">
                    {{ $bookingData['note']}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <h4>Payment Details</h4>
        <div class="container">
            <div class="row">
                <div class="col">
                    Subtotal {{ $bookingData['subtotal']}}
                </div>
            </div>
            <div class="row">
                <div class="col">
                    Downpayment Fee (10%) {{ $bookingData['downpayment_Fee']}}
                </div>
            </div>
            <div class="row">
                <div class="col">
                    Gcash Ref. Number {{ $bookingData['gcash_RefNo']}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection