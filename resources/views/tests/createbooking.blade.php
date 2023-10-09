@extends('layouts.index')

@section('content')
<div class="container">
<h2>BOOKING</h2>
    <div class="row container">
        <div class="container1" style="display: inline-block;">
            <h4 style="font-weight: 700">Booking Details</h4> <br>
            <form method="POST" action="{{ route('processbookingreq') }}">
            @csrf
            <div class="container">
                <div class="row">
                    <div class="col">
                        <b>Vehicle Type</b>
                    </div>
                    <div class="col">
                        <b>Quantity</b>
                    </div>
                </div>
                @foreach($vehicleTypes as $vehicleType)
                <div class="row">
                    <div class="col" style="padding: 5px;">
                        {{ $vehicleType->vehicle_Type }}
                    </div>
                    <div class="col" style="padding: 5px;">
                        <input type="number" name="TypeQuantity[{{ $vehicleType->vehicle_Type_ID }}]" value="1" min="1">
                    </div>
                </div>
                @endforeach
                <br>
                
                <div class="row">
                    <div class="col">
                        <i class="fas fa-map-marker-alt"></i> Location
                    </div>
                    <div class="col" >
                        <select id="location" name="location"  style="width: 100%">
                            @foreach($tariffData as $t)
                            <option>{{ $t -> location }}</option>
                            @endforeach
                        </select>
                    </div>
                </div> 
                <div class="row container1" style="background: none;box-shadow:none;">
                    <div class="col">
                        <i class="fas fa-calendar-alt"></i> Schedule Date  <hr>
                        <div class="row">
                            <div class="col">
                                Start Date <input type="date" name="StartDate" id="StartDate" required>
                            </div>
                            <div class="col">
                                End Date <input type="date" name="EndDate" id="EndDate" required>
                            </div>
                        </div><hr>
                    </div>
                </div>
                
                <div class="row container1" style="background: none;box-shadow:none;">
                    <div class="col">
                        <i class="fas fa-user"></i> Full Name 
                        <div class="row">
                            <div class="col">
                                First Name <input type="text" name="FirstName" required>
                            </div>
                            <div class="col">
                                Last Name <input type="text" name="LastName" required>
                            </div>
                        </div> <hr>
                    </div> 
                </div>
                <br>

                <div class="row">
                    <div class="col">
                        <i class="fas fa-envelope"></i> Email Address
                    </div>
                    <div class="col">
                        <input name="Email" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <i class="fas fa-phone"></i> Phone Number
                    </div>
                    <div class="col">
                        <input name="MobileNum" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <i class="fas fa-map-pin"></i> Pickup Address
                    </div>
                    <div class="col">
                        <input name="PickUpAddress" required>
                    </div>
                </div> 
                <div class="row">
                    <div class="col" style="margin-left:-12px">
                        <i class="fas fa-clock"></i> Pickup Time
                    </div>
                    <div class="col">
                        <input type="time" name="PickupTime" style="margin-left:8px;width: 173px" required>
                    </div>
                </div>
                 <br>
                <div class="col">
                    <div class="col">
                        <i class="fas fa-sticky-note"></i> Additional Notes
                    </div>
                    <div class="col-md-12">
                        <textarea class="form-control" rows="5" name='Note'></textarea>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col text-center">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-bookmark"></i> <strong>Book Now </strong></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col text-center">
                        <input type="checkbox">
                        <button type="button" class="btn btn-link" data-toggle="modal" data-target="#termsModal" style="color: black;font-size:13px;">
                            Terms and Conditions
                        </button>
                    </div>
                </div>                
            </div>
            </form>
        </div>
    </div>
</div>
@endsection

@include('customers.termsModal')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get references to the Start Date and End Date input elements
        var startDateInput = document.getElementById('StartDate');
        var endDateInput = document.getElementById('EndDate');

        // Get the current date in ISO format (YYYY-MM-DD)
        var currentDate = new Date();
        currentDate.setDate(currentDate.getDate() + 1); // Set currentDate to tomorrow

        // Convert currentDate to ISO format (YYYY-MM-DD)
        var isoDate = currentDate.toISOString().split('T')[0];

        // Set the minimum date for Start Date to tomorrow's date
        startDateInput.min = isoDate;

        // Get references to the checkbox and "Book Now" button
        var checkbox = document.querySelector('input[type="checkbox"]');
        var bookNowButton = document.querySelector('button[type="submit"]');

        // Disable the "Book Now" button initially
        bookNowButton.disabled = true;

        // Add an event listener to enable/disable the "Book Now" button based on the checkbox
        checkbox.addEventListener('change', function () {
            bookNowButton.disabled = !checkbox.checked;
        });

        // Add an event listener to disable dates before the selected Start Date in End Date
        startDateInput.addEventListener('change', function () {
            var selectedStartDate = new Date(startDateInput.value);
            selectedStartDate.setDate(selectedStartDate.getDate() + 1); // Add one day
            var minEndDate = selectedStartDate.toISOString().split('T')[0];
            endDateInput.min = minEndDate;
            endDateInput.value = ''; // Reset End Date

            // Disable the "Book Now" button if the checkbox is not checked
            if (!checkbox.checked) {
                bookNowButton.disabled = true;
            }
        });

        // Add an event listener to the form submission
        document.querySelector('form').addEventListener('submit', function (event) {
            if (endDateInput.value < startDateInput.value) {
                alert('End Date cannot be earlier than Start Date');
                event.preventDefault(); // Prevent form submission
            }
        });
    });
</script>





