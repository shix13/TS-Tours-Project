@extends('layouts.empbar')

@section('title')
    TS | Vehicles
@endsection

@section('content')
<br>
<br>
<div class="container">

    <div class="row">
        <div class="col-md-2">
            <a href="{{ Route::currentRouteName() == 'vehicles.vehicleRetired' ? route('employee.vehicle') : route('vehicles.vehicleRetired') }}" 
               class="{{ Route::currentRouteName() == 'vehicles.vehicleRetired' ? 'btn btn-info' : 'btn btn-danger' }}">
                @if(Route::currentRouteName() == 'vehicles.vehicleRetired')
                    <i class="fas fa-eye"></i> View Active Vehicles
                @else
                    <i class="fas fa-eye-slash"></i> View Inactive Vehicles
                @endif
            </a>
        </div>
        
        
        <div class="col-md-2">
            <a href="{{ route('vehicles.create') }}" class="btn btn-success" style="padding: 18px 23px"><i class="fas fa-plus"></i> Add Vehicle</a>
        </div>
        <div class="col-md-8">
            <div class="form-row" style="background-color: hsla(0, 0%, 100%, 0.7); padding: 10px; margin-right:0px; border-radius: 5px; margin-bottom: 20px;">
                <div class="form-group col-md-12">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by Unit, Pax, Color etc.">
                </div>
            </div>
        </div>
    </div>
    
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
    
    <div class="card">
        <div class="card-header">
            <h4 class="card-title" style="font-weight: 700">Vehicles</h4>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table id="table" class="table table-hover table-striped">
                    <thead class="text-primary font-montserrat">
                        <th class="bold-text">
                            <strong>#</strong>
                        </th>
                        <th class="bold-text" style="text-align: left">
                            <strong>Photo & Information</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Schedule Dates</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Status</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Actions</strong>
                        </th>
                    </thead>
                    <tbody>
                        @php
                        $counter = 1; // Initialize a counter variable
                    @endphp
                        @if ($vehicles !== null && $vehicles->count() > 0)
                            @foreach ($vehicles as $vehicle)
                                <tr class="vehicle-row" data-status="{{ $vehicle->status }}">
                                    <td>{{ $counter++ }}</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-5 text-center">
                                                <div style="width: 200px; height: 150px; overflow: hidden; margin: 0 auto;">
                                                    <img src="{{ asset('storage/' . $vehicle->pic) }}" alt="Vehicle Image" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                                <h5><strong>{{ $vehicle->unitName }} ({{ $vehicle->vehicleType->vehicle_Type }})</strong></h5>
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <p><strong>License Plate Number:</strong> {{ $vehicle->registrationNumber }}</p>
                                                <p><strong>Pax:</strong> {{ $vehicle->pax }}</p>
                                                    <p><strong>Year Model:</strong> {{ $vehicle->yearModel }}</p>
                                                    <p><strong>Color:</strong> {{ $vehicle->color }}</p>
                                                    <p><strong>Ownership Type:</strong> {{ $vehicle->ownership_type ?? 'N/A' }}</p>
                                                    <p><strong>Outsourced From:</strong> {{ $vehicle->outsourced_from ?? 'N/A' }}</p>
                                                <p>
                                                    <strong>Specifications:</strong> {{ $vehicle->specification ?? 'None' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="status col-md-3 align-middle text-center">
                                        <p><strong>Maintenance Date:</strong></p>
                                        @php
                                            // Filter maintenance records for the current week
                                            $maintenanceDates = $vehicle->maintenances->filter(function($maintenance) {
                                                return in_array($maintenance->status, ['Scheduled', 'In Progress']) &&
                                                       $maintenance->status !== 'Cancelled' && // Exclude maintenance records with status 'Cancelled'
                                                       \Carbon\Carbon::parse($maintenance->scheduleDate)->isCurrentWeek();
                                            });
                                        @endphp
                                    
                                        @if($maintenanceDates->isNotEmpty())
                                            @foreach($maintenanceDates as $maintenance)
                                                <p><i class="fa-solid fa-screwdriver-wrench"></i> <strong>{{ \Carbon\Carbon::parse($maintenance->scheduleDate)->isToday() ? 'Today: ' : '' }}</strong>{{ \Carbon\Carbon::parse($maintenance->scheduleDate)->toFormattedDateString() }} ({{ \Carbon\Carbon::parse($maintenance->scheduleDate)->diffForHumans() }})</p>
                                            @endforeach
                                        @else
                                            <p>No maintenance schedule within the week.</p>
                                        @endif
                                    
                                        <hr>
                                    
                                        <p><strong>Booking Dates:</strong></p>
                                        @php
                                            // Filter vehicle assignments for the current week and where booking status is not 'Denied'
                                            $bookingDates = $vehicle->vehicleAssignments->filter(function ($assignment) {
                                            return $assignment->booking &&
                                            $assignment->booking->status !== 'Denied' &&
                                            $assignment->booking->status !== 'Cancelled' &&
                                            (!$assignment->rent || !($assignment->rent->rent_Period_Status === 'Completed') && !($assignment->rent->rent_Period_Status === 'Cancelled')) &&
                                            (\Carbon\Carbon::parse($assignment->booking->startDate)->isCurrentWeek() || \Carbon\Carbon::parse($assignment->booking->endDate)->isCurrentWeek());
                                        });

                                        @endphp
                                    
                                        @if($bookingDates->isNotEmpty())
                                            @foreach($bookingDates as $assignment)
                                                <p><strong><i class="fas fa-calendar"></i>
                                                    {{ \Carbon\Carbon::parse($assignment->booking->startDate)->isToday() ? 'Today: ' : '' }}</strong>{{ \Carbon\Carbon::parse($assignment->booking->startDate)->toFormattedDateString() }}  - {{ \Carbon\Carbon::parse($assignment->booking->endDate)->toFormattedDateString() }} <br>    ID: {{ ($assignment->booking->reserveID)}} ({{ \Carbon\Carbon::parse($assignment->booking->startDate)->diffForHumans() }})</p>
                                            @endforeach
                                        @else
                                            <p>No booking schedule within the week.</p>
                                        @endif
                                    </td>
                                    
                                    
                                    
                                    
                                    
                                    <td class="status col-md-0 align-middle text-center">
                                        <span style="color: {{ $vehicle->status === 'Active' ? 'green' : 'red' }}"><strong>{{ $vehicle->status }}</strong></span>
                                    </td>
                                    <td class="status col-md-1 align-middle text-center">
                                        <a href="{{ route('vehicles.edit', $vehicle->unitID) }}" class="btn btn-primary"> <i class="fas fa-edit"></i>EDIT</a>
                                        
                                    </td>
                                </tr>
                            @endforeach
                        @elseif ($vehicles === null)
                            <tr>
                                <td colspan="3">The variable $vehicles is null.</td>
                            </tr>
                        @else
                            <tr>
                                <td colspan="12">No vehicles available.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                {{ $vehicles->links() }}
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        // Function to search the table rows based on the query
        function searchTable(query) {
            var table = $('#table');
            table.find('tbody tr.vehicle-row').each(function () {
                var row = $(this);
                var found = false;

                // Condition: query has a value
                if (query !== '') {
                    if (row.text().toLowerCase().includes(query.toLowerCase())) {
                        found = true;
                    }
                } 
                // Condition: query is null (show all rows)
                else {
                    found = true;
                }

                if (found) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }

        // Initial search when the page loads
        searchTable('');

        $('#searchInput').on('input', function () {
            var query = $('#searchInput').val();
            searchTable(query);
        });
    });
</script>
@endsection

