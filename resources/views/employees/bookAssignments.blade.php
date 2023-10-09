@extends('layouts.empbar')

@section('title')
    TS | Tariffs
@endsection

@section('content')
<br>
<br>
<div class="card">
    <div class="card-header">
        <h4 class="card-title" style="font-weight: 700">Customer Request</h4>
    </div>
    <div class="card-body">
        <div class="container1">

            <div class="mb-4">
                <strong><i class="fas fa-user"></i> Customer Name:</strong> {{ $pendingBooking->cust_first_name }} {{ $pendingBooking->cust_last_name }}<br>
                <strong><i class="fas fa-envelope"></i> Email:</strong> {{ $pendingBooking->cust_email }}<br>
                <strong><i class="fas fa-mobile-alt"></i> Mobile Number:</strong> {{ $pendingBooking->mobileNum }}<br>
                <strong><i class="fas fa-map-marker-alt"></i> Address:</strong> {{ $pendingBooking->pickUp_Address }}<br>
                <strong><i class="far fa-calendar"></i> Pick up Date:</strong> {{ \Carbon\Carbon::parse($pendingBooking->startDate)->format('F j, Y') }}<br>
                <strong><i class="far fa-clock"></i> Pickup Time:</strong> {{ \Carbon\Carbon::parse($pendingBooking->startDate)->format('g:i A') }}<br>
                <strong><i class="far fa-calendar-alt"></i> Return Date:</strong> {{ \Carbon\Carbon::parse($pendingBooking->endDate)->format('F j, Y') }}<br>
                <strong><i class="fas fa-calendar-day"></i> Number of Days:</strong> {{ \Carbon\Carbon::parse($pendingBooking->startDate)->diffInDays($pendingBooking->endDate) }} days<br>
                <strong><i class="fas fa-sticky-note"></i> Notes:</strong> {{ $pendingBooking->note }}
            </div>
            
            
            

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
        <div class="container">
            <h2>Assign Fleet</h2>
            <form method="POST" action="{{ route('booking.storeAssign') }}">
                @csrf
                <input type="hidden" name="reserveID" value="{{ $pendingBooking->reserveID }}">
                <!-- Dynamic fleet assignment rows -->
                <div id="fleet-assignment-container">
                   
                </div>
                
                <button type="button" id="add-fleet-button" class="btn btn-primary" onclick="addFleetAssignment()" disabled><i class="fa-solid fa-plus"></i>Add Fleet</button>

                <button type="submit" class="btn btn-success"><i class="fa-solid fa-check"></i>Save</button>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  

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
            <div class="col-md-4">
                <label for="unitID${assignmentCount}">Fleet</label>
                <select name="unitID[]" id="unitID${assignmentCount}" class="form-control  fleet-select fleet-dropdown" required>
                    <option value="" selected disabled>Select Unit </option>
                    @foreach($availableVehicles as $vehicle)
                        <option value="{{ $vehicle->unitID }}">{{ $vehicle->unitName }} ({{ $vehicle->vehicleType->vehicle_Type }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="empID${assignmentCount}">Driver Name</label>
                <select name="empID[]" id="empID${assignmentCount}" class="form-control driver-select driver-dropdown" required>
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
</script>

@endsection

