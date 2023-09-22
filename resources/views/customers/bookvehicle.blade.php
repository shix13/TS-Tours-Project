@extends('layouts.index')

@section('content')
<div class="container">
<h2>BOOKING</h2>
    <div class="row container">
        <div class="col">
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
        <div class="container1" style="display: inline-block;max-height:650px;">
            <h4>Booking Details</h4>
            <form method="POST" action="{{ route('processbooking') }}">
            @csrf
            <input type="hidden" name="vehicleID" value="{{ $vehicleData -> unitID }}">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <i class="fas fa-map-marker-alt"></i> Location
                    </div>
                    <div class="col">
                        <select id="location" name="location">
                            @foreach($tariffData as $t)
                            <option>{{ $t -> location }}</option>
                            @endforeach
                        </select>
                    </div>
                </div> <br>
                <div class="row container1" style="background: none;box-shadow:none;">
                    <div class="col">
                        <i class="fas fa-calendar-alt"></i> Schedule Date <hr>
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
                
                <div class="row">
                    <div class="col" style="margin-left:-12px">
                        <i class="fas fa-clock"></i> Pickup Time
                    </div>
                    <div class="col">
                        <input type="time" name="PickupTime" style="margin-left:8px;width: 173px" required>
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
                </div> <br>
                <div class="col">
                    <div class="col">
                        <i class="fas fa-sticky-note"></i> Additional Notes
                    </div>
                    <div class="col-md-12">
                        <textarea class="form-control" rows="5" name='note'></textarea>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col text-center">
                        <button type="submit" class="btn btn-primary">Book Now</button>
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





