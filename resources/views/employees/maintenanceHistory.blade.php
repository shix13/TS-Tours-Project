@extends('layouts.empbar')

@section('title')
    TS | Maintenace
@endsection

@section('content')
<br>
<br>

<div class="container">
    <div class="row">
        <div class="col-md-2">
            @if(request()->route()->getName() == 'maintenance.history')
                <a href="{{ route('employee.maintenance') }}" class="btn btn-danger">View Active Maintenance</a>
            @else
                <a href="{{ route('maintenance.history') }}" class="btn btn-danger">Maintenance History</a>
            @endif
        </div>
        
        <div class="col-md-2">
            <a href="{{ route('maintenance.create') }}" class="btn btn-success">Schedule Maintenance</a>
        </div>
        
        <div class="col-md-6">
            <div class="form-row" style="background-color: hsla(0, 0%, 100%, 0.7); padding: 10px;margin-right:-180px; border-radius: 5px; margin-bottom: 20px;">
                <div class="form-group col-md-8">
                    <input type="text" id="search" class="form-control" placeholder="Search by Fleet, Mechanic, Notes, Scheduled By">
                </div>
                <div class="col-md-4">
                    <select id="statusFilter" class="form-control">
                        <option value="">All</option>
                        <option value="Scheduled">Scheduled</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
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

    <!-- Table for Scheduled and In Progress Maintenance Records -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Maintenance Schedule</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="scheduledInProgressTable" class="table table-hover table-striped">
                    <thead class="text-primary font-montserrat">
                        <th class="bold-text">
                            <strong>#</strong>
                        </th>
                        <th class="bold-text col-md-2">
                            <strong>Fleet</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Mechanic</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Scheduled Date</strong>
                        </th>
                        <th class="bold-text col-md-3">
                            <strong>Notes</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Status</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Finished Date</strong>
                        </th>
                        <th class="bold-text">
                            <strong>Scheduled By</strong>
                        </th>
                    </thead>
                    <tbody>
                        @php
                        $counter = 1; // Initialize a counter variable
                        @endphp

                        @if ($maintenances !== null && $maintenances->count() > 0)
                        @foreach ($maintenances as $maintenance)
                        
                        <tr>
                            <td>{{ $counter++ }}</td>
                            <td class="text-center">
                                <strong>{{$maintenance->vehicle->unitName}} - {{$maintenance->vehicle->registrationNumber}}</strong>
                            </td>
                            <td class="text-center"> {{ $maintenance->mechanic_firstName }} {{ $maintenance->mechanic_lastName }}</td>
                            <td class="text-center">{{ date('M d, Y h:i A', strtotime($maintenance->scheduleDate)) }}</td>
                            <td class="{{ $maintenance->notes === null ? 'text-center' : '' }}">
                                @if ($maintenance->notes === null)
                                    <strong>--No Notes--</strong>
                                @else
                                    {!! $maintenance->notes !!}
                                @endif
                            </td>   
                            </td>
                            <td class="status col-md-0 align-middle text-center">
                                <span
                                    style="color: {{ $maintenance->status === 'Scheduled' ? 'blue' : ($maintenance->status === 'In Progress' ? 'orange' : ($maintenance->status === 'Cancelled' ? 'red' : ($maintenance->status === 'Completed' ? 'green' : ''))) }}">
                                    <strong>{{ $maintenance->status }}</strong>
                                </span>
                            </td>
                            <td class="text-center">
                                {{ $maintenance->endDate ? date('M d, Y h:i A', strtotime($maintenance->endDate)) : 'Not Completed' }}
                            </td>                            
                            <td class="text-center"> {{ $maintenance->scheduled_by_firstName }} {{ $maintenance->scheduled_by_lastName }}</td>
                        
                        </tr>
                        
                        @endforeach
                        @else
                        <tr>
                            <td colspan="12">No maintenance items available.</td>
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
<script src="https://cdn.jsdelivr.net/momentjs/2.29.1/moment.min.js"></script> <!-- Include moment.js from a CDN -->

<script>
    $(document).ready(function () {
        // Function to filter the table rows based on status and search query
        function filterAndSearchTable() {
            var status = $('#statusFilter').val();
            var query = $('#search').val().toLowerCase();

            $('#scheduledInProgressTable tbody tr').each(function () {
                var row = $(this);
                var rowStatus = row.find('td:eq(5)').text().toLowerCase();
                var fleetInfo = row.find('td:eq(1)').text().toLowerCase();
                var mechanicInfo = row.find('td:eq(2)').text().toLowerCase();
                var notes = row.find('td:eq(4)').text().toLowerCase();
                var scheduledDate = row.find('td:eq(3)').text().toLowerCase(); // Scheduled Date column in lowercase for case-insensitive comparison
                var scheduledEnd = row.find('td:eq(6)').text().toLowerCase();
                var scheduledBy = row.find('td:eq(7)').text().toLowerCase();
                var found = false;

                if ((status === '' || rowStatus.includes(status.toLowerCase())) &&
                    (query === '' ||
                        fleetInfo.includes(query) ||
                        mechanicInfo.includes(query) ||
                        notes.includes(query) ||
                        scheduledDate.includes(query) || // Search in scheduled date
                        scheduledEnd.includes(query) ||
                        scheduledBy.includes(query))) {
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
        filterAndSearchTable();

        // Handle filter button change and search input keyup/change
        $('#statusFilter, #search').on('input', filterAndSearchTable);
    });
</script>

@endsection


