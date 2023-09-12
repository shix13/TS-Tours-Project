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
            <a href="{{ route('vehicles.create') }}" class="btn btn-danger">ADD NEW VEHICLE</a>
        </div>
        <div class="col-md-10">
            <div class="form-row" style="background-color: hsla(0, 0%, 100%, 0.7); padding: 10px; margin-right:0px; border-radius: 5px; margin-bottom: 20px;">
                <div class="form-group col-md-8">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search by Unit, Registration Number, etc.">
                </div>
                <div class="form-group col-md-4">
                    <select id="availability" class="form-control">
                        <option value="All">All</option>
                        <option value="Available">Available</option>
                        <option value="Booked">Booked</option>
                        <option value="Maintenance">Maintenance</option>
                    </select>
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
            <h4 class="card-title">Vehicles</h4>
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
                                            <div class="col-md-4 text-center">
                                                <div style="width: 200px; height: 150px; overflow: hidden; margin: 0 auto;">
                                                    <img src="{{ asset('storage/' . $vehicle->pic) }}" alt="Vehicle Image" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                                <h5><strong>{{ $vehicle->unitName }}</strong></h5>
                                            </div>
                                            
                                            <div class="col-md-8">
                                                <p><strong>Registration Number:</strong> {{ $vehicle->registrationNumber }}</p>
                                                <p><strong>Pax:</strong> {{ $vehicle->pax }}</p>
                                                <p></p>
                                                <p>
                                                    <strong>Specifications:</strong> {{ $vehicle->specification ?? 'None' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="status col-md-0 align-middle text-center">
                                        <span style="color: {{ $vehicle->status === 'Available' ? 'green' : 'red' }}"><strong>{{ $vehicle->status }}</strong></span>
                                    </td>
                                    <td class="status col-md-3 align-middle text-center">
                                        <a href="{{ route('vehicles.edit', $vehicle->unitID) }}" class="btn btn-primary  col-4">EDIT</a>
                                        
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal{{ $vehicle->unitID }}">DELETE</button>
                                        @include('employees.vehiclemodal') 
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
                
            </div>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        // Function to filter the table rows based on availability and search query
        function filterAndSearchTable(availability, query) {
            var table = $('#table');
            table.find('tbody tr.vehicle-row').each(function () {
                var row = $(this);
                var rowStatus = row.data('status'); // Get the status from data-status attribute
                var found = false;

                // Condition 1: query is null and availability is 'All'
                if (query === '' && availability === 'All') {
                    found = true;
                } 
                // Condition 2: query has a value and availability is 'All'
                else if (query !== '' && availability === 'All') {
                    if (row.text().toLowerCase().includes(query.toLowerCase())) {
                        found = true;
                    }
                } 
                // Condition 3: query has a value and availability is not 'All'
                else if (query !== '' && availability !== 'All') {
                    if (row.text().toLowerCase().includes(query.toLowerCase()) && rowStatus === availability) {
                        found = true;
                    }
                } 
                // Condition 4: query is null and availability is not 'All'
                else {
                    if (rowStatus === availability) {
                        found = true;
                    }
                }

                if (found) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }

        // Initial filter when the page loads
        filterAndSearchTable('All', '');

        // Handle filter button change and search input keyup
        $('#availability').on('change', function () {
            var availability = $('#availability').val();
            var query = $('#searchInput').val();
            filterAndSearchTable(availability, query);
        });

        $('#searchInput').on('input', function () {
            var availability = $('#availability').val();
            var query = $('#searchInput').val();
            filterAndSearchTable(availability, query);
        });
    });
</script>
@endsection
