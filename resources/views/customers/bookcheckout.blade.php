@extends('layouts.index')

@section('content')
<div class="container">
<h2 style="margin-top: 10px"> PAYMENT AND CONFIRMATION</h2>
    <div class="row ">
        <div class="col-md-5">
            <h4 style="margin-left: 30px">Vehicle Details</h4>
            <div>
                <div class="card" style="width: 23rem;margin-left: 26px">
                    <img class="card-img-top" src="{{ asset('storage/' . $vehicleData->pic) }}" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title" style="text-align: center;text-transform:Uppercase"><strong>{{ $vehicleData -> unitName}}</strong></h5>
                        <p class="card-text" style="text-align: justify;font-weight:400;font-size:14px"><strong>Pax:</strong> {{ $vehicleData -> pax}}</p> 
                        <p class="card-text"style="text-align: justify;font-weight:400;font-size:14px"><strong>Specifications:</strong> <br> {{ $vehicleData -> specification}}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container1 col-md-6" style="display: inline-block;max-height:500px" >
            <h4>Booking Details</h4>
            <div class="container">
                @foreach($tariffData as $t)
                <div class="row">
                    <div class="col">
                        <i class="fas fa-map-marker-alt"></i> Location
                    </div>
                    <div class="col">
                        {{ $t -> location }}
                    </div>
                </div>
                @endforeach
                <div class="row container1" style="background: none;box-shadow:none">
                    <div class="col">
                        <i class="fas fa-calendar-alt"></i> Schedule Date <hr>
                        <div class="row">
                            <div class="col">
                                Start Date:
                            </div>
                            <div class="col">
                                {{ $bookingData['startDate'] }}
                            </div>
                            <div class="col">
                                End Date:
                            </div>
                            <div class="col">
                                {{ $bookingData['endDate'] }}
                            </div>
                        </div><hr>
                    </div>
                </div>
                <div class="row">
                    <div class="col" style="margin-left:-12px">
                        <i class="fas fa-clock"></i> Pickup Time
                    </div>
                    <div class="col">
                      <!--Code here to show time part of start date-->
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <i class="fas fa-phone"></i> Phone Number
                    </div>
                    <div class="col">
                        {{ $bookingData['mobileNum'] }}
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <i class="fas fa-map-pin"></i> Pickup Address
                    </div>
                    <div class="col">
                        {{ $bookingData['pickUp_Address'] }}
                    </div>
                </div> <br>
                <div class="col">
                    <div class="col ">
                        <i class="fas fa-sticky-note"></i> Additional Notes
                    </div>
                    <div class="col">
                        {{ $bookingData['note'] }}
                    </div>
                </div>
                <br>          
            </div>
        </div>
    </div>
    <hr>
    <div class="row container1" style="text-align: justify; width: 95%;padding:10px 80px">
        <div class="row">
            <div class="col-md-8">
                <h2 style="margin-bottom: 0px;margin-top:20px"><strong>Secure Payment</strong></h2>
                <div class="container">
                    <div class="row">
                        <div class="col">
                            Subtotal: {{ $bookingData['subtotal']}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            Downpayment Fee (10%): {{ $bookingData['downpayment_Fee']}}
                        </div>
                    </div>
                    <form method="POST" action="{{ route('checkout') }}">
                        @csrf
                        <input type="hidden" name="booking_data" value="{{ json_encode($bookingData) }}">
                        Enter GCASH reference number: <input name="gcash_RefNum" required>
                            <div class="col text-center"> 
                                <button type="submit" class="btn btn-primary"  style="margin-top: 50px">Confirm Payment</button>
                            </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4 ">
                <!-- Add your image here -->
                <img src="{{ asset('/storage/images/gcash.jpg') }}" alt="Your Image" style="width: 100%;margin-left:0%">
            </div>
        </div>
    </div>
    
    
</div>
@endsection