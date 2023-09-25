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
        <div class="container">
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
                    <!-- You can use JavaScript to add/remove rows dynamically -->
                </div>
                
                <button type="button" class="btn btn-primary" onclick="addFleetAssignment()"><i class="fa-solid fa-plus"></i>Add Assignment</button>

                <button type="submit" class="btn btn-success"><i class="fa-solid fa-check"></i> Assign Fleet</button>
                            
                
            </form>
        </div>
    </div>
</div>


 


<script>
    let assignmentCount = 1; // Initialize the assignment count

    function addFleetAssignment() {
        const assignmentContainer = document.getElementById('fleet-assignment-container');
        
        // Create a new fleet assignment row
        const newRow = document.createElement('div');
        newRow.className = 'row mb-3';
        newRow.innerHTML = `
            <div class="col-md-4">
                <label for="unitID${assignmentCount}">Unit ID</label>
                <select name="unitID[]" id="unitID${assignmentCount}" class="form-control" required>
                    <option value="" selected disabled>Select Unit ID</option>
                    @foreach($vehicles as $vehicle)
                        <option value="{{ $vehicle->unitID }}">{{ $vehicle->unitName }} ({{ $vehicle->vehicleType->vehicle_Type}})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="empID${assignmentCount}">Employee ID</label>
                <select name="empID[]" id="empID${assignmentCount}" class="form-control" required>
                    <option value="" selected disabled>Select Employee ID</option>
                    @foreach($employees as $employee)
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
    }

    function removeFleetAssignment(button) {
        const assignmentContainer = document.getElementById('fleet-assignment-container');
        assignmentContainer.removeChild(button.parentNode.parentNode); // Remove the row
    }
</script>

@endsection

@section('scripts')
@endsection
