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
            <a href="{{ route('maintenance.create') }}" class="btn btn-danger">Schedule Maintenance</a>
        </div>
        <div class="col-md-8">
            <div class="form-row" style="background-color: hsla(0, 0%, 100%, 0.7); padding: 10px;margin-right:-180px; border-radius: 5px; margin-bottom: 20px;">
                <div class="form-group col-md-8">
                    <input type="text" id="search" class="form-control" placeholder="Search by Fleet, Mechanic, Notes, Scheduled By">
                </div>
                <div class="col-md-4">
                    <select id="statusFilter" class="form-control">
                        <option value="">All</option>
                        <option value="Scheduled">Scheduled</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Cancelled">Cancelled</option>
                        <option value="Completed">Completed</option>
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
            <h4 class="card-title">Scheduled and In Progress Maintenance</h4>
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
                        <th class="bold-text">
                            <strong>Action</strong>
                        </th>
                    </thead>
                    <tbody>
                        @php
                        $counter = 1; // Initialize a counter variable
                        @endphp

                        @if ($maintenances !== null && $maintenances->count() > 0)
                        @foreach ($maintenances as $maintenance)
                        @if ($maintenance->status === 'Scheduled' || $maintenance->status === 'In Progress')
                        <tr>
                            <td>{{ $counter++ }}</td>
                            <td class="text-center">
                                {{-- Display vehicle image here --}}
                                <img src="{{ asset('storage/' . $maintenance->vehicle->pic) }}"
                                    alt="Vehicle Image"
                                    style="max-width: 150px; max-height: 150px;"> <br>
                                <strong>{{$maintenance->vehicle->unitName}} - {{$maintenance->vehicle->registrationNumber}}</strong>
                            </td>
                            <td class="text-center"> {{ $maintenance->mechanic_firstName }} {{ $maintenance->mechanic_lastName }}</td>
                            <td class="text-center">{{ date('M d, Y', strtotime($maintenance->scheduleDate)) }}</td>
                            <td class="{{ $maintenance->notes === null ? 'text-center' : '' }}">
                                @if ($maintenance->notes === null)
                                    <strong>--No Notes--</strong>
                                @else
                                    {!! $maintenance->notes !!}
                                @endif
                            </td>   
                            </td>
                            <td class="status col-md-0 align-middle">
                                <span
                                    style="color: {{ $maintenance->status === 'Scheduled' ? 'blue' : ($maintenance->status === 'In Progress' ? 'orange' : ($maintenance->status === 'Cancelled' ? 'red' : ($maintenance->status === 'Completed' ? 'green' : ''))) }}">
                                    <strong>{{ $maintenance->status }}</strong>
                                </span>
                            </td>
                            <td class="text-center">{{ $maintenance->endDate ? $maintenance->endDate : 'Not Completed' }}</td>
                            <td class="text-center"> {{ $maintenance->scheduled_by_firstName }} {{ $maintenance->scheduled_by_lastName }}</td>
                            <td>
                                <div class="text-center">
                                    <div class="btn-group">
                                        @if($maintenance->status !== 'Completed' && $maintenance->vehicle->status !== 'Booked')
                                        <button type="button" class="btn btn-primary dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Update Status
                                        </button>
                                        <div class="dropdown-menu">
                                            <form
                                                action="{{ route('maintenance.update', ['id' => $maintenance->maintID]) }}"
                                                method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                    <select class="form-control" name="status"
                                                        onclick="event.stopPropagation();">
                                                        <option value="In Progress"
                                                            @if($maintenance->status === 'In Progress') selected
                                                            @endif>In Progress</option>
                                                        <option value="Cancelled"
                                                            @if($maintenance->status === 'Cancelled') selected
                                                            @endif>Cancelled</option>
                                                        <option value="Completed"
                                                            @if($maintenance->status === 'Completed') selected
                                                            @endif>Completed</option>
                                                    </select>
                                                </div>
                                                <button type="submit"
                                                    class="btn btn-primary btn-block">Save</button>
                                            </form>
                                        </div>
                                        @else
                                        <button type="button" class="btn btn-primary" disabled>Vehicle is still
                                            booked</button>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7">No maintenance items available.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Table for Completed and Cancelled Maintenance Records -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Completed and Cancelled Maintenance</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="completedCancelledTable" class="table table-hover table-striped">
                    <thead class="text-primary font-montserrat">
                        <th class="bold-text">
                            <strong>#</strong>
                        </th>
                        <th class="bold-text col-md-2 ">
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
                        @if ($maintenance->status === 'Completed' || $maintenance->status === 'Cancelled')
                        <tr>
                            <td>{{ $counter++ }}</td>
                            <td class="text-center">
                                {{-- Display vehicle image here --}}
                                <img src="{{ asset('storage/' . $maintenance->vehicle->pic) }}"
                                    alt="Vehicle Image"
                                    style="max-width: 150px; max-height: 150px;"> <br>
                                <strong>{{$maintenance->vehicle->unitName}} - {{$maintenance->vehicle->registrationNumber}}</strong>
                            </td>
                            <td class="text-center"> {{ $maintenance->mechanic_firstName }} {{ $maintenance->mechanic_lastName }}</td>
                            <td class="text-center">{{ date('M d, Y', strtotime($maintenance->scheduleDate)) }}</td>
                            <td class="{{ $maintenance->notes === null ? 'text-center' : '' }}">
                                @if ($maintenance->notes === null)
                                    <strong>--No Notes--</strong>
                                @else
                                    {!! $maintenance->notes !!}
                                @endif
                            </td>                                            
                            <td class="status col-md-0 align-middle">
                                <span
                                    style="color: {{ $maintenance->status === 'Scheduled' ? 'blue' : ($maintenance->status === 'In Progress' ? 'orange' : ($maintenance->status === 'Cancelled' ? 'red' : ($maintenance->status === 'Completed' ? 'green' : ''))) }}">
                                    <strong>{{ $maintenance->status }}</strong>
                                </span>
                            </td>
                            <td class="text-center">{{ $maintenance->endDate ? $maintenance->endDate : 'Not Completed' }}</td>
                            <td class="text-center"> {{ $maintenance->scheduled_by_firstName }} {{ $maintenance->scheduled_by_lastName }}</td>
                            
                        </tr>
                        @endif
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7">No completed or cancelled maintenance items available.</td>
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
        // Function to filter the table rows based on status and search query
        function filterAndSearchTable(tableId, status, query) {
            var table = $('#' + tableId);
            table.find('tbody tr').each(function () {
                var row = $(this);
                var rowStatusCell = row.find('td:eq(5)'); // Update the column index for Status
                var rowStatus = rowStatusCell.text().trim();
                var found = false;

                // Condition 1: Status is null and query is null
                if (status === '' && query === '') {
                    found = true;
                } 
                // Condition 2: Status is null and there is a query
                else if (status === '' && query !== '') {
                    if (row.text().toLowerCase().includes(query.toLowerCase())) {
                        found = true;
                    }
                } 
                // Condition 3: Status has a value and query is null
                else if (status !== '' && query === '') {
                    if (rowStatus === status) {
                        found = true;
                    }
                } 
                // Default condition: Both status and query have values
                else {
                    if ((status === '' || rowStatus === status) && (query === '' || row.text().toLowerCase().includes(query.toLowerCase()))) {
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
        filterAndSearchTable('scheduledInProgressTable', '', '');
        filterAndSearchTable('completedCancelledTable', '', '');

        // Handle filter button change and search input keyup/change
        $('#statusFilter, #search').on('input', function () {
            var status = $('#statusFilter').val();
            var query = $('#search').val();

            // Check the conditions and show/hide tables and rows accordingly
            if (status === '' && query === '') {
                $('#scheduledInProgressTable').show();
                $('#completedCancelledTable').show();
                $('#scheduledInProgressTable tbody tr').show();
                $('#completedCancelledTable tbody tr').show();
            } else if (status === '' && query !== '') {
                $('#scheduledInProgressTable').show();
                $('#completedCancelledTable').show();
                filterAndSearchTable('scheduledInProgressTable', status, query);
                filterAndSearchTable('completedCancelledTable', status, query);
            } else if (status !== '' && query === '') {
                $('#scheduledInProgressTable').hide();
                $('#completedCancelledTable').hide();
                filterAndSearchTable('scheduledInProgressTable', status, query);
                filterAndSearchTable('completedCancelledTable', status, query);
                $('#' + (status === 'Completed' || status === 'Cancelled' ? 'completedCancelledTable' : 'scheduledInProgressTable')).show();
                $('#completedCancelledTable, #scheduledInProgressTable').not('#' + (status === 'Completed' || status === 'Cancelled' ? 'completedCancelledTable' : 'scheduledInProgressTable')).hide();
            } else {
                filterAndSearchTable('scheduledInProgressTable', status, query);
                filterAndSearchTable('completedCancelledTable', status, query);
                $('#scheduledInProgressTable, #completedCancelledTable').hide();
                $('#' + (status === 'Completed' || status === 'Cancelled' ? 'completedCancelledTable' : 'scheduledInProgressTable')).show();
                $('#completedCancelledTable, #scheduledInProgressTable').not('#' + (status === 'Completed' || status === 'Cancelled' ? 'completedCancelledTable' : 'scheduledInProgressTable')).hide();
            }
        });
    });
</script>


@endsection

