@extends('layouts.app')

@section('content')
<div class="container">
<h2>CHECKOUT</h2>
    <div class="row">
        <div class="col">
            <h4>Vehicle Details</h4>
            <div class="col-md-3">
                <div class="card" style="width: 18rem;">
                    <img class="card-img-top" src="..." alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">{{ $vehicleData -> unitName}}</h5>
                        <p class="card-text">Pax: {{ $vehicleData -> pax}}</p>
                        <p class="card-text">Specifications: {{ $vehicleData -> specification}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <h4>Booking Details</h4>
            <div class="container">
                @foreach($tariffData as $t)
                <div class="row">
                    <div class="col">
                        Location
                    </div>
                    <div class="col">
                        {{ $t -> location }}
                    </div>
                </div>
                @endforeach
                <div class="row">
                    <div class="col">
                        Schedule Date
                        <div class="row">
                            <div class="col">
                                Start date
                            </div>
                            <div class="col">
                                {{ $bookingData['startDate']}}
                            </div>
                            <div class="col">
                                End date
                            </div>
                            <div class="col">
                                {{ $bookingData['startDate']}}
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
        <h4>Secure Payment</h4>
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
            <form method="POST" action="{{ route('checkout') }}">
                @csrf
                <input type="hidden" name="booking_data" value="{{ json_encode($bookingData) }}">
                Enter GCASH reference number <input name="gcash_RefNum">
                <div class="row">
                    <div class="col text-center">
                        <button type="submit" class="btn btn-primary">Pay Now</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection