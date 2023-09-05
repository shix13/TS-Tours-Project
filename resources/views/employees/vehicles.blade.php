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
                    <select id="availabilityFilter" class="form-control">
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


    
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Vehicles</h4>
        </div>
        
        <div class="card-body">
            <div class="table-responsive">
                <table id="vehicleTable" class="table table-hover table-striped">
                    <thead class="text-primary font-montserrat">
                        <th class="bold-text">
                            <strong>#</strong>
                        </th>
                        <th class="bold-text">
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
                                <tr>
                                    <td>{{ $counter++ }}</td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <img src="{{ asset('storage/' . $vehicle->pic) }}" alt="Vehicle Image" class="img-fluid">
                                            </div>
                                            <div class="col-md-8">
                                                <h5><strong>{{ $vehicle->unitName }}</strong></h5>
                                                <p><strong>Registration Number:</strong> {{ $vehicle->registrationNumber }}</p>
                                                <p><strong>Pax:</strong> {{ $vehicle->pax }}</p>
                                                <p></p>
                                                <p>
                                                    <strong>Specifications:</strong> {{ $vehicle->specification }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="status col-md-0 align-middle">
                                        <span style="color: {{ $vehicle->status === 'Available' ? 'green' : 'red' }}"><strong>{{ $vehicle->status }}</strong></span>
                                    </td>
                                    <td class="status col-md-3 align-middle">
                                        <a href="{{ route('vehicles.edit', $vehicle->unitID) }}" class="btn btn-primary">EDIT</a>
                                        
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
                                <td colspan="3">No vehicles available.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                
            </div>
        </div>
    </div>

    <div class="card card-plain">
        <div class="card-header">
            <h4 class="card-title">Table on Plain Background</h4>
            <p class="category">Here is a subtitle for this table</p>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <!-- Second table structure here, if needed -->
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
            var table = $('#vehicleTable');
            table.find('tbody tr').each(function () {
                var row = $(this);
                var statusCell = row.find('.status');
                var status = statusCell.text().trim();
                var found = false;
                
                // Check both availability and search query conditions
                if ((status === availability || availability === 'All') && (query === '' || row.text().toLowerCase().includes(query.toLowerCase()))) {
                    found = true;
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
    
        // Handle filter button click and search input keyup
        $('#availabilityFilter, #searchInput').on('input', function () {
            var availability = $('#availabilityFilter').val();
            var query = $('#searchInput').val();
            filterAndSearchTable(availability, query);
        });
    });
    </script>
    
    
@endsection
