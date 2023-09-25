@extends('layouts.empbar')

@section('title')
    TS | Rental View
@endsection

@section('content')
<br><br>
@if(session('success'))
<div class="custom-success-message alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<br>
@endif

@if(session('error'))
<div class="custom-error-message alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<br>
@endif

<div class="content">
    <div class="row">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">Rent Information</h5> <hr><span style="background-color: orange; border-radius:5px;padding:5px">Rent Number:<strong> {{$rental->rentID}}</strong> | Booking ID : <strong> {{$bookings[0]->reserveID}} </strong></span>
                </div>

                <div class="card-body" style="font-size: 15px;">
                    <form method="POST" action="{{ route('rental.update', $rental->rentID) }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 pr-1">
                                <div class="form-group">
                                    <label style="color: black;">Client Name</label>
                                    <input style="color: black;background-color: rgb(255, 255, 255)" type="text" class="form-control" value='' readonly>
                                </div>
                            </div>
                            <div class="col-md-6 pl-1">
                                <div class="form-group">
                                    <label style="color: black">Fleet</label>
                                    <select class="form-control" name="vehicle_id">
                                        @foreach($availableVehicles as $vehicle)
                                        <option value="{{ $vehicle->unitID }}" {{ $vehicle->unitID === $bookings[0]->unitID ? 'selected' : '' }}>
                                            {{ $vehicle->unitName }} - {{ $vehicle->registrationNumber }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8 pr-1">
                                <div class="form-group">
                                    <label style="color: black;">Location</label>
                                    <select class="form-control" name="tariff_id" id="tariff_id">
                                        @foreach($tariffs as $tariff)
                                        <option value="{{ $tariff->tariffID}}" data-rent-per-hour="{{ $tariff->rent_Per_Hour }}" {{ $tariff->tariffID === $bookings[0]->tariffID ? 'selected' : '' }}>
                                            {{ $tariff->location }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4 pl-1">
                                <div class="form-group">
                                    <label style="color: black;">Contact Number</label>
                                    <input type="text" style="color: black;background-color: rgb(255, 255, 255)" class="form-control" value="{{$bookings[0]->mobileNum}}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label style="color: black;">Pick-Up Date</label>
                                    <input type="date" class="form-control" name="pickup_date" value="{{ \Carbon\Carbon::parse($bookings[0]->startDate)->format('Y-m-d') }}">
                                </div>
                            </div>

                            <div class="col-md-3 pl-1">
                                <div class="form-group">
                                    <label style="color: black;">Pick-Up Time</label>
                                    <input type="time" class="form-control" name="pickup_time" value="{{ \Carbon\Carbon::parse($bookings[0]->startDate)->format('H:i') }}">
                                </div>
                            </div>

                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label style="color: black;">Drop-Off Date</label>
                                    <input type="date" class="form-control" name="dropoff_date" value="{{ \Carbon\Carbon::parse($bookings[0]->endDate)->format('Y-m-d') }}">
                                </div>
                            </div>

                            <div class="col-md-3 pl-1">
                                <div class="form-group">
                                    <label style="color: black;">Drop-Off Time</label>
                                    <input type="time" class="form-control" name="dropoff_time" value="{{ \Carbon\Carbon::parse($bookings[0]->endDate)->format('H:i') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <label style="color: black;">Pick-Up Address</label>
                                    <input type="text" class="form-control" name="pickup_address" value="{{$bookings[0]->pickUp_Address}}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label style="color: black;">Note</label>
                                    <textarea class="form-control" rows="4" name='note' value="{{$bookings[0]->note}}"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label style="color: black;">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="Approved" {{ $bookings[0]->status === 'Approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="Canceled" {{ $bookings[0]->status === 'Canceled' ? 'selected' : '' }}>Cancel</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label style="color: black;">Gcash Reference No.</label>
                                    <input style="color: black;background-color: rgb(255, 255, 255)" type="text" class="form-control" value="{{$bookings[0]->gcash_RefNum}}" readonly>
                                </div>
                            </div>

                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label style="color: black;">Rate / Additional Hour</label>
                                    <input style="color: black; background-color: white" type="text" class="form-control" id="rent_per_hour" value="{{ $bookings[0]->tariff->rent_Per_Hour }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-3 pl-1">
                                <div class="form-group">
                                    <label style="color: black;">Total Price</label>
                                    <input style="color: black; background-color: rgb(255, 255, 255)" type="text" name='total_price' id='total_price' class="form-control" min='0' value="{{$rents[0]->total_Price}}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label style="color: black;">Driver Assigned</label>
                                    <select class="form-control" name="driver_assigned">
                                        @foreach($drivers as $driver)
                                        <option value="{{ $driver->empID }}" {{ $driver->empID === $rents[0]->driver->empID ? 'selected' : '' }}>
                                            {{ $driver->firstName }} {{ $driver->lastName }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label style="color: black;">Downpayment Amount</label>
                                    <input style="color: black;background-color: white" type="text" class="form-control" value="{{$bookings[0]->downpayment_Fee}}" readonly>
                                </div>
                            </div>

                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label style="color: black;">Extra Hours</label>
                                    <input id="extra_hours" type="number" class="form-control" min="0" name="extra_hours" min='0' value="{{ $rents[0]->extra_Hours ?? 0 }}">
                                </div>
                            </div>                            
                            <div class="col-md-3 pl-1">
                                <div class="form-group">
                                    <label style="color: black;">Balance</label>
                                    <input style="color: black;background-color: rgb(255, 255, 255)" type="text" class="form-control" value="{{$rents[0]->balance}}" id="balance" name='balance' readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label style="color: black;">Rental Status</label>
                                    <select class="form-control" name="rental_status">
                                        <option value="Booked" {{ $rents[0]->rent_Period_Status === 'Booked' ? 'selected' : '' }}>Booked</option>
                                        <option value="Ongoing" {{ $rents[0]->rent_Period_Status === 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                                        <option value="Completed" {{ $rents[0]->rent_Period_Status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label style="color: black;">Subtotal (Fleet Only)</label>
                                    <input style="color: black;background-color: white" type="text" class="form-control" readonly value="{{$bookings[0]->subtotal}}">
                                </div>
                            </div>

                            <div class="col-md-3 pr-1">
                                <div class="form-group">
                                    <label style="color: black;">Extra Hour Fees</label>
                                    <input style="color: black;background-color: rgb(255, 255, 255)" id='compute' type="text" class="form-control" value="0" readonly>
                                </div>
                            </div>

                            <div class="col-md-3 pl-1">
                                <div class="form-group">
                                    <label style="color: black;">Payment Status</label>
                                    <input style="color: black;background-color: rgb(255, 255, 255)" type="text" class="form-control" value="{{$rents[0]->payment_Status}}" readonly>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-danger" onclick="goBack()">Back</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function goBack() {
        window.history.back();
    }

    document.addEventListener("DOMContentLoaded", function () {
    // Get references to the select elements and input fields
    const tariffSelect = document.getElementById('tariff_id');
    const rentPerHourInput = document.getElementById('rent_per_hour');
    const extraHoursInput = document.getElementById('extra_hours');
    const computeInput = document.getElementById('compute');
    const totalPriceInput = document.getElementById('total_price');
    const balanceInput = document.getElementById('balance');
    const statusSelect = document.getElementById('status');

    // Store the initial values
    let initialRentPerHour = parseFloat(rentPerHourInput.value) || 0;
    let initialExtraHours = parseFloat(extraHoursInput.value) || 0;
    let initialTotalPrice = parseFloat(totalPriceInput.value) || 0;
    let initialBalance = parseFloat(balanceInput.value) || 0;
    let originalBalance = initialBalance; // Store the original balance
    let initialStatus = statusSelect.value;

    // Function to update the rate per hour based on the selected location
    function updateRatePerHour() {
        const selectedOption = tariffSelect.options[tariffSelect.selectedIndex];
        const rentPerHour = parseFloat(selectedOption.getAttribute('data-rent-per-hour'));

        // Update the displayed rate per hour
        rentPerHourInput.value = rentPerHour.toFixed(2);

        // Reset extra hours to the initial value
        extraHoursInput.value = initialExtraHours.toFixed(2);

        // Reset total price and balance to initial values
        totalPriceInput.value = initialTotalPrice.toFixed(2);
        balanceInput.value = originalBalance.toFixed(2); // Set the balance back to original
    }

    // Function to update the balance and total price when the extra hours change
    function updateBalanceAndTotalPrice() {
        const extraHours = parseFloat(extraHoursInput.value) || 0;
        const rentPerHour = parseFloat(rentPerHourInput.value) || 0;

        // Calculate the extra hour fee
        const extraHourFee = (extraHours * rentPerHour).toFixed(2);

        // Update the computed extra hour fee
        computeInput.value = extraHourFee;
    }

    // Add an event listener to the tariff select element
    tariffSelect.addEventListener('change', updateRatePerHour);

    // Add an event listener to the extra_hours input field
    extraHoursInput.addEventListener('input', updateBalanceAndTotalPrice);

    // Function to handle status change
    function handleStatusChange() {
        const selectedValue = statusSelect.value;
        statusSelect.style.backgroundColor = selectedValue === 'Approved' ? 'lightgreen' : 'red';
        initialStatus = selectedValue;
    }

    // Add an event listener to the status select element
    statusSelect.addEventListener('change', handleStatusChange);

    // Add an event listener to the tariff select element
tariffSelect.addEventListener('change', function () {
    // Calculate the extra hour fee based on the new location
    updateBalanceAndTotalPrice();

    // Reset extra hours to the initial value when a new location is selected
    extraHoursInput.value = initialExtraHours.toFixed(2);
});

    // Initial update
    updateRatePerHour();

    // Handle initial status
    handleStatusChange();

    // Compute extra hour fees on page load
    updateBalanceAndTotalPrice();
});



</script>
@endsection
