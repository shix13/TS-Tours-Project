@extends('layouts.empbar')

@section('title')
    TS | Assign Fleets
@endsection

@section('content')
<br>
<br>
<div class="card">
    <div class="card-header" style="color: red">
        <h5 class="card-title"><i class="fas fa-calendar-check"></i> Booking Request</h5>
    </div>
    
    <div class="card-body">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h6>Booking Number: {{ $pendingBooking->reserveID }}</h6> <hr>
                    <strong><i class="fas fa-map-marker-alt"></i> Pick-Up Address:</strong> {{ $pendingBooking->pickUp_Address }}<br>
                    <strong><i class="far fa-calendar"></i> Pick up Date:</strong> {{ \Carbon\Carbon::parse($pendingBooking->startDate)->format('F j, Y') }}<br>
                    <strong><i class="far fa-clock"></i> Pickup Time:</strong> {{ \Carbon\Carbon::parse($pendingBooking->startDate)->format('g:i A') }}<br>
                    <strong><i class="far fa-calendar-alt"></i> Return Date:</strong> {{ \Carbon\Carbon::parse($pendingBooking->endDate)->format('F j, Y') }}<br>
                    <strong><i class="fas fa-calendar-day"></i> Number of Days:</strong>
                    @php
                        $startDate = \Carbon\Carbon::parse($pendingBooking->startDate)->startOfDay(); // Start from midnight
                        $endDate = \Carbon\Carbon::parse($pendingBooking->endDate)->endOfDay(); // End at the end of the day
                        
                        // Calculate the total duration in days, including partial days, and round it up
                        $numberOfDays = ceil($startDate->floatDiffInDays($endDate));
                        
                        // Determine the label for day(s)
                        $daysLabel = ($numberOfDays == 1) ? 'day' : 'days';
                        
                        // Format the end time
                        $endTimeFormatted = $endDate->format('Y:m:d H:i:s');
                    @endphp

                    {{ $numberOfDays }} {{ $daysLabel }}
                    
                </div>
                <div class="col-md-6">
                    <h6>Customer Info:</h6> <hr>
                    <strong><i class="fas fa-user"></i> Customer Name:</strong> {{ $pendingBooking->cust_first_name }} {{ $pendingBooking->cust_last_name }}<br>
                    <strong><i class="fas fa-envelope"></i> Email:</strong> {{ $pendingBooking->cust_email }}<br>
                    <strong><i class="fas fa-mobile-alt"></i> Mobile Number:</strong> {{ $pendingBooking->mobileNum }}<br>       
                </div>
            </div>
        
            <hr/>

            <div class="row">
                <div class="col">
                <strong><i class="fas fa-users"></i> Pax:</strong> {{ $pendingBooking->pax }}<br>
                    <strong><i class="fas fa-sticky-note"></i> Notes:</strong> {{ $pendingBooking->note }}
                </div>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <div class="container1">
            <table class="table">
                <thead>
                    <tr>
                        <th style="font-weight: 400">Vehicle Type ID</th>
                        <th style="font-weight: 400">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookedTypes as $bookedType)
                    <tr>
                        <td>{{ $bookedType->vehicleType->vehicle_Type ?? 'N/A (No Vehicle Type)' }}</td>
                        <td>{{ $bookedType->quantity }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <hr>
        <!--div class="container">
            <h2>Assign Vehicle(s)</h2>
            <form method="POST" action="{{ route('booking.storeAssign') }}">
                @csrf
                <input type="hidden" name="reserveID" value="{{ $pendingBooking->reserveID }}">
                < Dynamic fleet assignment rows >
                <div id="fleet-assignment-container">
                   
                </div>
                
                <button type="button" id="add-fleet-button" class="btn btn-primary" onclick="addFleetAssignment()" disabled><i class="fa-solid fa-plus"></i>Add Fleet</button>

                <button type="submit" class="btn btn-success"><i class="fa-solid fa-save"></i> Save</button>
            </form>
        </div-->
        <div class="container">
            <h2>Assigned Vehicle(s)</h2>
            <form method="POST" action="{{ route('booking.storeAssign') }}">
                @csrf
                <input type="hidden" name="reserveID" value="{{ $pendingBooking->reserveID }}">
                <!-- Dynamic fleet assignment rows -->
                <div class="row mx-auto" id="fleet-assignment-container" style="height: 400px; overflow: auto;">
                </div>
                
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#assignmentModal">
                    <i class="fa-solid fa-plus"></i> Assign vehicle(s)
                </button>

                <button type="submit" id="submit" class="btn btn-success" disabled><i class="fa-solid fa-save"></i> Save</button>
            </form>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="assignmentModal" tabindex="-1" aria-labelledby="assignmentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable" style="max-width: 1100px; max-height: 700px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignmentModalLabel">Assign vehicle(s)</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <div class="container">
                        <div class="row mx-auto">
                        @foreach($availableVehicles as $vehicle)
                            <div class="col-md-4 mb-4" id="{{ $vehicle->unitID }}" data-identifier="{{ $vehicle->unitID }}">
                                <div class="vehicle-card" data-vehicle="{{ json_encode($vehicle) }}">
                                    <!--div class="row g-0"-->
                                    <div style="height: 100px; overflow: hidden; margin: 0 auto;">
                                        <img src="{{ asset('storage/' . $vehicle->pic) }}" class="card-img-top" style="width: 100%; height: 100%; object-fit: cover;" alt="Vehicle Image">
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title" style="text-align:center;"><b>{{ $vehicle->unitName }}</b></h5>
                                        <p class="card-text">
                                            Vehicle Type: {{ $vehicle->vehicleType->vehicle_Type }} <br/>
                                            License No.: {{ $vehicle->registrationNumber }} <br/>
                                            Capacity: {{ $vehicle->pax }}<br/>
                                            Year Model: {{ $vehicle->yearModel }}<br/>
                                            Color: {{ $vehicle->color }}<br/>
                                        </p>
                                    </div>    
                                </div>
                            </div>
                        @endforeach
                        </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" onclick="assignVehicle()">Assign selected vehicles</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let assignmentCount = 1; // Initialize the assignment count

    const selectedVehicles = [];

    document.querySelectorAll('.vehicle-card').forEach(card => {
        card.addEventListener('click', () => {
            const vehicleData = JSON.parse(card.getAttribute('data-vehicle'));

            // Toggle selection
            const vehicleIndex = selectedVehicles.findIndex(v => v.unitID === vehicleData.unitID);

            if (vehicleIndex !== -1) {
                selectedVehicles.splice(vehicleIndex, 1);
                card.style.backgroundColor = 'white'; // Deselect
            } else {
                selectedVehicles.push(vehicleData);
                card.style.backgroundColor = 'lightgreen'; // Select
            }
        });
    });

    function assignVehicle(){
        document.querySelectorAll('.vehicle-card').forEach(card => {
            card.style.backgroundColor = 'white'; // Deselect
        });

        if(selectedVehicles.length > 0){
        const assignmentContainer = document.getElementById('fleet-assignment-container');

        selectedVehicles.forEach(selectedVehicle => {
        var picURL = selectedVehicle.pic;
        
        // Create a new fleet assignment row
        const newRow = document.createElement('div');
        newRow.className = 'col-md-4 mb-4';
        newRow.innerHTML = `
            <input type="hidden" name="unitID[]" id="unitID${assignmentCount}" class="form-control fleet-select fleet-dropdown" value="${selectedVehicle.unitID}" style="font-size:18px">
            <div class="card">
                <div style="height: 100px; overflow: hidden; margin: 0 auto;">
                    <img src="../storage/${picURL}" class="card-img-top" style="width: 100%; height: 100%; object-fit: cover;" alt="Vehicle Image">
                </div>
                <div class="card-body">
                    <h5 class="card-title" style="text-align:center;"><b>${selectedVehicle.unitName}</b></h5>
                    <p class="card-text">
                        Vehicle Type: ${selectedVehicle.vehicleType} <br/>
                        License No.: ${selectedVehicle.registrationNumber} <br/>
                        Capacity: ${selectedVehicle.pax}<br/>
                        Year Model: ${selectedVehicle.yearModel}<br/>
                        Color: ${selectedVehicle.color}<br/>
                    </p>
                    <label for="empID${assignmentCount}">Driver Name</label>
                    <select name="empID[]" id="empID${assignmentCount}" class="form-control driver-select driver-dropdown" required style="font-size:18px" onchange="updateDriverOptions(this)">
                        <option value="" selected disabled>Select Employee</option>
                        @foreach($availableDrivers as $employee)
                            <option value="{{ $employee->empID }}">{{ $employee->firstName}} {{ $employee->lastName }}</option>
                        @endforeach
                    </select>
                    <hr>
                    <button type="button" class="btn btn-danger" onclick="removeAssignment(this)"><i class="fa-regular fa-x"></i> Remove</button>
                </div>
            </div>
        `;

        assignmentContainer.appendChild(newRow); // Add the new row to the container
        assignmentCount++; // Increment the assignment count

        $('#assignmentModal').modal('hide');

        // Disable selected options in other fleet selects
        disableSelectedOptions(selectedVehicle);
        });

        selectedVehicles.length=0;

        }
        else{
            alert("Please select a vehicle first!");
        }
    }

    function disableSelectedOptions(selectedVehicle) {
        // Store a unique identifier for each card
        const cardIdentifier = selectedVehicle.unitID;

        // Get a reference to the card element with the matching identifier
        const cardElement = document.getElementById(cardIdentifier);
        // Hide the card element
        cardElement.style.display="none";
    }

    function removeAssignment(card){
        // Get the parent element of the card
        const parentElement = card.parentNode.parentNode.parentNode;

        // Get the selected vehicle ID from the input element
        const inputElement = parentElement.querySelector('input');
        const selectedVehicleID = inputElement.value;

        // Remove the parent element from the DOM
        parentElement.parentNode.removeChild(parentElement);

        // Check if there are no more elements in the fleet-assignment-container
        const assignmentContainer = document.getElementById('fleet-assignment-container');
        const remainingElements = assignmentContainer.getElementsByClassName('col-md-4 mb-4').length;

        // If no more elements, disable the submit button
        if (remainingElements === 0) {
            document.getElementById('submit').disabled = true;
        }

        restoreOptions(selectedVehicleID);
    }

    function restoreOptions(selectedVehicle){
        // Store a unique identifier for each card
        const cardIdentifier = selectedVehicle;

        // Get a reference to the card element with the matching identifier
        const cardElement = document.getElementById(cardIdentifier);

        // Show the card element
        cardElement.style.display="block";
    }

    $(document).on('change', '.fleet-select, .driver-select', function() {
        updateDriverOptions(this);
    });

    function updateDriverOptions(changedDropdown) {
        // Enable all options in fleet and driver selects
        $('.driver-dropdown').find('option').removeClass('hidden');

        // Disable selected drivers in other driver selects
        const selectedDriverIds = Array.from(document.querySelectorAll('.driver-select')).map(select => select.value);
        selectedDriverIds.forEach(selectedDriverId => {
            $('.driver-dropdown').not(`[value="${selectedDriverId}"]`).find(`option[value="${selectedDriverId}"]`).addClass('hidden');
        });

        // Check if all dropdowns have a selected value other than "Select Employee"
        const allDropdownsFilled = Array.from(document.querySelectorAll('.driver-select')).every(select => select.value !== "");

        // Enable or disable the button based on the condition
        const submitButton = document.getElementById('submit'); // Change the ID based on your actual submit button ID
        submitButton.disabled = !allDropdownsFilled;
    }

</script>
<script>
    /*
    let assignmentCount = 1; // Initialize the assignment count

    function enableAddFleetButton() {
        const addFleetButton = document.getElementById('add-fleet-button');
        addFleetButton.disabled = false;
    }

    // Enable the button when the page loads
    enableAddFleetButton();


    function addFleetAssignment() {
        const assignmentContainer = document.getElementById('fleet-assignment-container');

        // Create a new fleet assignment row
        const newRow = document.createElement('div');
        newRow.className = 'row mb-3';
        newRow.innerHTML = `
            <div class="col-md-6">
                <label for="unitID${assignmentCount}">Fleet</label>
                <select name="unitID[]" id="unitID${assignmentCount}" class="form-control  fleet-select fleet-dropdown" required style="font-size:18px">
                    <option value="" selected disabled>Select Unit </option>
                    @foreach($availableVehicles as $vehicle)
                        <option value="{{ $vehicle->unitID }}">{{ $vehicle->unitName }}-{{ $vehicle->registrationNumber }} ({{ $vehicle->vehicleType->vehicle_Type }}) - {{ $vehicle->ownership_type }} </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="empID${assignmentCount}">Driver Name</label>
                <select name="empID[]" id="empID${assignmentCount}" class="form-control driver-select driver-dropdown" required style="font-size:18px">
                    <option value="" selected disabled>Select Employee</option>
                    @foreach($availableDrivers as $employee)
                        <option value="{{ $employee->empID }}">{{ $employee->firstName}} {{ $employee->lastName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger" onclick="removeFleetAssignment(this)"><i class="fa-regular fa-x"></i> Remove</button>
            </div>
        `;

        assignmentContainer.appendChild(newRow); // Add the new row to the container
        assignmentCount++; // Increment the assignment count

        // Disable selected options in other fleet selects
        updateDropdownOptions();

        // Enable "Add Fleet" button if the current row has values selected for fleet and driver
        const addFleetButton = document.getElementById('add-fleet-button');
        const currentFleetSelect = document.getElementById('unitID' + assignmentCount);
        const currentDriverSelect = document.getElementById('empID' + assignmentCount);

        if (currentFleetSelect.value && currentDriverSelect.value) {
            addFleetButton.disabled = false;
        } else {
            addFleetButton.disabled = true;
        }

      
    }

    function removeFleetAssignment(button) {
        const assignmentContainer = document.getElementById('fleet-assignment-container');
        assignmentContainer.removeChild(button.parentNode.parentNode); // Remove the row
        // Enable all options in fleet and driver selects after removing a row
        $('.fleet-select').find('option').prop('disabled', false);
        $('.driver-select').find('option').prop('disabled', false);
        // Update dropdown options after removing a row
        updateDropdownOptions();
    }

    function updateDropdownOptions() {
    // Enable all options in fleet and driver selects
    $('.fleet-dropdown, .driver-dropdown').find('option').removeClass('hidden');

    // Disable selected units in other fleet selects
    const selectedFleetUnits = Array.from(document.querySelectorAll('.fleet-select')).map(select => select.value);
    selectedFleetUnits.forEach(selectedUnitId => {
        $('.fleet-dropdown').not(`[value="${selectedUnitId}"]`).find(`option[value="${selectedUnitId}"]`).addClass('hidden');
    });

    // Disable selected drivers in other driver selects
    const selectedDriverIds = Array.from(document.querySelectorAll('.driver-select')).map(select => select.value);
    selectedDriverIds.forEach(selectedDriverId => {
        $('.driver-dropdown').not(`[value="${selectedDriverId}"]`).find(`option[value="${selectedDriverId}"]`).addClass('hidden');
    });

    // Enable "Add Fleet" button if the first row is filled
    const addFleetButton = document.getElementById('add-fleet-button');
    const firstFleetSelect = document.querySelector('.fleet-select');
    const firstDriverSelect = document.querySelector('.driver-select');

    if (firstFleetSelect.value && firstDriverSelect.value) {
        addFleetButton.disabled = false;
    } else {
        addFleetButton.disabled = true;
    }
}




    // Attach change event listeners to fleet and driver selects
    $(document).on('change', '.fleet-select, .driver-select', function() {
        updateDropdownOptions();
    });
    */
</script>
@endsection

