@extends('layouts.index')

@section('content')
<div class="container">
    <div id="pendingMessage" class="row" style="display: none;">
        <h1  style="font-weight: 700;width:100%">Booking Under Review</h1>
    <div class="row" style="padding: 10px;">
        <p style="font-weight:700;font-size:20px">Your booking is currently under Review. </p>
            <p><hr>You can check your email to be notified regarding the confirmation of your booking. <br>Advisory Letter(?) Cancellation of bookings can only take place up until 3 days before pickup date and time and is non-refundable.</p>
        <p style="margin-top:30px;font-weight:700">Thank You for choosing TS Tours Services!</p>
    </div>
</div>

<div id="deniedMessage" class="row" style="display: none;padding: 10px;">
    <h1 style="font-weight: 700;width:100%">Booking Denied</h1>
    <div class="row" style="padding: 10px;">
        <p style="font-weight:700;font-size:20px">Your booking has been Denied. </p>
            <p><hr>We are sorry for this inconvenience, it seems there were some unforeseen problems. You can contact our team for any concern or assistance on this matter.</p>
        <p style="margin-top:30px;font-weight:700">Thank You for choosing TS Tours Services!</p>
    </div>
</div>

<div id="prePay" class="row" style="display: none;">
    <h1 style="font-weight: 700;width:100%">Proceed to Payment</h1>
    <!--div class="row" style="padding: 10px;">
        <p style="font-weight:700;font-size:20px">Your booking has been Pre-Approved. </p>
        <p><hr>You can check your email regarding the confirmation of your booking. <br>Advisory Letter(?) Cancellation of bookings can only take place up until 3 days before pickup date and time and is non-refundable.</p>
        <p style="margin-top:30px;font-weight:700">Thank You for choosing TS Tours Services!</p>
    </div-->
    <img src="{{ asset('images/confirm-booking-payment.png') }}" style="border-radius: 10px;">
</div>

<div id="paymentForm" class="row" style="display: none;padding: 10px;">
    <div class="row container1" style=" width: 95%;padding:10px 80px">
        <div class="row">
            <div class="col-md-8">
                <h3 style="margin-bottom: 0px;margin-top:20px"><strong><i class="fas fa-lock"></i> Secure Payment</strong></h3>
                <div class="container">
                    <div class="row text-left" >
                        <div class="col"> <br>
                            Subtotal: ₱{{ $booking['subtotal']}}
                        </div>
                    </div>
                    <div class="row text-left">
                        <div class="col">
                            Minimum Downpayment Fee (10%) : ₱{{ $booking['subtotal'] *.1}}
                        </div>
                    </div>
                    <hr>
                    <form method="POST" action="{{ route('checkoutbooking') }}">
                        <input type="hidden" name="bookingID" value="{{ $booking->reserveID }}">
                        @csrf
                        <i class="fas fa-money-bill-wave"></i> Enter Amount Sent: <input type="number" name="amount" required> <br> <br>
                        
                        <i class="fas fa-lock"></i> Enter GCASH reference number: <input name="gcash_RefNum" required>
                            <div class="col text-center"> 
                                <button type="submit" class="btn btn-primary"  style="margin-top: 50px">Confirm Payment</button>
                            </div>
                    </form>
                    <div class="row text-center">
                        <div class="col">
                           Before confirming payment please make sure youre booking details are final. <br>No refunds will be made after confirming payment 
                        </div>
                    </div>
                </div> <hr>
            </div> 
            <div class="col-md-4 ">
                <!-- Add your image here -->
                <img src="{{ asset('images/gcash.jpg') }}" alt="GCash" style="width: 100%;margin-left:0%">
            </div>
        </div>
    </div>
</div>

<div id="postPay" class="row" style="display: none;padding: 10px;">
    <h1 style="font-weight: 700;width:100%">Payment Under Review</h1>
    <div class="row" style="padding: 10px;width:100%">
        <p style="font-weight:700;font-size:20px">Your downpayment is being reviewed. </p>
        <p><hr>You can check your email regarding the confirmation of your booking. <br>Advisory Letter(?) Cancellation of bookings can only take place up until 3 days before pickup date and time and is non-refundable.</p>
        <p style="margin-top:30px;font-weight:700">Thank You for choosing TS Tours Services!</p>
    </div>
</div>

<div id="approved" class="row" style="display: none;padding: 10px;">
    <h1 style="font-weight: 700;width:100%">Payment Approved</h1> 
    <div class="row" style="padding: 10px;width:100%">
        <p style="font-weight:700;font-size:20px">Your booking has been approved. </p>
        <p><hr>You can check your email regarding the confirmation of your booking. <br>Advisory Letter(?) Cancellation of bookings can only take place up until 3 days before pickup date and time and is non-refundable.</p>
        <p style="margin-top:30px;font-weight:700">Thank You for choosing TS Tours Services!</p>
    </div>
</div>


    <div class="row">
        <div class="col">
            <h4><strong>Booking Details</strong> | Booking Number: {{ $booking['reserveID'] }}</h4>
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="row">
                            <div class="col">
                                <i class="fas fa-car"></i> Vehicles booked
                                <div class="row">
                                    @foreach($vehicleTypesBooked as $vehicleTypeBooked)
                                        @php
                                            $vehicleType = $vehicleTypeBooked->vehicleType;
                                            $type = $vehicleType->vehicle_Type;
                                        @endphp
                                        <div class="col-md-12">
                                            x{{ $vehicleTypeBooked->quantity }} {{ $type }}
                                        </div>
                                    @endforeach
                                </div>
                                <hr />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <i class="fas fa-calendar-alt"></i> Schedule Date
                                <div class="row">
                                    <div class="col">
                                        Start Date:
                                    </div>
                                    <div class="col">
                                        {{ $booking['startDate'] }}
                                    </div>
                                    <div class="col">
                                        End Date:
                                    </div>
                                    <div class="col">
                                        {{ $booking['endDate'] }}
                                    </div>
                                </div>
                                <hr />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <i class="fas fa-map-marker-alt"></i> Location
                            </div>
                            <div class="col">
                                {{ $booking->tariff->location }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <i class="fas fa-user"></i> Customer Name
                            </div>
                            <div class="col">
                                {{ $booking['cust_first_name'] }} {{ $booking['cust_last_name'] }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <i class="fas fa-phone"></i> Phone Number
                            </div>
                            <div class="col">
                                {{ $booking['mobileNum'] }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <i class="fas fa-map-pin"></i> Pickup Address
                            </div>
                            <div class="col">
                                {{ $booking['pickUp_Address'] }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <i class="fas fa-sticky-note"></i> Additional Notes
                            </div>
                            <div class="col">
                                {{ $booking['note'] }}
                            </div>
                        </div> <hr>
                    </div>
                    <div class="col-md-12 text-center" style="border-left: 1px solid Silver; font-size: 18px">
                        Vehicles Assigned
                        <p style="font-size: 14px">You can view more details in the email we sent you</p>
                        <div class="row">
                            <div class="col-md-12">
                                @if(isset($vehiclesAssigned))
                                <div class="scrollable-container" style="height: 300px; overflow-y: auto;">
                                    @foreach($vehiclesAssigned as $vehicleAssigned)
                                        @php
                                            $vehicle = $vehicleAssigned->vehicle;
                                            $vPic = $vehicle->pic;
                                            $vPlate = $vehicle->registrationNumber;
                                            $vName = $vehicle->unitName;
                                            $vPax = $vehicle->pax;
                                            $vSpec = $vehicle->specification;
                                            $vModel = $vehicle->yearModel;
                                            $vColor = $vehicle->color;
                                        @endphp
                                        <div class="card">
                                            <div class="row no-gutters">
                                                <div class="col-md-6 d-flex align-items-center" style="padding: 10px">
                                                    <img src="{{ asset('storage/' . $vPic) }}" class="mx-auto card-img" alt="Vehicle Image" style="max-width: 100%; height: auto;">
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="card-body" style="font-size: 12px;">
                                                        <h5 class="card-title" style="font-weight:700;font-size:25px">{{ $vName }}</h5> <hr>
                                                        <p class="card-text" style="text-align: left;font-weight:400">
                                                            <strong>Plate Number:</strong> {{ $vPlate }}<br>
                                                            <strong>Passenger Capacity:</strong> {{ $vPax }}<br>
                                                            <strong>Year Model:</strong> {{ $vModel }}<br>
                                                            <strong>Color:</strong> {{ $vColor }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @else
                                <p>There are no assigned vehicles yet.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<script>
    // Assuming you have a variable or way to determine the booking status
    var bookingStatus = "{{ $booking->status }}"; // Change this to the actual booking status
var bookingGcash = "{{ $booking->gcash_RefNum }}";

// Show the appropriate message based on the booking status
if (bookingStatus === "Pending") {
    document.getElementById("pendingMessage").style.display = "flex";
} else if (bookingStatus === "Denied") {
    document.getElementById("deniedMessage").style.display = "flex";
} else if (bookingStatus === "Pre-approved" && (bookingGcash === null || bookingGcash.trim() === "")) {
    document.getElementById("prePay").style.display = "flex";
    document.getElementById("paymentForm").style.display = "flex";
} else if (bookingStatus === "Pre-approved" && bookingGcash !== null) {
    document.getElementById("postPay").style.display = "flex";
} else if (bookingStatus === "Approved") {
    document.getElementById("approved").style.display = "flex";
}
</script>


@endsection
